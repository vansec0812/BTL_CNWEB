<form method="POST" action="{{ $action }}" class="card shadow-sm border-0">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Vui lòng kiểm tra lại thông tin.</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">
            <!-- Chọn nhân khẩu -->
            <div class="col-lg-6">
                <label for="nhan_khau_id" class="form-label fw-semibold text-dark">Họ tên công dân <span class="text-danger">*</span></label>
                @if(isset($record) || ($isReadOnly ?? false))
                    <input type="text" class="form-control" value="{{ $record->nhanKhau->ho_ten }} - CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa có' }} (Sinh ngày: {{ $record->nhanKhau->ngay_sinh ? $record->nhanKhau->ngay_sinh->format('d/m/Y') : '—' }})" disabled>
                    <input type="hidden" name="nhan_khau_id" value="{{ $record->nhan_khau_id }}">
                @else
                    <select id="nhan_khau_id" name="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror" required>
                        <option value="">-- Chọn công dân nam chưa đăng ký NVQS --</option>
                        @foreach ($nhanKhau as $person)
                            <option value="{{ $person->id }}" @selected((string) old('nhan_khau_id') === (string) $person->id)>
                                {{ $person->ho_ten }} - CCCD: {{ $person->cccd_cmnd ?? 'Chưa có' }} (Sinh ngày: {{ $person->ngay_sinh ? $person->ngay_sinh->format('d/m/Y') : '—' }})
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Năm tuổi tuyển quân -->
            <div class="col-lg-3">
                <label for="nam_tuoi_tuyen_quan" class="form-label fw-semibold text-dark">Năm tuyển quân</label>
                <input type="number" id="nam_tuoi_tuyen_quan" name="nam_tuoi_tuyen_quan" value="{{ old('nam_tuoi_tuyen_quan', $record?->nam_tuoi_tuyen_quan ?? date('Y')) }}" class="form-control @error('nam_tuoi_tuyen_quan') is-invalid @enderror" min="1900" max="2100" @disabled($isReadOnly ?? false)>
                @error('nam_tuoi_tuyen_quan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Trạng thái NVQS -->
            <div class="col-lg-3">
                <label for="trang_thai_nvqs" class="form-label fw-semibold text-dark">Trạng thái NVQS <span class="text-danger">*</span></label>
                <select id="trang_thai_nvqs" name="trang_thai_nvqs" class="form-select @error('trang_thai_nvqs') is-invalid @enderror" required @disabled($isReadOnly ?? false)>
                    @foreach ($trangThaiNVQS as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai_nvqs', $record?->trang_thai_nvqs ?? 'du_dieu_kien') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai_nvqs')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- NHÓM PHẦN TỬ: TẠM HOÃN (Chỉ hiển thị khi trạng thái là tam_hoan) -->
            <div class="col-lg-6 cond-field field-tam_hoan">
                <label for="ly_do_tam_hoan" class="form-label fw-semibold text-dark">Lý do tạm hoãn</label>
                <select id="ly_do_tam_hoan" name="ly_do_tam_hoan" class="form-select @error('ly_do_tam_hoan') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    @foreach ($lyDoTamHoan as $value => $label)
                        <option value="{{ $value }}" @selected(old('ly_do_tam_hoan', $record?->ly_do_tam_hoan ?? 'khong_ap_dung') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('ly_do_tam_hoan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6 cond-field field-tam_hoan">
                <label for="ngay_tam_hoan_den" class="form-label fw-semibold text-dark">Tạm hoãn đến ngày</label>
                <input type="date" id="ngay_tam_hoan_den" name="ngay_tam_hoan_den" value="{{ old('ngay_tam_hoan_den', $record?->ngay_tam_hoan_den?->format('Y-m-d')) }}" class="form-control @error('ngay_tam_hoan_den') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_tam_hoan_den')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- NHÓM PHẦN TỬ: NHẬP NGŨ & XUẤT NGŨ -->
            <div class="col-lg-6 cond-field field-da_nhap_ngu field-xuat_ngu">
                <label for="ngay_nhap_ngu" class="form-label fw-semibold text-dark">Ngày nhập ngũ</label>
                <input type="date" id="ngay_nhap_ngu" name="ngay_nhap_ngu" value="{{ old('ngay_nhap_ngu', $record?->ngay_nhap_ngu?->format('Y-m-d')) }}" class="form-control @error('ngay_nhap_ngu') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_nhap_ngu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6 cond-field field-da_nhap_ngu field-xuat_ngu">
                <label for="don_vi_quan_doi" class="form-label fw-semibold text-dark">Đơn vị quân đội</label>
                <input type="text" id="don_vi_quan_doi" name="don_vi_quan_doi" value="{{ old('don_vi_quan_doi', $record?->don_vi_quan_doi) }}" class="form-control @error('don_vi_quan_doi') is-invalid @enderror" maxlength="255" @disabled($isReadOnly ?? false)>
                @error('don_vi_quan_doi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- NHÓM PHẦN TỬ: XUẤT NGŨ -->
            <div class="col-lg-6 cond-field field-xuat_ngu">
                <label for="ngay_xuat_ngu" class="form-label fw-semibold text-dark">Ngày xuất ngũ</label>
                <input type="date" id="ngay_xuat_ngu" name="ngay_xuat_ngu" value="{{ old('ngay_xuat_ngu', $record?->ngay_xuat_ngu?->format('Y-m-d')) }}" class="form-control @error('ngay_xuat_ngu') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_xuat_ngu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6 cond-field field-xuat_ngu">
                <label for="quan_ham_khi_xuat_ngu" class="form-label fw-semibold text-dark">Quân hàm khi xuất ngũ</label>
                <input type="text" id="quan_ham_khi_xuat_ngu" name="quan_ham_khi_xuat_ngu" value="{{ old('quan_ham_khi_xuat_ngu', $record?->quan_ham_khi_xuat_ngu) }}" class="form-control @error('quan_ham_khi_xuat_ngu') is-invalid @enderror" maxlength="100" @disabled($isReadOnly ?? false)>
                @error('quan_ham_khi_xuat_ngu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- NHÓM PHẦN TỬ CHUNG: KHÁM SỨC KHỎE -->
            <div class="col-lg-6">
                <label for="nam_dang_ky_kham_nvqs" class="form-label fw-semibold text-dark">Năm khám sức khỏe</label>
                <input type="number" id="nam_dang_ky_kham_nvqs" name="nam_dang_ky_kham_nvqs" value="{{ old('nam_dang_ky_kham_nvqs', $record?->nam_dang_ky_kham_nvqs) }}" class="form-control @error('nam_dang_ky_kham_nvqs') is-invalid @enderror" min="1900" max="2100" @disabled($isReadOnly ?? false)>
                @error('nam_dang_ky_kham_nvqs')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ket_qua_kham_suc_khoe" class="form-label fw-semibold text-dark">Kết quả khám sức khỏe</label>
                <select id="ket_qua_kham_suc_khoe" name="ket_qua_kham_suc_khoe" class="form-select @error('ket_qua_kham_suc_khoe') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    @foreach ($ketQuaKham as $value => $label)
                        <option value="{{ $value }}" @selected(old('ket_qua_kham_suc_khoe', $record?->ket_qua_kham_suc_khoe ?? 'chua_kham') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('ket_qua_kham_suc_khoe')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Ghi chú -->
            <div class="col-12">
                <label for="ghi_chu" class="form-label fw-semibold text-dark">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror" @disabled($isReadOnly ?? false)>{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-white d-flex justify-content-between py-3">
        <a href="{{ route('nghia-vu-quan-su.index') }}" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
        @if (!($isReadOnly ?? false))
            <button class="btn btn-success fw-semibold px-4" type="submit"><i class="bi bi-check-lg me-1"></i>{{ $submitLabel }}</button>
        @else
            @can('manage_nghia_vu')
                <a href="{{ route('nghia-vu-quan-su.edit', $record) }}" class="btn btn-success fw-semibold px-4"><i class="bi bi-pencil me-1"></i>Chỉnh sửa hồ sơ</a>
            @endcan
        @endif
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('trang_thai_nvqs');
    
    function toggleConditionalFields() {
        const status = statusSelect.value;
        
        document.querySelectorAll('.cond-field').forEach(field => {
            field.style.display = 'none';
            field.querySelectorAll('input, select').forEach(input => {
                input.disabled = true;
            });
        });
        
        const fieldsToShow = document.querySelectorAll('.field-' + status);
        fieldsToShow.forEach(field => {
            field.style.display = 'block';
            const isReadOnly = @json($isReadOnly ?? false);
            if (!isReadOnly) {
                field.querySelectorAll('input, select').forEach(input => {
                    input.disabled = false;
                });
            }
        });
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', toggleConditionalFields);
        toggleConditionalFields(); // Run once on load
    }
});
</script>
