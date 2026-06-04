@extends('layouts.app')

@section('title', 'Nghĩa vụ & An ninh quốc phòng')
@section('page_title', 'Nghĩa vụ & An ninh quốc phòng')

@section('content')
<style>
    /* Premium custom styling for NVQS module */
    .stat-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #ffffff;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .filter-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .data-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }
    .badge-status {
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
    }
    .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--admin-green-soft);
        color: var(--admin-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    /* Toast container placement */
    .toast-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1100;
    }
    .search-results-list {
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
</style>

{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0 fw-semibold">Tổng hồ sơ NVQS</p>
                    <h4 class="mb-0 fw-bold text-dark" id="stat-tong-so">{{ $stats['nghia_vu_quan_su'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0 fw-semibold">Đủ điều kiện tuyển quân</p>
                    <h4 class="mb-0 fw-bold text-dark" id="stat-du-dieu-kien">{{ $stats['du_dieu_kien'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0 fw-semibold">Tạm hoãn / Miễn gọi</p>
                    <h4 class="mb-0 fw-bold text-dark" id="stat-tam-hoan">{{ $stats['tam_hoan'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                    <i class="bi bi-person-up"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0 fw-semibold">Lực lượng Dân quân</p>
                    <h4 class="mb-0 fw-bold text-dark" id="stat-dan-quan">{{ $stats['dan_quan_tu_ve'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Content Area --}}
<div class="row g-4">
    {{-- Cột Bộ lọc --}}
    <div class="col-12">
        <div class="card filter-card">
            <div class="card-body">
                <h5 class="card-title fw-bold text-dark mb-3"><i class="bi bi-funnel me-2"></i>Bộ lọc tìm kiếm</h5>
                <form id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label for="filterSearch" class="form-label small fw-semibold text-secondary">Họ tên / Số CCCD</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bi bi-search text-secondary"></i></span>
                            <input type="text" class="form-control bg-light" id="filterSearch" placeholder="Nhập từ khóa tìm kiếm...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="filterStatus" class="form-label small fw-semibold text-secondary">Trạng thái NVQS</label>
                        <select class="form-select form-select-sm bg-light" id="filterStatus">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="du_dieu_kien">Đủ điều kiện</option>
                            <option value="tam_hoan">Tạm hoãn</option>
                            <option value="mien_goi">Miễn gọi</option>
                            <option value="trung_tuyen">Trúng tuyển</option>
                            <option value="da_nhap_ngu">Đã nhập ngũ</option>
                            <option value="xuat_ngu">Xuất ngũ</option>
                            <option value="chua_den_tuoi">Chưa đến tuổi</option>
                            <option value="da_qua_tuoi">Đã quá tuổi</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filterYear" class="form-label small fw-semibold text-secondary">Năm tuyển quân</label>
                        <input type="number" class="form-control form-control-sm bg-light" id="filterYear" placeholder="Ví dụ: {{ date('Y') }}" min="1900" max="2100">
                    </div>
                    <div class="col-md-2">
                        <label for="filterThon" class="form-label small fw-semibold text-secondary">Thôn / Xóm</label>
                        <input type="text" class="form-control form-control-sm bg-light" id="filterThon" placeholder="Nhập tên thôn...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-sm btn-success flex-grow-1"><i class="bi bi-funnel-fill"></i> Lọc</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearFilterBtn"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bảng danh sách chính --}}
    <div class="col-12">
        <div class="card data-card">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-table me-2"></i>Danh sách nghĩa vụ quân sự</h5>
                @can('manage_nghia_vu')
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-success fw-semibold" data-bs-toggle="modal" data-bs-target="#scanNVQSModal">
                        <i class="bi bi-cpu me-1"></i> Quét tự động
                    </button>
                    <button class="btn btn-sm btn-success fw-semibold" id="openAddModalBtn">
                        <i class="bi bi-plus-lg me-1"></i> Thêm thủ công
                    </button>
                </div>
                @endcan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small">
                            <tr>
                                <th class="ps-4">Họ tên công dân</th>
                                <th>CCCD/CMND</th>
                                <th>Ngày sinh</th>
                                <th>Địa chỉ (Thôn)</th>
                                <th>Học vấn</th>
                                <th>Năm tuyển quân</th>
                                <th>Trạng thái NVQS</th>
                                <th class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="nvqsTableBody">
                            {{-- AJAX loaded rows go here --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center" id="paginationWrapper">
                {{-- Pagination dynamically loaded --}}
            </div>
        </div>
    </div>
</div>

{{-- MODALS --}}

{{-- 1. Modal Quét tự động --}}
<div class="modal fade" id="scanNVQSModal" tabindex="-1" aria-labelledby="scanNVQSModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="scanNVQSModalLabel"><i class="bi bi-cpu text-success me-2"></i>Tự động quét danh sách NVQS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="scanForm">
                <div class="modal-body py-4">
                    <p class="text-secondary small">
                        Hệ thống sẽ quét tự động tất cả các nam công dân có hộ tịch (Thường trú, Tạm trú, Tạm vắng) trên địa bàn có độ tuổi:
                        <br>• Từ <strong>18 đến 25 tuổi</strong> (Phổ thông).
                        <br>• Từ <strong>26 đến 27 tuổi</strong> (Đối với công dân đã tốt nghiệp trình độ Đại học hoặc Sau đại học).
                        <br>Các công dân đủ điều kiện và chưa có hồ sơ NVQS sẽ được thêm mới tự động với trạng thái mặc định là <strong>Đủ điều kiện</strong>.
                    </p>
                    <div class="mb-3">
                        <label for="scanYear" class="form-label fw-semibold small text-secondary">Năm tuyển quân thực hiện quét</label>
                        <input type="number" class="form-control bg-light" id="scanYear" value="{{ date('Y') }}" required min="1900" max="2100">
                    </div>

                    {{-- Loading scanner --}}
                    <div id="scanLoading" class="text-center py-4 d-none">
                        <div class="spinner-border text-success mb-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-secondary small mb-0">Đang quét toàn bộ dữ liệu nhân khẩu trên địa bàn xã, vui lòng đợi...</p>
                    </div>

                    {{-- Scan results --}}
                    <div id="scanResult" class="d-none">
                        <div class="alert alert-success border-0 d-flex align-items-center gap-3 mb-3">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                            <div>
                                <h6 class="alert-heading fw-bold mb-1">Hoàn thành quét tự động!</h6>
                                <span class="small" id="scanSummaryText">Tổng số quét: X. Đã thêm mới: Y. Đã tồn tại: Z.</span>
                            </div>
                        </div>
                        <h6 class="fw-bold mb-2 text-dark small"><i class="bi bi-list-stars me-1"></i>Danh sách công dân mới được thêm vào:</h6>
                        <div class="border rounded-3 overflow-hidden" style="max-height: 250px; overflow-y: auto;">
                            <table class="table table-sm table-hover mb-0 text-nowrap align-middle">
                                <thead class="table-light small">
                                    <tr>
                                        <th class="ps-3">Họ và tên</th>
                                        <th>Ngày sinh</th>
                                        <th>Kết quả</th>
                                    </tr>
                                </thead>
                                <tbody id="scanResultTableBody" class="small">
                                    {{-- Scan details dynamic rows --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold" id="startScanBtn" style="border-radius: 8px;">Bắt đầu quét</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. Modal Thêm thủ công --}}
<div class="modal fade" id="addNVQSModal" tabindex="-1" aria-labelledby="addNVQSModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="addNVQSModalLabel"><i class="bi bi-person-plus text-success me-2"></i>Thêm mới hồ sơ nghĩa vụ quân sự</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addNVQSForm">
                <div class="modal-body py-3">
                    <!-- Search Citizen -->
                    <div class="mb-3 position-relative">
                        <label for="citizenSearchInput" class="form-label fw-semibold small text-secondary">Tìm kiếm nam công dân chưa đăng ký NVQS <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="citizenSearchInput" placeholder="Nhập họ tên hoặc CCCD để tìm kiếm..." autocomplete="off">
                        </div>
                        <ul class="list-group search-results-list d-none" id="citizenSearchResults"></ul>
                        <div id="selectedCitizenAlert" class="alert alert-info border-0 mt-2 py-2 d-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold" id="selectedCitizenInfo">Đã chọn: Nguyễn Văn A (CCCD: 001...)</span>
                                <button type="button" class="btn-close btn-sm" id="clearSelectedCitizenBtn" style="font-size: 0.65rem;"></button>
                            </div>
                        </div>
                        <input type="hidden" id="add_nhan_khau_id" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="add_nam_tuoi_tuyen_quan" class="form-label fw-semibold small text-secondary">Năm tuyển quân</label>
                            <input type="number" class="form-control" id="add_nam_tuoi_tuyen_quan" value="{{ date('Y') }}" min="1900" max="2100">
                        </div>
                        <div class="col-md-6">
                            <label for="add_trang_thai_nvqs" class="form-label fw-semibold small text-secondary">Trạng thái NVQS</label>
                            <select class="form-select form-status-select" id="add_trang_thai_nvqs">
                                <option value="du_dieu_kien">Đủ điều kiện</option>
                                <option value="tam_hoan">Tạm hoãn</option>
                                <option value="mien_goi">Miễn gọi</option>
                                <option value="trung_tuyen">Trúng tuyển</option>
                                <option value="da_nhap_ngu">Đã nhập ngũ</option>
                                <option value="xuat_ngu">Xuất ngũ</option>
                                <option value="chua_den_tuoi">Chưa đến tuổi</option>
                                <option value="da_qua_tuoi">Đã quá tuổi</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="add_ket_qua_kham_suc_khoe" class="form-label fw-semibold small text-secondary">Kết quả khám sức khỏe</label>
                            <select class="form-select" id="add_ket_qua_kham_suc_khoe">
                                <option value="chua_kham">Chưa khám</option>
                                <option value="loai_1">Loại 1 (Rất tốt)</option>
                                <option value="loai_2">Loại 2 (Tốt)</option>
                                <option value="loai_3">Loại 3 (Khá)</option>
                                <option value="loai_4">Loại 4 (Trung bình)</option>
                                <option value="loai_5">Loại 5 (Kém)</option>
                                <option value="khong_du_suc_khoe">Không đủ sức khỏe</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_nam_dang_ky_kham_nvqs" class="form-label fw-semibold small text-secondary">Năm đăng ký khám sức khỏe</label>
                            <input type="number" class="form-control" id="add_nam_dang_ky_kham_nvqs" placeholder="Ví dụ: {{ date('Y') }}">
                        </div>

                        <!-- Fields for Tam hoan -->
                        <div class="col-md-6 cond-field cond-tam_hoan d-none">
                            <label for="add_ly_do_tam_hoan" class="form-label fw-semibold small text-secondary">Lý do tạm hoãn</label>
                            <select class="form-select" id="add_ly_do_tam_hoan">
                                <option value="khong_ap_dung">Không áp dụng</option>
                                <option value="di_hoc_dai_hoc">Đi học Đại học/Cao đẳng</option>
                                <option value="benh_tat_suc_khoe">Bệnh tật/Sức khỏe yếu</option>
                                <option value="con_mot_con">Con một trong gia đình</option>
                                <option value="nuoi_duong_than_nhan">Nuôi dưỡng thân nhân neo đơn</option>
                                <option value="ly_do_khac">Lý do khác</option>
                            </select>
                        </div>
                        <div class="col-md-6 cond-field cond-tam_hoan d-none">
                            <label for="add_ngay_tam_hoan_den" class="form-label fw-semibold small text-secondary">Tạm hoãn đến ngày</label>
                            <input type="date" class="form-control" id="add_ngay_tam_hoan_den">
                        </div>

                        <!-- Fields for Nhap ngu / Xuat ngu -->
                        <div class="col-md-6 cond-field cond-da_nhap_ngu cond-xuat_ngu d-none">
                            <label for="add_ngay_nhap_ngu" class="form-label fw-semibold small text-secondary">Ngày nhập ngũ</label>
                            <input type="date" class="form-control" id="add_ngay_nhap_ngu">
                        </div>
                        <div class="col-md-6 cond-field cond-da_nhap_ngu cond-xuat_ngu d-none">
                            <label for="add_don_vi_quan_doi" class="form-label fw-semibold small text-secondary">Đơn vị quân đội</label>
                            <input type="text" class="form-control" id="add_don_vi_quan_doi" placeholder="Đại đội, Trung đoàn, Sư đoàn...">
                        </div>

                        <!-- Fields for Xuat ngu -->
                        <div class="col-md-6 cond-field cond-xuat_ngu d-none">
                            <label for="add_ngay_xuat_ngu" class="form-label fw-semibold small text-secondary">Ngày xuất ngũ</label>
                            <input type="date" class="form-control" id="add_ngay_xuat_ngu">
                        </div>
                        <div class="col-md-6 cond-field cond-xuat_ngu d-none">
                            <label for="add_quan_ham_khi_xuat_ngu" class="form-label fw-semibold small text-secondary">Quân hàm khi xuất ngũ</label>
                            <input type="text" class="form-control" id="add_quan_ham_khi_xuat_ngu" placeholder="Binh nhì, Hạ sĩ, Trung úy...">
                        </div>

                        <div class="col-12">
                            <label for="add_ghi_chu" class="form-label fw-semibold small text-secondary">Ghi chú thêm</label>
                            <textarea class="form-control" id="add_ghi_chu" rows="2" placeholder="Ghi nhận hồ sơ, hoàn cảnh gia đình hoặc ghi chú đặc biệt..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold" style="border-radius: 8px;">Thêm hồ sơ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. Modal Sửa hồ sơ --}}
<div class="modal fade" id="editNVQSModal" tabindex="-1" aria-labelledby="editNVQSModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editNVQSModalLabel"><i class="bi bi-pencil-square text-success me-2"></i>Cập nhật thông tin NVQS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editNVQSForm">
                <input type="hidden" id="edit_id">
                <input type="hidden" id="edit_nhan_khau_id">
                <div class="modal-body py-3">
                    <!-- Read-only citizen profile banner -->
                    <div class="bg-light p-3 rounded-3 mb-3 d-flex align-items-center gap-3">
                        <div class="avatar-placeholder text-uppercase" id="edit_citizen_initial">A</div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark" id="edit_citizen_name">Họ và tên công dân</h6>
                            <small class="text-secondary d-block" id="edit_citizen_subinfo">CCCD: ... | Sinh ngày: ... | Học vấn: ...</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_nam_tuoi_tuyen_quan" class="form-label fw-semibold small text-secondary">Năm tuyển quan</label>
                            <input type="number" class="form-control" id="edit_nam_tuoi_tuyen_quan" required min="1900" max="2100">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_trang_thai_nvqs" class="form-label fw-semibold small text-secondary">Trạng thái NVQS</label>
                            <select class="form-select form-status-select" id="edit_trang_thai_nvqs">
                                <option value="chua_den_tuoi">Chưa đến tuổi</option>
                                <option value="du_dieu_kien">Đủ điều kiện</option>
                                <option value="tam_hoan">Tạm hoãn</option>
                                <option value="mien_goi">Miễn gọi</option>
                                <option value="trung_tuyen">Trúng tuyển</option>
                                <option value="da_nhap_ngu">Đã nhập ngũ</option>
                                <option value="xuat_ngu">Xuất ngũ</option>
                                <option value="da_qua_tuoi">Đã quá tuổi</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_ket_qua_kham_suc_khoe" class="form-label fw-semibold small text-secondary">Kết quả khám sức khỏe</label>
                            <select class="form-select" id="edit_ket_qua_kham_suc_khoe">
                                <option value="chua_kham">Chưa khám</option>
                                <option value="loai_1">Loại 1 (Rất tốt)</option>
                                <option value="loai_2">Loại 2 (Tốt)</option>
                                <option value="loai_3">Loại 3 (Khá)</option>
                                <option value="loai_4">Loại 4 (Trung bình)</option>
                                <option value="loai_5">Loại 5 (Kém)</option>
                                <option value="khong_du_suc_khoe">Không đủ sức khỏe</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_nam_dang_ky_kham_nvqs" class="form-label fw-semibold small text-secondary">Năm đăng ký khám sức khỏe</label>
                            <input type="number" class="form-control" id="edit_nam_dang_ky_kham_nvqs">
                        </div>

                        <!-- Fields for Tam hoan -->
                        <div class="col-md-6 cond-field cond-tam_hoan d-none">
                            <label for="edit_ly_do_tam_hoan" class="form-label fw-semibold small text-secondary">Lý do tạm hoãn</label>
                            <select class="form-select" id="edit_ly_do_tam_hoan">
                                <option value="khong_ap_dung">Không áp dụng</option>
                                <option value="di_hoc_dai_hoc">Đi học Đại học/Cao đẳng</option>
                                <option value="benh_tat_suc_khoe">Bệnh tật/Sức khỏe yếu</option>
                                <option value="con_mot_con">Con một trong gia đình</option>
                                <option value="nuoi_duong_than_nhan">Nuôi dưỡng thân nhân neo đơn</option>
                                <option value="ly_do_khac">Lý do khác</option>
                            </select>
                        </div>
                        <div class="col-md-6 cond-field cond-tam_hoan d-none">
                            <label for="edit_ngay_tam_hoan_den" class="form-label fw-semibold small text-secondary">Tạm hoãn đến ngày</label>
                            <input type="date" class="form-control" id="edit_ngay_tam_hoan_den">
                        </div>

                        <!-- Fields for Nhap ngu / Xuat ngu -->
                        <div class="col-md-6 cond-field cond-da_nhap_ngu cond-xuat_ngu d-none">
                            <label for="edit_ngay_nhap_ngu" class="form-label fw-semibold small text-secondary">Ngày nhập ngũ</label>
                            <input type="date" class="form-control" id="edit_ngay_nhap_ngu">
                        </div>
                        <div class="col-md-6 cond-field cond-da_nhap_ngu cond-xuat_ngu d-none">
                            <label for="edit_don_vi_quan_doi" class="form-label fw-semibold small text-secondary">Đơn vị quân đội</label>
                            <input type="text" class="form-control" id="edit_don_vi_quan_doi">
                        </div>

                        <!-- Fields for Xuat ngu -->
                        <div class="col-md-6 cond-field cond-xuat_ngu d-none">
                            <label for="edit_ngay_xuat_ngu" class="form-label fw-semibold small text-secondary">Ngày xuất ngũ</label>
                            <input type="date" class="form-control" id="edit_ngay_xuat_ngu">
                        </div>
                        <div class="col-md-6 cond-field cond-xuat_ngu d-none">
                            <label for="edit_quan_ham_khi_xuat_ngu" class="form-label fw-semibold small text-secondary">Quân hàm khi xuất ngũ</label>
                            <input type="text" class="form-control" id="edit_quan_ham_khi_xuat_ngu">
                        </div>

                        <div class="col-12">
                            <label for="edit_ghi_chu" class="form-label fw-semibold small text-secondary">Ghi chú thêm</label>
                            <textarea class="form-control" id="edit_ghi_chu" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
                    <button type="submit" class="btn btn-success btn-sm fw-semibold" style="border-radius: 8px;">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. Modal Chi tiết hồ sơ --}}
<div class="modal fade" id="detailNVQSModal" tabindex="-1" aria-labelledby="detailNVQSModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="detailNVQSModalLabel"><i class="bi bi-info-circle text-success me-2"></i>Chi tiết hồ sơ nghĩa vụ quân sự</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                {{-- Banner display --}}
                <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-4">
                    <div class="avatar-placeholder text-uppercase fs-4" id="det_citizen_initial" style="width: 56px; height: 56px;">A</div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark" id="det_citizen_name">Họ và tên công dân</h5>
                        <span class="badge-status" id="det_trang_thai_nvqs_badge">Đủ điều kiện</span>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Thông tin công dân --}}
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-success mb-3"><i class="bi bi-person me-1"></i>Thông tin cá nhân</h6>
                        <table class="table table-borderless table-sm small">
                            <tr>
                                <td class="text-secondary fw-semibold py-1" style="width: 40%;">Số CCCD:</td>
                                <td class="text-dark py-1" id="det_cccd_cmnd">---</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-1">Ngày sinh:</td>
                                <td class="text-dark py-1" id="det_ngay_sinh">---</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-1">Học vấn:</td>
                                <td class="text-dark py-1" id="det_trinh_do_hoc_van">---</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-1">Trạng thái dân sự:</td>
                                <td class="text-dark py-1" id="det_dan_su_trang_thai">---</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-1">Nơi cư trú:</td>
                                <td class="text-dark py-1" id="det_thon_xom_dia_chi">---</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Thông tin quân sự --}}
                    <div class="col-md-6 ps-md-4">
                        <h6 class="fw-bold text-success mb-3"><i class="bi bi-shield-check me-1"></i>Thông tin nghĩa vụ</h6>
                        <table class="table table-borderless table-sm small">
                            <tr>
                                <td class="text-secondary fw-semibold py-1" style="width: 45%;">Năm tuyển quân:</td>
                                <td class="text-dark py-1 fw-bold" id="det_nam_tuoi_tuyen_quan">---</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-1">Khám sức khỏe:</td>
                                <td class="text-dark py-1" id="det_ket_qua_kham_suc_khoe">---</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-1">Năm khám:</td>
                                <td class="text-dark py-1" id="det_nam_dang_ky_kham_nvqs">---</td>
                            </tr>
                            <tr class="det-field-tam_hoan d-none">
                                <td class="text-secondary fw-semibold py-1">Lý do tạm hoãn:</td>
                                <td class="text-dark py-1" id="det_ly_do_tam_hoan">---</td>
                            </tr>
                            <tr class="det-field-tam_hoan d-none">
                                <td class="text-secondary fw-semibold py-1">Hoãn đến ngày:</td>
                                <td class="text-dark py-1" id="det_ngay_tam_hoan_den">---</td>
                            </tr>
                            <tr class="det-field-nhap_ngu d-none">
                                <td class="text-secondary fw-semibold py-1">Ngày nhập ngũ:</td>
                                <td class="text-dark py-1" id="det_ngay_nhap_ngu">---</td>
                            </tr>
                            <tr class="det-field-nhap_ngu d-none">
                                <td class="text-secondary fw-semibold py-1">Đơn vị phục vụ:</td>
                                <td class="text-dark py-1" id="det_don_vi_quan_doi">---</td>
                            </tr>
                            <tr class="det-field-xuat_ngu d-none">
                                <td class="text-secondary fw-semibold py-1">Ngày xuất ngũ:</td>
                                <td class="text-dark py-1" id="det_ngay_xuat_ngu">---</td>
                            </tr>
                            <tr class="det-field-xuat_ngu d-none">
                                <td class="text-secondary fw-semibold py-1">Quân hàm xuất ngũ:</td>
                                <td class="text-dark py-1" id="det_quan_ham_khi_xuat_ngu">---</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-12 mt-3 pt-3 border-top">
                        <label class="form-label fw-bold small text-secondary mb-1">Ghi chú nội bộ</label>
                        <p class="bg-light p-3 rounded-3 text-dark small" id="det_ghi_chu" style="white-space: pre-wrap; min-height: 50px;">Không có ghi chú.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal" style="border-radius: 8px;">Đóng</button>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div class="toast-container">
    <div id="statusToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 12px; display: none;">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2 small fw-semibold" id="toastMessage">
                <i class="bi bi-check-circle-fill"></i> Thành công!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';
    const canManage = @json(auth()->user()->can('manage_nghia_vu'));
    
    // UI Helpers
    const statusLabels = {
        'chua_den_tuoi': 'Chưa đến tuổi',
        'du_dieu_kien': 'Đủ điều kiện',
        'tam_hoan': 'Tạm hoãn',
        'mien_goi': 'Miễn gọi',
        'trung_tuyen': 'Trúng tuyển',
        'da_nhap_ngu': 'Đã nhập ngũ',
        'xuat_ngu': 'Xuất ngũ',
        'da_qua_tuoi': 'Đã quá tuổi'
    };

    const statusBadgeClasses = {
        'chua_den_tuoi': 'bg-light text-dark border',
        'du_dieu_kien': 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
        'tam_hoan': 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
        'mien_goi': 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
        'trung_tuyen': 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
        'da_nhap_ngu': 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
        'xuat_ngu': 'bg-dark bg-opacity-10 text-dark border border-dark border-opacity-25',
        'da_qua_tuoi': 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'
    };

    const healthLabels = {
        'chua_kham': 'Chưa khám',
        'loai_1': 'Loại 1 (Rất tốt)',
        'loai_2': 'Loại 2 (Tốt)',
        'loai_3': 'Loại 3 (Khá)',
        'loai_4': 'Loại 4 (Trung bình)',
        'loai_5': 'Loại 5 (Kém)',
        'khong_du_suc_khoe': 'Không đủ sức khỏe'
    };

    const deferralLabels = {
        'khong_ap_dung': 'Không áp dụng',
        'di_hoc_dai_hoc': 'Đi học Đại học/Cao đẳng',
        'benh_tat_suc_khoe': 'Bệnh tật/Sức khỏe yếu',
        'con_mot_con': 'Con một',
        'nuoi_duong_than_nhan': 'Nuôi dưỡng thân nhân neo đơn',
        'ly_do_khac': 'Lý do khác'
    };

    // State
    let currentPage = 1;
    let filters = {
        search: '',
        trang_thai_nvqs: '',
        nam_tuoi_tuyen_quan: '',
        thon_xom: ''
    };

    // DOM Elements
    const tableBody = document.getElementById('nvqsTableBody');
    const paginationWrapper = document.getElementById('paginationWrapper');
    const filterForm = document.getElementById('filterForm');
    const clearFilterBtn = document.getElementById('clearFilterBtn');
    
    // Initialize Modals
    const scanModal = new bootstrap.Modal(document.getElementById('scanNVQSModal'));
    const addModal = new bootstrap.Modal(document.getElementById('addNVQSModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editNVQSModal'));
    const detailModal = new bootstrap.Modal(document.getElementById('detailNVQSModal'));

    // Form conditional logic based on status select
    const statusSelects = document.querySelectorAll('.form-status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function () {
            const form = this.closest('form');
            const status = this.value;
            
            // Hide all conditional fields first
            form.querySelectorAll('.cond-field').forEach(el => el.classList.add('d-none'));

            if (status === 'tam_hoan') {
                form.querySelectorAll('.cond-tam_hoan').forEach(el => el.classList.remove('d-none'));
            } else if (status === 'da_nhap_ngu') {
                form.querySelectorAll('.cond-da_nhap_ngu').forEach(el => el.classList.remove('d-none'));
            } else if (status === 'xuat_ngu') {
                form.querySelectorAll('.cond-da_nhap_ngu, .cond-xuat_ngu').forEach(el => el.classList.remove('d-none'));
            }
        });
    });

    // Elegant Toast Alert
    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('statusToast');
        const messageEl = document.getElementById('toastMessage');
        
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        messageEl.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'}"></i> ${message}`;
        
        toastEl.style.display = 'block';
        const bsToast = new bootstrap.Toast(toastEl, { delay: 4000 });
        bsToast.show();
    }

    // Load data from API
    function loadNVQSData(page = 1) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="spinner-border text-success mb-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="text-secondary small">Đang tải dữ liệu hồ sơ NVQS...</div>
                </td>
            </tr>
        `;

        const queryParams = new URLSearchParams({
            page: page,
            search: filters.search,
            trang_thai_nvqs: filters.trang_thai_nvqs,
            nam_tuoi_tuyen_quan: filters.nam_tuoi_tuyen_quan,
            thon_xom: filters.thon_xom
        });

        fetch(`/api/nghia-vu-quan-su?${queryParams.toString()}`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    renderTable(res.data.data);
                    renderPagination(res.data);
                    currentPage = page;
                } else {
                    showToast('Có lỗi xảy ra khi lấy danh sách nghĩa vụ!', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Không thể kết nối đến máy chủ!', 'danger');
            });
    }

    function renderTable(data) {
        if (!data || data.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        <span class="fw-semibold">Không tìm thấy hồ sơ nghĩa vụ quân sự nào</span>
                        <div class="small text-secondary">Hãy thử thay đổi điều kiện lọc hoặc thực hiện quét tự động.</div>
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = data.map(item => {
            const citizen = item.nhan_khau || {};
            const fullname = citizen.ho_ten || '—';
            const cccd = citizen.cccd_cmnd || '—';
            const dob = citizen.ngay_sinh ? formatDate(citizen.ngay_sinh) : '—';
            const address = citizen.ho_khau?.thon_xom || '—';
            
            const educationKeys = {
                'mu_chu': 'Mù chữ',
                'tieu_hoc': 'Tiểu học',
                'thcs': 'THCS',
                'thpt': 'THPT',
                'trung_cap': 'Trung cấp',
                'cao_dang': 'Cao đẳng',
                'dai_hoc': 'Đại học',
                'sau_dai_hoc': 'Sau đại học'
            };
            const education = educationKeys[citizen.trinh_do_hoc_van] || '—';
            const targetYear = item.nam_tuoi_tuyen_quan || '—';
            
            const statusLabel = statusLabels[item.trang_thai_nvqs] || 'Chưa rõ';
            const statusClass = statusBadgeClasses[item.trang_thai_nvqs] || 'bg-secondary';

            return `
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-placeholder text-uppercase" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                ${fullname.substring(0, 1)}
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">${fullname}</span>
                            </div>
                        </div>
                    </td>
                    <td><code class="text-secondary">${cccd}</code></td>
                    <td>${dob}</td>
                    <td>${address}</td>
                    <td><span class="small fw-semibold text-secondary">${education}</span></td>
                    <td class="fw-bold text-dark">${targetYear}</td>
                    <td><span class="badge-status ${statusClass}">${statusLabel}</span></td>
                    <td class="text-end pe-4">
                        <div class="btn-group">
                            ${canManage ? `
                                <button class="btn btn-sm btn-light text-secondary action-view" data-id="${item.id}" title="Xem chi tiết" style="border-radius: 6px 0 0 6px;"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-light text-success action-edit" data-id="${item.id}" title="Cập nhật"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-light text-danger action-delete" data-id="${item.id}" title="Xóa hồ sơ" style="border-radius: 0 6px 6px 0;"><i class="bi bi-trash"></i></button>
                            ` : `
                                <button class="btn btn-sm btn-light text-secondary action-view" data-id="${item.id}" title="Xem chi tiết" style="border-radius: 6px;"><i class="bi bi-eye"></i></button>
                            `}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        // Attach action events dynamically
        document.querySelectorAll('.action-view').forEach(btn => {
            btn.addEventListener('click', function() { showDetail(this.dataset.id); });
        });
        if (canManage) {
            document.querySelectorAll('.action-edit').forEach(btn => {
                btn.addEventListener('click', function() { showEdit(this.dataset.id); });
            });
            document.querySelectorAll('.action-delete').forEach(btn => {
                btn.addEventListener('click', function() { handleDelete(this.dataset.id); });
            });
        }
    }

    function renderPagination(paginator) {
        if (!paginator || paginator.last_page <= 1) {
            paginationWrapper.innerHTML = `<span class="small text-secondary">Hiển thị tất cả ${paginator.total || 0} bản ghi</span>`;
            return;
        }

        const current = paginator.current_page;
        const last = paginator.last_page;
        
        let html = `<span class="small text-secondary">Hiển thị ${paginator.from || 0} đến ${paginator.to || 0} của ${paginator.total || 0} bản ghi</span>`;
        html += `<ul class="pagination pagination-sm mb-0">`;
        
        // Prev button
        html += `
            <li class="page-item ${current === 1 ? 'disabled' : ''}">
                <button class="page-link" data-page="${current - 1}">&laquo;</button>
            </li>
        `;

        for (let i = 1; i <= last; i++) {
            if (i === 1 || i === last || (i >= current - 2 && i <= current + 2)) {
                html += `
                    <li class="page-item ${i === current ? 'active' : ''}">
                        <button class="page-link ${i === current ? 'bg-success border-success' : 'text-success'}" data-page="${i}">${i}</button>
                    </li>
                `;
            } else if (i === 2 || i === last - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Next button
        html += `
            <li class="page-item ${current === last ? 'disabled' : ''}">
                <button class="page-link" data-page="${current + 1}">&raquo;</button>
            </li>
        `;
        html += `</ul>`;

        paginationWrapper.innerHTML = html;

        // Attach click events
        paginationWrapper.querySelectorAll('.page-link').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetPage = parseInt(this.dataset.page);
                if (targetPage && targetPage !== current) {
                    loadNVQSData(targetPage);
                }
            });
        });
    }

    // Date formatting helper
    function formatDate(dateString) {
        if (!dateString) return '';
        const d = new Date(dateString);
        if (isNaN(d.getTime())) return dateString;
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Format Date for Input elements (yyyy-MM-dd)
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        const d = new Date(dateString);
        if (isNaN(d.getTime())) return '';
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        return `${year}-${month}-${day}`;
    }

    // Load initial data
    loadNVQSData(1);

    // Filter Form submit
    filterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        filters.search = document.getElementById('filterSearch').value.trim();
        filters.trang_thai_nvqs = document.getElementById('filterStatus').value;
        filters.nam_tuoi_tuyen_quan = document.getElementById('filterYear').value.trim();
        filters.thon_xom = document.getElementById('filterThon').value.trim();
        loadNVQSData(1);
    });

    // Clear Filter
    clearFilterBtn.addEventListener('click', function () {
        document.getElementById('filterSearch').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterYear').value = '';
        document.getElementById('filterThon').value = '';
        filters = { search: '', trang_thai_nvqs: '', nam_tuoi_tuyen_quan: '', thon_xom: '' };
        loadNVQSData(1);
    });

    // --- AUTOMATIC SCANNING logic ---
    if (canManage) {
        const scanForm = document.getElementById('scanForm');
        const scanLoading = document.getElementById('scanLoading');
        const scanResult = document.getElementById('scanResult');
        const startScanBtn = document.getElementById('startScanBtn');
        const scanResultTableBody = document.getElementById('scanResultTableBody');

        scanForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const year = document.getElementById('scanYear').value;
            
            // UI reset states
            startScanBtn.disabled = true;
            scanLoading.classList.remove('d-none');
            scanResult.classList.add('d-none');

            fetch('/api/nghia-vu-quan-su/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ nam_tuyen_quan: year })
            })
            .then(res => res.json())
            .then(res => {
                scanLoading.classList.add('d-none');
                startScanBtn.disabled = false;
                
                if (res.success && res.data) {
                    const details = res.data.details || [];
                    
                    document.getElementById('scanSummaryText').textContent = 
                        `Tổng công dân được quét: ${res.data.total_scanned}. Đã tạo hồ sơ mới: ${res.data.added_count}. Đã có hồ sơ từ trước: ${res.data.existing_count}.`;
                    
                    if (details.length === 0) {
                        scanResultTableBody.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Không có công dân nào được thêm mới trong đợt quét này.</td>
                            </tr>
                        `;
                    } else {
                        scanResultTableBody.innerHTML = details.map(d => `
                            <tr>
                                <td class="ps-3 fw-semibold text-dark">${d.ho_ten}</td>
                                <td>${formatDate(d.ngay_sinh)}</td>
                                <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.7rem;">Đã thêm mới</span></td>
                            </tr>
                        `).join('');
                    }

                    scanResult.classList.remove('d-none');
                    
                    // Reload data table and stats at top
                    loadNVQSData(1);
                    updateStats();
                } else {
                    showToast(res.message || 'Quét thất bại!', 'danger');
                }
            })
            .catch(err => {
                scanLoading.classList.add('d-none');
                startScanBtn.disabled = false;
                console.error(err);
                showToast('Lỗi máy chủ khi quét tự động!', 'danger');
            });
        });

        // Reset scan modal on close
        document.getElementById('scanNVQSModal').addEventListener('hidden.bs.modal', function () {
            scanLoading.classList.add('d-none');
            scanResult.classList.add('d-none');
            startScanBtn.disabled = false;
            scanResultTableBody.innerHTML = '';
        });

        // --- MANUAL ADD CITIZEN logic ---
        const citizenSearchInput = document.getElementById('citizenSearchInput');
        const citizenSearchResults = document.getElementById('citizenSearchResults');
        const selectedCitizenAlert = document.getElementById('selectedCitizenAlert');
        const selectedCitizenInfo = document.getElementById('selectedCitizenInfo');
        const addNhanKhauId = document.getElementById('add_nhan_khau_id');
        const clearSelectedCitizenBtn = document.getElementById('clearSelectedCitizenBtn');

        // Trigger modal opening cleanly and reset form
        document.getElementById('openAddModalBtn').addEventListener('click', function () {
            document.getElementById('addNVQSForm').reset();
            resetSelectedCitizen();
            
            // Force hide all conditional fields
            document.querySelectorAll('#addNVQSForm .cond-field').forEach(el => el.classList.add('d-none'));
            addModal.show();
        });

        // Search eligible citizens
        let searchDebounce;
        citizenSearchInput.addEventListener('input', function () {
            clearTimeout(searchDebounce);
            const searchVal = this.value.trim();

            if (searchVal.length < 2) {
                citizenSearchResults.classList.add('d-none');
                return;
            }

            searchDebounce = setTimeout(() => {
                fetch(`/api/nghia-vu-quan-su/eligible-citizens?search=${encodeURIComponent(searchVal)}`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data) {
                            renderSearchResults(res.data);
                        }
                    });
            }, 300);
        });

        function renderSearchResults(data) {
            if (!data || data.length === 0) {
                citizenSearchResults.innerHTML = `
                    <li class="list-group-item text-muted small py-3 text-center">
                        <i class="bi bi-person-exclamation fs-4 d-block mb-1"></i>
                        Không tìm thấy nam công dân đủ điều kiện nào chưa đăng ký NVQS.
                    </li>
                `;
                citizenSearchResults.classList.remove('d-none');
                return;
            }

            citizenSearchResults.innerHTML = data.map(c => {
                const birth = c.ngay_sinh ? formatDate(c.ngay_sinh) : '—';
                const trinhDo = {
                    'dai_hoc': 'Đại học',
                    'sau_dai_hoc': 'Sau đại học',
                    'thpt': 'THPT',
                    'thcs': 'THCS',
                    'tieu_hoc': 'Tiểu học'
                }[c.trinh_do_hoc_van] || 'Khác';

                return `
                    <button type="button" class="list-group-item list-group-item-action text-start select-citizen-btn py-2" 
                        data-id="${c.id}" data-name="${c.ho_ten}" data-cccd="${c.cccd_cmnd}" data-dob="${birth}" data-edu="${trinhDo}">
                        <div class="fw-bold text-dark small">${c.ho_ten}</div>
                        <div class="text-secondary" style="font-size: 0.75rem;">CCCD: ${c.cccd_cmnd || '—'} | Sinh ngày: ${birth} | Học vấn: ${trinhDo}</div>
                    </button>
                `;
            }).join('');
            
            citizenSearchResults.classList.remove('d-none');

            // Attach click select handler
            citizenSearchResults.querySelectorAll('.select-citizen-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    selectCitizen(this.dataset.id, this.dataset.name, this.dataset.cccd, this.dataset.dob, this.dataset.edu);
                });
            });
        }

        function selectCitizen(id, name, cccd, dob, edu) {
            addNhanKhauId.value = id;
            selectedCitizenInfo.textContent = `Đã chọn: ${name} (CCCD: ${cccd || '—'} | Sinh ngày: ${dob} | Học vấn: ${edu})`;
            selectedCitizenAlert.classList.remove('d-none');
            citizenSearchInput.value = name;
            citizenSearchResults.classList.add('d-none');
            citizenSearchInput.readOnly = true;
        }

        function resetSelectedCitizen() {
            addNhanKhauId.value = '';
            selectedCitizenAlert.classList.add('d-none');
            citizenSearchInput.value = '';
            citizenSearchInput.readOnly = false;
            citizenSearchResults.classList.add('d-none');
        }

        clearSelectedCitizenBtn.addEventListener('click', resetSelectedCitizen);

        // Hide search suggestions clicking outside
        document.addEventListener('click', function (e) {
            if (!citizenSearchInput.contains(e.target) && !citizenSearchResults.contains(e.target)) {
                citizenSearchResults.classList.add('d-none');
            }
        });

        // Form manual submit
        const addNVQSForm = document.getElementById('addNVQSForm');
        addNVQSForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            if (!addNhanKhauId.value) {
                showToast('Vui lòng tìm và chọn một công dân trước!', 'danger');
                return;
            }

            const data = {
                nhan_khau_id: parseInt(addNhanKhauId.value),
                nam_tuoi_tuyen_quan: document.getElementById('add_nam_tuoi_tuyen_quan').value ? parseInt(document.getElementById('add_nam_tuoi_tuyen_quan').value) : null,
                trang_thai_nvqs: document.getElementById('add_trang_thai_nvqs').value,
                ket_qua_kham_suc_khoe: document.getElementById('add_ket_qua_kham_suc_khoe').value,
                nam_dang_ky_kham_nvqs: document.getElementById('add_nam_dang_ky_kham_nvqs').value ? parseInt(document.getElementById('add_nam_dang_ky_kham_nvqs').value) : null,
                ly_do_tam_hoan: document.getElementById('add_ly_do_tam_hoan').value,
                ngay_tam_hoan_den: document.getElementById('add_ngay_tam_hoan_den').value || null,
                ngay_nhap_ngu: document.getElementById('add_ngay_nhap_ngu').value || null,
                don_vi_quan_doi: document.getElementById('add_don_vi_quan_doi').value || null,
                ngay_xuat_ngu: document.getElementById('add_ngay_xuat_ngu').value || null,
                quan_ham_khi_xuat_ngu: document.getElementById('add_quan_ham_khi_xuat_ngu').value || null,
                ghi_chu: document.getElementById('add_ghi_chu').value || null
            };

            fetch('/api/nghia-vu-quan-su', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message || 'Thêm hồ sơ thành công!');
                    addModal.hide();
                    loadNVQSData(1);
                    updateStats();
                } else {
                    showToast(res.message || 'Thêm hồ sơ thất bại!', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Lỗi gửi dữ liệu đến máy chủ!', 'danger');
            });
        });
    }

    // --- SHOW DETAILED PROFILE ---
    function showDetail(id) {
        fetch(`/api/nghia-vu-quan-su/${id}`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const item = res.data;
                    const citizen = item.nhan_khau || {};
                    
                    document.getElementById('det_citizen_initial').textContent = (citizen.ho_ten || '—').substring(0, 1);
                    document.getElementById('det_citizen_name').textContent = citizen.ho_ten || '—';
                    
                    // Badge status
                    const statusBadge = document.getElementById('det_trang_thai_nvqs_badge');
                    statusBadge.textContent = statusLabels[item.trang_thai_nvqs] || 'Chưa rõ';
                    statusBadge.className = `badge-status ${statusBadgeClasses[item.trang_thai_nvqs] || 'bg-secondary'}`;
                    
                    // Citizen details
                    document.getElementById('det_cccd_cmnd').textContent = citizen.cccd_cmnd || '—';
                    document.getElementById('det_ngay_sinh').textContent = citizen.ngay_sinh ? formatDate(citizen.ngay_sinh) : '—';
                    document.getElementById('det_trinh_do_hoc_van').textContent = {
                        'mu_chu': 'Mù chữ', 'tieu_hoc': 'Tiểu học', 'thcs': 'THCS', 'thpt': 'THPT',
                        'trung_cap': 'Trung cấp', 'cao_dang': 'Cao đẳng', 'dai_hoc': 'Đại học', 'sau_dai_hoc': 'Sau đại học'
                    }[citizen.trinh_do_hoc_van] || 'Không xác định';
                    
                    document.getElementById('det_dan_su_trang_thai').textContent = {
                        'hoat_dong': 'Thường trú', 'tam_tru': 'Tạm trú', 'tam_vang': 'Tạm vắng',
                        'da_chuyen_di': 'Đã chuyển đi', 'da_mat': 'Đã mất'
                    }[citizen.trang_thai] || 'Không rõ';
                    
                    document.getElementById('det_thon_xom_dia_chi').textContent = citizen.ho_khau?.thon_xom || '—';
                    
                    // Military details
                    document.getElementById('det_nam_tuoi_tuyen_quan').textContent = item.nam_tuoi_tuyen_quan || '—';
                    document.getElementById('det_ket_qua_kham_suc_khoe').textContent = healthLabels[item.ket_qua_kham_suc_khoe] || 'Chưa khám';
                    document.getElementById('det_nam_dang_ky_kham_nvqs').textContent = item.nam_dang_ky_kham_nvqs || 'Chưa ghi nhận';
                    
                    // Hide all conditional display rows
                    document.querySelectorAll('.det-field-tam_hoan').forEach(el => el.classList.add('d-none'));
                    document.querySelectorAll('.det-field-nhap_ngu').forEach(el => el.classList.add('d-none'));
                    document.querySelectorAll('.det-field-xuat_ngu').forEach(el => el.classList.add('d-none'));

                    if (item.trang_thai_nvqs === 'tam_hoan') {
                        document.querySelectorAll('.det-field-tam_hoan').forEach(el => el.classList.remove('d-none'));
                        document.getElementById('det_ly_do_tam_hoan').textContent = deferralLabels[item.ly_do_tam_hoan] || 'Không áp dụng';
                        document.getElementById('det_ngay_tam_hoan_den').textContent = item.ngay_tam_hoan_den ? formatDate(item.ngay_tam_hoan_den) : 'Chưa thiết lập';
                    } else if (item.trang_thai_nvqs === 'da_nhap_ngu') {
                        document.querySelectorAll('.det-field-nhap_ngu').forEach(el => el.classList.remove('d-none'));
                        document.getElementById('det_ngay_nhap_ngu').textContent = item.ngay_nhap_ngu ? formatDate(item.ngay_nhap_ngu) : '—';
                        document.getElementById('det_don_vi_quan_doi').textContent = item.don_vi_quan_doi || '—';
                    } else if (item.trang_thai_nvqs === 'xuat_ngu') {
                        document.querySelectorAll('.det-field-nhap_ngu, .det-field-xuat_ngu').forEach(el => el.classList.remove('d-none'));
                        document.getElementById('det_ngay_nhap_ngu').textContent = item.ngay_nhap_ngu ? formatDate(item.ngay_nhap_ngu) : '—';
                        document.getElementById('det_don_vi_quan_doi').textContent = item.don_vi_quan_doi || '—';
                        document.getElementById('det_ngay_xuat_ngu').textContent = item.ngay_xuat_ngu ? formatDate(item.ngay_xuat_ngu) : '—';
                        document.getElementById('det_quan_ham_khi_xuat_ngu').textContent = item.quan_ham_khi_xuat_ngu || '—';
                    }

                    document.getElementById('det_ghi_chu').textContent = item.ghi_chu || 'Không có ghi chú nào.';
                    detailModal.show();
                }
            });
    }

    // --- EDIT RECORD logic ---
    if (canManage) {
        const editNVQSForm = document.getElementById('editNVQSForm');
        
        function showEdit(id) {
            fetch(`/api/nghia-vu-quan-su/${id}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success && res.data) {
                        const item = res.data;
                        const citizen = item.nhan_khau || {};
                        
                        document.getElementById('edit_id').value = item.id;
                        document.getElementById('edit_nhan_khau_id').value = item.nhan_khau_id;
                        
                        // Citizen banner info
                        document.getElementById('edit_citizen_initial').textContent = (citizen.ho_ten || '—').substring(0, 1);
                        document.getElementById('edit_citizen_name').textContent = citizen.ho_ten || '—';
                        
                        const birth = citizen.ngay_sinh ? formatDate(citizen.ngay_sinh) : '—';
                        const edu = {
                            'dai_hoc': 'Đại học', 'sau_dai_hoc': 'Sau đại học', 'thpt': 'THPT', 'thcs': 'THCS'
                        }[citizen.trinh_do_hoc_van] || 'Khác';
                        document.getElementById('edit_citizen_subinfo').textContent = `CCCD: ${citizen.cccd_cmnd || '—'} | Sinh ngày: ${birth} | Học vấn: ${edu}`;

                        // Bind inputs
                        document.getElementById('edit_nam_tuoi_tuyen_quan').value = item.nam_tuoi_tuyen_quan || '';
                        document.getElementById('edit_trang_thai_nvqs').value = item.trang_thai_nvqs;
                        document.getElementById('edit_ket_qua_kham_suc_khoe').value = item.ket_qua_kham_suc_khoe || 'chua_kham';
                        document.getElementById('edit_nam_dang_ky_kham_nvqs').value = item.nam_dang_ky_kham_nvqs || '';
                        
                        document.getElementById('edit_ly_do_tam_hoan').value = item.ly_do_tam_hoan || 'khong_ap_dung';
                        document.getElementById('edit_ngay_tam_hoan_den').value = formatDateForInput(item.ngay_tam_hoan_den);
                        
                        document.getElementById('edit_ngay_nhap_ngu').value = formatDateForInput(item.ngay_nhap_ngu);
                        document.getElementById('edit_don_vi_quan_doi').value = item.don_vi_quan_doi || '';
                        
                        document.getElementById('edit_ngay_xuat_ngu').value = formatDateForInput(item.ngay_xuat_ngu);
                        document.getElementById('edit_quan_ham_khi_xuat_ngu').value = item.quan_ham_khi_xuat_ngu || '';
                        
                        document.getElementById('edit_ghi_chu').value = item.ghi_chu || '';

                        // Trigger change manually to show/hide conditional fields properly
                        const statusSelect = document.getElementById('edit_trang_thai_nvqs');
                        statusSelect.dispatchEvent(new Event('change'));

                        editModal.show();
                    }
                });
        }

        editNVQSForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;
            
            const data = {
                nhan_khau_id: parseInt(document.getElementById('edit_nhan_khau_id').value),
                nam_tuoi_tuyen_quan: document.getElementById('edit_nam_tuoi_tuyen_quan').value ? parseInt(document.getElementById('edit_nam_tuoi_tuyen_quan').value) : null,
                trang_thai_nvqs: document.getElementById('edit_trang_thai_nvqs').value,
                ket_qua_kham_suc_khoe: document.getElementById('edit_ket_qua_kham_suc_khoe').value,
                nam_dang_ky_kham_nvqs: document.getElementById('edit_nam_dang_ky_kham_nvqs').value ? parseInt(document.getElementById('edit_nam_dang_ky_kham_nvqs').value) : null,
                ly_do_tam_hoan: document.getElementById('edit_ly_do_tam_hoan').value,
                ngay_tam_hoan_den: document.getElementById('edit_ngay_tam_hoan_den').value || null,
                ngay_nhap_ngu: document.getElementById('edit_ngay_nhap_ngu').value || null,
                don_vi_quan_doi: document.getElementById('edit_don_vi_quan_doi').value || null,
                ngay_xuat_ngu: document.getElementById('edit_ngay_xuat_ngu').value || null,
                quan_ham_khi_xuat_ngu: document.getElementById('edit_quan_ham_khi_xuat_ngu').value || null,
                ghi_chu: document.getElementById('edit_ghi_chu').value || null
            };

            fetch(`/api/nghia-vu-quan-su/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message || 'Cập nhật thành công!');
                    editModal.hide();
                    loadNVQSData(currentPage);
                    updateStats();
                } else {
                    showToast(res.message || 'Cập nhật thất bại!', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Lỗi gửi yêu cầu cập nhật lên máy chủ!', 'danger');
            });
        });

        // --- DELETE RECORD logic ---
        function handleDelete(id) {
            const confirmDeleteModalEl = document.getElementById('confirmDeleteModal');
            if (!confirmDeleteModalEl) {
                if (confirm('Bạn có chắc chắn muốn xóa hồ sơ nghĩa vụ quân sự này không?')) {
                    executeDelete(id);
                }
                return;
            }

            const messageEl = document.getElementById('confirmDeleteModalMessage');
            const confirmBtn = document.getElementById('confirmDeleteSubmitBtn');
            
            messageEl.textContent = 'Bạn có chắc chắn muốn xóa hồ sơ nghĩa vụ quân sự này? Công dân sẽ được rút tên khỏi danh sách nghĩa vụ quân sự hiện tại.';
            
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', function() {
                executeDelete(id);
                const bsModal = bootstrap.Modal.getInstance(confirmDeleteModalEl);
                if (bsModal) bsModal.hide();
            });

            const bsModal = new bootstrap.Modal(confirmDeleteModalEl);
            bsModal.show();
        }

        function executeDelete(id) {
            fetch(`/api/nghia-vu-quan-su/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message || 'Xóa hồ sơ thành công!');
                    loadNVQSData(currentPage);
                    updateStats();
                } else {
                    showToast(res.message || 'Xóa hồ sơ thất bại!', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Không thể thực hiện xóa hồ sơ nghĩa vụ quân sự!', 'danger');
            });
        }
    }

    // Refresh statistics cards
    function updateStats() {
        fetch('/api/nghia-vu-quan-su')
            .then(res => res.json())
            .then(res => {
                fetch(window.location.href)
                    .then(r => r.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newTongSo = doc.getElementById('stat-tong-so')?.textContent;
                        const newDuDieuKien = doc.getElementById('stat-du-dieu-kien')?.textContent;
                        const newTamHoan = doc.getElementById('stat-tam-hoan')?.textContent;
                        const newDanQuan = doc.getElementById('stat-dan-quan')?.textContent;
                        
                        if (newTongSo) document.getElementById('stat-tong-so').textContent = newTongSo;
                        if (newDuDieuKien) document.getElementById('stat-du-dieu-kien').textContent = newDuDieuKien;
                        if (newTamHoan) document.getElementById('stat-tam-hoan').textContent = newTamHoan;
                        if (newDanQuan) document.getElementById('stat-dan-quan').textContent = newDanQuan;
                    });
            });
    }
});
</script>
@endsection
