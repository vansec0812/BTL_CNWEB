@extends('layouts.app')

@section('title', 'Tạo kết nối việc làm')
@section('page_title', 'Tạo kết nối việc làm')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ket-noi.index') }}" class="text-decoration-none">Kết nối việc làm</a>
        <span class="mx-1">/</span>
        Tạo kết nối
    </div>
    <h2 class="fw-bold mb-1">Tạo kết nối giới thiệu việc làm</h2>
    <p class="text-secondary mb-0">Giới thiệu nhân khẩu đang thất nghiệp vào các vị trí tuyển dụng trống của các doanh nghiệp đóng trên địa bàn.</p>
</div>

@include('lao-dong.ket-noi._form', [
    'action' => route('ket-noi.store'),
    'method' => 'POST',
    'submitLabel' => 'Tạo kết nối',
])
@endsection
