<?php

use Illuminate\Support\Facades\Route;
// hạnh
use App\Http\Controllers\Auth\AuthController;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Payment;

Route::get('/', function () {
    return view('welcome');
});

//home
Route::get('/home', function () {
    return view('home');
});

// Chưa đăng nhập mới truy cập được
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
// Đăng nhập
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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


Route::get('/tours', function () {
    $tours = \App\Models\Tour::paginate(6);
    return view('tours.index', compact('tours'));
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