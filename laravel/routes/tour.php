<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;

// User Tours Routes (Public)
Route::get('/tours', [TourController::class, 'userIndex'])->name('tours.user-index');
Route::get('/tours/{id}', [TourController::class, 'show'])->name('tours.show');

// Admin Tours Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/tours', [TourController::class, 'index'])->name('tours.index');
    Route::get('/admin/tours/create', [TourController::class, 'create'])->name('tours.create');
    Route::post('/admin/tours', [TourController::class, 'store'])->name('tours.store');
    Route::get('/admin/tours/{id}/edit', [TourController::class, 'edit'])->name('tours.edit');
    Route::put('/admin/tours/{id}', [TourController::class, 'update'])->name('tours.update');
    Route::patch('/admin/tours/{id}/toggleStatus', [TourController::class, 'toggleStatus'])->name('tours.toggle-status');
    Route::delete('/admin/tours/{id}', [TourController::class, 'destroyTour'])->name('tours.destroy');
});
