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
                    <li><a href="{{ route('client.order.show', $order->id) }}">Chi tiết đơn hàng {{ $order->code_order ?? '#' . $order->id }}</a></li>
                    <li class="active">Theo dõi đơn hàng</li>
                </ol>
            </div>
        </div>

        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center">
                <h1 class="module-title font-alt mb-30">
                    <i class="fa fa-truck mr-10"></i>Theo dõi đơn hàng {{ $order->code_order ?? '#' . $order->id }}
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
                        <i class="fa fa-shopping-cart mr-10"></i>Sản phẩm trong đơn hàng
                    </h4>
                    
                    <!-- Product Summary -->
                    <div class="product-summary mb-20 p-15 bg-light rounded">
                        <div class="row">
                            <div class="col-sm-6">
                                <strong><i class="fa fa-cube mr-5"></i>Tổng sản phẩm:</strong> {{ $order->orderDetails->count() }} sản phẩm
                            </div>
                            <div class="col-sm-6">
                                <strong><i class="fa fa-money mr-5"></i>Tổng tiền:</strong> {{ number_format($order->total_amount) }}đ
                            </div>
                        </div>
                    </div>

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
                                            <div class="product-image-container">
                                                @if($detail->variant && $detail->variant->image_variant)
                                                    <img src="{{ asset('storage/' . (is_array(json_decode($detail->variant->image_variant, true)) ? json_decode($detail->variant->image_variant, true)[0] : $detail->variant->image_variant) ) }}" alt="{{ $detail->product_name }}" class="product-thumbnail">
                                                @elseif($detail->product && $detail->product->image)
                                                    <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product_name }}" class="product-thumbnail">
                                                @else
                                                    <div class="no-image-placeholder">
                                                        <i class="fa fa-image"></i>
                                                    </div>
                                                @endif
                                                @if($detail->variant && $detail->variant->color)
                                                    <div class="color-indicator" style="background-color: {{ $detail->variant->color->hex_code ?? '#ccc' }};"></div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <h5 class="product-name">
                                                    <a href="{{ route('client.single-product', $detail->product_id) }}">
                                                        {{ $detail->product_name ?? ($detail->product->name ?? '') }}
                                                    </a>
                                                </h5>
                                                <div class="product-variants">
                                                    @if($detail->original_storage_capacity)
                                                        <span class="variant-badge variant-storage">
                                                            <i class="fa fa-hdd-o mr-5"></i>
                                                            {{ $detail->original_storage_capacity }}GB
                                                        </span>
                                                    @elseif($detail->original_storage_name)
                                                        <span class="variant-badge variant-storage">
                                                            <i class="fa fa-hdd-o mr-5"></i>
                                                            {{ $detail->original_storage_name }}
                                                        </span>
                                                    @elseif($detail->variant && $detail->variant->storage && isset($detail->variant->storage->capacity))
                                                        <span class="variant-badge variant-storage">
                                                            <i class="fa fa-hdd-o mr-5"></i>
                                                            {{ $detail->variant->storage->capacity }}GB
                                                        </span>
                                                    @endif
                                                    @if($detail->original_color_name)
                                                        <span class="variant-badge variant-color">
                                                            <i class="fa fa-palette mr-5"></i>
                                                            {{ $detail->original_color_name }}
                                                        </span>
                                                    @elseif($detail->variant && $detail->variant->color)
                                                        <span class="variant-badge variant-color">
                                                            <i class="fa fa-palette mr-5"></i>
                                                            {{ $detail->variant->color->name }}
                                                        </span>
                                                    @endif
                                                    @if(($detail->original_color_name || ($detail->variant && $detail->variant->color)) && ($detail->original_storage_capacity || $detail->original_storage_name || ($detail->variant && $detail->variant->storage && isset($detail->variant->storage->capacity))))
                                                        <div class="variant-combination mt-5">
                                                            <small class="text-muted">
                                                                <i class="fa fa-tag mr-5"></i>
                                                                {{ $detail->original_color_name ?? ($detail->variant && $detail->variant->color ? $detail->variant->color->name : '') }} • {{ $detail->original_storage_capacity ?? $detail->original_storage_name ?? ($detail->variant && $detail->variant->storage && isset($detail->variant->storage->capacity) ? $detail->variant->storage->capacity . 'GB' : '') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
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
                                    <span class="label label-primary">Đang giao hàng</span>
                                    @break
                                @case('delivered')
                                    <span class="label label-warning">Đã giao hàng</span>
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
                                @case('cancelled')
                                    <span class="label label-danger">Đã hủy</span>
                                    @break
                                @default
                                    <span class="label label-default">{{ $order->payment_status }}</span>
                            @endswitch
                        </p>
                        <p><strong>Tổng tiền:</strong> <span class="total">{{ number_format($order->total_amount) }}đ</span></p>
                    </div>

                    @if($order->status === 'pending' && $order->payment_status === 'pending')
                        <div class="order-actions mt-30 pt-30 border-top">
                            <button type="button" class="btn btn-danger btn-block cancel-order-btn" onclick="showCancellationModal({{ $order->id }})" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}">
                                <i class="fa fa-times mr-10"></i>Hủy đơn hàng
                            </button>
                        </div>
                    @endif
                    @if(($order->status === 'shipping' || $order->status === 'delivered') && !$order->is_received)
                        <div class="order-actions mt-30 pt-30 border-top">
                            <a href="{{ route('client.order.show', $order->id) }}?action=confirm-received" class="btn btn-success btn-block">
                                <i class="fa fa-check mr-10"></i>Đã nhận hàng
                            </a>
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

/* Product Summary */
.product-summary {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}

.product-summary .row {
    margin: 0;
}

.product-summary .col-sm-6 {
    padding: 8px 15px;
}

.product-summary strong {
    color: #495057;
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

/* Product Image Container */
.product-image-container {
    position: relative;
    display: inline-block;
}

.product-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #f0f0f0;
    transition: all 0.3s ease;
}

.product-thumbnail:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

.no-image-placeholder {
    width: 60px;
    height: 60px;
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 20px;
}

.color-indicator {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
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
    margin-top: 8px;
}

.variant-item {
    display: inline-block;
}

.variant-badge {
    display: inline-block;
    padding: 4px 8px;
    margin: 2px 4px 2px 0;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.variant-color {
    background-color: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.variant-storage {
    background-color: #f3e5f5;
    color: #7b1fa2;
    border: 1px solid #e1bee7;
}

.variant-combination {
    margin-top: 6px;
    padding-top: 6px;
    border-top: 1px solid #f0f0f0;
}

.variant-combination small {
    font-size: 11px;
    color: #888;
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