@extends('layouts.app')

@section('title', 'Chi tiết nhật ký hệ thống')
@section('page_title', 'Chi tiết nhật ký')

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
    .data-diff-table td {
        vertical-align: middle;
    }
    .value-removed {
        background-color: #f8d7da;
        color: #842029;
        text-decoration: line-through;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.875em;
    }
    .value-added {
        background-color: #d1e7dd;
        color: #0f5132;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.875em;
    }
    .value-normal {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.875em;
        color: #495057;
    }
</style>

<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('audit-logs.index') }}" class="text-decoration-none">Nhật ký hệ thống</a>
        <span class="mx-1">/</span>
        Chi tiết ghi nhận #{{ $auditLog->id }}
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('audit-logs.index') }}" class="btn-back" title="Quay lại danh sách">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0 text-success">Chi tiết nhật ký thao tác</h2>
    </div>
</div>

<div class="row g-4">
    {{-- Thông tin cán bộ & hành động --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white fw-bold text-success border-0 pt-3">
                <i class="bi bi-info-circle me-1"></i>Thông tin chung
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="small text-secondary d-block">Thời điểm ghi nhận</label>
                    <span class="fw-bold text-dark">{{ $auditLog->created_at->format('H:i:s - d/m/Y') }}</span>
                    <small class="text-secondary d-block">({{ $auditLog->created_at->diffForHumans() }})</small>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary d-block">Cán bộ thực hiện</label>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.95rem;">
                            {{ substr($auditLog->user_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $auditLog->user_name }}</div>
                            <small class="text-secondary">Tài khoản ID: {{ $auditLog->user_id ?? 'Hệ thống tự động' }}</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary d-block">Môi trường & Thiết bị</label>
                    <div class="mt-1">
                        <span class="badge bg-light text-dark border d-inline-flex align-items-center gap-1 mb-1" title="Địa chỉ IP">
                            <i class="bi bi-pc-display"></i> IP: {{ $auditLog->ip_address }}
                        </span>
                        <div class="small text-secondary mt-1 text-break" style="font-size: 0.75rem;">
                            <strong>User Agent:</strong> {{ $auditLog->user_agent }}
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary d-block">Phân hệ / Module tác động</label>
                    <span class="badge bg-success bg-opacity-10 text-success fs-7 mt-1">
                        {{ Str::headline($auditLog->module) }}
                    </span>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary d-block">Đối tượng / Model liên kết</label>
                    <span class="value-normal text-break d-block mt-1">
                        {{ $auditLog->model_class }} (ID: {{ $auditLog->model_id ?? 'N/A' }})
                    </span>
                </div>

                <div>
                    <label class="small text-secondary d-block">Mô tả hành động</label>
                    <div class="p-2 bg-light rounded mt-1 border-start border-success border-3 fw-medium">
                        {{ $auditLog->mo_ta }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lịch sử thay đổi dữ liệu --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold text-success border-0 pt-3 d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-database-gear me-1"></i>Lịch sử biến động dữ liệu công dân
                </span>
                <div>
                    @php
                        $badgeClass = [
                            'create' => 'bg-success',
                            'update' => 'bg-info',
                            'delete' => 'bg-danger',
                            'login' => 'bg-primary',
                            'logout' => 'bg-secondary',
                            'export' => 'bg-warning text-dark',
                        ][$auditLog->action] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $badgeClass }} text-uppercase">{{ $auditLog->action }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($auditLog->action === 'update')
                    <p class="text-secondary small mb-3">Bảng bên dưới hiển thị các trường dữ liệu bị thay đổi trong quá trình cập nhật.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped data-diff-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%">Trường thông tin</th>
                                    <th style="width: 37%">Giá trị trước thay đổi (Cũ)</th>
                                    <th style="width: 38%">Giá trị sau thay đổi (Mới)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hasDiff = false;
                                    $oldData = $auditLog->gia_tri_cu ?? [];
                                    $newData = $auditLog->gia_tri_moi ?? [];
                                @endphp

                                @foreach($newData as $key => $newValue)
                                    @php
                                        $hasDiff = true;
                                        $oldValue = $oldData[$key] ?? null;
                                        
                                        // Format display values
                                        $displayOld = is_bool($oldValue) ? ($oldValue ? 'true' : 'false') : (is_array($oldValue) ? json_encode($oldValue, JSON_UNESCAPED_UNICODE) : $oldValue);
                                        $displayNew = is_bool($newValue) ? ($newValue ? 'true' : 'false') : (is_array($newValue) ? json_encode($newValue, JSON_UNESCAPED_UNICODE) : $newValue);
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ Str::headline($key) }}</strong>
                                            <code class="d-block text-secondary" style="font-size: 0.75rem;">{{ $key }}</code>
                                        </td>
                                        <td>
                                            @if($oldValue === null)
                                                <span class="text-muted small"><em>(Trống / Null)</em></span>
                                            @else
                                                <span class="value-removed">{{ $displayOld }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($newValue === null)
                                                <span class="text-muted small"><em>(Trống / Null)</em></span>
                                            @else
                                                <span class="value-added">{{ $displayNew }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                @if(!$hasDiff)
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-secondary">
                                            Không phát hiện thay đổi cụ thể hoặc giá trị thay đổi không có sự khác biệt thực tế.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @elseif($auditLog->action === 'create' && is_array($auditLog->gia_tri_moi))
                    <p class="text-success small mb-3"><i class="bi bi-plus-circle-fill me-1"></i>Bản ghi được thêm mới với các thông tin ban đầu sau:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped data-diff-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%">Trường thông tin</th>
                                    <th>Giá trị khởi tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLog->gia_tri_moi as $key => $value)
                                    @php
                                        if ($value === null || $value === '') continue;
                                        $displayVal = is_bool($value) ? ($value ? 'Có / Đúng' : 'Không / Sai') : (is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value);
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ Str::headline($key) }}</strong>
                                            <code class="d-block text-secondary" style="font-size: 0.75rem;">{{ $key }}</code>
                                        </td>
                                        <td>
                                            <span class="value-added">{{ $displayVal }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif($auditLog->action === 'delete' && is_array($auditLog->gia_tri_cu))
                    <p class="text-danger small mb-3"><i class="bi bi-trash-fill me-1"></i>Bản ghi đã bị xóa. Dưới đây là snapshot dữ liệu trước khi xóa:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped data-diff-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%">Trường thông tin</th>
                                    <th>Giá trị trước khi xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLog->gia_tri_cu as $key => $value)
                                    @php
                                        if ($value === null || $value === '') continue;
                                        $displayVal = is_bool($value) ? ($value ? 'Có / Đúng' : 'Không / Sai') : (is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value);
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ Str::headline($key) }}</strong>
                                            <code class="d-block text-secondary" style="font-size: 0.75rem;">{{ $key }}</code>
                                        </td>
                                        <td>
                                            <span class="value-removed">{{ $displayVal }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-shield-lock display-4 d-block mb-3 opacity-25"></i>
                        Không có dữ liệu thay đổi nào được lưu lại cho hành động này (ví dụ: hành động đăng nhập, đăng xuất hoặc xuất báo cáo).
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
