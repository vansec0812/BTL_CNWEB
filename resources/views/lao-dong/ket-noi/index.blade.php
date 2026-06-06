@extends('layouts.app')

@section('title', 'Kết nối việc làm')
@section('page_title', 'Kết nối việc làm')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-start">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Danh sách kết nối
        </div>
        <h2 class="fw-bold mb-0">Hồ sơ Giới thiệu & Kết nối việc làm</h2>
    </div>
    @can('manage_lao_dong')
    <a href="{{ route('ket-noi.create') }}" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Tạo kết nối mới
    </a>
    @endcan
</div>

{{-- Bộ lọc --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('ket-noi.index') }}" class="row g-3">
            <div class="col-md-5">
                <label for="search" class="form-label small text-secondary fw-semibold">Tìm kiếm</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" placeholder="Nhập tên người lao động hoặc tên doanh nghiệp...">
            </div>
            <div class="col-md-4">
                <label for="ket_qua" class="form-label small text-secondary fw-semibold">Kết quả giới thiệu</label>
                <select id="ket_qua" name="ket_qua" class="form-select form-select-sm">
                    <option value="">Tất cả kết quả</option>
                    @foreach ($ketQua as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['ket_qua'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Lọc kết nối</button>
                <a href="{{ route('ket-noi.index') }}" class="btn btn-sm btn-outline-secondary">Xóa lọc</a>
            </div>
        </form>
    </div>
</div>

{{-- Thống kê nhanh kết nối --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small mb-1">Tổng số lượt giới thiệu</h6>
                    <h3 class="fw-bold mb-0 text-primary">{{ $stats['tong_ket_noi'] ?? 0 }}</h3>
                </div>
                <i class="bi bi-send fs-2 text-primary text-opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small mb-1">Đang chờ phản hồi</h6>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['dang_cho'] ?? 0 }}</h3>
                </div>
                <i class="bi bi-hourglass-split fs-2 text-warning text-opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary small mb-1">Tuyển dụng thành công</h6>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['thanh_cong'] ?? 0 }}</h3>
                </div>
                <i class="bi bi-check-circle fs-2 text-success text-opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Bảng danh sách --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã kết nối</th>
                        <th>Người lao động</th>
                        <th>Doanh nghiệp tuyển dụng</th>
                        <th>Vị trí giới thiệu</th>
                        <th>Ngày kết nối</th>
                        <th>Cán bộ phụ trách</th>
                        <th>Kết quả</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $kn)
                    <tr>
                        <td>KN-{{ str_pad($kn->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <a href="{{ route('ho-so.show', $kn->lao_dong_id) }}" class="fw-bold text-decoration-none">
                                {{ $kn->laoDong->nhanKhau->ho_ten }}
                            </a>
                            <div class="text-muted small">CCCD: {{ $kn->laoDong->nhanKhau->cccd_cmnd ?? '—' }}</div>
                        </td>
                        <td>
                            <a href="{{ route('doanh-nghiep.show', $kn->doanh_nghiep_id) }}" class="fw-semibold text-decoration-none">
                                {{ $kn->doanhNghiep->ten_co_so }}
                            </a>
                            <div class="text-muted small">Ngành: {{ $kn->doanhNghiep->nganh_nghe_chinh }}</div>
                        </td>
                        <td>{{ $kn->vi_tri_gioi_thieu ?? '—' }}</td>
                        <td>{{ $kn->ngay_ket_noi ? $kn->ngay_ket_noi->format('d/m/Y') : '—' }}</td>
                        <td>{{ $kn->nguoiPhuTrach->name ?? 'Hệ thống' }}</td>
                        <td>
                            @php $res = $kn->ket_qua; @endphp
                            <span class="badge bg-{{ $res === 'duoc_nhan' ? 'success' : ($res === 'dang_cho_phan_hoi' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $res === 'duoc_nhan' ? 'success' : ($res === 'dang_cho_phan_hoi' ? 'warning' : 'danger') }} fs-7">
                                {{ $kn->ketQuaLabel() }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                @can('manage_lao_dong')
                                <a href="{{ route('ket-noi.edit', $kn->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa kết quả"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('ket-noi.destroy', $kn->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa kết nối giới thiệu này?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa kết nối"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Chưa thực hiện kết nối giới thiệu việc làm nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($records->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
