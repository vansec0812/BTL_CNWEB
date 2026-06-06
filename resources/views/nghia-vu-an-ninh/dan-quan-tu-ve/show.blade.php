@extends('layouts.app')

@section('title', 'Chi tiết thành viên Dân quân')
@section('page_title', 'Chi tiết thành viên Dân quân')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        {{-- Thẻ thông tin cá nhân --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($record->nhanKhau->ho_ten ?? 'D', 0, 1) }}
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ $record->nhanKhau->ho_ten ?? '—' }}</h4>
                <p class="text-muted mb-3">{{ $record->chuc_vu ?? 'Chiến sĩ' }} — {{ $record->don_vi ?? 'Chưa biên chế' }}</p>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                    <i class="bi bi-shield-fill-check me-1"></i> {{ $trangThai[$record->trang_thai] ?? $record->trang_thai }}
                </span>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-success">
                    <i class="bi bi-person-lines-fill me-1"></i>Thông tin lý lịch & Công tác
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <tbody>
                        <tr>
                            <td class="text-muted fw-semibold ps-4 py-3">Số CCCD/CMND:</td>
                            <td class="pe-4 py-3 text-end fw-semibold">{{ $record->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-4 py-3">Ngày sinh:</td>
                            <td class="pe-4 py-3 text-end">{{ $record->nhanKhau->ngay_sinh ? $record->nhanKhau->ngay_sinh->format('d/m/Y') : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-4 py-3">Giới tính:</td>
                            <td class="pe-4 py-3 text-end">
                                <span class="badge bg-opacity-10 text-{{ ($record->nhanKhau->gioi_tinh ?? '') === 'nam' ? 'primary bg-primary' : (($record->nhanKhau->gioi_tinh ?? '') === 'nu' ? 'danger bg-danger' : 'secondary bg-secondary') }}">
                                    {{ ($record->nhanKhau->gioi_tinh ?? '') === 'nam' ? 'Nam' : (($record->nhanKhau->gioi_tinh ?? '') === 'nu' ? 'Nữ' : 'Khác') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold ps-4 py-3">Ngày gia nhập:</td>
                            <td class="pe-4 py-3 text-end">{{ $record->ngay_gia_nhap ? $record->ngay_gia_nhap->format('d/m/Y') : '—' }}</td>
                        </tr>
                        @if($record->ngay_ket_thuc)
                        <tr>
                            <td class="text-muted fw-semibold ps-4 py-3">Ngày kết thúc:</td>
                            <td class="pe-4 py-3 text-end">{{ $record->ngay_ket_thuc->format('d/m/Y') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted fw-semibold ps-4 py-3">Ghi chú:</td>
                            <td class="pe-4 py-3 text-end text-secondary">{{ $record->ghi_chu ?? 'Không có' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('dan-quan-tu-ve.index') }}" class="btn btn-outline-secondary fw-semibold">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
            @can('manage_nghia_vu')
            <a href="{{ route('dan-quan-tu-ve.edit', $record->id) }}" class="btn btn-success fw-semibold">
                <i class="bi bi-pencil-square"></i> Chỉnh sửa thành viên
            </a>
            @endcan
        </div>
    </div>
</div>
@endsection
