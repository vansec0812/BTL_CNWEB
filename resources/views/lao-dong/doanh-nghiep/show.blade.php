@extends('layouts.app')

@section('title', 'Chi tiết doanh nghiệp')
@section('page_title', 'Chi tiết doanh nghiệp')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('doanh-nghiep.index') }}" class="text-decoration-none">Doanh nghiệp</a>
        <span class="mx-1">/</span>
        Chi tiết
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0">Cơ sở: {{ $record->ten_co_so }}</h2>
        @can('manage_lao_dong')
        <div class="d-flex gap-2">
            <a href="{{ route('doanh-nghiep.edit', $record->id) }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
        </div>
        @endcan
    </div>
</div>

<div class="row g-4">
    {{-- Cột trái: Thông tin doanh nghiệp --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-2">
                <i class="bi bi-building text-primary fs-4"></i>
                <h5 class="fw-bold mb-0">Thông tin chung</h5>
            </div>
            <div class="card-body pt-0">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-secondary py-2" style="width: 35%;">Tên cơ sở:</td>
                            <td class="fw-bold py-2 text-primary">{{ $record->ten_co_so }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Loại hình kinh doanh:</td>
                            <td class="py-2">{{ $record->loaiHinhLabel() }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Mã số thuế:</td>
                            <td class="py-2 fw-semibold">{{ $record->ma_so_thue ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Số đăng ký kinh doanh:</td>
                            <td class="py-2">{{ $record->ma_so_dang_ky_kinh_doanh ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Ngành nghề chính:</td>
                            <td class="py-2 fw-semibold">{{ $record->nganh_nghe_chinh ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Địa chỉ trụ sở:</td>
                            <td class="py-2">{{ $record->dia_chi ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Thôn/Xóm đóng chân:</td>
                            <td class="py-2 fw-semibold">{{ $record->thon_xom ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Ngày thành lập:</td>
                            <td class="py-2">{{ $record->ngay_thanh_lap ? $record->ngay_thanh_lap->format('d/m/Y') : '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center gap-2">
                <i class="bi bi-person-lines-fill text-success fs-4"></i>
                <h5 class="fw-bold mb-0">Liên hệ & Nhân lực</h5>
            </div>
            <div class="card-body pt-0">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-secondary py-2" style="width: 35%;">Người đại diện:</td>
                            <td class="py-2">
                                @if($record->nguoiDaiDien)
                                    <a href="{{ route('nhan-khau.show', $record->nguoi_dai_dien_nhan_khau_id) }}" class="text-decoration-none fw-semibold">
                                        {{ $record->ten_nguoi_dai_dien }}
                                    </a>
                                    <span class="badge bg-secondary small ms-1">Công dân xã</span>
                                @else
                                    {{ $record->ten_nguoi_dai_dien ?? '—' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Điện thoại liên hệ:</td>
                            <td class="py-2 fw-semibold">{{ $record->so_dien_thoai_lien_he ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Quy mô lao động:</td>
                            <td class="py-2"><strong>{{ $record->so_lao_dong_hien_tai }}</strong> người</td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Vị trí tuyển dụng trống:</td>
                            <td class="py-2">
                                @if ($record->so_vi_tri_tuyen_dung > 0)
                                    <span class="badge bg-success fs-6"><i class="bi bi-person-plus-fill me-1"></i>Đang tuyển {{ $record->so_vi_tri_tuyen_dung }} vị trí</span>
                                @else
                                    <span class="text-muted">Không tuyển dụng</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Trạng thái:</td>
                            <td class="py-2">
                                @php $status = $record->trang_thai; @endphp
                                <span class="badge bg-{{ $status === 'dang_hoat_dong' ? 'success' : ($status === 'tam_ngung' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $status === 'dang_hoat_dong' ? 'success' : ($status === 'tam_ngung' ? 'warning' : 'danger') }} fs-6">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-secondary py-2">Ghi chú:</td>
                            <td class="py-2 text-muted small">{{ $record->ghi_chu ?? 'Không có ghi chú.' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Cột phải: Lịch sử giới thiệu kết nối việc làm --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-link text-info fs-4"></i>
                    <h5 class="fw-bold mb-0">Hồ sơ kết nối việc làm</h5>
                </div>
                @can('manage_lao_dong')
                @if($record->so_vi_tri_tuyen_dung > 0 && $record->trang_thai === 'dang_hoat_dong')
                <a href="{{ route('ket-noi.create', ['doanh_nghiep_id' => $record->id]) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-plus-lg"></i> Thêm kết nối</a>
                @endif
                @endcan
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Lao động</th>
                                <th>Vị trí giới thiệu</th>
                                <th>Ngày kết nối</th>
                                <th>Kết quả</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($record->ketNoiViecLam as $kn)
                            <tr>
                                <td>
                                    <a href="{{ route('ho-so.show', $kn->lao_dong_id) }}" class="fw-semibold text-decoration-none">
                                        {{ $kn->laoDong->nhanKhau->ho_ten }}
                                    </a>
                                </td>
                                <td>{{ $kn->vi_tri_gioi_thieu ?? '—' }}</td>
                                <td>{{ $kn->ngay_ket_noi ? $kn->ngay_ket_noi->format('d/m/Y') : '—' }}</td>
                                <td>
                                    @php $res = $kn->ket_qua; @endphp
                                    <span class="badge bg-{{ $res === 'duoc_nhan' ? 'success' : ($res === 'dang_cho_phan_hoi' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $res === 'duoc_nhan' ? 'success' : ($res === 'dang_cho_phan_hoi' ? 'warning' : 'danger') }}">
                                        {{ $kn->ketQuaLabel() }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa thực hiện kết nối việc làm nào với cơ sở này.
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
@endsection
