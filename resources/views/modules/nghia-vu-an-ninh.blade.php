@extends('layouts.app')

@section('title', 'Nghĩa vụ & An ninh quốc phòng')
@section('page_title', 'Nghĩa vụ & An ninh quốc phòng')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle"><i class="bi bi-shield-check fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">NVQS — Tổng số hồ sơ</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['nghia_vu_quan_su'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle"><i class="bi bg-opacity-10 bi-people-shield fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">Dân quân tự vệ</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['dan_quan_tu_ve'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle"><i class="bi bi-check2-all fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">Đủ điều kiện NVQS</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['du_dieu_kien'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-danger bg-opacity-10 text-danger p-3 rounded-circle"><i class="bi bi-clock-history fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">Tạm hoãn</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['tam_hoan'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Danh sách gần đây -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold"><i class="bi bi-table me-1"></i>Hồ sơ nghĩa vụ quân sự gần đây</span>
                <a href="{{ route('nghia-vu-quan-su.index') }}" class="btn btn-sm btn-success fw-semibold"><i class="bi bi-arrow-right-short"></i> Quản lý toàn bộ</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Họ tên</th>
                                <th>Năm sinh</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dsNVQS ?? [] as $nvqs)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $nvqs->nhanKhau->ho_ten ?? '—' }}</td>
                                <td>{{ $nvqs->nhanKhau->ngay_sinh ? $nvqs->nhanKhau->ngay_sinh->format('Y') : '—' }}</td>
                                <td>
                                    @php
                                        $color = 'secondary';
                                        $label = 'Chưa đến tuổi';
                                        if ($nvqs->trang_thai_nvqs === 'du_dieu_kien') { $color = 'success'; $label = 'Đủ điều kiện'; }
                                        elseif ($nvqs->trang_thai_nvqs === 'tam_hoan') { $color = 'warning'; $label = 'Tạm hoãn'; }
                                        elseif ($nvqs->trang_thai_nvqs === 'trung_tuyen') { $color = 'info'; $label = 'Trúng tuyển'; }
                                        elseif ($nvqs->trang_thai_nvqs === 'da_nhap_ngu') { $color = 'primary'; $label = 'Đã nhập ngũ'; }
                                        elseif ($nvqs->trang_thai_nvqs === 'xuat_ngu') { $color = 'dark'; $label = 'Xuất ngũ'; }
                                        elseif ($nvqs->trang_thai_nvqs === 'mien_goi') { $color = 'secondary'; $label = 'Miễn gọi'; }
                                        elseif ($nvqs->trang_thai_nvqs === 'da_qua_tuoi') { $color = 'secondary'; $label = 'Đã quá tuổi'; }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('nghia-vu-quan-su.show', $nvqs) }}" class="btn btn-sm btn-outline-secondary" title="Xem chi tiết"><i class="bi bi-eye"></i> Chi tiết</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có dữ liệu nghĩa vụ quân sự.
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
