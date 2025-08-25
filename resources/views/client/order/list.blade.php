@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@if(auth()->check())
<meta name="auth-user" content="{{ auth()->id() }}">
@else
<meta name="auth-user" content="not_logged_in">
@endif

@push('styles')
<style>
/* Realtime animations for order list */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.02);
    }
}

@keyframes slideInDown {
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
    }
    to {
        opacity: 1;
    }
}

.order-item {
    transition: all 0.3s ease;
}

.order-item.updating {
    animation: pulse 1s ease-in-out;
}

.order-item.new {
    animation: slideInDown 0.5s ease-out;
}

.status-badge {
    transition: all 0.3s ease;
}

.status-badge.updating {
    animation: pulse 0.5s ease-in-out;
}



/* Status-specific animations */
.status-badge.status-pending {
    background-color: #3B82F6;
    color: white;
}

.status-badge.status-processing {
    background-color: #F59E0B;
    color: white;
}

.status-badge.status-shipping {
    background-color: #3B82F6;
    color: white;
}

.status-badge.status-delivered {
    background-color: #F97316;
    color: white;
}

.status-badge.status-received {
    background-color: #8B5CF6;
    color: white;
}

.status-badge.status-completed {
    background-color: #10B981;
    color: white;
}

.status-badge.status-cancelled {
    background-color: #EF4444;
    color: white;
}
</style>
@endpush

