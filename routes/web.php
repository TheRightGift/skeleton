<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::get('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
Route::get('/t/{key}', [TipController::class, 'showTippingPage'])->name('tipping.page');