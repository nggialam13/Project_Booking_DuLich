<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new tour.
     */
    public function create()
    {
        return view('tours.create');
    }

    /**
     * Store a newly created tour in database.
     */
    public function storeNewTour(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'slots' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['status'] = 'active';
        $validated['available_slots'] = 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tours', 'public');
            $validated['image'] = $path;
        }

        Tour::create($validated);

        return redirect()->route('tours.index')->with('success', 'Tour created successfully!');
    }
}
