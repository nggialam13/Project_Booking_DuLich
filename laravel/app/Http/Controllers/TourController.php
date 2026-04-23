<?php

namespace App\Http\Controllers;

use App\Models\Tour;

class TourController extends Controller
{
    /**
     * Display a listing of tours.
     */
    public function index()
    {
        $tours = Tour::paginate(10);
        return view('tours.index', compact('tours'));
    }
}
