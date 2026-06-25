@extends('layouts.app')

@section('title', 'Kết nối việc làm')
@section('page_title', 'Kết nối việc làm')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Kết nối việc làm
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Hồ sơ Giới thiệu & Kết nối việc làm</h2>
        </div>
        <p class="text-secondary mb-0">Theo dõi quá trình kết nối, giới thiệu việc làm giữa người lao động và doanh nghiệp tuyển dụng địa phương.</p>
    </div>
    @can('manage_lao_dong')
    <a href="{{ route('ket-noi.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Tạo kết nối mới
    </a>
    @endcan
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Tổng lượt giới thiệu</p>
                    <h4 class="fw-bold mb-0 text-primary">{{ $stats['tong_ket_noi'] ?? 0 }}</h4>
                </div>
                <i class="bi bi-send fs-2 text-primary text-opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Đang chờ phản hồi</p>
                    <h4 class="fw-bold mb-0 text-warning">{{ $stats['dang_cho'] ?? 0 }}</h4>
                </div>
                <i class="bi bi-hourglass-split fs-2 text-warning text-opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Tuyển dụng thành công</p>
                    <h4 class="fw-bold mb-0 text-success">{{ $stats['thanh_cong'] ?? 0 }}</h4>
                </div>
                <i class="bi bi-check-circle fs-2 text-success text-opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc kết nối</div>
    <div class="card-body">
        <form method="GET" action="{{ route('ket-noi.index') }}" class="row g-3">
            <div class="col-lg-5">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="search" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Tên người lao động hoặc tên doanh nghiệp...">
            </div>
            <div class="col-lg-4">
                <label for="ket_qua" class="form-label">Kết quả giới thiệu</label>
                <select id="ket_qua" name="ket_qua" class="form-select">
                    <option value="">Tất cả kết quả</option>
                    @foreach ($ketQua as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['ket_qua'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('ket-noi.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách kết nối việc làm</span>
        <span class="badge text-bg-light">{{ $records->total() }} bản ghi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Mã kết nối</th>
                        <th>Người lao động</th>
                        <th>Doanh nghiệp tuyển dụng</th>
                        <th>Vị trí giới thiệu</th>
                        <th>Ngày kết nối</th>
                        <th>Cán bộ phụ trách</th>
                        <th>Kết quả</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $kn)
                    <tr>
                        <td>{{ $loop->iteration + ($records->firstItem() - 1) }}</td>
                        <td class="fw-semibold text-success">KN-{{ str_pad($kn->id, 4, '0', STR_PAD_LEFT) }}</td>
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
                        <td>
                            <div class="small fw-semibold">{{ $kn->nguoiPhuTrach->name ?? 'Hệ thống' }}</div>
                        </td>
                        <td>
                            @php $res = $kn->ket_qua; @endphp
                            <span class="badge bg-{{ $res === 'duoc_nhan' ? 'success' : ($res === 'dang_cho_phan_hoi' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $res === 'duoc_nhan' ? 'success' : ($res === 'dang_cho_phan_hoi' ? 'warning' : 'danger') }}">
                                {{ $kn->ketQuaLabel() }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('ket-noi.show', $kn->id) }}" class="btn btn-sm btn-action-view" title="Xem">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('manage_lao_dong')
                                <a href="{{ route('ket-noi.edit', $kn->id) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('ket-noi.destroy', $kn->id) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa kết nối giới thiệu này?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-action-delete" type="submit" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
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
        <div class="card-footer bg-white border-top py-3">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
