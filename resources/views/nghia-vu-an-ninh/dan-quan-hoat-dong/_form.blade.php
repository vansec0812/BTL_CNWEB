<form method="POST" action="{{ $action }}" class="card shadow-sm border-0" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        <div class="row g-3">
            <!-- Chọn dân quân tự vệ -->
            <div class="col-lg-6">
                <label for="dan_quan_tu_ve_id" class="form-label fw-semibold text-dark">Họ tên dân quân <span class="text-danger">*</span></label>
                @if(isset($record) || ($isReadOnly ?? false))
                    @php
                        $militiaMember = $record->danQuanTuVe;
                    @endphp
                    <input type="text" class="form-control" value="{{ $militiaMember->nhanKhau->ho_ten }} - CCCD: {{ $militiaMember->nhanKhau->cccd_cmnd ?? 'Chưa có' }} (Sinh ngày: {{ $militiaMember->nhanKhau->ngay_sinh ? $militiaMember->nhanKhau->ngay_sinh->format('d/m/Y') : '—' }})" disabled>
                    <input type="hidden" name="dan_quan_tu_ve_id" value="{{ $record->dan_quan_tu_ve_id }}">
                @else
                    <select id="dan_quan_tu_ve_id" name="dan_quan_tu_ve_id" class="form-select @error('dan_quan_tu_ve_id') is-invalid @enderror" required>
                        <option value="">-- Chọn dân quân tự vệ đang phục vụ --</option>
                        @foreach ($militia as $item)
                            <option value="{{ $item->id }}" @selected((string) old('dan_quan_tu_ve_id') === (string) $item->id)>
                                {{ $item->nhanKhau->ho_ten }} - CCCD: {{ $item->nhanKhau->cccd_cmnd ?? 'Chưa có' }} (Sinh ngày: {{ $item->nhanKhau->ngay_sinh ? $item->nhanKhau->ngay_sinh->format('d/m/Y') : '—' }})
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('dan_quan_tu_ve_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Loại hoạt động -->
            <div class="col-lg-3">
                <label for="loai_hoat_dong" class="form-label fw-semibold text-dark">Loại hoạt động <span class="text-danger">*</span></label>
                <select id="loai_hoat_dong" name="loai_hoat_dong" class="form-select @error('loai_hoat_dong') is-invalid @enderror" required @disabled($isReadOnly ?? false)>
                    <option value="">-- Chọn loại --</option>
                    @foreach ($loaiHoatDong as $value => $label)
                        <option value="{{ $value }}" @selected(old('loai_hoat_dong', $record?->loai_hoat_dong) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('loai_hoat_dong')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Ngày thực hiện -->
            <div class="col-lg-3">
                <label for="ngay_thuc_hien" class="form-label fw-semibold text-dark">Ngày thực hiện <span class="text-danger">*</span></label>
                <input type="date" id="ngay_thuc_hien" name="ngay_thuc_hien" value="{{ old('ngay_thuc_hien', $record?->ngay_thuc_hien?->format('Y-m-d') ?? date('Y-m-d')) }}" class="form-control @error('ngay_thuc_hien') is-invalid @enderror" required @disabled($isReadOnly ?? false)>
                @error('ngay_thuc_hien')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Tên hoạt động -->
            <div class="col-lg-6">
                <label for="ten_hoat_dong" class="form-label fw-semibold text-dark">Tên hoạt động <span class="text-danger">*</span></label>
                <input type="text" id="ten_hoat_dong" name="ten_hoat_dong" value="{{ old('ten_hoat_dong', $record?->ten_hoat_dong) }}" class="form-control @error('ten_hoat_dong') is-invalid @enderror" placeholder="Ví dụ: Tập huấn quân sự đợt 1 / Trực gác chốt A" required @disabled($isReadOnly ?? false)>
                @error('ten_hoat_dong')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Trạng thái -->
            <div class="col-lg-6">
                <label for="trang_thai" class="form-label fw-semibold text-dark">Trạng thái <span class="text-danger">*</span></label>
                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required @disabled($isReadOnly ?? false)>
                    <!-- Options populated via JS -->
                </select>
                @error('trang_thai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Ghi chú -->
            <div class="col-12">
                <label for="ghi_chu" class="form-label fw-semibold text-dark">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror" @disabled($isReadOnly ?? false)>{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-white d-flex justify-content-between py-3">
        <a href="{{ route('dan-quan-hoat-dong.index') }}" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
        @if (!($isReadOnly ?? false))
            <button class="btn btn-success fw-semibold px-4" type="submit"><i class="bi bi-check-lg me-1"></i>{{ $submitLabel }}</button>
        @else
            @can('manage_nghia_vu')
                <a href="{{ route('dan-quan-hoat-dong.edit', $record) }}" class="btn btn-success fw-semibold px-4"><i class="bi bi-pencil me-1"></i>Chỉnh sửa</a>
            @endcan
        @endif
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loaiSelect = document.getElementById('loai_hoat_dong');
    const trangThaiSelect = document.getElementById('trang_thai');

    const statusOptions = {
        'tap_huan': [
            { value: 'tham_gia', label: 'Tham gia' },
            { value: 'vang_co_phep', label: 'Vắng có phép' },
            { value: 'vang_khong_phep', label: 'Vắng không phép' }
        ],
        'truc_ban': [
            { value: 'da_truc', label: 'Đã trực' },
            { value: 'vang_mat', label: 'Vắng mặt' }
        ]
    };

    const currentTrangThai = @json(old('trang_thai', $record?->trang_thai ?? ''));

    function updateTrangThaiOptions() {
        const selectedLoai = loaiSelect.value;
        trangThaiSelect.innerHTML = '';

        if (selectedLoai && statusOptions[selectedLoai]) {
            statusOptions[selectedLoai].forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.label;
                if (opt.value === currentTrangThai) {
                    option.selected = true;
                }
                trangThaiSelect.appendChild(option);
            });
            trangThaiSelect.disabled = @json($isReadOnly ?? false);
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = '-- Chọn loại hoạt động trước --';
            trangThaiSelect.appendChild(option);
            trangThaiSelect.disabled = true;
        }
    }

    if (loaiSelect) {
        loaiSelect.addEventListener('change', updateTrangThaiOptions);
        updateTrangThaiOptions();
    }
});
</script>
