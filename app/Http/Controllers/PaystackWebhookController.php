<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaystackWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        if ($payload['event'] === 'transfer.success') {
            $transaction = Transaction::where('qr_code_key', $payload['data']['reference'])->first();
            if ($transaction) {
                $transaction->status = 'completed';
                $transaction->save();
            }
        }
        return response()->json(['status' => 'success']);
    }
}