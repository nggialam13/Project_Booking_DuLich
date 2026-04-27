<?php

use Illuminate\Support\Facades\Route;
// hạnh
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});


// Chưa đăng nhập mới truy cập được
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
// Đăng nhập
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
// điều hướng trang profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('changePassword');
});
// Admin Tours List
require __DIR__ . '/tour.php';

// booking routes
require __DIR__.'/booking.php';

//payment
require __DIR__.'/payment.php';
require __DIR__.'/booking.php';
require __DIR__.'/tour.php';

Route::get('/', function () {
    return view('welcome');
});