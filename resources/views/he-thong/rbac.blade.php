@extends('layouts.app')

@section('title', 'Ma trận phân quyền (RBAC)')
@section('page_title', 'Cấu hình Phân quyền Hệ thống')

@section('content')
@php
    $permissionLabels = [
        'manage_users' => 'Quản lý người dùng (User cán bộ)',
        'view_audit_logs' => 'Xem nhật ký hệ thống (Audit Logs)',
        'view_ho_khau' => 'Xem danh sách & Chi tiết hộ khẩu',
        'manage_ho_khau' => 'Thay đổi / Sửa đổi sổ hộ khẩu (CRUD)',
        'view_nhan_khau' => 'Xem danh sách & Chi tiết nhân khẩu',
        'manage_nhan_khau' => 'Thay đổi / Sửa đổi nhân khẩu (CRUD)',
        'view_lao_dong' => 'Xem danh sách & Hồ sơ lao động',
        'manage_lao_dong' => 'Thay đổi / Sửa đổi lao động & Việc làm',
        'view_an_sinh' => 'Xem danh sách & Đối tượng chính sách',
        'manage_an_sinh' => 'Thay đổi / Quản lý trợ cấp an sinh',
        'view_nghia_vu' => 'Xem Nghĩa vụ & An ninh quốc phòng',
        'manage_nghia_vu' => 'Thay đổi / Quản lý Nghĩa vụ & An ninh quốc phòng',
        'view_dat_dai' => 'Xem hồ sơ thửa đất & Thuế phí',
        'manage_dat_dai' => 'Thay đổi / Quản lý đất đai & Thuế phí',
    ];

    $roleLabels = [
        'admin' => 'Admin Hệ thống',
        'tu_phap' => 'Cán bộ Tư pháp',
        'lao_dong' => 'Cán bộ Lao động',
        'dia_chinh' => 'Cán bộ Địa chính',
        'quan_su' => 'Cán bộ Quân sự',
    ];

    $roleColors = [
        'admin' => 'danger',
        'tu_phap' => 'success',
        'lao_dong' => 'primary',
        'dia_chinh' => 'info',
        'quan_su' => 'warning',
    ];

    $isAdmin = auth()->user()->hasRole('admin');
