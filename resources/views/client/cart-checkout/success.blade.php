@extends('layouts.client')

@section('title', 'Đặt hàng thành công')

@section('content')
<section class="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb font-alt">
                    <li><a href="{{ route('client.home') }}"><i class="fa fa-home"></i></a></li>
                    <li><a href="{{ route('client.product') }}">Sản phẩm</a></li>
                    <li class="active">Đặt hàng thành công</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <div class="success-message">
                    <div class="success-icon mb-30">
                        <i class="fa fa-check-circle" style="font-size: 60px; color: #28a745;"></i>
                    </div>
                    <h2 class="font-alt mb-30">Đặt hàng thành công!</h2>
                    <p class="lead">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
                    <div class="order-info mt-40">
                        <h4 class="font-alt">Thông tin đơn hàng #{{ $order->id }}</h4>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}đ</p>
                    </div>
                </div>
            </div>
        </div>

        @if($order->orderDetails && $order->orderDetails->count() > 0)
        <div class="row mt-40">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="order-details">
                    <h4 class="font-alt mb-20">Chi tiết đơn hàng</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-border checkout-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="hidden-xs">Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                    <tr>
                                        <td>
                                            <h5 class="product-title font-alt">{{ $detail->product->name }}</h5>
                                            @if($detail->variant)
                                                <div class="product-variant">
                                                    <small class="text-muted">
                                                        @if($detail->variant->color)
                                                            <span class="variant-color">Màu: {{ $detail->variant->color->name }}</span>
                                                        @endif
                                                        @if($detail->variant->storage)
                                                            @if($detail->variant->color)
                                                                <span class="mx-1">-</span>
                                                            @endif
                                                            <span class="variant-storage">Bộ nhớ: {{ $detail->variant->storage->name }}</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="hidden-xs">
                                            <h5 class="product-title font-alt">{{ number_format($detail->price) }}đ</h5>
                                        </td>
                                        <td>
                                            <h5 class="product-title font-alt">{{ $detail->quantity }}</h5>
                                        </td>
                                        <td>
                                            <h5 class="product-title font-alt">{{ number_format($detail->total) }}đ</h5>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="order-summary mt-40">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="font-alt">Thông tin giao hàng</h4>
                            <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</p>
                            @if($order->description)
                                <p><strong>Ghi chú:</strong> {{ $order->description }}</p>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <h4 class="font-alt">Thông tin thanh toán</h4>
                            <p><strong>Phương thức thanh toán:</strong>
                                @switch($order->payment_method)
                                    @case('cod')
                                        Thanh toán khi nhận hàng (COD)
                                        @break
                                    @case('bank_transfer')
                                        Chuyển khoản ngân hàng
                                        @break
                                    @case('momo')
                                        Ví MoMo
                                        @break
                                    @case('vnpay')
                                        VNPay
                                        @break
                                    @case('zalopay')
                                        ZaloPay
                                        @break
                                    @case('paypal')
                                        PayPal
                                        @break
                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </p>
                            <p><strong>Trạng thái thanh toán:</strong>
                                @switch($order->payment_status)
                                    @case('pending')
                                        <span class="label label-warning">Chưa thanh toán</span>
                                        @break
                                    @case('paid')
                                        <span class="label label-success">Đã thanh toán</span>
                                        @break
                                    @case('failed')
                                        <span class="label label-danger">Thanh toán thất bại</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $order->payment_status }}</span>
                                @endswitch
                            </p>
                            <div class="order-total mt-20">
                                <table class="table table-condensed">
                                    <tr>
                                        <td><strong>Tạm tính:</strong></td>
                                        <td class="text-right">{{ number_format($order->total_amount - 30000) }}đ</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phí vận chuyển:</strong></td>
                                        <td class="text-right">30,000đ</td>
                                    </tr>
                                    @if($order->coupon)
                                        <tr class="text-success">
                                            <td><strong>Giảm giá:</strong></td>
                                            <td class="text-right">-{{ number_format($order->coupon->discount_amount) }}đ</td>
                                        </tr>
                                    @endif
                                    <tr class="order-total">
                                        <td><strong>Tổng cộng:</strong></td>
                                        <td class="text-right"><strong>{{ number_format($order->total_amount) }}đ</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row mt-40">
            <div class="col-sm-12 text-center">
                <a href="{{ route('client.order.track', $order->id) }}" class="btn btn-primary btn-round">
                    <i class="fa fa-truck mr-5"></i>Theo dõi đơn hàng
                </a>
                <a href="{{ route('client.product') }}" class="btn btn-default btn-round ml-10">
                    <i class="fa fa-shopping-bag mr-5"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.success-message {
    padding: 40px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.order-info {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 4px;
    display: inline-block;
}

.order-details {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.product-variant {
    margin-top: 8px;
}

.product-variant small {
    font-size: 13px;
    color: #666;
    background-color: #f8f9fa;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
}

.variant-color, .variant-storage {
    display: inline-block;
}

.mx-1 {
    margin: 0 5px;
    color: #999;
}

.checkout-table {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
}

.checkout-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px 15px;
    font-weight: 600;
    color: #333;
}

.checkout-table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #eee;
}

.order-total td {
    font-size: 16px;
    padding: 10px 0;
}

.label {
    display: inline-block;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 500;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 15px;
}

.label-warning {
    background-color: #ffc107;
    color: #000;
}

.label-success {
    background-color: #28a745;
    color: #fff;
}

.label-danger {
    background-color: #dc3545;
    color: #fff;
}

.label-default {
    background-color: #6c757d;
    color: #fff;
}

.mt-20 {
    margin-top: 20px;
}

.mt-40 {
    margin-top: 40px;
}

.mb-20 {
    margin-bottom: 20px;
}

.mb-30 {
    margin-bottom: 30px;
}

.ml-10 {
    margin-left: 10px;
}

.mr-5 {
    margin-right: 5px;
}
</style>
@endsection