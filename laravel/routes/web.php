<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Tours List
Route::get('/admin/tours', [TourController::class, 'index'])->name('tours.index');

