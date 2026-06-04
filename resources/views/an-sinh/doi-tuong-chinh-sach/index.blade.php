@extends('layouts.app')

@section('title', 'Quản lý Diện chính sách')
@section('page_title', 'Quản lý Diện chính sách')

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
            Diện chính sách
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Danh sách diện chính sách</h2>
        </div>
        <p class="text-secondary mb-0">Theo dõi thương binh, bệnh binh, thân nhân liệt sĩ và người có công với cách mạng.</p>
    </div>
    <a href="{{ route('doi-tuong-chinh-sach.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Thêm hồ sơ
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng hồ sơ</p><h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Đang hưởng chế độ</p><h4 class="fw-bold mb-0 text-success">{{ $stats['dang_huong'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Cần rà soát quyết định</p><h4 class="fw-bold mb-0 text-warning">{{ $stats['can_ra_soat'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Trợ cấp hằng tháng</p><h4 class="fw-bold mb-0">{{ number_format($stats['tong_tro_cap'], 0, ',', '.') }}đ</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc hồ sơ</div>
    <div class="card-body">
        <form method="GET" action="{{ route('doi-tuong-chinh-sach.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="q" class="form-label">Tìm theo họ tên, CCCD hoặc số quyết định</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Ví dụ: Võ Thị Cúc">
            </div>
            <div class="col-lg-3">
                <label for="loai_chinh_sach" class="form-label">Loại chính sách</label>
                <select id="loai_chinh_sach" name="loai_chinh_sach" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiChinhSach as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_chinh_sach'] ?? '') === $value)>{{ $label }}</option>
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
                <a class="btn btn-outline-secondary" href="{{ route('doi-tuong-chinh-sach.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Hồ sơ diện chính sách</span>
        <span class="badge text-bg-light">{{ $records->total() }} hồ sơ</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nhân khẩu</th>
                        <th>Loại chính sách</th>
                        <th>Quyết định</th>
                        <th>Trợ cấp/tháng</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $record->nhanKhau->ho_ten }}</div>
                                <div class="small text-secondary">CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td>{{ $record->loaiLabel() }}</td>
                            <td>
                                <div>{{ $record->so_quyet_dinh_cong_nhan ?? 'Chưa có số quyết định' }}</div>
                                <div class="small text-secondary">{{ $record->co_quan_cap ?? 'Chưa cập nhật cơ quan cấp' }}</div>
                            </td>
                            <td>{{ number_format($record->muc_tro_cap_hang_thang ?? 0, 0, ',', '.') }}đ</td>
                            <td><span class="badge text-bg-{{ $record->trang_thai === 'dang_huong_che_do' ? 'success' : ($record->trang_thai === 'ngung_huong' ? 'warning' : 'secondary') }}">{{ $record->trangThaiLabel() }}</span></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('doi-tuong-chinh-sach.edit', $record) }}" class="btn btn-sm btn-action-edit d-inline-flex align-items-center gap-1" title="Sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('doi-tuong-chinh-sach.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ này?">
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
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có hồ sơ diện chính sách phù hợp.
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
