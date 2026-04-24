<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
    public function index()
{
    $tours = Tour::all();
    return view('tours.index', compact('tours'));
}
}
