@extends('layouts.app')

@section('title', 'Chi tiết hồ sơ lao động')
@section('page_title', 'Chi tiết hồ sơ lao động')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ho-so.index') }}" class="text-decoration-none">Hồ sơ lao động</a>
        <span class="mx-1">/</span>
        Chi tiết
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0">Hồ sơ: {{ $record->nhanKhau->ho_ten }}</h2>
        @can('manage_lao_dong')
        <a href="{{ route('ho-so.edit', $record->id) }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-pencil"></i> Chỉnh sửa hồ sơ
        </a>
        @endcan
    </div>
</div>

<div class="row g-4">
    {{-- Cột trái: Thông tin nhân khẩu & lao động --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-2">
                <i class="bi bi-person-circle text-primary fs-4"></i>
                <h5 class="fw-bold mb-0">Thông tin cá nhân</h5>
            </div>
            <div class="card-body pt-0">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-secondary py-2" style="width: 35%;">Họ và tên:</td>
                            <td class="fw-semibold py-2">{{ $record->nhanKhau->ho_ten }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Số CCCD/CMND:</td>
                            <td class="fw-semibold py-2">{{ $record->nhanKhau->cccd_cmnd ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Ngày sinh:</td>
                            <td class="py-2">{{ $record->nhanKhau->ngay_sinh ? $record->nhanKhau->ngay_sinh->format('d/m/Y') : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Giới tính:</td>
                            <td class="py-2">{{ $record->nhanKhau->gioiTinhLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Trình độ học vấn:</td>
                            <td class="py-2">{{ $record->nhanKhau->trinhDoLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Hộ khẩu thường trú:</td>
                            <td class="py-2">
                                @if($record->nhanKhau->hoKhau)
                                    <a href="{{ route('ho-khau.show', $record->nhanKhau->ho_khau_id) }}" class="text-decoration-none">
                                        Sổ HK: {{ $record->nhanKhau->hoKhau->so_so_ho_khau }}
                                    </a>
                                    <div class="small text-secondary">{{ $record->nhanKhau->hoKhau->dia_chi_thuong_tru }}</div>
                                @else
                                    <span class="text-muted">Không xác định</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-2">
                <i class="bi bi-briefcase text-success fs-4"></i>
                <h5 class="fw-bold mb-0">Thông tin lao động hiện tại</h5>
            </div>
            <div class="card-body pt-0">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-secondary py-2" style="width: 35%;">Trạng thái:</td>
                            <td class="py-2">
                                @php $trangThaiLd = $record->trang_thai_lao_dong; @endphp
                                <span class="badge bg-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }} bg-opacity-10 text-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }} fs-6">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Nghề nghiệp:</td>
                            <td class="fw-semibold py-2">{{ $record->nghe_nghiep ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Lĩnh vực ngành nghề:</td>
                            <td class="py-2">{{ $record->nganhNgheLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Loại hình công việc:</td>
                            <td class="py-2">{{ $record->loaiHinhLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Phạm vi làm việc:</td>
                            <td class="py-2">
                                @if ($record->xuat_khau_lao_dong)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-airplane-fill me-1"></i>Xuất khẩu lao động</span>
                                @elseif ($record->lam_viec_ngoai_tinh)
                                    <span class="badge bg-info text-dark"><i class="bi bi-geo-alt-fill me-1"></i>Làm việc ngoài tỉnh</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-house-door-fill me-1"></i>Tại địa phương</span>
                                @endif
                            </td>
                        </tr>
                        @if($record->lam_viec_ngoai_tinh)
                        <tr class="table-light">
                            <td class="text-secondary py-2">Tỉnh thành làm việc:</td>
                            <td class="py-2 fw-semibold">{{ $record->tinh_thanh_lam_viec }}</td>
                        </tr>
                        @endif
                        @if($record->xuat_khau_lao_dong)
                        <tr class="table-light">
                            <td class="text-secondary py-2">Quốc gia làm việc:</td>
                            <td class="py-2 fw-semibold">{{ $record->quoc_gia_lam_viec }}</td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-secondary py-2">Công ty nước ngoài:</td>
                            <td class="py-2">{{ $record->ten_cong_ty_nuoc_ngoai ?? '—' }}</td>
                        </tr>
                        <tr class="table-light">
                            <td class="text-secondary py-2">Thời hạn xuất cảnh:</td>
                            <td class="py-2">
                                Từ {{ $record->ngay_xuat_canh ? $record->ngay_xuat_canh->format('d/m/Y') : '—' }}
                                đến {{ $record->ngay_het_hop_dong_nuoc_ngoai ? $record->ngay_het_hop_dong_nuoc_ngoai->format('d/m/Y') : '—' }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-secondary py-2">Ghi chú:</td>
                            <td class="py-2 text-muted small">{{ $record->ghi_chu ?? 'Không có ghi chú.' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Cột phải: Dòng thời gian lịch sử công việc --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-info fs-4"></i>
                <h5 class="fw-bold mb-0">Lịch sử thay đổi công việc</h5>
            </div>
            <div class="card-body pt-0">
                <div class="position-relative ps-4 border-start border-2 border-light mt-3 ms-2">
                    @forelse($record->lichSuCongViec as $ls)
                    <div class="position-relative mb-4">
                        {{-- Icon mốc thời gian --}}
                        <span class="position-absolute start-0 translate-middle bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 14px; height: 14px; left: -26px !important; top: 6px;"></span>
                        
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold small">{{ $ls->ngay_thay_doi ? $ls->ngay_thay_doi->format('d/m/Y') : '—' }}</span>
                            <span class="text-muted small"><i class="bi bi-person"></i> {{ $ls->nguoiCapNhat->name ?? 'Hệ thống' }}</span>
                        </div>
                        
                        <div class="card border bg-light shadow-none mb-1">
                            <div class="card-body p-2 fs-7">
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <span class="text-secondary">Từ:</span> <strong class="text-danger">{{ $ls->ten_cong_viec_cu ?: '—' }}</strong>
                                    <i class="bi bi-arrow-right mx-1 text-secondary"></i>
                                    <span class="text-secondary">Sang:</span> <strong class="text-success">{{ $ls->ten_cong_viec_moi ?: '—' }}</strong>
                                </div>
                                @if($ls->ly_do_thay_doi)
                                <div class="text-muted small mt-1">Lý do: {{ $ls->ly_do_thay_doi }}</div>
                                @endif
                                @if($ls->ghi_chu)
                                <div class="text-muted small fs-8">Ghi chú: {{ $ls->ghi_chu }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Chưa ghi nhận lịch sử biến động công việc nào.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
