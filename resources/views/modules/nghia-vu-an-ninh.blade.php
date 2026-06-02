@extends('layouts.app')

@section('title', 'Nghĩa vụ & An ninh quốc phòng')
@section('page_title', 'Nghĩa vụ & An ninh quốc phòng')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-shield-check stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">NVQS — Đang quản lý</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['nghia_vu_quan_su'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people-shield stat-icon"></i>
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
                <i class="bi bi-check2-all stat-icon"></i>
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
                <i class="bi bi-clock-history stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Tạm hoãn</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['tam_hoan'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-list-check me-1"></i>Danh mục chức năng</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-1"><i class="bi bi-plus-lg small"></i></span>
                        Thêm hồ sơ NVQS
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-1"><i class="bi bi-people-shield small"></i></span>
                        Quản lý dân quân tự vệ
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-1"><i class="bi bi-search small"></i></span>
                        Quét danh sách đủ tuổi NVQS
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-1"><i class="bi bi-clock small"></i></span>
                        Ghi nhận tạm hoãn / miễn
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-1"><i class="bi bi-shield-exclamation small"></i></span>
                        An ninh trật tự
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table me-1"></i>Danh sách NVQS</span>
                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-search"></i> Quét tự động</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Họ tên</th>
                                <th>Năm sinh</th>
                                <th>Trạng thái NVQS</th>
                                <th>Lý do tạm hoãn</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dsNVQS ?? [] as $nvqs)
                            <tr>
                                <td>{{ $nvqs->id }}</td>
                                <td class="fw-semibold">{{ $nvqs->ho_ten ?? '—' }}</td>
                                <td>{{ $nvqs->nam_sinh ?? '—' }}</td>
                                <td>
                                    @php $ttNvqs = $nvqs->trang_thai_nvqs ?? 'chua_den_tuoi'; @endphp
                                    <span class="badge bg-{{ $ttNvqs === 'da_nhap_ngu' ? 'success' : ($ttNvqs === 'tam_hoan' ? 'warning' : ($ttNvqs === 'xuat_ngu' ? 'info' : 'secondary')) }}">
                                        {{ $ttNvqs === 'da_nhap_ngu' ? 'Đã nhập ngũ' : ($ttNvqs === 'tam_hoan' ? 'Tạm hoãn' : ($ttNvqs === 'xuat_ngu' ? 'Xuất ngũ' : ($ttNvqs === 'du_dieu_kien' ? 'Đủ điều kiện' : 'Chưa đến tuổi'))) }}
                                    </span>
                                </td>
                                <td>{{ $nvqs->ly_do_tam_hoan ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có dữ liệu NVQS.
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
