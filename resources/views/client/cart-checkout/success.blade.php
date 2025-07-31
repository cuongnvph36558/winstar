@extends('layouts.client')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3><i class="fa fa-check-circle text-success"></i> Đặt Hàng Thành Công!</h3>
                    <p>Cảm ơn bạn đã đặt hàng tại WINSTAR</p>
                </div>
                <div class="card-body">
                    <h5>Thông tin đơn hàng</h5>
                    <p><strong>Mã đơn hàng:</strong> {{ $order->code_order ?? '#' . $order->id }}</p>
                    <p><strong>Địa chỉ giao hàng:</strong> 
                        {{ $order->billing_address ?? '' }}
                        @if($order->billing_ward){{ ', ' . $order->billing_ward }}@endif
                        @if($order->billing_district){{ ', ' . $order->billing_district }}@endif
                        @if($order->billing_city){{ ', ' . $order->billing_city }}@endif
                    </p>
                    
                    <h5>Sản phẩm đã đặt</h5>
                    @if($order->orderDetails && $order->orderDetails->count() > 0)
                        @foreach($order->orderDetails as $detail)
                        <div class="border-bottom py-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <strong>{{ $detail->product_name ?? ($detail->product->name ?? 'Sản phẩm không xác định') }}</strong>
                                    @if($detail->variant && $detail->variant->color && $detail->variant->storage)
                                        <br><small class="text-muted">
                                            {{ $detail->variant->color->name ?? 'N/A' }} - {{ $detail->variant->storage->name ?? 'N/A' }}
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-2 text-center">
                                    x{{ $detail->quantity ?? 0 }}
                                </div>
                                <div class="col-md-2 text-right">
                                    {{ number_format($detail->total ?? 0, 0, ',', '.') }}đ
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">Không có sản phẩm nào</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Tổng quan đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($subtotal ?? 0, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span>{{ number_format($shipping ?? 0, 0, ',', '.') }}đ</span>
                    </div>
                    
                    @if(isset($couponDiscount) && $couponDiscount > 0 && $order->coupon)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá:</span>
                        <span>-{{ number_format($couponDiscount, 0, ',', '.') }}đ</span>
                    </div>
                    @endif
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-danger">{{ number_format($total ?? 0, 0, ',', '.') }}đ</strong>
                    </div>
                    
                    <hr>
                    <h6>Trạng thái đơn hàng</h6>
                    @switch($order->status ?? 'pending')
                        @case('pending')
                            <span class="badge badge-warning">Chờ xử lý</span>
                            @break
                        @case('processing')
                            <span class="badge badge-info">Đang xử lý</span>
                            @break
                        @case('shipping')
                            <span class="badge badge-primary">Đang giao hàng</span>
                            @break
                        @case('completed')
                            <span class="badge badge-success">Hoàn thành</span>
                            @break
                        @case('cancelled')
                            <span class="badge badge-danger">Đã hủy</span>
                            @break
                        @default
                            <span class="badge badge-secondary">{{ $order->status ?? 'Unknown' }}</span>
                    @endswitch
                    
                    <hr>
                    <h6>Thông tin thanh toán</h6>
                    <p><strong>Phương thức:</strong> 
                        @switch($order->payment_method ?? 'cod')
                            @case('cod')
                                Thanh toán khi nhận hàng (COD)
                                @break
                            @case('momo')
                                Ví MoMo
                                @break
                            @case('vnpay')
                                VNPay
                                @break
                            @default
                                {{ $order->payment_method ?? 'Unknown' }}
                        @endswitch
                    </p>
                    <p><strong>Trạng thái:</strong>
                        @switch($order->payment_status ?? 'pending')
                            @case('pending')
                                <span class="badge badge-warning">Chưa thanh toán</span>
                                @break
                            @case('paid')
                                <span class="badge badge-success">Đã thanh toán</span>
                                @break
                            @case('cancelled')
                                <span class="badge badge-danger">Đã hủy</span>
                                @break
                            @default
                                <span class="badge badge-secondary">{{ $order->payment_status ?? 'Unknown' }}</span>
                        @endswitch
                    </p>
                    
                    <div class="mt-3">
                        <a href="{{ route('client.order.track', $order) }}" class="btn btn-primary btn-block mb-2">
                            <i class="fa fa-truck mr-2"></i>Theo dõi đơn hàng
                        </a>
                        <a href="{{ route('client.product') }}" class="btn btn-outline-primary btn-block">
                            <i class="fa fa-shopping-cart mr-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 