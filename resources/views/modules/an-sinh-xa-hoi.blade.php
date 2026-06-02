@extends('layouts.app')

@section('title', 'An sinh xã hội, Y tế & Giáo dục')
@section('page_title', 'An sinh xã hội, Y tế & Giáo dục')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-award stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Đối tượng chính sách</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['doi_tuong_chinh_sach'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-heartbreak stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Bảo trợ xã hội</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['bao_tro_xa_hoi'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-gift stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Đợt trợ cấp</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['dot_tro_cap'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-heart-pulse stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Hồ sơ y tế</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['y_te'] ?? 0 }}</h4>
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
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1"><i class="bi bi-plus-lg small"></i></span>
                        Thêm đối tượng chính sách
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1"><i class="bi bi-heartbreak small"></i></span>
                        Quản lý bảo trợ xã hội
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1"><i class="bi bi-gift small"></i></span>
                        Tạo đợt trợ cấp
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1"><i class="bi bi-check2-square small"></i></span>
                        Ghi nhận đã nhận trợ cấp
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1"><i class="bi bi-heart-pulse small"></i></span>
                        Theo dõi y tế &amp; BHYT
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-1"><i class="bi bi-mortarboard small"></i></span>
                        Giáo dục &amp; tiêm chủng
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table me-1"></i>Đợt trợ cấp gần đây</span>
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-plus-lg"></i> Tạo đợt mới</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tên đợt trợ cấp</th>
                                <th>Loại</th>
                                <th>Tổng đối tượng</th>
                                <th>Đã nhận</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dsDotTroCap ?? [] as $dot)
                            <tr>
                                <td class="fw-semibold">{{ $dot->ten_dot }}</td>
                                <td>{{ $dot->loai_tro_cap === 'tien_mat' ? 'Tiền mặt' : ($dot->loai_tro_cap === 'hien_vat' ? 'Hiện vật' : 'Kết hợp') }}</td>
                                <td>{{ $dot->tong_so_doi_tuong }}</td>
                                <td>{{ $dot->so_da_nhan }}</td>
                                <td>
                                    @php $tt = $dot->trang_thai ?? 'sap_dien_ra'; @endphp
                                    <span class="badge bg-{{ $tt === 'hoan_thanh' ? 'success' : ($tt === 'dang_thuc_hien' ? 'warning' : 'secondary') }}">
                                        {{ $tt === 'hoan_thanh' ? 'Hoàn thành' : ($tt === 'dang_thuc_hien' ? 'Đang thực hiện' : ($tt === 'huy_bo' ? 'Hủy bỏ' : 'Sắp diễn ra')) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có đợt trợ cấp nào.
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
