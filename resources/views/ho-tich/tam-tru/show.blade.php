@extends('layouts.app')

@section('title', 'Chi tiết Tạm trú / Tạm vắng')
@section('page_title', 'Chi tiết hồ sơ')

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
        <a href="{{ route('tam-tru.index') }}" class="text-decoration-none">Tạm trú & Tạm vắng</a>
        <span class="mx-1">/</span>
        Chi tiết
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tam-tru.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Hồ sơ Tạm trú / Tạm vắng chi tiết</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-card-checklist me-1"></i>Chi tiết thông tin đăng ký
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;" class="bg-light ps-3">Họ và tên nhân khẩu</th>
                                <td class="ps-3 fw-bold text-success">
                                    @if($record->nhanKhau)
                                        <a href="{{ route('nhan-khau.show', $record->nhanKhau) }}" class="text-success text-decoration-none">
                                            {{ $record->nhanKhau->ho_ten }}
                                        </a>
                                    @else
                                        <span class="text-muted">Không xác định</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light ps-3">Số CCCD / CMND</th>
                                <td class="ps-3 fw-semibold">{{ $record->nhanKhau?->cccd_cmnd ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light ps-3">Loại khai báo</th>
                                <td class="ps-3">
                                    <span class="badge bg-{{ $record->loai === 'tam_tru' ? 'success' : 'info' }} bg-opacity-10 text-{{ $record->loai === 'tam_tru' ? 'success' : 'info' }} px-2.5 py-1.5 fs-6">
                                        {{ $record->loaiLabel() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light ps-3">Ngày bắt đầu</th>
                                <td class="ps-3 fw-semibold">{{ $record->ngay_bat_dau?->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light ps-3">Ngày hết hạn (dự kiến)</th>
                                <td class="ps-3 fw-semibold">
                                    @if($record->ngay_ket_thuc)
                                        {{ $record->ngay_ket_thuc->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted italic">Không xác định thời hạn</span>
                                    @endif
                                </td>
                            </tr>
                            @if($record->loai === 'tam_tru')
                            <tr>
                                <th class="bg-light ps-3">Địa chỉ cư trú thực tế</th>
                                <td class="ps-3 fw-semibold text-primary">{{ $record->dia_chi_cu_tru_thuc_te }}</td>
                            </tr>
                            @else
                            <tr>
                                <th class="bg-light ps-3">Địa chỉ vắng mặt (nơi đến)</th>
                                <td class="ps-3 fw-semibold text-info">{{ $record->dia_chi_vang_mat }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="bg-light ps-3">Trạng thái hồ sơ</th>
                                <td class="ps-3">
                                    @php
                                        $statusColor = 'secondary';
                                        if ($record->trang_thai === 'dang_hieu_luc') $statusColor = 'success';
                                        elseif ($record->trang_thai === 'da_het_han') $statusColor = 'warning';
                                        elseif ($record->trang_thai === 'da_huy') $statusColor = 'danger';
                                    @endphp
                                    <span class="badge text-bg-{{ $statusColor }} px-2.5 py-1.5">
                                        {{ $record->trangThaiLabel() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light ps-3">Cán bộ duyệt hồ sơ</th>
                                <td class="ps-3">{{ $record->nguoiXacNhan?->name ?? 'Hệ thống' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-journal-text me-1"></i>Lý do & Ghi chú bổ sung
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-secondary small d-block">Lý do khai báo</label>
                    <p class="mb-0 bg-light p-3 rounded text-dark border-start border-success border-3">{{ $record->ly_do ?? 'Không có lý do chi tiết' }}</p>
                </div>
                @if($record->ghi_chu)
                <div>
                    <label class="text-secondary small d-block">Ghi chú từ cán bộ</label>
                    <p class="mb-0 text-muted">{{ $record->ghi_chu }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Tác vụ Quản lý</h5>
                <p class="small text-secondary mb-4">Các hoạt động phê duyệt, sửa đổi thời hạn hay hủy bỏ hiệu lực đăng ký tạm trú / tạm vắng cho nhân khẩu này.</p>
                
                @can('manage_ho_khau')
                <div class="d-grid gap-2">
                    <a href="{{ route('tam-tru.edit', $record) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Sửa thông tin khai báo
                    </a>
                    <form method="POST" action="{{ route('tam-tru.destroy', $record) }}" class="w-100" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ khai báo này?">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100" type="submit">
                            <i class="bi bi-trash me-1"></i> Xóa hồ sơ này
                        </button>
                    </form>
                </div>
                @else
                <div class="alert alert-warning border-0 small mb-0">
                    Bạn chỉ có quyền xem thông tin chi tiết. Chỉ có Cán bộ Tư pháp hoặc Admin mới được phép thao tác hồ sơ tạm trú/tạm vắng.
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
