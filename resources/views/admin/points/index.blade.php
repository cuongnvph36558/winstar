@extends('layouts.admin')

@section('title', 'Quản Lý Điểm Tích Lũy')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-star text-warning"></i> Quản Lý Điểm Tích Lũy</h5>
                </div>
                <div class="ibox-content">
                    <!-- Thống kê tổng quan -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-primary">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-users fa-2x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span class="font-bold">{{ number_format($totalUsers) }}</span>
                                        <h3 class="font-bold">Tổng Users</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-success">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-star fa-2x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span class="font-bold">{{ number_format($usersWithPoints) }}</span>
                                        <h3 class="font-bold">Có Điểm</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-info">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-plus fa-2x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span class="font-bold">{{ number_format($totalEarnedPoints) }}</span>
                                        <h3 class="font-bold">Điểm Đã Tích</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="widget style1 bg-warning">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-minus fa-2x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span class="font-bold">{{ number_format($totalUsedPoints) }}</span>
                                        <h3 class="font-bold">Điểm Đã Dùng</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Top Users -->
                        <div class="col-lg-6">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-trophy text-warning"></i> Top Users Có Nhiều Điểm</h5>
                                </div>
                                <div class="ibox-content">
                                    @if($topUsers->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>User</th>
                                                        <th>Level VIP</th>
                                                        <th>Điểm</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topUsers as $index => $point)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.points.user-detail', $point->user) }}">
                                                                    {{ $point->user->name }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <span class="label label-{{ $point->vip_level === 'Bronze' ? 'default' : ($point->vip_level === 'Silver' ? 'info' : ($point->vip_level === 'Gold' ? 'warning' : 'danger')) }}">
                                                                    {{ $point->vip_level }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <strong>{{ number_format($point->total_points) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="fa fa-trophy fa-3x text-muted"></i>
                                            <p class="text-muted">Chưa có dữ liệu</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Thống kê VIP -->
                        <div class="col-lg-6">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-chart-pie text-info"></i> Thống Kê Level VIP</h5>
                                </div>
                                <div class="ibox-content">
                                    @if($vipStats->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Level VIP</th>
                                                        <th>Số lượng</th>
                                                        <th>Tỷ lệ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($vipStats as $stat)
                                                        <tr>
                                                            <td>
                                                                <span class="label label-{{ $stat->vip_level === 'Bronze' ? 'default' : ($stat->vip_level === 'Silver' ? 'info' : ($stat->vip_level === 'Gold' ? 'warning' : 'danger')) }}">
                                                                    {{ $stat->vip_level }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $stat->count }}</td>
                                                            <td>{{ round(($stat->count / $usersWithPoints) * 100, 1) }}%</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="fa fa-chart-pie fa-3x text-muted"></i>
                                            <p class="text-muted">Chưa có dữ liệu</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Giao dịch gần đây -->
                        <div class="col-lg-8">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-history text-success"></i> Giao Dịch Điểm Gần Đây</h5>
                                </div>
                                <div class="ibox-content">
                                    @if($recentTransactions->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Thời gian</th>
                                                        <th>User</th>
                                                        <th>Loại</th>
                                                        <th>Điểm</th>
                                                        <th>Mô tả</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentTransactions as $transaction)
                                                        <tr>
                                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.points.user-detail', $transaction->user) }}">
                                                                    {{ $transaction->user->name }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                @switch($transaction->type)
                                                                    @case('earn')
                                                                        <span class="label label-success">Tích điểm</span>
                                                                        @break
                                                                    @case('use')
                                                                        <span class="label label-warning">Sử dụng</span>
                                                                        @break
                                                                    @case('bonus')
                                                                        <span class="label label-info">Thưởng</span>
                                                                        @break
                                                                    @default
                                                                        <span class="label label-default">{{ $transaction->type }}</span>
                                                                @endswitch
                                                            </td>
                                                            <td>
                                                                <span class="font-bold {{ $transaction->points > 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $transaction->description }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.points.transactions') }}" class="btn btn-outline-primary">
                                                Xem tất cả giao dịch
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="fa fa-history fa-3x text-muted"></i>
                                            <p class="text-muted">Chưa có giao dịch nào</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Top Coupons -->
                        <div class="col-lg-4">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-ticket text-danger"></i> Mã Giảm Giá Được Sử Dụng Nhiều</h5>
                                </div>
                                <div class="ibox-content">
                                    @if($topCoupons->count() > 0)
                                        <div class="list-group">
                                            @foreach($topCoupons as $coupon)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">{{ $coupon->name }}</h6>
                                                            <small class="text-muted">{{ $coupon->description }}</small>
                                                        </div>
                                                        <span class="badge badge-primary">{{ $coupon->coupon_users_count }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.points.coupons') }}" class="btn btn-outline-primary">
                                                Quản lý mã giảm giá
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="fa fa-ticket fa-3x text-muted"></i>
                                            <p class="text-muted">Chưa có mã giảm giá nào</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-cogs text-info"></i> Hành Động</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.points.users') }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-users"></i> Quản lý Users
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.points.coupons') }}" class="btn btn-success btn-block">
                                                <i class="fa fa-ticket"></i> Quản lý Mã Giảm Giá
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.points.transactions') }}" class="btn btn-info btn-block">
                                                <i class="fa fa-history"></i> Lịch sử giao dịch
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <form action="{{ route('admin.points.process-expired') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-block">
                                                    <i class="fa fa-clock-o"></i> Xử lý điểm hết hạn
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
