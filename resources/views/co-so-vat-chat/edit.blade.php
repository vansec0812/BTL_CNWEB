@extends('layouts.app')

@section('title', 'Cập nhật công trình')
@section('page_title', 'Cập nhật công trình')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
        <span class="mx-1">/</span>
        <a href="{{ route('co-so-vat-chat.index') }}" class="text-decoration-none">Cơ sở vật chất</a>
        <span class="mx-1">/</span>
        Cập nhật công trình
    </div>
    <h2 class="fw-bold mb-1">Cập nhật công trình</h2>
    <p class="text-secondary mb-0">Chỉnh sửa thông tin cho công trình {{ $record->ten_cong_trinh }}.</p>
</div>

@include('co-so-vat-chat._form', [
    'action' => route('co-so-vat-chat.update', $record),
    'method' => 'PUT'
])
@endsection
