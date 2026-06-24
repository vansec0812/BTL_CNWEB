@extends('layouts.app')

@section('title', 'Chi tiết hoạt động dân quân')
@section('page_title', 'Chi tiết hoạt động dân quân')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none text-muted">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('dan-quan-hoat-dong.index') }}" class="text-decoration-none text-muted">Hoạt động dân quân</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Chi tiết hoạt động</span>
    </div>
    <h2 class="fw-bold mb-1">Chi tiết hoạt động dân quân</h2>
    <p class="text-secondary mb-0">Xem thông tin chi tiết về hoạt động thực tế, ngày thực hiện, trạng thái và ghi chú của dân quân tự vệ.</p>
</div>

@include('nghia-vu-an-ninh.dan-quan-hoat-dong._form', [
    'action' => '#',
    'method' => 'GET',
    'submitLabel' => '',
    'isReadOnly' => true,
])
@endsection
