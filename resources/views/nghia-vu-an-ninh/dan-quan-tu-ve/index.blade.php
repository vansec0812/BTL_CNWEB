@extends('layouts.app')

@section('title', 'Lực lượng Dân quân tự vệ')
@section('page_title', 'Lực lượng Dân quân tự vệ')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle"><i class="bi bi-people fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">Tổng lực lượng</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['tong_so'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-primary bg-opacity-10 text-primary p-3 rounded-circle"><i class="bi bi-person-check fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">Đang phục vụ</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['dang_phuc_vu'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-success bg-opacity-10 text-success p-3 rounded-circle"><i class="bi bi-patch-check fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">Đã hoàn thành</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['da_hoan_thanh'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="badge bg-danger bg-opacity-10 text-danger p-3 rounded-circle"><i class="bi bi-person-x fs-4"></i></span>
                <div>
                    <p class="text-muted small mb-0">Đã rời lực lượng</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['da_roi'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('dan-quan-tu-ve.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="q" class="form-label small fw-semibold">Tìm kiếm</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" id="q" class="form-control bg-light border-start-0" placeholder="Tên, CCCD, chức vụ, tổ đội..." value="{{ $filters['q'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="trang_thai" class="form-label small fw-semibold">Trạng thái</label>
                <select name="trang_thai" id="trang_thai" class="form-select bg-light">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($trangThai as $k => $v)
                        <option value="{{ $k }}" {{ ($filters['trang_thai'] ?? '') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="don_vi" class="form-label small fw-semibold">Tổ/Đội dân quân</label>
                <input type="text" name="don_vi" id="don_vi" class="form-control bg-light" placeholder="Nhập tên tổ/đội..." value="{{ $filters['don_vi'] ?? '' }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-success w-100 fw-semibold"><i class="bi bi-funnel"></i> Lọc</button>
                <a href="{{ route('dan-quan-tu-ve.index') }}" class="btn btn-outline-secondary w-100 fw-semibold" title="Xoá bộ lọc"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-success"><i class="bi bi-people-fill me-1"></i>Danh sách dân quân tự vệ nòng cốt</h5>
        @can('manage_nghia_vu')
        <a href="{{ route('dan-quan-tu-ve.create') }}" class="btn btn-success fw-semibold"><i class="bi bi-plus-circle"></i> Thêm mới thành viên</a>
        @endcan
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">STT</th>
                        <th>Họ tên</th>
                        <th>CCCD/CMND</th>
                        <th>Năm sinh</th>
                        <th>Chức vụ</th>
                        <th>Tổ/đội</th>
                        <th>Ngày gia nhập</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $index => $row)
                    <tr>
                        <td class="ps-3">{{ $index + 1 + ($records->currentPage() - 1) * $records->perPage() }}</td>
                        <td class="fw-semibold">{{ $row->nhanKhau->ho_ten ?? '—' }}</td>
                        <td>{{ $row->nhanKhau->cccd_cmnd ?? '—' }}</td>
                        <td>{{ $row->nhanKhau->ngay_sinh ? $row->nhanKhau->ngay_sinh->format('Y') : '—' }}</td>
                        <td>{{ $row->chuc_vu ?? 'Chiến sĩ' }}</td>
                        <td>{{ $row->don_vi ?? '—' }}</td>
                        <td>{{ $row->ngay_gia_nhap ? $row->ngay_gia_nhap->format('d/m/Y') : '—' }}</td>
                        <td>
                            @php
                                $color = 'primary';
                                if ($row->trang_thai === 'da_hoan_thanh') { $color = 'success'; }
                                elseif ($row->trang_thai === 'da_roi') { $color = 'danger'; }
                            @endphp
                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-2 py-1">{{ $trangThai[$row->trang_thai] ?? $row->trang_thai }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('dan-quan-tu-ve.show', $row->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> Xem</a>
                                @can('manage_nghia_vu')
                                <a href="{{ route('dan-quan-tu-ve.edit', $row->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Sửa</a>
                                <form method="POST" action="{{ route('dan-quan-tu-ve.destroy', $row->id) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xoá thành viên {{ $row->nhanKhau->ho_ten ?? '' }} khỏi lực lượng dân quân tự vệ?">
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
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Không có kết quả nào phù hợp.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
