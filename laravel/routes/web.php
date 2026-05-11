<?php

use Illuminate\Support\Facades\Route;
// hạnh
use App\Http\Controllers\Auth\AuthController;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Payment;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;


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
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('changePassword');
    
});
// Admin - Quản lý người dùng
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [App\Http\Controllers\Auth\AuthController::class, 'listUsers'])->name('admin.users');
    Route::delete('/users/{id}', [App\Http\Controllers\Auth\AuthController::class, 'deleteUser'])->name('admin.deleteUser');
});

// Admin Tours List
require __DIR__ . '/tour.php';

// booking routes
require __DIR__.'/booking.php';

//payment
require __DIR__.'/payment.php';

Route::get('/', function () {
    return view('welcome');
});


Route::get('/tours', function () {
    $tours = \App\Models\Tour::paginate(6);
    return view('tours.user-tours', compact('tours'));
});

Route::get('/tours/{id}', function ($id) {
    $tour = Tour::findOrFail($id);
    return view('tours.show', compact('tour'));
});

Route::get('/bookings/create', function () {
    $tour = \App\Models\Tour::first();
    return view('bookings.create', compact('tour'));
});

Route::get('/payments', function () {
    $payments = Payment::paginate(5); // hoặc all()
    return view('payments.index', compact('payments'));
});
Route::get('/test-alert', function () {
    return redirect('/tours')->with('success', 'Thành công!');
});


//admin dashboard
Route::middleware(['auth'])->get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

//admin report
Route::get('/admin/report', [ReportController::class, 'index']);
Route::get('/admin/report', [ReportController::class, 'index'])->name('admin.report');