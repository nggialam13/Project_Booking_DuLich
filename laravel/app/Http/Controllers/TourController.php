<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\Console;

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
     * Show the form for editing tour.
     */
    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        return view('tours.edit', compact('tour'));
    }

    /**
     * Update tour in database.
     */
    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $booked = $tour->slots - $tour->available_slots;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999999',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'slots' => 'required|integer|min:' . $booked . '|max:9999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Tính lại duration từ start_date & end_date
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $validated['duration'] = abs($endDate->diffInDays($startDate)) + 1;

        // Cập nhật available_slots khi slots thay đổi
        $booked = $tour->slots - $tour->available_slots;
        $validated['available_slots'] = $validated['slots'] - $booked;

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $uploadedHash = md5_file($uploadedFile->getRealPath());
            
            // Check if image with same hash already exists
            $existingImage = $this->findImageByHash($uploadedHash);
            
            if ($existingImage) {
                // Reuse existing image
                $validated['image'] = $existingImage;
            } else {
                // Upload new image
                $path = $uploadedFile->store('tours', 'public');
                $validated['image'] = $path;
            }
        } else {
            // Don't update image if no new file uploaded
            unset($validated['image']);
        }

        $tour->update($validated);

        return redirect()->route('tours.index')->with('success', 'Chỉnh sửa tour thành công!');
    }

    /**
     * Display tours for users (public view)
     */
    public function userIndex()
    {
        $search = request('search');
        $query = Tour::where('status', 'active');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tours = $query->paginate(12);
        return view('tours.user-tours', compact('tours'));
    }

    /**
     * Show single tour for user
     */
    public function show($id)
    {
        $tour = Tour::findOrFail($id);
        return view('tours.show', compact('tour'));
    }

    /**
     * Store a newly created tour in database.
     */
    public function storeNewTour(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999999',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'slots' => 'required|integer|min:1|max:9999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Tính lại duration từ start_date & end_date
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $validated['duration'] = abs($endDate->diffInDays($startDate)) + 1;

        $validated['status'] = 'active';
        $validated['available_slots'] = $validated['slots'];

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $uploadedHash = md5_file($uploadedFile->getRealPath());
            
            // Check if image with same hash already exists
            $existingImage = $this->findImageByHash($uploadedHash);
            
            if ($existingImage) {
                // Reuse existing image
                $validated['image'] = $existingImage;
            } else {
                // Upload new image
                $path = $uploadedFile->store('tours', 'public');
                $validated['image'] = $path;
            }
        }

        Tour::create($validated);

        return redirect()->route('tours.index')->with('success', 'Tạo tour thành công!');
    }

    public function destroyTour($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();
        return redirect()->route('tours.index')->with('success', 'Tour đã được xóa thành công!');
    }

    public function toggleStatus($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->status = $tour->status === 'active' ? 'inactive' : 'active';
        $tour->save();
        return redirect()->route('tours.index')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Find image by hash to detect duplicates
     */
    private function findImageByHash($uploadedHash)
    {
        $toursPath = storage_path('app/public/tours');
        
        if (!is_dir($toursPath)) {
            return null;
        }
        
        $files = array_diff(scandir($toursPath), ['.', '..']);
        
        foreach ($files as $file) {
            $filePath = $toursPath . '/' . $file;
            if (is_file($filePath)) {
                $fileHash = md5_file($filePath);
                if ($fileHash === $uploadedHash) {
                    return 'tours/' . $file;
                }
            }
        }
        
        return null;
    }
}
