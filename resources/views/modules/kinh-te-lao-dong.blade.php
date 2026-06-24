@extends('layouts.app')

@section('title', 'Kinh tế, Lao động & Việc làm')
@section('page_title', 'Kinh tế, Lao động & Việc làm')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people stat-icon text-primary bg-primary bg-opacity-10 p-3 rounded-3 fs-3"></i>
                <div>
                    <p class="text-muted small mb-0">Hồ sơ lao động</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['lao_dong'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-building stat-icon text-success bg-success bg-opacity-10 p-3 rounded-3 fs-3"></i>
                <div>
                    <p class="text-muted small mb-0">Doanh nghiệp / HKD</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['doanh_nghiep'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-graph-up-arrow stat-icon text-info bg-info bg-opacity-10 p-3 rounded-3 fs-3"></i>
                <div>
                    <p class="text-muted small mb-0">Kết nối việc làm</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['ket_noi_viec_lam'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-airplane stat-icon text-warning bg-warning bg-opacity-10 p-3 rounded-3 fs-3"></i>
                <div>
                    <p class="text-muted small mb-0">Xuất khẩu lao động</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['xuat_khau_lao_dong'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3"><i class="bi bi-grid-fill me-2 text-primary"></i>Danh mục Quản lý</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('ho-so.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2"><i class="bi bi-person-badge"></i></span>
                            <span>Hồ sơ lao động dân cư</span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('doanh-nghiep.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-2"><i class="bi bi-building"></i></span>
                            <span>Doanh nghiệp & Hộ kinh doanh</span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('ket-noi.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-2"><i class="bi bi-link-45deg"></i></span>
                            <span>Giới thiệu & Kết nối việc làm</span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>

        @can('manage_lao_dong')
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3"><i class="bi bi-plus-circle-fill me-2 text-success"></i>Thao tác nhanh</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('ho-so.create') }}" class="btn btn-outline-primary text-start d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Thêm hồ sơ lao động mới
                </a>
                <a href="{{ route('doanh-nghiep.create') }}" class="btn btn-outline-success text-start d-flex align-items-center gap-2">
                    <i class="bi bi-building-add"></i> Đăng ký doanh nghiệp mới
                </a>
                <a href="{{ route('ket-noi.create') }}" class="btn btn-outline-info text-start d-flex align-items-center gap-2">
                    <i class="bi bi-briefcase"></i> Tạo kết nối việc làm
                </a>
            </div>
        </div>
        @endcan
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Hồ sơ lao động mới cập nhật</span>
                <a href="{{ route('ho-so.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã HS</th>
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
                                <td>HS-{{ str_pad($ld->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-semibold">{{ $ld->ho_ten ?? '—' }}</td>
                                <td>
                                    @php $trangThaiLd = $ld->trang_thai_lao_dong; @endphp
                                    <span class="badge bg-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }} bg-opacity-10 text-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }}">
                                        {{ $ld->trangThaiLabel() }}
                                    </span>
                                </td>
                                <td>{{ $ld->nganhNgheLabel() }}</td>
                                <td>{{ $ld->loaiHinhLabel() }}</td>
                                <td>
                                    <a href="{{ route('ho-so.show', $ld->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> Chi tiết</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có dữ liệu lao động nào được ghi nhận.
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
