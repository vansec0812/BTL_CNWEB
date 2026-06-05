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
    public function showLoginForm(Request $request)
    {
        $users = User::with('roles')->get();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách người dùng đăng nhập nhanh thành công.',
                'data' => $users,
            ], 200);
        }

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

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công.',
                    'user' => Auth::user(),
                ], 200);
            }

            return redirect()->intended('/');
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác.',
            ], 401);
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

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công.',
            ], 200);
        }

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

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã chuyển đổi tài khoản thành công.',
                'user' => Auth::user(),
            ], 200);
        }

        return back()->with('status', 'Đã chuyển đổi tài khoản thành công.');
    }
}
