<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
class BookingController extends Controller
{


    public function create($tourId)
    {
        $tour = Tour::findOrFail($tourId);

        return view('bookings.create', compact('tour'));
    }
}
