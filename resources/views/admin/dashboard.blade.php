@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><i class="fa fa-dashboard"></i> Dashboard</h2>
        <ol class="breadcrumb">
            <li class="active">
                <strong>Dashboard</strong>
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

    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <span class="label label-success pull-right">
                        <i class="fa fa-users"></i> Tổng cộng
                    </span>
                    <h5><i class="fa fa-user"></i> Người dùng</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ App\Models\User::count() }}</h1>
                    <div class="stat-percent font-bold text-success">
                        <i class="fa fa-user-circle"></i> {{ App\Models\User::where('status', 1)->count() }}
                    </div>
                    <small>Người dùng hoạt động</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <span class="label label-info pull-right">
                        <i class="fa fa-cubes"></i> Tổng cộng
                    </span>
                    <h5><i class="fa fa-cube"></i> Sản phẩm</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ App\Models\Product::count() }}</h1>
                    <div class="stat-percent font-bold text-info">
                        <i class="fa fa-check-circle"></i> {{ App\Models\Product::where('status', 1)->count() }}
                    </div>
                    <small>Sản phẩm đang bán</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">
                        <i class="fa fa-shopping-cart"></i> Tổng cộng
                    </span>
                    <h5><i class="fa fa-shopping-bag"></i> Đơn hàng</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ App\Models\Order::count() }}</h1>
                    <div class="stat-percent font-bold text-navy">
                        <i class="fa fa-check"></i> {{ App\Models\Order::where('status', 'completed')->count() }}
                    </div>
                    <small>Đơn hàng hoàn thành</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">
                        <i class="fa fa-money"></i> Tổng cộng
                    </span>
                    <h5><i class="fa fa-credit-card"></i> Doanh thu</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ number_format(App\Models\Order::where('status', 'completed')->sum('total_amount')) }} VND</h1>
                    <div class="stat-percent font-bold text-danger">
                        <i class="fa fa-credit-card"></i> {{ App\Models\Order::where('payment_status', 'paid')->count() }}
                    </div>
                    <small>Đơn hàng đã thanh toán</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê chi tiết -->
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-bar-chart"></i> Thống kê đơn hàng theo trạng thái</h5>
                </div>
                <div class="ibox-content">
                    @php
                        try {
                            $orderStats = DB::table('view_order_status_count')->get();
                        } catch (Exception $e) {
                            $orderStats = collect();
                        }
                    @endphp
                    @if($orderStats->count() > 0)
                        <div class="row">
                            @foreach($orderStats as $stat)
                                <div class="col-sm-6 col-xs-12">
                                    <div class="widget style1 {{ $stat->status == 'completed' ? 'navy-bg' : ($stat->status == 'pending' ? 'yellow-bg' : 'red-bg') }}">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-shopping-cart fa-2x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span class="font-bold">{{ $stat->count }}</span>
                                                <h3 class="font-bold">{{ ucfirst($stat->status) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-info-circle fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có dữ liệu thống kê đơn hàng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-users"></i> Thống kê người dùng</h5>
                </div>
                <div class="ibox-content">
                    @php
                        try {
                            $userStats = DB::table('view_user_status_count')->get();
                        } catch (Exception $e) {
                            $userStats = collect();
                        }
                    @endphp
                    @if($userStats->count() > 0)
                        <div class="row">
                            @foreach($userStats as $stat)
                                <div class="col-sm-6 col-xs-12">
                                    <div class="widget style1 {{ $stat->status == 1 ? 'blue-bg' : 'gray-bg' }}">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-users fa-2x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span class="font-bold">{{ $stat->count }}</span>
                                                <h3 class="font-bold">{{ $stat->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-info-circle fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có dữ liệu thống kê người dùng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm bán chạy và danh mục -->
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-star"></i> Sản phẩm bán chạy nhất</h5>
                </div>
                <div class="ibox-content">
                    @php
                        try {
                            $topProducts = DB::table('view_top_products')->limit(5)->get();
                        } catch (Exception $e) {
                            $topProducts = collect();
                        }
                    @endphp
                    @if($topProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-cube"></i> Sản phẩm</th>
                                        <th class="text-center"><i class="fa fa-chart-line"></i> Số lượng bán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $index => $product)
                                        <tr>
                                            <td>
                                                <span class="badge badge-primary">{{ $index + 1 }}</span>
                                                {{ $product->name }}
                                            </td>
                                            <td class="text-center">
                                                <span class="label label-success">{{ $product->total_sold }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-star-o fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có dữ liệu sản phẩm bán chạy</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-folder"></i> Danh mục sản phẩm</h5>
                </div>
                <div class="ibox-content">
                    @php
                        $categories = App\Models\Category::withCount('products')->get();
                    @endphp
                    @if($categories->count() > 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="nav nav-tabs">
                                    @foreach($categories as $index => $category)
                                        <li class="{{ $index === 0 ? 'active' : '' }}">
                                            <a data-toggle="tab" href="#tab-{{ $category->id }}">
                                                <i class="fa fa-folder"></i> {{ $category->name }}
                                                <span class="badge badge-primary">{{ $category->products_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    @foreach($categories as $index => $category)
                                        <div id="tab-{{ $category->id }}" class="tab-pane {{ $index === 0 ? 'active' : '' }}">
                                            <div class="text-center">
                                                <i class="fa fa-folder fa-3x text-primary"></i>
                                                <h3 class="text-primary">{{ $category->name }}</h3>
                                                <h2 class="font-bold text-success">{{ $category->products_count }}</h2>
                                                <p class="text-muted">Sản phẩm trong danh mục</p>

                                                @php
                                                    $categoryProducts = App\Models\Product::where('category_id', $category->id)
                                                        ->where('status', 1)
                                                        ->limit(3)
                                                        ->get();
                                                @endphp

                                                @if($categoryProducts->count() > 0)
                                                    <div class="m-t-md">
                                                        <h5><i class="fa fa-cube"></i> Sản phẩm tiêu biểu:</h5>
                                                        <div class="list-group">
                                                            @foreach($categoryProducts as $product)
                                                                <div class="list-group-item">
                                                                    <div class="row">
                                                                        <div class="col-xs-8">
                                                                            <strong>{{ $product->name }}</strong>
                                                                            <br>
                                                                            <small class="text-muted">{{ number_format($product->price) }} VND</small>
                                                                        </div>
                                                                        <div class="col-xs-4 text-right">
                                                                            <span class="label label-info">{{ $product->view }}</span>
                                                                            <br>
                                                                            <small class="text-muted">lượt xem</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="m-t-md">
                                                        <i class="fa fa-info-circle fa-2x text-muted"></i>
                                                        <p class="text-muted">Chưa có sản phẩm trong danh mục này</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-folder-o fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có danh mục sản phẩm</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây và thông tin hệ thống -->
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
                                                <span class="label label-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                                    <i class="fa fa-{{ $order->status == 'completed' ? 'check' : ($order->status == 'pending' ? 'clock-o' : 'times') }}"></i>
                                                    {{ ucfirst($order->status) }}
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

        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-cogs"></i> Thông tin hệ thống</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <i class="fa fa-user-circle fa-3x text-primary"></i>
                                <h4>Xin chào, {{ Auth::user()->name }}!</h4>
                                <p class="text-muted">Bạn đã đăng nhập thành công vào hệ thống quản trị Winstar.</p>
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

                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <i class="fa fa-code text-primary"></i>
                                    <strong>Laravel Version:</strong> {{ app()->version() }}
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-server text-info"></i>
                                    <strong>PHP Version:</strong> {{ phpversion() }}
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-clock-o text-success"></i>
                                    <strong>Thời gian đăng nhập:</strong> {{ now()->format('d/m/Y H:i:s') }}
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-envelope text-warning"></i>
                                    <strong>Email:</strong> {{ Auth::user()->email }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê doanh thu -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins dashboard-card">
                <div class="ibox-title">
                    <h5><i class="fa fa-chart-pie"></i> Thống kê doanh thu theo danh mục</h5>
                </div>
                <div class="ibox-content">
                    @php
                        try {
                            $categoryRevenue = DB::table('view_revenue_by_category')->get();
                        } catch (Exception $e) {
                            $categoryRevenue = collect();
                        }
                    @endphp
                    @if($categoryRevenue->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-folder"></i> Danh mục</th>
                                        <th class="text-right"><i class="fa fa-money"></i> Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryRevenue as $revenue)
                                        <tr>
                                            <td>
                                                <i class="fa fa-folder text-primary"></i>
                                                <strong>{{ $revenue->category_name }}</strong>
                                            </td>
                                            <td class="text-right">
                                                <span class="label label-success">
                                                    <i class="fa fa-money"></i> {{ number_format($revenue->revenue) }} VND
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-chart-pie fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có dữ liệu doanh thu theo danh mục</p>
                        </div>
                    @endif
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

/* Widget Styles */
.widget.style1 {
    margin-bottom: 15px;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.widget.style1:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.widget.style1.navy-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.widget.style1.yellow-bg {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.widget.style1.red-bg {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
}

.widget.style1.blue-bg {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.widget.style1.gray-bg {
    background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);
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

.badge {
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 12px;
}

.list-group-item {
    border-left: none;
    border-right: none;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.btn-sm {
    margin: 3px;
    border-radius: 20px;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.text-muted {
    color: #6c757d;
}

.alert-dismissible .close {
    right: 0;
    top: 0;
    padding: 0.75rem 1.25rem;
}

/* Tab Styles */
.nav-tabs {
    border-bottom: 3px solid #e7eaec;
    margin-bottom: 20px;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
    padding: 10px 10px 0 10px;
}

.nav-tabs > li > a {
    border: none;
    color: #676a6c;
    font-weight: 600;
    padding: 12px 16px;
    margin-right: 5px;
    border-radius: 8px 8px 0 0;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.8);
}

.nav-tabs > li > a:hover {
    background-color: rgba(255,255,255,0.95);
    border: none;
    color: #676a6c;
    transform: translateY(-2px);
}

.nav-tabs > li.active > a,
.nav-tabs > li.active > a:focus,
.nav-tabs > li.active > a:hover {
    border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.nav-tabs > li.active > a .badge {
    background-color: white;
    color: #667eea;
}

.tab-content {
    padding: 20px 0;
    background: white;
    border-radius: 0 0 8px 8px;
}

.tab-pane {
    min-height: 250px;
    padding: 20px;
}

.tab-pane .text-center {
    padding: 30px 0;
}

.tab-pane h2 {
    margin: 15px 0;
    font-size: 3em;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.tab-pane h3 {
    margin: 15px 0;
    font-size: 1.8em;
    font-weight: 600;
}

.tab-pane .list-group {
    margin-top: 20px;
}

.tab-pane .list-group-item {
    border: 1px solid #e7eaec;
    margin-bottom: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.tab-pane .list-group-item:hover {
    background-color: white;
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.tab-pane .list-group-item .row {
    margin: 0;
}

.tab-pane .list-group-item .col-xs-8,
.tab-pane .list-group-item .col-xs-4 {
    padding: 12px 16px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .nav-tabs > li {
        float: none;
        display: block;
        margin-bottom: 5px;
    }

    .nav-tabs > li > a {
        border-radius: 8px;
        margin-right: 0;
        text-align: center;
    }

    .tab-pane {
        min-height: 200px;
    }

    .tab-pane h2 {
        font-size: 2.5em;
    }

    .tab-pane h3 {
        font-size: 1.5em;
    }

    .dashboard-card {
        margin-bottom: 15px;
    }
}

@media (max-width: 480px) {
    .nav-tabs > li > a {
        padding: 10px 12px;
        font-size: 13px;
    }

    .tab-pane .list-group-item .col-xs-8,
    .tab-pane .list-group-item .col-xs-4 {
        padding: 10px 12px;
    }

    .tab-pane h2 {
        font-size: 2.2em;
    }

    .tab-pane h3 {
        font-size: 1.3em;
    }

    .ibox-content {
        padding: 15px;
    }
}

/* Animation effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-card {
    animation: fadeInUp 0.6s ease-out;
}

.dashboard-card:nth-child(1) { animation-delay: 0.1s; }
.dashboard-card:nth-child(2) { animation-delay: 0.2s; }
.dashboard-card:nth-child(3) { animation-delay: 0.3s; }
.dashboard-card:nth-child(4) { animation-delay: 0.4s; }

/* Custom scrollbar */
.ibox-content::-webkit-scrollbar {
    width: 6px;
}

.ibox-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.ibox-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.ibox-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection
