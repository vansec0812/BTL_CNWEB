@extends('layouts.app')

@section('title', 'Sửa đợt trợ cấp: ' . $record->ten_dot)
@section('page_title', 'Sửa đợt trợ cấp')

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
    .criteria-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }
</style>

<div class="small text-secondary mb-1">
    <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
    <span class="mx-1">/</span>
    <a href="{{ route('dot-tro-cap.index') }}" class="text-decoration-none">Gói trợ cấp &amp; Quỹ từ thiện</a>
    <span class="mx-1">/</span>
    Sửa đợt trợ cấp
</div>
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('dot-tro-cap.index') }}" class="btn-back" title="Quay lại danh sách">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="fw-bold mb-0">Sửa đợt trợ cấp: {{ $record->ten_dot }}</h2>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <p class="fw-semibold mb-1">Có lỗi xảy ra, vui lòng kiểm tra lại:</p>
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('dot-tro-cap.update', $record) }}">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Cấu hình thông tin cơ bản -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-info-circle me-1"></i>Thông tin cơ bản</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="ten_dot" class="form-label">Tên đợt trợ cấp <span class="text-danger">*</span></label>
                        <input type="text" id="ten_dot" name="ten_dot" value="{{ old('ten_dot', $record->ten_dot) }}" class="form-control" placeholder="Ví dụ: Phát quà Tết Nguyên Đán Ất Tỵ 2025" required>
                    </div>

                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả chi tiết</label>
                        <textarea id="mo_ta" name="mo_ta" class="form-control" rows="3" placeholder="Nhập mục đích, thông điệp cứu trợ hoặc thông tin thêm về đợt trợ cấp này...">{{ old('mo_ta', $record->mo_ta) }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="loai_tro_cap" class="form-label">Hình thức trợ cấp <span class="text-danger">*</span></label>
                            <select id="loai_tro_cap" name="loai_tro_cap" class="form-select" required>
                                @foreach ($loaiTroCap as $value => $label)
                                    <option value="{{ $value }}" @selected(old('loai_tro_cap', $record->loai_tro_cap) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="gia_tri_quy_doi" class="form-label">Giá trị quy đổi/suất (VNĐ)</label>
                            <input type="number" id="gia_tri_quy_doi" name="gia_tri_quy_doi" value="{{ old('gia_tri_quy_doi', $record->gia_tri_quy_doi) }}" class="form-control" min="0" placeholder="Ví dụ: 500000">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nguon_kinh_phi" class="form-label">Nguồn kinh phí</label>
                        <input type="text" id="nguon_kinh_phi" name="nguon_kinh_phi" value="{{ old('nguon_kinh_phi', $record->nguon_kinh_phi) }}" class="form-control" placeholder="Ví dụ: Quỹ Vì Người Nghèo xã, ngân sách huyện, Mạnh thường quân tài trợ...">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ngay_bat_dau_cap_phat" class="form-label">Ngày bắt đầu cấp phát <span class="text-danger">*</span></label>
                            <input type="date" id="ngay_bat_dau_cap_phat" name="ngay_bat_dau_cap_phat" value="{{ old('ngay_bat_dau_cap_phat', $record->ngay_bat_dau_cap_phat?->format('Y-m-d')) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ngay_ket_thuc_cap_phat" class="form-label">Ngày kết thúc (Dự kiến)</label>
                            <input type="date" id="ngay_ket_thuc_cap_phat" name="ngay_ket_thuc_cap_phat" value="{{ old('ngay_ket_thuc_cap_phat', $record->ngay_ket_thuc_cap_phat?->format('Y-m-d')) }}" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select id="trang_thai" name="trang_thai" class="form-select" required>
                            @foreach ($trangThai as $value => $label)
                                <option value="{{ $value }}" @selected(old('trang_thai', $record->trang_thai) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="ghi_chu" class="form-label">Ghi chú</label>
                        <textarea id="ghi_chu" name="ghi_chu" class="form-control" rows="2" placeholder="Ghi chú nội bộ nếu có...">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cấu hình điều kiện đối tượng hưởng -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-white fw-semibold text-danger"><i class="bi bi-funnel me-1"></i>Điều kiện đối tượng được hưởng</div>
                <div class="card-body">
                    @if($record->trang_thai !== 'sap_dien_ra')
                        <div class="alert alert-warning border-0 small mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Đợt trợ cấp này **không ở trạng thái Sắp diễn ra**. 
                            Việc thay đổi điều kiện dưới đây sẽ **không tự động quét lại** danh sách người nhận để tránh mất mát dữ liệu thực tế. 
                            Nếu muốn xóa danh sách cũ và quét lại hoàn toàn, hãy tích vào ô ở dưới cùng.
                        </div>
                    @else
                        <div class="alert alert-info border-0 small mb-3">
                            <i class="bi bi-info-circle-fill me-1"></i> Đợt trợ cấp này ở trạng thái **Sắp diễn ra**. Thay đổi điều kiện và lưu sẽ tự động cập nhật lại danh sách đối tượng nhận (chỉ những đối tượng chưa xác nhận nhận quà sẽ bị cập nhật).
                        </div>
                    @endif

                    <!-- Bảo trợ xã hội -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark"><i class="bi bi-heartbreak text-danger me-1"></i>Đối tượng Bảo trợ xã hội</h6>
                        <div class="criteria-card p-3">
                            @foreach($loaiBaoTro as $value => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="loai_bao_tro[]" value="{{ $value }}" id="bt_{{ $value }}" @checked(in_array($value, old('loai_bao_tro', $selectedBaoTro)))>
                                    <label class="form-check-label small" for="bt_{{ $value }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Diện chính sách -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark"><i class="bi bi-award text-success me-1"></i>Đối tượng Diện chính sách</h6>
                        <div class="criteria-card p-3">
                            @foreach($loaiChinhSach as $value => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="loai_chinh_sach[]" value="{{ $value }}" id="cs_{{ $value }}" @checked(in_array($value, old('loai_chinh_sach', $selectedChinhSach)))>
                                    <label class="form-check-label small" for="cs_{{ $value }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Lọc địa bàn Thôn/Xóm -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark"><i class="bi bi-geo-alt text-primary me-1"></i>Giới hạn địa bàn (Thôn / Xóm)</h6>
                        <p class="text-muted small mb-2">Nếu không chọn, hệ thống mặc định quét trên toàn địa bàn xã.</p>
                        <div class="criteria-card p-3" style="max-height: 200px; overflow-y: auto;">
                            @foreach($thonXoms as $thon)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="thon_xom[]" value="{{ $thon }}" id="thon_{{ md5($thon) }}" @checked(in_array($thon, old('thon_xom', $selectedThonXom)))>
                                    <label class="form-check-label small" for="thon_{{ md5($thon) }}">
                                        {{ $thon }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($record->trang_thai !== 'sap_dien_ra')
                    <div class="form-check p-3 border border-warning rounded bg-warning bg-opacity-10 mb-0">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="refresh_recipients" value="1" id="refresh_recipients">
                        <label class="form-check-label small text-warning-emphasis fw-semibold" for="refresh_recipients">
                            <i class="bi bi-arrow-repeat me-1"></i> BẮT BUỘC QUÉT LẠI DANH SÁCH ĐỐI TƯỢNG HƯỞNG
                        </label>
                        <div class="text-muted small mt-1 ps-0">Chú ý: Tích chọn ô này sẽ xóa toàn bộ danh sách người nhận hiện tại của đợt trợ cấp này (kể cả những người đã ký nhận) và thực hiện quét lại theo điều kiện mới.</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('dot-tro-cap.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Cập nhật thông tin</button>
    </div>
</form>
@endsection
