@extends('layouts.app')

@section('title', 'Quản lý Đất đai & Tài sản')
@section('page_title', 'Quản lý Đất đai & Tài sản')

@section('content')
<style>
    /* Nút quay lại (Back arrow button) */
    .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: #6c757d;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        color: var(--admin-green);
        background-color: var(--admin-green-soft);
        border-color: rgba(15, 81, 50, 0.2);
        transform: translateX(-2px);
    }

    /* Style cho các nút hành động (Edit/Delete) */
    .btn-action-view {
        background-color: rgba(108, 117, 125, 0.08);
        color: #6c757d;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-action-view:hover {
        background-color: #6c757d;
        color: #ffffff;
    }
    .btn-action-edit {
        background-color: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-action-edit:hover {
        background-color: #0d6efd;
        color: #ffffff;
    }
    .btn-action-delete {
        background-color: rgba(220, 53, 69, 0.08);
        color: #dc3545;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-action-delete:hover {
        background-color: #dc3545;
        color: #ffffff;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
            <span class="mx-1">/</span>
            Đất đai & Tài sản
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="btn-back" title="Quay lại Tổng quan">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Danh sách Thửa đất</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý thửa đất, diện tích, GCN QSDĐ và tài sản gắn liền với đất.</p>
    </div>
    @can('manage_dat_dai')
    <a href="{{ route('dat-dai-tai-san.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Thêm thửa đất
    </a>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng số thửa đất</p><h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Đất thổ cư</p><h4 class="fw-bold mb-0 text-success">{{ $stats['dat_tho_cu'] }}</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Đất nông nghiệp</p><h4 class="fw-bold mb-0 text-warning">{{ $stats['dat_nong_nghiep'] }}</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</div>
    <div class="card-body">
        <form method="GET" action="{{ route('dat-dai-tai-san.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tên chủ hộ, Số GCN, số tờ, số thửa...">
            </div>
            <div class="col-lg-3">
                <label for="loai_dat" class="form-label">Loại đất</label>
                <select id="loai_dat" name="loai_dat" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="dat_tho_cu" @selected(request('loai_dat') === 'dat_tho_cu')>Đất thổ cư</option>
                    <option value="dat_nong_nghiep" @selected(request('loai_dat') === 'dat_nong_nghiep')>Đất nông nghiệp</option>
                    <option value="dat_lam_nghiep" @selected(request('loai_dat') === 'dat_lam_nghiep')>Đất lâm nghiệp</option>
                    <option value="dat_nuoi_trong_thuy_san" @selected(request('loai_dat') === 'dat_nuoi_trong_thuy_san')>Đất nuôi trồng thủy sản</option>
                    <option value="dat_kinh_doanh" @selected(request('loai_dat') === 'dat_kinh_doanh')>Đất kinh doanh</option>
                </select>
            </div>
            <div class="col-lg-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="dang_su_dung" @selected(request('trang_thai') === 'dang_su_dung')>Đang sử dụng</option>
                    <option value="cho_thue" @selected(request('trang_thai') === 'cho_thue')>Cho thuê</option>
                    <option value="bi_tranh_chap" @selected(request('trang_thai') === 'bi_tranh_chap')>Bị tranh chấp</option>
                    <option value="da_chuyen_nhuong" @selected(request('trang_thai') === 'da_chuyen_nhuong')>Đã chuyển nhượng</option>
                    <option value="thu_hoi" @selected(request('trang_thai') === 'thu_hoi')>Thu hồi</option>
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('dat-dai-tai-san.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách thửa đất</span>
        <span class="badge text-bg-light">{{ $records->total() }} thửa</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Hộ sở hữu</th>
                        <th>Thông tin thửa đất</th>
                        <th>Loại đất</th>
                        <th>Diện tích</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                @if($record->hoKhau && $record->hoKhau->chuHo)
                                    <div class="fw-semibold">{{ $record->hoKhau->chuHo->ho_ten }}</div>
                                    <div class="small text-secondary">Hộ: {{ $record->hoKhau->ma_ho }}</div>
                                @else
                                    <span class="text-muted small">Chưa xác định</span>
                                @endif
                            </td>
                            <td>
                                <div><span class="badge bg-secondary mb-1">GCN: {{ $record->so_gcn_qsdd ?? 'Chưa cấp' }}</span></div>
                                <div class="small">Tờ BĐ: {{ $record->so_to_ban_do ?? '-' }} | Thửa: {{ $record->so_thua_dat ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">{{ $record->loaiDatLabel() }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ number_format($record->dien_tich_m2, 2, ',', '.') }} m²</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($record->trang_thai) {
                                        'dang_su_dung' => 'success',
                                        'cho_thue' => 'primary',
                                        'bi_tranh_chap', 'thu_hoi' => 'danger',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $statusClass }}">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    @can('manage_dat_dai')
                                    <a href="{{ route('dat-dai-tai-san.edit', $record) }}" class="btn btn-sm btn-action-edit d-inline-flex align-items-center gap-1" title="Sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('dat-dai-tai-san.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa thửa đất này?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-action-delete d-inline-flex align-items-center gap-1" type="submit" title="Xóa">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có dữ liệu thửa đất.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($records->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="small text-secondary">Trang {{ $records->currentPage() }} / {{ $records->lastPage() }}</span>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-outline-secondary {{ $records->onFirstPage() ? 'disabled' : '' }}" href="{{ $records->previousPageUrl() ?? '#' }}">Trước</a>
                <a class="btn btn-outline-secondary {{ $records->hasMorePages() ? '' : 'disabled' }}" href="{{ $records->nextPageUrl() ?? '#' }}">Sau</a>
            </div>
        </div>
    @endif
</div>
@endsection
