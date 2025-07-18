@extends('layouts.client')

@section('title', 'Đặt hàng thành công')

@section('content')
<section class="module bg-light">
    <div class="container">
        <div class="row">
            <!-- Thông tin đơn hàng và sản phẩm -->
            <div class="col-md-8">
                <div class="cart-section bg-white rounded-lg shadow-sm p-4 mb-4">
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fa fa-check-circle fa-2x mr-3"></i>
                        <div>
                            <h4 class="mb-1">Đặt hàng thành công!</h4>
                            <p class="mb-0">Cảm ơn bạn đã đặt hàng!</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p><strong>Mã đơn hàng:</strong> <span class="text-primary">{{ $order->code_order }}</span></p>
                        <p><strong>Địa chỉ giao hàng:</strong> {{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</p>
                    </div>
                    <h5 class="font-alt mb-3"><i class="fa fa-shopping-cart mr-2"></i>Sản phẩm đã đặt</h5>
                    <div class="cart-table-wrapper">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                    <tr class="cart-item">
                                        <td>
                                            <div class="product-info">
                                                <h6 class="product-name mb-1">
                                                    <a href="{{ route('client.single-product', $detail->product_id) }}">
                                                        {{ $detail->product->name }}
                                                    </a>
                                                </h6>
                                                @if($detail->variant)
                                                    <div class="product-variants">
                                                        @if($detail->variant->color)
                                                            <span class="variant-item">Màu: {{ $detail->variant->color->name }}</span>
                                                        @endif
                                                        @if($detail->variant->storage)
                                                            <span class="variant-item ml-2">Dung lượng: {{ $detail->variant->storage->name }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="price">{{ number_format($detail->price) }}đ</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="quantity">{{ $detail->quantity }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="total">{{ number_format($detail->total) }}đ</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Tổng quan đơn hàng, trạng thái, thanh toán, nút thao tác -->
            <div class="col-md-4">
                <div class="order-summary bg-white rounded-lg shadow-sm p-4 sticky-summary">
                    <h5 class="font-alt mb-3"><i class="fa fa-calculator mr-2"></i>Tổng quan đơn hàng</h5>
                    <div class="summary-details mb-3">
                        <div class="summary-row d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span class="font-weight-bold">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="summary-row d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span class="font-weight-bold">{{ number_format($shipping, 0, ',', '.') }}đ</span>
                        </div>
                        <hr class="summary-divider">
                        <div class="summary-row d-flex justify-content-between total-row">
                            <span class="total-label">Tổng cộng:</span>
                            <span class="total-amount font-weight-bold text-danger">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                    <div class="order-status mt-4 pt-3 border-top">
                        <h6 class="font-alt mb-2">Trạng thái đơn hàng</h6>
                        @switch($order->status)
                            @case('pending')
                                <div class="status-badge status-warning"><i class="fa fa-clock-o"></i> Chờ xử lý</div>
                                @break
                            @case('processing')
                                <div class="status-badge status-info"><i class="fa fa-cog"></i> Đang xử lý</div>
                                @break
                            @case('shipping')
                                <div class="status-badge status-primary"><i class="fa fa-truck"></i> Đang giao hàng</div>
                                @break
                            @case('completed')
                                <div class="status-badge status-success"><i class="fa fa-check"></i> Hoàn thành</div>
                                @break
                            @case('cancelled')
                                <div class="status-badge status-cancelled"><i class="fa fa-ban"></i> Đã hủy</div>
                                @break
                            @default
                                <div class="status-badge">{{ $order->status }}</div>
                        @endswitch
                    </div>
                    <div class="payment-info mt-4 pt-3 border-top">
                        <h6 class="font-alt mb-2">Thông tin thanh toán</h6>
                        <p class="mb-1"><strong>Phương thức:</strong> 
                            @switch($order->payment_method)
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
                                    {{ $order->payment_method }}
                            @endswitch
                        </p>
                        <p class="mb-0"><strong>Trạng thái:</strong>
                            @switch($order->payment_status)
                                @case('pending')
                                    <span class="label label-warning">Chưa thanh toán</span>
                                    @break
                                @case('paid')
                                    <span class="label label-success">Đã thanh toán</span>
                                    @break
                                @case('cancelled')
                                    <span class="label label-cancelled">Đã hủy</span>
                                    @break
                                @default
                                    <span class="label label-default">{{ $order->payment_status }}</span>
                            @endswitch
                        </p>
                    </div>
                    <div class="order-actions mt-4 pt-3 border-top text-center">
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
</section>
@endsection

@section('styles')
<style>
.cart-section {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}
.cart-table th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 15px 10px;
}
.cart-table td {
    padding: 16px 10px;
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
}
.cart-item:hover {
    background: #f8f9fa;
}
.product-info {
    padding-right: 12px;
}
.product-name {
    font-weight: 600;
    margin-bottom: 4px;
}
.product-name a {
    color: #333;
    text-decoration: none;
}
.product-name a:hover {
    color: #007bff;
}
.product-variants {
    font-size: 13px;
    color: #6c757d;
}
.variant-item {
    display: inline-block;
}
.price, .total {
    font-size: 15px;
    font-weight: 600;
    color: #dc3545;
}
.quantity {
    font-weight: 500;
}
.order-summary {
    position: sticky;
    top: 20px;
}
.summary-divider {
    margin: 12px 0;
    border-color: #dee2e6;
}
.total-row {
    font-size: 17px;
    font-weight: 600;
    color: #212529;
}
.status-badge {
    display: inline-block;
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    margin-bottom: 10px;
}
.status-badge i {
    margin-right: 5px;
}
.status-warning {
    background-color: #fff3cd;
    color: #856404;
}
.status-info {
    background-color: #cce5ff;
    color: #004085;
}
.status-primary {
    background-color: #e8f0fe;
    color: #004085;
}
.status-success {
    background-color: #d4edda;
    color: #155724;
}
.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
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
.label-cancelled {
    background-color: #dc3545;
    color: #fff;
}
.label-default {
    background-color: #6c757d;
    color: #fff;
}
.btn {
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 12px 20px;
}
.btn i {
    margin-right: 5px;
}
@media (max-width: 767px) {
    .cart-section, .order-summary {
        padding: 12px !important;
        margin-bottom: 20px;
        position: static;
    }
    .product-info {
        padding-right: 0;
    }
    .order-actions .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endsection