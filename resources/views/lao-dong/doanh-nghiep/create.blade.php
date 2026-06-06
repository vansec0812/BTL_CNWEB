@extends('layouts.app')

@section('title', 'Đăng ký doanh nghiệp')
@section('page_title', 'Đăng ký doanh nghiệp')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('doanh-nghiep.index') }}" class="text-decoration-none">Doanh nghiệp</a>
        <span class="mx-1">/</span>
        Đăng ký
    </div>
    <h2 class="fw-bold mb-1">Đăng ký doanh nghiệp / Hộ kinh doanh mới</h2>
    <p class="text-secondary mb-0">Thêm mới doanh nghiệp, hợp tác xã hoặc cơ sở sản xuất kinh doanh đóng trên địa bàn xã để làm nơi kết nối việc làm.</p>
</div>

@include('lao-dong.doanh-nghiep._form', [
    'action' => route('doanh-nghiep.store'),
    'method' => 'POST',
    'submitLabel' => 'Đăng ký doanh nghiệp',
])
@endsection
