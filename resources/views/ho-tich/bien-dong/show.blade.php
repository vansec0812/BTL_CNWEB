@extends('layouts.app')

@section('title', 'Chi tiết biến động hộ khẩu')
@section('page_title', 'Chi tiết biến động')

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
        Chi tiết
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('bien-dong.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Thông tin chi tiết biến động</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-info-circle me-1"></i>Chi tiết nghiệp vụ
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-secondary small d-block">Loại biến động</label>
                        <span class="fs-5 fw-bold text-success">{{ $record->loaiLabel() }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small d-block">Ngày thực hiện</label>
                        <span class="fs-6 fw-semibold">{{ $record->ngay_bien_dong?->format('d/m/Y') }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small d-block">Số quyết định / Văn bản phê duyệt</label>
                        <span class="fs-6 fw-semibold text-danger">{{ $record->so_quyet_dinh ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small d-block">Cán bộ xử lý</label>
                        <span class="fs-6 fw-semibold">{{ $record->nguoiThucHien?->name ?? 'Hệ thống' }}</span>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary small d-block">Lý do biến động</label>
                        <p class="mb-0 bg-light p-3 rounded text-dark border-start border-success border-3">{{ $record->ly_do ?? 'Không có lý do chi tiết' }}</p>
                    </div>
                    @if($record->ghi_chu)
                    <div class="col-12">
                        <label class="text-secondary small d-block">Ghi chú</label>
                        <p class="mb-0 text-muted">{{ $record->ghi_chu }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-people me-1"></i>Nhân khẩu & Hộ khẩu liên quan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <tbody>
                            <tr>
                                <th style="width: 30%;" class="bg-light ps-3">Nhân khẩu bị biến động</th>
                                <td class="ps-3">
                                    @if($record->nhanKhau)
                                        <a href="{{ route('nhan-khau.show', $record->nhanKhau) }}" class="fw-semibold text-success text-decoration-none">
                                            {{ $record->nhanKhau->ho_ten }}
                                        </a>
                                        <div class="small text-secondary">CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật' }}</div>
                                    @else
                                        <span class="text-muted">Nhân khẩu không tồn tại hoặc đã bị xoá.</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light ps-3">Sổ hộ khẩu gốc (nguồn)</th>
                                <td class="ps-3">
                                    @if($record->hoKhauNguon)
                                        <a href="{{ route('ho-khau.show', $record->hoKhauNguon) }}" class="fw-semibold text-dark text-decoration-none">
                                            Hộ {{ $record->hoKhauNguon->ma_ho }} (Số sổ: {{ $record->hoKhauNguon->so_so_ho_khau }})
                                        </a>
                                        <div class="small text-secondary">Địa chỉ: {{ $record->hoKhauNguon->dia_chi_thuong_tru }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @if($record->hoKhauDich)
                            <tr>
                                <th class="bg-light ps-3">Sổ hộ khẩu mới (đích)</th>
                                <td class="ps-3">
                                    <a href="{{ route('ho-khau.show', $record->hoKhauDich) }}" class="fw-semibold text-success text-decoration-none">
                                        Hộ {{ $record->hoKhauDich->ma_ho }} (Số sổ: {{ $record->hoKhauDich->so_so_ho_khau }})
                                    </a>
                                    <div class="small text-secondary">Địa chỉ mới: {{ $record->hoKhauDich->dia_chi_thuong_tru }}</div>
                                </td>
                            </tr>
                            @endif
                            @if($record->dia_chi_chuyen_den)
                            <tr>
                                <th class="bg-light ps-3">Địa chỉ chuyển đến (ngoài xã)</th>
                                <td class="ps-3 fw-semibold text-danger">{{ $record->dia_chi_chuyen_den }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-success bg-opacity-10 text-success h-100">
            <div class="card-body d-flex flex-column justify-content-between p-4">
                <div>
                    <i class="bi bi-shield-check fs-1 d-block mb-3"></i>
                    <h4 class="fw-bold">Giao dịch Hợp lệ</h4>
                    <p class="small mb-0">Biến động này đã được ghi nhận vào cơ sở dữ liệu quốc gia về cư dân của xã Quốc Oai và đã đồng bộ trạng thái nhân khẩu/hộ khẩu tương ứng.</p>
                </div>
                <div class="mt-4 pt-3 border-top border-success border-opacity-25">
                    <a href="{{ route('bien-dong.index') }}" class="btn btn-success w-100">Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
