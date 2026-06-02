@extends('layouts.app')

@section('title', 'Kinh tế, Lao động & Việc làm')
@section('page_title', 'Kinh tế, Lao động & Việc làm')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Lao động</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['lao_dong'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-building stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Doanh nghiệp / HKD</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['doanh_nghiep'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-graph-up-arrow stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Kết nối việc làm</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['ket_noi_viec_lam'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-airplane stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Xuất khẩu lao động</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['xuat_khau_lao_dong'] ?? 0 }}</h4>
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
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-1"><i class="bi bi-plus-lg small"></i></span>
                        Thêm hồ sơ lao động
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-1"><i class="bi bi-pencil small"></i></span>
                        Cập nhật trạng thái lao động
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-1"><i class="bi bi-building-add small"></i></span>
                        Quản lý doanh nghiệp
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-1"><i class="bi bi-link small"></i></span>
                        Kết nối việc làm
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-1"><i class="bi bi-globe2 small"></i></span>
                        Quản lý xuất khẩu lao động
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-1"><i class="bi bi-funnel small"></i></span>
                        Bộ lọc nâng cao
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table me-1"></i>Danh sách lao động</span>
                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Xuất danh sách</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Họ tên</th>
                                <th>Trạng thái</th>
                                <th>Ngành nghề</th>
                                <th>Loại hình</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dsLaoDong ?? [] as $ld)
                            <tr>
                                <td>{{ $ld->id }}</td>
                                <td class="fw-semibold">{{ $ld->ho_ten ?? '—' }}</td>
                                <td>
                                    @php $trangThaiLd = $ld->trang_thai_lao_dong ?? 'chua_co'; @endphp
                                    <span class="badge bg-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }} bg-opacity-10 text-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }}">
                                        {{ $trangThaiLd === 'co_viec_lam' ? 'Có việc làm' : ($trangThaiLd === 'that_nghiep' ? 'Thất nghiệp' : 'Khác') }}
                                    </span>
                                </td>
                                <td>{{ $ld->nganh_nghe ?? '—' }}</td>
                                <td>{{ $ld->loai_hinh_cong_viec ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có dữ liệu lao động.
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
