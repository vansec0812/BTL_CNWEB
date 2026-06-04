@extends('layouts.app')

@section('title', 'Chi tiết sổ hộ khẩu')
@section('page_title', 'Chi tiết sổ hộ khẩu')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ho-khau.index') }}" class="text-decoration-none">Sổ hộ khẩu</a>
        <span class="mx-1">/</span>
        Chi tiết sổ hộ khẩu
    </div>
    <h2 class="fw-bold mb-1">Chi tiết sổ hộ khẩu: {{ $record->ma_ho }}</h2>
    <p class="text-secondary mb-0">Xem thông tin chi tiết (không chỉnh sửa) của sổ hộ khẩu.</p>
</div>

@include('ho-tich.ho-khau._form', [
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => '',
    'isReadOnly' => true,
])
@endsection
