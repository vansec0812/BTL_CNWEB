@extends('layouts.app')

@section('title', 'Lực lượng Dân quân tự vệ')
@section('page_title', 'Lực lượng Dân quân tự vệ')

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
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Lực lượng dân quân tự vệ
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Lực lượng Dân quân tự vệ</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý danh sách, chức vụ, đơn vị và trạng thái công tác của lực lượng dân quân tự vệ nòng cốt.</p>
    </div>
    @can('manage_nghia_vu')
    <div class="d-flex gap-2">
        <a href="{{ route('dan-quan-tu-ve.create') }}" class="btn btn-success fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới thành viên
        </a>
    </div>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('status') }}</div>
@endif

{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng lực lượng</p>
                <h4 class="fw-bold mb-0">{{ $stats['tong_so'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đang phục vụ</p>
                <h4 class="fw-bold mb-0 text-primary">{{ $stats['dang_phuc_vu'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đã hoàn thành</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['da_hoan_thanh'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đã rời lực lượng</p>
                <h4 class="fw-bold mb-0 text-danger">{{ $stats['da_roi'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Bộ lọc tìm kiếm -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</div>
    <div class="card-body">
        <form action="{{ route('dan-quan-tu-ve.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" name="q" id="q" class="form-control" placeholder="Tên, CCCD, chức vụ, tổ đội..." value="{{ $filters['q'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach($trangThai as $k => $v)
                        <option value="{{ $k }}" @selected(($filters['trang_thai'] ?? '') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="don_vi" class="form-label">Tổ/Đội dân quân</label>
                <input type="text" name="don_vi" id="don_vi" class="form-control" placeholder="Nhập tên tổ/đội..." value="{{ $filters['don_vi'] ?? '' }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-success w-100">Lọc</button>
                <a href="{{ route('dan-quan-tu-ve.index') }}" class="btn btn-outline-secondary">Xoá</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách dân quân tự vệ nòng cốt</span>
        <span class="badge text-bg-light">{{ $records->total() }} thành viên</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="ps-3">STT</th>
                        <th>Họ tên</th>
                        <th>CCCD/CMND</th>
                        <th>Năm sinh</th>
                        <th>Chức vụ</th>
                        <th>Tổ/đội</th>
                        <th>Ngày gia nhập</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-3" style="width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $index => $row)
                    <tr>
                        <td class="ps-3">{{ $index + 1 + ($records->currentPage() - 1) * $records->perPage() }}</td>
                        <td class="fw-semibold">{{ $row->nhanKhau->ho_ten ?? '—' }}</td>
                        <td>{{ $row->nhanKhau->cccd_cmnd ?? '—' }}</td>
                        <td>{{ $row->nhanKhau->ngay_sinh ? $row->nhanKhau->ngay_sinh->format('Y') : '—' }}</td>
                        <td>{{ $row->chuc_vu ?? 'Chiến sĩ' }}</td>
                        <td>{{ $row->don_vi ?? '—' }}</td>
                        <td>{{ $row->ngay_gia_nhap ? $row->ngay_gia_nhap->format('d/m/Y') : '—' }}</td>
                        <td>
                            @php
                                $color = 'primary';
                                if ($row->trang_thai === 'da_hoan_thanh') { $color = 'success'; }
                                elseif ($row->trang_thai === 'da_roi') { $color = 'danger'; }
                            @endphp
                            <span class="badge bg-{{ $color }} px-2 py-1">{{ $trangThai[$row->trang_thai] ?? $row->trang_thai }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('dan-quan-tu-ve.show', $row->id) }}" class="btn btn-sm btn-action-view" title="Chi tiết"><i class="bi bi-eye"></i></a>
                                @can('manage_nghia_vu')
                                <a href="{{ route('dan-quan-tu-ve.edit', $row->id) }}" class="btn btn-sm btn-action-edit" title="Chỉnh sửa"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('dan-quan-tu-ve.destroy', $row->id) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xoá thành viên {{ $row->nhanKhau->ho_ten ?? '' }} khỏi lực lượng dân quân tự vệ?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action-delete" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Không có kết quả nào phù hợp.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
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
