@extends('layouts.app')

@section('title', 'Quản lý An ninh trật tự')
@section('page_title', 'Quản lý An ninh trật tự')

@section('content')


<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            An ninh trật tự
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Hồ sơ An ninh trật tự</h2>
        </div>
        <p class="text-secondary mb-0">Theo dõi các đối tượng thuộc diện quản lý đặc biệt và danh sách quyết định xử phạt vi phạm hành chính.</p>
    </div>
    @can('manage_nghia_vu')
    <div>
        <a href="{{ route('an-ninh-trat-tu.create') }}" class="btn btn-success fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới hồ sơ
        </a>
    </div>
    @endcan
</div>

{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng số hồ sơ</p>
                <h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Quản lý đặc biệt</p>
                <h4 class="fw-bold mb-0 text-warning">{{ $stats['quan_ly_dac_biet'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Vi phạm hành chính</p>
                <h4 class="fw-bold mb-0 text-danger">{{ $stats['vi_pham_hanh_chinh'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Chưa chấp hành phạt</p>
                <h4 class="fw-bold mb-0 text-info">{{ $stats['chua_chap_hanh'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc tìm kiếm</div>
    <div class="card-body">
        {{-- Bộ lọc tìm kiếm --}}
        <form action="{{ route('an-ninh-trat-tu.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label for="q" class="form-label">Tìm kiếm</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" id="q" class="form-control border-start-0 ps-0"
                           placeholder="Tìm theo họ tên, CCCD, cơ quan giải quyết..." value="{{ $filters['q'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="loai_doi_tuong" class="form-label">Loại đối tượng</label>
                <select name="loai_doi_tuong" id="loai_doi_tuong" class="form-select">
                    <option value="">Tất cả phân loại</option>
                    @foreach($loaiDoiTuong as $k => $v)
                        <option value="{{ $k }}" {{ ($filters['loai_doi_tuong'] ?? '') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($trangThai as $k => $v)
                        <option value="{{ $k }}" {{ ($filters['trang_thai'] ?? '') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-success w-100">Lọc</button>
                @if(count($filters) > 0)
                    <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">Xóa</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60" class="text-center">STT</th>
                        <th>Họ tên / Đối tượng</th>
                        <th>Phân loại</th>
                        <th>Cơ quan giải quyết</th>
                        <th>Ngày ghi nhận</th>
                        <th>Hình thức xử lý / Phạt tiền</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $index => $row)
                        <tr>
                            <td class="text-center text-secondary small">{{ $records->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $row->ho_ten }}</div>
                                <div class="text-muted small">
                                    CCCD: {{ $row->cccd ?? '—' }}
                                    @if($row->nhanKhau)
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1" style="font-size: 0.7rem;">Cư dân</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1" style="font-size: 0.7rem;">Vãng lai</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $typeColor = 'secondary';
                                    if ($row->loai_doi_tuong === 'tien_an_tien_su') $typeColor = 'dark';
                                    elseif ($row->loai_doi_tuong === 'nguoi_nghien_ma_tuy') $typeColor = 'danger';
                                    elseif ($row->loai_doi_tuong === 'theo_doi_an_ninh') $typeColor = 'warning';
                                    elseif ($row->loai_doi_tuong === 'bao_luc_gia_dinh') $typeColor = 'info';
                                    elseif ($row->loai_doi_tuong === 'vi_pham_hanh_chinh') $typeColor = 'primary';
                                @endphp
                                <div class="mb-1">
                                    <span class="badge bg-light text-dark border px-2 py-1 small fw-semibold">{{ $row->nhomLabel() }}</span>
                                </div>
                                <span class="badge bg-{{ $typeColor }} bg-opacity-10 text-{{ $typeColor }} border border-{{ $typeColor }} px-2 py-1 small">
                                    {{ $row->loaiLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $row->co_quan_giai_quyet }}</div>
                            </td>
                            <td>
                                {{ $row->ngay_ghi_nhan ? $row->ngay_ghi_nhan->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                <div>{{ $row->hinh_thuc_xu_ly ?? '—' }}</div>
                                @if($row->so_tien_phat)
                                    <div class="text-primary fw-bold small">{{ $row->soTienPhatFormatted() }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColor = 'secondary';
                                    if ($row->trang_thai === 'dang_quan_ly') $statusColor = 'warning';
                                    elseif ($row->trang_thai === 'chua_chap_hanh') $statusColor = 'danger';
                                    elseif ($row->trang_thai === 'da_chap_hanh') $statusColor = 'success';
                                @endphp
                                <span class="badge bg-{{ $statusColor }} px-2 py-1">{{ $row->trangThaiLabel() }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('an-ninh-trat-tu.show', $row) }}" class="btn btn-sm btn-action-view" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('manage_nghia_vu')
                                        <a href="{{ route('an-ninh-trat-tu.edit', $row) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('an-ninh-trat-tu.destroy', $row) }}" method="POST" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa hồ sơ an ninh trật tự này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-action-delete" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bg-opacity-10 bi-shield-slash fs-1 text-secondary d-block mb-2"></i>
                                Không tìm thấy hồ sơ an ninh trật tự nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($records->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
