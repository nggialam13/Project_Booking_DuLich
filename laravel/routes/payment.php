<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\PaymentController;

Route::middleware('auth')->group(function () {
    Route::get('/payment/{booking_id}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');

    Route::get('/payment/momo/{id}', [PaymentController::class, 'momo'])->name('payment.momo');
    Route::get('/payment/vnpay/{id}', [PaymentController::class, 'vnpay'])->name('payment.vnpay');

    Route::get('/payment/success/{id}', [PaymentController::class, 'success'])->name('payment.success');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payment.show');
});