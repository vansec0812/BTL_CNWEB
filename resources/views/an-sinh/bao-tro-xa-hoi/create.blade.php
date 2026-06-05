@extends('layouts.app')

@section('title', 'Thêm hồ sơ bảo trợ xã hội')
@section('page_title', 'Thêm hồ sơ bảo trợ xã hội')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('bao-tro-xa-hoi.index') }}" class="text-decoration-none">Bảo trợ xã hội</a>
        <span class="mx-1">/</span>
        Thêm hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Thêm hồ sơ bảo trợ xã hội</h2>
    <p class="text-secondary mb-0">Chọn đúng nhóm hộ khẩu hoặc nhân khẩu để phục vụ xét duyệt và cấp phát trợ cấp.</p>
</div>

@include('an-sinh.bao-tro-xa-hoi._form', [
    'action' => route('bao-tro-xa-hoi.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu hồ sơ',
])
@endsection
