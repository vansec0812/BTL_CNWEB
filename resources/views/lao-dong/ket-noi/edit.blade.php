@extends('layouts.app')

@section('title', 'Cập nhật kết nối việc làm')
@section('page_title', 'Cập nhật kết nối việc làm')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ket-noi.index') }}" class="text-decoration-none">Kết nối việc làm</a>
        <span class="mx-1">/</span>
        Cập nhật kết quả
    </div>
    <h2 class="fw-bold mb-1">Cập nhật kết quả giới thiệu việc làm</h2>
    <p class="text-secondary mb-0">Cập nhật trạng thái nhận việc (được nhận / không được nhận / lao động từ chối) sau khi phỏng vấn.</p>
</div>

@include('lao-dong.ket-noi._form', [
    'action' => route('ket-noi.update', $record->id),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật kết quả',
])
@endsection
