@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ y tế')
@section('page_title', 'Chỉnh sửa hồ sơ y tế')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('y-te-nhan-khau.index') }}" class="text-decoration-none">Y tế & BHYT</a>
        <span class="mx-1">/</span>
        Chỉnh sửa
    </div>
    <h2 class="fw-bold mb-1">Chỉnh sửa hồ sơ y tế: {{ $record->nhanKhau?->ho_ten ?? '#'.$record->id }}</h2>
    <p class="text-secondary mb-0">Cập nhật thông tin BHYT, tiêm chủng và ghi chú sức khỏe.</p>
</div>

@include('an-sinh.y-te-nhan-khau._form', [
    'action' => route('y-te-nhan-khau.update', $record),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật',
])
@endsection
