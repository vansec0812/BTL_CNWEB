@extends('layouts.app')

@section('title', 'Hộ tịch & Cư trú')
@section('page_title', 'Hộ tịch & Cư trú')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-journal-text stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Sổ hộ khẩu</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['so_ho_khau'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-person-badge stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Nhân khẩu</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['nhan_khau'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-luggage stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Tạm trú / Tạm vắng</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['tam_tru'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-arrow-left-right stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Biến động gần đây</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['bien_dong'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Nội dung chính: danh sách chức năng + bảng mẫu --}}
<div class="row g-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-list-check me-1"></i>Danh mục chức năng</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1"><i class="bi bi-plus-lg small"></i></span>
                        Thêm mới hộ khẩu
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1"><i class="bi bi-pencil small"></i></span>
                        Cập nhật thông tin hộ
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1"><i class="bi bi-person-plus small"></i></span>
                        Thêm nhân khẩu mới
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1"><i class="bi bi-arrow-left-right small"></i></span>
                        Tách / Nhập hộ
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1"><i class="bi bi-luggage small"></i></span>
                        Khai báo tạm trú / tạm vắng
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1"><i class="bi bi-journal-x small"></i></span>
                        Khai tử
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-1"><i class="bi bi-file-text small"></i></span>
                        Quản lý mối quan hệ
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table me-1"></i>Danh sách hộ khẩu gần đây</span>
                <button class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i> Xuất danh sách</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Mã hộ</th>
                                <th>Số sổ hộ khẩu</th>
                                <th>Chủ hộ</th>
                                <th>Số thành viên</th>
                                <th>Phân loại</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dsHoKhau ?? [] as $ho)
                            <tr>
                                <td class="fw-semibold">{{ $ho->ma_ho }}</td>
                                <td>{{ $ho->so_so_ho_khau }}</td>
                                <td>{{ $ho->chu_ho_ten ?? '—' }}</td>
                                <td>{{ $ho->so_thanh_vien }}</td>
                                <td><span class="badge bg-info bg-opacity-10 text-info">{{ $ho->phan_loai === 'thuong_tru' ? 'Thường trú' : ($ho->phan_loai === 'tam_tru' ? 'Tạm trú' : 'Tạm vắng') }}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary" title="Xem chi tiết"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Sửa"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có dữ liệu hộ khẩu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
