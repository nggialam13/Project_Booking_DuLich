<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// tour routes
require __DIR__ . '/tour.php';

// booking routes
require __DIR__.'/booking.php';