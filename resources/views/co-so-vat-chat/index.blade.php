@extends('layouts.app')

@section('title', 'Cơ sở vật chất & Hạ tầng')
@section('page_title', 'Cơ sở vật chất')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
            <span class="mx-1">/</span>
            Cơ sở vật chất
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="btn-back" title="Quay lại Tổng quan">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Hạ tầng công cộng</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý đường xá, trường học, trạm y tế, nhà văn hóa và các công trình công cộng khác.</p>
    </div>
    <div class="d-flex gap-2">
        @can('manage_dat_dai')
        <a href="{{ route('co-so-vat-chat.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Thêm công trình
        </a>
        @endcan
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng số công trình</p><h4 class="fw-bold mb-0">{{ number_format($stats['tong_cong_trinh']) }}</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng vốn đầu tư</p><h4 class="fw-bold mb-0 text-success">{{ number_format($stats['tong_von_dau_tu']) }} đ</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Công trình xuống cấp</p><h4 class="fw-bold mb-0 text-danger">{{ number_format($stats['cong_trinh_xuong_cap']) }}</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</div>
    <div class="card-body">
        <form method="GET" action="{{ route('co-so-vat-chat.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="search" id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tên công trình, Thôn xóm...">
            </div>
            <div class="col-lg-3">
                <label for="phan_loai" class="form-label">Phân loại</label>
                <select id="phan_loai" name="phan_loai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach(\App\Models\CoSoVatChat::PHAN_LOAI as $key => $label)
                        <option value="{{ $key }}" @selected(request('phan_loai') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="tinh_trang" class="form-label">Tình trạng</label>
                <select id="tinh_trang" name="tinh_trang" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach(\App\Models\CoSoVatChat::TINH_TRANG as $key => $label)
                        <option value="{{ $key }}" @selected(request('tinh_trang') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('co-so-vat-chat.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách công trình</span>
        <span class="badge text-bg-light">{{ $records->total() }} công trình</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="ps-3">STT</th>
                        <th>Tên công trình</th>
                        <th>Phân loại</th>
                        <th>Vị trí</th>
                        <th class="text-end">Khánh thành</th>
                        <th class="text-end">Kinh phí (VNĐ)</th>
                        <th class="text-center">Tình trạng</th>
                        <th class="text-end pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration + ($records->firstItem() - 1) }}</td>
                            <td>
                                <div class="fw-semibold text-primary">{{ $record->ten_cong_trinh }}</div>
                                @if($record->ghi_chu)
                                    <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $record->ghi_chu }}">{{ $record->ghi_chu }}</div>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $record->phanLoaiLabel() }}</span></td>
                            <td>{{ $record->thon_xom ?? '--' }}</td>
                            <td class="text-end">{{ $record->ngay_dua_vao_su_dung ? $record->ngay_dua_vao_su_dung->format('d/m/Y') : '--' }}</td>
                            <td class="text-end fw-bold">{{ $record->kinh_phi_xay_dung ? number_format($record->kinh_phi_xay_dung) . ' ₫' : '--' }}</td>
                            <td class="text-center">
                                @php
                                    $color = match($record->tinh_trang) {
                                        'tot' => 'success',
                                        'dang_su_dung' => 'primary',
                                        'xuong_cap' => 'warning text-dark',
                                        'can_sua_chua' => 'danger',
                                        'ngung_su_dung' => 'secondary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $color }}">{{ $record->tinhTrangLabel() }}</span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    @can('manage_dat_dai')
                                    <a href="{{ route('co-so-vat-chat.edit', $record) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('co-so-vat-chat.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa công trình này?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action-delete" title="Xóa">
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
                                Chưa có công trình nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($records->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
