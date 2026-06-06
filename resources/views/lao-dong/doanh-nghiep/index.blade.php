@extends('layouts.app')

@section('title', 'Doanh nghiệp & Hộ kinh doanh')
@section('page_title', 'Doanh nghiệp & Hộ kinh doanh')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-start">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Danh sách doanh nghiệp
        </div>
        <h2 class="fw-bold mb-0">Doanh nghiệp & Hộ kinh doanh địa phương</h2>
    </div>
    @can('manage_lao_dong')
    <a href="{{ route('doanh-nghiep.create') }}" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Đăng ký doanh nghiệp mới
    </a>
    @endcan
</div>

{{-- Bộ lọc --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('doanh-nghiep.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label small text-secondary fw-semibold">Tìm kiếm</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" placeholder="Tên cơ sở, MST, người đại diện...">
            </div>
            <div class="col-md-3">
                <label for="loai_hinh" class="form-label small text-secondary fw-semibold">Loại hình</label>
                <select id="loai_hinh" name="loai_hinh" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    @foreach ($loaiHinh as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_hinh'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="trang_thai" class="form-label small text-secondary fw-semibold">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select form-select-sm">
                    <option value="">Tất cả</option>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Lọc</button>
                <a href="{{ route('doanh-nghiep.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Danh sách --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên cơ sở</th>
                        <th>Loại hình</th>
                        <th>Ngành nghề chính</th>
                        <th>Địa bàn (Thôn)</th>
                        <th>Đại diện pháp luật</th>
                        <th>SĐT liên hệ</th>
                        <th>Lao động / Tuyển dụng</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $dn)
                    <tr>
                        <td>{{ $dn->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $dn->ten_co_so }}</div>
                            @if($dn->ma_so_thue)
                                <div class="text-muted small">MST: {{ $dn->ma_so_thue }}</div>
                            @endif
                        </td>
                        <td>{{ $dn->loaiHinhLabel() }}</td>
                        <td>{{ $dn->nganh_nghe_chinh ?? '—' }}</td>
                        <td>{{ $dn->thon_xom ?? '—' }}</td>
                        <td>
                            @if($dn->nguoiDaiDien)
                                <a href="{{ route('nhan-khau.show', $dn->nguoi_dai_dien_nhan_khau_id) }}" class="text-decoration-none fw-semibold">
                                    {{ $dn->ten_nguoi_dai_dien }}
                                </a>
                                <span class="badge bg-secondary small ms-1">Công dân xã</span>
                            @else
                                {{ $dn->ten_nguoi_dai_dien ?? '—' }}
                            @endif
                        </td>
                        <td>{{ $dn->so_dien_thoai_lien_he ?? '—' }}</td>
                        <td>
                            <div>Hành chính: <strong>{{ $dn->so_lao_dong_hien_tai }}</strong></div>
                            @if($dn->so_vi_tri_tuyen_dung > 0)
                                <div class="text-success small"><i class="bi bi-person-plus-fill me-1"></i>Tuyển: <strong>{{ $dn->so_vi_tri_tuyen_dung }}</strong></div>
                            @else
                                <div class="text-muted small">Không tuyển dụng</div>
                            @endif
                        </td>
                        <td>
                            @php $status = $dn->trang_thai; @endphp
                            <span class="badge bg-{{ $status === 'dang_hoat_dong' ? 'success' : ($status === 'tam_ngung' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $status === 'dang_hoat_dong' ? 'success' : ($status === 'tam_ngung' ? 'warning' : 'danger') }}">
                                {{ $dn->trangThaiLabel() }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('doanh-nghiep.show', $dn->id) }}" class="btn btn-sm btn-outline-secondary" title="Chi tiết"><i class="bi bi-eye"></i></a>
                                @can('manage_lao_dong')
                                <a href="{{ route('doanh-nghiep.edit', $dn->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('doanh-nghiep.destroy', $dn->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa doanh nghiệp này?');" class="d-inline">
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
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Không tìm thấy doanh nghiệp nào khớp với điều kiện lọc.
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
