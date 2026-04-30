<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booking\BookingController;



Route::post('/bookings', [BookingController::class, 'store'])
    ->name('bookings.store');


Route::get('/bookings/create/{tour}', [BookingController::class, 'create'])
    ->name('bookings.create');
Route::get('/bookings/{id}', [BookingController::class, 'show'])
    ->name('bookings.show');

// Đặt tour (POST)  
Route::middleware('auth')->group(function () {
    Route::get('/bookings/create/{tour}', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');
});

Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
    ->name('bookings.cancel')
    ->middleware('auth');