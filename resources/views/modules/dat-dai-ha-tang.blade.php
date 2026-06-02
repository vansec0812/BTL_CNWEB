@extends('layouts.app')

@section('title', 'Đất đai, Hạ tầng & Tài sản hộ dân')
@section('page_title', 'Đất đai, Hạ tầng & Tài sản hộ dân')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-map stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Thửa đất đang quản lý</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['dat_dai'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-signpost-2 stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Đất thổ cư</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['dat_tho_cu'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-tree stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Đất nông nghiệp</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['dat_nong_nghiep'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-cash-coin stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Thuế &amp; phí địa phương</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($stats['tong_thue_phi'] ?? 0) }} đ</h4>
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
                        <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-1"><i class="bi bi-plus-lg small"></i></span>
                        Thêm thửa đất mới
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-1"><i class="bi bi-pencil small"></i></span>
                        Cập nhật thông tin đất
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-1"><i class="bi bi-cash-coin small"></i></span>
                        Quản lý thuế &amp; phí
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-1"><i class="bi bi-building small"></i></span>
                        Số nhà &amp; hạ tầng
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-1"><i class="bi bi-file-spreadsheet small"></i></span>
                        Báo cáo thuế theo kỳ
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-table me-1"></i>Danh sách thửa đất</span>
                <button class="btn btn-sm btn-outline-info"><i class="bi bi-plus-lg"></i> Thêm thửa đất</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Mã hộ</th>
                                <th>Số tờ bản đồ</th>
                                <th>Số thửa</th>
                                <th>Loại đất</th>
                                <th>Diện tích (m²)</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dsDatDai ?? [] as $dd)
                            <tr>
                                <td class="fw-semibold">{{ $dd->ma_ho ?? $dd->ho_khau_id }}</td>
                                <td>{{ $dd->so_to_ban_do ?? '—' }}</td>
                                <td>{{ $dd->so_thua_dat ?? '—' }}</td>
                                <td>{{ $dd->loai_dat === 'dat_tho_cu' ? 'Thổ cư' : ($dd->loai_dat === 'dat_nong_nghiep' ? 'Nông nghiệp' : 'Khác') }}</td>
                                <td>{{ number_format($dd->dien_tich_m2 ?? 0, 1) }}</td>
                                <td>
                                    @php $ttDat = $dd->trang_thai ?? 'dang_su_dung'; @endphp
                                    <span class="badge bg-{{ $ttDat === 'dang_su_dung' ? 'success' : ($ttDat === 'cho_thue' ? 'info' : 'danger') }}">
                                        {{ $ttDat === 'dang_su_dung' ? 'Đang sử dụng' : ($ttDat === 'cho_thue' ? 'Cho thuê' : 'Tranh chấp') }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có dữ liệu đất đai.
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
