@extends('layouts.app')

@section('title', 'Cập nhật diện chính sách')
@section('page_title', 'Cập nhật diện chính sách')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('doi-tuong-chinh-sach.index') }}" class="text-decoration-none">Diện chính sách</a>
        <span class="mx-1">/</span>
        Cập nhật hồ sơ
    </div>
    <h2 class="fw-bold mb-1">Cập nhật hồ sơ diện chính sách</h2>
    <p class="text-secondary mb-0">Điều chỉnh thông tin công nhận, trợ cấp hằng tháng và trạng thái hưởng chế độ.</p>
</div>

@include('an-sinh.doi-tuong-chinh-sach._form', [
    'action' => route('doi-tuong-chinh-sach.update', $record),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật hồ sơ',
])
@endsection
