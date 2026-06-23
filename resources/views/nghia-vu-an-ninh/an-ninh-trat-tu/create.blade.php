@extends('layouts.app')

@section('title', 'Thêm mới hồ sơ An ninh trật tự')
@section('page_title', 'Thêm mới hồ sơ An ninh trật tự')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('an-ninh-trat-tu.index') }}" class="text-decoration-none text-muted">An ninh trật tự</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Thêm hồ sơ mới</span>
    </div>
    <h2 class="fw-bold mb-1">Thêm mới hồ sơ An ninh trật tự</h2>
    <p class="text-secondary mb-0">Lập quyết định xử phạt vi phạm hành chính hoặc đưa đối tượng vào diện quản lý đặc biệt.</p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h5 class="mb-0 fw-bold text-success">
            <i class="bi bi-file-earmark-plus me-1"></i>Thông tin chi tiết hồ sơ
        </h5>
    </div>
    <div class="card-body p-4 pt-2">
        <form action="{{ route('an-ninh-trat-tu.store') }}" method="POST" novalidate>
            @include('nghia-vu-an-ninh.an-ninh-trat-tu._form')
            
            <div class="d-flex justify-content-between pt-4 mt-4 border-top">
                <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-success fw-semibold px-4">
                    <i class="bi bi-check-lg me-1"></i> Lưu thông tin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
