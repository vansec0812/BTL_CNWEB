@extends('layouts.app')

@section('title', 'Biến động hộ khẩu')
@section('page_title', 'Biến động hộ khẩu')

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
            Biến động hộ khẩu
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Nghiệp vụ Biến động hộ khẩu</h2>
        </div>
        <p class="text-secondary mb-0">Ghi nhận và quản lý các nghiệp vụ tách hộ, nhập hộ, và thay đổi nhân khẩu (chuyển đi/đến xã).</p>
    </div>
    @can('manage_ho_khau')
    <div class="d-flex gap-2">
        <a href="{{ route('bien-dong.create', ['type' => 'tach_ho']) }}" class="btn btn-success">
            <i class="bi bi-arrow-split me-1"></i> Tách hộ
        </a>
        <a href="{{ route('bien-dong.create', ['type' => 'nhap_ho']) }}" class="btn btn-info text-white">
            <i class="bi bi-box-arrow-in-right me-1"></i> Nhập hộ
        </a>
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-arrow-left-right me-1"></i> Chuyển cư trú
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('bien-dong.create', ['type' => 'chuyen_di']) }}"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Khai báo chuyển đi</a></li>
                <li><a class="dropdown-item" href="{{ route('bien-dong.create', ['type' => 'chuyen_den']) }}"><i class="bi bi-box-arrow-in-left me-2 text-success"></i>Khai báo chuyển đến</a></li>
            </ul>
        </div>
    </div>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng biến động</p><h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4></div></div>
    </div>
    <div class="col">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tách hộ</p><h4 class="fw-bold mb-0 text-success">{{ $stats['tach_ho'] }}</h4></div></div>
    </div>
    <div class="col">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Nhập hộ</p><h4 class="fw-bold mb-0 text-info">{{ $stats['nhap_ho'] }}</h4></div></div>
    </div>
    <div class="col">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Chuyển đi</p><h4 class="fw-bold mb-0 text-danger">{{ $stats['chuyen_di'] }}</h4></div></div>
    </div>
    <div class="col">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Chuyển đến</p><h4 class="fw-bold mb-0 text-primary">{{ $stats['chuyen_den'] }}</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc lịch sử biến động</div>
    <div class="card-body">
        <form method="GET" action="{{ route('bien-dong.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo quyết định, lý do, tên, CCCD...">
            </div>
            <div class="col-lg-3">
                <label for="loai_bien_dong" class="form-label">Loại biến động</label>
                <select id="loai_bien_dong" name="loai_bien_dong" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiBienDong as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_bien_dong'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-label">Khoảng thời gian</label>
                <div class="input-group">
                    <input type="date" name="ngay_bat_dau" value="{{ $filters['ngay_bat_dau'] ?? '' }}" class="form-control" placeholder="Từ ngày">
                    <input type="date" name="ngay_ket_thuc" value="{{ $filters['ngay_ket_thuc'] ?? '' }}" class="form-control" placeholder="Đến ngày">
                </div>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('bien-dong.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách biến động</span>
        <span class="badge text-bg-light">{{ $records->total() }} bản ghi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Loại biến động</th>
                        <th>Nhân khẩu liên quan</th>
                        <th>Hộ nguồn</th>
                        <th>Hộ đích</th>
                        <th>Ngày thực hiện</th>
                        <th>Số quyết định</th>
                        <th>Cán bộ thực hiện</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                @php
                                    $badgeColor = 'secondary';
                                    if ($record->loai_bien_dong === 'tach_ho') $badgeColor = 'success';
                                    elseif ($record->loai_bien_dong === 'nhap_ho') $badgeColor = 'info';
                                    elseif ($record->loai_bien_dong === 'chuyen_di') $badgeColor = 'danger';
                                    elseif ($record->loai_bien_dong === 'chuyen_den') $badgeColor = 'primary';
                                @endphp
                                <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-2.5 py-1.5" style="font-size: 0.85rem;">
                                    {{ $record->loaiLabel() }}
                                </span>
                            </td>
                            <td>
                                @if($record->nhanKhau)
                                    <div class="fw-semibold">{{ $record->nhanKhau->ho_ten }}</div>
                                    <div class="small text-secondary">CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa có' }}</div>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($record->hoKhauNguon)
                                    <div class="fw-semibold">{{ $record->hoKhauNguon->ma_ho }}</div>
                                    <div class="small text-secondary">{{ $record->hoKhauNguon->so_so_ho_khau }}</div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($record->hoKhauDich)
                                    <div class="fw-semibold">{{ $record->hoKhauDich->ma_ho }}</div>
                                    <div class="small text-secondary">{{ $record->hoKhauDich->so_so_ho_khau }}</div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ $record->ngay_bien_dong?->format('d/m/Y') }}</td>
                            <td>{{ $record->so_quyet_dinh ?? '—' }}</td>
                            <td>
                                <div class="small fw-semibold">{{ $record->nguoiThucHien?->name ?? 'Hệ thống' }}</div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('bien-dong.show', $record) }}" class="btn btn-sm btn-action-view d-inline-flex align-items-center gap-1" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                    @can('manage_ho_khau')
                                    <a href="{{ route('bien-dong.edit', $record) }}" class="btn btn-sm btn-action-edit d-inline-flex align-items-center gap-1" title="Chỉnh sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('bien-dong.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa bản ghi lịch sử này?">
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
                                Chưa có dữ liệu lịch sử biến động nào.
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
