@extends('layouts.client')

@section('title', 'Theo dõi đơn hàng #' . $order->id)

@section('content')
<section class="module bg-light">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="row mb-30">
            <div class="col-sm-12">
                <ol class="breadcrumb font-alt">
                    <li><a href="{{ route('client.home') }}"><i class="fa fa-home"></i></a></li>
                    <li><a href="{{ route('client.order.list') }}">Đơn hàng của tôi</a></li>
                    <li><a href="{{ route('client.order.show', $order->id) }}">Chi tiết đơn hàng #{{ $order->id }}</a></li>
                    <li class="active">Theo dõi đơn hàng</li>
                </ol>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <h1 class="module-title font-alt mb-30">
                    <i class="fa fa-truck mr-10"></i>Theo dõi đơn hàng #{{ $order->id }}
                </h1>
                <p class="lead">Theo dõi trạng thái và quá trình giao hàng</p>
            </div>
        </div>
        <hr class="divider-w pt-20 mb-40">

        <div class="row">
            <div class="col-sm-8">
                <!-- Order Timeline -->
                <div class="cart-section bg-white rounded-lg shadow-sm p-30 mb-30">
                    <h4 class="font-alt mb-25">
                        <i class="fa fa-history mr-10"></i>Lịch sử đơn hàng
                    </h4>

                    <div class="order-timeline">
                        @foreach($statusHistory as $status)
                            <div class="timeline-item {{ $status['active'] ? 'active' : '' }} {{ $status['done'] ? 'done' : '' }}">
                                <div class="timeline-icon">
                                    @switch($status['status'])
                                        @case('pending')
                                            <i class="fa fa-clock-o"></i>
                                            @break
                                        @case('processing')
                                            <i class="fa fa-cog"></i>
                                            @break
                                        @case('shipping')
                                            <i class="fa fa-truck"></i>
                                            @break
                                        @case('completed')
                                            <i class="fa fa-check"></i>
                                            @break
                                        @case('cancelled')
                                            <i class="fa fa-times"></i>
                                            @break
                                    @endswitch
                                </div>
                                <div class="timeline-content">
                                    <h5 class="font-alt">{{ $status['title'] }}</h5>
                                    <p>{{ $status['description'] }}</p>
                                    @if($status['time'])
                                        <small class="text-muted">
                                            {{ $status['time']->format('d/m/Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Products -->
                <div class="cart-section bg-white rounded-lg shadow-sm p-30">
                    <h4 class="font-alt mb-25">
                        <i class="fa fa-shopping-cart mr-10"></i>Sản phẩm trong đơn
                    </h4>

                    <div class="cart-table-wrapper">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th width="80">Hình ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                    <tr class="cart-item">
                                        <td>
                                            <div class="product-image">
                                                <a href="{{ route('client.single-product', $detail->product_id) }}">
                                                    <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                         alt="{{ $detail->product->name }}"
                                                         class="img-responsive"
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <h5 class="product-name">
                                                    <a href="{{ route('client.single-product', $detail->product_id) }}">
                                                        {{ $detail->product_name ?? ($detail->product->name ?? '') }}
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

            <div class="col-sm-4">
                <!-- Shipping Info -->
                <div class="order-summary bg-white rounded-lg shadow-sm p-30 mb-30">
                    <h4 class="font-alt mb-25">
                        <i class="fa fa-map-marker mr-10"></i>Thông tin giao hàng
                    </h4>

                    <div class="shipping-details">
                        <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</p>
                        @if($order->description)
                            <p><strong>Ghi chú:</strong> {{ $order->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Order Status -->
                <div class="order-summary bg-white rounded-lg shadow-sm p-30">
                    <h4 class="font-alt mb-25">
                        <i class="fa fa-info-circle mr-10"></i>Trạng thái đơn hàng
                    </h4>

                    <div class="status-details">
                        <p>
                            <strong>Trạng thái:</strong>
                            @switch($order->status)
                                @case('pending')
                                    <span class="label label-warning">Chờ xử lý</span>
                                    @break
                                @case('processing')
                                    <span class="label label-info">Đang chuẩn bị hàng</span>
                                    @break
                                @case('shipping')
                                    <span class="label label-primary">Đang giao</span>
                                    @break
                                @case('completed')
                                    <span class="label label-success">Hoàn thành</span>
                                    @break
                                @case('cancelled')
                                    <span class="label label-cancelled">Đã hủy</span>
                                    @break
                                @default
                                    <span class="label label-default">{{ $order->status }}</span>
                            @endswitch
                        </p>
                        <p>
                            <strong>Thanh toán:</strong>
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
                        <p><strong>Tổng tiền:</strong> <span class="total">{{ number_format($order->total_amount) }}đ</span></p>
                    </div>

                    @if($order->status === 'pending' && $order->payment_status === 'pending')
                        <div class="order-actions mt-30 pt-30 border-top">
                            <button type="button" class="btn btn-danger btn-block" onclick="cancelOrder()">
                                <i class="fa fa-times mr-10"></i>Hủy đơn hàng
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Shopping Actions -->
        <div class="row mt-40">
            <div class="col-sm-12 text-center">
                <a href="{{ route('client.product') }}" class="btn btn-primary">
                    <i class="fa fa-shopping-bag mr-10"></i>Tiếp tục mua sắm
                </a>
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

/* Product Image */
.product-image {
    width: 60px;
    height: 60px;
    overflow: hidden;
    border-radius: 4px;
    border: 1px solid #eee;
    transition: all 0.3s ease;
}

.product-image:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.product-image a:hover img {
    opacity: 0.9;
}

/* Order Timeline */
.order-timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    margin-bottom: 30px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: -30px;
    width: 2px;
    background: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 32px;
    height: 32px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    z-index: 1;
}

.timeline-item.active .timeline-icon {
    background: #007bff;
    border-color: #007bff;
    color: #fff;
}

.timeline-item.done .timeline-icon {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}

.timeline-content {
    background: #fff;
    padding: 15px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.timeline-content h5 {
    margin: 0 0 10px;
    color: #212529;
}

.timeline-content p {
    margin: 0 0 5px;
    color: #6c757d;
}

/* Product Info */
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

.label-info {
    background-color: #17a2b8;
    color: #fff;
}

.label-primary {
    background-color: #007bff;
    color: #fff;
}

.label-success {
    background-color: #28a745;
    color: #fff;
}

.label-cancelled {
    background-color: #6c757d;
    color: #fff;
}

.label-default {
    background-color: #6c757d;
    color: #fff;
}

/* Responsive */
@media (max-width: 767px) {
    .cart-section {
        padding: 15px !important;
    }
    
    .timeline-item {
        padding-left: 40px;
    }
    
    .timeline-item::before {
        left: 12px;
    }
    
    .timeline-icon {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
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
    background-color: #f8f9fa;
    color: #6c757d;
}
</style>
@endsection