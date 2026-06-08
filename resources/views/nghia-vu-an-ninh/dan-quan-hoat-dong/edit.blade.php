@extends('layouts.app')

@section('title', 'Chỉnh sửa hoạt động dân quân')
@section('page_title', 'Chỉnh sửa hoạt động dân quân')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dan-quan-hoat-dong.index') }}" class="text-decoration-none text-muted">Hoạt động dân quân</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Chỉnh sửa</span>
    </div>
    <h2 class="fw-bold mb-1">Chỉnh sửa hoạt động dân quân</h2>
    <p class="text-secondary mb-0">Cập nhật thông tin chi tiết về loại hoạt động, ngày thực hiện và trạng thái của dân quân.</p>
</div>

@include('nghia-vu-an-ninh.dan-quan-hoat-dong._form', [
    'action' => route('dan-quan-hoat-dong.update', $record),
    'method' => 'PUT',
    'submitLabel' => 'Lưu thay đổi', 
])
@endsection
