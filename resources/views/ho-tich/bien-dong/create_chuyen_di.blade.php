@extends('layouts.app')

@section('title', 'Khai báo Chuyển đi (Ngoài xã)')
@section('page_title', 'Chuyển đi ngoài xã')

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
        Chuyển đi
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('bien-dong.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Khai báo chuyển đi ngoài xã</h2>
    </div>
</div>

<form method="POST" action="{{ route('bien-dong.store') }}">
    @csrf
    <input type="hidden" name="loai_bien_dong" value="chuyen_di">

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-box-arrow-right me-1"></i>Đối tượng chuyển đi
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label d-block fw-semibold">Phạm vi chuyển đi</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scope" id="scope_nk" value="nhan_khau" checked onchange="toggleScope(this.value)">
                            <label class="form-check-label" for="scope_nk">Chuyển đi cá nhân (nhân khẩu)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scope" id="scope_ho" value="ho_khau" onchange="toggleScope(this.value)">
                            <label class="form-check-label" for="scope_ho">Chuyển đi cả hộ gia đình</label>
                        </div>
                    </div>

                    <div class="mb-3" id="nk-select-wrapper">
                        <label for="nhan_khau_id" class="form-label">Chọn nhân khẩu chuyển đi <span class="text-danger">*</span></label>
                        <select id="nhan_khau_id" name="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror">
                            <option value="">-- Chọn nhân khẩu chuyển đi --</option>
                            @foreach ($nhanKhauList as $nk)
                                <option value="{{ $nk->id }}" @selected(old('nhan_khau_id') == $nk->id)>
                                    {{ $nk->ho_ten }} - CCCD: {{ $nk->cccd_cmnd ?? 'Chưa cập nhật' }} (Mã hộ: {{ $nk->hoKhau?->ma_ho ?? '—' }})
                                </option>
                            @endforeach
                        </select>
                        @error('nhan_khau_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none" id="ho-select-wrapper">
                        <label for="ho_khau_id" class="form-label">Chọn sổ hộ khẩu chuyển đi <span class="text-danger">*</span></label>
                        <select id="ho_khau_id" name="ho_khau_id" class="form-select @error('ho_khau_id') is-invalid @enderror">
                            <option value="">-- Chọn hộ khẩu chuyển đi --</option>
                            @foreach ($hoKhauList as $ho)
                                <option value="{{ $ho->id }}" @selected(old('ho_khau_id') == $ho->id)>
                                    Hộ: {{ $ho->ma_ho }} - Số sổ: {{ $ho->so_so_ho_khau }} (Chủ hộ: {{ $ho->chuHo?->ho_ten ?? 'Chưa xác định' }})
                                </option>
                            @endforeach
                        </select>
                        @error('ho_khau_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="dia_chi_chuyen_den" class="form-label">Địa chỉ chuyển đến (Nơi đến ngoài xã) <span class="text-danger">*</span></label>
                        <input type="text" id="dia_chi_chuyen_den" name="dia_chi_chuyen_den" value="{{ old('dia_chi_chuyen_den') }}" class="form-control @error('dia_chi_chuyen_den') is-invalid @enderror" placeholder="Nhập địa chỉ nơi cư trú mới ngoài xã...">
                        @error('dia_chi_chuyen_den')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-check me-1"></i>Thông tin quyết định & ngày chuyển đi
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ngay_bien_dong" class="form-label">Ngày chuyển đi <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bien_dong" name="ngay_bien_dong" value="{{ old('ngay_bien_dong', date('Y-m-d')) }}" class="form-control @error('ngay_bien_dong') is-invalid @enderror">
                            @error('ngay_bien_dong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="so_quyet_dinh" class="form-label">Số quyết định / Văn bản</label>
                            <input type="text" id="so_quyet_dinh" name="so_quyet_dinh" value="{{ old('so_quyet_dinh') }}" class="form-control @error('so_quyet_dinh') is-invalid @enderror" placeholder="VD: QĐ-789/UBND">
                            @error('so_quyet_dinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ly_do" class="form-label">Lý do chuyển đi</label>
                            <textarea id="ly_do" name="ly_do" rows="3" class="form-control @error('ly_do') is-invalid @enderror" placeholder="VD: Chuyển công tác, mua nhà mới ngoài địa bàn xã Quốc Oai...">{{ old('ly_do') }}</textarea>
                            @error('ly_do')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ghi_chu" class="form-label">Ghi chú</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="2" class="form-control @error('ghi_chu') is-invalid @enderror" placeholder="Ghi chú thêm...">{{ old('ghi_chu') }}</textarea>
                            @error('ghi_chu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('bien-dong.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-danger">Xác nhận Chuyển đi</button>
            </div>
        </div>
    </div>
</form>

<script>
    function toggleScope(scope) {
        const nkWrapper = document.getElementById('nk-select-wrapper');
        const hoWrapper = document.getElementById('ho-select-wrapper');

        if (scope === 'nhan_khau') {
            nkWrapper.classList.remove('d-none');
            hoWrapper.classList.add('d-none');
            document.getElementById('ho_khau_id').value = '';
        } else {
            nkWrapper.classList.add('d-none');
            hoWrapper.classList.remove('d-none');
            document.getElementById('nhan_khau_id').value = '';
        }
    }

    // Restore old selection states
    document.addEventListener('DOMContentLoaded', function() {
        const oldScope = "{{ old('scope', 'nhan_khau') }}";
        toggleScope(oldScope);
        if (oldScope === 'ho_khau') {
            document.getElementById('scope_ho').checked = true;
        }
    });
</script>
@endsection
