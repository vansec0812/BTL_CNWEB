@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h4 class="fw-bold text-primary mb-1">Thêm Công trình mới</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('co-so-vat-chat.index') }}" class="text-decoration-none">Cơ sở vật chất</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            @include('co-so-vat-chat._form', [
                'action' => route('co-so-vat-chat.store'),
                'method' => 'POST'
            ])
        </div>
    </div>
</div>
@endsection
