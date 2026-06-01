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
    // Regex dùng chung
    private $latinRegex = '/^[\p{Latin}0-9\s]+$/u';
    private $asciiRegex = '/^[\x20-\x7E]+$/';
    private $phoneRegex = '/^[0-9]+$/';

    // Hiển thị form đăng ký
    public function showRegister()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:' . $this->latinRegex,
            'email' => 'required|string|email|max:255|unique:users|regex:' . $this->asciiRegex,
            'password' => 'required|string|min:6|confirmed|regex:' . $this->asciiRegex,
            'phone' => 'nullable|numeric|digits_between:9,12|regex:' . $this->phoneRegex,
        ], [

            // NAME
            'name.required' => 'Họ tên không được để trống.',
            'name.regex' => 'Họ tên không hỗ trợ ký tự hoặc ngôn ngữ đặc biệt.',

            // EMAIL
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'email.regex' => 'Email không hỗ trợ ký tự Unicode hoặc ngôn ngữ khác.',

            // PASSWORD
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.regex' => 'Mật khẩu không hỗ trợ ký tự Unicode hoặc ngôn ngữ khác.',

            // PHONE
            'phone.numeric' => 'Số điện thoại phải là chữ số.',
            'phone.digits_between' => 'Số điện thoại phải có độ dài từ 9 đến 12 số.',
            'phone.regex' => 'Số điện thoại không hỗ trợ số Unicode hoặc ký tự đặc biệt.',

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
            'role' => 'user',
        ]);

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/tours');
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
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu không được để trống.',
        ]);

        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/tours');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login')->with('success', 'Đã đăng xuất.');
    }

    // Hiển thị profile
    public function showProfile()
    {
        $user = Auth::user();

        // Kiểm tra user có thực sự tồn tại trong DB không
        $dbUser = User::find($user->id);
        if (!$dbUser) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị xóa khỏi hệ thống. Vui lòng đăng ký lại.');
        }
        return view('auth.profile.show', compact('user'));
    }

    // Form edit profile
    public function editProfile()
    {
        $user = Auth::user();

        // Kiểm tra user có thực sự tồn tại trong DB không
        $dbUser = User::find($user->id);
        if (!$dbUser) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị xóa khỏi hệ thống. Vui lòng đăng ký lại.');
        }
        return view('auth.profile.edit', compact('user'));
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        // Kiểm tra user có thực sự tồn tại trong DB không
        $dbUser = User::find($user->id);
        if (!$dbUser) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị xóa khỏi hệ thống. Vui lòng đăng ký lại.');
        }

        $request->validate([
            'name' => 'required|string|max:255|regex:' . $this->latinRegex,
            'email' => 'required|email|unique:users,email,' . $user->id . '|regex:' . $this->asciiRegex,
            'phone' => 'nullable|numeric|digits_between:9,12|regex:' . $this->phoneRegex,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'bio' => 'nullable|string|max:500|regex:' . $this->latinRegex,
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date|before:today',
        ], [

            'name.required' => 'Họ tên không được để trống.',
            'name.regex' => 'Họ tên không hỗ trợ ký tự hoặc ngôn ngữ đặc biệt.',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'email.regex' => 'Email không hỗ trợ ký tự Unicode.',

            'phone.numeric' => 'Số điện thoại phải là chữ số.',
            'phone.digits_between' => 'Số điện thoại phải có độ dài từ 9 đến 12 số.',
            'phone.regex' => 'Số điện thoại không hỗ trợ số Unicode.',

            'bio.regex' => 'Giới thiệu không hỗ trợ ký tự hoặc ngôn ngữ đặc biệt.',

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

        $user->update($request->only(
            'name',
            'email',
            'phone',
            'bio',
            'gender',
            'dob'
        ));

        return redirect()->route('profile.show')
            ->with('success', 'Cập nhật thông tin thành công.');
    }

    // Form đổi mật khẩu
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|regex:' . $this->asciiRegex,
            'new_password' => 'required|min:6|confirmed|regex:' . $this->asciiRegex,
        ], [
            'current_password.regex' => 'Mật khẩu hiện tại không hỗ trợ ký tự Unicode.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'new_password.regex' => 'Mật khẩu mới không hỗ trợ ký tự Unicode.',
        ]);

        $user = Auth::user();

        // Kiểm tra user có thực sự tồn tại trong DB không
        $dbUser = User::find($user->id);
        if (!$dbUser) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị xóa khỏi hệ thống. Vui lòng đăng ký lại.');
        }
        if (!Hash::check($request->current_password, $user->password)) {

            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không đúng.'
            ]);
        }

        $user->password = Hash::make($request->new_password);

        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Đổi mật khẩu thành công!');
    }

    // Danh sách users
    public function listUsers()
    {
        $users = User::paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Xóa user
    public function deleteUser($id)
    {
        if ($id == Auth::id()) {
            return back()->with('error', 'Không thể tự xóa chính mình.');
        }

        $user = User::findOrFail($id);

        $hasBookings = Booking::where('user_id', $id)->exists();

        if ($hasBookings) {
            return back()->with('error', 'Không thể xóa người dùng vì có tồn tại booking.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'Xóa người dùng thành công.');
    }

    // Form edit user
    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.users')
                ->with('error', 'Người dùng không tồn tại hoặc đã bị xóa.');
        }

        if (
            $request->has('original_updated_at')
            && $request->original_updated_at != $user->updated_at
        ) {
            return redirect()->route('admin.users')
                ->with('error', 'Dữ liệu đã bị thay đổi bởi người khác.');
        }

        $request->validate([
            'name' => 'required|string|max:255|regex:' . $this->latinRegex,
            'email' => 'required|email|unique:users,email,' . $id . '|regex:' . $this->asciiRegex,
            'phone' => 'nullable|numeric|digits_between:9,12|regex:' . $this->phoneRegex,
            'role' => 'required|in:user,admin',
        ], [

            'name.required' => 'Họ tên không được để trống.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'name.regex' => 'Họ tên không hỗ trợ ký tự hoặc ngôn ngữ đặc biệt.',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'email.regex' => 'Email không hỗ trợ ký tự Unicode.',

            'phone.numeric' => 'Số điện thoại phải là chữ số.',
            'phone.digits_between' => 'Số điện thoại phải có độ dài từ 9 đến 12 số.',
            'phone.regex' => 'Số điện thoại không hỗ trợ số Unicode.',

            'role.required' => 'Vai trò không được để trống.',
            'role.in' => 'Vai trò không hợp lệ.',
        ]);

        if ($id == Auth::id() && $request->role != $user->role) {

            return back()->withErrors([
                'role' => 'Bạn không thể thay đổi vai trò của chính mình.'
            ]);
        }

        $user->update($request->only(
            'name',
            'email',
            'phone',
            'role'
        ));

        return redirect()->route('admin.users')->with('success', 'Cập nhật người dùng thành công.');
    }
}

