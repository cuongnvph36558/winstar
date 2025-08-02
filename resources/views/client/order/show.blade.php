@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng ' . ($order->code_order ?? ('#' . $order->id)))

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <div class="hero-content">
                    <div class="hero-icon mb-30">
                        <i class="fa fa-file-text-o fa-3x"></i>
                    </div>
                    <h1 class="hero-title font-alt mb-20">
                        Chi tiết đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}
                    </h1>
                    <p class="hero-subtitle lead">
                        Theo dõi chi tiết đơn hàng và trạng thái giao hàng
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('client.order.list') }}" class="text-decoration-none">
                                <i class="fa fa-shopping-bag mr-5"></i>Đơn hàng của tôi
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fa fa-file-text-o mr-5"></i>Chi tiết đơn hàng
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="order-detail-section py-60">
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
            <!-- Order Details -->
            <div class="col-lg-8">
                <!-- Order Status Card -->
                <div class="order-status-card mb-30">
                    <div class="status-header">
                        <div class="status-info">
                            <h4 class="status-title">Trạng thái đơn hàng</h4>
                            <p class="status-subtitle">Cập nhật lần cuối: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="status-badge-large">
                            @switch($order->status)
                                @case('pending')
                                    <div class="status-badge status-pending">
                                        <i class="fa fa-clock-o mr-10"></i>Chờ xử lý
                                    </div>
                                    @break
                                @case('processing')
                                    <div class="status-badge status-processing">
                                        <i class="fa fa-cogs mr-10"></i>Đang chuẩn bị
                                    </div>
                                    @break
                                @case('shipping')
                                    <div class="status-badge status-shipping">
                                        <i class="fa fa-truck mr-10"></i>Đang giao
                                    </div>
                                    @break
                                @case('completed')
                                    <div class="status-badge status-completed">
                                        <i class="fa fa-check-circle mr-10"></i>Hoàn thành
                                    </div>
                                    @break
                                @case('cancelled')
                                    <div class="status-badge status-cancelled">
                                        <i class="fa fa-times-circle mr-10"></i>Đã hủy
                                    </div>
                                    @break
                                @default
                                    <div class="status-badge status-default">
                                        <i class="fa fa-question-circle mr-10"></i>{{ $order->status }}
                                    </div>
                            @endswitch
                        </div>
                    </div>
                    
                    @if($order->status === 'shipping')
                        <div class="status-actions mt-20">
                            <div class="alert alert-info mb-20">
                                <i class="fa fa-info-circle mr-10"></i>
                                <strong>Thông báo:</strong> Đơn hàng của bạn đang được giao. Khi nhận được hàng, vui lòng xác nhận để hoàn tất đơn hàng.
                            </div>
                            
                            <form id="confirmReceivedForm" action="{{ route('client.order.confirm-received', $order->id) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            
                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-lg" onclick="confirmReceived()">
                                    <i class="fa fa-check-circle mr-10"></i>
                                    Đã nhận hàng
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->status === 'completed')
                        <div class="status-actions mt-20">
                            <div class="alert alert-success mb-20">
                                <i class="fa fa-check-circle mr-10"></i>
                                <strong>Cảm ơn bạn!</strong> Đơn hàng đã hoàn thành. Hãy dành chút thời gian đánh giá sản phẩm để giúp chúng tôi cải thiện dịch vụ.
                            </div>
                            
                            <div class="review-products">
                                @foreach($order->orderDetails as $orderDetail)
                                    @php
                                        $product = $orderDetail->product;
                                        $variant = $orderDetail->variant;
                                        $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                            ->where('product_id', $product->id)
                                            ->first();
                                    @endphp
                                    
                                    <div class="review-product-item mb-20">
                                        <div class="product-info">
                                            <div class="product-image">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-thumb">
                                                @else
                                                    <div class="no-image">No Image</div>
                                                @endif
                                            </div>
                                            <div class="product-details">
                                                <h6 class="product-name">{{ $product->name }}</h6>
                                                @if($variant)
                                                    <p class="product-variant">
                                                        <span class="variant-color">{{ $variant->color->name ?? '' }}</span>
                                                        @if($variant->storage)
                                                            <span class="variant-storage">{{ $variant->storage->name ?? '' }}</span>
                                                        @endif
                                                    </p>
                                                @endif
                                                <p class="product-quantity">Số lượng: {{ $orderDetail->quantity }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($existingReview)
                                            <div class="existing-review">
                                                <div class="rating-display">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star {{ $i <= $existingReview->rating ? 'star-filled' : 'star-empty' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="review-content">{{ $existingReview->content }}</p>
                                                <small class="review-date">Đánh giá vào: {{ $existingReview->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        @else
                                            <div class="review-form">
                                                <form action="{{ route('client.review.store') }}" method="POST" class="review-form-submit">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <input type="hidden" name="debug" value="1">
                                                    
                                                    <div class="rating-section">
                                                        <label class="rating-label">Đánh giá của bạn:</label>
                                                        <div class="star-rating">
                                                            @for($i = 5; $i >= 1; $i--)
                                                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}_{{ $product->id }}" class="star-input">
                                                                <label for="star{{ $i }}_{{ $product->id }}" class="star-label">
                                                                    <i class="fa fa-star"></i>
                                                                </label>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="review-content-section">
                                                        <label for="content_{{ $product->id }}" class="content-label">Nhận xét:</label>
                                                        <textarea name="content" id="content_{{ $product->id }}" class="review-textarea" 
                                                                  placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." rows="3"></textarea>
                                                    </div>
                                                    
                                                    <div class="review-actions">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fa fa-paper-plane mr-5"></i>Gửi đánh giá
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Products Section -->
                <div class="order-card mb-30">
                    <div class="order-card-header">
                        <h4 class="section-title">
                            <i class="fa fa-shopping-cart mr-10"></i>Sản phẩm đã đặt
                        </h4>
                        <span class="product-count">{{ $order->orderDetails->count() }} sản phẩm</span>
                    </div>
                    <div class="order-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover products-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="12%">Ảnh</th>
                                        <th width="20%">Sản phẩm</th>
                                        <th width="18%">Biến thể</th>
                                        <th width="15%">Màu sắc</th>
                                        <th width="15%">Dung lượng</th>
                                        <th width="8%">Số lượng</th>
                                        <th width="7%">Đơn giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderDetails as $index => $detail)
                                        <tr>
                                            <td class="text-center">
                                                <span class="product-index">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="product-image-cell">
                                                    @if($detail->variant && $detail->variant->image_variant)
                                                        <img src="{{ asset('storage/' . (is_array(json_decode($detail->variant->image_variant, true)) ? json_decode($detail->variant->image_variant, true)[0] : $detail->variant->image_variant) ) }}" 
                                                             alt="{{ $detail->product_name }}" 
                                                             class="product-table-img"
                                                             onerror="this.parentElement.innerHTML='<div class=\'product-placeholder-small\'><i class=\'fa fa-image\'></i></div>'">
                                                    @elseif($detail->product && $detail->product->image)
                                                        <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                             alt="{{ $detail->product_name }}" 
                                                             class="product-table-img"
                                                             onerror="this.parentElement.innerHTML='<div class=\'product-placeholder-small\'><i class=\'fa fa-image\'></i></div>'">
                                                    @else
                                                        <div class="product-placeholder-small">
                                                            <i class="fa fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-info-cell">
                                                    <div class="product-name-table">
                                                        <a href="{{ route('client.single-product', $detail->product_id) }}" class="product-link">
                                                            {{ $detail->product_name ?? ($detail->product->name ?? 'Sản phẩm không tồn tại') }}
                                                        </a>
                                                    </div>
                                                    @if($detail->product && $detail->product->category)
                                                        <div class="product-category-table">
                                                            {{ $detail->product->category->name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="variant-badge-table">
                                                    {{ $detail->product_name ?? ($detail->product->name ?? 'Sản phẩm') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($detail->variant && $detail->variant->color)
                                                    <span class="color-badge-table">
                                                        {{ $detail->variant->color->name }}
                                                    </span>
                                                @else
                                                    <span class="color-badge-table">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($detail->variant && $detail->variant->storage)
                                                    <span class="capacity-badge-table">
                                                        {{ $detail->variant->storage->capacity }}
                                                    </span>
                                                @elseif($detail->product_name)
                                                    @php
                                                        // Extract dung lượng từ tên sản phẩm (ví dụ: "iPhone 16 128GB" -> "128GB")
                                                        preg_match('/(\d+GB|\d+TB)/i', $detail->product_name, $matches);
                                                        $capacity = $matches[1] ?? '-';
                                                    @endphp
                                                    <span class="capacity-badge-table">
                                                        {{ $capacity }}
                                                    </span>
                                                @else
                                                    <span class="capacity-badge-table">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="quantity-badge-table">{{ $detail->quantity }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span class="price-value-table">{{ number_format($detail->price) }}₫</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="order-card mb-30">
                    <div class="order-card-header">
                        <h4 class="section-title">
                            <i class="fa fa-info-circle mr-10"></i>Thông tin đơn hàng
                        </h4>
                    </div>
                    <div class="order-card-body">
                        <div class="order-info-grid">
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa fa-hashtag mr-10"></i>Mã đơn hàng:
                                </div>
                                <div class="info-value">{{ $order->code_order ?? ('#' . $order->id) }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa fa-calendar mr-10"></i>Ngày đặt hàng:
                                </div>
                                <div class="info-value">{{ $order->created_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa fa-clock-o mr-10"></i>Cập nhật lần cuối:
                                </div>
                                <div class="info-value">{{ $order->updated_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa fa-shopping-cart mr-10"></i>Tổng sản phẩm:
                                </div>
                                <div class="info-value">{{ $order->orderDetails->count() }} sản phẩm</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa fa-user mr-10"></i>Khách hàng:
                                </div>
                                <div class="info-value">{{ $order->user->name ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fa fa-envelope mr-10"></i>Email:
                                </div>
                                <div class="info-value">{{ $order->user->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="order-card mb-30">
                    <div class="order-card-header">
                        <h4 class="section-title">
                            <i class="fa fa-truck mr-10"></i>Thông tin giao hàng
                        </h4>
                    </div>
                    <div class="order-card-body">
                        <div class="shipping-details">
                            <div class="shipping-row">
                                <div class="shipping-label">
                                    <i class="fa fa-user mr-10"></i>Người nhận:
                                </div>
                                <div class="shipping-value">{{ $order->receiver_name }}</div>
                            </div>
                            <div class="shipping-row">
                                <div class="shipping-label">
                                    <i class="fa fa-phone mr-10"></i>Số điện thoại:
                                </div>
                                <div class="shipping-value">{{ $order->phone }}</div>
                            </div>
                            <div class="shipping-row">
                                <div class="shipping-label">
                                    <i class="fa fa-map-marker mr-10"></i>Địa chỉ:
                                </div>
                                <div class="shipping-value">
                                    {{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}
                                </div>
                            </div>
                            @if($order->description)
                                <div class="shipping-row">
                                    <div class="shipping-label">
                                        <i class="fa fa-sticky-note mr-10"></i>Ghi chú:
                                    </div>
                                    <div class="shipping-value">{{ $order->description }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="order-card mb-30">
                    <div class="order-card-header">
                        <h4 class="section-title">
                            <i class="fa fa-credit-card mr-10"></i>Chi tiết thanh toán
                        </h4>
                    </div>
                    <div class="order-card-body">
                        <div class="payment-details-grid">
                            <div class="payment-row">
                                <div class="payment-label">
                                    <i class="fa fa-credit-card mr-10"></i>Phương thức thanh toán:
                                </div>
                                <div class="payment-value">
                                    @switch($order->payment_method)
                                        @case('cod')
                                            <span class="payment-method-badge cod">
                                                <i class="fa fa-money mr-5"></i>Thanh toán khi nhận hàng (COD)
                                            </span>
                                            @break
                                        @case('momo')
                                            <span class="payment-method-badge momo">
                                                <i class="fa fa-mobile mr-5"></i>Ví MoMo
                                            </span>
                                            @break
                                        @case('vnpay')
                                            <span class="payment-method-badge vnpay">
                                                <i class="fa fa-credit-card mr-5"></i>VNPay
                                            </span>
                                            @break
                                        @default
                                            <span class="payment-method-badge default">
                                                <i class="fa fa-question mr-5"></i>{{ $order->payment_method }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                            <div class="payment-row">
                                <div class="payment-label">
                                    <i class="fa fa-info-circle mr-10"></i>Trạng thái thanh toán:
                                </div>
                                <div class="payment-value">
                                    @switch($order->payment_status)
                                        @case('pending')
                                            <span class="payment-status-badge pending">
                                                <i class="fa fa-clock-o mr-5"></i>Chưa thanh toán
                                            </span>
                                            @break
                                        @case('paid')
                                            <span class="payment-status-badge paid">
                                                <i class="fa fa-check mr-5"></i>Đã thanh toán
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="payment-status-badge cancelled">
                                                <i class="fa fa-times mr-5"></i>Đã hủy
                                            </span>
                                            @break
                                        @default
                                            <span class="payment-status-badge default">
                                                <i class="fa fa-question mr-5"></i>{{ $order->payment_status }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                            @if($order->coupon)
                                <div class="payment-row">
                                    <div class="payment-label">
                                        <i class="fa fa-tag mr-10"></i>Mã giảm giá:
                                    </div>
                                    <div class="payment-value">
                                        <span class="coupon-badge">
                                            <i class="fa fa-tag mr-5"></i>{{ $order->coupon->code }}
                                            <span class="coupon-discount">(-{{ number_format($order->discount_amount) }}đ)</span>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @if($order->payment_method !== 'cod' && $order->payment_status === 'paid')
                                <div class="payment-row">
                                    <div class="payment-label">
                                        <i class="fa fa-calendar-check-o mr-10"></i>Ngày thanh toán:
                                    </div>
                                    <div class="payment-value">{{ $order->updated_at->format('d/m/Y H:i:s') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="order-card mb-30">
                    <div class="order-card-header">
                        <h4 class="section-title">
                            <i class="fa fa-truck mr-10"></i>Theo dõi đơn hàng
                        </h4>
                    </div>
                    <div class="order-card-body">
                        <div class="order-timeline">
                            @php
                                $statusHistory = [
                                    'pending' => [
                                        'status' => 'pending',
                                        'title' => 'Đơn hàng đã đặt',
                                        'description' => 'Đơn hàng của bạn đã được đặt thành công',
                                        'time' => $order->created_at,
                                        'active' => $order->status === 'pending',
                                        'done' => true
                                    ],
                                    'processing' => [
                                        'status' => 'processing',
                                        'title' => 'Đang xử lý',
                                        'description' => 'Đơn hàng đang được xử lý và chuẩn bị',
                                        'time' => $order->status !== 'pending' ? $order->updated_at : null,
                                        'active' => $order->status === 'processing',
                                        'done' => in_array($order->status, ['processing', 'shipping', 'completed'])
                                    ],
                                    'shipping' => [
                                        'status' => 'shipping',
                                        'title' => 'Đang giao hàng',
                                        'description' => 'Đơn hàng đang được vận chuyển đến bạn',
                                        'time' => in_array($order->status, ['shipping', 'completed']) ? $order->updated_at : null,
                                        'active' => $order->status === 'shipping',
                                        'done' => $order->status === 'completed'
                                    ],
                                    'completed' => [
                                        'status' => 'completed',
                                        'title' => 'Hoàn thành',
                                        'description' => 'Đơn hàng đã được giao thành công',
                                        'time' => $order->status === 'completed' ? $order->updated_at : null,
                                        'active' => $order->status === 'completed',
                                        'done' => $order->status === 'completed'
                                    ]
                                ];
                                
                                if ($order->status === 'cancelled') {
                                    $statusHistory = [
                                        'pending' => [
                                            'status' => 'pending',
                                            'title' => 'Đơn hàng đã đặt',
                                            'description' => 'Đơn hàng của bạn đã được đặt thành công',
                                            'time' => $order->created_at,
                                            'active' => false,
                                            'done' => true
                                        ],
                                        'cancelled' => [
                                            'status' => 'cancelled',
                                            'title' => 'Đã hủy',
                                            'description' => 'Đơn hàng đã được hủy',
                                            'time' => $order->updated_at,
                                            'active' => true,
                                            'done' => true
                                        ]
                                    ];
                                }
                            @endphp

                            @foreach($statusHistory as $status)
                                <div class="timeline-item {{ $status['active'] ? 'active' : '' }} {{ $status['done'] ? 'done' : '' }}">
                                    <div class="timeline-icon">
                                        @switch($status['status'])
                                            @case('pending')
                                                <i class="fa fa-shopping-cart"></i>
                                                @break
                                            @case('processing')
                                                <i class="fa fa-cogs"></i>
                                                @break
                                            @case('shipping')
                                                <i class="fa fa-truck"></i>
                                                @break
                                            @case('completed')
                                                <i class="fa fa-check-circle"></i>
                                                @break
                                            @case('cancelled')
                                                <i class="fa fa-times-circle"></i>
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">{{ $status['title'] }}</h6>
                                        @if($status['time'])
                                            <p class="timeline-time">{{ $status['time']->format('d/m/Y H:i:s') }}</p>
                                        @endif
                                        <p class="timeline-description">{{ $status['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tracking Information -->
                <div class="order-card mb-30">
                    <div class="order-card-header">
                        <h4 class="section-title">
                            <i class="fa fa-info-circle mr-10"></i>Thông tin theo dõi
                        </h4>
                    </div>
                    <div class="order-card-body">
                        <div class="tracking-info-grid">
                            <div class="tracking-item">
                                <div class="tracking-icon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <div class="tracking-content">
                                    <h6 class="tracking-title">Ngày đặt hàng</h6>
                                    <p class="tracking-value">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                            
                            <div class="tracking-item">
                                <div class="tracking-icon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="tracking-content">
                                    <h6 class="tracking-title">Cập nhật lần cuối</h6>
                                    <p class="tracking-value">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                            
                            <div class="tracking-item">
                                <div class="tracking-icon">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="tracking-content">
                                    <h6 class="tracking-title">Địa chỉ giao hàng</h6>
                                    <p class="tracking-value">{{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</p>
                                </div>
                            </div>
                            
                            <div class="tracking-item">
                                <div class="tracking-icon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="tracking-content">
                                    <h6 class="tracking-title">Số điện thoại</h6>
                                    <p class="tracking-value">{{ $order->phone }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($order->description)
                        <div class="tracking-note mt-20">
                            <div class="note-header">
                                <i class="fa fa-sticky-note mr-10"></i>
                                <strong>Ghi chú đơn hàng:</strong>
                            </div>
                            <p class="note-content">{{ $order->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary-card sticky-summary">
                    <div class="summary-header">
                        <h4 class="summary-title">
                            <i class="fa fa-calculator mr-10"></i>Tổng quan đơn hàng
                        </h4>
                    </div>
                    <div class="summary-body">
                        <div class="summary-details">
                            <!-- Order Summary -->
                            <div class="summary-section">
                                <h6 class="section-subtitle">
                                    <i class="fa fa-calculator mr-10"></i>Chi tiết đơn hàng
                                </h6>
                                <div class="summary-row">
                                    <span class="summary-label">Tạm tính:</span>
                                    <span class="summary-value">{{ number_format($order->total_amount - 30000) }}đ</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Phí vận chuyển:</span>
                                    <span class="summary-value">30,000đ</span>
                                </div>
                                @if($order->coupon)
                                    <div class="summary-row discount">
                                        <span class="summary-label">Giảm giá ({{ $order->coupon->code }}):</span>
                                        <span class="summary-value">-{{ number_format($order->discount_amount) }}đ</span>
                                    </div>
                                @endif
                                <div class="summary-divider"></div>
                                <div class="summary-row total-row">
                                    <span class="summary-label">Tổng cộng:</span>
                                    <span class="summary-value total-amount">{{ number_format($order->total_amount) }}đ</span>
                                </div>
                            </div>

                            <!-- Products Summary -->
                            <div class="summary-section">
                                <h6 class="section-subtitle">
                                    <i class="fa fa-shopping-cart mr-10"></i>Tóm tắt sản phẩm
                                </h6>
                                <div class="products-summary">
                                    @foreach($order->orderDetails as $detail)
                                        <div class="product-summary-item">
                                            <div class="product-summary-info">
                                                <div class="product-summary-name">
                                                    {{ Str::limit($detail->product_name ?? ($detail->product->name ?? 'Sản phẩm không tồn tại'), 30) }}
                                                </div>
                                                <div class="product-summary-details">
                                                    @if($detail->variant && $detail->variant->color)
                                                        <span class="variant-tag">{{ $detail->variant->color->name }}</span>
                                                    @endif
                                                    @if($detail->variant && $detail->variant->storage)
                                                        <span class="variant-tag">{{ $detail->variant->storage->capacity }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="product-summary-pricing">
                                                <div class="product-summary-quantity">
                                                    <span class="quantity-badge">{{ $detail->quantity }}x</span>
                                                </div>
                                                <div class="product-summary-price">
                                                    {{ number_format($detail->total) }}đ
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Savings Summary -->
                            @if($order->coupon)
                                <div class="summary-section">
                                    <h6 class="section-subtitle">
                                        <i class="fa fa-gift mr-10"></i>Tiết kiệm
                                    </h6>
                                    <div class="savings-info">
                                        <div class="savings-row">
                                            <span class="savings-label">Mã giảm giá:</span>
                                            <span class="savings-value">{{ $order->coupon->code }}</span>
                                        </div>
                                        <div class="savings-row">
                                            <span class="savings-label">Giảm giá:</span>
                                            <span class="savings-value savings-amount">-{{ number_format($order->discount_amount) }}đ</span>
                                        </div>
                                        <div class="savings-row">
                                            <span class="savings-label">Tiết kiệm được:</span>
                                            <span class="savings-value savings-percentage">
                                                {{ round(($order->discount_amount / ($order->total_amount + $order->discount_amount)) * 100, 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Payment Information -->
                        <div class="payment-section">
                            <h5 class="section-subtitle">
                                <i class="fa fa-credit-card mr-10"></i>Thông tin thanh toán
                            </h5>
                            <div class="payment-details">
                                <div class="payment-row">
                                    <span class="payment-label">Phương thức:</span>
                                    <span class="payment-value">
                                        @switch($order->payment_method)
                                            @case('cod')
                                                <span class="payment-method cod">
                                                    <i class="fa fa-money mr-5"></i>Thanh toán khi nhận hàng
                                                </span>
                                                @break
                                            @case('momo')
                                                <span class="payment-method momo">
                                                    <i class="fa fa-mobile mr-5"></i>Ví MoMo
                                                </span>
                                                @break
                                            @case('vnpay')
                                                <span class="payment-method vnpay">
                                                    <i class="fa fa-credit-card mr-5"></i>VNPay
                                                </span>
                                                @break
                                            @default
                                                {{ $order->payment_method }}
                                        @endswitch
                                    </span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Trạng thái:</span>
                                    <span class="payment-value">
                                        @switch($order->payment_status)
                                            @case('pending')
                                                <span class="payment-status pending">
                                                    <i class="fa fa-clock-o mr-5"></i>Chưa thanh toán
                                                </span>
                                                @break
                                            @case('paid')
                                                <span class="payment-status paid">
                                                    <i class="fa fa-check mr-5"></i>Đã thanh toán
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="payment-status cancelled">
                                                    <i class="fa fa-times mr-5"></i>Đã hủy
                                                </span>
                                                @break
                                            @default
                                                <span class="payment-status default">
                                                    <i class="fa fa-question mr-5"></i>{{ $order->payment_status }}
                                                </span>
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="actions-section">
                            @if($order->status === 'pending' && $order->payment_status === 'pending')
                                <button type="button" class="btn btn-danger btn-block btn-action" onclick="cancelOrder()">
                                    <i class="fa fa-times mr-10"></i>Hủy đơn hàng
                                </button>
                            @endif
                            @if($order->status === 'pending' && $order->payment_status === 'pending' && $order->payment_method !== 'cod')
                                <div class="payment-options mt-20">
                                    <h6 class="options-title">
                                        <i class="fa fa-credit-card mr-10"></i>Chọn lại phương thức thanh toán
                                    </h6>
                                    <form method="POST" action="{{ route('client.place-order') }}" class="payment-form">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <div class="payment-methods">
                                            <label class="payment-method-option">
                                                <input type="radio" name="payment_method" value="momo" checked>
                                                <span class="method-label">
                                                    <i class="fa fa-mobile mr-10"></i>MoMo
                                                </span>
                                            </label>
                                            <label class="payment-method-option">
                                                <input type="radio" name="payment_method" value="vnpay">
                                                <span class="method-label">
                                                    <i class="fa fa-credit-card mr-10"></i>VNPay
                                                </span>
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block mt-15">
                                            <i class="fa fa-refresh mr-10"></i>Thanh toán lại
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
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

/* Order Status Card */
.order-status-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    padding: 30px;
    margin-bottom: 30px;
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.status-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.status-subtitle {
    color: #6c757d;
    margin: 5px 0 0 0;
    font-size: 0.9rem;
}

.status-badge-large {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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

.status-cancelled {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.status-default {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    color: white;
}

.status-actions {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
    margin-top: 20px;
}

.status-actions .review-products {
    max-height: 400px;
    overflow-y: auto;
}

.status-actions .review-product-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: #f8f9fa;
}

.status-actions .product-info {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.status-actions .product-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.status-actions .no-image {
    width: 60px;
    height: 60px;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #6c757d;
}

/* Star Rating in Status Actions */
.status-actions .star-rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 5px;
    margin-bottom: 15px;
}

.status-actions .star-input {
    display: none;
}

.status-actions .star-label {
    cursor: pointer;
    font-size: 20px;
    color: #ddd;
    transition: color 0.2s ease;
}

.status-actions .star-label:hover,
.status-actions .star-label:hover ~ .star-label,
.status-actions .star-input:checked ~ .star-label {
    color: #ffc107;
}

.status-actions .star-label i {
    transition: transform 0.2s ease;
}

.status-actions .star-label:hover i,
.status-actions .star-label:hover ~ .star-label i,
.status-actions .star-input:checked ~ .star-label i {
    transform: scale(1.2);
}

/* Order Card Styling */
.order-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    overflow: hidden;
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.order-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 25px 30px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.product-count {
    background: #667eea;
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.order-card-body {
    padding: 30px;
}

/* Products Table */
.products-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.products-table thead th {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 15px 10px;
    border: none;
    text-align: center;
}

.products-table thead th:first-child {
    text-align: center;
}

.products-table thead th:last-child {
    text-align: right;
}

.products-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.products-table tbody tr:hover {
    background: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.products-table tbody tr:last-child {
    border-bottom: none;
}

.products-table td {
    padding: 15px 10px;
    vertical-align: middle;
    border: none;
}

.product-index {
    background: #6c757d;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.product-image-cell {
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-table-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-placeholder-small {
    width: 60px;
    height: 60px;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.2rem;
    border-radius: 8px;
}

.product-info-cell {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.product-name-table {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    line-height: 1.3;
}

.product-name-table a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-name-table a:hover {
    color: #667eea;
}

.product-category-table {
    color: #6c757d;
    font-size: 0.8rem;
    font-style: italic;
}

.variant-badge-table {
    background: #f8f9fa;
    color: #495057;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    min-width: 80px;
    border: 1px solid #e9ecef;
}

.color-badge-table {
    background: #f8f9fa;
    color: #495057;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    min-width: 60px;
    border: 1px solid #e9ecef;
}

.capacity-badge-table {
    background: #f8f9fa;
    color: #495057;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    min-width: 60px;
    border: 1px solid #e9ecef;
}

.quantity-badge-table {
    background: #6c757d;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.price-value-table {
    font-weight: 700;
    color: #495057;
    font-size: 0.95rem;
}



.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px 0;
    border-bottom: 1px solid #f1f3f4;
}

.price-row:last-child {
    margin-bottom: 0;
    border-bottom: none;
    border-top: 2px solid #e9ecef;
    padding-top: 15px;
    margin-top: 15px;
}

.price-label {
    font-size: 0.95rem;
    color: #6c757d;
    font-weight: 600;
}

.price-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2c3e50;
}

.price-row.original-price .price-value {
    text-decoration: line-through;
    color: #6c757d;
}

.price-row.quantity-row .price-value {
    color: #dc3545;
    font-weight: 700;
}

.total-price-section {
    text-align: center;
    padding: 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.total-price {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.price-note {
    font-size: 0.85rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Order Information Grid */
.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-row:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.info-label {
    font-weight: 600;
    color: #2c3e50;
    min-width: 140px;
    display: flex;
    align-items: center;
}

.info-value {
    color: #495057;
    font-weight: 500;
}

/* Shipping Details */
.shipping-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.shipping-row {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.shipping-label {
    font-weight: 600;
    color: #2c3e50;
    min-width: 120px;
    display: flex;
    align-items: center;
}

.shipping-value {
    color: #495057;
    flex: 1;
}

/* Payment Details Grid */
.payment-details-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.payment-method-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.payment-method-badge.cod {
    background: #fff3cd;
    color: #856404;
}

.payment-method-badge.momo {
    background: #e8f5e8;
    color: #155724;
}

.payment-method-badge.vnpay {
    background: #e3f2fd;
    color: #1976d2;
}

.payment-method-badge.default {
    background: #e2e3e5;
    color: #383d41;
}

.payment-status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.payment-status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.payment-status-badge.paid {
    background: #d4edda;
    color: #155724;
}

.payment-status-badge.cancelled {
    background: #f8d7da;
    color: #721c24;
}

.payment-status-badge.default {
    background: #e2e3e5;
    color: #383d41;
}

.coupon-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: #e8f5e8;
    color: #155724;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.coupon-discount {
    margin-left: 8px;
    color: #dc3545;
    font-weight: 700;
}

/* Order Timeline */
.order-timeline {
    position: relative;
    padding: 20px 0;
}

.order-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    margin-bottom: 30px;
    opacity: 0.5;
    transition: all 0.3s ease;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item.active {
    opacity: 1;
}

.timeline-item.done {
    opacity: 0.8;
}

.timeline-item.done .timeline-icon {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 14px;
    z-index: 2;
    transition: all 0.3s ease;
}

.timeline-item.active .timeline-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transform: scale(1.1);
    animation: timelinePulse 2s infinite;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-left: 20px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
}

.timeline-item.active .timeline-content {
    background: white;
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    transform: translateX(5px);
}

.timeline-item.done .timeline-content {
    background: #f8fff9;
    border-color: #28a745;
}

.timeline-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 8px 0;
}

.timeline-time {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0 0 8px 0;
    font-weight: 500;
    font-style: italic;
}

.timeline-description {
    font-size: 0.9rem;
    color: #495057;
    margin: 0;
    line-height: 1.4;
}

/* Timeline Animation */
@keyframes timelinePulse {
    0% {
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
    }
}

/* Order Summary Card */
.order-summary-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    overflow: hidden;
    position: sticky;
    top: 20px;
}

.summary-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
}

.summary-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
}

.summary-body {
    padding: 30px;
}

.summary-details {
    margin-bottom: 30px;
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.summary-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px 0;
}

.summary-row:last-child {
    margin-bottom: 0;
}

.summary-label {
    color: #6c757d;
    font-weight: 600;
    font-size: 0.9rem;
}

.summary-value {
    font-weight: 700;
    color: #2c3e50;
    font-size: 0.9rem;
}

.summary-row.discount {
    color: #28a745;
}

.summary-row.discount .summary-value {
    color: #28a745;
}

.summary-divider {
    height: 1px;
    background: #e9ecef;
    margin: 15px 0;
}

.total-row {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    border-top: 2px solid #e9ecef;
    padding-top: 12px;
    margin-top: 12px;
}

.total-amount {
    color: #dc3545;
    font-size: 1.2rem;
}

/* Products Summary */
.products-summary {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.product-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.product-summary-info {
    flex: 1;
    min-width: 0;
}

.product-summary-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
    margin-bottom: 4px;
    line-height: 1.3;
}

.product-summary-details {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.variant-tag {
    background: #e3f2fd;
    color: #1976d2;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

.product-summary-pricing {
    text-align: right;
    flex-shrink: 0;
}

.product-summary-quantity {
    margin-bottom: 4px;
}

.quantity-badge {
    background: #667eea;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-summary-price {
    font-weight: 700;
    color: #dc3545;
    font-size: 0.9rem;
}

/* Savings Summary */
.savings-info {
    background: white;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
}

.savings-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    padding: 5px 0;
}

.savings-row:last-child {
    margin-bottom: 0;
}

.savings-label {
    color: #6c757d;
    font-weight: 600;
    font-size: 0.85rem;
}

.savings-value {
    font-weight: 700;
    color: #2c3e50;
    font-size: 0.85rem;
}

.savings-amount {
    color: #28a745;
}

.savings-percentage {
    color: #667eea;
    background: #e3f2fd;
    padding: 2px 6px;
    border-radius: 8px;
}

/* Payment Section */
.payment-section {
    margin-bottom: 30px;
}

.section-subtitle {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
}

.payment-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.payment-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-label {
    color: #6c757d;
    font-weight: 500;
}

.payment-value {
    font-weight: 600;
}

.payment-method {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.payment-method.cod {
    background: #fff3cd;
    color: #856404;
}

.payment-method.momo {
    background: #e8f5e8;
    color: #155724;
}

.payment-method.vnpay {
    background: #e3f2fd;
    color: #1976d2;
}

.payment-status {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.payment-status.pending {
    background: #fff3cd;
    color: #856404;
}

.payment-status.paid {
    background: #d4edda;
    color: #155724;
}

.payment-status.cancelled {
    background: #f8d7da;
    color: #721c24;
}

.payment-status.default {
    background: #e2e3e5;
    color: #383d41;
}

/* Actions Section */
.actions-section {
    margin-top: 30px;
}

.btn-action {
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
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

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

/* Payment Options */
.payment-options {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.options-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
}

.payment-method-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 10px;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.payment-method-option:hover {
    background: #e9ecef;
}

.payment-method-option input[type="radio"] {
    margin-right: 10px;
}

.method-label {
    display: flex;
    align-items: center;
    font-weight: 500;
    color: #2c3e50;
}

/* Animations */
.animate-slide-down {
    animation: slideDown 0.5s ease-out;
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

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .status-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .order-summary-card {
        position: static;
        margin-top: 30px;
    }
    
    .products-table {
        font-size: 0.85rem;
    }
    
    .products-table thead th {
        padding: 10px 5px;
        font-size: 0.75rem;
    }
    
    .products-table td {
        padding: 10px 5px;
    }
    
    .product-table-img {
        width: 40px;
        height: 40px;
    }
    
    .product-placeholder-small {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .product-name-table {
        font-size: 0.85rem;
    }
    
    .product-category-table {
        font-size: 0.75rem;
    }
    
    .variant-badge-table,
    .color-badge-table,
    .capacity-badge-table {
        font-size: 0.75rem;
        padding: 4px 8px;
        min-width: 60px;
    }
    
    .quantity-badge-table {
        width: 25px;
        height: 25px;
        font-size: 0.8rem;
    }
    
    .price-value-table {
        font-size: 0.85rem;
    }
    
    .summary-section {
        padding: 15px;
    }
    
    .product-summary-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        text-align: left;
    }
    
    .product-summary-pricing {
        text-align: left;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .product-variants {
        padding: 15px;
        gap: 10px;
    }
    
    .product-description {
        padding: 20px;
    }
    
    .shipping-row {
        flex-direction: column;
        gap: 5px;
    }
    
    .shipping-label {
        min-width: auto;
    }
    
    .payment-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    /* Order Info Grid Responsive */
    .order-info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .info-label {
        min-width: auto;
    }
    
    /* Timeline Responsive */
    .timeline {
        padding-left: 20px;
    }
    
    .timeline::before {
        left: 10px;
    }
    
    .timeline-icon {
        left: -12px;
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
    
    .timeline-content {
        margin-left: 15px;
        padding: 15px;
    }
}

@media (max-width: 576px) {
    .hero-section {
        padding: 60px 0;
    }
    
    .order-card-header,
    .order-card-body,
    .summary-header,
    .summary-body {
        padding: 20px;
    }
    
    .status-badge {
        font-size: 0.9rem;
        padding: 10px 20px;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
    }
}

/* Utility Classes */
.mb-30 { margin-bottom: 30px; }
.mt-15 { margin-top: 15px; }
.mt-20 { margin-top: 20px; }
.mt-30 { margin-top: 30px; }
.py-20 { padding-top: 20px; padding-bottom: 20px; }
.py-60 { padding-top: 60px; padding-bottom: 60px; }
.py-80 { padding-top: 80px; padding-bottom: 80px; }

/* Tracking Information */
.tracking-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.tracking-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.tracking-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.tracking-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
}

.tracking-content {
    flex: 1;
}

.tracking-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 5px 0;
}

.tracking-value {
    font-size: 0.85rem;
    color: #495057;
    margin: 0;
    line-height: 1.4;
}

.tracking-note {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 10px;
    padding: 15px;
}

.note-header {
    color: #856404;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.note-content {
    color: #856404;
    font-size: 0.85rem;
    margin: 0;
    line-height: 1.4;
}

/* Responsive for tracking */
@media (max-width: 768px) {
    .tracking-info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .tracking-item {
        padding: 12px;
    }
    
    .tracking-icon {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
}

/* Confirm Received Button Styling */
.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 25px;
    padding: 15px 40px;
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-success:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
}

.text-center {
    text-align: center;
}

.mt-20 {
    margin-top: 20px;
}

/* Review Section Styling */
.review-products {
    margin-top: 20px;
}

.review-product-item {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    background: #fff;
    transition: all 0.3s ease;
}

.review-product-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.product-info {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f3f4;
}

.product-image {
    flex-shrink: 0;
}

.product-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.no-image {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #6c757d;
}

.product-details {
    flex: 1;
}

.product-name {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 5px 0;
}

.product-variant {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0 0 5px 0;
}

.variant-color, .variant-storage {
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 12px;
    margin-right: 5px;
    font-size: 0.75rem;
}

.product-quantity {
    font-size: 0.85rem;
    color: #495057;
    margin: 0;
}

/* Star Rating Styling */
.rating-section {
    margin-bottom: 15px;
}

.rating-label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 5px;
}

.star-input {
    display: none;
}

.star-label {
    cursor: pointer;
    font-size: 24px;
    color: #ddd;
    transition: color 0.2s ease;
}

.star-label:hover,
.star-label:hover ~ .star-label,
.star-input:checked ~ .star-label {
    color: #ffc107;
}

.star-label i {
    transition: transform 0.2s ease;
}

.star-label:hover i,
.star-label:hover ~ .star-label i,
.star-input:checked ~ .star-label i {
    transform: scale(1.2);
}

/* Review Content Styling */
.review-content-section {
    margin-bottom: 15px;
}

.content-label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.review-textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.9rem;
    resize: vertical;
    transition: border-color 0.3s ease;
}

.review-textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.review-actions {
    text-align: right;
}

/* Existing Review Styling */
.existing-review {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #28a745;
}

.rating-display {
    margin-bottom: 10px;
}

.star-filled {
    color: #ffc107;
}

.star-empty {
    color: #ddd;
}

.review-content {
    font-style: italic;
    color: #495057;
    margin: 10px 0;
    line-height: 1.5;
}

.review-date {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Responsive Design for Reviews */
@media (max-width: 768px) {
    .product-info {
        flex-direction: column;
        text-align: center;
    }
    
    .product-thumb, .no-image {
        width: 80px;
        height: 80px;
    }
    
    .star-label {
        font-size: 20px;
    }
    
    .review-actions {
        text-align: center;
    }
    
    .btn-sm {
        width: 100%;
    }
}
</style>

@if($order->status === 'shipping')
<script>
    function confirmReceived() {
        if (confirm('Bạn có chắc chắn đã nhận được hàng và muốn xác nhận hoàn thành đơn hàng?')) {
            document.getElementById('confirmReceivedForm').submit();
        }
    }
</script>
@endif

<script>
// Xử lý action từ URL parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const action = urlParams.get('action');
    
    if (action === 'confirm-received') {
        // Xóa parameter action ngay lập tức để tránh hiện lại khi reload
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
        
        // Tự động xác nhận nhận hàng
        if (confirm('Bạn có chắc chắn đã nhận được hàng và muốn xác nhận hoàn thành đơn hàng?')) {
            // Tạo form và submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("client.order.confirm-received", $order->id) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Xử lý thông báo thành công từ parameter
    const success = urlParams.get('success');
    if (success === 'received') {
        // Xóa parameter success ngay lập tức để tránh hiện lại khi reload
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
        
        // Hiển thị thông báo thành công
        const successMessage = 'Đã xác nhận nhận hàng thành công! Đơn hàng đã được hoàn thành. Bạn có thể đánh giá sản phẩm bên dưới.';
        
        // Tạo alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show animate-slide-down';
        alertDiv.innerHTML = `
            <div class="alert-icon">
                <i class="fa fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <h6 class="alert-heading">Thành công!</h6>
                <p class="mb-0">${successMessage}</p>
            </div>
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
                <i class="fa fa-times"></i>
            </button>
        `;
        
        // Thêm vào đầu container
        const container = document.querySelector('.order-detail-section .container');
        const firstRow = container.querySelector('.row');
        container.insertBefore(alertDiv, firstRow);
        
        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Tự động scroll đến form đánh giá nếu có
    if (action === 'confirm-received' || action === 'review') {
        setTimeout(() => {
            const reviewSection = document.querySelector('.status-actions');
            if (reviewSection) {
                reviewSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        }, 1000);
    }
    
    // Kiểm tra nếu có thông báo success và đơn hàng đã hoàn thành, tự động scroll đến form đánh giá
    const successAlert = document.querySelector('.alert-success');
    if (successAlert && '{{ $order->status }}' === 'completed') {
        setTimeout(() => {
            const reviewSection = document.querySelector('.status-actions');
            if (reviewSection) {
                reviewSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        }, 2000);
    }
});
</script>
@endsection 