@extends('layouts.app')

@section('title', 'Chi tiết cơ sở vật chất')
@section('page_title', 'Cơ sở vật chất')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
            <span class="mx-1">/</span>
            <a href="{{ route('co-so-vat-chat.index') }}" class="text-decoration-none">Cơ sở vật chất</a>
            <span class="mx-1">/</span>
            Chi tiết
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('co-so-vat-chat.index') }}" class="btn-back" title="Quay lại danh sách">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">{{ $record->ten_cong_trinh }}</h2>
        </div>
        <p class="text-secondary mb-0">{{ $record->phanLoaiLabel() }}</p>
    </div>
    @can('manage_dat_dai')
        <a href="{{ route('co-so-vat-chat.edit', $record) }}" class="btn btn-success">
            <i class="bi bi-pencil me-1"></i> Sửa thông tin
        </a>
    @endcan
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Tên công trình</div>
                <div class="fw-semibold">{{ $record->ten_cong_trinh }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Tình trạng</div>
                <span class="badge text-bg-success">{{ $record->tinhTrangLabel() }}</span>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Phân loại</div>
                <div class="fw-semibold">{{ $record->phanLoaiLabel() }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Thôn/xóm</div>
                <div class="fw-semibold">{{ $record->thon_xom ?? '--' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-secondary small mb-1">Ngày đưa vào sử dụng</div>
                <div class="fw-semibold">{{ $record->ngay_dua_vao_su_dung?->format('d/m/Y') ?? '--' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-secondary small mb-1">Kinh phí xây dựng</div>
                <div class="fw-bold text-success">{{ $record->kinh_phi_xay_dung ? number_format($record->kinh_phi_xay_dung) : '--' }}</div>
            </div>
            <div class="col-12">
                <div class="text-secondary small mb-1">Ghi chú</div>
                <div>{{ $record->ghi_chu ?? '--' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
