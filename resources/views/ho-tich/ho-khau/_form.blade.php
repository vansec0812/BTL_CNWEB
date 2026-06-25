<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body p-4">

        <div class="row g-3">
            <div class="col-lg-6">
                <label for="so_so_ho_khau" class="form-label">Số sổ hộ khẩu <span class="text-danger">*</span></label>
                <input id="so_so_ho_khau" name="so_so_ho_khau" value="{{ old('so_so_ho_khau', $record?->so_so_ho_khau) }}" class="form-control @error('so_so_ho_khau') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false) maxlength="50">
                @error('so_so_ho_khau')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ma_ho" class="form-label">Mã hộ <span class="text-danger">*</span></label>
                <input id="ma_ho" name="ma_ho" value="{{ old('ma_ho', $record?->ma_ho) }}" class="form-control @error('ma_ho') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false) maxlength="30">
                @error('ma_ho')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if ($method === 'POST')
            <div class="col-lg-6">
                <label for="chu_ho_nhan_khau_id" class="form-label" id="chu_ho_select_label">Chủ hộ (Nhân khẩu sẵn có)</label>
                <select id="chu_ho_nhan_khau_id" name="chu_ho_nhan_khau_id" class="form-select @error('chu_ho_nhan_khau_id') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    <option value="">Chọn chủ hộ (Nhân khẩu)</option>
                    @foreach ($nhanKhau as $person)
                        <option value="{{ $person->id }}" @selected((string) old('chu_ho_nhan_khau_id', $record?->chu_ho_nhan_khau_id) === (string) $person->id)>
                            {{ $person->ho_ten }}{{ $person->cccd_cmnd ? ' - '.$person->cccd_cmnd : '' }}
                        </option>
                    @endforeach
                </select>
                @error('chu_ho_nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6 d-flex align-items-end mb-3">
                <div class="form-check form-switch pb-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="create_new_chu_ho" name="create_new_chu_ho" value="1" @checked(old('create_new_chu_ho')) @disabled($isReadOnly ?? false) onchange="toggleNewChuHo(this.checked)">
                    <label class="form-check-label fw-semibold" for="create_new_chu_ho">Tạo mới Chủ hộ (Nhân khẩu) cho hộ này</label>
                </div>
            </div>

            <!-- New Head of Household details section -->
            <div class="col-lg-12" id="new_chu_ho_section" style="display: {{ old('create_new_chu_ho') ? 'block' : 'none' }};">
                <div class="card bg-light border-0 p-3 mb-3">
                    <h5 class="fw-bold mb-3 text-success"><i class="bi bi-person-plus-fill me-2"></i>Thông tin Chủ hộ mới</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="chu_ho_ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input id="chu_ho_ho_ten" name="chu_ho_ho_ten" value="{{ old('chu_ho_ho_ten') }}" class="form-control @error('chu_ho_ho_ten') is-invalid @enderror" placeholder="Ví dụ: Nguyễn Văn An">
                            @error('chu_ho_ho_ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="chu_ho_cccd_cmnd" class="form-label">Số CCCD / CMND</label>
                            <input id="chu_ho_cccd_cmnd" name="chu_ho_cccd_cmnd" value="{{ old('chu_ho_cccd_cmnd') }}" class="form-control @error('chu_ho_cccd_cmnd') is-invalid @enderror" placeholder="12 chữ số quốc gia hoặc 9 số CMND">
                            @error('chu_ho_cccd_cmnd')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="chu_ho_ngay_sinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" id="chu_ho_ngay_sinh" name="chu_ho_ngay_sinh" value="{{ old('chu_ho_ngay_sinh') }}" class="form-control @error('chu_ho_ngay_sinh') is-invalid @enderror">
                            @error('chu_ho_ngay_sinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="chu_ho_gioi_tinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                            <select id="chu_ho_gioi_tinh" name="chu_ho_gioi_tinh" class="form-select @error('chu_ho_gioi_tinh') is-invalid @enderror">
                                <option value="">Chọn giới tính</option>
                                @foreach ($gioiTinh as $value => $label)
                                    <option value="{{ $value }}" @selected(old('chu_ho_gioi_tinh') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('chu_ho_gioi_tinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="chu_ho_dan_toc" class="form-label">Dân tộc <span class="text-danger">*</span></label>
                            <input id="chu_ho_dan_toc" name="chu_ho_dan_toc" value="{{ old('chu_ho_dan_toc', 'Kinh') }}" class="form-control @error('chu_ho_dan_toc') is-invalid @enderror">
                            @error('chu_ho_dan_toc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="chu_ho_ton_giao" class="form-label">Tôn giáo</label>
                            <input id="chu_ho_ton_giao" name="chu_ho_ton_giao" value="{{ old('chu_ho_ton_giao', 'Không') }}" class="form-control @error('chu_ho_ton_giao') is-invalid @enderror">
                            @error('chu_ho_ton_giao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="chu_ho_tinh_trang_hon_nhan" class="form-label">Tình trạng hôn nhân <span class="text-danger">*</span></label>
                            <select id="chu_ho_tinh_trang_hon_nhan" name="chu_ho_tinh_trang_hon_nhan" class="form-select @error('chu_ho_tinh_trang_hon_nhan') is-invalid @enderror">
                                <option value="">Chọn tình trạng</option>
                                @foreach ($tinhTrangHonNhan as $value => $label)
                                    <option value="{{ $value }}" @selected(old('chu_ho_tinh_trang_hon_nhan') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('chu_ho_tinh_trang_hon_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="chu_ho_que_quan" class="form-label">Quê quán <span class="text-danger">*</span></label>
                            <input id="chu_ho_que_quan" name="chu_ho_que_quan" value="{{ old('chu_ho_que_quan') }}" class="form-control @error('chu_ho_que_quan') is-invalid @enderror" placeholder="Tỉnh/Thành phố, Huyện/Quận gốc gác">
                            @error('chu_ho_que_quan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="chu_ho_noi_sinh" class="form-label">Nơi sinh</label>
                            <input id="chu_ho_noi_sinh" name="chu_ho_noi_sinh" value="{{ old('chu_ho_noi_sinh') }}" class="form-control @error('chu_ho_noi_sinh') is-invalid @enderror" placeholder="Bệnh viện hoặc địa chỉ khai sinh">
                            @error('chu_ho_noi_sinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label for="chu_ho_trinh_do_hoc_van" class="form-label">Trình độ học vấn <span class="text-danger">*</span></label>
                            <select id="chu_ho_trinh_do_hoc_van" name="chu_ho_trinh_do_hoc_van" class="form-select @error('chu_ho_trinh_do_hoc_van') is-invalid @enderror">
                                <option value="">Chọn trình độ</option>
                                @foreach ($trinhDoHocVan as $value => $label)
                                    <option value="{{ $value }}" @selected(old('chu_ho_trinh_do_hoc_van') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('chu_ho_trinh_do_hoc_van')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-6">
                <label for="chu_ho_nhan_khau_id" class="form-label">Chủ hộ</label>
                <select id="chu_ho_nhan_khau_id" name="chu_ho_nhan_khau_id" class="form-select @error('chu_ho_nhan_khau_id') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    <option value="">Chọn chủ hộ (Nhân khẩu)</option>
                    @foreach ($nhanKhau as $person)
                        <option value="{{ $person->id }}" @selected((string) old('chu_ho_nhan_khau_id', $record?->chu_ho_nhan_khau_id) === (string) $person->id)>
                            {{ $person->ho_ten }}{{ $person->cccd_cmnd ? ' - '.$person->cccd_cmnd : '' }}
                        </option>
                    @endforeach
                </select>
                @error('chu_ho_nhan_khau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @endif

            <div class="col-lg-6">
                <label for="thon_xom" class="form-label">Thôn</label>
                <select id="thon_xom" name="thon_xom" class="form-select @error('thon_xom') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                    <option value="">Chọn Thôn</option>
                    @php
                        $defaultThon = ['Thôn Phủ Quốc', 'Thôn Ngô Sài', 'Thôn Hoa Vôi', 'Thôn Du Nghệ', 'Thôn Đình Tổ', 'Thôn Sơn Trung', 'Thôn Ba Nhà', 'Thôn Quảng Yên'];
                        $currentValue = old('thon_xom', $record?->thon_xom);
                        $options = $defaultThon;
                        if ($currentValue && !in_array($currentValue, $defaultThon)) {
                            $options[] = $currentValue;
                        }
                    @endphp
                    @foreach ($options as $option)
                        <option value="{{ $option }}" @selected((string)$currentValue === (string)$option)>{{ $option }}</option>
                    @endforeach
                </select>
                @error('thon_xom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-12">
                <label for="dia_chi_thuong_tru" class="form-label">Địa chỉ thường trú <span class="text-danger">*</span></label>
                <input id="dia_chi_thuong_tru" name="dia_chi_thuong_tru" value="{{ old('dia_chi_thuong_tru', $record?->dia_chi_thuong_tru) }}" class="form-control @error('dia_chi_thuong_tru') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false) maxlength="500" placeholder="Số nhà, đường/phố, thôn, xã Quốc Oai...">
                @error('dia_chi_thuong_tru')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="phan_loai" class="form-label">Phân loại hộ <span class="text-danger">*</span></label>
                <select id="phan_loai" name="phan_loai" class="form-select @error('phan_loai') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($phanLoai as $value => $label)
                        <option value="{{ $value }}" @selected(old('phan_loai', $record?->phan_loai ?? 'thuong_tru') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('phan_loai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-4">
                <label for="so_thanh_vien" class="form-label">Số thành viên</label>
                <input type="number" id="so_thanh_vien" name="so_thanh_vien" value="{{ old('so_thanh_vien', $record?->so_thanh_vien ?? 0) }}" class="form-control" readonly style="background-color: #e9ecef; cursor: not-allowed;" title="Số thành viên được tự động tính toán dựa trên số nhân khẩu thực tế trong hộ.">
            </div>

            <div class="col-lg-4">
                <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" @required(!($isReadOnly ?? false)) @disabled($isReadOnly ?? false)>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(old('trang_thai', $record?->trang_thai ?? 'hoat_dong') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ngay_lap_so" class="form-label">Ngày lập sổ</label>
                <input type="date" id="ngay_lap_so" name="ngay_lap_so" value="{{ old('ngay_lap_so', $record?->ngay_lap_so?->format('Y-m-d')) }}" class="form-control @error('ngay_lap_so') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_lap_so')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-lg-6">
                <label for="ngay_cap_nhat" class="form-label">Ngày cập nhật gần nhất</label>
                <input type="date" id="ngay_cap_nhat" name="ngay_cap_nhat" value="{{ old('ngay_cap_nhat', $record?->ngay_cap_nhat?->format('Y-m-d')) }}" class="form-control @error('ngay_cap_nhat') is-invalid @enderror" @disabled($isReadOnly ?? false)>
                @error('ngay_cap_nhat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="4" class="form-control @error('ghi_chu') is-invalid @enderror" @disabled($isReadOnly ?? false)>{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between">
        <a href="{{ route('ho-khau.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        @if (!($isReadOnly ?? false))
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
        @else
            <a href="{{ route('ho-khau.edit', $record) }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
        @endif
    </div>
</form>

<script>
    function toggleNewChuHo(checked) {
        const newChuHoSection = document.getElementById('new_chu_ho_section');
        const chuHoSelect = document.getElementById('chu_ho_nhan_khau_id');
        const soThanhVienInput = document.getElementById('so_thanh_vien');
        
        if (newChuHoSection) {
            newChuHoSection.style.display = checked ? 'block' : 'none';
        }
        
        if (chuHoSelect) {
            chuHoSelect.disabled = checked;
            if (checked) {
                chuHoSelect.value = '';
                chuHoSelect.classList.remove('is-invalid');
            }
        }
        
        if (soThanhVienInput && !{{ isset($record) ? 'true' : 'false' }}) {
            soThanhVienInput.value = checked ? '1' : '0';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('create_new_chu_ho');
        if (checkbox) {
            toggleNewChuHo(checkbox.checked);
        }
    });
</script>
