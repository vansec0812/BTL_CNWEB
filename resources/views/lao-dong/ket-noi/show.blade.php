@extends('layouts.app')

@section('title', 'Chi tiết kết nối việc làm')
@section('page_title', 'Kết nối việc làm')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('ket-noi.index') }}" class="text-decoration-none">Kết nối việc làm</a>
            <span class="mx-1">/</span>
            Chi tiết
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('ket-noi.index') }}" class="btn-back" title="Quay lại danh sách">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">KN-{{ str_pad($record->id, 4, '0', STR_PAD_LEFT) }}</h2>
        </div>
        <p class="text-secondary mb-0">Chi tiết hồ sơ giới thiệu và kết nối việc làm.</p>
    </div>
    @can('manage_lao_dong')
        <a href="{{ route('ket-noi.edit', $record) }}" class="btn btn-success">
            <i class="bi bi-pencil me-1"></i> Sửa thông tin
        </a>
    @endcan
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Người lao động</div>
                <a href="{{ route('ho-so.show', $record->lao_dong_id) }}" class="fw-semibold text-decoration-none">
                    {{ $record->laoDong?->nhanKhau?->ho_ten ?? 'Chưa xác định' }}
                </a>
                <div class="small text-secondary">CCCD: {{ $record->laoDong?->nhanKhau?->cccd_cmnd ?? '--' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Doanh nghiệp tuyển dụng</div>
                <a href="{{ route('doanh-nghiep.show', $record->doanh_nghiep_id) }}" class="fw-semibold text-decoration-none">
                    {{ $record->doanhNghiep?->ten_co_so ?? 'Chưa xác định' }}
                </a>
                <div class="small text-secondary">Ngành: {{ $record->doanhNghiep?->nganh_nghe_chinh ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Vị trí giới thiệu</div>
                <div class="fw-semibold">{{ $record->vi_tri_gioi_thieu ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Ngày kết nối</div>
                <div class="fw-semibold">{{ $record->ngay_ket_noi?->format('d/m/Y') ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Kết quả</div>
                @php
                    $resultClass = match ($record->ket_qua) {
                        'duoc_nhan' => 'success',
                        'dang_cho_phan_hoi' => 'warning text-dark',
                        default => 'danger',
                    };
                @endphp
                <span class="badge text-bg-{{ $resultClass }}">{{ $record->ketQuaLabel() }}</span>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Cán bộ phụ trách</div>
                <div class="fw-semibold">{{ $record->nguoiPhuTrach?->name ?? 'Hệ thống' }}</div>
            </div>
            <div class="col-12">
                <div class="text-secondary small mb-1">Ghi chú</div>
                <div>{{ $record->ghi_chu ?? '--' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
