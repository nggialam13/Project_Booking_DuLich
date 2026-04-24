<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;

// User Tours Routes (Public)
Route::get('/tours', [TourController::class, 'userIndex'])->name('tours.user-index');
Route::get('/tours/{id}', [TourController::class, 'show'])->name('tours.show');

// Admin Tours Routes
Route::get('/admin/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/admin/tours/create', [TourController::class, 'create'])->name('tours.create');
Route::post('/admin/tours', [TourController::class, 'storeNewTour'])->name('tours.storeNewTour');

