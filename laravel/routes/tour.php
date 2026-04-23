<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;

// Admin Tours Routes
Route::get('/admin/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/admin/tours/create', [TourController::class, 'create'])->name('tours.create');
Route::post('/admin/tours', [TourController::class, 'storeNewTour'])->name('tours.storeNewTour');
