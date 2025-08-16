@extends('layouts.admin')
@section('title', 'Chi tiết mã giảm giá')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chi tiết mã giảm giá</h2>
        <ol class="breadcrumb">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="{{ route('admin.coupon.index') }}">Mã giảm giá</a></li>
            <li class="active"><strong>Chi tiết</strong></li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary">
                <i class="fa fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <!-- Thông tin chính -->
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center m-b-lg">
                                <h1 class="text-primary font-bold">{{ $coupon->code }}</h1>
                                @if($coupon->name)
                                    <h3 class="text-muted">{{ $coupon->name }}</h3>
                                @endif
                                @if($coupon->description)
                                    <p class="text-muted">{{ $coupon->description }}</p>
                                @endif
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="widget style1 bg-primary">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-percent fa-3x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span>Giá trị giảm</span>
                                                <h2 class="font-bold">{{ number_format($coupon->discount_value) }}{{ $coupon->discount_type == 'percentage' ? '%' : '₫' }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="widget style1 bg-success">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <i class="fa fa-users fa-3x"></i>
                                            </div>
                                            <div class="col-xs-8 text-right">
                                                <span>Đã sử dụng</span>
                                                <h2 class="font-bold">{{ $coupon->couponUsers()->count() }}/{{ $coupon->usage_limit ?? '∞' }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-t-lg">
                                <div class="col-md-12">
                                    <h3>Thông tin chi tiết</h3>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Loại giảm giá:</strong></td>
                                                    <td>
                                                        <span class="label label-{{ $coupon->discount_type == 'percentage' ? 'info' : 'warning' }}">
                                                            {{ $coupon->discount_type == 'percentage' ? 'Phần trăm' : 'Số tiền cố định' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>VIP Level:</strong></td>
                                                    <td>
                                                        @if($coupon->vip_level)
                                                            <span class="label label-{{ $coupon->vip_level == 'Diamond' ? 'danger' : ($coupon->vip_level == 'Platinum' ? 'warning' : ($coupon->vip_level == 'Gold' ? 'success' : ($coupon->vip_level == 'Silver' ? 'info' : 'default'))) }}">
                                                                {{ $coupon->vip_level }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">Tất cả</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Đơn hàng tối thiểu:</strong></td>
                                                    <td>
                                                        @if($coupon->min_order_value)
                                                            <span class="text-success font-bold">{{ number_format($coupon->min_order_value) }}₫</span>
                                                        @else
                                                            <span class="text-muted">Không giới hạn</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Giảm giá tối đa:</strong></td>
                                                    <td>
                                                        @if($coupon->max_discount_value)
                                                            <span class="text-danger font-bold">{{ number_format($coupon->max_discount_value) }}₫</span>
                                                        @elseif($coupon->max_discount)
                                                            <span class="text-danger font-bold">{{ number_format($coupon->max_discount) }}₫</span>
                                                        @else
                                                            <span class="text-muted">Không giới hạn</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Điểm đổi:</strong></td>
                                                    <td>
                                                        @if($coupon->exchange_points)
                                                            <span class="badge badge-info">{{ number_format($coupon->exchange_points) }} điểm</span>
                                                        @else
                                                            <span class="text-muted">Không cần điểm</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Số lần sử dụng/người:</strong></td>
                                                    <td>
                                                        @if($coupon->usage_limit_per_user)
                                                            <span class="badge badge-warning">{{ $coupon->usage_limit_per_user }} lần</span>
                                                        @else
                                                            <span class="text-muted">Không giới hạn</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Trạng thái:</strong></td>
                                                    <td>
                                                        <span class="label {{ $coupon->status ? 'label-primary' : 'label-default' }}">
                                                            {{ $coupon->status ? 'Kích hoạt' : 'Tạm dừng' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin phụ -->
        <div class="col-lg-4">
            <!-- Thời gian hiệu lực -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-calendar"></i> Thời gian hiệu lực</h5>
                </div>
                <div class="ibox-content">
                    <div class="text-center">
                        <div class="m-b-sm">
                            <i class="fa fa-play-circle fa-3x text-success"></i>
                        </div>
                        <h4>Bắt đầu</h4>
                        <p class="text-success font-bold">{{ $coupon->start_date->format('d/m/Y H:i') }}</p>
                        
                        <hr>
                        
                        <div class="m-b-sm">
                            <i class="fa fa-stop-circle fa-3x text-danger"></i>
                        </div>
                        <h4>Kết thúc</h4>
                        <p class="text-danger font-bold">{{ $coupon->end_date->format('d/m/Y H:i') }}</p>
                        
                        @php
                            $now = now();
                            $isActive = $now->between($coupon->start_date, $coupon->end_date);
                            $isExpired = $now->gt($coupon->end_date);
                            $isFuture = $now->lt($coupon->start_date);
                        @endphp
                        
                        <div class="m-t-md">
                            @if($isActive)
                                <span class="label label-success">Đang hiệu lực</span>
                            @elseif($isExpired)
                                <span class="label label-danger">Đã hết hạn</span>
                            @elseif($isFuture)
                                <span class="label label-warning">Chưa có hiệu lực</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê sử dụng -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-bar-chart"></i> Thống kê sử dụng</h5>
                </div>
                <div class="ibox-content">
                    @if($coupon->usage_limit)
                        @php
                            $usageCount = $coupon->couponUsers()->count();
                            $percentage = round(($usageCount / $coupon->usage_limit) * 100);
                            $progressClass = $percentage >= 80 ? 'progress-bar-danger' : ($percentage >= 60 ? 'progress-bar-warning' : 'progress-bar-success');
                        @endphp
                        
                        <div class="text-center m-b-sm">
                            <h3>{{ $usageCount }}/{{ $coupon->usage_limit }}</h3>
                            <p class="text-muted">Lượt sử dụng</p>
                        </div>
                        
                        <div class="progress">
                            <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                 style="width: {{ $percentage }}%;" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $percentage }}%
                            </div>
                        </div>
                        
                        @if($percentage >= 80)
                            <div class="alert alert-warning m-t-sm">
                                <i class="fa fa-warning"></i> Mã giảm giá sắp hết lượt sử dụng!
                            </div>
                        @endif
                    @else
                        <div class="text-center">
                            <h3>{{ $coupon->couponUsers()->count() }}</h3>
                            <p class="text-muted">Lượt sử dụng (Không giới hạn)</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hành động -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-cogs"></i> Hành động</h5>
                </div>
                <div class="ibox-content">
                    <div class="btn-group-vertical btn-block">
                        <a href="{{ route('admin.coupon.index') }}" class="btn btn-default btn-block">
                            <i class="fa fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary btn-block">
                            <i class="fa fa-edit"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.coupon.delete', $coupon->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                                <i class="fa fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
