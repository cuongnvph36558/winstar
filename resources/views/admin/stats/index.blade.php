@extends('layouts.admin')
@section('content')
    <form method="GET" class="form-inline mb-3" style="padding-left: 15px">
        <label class="mr-2">Chọn thời gian:</label>
        <select id="filter_type" name="filter_type" class="form-control mr-2">
            <option value="day" {{ request('filter_type') == 'day' ? 'selected' : '' }}>Theo ngày</option>
            <option value="week" {{ request('filter_type') == 'week' ? 'selected' : '' }}>Theo tuần</option>
            <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Theo tháng</option>
        </select>

        <input type="text" id="filter_value" name="filter_value" class="form-control mr-2"
            value="{{ request('filter_value', now()->toDateString()) }}">

        <button type="submit" class="btn btn-primary">Lọc</button>
    </form>
    <div class="wrapper wrapper-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Monthly</span>
                            <h5>Doanh thu tháng này</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{ number_format($monthlyRevenue->last()->total_revenue ?? 0) }}</h1>
                            <div class="stat-percent font-bold text-success"><i class="fa fa-bolt"></i></div>
                            <small>Tổng doanh thu</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">Đơn hàng</span>
                            <h5>Tổng đơn hàng</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{ $orderStatusCount->sum('total_orders') }}</h1>
                            <div class="stat-percent font-bold text-info"><i class="fa fa-shopping-cart"></i></div>
                            <small>Đơn hàng các trạng thái</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">Users</span>
                            <h5>Người dùng hoạt động</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">
                                {{ $userStatusCount->firstWhere('user_status', 'Active')->total_users ?? 0 }}
                            </h1>
                            <div class="stat-percent font-bold text-primary"><i class="fa fa-user"></i></div>
                            <small>Tài khoản đang hoạt động</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-warning pull-right">Products</span>
                            <h5>Sản phẩm tồn kho</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{ $totalStockPerProduct->sum('total_stock') }}</h1>
                            <div class="stat-percent font-bold text-warning"><i class="fa fa-archive"></i></div>
                            <small>Tổng số sản phẩm còn lại</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Biểu đồ doanh thu 6 tháng gần nhất</h5>
                        </div>
                        <div class="ibox-content">
                            <canvas id="revenueChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Top 5 sản phẩm bán chạy</h5>
                        </div>
                        <div class="ibox-content">
                            <ul class="list-group">
                                @foreach ($topProducts->take(5) as $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $product->variant_name }}
                                        <span class="badge badge-success">{{ $product->total_sold }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <!-- Thống kê mã giảm giá sử dụng nhiều -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Top mã giảm giá được sử dụng</h5>
                </div>
                <div class="ibox-content">
                    <ul class="list-group">
                        @foreach ($topCoupons->take(5) as $coupon)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $coupon->code }}
                                <span class="badge badge-info">{{ $coupon->total_usage }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Doanh thu theo danh mục -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Doanh thu theo danh mục</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Danh mục</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoryRevenue as $cat)
                                <tr>
                                    <td>{{ $cat->category_name }}</td>
                                    <td>{{ number_format($cat->revenue) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng đơn hàng theo trạng thái -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Đơn hàng theo trạng thái</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Trạng thái</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderStatusCount as $item)
                                <tr>
                                    <td>{{ ucfirst($item->status) }}</td>
                                    <td>{{ $item->total_orders }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/chart-dashboard.js') }}"></script>
@endsection
