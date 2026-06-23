@extends('layouts.app')

@section('title', 'Hệ thống, Tiện ích & Báo cáo')
@section('page_title', 'Hệ thống, Tiện ích & Báo cáo')

@section('content')
{{-- Thống kê nhanh --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people-fill stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Người dùng hệ thống</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['nguoi_dung'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-journal-check stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Nhật ký hệ thống</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['audit_log'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-file-earmark-bar-graph stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Mẫu báo cáo</p>
                    <h4 class="mb-0 fw-bold">6</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-database-check stat-icon"></i>
                <div>
                    <p class="text-muted small mb-0">Trạng thái hệ thống</p>
                    <h4 class="mb-0 fw-bold text-success">
                        <i class="bi bi-check-circle-fill"></i> {{ strtoupper(app()->environment()) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-list-check me-1"></i>Danh mục chức năng</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-circle p-1"><i class="bi bi-shield-lock small"></i></span>
                        Phân quyền (RBAC)
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-circle p-1"><i class="bi bi-clock-history small"></i></span>
                        Nhật ký hệ thống (Audit)
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-circle p-1"><i class="bi bi-graph-up small"></i></span>
                        Dashboard &amp; biểu đồ
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-circle p-1"><i class="bi bi-funnel small"></i></span>
                        Bộ lọc động &amp; tìm kiếm
                    </li>
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-circle p-1"><i class="bi bi-gear small"></i></span>
                        Cấu hình hệ thống
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-clock-history me-1"></i>Nhật ký hoạt động gần đây</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Người dùng</th>
                                <th>Hành động</th>
                                <th>Module</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dsAuditLog ?? [] as $log)
                            <tr>
                                <td class="text-muted small">{{ $log->created_at ?? '—' }}</td>
                                <td>{{ $log->user_name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->action === 'create' ? 'success' : ($log->action === 'update' ? 'info' : 'secondary') }}">
                                        {{ $log->action ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $log->module ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Chưa có nhật ký hoạt động.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-file-earmark-bar-graph me-1"></i>Mẫu báo cáo có sẵn</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 border rounded">
                            <i class="bi bi-file-earmark-text fs-4 text-secondary"></i>
                            <div>
                                <small class="fw-semibold d-block">Báo cáo dân số</small>
                                <small class="text-muted">Tháp dân số theo độ tuổi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 border rounded">
                            <i class="bi bi-file-earmark-text fs-4 text-secondary"></i>
                            <div>
                                <small class="fw-semibold d-block">Báo cáo lao động</small>
                                <small class="text-muted">Tỷ lệ việc làm / thất nghiệp</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 border rounded">
                            <i class="bi bi-file-earmark-text fs-4 text-secondary"></i>
                            <div>
                                <small class="fw-semibold d-block">Báo cáo an sinh</small>
                                <small class="text-muted">Hộ nghèo, chính sách, trợ cấp</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 p-2 border rounded">
                            <i class="bi bi-file-earmark-text fs-4 text-secondary"></i>
                            <div>
                                <small class="fw-semibold d-block">Báo cáo thuế</small>
                                <small class="text-muted">Thu ngân sách theo kỳ</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
