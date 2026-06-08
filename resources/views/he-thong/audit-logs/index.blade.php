@extends('layouts.app')

@section('title', 'Nhật ký hệ thống (Audit Log)')
@section('page_title', 'Nhật ký hệ thống')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        Nhật ký hệ thống (Audit Log)
    </div>
    <h2 class="fw-bold mb-0 text-success">
        <i class="bi bi-clock-history me-2"></i>Nhật ký hệ thống
    </h2>
    <p class="text-secondary mb-0">Giám sát hành động của cán bộ, lịch sử thay đổi dữ liệu phục vụ mục đích bảo mật thông tin công dân.</p>
</div>

{{-- Bộ lọc tìm kiếm --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label small fw-semibold text-secondary">Từ khóa tìm kiếm</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-secondary"></i></span>
                    <input type="text" id="search" name="search" class="form-control border-start-0" placeholder="Mô tả, IP, cán bộ..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-2">
                <label for="user_id" class="form-label small fw-semibold text-secondary">Cán bộ thực hiện</label>
                <select id="user_id" name="user_id" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="action" class="form-label small fw-semibold text-secondary">Hành động</label>
                <select id="action" name="action" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach ($actions as $key => $label)
                        <option value="{{ $key }}" @selected(request('action') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="module" class="form-label small fw-semibold text-secondary">Phân hệ / Module</label>
                <select id="module" name="module" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach ($logModules as $m)
                        <option value="{{ $m }}" @selected(request('module') === $m)>{{ Str::headline($m) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Khoảng thời gian</label>
                <div class="input-group">
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    <span class="input-group-text bg-light text-secondary">-</span>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                @if(request()->anyFilled(['search', 'user_id', 'action', 'module', 'from_date', 'to_date']))
                    <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
                        <i class="bi bi-x-circle"></i> Xóa bộ lọc
                    </a>
                @endif
                <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1">
                    <i class="bi bi-funnel"></i> Lọc nhật ký
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Bảng Nhật ký --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 15%">Thời điểm</th>
                        <th style="width: 15%">Cán bộ thực hiện</th>
                        <th style="width: 12%">Địa chỉ IP</th>
                        <th style="width: 12%">Hành động</th>
                        <th style="width: 15%">Module tác động</th>
                        <th>Nội dung chi tiết</th>
                        <th class="text-end pe-4" style="width: 10%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold text-dark">{{ $log->created_at->format('H:i:s') }}</div>
                                <small class="text-secondary">{{ $log->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                        {{ substr($log->user_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $log->user_name }}</div>
                                        <small class="text-secondary">ID: {{ $log->user_id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="bi bi-laptop me-1"></i>{{ $log->ip_address }}</span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'create' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                        'update' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
                                        'delete' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                        'login' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                        'logout' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
                                        'export' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                    ][$log->action] ?? 'bg-secondary bg-opacity-10 text-secondary';
                                    
                                    $actionLabel = $actions[$log->action] ?? Str::headline($log->action);
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $actionLabel }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    {{ Str::headline($log->module) }}
                                </span>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 380px;" title="{{ $log->mo_ta }}">
                                    {{ $log->mo_ta }}
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('audit-logs.show', $log) }}" class="btn btn-sm btn-outline-success" title="Xem chi tiết thay đổi">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-clock-history display-4 d-block mb-3 opacity-25"></i>
                                Không tìm thấy ghi chép nhật ký hệ thống nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($logs->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
