<form method="POST" action="{{ $action }}" class="card shadow-sm border-0">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Vui lòng kiểm tra lại thông tin.</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">
            {{-- Chọn người lao động --}}
            <div class="col-lg-6">
                <label for="lao_dong_id" class="form-label fw-semibold">Người lao động (Chỉ hiện người thất nghiệp) <span class="text-danger">*</span></label>
                @if(isset($record))
                    <input type="hidden" name="lao_dong_id" id="lao_dong_id" value="{{ $record->lao_dong_id }}">
                    <input type="text" class="form-control" value="{{ $record->laoDong->nhanKhau->ho_ten }} - CCCD: {{ $record->laoDong->nhanKhau->cccd_cmnd ?? '—' }}" readonly disabled>
                @else
                    <select id="lao_dong_id" name="lao_dong_id" class="form-select @error('lao_dong_id') is-invalid @enderror" required>
                        <option value="">Chọn người lao động...</option>
                        @foreach ($laoDong as $ld)
                            <option value="{{ $ld['id'] }}" @selected((string) old('lao_dong_id', request('lao_dong_id')) === (string) $ld['id']) data-nganh="{{ $ld['nganh_nghe'] }}">
                                {{ $ld['ho_ten'] }} (CCCD: {{ $ld['cccd_cmnd'] }}) [{{ $ld['nganh_nghe'] }}]
                            </option>
                        @endforeach
                    </select>
                    @error('lao_dong_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @endif
                
                {{-- Khu vực hiển thị doanh nghiệp gợi ý --}}
                <div id="job-suggestions" class="mt-2 p-2 border rounded bg-light" style="display: none;">
                    <div class="small fw-semibold text-secondary mb-1"><i class="bi bi-magic text-warning"></i> Doanh nghiệp gợi ý (Cùng ngành nghề):</div>
                    <div id="job-suggestions-list" class="d-flex flex-wrap gap-1"></div>
                </div>
            </div>

            {{-- Chọn doanh nghiệp --}}
            <div class="col-lg-6">
                <label for="doanh_nghiep_id" class="form-label fw-semibold">Doanh nghiệp tuyển dụng <span class="text-danger">*</span></label>
                @if(isset($record))
                    <input type="hidden" name="doanh_nghiep_id" id="doanh_nghiep_id" value="{{ $record->doanh_nghiep_id }}">
                    <input type="text" class="form-control" value="{{ $record->doanhNghiep->ten_co_so }} - Tuyển dụng: {{ $record->doanhNghiep->so_vi_tri_tuyen_dung }} vị trí" readonly disabled>
                @else
                    <select id="doanh_nghiep_id" name="doanh_nghiep_id" class="form-select @error('doanh_nghiep_id') is-invalid @enderror" required>
                        <option value="">Chọn doanh nghiệp...</option>
                        @foreach ($doanhNghiep as $dn)
                            <option value="{{ $dn->id }}" @selected((string) old('doanh_nghiep_id', request('doanh_nghiep_id')) === (string) $dn->id) data-tuyendung="{{ $dn->so_vi_tri_tuyen_dung }}">
                                {{ $dn->ten_co_so }} (Ngành: {{ $dn->nganh_nghe_chinh ?? 'Khác' }}) [Tuyển: {{ $dn->so_vi_tri_tuyen_dung }}]
                            </option>
                        @endforeach
                    </select>
                    @error('doanh_nghiep_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @endif

                {{-- Khu vực hiển thị ứng viên gợi ý --}}
                <div id="labor-suggestions" class="mt-2 p-2 border rounded bg-light" style="display: none;">
                    <div class="small fw-semibold text-secondary mb-1"><i class="bi bi-magic text-warning"></i> Ứng viên gợi ý (Cùng lĩnh vực):</div>
                    <div id="labor-suggestions-list" class="d-flex flex-wrap gap-1"></div>
                </div>
            </div>

            {{-- Ngày kết nối --}}
            <div class="col-lg-4">
                <label for="ngay_ket_noi" class="form-label fw-semibold">Ngày giới thiệu / kết nối <span class="text-danger">*</span></label>
                <input type="date" id="ngay_ket_noi" name="ngay_ket_noi" value="{{ old('ngay_ket_noi', $record?->ngay_ket_noi ? $record->ngay_ket_noi->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control @error('ngay_ket_noi') is-invalid @enderror" required>
                @error('ngay_ket_noi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Vị trí giới thiệu --}}
            <div class="col-lg-4">
                <label for="vi_tri_gioi_thieu" class="form-label fw-semibold">Vị trí giới thiệu</label>
                <input type="text" id="vi_tri_gioi_thieu" name="vi_tri_gioi_thieu" value="{{ old('vi_tri_gioi_thieu', $record?->vi_tri_gioi_thieu) }}" class="form-control @error('vi_tri_gioi_thieu') is-invalid @enderror" placeholder="Ví dụ: Thợ may, Nhân viên bán hàng...">
                @error('vi_tri_gioi_thieu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Kết quả --}}
            <div class="col-lg-4">
                <label for="ket_qua" class="form-label fw-semibold">Kết quả kết nối <span class="text-danger">*</span></label>
                <select id="ket_qua" name="ket_qua" class="form-select @error('ket_qua') is-invalid @enderror" required>
                    @foreach ($ketQua as $value => $label)
                        <option value="{{ $value }}" @selected(old('ket_qua', $record?->ket_qua ?? 'dang_cho_phan_hoi') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('ket_qua')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Ghi chú --}}
            <div class="col-12 border-top pt-3">
                <label for="ghi_chu" class="form-label fw-semibold">Ghi chú thêm</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu', $record?->ghi_chu) }}</textarea>
                @error('ghi_chu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between py-3">
        <a href="{{ route('ket-noi.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button class="btn btn-success px-4" type="submit">{{ $submitLabel }}</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const laborSelect = document.getElementById('lao_dong_id');
        const enterpriseSelect = document.getElementById('doanh_nghiep_id');
        const jobSuggestions = document.getElementById('job-suggestions');
        const jobSuggestionsList = document.getElementById('job-suggestions-list');
        const laborSuggestions = document.getElementById('labor-suggestions');
        const laborSuggestionsList = document.getElementById('labor-suggestions-list');

        function fetchJobSuggestions(laborId) {
            if (!laborId) {
                jobSuggestions.style.display = 'none';
                return;
            }
            // Gọi AJAX route: /api/lao-dong/ho-so/{laoDong}/jobs
            fetch(`/api/lao-dong/ho-so/${laborId}/jobs`)
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data.length > 0) {
                        jobSuggestionsList.innerHTML = '';
                        result.data.forEach(job => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn btn-xs btn-outline-primary m-1';
                            btn.innerHTML = `${job.ten_co_so} (Còn ${job.so_vi_tri_tuyen_dung} vị trí)`;
                            btn.addEventListener('click', function() {
                                enterpriseSelect.value = job.id;
                                // Kích hoạt sự kiện change thủ công
                                enterpriseSelect.dispatchEvent(new Event('change'));
                            });
                            jobSuggestionsList.appendChild(btn);
                        });
                        jobSuggestions.style.display = 'block';
                    } else {
                        jobSuggestions.style.display = 'none';
                    }
                })
                .catch(() => {
                    jobSuggestions.style.display = 'none';
                });
        }

        function fetchLaborSuggestions(enterpriseId) {
            if (!enterpriseId) {
                laborSuggestions.style.display = 'none';
                return;
            }
            // Gọi AJAX route: /api/lao-dong/doanh-nghiep/{doanhNghiep}/labors
            fetch(`/api/lao-dong/doanh-nghiep/${enterpriseId}/labors`)
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data.length > 0) {
                        laborSuggestionsList.innerHTML = '';
                        result.data.forEach(labor => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn btn-xs btn-outline-success m-1';
                            btn.innerHTML = `${labor.ho_ten} (${labor.nganh_nghe})`;
                            btn.addEventListener('click', function() {
                                laborSelect.value = labor.id;
                                // Kích hoạt sự kiện change thủ công
                                laborSelect.dispatchEvent(new Event('change'));
                            });
                            laborSuggestionsList.appendChild(btn);
                        });
                        laborSuggestions.style.display = 'block';
                    } else {
                        laborSuggestions.style.display = 'none';
                    }
                })
                .catch(() => {
                    laborSuggestions.style.display = 'none';
                });
        }

        if (laborSelect) {
            laborSelect.addEventListener('change', function() {
                fetchJobSuggestions(laborSelect.value);
            });
            // Gọi ban đầu nếu đã chọn sẵn
            if (laborSelect.value) {
                fetchJobSuggestions(laborSelect.value);
            }
        }

        if (enterpriseSelect) {
            enterpriseSelect.addEventListener('change', function() {
                fetchLaborSuggestions(enterpriseSelect.value);
            });
            // Gọi ban đầu nếu đã chọn sẵn
            if (enterpriseSelect.value) {
                fetchLaborSuggestions(enterpriseSelect.value);
            }
        }
    });
</script>

<style>
    .btn-xs {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
        border-radius: 0.2rem;
    }
</style>
