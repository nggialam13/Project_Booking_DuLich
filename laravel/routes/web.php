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

// Admin Tours List
require __DIR__ . '/tour.php';

// booking routes
require __DIR__.'/booking.php';

