@extends('layouts.app')

@section('title', 'Bộ lọc động dân cư')
@section('page_title', 'Hệ thống Tiện ích & Báo cáo')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        Bộ lọc động &amp; Tìm kiếm nâng cao
    </div>
    <h2 class="fw-bold mb-0">Cơ chế Lọc động dân cư nâng cao</h2>
    <p class="text-muted small mb-0">Cấu hình các bộ lọc phức tạp liên kết chéo các bảng (Hộ tịch, Lao động, An sinh xã hội, Nghĩa vụ quân sự, Đất đai) để trích xuất dữ liệu.</p>
</div>

{{-- Thống kê nhanh kết quả --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-primary border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people-fill stat-icon fs-3 text-primary"></i>
                <div>
                    <p class="text-muted small mb-0">Tổng kết quả</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-info border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-gender-ambiguous stat-icon fs-3 text-info"></i>
                <div>
                    <p class="text-muted small mb-0">Giới tính (Nam / Nữ)</p>
                    <h5 class="mb-0 fw-bold">{{ $stats['nam'] }} <span class="text-muted" style="font-size: 0.8rem">Nam</span> / {{ $stats['nu'] }} <span class="text-muted" style="font-size: 0.8rem">Nữ</span></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-warning border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-calendar-event stat-icon fs-3 text-warning"></i>
                <div>
                    <p class="text-muted small mb-0">Độ tuổi trung bình</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['avg_age'] }} <span class="text-muted small" style="font-size: 0.85rem">tuổi</span></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-success border-4 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-briefcase-fill stat-icon fs-3 text-success"></i>
                <div>
                    <p class="text-muted small mb-0">Việc làm (Có việc / TN / Khác)</p>
                    <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">{{ $stats['co_viec'] }} <span class="text-muted" style="font-size: 0.75rem">Có việc</span> / {{ $stats['that_nghiep'] }} <span class="text-muted" style="font-size: 0.75rem">TN</span> / {{ $stats['khac_lao_dong'] }} <span class="text-muted" style="font-size: 0.75rem">Khác</span></h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Cấu hình bộ lọc (Bên trái) --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 80px; z-index: 10;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-funnel-fill text-primary"></i> Cấu hình bộ lọc
                </h5>
                <a href="{{ route('he-thong.loc-dong') }}" class="btn btn-xs btn-outline-secondary py-1 px-2 text-decoration-none small" style="font-size: 0.75rem;">
                    Đặt lại
                </a>
            </div>
            <div class="card-body p-0">
                <form method="GET" action="{{ route('he-thong.loc-dong') }}" id="filterForm">
                    <div class="accordion accordion-flush" id="filterAccordion">
                        
                        {{-- Nhóm 1: Đặc điểm cá nhân --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPersonal">
                                <button class="accordion-button py-3 fw-semibold text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="true" aria-controls="collapsePersonal">
                                    <i class="bi bi-person me-2 text-primary"></i> 1. Đặc điểm cá nhân
                                </button>
                            </h2>
                            <div id="collapsePersonal" class="accordion-collapse collapse show" aria-labelledby="headingPersonal" data-bs-parent="#filterAccordion">
                                <div class="accordion-body row g-3">
                                    <div class="col-12">
                                        <label for="gioi_tinh" class="form-label small text-muted mb-1">Giới tính</label>
                                        <select id="gioi_tinh" name="gioi_tinh" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="nam" @selected(request('gioi_tinh') === 'nam')>Nam</option>
                                            <option value="nu" @selected(request('gioi_tinh') === 'nu')>Nữ</option>
                                            <option value="khac" @selected(request('gioi_tinh') === 'khac')>Khác</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="tuoi_tu" class="form-label small text-muted mb-1">Tuổi từ</label>
                                        <input type="number" id="tuoi_tu" name="tuoi_tu" value="{{ request('tuoi_tu') }}" min="0" max="120" class="form-control form-control-sm" placeholder="VD: 18">
                                    </div>
                                    <div class="col-6">
                                        <label for="tuoi_den" class="form-label small text-muted mb-1">Tuổi đến</label>
                                        <input type="number" id="tuoi_den" name="tuoi_den" value="{{ request('tuoi_den') }}" min="0" max="120" class="form-control form-control-sm" placeholder="VD: 25">
                                    </div>
                                    <div class="col-6">
                                        <label for="dan_toc" class="form-label small text-muted mb-1">Dân tộc</label>
                                        <input type="text" id="dan_toc" name="dan_toc" value="{{ request('dan_toc') }}" class="form-control form-control-sm" placeholder="Kinh, Tày...">
                                    </div>
                                    <div class="col-6">
                                        <label for="ton_giao" class="form-label small text-muted mb-1">Tôn giáo</label>
                                        <input type="text" id="ton_giao" name="ton_giao" value="{{ request('ton_giao') }}" class="form-control form-control-sm" placeholder="Không, Phật...">
                                    </div>
                                    <div class="col-12">
                                        <label for="trinh_do_hoc_van" class="form-label small text-muted mb-1">Trình độ học vấn</label>
                                        <select id="trinh_do_hoc_van" name="trinh_do_hoc_van" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            @foreach(\App\Models\NhanKhau::TRINH_DO_HOC_VAN as $value => $label)
                                                <option value="{{ $value }}" @selected(request('trinh_do_hoc_van') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="tinh_trang_hon_nhan" class="form-label small text-muted mb-1">Tình trạng hôn nhân</label>
                                        <select id="tinh_trang_hon_nhan" name="tinh_trang_hon_nhan" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            @foreach(\App\Models\NhanKhau::TINH_TRANG_HON_NHAN as $value => $label)
                                                <option value="{{ $value }}" @selected(request('tinh_trang_hon_nhan') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="trang_thai" class="form-label small text-muted mb-1">Trạng thái cư trú</label>
                                        <select id="trang_thai" name="trang_thai" class="form-select form-select-sm">
                                            <option value="">Tất cả đang cư trú</option>
                                            @foreach(\App\Models\NhanKhau::TRANG_THAI as $value => $label)
                                                <option value="{{ $value }}" @selected(request('trang_thai') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="co_tien_an" class="form-label small text-muted mb-1">Tiền án tiền sự</label>
                                        <select id="co_tien_an" name="co_tien_an" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="1" @selected(request('co_tien_an') === '1')>Có tiền án/tiền sự</option>
                                            <option value="0" @selected(request('co_tien_an') === '0')>Không có</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nhóm 2: Hộ khẩu & Địa bàn --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAddress">
                                <button class="accordion-button collapsed py-3 fw-semibold text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddress" aria-expanded="false" aria-controls="collapseAddress">
                                    <i class="bi bi-geo-alt me-2 text-primary"></i> 2. Hộ khẩu &amp; Địa bàn
                                </button>
                            </h2>
                            <div id="collapseAddress" class="accordion-collapse collapse" aria-labelledby="headingAddress" data-bs-parent="#filterAccordion">
                                <div class="accordion-body row g-3">
                                    <div class="col-12">
                                        <label for="thon_xom" class="form-label small text-muted mb-1">Thôn/Xóm</label>
                                        <select id="thon_xom" name="thon_xom" class="form-select form-select-sm">
                                            <option value="">Tất cả thôn xóm</option>
                                            @foreach($thonXomList as $tx)
                                                <option value="{{ $tx }}" @selected(request('thon_xom') === $tx)>{{ $tx }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="ho_ngheo" name="ho_ngheo" value="1" @checked(request('ho_ngheo') === '1')>
                                            <label class="form-check-label small" for="ho_ngheo">Thuộc hộ nghèo</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="ho_can_ngheo" name="ho_can_ngheo" value="1" @checked(request('ho_can_ngheo') === '1')>
                                            <label class="form-check-label small" for="ho_can_ngheo">Thuộc hộ cận nghèo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nhóm 3: Lao động & Việc làm --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingLabor">
                                <button class="accordion-button collapsed py-3 fw-semibold text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLabor" aria-expanded="false" aria-controls="collapseLabor">
                                    <i class="bi bi-briefcase me-2 text-primary"></i> 3. Lao động &amp; Việc làm
                                </button>
                            </h2>
                            <div id="collapseLabor" class="accordion-collapse collapse" aria-labelledby="headingLabor" data-bs-parent="#filterAccordion">
                                <div class="accordion-body row g-3">
                                    <div class="col-12">
                                        <label for="trang_thai_lao_dong" class="form-label small text-muted mb-1">Trạng thái lao động</label>
                                        <select id="trang_thai_lao_dong" name="trang_thai_lao_dong" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="co_viec_lam" @selected(request('trang_thai_lao_dong') === 'co_viec_lam')>Có việc làm</option>
                                            <option value="that_nghiep" @selected(request('trang_thai_lao_dong') === 'that_nghiep')>Thất nghiệp</option>
                                            <option value="khac" @selected(request('trang_thai_lao_dong') === 'khac')>Khác</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="loai_hinh_cong_viec" class="form-label small text-muted mb-1">Loại hình công việc</label>
                                        <select id="loai_hinh_cong_viec" name="loai_hinh_cong_viec" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            @foreach(\App\Models\LaoDong::LOAI_HINH_CONG_VIEC as $value => $label)
                                                <option value="{{ $value }}" @selected(request('loai_hinh_cong_viec') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="nganh_nghe" class="form-label small text-muted mb-1">Ngành nghề</label>
                                        <select id="nganh_nghe" name="nganh_nghe" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            @foreach(\App\Models\LaoDong::NGANH_NGHE as $value => $label)
                                                <option value="{{ $value }}" @selected(request('nganh_nghe') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="lam_viec_ngoai_tinh" class="form-label small text-muted mb-1">Làm việc ngoài tỉnh</label>
                                        <select id="lam_viec_ngoai_tinh" name="lam_viec_ngoai_tinh" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="1" @selected(request('lam_viec_ngoai_tinh') === '1')>Làm việc ngoài tỉnh (Làm xa)</option>
                                            <option value="0" @selected(request('lam_viec_ngoai_tinh') === '0')>Làm việc tại địa phương</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="xuat_khau_lao_dong" class="form-label small text-muted mb-1">Xuất khẩu lao động</label>
                                        <select id="xuat_khau_lao_dong" name="xuat_khau_lao_dong" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="1" @selected(request('xuat_khau_lao_dong') === '1')>Đang làm việc tại nước ngoài</option>
                                            <option value="0" @selected(request('xuat_khau_lao_dong') === '0')>Không thuộc diện XKLD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nhóm 4: Chính sách & Nghĩa vụ --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPolicy">
                                <button class="accordion-button collapsed py-3 fw-semibold text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePolicy" aria-expanded="false" aria-controls="collapsePolicy">
                                    <i class="bi bi-shield-check me-2 text-primary"></i> 4. An sinh &amp; Nghĩa vụ
                                </button>
                            </h2>
                            <div id="collapsePolicy" class="accordion-collapse collapse" aria-labelledby="headingPolicy" data-bs-parent="#filterAccordion">
                                <div class="accordion-body row g-3">
                                    <div class="col-12">
                                        <label for="co_dien_chinh_sach" class="form-label small text-muted mb-1">Diện chính sách (Có công)</label>
                                        <select id="co_dien_chinh_sach" name="co_dien_chinh_sach" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="1" @selected(request('co_dien_chinh_sach') === '1')>Có hưởng chính sách</option>
                                            <option value="0" @selected(request('co_dien_chinh_sach') === '0')>Không</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="loai_chinh_sach" class="form-label small text-muted mb-1">Loại chính sách cụ thể</label>
                                        <select id="loai_chinh_sach" name="loai_chinh_sach" class="form-select form-select-sm">
                                            <option value="">Tất cả diện</option>
                                            @foreach(\App\Models\DoiTuongChinhSach::LOAI_CHINH_SACH as $value => $label)
                                                <option value="{{ $value }}" @selected(request('loai_chinh_sach') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="co_bao_tro_xa_hoi" class="form-label small text-muted mb-1">Đối tượng bảo trợ xã hội</label>
                                        <select id="co_bao_tro_xa_hoi" name="co_bao_tro_xa_hoi" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="1" @selected(request('co_bao_tro_xa_hoi') === '1')>Có nhận bảo trợ cá nhân</option>
                                            <option value="0" @selected(request('co_bao_tro_xa_hoi') === '0')>Không</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="loai_bao_tro" class="form-label small text-muted mb-1">Loại bảo trợ cụ thể</label>
                                        <select id="loai_bao_tro" name="loai_bao_tro" class="form-select form-select-sm">
                                            <option value="">Tất cả diện</option>
                                            @foreach(\App\Models\BaoTroXaHoi::LOAI_BAO_TRO as $value => $label)
                                                @if(!in_array($value, \App\Models\BaoTroXaHoi::LOAI_THEO_HO))
                                                    <option value="{{ $value }}" @selected(request('loai_bao_tro') === $value)>{{ $label }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="trong_do_tuoi_nvqs" name="trong_do_tuoi_nvqs" value="1" @checked(request('trong_do_tuoi_nvqs') === '1')>
                                            <label class="form-check-label small fw-semibold text-danger" for="trong_do_tuoi_nvqs">Nam đủ tuổi NVQS (18 - 27)</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="trang_thai_nvqs" class="form-label small text-muted mb-1">Trạng thái NVQS</label>
                                        <select id="trang_thai_nvqs" name="trang_thai_nvqs" class="form-select form-select-sm">
                                            <option value="">Tất cả trạng thái</option>
                                            <option value="du_dieu_kien" @selected(request('trang_thai_nvqs') === 'du_dieu_kien')>Đủ điều kiện</option>
                                            <option value="tam_hoan" @selected(request('trang_thai_nvqs') === 'tam_hoan')>Tạm hoãn</option>
                                            <option value="trung_tuyen" @selected(request('trang_thai_nvqs') === 'trung_tuyen')>Trúng tuyển</option>
                                            <option value="da_nhap_ngu" @selected(request('trang_thai_nvqs') === 'da_nhap_ngu')>Đã nhập ngũ</option>
                                            <option value="xuat_ngu" @selected(request('trang_thai_nvqs') === 'xuat_ngu')>Xuất ngũ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nhóm 5: Đất đai --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingLand">
                                <button class="accordion-button collapsed py-3 fw-semibold text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLand" aria-expanded="false" aria-controls="collapseLand">
                                    <i class="bi bi-geo me-2 text-primary"></i> 5. Đất đai &amp; Tài sản
                                </button>
                            </h2>
                            <div id="collapseLand" class="accordion-collapse collapse" aria-labelledby="headingLand" data-bs-parent="#filterAccordion">
                                <div class="accordion-body row g-3">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="co_dat_tho_cu" name="co_dat_tho_cu" value="1" @checked(request('co_dat_tho_cu') === '1')>
                                            <label class="form-check-label small" for="co_dat_tho_cu">Hộ có sở hữu đất thổ cư</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="co_dat_nong_nghiep" name="co_dat_nong_nghiep" value="1" @checked(request('co_dat_nong_nghiep') === '1')>
                                            <label class="form-check-label small" for="co_dat_nong_nghiep">Hộ có đất nông nghiệp</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="dien_tich_dat_tu" class="form-label small text-muted mb-1">Diện tích đất tối thiểu (m²)</label>
                                        <input type="number" id="dien_tich_dat_tu" name="dien_tich_dat_tu" value="{{ request('dien_tich_dat_tu') }}" min="0" class="form-control form-control-sm" placeholder="VD: 500">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="p-3 bg-light border-top">
                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-search"></i> Thực hiện lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Kết quả và bảng dữ liệu (Bên phải) --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-list-stars text-success"></i> Danh sách nhân khẩu trùng khớp
                </h5>
                <span class="badge bg-secondary rounded-pill px-3 py-1 text-white">
                    Đang hiển thị {{ $results->count() }}/{{ $results->total() }} dòng
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thông tin cá nhân</th>
                                <th>CCCD / CMND</th>
                                <th>Quan hệ / Hộ khẩu</th>
                                <th>Địa bàn</th>
                                <th>Trạng thái &amp; Nhãn đặc biệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $nk)
                            <tr>
                                <td>
                                    <div class="fw-bold">
                                        <a href="{{ route('nhan-khau.show', $nk->id) }}" class="text-decoration-none text-dark hover-primary">
                                            {{ $nk->ho_ten }}
                                        </a>
                                    </div>
                                    <div class="text-muted small">
                                        Giới tính: <span class="fw-semibold">{{ $nk->gioiTinhLabel() }}</span> | 
                                        Tuổi: <span class="fw-semibold">{{ $nk->ngay_sinh ? $nk->ngay_sinh->age : '—' }}</span>
                                        ({{ $nk->ngay_sinh ? $nk->ngay_sinh->format('d/m/Y') : 'Chưa nhập' }})
                                    </div>
                                </td>
                                <td>
                                    <code class="text-secondary" style="font-size: 0.9rem">{{ $nk->cccd_cmnd ?? 'Chưa cấp' }}</code>
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $nk->quan_he_chu_ho }}</div>
                                    @if($nk->hoKhau)
                                        <div class="text-muted small">
                                            Số sổ: <a href="{{ route('ho-khau.show', $nk->hoKhau->id) }}" class="text-decoration-none">{{ $nk->hoKhau->so_so_ho_khau }}</a>
                                        </div>
                                    @else
                                        <span class="text-danger small">Không có hộ khẩu</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small fw-semibold">{{ $nk->hoKhau->thon_xom ?? '—' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        {{-- Trạng thái cư trú --}}
                                        <span class="badge bg-{{ $nk->trang_thai === 'hoat_dong' ? 'success' : ($nk->trang_thai === 'tam_tru' ? 'info' : 'warning') }} bg-opacity-10 text-{{ $nk->trang_thai === 'hoat_dong' ? 'success' : ($nk->trang_thai === 'tam_tru' ? 'info' : 'warning') }}" style="font-size: 0.75rem;">
                                            {{ $nk->trangThaiLabel() }}
                                        </span>

                                        {{-- Hộ nghèo/cận nghèo --}}
                                        @if($nk->hoKhau)
                                            @php 
                                                $povertyType = $nk->hoKhau->baoTroXaHoi()
                                                    ->whereIn('loai_bao_tro', ['ho_ngheo', 'ho_can_ngheo'])
                                                    ->where('trang_thai', 'dang_huong')
                                                    ->value('loai_bao_tro');
                                            @endphp
                                            @if($povertyType === 'ho_ngheo')
                                                <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 0.75rem;"><i class="bi bi-house-door me-1"></i>Hộ nghèo</span>
                                            @elseif($povertyType === 'ho_can_ngheo')
                                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis" style="font-size: 0.75rem;"><i class="bi bi-house-door me-1"></i>Hộ cận nghèo</span>
                                            @endif
                                        @endif

                                        {{-- Lao động --}}
                                         @if($nk->laoDong && $nk->laoDong->trang_thai_lao_dong)
                                             @php
                                                 $trangThaiLd = $nk->laoDong->trang_thai_lao_dong;
                                                 $labelLd = \App\Models\LaoDong::TRANG_THAI_LAO_DONG[$trangThaiLd] ?? $trangThaiLd;
                                                 $badgeClass = 'bg-secondary text-secondary';
                                                 if ($trangThaiLd === 'co_viec_lam') {
                                                     $badgeClass = 'bg-success text-success';
                                                 } elseif ($trangThaiLd === 'that_nghiep') {
                                                     $badgeClass = 'bg-danger text-danger';
                                                 } elseif ($trangThaiLd === 'hoc_sinh_sinh_vien') {
                                                     $badgeClass = 'bg-info text-info';
                                                 }
                                             @endphp
                                             <span class="badge {{ $badgeClass }} bg-opacity-10" style="font-size: 0.75rem;">{{ $labelLd }}</span>
                                         @endif

                                         @if($nk->laoDong && $nk->laoDong->xuat_khau_lao_dong)
                                             <span class="badge bg-primary" style="font-size: 0.75rem;"><i class="bi bi-airplane me-1"></i>XKLD</span>
                                         @endif

                                        {{-- Chính sách --}}
                                        @if($nk->doiTuongChinhSach)
                                            <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.75rem;"><i class="bi bi-award me-1"></i>Diện chính sách</span>
                                        @endif

                                        {{-- Bảo trợ xã hội cá nhân --}}
                                        @if($nk->baoTroXaHoi && !in_array($nk->baoTroXaHoi->loai_bao_tro, ['ho_ngheo', 'ho_can_ngheo']))
                                            <span class="badge bg-dark bg-opacity-10 text-dark" style="font-size: 0.75rem;"><i class="bi bi-heart me-1"></i>Bảo trợ cá nhân</span>
                                        @endif

                                        {{-- Tiền án --}}
                                        @if($nk->co_tien_an)
                                            <span class="badge bg-danger text-white" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle me-1"></i>Tiền án</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-funnel fs-2 d-block mb-3 text-secondary opacity-50"></i>
                                    <span class="fw-semibold">Không tìm thấy công dân trùng khớp.</span><br>
                                    <small>Hãy thử điều chỉnh hoặc thu hẹp cấu hình bộ lọc ở cột bên trái.</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                @if($results->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    {{ $results->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<style>
    .hover-primary:hover {
        color: var(--admin-green) !important;
        text-decoration: underline !important;
    }
    .accordion-button:not(.collapsed) {
        background-color: var(--admin-green-soft);
        color: var(--admin-green) !important;
    }
    .accordion-button:focus {
        border-color: var(--admin-green);
        box-shadow: 0 0 0 0.25rem rgba(15, 81, 50, 0.25);
    }
</style>
@endsection
