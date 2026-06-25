<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body p-4">

        <h5 class="fw-bold mb-3 text-success"><i class="bi bi-person-badge me-2"></i>Thông tin cơ bản</h5>
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input id="ho_ten" name="ho_ten" value="{{ old('ho_ten', $record?->ho_ten) }}" class="form-control @error('ho_ten') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false) placeholder="Ví dụ: Nguyễn Văn An">
                @error('ho_ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="cccd_cmnd" class="form-label">Số CCCD / CMND / Mã định danh</label>
                <input id="cccd_cmnd" name="cccd_cmnd" value="{{ old('cccd_cmnd', $record?->cccd_cmnd) }}" class="form-control @error('cccd_cmnd') is-invalid @enderror" @disabled($isReadOnly ?? false) placeholder="12 chữ số quốc gia hoặc 9 số CMND">
                @error('cccd_cmnd')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="ngay_sinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                <input type="date" id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh', $record?->ngay_sinh?->format('Y-m-d')) }}" class="form-control @error('ngay_sinh') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                @error('ngay_sinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-3">
                <label for="gioi_tinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                <select id="gioi_tinh" name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($gioiTinh as $value => $label)
                        <option value="{{ $value }}" @selected(old('gioi_tinh', $record?->gioi_tinh) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('gioi_tinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-3">
                <label for="dan_toc" class="form-label">Dân tộc <span class="text-danger">*</span></label>
                <input id="dan_toc" name="dan_toc" value="{{ old('dan_toc', $record?->dan_toc ?? 'Kinh') }}" class="form-control @error('dan_toc') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                @error('dan_toc')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-3">
                <label for="ton_giao" class="form-label">Tôn giáo</label>
                <input id="ton_giao" name="ton_giao" value="{{ old('ton_giao', $record?->ton_giao ?? 'Không') }}" class="form-control @error('ton_giao') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ton_giao')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-3">
                <label for="tinh_trang_hon_nhan" class="form-label">Tình trạng hôn nhân <span class="text-danger">*</span></label>
                <select id="tinh_trang_hon_nhan" name="tinh_trang_hon_nhan" class="form-select @error('tinh_trang_hon_nhan') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($tinhTrangHonNhan as $value => $label)
                        <option value="{{ $value }}" @selected(old('tinh_trang_hon_nhan', $record?->tinh_trang_hon_nhan) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('tinh_trang_hon_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="que_quan" class="form-label">Quê quán</label>
                <input id="que_quan" name="que_quan" value="{{ old('que_quan', $record?->que_quan) }}" class="form-control @error('que_quan') is-invalid @enderror" @disabled($isReadOnly ?? false) placeholder="Tỉnh/Thành phố, Huyện/Quận gốc gác">
                @error('que_quan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="noi_sinh" class="form-label">Nơi sinh</label>
                <input id="noi_sinh" name="noi_sinh" value="{{ old('noi_sinh', $record?->noi_sinh) }}" class="form-control @error('noi_sinh') is-invalid @enderror" @disabled($isReadOnly ?? false) placeholder="Bệnh viện hoặc địa chỉ khai sinh">
                @error('noi_sinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success"><i class="bi bi-house-check-fill me-2"></i>Hộ khẩu & Trạng thái</h5>
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <label for="ho_khau_id" class="form-label">Sổ hộ khẩu liên kết <span class="text-danger">*</span></label>
                <select id="ho_khau_id" name="ho_khau_id" class="form-select @error('ho_khau_id') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    <option value="">Chọn sổ hộ khẩu</option>
                    @foreach ($hoKhauList as $hk)
                        <option value="{{ $hk->id }}" @selected(old('ho_khau_id', $record?->ho_khau_id) == $hk->id)>
                            {{ $hk->so_so_ho_khau }} (Mã hộ: {{ $hk->ma_ho }}) - {{ Str::limit($hk->dia_chi_thuong_tru, 30) }}
                        </option>
                    @endforeach
                </select>
                @error('ho_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="quan_he_chu_ho" class="form-label">Quan hệ với chủ hộ</label>
                <input id="quan_he_chu_ho" name="quan_he_chu_ho" value="{{ old('quan_he_chu_ho', $record?->quan_he_chu_ho) }}" class="form-control @error('quan_he_chu_ho') is-invalid @enderror" @disabled($isReadOnly ?? false) placeholder="Ví dụ: Vợ, Con trai, Cháu nội...">
                @error('quan_he_chu_ho')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="trang_thai" class="form-label">Trạng thái cư trú <span class="text-danger">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai', $record?->trang_thai ?? 'hoat_dong') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4 d-flex align-items-center pt-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="la_chu_ho" value="0">
                    <input class="form-check-input" type="checkbox" role="switch" id="la_chu_ho" name="la_chu_ho" value="1" @checked(old('la_chu_ho', $record?->la_chu_ho)) @disabled($isReadOnly ?? false)>
                    <label class="form-check-label fw-semibold" for="la_chu_ho">Đặt người này làm Chủ Hộ</label>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success"><i class="bi bi-mortarboard-fill me-2"></i>Trình độ & Hồ sơ tư pháp</h5>
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <label for="trinh_do_hoc_van" class="form-label">Trình độ học vấn cao nhất</label>
                <select id="trinh_do_hoc_van" name="trinh_do_hoc_van" class="form-select @error('trinh_do_hoc_van') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    <option value="">Chọn trình độ</option>
                    @foreach ($trinhDoHocVan as $value => $label)
                        <option value="{{ $value }}" @selected(old('trinh_do_hoc_van', $record?->trinh_do_hoc_van) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trinh_do_hoc_van')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4 d-flex align-items-center pt-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="co_tien_an" value="0">
                    <input class="form-check-input" type="checkbox" role="switch" id="co_tien_an" name="co_tien_an" value="1" @checked(old('co_tien_an', $record?->co_tien_an)) @disabled($isReadOnly ?? false) onchange="toggleTienAn(this.checked)">
                    <label class="form-check-label fw-semibold text-danger" for="co_tien_an">Có tiền án / tiền sự</label>
                </div>
            </div>

            <div class="col-lg-12" id="ghi_chu_tien_an_wrapper" style="display: {{ old('co_tien_an', $record?->co_tien_an) ? 'block' : 'none' }};">
                <label for="ghi_chu_tien_an" class="form-label text-danger fw-semibold">Chi tiết tiền án tiền sự (Bảo mật)</label>
                <textarea id="ghi_chu_tien_an" name="ghi_chu_tien_an" rows="3" class="form-control @error('ghi_chu_tien_an') is-invalid @enderror" @disabled($isReadOnly ?? false) placeholder="Nội dung vi phạm, tội danh, hình phạt, thời gian chấp hành án...">{{ old('ghi_chu_tien_an', $record?->ghi_chu_tien_an) }}</textarea>
                @error('ghi_chu_tien_an')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-success"><i class="bi bi-calendar-event me-2"></i>Mốc thời gian hành chính</h5>
        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <label for="ngay_dang_ky_khai_sinh" class="form-label">Ngày khai sinh</label>
                <input type="date" id="ngay_dang_ky_khai_sinh" name="ngay_dang_ky_khai_sinh" value="{{ old('ngay_dang_ky_khai_sinh', $record?->ngay_dang_ky_khai_sinh?->format('Y-m-d')) }}" class="form-control @error('ngay_dang_ky_khai_sinh') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_dang_ky_khai_sinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="ngay_chuyen_di" class="form-label">Ngày chuyển đi</label>
                <input type="date" id="ngay_chuyen_di" name="ngay_chuyen_di" value="{{ old('ngay_chuyen_di', $record?->ngay_chuyen_di?->format('Y-m-d')) }}" class="form-control @error('ngay_chuyen_di') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_chuyen_di')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="ngay_khai_tu" class="form-label">Ngày báo mất / khai tử</label>
                <input type="date" id="ngay_khai_tu" name="ngay_khai_tu" value="{{ old('ngay_khai_tu', $record?->ngay_khai_tu?->format('Y-m-d')) }}" class="form-control @error('ngay_khai_tu') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_khai_tu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú thêm</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror" @disabled($isReadOnly ?? false)>{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between p-3">
        <a href="{{ route('nhan-khau.index') }}" class="btn btn-outline-secondary px-4">Quay lại</a>
        @if (!($isReadOnly ?? false))
            <button class="btn btn-success px-4" type="submit">{{ $submitLabel }}</button>
        @else
            <a href="{{ route('nhan-khau.edit', $record) }}" class="btn btn-primary px-4">Chỉnh sửa thông tin</a>
        @endif
    </div>
</form>

<script>
    function toggleTienAn(checked) {
        const wrapper = document.getElementById('ghi_chu_tien_an_wrapper');
        if (wrapper) {
            wrapper.style.display = checked ? 'block' : 'none';
            if (!checked) {
                document.getElementById('ghi_chu_tien_an').value = '';
            }
        }
    }
</script>
