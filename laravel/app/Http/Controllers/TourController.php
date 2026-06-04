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

    public function edit(int $id)
    {
        $tour = Tour::find($id);

        if (!$tour) {
            return redirect()->route('tours.index')
                ->with('error', 'Tour đã bị xóa hoặc không còn tồn tại.');
        }

        $this->syncTourStatus($tour);

        return view('tours.edit', compact('tour'));
    }

    public function update(Request $request, int $id)
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
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề tour.',
            'title.string' => 'Tiêu đề tour phải là chuỗi ký tự hợp lệ.',
            'title.max' => 'Tiêu đề tour không được vượt quá 255 ký tự.',
            'description.required' => 'Vui lòng nhập mô tả tour.',
            'description.string' => 'Mô tả tour phải là chuỗi ký tự hợp lệ.',
            'description.max' => 'Mô tả tour không được vượt quá 255 ký tự.',
            'price.required' => 'Vui lòng nhập giá tour.',
            'price.numeric' => 'Giá tour phải là một con số.',
            'price.min' => 'Giá tour không được nhỏ hơn 0.',
            'price.max' => 'Giá tour vượt quá giới hạn cho phép.',
            'location.required' => 'Vui lòng nhập địa điểm tour.',
            'location.string' => 'Địa điểm tour phải là chuỗi ký tự hợp lệ.',
            'location.max' => 'Địa điểm tour không được vượt quá 255 ký tự.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'slots.required' => 'Vui lòng nhập số lượng chỗ.',
            'slots.integer' => 'Số lượng chỗ phải là số nguyên.',
            'slots.min' => 'Số lượng chỗ phải lớn hơn 0.',
            'slots.max' => 'Số lượng chỗ không được vượt quá 9999.',
            'image.image' => 'Hình ảnh phải là tệp ảnh hợp lệ.',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận định dạng PNG hoặc JPG.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ]);

        foreach (['title', 'description', 'location'] as $field) {
            $validated[$field] = trim((string) $validated[$field]);

            if (preg_match('/^https?:\/\//i', $validated[$field])) {
                return back()
                    ->withErrors([
                        $field => 'Vui lòng nhập ' . ($field === 'title' ? 'tiêu đề' : ($field === 'description' ? 'mô tả' : 'địa điểm')) . ' hợp lệ, không phải đường dẫn URL.',
                    ])
                    ->withInput();
            }
        }

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

    public function show(int $id)
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
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề tour.',
            'title.string' => 'Tiêu đề tour phải là chuỗi ký tự hợp lệ.',
            'title.max' => 'Tiêu đề tour không được vượt quá 255 ký tự.',
            'description.required' => 'Vui lòng nhập mô tả tour.',
            'description.string' => 'Mô tả tour phải là chuỗi ký tự hợp lệ.',
            'description.max' => 'Mô tả tour không được vượt quá 255 ký tự.',
            'price.required' => 'Vui lòng nhập giá tour.',
            'price.numeric' => 'Giá tour phải là một con số.',
            'price.min' => 'Giá tour không được nhỏ hơn 0.',
            'price.max' => 'Giá tour vượt quá giới hạn cho phép.',
            'location.required' => 'Vui lòng nhập địa điểm tour.',
            'location.string' => 'Địa điểm tour phải là chuỗi ký tự hợp lệ.',
            'location.max' => 'Địa điểm tour không được vượt quá 255 ký tự.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'slots.required' => 'Vui lòng nhập số lượng chỗ.',
            'slots.integer' => 'Số lượng chỗ phải là số nguyên.',
            'slots.min' => 'Số lượng chỗ phải lớn hơn 0.',
            'slots.max' => 'Số lượng chỗ không được vượt quá 9999.',
            'image.image' => 'Hình ảnh phải là tệp ảnh hợp lệ.',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận định dạng PNG hoặc JPG.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ]);

        foreach (['title', 'description', 'location'] as $field) {
            $validated[$field] = trim((string) $validated[$field]);

            if (preg_match('/^https?:\/\//i', $validated[$field])) {
                return back()
                    ->withErrors([
                        $field => 'Vui lòng nhập ' . ($field === 'title' ? 'tiêu đề' : ($field === 'description' ? 'mô tả' : 'địa điểm')) . ' hợp lệ, không phải đường dẫn URL.',
                    ])
                    ->withInput();
            }
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $validated['duration'] = abs($endDate->diffInDays($startDate)) + 1;
        // If the tour starts today (or earlier) or ends today (or earlier), mark as inactive
        $validated['status'] = ($startDate->lte(today()) || $endDate->lte(today())) ? 'inactive' : 'active';
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

    public function destroyTour(int $id)
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

    public function toggleStatus(int $id)
    {
        $tour = Tour::find($id);

        if (!$tour) {
            return redirect()->route('tours.index')
                ->with('error', 'Tour đã bị xóa hoặc không còn tồn tại.');
        }

        $start = Carbon::parse($tour->start_date);
        $end = Carbon::parse($tour->end_date);

        if ($start->lte(today()) || $end->lte(today())) {
            $tour->status = 'inactive';
        } else {
            $tour->status = $tour->status === 'active' ? 'inactive' : 'active';
        }

        $tour->save();

        return redirect()->route('tours.index')->with('success', 'Cập nhật thành công!');
    }

    private function syncExpiredTours(): void
    {
        // Mark tours that start today or earlier, or end today or earlier, as inactive
        Tour::where(function ($q) {
            $q->whereDate('start_date', '<=', today())
                ->orWhereDate('end_date', '<=', today());
        })->where('status', '!=', 'inactive')
            ->update(['status' => 'inactive']);
    }

    private function syncTourStatus(Tour $tour): void
    {
        $start = Carbon::parse($tour->start_date);
        $end = Carbon::parse($tour->end_date);

        if (($start->lte(today()) || $end->lte(today())) && $tour->status !== 'inactive') {
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
