@extends('layouts.app')

@section('title', 'Đăng ký hoạt động dân quân')
@section('page_title', 'Đăng ký hoạt động dân quân')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dan-quan-hoat-dong.index') }}" class="text-decoration-none text-muted">Hoạt động dân quân</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Đăng ký mới</span>
    </div>
    <h2 class="fw-bold mb-1">Đăng ký mới hoạt động dân quân</h2>
    <p class="text-secondary mb-0">Tạo hoạt động tập huấn hoặc phân ca trực ban cho lực lượng dân quân tự vệ trên địa bàn.</p>
</div>

@include('nghia-vu-an-ninh.dan-quan-hoat-dong._form', [
    'action' => route('dan-quan-hoat-dong.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu hoạt động',
])
@endsection
