<?php

use Illuminate\Support\Facades\Route;
// hạnh
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Guest routes (chưa đăng nhập)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Auth routes (đã đăng nhập)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('changePassword');
});

// Route tạm cho tours.index
Route::middleware('auth')->get('/tours', function () {
    return view('tours.index');
})->name('tours.index');

// Route tạm cho admin.dashboard
Route::middleware(['auth'])->get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Các route quản lý user
    Route::get('/users', [AuthController::class, 'listUsers'])->name('admin.users');
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser'])->name('admin.deleteUser');
    // Các route khác
});

// Admin Tours List
require __DIR__ . '/tour.php';

// booking routes
require __DIR__ . '/booking.php';

//payment
require __DIR__ . '/payment.php';