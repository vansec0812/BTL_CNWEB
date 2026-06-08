@extends('layouts.app')

@section('title', 'Khai báo Chuyển đến (Từ ngoài xã)')
@section('page_title', 'Chuyển đến từ ngoài xã')

@section('content')
<style>
    .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: #6c757d;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-back:hover {
        color: var(--admin-green);
        background-color: var(--admin-green-soft);
        border-color: rgba(15, 81, 50, 0.2);
        transform: translateX(-2px);
    }
</style>

<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('bien-dong.index') }}" class="text-decoration-none">Biến động hộ khẩu</a>
        <span class="mx-1">/</span>
        Chuyển đến
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('bien-dong.index') }}" class="btn-back" title="Quay lại">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Đăng ký chuyển đến (Từ ngoài xã)</h2>
    </div>
</div>

<form method="POST" action="{{ route('bien-dong.store') }}" novalidate>
    @csrf
    <input type="hidden" name="loai_bien_dong" value="chuyen_den">

    <div class="row g-4">
        {{-- Thông tin cư trú --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-geo-alt me-1"></i>Hộ khẩu tiếp nhận
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="ho_khau_id" class="form-label">Sổ hộ khẩu nhận nhân khẩu <span class="text-danger">*</span></label>
                        <select id="ho_khau_id" name="ho_khau_id" class="form-select @error('ho_khau_id') is-invalid @enderror">
                            <option value="">-- Chọn hộ khẩu nhận --</option>
                            @foreach ($hoKhauList as $ho)
                                <option value="{{ $ho->id }}" @selected(old('ho_khau_id') == $ho->id)>
                                    Hộ: {{ $ho->ma_ho }} - Số sổ: {{ $ho->so_so_ho_khau }} (Chủ hộ: {{ $ho->chuHo?->ho_ten ?? 'Chưa xác định' }})
                                </option>
                            @endforeach
                        </select>
                        @error('ho_khau_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quan_he_chu_ho" class="form-label">Quan hệ với chủ hộ tiếp nhận <span class="text-danger">*</span></label>
                        <input type="text" id="quan_he_chu_ho" name="nhan_khau[quan_he_chu_ho]" value="{{ old('nhan_khau.quan_he_chu_ho', 'Thành viên') }}" class="form-control @error('nhan_khau.quan_he_chu_ho') is-invalid @enderror" placeholder="Vợ, con, chồng, cháu...">
                        @error('nhan_khau.quan_he_chu_ho')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-card-text me-1"></i>Văn bản quyết định
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ngay_bien_dong" class="form-label">Ngày chuyển đến <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bien_dong" name="ngay_bien_dong" value="{{ old('ngay_bien_dong', date('Y-m-d')) }}" class="form-control @error('ngay_bien_dong') is-invalid @enderror">
                            @error('ngay_bien_dong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="so_quyet_dinh" class="form-label">Số quyết định / Văn bản</label>
                            <input type="text" id="so_quyet_dinh" name="so_quyet_dinh" value="{{ old('so_quyet_dinh') }}" class="form-control @error('so_quyet_dinh') is-invalid @enderror" placeholder="VD: QĐ-12/UBND">
                            @error('so_quyet_dinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ly_do" class="form-label">Lý do chuyển đến</label>
                            <textarea id="ly_do" name="ly_do" rows="2" class="form-control @error('ly_do') is-invalid @enderror" placeholder="VD: Kết hôn, chuyển đến sinh sống lâu dài...">{{ old('ly_do') }}</textarea>
                            @error('ly_do')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="ghi_chu" class="form-label">Ghi chú</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="2" class="form-control">{{ old('ghi_chu') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thông tin nhân khẩu mới --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person-plus me-1"></i>Thông tin nhân khẩu nhập mới
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" id="ho_ten" name="nhan_khau[ho_ten]" value="{{ old('nhan_khau.ho_ten') }}" class="form-control @error('nhan_khau.ho_ten') is-invalid @enderror" placeholder="Nhập họ và tên...">
                            @error('nhan_khau.ho_ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cccd_cmnd" class="form-label">CCCD/CMND</label>
                            <input type="text" id="cccd_cmnd" name="nhan_khau[cccd_cmnd]" value="{{ old('nhan_khau.cccd_cmnd') }}" class="form-control @error('nhan_khau.cccd_cmnd') is-invalid @enderror" placeholder="Nhập số CCCD/CMND (nếu có)...">
                            @error('nhan_khau.cccd_cmnd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ngay_sinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_sinh" name="nhan_khau[ngay_sinh]" value="{{ old('nhan_khau.ngay_sinh') }}" class="form-control @error('nhan_khau.ngay_sinh') is-invalid @enderror">
                            @error('nhan_khau.ngay_sinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="gioi_tinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                            <select id="gioi_tinh" name="nhan_khau[gioi_tinh]" class="form-select @error('nhan_khau.gioi_tinh') is-invalid @enderror">
                                <option value="">-- Chọn giới tính --</option>
                                @foreach($gioiTinh as $val => $lbl)
                                    <option value="{{ $val }}" @selected(old('nhan_khau.gioi_tinh') == $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('nhan_khau.gioi_tinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="dan_toc" class="form-label">Dân tộc <span class="text-danger">*</span></label>
                            <input type="text" id="dan_toc" name="nhan_khau[dan_toc]" value="{{ old('nhan_khau.dan_toc', 'Kinh') }}" class="form-control @error('nhan_khau.dan_toc') is-invalid @enderror">
                            @error('nhan_khau.dan_toc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ton_giao" class="form-label">Tôn giáo</label>
                            <input type="text" id="ton_giao" name="nhan_khau[ton_giao]" value="{{ old('nhan_khau.ton_giao', 'Không') }}" class="form-control @error('nhan_khau.ton_giao') is-invalid @enderror">
                            @error('nhan_khau.ton_giao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trinh_do_hoc_van" class="form-label">Trình độ học vấn</label>
                            <select id="trinh_do_hoc_van" name="nhan_khau[trinh_do_hoc_van]" class="form-select @error('nhan_khau.trinh_do_hoc_van') is-invalid @enderror">
                                <option value="">-- Chọn trình độ --</option>
                                @foreach($trinhDoHocVan as $val => $lbl)
                                    <option value="{{ $val }}" @selected(old('nhan_khau.trinh_do_hoc_van') == $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('nhan_khau.trinh_do_hoc_van')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tinh_trang_hon_nhan" class="form-label">Tình trạng hôn nhân <span class="text-danger">*</span></label>
                            <select id="tinh_trang_hon_nhan" name="nhan_khau[tinh_trang_hon_nhan]" class="form-select @error('nhan_khau.tinh_trang_hon_nhan') is-invalid @enderror">
                                @foreach($tinhTrangHonNhan as $val => $lbl)
                                    <option value="{{ $val }}" @selected(old('nhan_khau.tinh_trang_hon_nhan', 'doc_than') == $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('nhan_khau.tinh_trang_hon_nhan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="que_quan" class="form-label">Quê quán</label>
                            <input type="text" id="que_quan" name="nhan_khau[que_quan]" value="{{ old('nhan_khau.que_quan') }}" class="form-control @error('nhan_khau.que_quan') is-invalid @enderror" placeholder="Xã/Huyện/Tỉnh...">
                            @error('nhan_khau.que_quan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="noi_sinh" class="form-label">Nơi sinh</label>
                            <input type="text" id="noi_sinh" name="nhan_khau[noi_sinh]" value="{{ old('nhan_khau.noi_sinh') }}" class="form-control @error('nhan_khau.noi_sinh') is-invalid @enderror" placeholder="Nhà thương/Bệnh viện/Quận/Tỉnh...">
                            @error('nhan_khau.noi_sinh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('bien-dong.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-success">Đăng ký Chuyển đến</button>
            </div>
        </div>
    </div>
</form>
@endsection