@push('scripts')
{{-- Realtime is handled by layout script --}}
<!-- Pusher disabled - Real-time notifications turned off -->
<!-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script> -->
<script>
// Realtime Order List Updates (DISABLED - All notifications turned off)
document.addEventListener('DOMContentLoaded', function() {
    console.log('ℹ️ Realtime notifications completely disabled');
    
    // Initialize Pusher for realtime updates (DISABLED)
    // const pusher = new Pusher('{{ env("PUSHER_APP_KEY", "localkey123") }}', {
    //     cluster: '{{ env("PUSHER_APP_CLUSTER", "mt1") }}',
    //     encrypted: false,
    //     wsHost: '{{ env("PUSHER_HOST", "127.0.0.1") }}',
    //     wsPort: {{ env("PUSHER_PORT", 6001) }},
    //     forceTLS: false,
    //     enabledTransports: ['ws', 'wss'],
    //     activityTimeout: 30000,
    //     pongTimeout: 15000,
    //     maxReconnectionAttempts: 5,
    //     maxReconnectGap: 5000
    // });

    // Subscribe to user's order channels (DISABLED)
    // const userId = {{ auth()->id() ?? 'null' }};
    // console.log removed
    
    // if (userId) {
    //     const userChannel = pusher.subscribe('private-user.' + userId);
    //     // console.log removed
        
    //     // Also subscribe to a public channel for testing
    //     const publicChannel = pusher.subscribe('orders');
    //     // console.log removed
        
    //     // Listen for order status updates
    //     userChannel.bind('App\\Events\\OrderStatusUpdated', function(data) {
    //         console.log('🎯 Order status updated via WebSocket (private):', data);
    //         updateOrderInList(data);
    //     });
        
    //     // Listen for new orders
    //     userChannel.bind('App\\Events\\NewOrderPlaced', function(data) {
    //         console.log('🎯 New order placed via WebSocket (private):', data);
    //         addNewOrderToList(data);
    //     });
        
    //     // Listen for order cancellations
    //     userChannel.bind('App\\Events\\OrderCancelled', function(data) {
    //         console.log('🎯 Order cancelled via WebSocket (private):', data);
    //         updateOrderInList(data);
    //     });
        
    //     // Listen on public channel too
    //     publicChannel.bind('App\\Events\\OrderStatusUpdated', function(data) {
    //         console.log('🎯 Order status updated via WebSocket (public):', data);
    //         updateOrderInList(data);
    //     });
        
    //     // Add channel subscription status
    //     userChannel.bind('pusher:subscription_succeeded', function() {
    //         // console.log removed
    //     });
        
    //     userChannel.bind('pusher:subscription_error', function(status) {
    //         console.error('🎯 Failed to subscribe to user channel:', status);
    //     });
        
    //     publicChannel.bind('pusher:subscription_succeeded', function() {
    //         // console.log removed
    //     });
        
    //     publicChannel.bind('pusher:subscription_error', function(status) {
    //         console.error('🎯 Failed to subscribe to public orders channel:', status);
    //     });
    // } else {
    //     console.error('🎯 No user ID found, cannot subscribe to realtime updates');
    // }

    // Function to update order status in the list
    function updateOrderInList(data) {
        const orderItem = document.querySelector(`[data-order-id="${data.order_id}"]`);
        if (!orderItem) {
            // console.log removed
            return;
        }
        
        // console.log removed
        
        // Update status badge
        const statusBadge = orderItem.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = `status-badge status-${data.status}`;
            statusBadge.textContent = getStatusText(data.status);
        }
        
        // Update order item data attribute
        orderItem.setAttribute('data-status', data.status);
        
        // Update action buttons based on new status
        updateActionButtons(orderItem, data.status);
        
        // Add visual feedback
        orderItem.style.animation = 'pulse 1s ease-in-out';
        setTimeout(() => {
            orderItem.style.animation = '';
        }, 1000);
        
        // Update order count if needed
        updateOrderCount();
    }

    // Function to add new order to the list
    function addNewOrderToList(data) {
        // console.log removed
        
        // Create new order item HTML
        const newOrderHTML = createOrderItemHTML(data);
        
        // Add to the beginning of the orders container
        const ordersContainer = document.getElementById('ordersContainer');
        if (ordersContainer) {
            ordersContainer.insertAdjacentHTML('afterbegin', newOrderHTML);
            
            // Add visual feedback
            const newOrderItem = ordersContainer.querySelector(`[data-order-id="${data.order_id}"]`);
            if (newOrderItem) {
                newOrderItem.style.animation = 'slideInDown 0.5s ease-out';
                setTimeout(() => {
                    newOrderItem.style.animation = '';
                }, 500);
            }
        }
        
        // Update order count
        updateOrderCount();
    }

    // Function to get status text
    function getStatusText(status) {
        const statusMap = {
            'pending': 'Chờ xử lý',
            'processing': 'Đang chuẩn bị hàng',
            'shipping': 'Đang giao hàng',
            'delivered': 'Đã giao hàng',
            'received': 'Đã nhận hàng',
            'completed': 'Hoàn thành',
            'cancelled': 'Đã hủy'
        };
        return statusMap[status] || status;
    }

    // Function to update action buttons based on status
    function updateActionButtons(orderItem, status) {
        const actionButtons = orderItem.querySelector('.order-actions');
        if (!actionButtons) return;
        
        const orderId = orderItem.getAttribute('data-order-id');
        const orderCreatedAt = orderItem.getAttribute('data-created-at');
        
        let buttonsHTML = '';
        
        // Kiểm tra logic chỉnh sửa (15 phút đầu và trạng thái phù hợp)
        const canEdit = false;
        if (orderCreatedAt) {
            const createdTime = new Date(orderCreatedAt);
            const timeLimit = new Date(Date.now() - 15 * 60 * 1000); // 15 phút trước
            const isWithinTimeLimit = createdTime > timeLimit;
            const isEditableStatus = ['pending', 'processing'].includes(status);
            canEdit = isWithinTimeLimit && isEditableStatus;
        }
        
        // Kiểm tra logic hủy đơn
        const canCancel = (status === 'pending') || 
                         (status === 'processing' && orderCreatedAt && 
                          new Date(orderCreatedAt) > new Date(Date.now() - 15 * 60 * 1000));
        
        switch (status) {
            case 'pending':
                buttonsHTML = `
                    <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-eye"></i>Xem chi tiết
                    </a>
                    ${canEdit ? `
                    <a href="{{ route('client.order.edit', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-edit"></i>Chỉnh sửa
                    </a>
                    ` : ''}
                    ${canCancel ? `
                    <button onclick="showCancellationModal(':orderId')" class="action-btn action-btn-danger">
                        <i class="fas fa-times"></i>Hủy đơn hàng
                    </button>
                    ` : ''}
                `;
                break;
            case 'processing':
                buttonsHTML = `
                    <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-eye"></i>Xem chi tiết
                    </a>
                    ${canEdit ? `
                    <a href="{{ route('client.order.edit', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-edit"></i>Chỉnh sửa
                    </a>
                    ` : ''}
                    ${canCancel ? `
                    <button onclick="showCancellationModal(':orderId')" class="action-btn action-btn-danger">
                        <i class="fas fa-times"></i>Hủy đơn hàng
                    </button>
                    ` : ''}
                `;
                break;
            case 'shipping':
                buttonsHTML = `
                    <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-eye"></i>Xem chi tiết
                    </a>
                    <form method="POST" action="/order/:orderId/confirm-received" class="inline">
                        @csrf
                        <button type="submit" class="action-btn action-btn-success">
                            <i class="fas fa-check"></i>Đã nhận hàng
                        </button>
                    </form>
                `;
                break;
            case 'delivered':
                buttonsHTML = `
                    <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-eye"></i>Xem chi tiết
                    </a>
                    <form method="POST" action="/order/:orderId/confirm-received" class="inline">
                        @csrf
                        <button type="submit" class="action-btn action-btn-success">
                            <i class="fas fa-check"></i>Đã nhận hàng
                        </button>
                    </form>
                `;
                break;
            case 'completed':
                buttonsHTML = `
                    <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-eye"></i>Xem chi tiết
                    </a>
                    <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-secondary">
                        <i class="fas fa-star"></i>Đánh giá sản phẩm
                    </a>
                `;
                break;
            default:
                buttonsHTML = `
                    <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                        <i class="fas fa-eye"></i>Xem chi tiết
                    </a>
                `;
        }
        
        actionButtons.innerHTML = buttonsHTML.replace(/:orderId/g, orderId);
        
        // Re-attach event listeners for new buttons
        attachEventListeners(orderItem);
    }

    // Function to create order item HTML
    function createOrderItemHTML(data) {
        return `
            <div class="order-item" data-order-id="${data.order_id}" data-status="${data.status}" data-created-at="${data.created_at}">
                <div class="order-header">
                    <div class="order-header-main">
                        <div class="order-info">
                            <p class="order-code">
                                Đơn hàng #${data.order_code || data.order_id}
                            </p>
                            <p class="order-date">
                                Ngày: ${new Date(data.created_at).toLocaleDateString('vi-VN')}
                            </p>
                        </div>
                        <span class="status-badge status-${data.status}">
                            ${getStatusText(data.status)}
                        </span>
                    </div>
                    <button class="toggle-details">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="order-details" style="display: none;">
                    <div class="order-summary">
                        <p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat('vi-VN').format(data.total_amount || 0)}₫</p>
                        <p><strong>Trạng thái thanh toán:</strong> ${data.payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'}</p>
                    </div>
                    <div class="order-actions">
                        <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                            <i class="fas fa-eye"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        `;
    }

    // Function to update order count
    function updateOrderCount() {
        const titleElement = document.querySelector('.order-list-title');
        if (titleElement) {
            const visibleOrders = document.querySelectorAll('.order-item[style*="display: block"], .order-item:not([style*="display: none"])');
            const totalOrders = document.querySelectorAll('.order-item').length;
            titleElement.textContent = `Đơn hàng của tôi (${totalOrders})`;
        }
    }

    // Function to attach event listeners
    function attachEventListeners(orderItem) {
        // Re-attach toggle functionality
        const toggleButton = orderItem.querySelector('.toggle-details');
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                const details = orderItem.querySelector('.order-details');
                const icon = this.querySelector('i');
                
                if (details.style.display === 'none') {
                    details.style.display = 'block';
                    icon.className = 'fas fa-chevron-up';
                } else {
                    details.style.display = 'none';
                    icon.className = 'fas fa-chevron-down';
                }
            });
        }
    }

    // Add connection status monitoring
    pusher.connection.bind('connected', function() {
        // console.log removed
    });

    pusher.connection.bind('error', function(err) {
        console.error('🎯 WebSocket connection error:', err);
    });

    pusher.connection.bind('disconnected', function() {
        // console.log removed
    });
    
    // Polling fallback for order updates (every 5 seconds)
    setInterval(function() {
        // console.log removed
        
        // Get all order IDs from the page
        const orderItems = document.querySelectorAll('.order-item');
        const orderIds = Array.from(orderItems).map(item => item.getAttribute('data-order-id'));
        
        // Check each order's status
        orderIds.forEach(orderId => {
            fetch(`/order/${orderId}/status`, {
                method: 'GET',
                headers: {
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                }
            })
            .then(response => response.json())
            .then(data => {
                const orderItem = document.querySelector(`[data-order-id="${orderId}"]`);
                if (orderItem) {
                    const currentStatus = orderItem.getAttribute('data-status');
                    if (data.status && data.status !== currentStatus) {
                        // console.log removed
                        updateOrderInList({
                            order_id: parseInt(orderId),
                            status: data.status,
                            payment_status: data.payment_status,
                            subtotal: data.subtotal,
                            discount_amount: data.discount_amount,
                            shipping_fee: data.shipping_fee,
                            total_amount: data.total_amount
                        });
                    }
                }
            })
            .catch(error => console.log('🎯 Polling error for order', orderId, ':', error));
        });
    }, 5000);
});
</script>
@endpush

