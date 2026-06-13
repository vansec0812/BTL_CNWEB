<style>
    /* Làm nổi bật nút radio */
    .form-check-input[type="radio"] {
        border: 2px solid #555 !important;
        width: 1.35rem;
        height: 1.35rem;
        cursor: pointer;
        margin-top: 0.15rem;
    }
    .form-check-input[type="radio"]:checked {
        background-color: var(--admin-green, #198754) !important;
        border-color: var(--admin-green, #198754) !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
</style>
@csrf

<div class="row g-3">
    <!-- Nhóm đối tượng -->
    <div class="col-12 mb-2">
        <label class="form-label small fw-semibold d-block">Nhóm đối tượng <span class="text-danger">*</span></label>
        <div class="d-flex gap-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="nhom_doi_tuong" id="nhom_vi_pham" value="vi_pham_hanh_chinh" 
                       {{ old('nhom_doi_tuong', $record->nhom_doi_tuong ?? 'vi_pham_hanh_chinh') === 'vi_pham_hanh_chinh' ? 'checked' : '' }}
                       onchange="toggleFormLogic()">
                <label class="form-check-label fw-semibold" for="nhom_vi_pham">
                    Vi phạm hành chính
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="nhom_doi_tuong" id="nhom_quan_ly" value="quan_ly_dac_biet" 
                       {{ old('nhom_doi_tuong', $record->nhom_doi_tuong ?? '') === 'quan_ly_dac_biet' ? 'checked' : '' }}
                       onchange="toggleFormLogic()">
                <label class="form-check-label fw-semibold" for="nhom_quan_ly">
                    Quản lý đặc biệt / Tội phạm
                </label>
            </div>
        </div>
        @error('nhom_doi_tuong')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Tìm kiếm nhanh nhân khẩu địa phương -->
    <div class="col-12 mb-2">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body p-3">
                <label for="nhan_khau_search" class="form-label small fw-bold text-success"><i class="bi bi-search me-1"></i>Tìm nhanh nhân khẩu địa phương (Tự động điền)</label>
                <input type="hidden" name="nhan_khau_id" id="nhan_khau_id" value="{{ old('nhan_khau_id', $record->nhan_khau_id ?? '') }}">
                <input type="text" id="nhan_khau_search" list="nhan_khau_list" class="form-control" 
                       placeholder="Gõ tên hoặc số CCCD để chọn..." autocomplete="off" oninput="onNhanKhauSearchChange()">
                <datalist id="nhan_khau_list">
                    @foreach($nhanKhau as $nk)
                        <option data-id="{{ $nk->id }}" 
                                data-hoten="{{ $nk->ho_ten }}"
                                data-cccd="{{ $nk->cccd_cmnd ?? '' }}"
                                data-diachi="{{ $nk->hoKhau->dia_chi_thuong_tru ?? '' }}"
                                value="{{ $nk->ho_ten }} - CCCD: {{ $nk->cccd_cmnd ?? 'Chưa có' }} - Địa chỉ: {{ $nk->hoKhau->dia_chi_thuong_tru ?? '—' }}">
                    @endforeach
                </datalist>
                <small class="text-muted">Chọn nhân khẩu để tự động điền. Nếu đối tượng vãng lai không có trong danh sách, hãy bỏ trống ô này và tự gõ trực tiếp vào các ô Họ tên, CCCD, Địa chỉ dưới đây.</small>
            </div>
        </div>
    </div>

    <!-- Họ tên đối tượng -->
    <div class="col-md-6">
        <label for="ho_ten" class="form-label small fw-semibold">Họ tên đối tượng <span class="text-danger">*</span></label>
        <input type="text" name="ho_ten" id="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror" 
               placeholder="Nhập họ tên đối tượng..." value="{{ old('ho_ten', $record->ho_ten ?? '') }}" required>
        @error('ho_ten')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Căn cước công dân -->
    <div class="col-md-6">
        <label for="cccd" class="form-label small fw-semibold">Số CCCD/CMND</label>
        <input type="text" name="cccd" id="cccd" class="form-control @error('cccd') is-invalid @enderror" 
               placeholder="Nhập số CCCD/CMND..." value="{{ old('cccd', $record->cccd ?? '') }}">
        @error('cccd')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Địa chỉ -->
    <div class="col-12">
        <label for="dia_chi" class="form-label small fw-semibold">Địa chỉ cư trú</label>
        <input type="text" name="dia_chi" id="dia_chi" class="form-control @error('dia_chi') is-invalid @enderror" 
               placeholder="Ví dụ: Thôn 3, Xã Quốc Oai, Huyện Quốc Oai, Hà Nội..." value="{{ old('dia_chi', $record->dia_chi ?? '') }}">
        @error('dia_chi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phân loại đối tượng -->
    <div class="col-md-6">
        <label for="loai_doi_tuong" class="form-label small fw-semibold">Phân loại đối tượng / Hành vi <span class="text-danger">*</span></label>
        <input type="text" name="loai_doi_tuong" id="loai_doi_tuong" class="form-control @error('loai_doi_tuong') is-invalid @enderror" 
               placeholder="Ví dụ: Vi phạm hành chính, Người nghiện ma túy, Bạo lực gia đình..." 
               value="{{ old('loai_doi_tuong', isset($record->loai_doi_tuong) ? ($loaiDoiTuong[$record->loai_doi_tuong] ?? $record->loai_doi_tuong) : '') }}" required>
        @error('loai_doi_tuong')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <!-- Cơ quan giải quyết -->
    <div class="col-md-6">
        <label for="co_quan_giai_quyet" class="form-label small fw-semibold">Cơ quan giải quyết / ban hành <span class="text-danger">*</span></label>
        <input type="text" name="co_quan_giai_quyet" id="co_quan_giai_quyet" class="form-control @error('co_quan_giai_quyet') is-invalid @enderror" 
               placeholder="Ví dụ: Công an xã Quốc Oai" value="{{ old('co_quan_giai_quyet', $record->co_quan_giai_quyet ?? 'Công an xã Quốc Oai') }}" required>
        @error('co_quan_giai_quyet')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Ngày ghi nhận -->
    <div class="col-md-6">
        <label for="ngay_ghi_nhan" class="form-label small fw-semibold">Ngày ghi nhận / Quyết định <span class="text-danger">*</span></label>
        <input type="date" name="ngay_ghi_nhan" id="ngay_ghi_nhan" class="form-control @error('ngay_ghi_nhan') is-invalid @enderror" 
               value="{{ old('ngay_ghi_nhan', isset($record->ngay_ghi_nhan) ? $record->ngay_ghi_nhan->format('Y-m-d') : date('Y-m-d')) }}" required>
        @error('ngay_ghi_nhan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Hình thức xử lý -->
    <div class="col-md-6">
        <label for="hinh_thuc_xu_ly" class="form-label small fw-semibold">Hình thức xử lý / Biện pháp</label>
        <input type="text" name="hinh_thuc_xu_ly" id="hinh_thuc_xu_ly" class="form-control @error('hinh_thuc_xu_ly') is-invalid @enderror" 
               placeholder="Ví dụ: Phạt tiền, Cảnh cáo, Giám sát hành vi..." value="{{ old('hinh_thuc_xu_ly', $record->hinh_thuc_xu_ly ?? '') }}">
        @error('hinh_thuc_xu_ly')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Số tiền phạt -->
    <div class="col-md-6" id="so_tien_phat_container">
        <label for="so_tien_phat" class="form-label small fw-semibold">Số tiền phạt (VNĐ)</label>
        <input type="number" name="so_tien_phat" id="so_tien_phat" class="form-control @error('so_tien_phat') is-invalid @enderror" 
               placeholder="Nhập số tiền..." value="{{ old('so_tien_phat', isset($record->so_tien_phat) ? (int)$record->so_tien_phat : '') }}">
        @error('so_tien_phat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Trạng thái -->
    <div class="col-md-6">
        <label for="trang_thai" class="form-label small fw-semibold">Trạng thái chấp hành / quản lý <span class="text-danger">*</span></label>
        <select name="trang_thai" id="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
            @foreach($trangThai as $k => $v)
                <option value="{{ $k }}" {{ old('trang_thai', $record->trang_thai ?? 'dang_quan_ly') === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        @error('trang_thai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Nội dung lý do / hành vi -->
    <div class="col-12">
        <label for="noi_dung" class="form-label small fw-semibold">Nội dung chi tiết hành vi / Lý do quản lý <span class="text-danger">*</span></label>
        <textarea name="noi_dung" id="noi_dung" rows="3" class="form-control @error('noi_dung') is-invalid @enderror" 
                  placeholder="Mô tả cụ thể hành vi vi phạm hoặc lý do đối tượng bị đưa vào danh sách giám sát đặc biệt..." required>{{ old('noi_dung', $record->noi_dung ?? '') }}</textarea>
        @error('noi_dung')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<script>
    function onNhanKhauSearchChange() {
        var searchInput = document.getElementById('nhan_khau_search');
        var hiddenInput = document.getElementById('nhan_khau_id');
        var hoTenInput = document.getElementById('ho_ten');
        var cccdInput = document.getElementById('cccd');
        var diaChiInput = document.getElementById('dia_chi');
        var list = document.getElementById('nhan_khau_list');
        var searchVal = searchInput.value;
        
        var found = false;
        for (var i = 0; i < list.options.length; i++) {
            var option = list.options[i];
            if (option.value === searchVal) {
                hiddenInput.value = option.getAttribute('data-id');
                hoTenInput.value = option.getAttribute('data-hoten') || '';
                cccdInput.value = option.getAttribute('data-cccd') || '';
                diaChiInput.value = option.getAttribute('data-diachi') || '';
                found = true;
                break;
            }
        }
        
        if (!found) {
            if (searchVal.trim() === "") {
                hiddenInput.value = "";
                hoTenInput.value = "";
                cccdInput.value = "";
                diaChiInput.value = "";
            } else {
                hiddenInput.value = "";
            }
        }
    }

    function toggleFormLogic() {
        var nhomViPhamRadio = document.getElementById('nhom_vi_pham');
        var tienContainer = document.getElementById('so_tien_phat_container');
        var tienInput = document.getElementById('so_tien_phat');
        
        var isViPham = nhomViPhamRadio.checked;
        
        if (isViPham) {
            tienContainer.style.display = 'block';
        } else {
            tienContainer.style.display = 'none';
            if (!{{ isset($record) ? 'true' : 'false' }}) {
                tienInput.value = "";
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        var hiddenInput = document.getElementById('nhan_khau_id');
        var searchInput = document.getElementById('nhan_khau_search');
        var list = document.getElementById('nhan_khau_list');
        
        if (hiddenInput && hiddenInput.value) {
            for (var i = 0; i < list.options.length; i++) {
                var option = list.options[i];
                if (option.getAttribute('data-id') == hiddenInput.value) {
                    searchInput.value = option.value;
                    break;
                }
            }
        }
        
        toggleFormLogic();
    });
</script>
