<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Thông tin công trình</h5>
        
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="ten_cong_trinh" class="form-label">Tên công trình <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('ten_cong_trinh') is-invalid @enderror" 
                       id="ten_cong_trinh" name="ten_cong_trinh" 
                       value="{{ old('ten_cong_trinh', $record->ten_cong_trinh ?? '') }}" 
                       placeholder="Nhập tên công trình (VD: Nhà văn hóa Thôn 1)" required>
                @error('ten_cong_trinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-3">
                <label for="phan_loai" class="form-label">Phân loại <span class="text-danger">*</span></label>
                <select class="form-select @error('phan_loai') is-invalid @enderror" id="phan_loai" name="phan_loai" required>
                    <option value="">-- Chọn loại --</option>
                    @foreach(\App\Models\CoSoVatChat::PHAN_LOAI as $key => $label)
                        <option value="{{ $key }}" @selected(old('phan_loai', $record->phan_loai ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('phan_loai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="thon_xom" class="form-label">Thuộc Thôn/Xóm</label>
                <input type="text" class="form-control @error('thon_xom') is-invalid @enderror" 
                       id="thon_xom" name="thon_xom" 
                       value="{{ old('thon_xom', $record->thon_xom ?? '') }}" 
                       placeholder="VD: Thôn 1">
                @error('thon_xom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Chi tiết hiện trạng</h5>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="ngay_dua_vao_su_dung" class="form-label">Ngày khánh thành</label>
                <input type="date" class="form-control @error('ngay_dua_vao_su_dung') is-invalid @enderror" 
                       id="ngay_dua_vao_su_dung" name="ngay_dua_vao_su_dung" 
                       value="{{ old('ngay_dua_vao_su_dung', isset($record->ngay_dua_vao_su_dung) ? $record->ngay_dua_vao_su_dung->format('Y-m-d') : '') }}">
                @error('ngay_dua_vao_su_dung')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="kinh_phi_xay_dung" class="form-label">Vốn đầu tư (VNĐ)</label>
                <div class="input-group">
                    <input type="number" class="form-control text-success fw-bold @error('kinh_phi_xay_dung') is-invalid @enderror" 
                           id="kinh_phi_xay_dung" name="kinh_phi_xay_dung" 
                           value="{{ old('kinh_phi_xay_dung', $record->kinh_phi_xay_dung ?? '') }}" 
                           min="0" step="1000">
                    <span class="input-group-text text-success">₫</span>
                </div>
                @error('kinh_phi_xay_dung')<div class="d-block invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-5">
                <label for="tinh_trang" class="form-label">Tình trạng hiện tại <span class="text-danger">*</span></label>
                <select class="form-select @error('tinh_trang') is-invalid @enderror" id="tinh_trang" name="tinh_trang" required>
                    <option value="">-- Chọn tình trạng --</option>
                    @foreach(\App\Models\CoSoVatChat::TINH_TRANG as $key => $label)
                        <option value="{{ $key }}" @selected(old('tinh_trang', $record->tinh_trang ?? 'tot') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('tinh_trang')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú (Bảo trì, sửa chữa...)</label>
                <textarea class="form-control @error('ghi_chu') is-invalid @enderror" 
                          id="ghi_chu" name="ghi_chu" rows="2">{{ old('ghi_chu', $record->ghi_chu ?? '') }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="card-footer bg-white text-end py-3">
        <a href="{{ route('co-so-vat-chat.index') }}" class="btn btn-outline-secondary me-2">Hủy bỏ</a>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check2 me-1"></i> Lưu thông tin
        </button>
    </div>
</form>
