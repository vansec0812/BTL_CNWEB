@extends('layouts.app')

@section('title', 'Thêm nhân khẩu')
@section('page_title', 'Thêm nhân khẩu')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('nhan-khau.index') }}" class="text-decoration-none">Nhân khẩu</a>
        <span class="mx-1">/</span>
        Thêm nhân khẩu
    </div>
    <h2 class="fw-bold mb-1">Thêm nhân khẩu mới</h2>
    <p class="text-secondary mb-0">Nhập đầy đủ thông tin cá nhân và hộ tịch của công dân mới.</p>
</div>

@include('ho-tich.nhan-khau._form', [
    'action' => route('nhan-khau.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu nhân khẩu',
])
@endsection
