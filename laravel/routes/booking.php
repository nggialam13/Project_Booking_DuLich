<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booking\BookingController;

Route::get('/bookings/create/{tour}', [BookingController::class, 'create'])
    ->name('bookings.create');
    

Route::post('/bookings/store', [BookingController::class, 'store'])
    ->name('bookings.store');