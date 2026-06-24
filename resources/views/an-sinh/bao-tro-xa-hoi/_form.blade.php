<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-6">
                <label for="loai_bao_tro" class="form-label">Loại bảo trợ <span class="text-danger">*</span></label>
                <select id="loai_bao_tro" name="loai_bao_tro" class="form-select @error('loai_bao_tro') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($loaiBaoTro as $value => $label)
                        <option value="{{ $value }}" @selected(old('loai_bao_tro', $record?->loai_bao_tro ?? 'ho_ngheo') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loai_bao_tro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Hộ nghèo/cận nghèo chọn sổ hộ khẩu; các loại còn lại chọn nhân khẩu.</div>
            </div>

            <div class="col-lg-6">
                <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai', $record?->trang_thai ?? 'dang_huong') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ho_khau_id" class="form-label">Sổ hộ khẩu áp dụng</label>
                <select id="ho_khau_id" name="ho_khau_id" class="form-select @error('ho_khau_id') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    <option value="">Không áp dụng / chọn nhân khẩu</option>
                    @foreach ($hoKhau as $household)
                        <option value="{{ $household->id }}" @selected((string) old('ho_khau_id', $record?->ho_khau_id) === (string) $household->id)>
                            {{ $household->so_so_ho_khau }}{{ $household->ma_ho ? ' - '.$household->ma_ho : '' }}{{ $household->thon_xom ? ' - '.$household->thon_xom : '' }}
                        </option>
                    @endforeach
                </select>
                @error('ho_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="nhan_khau_id" class="form-label">Nhân khẩu áp dụng</label>
                <select id="nhan_khau_id" name="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    <option value="">Không áp dụng / chọn hộ khẩu</option>
                    @foreach ($nhanKhau as $person)
                        <option value="{{ $person->id }}" @selected((string) old('nhan_khau_id', $record?->nhan_khau_id) === (string) $person->id)>
                            {{ $person->ho_ten }}{{ $person->cccd_cmnd ? ' - '.$person->cccd_cmnd : '' }}
                        </option>
                    @endforeach
                </select>
                @error('nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="muc_do_khuyet_tat" class="form-label">Mức độ khuyết tật</label>
                <select id="muc_do_khuyet_tat" name="muc_do_khuyet_tat" class="form-select @error('muc_do_khuyet_tat') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    @foreach ($mucDoKhuyetTat as $value => $label)
                        <option value="{{ $value }}" @selected(old('muc_do_khuyet_tat', $record?->muc_do_khuyet_tat ?? 'khong_ap_dung') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('muc_do_khuyet_tat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="dang_khuyet_tat" class="form-label">Dạng khuyết tật</label>
                <input id="dang_khuyet_tat" name="dang_khuyet_tat" value="{{ old('dang_khuyet_tat', $record?->dang_khuyet_tat) }}" class="form-control @error('dang_khuyet_tat') is-invalid @enderror" @disabled($isReadOnly ?? false) maxlength="255" placeholder="Vận động, nghe, nhìn...">
                @error('dang_khuyet_tat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="so_quyet_dinh" class="form-label">Số quyết định</label>
                <input id="so_quyet_dinh" name="so_quyet_dinh" value="{{ old('so_quyet_dinh', $record?->so_quyet_dinh) }}" class="form-control @error('so_quyet_dinh') is-invalid @enderror" @disabled($isReadOnly ?? false) maxlength="100">
                @error('so_quyet_dinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="ngay_bat_dau_huong" class="form-label">Ngày bắt đầu hưởng</label>
                <input type="date" id="ngay_bat_dau_huong" name="ngay_bat_dau_huong" value="{{ old('ngay_bat_dau_huong', $record?->ngay_bat_dau_huong?->format('Y-m-d')) }}" class="form-control @error('ngay_bat_dau_huong') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_bat_dau_huong')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="ngay_ket_thuc_huong" class="form-label">Ngày kết thúc hưởng</label>
                <input type="date" id="ngay_ket_thuc_huong" name="ngay_ket_thuc_huong" value="{{ old('ngay_ket_thuc_huong', $record?->ngay_ket_thuc_huong?->format('Y-m-d')) }}" class="form-control @error('ngay_ket_thuc_huong') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_ket_thuc_huong')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="muc_tro_cap_hang_thang" class="form-label">Mức trợ cấp hằng tháng</label>
                <input type="number" min="0" id="muc_tro_cap_hang_thang" name="muc_tro_cap_hang_thang" value="{{ old('muc_tro_cap_hang_thang', $record?->muc_tro_cap_hang_thang) }}" class="form-control @error('muc_tro_cap_hang_thang') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('muc_tro_cap_hang_thang')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="4" class="form-control @error('ghi_chu') is-invalid @enderror" @disabled($isReadOnly ?? false)>{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between">
        <a href="{{ route('bao-tro-xa-hoi.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        @if (!($isReadOnly ?? false))
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
        @else
            @can('manage_an_sinh')
            <a href="{{ route('bao-tro-xa-hoi.edit', $record) }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
            @endcan
        @endif
    </div>
</form>
