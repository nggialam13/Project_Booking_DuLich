<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booking\BookingController;



Route::post('/bookings', [BookingController::class, 'store'])
    ->name('bookings.store');
  

Route::get('/bookings/create/{tour}', [BookingController::class, 'create'])
    ->name('bookings.create');


    // Đặt tour (POST)  
    Route::middleware('auth')->group(function () {
    Route::get('/bookings/create/{tour}', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');
});