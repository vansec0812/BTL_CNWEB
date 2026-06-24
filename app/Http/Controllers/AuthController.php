<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị giao diện đăng nhập.
     */
    public function showLoginForm(Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Sẵn sàng đăng nhập.',
            ], 200);
        }

        return view('auth.login');
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

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'login',
                'module' => 'he-thong',
                'mo_ta' => 'Cán bộ ['.Auth::user()->name.'] đăng nhập hệ thống thành công.',
            ]);

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
        $user = Auth::user();
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'logout',
                'module' => 'he-thong',
                'mo_ta' => 'Cán bộ ['.$user->name.'] đăng xuất khỏi hệ thống.',
            ]);
        }

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
}
