@extends('layouts.app')

@section('title', 'Quản lý Thuế & Phí địa phương')
@section('page_title', 'Thuế & Phí địa phương')

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
</style>

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
            <span class="mx-1">/</span>
            Thuế & Phí địa phương
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="btn-back" title="Quay lại Tổng quan">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Thu nộp ngân sách</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý thuế đất, phí vệ sinh, quỹ khuyến học và các khoản thu khác theo năm.</p>
    </div>
    <div class="d-flex gap-2">
        @can('manage_dat_dai')
        <form method="POST" action="{{ route('thue-va-phi.generate') }}" data-confirm="Bạn có muốn tự động quét và tính Thuế sử dụng đất phi nông nghiệp cho tất cả hộ dân năm nay không?">
            @csrf
            <button class="btn btn-warning" type="submit">
                <i class="bi bi-magic me-1"></i> Tính Thuế Đất Tự Động
            </button>
        </form>
        <a href="{{ route('thue-va-phi.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Tạo khoản thu mới
        </a>
        @endcan
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng tiền phải thu</p><h4 class="fw-bold mb-0">{{ number_format($stats['tong_phai_thu']) }} đ</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng tiền đã thu</p><h4 class="fw-bold mb-0 text-success">{{ number_format($stats['tong_da_thu']) }} đ</h4></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100"><div class="card-body"><p class="text-muted small mb-1">Tổng nợ đọng</p><h4 class="fw-bold mb-0 text-danger">{{ number_format($stats['tong_chua_thu']) }} đ</h4></div></div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</div>
    <div class="card-body">
        <form method="GET" action="{{ route('thue-va-phi.index') }}" class="row g-3">
            <div class="col-lg-3">
                <label for="q" class="form-label">Tìm Hộ khẩu</label>
                <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tên chủ hộ, Mã hộ...">
            </div>
            <div class="col-lg-2">
                <label for="nam" class="form-label">Năm</label>
                <select id="nam" name="nam" class="form-select">
                    @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                        <option value="{{ $i }}" @selected(request('nam', date('Y')) == $i)>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-lg-3">
                <label for="loai_khoan_thu" class="form-label">Loại phí</label>
                <select id="loai_khoan_thu" name="loai_khoan_thu" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach(\App\Models\ThueVaPhiDiaPhuong::LOAI_KHOAN_THU as $key => $label)
                        <option value="{{ $key }}" @selected(request('loai_khoan_thu') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label for="trang_thai_thanh_toan" class="form-label">Trạng thái</label>
                <select id="trang_thai_thanh_toan" name="trang_thai_thanh_toan" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach(\App\Models\ThueVaPhiDiaPhuong::TRANG_THAI as $key => $label)
                        <option value="{{ $key }}" @selected(request('trang_thai_thanh_toan') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('thue-va-phi.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách khoản thu</span>
        <span class="badge text-bg-light">{{ $records->total() }} khoản</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Năm</th>
                        <th>Loại khoản thu</th>
                        <th>Hộ gia đình</th>
                        <th class="text-end">Phải nộp</th>
                        <th class="text-end">Đã nộp</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $record->nam }}</span></td>
                            <td>
                                <div class="fw-semibold text-primary">{{ $record->loaiKhoanThuLabel() }}</div>
                                @if($record->ghi_chu)
                                    <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $record->ghi_chu }}">{{ $record->ghi_chu }}</div>
                                @endif
                            </td>
                            <td>
                                @if($record->hoKhau && $record->hoKhau->chuHo)
                                    <div class="fw-semibold">{{ $record->hoKhau->chuHo->ho_ten }}</div>
                                    <div class="small text-secondary">Hộ: {{ $record->hoKhau->ma_ho }}</div>
                                @else
                                    <span class="text-muted small">Chưa xác định</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($record->so_tien_phai_nop) }} ₫</td>
                            <td class="text-end fw-bold text-success">{{ number_format($record->so_tien_da_nop) }} ₫</td>
                            <td class="text-center">
                                @php
                                    $statusClass = match($record->trang_thai_thanh_toan) {
                                        'da_nop_du' => 'success',
                                        'nop_mot_phan' => 'warning',
                                        default => 'danger'
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $statusClass }}">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    @can('manage_dat_dai')
                                    <a href="{{ route('thue-va-phi.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="Cập nhật tiền nộp">
                                        Thu tiền
                                    </a>
                                    <form method="POST" action="{{ route('thue-va-phi.destroy', $record) }}" class="d-inline" data-confirm="Xóa khoản thu này?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Xóa">
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
                                Chưa có khoản thu nào.
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
