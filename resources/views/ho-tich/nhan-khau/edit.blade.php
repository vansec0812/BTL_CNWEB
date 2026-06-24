@extends('layouts.app')

@section('title', 'Sửa nhân khẩu')
@section('page_title', 'Sửa nhân khẩu')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('nhan-khau.index') }}" class="text-decoration-none">Nhân khẩu</a>
        <span class="mx-1">/</span>
        Sửa nhân khẩu
    </div>
    <h2 class="fw-bold mb-1">Sửa nhân khẩu: {{ $record->ho_ten }}</h2>
    <p class="text-secondary mb-0">Cập nhật thông tin chi tiết về lý lịch và trạng thái của công dân.</p>
</div>

@include('ho-tich.nhan-khau._form', [
    'action' => route('nhan-khau.update', $record),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật nhân khẩu',
])
@endsection
