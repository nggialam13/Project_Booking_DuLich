<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;

class AuthController extends Controller
{
    // Hiển thị form đăng ký
    public function showRegister()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Họ tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tạo user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'user', // mặc định
        ]);

        // Đăng nhập ngay sau khi đăng ký
        Auth::login($user);

        // Chuyển hướng
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect('/tours');
        //return redirect()->route('tours.user-index');
    }
    // Hiển thị form đăng nhập
    public function showLogin()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');
        // đường dẫn tới trang profile
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // Redirect theo role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/tours');
            //return redirect()->route('tours.user-index');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    // xử lý logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login')->with('success', 'Đã đăng xuất.');
    }

    // Hiển thị trang profile (chỉ xem)
    public function showProfile()
    {
        $user = Auth::user();
        return view('auth.profile.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa profile
    public function editProfile()
    {
        $user = Auth::user();
        return view('auth.profile.edit', compact('user'));
    }

    // Xử lý cập nhật profile (dùng PUT)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'bio' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date|before:today',
        ], [
            'name.required' => 'Họ tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'avatar.image' => 'File tải lên phải là ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'Dung lượng ảnh không được quá 2MB.',
            'dob.before' => 'Ngày sinh phải trước ngày hôm nay.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->update($request->only('name', 'email', 'phone', 'bio', 'gender', 'dob'));

        return redirect()->route('profile.show')->with('success', 'Cập nhật thông tin thành công.');
    }
    // Hiển thị form đổi mật khẩu riêng
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }
    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Đổi mật khẩu thành công!');
    }
    // hiển thị danh sách users
    public function listUsers()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }
    // xóa user (admin không thể xóa chính mình)

    public function deleteUser($id)
    {
        // Không cho xóa chính mình
        if ($id == Auth::id()) {
            return back()->with('error', 'Không thể tự xóa chính mình.');
        }

        $user = User::findOrFail($id);

        // Kiểm tra user có booking không
        $hasBookings = Booking::where('user_id', $id)->exists();
        if ($hasBookings) {
            return back()->with('error', 'Không thể xóa người dùng vì có tồn tại booking.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Xóa người dùng thành công.');
    }
    // Hiển thị form edit user
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Xử lý cập nhật user
    public function updateUser(Request $request, $id)
    {
        // 1. Kiểm tra user tồn tại hay không
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'Người dùng không tồn tại hoặc đã bị xóa.');
        }

        // 2. Kiểm tra dữ liệu có bị thay đổi từ lúc load form không
        if ($request->has('original_updated_at') && $request->original_updated_at != $user->updated_at) {
            return redirect()->route('admin.users')->with('error', 'Dữ liệu đã bị thay đổi bởi người khác. Vui lòng thao tác lại.');
        }

        // 3. Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
        ], [
            'name.required' => 'Họ tên không được để trống.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'role.required' => 'Vai trò không được để trống.',
            'role.in' => 'Vai trò không hợp lệ.',
        ]);

        // 4. Không cho admin tự thay đổi role của chính mình
        if ($id == Auth::id() && $request->role != $user->role) {
            return back()->withErrors(['role' => 'Bạn không thể thay đổi vai trò của chính mình.']);
        }

        // 5. Cập nhật
        $user->update($request->only('name', 'email', 'phone', 'role'));

        return redirect()->route('admin.users')->with('success', 'Cập nhật người dùng thành công.');
    }
}