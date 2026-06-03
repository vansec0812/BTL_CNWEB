@extends('layouts.app')

@section('title', 'Sửa sổ hộ khẩu')
@section('page_title', 'Sửa sổ hộ khẩu')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ho-khau.index') }}" class="text-decoration-none">Sổ hộ khẩu</a>
        <span class="mx-1">/</span>
        Sửa sổ hộ khẩu
    </div>
    <h2 class="fw-bold mb-1">Sửa sổ hộ khẩu: {{ $record->ma_ho }}</h2>
    <p class="text-secondary mb-0">Cập nhật thông tin chi tiết sổ hộ khẩu của hộ gia đình.</p>
</div>

@include('ho-tich.ho-khau._form', [
    'action' => route('ho-khau.update', $record),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật sổ hộ khẩu',
])
@endsection
