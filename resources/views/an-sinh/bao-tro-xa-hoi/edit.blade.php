@extends('layouts.app')

@section('title', 'Cập nhật hồ sơ bảo trợ xã hội')
@section('page_title', 'Cập nhật hồ sơ bảo trợ xã hội')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('bao-tro-xa-hoi.index') }}" class="text-decoration-none">Bảo trợ xã hội</a>
        <span class="mx-1">/</span>
        Cập nhật hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Cập nhật hồ sơ bảo trợ xã hội</h2>
    <p class="text-secondary mb-0">Điều chỉnh quyết định, trạng thái hưởng và mức trợ cấp khi có thay đổi thực tế.</p>
</div>

@include('an-sinh.bao-tro-xa-hoi._form', [
    'action' => route('bao-tro-xa-hoi.update', $record),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật hồ sơ',
])
@endsection
