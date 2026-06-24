<form method="POST" action="{{ $action }}" class="card shadow-sm border-0 labor-profile-form" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-header bg-white py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <span class="form-section-title mb-0">
                <i class="bi bi-person-workspace"></i>
                Thông tin hồ sơ
            </span>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row g-3">
            {{-- Nhân khẩu --}}
            <div class="col-lg-6">
                <label for="nhan_khau_id" class="form-label fw-semibold">Nhân khẩu <span class="text-danger">*</span></label>
                @if (isset($record))
                    <input type="hidden" name="nhan_khau_id" value="{{ $record->nhan_khau_id }}">
                    <input type="text" class="form-control" value="{{ $record->nhanKhau->ho_ten }} - {{ $record->nhanKhau->cccd_cmnd ?? 'Không có CCCD' }}" readonly disabled>
                @else
                    <select id="nhan_khau_id" name="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror" required>
                        <option value="">Chọn nhân khẩu</option>
                        @foreach ($nhanKhau as $person)
                            <option value="{{ $person->id }}" @selected((string) old('nhan_khau_id') === (string) $person->id)>
                                {{ $person->ho_ten }} ({{ $person->cccd_cmnd ?? 'Không có CCCD' }})
                            </option>
                        @endforeach
                    </select>
                    @error('nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @endif
            </div>

            {{-- Trạng thái lao động --}}
            <div class="col-lg-6">
                <label for="trang_thai_lao_dong" class="form-label fw-semibold">Trạng thái lao động <span class="text-danger">*</span></label>
                <select id="trang_thai_lao_dong" name="trang_thai_lao_dong" class="form-select @error('trang_thai_lao_dong') is-invalid @enderror" required>
                    @foreach ($trangThaiLaoDong as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai_lao_dong', $record?->trang_thai_lao_dong ?? 'co_viec_lam') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai_lao_dong')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Nghề nghiệp cụ thể --}}
            <div class="col-lg-4 job-fields">
                <label for="nghe_nghiep" class="form-label fw-semibold">Nghề nghiệp cụ thể</label>
                <input type="text" id="nghe_nghiep" name="nghe_nghiep" value="{{ old('nghe_nghiep', $record?->nghe_nghiep) }}" class="form-control @error('nghe_nghiep') is-invalid @enderror" placeholder="Ví dụ: Công nhân may, Kế toán...">
                @error('nghe_nghiep')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Loại hình công việc --}}
            <div class="col-lg-4 job-fields">
                <label for="loai_hinh_cong_viec" class="form-label fw-semibold">Loại hình công việc</label>
                <select id="loai_hinh_cong_viec" name="loai_hinh_cong_viec" class="form-select @error('loai_hinh_cong_viec') is-invalid @enderror">
                    <option value="">Chọn loại hình</option>
                    @foreach ($loaiHinhCongViec as $value => $label)
                        <option value="{{ $value }}" @selected(old('loai_hinh_cong_viec', $record?->loai_hinh_cong_viec) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loai_hinh_cong_viec')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Ngành nghề lĩnh vực --}}
            <div class="col-lg-4 job-fields">
                <label for="nganh_nghe" class="form-label fw-semibold">Ngành nghề, Lĩnh vực</label>
                <select id="nganh_nghe" name="nganh_nghe" class="form-select @error('nganh_nghe') is-invalid @enderror">
                    <option value="">Chọn ngành nghề</option>
                    @foreach ($nganhNghe as $value => $label)
                        <option value="{{ $value }}" @selected(old('nganh_nghe', $record?->nganh_nghe) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('nganh_nghe')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Switches: Làm việc xa, xuất khẩu --}}
            <div class="col-md-6">
                <div class="choice-panel">
                <div class="form-check form-switch py-1">
                    <input type="hidden" name="lam_viec_ngoai_tinh" value="0">
                    <input type="checkbox" class="form-check-input" id="lam_viec_ngoai_tinh" name="lam_viec_ngoai_tinh" value="1" @checked(old('lam_viec_ngoai_tinh', $record?->lam_viec_ngoai_tinh ?? false))>
                    <label class="form-check-label fw-semibold" for="lam_viec_ngoai_tinh">Đang làm việc ngoài tỉnh (trong nước)</label>
                </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="choice-panel">
                <div class="form-check form-switch py-1">
                    <input type="hidden" name="xuat_khau_lao_dong" value="0">
                    <input type="checkbox" class="form-check-input" id="xuat_khau_lao_dong" name="xuat_khau_lao_dong" value="1" @checked(old('xuat_khau_lao_dong', $record?->xuat_khau_lao_dong ?? false))>
                    <label class="form-check-label fw-semibold" for="xuat_khau_lao_dong">Đang xuất khẩu lao động (nước ngoài)</label>
                </div>
                </div>
            </div>

            {{-- Thông tin làm việc ngoài tỉnh --}}
            <div class="col-12 out-province-section" style="display: none;">
                <div class="form-section">
                <h6 class="form-section-title"><i class="bi bi-geo-alt"></i>Thông tin làm việc ngoài tỉnh</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tinh_thanh_lam_viec" class="form-label">Tỉnh/Thành phố đang làm việc</label>
                        <input type="text" id="tinh_thanh_lam_viec" name="tinh_thanh_lam_viec" value="{{ old('tinh_thanh_lam_viec', $record?->tinh_thanh_lam_viec) }}" class="form-control @error('tinh_thanh_lam_viec') is-invalid @enderror" placeholder="Ví dụ: Hà Nội, TP. Hồ Chí Minh...">
                        @error('tinh_thanh_lam_viec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                </div>
            </div>

            {{-- Thông tin xuất khẩu lao động --}}
            <div class="col-12 export-section" style="display: none;">
                <div class="form-section">
                <h6 class="form-section-title"><i class="bi bi-airplane"></i>Thông tin xuất khẩu lao động</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="quoc_gia_lam_viec" class="form-label">Quốc gia đang làm việc</label>
                        <input type="text" id="quoc_gia_lam_viec" name="quoc_gia_lam_viec" value="{{ old('quoc_gia_lam_viec', $record?->quoc_gia_lam_viec) }}" class="form-control @error('quoc_gia_lam_viec') is-invalid @enderror" placeholder="Ví dụ: Nhật Bản, Hàn Quốc...">
                        @error('quoc_gia_lam_viec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label for="ten_cong_ty_nuoc_ngoai" class="form-label">Tên công ty / Nghiệp đoàn nước ngoài</label>
                        <input type="text" id="ten_cong_ty_nuoc_ngoai" name="ten_cong_ty_nuoc_ngoai" value="{{ old('ten_cong_ty_nuoc_ngoai', $record?->ten_cong_ty_nuoc_ngoai) }}" class="form-control @error('ten_cong_ty_nuoc_ngoai') is-invalid @enderror" placeholder="Tên đối tác hoặc nơi làm việc ở nước ngoài">
                        @error('ten_cong_ty_nuoc_ngoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="ngay_xuat_canh" class="form-label">Ngày xuất cảnh</label>
                        <input type="date" id="ngay_xuat_canh" name="ngay_xuat_canh" value="{{ old('ngay_xuat_canh', $record?->ngay_xuat_canh ? $record->ngay_xuat_canh->format('Y-m-d') : '') }}" class="form-control @error('ngay_xuat_canh') is-invalid @enderror">
                        @error('ngay_xuat_canh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="ngay_het_hop_dong_nuoc_ngoai" class="form-label">Ngày hết hạn hợp đồng</label>
                        <input type="date" id="ngay_het_hop_dong_nuoc_ngoai" name="ngay_het_hop_dong_nuoc_ngoai" value="{{ old('ngay_het_hop_dong_nuoc_ngoai', $record?->ngay_het_hop_dong_nuoc_ngoai ? $record->ngay_het_hop_dong_nuoc_ngoai->format('Y-m-d') : '') }}" class="form-control @error('ngay_het_hop_dong_nuoc_ngoai') is-invalid @enderror">
                        @error('ngay_het_hop_dong_nuoc_ngoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                </div>
            </div>

            {{-- Lý do thay đổi (Chỉ hiển thị khi cập nhật) --}}
            @if (isset($record))
            <div class="col-12">
                <div class="form-section">
                <label for="ly_do_thay_doi" class="form-label fw-semibold text-danger">Lý do cập nhật hồ sơ (Lưu vào lịch sử công việc)</label>
                <input type="text" id="ly_do_thay_doi" name="ly_do_thay_doi" value="{{ old('ly_do_thay_doi') }}" class="form-control @error('ly_do_thay_doi') is-invalid @enderror" placeholder="Ví dụ: Thay đổi công việc từ nông dân sang công nhân may, đi XKLD...">
                @error('ly_do_thay_doi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            @endif

            {{-- Ghi chú --}}
            <div class="col-12">
                <div class="form-section">
                <label for="ghi_chu" class="form-label fw-semibold">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between py-3">
        <a href="{{ route('ho-so.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button class="btn btn-success px-4" type="submit">{{ $submitLabel }}</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const outProvinceSwitch = document.getElementById('lam_viec_ngoai_tinh');
        const exportSwitch = document.getElementById('xuat_khau_lao_dong');
        const outProvinceSection = document.querySelector('.out-province-section');
        const exportSection = document.querySelector('.export-section');
        const trangThaiSelect = document.getElementById('trang_thai_lao_dong');
        const jobFields = document.querySelectorAll('.job-fields');

        function toggleSections() {
            if (outProvinceSwitch.checked) {
                outProvinceSection.style.display = 'block';
            } else {
                outProvinceSection.style.display = 'none';
            }

            if (exportSwitch.checked) {
                exportSection.style.display = 'block';
            } else {
                exportSection.style.display = 'none';
            }
        }

        function toggleJobFields() {
            const status = trangThaiSelect.value;
            // Nếu thất nghiệp, học sinh sinh viên, mất sức, chưa đến tuổi thì ẩn/vô hiệu hóa trường công việc
            const hideJobFields = ['that_nghiep', 'hoc_sinh_sinh_vien', 'mat_suc_lao_dong', 'chua_den_tuoi_lao_dong', 'noi_tro'].includes(status);
            
            jobFields.forEach(field => {
                const inputs = field.querySelectorAll('input, select');
                if (hideJobFields) {
                    field.style.opacity = '0.5';
                    inputs.forEach(input => {
                        input.disabled = true;
                        input.value = '';
                    });
                } else {
                    field.style.opacity = '1';
                    inputs.forEach(input => input.disabled = false);
                }
            });
        }

        outProvinceSwitch.addEventListener('change', toggleSections);
        exportSwitch.addEventListener('change', toggleSections);
        trangThaiSelect.addEventListener('change', toggleJobFields);

        // Chạy lúc khởi tạo
        toggleSections();
        toggleJobFields();
    });
</script>
