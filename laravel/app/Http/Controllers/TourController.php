<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\Console;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $title = $request->get('title');
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        $daysMin = $request->get('days_min');
        $daysMax = $request->get('days_max');

        $query = Tour::query();

        if ($title) {
            $query->where('title', 'like', "%{$title}%");
        }

        if ($priceMin !== null && $priceMin !== '' && is_numeric($priceMin)) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax !== null && $priceMax !== '' && is_numeric($priceMax)) {
            $query->where('price', '<=', $priceMax);
        }

        if ($daysMin !== null && $daysMin !== '' && is_numeric($daysMin)) {
            $query->where('duration', '>=', $daysMin);
        }

        if ($daysMax !== null && $daysMax !== '' && is_numeric($daysMax)) {
            $query->where('duration', '<=', $daysMax);
        }

        $tours = $query->orderBy('id', 'asc')->paginate(30)->withQueryString();

        return view('tours.index', compact('tours'));
    }

    public function create()
    {
        return view('tours.create');
    }

    public function edit($id)
    {
        $tour = Tour::find($id);

        if (!$tour) {
            return redirect()->route('tours.index')
                ->with('error', 'Tour đã bị xóa hoặc không còn tồn tại.');
        }

        return view('tours.edit', compact('tour'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::find($id);

        if (!$tour) {
            return redirect()->route('tours.index')
                ->with('error', 'Tour đã bị xóa hoặc không còn tồn tại.');
        }

        $booked = $tour->slots - $tour->available_slots;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999999',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'slots' => 'required|integer|min:' . $booked . '|max:9999',
            'image' => 'nullable|image|mimes:png,jpg|max:2048'
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
        $title = request('title');
        $priceMin = request('price_min');
        $priceMax = request('price_max');
        $daysMin = request('days_min');
        $daysMax = request('days_max');
        $query = Tour::where('status', 'active');

        if ($title) {
            $query->where('title', 'like', "%{$title}%");
        }

        if ($priceMin !== null && $priceMin !== '' && is_numeric($priceMin)) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax !== null && $priceMax !== '' && is_numeric($priceMax)) {
            $query->where('price', '<=', $priceMax);
        }

        if ($daysMin !== null && $daysMin !== '' && is_numeric($daysMin)) {
            $query->where('duration', '>=', $daysMin);
        }

        if ($daysMax !== null && $daysMax !== '' && is_numeric($daysMax)) {
            $query->where('duration', '<=', $daysMax);
        }

        $tours = $query->paginate(12)->withQueryString();
        return view('tours.user-tours', compact('tours'));
    }

    public function show($id)
    {
        $tour = Tour::findOrFail($id);
        return view('tours.show', compact('tour'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999999',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'slots' => 'required|integer|min:1|max:9999',
            'image' => 'nullable|image|mimes:png,jpg|max:2048'
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
        $tour = Tour::find($id);

        if (!$tour) {
            return redirect()->route('tours.index')
                ->with('error', 'Tour đã bị xóa hoặc không còn tồn tại.');
        }

        $bookedSlots = max(0, (int) $tour->slots - (int) $tour->available_slots);

        if ($bookedSlots > 0) {
            return redirect()->route('tours.index')
            ->with('error', 'Không thể xóa tour đã có người đặt. Hãy đổi trạng thái sang Tắt thay vì xóa.');
        }

        $tour->delete();
        return redirect()->route('tours.index')->with('success', 'Tour đã được xóa thành công!');
    }

    public function toggleStatus($id)
    {
        $tour = Tour::find($id);

        if (!$tour) {
            return redirect()->route('tours.index')
                ->with('error', 'Tour đã bị xóa hoặc không còn tồn tại.');
        }

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
