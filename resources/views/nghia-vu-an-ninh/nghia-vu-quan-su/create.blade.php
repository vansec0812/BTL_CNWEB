@extends('layouts.app')

@section('title', 'Đăng ký hồ sơ NVQS')
@section('page_title', 'Đăng ký hồ sơ NVQS')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('nghia-vu-quan-su.index') }}" class="text-decoration-none text-muted">Nghĩa vụ quân sự</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Đăng ký mới</span>
    </div>
    <h2 class="fw-bold mb-1">Đăng ký mới hồ sơ NVQS</h2>
    <p class="text-secondary mb-0">Tạo hồ sơ đăng ký nghĩa vụ quân sự thủ công cho nam công dân trong địa bàn xã.</p>
</div>

@include('nghia-vu-an-ninh.nghia-vu-quan-su._form', [
    'action' => route('nghia-vu-quan-su.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu hồ sơ',
])
@endsection
