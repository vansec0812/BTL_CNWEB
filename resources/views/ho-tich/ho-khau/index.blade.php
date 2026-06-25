@extends('layouts.app')

@section('title', 'Quản lý Sổ hộ khẩu')
@section('page_title', 'Quản lý Sổ hộ khẩu')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Sổ hộ khẩu
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Danh sách sổ hộ khẩu</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý mã hộ, số sổ, thông tin chủ hộ, địa chỉ và phân loại cư trú.</p>
    </div>
    @can('manage_ho_khau')
    <a href="{{ route('ho-khau.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Thêm sổ hộ khẩu
    </a>
    @endcan
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng số hộ</p><h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Thường trú</p><h4 class="fw-bold mb-0 text-success">{{ $stats['thuong_tru'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tạm trú</p><h4 class="fw-bold mb-0 text-info">{{ $stats['tam_tru'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tạm vắng</p><h4 class="fw-bold mb-0 text-warning">{{ $stats['tam_vang'] }}</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc hộ khẩu</div>
    <div class="card-body">
        <form method="GET" action="{{ route('ho-khau.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo mã hộ, số sổ, địa chỉ, tên chủ hộ...">
            </div>
            <div class="col-lg-3">
                <label for="phan_loai" class="form-label">Phân loại</label>
                <select id="phan_loai" name="phan_loai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($phanLoai as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['phan_loai'] ?? '') === $value)>{{ $label }}</option>
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
                <a class="btn btn-outline-secondary" href="{{ route('ho-khau.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách hộ khẩu</span>
        <span class="badge text-bg-light">{{ $records->total() }} hộ khẩu</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Mã hộ</th>
                        <th>Số sổ hộ khẩu</th>
                        <th>Chủ hộ</th>
                        <th>Địa chỉ</th>
                        <th>Thành viên</th>
                        <th>Phân loại</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration + ($records->firstItem() - 1) }}</td>
                            <td class="fw-semibold text-success">{{ $record->ma_ho }}</td>
                            <td>{{ $record->so_so_ho_khau }}</td>
                            <td>
                                @if($record->chuHo)
                                    <div class="fw-semibold">{{ $record->chuHo->ho_ten }}</div>
                                    <div class="small text-secondary">CCCD: {{ $record->chuHo->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                                @else
                                    <span class="text-muted small">Chưa xác định chủ hộ</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $record->dia_chi_thuong_tru }}</div>
                                <div class="small text-secondary">Thôn: {{ $record->thon_xom ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="badge text-bg-light">{{ $record->so_thanh_vien }} người</span>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">{{ $record->phanLoaiLabel() }}</span>
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $record->trang_thai === 'hoat_dong' ? 'success' : ($record->trang_thai === 'da_giai_the' ? 'danger' : 'warning') }}">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('ho-khau.show', $record) }}" class="btn btn-sm btn-action-view" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('manage_ho_khau')
                                    <a href="{{ route('ho-khau.edit', $record) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('ho-khau.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa sổ hộ khẩu này?">
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
                                Chưa có sổ hộ khẩu nào phù hợp.
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
