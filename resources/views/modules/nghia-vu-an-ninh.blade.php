@extends('layouts.app')

@section('title', 'Nghĩa vụ & An ninh quốc phòng')
@section('page_title', 'Nghĩa vụ & An ninh quốc phòng')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-success border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle"><i class="bi bi-shield-check fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nghĩa vụ quân sự</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['nghia_vu_quan_su'] ?? 0 }}</h4>
                    <small class="text-success small"><i class="bi bi-check-circle"></i> Đang quản lý</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-primary border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-primary bg-opacity-10 text-primary p-3 rounded-circle"><i class="bi bi-people-fill fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Dân quân tự vệ</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['dan_quan_tu_ve'] ?? 0 }}</h4>
                    <small class="text-primary small"><i class="bi bi-shield"></i> Lực lượng nòng cốt</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-warning border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-warning bg-opacity-10 text-warning p-3 rounded-circle"><i class="bi bi-shield-exclamation fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Quản lý đặc biệt</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['doi_tuong_quan_ly'] ?? 0 }}</h4>
                    <small class="text-warning small"><i class="bi bi-eye"></i> Diện giám sát</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-start border-danger border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-danger bg-opacity-10 text-danger p-3 rounded-circle"><i class="bi bi-exclamation-triangle fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Vi phạm hành chính</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['vi_pham_hanh_chinh'] ?? 0 }}</h4>
                    <small class="text-danger small"><i class="bi bi-file-earmark-text"></i> Quyết định xử phạt</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #nghiaVuAnNinhTabs .nav-link {
        border: none;
        color: #6c757d;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }
    #nghiaVuAnNinhTabs .nav-link:hover {
        border-color: transparent;
        color: #198754;
    }
    #nghiaVuAnNinhTabs .nav-link.active {
        background-color: transparent;
        color: #198754;
        border-bottom: 2px solid #198754;
    }
</style>

