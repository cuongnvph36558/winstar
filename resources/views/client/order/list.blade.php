@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@section('content')
<section class="module bg-light">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="row mb-30">
            <div class="col-sm-12">
                <ol class="breadcrumb font-alt">
                    <li><a href="{{ route('client.home') }}"><i class="fa fa-home"></i></a></li>
                    <li class="active">Đơn hàng của tôi</li>
                </ol>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <h1 class="module-title font-alt mb-30">
                    <i class="fa fa-list mr-10"></i>Đơn hàng của tôi
                </h1>
                <p class="lead">Xem và theo dõi các đơn hàng của bạn</p>
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
            <div class="col-sm-12">
                <div class="cart-section bg-white rounded-lg shadow-sm p-30">
                    <!-- Desktop Table View -->
                    <div class="cart-table-wrapper hidden-xs hidden-sm">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Thanh toán</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr class="cart-item">
                                        <td>
                                            <span class="order-id font-alt">{{ $order->code_order ?? ('#' . $order->id) }}</span>
                                        </td>
                                        <td>
                                            <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="price">{{ number_format($order->total_amount) }}đ</span>
                                        </td>
                                        <td class="text-center">
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
                                                    <span class="label label-danger">Đã hủy</span>
                                                    @break
                                                @default
                                                    <span class="label label-default">{{ $order->status }}</span>
                                            @endswitch
                                        </td>
                                        <td class="text-center">
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
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('client.order.show', $order->id) }}" 
                                                   class="btn btn-primary btn-sm" 
                                                   title="Xem chi tiết">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('client.order.track', $order->id) }}" 
                                                   class="btn btn-info btn-sm"
                                                   title="Theo dõi đơn hàng">
                                                    <i class="fa fa-truck"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-cart text-center p-50">
                                                <div class="empty-cart-icon mb-30">
                                                    <i class="fa fa-shopping-cart" style="font-size: 120px; color: #e9ecef;"></i>
                                                </div>
                                                <h3 class="mb-20">Bạn chưa có đơn hàng nào</h3>
                                                <p class="text-muted mb-30">
                                                    Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và đặt hàng ngay.
                                                </p>
                                                <a href="{{ route('client.product') }}" class="btn btn-primary btn-lg">
                                                    <i class="fa fa-shopping-bag mr-10"></i>Mua sắm ngay
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="cart-mobile-view visible-xs visible-sm">
                        @forelse($orders as $order)
                            <div class="cart-item-card mb-20">
                                <div class="order-header">
                                    <span class="order-id font-alt">{{ $order->code_order ?? ('#' . $order->id) }}</span>
                                    <span class="order-date pull-right">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="order-body">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <small class="text-muted">Tổng tiền:</small>
                                            <div class="price">{{ number_format($order->total_amount) }}đ</div>
                                        </div>
                                        <div class="col-xs-6">
                                            <small class="text-muted">Trạng thái:</small>
                                            <div>
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
                                                        <span class="label label-danger">Đã hủy</span>
                                                        @break
                                                    @default
                                                        <span class="label label-default">{{ $order->status }}</span>
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-10">
                                        <div class="col-xs-6">
                                            <small class="text-muted">Thanh toán:</small>
                                            <div>
                                                @switch($order->payment_status)
                                                    @case('pending')
                                                        <span class="label label-warning">Chưa thanh toán</span>
                                                        @break
                                                    @case('paid')
                                                        <span class="label label-success">Đã thanh toán</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="label label-danger">Đã hủy</span>
                                                        @break
                                                    @default
                                                        <span class="label label-default">{{ $order->payment_status }}</span>
                                                @endswitch
                                            </div>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <div class="btn-group">
                                                <a href="{{ route('client.order.show', $order->id) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i> Chi tiết
                                                </a>
                                                <a href="{{ route('client.order.track', $order->id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fa fa-truck"></i> Theo dõi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-cart text-center p-50">
                                <div class="empty-cart-icon mb-30">
                                    <i class="fa fa-shopping-cart" style="font-size: 80px; color: #e9ecef;"></i>
                                </div>
                                <h4 class="mb-20">Bạn chưa có đơn hàng nào</h4>
                                <p class="text-muted mb-30">
                                    Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và đặt hàng ngay.
                                </p>
                                <a href="{{ route('client.product') }}" class="btn btn-primary">
                                    <i class="fa fa-shopping-bag mr-10"></i>Mua sắm ngay
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if($orders->hasPages())
                        <div class="pagination-wrapper text-center mt-30">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
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

.order-id {
    font-weight: 600;
    color: #333;
}

.order-date {
    color: #6c757d;
}

.price {
    font-size: 16px;
    font-weight: 600;
    color: #dc3545;
}

/* Mobile Card View */
.cart-item-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.order-header {
    padding-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 15px;
}

.mt-10 {
    margin-top: 10px;
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

.label-danger {
    background-color: #dc3545;
    color: #fff;
}

.label-default {
    background-color: #6c757d;
    color: #fff;
}

/* Empty Cart State */
.empty-cart {
    padding: 40px 0;
}

.empty-cart-icon {
    margin-bottom: 20px;
}

/* Buttons */
.btn-group .btn {
    margin: 0 2px;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 30px;
}

.pagination {
    margin: 0;
}

/* Responsive Adjustments */
@media (max-width: 767px) {
    .cart-section {
        padding: 15px !important;
    }
    
    .cart-item-card {
        margin-bottom: 15px;
    }
    
    .btn-group .btn {
        padding: 6px 12px;
    }
}
</style>
@endsection 

@push('scripts')
<script>
// Lắng nghe realtime cập nhật trạng thái đơn hàng
document.addEventListener('DOMContentLoaded', function() {
    // Lấy user ID từ meta tag
    window.currentUserId = document.querySelector('meta[name="auth-user"]') ? 
        parseInt(document.querySelector('meta[name="auth-user"]').getAttribute('content')) : null;
    
    if (window.pusher && window.currentUserId) {
        console.log('🔧 Setting up realtime order status listener for user:', window.currentUserId);
        
        // Lắng nghe channel riêng cho user
        const userChannel = window.pusher.subscribe(`private-user.${window.currentUserId}`);
        userChannel.bind('OrderStatusUpdated', function(e) {
            console.log('📡 Received OrderStatusUpdated event from private channel:', e);
            
            if (e.order && e.order.user_id == window.currentUserId) {
                console.log('✅ Order belongs to current user, reloading page...');
                location.reload();
            } else {
                console.log('❌ Order does not belong to current user or missing data');
            }
        });
        
        // Lắng nghe channel chung orders (backup)
        const ordersChannel = window.pusher.subscribe('orders');
        ordersChannel.bind('OrderStatusUpdated', function(e) {
            console.log('📡 Received OrderStatusUpdated event from orders channel:', e);
            
            if (e.order && e.order.user_id == window.currentUserId) {
                console.log('✅ Order belongs to current user, reloading page...');
                location.reload();
            }
        });
        
        console.log('✅ Realtime order status listener setup complete');
    } else {
        console.error('❌ Pusher not available or user not authenticated for realtime order status');
        console.log('Pusher available:', !!window.pusher);
        console.log('Current user ID:', window.currentUserId);
    }
});
</script>
@endpush