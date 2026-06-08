@extends('layouts.app')

@section('title', 'Chi tiết thành viên Dân quân')
@section('page_title', 'Chi tiết thành viên Dân quân')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dan-quan-tu-ve.index') }}" class="text-decoration-none text-muted">Lực lượng dân quân tự vệ</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Chi tiết thành viên</span>
    </div>
    <h2 class="fw-bold mb-1">Chi tiết thành viên Dân quân</h2>
    <p class="text-secondary mb-0">Xem thông tin lý lịch, chức vụ, đơn vị công tác và thời gian hoạt động của chiến sĩ dân quân tự vệ.</p>
</div>

<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs mb-4" id="danQuanDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="thong-tin-tab" data-bs-toggle="tab" data-bs-target="#thong-tin-pane" type="button" role="tab" aria-controls="thong-tin-pane" aria-selected="true">
                    <i class="bi bi-person-lines-fill me-1"></i>Thông tin
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="lich-su-tab" data-bs-toggle="tab" data-bs-target="#lich-su-pane" type="button" role="tab" aria-controls="lich-su-pane" aria-selected="false">
                    <i class="bi bi-clock-history me-1"></i>Lịch sử hoạt động
                </button>
            </li>
        </ul>

        <div class="tab-content" id="danQuanDetailTabsContent">
            <div class="tab-pane fade show active" id="thong-tin-pane" role="tabpanel" aria-labelledby="thong-tin-tab" tabindex="0">
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
                @can('manage_nghia_vu')
                    <div class="d-flex justify-content-end mb-4">
                        <a href="{{ route('dan-quan-tu-ve.edit', $record->id) }}" class="btn btn-success fw-semibold px-4">
                            <i class="bi bi-pencil me-1"></i>Chỉnh sửa thông tin
                        </a>
                    </div>
                @endcan
            </div>

            <div class="tab-pane fade" id="lich-su-pane" role="tabpanel" aria-labelledby="lich-su-tab" tabindex="0">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="bi bi-clock-history me-1"></i>Lịch sử hoạt động
                        </h5>
                        <span class="badge text-bg-light">{{ $record->hoatDong->count() }} bản ghi</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">STT</th>
                                        <th>Loại hoạt động</th>
                                        <th>Tên hoạt động</th>
                                        <th>Ngày thực hiện</th>
                                        <th>Trạng thái</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($record->hoatDong as $activity)
                                        @php
                                            $color = 'secondary';
                                            if (in_array($activity->trang_thai, ['tham_gia', 'da_truc'])) $color = 'success';
                                            elseif ($activity->trang_thai === 'vang_co_phep') $color = 'warning';
                                            elseif (in_array($activity->trang_thai, ['vang_khong_phep', 'vang_mat'])) $color = 'danger';
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge bg-{{ $activity->loai_hoat_dong === 'tap_huan' ? 'info' : 'primary' }}">
                                                    {{ $loaiHoatDong[$activity->loai_hoat_dong] ?? $activity->loai_hoat_dong }}
                                                </span>
                                            </td>
                                            <td class="fw-semibold">{{ $activity->ten_hoat_dong }}</td>
                                            <td>{{ $activity->ngay_thuc_hien ? $activity->ngay_thuc_hien->format('d/m/Y') : '—' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $color }}">
                                                    {{ $trangThaiHoatDong[$activity->trang_thai] ?? $activity->trang_thai }}
                                                </span>
                                            </td>
                                            <td class="text-secondary">{{ $activity->ghi_chu ?? 'Không có' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                                Chưa có hoạt động dân quân nào được ghi nhận.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('dan-quan-tu-ve.index') }}" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
        </div>
    </div>
</div>
@endsection
