@extends('layouts.admin')

@section('title', 'Thống kê - Dashboard')

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
</style>

@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><i class="fa fa-chart-bar"></i> Thống kê tổng quan</h2>
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
                                        <a href="{{ route('admin.statistics.index') }}" class="btn btn-default">
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
                        <span class="label label-primary">Người dùng</span>
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
                        <span class="label label-warning">Sản phẩm</span>
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
                        <h5><i class="fa fa-chart-line"></i> 
                            @if(request('start_date') && request('end_date'))
                                Biểu đồ doanh thu theo ngày
                            @else
                                Biểu đồ doanh thu 6 tháng gần nhất
                            @endif
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox h-100 top-products-card">
                    <div class="ibox-title">
                        <h5><i class="fa fa-star"></i> Top 5 sản phẩm bán chạy nhất</h5>
                    </div>
                    <div class="ibox-content">
                        @if($topProducts->count() > 0)
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

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="ibox h-100 coupons-card">
                    <div class="ibox-title">
                        <h5><i class="fa fa-ticket"></i> Top mã giảm giá được sử dụng nhiều nhất</h5>
                    </div>
                    <div class="ibox-content">
                        @if($topCoupons->count() > 0)
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
                        <h5><i class="fa fa-folder"></i> Doanh thu theo danh mục sản phẩm</h5>
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
                        <h5><i class="fa fa-list"></i> Thống kê đơn hàng theo trạng thái</h5>
                    </div>
                    <div class="ibox-content">
                        @if($orderStatusCount->count() > 0)
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

/* Chart Container */
.chart-container {
    width: 100%;
    height: 300px;
    position: relative;
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
    
    .chart-container {
        height: 250px;
    }
}
</style>


@endsection

@section('scripts')
    <script src="{{ asset('admin/js/plugins/chartJs/Chart.min.js') }}"></script>
    <script src="{{ asset('js/chart-dashboard.js') }}"></script>
    
    <script>

    
    // Truyền dữ liệu từ PHP sang JavaScript
    var chartData = {
        monthlyRevenue: @json($monthlyRevenue ?: []),
        paidRevenue: @json($paidRevenue ?: [])
    };
    console.log('Chart data loaded:', chartData);
    
    // Khởi tạo biểu đồ khi trang đã load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing chart...');
        if (typeof initRevenueChart === 'function') {
            console.log('initRevenueChart function found, calling...');
            initRevenueChart();
        } else {
            console.error('initRevenueChart function not found');
            // Tạo biểu đồ đơn giản để test
            createSimpleChart();
        }
    });
    
    // Hàm tạo biểu đồ đơn giản để test
    function createSimpleChart() {
        console.log('Creating empty chart...');
        const ctx = document.getElementById('revenueChart');
        if (!ctx) {
            console.error('Canvas not found');
            return;
        }
        
        // Hiển thị thông báo không có dữ liệu
        const chartContainer = ctx.parentElement;
        if (chartContainer) {
            const notice = document.createElement('div');
            notice.className = 'alert alert-warning mt-2';
            notice.innerHTML = '<i class="fa fa-exclamation-triangle"></i> Chưa có dữ liệu doanh thu. Biểu đồ sẽ hiển thị khi có đơn hàng.';
            chartContainer.appendChild(notice);
        }
        
        console.log('Empty chart notice displayed');
    }
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Statistics page script loaded');

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

        console.log('Statistics page initialization completed');

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
