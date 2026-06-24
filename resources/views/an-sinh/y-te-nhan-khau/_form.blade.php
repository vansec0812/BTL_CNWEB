<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        <div class="row g-3">

            {{-- Nhân khẩu --}}
            <div class="col-lg-6">
                <label for="nhan_khau_id" class="form-label">Nhân khẩu <span class="text-danger">*</span></label>
                <select id="nhan_khau_id" name="nhan_khau_id"
                    class="form-select @error('nhan_khau_id') is-invalid @enderror"
                    @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    <option value="">-- Chọn nhân khẩu --</option>
                    @foreach ($nhanKhau as $person)
                        <option value="{{ $person->id }}"
                            @selected((string) old('nhan_khau_id', $record?->nhan_khau_id) === (string) $person->id)>
                            {{ $person->ho_ten }}{{ $person->cccd_cmnd ? ' — '.$person->cccd_cmnd : '' }}
                        </option>
                    @endforeach
                </select>
                @error('nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Mỗi nhân khẩu chỉ có một hồ sơ y tế.</div>
            </div>

            {{-- Loại BHYT --}}
            <div class="col-lg-6">
                <label for="loai_bhyt" class="form-label">Loại BHYT <span class="text-danger">*</span></label>
                <select id="loai_bhyt" name="loai_bhyt"
                    class="form-select @error('loai_bhyt') is-invalid @enderror"
                    @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($loaiBhyt as $value => $label)
                        <option value="{{ $value }}" @selected(old('loai_bhyt', $record?->loai_bhyt ?? 'khong_co') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loai_bhyt')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Số thẻ BHYT --}}
            <div class="col-lg-4">
                <label for="so_the_bhyt" class="form-label">Số thẻ BHYT</label>
                <input id="so_the_bhyt" name="so_the_bhyt"
                    value="{{ old('so_the_bhyt', $record?->so_the_bhyt) }}"
                    class="form-control @error('so_the_bhyt') is-invalid @enderror"
                    @disabled($isReadOnly ?? false) maxlength="50" placeholder="VD: DN4000123456789">
                @error('so_the_bhyt')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Ngày cấp thẻ --}}
            <div class="col-lg-4">
                <label for="ngay_cap_the_bhyt" class="form-label">Ngày cấp thẻ</label>
                <input type="date" id="ngay_cap_the_bhyt" name="ngay_cap_the_bhyt"
                    value="{{ old('ngay_cap_the_bhyt', $record?->ngay_cap_the_bhyt?->format('Y-m-d')) }}"
                    class="form-control @error('ngay_cap_the_bhyt') is-invalid @enderror"
                    @disabled($isReadOnly ?? false)>
                @error('ngay_cap_the_bhyt')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Ngày hết hạn thẻ --}}
            <div class="col-lg-4">
                <label for="ngay_het_han_the_bhyt" class="form-label">Ngày hết hạn thẻ</label>
                <input type="date" id="ngay_het_han_the_bhyt" name="ngay_het_han_the_bhyt"
                    value="{{ old('ngay_het_han_the_bhyt', $record?->ngay_het_han_the_bhyt?->format('Y-m-d')) }}"
                    class="form-control @error('ngay_het_han_the_bhyt') is-invalid @enderror"
                    @disabled($isReadOnly ?? false)>
                @error('ngay_het_han_the_bhyt')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Nơi đăng ký KCB --}}
            <div class="col-lg-12">
                <label for="noi_dang_ky_kham_chua_benh" class="form-label">Nơi đăng ký khám chữa bệnh ban đầu</label>
                <input id="noi_dang_ky_kham_chua_benh" name="noi_dang_ky_kham_chua_benh"
                    value="{{ old('noi_dang_ky_kham_chua_benh', $record?->noi_dang_ky_kham_chua_benh) }}"
                    class="form-control @error('noi_dang_ky_kham_chua_benh') is-invalid @enderror"
                    @disabled($isReadOnly ?? false) maxlength="255" placeholder="Trạm y tế xã X, Bệnh viện huyện Y...">
                @error('noi_dang_ky_kham_chua_benh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Tiêm chủng mở rộng --}}
            <div class="col-lg-6">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="hoan_thanh_tiem_chung_mo_rong"
                        name="hoan_thanh_tiem_chung_mo_rong" value="1"
                        @checked(old('hoan_thanh_tiem_chung_mo_rong', $record?->hoan_thanh_tiem_chung_mo_rong))
                        @disabled($isReadOnly ?? false)>
                    <label class="form-check-label" for="hoan_thanh_tiem_chung_mo_rong">
                        Đã hoàn thành chương trình tiêm chủng mở rộng
                    </label>
                </div>
                <div class="form-text">Áp dụng chủ yếu cho trẻ em theo chương trình TCMR quốc gia.</div>
            </div>

            {{-- Lịch sử tiêm chủng JSON --}}
            <div class="col-lg-6">
                <label for="lich_su_tiem_chung" class="form-label">Lịch sử tiêm chủng (JSON)</label>
                <textarea id="lich_su_tiem_chung" name="lich_su_tiem_chung" rows="3"
                    class="form-control font-monospace @error('lich_su_tiem_chung') is-invalid @enderror"
                    @disabled($isReadOnly ?? false)
                    placeholder='[{"ten_mui": "Sởi", "ngay_tiem": "2023-01-15"}]'>{{ old('lich_su_tiem_chung', $record ? json_encode($record->lich_su_tiem_chung, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
                @error('lich_su_tiem_chung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Nhập dạng JSON array, hoặc để trống nếu chưa có thông tin.</div>
            </div>

            {{-- Ghi chú sức khỏe --}}
            <div class="col-12">
                <label for="ghi_chu_suc_khoe" class="form-label">Ghi chú tình trạng sức khỏe</label>
                <textarea id="ghi_chu_suc_khoe" name="ghi_chu_suc_khoe" rows="3"
                    class="form-control @error('ghi_chu_suc_khoe') is-invalid @enderror"
                    @disabled($isReadOnly ?? false)
                    placeholder="Dị ứng, bệnh mãn tính, tiền sử bệnh...">{{ old('ghi_chu_suc_khoe', $record?->ghi_chu_suc_khoe) }}</textarea>
                @error('ghi_chu_suc_khoe')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>

    <div class="card-footer bg-white d-flex justify-content-between">
        <a href="{{ route('y-te-nhan-khau.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        @if (!($isReadOnly ?? false))
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
        @else
            @can('manage_an_sinh')
            <a href="{{ route('y-te-nhan-khau.edit', $record) }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
            @endcan
        @endif
    </div>
</form>
