@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng ' . ($order->code_order ?? ('#' . $order->id)))

@section('content')
<section class="module bg-light">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="row mb-30">
            <div class="col-sm-12">
                <ol class="breadcrumb font-alt">
                    <li><a href="{{ route('client.home') }}"><i class="fa fa-home"></i></a></li>
                    <li><a href="{{ route('client.order.list') }}">Đơn hàng của tôi</a></li>
                    <li class="active">Chi tiết đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}</li>
                </ol>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <h1 class="module-title font-alt mb-30">
                    <i class="fa fa-file-text-o mr-10"></i>Chi tiết đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}
                </h1>
                <p class="lead">Xem chi tiết đơn hàng và theo dõi trạng thái</p>
            </div>
        </div>
        <hr class="divider-w pt-20 mb-40">

        @if(session('success'))
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="alert alert-success alert-dismissible animate-in">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="alert alert-danger alert-dismissible animate-in">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Order Details -->
            <div class="col-sm-8">
                <div class="cart-section bg-white rounded-lg shadow-sm p-30 mb-30">
                    <h4 class="font-alt mb-25">
                        <i class="fa fa-shopping-cart mr-10"></i>Sản phẩm đã đặt
                    </h4>

                    <!-- Products Table -->
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
                                                <h5 class="product-name">
                                                    <a href="{{ route('client.single-product', $detail->product_id) }}">
                                                        {{ $detail->product->name }}
                                                    </a>
                                                </h5>
                                                @if($detail->variant)
                                                    <div class="product-variants">
                                                        @if($detail->variant->color)
                                                            <span class="variant-item">
                                                                Màu: {{ $detail->variant->color->name }}
                                                            </span>
                                                        @endif
                                                        @if($detail->variant->storage)
                                                            <span class="variant-item ml-15">
                                                                Dung lượng: {{ $detail->variant->storage->name }}
                                                            </span>
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

                    <!-- Shipping Information -->
                    <div class="shipping-info mt-40 pt-40 border-top">
                        <h4 class="font-alt mb-25">
                            <i class="fa fa-truck mr-10"></i>Thông tin giao hàng
                        </h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                                <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p><strong>Địa chỉ:</strong> {{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</p>
                                @if($order->description)
                                    <p><strong>Ghi chú:</strong> {{ $order->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-sm-4">
                <div class="order-summary bg-white rounded-lg shadow-sm p-30 sticky-summary">
                    <h4 class="font-alt mb-25">
                        <i class="fa fa-calculator mr-10"></i>Tổng quan đơn hàng
                    </h4>

                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span class="font-weight-600">{{ number_format($order->total_amount - 30000) }}đ</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span class="font-weight-600">30,000đ</span>
                        </div>
                        @if($order->coupon)
                            <div class="summary-row discount">
                                <span>Giảm giá:</span>
                                <span>-{{ number_format($order->coupon->discount_amount) }}đ</span>
                            </div>
                        @endif
                        <hr class="summary-divider">
                        <div class="summary-row total-row">
                            <span class="total-label">Tổng cộng:</span>
                            <span class="total-amount">{{ number_format($order->total_amount) }}đ</span>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="order-status mt-30 pt-30 border-top">
                        <h5 class="font-alt mb-15">Trạng thái đơn hàng</h5>
                        @switch($order->status)
                            @case('pending')
                                <div class="status-badge status-warning">
                                    <i class="fa fa-clock-o"></i> Chờ xử lý
                                </div>
                                @break
                            @case('processing')
                                <div class="status-badge status-info">
                                    <i class="fa fa-cog"></i> Đang xử lý
                                </div>
                                @break
                            @case('shipping')
                                <div class="status-badge status-primary">
                                    <i class="fa fa-truck"></i> Đang giao hàng
                                </div>
                                @break
                            @case('completed')
                                <div class="status-badge status-success">
                                    <i class="fa fa-check"></i> Hoàn thành
                                </div>
                                @break
                            @case('cancelled')
                                <div class="status-badge status-cancelled">
                                    <i class="fa fa-ban"></i> Đã hủy
                                </div>
                                @break
                            @default
                                <div class="status-badge">{{ $order->status }}</div>
                        @endswitch
                    </div>

                    <!-- Payment Information -->
                    <div class="payment-info mt-30 pt-30 border-top">
                        <h5 class="font-alt mb-15">Thông tin thanh toán</h5>
                        <p><strong>Phương thức:</strong> 
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
                        <p><strong>Trạng thái:</strong>
                            @switch($order->payment_status)
                                @case('pending')
                                    <span class="label label-warning">Chưa thanh toán</span>
                                    @break
                                @case('paid')
                                    <span class="label label-success">Đã thanh toán</span>
                                    @break
                                @case('failed')
                                    <span class="label label-cancelled">Đã hủy</span>
                                    @break
                                @default
                                    <span class="label label-default">{{ $order->payment_status }}</span>
                            @endswitch
                        </p>
                    </div>

                    <!-- Order Actions -->
                    <div class="order-actions mt-30 pt-30 border-top">
                        <a href="{{ route('client.order.track', $order->id) }}" class="btn btn-primary btn-block">
                            <i class="fa fa-truck mr-10"></i>Theo dõi đơn hàng
                        </a>
                        @if($order->status === 'pending' && $order->payment_status === 'pending')
                            <button type="button" class="btn btn-danger btn-block mt-10" onclick="cancelOrder()">
                                <i class="fa fa-times mr-10"></i>Hủy đơn hàng
                            </button>
                            <!-- Chọn lại phương thức thanh toán -->
                            <div class="panel panel-default mt-20">
                                <div class="panel-heading"><strong>Chọn lại phương thức thanh toán</strong></div>
                                <div class="panel-body">
                                    <form method="POST" action="{{ route('client.place-order') }}">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <div class="form-group">
                                            <label>Phương thức thanh toán:</label><br>
                                            <label><input type="radio" name="payment_method" value="momo" checked> MoMo</label>
                                            <label style="margin-left: 20px;"><input type="radio" name="payment_method" value="vnpay"> VNPay</label>
                                            <label style="margin-left: 20px;"><input type="radio" name="payment_method" value="cod"> COD</label>
                                        </div>
                                        <button type="submit" class="btn btn-success">Thanh toán lại</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($order->status === 'pending' && $order->payment_status === 'pending')
    <form id="cancelOrderForm" action="{{ route('client.order.cancel', $order->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PUT')
    </form>

    <script>
        function cancelOrder() {
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                document.getElementById('cancelOrderForm').submit();
            }
        }
    </script>
@endif
@endsection

@section('styles')
<style>
/* Cart Section Styling */
.cart-section {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.cart-table {
    margin-bottom: 0;
}

.cart-table th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 15px 10px;
}

.cart-table td {
    padding: 20px 10px;
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
}

.cart-item:hover {
    background: #f8f9fa;
}

.product-info {
    padding-right: 20px;
}

.product-name {
    font-weight: 600;
    margin-bottom: 8px;
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
    font-size: 16px;
    font-weight: 600;
    color: #dc3545;
}

.quantity {
    font-weight: 500;
}

/* Order Summary */
.order-summary {
    position: sticky;
    top: 20px;
}

.summary-details {
    margin-bottom: 30px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    color: #495057;
}

.summary-row.discount {
    color: #28a745;
}

.summary-divider {
    margin: 15px 0;
    border-color: #dee2e6;
}

.total-row {
    font-size: 18px;
    font-weight: 600;
    color: #212529;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 10px 20px;
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

/* Labels */
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

/* Buttons */
.btn {
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 12px 20px;
}

.btn i {
    margin-right: 5px;
}

/* Responsive */
@media (max-width: 767px) {
    .cart-section {
        padding: 15px !important;
    }
    
    .order-summary {
        margin-top: 30px;
        position: static;
    }
    
    .product-info {
        padding-right: 0;
    }
}
</style>
@endsection 