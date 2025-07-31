@extends('layouts.admin')

@section('styles')
<link href="{{ asset('admin/css/dashboard-stats.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><i class="fa fa-chart-bar"></i> Thống kê</h2>
        <ol class="breadcrumb">
            <li class="active">
                <strong>Thống kê</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content">
    <!-- Form lọc thời gian -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-calendar"></i> Lọc theo thời gian</h5>
                </div>
                <div class="ibox-content">
                    <form method="GET" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Loại lọc:</label>
                                    <select id="filter_type" name="filter_type" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Theo ngày</option>
                                        <option value="week" {{ request('filter_type') == 'week' ? 'selected' : '' }}>Theo tuần</option>
                                        <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Theo tháng</option>
                                        <option value="custom" {{ (request('start_date') && request('end_date')) ? 'selected' : '' }}>Khoảng thời gian tùy chỉnh</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3" id="filter_value_div" class="filter-field" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label">Giá trị:</label>
                                    <input type="text" id="filter_value" name="filter_value" class="form-control"
                                           value="{{ request('filter_value', now()->toDateString()) }}"
                                           placeholder="Chọn ngày/tuần/tháng">
                                </div>
                            </div>

                            <div class="col-md-3" id="start_date_div" class="filter-field" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label">Từ ngày:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control"
                                           value="{{ request('start_date') }}">
                                </div>
                            </div>

                            <div class="col-md-3" id="end_date_div" class="filter-field" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label">Đến ngày:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control"
                                           value="{{ request('end_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="filter-submit-btn">
                                        <i class="fa fa-search"></i> Lọc dữ liệu
                                    </button>
                                    <a href="{{ route('admin.statistics.index') }}" class="btn btn-default">
                                        <i class="fa fa-refresh"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin khoảng thời gian đã chọn -->
    @if(request('start_date') && request('end_date'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                <strong>Khoảng thời gian đã chọn:</strong>
                Từ {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                đến {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
            </div>
        </div>
    </div>
    @endif

    <div class="container-fluid">
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title d-flex justify-content-between">
                        <h5><i class="fa fa-money"></i> Doanh thu</h5>
                        <span class="label label-success">VND</span>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ number_format($totalRevenue) }}</h1>
                        <div class="stat-percent font-bold text-success">
                            <i class="fa fa-bolt"></i>
                            @if(request('start_date') && request('end_date'))
                                Trong khoảng thời gian
                            @else
                                Tổng cộng
                            @endif
                        </div>
                        <small>Doanh thu {{ request('start_date') && request('end_date') ? 'trong khoảng thời gian' : 'tổng cộng' }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title d-flex justify-content-between">
                        <h5><i class="fa fa-shopping-cart"></i> Đơn hàng</h5>
                        <span class="label label-info">Đơn hàng</span>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $totalOrders }}</h1>
                        <div class="stat-percent font-bold text-info">
                            <i class="fa fa-shopping-cart"></i>
                            @if(request('start_date') && request('end_date'))
                                Trong khoảng thời gian
                            @else
                                Tổng cộng
                            @endif
                        </div>
                        <small>Đơn hàng {{ request('start_date') && request('end_date') ? 'trong khoảng thời gian' : 'tổng cộng' }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title d-flex justify-content-between">
                        <h5><i class="fa fa-users"></i> Người dùng</h5>
                        <span class="label label-primary">Users</span>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $totalUsers }}</h1>
                        <div class="stat-percent font-bold text-primary">
                            <i class="fa fa-user"></i>
                            @if(request('start_date') && request('end_date'))
                                Mới đăng ký
                            @else
                                Hoạt động
                            @endif
                        </div>
                        <small>Người dùng {{ request('start_date') && request('end_date') ? 'mới đăng ký' : 'đang hoạt động' }}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title d-flex justify-content-between">
                        <h5><i class="fa fa-cube"></i> Sản phẩm</h5>
                        <span class="label label-warning">Products</span>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $totalProducts }}</h1>
                        <div class="stat-percent font-bold text-warning">
                            <i class="fa fa-archive"></i>
                            @if(request('start_date') && request('end_date'))
                                Mới tạo
                            @else
                                Đang bán
                            @endif
                        </div>
                        <small>Sản phẩm {{ request('start_date') && request('end_date') ? 'mới tạo' : 'đang bán' }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title">
                        <h5><i class="fa fa-chart-line"></i> Biểu đồ doanh thu 6 tháng gần nhất</h5>
                    </div>
                    <div class="ibox-content">
                        <canvas id="revenueChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title">
                        <h5><i class="fa fa-star"></i> Top 5 sản phẩm bán chạy</h5>
                    </div>
                    <div class="ibox-content">
                        @if($topProducts->count() > 0)
                            <ul class="list-group">
                                @foreach ($topProducts->take(5) as $index => $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge badge-primary">{{ $index + 1 }}</span>
                                            {{ $product->variant_name }}
                                        </div>
                                        <span class="badge bg-success">{{ $product->total_sold }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center">
                                <i class="fa fa-info-circle fa-3x text-muted"></i>
                                <p class="text-muted">Chưa có dữ liệu sản phẩm bán chạy</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title">
                        <h5><i class="fa fa-ticket"></i> Top mã giảm giá được sử dụng</h5>
                    </div>
                    <div class="ibox-content">
                        @if($topCoupons->count() > 0)
                            <ul class="list-group">
                                @foreach ($topCoupons->take(5) as $index => $coupon)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge badge-info">{{ $index + 1 }}</span>
                                            {{ $coupon->code }}
                                        </div>
                                        <span class="badge bg-info">{{ $coupon->total_usage }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center">
                                <i class="fa fa-info-circle fa-3x text-muted"></i>
                                <p class="text-muted">Chưa có dữ liệu mã giảm giá</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox h-100 dashboard-card">
                    <div class="ibox-title">
                        <h5><i class="fa fa-folder"></i> Doanh thu theo danh mục</h5>
                    </div>
                    <div class="ibox-content">
                        @if($categoryRevenue->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Danh mục</th>
                                            <th class="text-right">Doanh thu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categoryRevenue as $cat)
                                            <tr>
                                                <td>
                                                    <i class="fa fa-folder text-primary"></i>
                                                    {{ $cat->category_name }}
                                                </td>
                                                <td class="text-right">
                                                    <span class="label label-success">
                                                        {{ number_format($cat->revenue) }} VND
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center">
                                <i class="fa fa-info-circle fa-3x text-muted"></i>
                                <p class="text-muted">Chưa có dữ liệu doanh thu theo danh mục</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="ibox dashboard-card">
                    <div class="ibox-title">
                        <h5><i class="fa fa-list"></i> Đơn hàng theo trạng thái</h5>
                    </div>
                    <div class="ibox-content">
                        @if($orderStatusCount->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Trạng thái</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Tỷ lệ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalOrdersStatus = $orderStatusCount->sum('total_orders');
                                        @endphp
                                        @foreach ($orderStatusCount as $item)
                                            <tr>
                                                <td>
                                                    <span class="label label-{{ $item->status == 'completed' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                                        <i class="fa fa-{{ $item->status == 'completed' ? 'check' : ($item->status == 'pending' ? 'clock-o' : 'times') }}"></i>
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ $item->total_orders }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-info">
                                                        {{ $totalOrdersStatus > 0 ? round(($item->total_orders / $totalOrdersStatus) * 100, 1) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center">
                                <i class="fa fa-info-circle fa-3x text-muted"></i>
                                <p class="text-muted">Chưa có dữ liệu đơn hàng theo trạng thái</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Cards */
.dashboard-card {
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.dashboard-card .ibox-title {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 15px 20px;
    border: none;
}

.dashboard-card .ibox-title h5 {
    color: white;
    margin: 0;
    font-weight: 600;
}

.dashboard-card .ibox-title .label {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    font-weight: 500;
}

.dashboard-card .ibox-content {
    padding: 25px 20px;
    background: white;
}

/* Form Styles */
.form-horizontal .form-group {
    margin-bottom: 15px;
}

.form-horizontal .control-label {
    font-weight: 600;
    color: #676a6c;
    margin-bottom: 8px;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e7eaec;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Alert Styles */
.alert-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    border-radius: 12px;
    padding: 15px 20px;
}

.alert-info i {
    margin-right: 8px;
}

/* Table Styles */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.table-bordered {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.table-bordered thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    font-weight: 600;
}

/* List Group Styles */
.list-group-item {
    border: none;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.list-group-item:hover {
    background-color: white;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.list-group-item .badge {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .form-horizontal .col-md-3 {
        margin-bottom: 15px;
    }

    .dashboard-card {
        margin-bottom: 15px;
    }

    .ibox-content {
        padding: 15px;
    }
}
</style>


@endsection

@section('scripts')
    <script src="{{ asset('admin/js/plugins/chartJs/Chart.min.js') }}"></script>
        <script src="{{ asset('js/chart-dashboard.js') }}"></script>

    <script>
    console.log('Script tag loaded');
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Statistics page script loaded');

        const filterType = document.getElementById('filter_type');
        const filterValueDiv = document.getElementById('filter_value_div');
        const startDateDiv = document.getElementById('start_date_div');
        const endDateDiv = document.getElementById('end_date_div');
        const filterValue = document.getElementById('filter_value');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        // Debug: Check if elements exist
        console.log('Filter type element:', filterType);
        console.log('Filter value div:', filterValueDiv);
        console.log('Start date div:', startDateDiv);
        console.log('End date div:', endDateDiv);

                function updateFields() {
            const type = filterType.value;
            console.log('Filter type changed to:', type);

            // Hide all fields first with !important
            filterValueDiv.style.setProperty('display', 'none', 'important');
            startDateDiv.style.setProperty('display', 'none', 'important');
            endDateDiv.style.setProperty('display', 'none', 'important');

            // Remove required attributes
            filterValue.removeAttribute('required');
            startDate.removeAttribute('required');
            endDate.removeAttribute('required');

            if (type === 'custom') {
                // Show date fields for custom range
                startDateDiv.style.setProperty('display', 'block', 'important');
                endDateDiv.style.setProperty('display', 'block', 'important');
                startDate.setAttribute('required', 'required');
                endDate.setAttribute('required', 'required');
                console.log('Showing date fields for custom range');
            } else if (type && type !== '') {
                // Show value field for other filters
                filterValueDiv.style.setProperty('display', 'block', 'important');
                filterValue.setAttribute('required', 'required');
                console.log('Showing value field for:', type);
            } else {
                console.log('Hiding all fields for "Tất cả"');
            }
        }

                // Initialize based on current value
        const currentFilterType = filterType.value;
        console.log('Initial filter type:', currentFilterType);

        if (currentFilterType === 'custom') {
            startDateDiv.style.setProperty('display', 'block', 'important');
            endDateDiv.style.setProperty('display', 'block', 'important');
            startDate.setAttribute('required', 'required');
            endDate.setAttribute('required', 'required');
            console.log('Initial state: showing date fields');
        } else if (currentFilterType && currentFilterType !== '') {
            filterValueDiv.style.setProperty('display', 'block', 'important');
            filterValue.setAttribute('required', 'required');
            console.log('Initial state: showing value field');
        }

        // Add change event listener
        filterType.addEventListener('change', updateFields);

        // Date picker for filter_value (if jQuery is available)
        if (typeof $ !== 'undefined') {
            $(filterValue).datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        }

        console.log('Statistics page initialization completed');

        // Debug form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submitted');
            console.log('Filter type:', filterType.value);
            console.log('Filter value:', filterValue.value);
            console.log('Start date:', startDate.value);
            console.log('End date:', endDate.value);

            // Validate form
            const type = filterType.value;

            if (type === 'custom') {
                const startVal = startDate.value;
                const endVal = endDate.value;

                if (!startVal || !endVal) {
                    e.preventDefault();
                    alert('Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc!');
                    return false;
                }

                if (new Date(startVal) > new Date(endVal)) {
                    e.preventDefault();
                    alert('Ngày bắt đầu không thể lớn hơn ngày kết thúc!');
                    return false;
                }
            } else if (type && type !== '') {
                const filterVal = filterValue.value;
                if (!filterVal) {
                    e.preventDefault();
                    alert('Vui lòng nhập giá trị cho loại lọc đã chọn!');
                    return false;
                }
            }

            console.log('Form validation passed, submitting...');
        });
    });
    </script>
@endsection
