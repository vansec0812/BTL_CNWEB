@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ lao động')
@section('page_title', 'Chỉnh sửa hồ sơ lao động')

@section('content')
<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', $parentModule['slug']) }}" class="text-decoration-none">{{ $parentModule['title'] }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('ho-so.index') }}" class="text-decoration-none">Hồ sơ lao động</a>
        <span class="mx-1">/</span>
        Chỉnh sửa
    </div>
    <h2 class="fw-bold mb-1">Chỉnh sửa hồ sơ lao động</h2>
    <p class="text-secondary mb-0">Cập nhật thông tin công việc, nghề nghiệp hoặc trạng thái làm xa của công dân.</p>
</div>

@include('lao-dong.ho-so._form', [
    'action' => route('ho-so.update', $record->id),
    'method' => 'PUT',
    'submitLabel' => 'Cập nhật hồ sơ',
])
@endsection
