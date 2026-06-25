@extends('layouts.app')

@section('title', 'Đăng ký Dân quân tự vệ')
@section('page_title', 'Đăng ký Dân quân tự vệ')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dan-quan-tu-ve.index') }}" class="text-decoration-none text-muted">Lực lượng dân quân tự vệ</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Đăng ký mới</span>
    </div>
    <h2 class="fw-bold mb-1">Đăng ký Dân quân tự vệ</h2>
    <p class="text-secondary mb-0">Thêm mới công dân vào lực lượng dân quân tự vệ nòng cốt của địa phương.</p>
</div>

<form action="{{ route('dan-quan-tu-ve.store') }}" method="POST" id="militiaForm" novalidate>
    @csrf

    <div class="row g-3 align-items-stretch">
        <!-- Cột trái: Thông tin chung -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="bi bi-file-earmark-text-fill me-1"></i>Thông tin nhiệm kỳ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="chuc_vu" class="form-label fw-semibold">Chức vụ trong lực lượng</label>
                        <select name="chuc_vu" id="chuc_vu" class="form-select @error('chuc_vu') is-invalid @enderror">
                            @foreach(\App\Models\DanQuanTuVe::CHUC_VU_LIST as $val)
                                <option value="{{ $val }}" {{ old('chuc_vu', 'Chiến sĩ') === $val ? 'selected' : '' }}>
                                    {{ $val }}
                                </option>
                            @endforeach
                        </select>
                        @error('chuc_vu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="don_vi" class="form-label fw-semibold">Tổ/đội dân quân</label>
                        <input type="text" name="don_vi" id="don_vi" class="form-control @error('don_vi') is-invalid @enderror" placeholder="Ví dụ: Tổ dân quân Thôn 1" value="{{ old('don_vi') }}">
                        @error('don_vi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ngay_gia_nhap" class="form-label fw-semibold">Ngày gia nhập</label>
                        <input type="date" name="ngay_gia_nhap" id="ngay_gia_nhap" class="form-control @error('ngay_gia_nhap') is-invalid @enderror" value="{{ old('ngay_gia_nhap', date('Y-m-d')) }}">
                        @error('ngay_gia_nhap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="trang_thai" class="form-label fw-semibold">Trạng thái phục vụ <span class="text-danger">*</span></label>
                        <select name="trang_thai" id="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                            @foreach($trangThai as $k => $v)
                                <option value="{{ $k }}" {{ old('trang_thai', 'dang_phuc_vu') === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ngay_ket_thuc" class="form-label fw-semibold">Ngày kết thúc nhiệm kỳ</label>
                        <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control @error('ngay_ket_thuc') is-invalid @enderror" value="{{ old('ngay_ket_thuc') }}">
                        @error('ngay_ket_thuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ghi_chu" class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="ghi_chu" id="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror" placeholder="Nhập ghi chú thêm nếu có...">{{ old('ghi_chu') }}</textarea>
                        @error('ghi_chu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Chọn công dân đăng ký hàng loạt -->
        <div class="col">
            <div class="card shadow-sm border-0 h-100" style="display:flex; flex-direction:column;">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="bi bi-people-fill me-1"></i>Chọn công dân đăng ký
                    </h5>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-semibold fs-7">
                        Đã chọn: <span id="selectedCount">0</span>
                    </span>
                </div>
                <div class="card-body d-flex flex-column p-3" style="flex: 1 1 0; min-height: 0;">
                    <!-- Search Input -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="citizenSearch" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm theo Họ tên hoặc CCCD/CMND..." autocomplete="off">
                    </div>

                    @error('nhan_khau_ids')
                        <div class="alert alert-danger py-2 mb-3 fs-7"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}</div>
                    @enderror

                    <!-- Citizen Table -->
                    <div class="flex-grow-1 border rounded overflow-y-auto" id="citizenTableWrapper">
                        <table class="table table-hover align-middle mb-0" id="citizenTable">
                            <thead class="table-light sticky-top" style="z-index: 10;">
                                <tr>
                                    <th width="50" class="text-center">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Họ và tên</th>
                                    <th>CCCD/CMND</th>
                                    <th class="text-center">Năm sinh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nhanKhau as $person)
                                    <tr class="citizen-row">
                                        <td class="text-center">
                                            <input type="checkbox" name="nhan_khau_ids[]" value="{{ $person->id }}" 
                                                   class="form-check-input citizen-checkbox" 
                                                   {{ is_array(old('nhan_khau_ids')) && in_array($person->id, old('nhan_khau_ids')) ? 'checked' : '' }}>
                                        </td>
                                        <td class="fw-semibold text-dark citizen-name">{{ $person->ho_ten }}</td>
                                        <td class="text-muted citizen-cccd">{{ $person->cccd_cmnd ?? 'Chưa cập nhật' }}</td>
                                        <td class="text-center text-secondary">
                                            {{ $person->ngay_sinh ? $person->ngay_sinh->format('Y') : '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                                            Không có công dân nào đủ điều kiện tham gia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between py-3">
                    <a href="{{ route('dan-quan-tu-ve.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-success fw-semibold px-4" id="submitBtn" disabled>
                        <i class="bi bi-check-lg me-1"></i>Đăng ký thành viên
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .border-success-subtle {
        border-color: rgba(25, 135, 84, 0.2) !important;
    }
    .fs-7 {
        font-size: 0.875rem;
    }
    /* Sticky thead trong bảng */
    #citizenTable thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 10;
    }
    /* Table wrapper cuộn theo chiều dọc, fill hết card-body */
    #citizenTableWrapper {
        overflow-y: auto;
        flex: 1 1 0;
        min-height: 200px;
    }
    /* Card bên phải (col) fill full height qua flex */
    #militiaForm .card-body.flex-column {
        flex: 1 1 0 !important;
        min-height: 0 !important;
    }
    /* Chiều cao tối thiểu của row để cân bằng hai cột */
    #militiaForm .row {
        min-height: 540px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const citizenSearch = document.getElementById('citizenSearch');
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.citizen-checkbox');
        const selectedCountEl = document.getElementById('selectedCount');
        const submitBtn = document.getElementById('submitBtn');
        const rows = document.querySelectorAll('.citizen-row');

        // Search filtering function (ignores accents)
        citizenSearch.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
            rows.forEach(row => {
                const name = row.querySelector('.citizen-name').textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const cccd = row.querySelector('.citizen-cccd').textContent.toLowerCase();
                
                if (name.includes(query) || cccd.includes(query)) {
                    row.style.setProperty('display', '', 'important');
                } else {
                    row.style.setProperty('display', 'none', 'important');
                }
            });
            updateSelectAllState();
        });

        // Select all event
        selectAll.addEventListener('change', function(e) {
            const isChecked = e.target.checked;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.citizen-checkbox');
                    if (cb) {
                        cb.checked = isChecked;
                    }
                }
            });
            updateSelectedCount();
        });

        // Individual checkbox event
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateSelectedCount();
                updateSelectAllState();
            });
        });

        // Update selected count and enable/disable submit button
        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.citizen-checkbox:checked').length;
            selectedCountEl.textContent = checkedCount;
            
            if (checkedCount > 0) {
                submitBtn.removeAttribute('disabled');
            } else {
                submitBtn.setAttribute('disabled', 'true');
            }
        }

        // Update "Select All" checkbox state based on visibility and check status
        function updateSelectAllState() {
            const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
            if (visibleRows.length === 0) {
                selectAll.checked = false;
                return;
            }
            
            const allChecked = visibleRows.every(row => {
                const cb = row.querySelector('.citizen-checkbox');
                return cb && cb.checked;
            });
            
            selectAll.checked = allChecked;
        }

        // Run on load to handle pre-filled old input validation failures
        updateSelectedCount();
        updateSelectAllState();
    });
</script>
@endsection
