@extends('layouts.app')

@section('title', 'Hồ sơ lao động')
@section('page_title', 'Quản lý Hồ sơ lao động')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Hồ sơ lao động
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Hồ sơ lao động dân cư</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý thông tin lao động, ngành nghề, loại hình công việc và tình trạng việc làm của công dân.</p>
    </div>
    @can('manage_lao_dong')
    <a href="{{ route('ho-so.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Thêm hồ sơ mới
    </a>
    @endcan
</div>

<!-- Thống kê nhanh -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng số lao động</p>
                <h4 class="fw-bold mb-0">{{ $stats['tong_lao_dong'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Có việc làm</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['co_viec_lam'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Thất nghiệp</p>
                <h4 class="fw-bold mb-0 text-danger">{{ $stats['that_nghiep'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Xuất khẩu lao động</p>
                <h4 class="fw-bold mb-0 text-primary">{{ $stats['xkld'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc hồ sơ lao động</div>
    <div class="card-body">
        <form method="GET" action="{{ route('ho-so.index') }}" class="row g-3">
            <div class="col-lg-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="search" id="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Nhập tên, số CCCD, thôn/xóm...">
            </div>
            <div class="col-lg-2">
                <label for="trang_thai_lao_dong" class="form-label">Trạng thái</label>
                <select id="trang_thai_lao_dong" name="trang_thai_lao_dong" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($trangThaiLaoDong as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['trang_thai_lao_dong'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label for="nganh_nghe" class="form-label">Ngành nghề</label>
                <select id="nganh_nghe" name="nganh_nghe" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($nganhNghe as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['nganh_nghe'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label for="loai_hinh_cong_viec" class="form-label">Loại hình</label>
                <select id="loai_hinh_cong_viec" name="loai_hinh_cong_viec" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiHinhCongViec as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_hinh_cong_viec'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('ho-so.index') }}">Xóa</a>
            </div>

        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table me-1"></i>Danh sách hồ sơ lao động</span>
        <span class="badge text-bg-light">{{ $records->total() }} hồ sơ</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Mã HS</th>
                        <th>Họ tên</th>
                        <th>CCCD</th>
                        <th>Thôn/Xóm</th>
                        <th>Trạng thái</th>
                        <th>Ngành nghề</th>
                        <th>Loại hình</th>
                        <th>Làm xa / XKLD</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $ld)
                    <tr>
                        <td>{{ $loop->iteration + ($records->firstItem() - 1) }}</td>
                        <td class="fw-semibold text-success">HS-{{ str_pad($ld->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-semibold">{{ $ld->nhanKhau->ho_ten }}</div>
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
                            @if($ld->nghe_nghiep && $ld->nganhNgheLabel() && $ld->nganhNgheLabel() !== '—')
                                {{ $ld->nganhNgheLabel() }} ({{ $ld->nghe_nghiep }})
                            @elseif($ld->nghe_nghiep)
                                {{ $ld->nghe_nghiep }}
                            @else
                                {{ $ld->nganhNgheLabel() }}
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
                                <a href="{{ route('ho-so.show', $ld->id) }}" class="btn btn-sm btn-action-view" title="Xem">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('manage_lao_dong')
                                <a href="{{ route('ho-so.edit', $ld->id) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('ho-so.destroy', $ld->id) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ lao động này?">
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
                            Không tìm thấy hồ sơ lao động nào khớp với điều kiện lọc.
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
