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

/* Fix layout issues */
.success-container {
    position: relative;
    z-index: 1;
    overflow-x: hidden;
    max-width: 100%;
}

.progress-container {
    background: white !important;
    border-radius: 1rem !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
    padding: 5rem !important;
    margin-bottom: 4rem !important;
    border: none !important;
    position: relative;
    z-index: 2;
    min-height: 200px !important;
    width: 100% !important;
}

/* Responsive fixes */
@media (max-width: 768px) {
    .success-container {
        padding: 1rem;
    }
    
    .progress-container {
        padding: 3rem !important;
        margin-bottom: 2rem !important;
    }
    
    .progress-steps {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
    }
}
</style>
@endsection

@section('content')
<div class="success-container" data-order-id="{{ $order->id }}" style="position: relative; z-index: 1;">
    <!-- Progress Steps at top like checkout -->
    <div class="progress-container" style="position: relative; z-index: 2;">
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
                            <span class="text-blue-600">Đang chuẩn bị hàng</span>
                            @break
                        @case('shipping')
                            <span class="text-purple-600">Đang giao hàng</span>
                            @break
                        @case('received')
                            <span class="text-indigo-600">Đã nhận hàng</span>
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
            <button type="button" class="button button-danger" onclick="showCancellationModal({{ $order->id }})">
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



@section('scripts')
<script>
// Function to show cancellation modal - đặt trong global scope
window.showCancellationModal = function(orderId) {
    console.log('🎯 Show cancellation modal for order ID:', orderId);
    
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
                            <button type="submit" class="btn btn-danger" id="confirmCancelBtn" disabled>
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
    const actionUrl = '{{ route("client.order.cancel", ":orderId") }}'.replace(':orderId', orderId);
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
        
        // Disable submit button to prevent double submission
        $('#confirmCancelBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
        
        // Submit form
        this.submit();
    });
}

$(document).ready(function() {
    // Add success animation
    $('.page-title i').addClass('animate__animated animate__bounceIn');
    
    // Add smooth scroll to top
    $('html, body').animate({
        scrollTop: 0
    }, 500);
    
    // Initialize realtime order status updates
    initRealtimeOrderUpdates();
});

function initRealtimeOrderUpdates() {
    // Check if realtime is available
    if (typeof window.simpleRealtimeHandler === 'undefined') {
        console.log('ℹ️ Realtime handler not available');
        return;
    }
    
    const orderId = document.querySelector('.success-container').dataset.orderId;
    console.log('🔄 Initializing realtime updates for order:', orderId);
    
    // Override the updateOrderStatus method for this page
    const originalUpdateOrderStatus = window.simpleRealtimeHandler.updateOrderStatus;
    window.simpleRealtimeHandler.updateOrderStatus = function(data) {
        // Call original method first
        originalUpdateOrderStatus.call(this, data);
        
        // Check if this update is for our order
        if (data.order_id == orderId) {
            console.log('📦 Order status update received for this order:', data);
            updateOrderSuccessPage(data);
        }
    };
}

function updateOrderSuccessPage(data) {
    const newStatus = data.new_status;
    const statusText = getStatusText(newStatus);
    const statusClass = getStatusClass(newStatus);
    
    console.log('🔄 Updating order success page with status:', newStatus);
    
    // Update order status display
    const statusElements = document.querySelectorAll('.customer-info-item span');
    let statusElement = null;
    
    for (const element of statusElements) {
        if (element.querySelector('.text-yellow-600, .text-blue-600, .text-purple-600, .text-green-600, .text-red-600')) {
            statusElement = element;
            break;
        }
    }
    
    if (statusElement) {
        statusElement.innerHTML = `<span class="${statusClass}">${statusText}</span>`;
        
        // Add highlight effect
        statusElement.style.backgroundColor = '#d4edda';
        statusElement.style.borderRadius = '4px';
        statusElement.style.padding = '4px 8px';
        
        setTimeout(() => {
            statusElement.style.backgroundColor = '';
            statusElement.style.borderRadius = '';
            statusElement.style.padding = '';
        }, 3000);
    }
    
    // Show notification
    showOrderStatusNotification(data);
    
    // Update cancel button visibility
    updateCancelButtonVisibility(newStatus);
}

function getStatusText(status) {
    const statusTexts = {
        'pending': 'Chờ xử lý',
        'processing': 'Đang chuẩn bị hàng',
        'shipping': 'Đang giao hàng',
        'received': 'Đã nhận hàng',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy'
    };
    return statusTexts[status] || status;
}

function getStatusClass(status) {
    const statusClasses = {
        'pending': 'text-yellow-600',
        'processing': 'text-blue-600',
        'shipping': 'text-purple-600',
        'received': 'text-indigo-600',
        'completed': 'text-green-600',
        'cancelled': 'text-red-600'
    };
    return statusClasses[status] || 'text-gray-600';
}

function showOrderStatusNotification(data) {
    const message = `🔄 Trạng thái đơn hàng đã được cập nhật: ${getStatusText(data.new_status)}`;
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'alert alert-info alert-dismissible';
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; margin: 0;';
    notification.innerHTML = `
        <button type="button" class="close" onclick="this.parentElement.remove()">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fa fa-info-circle"></i>
        <strong>Cập nhật trạng thái:</strong><br>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

function updateCancelButtonVisibility(status) {
    const cancelButton = document.querySelector('.button-danger');
    if (cancelButton) {
        if (status === 'pending') {
            cancelButton.style.display = 'inline-block';
        } else {
            cancelButton.style.display = 'none';
        }
    }
}
</script>
@endsection 