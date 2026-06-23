@extends('layouts.app')

@section('title', 'Quản lý Gói trợ cấp & Quỹ từ thiện')
@section('page_title', 'Quản lý Gói trợ cấp & Quỹ từ thiện')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="small text-secondary mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
            <span class="mx-1">/</span>
            Gói trợ cấp &amp; Quỹ từ thiện
        </div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('modules.show', $parentModule['slug']) }}" class="btn-back" title="Quay lại {{ $parentModule['title'] }}">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">Quản lý Gói trợ cấp &amp; Quỹ từ thiện</h2>
        </div>
        <p class="text-secondary mb-0">Quản lý các chiến dịch phát quà, đợt trợ cấp khó khăn và cứu trợ nhân dân trên địa bàn xã.</p>
    </div>
    @can('manage_an_sinh')
    <a href="{{ route('dot-tro-cap.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i> Tạo đợt trợ cấp mới
    </a>
    @endcan
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-2.4 col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng số đợt</p>
                <h4 class="fw-bold mb-0">{{ $stats['tong_so'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2.4 col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đang thực hiện</p>
                <h4 class="fw-bold mb-0 text-warning">{{ $stats['dang_thuc_hien'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2.4 col-sm-6 col-lg-2">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đã hoàn thành</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['hoan_thanh'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2.4 col-sm-6 col-lg-2.5">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Tổng quỹ cấp phát</p>
                <h4 class="fw-bold mb-0 text-primary">{{ number_format($stats['tong_quy_cap_phat'], 0, ',', '.') }}đ</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2.4 col-sm-6 col-lg-2.5">
        <div class="card stat-card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Đã trao thực tế</p>
                <h4 class="fw-bold mb-0 text-info">{{ number_format($stats['tong_da_trao'], 0, ',', '.') }}đ</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ lọc đợt trợ cấp</div>
    <div class="card-body">
        <form method="GET" action="{{ route('dot-tro-cap.index') }}" class="row g-3">
            <div class="col-lg-3">
                <label for="q" class="form-label">Tìm kiếm</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo tên đợt, mô tả hoặc nguồn kinh phí...">
            </div>
            <div class="col-lg-3">
                <label for="loai_tro_cap" class="form-label">Hình thức trợ cấp</label>
                <select id="loai_tro_cap" name="loai_tro_cap" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($loaiTroCap as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['loai_tro_cap'] ?? '') === $value)>{{ $label }}</option>
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
            <div class="col-lg-3 d-flex align-items-end gap-2">
                <button class="btn btn-success w-100" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('dot-tro-cap.index') }}">Xóa</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-gift me-1"></i>Danh sách gói trợ cấp</span>
        <span class="badge text-bg-light">{{ $records->total() }} đợt</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Tên đợt trợ cấp</th>
                        <th>Hình thức / Suất</th>
                        <th>Thời gian cấp phát</th>
                        <th>Tiến độ trao</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration + ($records->firstItem() - 1) }}</td>
                            <td>
                                <div class="fw-semibold">{{ $record->ten_dot }}</div>
                                <div class="small text-secondary text-truncate" style="max-width: 300px;">Nguồn: {{ $record->nguon_kinh_phi ?? 'Địa phương' }}</div>
                            </td>
                            <td>
                                <div>{{ $record->loaiLabel() }}</div>
                                <div class="small text-secondary">{{ number_format($record->gia_tri_quy_doi ?? 0, 0, ',', '.') }}đ/suất</div>
                            </td>
                            <td>
                                <div>Bắt đầu: {{ $record->ngay_bat_dau_cap_phat->format('d/m/Y') }}</div>
                                @if($record->ngay_ket_thuc_cap_phat)
                                <div class="small text-secondary">Kết thúc: {{ $record->ngay_ket_thuc_cap_phat->format('d/m/Y') }}</div>
                                @else
                                <div class="small text-secondary">Chưa xác định ngày đóng</div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        @php
                                            $percent = $record->tong_so_doi_tuong > 0 
                                                ? ($record->so_da_nhan / $record->tong_so_doi_tuong) * 100 
                                                : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="small fw-semibold text-secondary">{{ $record->so_da_nhan }}/{{ $record->tong_so_doi_tuong }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $record->trangThaiBadgeColor() }}">
                                    {{ $record->trangThaiLabel() }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('dot-tro-cap.show', $record) }}" class="btn btn-sm btn-action-view" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('manage_an_sinh')
                                    <a href="{{ route('dot-tro-cap.edit', $record) }}" class="btn btn-sm btn-action-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('dot-tro-cap.destroy', $record) }}" class="d-inline" data-confirm="Bạn có chắc chắn muốn xóa đợt trợ cấp này? Việc xóa sẽ xóa tất cả chi tiết cấp phát đi kèm.">
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
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Chưa có đợt trợ cấp nào phù hợp.
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
