@extends('layouts.app')

@section('title', 'Chi tiết hồ sơ bảo trợ xã hội')
@section('page_title', 'Chi tiết hồ sơ bảo trợ xã hội')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('bao-tro-xa-hoi.index') }}" class="text-decoration-none">Bảo trợ xã hội</a>
        <span class="mx-1">/</span>
        Chi tiết hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Chi tiết hồ sơ bảo trợ xã hội: {{ $record->doiTuongLabel() }}</h2>
    <p class="text-secondary mb-0">Xem thông tin chi tiết của hồ sơ bảo trợ xã hội.</p>
</div>

@include('an-sinh.bao-tro-xa-hoi._form', [
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => '',
    'isReadOnly' => true,
])
@endsection
