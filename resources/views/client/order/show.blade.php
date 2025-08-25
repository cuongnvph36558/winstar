<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Chi tiết đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}</title>
  <!-- Favicons -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v={{ time() }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}?v={{ time() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Pusher disabled - Real-time notifications turned off -->
<!-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script> -->
  <style>
    .max-w-4xl {
      max-width: 54rem;
    }
    
    .section-container {
      margin: 1.5rem auto;
      padding: 1.5rem;
    }
    
    .mx-auto {
      margin-left: auto;
      margin-right: auto;
    }
    
    /* Summary Table Styles - Copy from checkout */
    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
    }

    .summary-table th,
    .summary-table td {
      padding: 15px 0;
      border-bottom: 1px solid #e9ecef;
      vertical-align: middle;
    }

    .summary-table th {
      font-weight: 600;
      color: #2c3e50;
      text-align: left;
      width: 60%;
    }

    .summary-table td {
      text-align: right;
      font-weight: 500;
      width: 40%;
    }

    .summary-table .total-row {
      border-top: 2px solid #e9ecef;
      font-size: 1.1rem;
      font-weight: 700;
    }

    .summary-table .total-row th,
    .summary-table .total-row td {
      color: #e74c3c;
      padding-top: 15px;
    }
    
    .summary-header {
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f8f9fa;
    }

    .summary-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 0;
    }
    
    .status-card {
      transition: all 0.3s ease;
      border-left: 3px solid;
    }
    .status-new {
      border-left-color: #3B82F6;
      background-color: #EFF6FF;
    }
    .status-processing {
      border-left-color: #F59E0B;
      background-color: #FFFBEB;
    }
    .status-completed {
      border-left-color: #10B981;
      background-color: #ECFDF5;
    }
    .status-cancelled {
      border-left-color: #EF4444;
      background-color: #FEF2F2;
    }
    .product-card:hover {
      background-color: #F9FAFB;
    }
    
    /* Timeline animations */
    @keyframes pulse {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.7;
      }
    }
    
    .timeline-item {
      transition: all 0.3s ease;
    }
    
    .timeline-item:hover {
      transform: translateX(5px);
    }
    
    /* Status-specific colors */
    .status-pending {
      border-left-color: #3B82F6;
      background-color: #EFF6FF;
    }
    .status-processing {
      border-left-color: #F59E0B;
      background-color: #FFFBEB;
    }
    .status-shipping {
      border-left-color: #3B82F6;
      background-color: #EFF6FF;
    }
    .status-delivered {
      border-left-color: #F97316;
      background-color: #FFF7ED;
    }
    .status-received {
      border-left-color: #8B5CF6;
      background-color: #F3F4F6;
    }
    .status-completed {
      border-left-color: #10B981;
      background-color: #ECFDF5;
    }
    .status-cancelled {
      border-left-color: #EF4444;
      background-color: #FEF2F2;
    }
    
    /* Payment method styles */
    .payment-method-item {
      transition: all 0.3s ease;
      border: 2px solid #e9ecef;
      border-radius: 8px;
      margin-bottom: 12px;
      background: white;
    }
    
    .payment-method-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-color: #007bff;
    }

    .payment-radio {
      position: relative;
    }

    .payment-radio input[type="radio"] {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }

    .payment-label {
      display: block;
      padding: 16px;
      cursor: pointer;
      margin: 0;
      width: 100%;
    }

    .payment-radio input[type="radio"]:checked + .payment-label {
      background-color: #f8f9ff;
      border-color: #007bff;
    }

    .payment-radio input[type="radio"]:checked ~ .payment-method-item {
      border-color: #007bff;
      background-color: #f8f9ff;
    }

    /* Payment Methods Container */
    .payment-methods-container {
      margin-bottom: 1rem;
    }

    .payment-methods-container .section-header {
      margin-bottom: 1rem;
    }

    /* Payment Display Components */
    .payment-method-display-container,
    .payment-status-display-container {
      margin-bottom: 1rem;
    }

    .payment-method-display {
      display: flex;
      align-items: center;
      width: 100%;
      padding: 12px;
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .payment-method-display:hover {
      border-color: #3b82f6;
      box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    }

    .payment-method-display .payment-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 25px;
      margin-right: 12px;
    }

    .payment-method-display .payment-icon img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .payment-method-display .payment-icon i {
      font-size: 18px;
    }

    .payment-method-display .payment-info {
      flex: 1;
    }

    .payment-method-display .payment-info .font-medium {
      font-weight: 600;
      color: #1f2937;
      margin: 0;
      font-size: 14px;
    }

    .payment-method-display .payment-info .text-sm {
      font-size: 12px;
      color: #6b7280;
      margin: 2px 0 0 0;
    }

    .payment-method-display .payment-features {
      margin-top: 8px;
    }

    .payment-method-display .payment-features .text-xs {
      font-size: 11px;
      color: #9ca3af;
      margin-bottom: 2px;
    }

    .payment-method-display .payment-features .text-xs:last-child {
      margin-bottom: 0;
    }

    /* Payment Summary Section */
    .payment-summary-section {
      margin-bottom: 1.5rem;
    }

    .payment-summary-section .bg-gray-50 {
      background-color: #f9fafb;
    }

    .payment-summary-section .space-y-2 > div {
      margin-bottom: 0.5rem;
    }

    .payment-summary-section .space-y-2 > div:last-child {
      margin-bottom: 0;
    }

    /* Payment Info Section */
    .payment-info-section {
      margin-bottom: 1.5rem;
    }

    .payment-info-section .bg-blue-50 {
      background-color: #eff6ff;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .payment-method-display {
        padding: 10px;
      }
      
      .payment-method-display .payment-icon {
        width: 35px;
        height: 22px;
        margin-right: 10px;
      }
      
      .payment-method-display .payment-info .font-medium {
        font-size: 13px;
      }
      
      .payment-method-display .payment-info .text-sm {
        font-size: 11px;
      }
    }
    }
    
    .payment-method-item input[type="radio"] {
      accent-color: #3B82F6;
    }
    
    .payment-method-item input[type="radio"]:checked + label {
      color: #3B82F6;
    }
    
    .payment-icon {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .payment-info h6 {
      margin: 0;
      font-weight: 600;
    }
    
    .payment-info p {
      margin: 0;
      color: #6B7280;
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="container mx-auto px-4 py-8" style="max-width: 54rem;">
    <!-- Toast notifications will be shown here -->

    <!-- Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
        <a href="{{ route('client.order.list') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
          <i class="fas fa-arrow-left mr-2"></i>
          Quay lại danh sách đơn hàng
        </a>
        <a href="{{ route('client.home') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
          <i class="fas fa-home mr-2"></i>
          Trang chủ
        </a>
            </div>
      <h1 class="text-2xl font-bold text-gray-900">Chi tiết đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}</h1>
      <div class="flex items-center mt-2">
        <span class="px-3 py-1 rounded-full bg-blue-600 text-white text-sm font-medium">
          Ngày đặt: {{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}
        </span>
        </div>
    </div>

    <!-- Order Status Card -->
    <div id="order-status-card" data-order-id="{{ $order->id }}" class="status-card status-{{ $order->status }} rounded-lg p-4 mb-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-medium text-lg mb-1">Trạng thái đơn hàng</h3>
          <p class="text-base">
            @switch($order->status)
              @case('pending')
                Đơn hàng của bạn đã được đặt thành công
                @break
              @case('processing')
                Đơn hàng của bạn đang được chuẩn bị
                @break
              @case('shipping')
                Đơn hàng của bạn đang được giao
                @break
              @case('delivered')
                Đơn hàng của bạn đã được giao
                @break
              @case('received')
                Đơn hàng của bạn đã được nhận
                @break
              @case('completed')
                Đơn hàng của bạn đã hoàn thành
                @break
              @case('cancelled')
                Đơn hàng của bạn đã được hủy
                @break
              @default
                Đơn hàng của bạn đang được xử lý
            @endswitch
          </p>
                        </div>
        <div class="text-right">
          <span class="px-3 py-1 rounded-full text-white text-sm font-medium
            @switch($order->status)
              @case('pending')
                bg-blue-500
                @break
              @case('processing')
                bg-yellow-500
                @break
              @case('shipping')
                bg-blue-600
                @break
              @case('delivered')
                bg-orange-500
                @break
              @case('received')
                bg-purple-500
                @break
              @case('completed')
                bg-green-500
                @break
              @case('cancelled')
                bg-red-500
                @break
              @default
                bg-gray-500
            @endswitch">
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
                Đang xử lý
            @endswitch
          </span>
          @if($order->status === 'processing')
            <p class="text-sm mt-1">Dự kiến hoàn thành: {{ $order->created_at ? $order->created_at->addDays(4)->format('d/m/Y') : 'N/A' }}</p>
          @endif
                        </div>
                    </div>
                </div>

    <!-- Customer & Shipping Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" style="max-width: 54rem;">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <h3 class="font-medium mb-3 flex items-center">
          <i class="fas fa-user-circle mr-2 text-gray-500"></i>
          Thông tin khách hàng
        </h3>
        <div class="space-y-2 text-sm">
          <p><span class="text-gray-500 inline-block w-28">Họ tên:</span> {{ $order->user->name ?? 'N/A' }}</p>
          <p><span class="text-gray-500 inline-block w-28">Số điện thoại:</span> {{ $order->phone }}</p>
          <p><span class="text-gray-500 inline-block w-28">Email:</span> {{ $order->user->email ?? 'N/A' }}</p>
                        </div>
                        </div>
      
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <h3 class="font-medium mb-3 flex items-center">
          <i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>
          Địa chỉ giao hàng
        </h3>
        <div class="text-sm">
          <p class="mb-2">{{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</p>
          <p class="mt-3 text-gray-500"><i class="fas fa-phone mr-1"></i> {{ $order->receiver_name }}</p>
                    </div>
                </div>
            </div>

    <!-- Order Products -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
      <div class="p-4 border-b border-gray-200">
        <h3 class="font-medium">Sản phẩm đã đặt ({{ $order->orderDetails->count() }})</h3>
                                </div>
      
      @foreach($order->orderDetails as $detail)
      <div class="product-card p-4 border-b border-gray-200 flex transition {{ $loop->last ? '' : 'border-b' }}">
        <div class="w-16 h-16 mr-4">
                                                                @if($detail->variant && $detail->variant->image_variant)
                                                                    <img src="{{ asset('storage/' . (is_array(json_decode($detail->variant->image_variant, true)) ? json_decode($detail->variant->image_variant, true)[0] : $detail->variant->image_variant) ) }}" 
                                                                         alt="{{ $detail->product_name }}" 
                 class="w-16 h-16 object-cover rounded border"
                 onerror="this.parentElement.innerHTML='<div class=\'w-16 h-16 bg-gray-200 rounded border flex items-center justify-center\'><i class=\'fas fa-image text-gray-400\'></i></div>'">
                                                                @elseif($detail->product && $detail->product->image)
                                                                    <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                                         alt="{{ $detail->product_name }}" 
                 class="w-16 h-16 object-cover rounded border"
                 onerror="this.parentElement.innerHTML='<div class=\'w-16 h-16 bg-gray-200 rounded border flex items-center justify-center\'><i class=\'fas fa-image text-gray-400\'></i></div>'">
                                                                @else
            <div class="w-16 h-16 bg-gray-200 rounded border flex items-center justify-center">
              <i class="fas fa-image text-gray-400"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
        <div class="flex-1">
          <h4 class="font-medium">{{ $detail->product->name ?? 'Sản phẩm không tồn tại' }}</h4>
          <p class="text-sm text-gray-500 mb-2">
            @if($detail->original_storage_capacity)
              Dung lượng: {{ \App\Helpers\StorageHelper::formatCapacity($detail->original_storage_capacity) }}
              @if($detail->original_color_name) | @endif
            @elseif($detail->original_storage_name)
              Dung lượng: {{ $detail->original_storage_name }}
              @if($detail->original_color_name) | @endif
            @elseif($detail->variant && $detail->variant->storage && isset($detail->variant->storage->capacity))
              Dung lượng: {{ \App\Helpers\StorageHelper::formatCapacity($detail->variant->storage->capacity) }}
              @if($detail->variant && $detail->variant->color) | @endif
            @endif
            @if($detail->original_color_name)
              Màu: {{ $detail->original_color_name }}
            @elseif($detail->variant && $detail->variant->color)
              Màu: {{ $detail->variant->color->name }}
            @endif
            | Số lượng: {{ $detail->quantity }}
          </p>
          
          <div class="flex items-center justify-between">
            <div>
              <span class="font-medium">{{ number_format($detail->price * $detail->quantity) }}₫</span>
              @if($detail->price < $detail->product->price ?? 0)
                <span class="text-sm text-gray-500 line-through ml-2">{{ number_format(($detail->product->price ?? 0) * $detail->quantity) }}₫</span>
                                                        @endif
                                                            </div>
            @if($detail->price < $detail->product->price ?? 0)
              @php
                $discount = (($detail->product->price ?? 0) - $detail->price) / ($detail->product->price ?? 1) * 100;
                                                    @endphp
              <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Giảm {{ round($discount) }}%</span>
                                                @endif
                        </div>
                    </div>
                    

                </div>
      @endforeach
                        </div>


      


    <!-- Payment Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mx-auto" style="max-width: 54rem;">
      <h3 class="font-medium mb-3 flex items-center">
        <i class="fa fa-credit-card mr-2"></i>
        Chi tiết thanh toán
      </h3>
      
      <!-- Payment Summary -->
      <div class="mb-4">
        <div class="summary-header mb-4">
          <h4 class="summary-title">
            <i class="fa fa-calculator mr-2"></i>
            Tổng quan đơn hàng
          </h4>
        </div>
        @php
          // Tính toán lại các giá trị để đảm bảo chính xác
          $subtotal = $order->orderDetails->sum(function($detail) {
            return $detail->price * $detail->quantity;
          });
          
          // Lấy thông tin giảm giá từ database
          $discount_amount = $order->discount_amount ?? 0;
          $points_used = $order->points_used ?? 0;
          
          // Tính toán giá trị điểm đã sử dụng (1 điểm = 1 VND)
          $points_value = $points_used; // 1 điểm = 1 VND
          
          $shipping_fee = 30000; // Phí ship mặc định
          
          // Lấy thông tin mã giảm giá nếu có
          $coupon_code = null;
          if ($order->coupon_id) {
            $coupon = \App\Models\Coupon::find($order->coupon_id);
            $coupon_code = $coupon ? $coupon->code : null;
          }
          
          // Tính tổng tiền sau khi trừ giảm giá và điểm
          $total_before_points = $subtotal - $discount_amount + $shipping_fee;
          
          // Nếu điểm sử dụng >= tổng tiền trước điểm, thì tổng tiền = 0
          if ($points_value >= $total_before_points) {
            $total_amount = 0;
            $points_value = $total_before_points; // Chỉ sử dụng đúng số điểm cần thiết
          } else {
            $total_amount = $total_before_points - $points_value;
          }
        @endphp
        
        <table class="summary-table w-full">
          <tbody>
            <tr>
              <th class="text-left py-2">Tạm tính:</th>
              <td class="text-right py-2">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
            </tr>
            <tr>
              <th class="text-left py-2">Phí vận chuyển:</th>
              <td class="text-right py-2">{{ number_format($shipping_fee, 0, ',', '.') }}đ</td>
            </tr>
            @if($discount_amount > 0)
            <tr class="discount">
              <th class="text-left py-2">Giảm giá:</th>
              <td class="text-right py-2 text-green-600">-{{ number_format($discount_amount, 0, ',', '.') }}đ</td>
            </tr>
            @endif
            @if($points_used > 0)
            <tr class="points-discount">
              <th class="text-left py-2">Giảm điểm ({{ number_format($points_value) }} điểm):</th>
              <td class="text-right py-2 text-blue-600">-{{ number_format($points_value, 0, ',', '.') }}đ</td>
            </tr>
            @endif
            <tr class="total-row border-t border-gray-200">
              <th class="text-left py-2 font-semibold">Tổng cộng:</th>
              <td class="text-right py-2 font-semibold text-lg text-blue-600">{{ number_format($total_amount, 0, ',', '.') }}đ</td>
            </tr>
          </tbody>
        </table>
      </div>
        
        <!-- Payment Method Section -->
        <div class="mb-4">
          <h5 class="font-medium mb-2 text-sm text-gray-600">Phương thức thanh toán</h5>
          <div class="payment-method-display-container">
            <x-payment-method-display :paymentMethod="$order->payment_method" :showDescription="true" :showFeatures="true" />
          </div>
        </div>

        <!-- Payment Status Section -->
        <div class="mb-4">
          <h5 class="font-medium mb-2 text-sm text-gray-600">Trạng thái thanh toán</h5>
          <div class="payment-status-display-container">
            <x-payment-status-display :paymentStatus="$order->payment_status" :orderStatus="$order->status" />
          </div>
        </div>
        

      </div>
    </div>

    <!-- Retry Payment Section -->
    @if(\App\Helpers\PaymentHelper::canRetryPayment($order))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mt-6 mx-auto" style="max-width: 54rem;">
      <h3 class="font-medium mb-3 flex items-center">
        <i class="fas fa-credit-card mr-2 text-blue-500"></i>
        Thanh toán lại
      </h3>
      <p class="text-sm text-gray-600 mb-4">Đơn hàng của bạn chưa được thanh toán. Vui lòng chọn phương thức thanh toán để tiếp tục:</p>
      
      <!-- Payment Methods Container -->
      <div class="payment-methods-container">
        <div class="section-header mb-3">
          <h5 class="font-medium text-sm text-gray-700">Chọn phương thức thanh toán</h5>
        </div>
        
        @php
          $retryPaymentOptions = \App\Helpers\PaymentHelper::getPaymentMethodOptions();
          // Chỉ hiển thị VNPay cho retry payment
          $retryPaymentOptions = array_filter($retryPaymentOptions, function($key) {
            return $key === 'vnpay';
          }, ARRAY_FILTER_USE_KEY);
        @endphp
        
        @foreach($retryPaymentOptions as $method => $option)
        <div class="payment-method-item">
          <div class="payment-radio">
            <input type="radio" name="retry_payment_method" value="{{ $option['value'] }}" id="retry_{{ $option['value'] }}" checked>
            <label for="retry_{{ $option['value'] }}" class="payment-label">
              <x-payment-method-display :paymentMethod="$option['value']" :showDescription="true" />
            </label>
          </div>
        </div>
        @endforeach
      </div>
      
      <div class="mt-4">
        <button onclick="retryPayment()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200 w-full">
          <i class="fas fa-credit-card mr-2"></i>Thanh toán ngay
        </button>
      </div>
    </div>
    @endif

    <!-- COD Payment Info -->
    @if($order->payment_method === 'cod' && $order->payment_status === 'pending' && $order->status !== 'cancelled')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mt-6 mx-auto" style="max-width: 54rem;">
      <h3 class="font-medium mb-3 flex items-center">
        <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
        Thông tin thanh toán COD
      </h3>
      <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-start">
          <i class="fas fa-info-circle text-green-600 mt-1 mr-3"></i>
          <div>
            <p class="text-sm text-green-800 font-medium mb-1">Thanh toán khi nhận hàng (COD)</p>
            <p class="text-sm text-green-700">Đơn hàng của bạn sẽ được thanh toán khi nhận hàng. Không cần thanh toán trước.</p>
          </div>
        </div>
      </div>
    </div>
    @endif

      <!-- Additional Info -->
      @if($order->description)
      <div class="mt-4 pt-4 border-t border-gray-200">
        <h4 class="font-medium mb-2 text-sm text-gray-700">Ghi chú đơn hàng</h4>
        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $order->description }}</p>
                                                </div>
                                            @endif
                </div>

                                 <!-- Order Timeline -->
     <div class="container mx-auto px-4 py-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-4 mb-8" style="max-width: 54rem;">
       <h3 class="font-medium mb-4 flex items-center justify-between">
         <div class="flex items-center">
           <i class="fas fa-route mr-2 text-blue-500"></i>
           Theo dõi đơn hàng
         </div>
         <div class="flex items-center text-sm text-gray-500">
           <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
           <span>Đang theo dõi</span>
         </div>
       </h3>
       <div id="order-timeline" class="space-y-4">
         <!-- Timeline will be updated dynamically via JavaScript -->
       </div>
       <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
         <i class="fas fa-info-circle mr-1"></i>
         Trạng thái sẽ được cập nhật tự động khi có thay đổi
       </div>
     </div>

        <!-- Order Actions Section -->
        @php
            $canCancel = false;
            $cancelMessage = '';
            $cancelWarning = '';
            $canEdit = false;
            $editMessage = '';
            
            // Kiểm tra có thể chỉnh sửa không (trong 15 phút đầu và trạng thái phù hợp)
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
                    $cancelWarning = 'Lưu ý: Đơn hàng đã thanh toán. Bạn chỉ có thể hủy trong 15 phút đầu sau khi đặt hàng.';
                }
            }
        @endphp
        
        <!-- Edit Order Button -->
        @if($canEdit)
            <div class="mt-6 text-center edit-order-button" style="max-width: 54rem; margin: 0 auto; margin-bottom: 1.5rem;" data-created-at="{{ $order->created_at }}">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                        <span class="text-blue-800 text-sm">
                            <strong>Chỉnh sửa đơn hàng:</strong> Bạn có thể thay đổi thông tin người nhận và địa chỉ giao hàng trong 15 phút đầu.
                        </span>
                    </div>
                </div>
                <a href="{{ route('client.order.edit', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa đơn hàng
                </a>
            </div>
        @endif
        
        <!-- Cancel Order Button -->
        @if($canCancel)
            <div class="mt-6 text-center cancel-order-button" style="max-width: 54rem; margin: 0 auto; margin-bottom: 1.5rem;" data-created-at="{{ $order->created_at }}">
                @if($cancelWarning)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                            <span class="text-yellow-800 text-sm">{{ $cancelWarning }}</span>
                        </div>
                    </div>
                @endif
                <button onclick="showCancellationModal({{ $order->id }})" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-times mr-2"></i>Hủy đơn hàng
                </button>
            </div>
        @endif

    <!-- Confirm Received Button -->
    @if($order->status === 'shipping' || $order->status === 'delivered')
    <div class="mt-6 text-center confirm-received-button">
      <button onclick="confirmReceived({{ $order->id }})" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-200">
        <i class="fas fa-check mr-2"></i>Đã nhận hàng
      </button>
      <p class="text-sm text-gray-600 mt-2">Vui lòng xác nhận khi bạn đã nhận được hàng</p>
    </div>
    @endif

    <!-- Review Products Button -->
    @if($order->status === 'completed')
      @php
        $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->exists();
      @endphp
      
      <!-- Reviews Section -->
      @if($order->status === 'completed')
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 mx-auto" style="max-width: 54rem;">
        <h3 class="font-medium mb-4 flex items-center">
          <i class="fas fa-star mr-2 text-yellow-500"></i>
          Đánh giá sản phẩm trong đơn hàng này
        </h3>
        
        @php
          $orderReviews = \App\Models\Review::where('user_id', auth()->id())
              ->where('order_id', $order->id)
              ->orderBy('created_at', 'desc')
              ->get();
          $hasReviewed = $orderReviews->count() > 0;
        @endphp
        
        @if($orderReviews->count() > 0)
          <div class="space-y-4">
            @foreach($orderReviews as $review)
              @php
                $product = $order->orderDetails->where('product_id', $review->product_id)->first();
              @endphp
              @if($product && $review->product)
                <div class="border border-gray-200 rounded-lg p-4">
                  <div class="flex items-start space-x-3">
                    <!-- Product Image -->
                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                      @if($product->variant && $product->variant->image_variant)
                        <img src="{{ asset('storage/' . (is_array(json_decode($product->variant->image_variant, true)) ? json_decode($product->variant->image_variant, true)[0] : $product->variant->image_variant) ) }}" 
                             alt="{{ $product->product->name }}" 
                             class="w-12 h-12 object-cover rounded-lg">
                      @elseif($product->product && $product->product->image)
                        <img src="{{ asset('storage/' . $product->product->image) }}" 
                             alt="{{ $product->product->name }}" 
                             class="w-12 h-12 object-cover rounded-lg">
                      @else
                        <i class="fas fa-image text-gray-400"></i>
                      @endif
                    </div>
                    
                    <!-- Review Content -->
                    <div class="flex-1">
                      <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-900">{{ $product->product->name ?? 'Sản phẩm không tồn tại' }}</h4>
                        <div class="flex items-center space-x-2">
                          <span class="text-xs px-2 py-1 rounded {{ $review->status == 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $review->status == 1 ? 'Đã duyệt' : 'Chờ duyệt' }}
                          </span>
                          <span class="text-xs text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                      </div>
                      
                      <!-- Rating Stars -->
                      <div class="flex items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                          <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-xs text-gray-500 ml-2">{{ $review->rating }}/5</span>
                      </div>
                      
                      <!-- Review Text -->
                      <p class="text-sm text-gray-700">{{ $review->content }}</p>
                    </div>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        @else
          <div class="text-center py-8">
            <i class="fas fa-star text-4xl text-gray-300 mb-4"></i>
            <h4 class="text-lg font-medium text-gray-900 mb-2">Bạn chưa đánh giá sản phẩm nào</h4>
            <p class="text-gray-600 mb-4">Hãy chia sẻ trải nghiệm của bạn về các sản phẩm trong đơn hàng này</p>
          </div>
        @endif
      </div>
      @endif

      @if(!$hasReviewed)
        <div class="mt-6 text-center review-products-button">
          <button onclick="openReviewForm()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
            <i class="fas fa-star mr-2"></i>Đánh giá sản phẩm
          </button>
          <p class="text-sm text-gray-600 mt-2">Chia sẻ trải nghiệm của bạn về sản phẩm</p>
        </div>
      @endif
      
      @if($hasReviewed)
        <div class="mt-6 text-center">
          <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-center">
              <i class="fas fa-check-circle text-green-600 mr-2"></i>
              <span class="text-green-800 font-medium">Bạn đã đánh giá đơn hàng này</span>
            </div>
            <p class="text-sm text-green-700 mt-1">Cảm ơn bạn đã chia sẻ trải nghiệm!</p>
          </div>
        </div>
      @endif
    @endif

    <!-- Return/Exchange Section -->
    @if($order->status === 'completed')
              @php
          // Kiểm tra xem đơn hàng đã có yêu cầu đổi hoàn hàng chưa
          $hasReturnRequest = $order->return_status !== 'none';
          // Kiểm tra thời gian (cho phép đổi hoàn trong vòng 7 ngày sau khi hoàn thành)
          // Sử dụng thời điểm đơn hàng được cập nhật thành completed
          $deadline = $order->updated_at->addDays(7);
          $canReturn = now()->lte($deadline);
        @endphp
      
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 mx-auto" style="max-width: 54rem;" data-section="return-exchange">
        <h3 class="font-medium mb-4 flex items-center">
          <i class="fas fa-exchange-alt mr-2 text-orange-500"></i>
          Đổi hoàn hàng
        </h3>
        
        @if($hasReturnRequest)
          <!-- Hiển thị trạng thái yêu cầu đổi hoàn hàng -->
          <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                  <span class="text-blue-800 font-medium">Yêu cầu đổi hoàn hàng</span>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                  @switch($order->return_status)
                    @case('requested')
                      bg-yellow-100 text-yellow-800
                      @break
                    @case('approved')
                      bg-green-100 text-green-800
                      @break
                    @case('rejected')
                      bg-red-100 text-red-800
                      @break
                    @case('completed')
                      bg-blue-100 text-blue-800
                      @break
                    @default
                      bg-gray-100 text-gray-800
                  @endswitch">
                  @switch($order->return_status)
                    @case('requested')
                      Chờ xử lý
                      @break
                    @case('approved')
                      Đã chấp thuận
                      @break
                    @case('rejected')
                      Đã từ chối
                      @break
                    @case('completed')
                      Hoàn thành
                      @break
                    @default
                      Không xác định
                  @endswitch
                </span>
              </div>
              
              @if($order->return_reason)
                <div class="mt-3">
                  <p class="text-sm text-blue-700"><strong>Lý do:</strong> {{ $order->return_reason }}</p>
                </div>
              @endif
              
              @if($order->return_description)
                <div class="mt-2">
                  <p class="text-sm text-blue-700"><strong>Mô tả:</strong> {{ $order->return_description }}</p>
                </div>
              @endif
              
              @if($order->return_method)
                <div class="mt-2">
                  <p class="text-sm text-blue-700">
                    <strong>Phương thức:</strong> 
                    {{ $order->return_method === 'points' ? 'Đổi điểm' : 'Đổi hàng' }}
                  </p>
                </div>
              @endif
              
              @if($order->return_requested_at)
                <div class="mt-2">
                  <p class="text-sm text-blue-700">
                    <strong>Ngày yêu cầu:</strong> {{ $order->return_requested_at->format('d/m/Y H:i') }}
                  </p>
                </div>
              @endif
              
              @if($order->admin_return_note)
                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                  <p class="text-sm text-gray-700">
                    <strong>Ghi chú từ admin:</strong> {{ $order->admin_return_note }}
                  </p>
                </div>
              @endif
              
              @if($order->return_status === 'requested')
                <div class="mt-4">
                  <form action="{{ route('client.return.cancel', $order->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 text-sm" 
                            onclick="return confirm('Bạn có chắc chắn muốn hủy yêu cầu đổi hoàn hàng?')">
                      <i class="fas fa-times mr-1"></i>Hủy yêu cầu
                    </button>
                  </form>
                </div>
              @endif
            </div>
          </div>
        @elseif($canReturn)
          <!-- Hiển thị nút yêu cầu đổi hoàn hàng -->
          <div class="text-center py-8">
            <i class="fas fa-exchange-alt text-4xl text-orange-300 mb-4"></i>
            <h4 class="text-lg font-medium text-gray-900 mb-2">Bạn có muốn đổi hoàn hàng?</h4>
            <p class="text-gray-600 mb-4">Nếu sản phẩm có vấn đề, bạn có thể yêu cầu đổi hoàn hàng trong vòng 7 ngày kể từ ngày hoàn thành đơn hàng</p>
            <div class="flex justify-center space-x-4">
              <a href="{{ route('client.return.form', $order->id) }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-exchange-alt mr-2"></i>Yêu cầu đổi hoàn hàng
              </a>
            </div>
            <p class="text-xs text-gray-500 mt-3">
              <i class="fas fa-clock mr-1"></i>
              Hạn cuối: {{ $deadline->format('d/m/Y H:i') }}
            </p>
          </div>
        @else
          <!-- Hết hạn đổi hoàn hàng -->
          <div class="text-center py-8">
            <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
            <h4 class="text-lg font-medium text-gray-900 mb-2">Đã hết hạn đổi hoàn hàng</h4>
            <p class="text-gray-600 mb-4">Thời gian yêu cầu đổi hoàn hàng đã kết thúc (7 ngày sau khi hoàn thành đơn hàng)</p>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
              <p class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Hạn cuối đã qua: {{ $deadline->format('d/m/Y H:i') }}
              </p>
            </div>
          </div>
        @endif
      </div>
    @endif
                                    </div>
                                    
  <!-- Cancellation Modal -->
  <div id="cancellationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
          <i class="fas fa-exclamation-triangle text-red-600"></i>
        </div>
        <div class="mt-3 text-center">
          <h3 class="text-lg font-medium text-gray-900">Xác nhận hủy đơn hàng</h3>
          <div class="mt-2 px-7 py-3">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
              <div class="flex items-center">
                <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                <span class="text-yellow-800 text-sm">
                  <strong>Điều kiện hủy đơn:</strong><br>
                  • Đơn hàng chưa thanh toán: Có thể hủy bất cứ lúc nào<br>
                  • Đơn hàng đã thanh toán online: Chỉ có thể hủy trong 15 phút đầu<br>
                  • Đơn hàng đang được xử lý hoặc đã giao: Không thể hủy
                </span>
              </div>
            </div>
            
            @if($order->status === 'processing' && $order->payment_status === 'paid')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
              <div class="flex items-center">
                <i class="fas fa-coins text-blue-600 mr-2"></i>
                <span class="text-blue-800 text-sm">
                  <strong>Thông tin hoàn điểm:</strong><br>
                  • Khi hủy đơn hàng đã thanh toán, số tiền {{ number_format($order->total_amount) }} VND sẽ được hoàn thành điểm vào tài khoản của bạn<br>
                  • Tỷ lệ hoàn điểm: 1 VND = 1 điểm<br>
                  • Điểm hoàn sẽ có hiệu lực ngay lập tức và có thể sử dụng cho các đơn hàng tiếp theo
                </span>
              </div>
            </div>
            @endif
            <p class="text-sm text-gray-500">Vui lòng nhập lý do hủy đơn hàng (tối thiểu 10 ký tự)</p>
            <textarea id="cancellation_reason" class="mt-3 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" rows="4" placeholder="Nhập lý do hủy đơn hàng..." minlength="10" maxlength="500"></textarea>
            <div class="text-xs text-gray-400 mt-1">
                                            <span id="charCount">0</span>/500 ký tự
                                        </div>
                                        </div>
          <div class="items-center px-4 py-3">
            <button id="confirmCancelBtn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50" disabled>
              Xác nhận hủy đơn hàng
                                    </button>
            <button onclick="closeCancellationModal()" class="mt-2 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
              Hủy bỏ
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
  </div>

  <script>
    // Function to show cancellation modal
    function showCancellationModal(orderId) {
      document.getElementById('cancellationModal').classList.remove('hidden');
            
            // Character count
      const textarea = document.getElementById('cancellation_reason');
      const charCount = document.getElementById('charCount');
      const confirmBtn = document.getElementById('confirmCancelBtn');
      
      textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
                
                if (count < 10) {
          this.classList.add('border-red-500');
          confirmBtn.disabled = true;
                } else {
          this.classList.remove('border-red-500');
          confirmBtn.disabled = false;
                }
            });
            
            // Form submission
      confirmBtn.addEventListener('click', function() {
        const reason = textarea.value.trim();
                if (reason.length < 10) {
                    alert('Vui lòng nhập lý do hủy đơn hàng (tối thiểu 10 ký tự)');
                    return;
                }
                
        // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
        form.action = '{{ route("client.order.cancel", ":orderId") }}'.replace(':orderId', orderId);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        form.appendChild(tokenInput);
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'cancellation_reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        
            document.body.appendChild(form);
            form.submit();
      });
    }
    
    function closeCancellationModal() {
      document.getElementById('cancellationModal').classList.add('hidden');
    }

    // Check if should show review modal
    @if(session('show_review_modal'))
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
          document.getElementById('reviewModal').classList.remove('hidden');
        }, 500);
      });
    @endif

    function goToReview() {
      // Show review form modal instead of redirecting
      document.getElementById('reviewModal').classList.add('hidden');
      setTimeout(function() {
        document.getElementById('reviewFormModal').classList.remove('hidden');
      }, 300);
    }

    function closeReviewModal() {
      document.getElementById('reviewModal').classList.add('hidden');
    }

    function openReviewForm() {
      document.getElementById('reviewFormModal').classList.remove('hidden');
    }

    function selectProductForReview(productId, productName) {
      // Update hidden inputs in the review form
      document.getElementById('selectedProductId').value = productId;
      document.getElementById('selectedProductName').textContent = productName;
      
      // Show selected product info
      document.getElementById('selectedProductInfo').classList.remove('hidden');
      
      // Show form and hide message
      document.getElementById('reviewForm').classList.remove('hidden');
      document.getElementById('noProductMessage').classList.add('hidden');
      
      // Open the review form modal
      document.getElementById('reviewFormModal').classList.remove('hidden');
      
      // Enable submit button if rating is selected
      updateSubmitButton();
    }

    // Function to create timeline HTML
    function createTimelineHTML(status) {
      const statusHistory = {
        'pending': {
          title: 'Đơn hàng đã đặt',
          description: 'Đơn hàng của bạn đã được đặt thành công',
          icon: 'fa-shopping-cart',
          done: true
        },
        'processing': {
          title: 'Đang chuẩn bị hàng',
          description: 'Đơn hàng đang được chuẩn bị và đóng gói',
          icon: 'fa-box',
          done: ['processing', 'shipping', 'delivered', 'completed'].includes(status)
        },
        'shipping': {
          title: 'Đang giao hàng',
          description: 'Đơn hàng đang được vận chuyển đến bạn',
          icon: 'fa-truck',
          done: ['delivered', 'completed'].includes(status)
        },
        'delivered': {
          title: 'Đã giao hàng',
          description: 'Đơn hàng đã được giao thành công',
          icon: 'fa-home',
          done: ['completed'].includes(status)
        },
        'completed': {
          title: 'Hoàn thành',
          description: 'Đơn hàng đã được giao thành công',
          icon: 'fa-star',
          done: status === 'completed'
        }
      };

      let timelineHTML = '';
      
      // If status is cancelled, show a special timeline
      if (status === 'cancelled') {
        timelineHTML = `
          <div class="flex items-start space-x-3 opacity-100 timeline-item" data-status="cancelled">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-red-500 text-white">
              <i class="fas fa-times-circle text-xs"></i>
            </div>
            <div class="flex-1">
              <p class="font-medium text-sm text-red-600">Đơn hàng đã hủy</p>
              <p class="text-sm text-gray-500">Đơn hàng của bạn đã được hủy thành công</p>
              <p class="text-xs text-red-500 mt-1 font-medium">❌ Đã hủy</p>
            </div>
          </div>
        `;
        return timelineHTML;
      }
      
      // Normal timeline for other statuses
      const statuses = ['pending', 'processing', 'shipping', 'delivered', 'completed'];
      
      statuses.forEach((statusKey, index) => {
        const statusInfo = statusHistory[statusKey];
        const isActive = status === statusKey;
        const isDone = statusInfo.done;
        const isFuture = !isDone && !isActive;
        
        const opacity = isActive ? 'opacity-100' : (isDone ? 'opacity-80' : 'opacity-40');
        const bgColor = isActive ? 'bg-blue-500' : (isDone ? 'bg-green-500' : 'bg-gray-300');
        const textColor = isActive ? 'text-blue-600' : 'text-gray-700';
        const iconColor = isActive ? 'text-white' : (isDone ? 'text-white' : 'text-gray-600');
        
        timelineHTML += `
          <div class="flex items-start space-x-3 ${opacity} timeline-item" data-status="${statusKey}">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center ${bgColor} ${iconColor}">
              <i class="fas ${statusInfo.icon} text-xs"></i>
            </div>
            <div class="flex-1">
              <p class="font-medium text-sm ${textColor}">${statusInfo.title}</p>
              <p class="text-sm text-gray-500">${statusInfo.description}</p>
              ${isActive && statusKey !== 'completed' ? '<p class="text-xs text-blue-500 mt-1 font-medium">🔄 Đang xử lý...</p>' : ''}
            </div>
          </div>
        `;
      });
      
      return timelineHTML;
    }

    // Function to update timeline
    function updateTimeline(status) {
      const timelineContainer = document.getElementById('order-timeline');
      if (timelineContainer) {
        // console.log removed
        timelineContainer.innerHTML = createTimelineHTML(status);
        
        // Add animation for active status
        const activeItem = timelineContainer.querySelector(`[data-status="${status}"]`);
        if (activeItem) {
          activeItem.style.animation = 'pulse 2s infinite';
        }
      }
    }

    // Function to update payment status
    function updatePaymentStatus(paymentStatus) {
      const paymentStatusElement = document.getElementById('payment-status');
      if (!paymentStatusElement) return;
      
      let statusHTML = '';
      switch (paymentStatus) {
        case 'paid':
          statusHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Đã thanh toán</span>';
          break;
        case 'pending':
          statusHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Chưa thanh toán</span>';
          break;
        case 'cancelled':
          statusHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Đã hủy</span>';
          break;
        default:
          statusHTML = `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><i class="fas fa-question-circle mr-1"></i>${paymentStatus}</span>`;
      }
      
      paymentStatusElement.innerHTML = statusHTML;
      // console.log removed
      
      // Hiển thị toast thông báo khi trạng thái thanh toán thay đổi
      if (paymentStatus === 'paid') {
        showToast('✅ Trạng thái thanh toán đã được cập nhật thành "Đã thanh toán"', 'success');
      }
    }

    // Function to update order totals
    function updateOrderTotals(data) {
      if (data.subtotal !== undefined) {
        const subtotalElement = document.getElementById('order-subtotal');
        if (subtotalElement) {
          subtotalElement.textContent = new Intl.NumberFormat('vi-VN').format(data.subtotal) + '₫';
        }
      }
      
      if (data.discount_amount !== undefined) {
        const discountElement = document.getElementById('order-discount');
        if (discountElement) {
          discountElement.textContent = '-' + new Intl.NumberFormat('vi-VN').format(data.discount_amount) + '₫';
        }
      }
      
      if (data.shipping_fee !== undefined) {
        const shippingElement = document.getElementById('order-shipping');
        if (shippingElement) {
          shippingElement.textContent = data.shipping_fee > 0 ? new Intl.NumberFormat('vi-VN').format(data.shipping_fee) + '₫' : 'Miễn phí';
        }
      }
      
      if (data.total_amount !== undefined) {
        const totalElement = document.getElementById('order-total');
        if (totalElement) {
          totalElement.textContent = new Intl.NumberFormat('vi-VN').format(data.total_amount) + '₫';
        }
      }
      
      // console.log removed
    }

    // Enhanced updateOrderStatus function
    function updateOrderStatus(newStatus, additionalData = {}) {
      // console.log removed
      
      // Debug: Check all elements with status-card class
      const allStatusCards = document.querySelectorAll('.status-card');
      // console.log removed
      allStatusCards.forEach((card, index) => {
        // console.log removed
      });
      
      // More robust DOM element selection with priority on ID
      let statusCard = document.getElementById('order-status-card') || document.querySelector('.status-card');
      if (!statusCard) {
        console.error('Status card not found');
        console.log('All elements with "status" in class:', document.querySelectorAll('[class*="status"]'));
        console.log('All div elements:', document.querySelectorAll('div'));
        return;
      }
      
      // console.log removed
      // console.log removed
      
      let statusDescription = statusCard.querySelector('p');
      let statusBadge = statusCard.querySelector('.text-right span');
      
      // Fallback: try to find elements with different selectors
      if (!statusDescription) {
        statusDescription = statusCard.querySelector('.text-left p') || statusCard.querySelector('div p');
        // console.log removed
      }
      
      if (!statusBadge) {
        statusBadge = statusCard.querySelector('.text-right .badge') || statusCard.querySelector('span.badge') || statusCard.querySelector('.text-right span');
        // console.log removed
      }
      
      if (!statusDescription || !statusBadge) {
        console.error('Status description or badge not found even with fallback selectors');
        // console.log removed
        return;
      }
      
      // console.log removed
      
      // Update status card immediately
      statusCard.className = statusCard.className.replace(/status-\w+/g, '') + ` status-${newStatus}`;
      statusBadge.className = statusBadge.className.replace(/bg-\w+-\d+/g, '');
      
      // Fast status config lookup
      const statusConfig = {
        'pending': { text: 'Chờ xử lý', color: 'bg-blue-500', description: 'Đơn hàng của bạn đã được đặt thành công' },
        'processing': { text: 'Đang chuẩn bị hàng', color: 'bg-yellow-500', description: 'Đơn hàng của bạn đang được chuẩn bị' },
        'shipping': { text: 'Đang giao hàng', color: 'bg-blue-600', description: 'Đơn hàng của bạn đang được giao' },
        'delivered': { text: 'Đã giao hàng', color: 'bg-orange-500', description: 'Đơn hàng của bạn đã được giao' },
        'received': { text: 'Đã nhận hàng', color: 'bg-purple-500', description: 'Đơn hàng của bạn đã được nhận' },
        'completed': { text: 'Hoàn thành', color: 'bg-green-500', description: 'Đơn hàng của bạn đã hoàn thành' },
        'cancelled': { text: 'Đã hủy', color: 'bg-red-500', description: 'Đơn hàng của bạn đã được hủy' }
      };
      
      const config = statusConfig[newStatus] || statusConfig['pending'];
      // console.log removed
      
      // Update UI immediately
      statusBadge.textContent = config.text;
      statusBadge.classList.add(config.color);
      statusDescription.textContent = config.description;
      
      // console.log removed
      
      // Update timeline
      updateTimeline(newStatus);
      
      // Update payment status if provided or auto-update based on order status
      if (additionalData.payment_status) {
        updatePaymentStatus(additionalData.payment_status);
      } else if (newStatus === 'completed' || newStatus === 'delivered') {
        // Tự động cập nhật trạng thái thanh toán khi đơn hàng hoàn thành hoặc đã giao hàng
        updatePaymentStatus('paid');
      }
      
      // Update order totals if provided
      if (additionalData.subtotal || additionalData.discount_amount || additionalData.shipping_fee || additionalData.total_amount) {
        updateOrderTotals(additionalData);
      }
      
      // Fast button updates with more robust selectors
      const cancelButton = document.querySelector('.cancel-order-button');
      if (cancelButton) {
        const orderCreatedAt = cancelButton.getAttribute('data-created-at') || '{{ $order->created_at }}';
        const createdTime = new Date(orderCreatedAt);
        const timeLimit = new Date(Date.now() - 15 * 60 * 1000); // 15 phút trước
        const isWithinTimeLimit = createdTime > timeLimit;
        const canCancelStatus = ['pending', 'processing'].includes(newStatus);
        
        if (!isWithinTimeLimit || !canCancelStatus) {
          cancelButton.style.display = 'none';
          // console.log removed
        } else {
          cancelButton.style.display = 'block';
          // console.log removed
        }
      }

      // Fast edit button update - ẩn/hiện button edit dựa trên trạng thái và thời gian
      const editButton = document.querySelector('.edit-order-button');
      if (editButton) {
        const orderCreatedAt = editButton.getAttribute('data-created-at') || '{{ $order->created_at }}';
        const createdTime = new Date(orderCreatedAt);
        const timeLimit = new Date(Date.now() - 15 * 60 * 1000); // 15 phút trước
        const isWithinTimeLimit = createdTime > timeLimit;
        const isEditableStatus = ['pending', 'processing'].includes(newStatus);
        
        if (!isWithinTimeLimit || !isEditableStatus) {
          editButton.style.display = 'none';
          // console.log removed
        } else {
          editButton.style.display = 'block';
          // console.log removed
        }
      }

      // Fast confirm received button update
      const confirmReceivedButton = document.querySelector('.confirm-received-button');
      if ((newStatus === 'shipping' || newStatus === 'delivered') && !confirmReceivedButton) {
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'mt-6 text-center confirm-received-button';
        buttonContainer.innerHTML = `
          <button onclick="confirmReceived({{ $order->id }})" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-200">
            <i class="fas fa-check mr-2"></i>Đã nhận hàng
          </button>
          <p class="text-sm text-gray-600 mt-2">Vui lòng xác nhận khi bạn đã nhận được hàng</p>
        `;
        document.querySelector('.container').appendChild(buttonContainer);
        // console.log removed
      } else if (confirmReceivedButton && newStatus !== 'shipping' && newStatus !== 'delivered') {
        confirmReceivedButton.style.display = 'none';
        // console.log removed
      }

      // Fast review button update
      const reviewButton = document.querySelector('.review-products-button');
      if (newStatus === 'completed' && !reviewButton) {
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'mt-6 text-center review-products-button';
        buttonContainer.innerHTML = `
          <button onclick="openReviewForm()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
            <i class="fas fa-star mr-2"></i>Đánh giá sản phẩm
          </button>
          <p class="text-sm text-gray-600 mt-2">Chia sẻ trải nghiệm của bạn về sản phẩm</p>
        `;
        document.querySelector('.container').appendChild(buttonContainer);
        // console.log removed
      } else if (reviewButton && newStatus !== 'completed') {
        reviewButton.style.display = 'none';
        // console.log removed
      }

      // Fast return/exchange section update
      const returnSection = document.querySelector('[data-section="return-exchange"]');
      if (newStatus === 'completed' && !returnSection) {
        const returnContainer = document.createElement('div');
        returnContainer.className = 'bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 mx-auto';
        returnContainer.style.maxWidth = '54rem';
        returnContainer.setAttribute('data-section', 'return-exchange');
        
        // Kiểm tra xem đơn hàng đã có yêu cầu đổi hoàn hàng chưa
        const hasReturnRequest = false; // Sẽ được cập nhật từ server
        const deadline = new Date();
        deadline.setDate(deadline.getDate() + 7);
        const canReturn = true; // Sẽ được cập nhật từ server
        
        if (hasReturnRequest) {
          returnContainer.innerHTML = `
            <h3 class="font-medium mb-4 flex items-center">
              <i class="fas fa-exchange-alt mr-2 text-orange-500"></i>
              Đổi hoàn hàng
            </h3>
            <div class="space-y-4">
              <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <span class="text-blue-800 font-medium">Yêu cầu đổi hoàn hàng</span>
                  </div>
                  <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    Chờ xử lý
                  </span>
                </div>
              </div>
            </div>
          `;
        } else if (canReturn) {
          returnContainer.innerHTML = `
            <h3 class="font-medium mb-4 flex items-center">
              <i class="fas fa-exchange-alt mr-2 text-orange-500"></i>
              Đổi hoàn hàng
            </h3>
            <div class="text-center py-8">
              <i class="fas fa-exchange-alt text-4xl text-orange-300 mb-4"></i>
              <h4 class="text-lg font-medium text-gray-900 mb-2">Bạn có muốn đổi hoàn hàng?</h4>
              <p class="text-gray-600 mb-4">Nếu sản phẩm có vấn đề, bạn có thể yêu cầu đổi hoàn hàng trong vòng 7 ngày kể từ ngày hoàn thành đơn hàng</p>
              <div class="flex justify-center space-x-4">
                <a href="/order/return/{{ $order->id }}/form" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition duration-200">
                  <i class="fas fa-exchange-alt mr-2"></i>Yêu cầu đổi hoàn hàng
                </a>
              </div>
              <p class="text-xs text-gray-500 mt-3">
                <i class="fas fa-clock mr-1"></i>
                Hạn cuối: ${deadline.toLocaleDateString('vi-VN')} ${deadline.toLocaleTimeString('vi-VN')}
              </p>
            </div>
          `;
        } else {
          returnContainer.innerHTML = `
            <h3 class="font-medium mb-4 flex items-center">
              <i class="fas fa-exchange-alt mr-2 text-orange-500"></i>
              Đổi hoàn hàng
            </h3>
            <div class="text-center py-8">
              <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
              <h4 class="text-lg font-medium text-gray-900 mb-2">Đã hết hạn đổi hoàn hàng</h4>
              <p class="text-gray-600 mb-4">Thời gian yêu cầu đổi hoàn hàng đã kết thúc (7 ngày sau khi hoàn thành đơn hàng)</p>
              <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">
                  <i class="fas fa-info-circle mr-1"></i>
                  Hạn cuối đã qua: ${deadline.toLocaleDateString('vi-VN')} ${deadline.toLocaleTimeString('vi-VN')}
                </p>
              </div>
            </div>
          `;
        }
        
        document.querySelector('.container').appendChild(returnContainer);
        // console.log removed
      } else if (returnSection && newStatus !== 'completed') {
        returnSection.style.display = 'none';
        // console.log removed
      }
    }
    
    // Toast notification system
    function showToast(message, type = 'info') {
      // Remove existing toasts
      const existingToasts = document.querySelectorAll('.toast-notification');
      existingToasts.forEach(toast => toast.remove());
      
      // Create toast element
      const toast = document.createElement('div');
      toast.className = `toast-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
      
      const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
      const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
      
      toast.innerHTML = `
        <div class="flex items-center text-white ${bgColor} p-3 rounded-lg min-w-80">
          <i class="fas ${icon} mr-3 text-lg"></i>
          <span class="flex-1">${message}</span>
          <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
      `;
      
      document.body.appendChild(toast);
      
      // Animate in
      setTimeout(() => {
        toast.classList.remove('translate-x-full');
      }, 100);
      
      // Auto remove after 3 seconds
      setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    }

    // Ensure DOM is fully loaded before running any JavaScript
    function initializeRealtimeUpdates() {
      // console.log removed
      
      // Track current status to prevent duplicate updates
      let currentStatus = '{{ $order->status }}';
      
      // Function to wait for DOM elements to be ready with timeout
      function waitForElements() {
        return new Promise((resolve, reject) => {
          let attempts = 0;
          const maxAttempts = 50; // 5 seconds max
          
          const checkElements = () => {
            attempts++;
            // console.log removed
            
            // Try multiple selectors with priority on ID
            const statusCard = document.getElementById('order-status-card') ||
                             document.querySelector('.status-card') || 
                             document.querySelector('[class*="status-card"]') ||
                             document.querySelector('div[class*="status"]');
            
            if (statusCard) {
              // console.log removed
              // console.log removed
              // console.log removed
              resolve(statusCard);
            } else if (attempts >= maxAttempts) {
              console.error('Status card not found after', maxAttempts, 'attempts');
              console.log('Available elements with "status":', document.querySelectorAll('[class*="status"]'));
              console.log('Available divs:', document.querySelectorAll('div').length);
              reject(new Error('Status card not found'));
            } else {
              console.log('Status card not found, retrying... (attempt', attempts, ')');
              setTimeout(checkElements, 100);
            }
          };
          checkElements();
        });
      }
      
      // Pusher disabled - Real-time notifications turned off
      // const pusher = new Pusher('{{ env("PUSHER_APP_KEY", "localkey123") }}', {
      //   cluster: '{{ env("PUSHER_APP_CLUSTER", "mt1") }}',
      //   encrypted: false,
      //   wsHost: '{{ env("PUSHER_HOST", "127.0.0.1") }}',
      //   wsPort: {{ env("PUSHER_PORT", 6001) }},
      //   forceTLS: false,
      //   enabledTransports: ['ws', 'wss'],
      //   // Optimize for speed
      //   activityTimeout: 30000,
      //   pongTimeout: 15000,
      //   maxReconnectionAttempts: 5,
      //   maxReconnectGap: 5000
      // });

      // Subscribe to order channel immediately (DISABLED)
      // const channel = pusher.subscribe('private-order.{{ $order->id }}');
      
      // Listen for order status updates with immediate response (DISABLED)
      // channel.bind('App\\Events\\OrderStatusUpdated', function(data) {
      //   // console.log removed
      //   // console.log removed
      //   // console.log removed
      //   if (data.status && data.status !== currentStatus) {
      //     // console.log removed
      //     currentStatus = data.status;
      //     // Wait for DOM to be ready before updating
      //     waitForElements().then(() => {
      //       updateOrderStatus(data.status, {
      //         payment_status: data.payment_status,
      //         subtotal: data.subtotal,
      //         discount_amount: data.discount_amount,
      //         shipping_fee: data.shipping_fee,
      //         total_amount: data.total_amount
      //       });
      //     }).catch(error => {
      //       console.error('Failed to update status:', error);
      //     });
      //   } else {
      //     // console.log removed
      //   }
      // });

      // Add connection status monitoring (DISABLED)
      // pusher.connection.bind('connected', function() {
      //   // console.log removed
      // });

      // pusher.connection.bind('error', function(err) {
      //   console.error('WebSocket connection error:', err);
      // });

      // pusher.connection.bind('disconnected', function() {
      //   // console.log removed
      // });

      // Enhanced polling as backup (every 1 second)
      setInterval(function() {
        fetch('{{ route("client.order.status", $order->id) }}', {
          method: 'GET',
          headers: {
            'Cache-Control': 'no-cache',
            'Pragma': 'no-cache'
          }
        })
        .then(response => response.json())
        .then(data => {
          // console.log removed
          if (data.status && data.status !== currentStatus) {
            // console.log removed
            currentStatus = data.status;
            // Wait for DOM to be ready before updating
            waitForElements().then(() => {
              updateOrderStatus(data.status, {
                payment_status: data.payment_status,
                subtotal: data.subtotal,
                discount_amount: data.discount_amount,
                shipping_fee: data.shipping_fee,
                total_amount: data.total_amount
              });
            }).catch(error => {
              console.error('Failed to update status via polling:', error);
            });
          }
        })
        .catch(error => console.log('Error checking order status:', error));
      }, 1000);

      // Immediate check on page load
      waitForElements().then(() => {
        // Initialize timeline
        updateTimeline('{{ $order->status }}');
        
        fetch('{{ route("client.order.status", $order->id) }}', {
          method: 'GET',
          headers: {
            'Cache-Control': 'no-cache',
            'Pragma': 'no-cache'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.status && data.status !== currentStatus) {
            // console.log removed
            currentStatus = data.status;
            updateOrderStatus(data.status);
          }
        })
        .catch(error => console.log('Error in initial status check:', error));
      }).catch(error => {
        console.error('Failed to initialize status check:', error);
      });
    }

    // Check for session messages and show as toasts
    @if(session('success'))
      document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('success') }}', 'success');
      });
    @endif

    @if(session('error'))
      document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('error') }}', 'error');
      });
    @endif

    @if(session('warning'))
      document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('warning') }}', 'warning');
      });
    @endif

    @if(session('info'))
      document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('info') }}', 'info');
      });
    @endif

    // Payment method selection handling
    document.addEventListener('DOMContentLoaded', function() {
      const paymentRadios = document.querySelectorAll('input[name="retry_payment_method"]');
      paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          // Remove active class from all payment method items
          document.querySelectorAll('.payment-method-item').forEach(item => {
            item.classList.remove('border-blue-500', 'bg-blue-50');
            item.classList.add('border-gray-200');
          });
          
          // Add active class to selected payment method item
          if (this.checked) {
            const paymentItem = this.closest('.payment-method-item');
            paymentItem.classList.remove('border-gray-200');
            paymentItem.classList.add('border-blue-500', 'bg-blue-50');
          }
        });
      });
      
      // Initialize first payment method as selected
      const firstRadio = document.querySelector('input[name="retry_payment_method"]');
      if (firstRadio) {
        firstRadio.checked = true;
        const paymentItem = firstRadio.closest('.payment-method-item');
        paymentItem.classList.remove('border-gray-200');
        paymentItem.classList.add('border-blue-500', 'bg-blue-50');
      }
    });

    // Multiple ways to ensure DOM is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializeRealtimeUpdates);
    } else {
      // DOM is already loaded
      initializeRealtimeUpdates();
    }

    // Backup: also try on window load
    window.addEventListener('load', function() {
      // console.log removed
      if (typeof window.realtimeInitialized === 'undefined') {
        // console.log removed
        window.realtimeInitialized = true;
        initializeRealtimeUpdates();
      }
    });

    // Function to retry payment - make it globally accessible
    window.retryPayment = function() {
        const selectedMethod = document.querySelector('input[name="retry_payment_method"]:checked');
        if (!selectedMethod) {
            showToast('Vui lòng chọn phương thức thanh toán!', 'error');
            return;
        }
        
        const paymentMethod = selectedMethod.value;
        // console.log removed
        
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
        
        // Submit via AJAX
        fetch(`/order/{{ $order->id }}/retry-payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                payment_method: paymentMethod
            })
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
                // Redirect to payment gateway
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    showToast('Thanh toán được xử lý thành công!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra khi xử lý thanh toán');
            }
        })
        .catch(error => {
            console.error('Retry payment error:', error);
            
            // Re-enable button
            button.disabled = false;
            button.innerHTML = originalText;
            
            // Show error message
            let errorMessage = 'Có lỗi xảy ra khi xử lý thanh toán';
            if (error.message) {
                if (error.message.includes('HTTP error! status: 403')) {
                    errorMessage = 'Bạn không có quyền thực hiện hành động này';
                } else if (error.message.includes('HTTP error! status: 404')) {
                    errorMessage = 'Đơn hàng không tồn tại';
                } else {
                    errorMessage = error.message;
                }
            }
            
            showToast(errorMessage, 'error');
        });
    };

    // Function to confirm received order - make it globally accessible
    window.confirmReceived = function(orderId) {
        // console.log removed
        
        if (confirm('Bạn có chắc chắn đã nhận hàng thành công?')) {
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
            
            // Submit via AJAX
            fetch(`/order/${orderId}/confirm-received`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
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
                    // Show success message
                    showSuccessMessage(data.message || '🎉 Đã xác nhận nhận hàng thành công!');
                    
                    // Update UI
                    updateOrderStatus('completed', {
                        payment_status: 'paid'
                    });
                    
                    // Hide confirm button and show review button
                    const confirmButton = document.querySelector('.confirm-received-button');
                    if (confirmButton) {
                        confirmButton.style.display = 'none';
                    }
                    
                    // Show review button
                    const reviewButton = document.querySelector('.review-products-button');
                    if (reviewButton) {
                        reviewButton.style.display = 'block';
                    }
                    
                    // Reload page after 2 seconds to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Confirm received error:', error);
                
                // Re-enable button
                button.disabled = false;
                button.innerHTML = originalText;
                
                // Show error message
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
                
                showErrorMessage('❌ ' + errorMessage);
            });
        }
    };

         // Helper functions using toast system
     function showSuccessMessage(message) {
       showToast(message, 'success');
     }

     function showErrorMessage(message) {
       showToast(message, 'error');
     }

     function showWarningMessage(message) {
       showToast(message, 'warning');
     }

     function showInfoMessage(message) {
       showToast(message, 'info');
     }
</script>



  <!-- Include Review Form -->
  @include('client.order.review-form')

</body>
</html> 
