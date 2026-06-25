<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body p-4">
        <div class="row g-3">
            {{-- Tên cơ sở --}}
            <div class="col-lg-6">
                <label for="ten_co_so" class="form-label fw-semibold">Tên cơ sở kinh doanh / Doanh nghiệp <span class="text-danger">*</span></label>
                <input type="text" id="ten_co_so" name="ten_co_so" value="{{ old('ten_co_so', $record?->ten_co_so) }}" class="form-control @error('ten_co_so') is-invalid @enderror" required placeholder="Tên đầy đủ của doanh nghiệp/HTX/Hộ kinh doanh...">
                @error('ten_co_so')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Loại hình --}}
            <div class="col-lg-6">
                <label for="loai_hinh" class="form-label fw-semibold">Loại hình kinh doanh <span class="text-danger">*</span></label>
                <select id="loai_hinh" name="loai_hinh" class="form-select @error('loai_hinh') is-invalid @enderror" required>
                    @foreach ($loaiHinh as $value => $label)
                        <option value="{{ $value }}" @selected(old('loai_hinh', $record?->loai_hinh ?? 'ho_kinh_doanh_ca_the') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loai_hinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Mã số thuế --}}
            <div class="col-lg-4">
                <label for="ma_so_thue" class="form-label fw-semibold">Mã số thuế</label>
                <input type="text" id="ma_so_thue" name="ma_so_thue" value="{{ old('ma_so_thue', $record?->ma_so_thue) }}" class="form-control @error('ma_so_thue') is-invalid @enderror" placeholder="Nhập MST (nếu có)...">
                @error('ma_so_thue')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Số ĐKKD --}}
            <div class="col-lg-4">
                <label for="ma_so_dang_ky_kinh_doanh" class="form-label fw-semibold">Số đăng ký kinh doanh</label>
                <input type="text" id="ma_so_dang_ky_kinh_doanh" name="ma_so_dang_ky_kinh_doanh" value="{{ old('ma_so_dang_ky_kinh_doanh', $record?->ma_so_dang_ky_kinh_doanh) }}" class="form-control @error('ma_so_dang_ky_kinh_doanh') is-invalid @enderror" placeholder="Số chứng nhận ĐKKD...">
                @error('ma_so_dang_ky_kinh_doanh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Ngành nghề chính --}}
            <div class="col-lg-4">
                <label for="nganh_nghe_chinh" class="form-label fw-semibold">Ngành nghề chính</label>
                <input type="text" id="nganh_nghe_chinh" name="nganh_nghe_chinh" value="{{ old('nganh_nghe_chinh', $record?->nganh_nghe_chinh) }}" class="form-control @error('nganh_nghe_chinh') is-invalid @enderror" placeholder="Ví dụ: Nông nghiệp, May mặc, Bán lẻ...">
                @error('nganh_nghe_chinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Địa chỉ --}}
            <div class="col-lg-8">
                <label for="dia_chi" class="form-label fw-semibold">Địa chỉ trụ sở</label>
                <input type="text" id="dia_chi" name="dia_chi" value="{{ old('dia_chi', $record?->dia_chi) }}" class="form-control @error('dia_chi') is-invalid @enderror" placeholder="Số nhà, đường phố...">
                @error('dia_chi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Thôn xóm đặt trụ sở --}}
            <div class="col-lg-4">
                <label for="thon_xom" class="form-label fw-semibold">Thôn/Xóm địa bàn xã <span class="text-danger">*</span></label>
                <input type="text" id="thon_xom" name="thon_xom" value="{{ old('thon_xom', $record?->thon_xom) }}" class="form-control @error('thon_xom') is-invalid @enderror" required placeholder="Thôn đóng chân của doanh nghiệp...">
                @error('thon_xom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Người đại diện là công dân xã --}}
            <div class="col-lg-6">
                <label for="nguoi_dai_dien_nhan_khau_id" class="form-label fw-semibold">Người đại diện (Nếu là công dân địa bàn xã)</label>
                <select id="nguoi_dai_dien_nhan_khau_id" name="nguoi_dai_dien_nhan_khau_id" class="form-select @error('nguoi_dai_dien_nhan_khau_id') is-invalid @enderror">
                    <option value="">Chọn nhân khẩu</option>
                    @foreach ($nhanKhau as $person)
                        <option value="{{ $person->id }}" @selected((string) old('nguoi_dai_dien_nhan_khau_id', $record?->nguoi_dai_dien_nhan_khau_id) === (string) $person->id)>
                            {{ $person->ho_ten }} ({{ $person->cccd_cmnd ?? 'Không có CCCD' }})
                        </option>
                    @endforeach
                </select>
                @error('nguoi_dai_dien_nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Tên người đại diện pháp luật --}}
            <div class="col-lg-6">
                <label for="ten_nguoi_dai_dien" class="form-label fw-semibold">Tên người đại diện (Tự do/Vãng lai)</label>
                <input type="text" id="ten_nguoi_dai_dien" name="ten_nguoi_dai_dien" value="{{ old('ten_nguoi_dai_dien', $record?->ten_nguoi_dai_dien) }}" class="form-control @error('ten_nguoi_dai_dien') is-invalid @enderror" placeholder="Họ tên người đại diện...">
                @error('ten_nguoi_dai_dien')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Số điện thoại --}}
            <div class="col-lg-4">
                <label for="so_dien_thoai_lien_he" class="form-label fw-semibold">Số điện thoại liên hệ</label>
                <input type="text" id="so_dien_thoai_lien_he" name="so_dien_thoai_lien_he" value="{{ old('so_dien_thoai_lien_he', $record?->so_dien_thoai_lien_he) }}" class="form-control @error('so_dien_thoai_lien_he') is-invalid @enderror" placeholder="SĐT liên lạc...">
                @error('so_dien_thoai_lien_he')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Ngày thành lập --}}
            <div class="col-lg-4">
                <label for="ngay_thanh_lap" class="form-label fw-semibold">Ngày thành lập / Cấp phép</label>
                <input type="date" id="ngay_thanh_lap" name="ngay_thanh_lap" value="{{ old('ngay_thanh_lap', $record?->ngay_thanh_lap ? $record->ngay_thanh_lap->format('Y-m-d') : '') }}" class="form-control @error('ngay_thanh_lap') is-invalid @enderror">
                @error('ngay_thanh_lap')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Trạng thái --}}
            <div class="col-lg-4">
                <label for="trang_thai" class="form-label fw-semibold">Trạng thái hoạt động <span class="text-danger">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai', $record?->trang_thai ?? 'dang_hoat_dong') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Lao động hiện tại --}}
            <div class="col-lg-6">
                <label for="so_lao_dong_hien_tai" class="form-label fw-semibold">Số lao động hiện tại</label>
                <input type="number" id="so_lao_dong_hien_tai" name="so_lao_dong_hien_tai" value="{{ old('so_lao_dong_hien_tai', $record?->so_lao_dong_hien_tai ?? 0) }}" class="form-control @error('so_lao_dong_hien_tai') is-invalid @enderror" min="0">
                @error('so_lao_dong_hien_tai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Vị trí tuyển dụng --}}
            <div class="col-lg-6">
                <label for="so_vi_tri_tuyen_dung" class="form-label fw-semibold">Số vị trí đang tuyển dụng</label>
                <input type="number" id="so_vi_tri_tuyen_dung" name="so_vi_tri_tuyen_dung" value="{{ old('so_vi_tri_tuyen_dung', $record?->so_vi_tri_tuyen_dung ?? 0) }}" class="form-control @error('so_vi_tri_tuyen_dung') is-invalid @enderror" min="0">
                @error('so_vi_tri_tuyen_dung')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Ghi chú --}}
            <div class="col-12 border-top pt-3">
                <label for="ghi_chu" class="form-label fw-semibold">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between py-3">
        <a href="{{ route('doanh-nghiep.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button class="btn btn-success px-4" type="submit">{{ $submitLabel }}</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const citizenSelect = document.getElementById('nguoi_dai_dien_nhan_khau_id');
        const textInput = document.getElementById('ten_nguoi_dai_dien');

        // Khi chọn nhân khẩu trong danh sách thì tự động điền họ tên sang input text đại diện
        citizenSelect.addEventListener('change', function() {
            if (citizenSelect.value) {
                const selectedText = citizenSelect.options[citizenSelect.selectedIndex].text;
                // Lấy phần tên trước ngoặc đơn
                const name = selectedText.split('(')[0].trim();
                textInput.value = name;
            }
        });
    });
</script>
