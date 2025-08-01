@extends('layouts.client')

@section('title', 'Đặt hàng thành công - Winstar')

@section('styles')
<link href="{{ asset('client/assets/css/tailwind.min.css') }}" rel="stylesheet">
<link href="{{ asset('client/assets/css/modern-styles.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="success-container">
    <!-- Progress Steps at top like checkout -->
    <div class="progress-container">
        <div class="progress-steps">
            <div class="step completed">
                <div class="step-icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="step-label">Giỏ hàng</div>
            </div>
            <div class="step inactive">
                <div class="step-icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="step-label">Thanh toán</div>
            </div>
            <div class="step active">
                <div class="step-icon">
                    <i class="fa fa-check"></i>
                </div>
                <div class="step-label">Hoàn thành</div>
            </div>
        </div>
    </div>

    <!-- Page Header like checkout -->
    <div class="page-header">
        <!-- Breadcrumbs -->
        <nav class="breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('client.home') }}">
                    <i class="fa fa-home"></i> Trang chủ
                </a>
            </div>
            <span class="breadcrumb-separator">/</span>
            <div class="breadcrumb-item">
                <a href="{{ route('client.product') }}">
                    <i class="fa fa-shopping-bag"></i> Sản phẩm
                </a>
            </div>
            <span class="breadcrumb-separator">/</span>
            <div class="breadcrumb-item active">
                <a href="#">
                    <i class="fa fa-check"></i> Hoàn thành
                </a>
            </div>
        </nav>

        <!-- Page Title Section -->
        <div class="page-title-section">
            <h1 class="page-title">
                <i class="fa fa-check-circle"></i>
                Đặt Hàng Thành Công!
            </h1>
            <p class="page-subtitle">Cảm ơn bạn đã đặt hàng tại WINSTAR</p>
        </div>
    </div>

    <!-- Thông tin Đơn hàng -->
    <div class="card">
        <h3 class="section-title">Thông tin Đơn hàng</h3>
        <p><strong>Mã đơn hàng:</strong> <span class="text-blue-600">{{ $order->code_order ?? '#' . $order->id }}</span></p>
        <p><strong>Ngày đặt hàng:</strong> <span class="text-gray-600">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</span></p>
        <p><strong>Trạng thái đơn hàng:</strong> 
            @switch($order->status ?? 'pending')
                @case('pending')
                    <span class="text-yellow-600">Chờ xử lý</span>
                    @break
                @case('processing')
                    <span class="text-blue-600">Đang xử lý</span>
                    @break
                @case('shipping')
                    <span class="text-purple-600">Đang giao hàng</span>
                    @break
                @case('completed')
                    <span class="text-green-600">Hoàn thành</span>
                    @break
                @case('cancelled')
                    <span class="text-red-600">Đã hủy</span>
                    @break
                @default
                    <span class="text-gray-600">{{ $order->status ?? 'Unknown' }}</span>
            @endswitch
        </p>
    </div>

    <!-- Thông tin Khách hàng -->
    <div class="card">
        <h3 class="section-title">Thông tin Khách hàng</h3>
        <p><strong>Họ và tên:</strong> <span class="text-gray-800">{{ $order->billing_name ?? 'N/A' }}</span></p>
        <p><strong>Email:</strong> <span class="text-gray-800">{{ $order->billing_email ?? 'N/A' }}</span></p>
        <p><strong>Số điện thoại:</strong> <span class="text-gray-800">{{ $order->billing_phone ?? 'N/A' }}</span></p>
        <p><strong>Địa chỉ giao hàng:</strong> <span class="text-gray-800">
            {{ $order->billing_address ?? '' }}
            @if($order->billing_ward){{ ', ' . $order->billing_ward }}@endif
            @if($order->billing_district){{ ', ' . $order->billing_district }}@endif
            @if($order->billing_city){{ ', ' . $order->billing_city }}@endif
        </span></p>
    </div>

    <!-- Thông tin Sản phẩm -->
    <div class="card">
        <h3 class="section-title">Thông tin Sản phẩm</h3>
        @if($order->orderDetails && $order->orderDetails->count() > 0)
            @foreach($order->orderDetails as $detail)
            <div class="flex items-center mb-4">
                <img src="{{ $detail->product && $detail->product->image ? asset('storage/' . $detail->product->image) : 'https://placehold.co/100x100' }}" 
                     alt="{{ $detail->product_name ?? ($detail->product->name ?? 'Sản phẩm') }}" 
                     class="product-image mr-4">
                <div>
                    <p><strong>Tên:</strong> {{ $detail->product_name ?? ($detail->product->name ?? 'Sản phẩm không xác định') }}</p>
                    @if($detail->variant && $detail->variant->color && $detail->variant->storage)
                        <p><strong>Màu sắc:</strong> {{ $detail->variant->color->name ?? 'N/A' }}</p>
                        <p><strong>Dung lượng:</strong> {{ $detail->variant->storage->name ?? 'N/A' }}</p>
                    @endif
                    <p><strong>Số lượng:</strong> {{ $detail->quantity ?? 0 }}</p>
                    <p><strong>Giá:</strong> <span class="text-blue-600">{{ number_format($detail->price ?? 0, 0, ',', '.') }}₫</span></p>
                    <p><strong>Tổng:</strong> <span class="text-blue-600">{{ number_format($detail->total ?? 0, 0, ',', '.') }}₫</span></p>
                </div>
            </div>
            @endforeach
        @else
            <p class="text-gray-500">Không có sản phẩm nào</p>
        @endif
    </div>

    <!-- Tổng cộng -->
    <div class="card highlight">
        <h3 class="section-title">Tổng cộng</h3>
        <p><strong>Tổng tiền sản phẩm:</strong> <span class="text-blue-600">{{ number_format($subtotal ?? 0, 0, ',', '.') }}₫</span></p>
        <p><strong>Phí vận chuyển:</strong> <span class="text-blue-600">{{ number_format($shipping ?? 0, 0, ',', '.') }}₫</span></p>
        @if(isset($couponDiscount) && $couponDiscount > 0 && $order->coupon)
        <p><strong>Giảm giá:</strong> <span class="text-green-600">-{{ number_format($couponDiscount, 0, ',', '.') }}₫</span></p>
        @endif
        <p class="font-bold text-xl"><strong>Tổng cộng:</strong> <span class="text-red-600">{{ number_format($total ?? 0, 0, ',', '.') }}₫</span></p>
    </div>

    <!-- Hình thức thanh toán -->
    <div class="card">
        <h3 class="section-title">Hình thức thanh toán</h3>
        <p><strong>Phương thức thanh toán:</strong> 
            <span class="text-gray-800">
                @switch($order->payment_method ?? 'cod')
                    @case('cod')
                        <i class="fa fa-money mr-1"></i>Thanh toán khi nhận hàng (COD)
                        @break
                    @case('momo')
                        <i class="fa fa-mobile mr-1"></i>Ví MoMo
                        @break
                    @case('vnpay')
                        <i class="fa fa-credit-card mr-1"></i>VNPay
                        @break
                    @default
                        {{ $order->payment_method ?? 'Unknown' }}
                @endswitch
            </span>
        </p>
        <p><strong>Trạng thái thanh toán:</strong> 
            @switch($order->payment_status ?? 'pending')
                @case('pending')
                    <span class="text-yellow-600">Chưa thanh toán</span>
                    @break
                @case('paid')
                    <span class="text-green-600">Đã thanh toán</span>
                    @break
                @case('cancelled')
                    <span class="text-red-600">Đã hủy</span>
                    @break
                @default
                    <span class="text-gray-600">{{ $order->payment_status ?? 'Unknown' }}</span>
            @endswitch
        </p>
    </div>

    <!-- Nút Hành động -->
    <div class="flex justify-center mt-6">
        <a href="{{ route('client.order.track', $order) }}" class="button">
            <i class="fa fa-truck mr-2"></i>Theo dõi đơn hàng
        </a>
        <a href="{{ route('client.product') }}" class="button ml-4">
            <i class="fa fa-shopping-cart mr-2"></i>Tiếp tục mua sắm
        </a>
        <a href="{{ route('client.order.list') }}" class="button ml-4">
            <i class="fa fa-list mr-2"></i>Xem tất cả đơn hàng
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Add success animation
    $('.page-title i').addClass('animate__animated animate__bounceIn');
    
    // Add smooth scroll to top
    $('html, body').animate({
        scrollTop: 0
    }, 500);
});
</script>
@endsection 