@extends('layouts.app')

@section('title', 'Hồ sơ lao động')
@section('page_title', 'Quản lý Hồ sơ lao động')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-start">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Danh sách lao động
        </div>
        <h2 class="fw-bold mb-0">Hồ sơ lao động dân cư</h2>
    </div>
    @can('manage_lao_dong')
    <a href="{{ route('ho-so.create') }}" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Thêm hồ sơ mới
    </a>
    @endcan
</div>

{{-- Bộ lọc --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('ho-so.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label small text-secondary fw-semibold">Tìm kiếm</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" placeholder="Nhập tên, số CCCD...">
            </div>
            <div class="col-md-2">
                <label for="trang_thai_lao_dong" class="form-label small text-secondary fw-semibold">Trạng thái</label>
                <select id="trang_thai_lao_dong" name="trang_thai_lao_dong" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    @foreach ($trangThaiLaoDong as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai_lao_dong'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="nganh_nghe" class="form-label small text-secondary fw-semibold">Ngành nghề</label>
                <select id="nganh_nghe" name="nganh_nghe" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    @foreach ($nganhNghe as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['nganh_nghe'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="loai_hinh_cong_viec" class="form-label small text-secondary fw-semibold">Loại hình</label>
                <select id="loai_hinh_cong_viec" name="loai_hinh_cong_viec" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    @foreach ($loaiHinhCongViec as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_hinh_cong_viec'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="thon_xom" class="form-label small text-secondary fw-semibold">Thôn/Xóm</label>
                <input type="text" id="thon_xom" name="thon_xom" value="{{ $filters['thon_xom'] ?? '' }}" class="form-control form-control-sm" placeholder="Tên thôn...">
            </div>
            <div class="col-md-2">
                <label for="xuat_khau_lao_dong" class="form-label small text-secondary fw-semibold">Đi XKLD?</label>
                <select id="xuat_khau_lao_dong" name="xuat_khau_lao_dong" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(($filters['xuat_khau_lao_dong'] ?? '') === '1')>Có</option>
                    <option value="0" @selected(($filters['xuat_khau_lao_dong'] ?? '') === '0')>Không</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="lam_viec_ngoai_tinh" class="form-label small text-secondary fw-semibold">Làm việc ngoài tỉnh?</label>
                <select id="lam_viec_ngoai_tinh" name="lam_viec_ngoai_tinh" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(($filters['lam_viec_ngoai_tinh'] ?? '') === '1')>Có</option>
                    <option value="0" @selected(($filters['lam_viec_ngoai_tinh'] ?? '') === '0')>Không</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <a href="{{ route('ho-so.index') }}" class="btn btn-sm btn-outline-secondary px-3">Xóa bộ lọc</a>
                <button type="submit" class="btn btn-sm btn-primary px-4">Lọc dữ liệu</button>
            </div>
        </form>
    </div>
</div>

{{-- Bảng danh sách --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã HS</th>
                        <th>Họ tên</th>
                        <th>CCCD</th>
                        <th>Thôn/Xóm</th>
                        <th>Trạng thái</th>
                        <th>Ngành nghề</th>
                        <th>Loại hình</th>
                        <th>Làm xa / XKLD</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $ld)
                    <tr>
                        <td class="fw-semibold">HS-{{ str_pad($ld->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-semibold">{{ $ld->nhanKhau->ho_ten }}</div>
                            <div class="text-muted small">Tuổi: {{ $ld->nhanKhau->ngay_sinh ? $ld->nhanKhau->ngay_sinh->age : '—' }}</div>
                        </td>
                        <td>{{ $ld->nhanKhau->cccd_cmnd ?? '—' }}</td>
                        <td>{{ $ld->nhanKhau->hoKhau->thon_xom ?? '—' }}</td>
                        <td>
                            @php $trangThaiLd = $ld->trang_thai_lao_dong; @endphp
                            <span class="badge bg-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }} bg-opacity-10 text-{{ $trangThaiLd === 'co_viec_lam' ? 'success' : ($trangThaiLd === 'that_nghiep' ? 'danger' : 'secondary') }}">
                                {{ $ld->trangThaiLabel() }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $ld->nganhNgheLabel() }}</div>
                            @if($ld->nghe_nghiep)
                                <div class="text-muted small">{{ $ld->nghe_nghiep }}</div>
                            @endif
                        </td>
                        <td>{{ $ld->loaiHinhLabel() }}</td>
                        <td>
                            @if ($ld->xuat_khau_lao_dong)
                                <span class="badge bg-warning text-dark"><i class="bi bi-airplane-fill me-1"></i>XKLD: {{ $ld->quoc_gia_lam_viec }}</span>
                            @elseif ($ld->lam_viec_ngoai_tinh)
                                <span class="badge bg-info text-dark"><i class="bi bi-geo-alt-fill me-1"></i>Ngoại tỉnh</span>
                            @else
                                <span class="text-muted small">Tại địa phương</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('ho-so.show', $ld->id) }}" class="btn btn-sm btn-outline-secondary" title="Chi tiết"><i class="bi bi-eye"></i></a>
                                @can('manage_lao_dong')
                                <a href="{{ route('ho-so.edit', $ld->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('ho-so.destroy', $ld->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hồ sơ lao động này?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Không tìm thấy hồ sơ lao động nào khớp với điều kiện lọc.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($records->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
