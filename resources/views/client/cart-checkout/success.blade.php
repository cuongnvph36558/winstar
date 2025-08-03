@extends('layouts.client')

@section('title', 'Đặt hàng thành công - Winstar')

@section('styles')
<link href="{{ asset('client/assets/css/tailwind.min.css') }}" rel="stylesheet">
<link href="{{ asset('client/assets/css/modern-styles.css') }}" rel="stylesheet">
<style>
.product-image {
    width: 80px !important;
    height: 80px !important;
    object-fit: cover !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
}

.product-info {
    flex: 1;
    margin-left: 1rem;
}

.product-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.product-detail-item {
    font-size: 0.9rem;
    color: #6c757d;
}

.product-detail-item strong {
    color: #495057;
    font-weight: 600;
}

.customer-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.customer-info-item {
    display: flex;
    flex-direction: column;
}

.customer-info-item strong {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.customer-info-item span {
    color: #6c757d;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 0.25rem;
    border-left: 3px solid #007bff;
}

.customer-info-item .not-available {
    color: #adb5bd;
    font-style: italic;
    background: #f8f9fa;
    border-left-color: #adb5bd;
}

.button-danger {
    background-color: #dc3545 !important;
    color: white !important;
    border: 1px solid #dc3545 !important;
}

.button-danger:hover {
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
}
</style>
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
        <div class="customer-info">
            <div class="customer-info-item">
                <strong>Mã đơn hàng:</strong>
                <span>{{ $order->code_order ?? '#' . $order->id }}</span>
            </div>
            <div class="customer-info-item">
                <strong>Ngày đặt hàng:</strong>
                <span>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
            </div>
            <div class="customer-info-item">
                <strong>Trạng thái đơn hàng:</strong>
                <span>
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
                </span>
            </div>
        </div>
    </div>

    <!-- Thông tin Khách hàng -->
    <div class="card">
        <h3 class="section-title">Thông tin Khách hàng</h3>
        <div class="customer-info">
            @if($order->receiver_name)
            <div class="customer-info-item">
                <strong>Họ và tên:</strong>
                <span>{{ $order->receiver_name }}</span>
            </div>
            @endif
            
            @if($order->phone)
            <div class="customer-info-item">
                <strong>Số điện thoại:</strong>
                <span>{{ $order->phone }}</span>
            </div>
            @endif
            
            @if($order->billing_address || $order->billing_ward || $order->billing_district || $order->billing_city)
            <div class="customer-info-item">
                <strong>Địa chỉ giao hàng:</strong>
                <span>
                    @if($order->billing_address){{ $order->billing_address }}@endif
                    @if($order->billing_ward){{ $order->billing_address ? ', ' : '' }}{{ $order->billing_ward }}@endif
                    @if($order->billing_district){{ ($order->billing_address || $order->billing_ward) ? ', ' : '' }}{{ $order->billing_district }}@endif
                    @if($order->billing_city){{ ($order->billing_address || $order->billing_ward || $order->billing_district) ? ', ' : '' }}{{ $order->billing_city }}@endif
                </span>
            </div>
            @endif
            
            @if($order->description)
            <div class="customer-info-item">
                <strong>Ghi chú:</strong>
                <span>{{ $order->description }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Thông tin Sản phẩm -->
    <div class="card">
        <h3 class="section-title">Thông tin Sản phẩm</h3>
        @if($order->orderDetails && $order->orderDetails->count() > 0)
            @foreach($order->orderDetails as $detail)
            <div class="flex items-start mb-6 p-4 border border-gray-200 rounded-lg">
                <img src="{{ $detail->product && $detail->product->image ? asset('storage/' . $detail->product->image) : asset('assets/external/images/placeholder-80x80.png') }}" 
                     alt="{{ $detail->product_name ?? ($detail->product->name ?? 'Sản phẩm') }}" 
                     class="product-image">
                <div class="product-info">
                    <h4 class="font-semibold text-lg mb-2">{{ $detail->product_name ?? ($detail->product->name ?? 'Sản phẩm không xác định') }}</h4>
                    
                    <div class="product-details">
                        @if($detail->product && $detail->product->category)
                        <div class="product-detail-item">
                            <strong>Danh mục:</strong> {{ $detail->product->category->name }}
                        </div>
                        @endif
                        
                        @if($detail->variant)
                        <div class="product-detail-item">
                            <strong>Phiên bản:</strong> {{ $detail->variant->variant_name }}
                        </div>
                        @endif
                        
                        @if($detail->variant && $detail->variant->color)
                        <div class="product-detail-item">
                            <strong>Màu sắc:</strong> {{ $detail->variant->color->name }}
                        </div>
                        @endif
                        
                        @if($detail->variant && $detail->variant->storage)
                        <div class="product-detail-item">
                            <strong>Dung lượng:</strong> {{ $detail->variant->storage->name }}
                        </div>
                        @endif
                        
                        <div class="product-detail-item">
                            <strong>Số lượng:</strong> {{ $detail->quantity ?? 0 }}
                        </div>
                        
                        <div class="product-detail-item">
                            <strong>Đơn giá:</strong> <span class="text-blue-600">{{ number_format($detail->price ?? 0, 0, ',', '.') }}₫</span>
                        </div>
                        
                        <div class="product-detail-item">
                            <strong>Thành tiền:</strong> <span class="text-blue-600 font-semibold">{{ number_format($detail->total ?? 0, 0, ',', '.') }}₫</span>
                        </div>
                        
                        @if($detail->product && $detail->product->description)
                        <div class="product-detail-item col-span-full">
                            <strong>Mô tả:</strong> {{ Str::limit($detail->product->description, 200) }}
                        </div>
                        @endif
                    </div>
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
        <div class="customer-info">
            <div class="customer-info-item">
                <strong>Tổng tiền sản phẩm:</strong>
                <span class="text-blue-600">{{ number_format($subtotal ?? 0, 0, ',', '.') }}₫</span>
            </div>
            <div class="customer-info-item">
                <strong>Phí vận chuyển:</strong>
                <span class="text-blue-600">{{ number_format($shipping ?? 0, 0, ',', '.') }}₫</span>
            </div>
            @if(isset($couponDiscount) && $couponDiscount > 0 && $order->coupon)
            <div class="customer-info-item">
                <strong>Giảm giá:</strong>
                <span class="text-green-600">-{{ number_format($couponDiscount, 0, ',', '.') }}₫</span>
            </div>
            @endif
            <div class="customer-info-item">
                <strong>Tổng cộng:</strong>
                <span class="text-red-600 font-bold text-xl">{{ number_format($total ?? 0, 0, ',', '.') }}₫</span>
            </div>
        </div>
    </div>

    <!-- Hình thức thanh toán -->
    <div class="card">
        <h3 class="section-title">Hình thức thanh toán</h3>
        <div class="customer-info">
            <div class="customer-info-item">
                <strong>Phương thức thanh toán:</strong>
                <span>
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
            </div>
            <div class="customer-info-item">
                <strong>Trạng thái thanh toán:</strong>
                <span>
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
                </span>
            </div>
        </div>
    </div>

    <!-- Nút Hành động -->
    <div class="flex justify-center mt-6">
        @if($order->status === 'pending' && $order->payment_status === 'pending')
            <button type="button" class="button button-danger" onclick="cancelOrder()">
                <i class="fa fa-times mr-2"></i>Hủy đơn hàng
            </button>
        @endif
        <a href="{{ route('client.product') }}" class="button ml-4">
            <i class="fa fa-shopping-cart mr-2"></i>Tiếp tục mua sắm
        </a>
        <a href="{{ route('client.order.list') }}" class="button ml-4">
            <i class="fa fa-list mr-2"></i>Xem tất cả đơn hàng
        </a>
    </div>
</div>
@endsection

@if($order->status === 'pending' && $order->payment_status === 'pending')
    <form id="cancelOrderForm" action="{{ route('client.order.cancel', $order->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PUT')
    </form>
@endif

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

function cancelOrder() {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        document.getElementById('cancelOrderForm').submit();
    }
}
</script>
@endsection 