@extends('layouts.app')

@section('title', 'Danh sách Cán bộ')
@section('page_title', 'Quản lý tài khoản Cán bộ')

@section('content')
@php
    $roleLabels = [
        'admin' => 'Admin Hệ thống',
        'tu_phap' => 'Cán bộ Tư pháp',
        'lao_dong' => 'Cán bộ Lao động',
        'dia_chinh' => 'Cán bộ Địa chính',
        'quan_su' => 'Cán bộ Quân sự',
        'truong_thon' => 'Trưởng thôn/xóm',
    ];

    $roleColors = [
        'admin' => 'danger',
        'tu_phap' => 'success',
        'lao_dong' => 'primary',
        'dia_chinh' => 'info',
        'quan_su' => 'warning',
        'truong_thon' => 'secondary',
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Danh sách cán bộ</h4>
        <p class="text-muted small mb-0">Quản lý thông tin cá nhân, chức vụ, vai trò và trạng thái hoạt động của các cán bộ xã.</p>
    </div>
    @can('manage_users')
        <a href="{{ route('users.create') }}" class="btn btn-success d-flex align-items-center gap-1" style="border-radius: 8px;">
            <i class="bi bi-plus-lg"></i>
            <span>Thêm cán bộ mới</span>
        </a>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Bộ lọc --}}
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
    <div class="card-body p-3">
        <form action="{{ route('users.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 text-secondary" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control bg-light border-start-0 ps-0" placeholder="Tìm theo tên, email, CCCD hoặc chức vụ..." value="{{ request('q') }}" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                </div>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select form-select-sm bg-light" style="border-radius: 8px;">
                    <option value="">— Vai trò —</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ $roleLabels[$role->name] ?? $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="trang_thai" class="form-select form-select-sm bg-light" style="border-radius: 8px;">
                    <option value="">— Trạng thái —</option>
                    <option value="active" {{ request('trang_thai') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('trang_thai') === 'inactive' ? 'selected' : '' }}>Đã khóa</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-dark flex-grow-1" style="border-radius: 8px;">Lọc dữ liệu</button>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary flex-grow-1" style="border-radius: 8px;">Đặt lại</a>
            </div>
        </form>
    </div>
</div>

{{-- Bảng hiển thị --}}
<div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3" style="width: 250px;">Cán bộ</th>
                        <th class="py-3">Chức danh / Số điện thoại</th>
                        <th class="py-3 text-center" style="width: 180px;">Vai trò</th>
                        <th class="py-3 text-center" style="width: 150px;">Trạng thái</th>
                        <th class="pe-4 py-3 text-end" style="width: 220px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $userRole = $user->roles->first()?->name;
                            $roleLabel = $roleLabels[$userRole] ?? 'Cán bộ';
                            $roleColor = $roleColors[$userRole] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success fw-bold" style="width: 40px; height: 40px; font-size: 1rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark lh-sm">{{ $user->name }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark lh-sm">{{ $user->chuc_vu ?? 'Chưa cập nhật' }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-telephone me-1"></i>{{ $user->so_dien_thoai ?? '—' }}
                                </small>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} px-2.5 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                @if($user->trang_thai === 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1">
                                        Đã khóa
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-secondary btn-sm p-1.5 d-flex align-items-center justify-content-center" style="border-radius: 6px;" title="Xem hồ sơ">
                                        <i class="bi bi-eye-fill px-0.5"></i>
                                    </a>
                                    @can('manage_users')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm p-1.5 d-flex align-items-center justify-content-center" style="border-radius: 6px;" title="Sửa thông tin">
                                            <i class="bi bi-pencil-fill px-0.5"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-inline mb-0">
                                                @csrf
                                                @if($user->trang_thai === 'active')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm p-1.5 d-flex align-items-center justify-content-center" style="border-radius: 6px;" title="Khóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản của cán bộ này?')">
                                                        <i class="bi bi-lock-fill px-0.5"></i>
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-outline-success btn-sm p-1.5 d-flex align-items-center justify-content-center" style="border-radius: 6px;" title="Mở khóa tài khoản">
                                                        <i class="bi bi-unlock-fill px-0.5"></i>
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Không tìm thấy tài khoản cán bộ nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection
