@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ lao động')
@section('page_title', 'Chỉnh sửa hồ sơ lao động')

@section('content')
<div class="page-header">
    <div class="breadcrumb-trail">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <i class="bi bi-chevron-right separator"></i>
        <a href="{{ route('ho-so.index') }}" class="text-decoration-none">Hồ sơ lao động</a>
        <i class="bi bi-chevron-right separator"></i>
        <span>Chỉnh sửa</span>
    </div>
    <div class="d-flex align-items-start justify-content-between gap-3">
        <div>
            <h2 class="mb-2">Chỉnh sửa hồ sơ lao động</h2>
            <p class="text-secondary mb-0">Cập nhật thông tin công việc, nghề nghiệp hoặc trạng thái làm xa của công dân.</p>
        </div>
        <a href="{{ route('ho-so.index') }}" class="btn btn-outline-secondary d-none d-md-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Quay lại</span>
        </a>
    </div>
</div>

@include('lao-dong.ho-so._form', [
    'action' => route('ho-so.update', $record->id),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật hồ sơ',
])
@endsection
