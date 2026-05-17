<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Tour;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('changePassword');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AuthController::class, 'listUsers'])->name('admin.users');
    Route::get('/users/{id}/edit', [AuthController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AuthController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser'])->name('admin.deleteUser');

    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments.index');
    Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('admin.payments.show');
    Route::patch('/payments/{payment}/status', [AdminController::class, 'updatePaymentStatus'])->name('admin.payments.update-status');

    Route::get('/report', [ReportController::class, 'index'])->name('admin.report');
});

require __DIR__ . '/tour.php';
require __DIR__.'/booking.php';
require __DIR__.'/payment.php';

Route::get('/tours', function () {
    $tours = Tour::paginate(6);
    return view('tours.user-tours', compact('tours'));
});

Route::get('/tours/{id}', function ($id) {
    $tour = Tour::findOrFail($id);
    return view('tours.show', compact('tour'));
});

Route::get('/test-alert', function () {
    return redirect('/tours')->with('success', 'Thành công!');
});
