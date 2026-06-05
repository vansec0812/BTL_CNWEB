@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ NVQS')
@section('page_title', 'Chỉnh sửa hồ sơ NVQS')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('nghia-vu-quan-su.index') }}" class="text-decoration-none text-muted">Nghĩa vụ quân sự</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Chỉnh sửa</span>
    </div>
    <h2 class="fw-bold mb-1">Chỉnh sửa hồ sơ NVQS</h2>
    <p class="text-secondary mb-0">Cập nhật thông tin chi tiết về sức khỏe, năm tuyển quân, và trạng thái nghĩa vụ quân sự của công dân.</p>
</div>

@include('nghia-vu-an-ninh.nghia-vu-quan-su._form', [
    'action' => route('nghia-vu-quan-su.update', $record),
    'method' => 'PUT',
    'submitLabel' => 'Lưu thay đổi',
])
@endsection
