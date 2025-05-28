<?php
namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;

class TipController extends Controller
{
    public function showTippingPage($key)
    {
        $wallet = Wallet::where('tipping_url', config('app.url') . '/t/' . $key)->first();

        if (!$wallet) {
            abort(404, 'Invalid tipping URL');
        }

        $user = $wallet->user;

        return view('tip', [
            'user' => $user,
            'tipping_url' => url($wallet->tipping_url),
            'key' => $key,
        ]);
    }

    public function initiateTip(Request $request, $key)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'email' => 'required|email',
        ]);

        $wallet = Wallet::where('tipping_url', config('app.url') . '/t/' . $key)->first();
        if (!$wallet) {
            return response()->json(['message' => 'Invalid tipping URL'], 404);
        }

        try {
            $paymentData = [
                'amount' => $request->amount * 100, // Convert to kobo
                'email' => $request->email,
                'type' => 'bank, ussd',
                'currency' => 'NGN',
                'callback_url' => config('app.url') . '/t/' . $key . '/callback',
                'metadata' => [
                    'wallet_id' => $wallet->id,
                    'tipping_url' => $wallet->tipping_url,
                    'tip_recipient' => $wallet->user->name,
                    'custom_fields' => [
                        [
                            'display_name' => 'Tip for',
                            'variable_name' => 'tip_recipient',
                            'value' => $wallet->user->name
                        ]
                    ]
                ]
            ];

            // Use direct API call matching the docs example
            $secretKey = config('services.paystack.secret');

            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://api.paystack.co/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $secretKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $paymentData
            ]);

            $result = json_decode($response->getBody(), true);

            if ($result && isset($result['status']) && $result['status']) {
                return response()->json([
                    'message' => 'Payment initialized',
                    'authorization_url' => $result['data']['authorization_url'],
                    'access_code' => $result['data']['access_code'],
                    'reference' => $result['data']['reference']
                ]);
            }

            return response()->json(['message' => 'Payment initialization failed'], 400);
        } catch (\Exception $e) {
            // Add logging for troubleshooting
            \Illuminate\Support\Facades\Log::error('Paystack error: ' . $e->getMessage());
            return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 400);
        }
    }

    public function verifyOtp(Request $request, $key)
    {
        $request->validate([
            'reference' => 'required|string',
        ]);

        $wallet = Wallet::where('tipping_url', config('app.url') . '/t/' . $key)->first();
        if (!$wallet) {
            return response()->json(['message' => 'Invalid tipping URL'], 404);
        }

        try {
            // Verify the payment using Paystack API
            $secretKey = config('services.paystack.secret');
            $client = new \GuzzleHttp\Client();

            $response = $client->request('GET', 'https://api.paystack.co/transaction/verify/' . $request->reference, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $secretKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            if ($result && isset($result['status']) && $result['status'] && $result['data']['status'] === 'success') {
                $amount = $result['data']['amount'] / 100; // Convert from kobo

                // Check if transaction already exists to prevent double processing
                $existingTransaction = \App\Models\Transaction::where('reference', $request->reference)->first();
                if ($existingTransaction) {
                    return response()->json(['message' => 'Transaction already processed']);
                }

                $wallet->balance += $amount;
                $wallet->save();

                // Create transaction record
                \App\Models\Transaction::create([
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'type' => 'tip',
                    'status' => 'completed',
                    'reference' => $request->reference,
                ]);

                return response()->json(['message' => 'Tip processed successfully']);
            }

            return response()->json(['message' => 'Payment verification failed'], 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment verification error: ' . $e->getMessage());
            return response()->json(['message' => 'Verification failed: ' . $e->getMessage()], 400);
        }
    }

    public function handleCallback(Request $request, $key)
    {
        $reference = $request->query('reference');
        $status = $request->query('status');

        if ($status === 'success' && $reference) {
            // Verify the payment in the background
            try {
                $verificationRequest = new Request(['reference' => $reference]);
                $this->verifyOtp($verificationRequest, $key);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Callback verification error: ' . $e->getMessage());
                return redirect('/t/' . $key . '?payment=failed');
            }

            // Redirect back to tipping page with success message
            return redirect('/t/' . $key . '?payment=success');
        }

        // Redirect back to tipping page with error message
        return redirect('/t/' . $key . '?payment=failed');
    }

    public function webhook(Request $request)
    {
        // Verify webhook signature (recommended for production)
        $signature = $request->header('x-paystack-signature');
        $webhookSecret = config('services.paystack.webhook_secret');

        if ($webhookSecret && $signature) {
            $computedSignature = hash_hmac('sha512', $request->getContent(), $webhookSecret);
            if (!hash_equals($signature, $computedSignature)) {
                \Illuminate\Support\Facades\Log::warning('Invalid webhook signature');
                return response()->json(['message' => 'Invalid signature'], 400);
            }
        }

        $event = $request->input('event');
        $data = $request->input('data');

        \Illuminate\Support\Facades\Log::info('Paystack webhook received', [
            'event' => $event,
            'reference' => $data['reference'] ?? null
        ]);

        if ($event === 'charge.success') {
            $reference = $data['reference'];
            $amount = $data['amount'] / 100; // Convert from kobo
            $metadata = $data['metadata'] ?? [];

            if (isset($metadata['wallet_id'])) {
                $wallet = Wallet::find($metadata['wallet_id']);

                if ($wallet) {
                    // Check if transaction already exists to prevent double processing
                    $existingTransaction = \App\Models\Transaction::where('reference', $reference)->first();
                    if (!$existingTransaction) {
                        try {
                            // Update wallet balance
                            $wallet->balance += $amount;
                            $wallet->save();

                            // Create transaction record
                            \App\Models\Transaction::create([
                                'wallet_id' => $wallet->id,
                                'amount' => $amount,
                                'type' => 'tip',
                                'status' => 'completed',
                                'reference' => $reference,
                            ]);

                            \Illuminate\Support\Facades\Log::info('Tip processed via webhook', [
                                'reference' => $reference,
                                'amount' => $amount,
                                'wallet_id' => $wallet->id
                            ]);
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Webhook processing error: ' . $e->getMessage());
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::info('Transaction already processed', ['reference' => $reference]);
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning('Wallet not found for webhook', ['wallet_id' => $metadata['wallet_id']]);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('No wallet_id in webhook metadata', ['metadata' => $metadata]);
            }
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}
