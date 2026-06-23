@extends('layouts.app')

@section('title', 'Quản lý Đối tượng bảo trợ xã hội')
@section('page_title', 'Quản lý Đối tượng bảo trợ xã hội')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Bảo trợ xã hội
        </div>
        <h2 class="fw-bold mb-1">Danh sách đối tượng bảo trợ xã hội</h2>
        <p class="text-secondary mb-0">Theo dõi hộ nghèo, hộ cận nghèo, người khuyết tật, người già neo đơn và nhóm hoàn cảnh khó khăn.</p>
    </div>
    @can('manage_an_sinh')
    <a href="{{ route('bao-tro-xa-hoi.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Thêm hồ sơ
    </a>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng hồ sơ</p><h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Đang hưởng</p><h4 class="fw-bold mb-0 text-success">{{ $stats['dang_huong'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Hộ nghèo/cận nghèo</p><h4 class="fw-bold mb-0 text-warning">{{ $stats['ho_ngheo_can_ngheo'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Trợ cấp hằng tháng</p><h4 class="fw-bold mb-0">{{ number_format($stats['tong_tro_cap'], 0, ',', '.') }}đ</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc hồ sơ</div>
    <div class="card-body">
        <form method="GET" action="{{ route('bao-tro-xa-hoi.index') }}" class="row g-3">
            <div class="col-lg-3">
                <label for="q" class="form-label">Tìm theo hộ, nhân khẩu hoặc quyết định</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Ví dụ: QĐ-UBND-017">
            </div>
            <div class="col-lg-3">
                <label for="loai_bao_tro" class="form-label">Loại bảo trợ</label>
                <select id="loai_bao_tro" name="loai_bao_tro" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiBaoTro as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_bao_tro'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label for="doi_tuong" class="form-label">Đối tượng</label>
                <select id="doi_tuong" name="doi_tuong" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="ho_khau" @selected(($filters['doi_tuong'] ?? '') === 'ho_khau')>Hộ khẩu</option>
                    <option value="nhan_khau" @selected(($filters['doi_tuong'] ?? '') === 'nhan_khau')>Nhân khẩu</option>
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
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('bao-tro-xa-hoi.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Hồ sơ bảo trợ xã hội</span>
        <span class="badge text-bg-light">{{ $records->total() }} hồ sơ</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Đối tượng</th>
                        <th>Loại bảo trợ</th>
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
                                <div class="fw-semibold">{{ $record->doiTuongLabel() }}</div>
                                <div class="small text-secondary">
                                    @if ($record->ho_khau_id)
                                        {{ $record->hoKhau?->dia_chi_thuong_tru ?? 'Chưa cập nhật địa chỉ' }}
                                    @else
                                        CCCD: {{ $record->nhanKhau?->cccd_cmnd ?? 'Chưa cập nhật' }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{ $record->loaiLabel() }}
                                @if ($record->loai_bao_tro === 'nguoi_khuyet_tat')
                                    <div class="small text-secondary">{{ $record->mucDoKhuyetTatLabel() }}{{ $record->dang_khuyet_tat ? ' - '.$record->dang_khuyet_tat : '' }}</div>
                                @endif
                            </td>
                            <td>{{ $record->so_quyet_dinh ?? 'Chưa có số quyết định' }}</td>
                            <td>{{ number_format($record->muc_tro_cap_hang_thang ?? 0, 0, ',', '.') }}đ</td>
                            <td><span class="badge text-bg-{{ $record->trang_thai === 'dang_huong' ? 'success' : ($record->trang_thai === 'tam_ngung' ? 'warning' : 'secondary') }}">{{ $record->trangThaiLabel() }}</span></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('bao-tro-xa-hoi.show', $record) }}" class="btn btn-sm btn-outline-secondary" title="Xem"><i class="bi bi-eye"></i> Xem</a>
                                    @can('manage_an_sinh')
                                    <a href="{{ route('bao-tro-xa-hoi.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="Sửa"><i class="bi bi-pencil-square"></i> Sửa</a>
                                    <form method="POST" action="{{ route('bao-tro-xa-hoi.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ này?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Xóa"><i class="bi bi-trash"></i> Xóa</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có hồ sơ bảo trợ xã hội phù hợp.
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
