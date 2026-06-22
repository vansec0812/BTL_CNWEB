<form method="POST" action="{{ $action }}" class="card shadow-sm border-0">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger mb-4 border-0 shadow-sm">
                <div class="fw-semibold mb-1">Vui lòng kiểm tra lại thông tin:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                @if($method !== 'POST')
                    <input type="hidden" name="ho_khau_id" value="{{ $thueVaPhi->ho_khau_id }}">
                @endif
            </div>
            <div class="col-md-3">
                <label for="nam" class="form-label">Năm áp dụng <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="nam" name="nam" value="{{ old('nam', $thueVaPhi->nam ?? date('Y')) }}" required>
            </div>
            <div class="col-md-3">
                <label for="loai_khoan_thu" class="form-label">Loại khoản thu <span class="text-danger">*</span></label>
                <select class="form-select" id="loai_khoan_thu" name="loai_khoan_thu" required>
                    @foreach(\App\Models\ThueVaPhiDiaPhuong::LOAI_KHOAN_THU as $key => $label)
                        <option value="{{ $key }}" @selected(old('loai_khoan_thu', $thueVaPhi->loai_khoan_thu ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Thu tiền</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="so_tien_phai_nop" class="form-label">Số tiền phải nộp (VNĐ) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" class="form-control" id="so_tien_phai_nop" name="so_tien_phai_nop" value="{{ old('so_tien_phai_nop', $thueVaPhi->so_tien_phai_nop ?? '') }}" required>
                    <span class="input-group-text">₫</span>
                </div>
            </div>
            <div class="col-md-6">
                <label for="so_tien_da_nop" class="form-label">Số tiền dân đã nộp (VNĐ) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" min="0" class="form-control text-success fw-bold" id="so_tien_da_nop" name="so_tien_da_nop" value="{{ old('so_tien_da_nop', $thueVaPhi->so_tien_da_nop ?? 0) }}" required>
                    <span class="input-group-text text-success">₫</span>
                </div>
                <small class="text-muted">Nhập vào số tiền thực tế người dân đã nộp. Hệ thống sẽ tự động chuyển trạng thái.</small>
            </div>
            <div class="col-md-6">
                <label for="han_nop" class="form-label">Hạn nộp</label>
                <input type="date" class="form-control" id="han_nop" name="han_nop" value="{{ old('han_nop', isset($thueVaPhi->han_nop) ? $thueVaPhi->han_nop->format('Y-m-d') : '') }}">
            </div>
            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú (Chi tiết diện tích tính thuế...)</label>
                <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="2">{{ old('ghi_chu', $thueVaPhi->ghi_chu ?? '') }}</textarea>
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
