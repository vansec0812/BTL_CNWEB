@extends('layouts.app')

@section('title', 'Quản lý Nghĩa vụ Quân sự')
@section('page_title', 'Quản lý Nghĩa vụ Quân sự')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Nghĩa vụ quân sự
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Hồ sơ Nghĩa vụ quân sự</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý sức khỏe, trạng thái khám tuyển và chế độ phục vụ nghĩa vụ quân sự của nam công dân.</p>
    </div>
    @can('manage_nghia_vu')
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success fw-semibold" data-bs-toggle="modal" data-bs-target="#scanModal">
            <i class="bi bi-search me-1"></i> Quét tự động
        </button>
        <a href="{{ route('nghia-vu-quan-su.create') }}" class="btn btn-success fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới hồ sơ
        </a>
    </div>
    @endcan
</div>

<!-- Thống kê nhanh -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Hồ sơ quản lý</p>
                <h4 class="fw-bold mb-0">{{ $stats['nghia_vu_quan_su'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đủ điều kiện</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['du_dieu_kien'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tạm hoãn</p>
                <h4 class="fw-bold mb-0 text-warning">{{ $stats['tam_hoan'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đã nhập ngũ</p>
                <h4 class="fw-bold mb-0 text-primary">{{ $stats['da_nhap_ngu'] }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Bộ lọc tìm kiếm -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</div>
    <div class="card-body">
        <form method="GET" action="{{ route('nghia-vu-quan-su.index') }}" class="row g-3">
            <div class="col-lg-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="search" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Tên, CCCD hoặc địa bàn...">
            </div>
            <div class="col-lg-3">
                <label for="trang_thai_nvqs" class="form-label">Trạng thái NVQS</label>
                <select id="trang_thai_nvqs" name="trang_thai_nvqs" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($trangThaiNVQS as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai_nvqs'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="nam_tuoi_tuyen_quan" class="form-label">Năm tuyển quân</label>
                <input type="number" id="nam_tuoi_tuyen_quan" name="nam_tuoi_tuyen_quan" value="{{ $filters['nam_tuoi_tuyen_quan'] ?? '' }}" class="form-control" placeholder="VD: {{ date('Y') }}" min="1900" max="2100">
            </div>
            <div class="col-lg-3 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('nghia-vu-quan-su.index') }}">Xoá</a>
            </div>
        </form>
    </div>
</div>

<!-- Bảng hiển thị -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách nghĩa vụ quân sự</span>
        <span class="badge text-bg-light">{{ $records->total() }} hồ sơ</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Họ và tên</th>
                        <th>Năm sinh</th>
                        <th>Địa chỉ cư trú</th>
                        <th>Năm tuyển</th>
                        <th>Sức khỏe</th>
                        <th>Trạng thái NVQS</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
                            <td>
                                <div class="fw-semibold">{{ $record->nhanKhau?->ho_ten ?? 'Không xác định' }}</div>
                                <div class="small text-secondary">CCCD: {{ $record->nhanKhau?->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td>{{ $record->nhanKhau?->ngay_sinh ? $record->nhanKhau->ngay_sinh->format('Y') : '—' }}</td>
                            <td>
                                <div>{{ $record->nhanKhau?->hoKhau?->dia_chi_thuong_tru ?? '—' }}</div>
                                <div class="small text-secondary">Thôn/Xóm: {{ $record->nhanKhau?->hoKhau?->thon_xom ?? '—' }}</div>
                            </td>
                            <td><span class="badge text-bg-light">{{ $record->nam_tuoi_tuyen_quan }}</span></td>
                            <td>
                                <div>
                                    <span class="badge bg-{{ $record->ket_qua_kham_suc_khoe === 'chua_kham' ? 'secondary' : (in_array($record->ket_qua_kham_suc_khoe, ['loai_1', 'loai_2', 'loai_3']) ? 'success' : 'danger') }}">
                                        {{ $ketQuaKham[$record->ket_qua_kham_suc_khoe] ?? '—' }}
                                    </span>
                                </div>
                                @if($record->nam_dang_ky_kham_nvqs)
                                    <small class="text-muted">Khám năm: {{ $record->nam_dang_ky_kham_nvqs }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $color = 'secondary';
                                    if ($record->trang_thai_nvqs === 'du_dieu_kien') $color = 'success';
                                    elseif ($record->trang_thai_nvqs === 'tam_hoan') $color = 'warning';
                                    elseif ($record->trang_thai_nvqs === 'trung_tuyen') $color = 'info';
                                    elseif ($record->trang_thai_nvqs === 'da_nhap_ngu') $color = 'primary';
                                    elseif ($record->trang_thai_nvqs === 'xuat_ngu') $color = 'dark';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ $trangThaiNVQS[$record->trang_thai_nvqs] ?? '—' }}
                                </span>
                                @if($record->trang_thai_nvqs === 'tam_hoan' && $record->ly_do_tam_hoan !== 'khong_ap_dung')
                                    <div class="small text-secondary mt-1">Lý do: {{ $lyDoTamHoan[$record->ly_do_tam_hoan] ?? 'Khác' }}</div>
                                @elseif($record->trang_thai_nvqs === 'da_nhap_ngu' && $record->don_vi_quan_doi)
                                    <div class="small text-secondary mt-1">Đơn vị: {{ $record->don_vi_quan_doi }}</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('nghia-vu-quan-su.show', $record) }}" class="btn btn-sm btn-action-view" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('manage_nghia_vu')
                                    <a href="{{ route('nghia-vu-quan-su.edit', $record) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('nghia-vu-quan-su.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ nghĩa vụ quân sự của công dân này?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-action-delete" type="submit" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có hồ sơ nghĩa vụ quân sự nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($records->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $records->links() }}
        </div>
    @endif
</div>

<!-- Modal Quét tự động -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="POST" action="{{ route('nghia-vu-quan-su.scan-store') }}" id="scan-form" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="scanModalLabel">Tự động quét công dân đủ tuổi NVQS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Config Year (Initially visible) -->
                <div id="scan-config-step">
                    <p class="text-secondary mb-3">Hệ thống sẽ quét tự động tất cả các công dân nam hoạt động tại địa phương trong độ tuổi từ 18 đến 25 tuổi (hoặc đến 27 tuổi nếu có trình độ Đại học/Sau Đại học) chưa có trong danh sách NVQS.</p>
                    <div class="mb-3">
                        <label for="nam_tuyen_quan" class="form-label fw-semibold">Năm tuyển quân nhắm mục tiêu</label>
                        <input type="number" id="nam_tuyen_quan" name="nam_tuyen_quan" value="{{ date('Y') }}" class="form-control" required min="1900" max="2100">
                    </div>
                </div>

                <!-- Loading Spinner (Hidden initially) -->
                <div id="scan-loading" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h6 class="fw-semibold text-secondary">Đang quét dữ liệu công dân...</h6>
                    <p class="text-muted small mb-0">Quá trình này có thể mất vài giây.</p>
                </div>

                <!-- Step 2: Show Results and Choose (Hidden initially) -->
                <div id="scan-results-step" style="display: none;">
                    <div class="alert alert-success border-0 py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i> Phát hiện các công dân sau đủ tiêu chuẩn. Vui lòng chọn những người muốn thêm vào danh sách NVQS.
                    </div>
                    <div class="table-responsive" style="max-height: 350px;">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 40px; background-color: #f8f9fa;">
                                        <input class="form-check-input" type="checkbox" id="scan-select-all" checked>
                                    </th>
                                    <th>Họ tên</th>
                                    <th>Năm sinh</th>
                                    <th>CCCD</th>
                                    <th>Trình độ</th>
                                </tr>
                            </thead>
                            <tbody id="scan-results-tbody">
                                <!-- Dynamic rows via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Step 3: No Results Message (Hidden initially) -->
                <div id="scan-empty-step" class="text-center py-4" style="display: none;">
                    <i class="bi bg-opacity-10 bi-emoji-smile fs-1 text-success d-block mb-2"></i>
                    <h6 class="fw-semibold text-dark">Không tìm thấy công dân nào mới</h6>
                    <p class="text-muted small mb-0">Tất cả nam thanh niên đủ tuổi tuyển quân trong năm đã được lập hồ sơ.</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" id="scan-btn-cancel" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="button" class="btn btn-outline-secondary" id="scan-btn-back" style="display: none;">Quay lại</button>
                <button type="button" class="btn btn-success fw-semibold" id="scan-btn-action"><i class="bi bi-search me-1"></i> Bắt đầu quét</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scanModal = document.getElementById('scanModal');
    const scanForm = document.getElementById('scan-form');
    const configStep = document.getElementById('scan-config-step');
    const loadingStep = document.getElementById('scan-loading');
    const resultsStep = document.getElementById('scan-results-step');
    const emptyStep = document.getElementById('scan-empty-step');
    
    const btnCancel = document.getElementById('scan-btn-cancel');
    const btnBack = document.getElementById('scan-btn-back');
    const btnAction = document.getElementById('scan-btn-action');
    
    const inputYear = document.getElementById('nam_tuyen_quan');
    const resultsTbody = document.getElementById('scan-results-tbody');
    const selectAllCheckbox = document.getElementById('scan-select-all');
    
    let currentStep = 1; // 1: config, 2: results, 3: empty
    
    if (scanModal) {
        scanModal.addEventListener('hidden.bs.modal', function () {
            currentStep = 1;
            configStep.style.display = 'block';
            loadingStep.style.display = 'none';
            resultsStep.style.display = 'none';
            emptyStep.style.display = 'none';
            btnCancel.style.display = 'inline-block';
            btnBack.style.display = 'none';
            btnAction.style.display = 'inline-block';
            btnAction.innerHTML = '<i class="bi bi-search me-1"></i> Bắt đầu quét';
            btnAction.disabled = false;
            resultsTbody.innerHTML = '';
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = true;
            }
        });
    }

    if (btnAction) {
        btnAction.addEventListener('click', function(e) {
            if (currentStep === 1) {
                const year = inputYear.value;
                if (!year) {
                    alert('Vui lòng nhập năm tuyển quân');
                    return;
                }
                
                configStep.style.display = 'none';
                btnCancel.style.display = 'none';
                btnAction.style.display = 'none';
                loadingStep.style.display = 'block';
                
                fetch(`{{ route('nghia-vu-quan-su.scan-preview') }}?nam_tuyen_quan=${year}`)
                    .then(response => response.json())
                    .then(res => {
                        loadingStep.style.display = 'none';
                        btnBack.style.display = 'inline-block';
                        btnAction.style.display = 'inline-block';
                        
                        if (res.success && res.data && res.data.length > 0) {
                            currentStep = 2;
                            resultsStep.style.display = 'block';
                            
                            resultsTbody.innerHTML = '';
                            res.data.forEach(citizen => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>
                                        <input class="form-check-input citizen-checkbox" type="checkbox" name="nhan_khau_ids[]" value="${citizen.id}" checked>
                                    </td>
                                    <td><span class="fw-semibold text-dark">${citizen.ho_ten}</span></td>
                                    <td>${citizen.nam_sinh}</td>
                                    <td><code class="text-secondary">${citizen.cccd_cmnd}</code></td>
                                    <td><span class="badge bg-light text-dark border">${citizen.trinh_do_hoc_van}</span></td>
                                `;
                                resultsTbody.appendChild(tr);
                            });
                            
                            updateActionBtnState();
                            
                            document.querySelectorAll('.citizen-checkbox').forEach(cb => {
                                cb.addEventListener('change', updateActionBtnState);
                            });
                        } else {
                            currentStep = 3;
                            emptyStep.style.display = 'block';
                            btnAction.style.display = 'none';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Đã xảy ra lỗi khi quét dữ liệu.');
                        loadingStep.style.display = 'none';
                        configStep.style.display = 'block';
                        btnCancel.style.display = 'inline-block';
                        btnAction.style.display = 'inline-block';
                    });
            } else if (currentStep === 2) {
                scanForm.submit();
            }
        });
    }

    if (btnBack) {
        btnBack.addEventListener('click', function() {
            currentStep = 1;
            configStep.style.display = 'block';
            resultsStep.style.display = 'none';
            emptyStep.style.display = 'none';
            btnCancel.style.display = 'inline-block';
            btnBack.style.display = 'none';
            btnAction.style.display = 'inline-block';
            btnAction.innerHTML = '<i class="bi bi-search me-1"></i> Bắt đầu quét';
            btnAction.disabled = false;
            resultsTbody.innerHTML = '';
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.citizen-checkbox').forEach(cb => {
                cb.checked = checked;
            });
            updateActionBtnState();
        });
    }

    function updateActionBtnState() {
        const selected = document.querySelectorAll('.citizen-checkbox:checked');
        const total = document.querySelectorAll('.citizen-checkbox');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selected.length === total.length;
            selectAllCheckbox.indeterminate = selected.length > 0 && selected.length < total.length;
        }
        
        btnAction.innerHTML = `<i class="bi bi-plus-lg me-1"></i> Thêm vào danh sách (${selected.length})`;
        btnAction.disabled = selected.length === 0;
    }
});
</script>
@endsection
