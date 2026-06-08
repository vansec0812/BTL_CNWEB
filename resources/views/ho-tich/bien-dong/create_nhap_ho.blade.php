@extends('layouts.app')

@section('title', 'Nghiệp vụ Nhập hộ khẩu')
@section('page_title', 'Nhập hộ khẩu')

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
        <a href="{{ route('bien-dong.index') }}" class="text-decoration-none">Biến động hộ khẩu</a>
        <span class="mx-1">/</span>
        Nhập hộ
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('bien-dong.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Nghiệp vụ Nhập hộ khẩu</h2>
    </div>
</div>

<form method="POST" action="{{ route('bien-dong.store') }}">
    @csrf
    <input type="hidden" name="loai_bien_dong" value="nhap_ho">

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Thông tin Nhập hộ khẩu
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="ho_khau_dich_id" class="form-label">Sổ hộ khẩu đích (Nhập vào hộ) <span class="text-danger">*</span></label>
                        <select id="ho_khau_dich_id" name="ho_khau_dich_id" class="form-select @error('ho_khau_dich_id') is-invalid @enderror">
                            <option value="">-- Chọn sổ hộ khẩu nhận thành viên --</option>
                            @foreach ($hoKhauList as $ho)
                                <option value="{{ $ho->id }}" @selected(old('ho_khau_dich_id') == $ho->id)>
                                    Hộ: {{ $ho->ma_ho }} - Số sổ: {{ $ho->so_so_ho_khau }} (Chủ hộ: {{ $ho->chuHo?->ho_ten ?? 'Chưa xác định' }})
                                </option>
                            @endforeach
                        </select>
                        @error('ho_khau_dich_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nhan_khau_id" class="form-label">Chọn nhân khẩu chuyển đến <span class="text-danger">*</span></label>
                        <select id="nhan_khau_id" name="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror">
                            <option value="">-- Chọn nhân khẩu chuyển đến --</option>
                            @foreach ($nhanKhauList as $nk)
                                <option value="{{ $nk->id }}" @selected(old('nhan_khau_id') == $nk->id)>
                                    {{ $nk->ho_ten }} - CCCD: {{ $nk->cccd_cmnd ?? 'Chưa cập nhật' }} (Hộ cũ: {{ $nk->hoKhau?->ma_ho ?? 'Không có' }})
                                </option>
                            @endforeach
                        </select>
                        @error('nhan_khau_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quan_he_chu_ho" class="form-label">Quan hệ với chủ hộ đích <span class="text-danger">*</span></label>
                        <input type="text" id="quan_he_chu_ho" name="quan_he_chu_ho" value="{{ old('quan_he_chu_ho', 'Thành viên') }}" class="form-control @error('quan_he_chu_ho') is-invalid @enderror" placeholder="Vợ, chồng, con, cháu, anh/chị/em...">
                        @error('quan_he_chu_ho')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-check me-1"></i>Thông tin quyết định & ngày chuyển
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ngay_bien_dong" class="form-label">Ngày nhập hộ <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bien_dong" name="ngay_bien_dong" value="{{ old('ngay_bien_dong', date('Y-m-d')) }}" class="form-control @error('ngay_bien_dong') is-invalid @enderror">
                            @error('ngay_bien_dong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="so_quyet_dinh" class="form-label">Số quyết định / Văn bản</label>
                            <input type="text" id="so_quyet_dinh" name="so_quyet_dinh" value="{{ old('so_quyet_dinh') }}" class="form-control @error('so_quyet_dinh') is-invalid @enderror" placeholder="VD: QĐ-456/UBND">
                            @error('so_quyet_dinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ly_do" class="form-label">Lý do nhập hộ</label>
                            <textarea id="ly_do" name="ly_do" rows="3" class="form-control @error('ly_do') is-invalid @enderror" placeholder="Nhập lý do chi tiết (kết hôn, chuyển nơi sinh sống, v.v.)...">{{ old('ly_do') }}</textarea>
                            @error('ly_do')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ghi_chu" class="form-label">Ghi chú thêm</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="2" class="form-control @error('ghi_chu') is-invalid @enderror" placeholder="Ghi chú thêm (nếu có)...">{{ old('ghi_chu') }}</textarea>
                            @error('ghi_chu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('bien-dong.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-info text-white">Thực hiện Nhập hộ</button>
            </div>
        </div>
    </div>
</form>
@endsection
