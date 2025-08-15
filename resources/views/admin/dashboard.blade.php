@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('styles')
<link href="{{ asset('admin/css/dashboard-stats.css') }}" rel="stylesheet">
<link href="{{ asset('admin/css/custom-stats.css') }}" rel="stylesheet">
<style>
/* Form layout balance */
.form-horizontal .form-group {
    margin-bottom: 15px;
}

.form-horizontal .control-label {
    font-weight: 600;
    color: #676a6c;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e7eaec;
    transition: all 0.3s ease;
    height: 38px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-group {
    display: flex;
    gap: 10px;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
    transition: all 0.3s ease;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn i {
    margin-right: 5px;
}

/* Ensure equal height for form elements */
.form-group {
    display: flex;
    flex-direction: column;
}

.form-group > div {
    flex: 1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        gap: 5px;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 5px;
    }
}

/* Top Products List */
.top-products-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Customer Rank */
.customer-rank {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    color: white;
}

.customer-rank.rank-1 { background: #ffd700; }
.customer-rank.rank-2 { background: #c0c0c0; }
.customer-rank.rank-3 { background: #cd7f32; }
.customer-rank.rank-4, .customer-rank.rank-5 { background: #6c757d; }
.customer-rank.rank-6, .customer-rank.rank-7, .customer-rank.rank-8, .customer-rank.rank-9, .customer-rank.rank-10 { background: #495057; }

.customer-info {
    line-height: 1.4;
}

.customer-info strong {
    color: #333;
    font-size: 14px;
}

.customer-info small {
    font-size: 12px;
}

/* VIP Level Labels */
.label.label-danger { background-color: #d9534f; }
.label.label-primary { background-color: #337ab7; }
.label.label-warning { background-color: #f0ad4e; }
.label.label-info { background-color: #5bc0de; }
.label.label-secondary { background-color: #6c757d; }

.label {
    color: white;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.top-products-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-products-list li:last-child {
    border-bottom: none;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-rank {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    color: white;
}

.rank-1 { background: #ffd700; }
.rank-2 { background: #c0c0c0; }
.rank-3 { background: #cd7f32; }
.rank-4, .rank-5 { background: #6c757d; }

.product-name {
    font-weight: 500;
}

.product-sales {
    font-size: 12px;
    color: #666;
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 12px;
}

/* Coupons List */
.coupons-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.coupons-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.coupons-list li:last-child {
    border-bottom: none;
}

.coupon-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.coupon-rank {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    color: white;
}

.coupon-code {
    font-weight: 500;
    font-family: monospace;
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 4px;
}

.coupon-usage {
    font-size: 12px;
    color: #666;
    background: #e9ecef;
    padding: 4px 8px;
    border-radius: 12px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 3em;
    margin-bottom: 10px;
    opacity: 0.5;
}

/* Chart Container */
.chart-container {
    width: 100%;
    height: 350px;
    position: relative;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.chart-container canvas {
    border-radius: 12px;
    background: transparent;
}

/* Chart Animation */
@keyframes chartFadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chart-container {
    animation: chartFadeIn 0.8s ease-out;
}

/* Mini Chart Container */
.mini-chart-container {
    height: 200px;
    position: relative;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(102, 126, 234, 0.1);
}

/* Chart Stats */
.chart-stats {
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 10px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: white;
}

.stat-icon.completed {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.stat-icon.pending {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.stat-icon.processing {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    line-height: 1;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
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

.alert-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    border-radius: 12px;
    padding: 15px 20px;
}

.alert-success i {
    margin-right: 8px;
}

.alert small {
    opacity: 0.9;
    font-size: 13px;
}
</style>
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><i class="fa fa-dashboard"></i> Bảng điều khiển</h2>
        <ol class="breadcrumb">
            <li class="active">
                <strong>Bảng điều khiển</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-sign-out"></i> Đăng xuất
            </button>
        </form>
    </div>
</div>

<div class="wrapper wrapper-content">
    @if(session('success'))
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

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
                                    <label class="control-label">Từ ngày:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control"
                                           value="{{ request('start_date') }}" min="2020-01-01" max="2030-12-31">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Đến ngày:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control"
                                           value="{{ request('end_date') }}" min="2020-01-01" max="2030-12-31">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-primary" id="filter-submit-btn">
                                            <i class="fa fa-search"></i> Lọc dữ liệu
                                        </button>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-default">
                                            <i class="fa fa-refresh"></i> Làm mới
                                        </a>
                                    </div>
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
                <br>
                <small><i class="fa fa-filter"></i> Tất cả thống kê bên dưới đều được lọc theo khoảng thời gian này</small>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success">
                <i class="fa fa-chart-bar"></i>
                <strong>Thống kê tổng quan:</strong>
                Hiển thị tất cả dữ liệu từ khi bắt đầu hoạt động
                <br>
                <small><i class="fa fa-calendar"></i> Sử dụng bộ lọc thời gian bên trên để xem thống kê theo khoảng thời gian cụ thể</small>
                </div>
            </div>
        </div>
    @endif

    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title d-flex justify-content-between">
                    <h5><i class="fa fa-money"></i> Doanh thu</h5>
                    <span class="label label-success">VND</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format($totalRevenue ?? App\Models\Order::where('status', 'completed')->sum('total_amount')) }}</h1>
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

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title d-flex justify-content-between">
                    <h5><i class="fa fa-shopping-cart"></i> Đơn hàng</h5>
                    <span class="label label-info">Đơn hàng</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $totalOrders ?? App\Models\Order::count() }}</h1>
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

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title d-flex justify-content-between">
                    <h5><i class="fa fa-users"></i> Người dùng</h5>
                    <span class="label label-primary">Người dùng</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $totalUsers ?? App\Models\User::count() }}</h1>
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

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title d-flex justify-content-between">
                    <h5><i class="fa fa-cube"></i> Sản phẩm</h5>
                    <span class="label label-warning">Sản phẩm</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $totalProducts ?? App\Models\Product::count() }}</h1>
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

    <!-- Biểu đồ và thống kê chi tiết -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="ibox h-100 dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-chart-line"></i> 
                        @if(request('start_date') && request('end_date'))
                            Biểu đồ doanh thu theo ngày
                        @else
                            Biểu đồ doanh thu 30 ngày gần nhất
                        @endif
                    </h5>
                </div>
                                    <div class="ibox-content">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                        <!-- Thêm biểu đồ tròn cho trạng thái đơn hàng -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="mini-chart-container">
                                    <canvas id="orderStatusChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart-stats">
                                    <div class="stat-item">
                                        <div class="stat-icon completed">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-value" id="completedOrders">0</div>
                                            <div class="stat-label">Hoàn thành</div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon pending">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-value" id="pendingOrders">0</div>
                                            <div class="stat-label">Chờ xử lý</div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon processing">
                                            <i class="fa fa-cogs"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-value" id="processingOrders">0</div>
                                            <div class="stat-label">Đang xử lý</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox h-100 top-products-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-star"></i> Top 5 sản phẩm bán chạy nhất
                        @if(request('start_date') && request('end_date'))
                            <small class="text-white-50">(trong khoảng thời gian)</small>
                        @endif
                    </h5>
                </div>
                <div class="ibox-content">
                    @if(isset($topProducts) && $topProducts->count() > 0)
                        <ul class="top-products-list">
                            @foreach ($topProducts->take(5) as $index => $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="product-info">
                                        <span class="product-rank rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                        <span class="product-name">{{ $product->variant_name ?? $product->name }}</span>
                                    </div>
                                    <span class="product-sales">{{ $product->total_sold }} đã bán</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state">
                            <i class="fa fa-info-circle"></i>
                            <p>Chưa có dữ liệu sản phẩm bán chạy</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê chi tiết -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="ibox h-100 coupons-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-ticket"></i> Top mã giảm giá được sử dụng nhiều nhất
                        @if(request('start_date') && request('end_date'))
                            <small class="text-white-50">(trong khoảng thời gian)</small>
                        @endif
                    </h5>
                </div>
                <div class="ibox-content">
                    @if(isset($topCoupons) && $topCoupons->count() > 0)
                        <ul class="coupons-list">
                            @foreach ($topCoupons->take(5) as $index => $coupon)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="coupon-info">
                                        <span class="coupon-rank rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                        <span class="coupon-code">{{ $coupon->code }}</span>
                                    </div>
                                    <span class="coupon-usage">{{ $coupon->total_usage ?? $coupon->used_count }} lần sử dụng</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state">
                            <i class="fa fa-info-circle"></i>
                            <p>Chưa có dữ liệu mã giảm giá</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="ibox h-100 dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-folder"></i> Doanh thu theo danh mục sản phẩm
                        @if(request('start_date') && request('end_date'))
                            <small class="text-white-50">(trong khoảng thời gian)</small>
                        @endif
                    </h5>
                </div>
                <div class="ibox-content">
                    @if(isset($categoryRevenue) && $categoryRevenue->count() > 0)
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

    <!-- Top khách hàng mua hàng nhiều nhất -->
    <div class="row g-4 mb-4">
        <div class="col-lg-12">
            <div class="ibox dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-users"></i> Top 10 khách hàng mua hàng nhiều nhất
                        @if(request('start_date') && request('end_date'))
                            <small class="text-white-50">(trong khoảng thời gian)</small>
                        @endif
                    </h5>
                </div>
                <div class="ibox-content">
                    @if(isset($topCustomers) && $topCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Khách hàng</th>
                                        <th class="text-center">Số đơn hàng</th>
                                        <th class="text-right">Tổng chi tiêu</th>
                                        <th class="text-right">Giá trị TB/đơn</th>
                                        <th class="text-center">Điểm hiện tại</th>
                                        <th class="text-center">Hạng VIP</th>
                                        <th class="text-center">Đơn hàng cuối</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topCustomers as $index => $customer)
                                        <tr>
                                            <td class="text-center">
                                                <span class="customer-rank rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="customer-info">
                                                    <strong>{{ $customer->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fa fa-envelope"></i> {{ $customer->email }}
                                                        @if($customer->phone)
                                                            <br><i class="fa fa-phone"></i> {{ $customer->phone }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">{{ $customer->total_orders }}</span>
                                            </td>
                                            <td class="text-right">
                                                <strong class="text-success">{{ number_format($customer->total_spent) }} VND</strong>
                                            </td>
                                            <td class="text-right">
                                                <span class="text-muted">{{ number_format($customer->avg_order_value) }} VND</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-warning">{{ number_format($customer->current_points) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $vipColors = [
                                                        'Diamond' => 'danger',
                                                        'Platinum' => 'primary', 
                                                        'Gold' => 'warning',
                                                        'Silver' => 'info',
                                                        'Bronze' => 'secondary'
                                                    ];
                                                    $vipColor = $vipColors[$customer->vip_level] ?? 'secondary';
                                                @endphp
                                                <span class="label label-{{ $vipColor }}">{{ $customer->vip_level }}</span>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($customer->last_order_date)->format('d/m/Y') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-users fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có dữ liệu khách hàng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê đơn hàng theo trạng thái -->
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="ibox dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-list"></i> Thống kê đơn hàng theo trạng thái
                        @if(request('start_date') && request('end_date'))
                            <small class="text-white-50">(trong khoảng thời gian)</small>
                        @endif
                    </h5>
                </div>
                <div class="ibox-content">
                    @if(isset($orderStatusCount) && $orderStatusCount->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Trạng thái đơn hàng</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">Tỷ lệ phần trăm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalOrdersStatus = $orderStatusCount->sum('count');
                                    @endphp
                                    @foreach ($orderStatusCount as $item)
                                        <tr>
                                            <td>
                                                <span class="label label-{{ $item->status == 'completed' ? 'success' : ($item->status == 'pending' ? 'warning' : ($item->status == 'processing' ? 'info' : ($item->status == 'shipping' ? 'primary' : 'danger'))) }}">
                                                    <i class="fa fa-{{ $item->status == 'completed' ? 'check' : ($item->status == 'pending' ? 'clock-o' : ($item->status == 'processing' ? 'cogs' : ($item->status == 'shipping' ? 'truck' : 'times'))) }}"></i>
                                                    @if($item->status == 'completed')
                                                        Hoàn thành
                                                    @elseif($item->status == 'pending')
                                                        Chờ xử lý
                                                    @elseif($item->status == 'processing')
                                                        Đang chuẩn bị hàng
                                                    @elseif($item->status == 'shipping')
                                                        Đang giao hàng
                                                    @elseif($item->status == 'cancelled')
                                                        Đã hủy
                                                    @else
                                                        {{ ucfirst($item->status) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $item->count }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">
                                                    {{ $totalOrdersStatus > 0 ? round(($item->count / $totalOrdersStatus) * 100, 1) : 0 }}%
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

    <!-- Đơn hàng gần đây -->
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-clock-o"></i> Đơn hàng gần đây</h5>
                </div>
                <div class="ibox-content">
                    @php
                        $recentOrders = App\Models\Order::with('user')->latest()->limit(10)->get();
                    @endphp
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-hashtag"></i> Mã đơn hàng</th>
                                        <th><i class="fa fa-user"></i> Khách hàng</th>
                                        <th class="text-right"><i class="fa fa-money"></i> Tổng tiền</th>
                                        <th class="text-center"><i class="fa fa-info-circle"></i> Trạng thái</th>
                                        <th class="text-center"><i class="fa fa-calendar"></i> Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <strong>{{ $order->code_order }}</strong>
                                            </td>
                                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                                            <td class="text-right">
                                                <strong>{{ number_format($order->total_amount) }} VND</strong>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusConfig = [
                                                        'completed' => ['class' => 'success', 'icon' => 'check', 'text' => 'Hoàn thành'],
                                                        'pending' => ['class' => 'warning', 'icon' => 'clock-o', 'text' => 'Chờ xử lý'],
                                                        'processing' => ['class' => 'info', 'icon' => 'cogs', 'text' => 'Đang chuẩn bị hàng'],
                                                        'shipping' => ['class' => 'primary', 'icon' => 'truck', 'text' => 'Đang giao hàng'],
                                                        'cancelled' => ['class' => 'danger', 'icon' => 'times', 'text' => 'Đã hủy']
                                                    ];
                                                    $status = $statusConfig[$order->status] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => ucfirst($order->status)];
                                                @endphp
                                                <span class="label label-{{ $status['class'] }}">
                                                    <i class="fa fa-{{ $status['icon'] }}"></i>
                                                    {{ $status['text'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-shopping-cart fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có đơn hàng nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thao tác nhanh -->
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-cogs"></i> Thao tác nhanh</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <i class="fa fa-user-circle fa-3x text-primary"></i>
                                <h4>Xin chào, {{ Auth::user()->name }}!</h4>
                                <p class="text-muted">Chào mừng bạn đến với hệ thống quản trị Winstar.</p>
                            </div>

                            <div class="m-t-lg text-center">
                                <a href="{{ route('admin.category.index-category') }}" class="btn btn-primary btn-sm m-b-xs">
                                    <i class="fa fa-folder"></i> Quản lý danh mục
                                </a>
                                <a href="{{ route('admin.product.index-product') }}" class="btn btn-success btn-sm m-b-xs">
                                    <i class="fa fa-cube"></i> Quản lý sản phẩm
                                </a>
                                <a href="{{ route('admin.order.index') }}" class="btn btn-info btn-sm m-b-xs">
                                    <i class="fa fa-shopping-cart"></i> Quản lý đơn hàng
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-warning btn-sm m-b-xs">
                                    <i class="fa fa-users"></i> Quản lý người dùng
                                </a>
                            </div>
                        </div>
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

/* Ibox Styles */
.ibox {
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
}

.ibox-title {
    border-radius: 12px 12px 0 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border: none;
}

.ibox-title h5 {
    color: white;
    margin: 0;
    font-weight: 600;
}

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

/* Responsive */
@media (max-width: 768px) {
    .dashboard-card {
        margin-bottom: 15px;
    }

    .ibox-content {
        padding: 15px;
    }
    
    .chart-container {
        height: 250px;
    }
}
</style>

@endsection

@section('scripts')
    <script src="{{ asset('admin/js/plugins/chartJs/Chart.min.js') }}"></script>
    <script src="{{ asset('js/chart-final.js') }}"></script>
    
    <script>
    // Truyền dữ liệu từ PHP sang JavaScript
    window.chartData = {
        monthlyRevenue: @json($monthlyRevenue ?? []),
        paidRevenue: @json($paidRevenue ?? []),
        orderStatusCount: @json($orderStatusCount ?? [])
    };
    console.log('Chart data loaded:', window.chartData);
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard page script loaded');

        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        // Debug: Check if elements exist
        console.log('Start date element:', startDate);
        console.log('End date element:', endDate);
        
        // Kiểm tra xem các element có tồn tại không
        if (!startDate || !endDate) {
            console.error('Date elements not found!');
            return;
        }

        console.log('Dashboard page initialization completed');

        // Debug form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submitted');
            console.log('Start date:', startDate.value);
            console.log('End date:', endDate.value);

            // Validate form
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
            
            // Kiểm tra khoảng thời gian không quá 1 năm
            const startDateObj = new Date(startVal);
            const endDateObj = new Date(endVal);
            const diffTime = Math.abs(endDateObj - startDateObj);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 365) {
                e.preventDefault();
                alert('Khoảng thời gian không được vượt quá 1 năm!');
                return false;
            }

            console.log('Form validation passed, submitting...');
        });
    });
    </script>
@endsection
