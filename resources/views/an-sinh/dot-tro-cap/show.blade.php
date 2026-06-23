@extends('layouts.app')

@section('title', 'Đợt trợ cấp: ' . $record->ten_dot)
@section('page_title', 'Chi tiết đợt trợ cấp')

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

<div class="small text-secondary mb-1">
    <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
    <span class="mx-1">/</span>
    <a href="{{ route('dot-tro-cap.index') }}" class="text-decoration-none">Gói trợ cấp &amp; Quỹ từ thiện</a>
    <span class="mx-1">/</span>
    Chi tiết đợt trợ cấp
</div>
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dot-tro-cap.index') }}" class="btn-back" title="Quay lại danh sách">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-0">{{ $record->ten_dot }}</h2>
            <span class="badge text-bg-{{ $record->trangThaiBadgeColor() }} mt-1">
                {{ $record->trangThaiLabel() }}
            </span>
        </div>
    </div>
    <div class="d-flex gap-2">
        @can('manage_an_sinh')
        <a href="{{ route('dot-tro-cap.edit', $record) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa đợt
        </a>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRecipientModal">
            <i class="bi bi-person-plus me-1"></i> Thêm thủ công
        </button>
        @endcan
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('status') }}</div>
@endif



<div class="row g-4">
    <!-- Cột trái: Thông tin chiến dịch và thống kê -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-info-circle me-1"></i>Thông tin đợt trợ cấp</div>
            <div class="card-body p-0">
                <table class="table table-striped-columns align-middle mb-0 small">
                    <tbody>
                        <tr>
                            <th class="w-40 py-3 ps-3">Hình thức</th>
                            <td class="py-3 pe-3">{{ $record->loaiLabel() }}</td>
                        </tr>
                        <tr>
                            <th class="py-3 ps-3">Giá trị / Suất</th>
                            <td class="py-3 pe-3 fw-semibold text-danger">{{ number_format($record->gia_tri_quy_doi ?? 0, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        <tr>
                            <th class="py-3 ps-3">Nguồn kinh phí</th>
                            <td class="py-3 pe-3 text-secondary">{{ $record->nguon_kinh_phi ?? 'Ngân sách xã / Từ thiện' }}</td>
                        </tr>
                        <tr>
                            <th class="py-3 ps-3">Ngày bắt đầu</th>
                            <td class="py-3 pe-3">{{ $record->ngay_bat_dau_cap_phat->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="py-3 ps-3">Ngày kết thúc</th>
                            <td class="py-3 pe-3">{{ $record->ngay_ket_thuc_cap_phat ? $record->ngay_ket_thuc_cap_phat->format('d/m/Y') : 'Chưa đóng đợt' }}</td>
                        </tr>
                        <tr>
                            <th class="py-3 ps-3">Tổng giá trị dự kiến</th>
                            <td class="py-3 pe-3 fw-bold">{{ number_format(($record->tong_so_doi_tuong * $record->gia_tri_quy_doi), 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                            <th class="py-3 ps-3">Người tạo lập</th>
                            <td class="py-3 pe-3">{{ $record->nguoiTao?->name ?? 'Hệ thống' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($record->mo_ta)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-card-text me-1"></i>Mô tả / Chiến dịch</div>
            <div class="card-body small text-secondary">
                {!! nl2br(e($record->mo_ta)) !!}
            </div>
        </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-pie-chart me-1"></i>Tiến độ thực tế</div>
            <div class="card-body">
                @php
                    $percent = $record->tong_so_doi_tuong > 0 
                        ? ($record->so_da_nhan / $record->tong_so_doi_tuong) * 100 
                        : 0;
                @endphp
                <div class="text-center mb-3">
                    <h3 class="fw-bold text-success mb-1">{{ round($percent, 1) }}%</h3>
                    <p class="text-muted small mb-0">Đã phát thành công {{ $record->so_da_nhan }} / {{ $record->tong_so_doi_tuong }} suất</p>
                </div>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-secondary"><i class="bi bi-x-circle text-danger me-1"></i>Chưa nhận: {{ $record->tong_so_doi_tuong - $record->so_da_nhan }}</span>
                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Đã nhận: {{ $record->so_da_nhan }}</span>
                </div>
            </div>
        </div>

        <!-- Điều kiện ban đầu của đợt -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-funnel me-1"></i>Bộ tiêu chí quét</div>
            <div class="card-body small">
                @php
                    $dieuKien = $record->dieu_kien_doi_tuong ?? [];
                    $loaiBaoTroDK = $dieuKien['loai_bao_tro'] ?? [];
                    $loaiChinhSachDK = $dieuKien['loai_chinh_sach'] ?? [];
                    $thonXomDK = $dieuKien['thon_xom'] ?? [];
                @endphp
                @if(empty($loaiBaoTroDK) && empty($loaiChinhSachDK))
                    <p class="text-muted mb-0">Đợt trợ cấp được lập thủ công hoặc không áp dụng bộ lọc quét.</p>
                @else
                    @if(!empty($loaiBaoTroDK))
                        <div class="mb-2">
                            <strong>Đối tượng Bảo trợ:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($loaiBaoTroDK as $val)
                                    <li>{{ \App\Models\BaoTroXaHoi::LOAI_BAO_TRO[$val] ?? $val }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($loaiChinhSachDK))
                        <div class="mb-2">
                            <strong>Diện chính sách:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($loaiChinhSachDK as $val)
                                    <li>{{ \App\Models\DoiTuongChinhSach::LOAI_CHINH_SACH[$val] ?? $val }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($thonXomDK))
                        <div class="mb-0">
                            <strong>Thôn xóm áp dụng:</strong>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @foreach($thonXomDK as $thon)
                                    <span class="badge bg-light text-dark border">{{ $thon }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Cột phải: Danh sách người nhận, tìm kiếm, xác nhận -->
    <div class="col-lg-8">
        <!-- Bộ lọc danh sách người nhận -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold"><i class="bi bi-search me-1"></i>Tìm kiếm &amp; Lọc người nhận</div>
            <div class="card-body">
                <form method="GET" action="{{ route('dot-tro-cap.show', $record) }}" class="row g-2">
                    <div class="col-md-6">
                        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Tìm theo tên người nhận, CCCD, số hộ khẩu...">
                    </div>
                    <div class="col-md-4">
                        <select name="da_nhan" class="form-select">
                            <option value="">Trạng thái nhận (Tất cả)</option>
                            <option value="1" @selected(($filters['da_nhan'] ?? '') === '1')>Đã nhận</option>
                            <option value="0" @selected(($filters['da_nhan'] ?? '') === '0')>Chưa nhận</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">Tìm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng danh sách người nhận và batch-actions -->
        <form id="batchForm" method="POST" action="{{ route('dot-tro-cap.confirm-batch', $record) }}">
            @csrf
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span class="fw-semibold"><i class="bi bi-people me-1"></i>Danh sách đối tượng nhận</span>
                    <div class="d-flex gap-2">
                        @can('manage_an_sinh')
                        <button type="submit" id="btnBatchConfirm" class="btn btn-sm btn-outline-success d-none">
                            <i class="bi bi-check-all me-1"></i> Xác nhận đã nhận (Hàng loạt)
                        </button>
                        @endcan
                        <span class="badge text-bg-light">{{ $recipients->total() }} đối tượng</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    @can('manage_an_sinh')
                                    <th class="ps-3" style="width: 40px;">
                                        <input type="checkbox" id="checkAll" class="form-check-input">
                                    </th>
                                    @endcan
                                    <th>Đối tượng</th>
                                    <th>Phân loại</th>
                                    <th>Giá trị nhận</th>
                                    <th>Trạng thái</th>
                                    <th>Xác nhận bởi / Lúc</th>
                                    @can('manage_an_sinh')
                                    <th class="text-end pe-3">Thao tác</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recipients as $item)
                                    <tr>
                                        @can('manage_an_sinh')
                                        <td class="ps-3">
                                            @if(!$item->da_nhan)
                                                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="form-check-input checkItem">
                                            @else
                                                <input type="checkbox" class="form-check-input" disabled>
                                            @endif
                                        </td>
                                        @endcan
                                        <td>
                                            @if($item->ho_khau_id)
                                                <div class="fw-semibold text-primary"><i class="bi bi-house-door me-1"></i>{{ $item->recipent_name }}</div>
                                                <div class="small text-secondary">Thôn/Xóm: {{ $item->hoKhau?->thon_xom }}</div>
                                            @else
                                                <div class="fw-semibold"><i class="bi bi-person me-1"></i>{{ $item->recipent_name }}</div>
                                                <div class="small text-secondary">
                                                    CCCD: {{ $item->nhanKhau?->cccd_cmnd ?? 'Chưa cập nhật' }} 
                                                    @if($item->nhanKhau?->hoKhau)
                                                        | Thôn: {{ $item->nhanKhau->hoKhau->thon_xom }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $item->recipient_type }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ number_format($item->gia_tri_nhan, 0, ',', '.') }}đ</div>
                                            @if($item->so_suat > 1)
                                                <div class="small text-muted">Số suất: {{ $item->so_suat }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge text-bg-{{ $item->da_nhan ? 'success' : 'danger' }}">
                                                {{ $item->da_nhan ? 'Đã nhận' : 'Chưa nhận' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->da_nhan)
                                                <div class="small fw-semibold">{{ $item->nguoiXacNhan?->name ?? 'Cán bộ' }}</div>
                                                <div class="small text-secondary">{{ $item->thoi_gian_nhan?->format('H:i d/m/Y') }}</div>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        @can('manage_an_sinh')
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end gap-1">
                                                @if(!$item->da_nhan)
                                                    <button type="button" class="btn btn-sm btn-success px-2 py-1 btnConfirmOne" data-url="{{ route('dot-tro-cap.confirm', [$record, $item->id]) }}" title="Đã nhận quà">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-action-delete px-2 py-1 btnRemoveRecip" data-url="{{ route('dot-tro-cap.remove-recipient', [$record, $item->id]) }}" title="Xóa khỏi danh sách">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                                            Chưa có đối tượng thụ hưởng nào trong danh sách.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($recipients->hasPages())
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <span class="small text-secondary">Trang {{ $recipients->currentPage() }} / {{ $recipients->lastPage() }}</span>
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-secondary {{ $recipients->onFirstPage() ? 'disabled' : '' }}" href="{{ $recipients->previousPageUrl() ?? '#' }}">Trước</a>
                            <a class="btn btn-outline-secondary {{ $recipients->hasMorePages() ? '' : 'disabled' }}" href="{{ $recipients->nextPageUrl() ?? '#' }}">Sau</a>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Modal Thêm đối tượng thủ công -->
@can('manage_an_sinh')
<div class="modal fade" id="addRecipientModal" tabindex="-1" aria-labelledby="addRecipientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('dot-tro-cap.add-recipient', $record) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addRecipientModalLabel"><i class="bi bi-person-plus me-1 text-success"></i>Thêm đối tượng thụ hưởng thủ công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Loại đối tượng nhận</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeNhanKhau" value="nhan_khau" checked>
                                <label class="form-check-label" for="typeNhanKhau">
                                    Cá nhân (Nhân khẩu)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeHoKhau" value="ho_khau">
                                <label class="form-check-label" for="typeHoKhau">
                                    Hộ gia đình
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Chọn cá nhân -->
                    <div class="mb-3" id="selectNhanKhauGroup">
                        <label for="nhan_khau_id" class="form-label fw-semibold">Chọn cá nhân</label>
                        <select name="nhan_khau_id" id="nhan_khau_id" class="form-select select2-autocomplete" style="width: 100%;">
                            <option value="">-- Tìm kiếm &amp; Chọn cá nhân --</option>
                            @foreach($allNhanKhau as $nk)
                                <option value="{{ $nk->id }}">{{ $nk->ho_ten }} (CCCD: {{ $nk->cccd_cmnd ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chọn hộ gia đình -->
                    <div class="mb-3 d-none" id="selectHoKhauGroup">
                        <label for="ho_khau_id" class="form-label fw-semibold">Chọn hộ gia đình</label>
                        <select name="ho_khau_id" id="ho_khau_id" class="form-select select2-autocomplete" style="width: 100%;">
                            <option value="">-- Tìm kiếm &amp; Chọn hộ gia đình --</option>
                            @foreach($allHoKhau as $hk)
                                <option value="{{ $hk['id'] }}">{{ $hk['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="so_suat" class="form-label fw-semibold">Số suất nhận</label>
                            <input type="number" id="so_suat" name="so_suat" value="1" min="1" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="gia_tri_nhan" class="form-label fw-semibold">Giá trị (VNĐ)</label>
                            <input type="number" id="gia_tri_nhan" name="gia_tri_nhan" value="{{ $record->gia_tri_quy_doi ?? 0 }}" min="0" class="form-control">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="recipient_ghi_chu" class="form-label fw-semibold">Ghi chú</label>
                        <input type="text" id="recipient_ghi_chu" name="ghi_chu" class="form-control" placeholder="Lý do bổ sung (Ví dụ: Hoàn cảnh đặc biệt phát sinh...)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Bổ sung vào đợt</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Forms ẩn để gửi Request (Xác nhận, Xóa) -->
<form id="actionForm" method="POST" action="" class="d-none">
    @csrf
</form>

<form id="deleteForm" method="POST" action="" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endcan

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle giữa cá nhân và hộ gia đình trong modal thêm thủ công
        const typeNhanKhau = document.getElementById('typeNhanKhau');
        const typeHoKhau = document.getElementById('typeHoKhau');
        const selectNhanKhauGroup = document.getElementById('selectNhanKhauGroup');
        const selectHoKhauGroup = document.getElementById('selectHoKhauGroup');
        const nhanKhauSelect = document.getElementById('nhan_khau_id');
        const hoKhauSelect = document.getElementById('ho_khau_id');

        if (typeNhanKhau && typeHoKhau) {
            typeNhanKhau.addEventListener('change', function() {
                if(this.checked) {
                    selectNhanKhauGroup.classList.remove('d-none');
                    selectHoKhauGroup.classList.add('d-none');
                    nhanKhauSelect.setAttribute('required', 'required');
                    hoKhauSelect.removeAttribute('required');
                }
            });

            typeHoKhau.addEventListener('change', function() {
                if(this.checked) {
                    selectNhanKhauGroup.classList.add('d-none');
                    selectHoKhauGroup.classList.remove('d-none');
                    hoKhauSelect.setAttribute('required', 'required');
                    nhanKhauSelect.removeAttribute('required');
                }
            });
            
            // Set initial state
            nhanKhauSelect.setAttribute('required', 'required');
        }

        // JS cho chọn checkbox hàng loạt
        const checkAll = document.getElementById('checkAll');
        const checkItems = document.querySelectorAll('.checkItem');
        const btnBatchConfirm = document.getElementById('btnBatchConfirm');

        function updateBatchButtonVisibility() {
            if (!btnBatchConfirm) return;
            const checkedCount = document.querySelectorAll('.checkItem:checked').length;
            if (checkedCount > 0) {
                btnBatchConfirm.classList.remove('d-none');
                btnBatchConfirm.innerHTML = `<i class="bi bi-check-all me-1"></i> Xác nhận đã nhận (${checkedCount} mục)`;
            } else {
                btnBatchConfirm.classList.add('d-none');
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkItems.forEach(item => {
                    item.checked = this.checked;
                });
                updateBatchButtonVisibility();
            });
        }

        checkItems.forEach(item => {
            item.addEventListener('change', function() {
                if (!this.checked && checkAll) {
                    checkAll.checked = false;
                } else if (checkAll && document.querySelectorAll('.checkItem:checked').length === checkItems.length) {
                    checkAll.checked = true;
                }
                updateBatchButtonVisibility();
            });
        });

        // Xác nhận nhận cho một cá nhân/hộ
        const btnConfirmOnes = document.querySelectorAll('.btnConfirmOne');
        const actionForm = document.getElementById('actionForm');

        btnConfirmOnes.forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                if (confirm('Xác nhận đối tượng này đã nhận đủ tiền/quà của đợt trợ cấp?')) {
                    actionForm.setAttribute('action', url);
                    actionForm.submit();
                }
            });
        });

        // Xóa đối tượng khỏi danh sách
        const btnRemoveRecips = document.querySelectorAll('.btnRemoveRecip');
        const deleteForm = document.getElementById('deleteForm');

        btnRemoveRecips.forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                if (confirm('Bạn có chắc muốn xóa đối tượng này khỏi danh sách nhận trợ cấp?')) {
                    deleteForm.setAttribute('action', url);
                    deleteForm.submit();
                }
            });
        });
    });
</script>
@endsection