@section('content')
<!-- Modern Order List with custom CSS -->
<div class="order-list-modern" style="max-width: 54rem; margin: 0 auto; padding: 0 1rem;">
    <div class="order-list-container">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Header section -->
        <div class="order-list-header">
            <h1 class="order-list-title">
                Đơn hàng của tôi ({{ $orders->count() }})
            </h1>
            
            <div class="order-list-filters">
                <div class="filter-select">
                    <select id="statusFilter">
                        <option value="all">Tất cả đơn hàng</option>
                        <option value="pending">Chờ xử lý</option>
                        <option value="processing">Đang chuẩn bị hàng</option>
                        <option value="shipping">Đang giao hàng</option>
                        <option value="delivered">Đã giao hàng</option>
                        <option value="received">Đã nhận hàng</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                    <i class="fas fa-chevron-down"></i>
                </div>
                
                <div class="filter-month">
                    <input type="month" id="monthFilter">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Order List -->
        <div class="orders-container" id="ordersContainer">
            @forelse($orders as $order)
                <div class="order-item" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}" data-created-at="{{ $order->created_at }}">
                    <div class="order-header">
                        <div class="order-header-main">
                            <div class="order-info">
                                <p class="order-code">
                                    Đơn hàng #{{ $order->code_order ?? $order->id }}
                                </p>
                                <p class="order-date">
                                    Ngày: {{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                            <span class="status-badge status-{{ $order->status }}">
                                @switch($order->status)
                                    @case('pending')
                                        Chờ xử lý
                                        @break
                                    @case('processing')
                                        Đang chuẩn bị hàng
                                        @break
                                    @case('shipping')
                                        Đang giao hàng
                                        @break
                                    @case('delivered')
                                        Đã giao hàng
                                        @break
                                    @case('received')
                                        Đã nhận hàng
                                        @break
                                    @case('completed')
                                        Hoàn thành
                                        @break
                                    @case('cancelled')
                                        Đã hủy
                                        @break
                                    @default
                                        {{ $order->status }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="order-summary">
                            <p class="order-total">{{ number_format($order->total_amount) }}₫</p>
                            <button class="toggle-details">
                                <i class="fas fa-chevron-down"></i> Chi tiết
                            </button>
                        </div>
                    </div>
                    
                    <div class="order-content">
                        <div class="order-content-inner">
                            @foreach($order->orderDetails as $orderDetail)
                                <div class="product-item">
                                    <div class="product-image">
                                        @if($orderDetail->variant && $orderDetail->variant->image_variant)
                                            <img src="{{ asset('storage/' . (is_array(json_decode($orderDetail->variant->image_variant, true)) ? json_decode($orderDetail->variant->image_variant, true)[0] : $orderDetail->variant->image_variant) ) }}" 
                                                 alt="{{ $orderDetail->product->name ?? 'Sản phẩm' }}"
                                                 onerror="this.parentElement.innerHTML='<i class=\'fas fa-image\'></i>'">
                                        @elseif($orderDetail->product && $orderDetail->product->image)
                                            <img src="{{ asset('storage/' . $orderDetail->product->image) }}" 
                                                 alt="{{ $orderDetail->product->name }}"
                                                 onerror="this.parentElement.innerHTML='<i class=\'fas fa-image\'></i>'">
                                        @else
                                            <i class="fas fa-image"></i>
                                        @endif
                                    </div>
                                    <div class="product-details">
                                        <p class="product-name">
                                            <a href="{{ route('client.single-product', $orderDetail->product_id) }}">
                                                {{ $orderDetail->product->name ?? 'Sản phẩm không tồn tại' }}
                                            </a>
                                        </p>
                                        <p class="product-meta">
                                            Số lượng: {{ $orderDetail->quantity }}
                                            @if($orderDetail->original_storage_capacity)
                                                • {{ \App\Helpers\StorageHelper::formatCapacity($orderDetail->original_storage_capacity) }}
                                            @elseif($orderDetail->original_storage_name)
                                                • {{ $orderDetail->original_storage_name }}
                                            @elseif($orderDetail->variant)
                                                @if($orderDetail->variant->storage && isset($orderDetail->variant->storage->capacity))
                                                    • {{ \App\Helpers\StorageHelper::formatCapacity($orderDetail->variant->storage->capacity) }}
                                                @endif
                                            @endif
                                            @if($orderDetail->original_color_name)
                                                • {{ $orderDetail->original_color_name }}
                                            @elseif($orderDetail->variant && $orderDetail->variant->color)
                                                • {{ $orderDetail->variant->color->name }}
                                            @endif
                                        </p>
                                    </div>
                                    <p class="product-price">
                                        {{ number_format($orderDetail->price * $orderDetail->quantity) }}₫
                                    </p>
                                </div>
                            @endforeach
                            
                            <div class="payment-info">
                                <span class="payment-label">Phương thức thanh toán:</span>
                                <span class="payment-value">
                                    @switch($order->payment_method)
                                        @case('cod')
                                            Tiền mặt (COD)
                                            @break
                                        @case('bank_transfer')
                                            Chuyển khoản
                                            @break
                                        @case('vnpay')
                                            VNPay
                                            @break
                                        @default
                                            {{ $order->payment_method }}
                                    @endswitch
                                </span>
                            </div>

                            @if($order->payment_status === 'pending')
                                <div class="payment-status">
                                    <span class="payment-status-label">Trạng thái thanh toán:</span>
                                    <span class="payment-status-value pending">Chưa thanh toán</span>
                                </div>
                            @elseif($order->payment_status === 'paid')
                                <div class="payment-status">
                                    <span class="payment-status-label">Trạng thái thanh toán:</span>
                                    <span class="payment-status-value paid">Đã thanh toán</span>
                                </div>
                            @endif
                            
                            <div class="order-actions">
                                <a href="{{ route('client.order.show', $order->id) }}" 
                                   class="action-btn action-btn-primary">
                                    <i class="fas fa-eye"></i>Xem chi tiết
                                </a>
                                
                                @php
                                    $canCancel = false;
                                    $cancelMessage = '';
                                    $canEdit = false;
                                    $editMessage = '';
                                    
                                    // Kiểm tra có thể chỉnh sửa không
                                    $orderCreatedTime = $order->created_at;
                                    $timeLimit = now()->subMinutes(15);
                                    
                                    if ($orderCreatedTime->gt($timeLimit) && in_array($order->status, ['pending', 'processing'])) {
                                        $canEdit = true;
                                    }
                                    
                                    // Trường hợp 1: Đơn hàng chưa thanh toán
                                    if ($order->status === 'pending' && $order->payment_status === 'pending') {
                                        $canCancel = true;
                                    }
                                    // Trường hợp 2: Đơn hàng online đã thanh toán nhưng trong 15 phút đầu
                                    elseif ($order->status === 'processing' && $order->payment_status === 'paid') {
                                        if ($orderCreatedTime->gt($timeLimit)) {
                                            $canCancel = true;
                                            $cancelMessage = 'Lưu ý: Đơn hàng đã thanh toán. Bạn chỉ có thể hủy trong 15 phút đầu.';
                                        }
                                    }
                                @endphp
                                
                                @if($canEdit)
                                    <a href="{{ route('client.order.edit', $order->id) }}" 
                                       class="action-btn action-btn-primary"
                                       title="Chỉnh sửa thông tin đơn hàng">
                                        <i class="fas fa-edit"></i>Chỉnh sửa
                                    </a>
                                @endif
                                
                                @if($canCancel)
                                    <button onclick="cancelOrder({{ $order->id }})" 
                                            class="action-btn action-btn-danger"
                                            @if($cancelMessage) title="{{ $cancelMessage }}" @endif>
                                        <i class="fas fa-times"></i>Hủy đơn
                                    </button>
                                @endif
                                
                                @if($order->status === 'shipping' && !$order->is_received)
                                    <a href="{{ route('client.order.show', $order->id) }}?action=confirm-received" 
                                       class="action-btn action-btn-success">
                                        <i class="fas fa-check"></i>Đã nhận hàng
                                    </a>
                                @endif
                                
                                @if($order->status === 'delivered')
                                    <form method="POST" action="{{ route('client.order.confirm-received', $order->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="action-btn action-btn-success">
                                            <i class="fas fa-check"></i>Xác nhận đã nhận
                                        </button>
                                    </form>
                                @endif
                                
                                @if($order->status === 'received')
                                    <span class="action-btn action-btn-disabled">
                                        <i class="fas fa-check-circle"></i>Đã xác nhận nhận hàng
                                    </span>
                                @endif
                                
                                @if($order->status === 'completed')
                                    <a href="{{ route('client.order.show', $order->id) }}" class="action-btn action-btn-primary">
                                        <i class="fas fa-star"></i>Đánh giá sản phẩm
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3 class="empty-title">Bạn chưa có đơn hàng nào</h3>
                    <p class="empty-description">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và bắt đầu mua sắm ngay hôm nay!</p>
                    <div class="empty-actions">
                        <a href="{{ route('client.product') }}" class="empty-btn empty-btn-primary">
                            <i class="fas fa-shopping-bag"></i>Mua sắm ngay
                        </a>
                        <a href="{{ route('client.home') }}" class="empty-btn empty-btn-secondary">
                            <i class="fas fa-home"></i>Về trang chủ
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pagination-container">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Review Prompt Modal -->
<div id="reviewPromptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full">
                <i class="fas fa-star text-green-600"></i>
            </div>
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">🎉 Đơn hàng đã hoàn thành!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-600 mb-4">Cảm ơn bạn đã mua sắm tại WinStar! Chúng tôi rất mong nhận được đánh giá của bạn về sản phẩm và dịch vụ.</p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800 font-medium">💡 Đánh giá của bạn giúp chúng tôi:</p>
                        <ul class="text-xs text-blue-700 mt-2 space-y-1">
                            <li>• Cải thiện chất lượng sản phẩm</li>
                            <li>• Nâng cao dịch vụ khách hàng</li>
                            <li>• Giúp khách hàng khác lựa chọn tốt hơn</li>
                        </ul>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="goToOrderDetail()" class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <i class="fas fa-star mr-2"></i>Đánh giá ngay
                        </button>
                        <button onclick="closeReviewPrompt()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Để sau
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
    <link href="{{ asset('css/order-list-enhanced.css') }}" rel="stylesheet">
@endsection

@push('scripts')
<script>
// Toggle order details
document.addEventListener('DOMContentLoaded', function() {
    // console.log removed
    
    // Toggle order details functionality
    const toggleButtons = document.querySelectorAll('.toggle-details');
    // console.log removed
    
    toggleButtons.forEach((button, index) => {
        // console.log removed
        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            // console.log removed
            
            const orderItem = button.closest('.order-item');
            if (orderItem) {
                const wasActive = orderItem.classList.contains('active');
                orderItem.classList.toggle('active');
                const isActive = orderItem.classList.contains('active');
                // console.log removed
                
                const icon = button.querySelector('i');
                if (icon) {
                    if (isActive) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                        // console.log removed
                    } else {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                        // console.log removed
                    }
                } else {
                    // console.log removed
                }
            } else {
                // console.log removed
            }
        });
    });

    // Status filter functionality
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        // console.log removed
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            // console.log removed
            
            const orders = document.querySelectorAll('.order-item');
            orders.forEach(order => {
                const statusBadge = order.querySelector('.status-badge');
                if (statusBadge) {
                    const orderStatus = statusBadge.textContent.trim().toLowerCase();
                    // Map Vietnamese status text to English status values
                    const statusMap = {
                        'chờ xử lý': 'pending',
                        'đang chuẩn bị hàng': 'processing',
                        'đang giao hàng': 'shipping',
                        'đã giao hàng': 'delivered',
                        'đã nhận hàng': 'received',
                        'hoàn thành': 'completed',
                        'đã hủy': 'cancelled'
                    };
                    
                    const mappedStatus = statusMap[orderStatus] || orderStatus;
                    if (status === 'all' || mappedStatus === status) {
                        order.style.display = 'block';
                    } else {
                        order.style.display = 'none';
                    }
                }
            });
        });
    }

    // Month filter functionality
    const monthFilter = document.getElementById('monthFilter');
    if (monthFilter) {
        // console.log removed
        monthFilter.addEventListener('change', function() {
            const selectedMonth = this.value;
            // console.log removed
            
            if (!selectedMonth) {
                // Show all orders if no month selected
                document.querySelectorAll('.order-item').forEach(order => {
                    order.style.display = 'block';
                });
                return;
            }
            
            const orders = document.querySelectorAll('.order-item');
            const [year, month] = selectedMonth.split('-');
            
            orders.forEach(order => {
                const orderDateElement = order.querySelector('.order-date');
                if (orderDateElement) {
                    const orderDate = orderDateElement.textContent;
                    // Parse date format like "25/12/2024"
                    const orderMonth = orderDate.match(/(\d{2})\/(\d{2})\/(\d{4})/);
                    
                    if (orderMonth) {
                        const [, day, orderMonthNum, orderYear] = orderMonth;
                        if (orderYear === year && orderMonthNum === month) {
                            order.style.display = 'block';
                        } else {
                            order.style.display = 'none';
                        }
                    }
                }
            });
        });
    }

    // Handle confirm received form submissions
    const confirmForms = document.querySelectorAll('form[action*="confirm-received"]');
    // console.log removed

    confirmForms.forEach((form, index) => {
        // console.log removed
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // console.log removed

            const button = form.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';

            // Submit form via AJAX
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // console.log removed
                
                if (data.success) {
                    // Update order status in the UI
                    const orderItem = form.closest('.order-item');
                    const statusBadge = orderItem.querySelector('.status-badge');
                    const actionButtons = orderItem.querySelector('.order-actions');
                    
                    // Update status badge
                    statusBadge.className = 'status-badge status-completed';
                    statusBadge.textContent = 'Hoàn thành';
                    
                    // Replace action buttons with review button
                    actionButtons.innerHTML = `
                        <a href="{{ route('client.order.show', ':orderId') }}" class="action-btn action-btn-primary">
                            <i class="fas fa-star"></i>Đánh giá sản phẩm
                        </a>
                    `.replace(':orderId', orderId);
                    
                    // Show success notification
                    showNotification(data.message || '🎉 Đã xác nhận nhận hàng thành công! Đơn hàng đã hoàn thành.', 'success');
                    
                    // Show review prompt modal only if status is completed
                    if (data.status === 'completed') {
                        setTimeout(() => {
                            showReviewPromptModal(orderId);
                        }, 1000);
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('🎯 Confirm received error:', error);
                
                // Re-enable button
                button.disabled = false;
                button.innerHTML = originalText;
                
                // Show error notification
                let errorMessage = 'Có lỗi xảy ra khi xác nhận nhận hàng';
                if (error.message) {
                    if (error.message.includes('HTTP error! status: 403')) {
                        errorMessage = 'Bạn không có quyền thực hiện hành động này';
                    } else if (error.message.includes('HTTP error! status: 404')) {
                        errorMessage = 'Đơn hàng không tồn tại';
                    } else if (error.message.includes('HTTP error! status: 422')) {
                        errorMessage = 'Dữ liệu không hợp lệ';
                    } else {
                        errorMessage = error.message;
                    }
                }
                
                showNotification('❌ ' + errorMessage, 'error');
            });
        });
    });

    // Global variables for review prompt
    let currentOrderId = null;

    // Review prompt modal functions
    function showReviewPromptModal(orderId) {
        currentOrderId = orderId;
        document.getElementById('reviewPromptModal').classList.remove('hidden');
    }

    function closeReviewPrompt() {
        document.getElementById('reviewPromptModal').classList.add('hidden');
        currentOrderId = null;
    }

    function goToOrderDetail() {
        if (currentOrderId) {
            window.location.href = `{{ route('client.order.show', ':orderId') }}`.replace(':orderId', currentOrderId);
        }
    }

    // Notification function
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
        
        notification.innerHTML = `
            <div class="flex items-center text-white">
                <i class="fas ${icon} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }

    // console.log removed
});

// Function to cancel order - make it globally accessible
window.cancelOrder = function(orderId) {
    // console.log removed
    
    // Show cancellation modal
    showCancellationModal(orderId);
}

// Function to show cancellation modal
function showCancellationModal(orderId) {
    const modalHtml = `
        <div class="modal fade" id="cancellationModal" tabindex="-1" role="dialog" aria-labelledby="cancellationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-red-600 text-white">
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
                            
                            <div class="alert alert-info">
                                <i class="fa fa-clock-o"></i>
                                <strong>Điều kiện hủy đơn:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Đơn hàng chưa thanh toán: Có thể hủy bất cứ lúc nào</li>
                                    <li>Đơn hàng đã thanh toán online: Chỉ có thể hủy trong 15 phút đầu sau khi đặt hàng</li>
                                    <li>Đơn hàng đang được xử lý hoặc đã giao: Không thể hủy, vui lòng liên hệ hỗ trợ</li>
                                </ul>
                            </div>
                            
                            <div class="alert alert-primary" id="refundInfo" style="display: none;">
                                <i class="fa fa-coins"></i>
                                <strong>Thông tin hoàn điểm:</strong>
                                <ul class="mb-0 mt-2" id="refundDetails">
                                    <!-- Sẽ được cập nhật bằng JavaScript -->
                                </ul>
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
    
    // Hiển thị thông tin hoàn điểm nếu là đơn hàng đã thanh toán
    const orderItem = $(`.order-item[data-order-id="${orderId}"]`);
    const orderStatus = orderItem.find('.status-badge').text().trim();
    const paymentStatus = orderItem.find('.payment-status-value').text().trim();
    const orderTotal = orderItem.find('.order-total').text().trim();
    
    if (orderStatus === 'Đang chuẩn bị hàng' && paymentStatus === 'Đã thanh toán') {
        // Lấy số tiền từ text (loại bỏ "₫" và dấu phẩy)
        const amountText = orderTotal.replace(/[^\d]/g, '');
        const amount = parseInt(amountText);
        
        if (amount > 0) {
            $('#refundDetails').html(`
                <li>Số tiền <strong>${amount.toLocaleString()} VND</strong> sẽ được hoàn thành điểm vào tài khoản của bạn</li>
                <li>Tỷ lệ hoàn điểm: <strong>1 VND = 1 điểm</strong></li>
                <li>Điểm hoàn sẽ có hiệu lực ngay lập tức và có thể sử dụng cho các đơn hàng tiếp theo</li>
            `);
            $('#refundInfo').show();
        }
    }
    
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
