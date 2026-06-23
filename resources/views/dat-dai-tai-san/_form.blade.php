<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Thông tin sở hữu</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="ho_khau_id" class="form-label">Hộ gia đình sở hữu <span class="text-danger">*</span></label>
                <select class="form-select @error('ho_khau_id') is-invalid @enderror" id="ho_khau_id" name="ho_khau_id" required>
                    <option value="">-- Chọn Hộ khẩu --</option>
                    @foreach($hoKhaus as $ho)
                        <option value="{{ $ho->id }}" @selected(old('ho_khau_id', $datDaiTaiSan->ho_khau_id ?? '') == $ho->id)>
                            Hộ: {{ $ho->ma_ho }} - Chủ hộ: {{ $ho->chuHo ? $ho->chuHo->ho_ten : 'Chưa cập nhật' }}
                        </option>
                    @endforeach
                </select>
                @error('ho_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="thon_xom" class="form-label">Thôn/Xóm (Địa bàn)</label>
                <input type="text" class="form-control @error('thon_xom') is-invalid @enderror" id="thon_xom" name="thon_xom" value="{{ old('thon_xom', $datDaiTaiSan->thon_xom ?? '') }}">
                @error('thon_xom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Thông tin Giấy chứng nhận QSDĐ</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="so_gcn_qsdd" class="form-label">Số GCN QSDĐ (Sổ đỏ/hồng)</label>
                <input type="text" class="form-control @error('so_gcn_qsdd') is-invalid @enderror" id="so_gcn_qsdd" name="so_gcn_qsdd" value="{{ old('so_gcn_qsdd', $datDaiTaiSan->so_gcn_qsdd ?? '') }}">
                @error('so_gcn_qsdd')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="so_to_ban_do" class="form-label">Số tờ bản đồ</label>
                <input type="text" class="form-control @error('so_to_ban_do') is-invalid @enderror" id="so_to_ban_do" name="so_to_ban_do" value="{{ old('so_to_ban_do', $datDaiTaiSan->so_to_ban_do ?? '') }}">
                @error('so_to_ban_do')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="so_thua_dat" class="form-label">Số thửa đất</label>
                <input type="text" class="form-control @error('so_thua_dat') is-invalid @enderror" id="so_thua_dat" name="so_thua_dat" value="{{ old('so_thua_dat', $datDaiTaiSan->so_thua_dat ?? '') }}">
                @error('so_thua_dat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="ngay_cap_gcn" class="form-label">Ngày cấp GCN</label>
                <input type="date" class="form-control @error('ngay_cap_gcn') is-invalid @enderror" id="ngay_cap_gcn" name="ngay_cap_gcn" value="{{ old('ngay_cap_gcn', isset($datDaiTaiSan->ngay_cap_gcn) ? $datDaiTaiSan->ngay_cap_gcn->format('Y-m-d') : '') }}">
                @error('ngay_cap_gcn')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="ngay_het_han_gcn" class="form-label">Ngày hết hạn (Nếu có)</label>
                <input type="date" class="form-control @error('ngay_het_han_gcn') is-invalid @enderror" id="ngay_het_han_gcn" name="ngay_het_han_gcn" value="{{ old('ngay_het_han_gcn', isset($datDaiTaiSan->ngay_het_han_gcn) ? $datDaiTaiSan->ngay_het_han_gcn->format('Y-m-d') : '') }}">
                @error('ngay_het_han_gcn')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success border-bottom pb-2">Hiện trạng sử dụng</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="loai_dat" class="form-label">Loại đất <span class="text-danger">*</span></label>
                <select class="form-select @error('loai_dat') is-invalid @enderror" id="loai_dat" name="loai_dat" required>
                    @php $loai = old('loai_dat', $datDaiTaiSan->loai_dat ?? ''); @endphp
                    <option value="">-- Chọn Loại đất --</option>
                    <option value="dat_tho_cu" @selected($loai === 'dat_tho_cu')>Đất thổ cư</option>
                    <option value="dat_nong_nghiep" @selected($loai === 'dat_nong_nghiep')>Đất nông nghiệp</option>
                    <option value="dat_lam_nghiep" @selected($loai === 'dat_lam_nghiep')>Đất lâm nghiệp</option>
                    <option value="dat_nuoi_trong_thuy_san" @selected($loai === 'dat_nuoi_trong_thuy_san')>Đất nuôi trồng thủy sản</option>
                    <option value="dat_kinh_doanh" @selected($loai === 'dat_kinh_doanh')>Đất kinh doanh phi nông nghiệp</option>
                    <option value="khac" @selected($loai === 'khac')>Khác</option>
                </select>
                @error('loai_dat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="dien_tich_m2" class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" class="form-control @error('dien_tich_m2') is-invalid @enderror" id="dien_tich_m2" name="dien_tich_m2" value="{{ old('dien_tich_m2', $datDaiTaiSan->dien_tich_m2 ?? '') }}" required>
                @error('dien_tich_m2')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select @error('trang_thai') is-invalid @enderror" id="trang_thai" name="trang_thai" required>
                    @php $tt = old('trang_thai', $datDaiTaiSan->trang_thai ?? 'dang_su_dung'); @endphp
                    <option value="dang_su_dung" @selected($tt === 'dang_su_dung')>Đang sử dụng</option>
                    <option value="cho_thue" @selected($tt === 'cho_thue')>Cho thuê</option>
                    <option value="bi_tranh_chap" @selected($tt === 'bi_tranh_chap')>Bị tranh chấp</option>
                    <option value="da_chuyen_nhuong" @selected($tt === 'da_chuyen_nhuong')>Đã chuyển nhượng</option>
                    <option value="thu_hoi" @selected($tt === 'thu_hoi')>Thu hồi</option>
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="vi_tri_mo_ta" class="form-label">Mô tả vị trí</label>
                <input type="text" class="form-control @error('vi_tri_mo_ta') is-invalid @enderror" id="vi_tri_mo_ta" name="vi_tri_mo_ta" value="{{ old('vi_tri_mo_ta', $datDaiTaiSan->vi_tri_mo_ta ?? '') }}">
                @error('vi_tri_mo_ta')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea class="form-control @error('ghi_chu') is-invalid @enderror" id="ghi_chu" name="ghi_chu" rows="2">{{ old('ghi_chu', $datDaiTaiSan->ghi_chu ?? '') }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-white text-end py-3">
        <a href="{{ route('dat-dai-tai-san.index') }}" class="btn btn-outline-secondary me-2">Hủy bỏ</a>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check2 me-1"></i> {{ $submitLabel }}
        </button>
    </div>
</form>
