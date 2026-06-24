@extends('layouts.app')

@section('title', 'Thêm diện chính sách')
@section('page_title', 'Thêm diện chính sách')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('doi-tuong-chinh-sach.index') }}" class="text-decoration-none">Diện chính sách</a>
        <span class="mx-1">/</span>
        Thêm hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Thêm hồ sơ diện chính sách</h2>
    <p class="text-secondary mb-0">Ghi nhận người dân thuộc diện thương binh, bệnh binh, thân nhân liệt sĩ hoặc người có công.</p>
</div>

@include('an-sinh.doi-tuong-chinh-sach._form', [
    'action' => route('doi-tuong-chinh-sach.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu hồ sơ',
])
@endsection
