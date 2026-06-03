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
                <label for="so_so_ho_khau" class="form-label">Số sổ hộ khẩu <span class="text-danger">*</span></label>
                <input id="so_so_ho_khau" name="so_so_ho_khau" value="{{ old('so_so_ho_khau', $record?->so_so_ho_khau) }}" class="form-control @error('so_so_ho_khau') is-invalid @enderror" required maxlength="50">
                @error('so_so_ho_khau')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ma_ho" class="form-label">Mã hộ <span class="text-danger">*</span></label>
                <input id="ma_ho" name="ma_ho" value="{{ old('ma_ho', $record?->ma_ho) }}" class="form-control @error('ma_ho') is-invalid @enderror" required maxlength="30">
                @error('ma_ho')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="chu_ho_nhan_khau_id" class="form-label">Chủ hộ</label>
                <select id="chu_ho_nhan_khau_id" name="chu_ho_nhan_khau_id" class="form-select @error('chu_ho_nhan_khau_id') is-invalid @enderror">
                    <option value="">Chọn chủ hộ (Nhân khẩu)</option>
                    @foreach ($nhanKhau as $person)
                        <option value="{{ $person->id }}" @selected((string) old('chu_ho_nhan_khau_id', $record?->chu_ho_nhan_khau_id) === (string) $person->id)>
                            {{ $person->ho_ten }}{{ $person->cccd_cmnd ? ' - '.$person->cccd_cmnd : '' }}
                        </option>
                    @endforeach
                </select>
                @error('chu_ho_nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="thon_xom" class="form-label">Thôn/Xóm/Đội</label>
                <input id="thon_xom" name="thon_xom" value="{{ old('thon_xom', $record?->thon_xom) }}" class="form-control @error('thon_xom') is-invalid @enderror" maxlength="100" placeholder="Ví dụ: Thôn 1">
                @error('thon_xom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-12">
                <label for="dia_chi_thuong_tru" class="form-label">Địa chỉ thường trú <span class="text-danger">*</span></label>
                <input id="dia_chi_thuong_tru" name="dia_chi_thuong_tru" value="{{ old('dia_chi_thuong_tru', $record?->dia_chi_thuong_tru) }}" class="form-control @error('dia_chi_thuong_tru') is-invalid @enderror" required maxlength="500" placeholder="Số nhà, đường/phố, thôn/xóm, xã Quốc Oai...">
                @error('dia_chi_thuong_tru')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="phan_loai" class="form-label">Phân loại hộ <span class="text-danger">*</span></label>
                <select id="phan_loai" name="phan_loai" class="form-select @error('phan_loai') is-invalid @enderror" required>
                    @foreach ($phanLoai as $value => $label)
                        <option value="{{ $value }}" @selected(old('phan_loai', $record?->phan_loai ?? 'thuong_tru') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('phan_loai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="so_thanh_vien" class="form-label">Số thành viên</label>
                <input type="number" min="0" id="so_thanh_vien" name="so_thanh_vien" value="{{ old('so_thanh_vien', $record?->so_thanh_vien ?? 0) }}" class="form-control @error('so_thanh_vien') is-invalid @enderror">
                @error('so_thanh_vien')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai', $record?->trang_thai ?? 'hoat_dong') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ngay_lap_so" class="form-label">Ngày lập sổ</label>
                <input type="date" id="ngay_lap_so" name="ngay_lap_so" value="{{ old('ngay_lap_so', $record?->ngay_lap_so?->format('Y-m-d')) }}" class="form-control @error('ngay_lap_so') is-invalid @enderror">
                @error('ngay_lap_so')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ngay_cap_nhat" class="form-label">Ngày cập nhật gần nhất</label>
                <input type="date" id="ngay_cap_nhat" name="ngay_cap_nhat" value="{{ old('ngay_cap_nhat', $record?->ngay_cap_nhat?->format('Y-m-d')) }}" class="form-control @error('ngay_cap_nhat') is-invalid @enderror">
                @error('ngay_cap_nhat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="4" class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between">
        <a href="{{ route('ho-khau.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
    </div>
</form>
