@extends('layouts.app')

@section('title', 'Khai báo Tạm trú / Tạm vắng')
@section('page_title', 'Tạm trú / Tạm vắng')

@section('content')
<style>
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
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Tạm trú & Tạm vắng
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Quản lý Tạm trú & Tạm vắng</h2>
        </div>
        <p class="text-secondary mb-0">Theo dõi thông tin tạm trú của người từ nơi khác đến và tạm vắng của công dân trong xã.</p>
    </div>
    @can('manage_ho_khau')
    <a href="{{ route('tam-tru.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Khai báo mới
    </a>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng khai báo</p><h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Đang tạm trú</p><h4 class="fw-bold mb-0 text-success">{{ $stats['tam_tru'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Đang tạm vắng</p><h4 class="fw-bold mb-0 text-info">{{ $stats['tam_vang'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Đã hết hạn</p><h4 class="fw-bold mb-0 text-warning">{{ $stats['het_han'] }}</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc khai báo</div>
    <div class="card-body">
        <form method="GET" action="{{ route('tam-tru.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo lý do, địa chỉ, tên, CCCD...">
            </div>
            <div class="col-lg-3">
                <label for="loai" class="form-label">Loại khai báo</label>
                <select id="loai" name="loai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loai as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('tam-tru.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách hồ sơ</span>
        <span class="badge text-bg-light">{{ $records->total() }} bản ghi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nhân khẩu</th>
                        <th>Loại khai báo</th>
                        <th>Từ ngày</th>
                        <th>Đến ngày</th>
                        <th>Nơi cư trú thực tế/Nơi đến</th>
                        <th>Trạng thái</th>
                        <th>Cán bộ duyệt</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                @if($record->nhanKhau)
                                    <div class="fw-semibold">{{ $record->nhanKhau->ho_ten }}</div>
                                    <div class="small text-secondary">CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                                @else
                                    <span class="text-muted">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $record->loai === 'tam_tru' ? 'success' : 'info' }} bg-opacity-10 text-{{ $record->loai === 'tam_tru' ? 'success' : 'info' }} px-2 py-1">
                                    {{ $record->loaiLabel() }}
                                </span>
                            </td>
                            <td>{{ $record->ngay_bat_dau?->format('d/m/Y') }}</td>
                            <td>
                                @if($record->ngay_ket_thuc)
                                    {{ $record->ngay_ket_thuc->format('d/m/Y') }}
                                @else
                                    <span class="text-muted small">Không thời hạn</span>
                                @endif
                            </td>
                            <td>
                                @if($record->loai === 'tam_tru')
                                    <span class="small d-block text-truncate" style="max-width: 200px;" title="{{ $record->dia_chi_cu_tru_thuc_te }}">{{ $record->dia_chi_cu_tru_thuc_te }}</span>
                                @else
                                    <span class="small d-block text-truncate" style="max-width: 200px;" title="{{ $record->dia_chi_vang_mat }}">{{ $record->dia_chi_vang_mat }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColor = 'secondary';
                                    if ($record->trang_thai === 'dang_hieu_luc') $statusColor = 'success';
                                    elseif ($record->trang_thai === 'da_het_han') $statusColor = 'warning';
                                    elseif ($record->trang_thai === 'da_huy') $statusColor = 'danger';
                                @endphp
                                <span class="badge text-bg-{{ $statusColor }}">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="small text-secondary">{{ $record->nguoiXacNhan?->name ?? 'Hệ thống' }}</div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('tam-tru.show', $record) }}" class="btn btn-sm btn-action-view d-inline-flex align-items-center gap-1" title="Xem">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                    @can('manage_ho_khau')
                                    <a href="{{ route('tam-tru.edit', $record) }}" class="btn btn-sm btn-action-edit d-inline-flex align-items-center gap-1" title="Sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('tam-tru.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa khai báo này?">
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
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có hồ sơ khai báo nào phù hợp.
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
