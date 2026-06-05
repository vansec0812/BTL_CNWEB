@extends('layouts.app')

@section('title', 'Chi tiết hồ sơ NVQS')
@section('page_title', 'Chi tiết hồ sơ NVQS')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('nghia-vu-quan-su.index') }}" class="text-decoration-none text-muted">Nghĩa vụ quân sự</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Chi tiết hồ sơ</span>
    </div>
    <h2 class="fw-bold mb-1">Chi tiết hồ sơ NVQS</h2>
    <p class="text-secondary mb-0">Xem thông tin chi tiết lịch trình, sức khỏe, trạng thái và các tài liệu liên quan đến nghĩa vụ quân sự của công dân.</p>
</div>

@include('nghia-vu-an-ninh.nghia-vu-quan-su._form', [
    'action' => '#',
    'method' => 'GET',
    'submitLabel' => '',
    'isReadOnly' => true,
])
@endsection
