@extends('layouts.app')

@section('title', 'Chi tiết thửa đất')
@section('page_title', 'Đất đai & Tài sản')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
            <span class="mx-1">/</span>
            <a href="{{ route('dat-dai-tai-san.index') }}" class="text-decoration-none">Đất đai & Tài sản</a>
            <span class="mx-1">/</span>
            Chi tiết
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('dat-dai-tai-san.index') }}" class="btn-back" title="Quay lại danh sách">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Chi tiết thửa đất</h2>
        </div>
        <p class="text-secondary mb-0">GCN: {{ $datDaiTaiSan->so_gcn_qsdd ?? 'Chưa cấp' }}</p>
    </div>
    @can('manage_dat_dai')
        <a href="{{ route('dat-dai-tai-san.edit', $datDaiTaiSan) }}" class="btn btn-success">
            <i class="bi bi-pencil me-1"></i> Sửa thông tin
        </a>
    @endcan
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Chủ sở hữu</div>
                <div class="fw-semibold">{{ $datDaiTaiSan->chuSoHuu->ho_ten ?? 'Chưa xác định' }}</div>
                <div class="small text-secondary">CCCD: {{ $datDaiTaiSan->chuSoHuu->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Trạng thái</div>
                <span class="badge text-bg-success">{{ $datDaiTaiSan->trangThaiLabel() }}</span>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Loại đất</div>
                <div class="fw-semibold">{{ $datDaiTaiSan->loaiDatLabel() }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Diện tích</div>
                <div class="fw-semibold">{{ number_format($datDaiTaiSan->dien_tich_m2, 2, ',', '.') }} m²</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Thôn/xóm</div>
                <div class="fw-semibold">{{ $datDaiTaiSan->thon_xom ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Số tờ bản đồ</div>
                <div class="fw-semibold">{{ $datDaiTaiSan->so_to_ban_do ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Số thửa đất</div>
                <div class="fw-semibold">{{ $datDaiTaiSan->so_thua_dat ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Ngày cấp GCN</div>
                <div class="fw-semibold">{{ $datDaiTaiSan->ngay_cap_gcn?->format('d/m/Y') ?? '--' }}</div>
            </div>
            <div class="col-12">
                <div class="text-secondary small mb-1">Vị trí mô tả</div>
                <div>{{ $datDaiTaiSan->vi_tri_mo_ta ?? '--' }}</div>
            </div>
            <div class="col-12">
                <div class="text-secondary small mb-1">Ghi chú</div>
                <div>{{ $datDaiTaiSan->ghi_chu ?? '--' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
