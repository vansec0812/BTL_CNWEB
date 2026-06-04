@extends('layouts.app')

@section('title', 'Quản lý Nhân khẩu')
@section('page_title', 'Quản lý Nhân khẩu')

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
            Nhân khẩu
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Danh sách nhân khẩu</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý lý lịch cá nhân, định danh cá nhân, trình độ học vấn, tình trạng hôn nhân và quan hệ gia đình.</p>
    </div>
    <a href="{{ route('nhan-khau.create') }}" class="btn btn-success">
        <i class="bi bi-person-plus-fill me-1"></i> Thêm nhân khẩu mới
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng nhân khẩu</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['tong_so'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Nam giới</p>
                <h4 class="fw-bold mb-0 text-primary">{{ $stats['nam'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Nữ giới</p>
                <h4 class="fw-bold mb-0 text-danger">{{ $stats['nu'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Số lượng tạm trú</p>
                <h4 class="fw-bold mb-0 text-info">{{ $stats['tam_tru'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc nhân khẩu</div>
    <div class="card-body">
        <form method="GET" action="{{ route('nhan-khau.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo họ tên, CCCD/CMND, quê quán...">
            </div>
            <div class="col-lg-3">
                <label for="gioi_tinh" class="form-label">Giới tính</label>
                <select id="gioi_tinh" name="gioi_tinh" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($gioiTinh as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['gioi_tinh'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="trang_thai" class="form-label">Trạng thái cư trú</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('nhan-khau.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách nhân khẩu</span>
        <span class="badge text-bg-light">{{ $records->total() }} nhân khẩu</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Họ và tên</th>
                        <th>CCCD/CMND</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Hộ khẩu / Vai trò</th>
                        <th>Tiền án tiền sự</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $record->ho_ten }}</div>
                                @if($record->la_chu_ho)
                                    <span class="badge bg-success bg-opacity-10 text-success small" style="font-size: 0.75rem;">Chủ hộ</span>
                                @endif
                            </td>
                            <td>{{ $record->cccd_cmnd ?? '—' }}</td>
                            <td>{{ $record->ngay_sinh ? $record->ngay_sinh->format('d/m/Y') : '—' }}</td>
                            <td>
                                <span class="badge bg-opacity-10 text-{{ $record->gioi_tinh === 'nam' ? 'primary bg-primary' : ($record->gioi_tinh === 'nu' ? 'danger bg-danger' : 'secondary bg-secondary') }}">
                                    {{ $record->gioiTinhLabel() }}
                                </span>
                            </td>
                            <td>
                                @if($record->hoKhau)
                                    <a href="{{ route('ho-khau.edit', $record->hoKhau) }}" class="text-decoration-none fw-semibold">
                                        {{ $record->hoKhau->ma_ho }}
                                    </a>
                                    @if($record->quan_he_chu_ho)
                                        <div class="small text-secondary">Qh: {{ $record->quan_he_chu_ho }}</div>
                                    @endif
                                @else
                                    <span class="text-muted small">Chưa vào hộ</span>
                                @endif
                            </td>
                            <td>
                                @if($record->co_tien_an)
                                    <span class="badge bg-danger bg-opacity-10 text-danger" title="{{ $record->ghi_chu_tien_an }}">Có</span>
                                @else
                                    <span class="text-muted small">Không</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeVariant = match($record->trang_thai) {
                                        'hoat_dong' => 'success',
                                        'tam_tru' => 'info',
                                        'tam_vang' => 'warning',
                                        'da_chuyen_di' => 'secondary',
                                        'da_mat' => 'danger',
                                        default => 'light',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $badgeVariant }}">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('nhan-khau.show', $record) }}" class="btn btn-sm btn-action-view d-inline-flex align-items-center gap-1" title="Xem">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                    <a href="{{ route('nhan-khau.edit', $record) }}" class="btn btn-sm btn-action-edit d-inline-flex align-items-center gap-1" title="Sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('nhan-khau.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa thông tin nhân khẩu của: {{ $record->ho_ten }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-action-delete d-inline-flex align-items-center gap-1" type="submit" title="Xóa">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có dữ liệu nhân khẩu nào phù hợp.
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
