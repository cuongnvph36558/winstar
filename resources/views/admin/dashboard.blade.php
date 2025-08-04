@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

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
                                <a href="{{ route('admin.statistics.index') }}" class="btn btn-danger btn-sm m-b-xs">
                                    <i class="fa fa-chart-bar"></i> Xem thống kê
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
}
</style>
@endsection
