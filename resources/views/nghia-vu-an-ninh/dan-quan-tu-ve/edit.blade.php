@extends('layouts.app')

@section('title', 'Chỉnh sửa thông tin thành viên Dân quân')
@section('page_title', 'Chỉnh sửa thông tin thành viên Dân quân')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-success"><i class="bi bi-pencil-square me-1"></i>Cập nhật thông tin thành viên</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dan-quan-tu-ve.update', $record->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nhan_khau_id" class="form-label fw-semibold">Chọn công dân <span class="text-danger">*</span></label>
                        <select name="nhan_khau_id" id="nhan_khau_id" class="form-select @error('nhan_khau_id') is-invalid @enderror">
                            @foreach($nhanKhau as $person)
                                <option value="{{ $person->id }}" {{ old('nhan_khau_id', $record->nhan_khau_id) == $person->id ? 'selected' : '' }}>
                                    {{ $person->ho_ten }} (CCCD: {{ $person->cccd_cmnd ?? 'Chưa cập nhật' }} - Sinh năm: {{ $person->ngay_sinh ? $person->ngay_sinh->format('Y') : '—' }})
                                </option>
                            @endforeach
                        </select>
                        @error('nhan_khau_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="chuc_vu" class="form-label fw-semibold">Chức vụ trong lực lượng</label>
                            <input type="text" name="chuc_vu" id="chuc_vu" class="form-control @error('chuc_vu') is-invalid @enderror" placeholder="Ví dụ: Chiến sĩ, Tiểu đội trưởng..." value="{{ old('chuc_vu', $record->chuc_vu) }}">
                            @error('chuc_vu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="don_vi" class="form-label fw-semibold">Tổ/đội dân quân</label>
                            <input type="text" name="don_vi" id="don_vi" class="form-control @error('don_vi') is-invalid @enderror" placeholder="Ví dụ: Tổ dân quân Thôn 1" value="{{ old('don_vi', $record->don_vi) }}">
                            @error('don_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ngay_gia_nhap" class="form-label fw-semibold">Ngày gia nhập</label>
                            <input type="date" name="ngay_gia_nhap" id="ngay_gia_nhap" class="form-control @error('ngay_gia_nhap') is-invalid @enderror" value="{{ old('ngay_gia_nhap', $record->ngay_gia_nhap ? $record->ngay_gia_nhap->format('Y-m-d') : '') }}">
                            @error('ngay_gia_nhap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
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
                        <a href="{{ route('dan-quan-tu-ve.index') }}" class="btn btn-outline-secondary fw-semibold"><i class="bi bi-x-circle"></i> Huỷ bỏ</a>
                        <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-check-circle"></i> Cập nhật thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
