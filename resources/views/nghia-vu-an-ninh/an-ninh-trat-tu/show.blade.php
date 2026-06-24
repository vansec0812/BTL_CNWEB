@extends('layouts.app')

@section('title', 'Chi tiết hồ sơ An ninh trật tự')
@section('page_title', 'Chi tiết hồ sơ An ninh trật tự')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('an-ninh-trat-tu.index') }}" class="text-decoration-none text-muted">An ninh trật tự</a>
        <span class="mx-1">/</span>
        <span class="text-dark fw-semibold">Chi tiết hồ sơ</span>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-1">Chi tiết hồ sơ An ninh trật tự</h2>
        @can('manage_nghia_vu')
            <a href="{{ route('an-ninh-trat-tu.edit', $record) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa hồ sơ
            </a>
        @endcan
    </div>
</div>

<div class="row g-4">
    <!-- Cột trái: Thông tin vụ việc/hồ sơ -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 fw-bold text-success">
                    <i class="bi bi-file-earmark-text me-1"></i>Thông tin quyết định / Giám sát
                </h5>
            </div>
            <div class="card-body p-4 pt-2">
                <table class="table table-borderless">
                    <tbody>
                        <tr class="border-bottom">
                            <td width="160" class="text-secondary fw-semibold py-3">Nhóm đối tượng</td>
                            <td class="py-3">
                                <span class="badge bg-light text-dark border px-2 py-1 small fw-semibold">{{ $record->nhomLabel() }}</span>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Họ tên đối tượng</td>
                            <td class="py-3 fw-bold">{{ $record->ho_ten }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Số CCCD/CMND</td>
                            <td class="py-3">{{ $record->cccd ?? '—' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Địa chỉ cư trú</td>
                            <td class="py-3">{{ $record->dia_chi ?? '—' }}</td>
                        </tr>

                        <tr class="border-bottom">
                            <td width="160" class="text-secondary fw-semibold py-3">Phân loại</td>
                            <td class="py-3">
                                @php
                                    $typeColor = 'secondary';
                                    if ($record->loai_doi_tuong === 'tien_an_tien_su') $typeColor = 'dark';
                                    elseif ($record->loai_doi_tuong === 'nguoi_nghien_ma_tuy') $typeColor = 'danger';
                                    elseif ($record->loai_doi_tuong === 'theo_doi_an_ninh') $typeColor = 'warning';
                                    elseif ($record->loai_doi_tuong === 'bao_luc_gia_dinh') $typeColor = 'info';
                                    elseif ($record->loai_doi_tuong === 'vi_pham_hanh_chinh') $typeColor = 'primary';
                                @endphp
                                <span class="badge bg-{{ $typeColor }} bg-opacity-10 text-{{ $typeColor }} border border-{{ $typeColor }} px-2 py-1">
                                    {{ $record->loaiLabel() }}
                                </span>
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Cơ quan giải quyết</td>
                            <td class="py-3">{{ $record->co_quan_giai_quyet }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Ngày ghi nhận</td>
                            <td class="py-3">{{ $record->ngay_ghi_nhan ? $record->ngay_ghi_nhan->format('d/m/Y') : '—' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Biện pháp / Hình thức</td>
                            <td class="py-3">{{ $record->hinh_thuc_xu_ly ?? '—' }}</td>
                        </tr>
                        @if($record->so_tien_phat)
                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Số tiền phạt</td>
                            <td class="py-3 text-primary fw-bold fs-5">{{ $record->soTienPhatFormatted() }}</td>
                        </tr>
                        @endif
                        <tr class="border-bottom">
                            <td class="text-secondary fw-semibold py-3">Trạng thái</td>
                            <td class="py-3">
                                @php
                                    $statusColor = 'secondary';
                                    if ($record->trang_thai === 'dang_quan_ly') $statusColor = 'warning';
                                    elseif ($record->trang_thai === 'chua_chap_hanh') $statusColor = 'danger';
                                    elseif ($record->trang_thai === 'da_chap_hanh') $statusColor = 'success';
                                @endphp
                                <span class="badge bg-{{ $statusColor }} px-3 py-2 fs-7">{{ $record->trangThaiLabel() }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cột phải: Đối tượng liên kết -->
    <div class="col-lg-6">
        @if($record->nhanKhau)
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="bi bi-person-bounding-box me-1"></i>Thông tin nhân khẩu địa phương
                    </h5>
                    <a href="{{ route('nhan-khau.show', $record->nhanKhau) }}" class="btn btn-sm btn-outline-success">
                        Xem hồ sơ nhân khẩu
                    </a>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                            <i class="bi bi-person-fill fs-2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-1">{{ $record->nhanKhau->ho_ten }}</h4>
                            <p class="text-secondary mb-0">CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td width="150" class="text-secondary fw-semibold py-2">Ngày sinh</td>
                                <td class="py-2">{{ $record->nhanKhau->ngay_sinh ? $record->nhanKhau->ngay_sinh->format('d/m/Y') : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-2">Giới tính</td>
                                <td class="py-2">{{ $record->nhanKhau->gioiTinhLabel() }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-2">Mối quan hệ chủ hộ</td>
                                <td class="py-2">{{ $record->nhanKhau->quan_he_chu_ho }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-2">Thôn / Xóm</td>
                                <td class="py-2">{{ $record->nhanKhau->hoKhau->thon_xom ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-2">Mã hộ</td>
                                <td class="py-2">{{ $record->nhanKhau->hoKhau->ma_ho ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-secondary">
                        <i class="bi bi-person-x-fill me-1"></i>Thông tin đối tượng vãng lai
                    </h5>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="badge bg-secondary bg-opacity-10 text-secondary p-3 rounded-circle me-3">
                            <i class="bi bi-person-x fs-2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-secondary mb-1">{{ $record->ho_ten }}</h4>
                            <p class="text-danger mb-0 italic">Người vãng lai (không thuộc dân cư địa phương)</p>
                        </div>
                    </div>
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td width="150" class="text-secondary fw-semibold py-2">Số CCCD/CMND</td>
                                <td class="py-2">{{ $record->cccd ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary fw-semibold py-2">Địa chỉ cư trú</td>
                                <td class="py-2">{{ $record->dia_chi ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Cột dưới: Nội dung chi tiết lý do và hành vi -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold text-success mb-3">
                    <i class="bi bi-chat-left-text me-1"></i>Chi tiết hành vi vi phạm / Lý do đưa vào diện quản lý đặc biệt
                </h5>
                <div class="p-3 bg-light rounded border mb-4" style="white-space: pre-line; font-size: 1.05rem;">
                    {{ $record->noi_dung }}
                </div>


            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>
@endsection
