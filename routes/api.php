<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TipController;

// Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable']);
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    Route::get('/banks', [WalletController::class, 'getBanks']);
    Route::get('/wallet/qr-code', [WalletController::class, 'generateTippingQrCode']);
    Route::get('/user', function () {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'wallet' => $user->wallet,
            'transactions' => $user->wallet->transactions,
        ]);
    });
});

Route::post('/tip/{key}', [TipController::class, 'initiateTip']);
Route::post('/tip/{key}/verify', [TipController::class, 'verifyOtp']);
