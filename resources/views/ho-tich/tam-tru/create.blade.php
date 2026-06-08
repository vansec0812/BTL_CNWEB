@extends('layouts.app')

@section('title', 'Khai báo Tạm trú / Tạm vắng')
@section('page_title', 'Tạo khai báo')

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
        Khai báo mới
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tam-tru.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Tạo khai báo Tạm trú / Tạm vắng</h2>
    </div>
</div>

<form method="POST" action="{{ route('tam-tru.store') }}" novalidate>
    @csrf

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person me-1"></i>Bước 1: Chọn nhân khẩu & Loại khai báo
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nhan_khau_id" class="form-label">Nhân khẩu khai báo <span class="text-danger">*</span></label>
                        <select id="nhan_khau_id" name="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror">
                            <option value="">-- Chọn nhân khẩu --</option>
                            @foreach ($nhanKhauList as $nk)
                                <option value="{{ $nk->id }}" @selected(old('nhan_khau_id') == $nk->id)>
                                    {{ $nk->ho_ten }} - CCCD: {{ $nk->cccd_cmnd ?? 'Chưa cập nhật' }} (Ngày sinh: {{ $nk->ngay_sinh?->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('nhan_khau_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block fw-semibold">Loại khai báo <span class="text-danger">*</span></label>
                        @foreach($loai as $val => $lbl)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="loai" id="loai_{{ $val }}" value="{{ $val }}" @checked(old('loai', 'tam_tru') == $val) onchange="toggleTypeFields(this.value)">
                                <label class="form-check-label" for="loai_{{ $val }}">{{ $lbl }}</label>
                            </div>
                        @endforeach
                        @error('loai')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clock me-1"></i>Bước 2: Thời hạn áp dụng
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bat_dau" name="ngay_bat_dau" value="{{ old('ngay_bat_dau', date('Y-m-d')) }}" class="form-control @error('ngay_bat_dau') is-invalid @enderror">
                            @error('ngay_bat_dau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ngay_ket_thuc" class="form-label">Ngày hết hạn <span class="text-secondary">(Tùy chọn)</span></label>
                            <input type="date" id="ngay_ket_thuc" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc') }}" class="form-control @error('ngay_ket_thuc') is-invalid @enderror">
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
                    <i class="bi bi-geo-alt me-1"></i>Bước 3: Địa chỉ & Lý do
                </div>
                <div class="card-body">
                    <div class="mb-3" id="tam_tru_address_field">
                        <label for="dia_chi_cu_tru_thuc_te" class="form-label">Địa chỉ cư trú thực tế (Tại xã Quốc Oai) <span class="text-danger">*</span></label>
                        <input type="text" id="dia_chi_cu_tru_thuc_te" name="dia_chi_cu_tru_thuc_te" value="{{ old('dia_chi_cu_tru_thuc_te') }}" class="form-control @error('dia_chi_cu_tru_thuc_te') is-invalid @enderror" placeholder="Nhập địa chỉ cư trú tạm thời tại địa bàn xã...">
                        @error('dia_chi_cu_tru_thuc_te')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none" id="tam_vang_address_field">
                        <label for="dia_chi_vang_mat" class="form-label">Địa chỉ nơi đến (Khi tạm vắng) <span class="text-danger">*</span></label>
                        <input type="text" id="dia_chi_vang_mat" name="dia_chi_vang_mat" value="{{ old('dia_chi_vang_mat') }}" class="form-control @error('dia_chi_vang_mat') is-invalid @enderror" placeholder="Nhập địa chỉ nơi nhân khẩu sắp đi đến...">
                        @error('dia_chi_vang_mat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ly_do" class="form-label">Lý do khai báo</label>
                        <textarea id="ly_do" name="ly_do" rows="3" class="form-control @error('ly_do') is-invalid @enderror" placeholder="Nhập lý do cụ thể (đi học, đi làm, chữa bệnh, đi nghĩa vụ...)...">{{ old('ly_do') }}</textarea>
                        @error('ly_do')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ghi_chu" class="form-label">Ghi chú thêm</label>
                        <textarea id="ghi_chu" name="ghi_chu" rows="2" class="form-control @error('ghi_chu') is-invalid @enderror" placeholder="Ghi chú thêm (nếu có)...">{{ old('ghi_chu') }}</textarea>
                        @error('ghi_chu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tam-tru.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-success">Lưu khai báo</button>
            </div>
        </div>
    </div>
</form>

<script>
    function toggleTypeFields(val) {
        const tamTruField = document.getElementById('tam_tru_address_field');
        const tamVangField = document.getElementById('tam_vang_address_field');

        if (val === 'tam_tru') {
            tamTruField.classList.remove('d-none');
            tamVangField.classList.add('d-none');
            document.getElementById('dia_chi_vang_mat').value = '';
        } else {
            tamTruField.classList.add('d-none');
            tamVangField.classList.remove('d-none');
            document.getElementById('dia_chi_cu_tru_thuc_te').value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkedType = document.querySelector('input[name="loai"]:checked').value;
        toggleTypeFields(checkedType);
    });
</script>
@endsection
