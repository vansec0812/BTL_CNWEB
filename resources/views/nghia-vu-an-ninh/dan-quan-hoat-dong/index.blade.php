@extends('layouts.app')

@section('title', 'Quản lý Hoạt động Dân quân')
@section('page_title', 'Quản lý Hoạt động Dân quân')

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
</style>

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Hoạt động dân quân
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Hoạt động Dân quân tự vệ</h2>
        </div>
        <p class="text-secondary mb-0">Theo dõi phân lịch trực ban, các khóa tập huấn quân sự và tình trạng tham gia của lực lượng dân quân.</p>
    </div>
    @can('manage_nghia_vu')
    <div class="d-flex gap-2">
        <a href="{{ route('dan-quan-hoat-dong.create') }}" class="btn btn-success fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới hoạt động
        </a>
    </div>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('status') }}</div>
@endif

<!-- Thống kê nhanh -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng số hoạt động</p>
                <h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tập huấn quân sự</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['tap_huan'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Ca trực ban</p>
                <h4 class="fw-bold mb-0 text-primary">{{ $stats['truc_ban'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Vắng mặt (Tập huấn / Trực)</p>
                <h4 class="fw-bold mb-0 text-danger">{{ $stats['vang'] }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Bộ lọc tìm kiếm -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</div>
    <div class="card-body">
        <form method="GET" action="{{ route('dan-quan-hoat-dong.index') }}" class="row g-3">
            <div class="col-lg-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="search" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Tên hoạt động, tên/CCCD dân quân...">
            </div>
            <div class="col-lg-3">
                <label for="loai_hoat_dong" class="form-label">Loại hoạt động</label>
                <select id="loai_hoat_dong" name="loai_hoat_dong" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiHoatDong as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_hoat_dong'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label for="don_vi" class="form-label">Đơn vị</label>
                <input type="text" id="don_vi" name="don_vi" value="{{ $filters['don_vi'] ?? '' }}" class="form-control" placeholder="Tên đơn vị...">
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('dan-quan-hoat-dong.index') }}">Xoá</a>
            </div>
        </form>
    </div>
</div>

<!-- Bảng hiển thị -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách hoạt động dân quân</span>
        <span class="badge text-bg-light">{{ $records->total() }} bản ghi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Họ tên dân quân</th>
                        <th>Chức vụ / Đơn vị</th>
                        <th>Loại hoạt động</th>
                        <th>Tên hoạt động</th>
                        <th>Ngày thực hiện</th>
                        <th>Trạng thái</th>
                        <th class="text-end" style="width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
                            <td>
                                <div class="fw-semibold">{{ $record->danQuanTuVe->nhanKhau->ho_ten }}</div>
                                <div class="small text-secondary">CCCD: {{ $record->danQuanTuVe->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td>
                                <div>{{ $record->danQuanTuVe->chuc_vu }}</div>
                                <div class="small text-secondary">{{ $record->danQuanTuVe->don_vi ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $record->loai_hoat_dong === 'tap_huan' ? 'info' : 'primary' }}">
                                    {{ $loaiHoatDong[$record->loai_hoat_dong] ?? '—' }}
                                </span>
                            </td>
                            <td><span class="fw-medium">{{ $record->ten_hoat_dong }}</span></td>
                            <td>{{ $record->ngay_thuc_hien ? $record->ngay_thuc_hien->format('d/m/Y') : '—' }}</td>
                            <td>
                                @php
                                    $color = 'secondary';
                                    if (in_array($record->trang_thai, ['tham_gia', 'da_truc'])) $color = 'success';
                                    elseif ($record->trang_thai === 'vang_co_phep') $color = 'warning';
                                    elseif (in_array($record->trang_thai, ['vang_khong_phep', 'vang_mat'])) $color = 'danger';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ $trangThai[$record->trang_thai] ?? $record->trang_thai }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('dan-quan-hoat-dong.show', $record) }}" class="btn btn-sm btn-outline-secondary" title="Chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('manage_nghia_vu')
                                    <a href="{{ route('dan-quan-hoat-dong.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('dan-quan-hoat-dong.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hoạt động này của dân quân?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có hoạt động dân quân nào được ghi nhận.
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
