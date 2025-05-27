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
            'tipping_url' => $wallet->tipping_url,
            'key' => $key,
        ]);
    }

    public function initiateTip(Request $request, $key)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'account_number' => 'required|string',
            'bank_code' => 'required|string',
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
                'currency' => 'NGN',
                'channels' => ['bank', 'ussd', 'qr'],
                'metadata' => [
                    'wallet_id' => $wallet->id,
                    'tipping_url' => $wallet->tipping_url,
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
            $secretKey = env('PAYSTACK_SECRET_KEY');

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

    public function verifyPayment(Request $request, $key)
    {
        $request->validate([
            'reference' => 'required|string',
        ]);

        $wallet = Wallet::where('tipping_url', config('app.url') . '/t/' . $key)->first();
        if (!$wallet) {
            return response()->json(['message' => 'Invalid tipping URL'], 404);
        }

        try {
            // Verify the payment
            $payment = Paystack::getPaymentData();

            if ($payment['data']['status'] === 'success') {
                $amount = $payment['data']['amount'] / 100; // Convert from kobo
                $wallet->balance += $amount;
                $wallet->save();

                // Create transaction record
                \App\Models\Transaction::create([
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'type' => 'tip',
                    'status' => 'completed',
                    'qr_code_key' => $payment['data']['reference'],
                ]);

                return response()->json(['message' => 'Tip processed successfully']);
            }

            return response()->json(['message' => 'Payment verification failed'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Verification failed: ' . $e->getMessage()], 400);
        }
    }
}
