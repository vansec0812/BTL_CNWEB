<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        // Search by name, email, cccd, or title
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('so_cccd', 'like', "%{$q}%")
                    ->orWhere('chuc_vu', 'like', "%{$q}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->input('role'));
        }

        // Filter by status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->input('trang_thai'));
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::all();
        $modules = ModuleRegistry::all();
        $parentModule = ModuleRegistry::findBySlug('he-thong-bao-cao');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách cán bộ thành công.',
                'data' => $users,
                'roles' => $roles,
            ], 200);
        }

        return view('he-thong.users.index', compact('users', 'roles', 'modules', 'parentModule'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $roles = Role::all();
        $modules = ModuleRegistry::all();
        $parentModule = ModuleRegistry::findBySlug('he-thong-bao-cao');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form tạo cán bộ thành công.',
                'data' => [
                    'roles' => $roles,
                ],
            ], 200);
        }

        return view('he-thong.users.create', compact('roles', 'modules', 'parentModule'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        // Create the user profile
        $user = User::create($validated);

        // Assign the Spatie role
        $user->assignRole($validated['role']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => "Đã tạo tài khoản cán bộ {$user->name} thành công.",
                'data' => $user->load('roles'),
            ], 201);
        }

        return redirect()
            ->route('users.index')
            ->with('status', "Đã tạo tài khoản cán bộ {$user->name} thành công.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Request $request)
    {
        if (auth()->id() !== $user->id && ! auth()->user()->can('manage_users')) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem hồ sơ của cán bộ khác.',
                ], 403);
            }
            abort(403, 'Bạn không có quyền xem hồ sơ của cán bộ khác.');
        }

        $modules = ModuleRegistry::all();
        $parentModule = ModuleRegistry::findBySlug('he-thong-bao-cao');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết cán bộ thành công.',
                'data' => $user->load('roles'),
            ], 200);
        }

        return view('he-thong.users.show', compact('user', 'modules', 'parentModule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Request $request)
    {
        $roles = Role::all();
        $modules = ModuleRegistry::all();
        $parentModule = ModuleRegistry::findBySlug('he-thong-bao-cao');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => [
                    'user' => $user->load('roles'),
                    'roles' => $roles,
                ],
            ], 200);
        }

        return view('he-thong.users.edit', compact('user', 'roles', 'modules', 'parentModule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if (filled($validated['password'] ?? null)) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update user
        $user->update($validated);

        // Sync Spatie role
        $user->syncRoles($validated['role']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật tài khoản cán bộ {$user->name} thành công.",
                'data' => $user->load('roles'),
            ], 200);
        }

        return redirect()
            ->route('users.index')
            ->with('status', "Đã cập nhật tài khoản cán bộ {$user->name} thành công.");
    }

    /**
     * Toggle status (Lock / Unlock).
     */
    public function toggleStatus(User $user, Request $request)
    {
        // Prevent lockout of admin
        if ($user->hasRole('admin') && $user->trang_thai === 'active') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể khóa tài khoản vai trò Admin Hệ thống.',
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', 'Không thể khóa tài khoản vai trò Admin Hệ thống.');
        }

        $newStatus = $user->trang_thai === 'active' ? 'inactive' : 'active';
        $user->update(['trang_thai' => $newStatus]);

        $message = $newStatus === 'active' ? "Đã mở khóa tài khoản {$user->name}." : "Đã khóa tài khoản {$user->name}.";

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $user->load('roles'),
            ], 200);
        }

        return redirect()
            ->back()
            ->with('status', $message);
    }

    /**
     * Change password for the logged-in user.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc.',
            'new_password.required' => 'Mật khẩu mới là bắt buộc.',
            'new_password.min' => 'Mật khẩu mới phải có tối thiểu :min ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không trùng khớp.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không chính xác.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        \App\Models\AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'change_password',
            'module' => 'he-thong',
            'mo_ta' => 'Cán bộ [' . $user->name . '] thay đổi mật khẩu tài khoản thành công.',
        ]);

        return back()->with('status', 'Đổi mật khẩu thành công!');
    }
}
