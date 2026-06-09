@extends('layouts.app')

@section('title', 'Dashboard & Biểu đồ thống kê')
@section('page_title', 'Báo cáo & Biểu đồ trực quan')

@section('content')
<style>
    .metric-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    .chart-card {
        border: none;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
    }
    .chart-container {
        position: relative;
        margin: auto;
        width: 100%;
    }
    .icon-wrapper {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }
</style>

<div class="mb-4">
    <div class="small text-secondary mb-1">
        <a href="{{ route('modules.show', 'he-thong-bao-cao') }}" class="text-decoration-none text-muted">Hệ thống & Báo cáo</a>
        <span class="mx-1">/</span>
        <span class="text-dark">Dashboard & Biểu đồ</span>
    </div>
    <h2 class="fw-bold mb-1">Dashboard & Biểu đồ trực quan</h2>
    <p class="text-secondary mb-0">Thống kê cơ cấu dân cư, tỷ lệ hộ nghèo và hiện trạng lao động trên địa bàn xã Quốc Oai.</p>
</div>

<!-- Quick Statistics Row -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Tổng nhân khẩu</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalNhanKhau) }}</h3>
                    <small class="text-success fw-medium"><i class="bi bi-arrow-up-short"></i> Dữ liệu thực tế</small>
                </div>
                <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-people fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Tổng hộ khẩu</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalHoKhau) }}</h3>
                    <small class="text-primary fw-medium"><i class="bi bi-house"></i> Đã số hóa</small>
                </div>
                <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-journal-text fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                @php
                    $povertyRate = $totalHoKhau > 0 ? round((($hoNgheoCount + $hoCanNgheoCount) / $totalHoKhau) * 100, 1) : 0;
                @endphp
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Tỷ lệ hộ nghèo</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $povertyRate }}%</h3>
                    <small class="text-danger fw-medium">{{ $hoNgheoCount }} nghèo | {{ $hoCanNgheoCount }} cận nghèo</small>
                </div>
                <div class="icon-wrapper bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-shield-exclamation fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card metric-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Độ tuổi trung bình</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $avgAge }} tuổi</h3>
                    <small class="text-warning fw-medium"><i class="bi bi-clock"></i> Cơ cấu dân số trẻ</small>
                </div>
                <div class="icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-activity fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- 1. Population Pyramid Chart -->
    <div class="col-12 col-lg-8">
        <div class="card chart-card">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-bar-chart-steps text-success me-2"></i>Tháp dân số xã</h5>
                    <p class="text-muted small mb-0">Phân tích cơ cấu dân số theo độ tuổi và giới tính (Nam bên trái, Nữ bên phải)</p>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="chart-container" style="height: 380px;">
                    <canvas id="populationPyramidChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Poverty Rate Pie Chart -->
    <div class="col-12 col-lg-4">
        <div class="card chart-card">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-pie-chart-fill text-danger me-2"></i>Tỷ lệ hộ nghèo</h5>
                <p class="text-muted small mb-0">Tỷ lệ hộ nghèo & cận nghèo trong tổng số hộ khẩu</p>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="chart-container d-flex align-items-center justify-content-center" style="height: 280px;">
                    <canvas id="povertyPieChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <span class="small text-secondary"><i class="bi bi-circle-fill text-danger me-1"></i> Hộ nghèo</span>
                        <span class="fw-bold text-dark">{{ $hoNgheoCount }} hộ ({{ $totalHoKhau > 0 ? round(($hoNgheoCount / $totalHoKhau) * 100, 1) : 0 }}%)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <span class="small text-secondary"><i class="bi bi-circle-fill text-warning me-1"></i> Hộ cận nghèo</span>
                        <span class="fw-bold text-dark">{{ $hoCanNgheoCount }} hộ ({{ $totalHoKhau > 0 ? round(($hoCanNgheoCount / $totalHoKhau) * 100, 1) : 0 }}%)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="small text-secondary"><i class="bi bi-circle-fill text-success me-1"></i> Hộ bình thường</span>
                        <span class="fw-bold text-dark">{{ $normalHoKhauCount }} hộ ({{ $totalHoKhau > 0 ? round(($normalHoKhauCount / $totalHoKhau) * 100, 1) : 0 }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- 3. Labor Trend Chart -->
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-briefcase-fill text-primary me-2"></i>Xu hướng & Hiện trạng lao động</h5>
                    <p class="text-muted small mb-0">Phân bổ nhân khẩu theo trạng thái hoạt động kinh tế & lao động</p>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">Tổng số lao động: {{ $totalLaoDong }}</span>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8">
                        <div class="chart-container" style="height: 320px;">
                            <canvas id="laborTrendChart"></canvas>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="p-3 bg-light rounded-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-1"></i> Chỉ số nổi bật:</h6>
                            <ul class="list-unstyled mb-0">
                                @php
                                    $hasJobIndex = array_search('Có việc làm', $laborTrendLabels);
                                    $unemployedIndex = array_search('Thất nghiệp', $laborTrendLabels);
                                    
                                    $hasJobCount = $hasJobIndex !== false ? $laborTrendValues[$hasJobIndex] : 0;
                                    $unemployedCount = $unemployedIndex !== false ? $laborTrendValues[$unemployedIndex] : 0;
                                    
                                    $totalLaborAge = array_sum($laborTrendValues);
                                    $employmentRate = $totalLaborAge > 0 ? round(($hasJobCount / $totalLaborAge) * 100, 1) : 0;
                                    $unemploymentRate = $totalLaborAge > 0 ? round(($unemployedCount / $totalLaborAge) * 100, 1) : 0;
                                @endphp
                                <li class="mb-3">
                                    <span class="text-secondary small d-block">Tỷ lệ có việc làm:</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $employmentRate }}%" aria-valuenow="{{ $employmentRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $employmentRate }}%</span>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <span class="text-secondary small d-block">Tỷ lệ thất nghiệp lực lượng:</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $unemploymentRate }}%" aria-valuenow="{{ $unemploymentRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $unemploymentRate }}%</span>
                                    </div>
                                </li>
                                <li class="mb-0">
                                    <span class="text-secondary small d-block">Hiện trạng doanh nghiệp:</span>
                                    <p class="mb-0 fw-semibold text-dark"><i class="bi bi-building text-primary me-1"></i> {{ $totalDoanhNghiep }} cơ sở sản xuất & doanh nghiệp</p>
                                    <span class="text-muted small">đang hoạt động trên địa bàn, góp phần tạo công ăn việc làm.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart Tháp dân số (Population Pyramid)
    const pyramidLabels = @json($pyramidLabels);
    const maleData = @json($pyramidMale);
    const femaleData = @json($pyramidFemale);

    const ctxPyramid = document.getElementById('populationPyramidChart').getContext('2d');
    new Chart(ctxPyramid, {
        type: 'bar',
        data: {
            labels: pyramidLabels,
            datasets: [
                {
                    label: 'Nam',
                    data: maleData,
                    backgroundColor: 'rgba(13, 110, 253, 0.85)',
                    borderColor: 'rgb(13, 110, 253)',
                    borderWidth: 1,
                    borderRadius: { topLeft: 5, bottomLeft: 5 }
                },
                {
                    label: 'Nữ',
                    data: femaleData,
                    backgroundColor: 'rgba(235, 104, 162, 0.85)',
                    borderColor: 'rgb(235, 104, 162)',
                    borderWidth: 1,
                    borderRadius: { topRight: 5, bottomRight: 5 }
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        callback: function(value) {
                            return Math.abs(value);
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    stacked: true,
                    position: 'left',
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = Math.abs(context.raw);
                            return `${label}: ${value} người`;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8
                    }
                }
            }
        }
    });

    // 2. Chart Tỷ lệ hộ nghèo (Poverty Pie Chart)
    const ctxPie = document.getElementById('povertyPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Hộ nghèo', 'Hộ cận nghèo', 'Hộ bình thường'],
            datasets: [{
                data: [{{ $hoNgheoCount }}, {{ $hoCanNgheoCount }}, {{ $normalHoKhauCount }}],
                backgroundColor: [
                    'rgba(220, 53, 69, 0.85)',
                    'rgba(255, 193, 7, 0.85)',
                    'rgba(25, 135, 84, 0.85)'
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = total > 0 ? Math.round((value / total) * 1000) / 10 : 0;
                            return `${label}: ${value} hộ (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // 3. Chart Xu hướng lao động (Labor Trend Chart)
    const laborLabels = @json($laborTrendLabels);
    const laborValues = @json($laborTrendValues);

    const ctxLabor = document.getElementById('laborTrendChart').getContext('2d');
    new Chart(ctxLabor, {
        type: 'bar',
        data: {
            labels: laborLabels,
            datasets: [{
                label: 'Số lượng lao động',
                data: laborValues,
                backgroundColor: [
                    'rgba(25, 135, 84, 0.75)',
                    'rgba(220, 53, 69, 0.75)',
                    'rgba(13, 110, 253, 0.75)',
                    'rgba(108, 117, 125, 0.75)',
                    'rgba(255, 193, 7, 0.75)',
                    'rgba(111, 66, 193, 0.75)',
                    'rgba(0, 200, 220, 0.75)'
                ],
                borderColor: [
                    'rgb(25, 135, 84)',
                    'rgb(220, 53, 69)',
                    'rgb(13, 110, 253)',
                    'rgb(108, 117, 125)',
                    'rgb(255, 193, 7)',
                    'rgb(111, 66, 193)',
                    'rgb(0, 200, 220)'
                ],
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endsection
