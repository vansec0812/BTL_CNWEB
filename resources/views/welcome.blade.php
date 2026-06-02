@extends('layouts.app')

@section('title', 'Tổng quan quản lý hộ dân')
@section('page_title', 'Tổng quan điều hành')

@section('content')
    <section class="page-hero rounded-4 p-4 p-lg-5 mb-4 text-white shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <p class="text-uppercase small fw-semibold opacity-75 mb-2">Hệ thống quản lý hành chính cấp xã</p>
                <h1 class="display-6 fw-bold mb-3">Quản lý thông tin hộ dân cư trên địa bàn xã</h1>
                <p class="lead mb-0">Theo dõi hộ khẩu, nhân khẩu, lao động, an sinh, nghĩa vụ, đất đai và báo cáo điều hành trong một giao diện thống nhất.</p>
            </div>
            <div class="col-lg-4">
                <div class="bg-white bg-opacity-10 border border-light border-opacity-25 rounded-4 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Trạng thái dữ liệu</span>
                        <span class="badge text-bg-light">Đang vận hành</span>
                    </div>
                    <p class="small mb-0 opacity-75">Các số liệu bên dưới tự đọc từ bảng dữ liệu nếu đã migrate/seed, đồng thời có giá trị dự phòng để trang luôn hiển thị ổn định.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-4">
        @foreach ($stats as $stat)
            <div class="col">
                <article class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-secondary small mb-1">{{ $stat['label'] }}</p>
                                <div class="h2 fw-bold mb-1">{{ $stat['value'] }}</div>
                                <p class="small text-secondary mb-0">{{ $stat['note'] }}</p>
                            </div>
                            <span class="badge rounded-pill text-bg-{{ $stat['variant'] }}">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </section>

    <section class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="h5 mb-1">Các phân hệ nghiệp vụ</h2>
                    <p class="text-secondary mb-0">Tạo sẵn theo các cụm module trong OUTLINE.md để từng thành viên tiếp tục phát triển CRUD.</p>
                </div>
                <div class="card-body p-4">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @foreach ($modules as $module)
                            <div class="col">
                                <a class="card module-card h-100 text-decoration-none border" href="{{ route('modules.show', $module['slug']) }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                            <h3 class="h6 text-dark mb-0">{{ $module['title'] }}</h3>
                                            <span class="badge text-bg-{{ $module['color'] }}">{{ $module['owner'] }}</span>
                                        </div>
                                        <p class="small text-secondary mb-3">{{ $module['description'] }}</p>
                                        <span class="btn btn-outline-success btn-sm">Mở phân hệ</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="h5 mb-1">Việc cần xử lý</h2>
                    <p class="text-secondary mb-0">Danh sách mẫu cho màn hình điều hành.</p>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($tasks as $task)
                        <div class="list-group-item px-4 py-3">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $task['title'] }}</div>
                                    <small class="text-secondary">{{ $task['meta'] }}</small>
                                </div>
                                <span class="badge text-bg-success align-self-start">{{ $task['priority'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Tiến độ chuẩn hóa dữ liệu</h2>
                    @foreach ([['Hộ khẩu', 88], ['Nhân khẩu', 76], ['An sinh', 61], ['Thuế phí', 54]] as [$label, $percent])
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold">{{ $label }}</span>
                                <span class="text-secondary">{{ $percent }}%</span>
                            </div>
                            <div class="progress" role="progressbar" aria-label="Tiến độ {{ $label }}" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="h5 mb-1">Lịch hoạt động gần nhất</h2>
                            <p class="text-secondary mb-0">Mô phỏng dòng thời gian cho cán bộ xã.</p>
                        </div>
                        <span class="badge text-bg-light">Cập nhật hôm nay</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-secondary">08:30</td>
                                    <td>Tiếp nhận hồ sơ chuyển khẩu</td>
                                    <td><span class="badge text-bg-success">Đã xử lý</span></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">10:15</td>
                                    <td>Cập nhật danh sách lao động</td>
                                    <td><span class="badge text-bg-primary">Đang làm</span></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">14:00</td>
                                    <td>Tổng hợp thu phí địa phương</td>
                                    <td><span class="badge text-bg-warning">Chờ đối soát</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
