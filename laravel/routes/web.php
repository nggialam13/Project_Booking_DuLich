<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
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
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password.form');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('change-password.update');
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

Route::get('/test-alert', function () {
    return redirect('/tours')->with('success', 'Thành công!');
});
