@extends('layouts.app')

@section('title', 'Thêm thửa đất')
@section('page_title', 'Thêm thửa đất')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dat-dai-tai-san.index') }}" class="text-decoration-none">Đất đai & Tài sản</a>
        <span class="mx-1">/</span>
        Thêm thửa đất
    </div>
    <h2 class="fw-bold mb-1">Thêm thửa đất mới</h2>
    <p class="text-secondary mb-0">Nhập thông tin giấy chứng nhận QSDĐ và diện tích của hộ gia đình.</p>
</div>

@include('dat-dai-tai-san._form', [
    'action' => route('dat-dai-tai-san.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu thửa đất',
])
@endsection
