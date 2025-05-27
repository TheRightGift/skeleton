<?php
namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\BankDetail;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Paystack;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WalletController extends Controller
{
    protected $paystack;

    public function __construct()
    {
        $this->paystack = new Paystack();
    }

    public function generateTippingQrCode(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $wallet = $user->wallet;
        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $qrCode = QrCode::size(200)->format('png')->generate($wallet->tipping_url);
        $qrCodeBase64 = base64_encode($qrCode);

        return response()->json([
            'message' => 'QR code generated',
            'tipping_url' => $wallet->tipping_url,
            'qr_code' => 'data:image/png;base64,' . $qrCodeBase64,
        ]);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'account_number' => 'required|string',
            'bank_code' => 'required|string',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $wallet = $user->wallet;
        $total = $request->amount + 300;

        if ($total > $wallet->balance) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        BankDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bank_name' => $request->bank_name ?? 'Unknown',
                'account_number' => $request->account_number,
                'account_name' => $request->account_name ?? 'Unknown',
                'bank_code' => $request->bank_code,
            ]
        );

        try {
            $transfer = $this->paystack->transfer([
                'amount' => $request->amount * 100,
                'recipient' => $this->createRecipient($request->account_number, $request->bank_code),
                'reason' => 'Wallet Withdrawal',
            ]);

            \App\Models\Transaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $total,
                'type' => 'withdrawal',
                'status' => 'pending',
                'qr_code_key' => \Illuminate\Support\Str::random(32),
            ]);

            $wallet->balance -= $total;
            $wallet->save();

            return response()->json(['message' => 'Withdrawal initiated']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Withdrawal failed: ' . $e->getMessage()], 400);
        }
    }

    public function getBanks()
    {
        try {
            $banks = $this->paystack->getAllBanks();
            return response()->json(['banks' => $banks['data']]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch banks'], 400);
        }
    }

    protected function createRecipient($account_number, $bank_code)
    {
        $recipient = $this->paystack->createTransferRecipient([
            'type' => 'nuban',
            'account_number' => $account_number,
            'bank_code' => $bank_code,
            'currency' => 'NGN',
        ]);
        return $recipient['data']['recipient_code'];
    }
}