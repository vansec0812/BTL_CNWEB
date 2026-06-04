<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
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

        return view('he-thong.users.index', compact('users', 'roles', 'modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::all();
        $modules = ModuleRegistry::all();
        return view('he-thong.users.create', compact('roles', 'modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        // Create the user profile
        $user = User::create($validated);

        // Assign the Spatie role
        $user->assignRole($validated['role']);

        return redirect()
            ->route('users.index')
            ->with('status', "Đã tạo tài khoản cán bộ {$user->name} thành công.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        if (auth()->id() !== $user->id && !auth()->user()->can('manage_users')) {
            abort(403, 'Bạn không có quyền xem hồ sơ của cán bộ khác.');
        }

        $modules = ModuleRegistry::all();
        return view('he-thong.users.show', compact('user', 'modules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        $modules = ModuleRegistry::all();
        return view('he-thong.users.edit', compact('user', 'roles', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
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

        return redirect()
            ->route('users.index')
            ->with('status', "Đã cập nhật tài khoản cán bộ {$user->name} thành công.");
    }

    /**
     * Toggle status (Lock / Unlock).
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        // Prevent lockout of admin
        if ($user->hasRole('admin') && $user->trang_thai === 'active') {
            return redirect()
                ->back()
                ->with('error', 'Không thể khóa tài khoản vai trò Admin Hệ thống.');
        }

        $newStatus = $user->trang_thai === 'active' ? 'inactive' : 'active';
        $user->update(['trang_thai' => $newStatus]);

        $message = $newStatus === 'active' ? "Đã mở khóa tài khoản {$user->name}." : "Đã khóa tài khoản {$user->name}.";

        return redirect()
            ->back()
            ->with('status', $message);
    }
}