<div class="row g-3">
    <!-- Cột bên trái: Danh mục & Thao tác nhanh -->
    <div class="col-lg-4">
        <!-- Danh mục chức năng -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3"><i class="bi bi-grid-fill me-2 text-success"></i>Danh mục Quản lý</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('nghia-vu-quan-su.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-2"><i class="bi bi-person-check"></i></span>
                            <span>Nghĩa vụ quân sự</span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('dan-quan-tu-ve.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle p-2"><i class="bi bi-people"></i></span>
                            <span>Dân quân tự vệ</span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('dan-quan-hoat-dong.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info bg-opacity-10 text-info rounded-circle p-2"><i class="bi bi-activity"></i></span>
                            <span>Hoạt động dân quân</span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('an-ninh-trat-tu.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-2"><i class="bi bi-shield-exclamation"></i></span>
                            <span>An ninh trật tự</span>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>

        @can('manage_nghia_vu')
        <!-- Thao tác nhanh -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3"><i class="bi bi-plus-circle-fill me-2 text-success"></i>Thao tác nhanh</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('nghia-vu-quan-su.create') }}" class="btn btn-outline-success text-start d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Thêm hồ sơ nghĩa vụ quân sự
                </a>
                <a href="{{ route('dan-quan-tu-ve.create') }}" class="btn btn-outline-primary text-start d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill"></i> Thêm thành viên dân quân
                </a>
                <a href="{{ route('dan-quan-hoat-dong.create') }}" class="btn btn-outline-info text-start d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-plus"></i> Đăng ký hoạt động dân quân
                </a>
                <a href="{{ route('an-ninh-trat-tu.create') }}" class="btn btn-outline-warning text-start d-flex align-items-center gap-2">
                    <i class="bi bi-shield-plus"></i> Thêm hồ sơ an ninh trật tự
                </a>
            </div>
        </div>
        @endcan
    </div>

    <!-- Cột bên phải: Tabs bảng dữ liệu gần đây -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-success"></i>Dữ liệu cập nhật gần đây</span>
                </div>
                <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="nghiaVuAnNinhTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" id="nvqs-tab" data-bs-toggle="tab" data-bs-target="#nvqs" type="button" role="tab" aria-controls="nvqs" aria-selected="true">
                            <i class="bi bi-person-check me-1"></i>Nghĩa vụ quân sự
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="dtql-tab" data-bs-toggle="tab" data-bs-target="#dtql" type="button" role="tab" aria-controls="dtql" aria-selected="false">
                            <i class="bi bi-shield-exclamation me-1"></i>Đối tượng đặc biệt
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="vipham-tab" data-bs-toggle="tab" data-bs-target="#vipham" type="button" role="tab" aria-controls="vipham" aria-selected="false">
                            <i class="bi bi-exclamation-triangle me-1"></i>Vi phạm hành chính
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0 tab-content" id="nghiaVuAnNinhTabsContent">
                <!-- Tab Nghĩa vụ quân sự -->
                <div class="tab-pane fade show active" id="nvqs" role="tabpanel" aria-labelledby="nvqs-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Năm sinh</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dsNVQS ?? [] as $nvqs)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $nvqs->nhanKhau->ho_ten ?? '—' }}</td>
                                    <td>{{ $nvqs->nhanKhau->ngay_sinh ? $nvqs->nhanKhau->ngay_sinh->format('Y') : '—' }}</td>
                                    <td>
                                        @php
                                            $color = 'secondary';
                                            $label = 'Chưa rõ';
                                            if ($nvqs->trang_thai_nvqs === 'du_dieu_kien') { $color = 'success'; $label = 'Đủ điều kiện'; }
                                            elseif ($nvqs->trang_thai_nvqs === 'tam_hoan') { $color = 'warning'; $label = 'Tạm hoãn'; }
                                            elseif ($nvqs->trang_thai_nvqs === 'trung_tuyen') { $color = 'info'; $label = 'Trúng tuyển'; }
                                            elseif ($nvqs->trang_thai_nvqs === 'da_nhap_ngu') { $color = 'primary'; $label = 'Đã nhập ngũ'; }
                                            elseif ($nvqs->trang_thai_nvqs === 'xuat_ngu') { $color = 'dark'; $label = 'Xuất ngũ'; }
                                            elseif ($nvqs->trang_thai_nvqs === 'mien_goi') { $color = 'secondary'; $label = 'Miễn gọi'; }
                                            elseif ($nvqs->trang_thai_nvqs === 'da_qua_tuoi') { $color = 'secondary'; $label = 'Đã quá tuổi'; }
                                        @endphp
                                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} px-2 py-1" style="font-size: 0.75rem;">{{ $label }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('nghia-vu-quan-su.show', $nvqs) }}" class="btn btn-sm btn-link text-success p-0" title="Xem chi tiết"><i class="bi bi-eye"></i> Chi tiết</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu nghĩa vụ quân sự.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-end">
                        <a href="{{ route('nghia-vu-quan-su.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-success fw-semibold">Xem tất cả</a>
                    </div>
                </div>

                <!-- Tab Đối tượng đặc biệt -->
                <div class="tab-pane fade" id="dtql" role="tabpanel" aria-labelledby="dtql-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Phân loại</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dsDoiTuongQuanLy ?? [] as $dtql)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $dtql->nhanKhau->ho_ten ?? '—' }}</td>
                                    <td>
                                        <small class="d-block fw-semibold text-secondary">
                                            {{ \App\Models\AnNinhTratTu::LOAI_DOI_TUONG[$dtql->loai_doi_tuong] ?? 'Khác' }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $color = 'secondary';
                                            if ($dtql->trang_thai === 'dang_quan_ly') $color = 'warning';
                                            elseif ($dtql->trang_thai === 'da_xoa_quan_ly') $color = 'secondary';
                                            elseif ($dtql->trang_thai === 'da_chap_hanh') $color = 'success';
                                        @endphp
                                        <span class="badge bg-{{ $color }} px-2 py-1 text-white" style="font-size: 0.75rem;">
                                            {{ $dtql->trangThaiLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('an-ninh-trat-tu.show', $dtql) }}" class="btn btn-sm btn-link text-warning p-0" title="Xem chi tiết"><i class="bi bi-eye"></i> Chi tiết</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Chưa có đối tượng quản lý đặc biệt nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-end">
                        <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-warning fw-semibold">Xem tất cả</a>
                    </div>
                </div>

                <!-- Tab Vi phạm hành chính -->
                <div class="tab-pane fade" id="vipham" role="tabpanel" aria-labelledby="vipham-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>Số quyết định</th>
                                    <th>Người vi phạm</th>
                                    <th>Hành vi vi phạm</th>
                                    <th>Tiền phạt</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dsViPham ?? [] as $vp)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $vp->so_quyet_dinh }}</td>
                                    <td>
                                        @if($vp->nhanKhau)
                                            <span class="fw-semibold text-dark">{{ $vp->nhanKhau->ho_ten }}</span>
                                            <small class="d-block text-muted">CCCD: {{ $vp->nhanKhau->cccd_cmnd ?? 'Chưa có' }}</small>
                                        @else
                                            <span class="fw-bold text-secondary">{{ $vp->ho_ten_vang_lai }}</span>
                                            <small class="d-block text-muted italic">Đối tượng vãng lai</small>
                                        @endif
                                    </td>
                                    <td style="max-width: 250px;" class="text-truncate" title="{{ $vp->noi_dung }}">
                                        {{ $vp->noi_dung }}
                                    </td>
                                    <td class="fw-semibold text-primary">
                                        {{ $vp->soTienPhatFormatted() }}
                                    </td>
                                    <td>
                                        @php
                                            $color = 'warning';
                                            if ($vp->trang_thai === 'da_chap_hanh') $color = 'success';
                                            elseif ($vp->trang_thai === 'chua_chap_hanh') $color = 'danger';
                                        @endphp
                                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $vp->trangThaiLabel() }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('an-ninh-trat-tu.show', $vp) }}" class="btn btn-sm btn-link text-danger p-0" title="Xem chi tiết"><i class="bi bi-eye"></i> Chi tiết</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Chưa có vi phạm hành chính nào được ghi nhận.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-end">
                        <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-danger fw-semibold">Xem tất cả</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
