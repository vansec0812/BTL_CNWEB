@extends('layouts.app')

@section('title', 'Thêm Cán bộ Mới')
@section('page_title', 'Thêm cán bộ mới')

@section('content')
@php
    $roleLabels = [
        'admin' => 'Admin Hệ thống',
        'tu_phap' => 'Cán bộ Tư pháp (Hộ tịch & Cư trú)',
        'lao_dong' => 'Cán bộ Lao động (Kinh tế, Lao động, An sinh)',
        'dia_chinh' => 'Cán bộ Địa chính (Đất đai & Hạ tầng)',
        'quan_su' => 'Cán bộ Quân sự (Nghĩa vụ & An ninh quốc phòng)',
    ];
@endphp

<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('users.index') }}" class="text-decoration-none">Tài khoản cán bộ</a>
        <span class="mx-1">/</span>
        Thêm cán bộ mới
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-header bg-light py-3">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-plus me-1 text-success"></i>Tạo tài khoản cán bộ mới</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('users.store') }}" method="POST" novalidate>
            @csrf

            <div class="row g-3">
                {{-- PHẦN 1: THÔNG TIN TÀI KHOẢN ĐĂNG NHẬP --}}
                <div class="col-12 border-bottom pb-2">
                    <h6 class="fw-bold text-success mb-0"><i class="bi bi-shield-lock me-1"></i>Thông tin đăng nhập & Vai trò</h6>
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label small fw-semibold text-secondary">Họ và tên cán bộ <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Nguyễn Văn A" style="border-radius: 8px;" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label small fw-semibold text-secondary">Địa chỉ Email đăng nhập <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Viết thường, ví dụ: nva@ubnd-xa.vn" style="border-radius: 8px;" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label small fw-semibold text-secondary">Mật khẩu đăng nhập <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control form-control-sm @error('password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự..." style="border-radius: 8px;" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="role" class="form-label small fw-semibold text-secondary">Vai trò hệ thống <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-select form-select-sm @error('role') is-invalid @enderror" style="border-radius: 8px;" required>
                        <option value="">— Chọn vai trò —</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                {{ $roleLabels[$role->name] ?? $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PHẦN 2: THÔNG TIN LÝ LỊCH CÁ NHÂN --}}
                <div class="col-12 border-bottom pb-2 mt-4">
                    <h6 class="fw-bold text-success mb-0"><i class="bi bi-file-earmark-person me-1"></i>Hồ sơ cá nhân & Chức danh</h6>
                </div>

                <div class="col-md-4">
                    <label for="so_cccd" class="form-label small fw-semibold text-secondary">Số CCCD (12 số)</label>
                    <input type="text" name="so_cccd" id="so_cccd" class="form-control form-control-sm @error('so_cccd') is-invalid @enderror" value="{{ old('so_cccd') }}" placeholder="Số định danh cá nhân" style="border-radius: 8px;">
                    @error('so_cccd')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="gioi_tinh" class="form-label small fw-semibold text-secondary">Giới tính <span class="text-danger">*</span></label>
                    <select name="gioi_tinh" id="gioi_tinh" class="form-select form-select-sm" style="border-radius: 8px;">
                        <option value="nam" {{ old('gioi_tinh') === 'nam' ? 'selected' : '' }}>Nam</option>
                        <option value="nu" {{ old('gioi_tinh') === 'nu' ? 'selected' : '' }}>Nữ</option>
                        <option value="khac" {{ old('gioi_tinh') === 'khac' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="ngay_sinh" class="form-label small fw-semibold text-secondary">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control form-control-sm" value="{{ old('ngay_sinh') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label for="so_dien_thoai" class="form-label small fw-semibold text-secondary">Số điện thoại liên hệ</label>
                    <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control form-control-sm" value="{{ old('so_dien_thoai') }}" placeholder="Ví dụ: 0912345678" style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label for="chuc_vu" class="form-label small fw-semibold text-secondary">Chức danh hành chính (Chức vụ)</label>
                    <input type="text" name="chuc_vu" id="chuc_vu" class="form-control form-control-sm" value="{{ old('chuc_vu') }}" placeholder="Ví dụ: Cán bộ địa chính, Cán bộ quân sự..." style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label for="trang_thai" class="form-label small fw-semibold text-secondary">Trạng thái tài khoản <span class="text-danger">*</span></label>
                    <select name="trang_thai" id="trang_thai" class="form-select form-select-sm" style="border-radius: 8px;">
                        <option value="active" {{ old('trang_thai') === 'active' ? 'selected' : '' }}>Hoạt động (Active)</option>
                        <option value="inactive" {{ old('trang_thai') === 'inactive' ? 'selected' : '' }}>Khóa tài khoản (Inactive)</option>
                    </select>
                </div>

                <div class="col-12 border-top pt-3 mt-3">
                    <span class="small fw-semibold text-success d-block mb-2"><i class="bi bi-geo-alt me-1"></i>Quê quán (Tỉnh / Huyện / Xã)</span>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select id="que_quan_tinh" class="form-select form-select-sm" style="border-radius: 8px;">
                                <option value="">— Chọn Tỉnh/Thành phố —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="que_quan_huyen" class="form-select form-select-sm" style="border-radius: 8px;">
                                <option value="">— Chọn Huyện/Quận —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="que_quan_xa" class="form-select form-select-sm" style="border-radius: 8px;">
                                <option value="">— Chọn Xã/Phường —</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="que_quan" id="que_quan_hidden" value="{{ old('que_quan') }}">
                </div>

                <div class="col-12 border-top pt-3 mt-3">
                    <span class="small fw-semibold text-success d-block mb-2"><i class="bi bi-house-door me-1"></i>Địa chỉ nơi ở hiện tại</span>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select id="dia_chi_tinh" class="form-select form-select-sm" style="border-radius: 8px;">
                                <option value="">— Chọn Tỉnh/Thành phố —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="dia_chi_huyen" class="form-select form-select-sm" style="border-radius: 8px;">
                                <option value="">— Chọn Huyện/Quận —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="dia_chi_xa" class="form-select form-select-sm" style="border-radius: 8px;">
                                <option value="">— Chọn Xã/Phường —</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="dia_chi_chi_tiet" class="form-control form-control-sm" placeholder="Số nhà, đường, thôn/xóm..." style="border-radius: 8px;">
                        </div>
                    </div>
                    <input type="hidden" name="dia_chi" id="dia_chi_hidden" value="{{ old('dia_chi') }}">
                </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tinhQueQuanSelect = document.getElementById('que_quan_tinh');
    const huyenQueQuanSelect = document.getElementById('que_quan_huyen');
    const xaQueQuanSelect = document.getElementById('que_quan_xa');
    const queQuanHidden = document.getElementById('que_quan_hidden');

    const tinhDiaChiSelect = document.getElementById('dia_chi_tinh');
    const huyenDiaChiSelect = document.getElementById('dia_chi_huyen');
    const xaDiaChiSelect = document.getElementById('dia_chi_xa');
    const chiTietDiaChiInput = document.getElementById('dia_chi_chi_tiet');
    const diaChiHidden = document.getElementById('dia_chi_hidden');

    const initialQueQuan = @json(old('que_quan', ''));
    const initialDiaChi = @json(old('dia_chi', ''));

    let addressData = [];

    function parseAddress(addressString, isQueQuan = false) {
        if (!addressString) return { tinh: '', huyen: '', xa: '', chiTiet: '' };
        const parts = addressString.split(',').map(s => s.trim());
        
        if (isQueQuan) {
            if (parts.length >= 3) {
                return {
                    tinh: parts[parts.length - 1],
                    huyen: parts[parts.length - 2],
                    xa: parts[parts.length - 3],
                    chiTiet: ''
                };
            } else if (parts.length === 2) {
                return {
                    tinh: parts[1],
                    huyen: parts[0],
                    xa: '',
                    chiTiet: ''
                };
            } else {
                return {
                    tinh: parts[0],
                    huyen: '',
                    xa: '',
                    chiTiet: ''
                };
            }
        } else {
            if (parts.length >= 4) {
                return {
                    tinh: parts[parts.length - 1],
                    huyen: parts[parts.length - 2],
                    xa: parts[parts.length - 3],
                    chiTiet: parts.slice(0, parts.length - 3).join(', ')
                };
            } else if (parts.length === 3) {
                return {
                    tinh: parts[2],
                    huyen: parts[1],
                    xa: parts[0],
                    chiTiet: ''
                };
            } else if (parts.length === 2) {
                return {
                    tinh: parts[1],
                    huyen: parts[0],
                    xa: '',
                    chiTiet: ''
                };
            } else {
                return {
                    tinh: '',
                    huyen: '',
                    xa: '',
                    chiTiet: parts[0] || ''
                };
            }
        }
    }

    const parsedQueQuan = parseAddress(initialQueQuan, true);
    const parsedDiaChi = parseAddress(initialDiaChi, false);

    fetch('https://cdn.jsdelivr.net/gh/kenzouno1/DiaGioiHanhChinhVN@master/data.json')
        .then(response => response.json())
        .then(data => {
            addressData = data;
            
            populateProvinces(tinhQueQuanSelect, parsedQueQuan.tinh);
            populateProvinces(tinhDiaChiSelect, parsedDiaChi.tinh);

            if (parsedQueQuan.tinh) {
                const prov = findByName(addressData, parsedQueQuan.tinh);
                if (prov) {
                    populateDistricts(huyenQueQuanSelect, prov.Districts, parsedQueQuan.huyen);
                    if (parsedQueQuan.huyen) {
                        const dist = findByName(prov.Districts, parsedQueQuan.huyen);
                        if (dist) {
                            populateWards(xaQueQuanSelect, dist.Wards, parsedQueQuan.xa);
                        }
                    }
                }
            }
            updateQueQuanHidden();

            if (parsedDiaChi.tinh) {
                const prov = findByName(addressData, parsedDiaChi.tinh);
                if (prov) {
                    populateDistricts(huyenDiaChiSelect, prov.Districts, parsedDiaChi.huyen);
                    if (parsedDiaChi.huyen) {
                        const dist = findByName(prov.Districts, parsedDiaChi.huyen);
                        if (dist) {
                            populateWards(xaDiaChiSelect, dist.Wards, parsedDiaChi.xa);
                        }
                    }
                }
            }
            chiTietDiaChiInput.value = parsedDiaChi.chiTiet;
            updateDiaChiHidden();
        })
        .catch(err => console.error("Error loading address data:", err));

    function populateProvinces(selectEl, selectedVal) {
        selectEl.innerHTML = '<option value="">— Chọn Tỉnh/Thành phố —</option>';
        addressData.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.Name;
            opt.textContent = item.Name;
            if (selectedVal && normalizeName(item.Name) === normalizeName(selectedVal)) {
                opt.selected = true;
            }
            selectEl.appendChild(opt);
        });
    }

    function populateDistricts(selectEl, districts, selectedVal) {
        selectEl.innerHTML = '<option value="">— Chọn Huyện/Quận —</option>';
        if (!districts) return;
        districts.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.Name;
            opt.textContent = item.Name;
            if (selectedVal && normalizeName(item.Name) === normalizeName(selectedVal)) {
                opt.selected = true;
            }
            selectEl.appendChild(opt);
        });
    }

    function populateWards(selectEl, wards, selectedVal) {
        selectEl.innerHTML = '<option value="">— Chọn Xã/Phường —</option>';
        if (!wards) return;
        wards.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.Name;
            opt.textContent = item.Name;
            if (selectedVal && normalizeName(item.Name) === normalizeName(selectedVal)) {
                opt.selected = true;
            }
            selectEl.appendChild(opt);
        });
    }

    function findByName(list, name) {
        if (!list || !name) return null;
        let found = list.find(item => item.Name.toLowerCase().trim() === name.toLowerCase().trim());
        if (found) return found;
        const normName = normalizeName(name);
        return list.find(item => normalizeName(item.Name) === normName);
    }

    function normalizeName(str) {
        return str.toLowerCase().replace(/^(tỉnh|thành phố|quận|huyện|thị xã|xã|phường|thị trấn)\s+/i, '').trim();
    }

    tinhQueQuanSelect.addEventListener('change', function () {
        const prov = findByName(addressData, this.value);
        huyenQueQuanSelect.innerHTML = '<option value="">— Chọn Huyện/Quận —</option>';
        xaQueQuanSelect.innerHTML = '<option value="">— Chọn Xã/Phường —</option>';
        if (prov) {
            populateDistricts(huyenQueQuanSelect, prov.Districts);
        }
        updateQueQuanHidden();
    });

    huyenQueQuanSelect.addEventListener('change', function () {
        const prov = findByName(addressData, tinhQueQuanSelect.value);
        xaQueQuanSelect.innerHTML = '<option value="">— Chọn Xã/Phường —</option>';
        if (prov) {
            const dist = findByName(prov.Districts, this.value);
            if (dist) {
                populateWards(xaQueQuanSelect, dist.Wards);
            }
        }
        updateQueQuanHidden();
    });

    xaQueQuanSelect.addEventListener('change', updateQueQuanHidden);

    tinhDiaChiSelect.addEventListener('change', function () {
        const prov = findByName(addressData, this.value);
        huyenDiaChiSelect.innerHTML = '<option value="">— Chọn Huyện/Quận —</option>';
        xaDiaChiSelect.innerHTML = '<option value="">— Chọn Xã/Phường —</option>';
        if (prov) {
            populateDistricts(huyenDiaChiSelect, prov.Districts);
        }
        updateDiaChiHidden();
    });

    huyenDiaChiSelect.addEventListener('change', function () {
        const prov = findByName(addressData, tinhDiaChiSelect.value);
        xaDiaChiSelect.innerHTML = '<option value="">— Chọn Xã/Phường —</option>';
        if (prov) {
            const dist = findByName(prov.Districts, this.value);
            if (dist) {
                populateWards(xaDiaChiSelect, dist.Wards);
            }
        }
        updateDiaChiHidden();
    });

    xaDiaChiSelect.addEventListener('change', updateDiaChiHidden);
    chiTietDiaChiInput.addEventListener('input', updateDiaChiHidden);

    function updateQueQuanHidden() {
        const tinh = tinhQueQuanSelect.value;
        const huyen = huyenQueQuanSelect.value;
        const xa = xaQueQuanSelect.value;
        const parts = [xa, huyen, tinh].filter(Boolean);
        queQuanHidden.value = parts.join(', ');
    }

    function updateDiaChiHidden() {
        const tinh = tinhDiaChiSelect.value;
        const huyen = huyenDiaChiSelect.value;
        const xa = xaDiaChiSelect.value;
        const chiTiet = chiTietDiaChiInput.value.trim();
        const parts = [chiTiet, xa, huyen, tinh].filter(Boolean);
        diaChiHidden.value = parts.join(', ');
    }
});
</script>

                <div class="col-12 d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">
                        <i class="bi bi-save me-1"></i> Tạo tài khoản
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-light px-4 border" style="border-radius: 8px;">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
