<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Thông tin khoản thu</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="ho_khau_id" class="form-label">Hộ gia đình <span class="text-danger">*</span></label>
                <select class="form-select @error('ho_khau_id') is-invalid @enderror" id="ho_khau_id" name="ho_khau_id" required {{ $method !== 'POST' ? 'disabled' : '' }}>
                    <option value="">-- Chọn Hộ khẩu --</option>
                    @foreach($hoKhaus as $ho)
                        <option value="{{ $ho->id }}" @selected(old('ho_khau_id', $thueVaPhi->ho_khau_id ?? '') == $ho->id)>
                            Hộ: {{ $ho->ma_ho }} - Chủ hộ: {{ $ho->chuHo ? $ho->chuHo->ho_ten : 'Chưa cập nhật' }}
                        </option>
                    @endforeach
                </select>
                @error('ho_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($method !== 'POST')
                    <input type="hidden" name="ho_khau_id" value="{{ $thueVaPhi->ho_khau_id }}">
                @endif
            </div>
            <div class="col-md-3">
                <label for="nam" class="form-label">Năm áp dụng <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('nam') is-invalid @enderror" id="nam" name="nam" value="{{ old('nam', $thueVaPhi->nam ?? date('Y')) }}" required>
                @error('nam')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="loai_khoan_thu" class="form-label">Loại khoản thu <span class="text-danger">*</span></label>
                <select class="form-select @error('loai_khoan_thu') is-invalid @enderror" id="loai_khoan_thu" name="loai_khoan_thu" required>
                    @foreach(\App\Models\ThueVaPhiDiaPhuong::LOAI_KHOAN_THU as $key => $label)
                        <option value="{{ $key }}" @selected(old('loai_khoan_thu', $thueVaPhi->loai_khoan_thu ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loai_khoan_thu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Thu tiền</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="so_tien_phai_nop" class="form-label">Số tiền phải nộp (VNĐ) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" class="form-control @error('so_tien_phai_nop') is-invalid @enderror" id="so_tien_phai_nop" name="so_tien_phai_nop" value="{{ old('so_tien_phai_nop', $thueVaPhi->so_tien_phai_nop ?? '') }}" required>
                    <span class="input-group-text">₫</span>
                </div>
                @error('so_tien_phai_nop')<div class="d-block invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="so_tien_da_nop" class="form-label">Số tiền dân đã nộp (VNĐ) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" class="form-control text-success fw-bold @error('so_tien_da_nop') is-invalid @enderror" id="so_tien_da_nop" name="so_tien_da_nop" value="{{ old('so_tien_da_nop', $thueVaPhi->so_tien_da_nop ?? 0) }}" required>
                    <span class="input-group-text text-success">₫</span>
                </div>
                @error('so_tien_da_nop')<div class="d-block invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Nhập vào số tiền thực tế người dân đã nộp. Hệ thống sẽ tự động chuyển trạng thái.</small>
            </div>
            <div class="col-md-6">
                <label for="han_nop" class="form-label">Hạn nộp</label>
                <input type="date" class="form-control @error('han_nop') is-invalid @enderror" id="han_nop" name="han_nop" value="{{ old('han_nop', isset($thueVaPhi->han_nop) ? $thueVaPhi->han_nop->format('Y-m-d') : '') }}">
                @error('han_nop')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú (Chi tiết diện tích tính thuế...)</label>
                <textarea class="form-control @error('ghi_chu') is-invalid @enderror" id="ghi_chu" name="ghi_chu" rows="2">{{ old('ghi_chu', $thueVaPhi->ghi_chu ?? '') }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="card-footer bg-white text-end py-3">
        <a href="{{ route('thue-va-phi.index') }}" class="btn btn-outline-secondary me-2">Hủy bỏ</a>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check2 me-1"></i> {{ $submitLabel }}
        </button>
    </div>
</form>
