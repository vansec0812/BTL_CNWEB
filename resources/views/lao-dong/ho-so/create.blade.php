@extends('layouts.app')

@section('title', 'Thêm hồ sơ lao động')
@section('page_title', 'Thêm hồ sơ lao động')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ho-so.index') }}" class="text-decoration-none">Hồ sơ lao động</a>
        <span class="mx-1">/</span>
        Thêm hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Thêm mới hồ sơ lao động</h2>
    <p class="text-secondary mb-0">Thiết lập trạng thái lao động, ngành nghề và thông tin làm xa của nhân khẩu trong xã.</p>
</div>

@include('lao-dong.ho-so._form', [
    'action' => route('ho-so.store'),
    'method' => 'POST',
    'submitLabel' => 'Lưu hồ sơ lao động',
])
@endsection
