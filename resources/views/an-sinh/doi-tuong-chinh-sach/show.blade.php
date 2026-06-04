@extends('layouts.app')

@section('title', 'Chi tiết hồ sơ chính sách')
@section('page_title', 'Chi tiết hồ sơ chính sách')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('doi-tuong-chinh-sach.index') }}" class="text-decoration-none">Hồ sơ chính sách</a>
        <span class="mx-1">/</span>
        Chi tiết hồ sơ chính sách
    </div>
    <h2 class="fw-bold mb-1">Chi tiết hồ sơ chính sách: {{ $record->nhanKhau->ho_ten ?? '' }}</h2>
    <p class="text-secondary mb-0">Xem thông tin chi tiết (không chỉnh sửa) của hồ sơ diện chính sách.</p>
</div>

@include('an-sinh.doi-tuong-chinh-sach._form', [
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => '',
    'isReadOnly' => true,
])
@endsection
