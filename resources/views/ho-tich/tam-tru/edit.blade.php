@extends('layouts.app')

@section('title', 'Sửa khai báo Tạm trú / Tạm vắng')
@section('page_title', 'Sửa khai báo')

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

<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('tam-tru.index') }}" class="text-decoration-none">Tạm trú & Tạm vắng</a>
        <span class="mx-1">/</span>
        Sửa hồ sơ #{{ $record->id }}
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tam-tru.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Cập nhật thông tin Khai báo</h2>
    </div>
</div>

<form method="POST" action="{{ route('tam-tru.update', $record) }}">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person me-1"></i>Thông tin đối tượng (Không thể thay đổi)
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-secondary small d-block">Nhân khẩu</label>
                        <span class="fw-bold text-dark fs-5">{{ $record->nhanKhau?->ho_ten ?? 'Không xác định' }}</span>
                        <span class="small text-secondary d-block">CCCD: {{ $record->nhanKhau?->cccd_cmnd ?? 'Chưa cập nhật' }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="text-secondary small d-block">Loại khai báo</label>
                        <span class="badge bg-{{ $record->loai === 'tam_tru' ? 'success' : 'info' }} bg-opacity-10 text-{{ $record->loai === 'tam_tru' ? 'success' : 'info' }} px-3 py-1.5 fs-6">
                            {{ $record->loaiLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clock me-1"></i>Thời gian khai báo
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" value="{{ old('ngay_bat_dau', $record->ngay_bat_dau?->format('Y-m-d')) }}" class="form-control @error('ngay_bat_dau') is-invalid @enderror">
                            @error('ngay_bat_dau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ngay_ket_thuc" class="form-label">Ngày hết hạn</label>
                            <input type="date" id="ngay_ket_thuc" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc', $record->ngay_ket_thuc?->format('Y-m-d')) }}" class="form-control @error('ngay_ket_thuc') is-invalid @enderror">
                            @error('ngay_ket_thuc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-geo-alt me-1"></i>Địa chỉ, lý do & trạng thái
                </div>
                <div class="card-body">
                    @if($record->loai === 'tam_tru')
                    <div class="mb-3">
                        <label for="dia_chi_cu_tru_thuc_te" class="form-label">Địa chỉ cư trú thực tế tại địa bàn xã <span class="text-danger">*</span></label>
                        <input type="text" id="dia_chi_cu_tru_thuc_te" name="dia_chi_cu_tru_thuc_te" value="{{ old('dia_chi_cu_tru_thuc_te', $record->dia_chi_cu_tru_thuc_te) }}" class="form-control @error('dia_chi_cu_tru_thuc_te') is-invalid @enderror">
                        @error('dia_chi_cu_tru_thuc_te')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="dia_chi_vang_mat" class="form-label">Địa chỉ nơi đến (Khi tạm vắng) <span class="text-danger">*</span></label>
                        <input type="text" id="dia_chi_vang_mat" name="dia_chi_vang_mat" value="{{ old('dia_chi_vang_mat', $record->dia_chi_vang_mat) }}" class="form-control @error('dia_chi_vang_mat') is-invalid @enderror">
                        @error('dia_chi_vang_mat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="trang_thai" class="form-label">Trạng thái phê duyệt <span class="text-danger">*</span></label>
                        <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                            @foreach($trangThai as $val => $lbl)
                                <option value="{{ $val }}" @selected(old('trang_thai', $record->trang_thai) == $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ly_do" class="form-label">Lý do khai báo</label>
                        <textarea id="ly_do" name="ly_do" rows="3" class="form-control @error('ly_do') is-invalid @enderror">{{ old('ly_do', $record->ly_do) }}</textarea>
                        @error('ly_do')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ghi_chu" class="form-label">Ghi chú cán bộ</label>
                        <textarea id="ghi_chu" name="ghi_chu" rows="2" class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
                        @error('ghi_chu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tam-tru.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-success">Cập nhật Hồ sơ</button>
            </div>
        </div>
    </div>
</form>
@endsection
