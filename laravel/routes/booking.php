<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booking\BookingController;

/*
|--------------------------------------------------------------------------
| USER BOOKING
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // form đặt tour
    Route::get('/bookings/create/{tour}', [BookingController::class, 'create'])
        ->name('bookings.create');

    // tạo booking
    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');

    // xem chi tiết
    Route::get('/bookings/{id}', [BookingController::class, 'show'])
        ->name('bookings.show');

    // user cancel
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');
});


/*
|--------------------------------------------------------------------------
| ADMIN BOOKING
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // list booking
    Route::get('/bookings', [BookingController::class, 'adminIndex'])
        ->name('admin.bookings.index');

    // confirm
    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm'])
        ->name('admin.bookings.confirm');

    // admin cancel
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'adminCancel'])
        ->name('admin.bookings.cancel');
});