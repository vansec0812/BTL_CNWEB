@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">Cơ sở vật chất & Hạ tầng</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cơ sở vật chất</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('co-so-vat-chat.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Thêm công trình
        </a>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100 rounded-4 border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 mb-1">Tổng Công Trình</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['tong_cong_trinh']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100 rounded-4 border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 mb-1">Tổng Đầu Tư (VNĐ)</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['tong_von_dau_tu']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white h-100 rounded-4 border-0 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 mb-1">Cần Sửa Chữa</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['cong_trinh_xuong_cap']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom p-4">
            <form action="{{ route('co-so-vat-chat.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-lg rounded-pill bg-light border-0" 
                           name="search" value="{{ request('search') }}" 
                           placeholder="🔍 Tên công trình, thôn xóm...">
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-lg rounded-pill bg-light border-0" name="phan_loai">
                        <option value="">-- Phân loại --</option>
                        @foreach(\App\Models\CoSoVatChat::PHAN_LOAI as $key => $label)
                            <option value="{{ $key }}" @selected(request('phan_loai') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-lg rounded-pill bg-light border-0" name="tinh_trang">
                        <option value="">-- Tình trạng --</option>
                        @foreach(\App\Models\CoSoVatChat::TINH_TRANG as $key => $label)
                            <option value="{{ $key }}" @selected(request('tinh_trang') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark btn-lg rounded-pill w-100 shadow-sm">Lọc</button>
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tên công trình</th>
                            <th>Phân loại</th>
                            <th>Thôn xóm</th>
                            <th>Ngày khánh thành</th>
                            <th>Kinh phí (VNĐ)</th>
                            <th>Tình trạng</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td class="ps-4 fw-medium text-dark">{{ $record->ten_cong_trinh }}</td>
                                <td><span class="badge bg-secondary rounded-pill fw-normal">{{ $record->phanLoaiLabel() }}</span></td>
                                <td>{{ $record->thon_xom ?? '--' }}</td>
                                <td>{{ $record->ngay_dua_vao_su_dung ? $record->ngay_dua_vao_su_dung->format('d/m/Y') : '--' }}</td>
                                <td>{{ $record->kinh_phi_xay_dung ? number_format($record->kinh_phi_xay_dung) : '--' }}</td>
                                <td>
                                    @php
                                        $color = match($record->tinh_trang) {
                                            'tot' => 'success',
                                            'dang_su_dung' => 'primary',
                                            'xuong_cap' => 'warning text-dark',
                                            'can_sua_chua' => 'danger',
                                            'ngung_su_dung' => 'secondary',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }} rounded-pill">{{ $record->tinhTrangLabel() }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('co-so-vat-chat.edit', $record) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('co-so-vat-chat.destroy', $record) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" 
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa công trình này?')" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Không tìm thấy công trình nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($records->hasPages())
            <div class="card-footer bg-white border-top p-4">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
