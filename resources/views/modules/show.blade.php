@extends('layouts.app')

@section('title', $selected['title'])
@section('page_title', $selected['title'])

@section('content')
    <section class="page-hero rounded-4 p-4 p-lg-5 mb-4 text-white shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <p class="text-uppercase small fw-semibold opacity-75 mb-2">{{ $selected['owner'] }}</p>
                <h1 class="display-6 fw-bold mb-3">{{ $selected['title'] }}</h1>
                <p class="lead mb-0">{{ $selected['description'] }}</p>
            </div>
            <div class="col-lg-4">
                <div class="bg-white bg-opacity-10 border border-light border-opacity-25 rounded-4 p-3">
                    <div class="fw-semibold mb-2">Mục tiêu trang</div>
                    <p class="small mb-0 opacity-75">Trang khung này dùng để thống nhất bố cục trước khi bổ sung form, bộ lọc và xử lý CRUD thật.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
        @foreach ($metrics as $metric)
            <div class="col">
                <article class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="small text-secondary text-uppercase mb-1">{{ $metric['label'] }}</p>
                        <div class="h3 fw-bold mb-1">{{ $metric['count'] }}</div>
                        <span class="badge text-bg-{{ $selected['color'] }}">Bảng dữ liệu</span>
                    </div>
                </article>
            </div>
        @endforeach
    </section>

    <section class="row g-4">
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="h5 mb-1">Chức năng dự kiến</h2>
                    <p class="text-secondary mb-0">Tóm tắt theo phân tích trong OUTLINE.md.</p>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @foreach ($selected['features'] as $feature)
                            <div class="list-group-item px-0 d-flex gap-3">
                                <span class="badge rounded-pill text-bg-success align-self-start">{{ $loop->iteration }}</span>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                        <div>
                            <h2 class="h5 mb-1">Bảng nghiệp vụ mẫu</h2>
                            <p class="text-secondary mb-0">Hiển thị định hướng dữ liệu, chưa thực hiện ghi/sửa/xóa.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success btn-sm" type="button">Lọc dữ liệu</button>
                            <button class="btn btn-success btn-sm" type="button">Thêm hồ sơ</button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nghiệp vụ</th>
                                    <th>Dữ liệu chính</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selected['rows'] as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $row[0] }}</td>
                                        <td>{{ $row[1] }}</td>
                                        <td><span class="badge text-bg-light border">{{ $row[2] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
