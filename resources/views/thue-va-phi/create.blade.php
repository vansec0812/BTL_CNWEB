@extends('layouts.app')

@section('title', 'Tạo khoản thu mới')
@section('page_title', 'Tạo khoản thu mới')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
        <span class="mx-1">/</span>
        <a href="{{ route('thue-va-phi.index') }}" class="text-decoration-none">Thuế & Phí địa phương</a>
        <span class="mx-1">/</span>
        Tạo khoản thu
    </div>
    <h2 class="fw-bold mb-1">Tạo khoản thu mới</h2>
    <p class="text-secondary mb-0">Thêm khoản thuế phí thủ công cho hộ gia đình.</p>
</div>

@include('thue-va-phi._form', [
    'action' => route('thue-va-phi.store'),
    'method' => 'POST',
    'submitLabel' => 'Khởi tạo khoản thu',
])
@endsection
