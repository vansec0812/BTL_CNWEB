@extends('layouts.app')

@section('title', 'Chỉnh sửa doanh nghiệp')
@section('page_title', 'Chỉnh sửa doanh nghiệp')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('doanh-nghiep.index') }}" class="text-decoration-none">Doanh nghiệp</a>
        <span class="mx-1">/</span>
        Chỉnh sửa
    </div>
    <h2 class="fw-bold mb-1">Chỉnh sửa thông tin doanh nghiệp</h2>
    <p class="text-secondary mb-0">Cập nhật thông tin đại diện pháp luật, địa chỉ trụ sở, số điện thoại hoặc trạng thái tuyển dụng.</p>
</div>

@include('lao-dong.doanh-nghiep._form', [
    'action' => route('doanh-nghiep.update', $record->id),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật doanh nghiệp',
])
@endsection
