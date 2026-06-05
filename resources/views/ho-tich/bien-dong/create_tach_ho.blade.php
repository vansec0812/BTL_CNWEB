@extends('layouts.app')

@section('title', 'Nghiệp vụ Tách hộ khẩu')
@section('page_title', 'Tách hộ khẩu')

@section('content')
<style>
    .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: #6c757d;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        color: var(--admin-green);
        background-color: var(--admin-green-soft);
        border-color: rgba(15, 81, 50, 0.2);
        transform: translateX(-2px);
    }
</style>

<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('bien-dong.index') }}" class="text-decoration-none">Biến động hộ khẩu</a>
        <span class="mx-1">/</span>
        Tách hộ
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('bien-dong.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Nghiệp vụ Tách hộ khẩu</h2>
    </div>
</div>

<form method="POST" action="{{ route('bien-dong.store') }}">
    @csrf
    <input type="hidden" name="loai_bien_dong" value="tach_ho">

    <div class="row g-4">
        {{-- Chọn hộ gốc --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-journal-text me-1"></i>Bước 1: Chọn Hộ khẩu gốc (Nguồn)
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="ho_khau_nguon_id" class="form-label">Sổ hộ khẩu nguồn</label>
                        <select id="ho_khau_nguon_id" name="ho_khau_nguon_id" class="form-select @error('ho_khau_nguon_id') is-invalid @enderror" onchange="loadMembers(this.value)">
                            <option value="">-- Chọn hộ khẩu nguồn --</option>
                            @foreach ($hoKhauList as $ho)
                                <option value="{{ $ho->id }}" @selected(old('ho_khau_nguon_id', $sourceHoKhau?->id) == $ho->id)>
                                    Hộ: {{ $ho->ma_ho }} - Số sổ: {{ $ho->so_so_ho_khau }} ({{ $ho->chuHo?->ho_ten ?? 'Chưa xác định chủ hộ' }})
                                </option>
                            @endforeach
                        </select>
                        @error('ho_khau_nguon_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($sourceHoKhau)
                        <div class="p-3 bg-light rounded small">
                            <h6 class="fw-bold mb-2 text-success">Thông tin hộ nguồn:</h6>
                            <p class="mb-1"><strong>Chủ hộ:</strong> {{ $sourceHoKhau->chuHo?->ho_ten ?? 'Chưa xác định' }}</p>
                            <p class="mb-1"><strong>Địa chỉ:</strong> {{ $sourceHoKhau->dia_chi_thuong_tru }}</p>
                            <p class="mb-0"><strong>Số thành viên hiện tại:</strong> {{ $sourceHoKhau->so_thanh_vien }} người</p>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted border border-dashed rounded">
                            <i class="bi bi-info-circle d-block fs-3 mb-2"></i>
                            Vui lòng chọn một sổ hộ khẩu để tải danh sách thành viên.
                        </div>
                    @endif
                </div>
            </div>

            @if($sourceHoKhau)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person-check me-1"></i>Bước 2: Chọn thành viên chuyển đi
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;" class="text-center">Chọn</th>
                                    <th>Họ tên</th>
                                    <th>Quan hệ cũ</th>
                                    <th>Làm Chủ hộ mới</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $m)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="thanh_vien_ids[]" value="{{ $m->id }}" class="form-check-input member-checkbox" @checked(in_array($m->id, old('thanh_vien_ids', []))) id="check-{{ $m->id }}" onchange="toggleRelationInput({{ $m->id }})">
                                    </td>
                                    <td>
                                        <label for="check-{{ $m->id }}" class="fw-semibold d-block pointer">{{ $m->ho_ten }}</label>
                                        <span class="small text-secondary">CCCD: {{ $m->cccd_cmnd ?? 'Chưa có' }}</span>
                                    </td>
                                    <td>{{ $m->quan_he_chu_ho }}</td>
                                    <td class="text-center">
                                        <input type="radio" name="new_chu_ho_id" value="{{ $m->id }}" class="form-check-input chu-ho-radio" @checked(old('new_chu_ho_id') == $m->id) disabled id="radio-{{ $m->id }}">
                                    </td>
                                </tr>
                                <tr id="relation-row-{{ $m->id }}" class="relation-row d-none bg-light">
                                    <td colspan="4" class="py-2 px-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <small class="text-secondary fw-semibold">Quan hệ với chủ hộ mới:</small>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="quan_he[{{ $m->id }}]" value="{{ old('quan_he.'.$m->id, 'Thành viên') }}" class="form-control form-control-sm" placeholder="Vợ, chồng, con, cháu...">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Hộ khẩu này không có thành viên nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @error('thanh_vien_ids')
                        <div class="p-3 text-danger small">{{ $message }}</div>
                    @enderror
                    @error('new_chu_ho_id')
                        <div class="p-3 text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif
        </div>

        {{-- Điền thông tin hộ mới --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-file-earmark-plus me-1"></i>Bước 3: Thông tin Sổ hộ khẩu mới
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="so_so_ho_khau_moi" class="form-label">Số sổ hộ khẩu mới <span class="text-danger">*</span></label>
                            <input type="text" id="so_so_ho_khau_moi" name="so_so_ho_khau_moi" value="{{ old('so_so_ho_khau_moi') }}" class="form-control @error('so_so_ho_khau_moi') is-invalid @enderror" placeholder="VD: HK202611">
                            @error('so_so_ho_khau_moi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ma_ho_moi" class="form-label">Mã hộ mới <span class="text-danger">*</span></label>
                            <input type="text" id="ma_ho_moi" name="ma_ho_moi" value="{{ old('ma_ho_moi') }}" class="form-control @error('ma_ho_moi') is-invalid @enderror" placeholder="VD: MH202602">
                            @error('ma_ho_moi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="thon_xom_moi" class="form-label">Thôn/Xóm <span class="text-secondary">(Tùy chọn)</span></label>
                            <input type="text" id="thon_xom_moi" name="thon_xom_moi" value="{{ old('thon_xom_moi') }}" class="form-control @error('thon_xom_moi') is-invalid @enderror" placeholder="VD: Thôn 1">
                            @error('thon_xom_moi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phan_loai_moi" class="form-label">Phân loại cư trú</label>
                            <select id="phan_loai_moi" name="phan_loai_moi" class="form-select">
                                @foreach($phanLoai as $val => $lbl)
                                    <option value="{{ $val }}" @selected(old('phan_loai_moi') == $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="dia_chi_thuong_tru_moi" class="form-label">Địa chỉ thường trú mới <span class="text-danger">*</span></label>
                            <input type="text" id="dia_chi_thuong_tru_moi" name="dia_chi_thuong_tru_moi" value="{{ old('dia_chi_thuong_tru_moi') }}" class="form-control @error('dia_chi_thuong_tru_moi') is-invalid @enderror" placeholder="Nhập địa chỉ chi tiết...">
                            @error('dia_chi_thuong_tru_moi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ghi_chu_moi" class="form-label">Ghi chú sổ mới</label>
                            <textarea id="ghi_chu_moi" name="ghi_chu_moi" rows="2" class="form-control" placeholder="Ghi chú thêm về sổ hộ khẩu mới...">{{ old('ghi_chu_moi') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-event me-1"></i>Bước 4: Thông tin quyết định tách hộ
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ngay_bien_dong" class="form-label">Ngày quyết định <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bien_dong" name="ngay_bien_dong" value="{{ old('ngay_bien_dong', date('Y-m-d')) }}" class="form-control @error('ngay_bien_dong') is-invalid @enderror">
                            @error('ngay_bien_dong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="so_quyet_dinh" class="form-label">Số quyết định / Văn bản</label>
                            <input type="text" id="so_quyet_dinh" name="so_quyet_dinh" value="{{ old('so_quyet_dinh') }}" class="form-control @error('so_quyet_dinh') is-invalid @enderror" placeholder="VD: QĐ-123/UBND">
                            @error('so_quyet_dinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ly_do" class="form-label">Lý do tách hộ</label>
                            <textarea id="ly_do" name="ly_do" rows="3" class="form-control @error('ly_do') is-invalid @enderror" placeholder="Lý do cụ thể chuyển dời...">{{ old('ly_do') }}</textarea>
                            @error('ly_do')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ghi_chu" class="form-label">Ghi chú biến động</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="2" class="form-control">{{ old('ghi_chu') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('bien-dong.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-success" id="btn-submit" {{ $sourceHoKhau ? '' : 'disabled' }}>Thực hiện Tách hộ</button>
            </div>
        </div>
    </div>
</form>

<script>
    function loadMembers(hoKhauId) {
        if (!hoKhauId) {
            window.location.href = "{{ route('bien-dong.create', ['type' => 'tach_ho']) }}";
            return;
        }
        window.location.href = "{{ route('bien-dong.create', ['type' => 'tach_ho']) }}&source_ho_khau_id=" + hoKhauId;
    }

    function toggleRelationInput(id) {
        const checkbox = document.getElementById('check-' + id);
        const radio = document.getElementById('radio-' + id);
        const relationRow = document.getElementById('relation-row-' + id);

        if (checkbox.checked) {
            radio.disabled = false;
            relationRow.classList.remove('d-none');
            // If no radio is checked yet, make this the default
            const checkedRadio = document.querySelector('.chu-ho-radio:checked');
            if (!checkedRadio) {
                radio.checked = true;
            }
        } else {
            radio.disabled = true;
            radio.checked = false;
            relationRow.classList.add('d-none');
        }
    }

    // Run on boot to ensure checked checkboxes keep rows visible
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.member-checkbox').forEach(function(checkbox) {
            const id = checkbox.value;
            if (checkbox.checked) {
                document.getElementById('radio-' + id).disabled = false;
                document.getElementById('relation-row-' + id).classList.remove('d-none');
            }
        });
    });
</script>
@endsection
