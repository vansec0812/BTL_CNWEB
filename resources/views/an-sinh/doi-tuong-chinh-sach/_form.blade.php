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
            <div class="col-lg-6">
                <label for="nhan_khau_id" class="form-label">Nhân khẩu thuộc diện chính sách <span class="text-danger">*</span></label>
                <select id="nhan_khau_id" name="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    <option value="">Chọn nhân khẩu</option>
                    @foreach ($nhanKhau as $person)
                        <option value="{{ $person->id }}" @selected((string) old('nhan_khau_id', $record?->nhan_khau_id) === (string) $person->id)>
                            {{ $person->ho_ten }}{{ $person->cccd_cmnd ? ' - '.$person->cccd_cmnd : '' }}
                        </option>
                    @endforeach
                </select>
                @error('nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="loai_chinh_sach" class="form-label">Loại chính sách <span class="text-danger">*</span></label>
                <select id="loai_chinh_sach" name="loai_chinh_sach" class="form-select @error('loai_chinh_sach') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($loaiChinhSach as $value => $label)
                        <option value="{{ $value }}" @selected(old('loai_chinh_sach', $record?->loai_chinh_sach ?? 'thuong_binh') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loai_chinh_sach')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="so_quyet_dinh_cong_nhan" class="form-label">Số quyết định công nhận</label>
                <input id="so_quyet_dinh_cong_nhan" name="so_quyet_dinh_cong_nhan" value="{{ old('so_quyet_dinh_cong_nhan', $record?->so_quyet_dinh_cong_nhan) }}" class="form-control @error('so_quyet_dinh_cong_nhan') is-invalid @enderror" @disabled($isReadOnly ?? false) maxlength="100">
                @error('so_quyet_dinh_cong_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="ngay_cong_nhan" class="form-label">Ngày công nhận</label>
                <input type="date" id="ngay_cong_nhan" name="ngay_cong_nhan" value="{{ old('ngay_cong_nhan', $record?->ngay_cong_nhan?->format('Y-m-d')) }}" class="form-control @error('ngay_cong_nhan') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_cong_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="co_quan_cap" class="form-label">Cơ quan cấp</label>
                <input id="co_quan_cap" name="co_quan_cap" value="{{ old('co_quan_cap', $record?->co_quan_cap) }}" class="form-control @error('co_quan_cap') is-invalid @enderror" @disabled($isReadOnly ?? false) maxlength="255">
                @error('co_quan_cap')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="ty_le_thuong_tat" class="form-label">Tỷ lệ thương tật (%)</label>
                <input type="number" step="0.01" min="0" max="100" id="ty_le_thuong_tat" name="ty_le_thuong_tat" value="{{ old('ty_le_thuong_tat', $record?->ty_le_thuong_tat) }}" class="form-control @error('ty_le_thuong_tat') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ty_le_thuong_tat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="muc_tro_cap_hang_thang" class="form-label">Mức trợ cấp hằng tháng</label>
                <input type="number" min="0" id="muc_tro_cap_hang_thang" name="muc_tro_cap_hang_thang" value="{{ old('muc_tro_cap_hang_thang', $record?->muc_tro_cap_hang_thang) }}" class="form-control @error('muc_tro_cap_hang_thang') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('muc_tro_cap_hang_thang')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai', $record?->trang_thai ?? 'dang_huong_che_do') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="4" class="form-control @error('ghi_chu') is-invalid @enderror" @disabled($isReadOnly ?? false)>{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between">
        <a href="{{ route('doi-tuong-chinh-sach.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        @if (!($isReadOnly ?? false))
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
        @else
            <a href="{{ route('doi-tuong-chinh-sach.edit', $record) }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
        @endif
    </div>
</form>
