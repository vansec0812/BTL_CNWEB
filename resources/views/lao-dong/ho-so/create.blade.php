@extends('layouts.app')

@section('title', 'Thêm hồ sơ lao động')
@section('page_title', 'Thêm hồ sơ lao động')

@section('content')
<div class="page-header">
    <div class="breadcrumb-trail">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <i class="bi bi-chevron-right separator"></i>
        <a href="{{ route('ho-so.index') }}" class="text-decoration-none">Hồ sơ lao động</a>
        <i class="bi bi-chevron-right separator"></i>
        <span>Thêm hồ sơ</span>
    </div>
    <div class="d-flex align-items-start justify-content-between gap-3">
        <div>
            <h2 class="mb-2">Thêm mới hồ sơ lao động</h2>
            <p class="text-secondary mb-0">Thiết lập trạng thái lao động, ngành nghề và thông tin làm xa của nhân khẩu trong xã.</p>
        </div>
        <a href="{{ route('ho-so.index') }}" class="btn btn-outline-secondary d-none d-md-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Quay lại</span>
        </a>
    </div>
</div>

@include('lao-dong.ho-so._form', [
    'action' => route('ho-so.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu hồ sơ lao động',
])
@endsection
