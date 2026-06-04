@extends('layouts.app')

@section('title', 'Thêm sổ hộ khẩu')
@section('page_title', 'Thêm sổ hộ khẩu')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ho-khau.index') }}" class="text-decoration-none">Sổ hộ khẩu</a>
        <span class="mx-1">/</span>
        Thêm sổ hộ khẩu
    </div>
    <h2 class="fw-bold mb-1">Thêm sổ hộ khẩu mới</h2>
    <p class="text-secondary mb-0">Tạo sổ hộ khẩu mới cho hộ gia đình trên địa bàn xã.</p>
</div>

@include('ho-tich.ho-khau._form', [
    'action' => route('ho-khau.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu sổ hộ khẩu',
])
@endsection
