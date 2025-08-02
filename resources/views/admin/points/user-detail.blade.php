@extends('layouts.admin')

@section('title', 'Chi Tiết User - ' . $user->name)

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-user text-info"></i> Chi Tiết User: {{ $user->name }}</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.points.users') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Thông tin user -->
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Thông Tin User</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID:</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày tham gia:</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4>Thống Kê Điểm</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="widget style1 bg-primary">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-star fa-2x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span> Tổng điểm</span>
                                                <h2 class="font-bold">{{ number_format($pointStats['total_points']) }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="widget style1 bg-success">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-plus fa-2x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span> Đã tích</span>
                                                <h2 class="font-bold">{{ number_format($pointStats['earned_points']) }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="widget style1 bg-warning">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-minus fa-2x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span> Đã dùng</span>
                                                <h2 class="font-bold">{{ number_format($pointStats['used_points']) }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="widget style1 bg-danger">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-clock-o fa-2x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span> Hết hạn</span>
                                                <h2 class="font-bold">{{ number_format($pointStats['expired_points']) }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Level VIP -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Level VIP</h4>
                            <div class="alert alert-info">
                                <strong>Level hiện tại:</strong>
                                <span class="label label-{{
                                    $pointStats['vip_level'] === 'Bronze' ? 'default' :
                                    ($pointStats['vip_level'] === 'Silver' ? 'info' :
                                    ($pointStats['vip_level'] === 'Gold' ? 'warning' :
                                    ($pointStats['vip_level'] === 'Platinum' ? 'primary' : 'danger')))
                                }}">
                                    {{ $pointStats['vip_level'] }}
                                </span>
                                <br>
                                <strong>Tỷ lệ tích điểm:</strong> {{ ($pointStats['point_rate'] * 100) }}%
                            </div>
                        </div>
                    </div>

                    <!-- Lịch sử giao dịch -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Lịch Sử Giao Dịch Điểm</h4>
                            @if(count($pointHistory) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Thời gian</th>
                                                <th>Loại</th>
                                                <th>Điểm</th>
                                                <th>Mô tả</th>
                                                <th>Tham chiếu</th>
                                                <th>Hết hạn</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pointHistory as $transaction)
                                                <tr>
                                                    <td>{{ $transaction['created_at'] }}</td>
                                                    <td>
                                                        @switch($transaction['type'])
                                                            @case('earn')
                                                                <span class="label label-success">Tích điểm</span>
                                                                @break
                                                            @case('use')
                                                                <span class="label label-warning">Sử dụng</span>
                                                                @break
                                                            @case('expire')
                                                                <span class="label label-danger">Hết hạn</span>
                                                                @break
                                                            @case('bonus')
                                                                <span class="label label-info">Thưởng</span>
                                                                @break
                                                            @default
                                                                <span class="label label-default">{{ $transaction['type'] }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @if($transaction['type'] === 'earn' || $transaction['type'] === 'bonus')
                                                            <span class="text-success">+{{ number_format($transaction['points']) }}</span>
                                                        @else
                                                            <span class="text-danger">-{{ number_format($transaction['points']) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $transaction['description'] }}</small>
                                                    </td>
                                                    <td>
                                                        @if($transaction['reference'])
                                                            @if($transaction['reference']['type'] === 'order')
                                                                <a href="{{ route('admin.order.show', $transaction['reference']['id']) }}"
                                                                   class="btn btn-xs btn-default">
                                                                    <i class="fa fa-shopping-cart"></i> Đơn hàng #{{ $transaction['reference']['id'] }}
                                                                </a>
                                                            @elseif($transaction['reference']['type'] === 'voucher')
                                                                <span class="label label-info">Voucher</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($transaction['expiry_date'])
                                                            <small class="{{ $transaction['is_expired'] ? 'text-danger' : 'text-muted' }}">
                                                                {{ $transaction['expiry_date'] }}
                                                            </small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($transaction['is_expired'])
                                                            <span class="label label-danger">Hết hạn</span>
                                                        @else
                                                            <span class="label label-success">Còn hiệu lực</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center">
                                    <i class="fa fa-history fa-3x text-muted"></i>
                                    <p class="text-muted">Chưa có giao dịch nào</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Voucher đã đổi -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Voucher Đã Đổi</h4>
                            @if($userVouchers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Voucher</th>
                                                <th>Mã voucher</th>
                                                <th>Ngày đổi</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userVouchers as $userVoucher)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $userVoucher->pointVoucher->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $userVoucher->pointVoucher->description }}</small>
                                                    </td>
                                                    <td>
                                                        <code>{{ $userVoucher->voucher_code }}</code>
                                                    </td>
                                                    <td>
                                                        <small>{{ $userVoucher->created_at->format('d/m/Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($userVoucher->is_used)
                                                            <span class="label label-success">Đã sử dụng</span>
                                                        @else
                                                            <span class="label label-warning">Chưa sử dụng</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center">
                                    {{ $userVouchers->links() }}
                                </div>
                            @else
                                <div class="text-center">
                                    <i class="fa fa-gift fa-3x text-muted"></i>
                                    <p class="text-muted">Chưa đổi voucher nào</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
