@extends('layouts.app')

@section('title', 'Quản lý Diện chính sách')
@section('page_title', 'Quản lý Diện chính sách')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Diện chính sách
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <!-- <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a> -->
            <h2 class="fw-bold mb-0">Danh sách diện chính sách</h2>
        </div>
        <p class="text-secondary mb-0">Theo dõi thương binh, bệnh binh, thân nhân liệt sĩ và người có công với cách mạng.</p>
    </div>
    @can('manage_an_sinh')
    <a href="{{ route('doi-tuong-chinh-sach.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Thêm hồ sơ
    </a>
    @endcan
</div>

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
            <div class="col-lg-3">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo họ tên, CCCD hoặc số quyết định...">
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
            <div class="col-lg-3 d-flex align-items-end gap-2">
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
                        <th style="width: 50px;">STT</th>
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
                            <td>{{ $loop->iteration + ($records->firstItem() - 1) }}</td>
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
                                    <a href="{{ route('doi-tuong-chinh-sach.show', $record) }}" class="btn btn-sm btn-action-view" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('manage_an_sinh')
                                    <a href="{{ route('doi-tuong-chinh-sach.edit', $record) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('doi-tuong-chinh-sach.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ này?">
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
                            <td colspan="7" class="text-center text-muted py-5">
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
        <div class="card-footer bg-white border-top py-3">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
