@extends('layouts.app')

@section('title', 'Chi tiết hồ sơ y tế')
@section('page_title', 'Chi tiết hồ sơ y tế')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('y-te-nhan-khau.index') }}" class="text-decoration-none">Y tế & BHYT</a>
        <span class="mx-1">/</span>
        Chi tiết hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Hồ sơ y tế: {{ $record->nhanKhau?->ho_ten ?? '#'.$record->id }}</h2>
    <p class="text-secondary mb-0">Xem thông tin chi tiết hồ sơ y tế và bảo hiểm y tế của nhân khẩu.</p>
</div>

@include('an-sinh.y-te-nhan-khau._form', [
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => '',
    'isReadOnly' => true,
])
@endsection
