<?php

use Illuminate\Support\Facades\Route;
// hạnh
use App\Http\Controllers\Auth\AuthController;
use App\Models\Tour;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    return view('welcome');
});

// Guest routes (chưa đăng nhập)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
// Đăng nhập
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
//profile
Route::middleware('auth')->group(function () {
    //Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    //Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('updateProfile');
    // hiện trang profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    // Hiển thị form đổi mật khẩu
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.update');
});
// Admin - Quản lý người dùng
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    //list users
    Route::get('/users', [App\Http\Controllers\Auth\AuthController::class, 'listUsers'])->name('admin.users');
    // update
    Route::get('/users/{id}/edit', [AuthController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AuthController::class, 'updateUser'])->name('admin.users.update');

    Route::delete('/users/{id}', [App\Http\Controllers\Auth\AuthController::class, 'deleteUser'])->name('admin.deleteUser');

    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments.index');
    Route::get('/payments/create', [AdminController::class, 'createPayment'])->name('admin.payments.create');
    Route::post('/payments', [AdminController::class, 'storePayment'])->name('admin.payments.store');
    Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('admin.payments.show');
    Route::patch('/payments/{payment}/status', [AdminController::class, 'updatePaymentStatus'])->name('admin.payments.update-status');
});

// Admin Tours List
require __DIR__ . '/tour.php';

// booking routes
require __DIR__ . '/booking.php';

//payment
require __DIR__ . '/payment.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bookings/create', function () {
    $tour = \App\Models\Tour::first();
    return view('bookings.create', compact('tour'));
});

Route::get('/test-alert', function () {
    return redirect('/tours')->with('success', 'Thành công!');
});


//admin dashboard
Route::middleware(['auth'])->get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

//admin report
//Route::get('/admin/report', [ReportController::class, 'index']);
Route::get('/admin/report', [ReportController::class, 'index'])->name('admin.report');
//admin chart
Route::get('/admin/chart', [ReportController::class, 'chart'])
    ->name('admin.chart');