@endphp

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-1">Ma trận phân quyền (RBAC Matrix)</h4>
                <p class="text-muted small mb-0">Thiết lập mối quan hệ giữa các Vai trò (Roles) và Quyền hạn (Permissions) trong hệ thống.</p>
            </div>
            <div>
                @if($isAdmin)
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2" style="font-size: 0.85rem;">
                        <i class="bi bi-pencil-square me-1"></i> Chế độ chỉnh sửa (Admin)
                    </span>
                @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-3 py-2" style="font-size: 0.85rem;">
                        <i class="bi bi-eye me-1"></i> Chế độ xem (Chỉ đọc)
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="border-collapse: separate;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3" style="width: 350px; min-width: 280px; background: #f8f9fa;">Quyền hạn / Chức năng</th>
                        @foreach($roles as $role)
                            <th class="text-center py-3" style="min-width: 130px; background: #f8f9fa;">
                                <span class="badge bg-{{ $roleColors[$role->name] ?? 'dark' }} bg-opacity-10 text-{{ $roleColors[$role->name] ?? 'dark' }} px-2 py-1 small fw-bold">
                                    {{ $roleLabels[$role->name] ?? $role->name }}
                                </span>
                                <small class="d-block text-muted mt-1" style="font-size: 0.7rem; font-weight: normal;">slug: {{ $role->name }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedPermissions as $groupName => $perms)
                        @if($perms->count() > 0)
                            <tr class="table-light">
                                <td colspan="{{ $roles->count() + 1 }}" class="ps-4 fw-bold text-dark py-2" style="background: rgba(15, 81, 50, 0.04); font-size: 0.85rem;">
                                    <i class="bi bi-folder-fill text-success me-1"></i> {{ $groupName }}
                                </td>
                            </tr>
                            @foreach($perms as $perm)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                            {{ $permissionLabels[$perm->name] ?? $perm->name }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Quyền: {{ $perm->name }}</small>
                                    </td>
                                    @foreach($roles as $role)
                                        @php
                                            $hasPerm = $role->hasPermissionTo($perm);
                                            // Admin shouldn't lose core permissions
                                            $isLockedAdminPerm = $role->name === 'admin' && in_array($perm->name, ['manage_users', 'view_audit_logs']);
                                        @endphp
                                        <td class="text-center py-3">
                                            @if($isAdmin)
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input permission-switch" 
                                                           type="checkbox" 
                                                           data-role-id="{{ $role->id }}" 
                                                           data-perm-id="{{ $perm->id }}"
                                                           {{ $hasPerm ? 'checked' : '' }}
                                                           {{ $isLockedAdminPerm ? 'disabled' : '' }}
                                                           style="cursor: {{ $isLockedAdminPerm ? 'not-allowed' : 'pointer' }}; width: 2.2em; height: 1.1em;">
                                                </div>
                                            @else
                                                @if($hasPerm)
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                        <i class="bi bi-check-lg" style="font-size: 0.85rem;"></i>
                                                    </span>
                                                @else
                                                    <span class="text-muted opacity-25">—</span>
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bàn phím chú thích --}}
<div class="mt-4 card border-0 shadow-sm bg-light">
    <div class="card-body p-3 small text-secondary">
        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle me-1"></i> Hướng dẫn vận hành:</h6>
        <ul class="mb-0 ps-3">
            <li>Để sửa đổi ma trận này, bạn cần chuyển đổi sang tài khoản **Admin Hệ thống** thông qua thanh chọn nhanh ở góc trên bên phải.</li>
            <li>Các thay đổi được áp dụng **ngay lập tức** thông qua cơ chế AJAX và đồng bộ tự động vào CSDL (bảng `role_has_permissions`). Các cán bộ truy cập sẽ chịu ảnh hưởng của phân quyền mới mà không cần đăng nhập lại.</li>
            <li>Để đảm bảo tính bảo mật và an toàn, quyền cốt lõi của vai trò **Admin** (`manage_users`, `view_audit_logs`) bị khóa cứng để tránh tình trạng Admin vô tình khóa chính mình.</li>
        </ul>
    </div>
</div>

{{-- Notification Toast Container --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="toast-notify" class="toast align-items-center text-white border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-info-circle-fill me-1"></i> <span id="toast-message">Đang cập nhật...</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const switches = document.querySelectorAll('.permission-switch');
    const toastEl = document.getElementById('toast-notify');
    const toastBody = document.getElementById('toast-message');
    
    // Initialize Bootstrap Toast if available
    let toast = null;
    if (window.bootstrap && window.bootstrap.Toast) {
        toast = new window.bootstrap.Toast(toastEl, { delay: 3000 });
    }

    function showToast(message, type = 'success') {
        toastEl.className = `toast align-items-center text-white border-0 shadow bg-${type}`;
        toastBody.textContent = message;
        if (toast) {
            toast.show();
        } else {
            alert(message); // Fallback if bootstrap is loading slowly
        }
    }

    switches.forEach(sw => {
        sw.addEventListener('change', function () {
            const roleId = this.getAttribute('data-role-id');
            const permId = this.getAttribute('data-perm-id');
            const isChecked = this.checked;
            
            // Disable element during AJAX
            this.disabled = true;

            fetch('{{ route("he-thong.rbac.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    role_id: roleId,
                    permission_id: permId
                })
            })
            .then(res => res.json())
            .then(data => {
                this.disabled = false;
                if (data.success) {
                    showToast(data.message, 'success');
                    this.checked = data.active;
                } else {
                    showToast(data.message, 'danger');
                    this.checked = !isChecked; // Revert
                }
            })
            .catch(err => {
                this.disabled = false;
                this.checked = !isChecked; // Revert
                showToast('Có lỗi kết nối mạng xảy ra. Hãy thử lại.', 'danger');
                console.error(err);
            });
        });
    });
});
</script>
@endsection
