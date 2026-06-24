@extends('layouts.app')

@section('title', 'Chi tiết nhân khẩu')
@section('page_title', 'Chi tiết nhân khẩu')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('nhan-khau.index') }}" class="text-decoration-none">Nhân khẩu</a>
        <span class="mx-1">/</span>
        Chi tiết nhân khẩu
    </div>
    <h2 class="fw-bold mb-1">Chi tiết nhân khẩu: {{ $record->ho_ten }}</h2>
    <p class="text-secondary mb-0">Xem thông tin chi tiết (không chỉnh sửa) của nhân khẩu.</p>
</div>

@include('ho-tich.nhan-khau._form', [
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => '',
    'isReadOnly' => true,
])
@endsection
