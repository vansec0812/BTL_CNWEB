<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị giao diện đăng nhập.
     */
    public function showLoginForm()
    {
        $users = User::with('roles')->get();

        return view('auth.login', compact('users'));
    }

    /**
     * Xử lý đăng nhập bằng tài khoản/mật khẩu.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Đăng xuất khỏi hệ thống.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Chuyển nhanh tài khoản để test (User Switcher).
     */
    public function switchUser(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        Auth::loginUsingId($request->user_id);

        return back()->with('status', 'Đã chuyển đổi tài khoản thành công.');
    }
}
