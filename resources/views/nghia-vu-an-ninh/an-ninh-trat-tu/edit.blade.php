@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ An ninh trật tự')
@section('page_title', 'Chỉnh sửa hồ sơ An ninh trật tự')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('an-ninh-trat-tu.index') }}" class="text-decoration-none text-muted">An ninh trật tự</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Chỉnh sửa hồ sơ</span>
    </div>
    <h2 class="fw-bold mb-1">Chỉnh sửa hồ sơ An ninh trật tự</h2>
    <p class="text-secondary mb-0">Cập nhật quyết định hoặc hiện trạng quản lý đối tượng.</p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h5 class="mb-0 fw-bold text-success">
            <i class="bi bi-pencil-square me-1"></i>Thông tin hồ sơ cần sửa
        </h5>
    </div>
    <div class="card-body p-4 pt-2">
        <form action="{{ route('an-ninh-trat-tu.update', $record) }}" method="POST">
            @method('PUT')
            @include('nghia-vu-an-ninh.an-ninh-trat-tu._form')
            
            <div class="d-flex justify-content-between pt-4 mt-4 border-top">
                <a href="{{ route('an-ninh-trat-tu.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-success fw-semibold px-4">
                    <i class="bi bi-check-lg me-1"></i> Cập nhật thông tin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
