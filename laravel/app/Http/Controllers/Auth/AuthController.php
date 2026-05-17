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

    // Hiển thị trang profile
    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    // Cập nhật thông tin cá nhân
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:500',
            'interests' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date|before:today',
        ]);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->update($request->only('name', 'email', 'phone', 'bio', 'interests', 'gender', 'dob'));

        return redirect()->route('profile')->with('success', 'Cập nhật thông tin thành công!');
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

        return redirect()->route('change-password.form')->with('success', 'Đổi mật khẩu thành công!');
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
        if ($id == Auth::id()) {
            return back()->with('error', 'Không thể tự xóa chính mình.');
        }
        $user = User::findOrFail($id);
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
        $user = User::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:11',
            'role' => 'required|in:user,admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'avatar.image' => 'File phải là ảnh.',
            'avatar.mimes' => 'Chỉ chấp nhận định dạng: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
        ]);

        // Không cho admin tự hạ cấp role của chính mình
        if ($id == Auth::id() && $request->role != Auth::user()->role) {
            return back()->withErrors(['role' => 'Bạn không thể thay đổi vai trò của chính mình.']);
        }

        $user->update($request->only('name', 'email', 'phone', 'role'));

        return redirect()->route('admin.users')->with('success', 'Cập nhật người dùng thành công.');
    }
}