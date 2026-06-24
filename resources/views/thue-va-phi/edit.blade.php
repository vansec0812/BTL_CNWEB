@extends('layouts.app')

@section('title', 'Cập nhật thu tiền')
@section('page_title', 'Cập nhật thu tiền')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', 'dat-dai-ha-tang') }}" class="text-decoration-none">Đất đai, Hạ tầng & Tài sản hộ dân</a>
        <span class="mx-1">/</span>
        <a href="{{ route('thue-va-phi.index') }}" class="text-decoration-none">Thuế & Phí địa phương</a>
        <span class="mx-1">/</span>
        Thu tiền
    </div>
    <h2 class="fw-bold mb-1">Cập nhật số tiền nộp</h2>
    <p class="text-secondary mb-0">Ghi nhận số tiền người dân nộp vào ngân sách.</p>
</div>

@include('thue-va-phi._form', [
    'action' => route('thue-va-phi.update', $thueVaPhi),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật biên lai',
])
@endsection
