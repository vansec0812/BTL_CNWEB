@extends('layouts.app')

@section('title', 'Doanh nghiệp & Hộ kinh doanh')
@section('page_title', 'Doanh nghiệp & Hộ kinh doanh')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Doanh nghiệp & Hộ kinh doanh
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Doanh nghiệp & Hộ kinh doanh địa phương</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý thông tin cơ sở kinh doanh, người đại diện, lao động và tình trạng hoạt động trên địa bàn xã.</p>
    </div>
    @can('manage_lao_dong')
    <a href="{{ route('doanh-nghiep.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Đăng ký doanh nghiệp mới
    </a>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('status') }}</div>
@endif

<!-- Thống kê nhanh -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng số cơ sở</p>
                <h4 class="fw-bold mb-0">{{ $stats['tong_doanh_nghiep'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đang hoạt động</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['dang_hoat_dong'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Nhu cầu tuyển dụng</p>
                <h4 class="fw-bold mb-0 text-primary">{{ $stats['tuyen_dung'] }} lao động</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc doanh nghiệp</div>
    <div class="card-body">
        <form method="GET" action="{{ route('doanh-nghiep.index') }}" class="row g-3">
            <div class="col-lg-4">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="search" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Tên cơ sở, MST, người đại diện...">
            </div>
            <div class="col-lg-3">
                <label for="loai_hinh" class="form-label">Loại hình</label>
                <select id="loai_hinh" name="loai_hinh" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiHinh as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_hinh'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($trangThai as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('doanh-nghiep.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách doanh nghiệp & hộ kinh doanh</span>
        <span class="badge text-bg-light">{{ $records->total() }} cơ sở</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
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
                        <td>{{ $loop->iteration + ($records->firstItem() - 1) }}</td>
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
                                <a href="{{ route('doanh-nghiep.show', $dn->id) }}" class="btn btn-sm btn-action-view" title="Xem">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('manage_lao_dong')
                                <a href="{{ route('doanh-nghiep.edit', $dn->id) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('doanh-nghiep.destroy', $dn->id) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa doanh nghiệp này?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-action-delete" type="submit" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
        <div class="card-footer bg-white border-top py-3">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
