@extends('layouts.app')

@section('title', 'Thêm hồ sơ y tế')
@section('page_title', 'Thêm hồ sơ y tế')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('y-te-nhan-khau.index') }}" class="text-decoration-none">Y tế & BHYT</a>
        <span class="mx-1">/</span>
        Thêm hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Thêm hồ sơ y tế</h2>
    <p class="text-secondary mb-0">Tạo hồ sơ theo dõi BHYT và tiêm chủng cho nhân khẩu trên địa bàn.</p>
</div>

@include('an-sinh.y-te-nhan-khau._form', [
    'action' => route('y-te-nhan-khau.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu hồ sơ',
])
@endsection
