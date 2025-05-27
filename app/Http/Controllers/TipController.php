<?php
namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Paystack;

class TipController extends Controller
{
    protected $paystack;

    public function __construct()
    {
        $this->paystack = new Paystack();
    }

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
            $payment = $this->paystack->charge([
                'amount' => $request->amount * 100,
                'email' => $request->email,
                'bank' => [
                    'account_number' => $request->account_number,
                    'bank_code' => $request->bank_code,
                ],
                'metadata' => [
                    'wallet_id' => $wallet->id,
                    'tipping_url' => $wallet->tipping_url,
                ],
            ]);

            if ($payment['data']['status'] === 'send_otp') {
                return response()->json([
                    'message' => 'OTP required',
                    'reference' => $payment['data']['reference'],
                ]);
            }

            return response()->json(['message' => 'Payment initiated', 'reference' => $payment['data']['reference']]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 400);
        }
    }

    public function verifyOtp(Request $request, $key)
    {
        $request->validate([
            'reference' => 'required|string',
            'otp' => 'required|string',
        ]);

        $wallet = Wallet::where('tipping_url', config('app.url') . '/t/' . $key)->first();
        if (!$wallet) {
            return response()->json(['message' => 'Invalid tipping URL'], 404);
        }

        try {
            $payment = $this->paystack->charge([
                'reference' => $request->reference,
                'otp' => $request->otp,
            ]);

            if ($payment['data']['status'] === 'success') {
                $amount = $payment['data']['amount'] / 100;
                $wallet->balance += $amount;
                $wallet->save();

                \App\Models\Transaction::create([
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'type' => 'deposit',
                    'status' => 'completed',
                    'qr_code_key' => \Illuminate\Support\Str::random(32),
                ]);

                $wallet->tipping_url = config('app.url') . '/t/' . \Illuminate\Support\Str::random(32);
                $wallet->save();

                return response()->json(['message' => 'Tip processed successfully']);
            }

            return response()->json(['message' => 'Payment verification failed'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Verification failed: ' . $e->getMessage()], 400);
        }
    }
}