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

<div class="row g-4">
    <!-- Danh sách Nghĩa vụ quân sự gần đây -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark"><i class="bi bi-person-check text-success me-2"></i>Nghĩa vụ quân sự gần đây</span>
                <a href="{{ route('nghia-vu-quan-su.index') }}" class="btn btn-xs btn-outline-success fw-semibold btn-sm"><i class="bi bi-arrow-right-short"></i> Quản lý</a>
            </div>
            <div class="card-body p-0">
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
            </div>
        </div>
    </div>

    <!-- Danh sách Đối tượng quản lý đặc biệt gần đây -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark"><i class="bi bi-shield-exclamation text-warning me-2"></i>Đối tượng quản lý đặc biệt mới</span>
                <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-xs btn-outline-warning fw-semibold btn-sm"><i class="bi bi-arrow-right-short"></i> Quản lý</a>
            </div>
            <div class="card-body p-0">
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
            </div>
        </div>
    </div>

    <!-- Danh sách Vi phạm hành chính mới nhất -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Quyết định vi phạm hành chính mới nhất</span>
                <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-xs btn-outline-danger fw-semibold btn-sm"><i class="bi bi-arrow-right-short"></i> Quản lý</a>
            </div>
            <div class="card-body p-0">
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
                                <td style="max-width: 300px;" class="text-truncate" title="{{ $vp->noi_dung }}">
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
            </div>
        </div>
    </div>
</div>
@endsection
