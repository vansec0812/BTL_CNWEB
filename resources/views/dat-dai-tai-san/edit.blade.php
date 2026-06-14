@extends('layouts.app')

@section('title', 'Cập nhật thửa đất')
@section('page_title', 'Cập nhật thửa đất')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dat-dai-tai-san.index') }}" class="text-decoration-none">Đất đai & Tài sản</a>
        <span class="mx-1">/</span>
        Cập nhật thửa đất
    </div>
    <h2 class="fw-bold mb-1">Cập nhật thông tin thửa đất</h2>
    <p class="text-secondary mb-0">Chỉnh sửa thông tin diện tích, trạng thái sử dụng đất.</p>
</div>

@include('dat-dai-tai-san._form', [
    'action' => route('dat-dai-tai-san.update', $datDaiTaiSan),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật',
])
@endsection
