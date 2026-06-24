@extends('layouts.app')

@section('title', 'Chi tiết khoản thu')
@section('page_title', 'Thuế & Phí địa phương')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
            <span class="mx-1">/</span>
            <a href="{{ route('thue-va-phi.index') }}" class="text-decoration-none">Thuế & Phí địa phương</a>
            <span class="mx-1">/</span>
            Chi tiết
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('thue-va-phi.index') }}" class="btn-back" title="Quay lại danh sách">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Chi tiết khoản thu</h2>
        </div>
        <p class="text-secondary mb-0">{{ $thueVaPhi->loaiKhoanThuLabel() }} năm {{ $thueVaPhi->nam }}</p>
    </div>
    @can('manage_dat_dai')
        <a href="{{ route('thue-va-phi.edit', $thueVaPhi) }}" class="btn btn-success">
            <i class="bi bi-pencil me-1"></i> Sửa thông tin
        </a>
    @endcan
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Hộ gia đình</div>
                <div class="fw-semibold">{{ $thueVaPhi->hoKhau?->chuHo?->ho_ten ?? 'Chưa xác định' }}</div>
                <div class="small text-secondary">Mã hộ: {{ $thueVaPhi->hoKhau?->ma_ho ?? '--' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Trạng thái</div>
                <span class="badge text-bg-success">{{ $thueVaPhi->trangThaiLabel() }}</span>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Loại khoản thu</div>
                <div class="fw-semibold">{{ $thueVaPhi->loaiKhoanThuLabel() }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Năm</div>
                <div class="fw-semibold">{{ $thueVaPhi->nam }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Hạn nộp</div>
                <div class="fw-semibold">{{ $thueVaPhi->han_nop?->format('d/m/Y') ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Phải nộp</div>
                <div class="fw-bold">{{ number_format($thueVaPhi->so_tien_phai_nop) }} đ</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Đã nộp</div>
                <div class="fw-bold text-success">{{ number_format($thueVaPhi->so_tien_da_nop) }} đ</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Còn lại</div>
                <div class="fw-bold text-danger">{{ number_format(max(0, $thueVaPhi->so_tien_phai_nop - $thueVaPhi->so_tien_da_nop)) }} đ</div>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Ngày nộp thực tế</div>
                <div class="fw-semibold">{{ $thueVaPhi->ngay_nop_thuc_te?->format('d/m/Y') ?? '--' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Người thu</div>
                <div class="fw-semibold">{{ $thueVaPhi->nguoiThu?->name ?? '--' }}</div>
            </div>
            <div class="col-12">
                <div class="text-secondary small mb-1">Thửa đất liên quan</div>
                <div>{{ $thueVaPhi->datDaiTaiSan ? 'GCN: '.($thueVaPhi->datDaiTaiSan->so_gcn_qsdd ?? 'Chưa cấp') : '--' }}</div>
            </div>
            <div class="col-12">
                <div class="text-secondary small mb-1">Ghi chú</div>
                <div>{{ $thueVaPhi->ghi_chu ?? '--' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
