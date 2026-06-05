@extends('layouts.app')

@section('title', 'Chỉnh sửa biến động hộ khẩu')
@section('page_title', 'Chỉnh sửa biến động')

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
        Chỉnh sửa
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('bien-dong.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Chỉnh sửa lịch sử biến động</h2>
    </div>
</div>

<form method="POST" action="{{ route('bien-dong.update', $record) }}">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- Form fields --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-pencil-square me-1"></i>Thông tin thay đổi
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ngay_bien_dong" class="form-label">Ngày thực hiện <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bien_dong" name="ngay_bien_dong" 
                                   value="{{ old('ngay_bien_dong', $record->ngay_bien_dong?->format('Y-m-d')) }}" 
                                   class="form-control @error('ngay_bien_dong') is-invalid @enderror" required>
                            @error('ngay_bien_dong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="so_quyet_dinh" class="form-label">Số quyết định / Văn bản</label>
                            <input type="text" id="so_quyet_dinh" name="so_quyet_dinh" 
                                   value="{{ old('so_quyet_dinh', $record->so_quyet_dinh) }}" 
                                   class="form-control @error('so_quyet_dinh') is-invalid @enderror" 
                                   placeholder="VD: QĐ-12/UBND">
                            @error('so_quyet_dinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(in_array($record->loai_bien_dong, ['chuyen_di', 'chuyen_den']))
                        <div class="col-12">
                            <label for="dia_chi_chuyen_den" class="form-label">Địa chỉ chuyển đến (ngoài xã)</label>
                            <input type="text" id="dia_chi_chuyen_den" name="dia_chi_chuyen_den" 
                                   value="{{ old('dia_chi_chuyen_den', $record->dia_chi_chuyen_den) }}" 
                                   class="form-control @error('dia_chi_chuyen_den') is-invalid @enderror" 
                                   placeholder="Nhập địa chỉ nhận cư trú mới...">
                            @error('dia_chi_chuyen_den')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="col-12">
                            <label for="ly_do" class="form-label">Lý do biến động</label>
                            <textarea id="ly_do" name="ly_do" rows="3" 
                                      class="form-control @error('ly_do') is-invalid @enderror" 
                                      placeholder="Mô tả lý do biến động...">{{ old('ly_do', $record->ly_do) }}</textarea>
                            @error('ly_do')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="ghi_chu" class="form-label">Ghi chú</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="2" 
                                      class="form-control @error('ghi_chu') is-invalid @enderror" 
                                      placeholder="Ghi chú thêm nếu có...">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
                            @error('ghi_chu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('bien-dong.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            </div>
        </div>

        {{-- Readonly Context --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-info-circle me-1"></i>Thông tin gốc
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-secondary small d-block">Loại biến động</label>
                        <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 fw-semibold" style="font-size: 0.85rem;">
                            {{ $record->loaiLabel() }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <label class="text-secondary small d-block">Nhân khẩu liên quan</label>
                        @if($record->nhanKhau)
                            <div class="fw-semibold">{{ $record->nhanKhau->ho_ten }}</div>
                            <div class="small text-muted">CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </div>

                    @if($record->hoKhauNguon)
                    <div class="mb-3">
                        <label class="text-secondary small d-block">Hộ khẩu nguồn</label>
                        <div class="fw-semibold">Hộ {{ $record->hoKhauNguon->ma_ho }}</div>
                        <div class="small text-muted">Số sổ: {{ $record->hoKhauNguon->so_so_ho_khau }}</div>
                    </div>
                    @endif

                    @if($record->hoKhauDich)
                    <div class="mb-3">
                        <label class="text-secondary small d-block">Hộ khẩu đích</label>
                        <div class="fw-semibold">Hộ {{ $record->hoKhauDich->ma_ho }}</div>
                        <div class="small text-muted">Số sổ: {{ $record->hoKhauDich->so_so_ho_khau }}</div>
                    </div>
                    @endif

                    <div>
                        <label class="text-secondary small d-block">Người thực hiện</label>
                        <div class="fw-semibold text-secondary">{{ $record->nguoiThucHien?->name ?? 'Hệ thống' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
