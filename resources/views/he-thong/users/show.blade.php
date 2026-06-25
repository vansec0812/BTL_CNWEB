@extends('layouts.app')

@section('title', 'Thông tin Cán bộ')
@section('page_title', 'Hồ sơ chi tiết cán bộ')

@section('content')
@php
    $roleLabels = [
        'admin' => 'Admin Hệ thống',
        'tu_phap' => 'Cán bộ Tư pháp (Hộ tịch & Cư trú)',
        'lao_dong' => 'Cán bộ Lao động (Kinh tế, Lao động, An sinh)',
        'dia_chinh' => 'Cán bộ Địa chính (Đất đai & Hạ tầng)',
        'quan_su' => 'Cán bộ Quân sự (Nghĩa vụ & An ninh quốc phòng)',
    ];

    $roleColors = [
        'admin' => 'danger',
        'tu_phap' => 'success',
        'lao_dong' => 'primary',
        'dia_chinh' => 'info',
        'quan_su' => 'warning',
    ];

    $userRole = $user->roles->first()?->name;
    $roleLabel = $roleLabels[$userRole] ?? 'Cán bộ';
    $roleColor = $roleColors[$userRole] ?? 'secondary';
@endphp

<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        @can('manage_users')
            <a href="{{ route('users.index') }}" class="text-decoration-none">Tài khoản cán bộ</a>
            <span class="mx-1">/</span>
        @endcan
        Chi tiết hồ sơ cán bộ
    </div>
    @can('manage_users')
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
            <i class="bi bi-arrow-left"></i> Quay lại trang chủ
        </a>
    @endcan
</div>

<div class="row g-3">
    {{-- Cột bên trái: Thẻ đại diện --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 text-center p-4" style="border-radius: 12px;">
            <div class="card-body">
                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center text-success fw-bold mx-auto mb-3" style="width: 90px; height: 90px; font-size: 2.5rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                <p class="text-secondary small mb-3">{{ $user->email }}</p>

                <div class="mb-3">
                    <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} px-3 py-2 fw-bold" style="font-size: 0.8rem; border-radius: 20px;">
                        {{ $roleLabel }}
                    </span>
                </div>

                <div class="mb-4">
                    @if($user->trang_thai === 'active')
                        <span class="badge bg-success text-white px-3 py-1.5" style="border-radius: 20px; font-size: 0.75rem;">
                            <i class="bi bi-check-circle-fill me-1"></i> Không khóa
                        </span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-1.5" style="border-radius: 20px; font-size: 0.75rem;">
                            <i class="bi bi-lock-fill me-1"></i> Khóa
                        </span>
                    @endif
                </div>

                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    @if($user->id === auth()->id())
                        <button type="button" class="btn btn-warning btn-sm px-3 d-flex align-items-center gap-1" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key-fill"></i>
                            <span>Đổi mật khẩu</span>
                        </button>
                    @endif
                    @can('manage_users')
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" style="border-radius: 8px;">
                            <i class="bi bi-pencil-fill"></i>
                            <span>Chỉnh sửa</span>
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="mb-0">
                                @csrf
                                @if($user->trang_thai === 'active')
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 8px;" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản cán bộ này?')">
                                        Khóa tài khoản
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-outline-success btn-sm px-3" style="border-radius: 8px;">
                                        Mở khóa
                                    </button>
                                @endif
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Cột bên phải: Chi tiết hồ sơ lý lịch --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
            <div class="card-header bg-light py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-person me-1 text-success"></i>Thông tin hồ sơ cán bộ</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="small text-secondary mb-1">Số Căn cước công dân (CCCD)</div>
                        <div class="fw-bold text-dark fs-6">{{ $user->so_cccd ?? 'Chưa cập nhật' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary mb-1">Chức danh công tác (Chức vụ)</div>
                        <div class="fw-bold text-dark fs-6">{{ $user->chuc_vu ?? 'Chưa cập nhật' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary mb-1">Giới tính</div>
                        <div class="fw-bold text-dark fs-6">
                            {{ $user->gioi_tinh === 'nam' ? 'Nam' : ($user->gioi_tinh === 'nu' ? 'Nữ' : 'Khác') }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary mb-1">Ngày sinh</div>
                        <div class="fw-bold text-dark fs-6">
                            {{ $user->ngay_sinh ? $user->ngay_sinh->format('d/m/Y') : 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary mb-1">Số điện thoại liên lạc</div>
                        <div class="fw-bold text-dark fs-6">{{ $user->so_dien_thoai ?? 'Chưa cập nhật' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-secondary mb-1">Quê quán</div>
                        <div class="fw-bold text-dark fs-6">{{ $user->que_quan ?? 'Chưa cập nhật' }}</div>
                    </div>

                    <div class="col-12">
                        <div class="small text-secondary mb-1">Địa chỉ thường trú / Nơi ở hiện tại</div>
                        <div class="fw-bold text-dark fs-6">{{ $user->dia_chi ?? 'Chưa cập nhật' }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Đổi mật khẩu -->
@if($user->id === auth()->id())
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-warning bg-opacity-10 py-3 border-bottom-0">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="changePasswordModalLabel">
                    <i class="bi bi-key-fill text-warning"></i> Đổi mật khẩu tài khoản
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.change-password') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <div class="mb-3 text-start">
                        <label for="current_password" class="form-label fw-semibold text-secondary small mb-1">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="current_password" id="current_password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" placeholder="Nhập mật khẩu đang sử dụng" required>
                        </div>
                        @error('current_password')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label for="new_password" class="form-label fw-semibold text-secondary small mb-1">Mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-key"></i></span>
                            <input type="password" name="new_password" id="new_password" class="form-control border-start-0 @error('new_password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự" required>
                        </div>
                        @error('new_password')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 text-start">
                        <label for="new_password_confirmation" class="form-label fw-semibold text-secondary small mb-1">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-key"></i></span>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control border-start-0" placeholder="Nhập lại mật khẩu mới" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-top-0 d-flex gap-2" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px;">Hủy bỏ</button>
                    <button type="submit" class="btn btn-success px-4 py-2" style="border-radius: 8px;">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if($errors->has('current_password') || $errors->has('new_password'))
        var myModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        myModal.show();
    @endif
});
</script>
@endif
@endsection
