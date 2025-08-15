@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@if(auth()->check())
<meta name="auth-user" content="{{ auth()->id() }}">
@else
<meta name="auth-user" content="not_logged_in">
@endif

@push('scripts')
{{-- Realtime is handled by layout script --}}
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <div class="hero-content">
                    <div class="hero-icon mb-30">
                        <i class="fa fa-shopping-bag fa-3x"></i>
                    </div>
                    <h1 class="hero-title font-alt mb-20">
                        Đơn hàng của tôi
                    </h1>
                    <p class="hero-subtitle lead">
                        Theo dõi và quản lý tất cả đơn hàng của bạn một cách dễ dàng
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb Section -->
<section class="breadcrumb-section py-20 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('client.home') }}" class="text-decoration-none">
                                <i class="fa fa-home mr-5"></i>Trang chủ
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fa fa-shopping-bag mr-5"></i>Đơn hàng của tôi
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="orders-section py-60">
    <div class="container">
        @if(session('success'))
            <div class="row mb-30">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="alert alert-success alert-dismissible fade show animate-slide-down" role="alert">
                        <div class="alert-icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="alert-content">
                            <h6 class="alert-heading">Thành công!</h6>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="row mb-30">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="alert alert-danger alert-dismissible fade show animate-slide-down" role="alert">
                        <div class="alert-icon">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-content">
                            <h6 class="alert-heading">Có lỗi xảy ra!</h6>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <!-- Orders Container -->
                <div class="orders-container">
                    @forelse($orders as $order)
                        <div class="order-card mb-30 animate-fade-in" data-order-id="{{ $order->id }}">
                            <div class="order-card-header">
                                <div class="order-info">
                                    <div class="order-id-section">
                                        <h5 class="order-id mb-0">
                                            <i class="fa fa-hashtag mr-10"></i>
                                            {{ $order->code_order ?? ('#' . $order->id) }}
                                        </h5>
                                        <span class="order-date">
                                            <i class="fa fa-calendar mr-5"></i>
                                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="order-status-badge">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="status-badge status-pending">
                                                    <i class="fa fa-clock-o mr-5"></i>Chờ xử lý
                                                </span>
                                                @break
                                            @case('processing')
                                                <span class="status-badge status-processing">
                                                    <i class="fa fa-cogs mr-5"></i>Đang chuẩn bị
                                                </span>
                                                @break
                                            @case('shipping')
                                                <span class="status-badge status-shipping">
                                                    <i class="fa fa-truck mr-5"></i>Đang giao hàng
                                                </span>
                                                @break
                                            @case('completed')
                                                @if($order->is_received)
                                                    <span class="status-badge status-received">
                                                        <i class="fa fa-check-circle mr-5"></i>Đã nhận hàng
                                                    </span>
                                                @else
                                                    <span class="status-badge status-completed">
                                                        <i class="fa fa-check-circle mr-5"></i>Hoàn thành
                                                    </span>
                                                @endif
                                                @break
                                            @case('cancelled')
                                                <span class="status-badge status-cancelled">
                                                    <i class="fa fa-times-circle mr-5"></i>Đã hủy
                                                </span>
                                                @break
                                            @default
                                                <span class="status-badge status-default">
                                                    <i class="fa fa-question-circle mr-5"></i>{{ $order->status }}
                                                </span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>

                            <div class="order-card-body">
                                <!-- Products Section -->
                                <div class="products-section mb-30">
                                    <h6 class="section-title mb-20">
                                        <i class="fa fa-shopping-cart mr-10"></i>Sản phẩm trong đơn hàng
                                    </h6>
                                    <div class="products-grid">
                                        @foreach($order->orderDetails as $orderDetail)
                                            <div class="product-item">
                                                <div class="product-image">
                                                    @if($orderDetail->product && $orderDetail->product->image)
                                                        <img src="{{ asset('storage/' . $orderDetail->product->image) }}" 
                                                             alt="{{ $orderDetail->product->name }}"
                                                             class="product-img"
                                                             onerror="this.parentElement.innerHTML='<div class=\'product-placeholder\'><i class=\'fa fa-image\'></i></div>'">
                                                    @else
                                                        <div class="product-placeholder">
                                                            <i class="fa fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-name">
                                                        {{ $orderDetail->product->name ?? 'Sản phẩm không tồn tại' }}
                                                    </h6>
                                                    <div class="product-details">
                                                        <span class="product-quantity">
                                                            <i class="fa fa-times mr-5"></i>{{ $orderDetail->quantity }}
                                                        </span>
                                                        <span class="product-price">
                                                            {{ number_format($orderDetail->price) }}đ
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div class="order-summary">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="order-detail-item">
                                                <div class="detail-label">
                                                    <i class="fa fa-money mr-10"></i>Tổng tiền
                                                </div>
                                                <div class="detail-value price-value">
                                                    {{ number_format($order->total_amount) }}đ
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="order-detail-item">
                                                <div class="detail-label">
                                                    <i class="fa fa-credit-card mr-10"></i>Thanh toán
                                                </div>
                                                <div class="detail-value">
                                                    @switch($order->payment_status)
                                                        @case('pending')
                                                            <span class="payment-badge payment-pending">
                                                                <i class="fa fa-clock-o mr-5"></i>Chưa thanh toán
                                                            </span>
                                                            @break
                                                        @case('paid')
                                                            <span class="payment-badge payment-paid">
                                                                <i class="fa fa-check mr-5"></i>Đã thanh toán
                                                            </span>
                                                            @break
                                                        @case('failed')
                                                            <span class="payment-badge payment-failed">
                                                                <i class="fa fa-times mr-5"></i>Thanh toán thất bại
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="payment-badge payment-default">
                                                                <i class="fa fa-question mr-5"></i>{{ $order->payment_status }}
                                                            </span>
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="order-detail-item">
                                                <div class="detail-label">
                                                    <i class="fa fa-shopping-cart mr-10"></i>Tổng sản phẩm
                                                </div>
                                                <div class="detail-value">
                                                    {{ $order->orderDetails->count() }} sản phẩm
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="order-card-footer">
                                <div class="order-actions">
                                    <a href="{{ route('client.order.show', $order->id) }}" 
                                       class="btn btn-primary btn-action">
                                        <i class="fa fa-eye mr-10"></i>Xem chi tiết
                                    </a>
                                    @if($order->status === 'pending' && $order->payment_status === 'pending')
                                        <button type="button" class="btn btn-danger btn-action" onclick="cancelOrder({{ $order->id }})" data-order-id="{{ $order->id }}">
                                            <i class="fa fa-times mr-10"></i>Hủy đơn hàng
                                        </button>
                                    @endif
                                    @if($order->status === 'shipping' && !$order->is_received)
                                        <a href="{{ route('client.order.show', $order->id) }}?action=confirm-received" class="btn btn-success btn-action">
                                            <i class="fa fa-check mr-10"></i>Đã nhận hàng
                                        </a>
                                    @endif
                                    @if($order->status === 'completed' && !$order->is_received)
                                        <a href="{{ route('client.order.show', $order->id) }}?action=confirm-received" class="btn btn-success btn-action">
                                            <i class="fa fa-check mr-10"></i>Đã nhận hàng
                                        </a>
                                    @endif
                                    @if($order->is_received)
                                        <span class="btn btn-outline-success btn-action disabled">
                                            <i class="fa fa-check-circle mr-10"></i>Đã xác nhận nhận hàng
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-orders text-center py-80">
                            <div class="empty-orders-icon mb-40">
                                <div class="icon-circle">
                                    <i class="fa fa-shopping-bag"></i>
                                </div>
                            </div>
                            <h3 class="empty-orders-title mb-20">Bạn chưa có đơn hàng nào</h3>
                            <p class="empty-orders-subtitle text-muted mb-40">
                                Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và bắt đầu mua sắm ngay hôm nay!
                            </p>
                            <div class="empty-orders-actions">
                                <a href="{{ route('client.product') }}" class="btn btn-primary btn-lg">
                                    <i class="fa fa-shopping-bag mr-10"></i>Mua sắm ngay
                                </a>
                                <a href="{{ route('client.home') }}" class="btn btn-outline-secondary btn-lg ml-20">
                                    <i class="fa fa-home mr-10"></i>Về trang chủ
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="pagination-wrapper text-center mt-50">
                        <nav aria-label="Orders pagination">
                            {{ $orders->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-icon {
    animation: float 3s ease-in-out infinite;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Breadcrumb Styling */
.breadcrumb-section {
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: #6c757d;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #667eea;
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 600;
}

/* Alert Styling */
.alert {
    border: none;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.alert-icon {
    font-size: 24px;
    margin-right: 15px;
    flex-shrink: 0;
}

.alert-success .alert-icon {
    color: #28a745;
}

.alert-danger .alert-icon {
    color: #dc3545;
}

.alert-content {
    flex: 1;
}

.alert-heading {
    margin: 0 0 5px 0;
    font-weight: 600;
}

.btn-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #6c757d;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.btn-close:hover {
    background: rgba(0,0,0,0.1);
    color: #495057;
}

/* Order Card Styling */
.order-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    transition: all 0.3s ease;
    overflow: hidden;
}

.order-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

/* Products Section Styling */
.products-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 25px;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.product-item {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.product-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    margin-right: 15px;
    flex-shrink: 0;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.product-placeholder {
    width: 100%;
    height: 100%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.5rem;
    border-radius: 8px;
}

.product-info {
    flex: 1;
    min-width: 0;
}

.product-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 8px 0;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.product-quantity {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.product-price {
    font-size: 0.9rem;
    font-weight: 600;
    color: #dc3545;
}

/* Order Summary Styling */
.order-summary {
    padding-top: 25px;
}

.order-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 25px 30px;
    border-bottom: 1px solid #e9ecef;
}

.order-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.order-id-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-id {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.order-date {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.order-status-badge {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.status-pending {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #000;
}

.status-processing {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.status-shipping {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.status-completed {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    color: white;
}

.status-received {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    color: white;
}

.status-cancelled {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.status-default {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    color: white;
}

.order-card-body {
    padding: 30px;
}

.order-detail-item {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.order-detail-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.detail-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 8px;
}

.detail-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.price-value {
    color: #dc3545;
    font-size: 1.25rem;
}

.payment-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payment-pending {
    background: #fff3cd;
    color: #856404;
}

.payment-paid {
    background: #d4edda;
    color: #155724;
}

.payment-failed {
    background: #f8d7da;
    color: #721c24;
}

.payment-default {
    background: #e2e3e5;
    color: #383d41;
}

.order-card-footer {
    background: #f8f9fa;
    padding: 25px 30px;
    border-top: 1px solid #e9ecef;
}

.order-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.btn-action {
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.btn-outline-success {
    background: transparent;
    border: 2px solid #28a745;
    color: #28a745;
}

.btn-outline-success:hover {
    background: #28a745;
    color: white;
}

.btn-outline-success.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-outline-success.disabled:hover {
    background: transparent;
    color: #28a745;
}

/* Empty Orders State */
.empty-orders {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
}

.icon-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 3rem;
    color: #6c757d;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.empty-orders-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.empty-orders-subtitle {
    font-size: 1.1rem;
    max-width: 500px;
    margin: 0 auto;
}

.empty-orders-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-lg {
    padding: 15px 30px;
    font-size: 1rem;
    border-radius: 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
    background: transparent;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
}

/* Pagination Styling */
.pagination-wrapper {
    margin-top: 50px;
}

.pagination {
    justify-content: center;
}

.page-link {
    border: none;
    color: #667eea;
    padding: 12px 16px;
    margin: 0 5px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background: transparent;
}

/* Animations */
.animate-slide-down {
    animation: slideDown 0.5s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .order-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .order-actions {
        justify-content: center;
    }
    
    .order-detail-item {
        margin-bottom: 15px;
    }
    
    .empty-orders-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-lg {
        width: 100%;
        max-width: 300px;
    }
    
    /* Products Grid Responsive */
    .products-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .product-item {
        padding: 12px;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
        margin-right: 12px;
    }
    
    .product-name {
        font-size: 0.9rem;
    }
    
    .product-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}

@media (max-width: 576px) {
    .hero-section {
        padding: 60px 0;
    }
    
    .order-card-header,
    .order-card-body,
    .order-card-footer {
        padding: 20px;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 6px 12px;
    }
    
    .btn-action {
        width: 100%;
        margin-bottom: 10px;
    }
}

/* Utility Classes */
.mb-30 { margin-bottom: 30px; }
.mb-40 { margin-bottom: 40px; }
.mb-50 { margin-bottom: 50px; }
.mt-50 { margin-top: 50px; }
.py-20 { padding-top: 20px; padding-bottom: 20px; }
.py-60 { padding-top: 60px; padding-bottom: 60px; }
.py-80 { padding-top: 80px; padding-bottom: 80px; }
.ml-20 { margin-left: 20px; }
</style>
@endsection

@push('scripts')
<script>
// Debug: Check if script is loaded
console.log('🔧 Order list page scripts loaded');
console.log('🔧 cancelOrder function available:', typeof window.cancelOrder);
console.log('🔧 confirmReceived function available:', typeof window.confirmReceived);

// Add click event listeners to all cancel buttons
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Setting up cancel order button listeners...');
    const cancelButtons = document.querySelectorAll('.btn-danger[onclick*="cancelOrder"]');
    console.log(`Found ${cancelButtons.length} cancel order buttons`);
    
    cancelButtons.forEach(function(button, index) {
        console.log(`Button ${index + 1}:`, {
            orderId: button.getAttribute('data-order-id'),
            onclick: button.getAttribute('onclick'),
            text: button.textContent.trim()
        });
    });
});

// Function to cancel order - make it globally accessible
window.cancelOrder = function(orderId) {
    console.log('🎯 Cancel order clicked for order ID:', orderId);
    
    // Show cancellation modal
    showCancellationModal(orderId);
}

// Function to show cancellation modal
function showCancellationModal(orderId) {
    const modalHtml = `
        <div class="modal fade" id="cancellationModal" tabindex="-1" role="dialog" aria-labelledby="cancellationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="cancellationModalLabel">
                            <i class="fa fa-exclamation-triangle"></i> Xác nhận hủy đơn hàng
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="cancellationForm" method="POST">
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fa fa-info-circle"></i>
                                <strong>Lưu ý:</strong> Việc hủy đơn hàng sẽ hoàn lại số lượng sản phẩm vào kho và thông báo cho admin.
                            </div>
                            
                            <div class="form-group">
                                <label for="cancellation_reason" class="form-label">
                                    <strong>Lý do hủy đơn hàng <span class="text-danger">*</span></strong>
                                </label>
                                <textarea 
                                    class="form-control" 
                                    id="cancellation_reason" 
                                    name="cancellation_reason" 
                                    rows="4" 
                                    placeholder="Vui lòng nhập lý do hủy đơn hàng (tối thiểu 10 ký tự)..."
                                    required
                                    minlength="10"
                                    maxlength="500"
                                ></textarea>
                                <div class="form-text">
                                    <span id="charCount">0</span>/500 ký tự
                                </div>
                                <div class="invalid-feedback">
                                    Vui lòng nhập lý do hủy đơn hàng (tối thiểu 10 ký tự)
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fa fa-times"></i> Hủy bỏ
                            </button>
                            <button type="submit" class="btn btn-danger" id="confirmCancelBtn">
                                <i class="fa fa-check"></i> Xác nhận hủy đơn hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#cancellationModal').remove();
    
    // Add modal to body
    $('body').append(modalHtml);
    
    // Set form action
    const actionUrl = `{{ route('client.order.cancel', ':orderId') }}`.replace(':orderId', orderId);
    $('#cancellationForm').attr('action', actionUrl);
    
    // Add method override
    const methodField = $('<input>').attr({
        type: 'hidden',
        name: '_method',
        value: 'PUT'
    });
    $('#cancellationForm').append(methodField);
    
    // Add CSRF token
    const csrfToken = $('<input>').attr({
        type: 'hidden',
        name: '_token',
        value: $('meta[name="csrf-token"]').attr('content')
    });
    $('#cancellationForm').append(csrfToken);
    
    // Show modal
    $('#cancellationModal').modal('show');
    
    // Character count
    $('#cancellation_reason').on('input', function() {
        const count = $(this).val().length;
        $('#charCount').text(count);
        
        if (count < 10) {
            $(this).addClass('is-invalid');
            $('#confirmCancelBtn').prop('disabled', true);
        } else {
            $(this).removeClass('is-invalid');
            $('#confirmCancelBtn').prop('disabled', false);
        }
    });
    
    // Form submission
    $('#cancellationForm').on('submit', function(e) {
        e.preventDefault();
        
        const reason = $('#cancellation_reason').val().trim();
        if (reason.length < 10) {
            alert('Vui lòng nhập lý do hủy đơn hàng (tối thiểu 10 ký tự)');
            return;
        }
        
        // Disable submit button
        $('#confirmCancelBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
        
        // Submit form
        this.submit();
    });
}

// Function to confirm received order - make it globally accessible
window.confirmReceived = function(orderId) {
    if (confirm('Bạn có chắc chắn đã nhận hàng thành công?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/order/${orderId}/confirm-received`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush