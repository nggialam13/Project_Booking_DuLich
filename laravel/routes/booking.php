<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booking\BookingController;

Route::get('/bookings/create/{tour}', [BookingController::class, 'create'])
    ->name('bookings.create');


Route::post('/bookings/store', [BookingController::class, 'store'])
    ->name('bookings.store');

Route::get('/bookings', [BookingController::class, 'index'])
    ->name('bookings.index');

Route::get('/bookings/{id}', [BookingController::class, 'show'])
    ->name('bookings.show');

Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
    ->name('bookings.cancel');