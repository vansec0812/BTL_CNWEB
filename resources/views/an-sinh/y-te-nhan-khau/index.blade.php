@extends('layouts.app')

@section('title', 'Quản lý Y tế & Bảo hiểm y tế')
@section('page_title', 'Quản lý Y tế & Bảo hiểm y tế')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Y tế & BHYT
        </div>
        <h2 class="fw-bold mb-1">Hồ sơ Y tế & Bảo hiểm y tế</h2>
        <p class="text-secondary mb-0">Theo dõi thẻ BHYT, tình trạng tiêm chủng mở rộng và ghi chú sức khỏe của từng nhân khẩu.</p>
    </div>
    @can('manage_an_sinh')
    <a href="{{ route('y-te-nhan-khau.create') }}" class="btn btn-info text-white">
        <i class="bi bi-plus-lg me-1"></i> Thêm hồ sơ y tế
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
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Có BHYT</p><h4 class="fw-bold mb-0 text-info">{{ $stats['co_bhyt'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Thẻ hết hạn</p><h4 class="fw-bold mb-0 text-danger">{{ $stats['het_han'] }}</h4></div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Hoàn thành tiêm chủng</p><h4 class="fw-bold mb-0 text-success">{{ $stats['da_tiem_chung'] }}</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc hồ sơ</div>
    <div class="card-body">
        <form method="GET" action="{{ route('y-te-nhan-khau.index') }}" class="row g-3">
            <div class="col-lg-3">
                <label for="q" class="form-label">Tìm theo tên/CCCD/số thẻ BHYT</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Ví dụ: Nguyễn Văn A">
            </div>
            <div class="col-lg-3">
                <label for="loai_bhyt" class="form-label">Loại BHYT</label>
                <select id="loai_bhyt" name="loai_bhyt" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiBhyt as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_bhyt'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label for="tiem_chung" class="form-label">Tiêm chủng mở rộng</label>
                <select id="tiem_chung" name="tiem_chung" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(($filters['tiem_chung'] ?? '') === '1')>Đã hoàn thành</option>
                    <option value="0" @selected(($filters['tiem_chung'] ?? '') === '0')>Chưa hoàn thành</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="het_han" class="form-label">Trạng thái thẻ</label>
                <select id="het_han" name="het_han" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(($filters['het_han'] ?? '') === '1')>Thẻ đã hết hạn</option>
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-info text-white w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('y-te-nhan-khau.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-heart-pulse me-1"></i>Hồ sơ y tế nhân khẩu</span>
        <span class="badge text-bg-light">{{ $records->total() }} hồ sơ</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nhân khẩu</th>
                        <th>Số thẻ BHYT</th>
                        <th>Loại BHYT</th>
                        <th>Hạn thẻ</th>
                        <th>Tiêm chủng</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $record->nhanKhau?->ho_ten ?? '—' }}</div>
                                <div class="small text-secondary">CCCD: {{ $record->nhanKhau?->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td>{{ $record->so_the_bhyt ?? '—' }}</td>
                            <td>
                                <span class="badge text-bg-{{ $record->loai_bhyt === 'khong_co' ? 'secondary' : 'info' }}">
                                    {{ $record->loaiBhytLabel() }}
                                </span>
                            </td>
                            <td>
                                @if ($record->loai_bhyt === 'khong_co')
                                    <span class="text-secondary small">Không có thẻ</span>
                                @elseif ($record->ngay_het_han_the_bhyt)
                                    <span class="badge text-bg-{{ $record->isTheBhytConHan() ? 'success' : 'danger' }}">
                                        {{ $record->ngay_het_han_the_bhyt->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-secondary small">Không hạn</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $record->hoan_thanh_tiem_chung_mo_rong ? 'success' : 'warning' }}">
                                    {{ $record->hoan_thanh_tiem_chung_mo_rong ? 'Đã hoàn thành' : 'Chưa hoàn thành' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('y-te-nhan-khau.show', $record) }}" class="btn btn-sm btn-outline-secondary" title="Xem"><i class="bi bi-eye"></i> Xem</a>
                                    @can('manage_an_sinh')
                                    <a href="{{ route('y-te-nhan-khau.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="Sửa"><i class="bi bi-pencil-square"></i> Sửa</a>
                                    <form method="POST" action="{{ route('y-te-nhan-khau.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ y tế này?">
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
                                Chưa có hồ sơ y tế nào phù hợp.
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
