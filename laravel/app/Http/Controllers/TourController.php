<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $this->syncExpiredTours();

        $title = $request->input('title');
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $daysMin = $request->input('days_min');
        $daysMax = $request->input('days_max');

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

        $this->syncTourStatus($tour);

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
            'image' => 'nullable|image|mimes:png,jpg|max:2048',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $validated['duration'] = abs($endDate->diffInDays($startDate)) + 1;

        $booked = $tour->slots - $tour->available_slots;
        $validated['available_slots'] = $validated['slots'] - $booked;

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $uploadedHash = md5_file($uploadedFile->getRealPath());
            $existingImage = $this->findImageByHash($uploadedHash);

            if ($existingImage) {
                $validated['image'] = $existingImage;
            } else {
                $validated['image'] = $uploadedFile->store('tours', 'public');
            }
        } else {
            unset($validated['image']);
        }

        $tour->update($validated);
        $this->syncTourStatus($tour->fresh());

        return redirect()->route('tours.index')->with('success', 'Chỉnh sửa tour thành công!');
    }

    public function userIndex()
    {
        $this->syncExpiredTours();

        $title = request('title');
        $priceMin = request('price_min');
        $priceMax = request('price_max');
        $daysMin = request('days_min');
        $daysMax = request('days_max');

        $query = Tour::where('status', 'active')->whereDate('end_date', '>=', today());

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
        $this->syncTourStatus($tour);

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
            'image' => 'nullable|image|mimes:png,jpg|max:2048',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $validated['duration'] = abs($endDate->diffInDays($startDate)) + 1;
        $validated['status'] = $endDate->lt(today()) ? 'inactive' : 'active';
        $validated['available_slots'] = $validated['slots'];

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $uploadedHash = md5_file($uploadedFile->getRealPath());
            $existingImage = $this->findImageByHash($uploadedHash);

            if ($existingImage) {
                $validated['image'] = $existingImage;
            } else {
                $validated['image'] = $uploadedFile->store('tours', 'public');
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

        if (Carbon::parse($tour->end_date)->lt(today())) {
            $tour->status = 'inactive';
        } else {
            $tour->status = $tour->status === 'active' ? 'inactive' : 'active';
        }

        $tour->save();

        return redirect()->route('tours.index')->with('success', 'Cập nhật thành công!');
    }

    private function syncExpiredTours(): void
    {
        Tour::whereDate('end_date', '<', today())
            ->where('status', '!=', 'inactive')
            ->update(['status' => 'inactive']);
    }

    private function syncTourStatus(Tour $tour): void
    {
        if (Carbon::parse($tour->end_date)->lt(today()) && $tour->status !== 'inactive') {
            $tour->status = 'inactive';
            $tour->save();
        }
    }

    private function findImageByHash(string $uploadedHash): ?string
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
