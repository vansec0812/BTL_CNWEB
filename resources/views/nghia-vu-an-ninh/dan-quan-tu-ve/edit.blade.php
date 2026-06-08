@extends('layouts.app')

@section('title', 'Chỉnh sửa thông tin thành viên Dân quân')
@section('page_title', 'Chỉnh sửa thông tin thành viên Dân quân')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dan-quan-tu-ve.index') }}" class="text-decoration-none text-muted">Lực lượng dân quân tự vệ</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Chỉnh sửa thông tin</span>
    </div>
    <h2 class="fw-bold mb-1">Chỉnh sửa thông tin thành viên Dân quân</h2>
    <p class="text-secondary mb-0">Cập nhật chức vụ, tổ đội, ngày tham gia và trạng thái phục vụ của chiến sĩ.</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-success"><i class="bi bi-pencil-square me-1"></i>Cập nhật thông tin thành viên</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dan-quan-tu-ve.update', $record->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Công dân phục vụ</label>
                        <input type="text" class="form-control" value="{{ $record->nhanKhau->ho_ten }} - CCCD: {{ $record->nhanKhau->cccd_cmnd ?? 'Chưa có' }} (Sinh ngày: {{ $record->nhanKhau->ngay_sinh ? $record->nhanKhau->ngay_sinh->format('d/m/Y') : '—' }})" disabled>
                        <input type="hidden" name="nhan_khau_id" value="{{ $record->nhan_khau_id }}">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <label for="chuc_vu" class="form-label fw-semibold">Chức vụ trong lực lượng</label>
                            <select name="chuc_vu" id="chuc_vu" class="form-select @error('chuc_vu') is-invalid @enderror">
                                @foreach(\App\Models\DanQuanTuVe::CHUC_VU_LIST as $val)
                                    <option value="{{ $val }}" {{ old('chuc_vu', $record->chuc_vu) === $val ? 'selected' : '' }}>
                                        {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                            @error('chuc_vu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label for="don_vi" class="form-label fw-semibold">Tổ/đội dân quân</label>
                            <input type="text" name="don_vi" id="don_vi" class="form-control @error('don_vi') is-invalid @enderror" placeholder="Ví dụ: Tổ dân quân Thôn 1" value="{{ old('don_vi', $record->don_vi) }}">
                            @error('don_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-lg-4">
                            <label for="ngay_gia_nhap" class="form-label fw-semibold">Ngày gia nhập</label>
                            <input type="date" name="ngay_gia_nhap" id="ngay_gia_nhap" class="form-control @error('ngay_gia_nhap') is-invalid @enderror" value="{{ old('ngay_gia_nhap', $record->ngay_gia_nhap ? $record->ngay_gia_nhap->format('Y-m-d') : '') }}">
                            @error('ngay_gia_nhap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="trang_thai" class="form-label fw-semibold">Trạng thái phục vụ <span class="text-danger">*</span></label>
                            <select name="trang_thai" id="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                                @foreach($trangThai as $k => $v)
                                    <option value="{{ $k }}" {{ old('trang_thai', $record->trang_thai) === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('trang_thai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ngay_ket_thuc" class="form-label fw-semibold">Ngày kết thúc nhiệm kỳ</label>
                        <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control @error('ngay_ket_thuc') is-invalid @enderror" value="{{ old('ngay_ket_thuc', $record->ngay_ket_thuc ? $record->ngay_ket_thuc->format('Y-m-d') : '') }}">
                        @error('ngay_ket_thuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ghi_chu" class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="ghi_chu" id="ghi_chu" rows="3" class="form-control" placeholder="Nhập ghi chú thêm nếu có...">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('dan-quan-tu-ve.index') }}" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
                        <button type="submit" class="btn btn-success fw-semibold px-4"><i class="bi bi-check-lg me-1"></i>Cập nhật thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
