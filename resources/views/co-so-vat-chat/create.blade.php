@extends('layouts.app')

@section('title', 'Thêm công trình mới')
@section('page_title', 'Thêm công trình mới')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
        <span class="mx-1">/</span>
        <a href="{{ route('co-so-vat-chat.index') }}" class="text-decoration-none">Cơ sở vật chất</a>
        <span class="mx-1">/</span>
        Thêm công trình
    </div>
    <h2 class="fw-bold mb-1">Thêm công trình mới</h2>
    <p class="text-secondary mb-0">Nhập thông tin cho công trình cơ sở hạ tầng mới.</p>
</div>

@include('co-so-vat-chat._form', [
    'action' => route('co-so-vat-chat.store'),
    'method' => 'POST'
])
@endsection
