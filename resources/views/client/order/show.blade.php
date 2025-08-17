<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Chi tiết đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <style>
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
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="container mx-auto px-4 py-8 max-w-4xl">
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
                Đơn hàng của bạn đang được xử lý
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
                Đang xử lý
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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
            @if($detail->variant && $detail->variant->color)
              Màu: {{ $detail->variant->color->name }}
                                                                @endif
            @if($detail->variant && $detail->variant->storage)
              | Dung lượng: {{ $detail->variant->storage->capacity }}
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

    <!-- Payment Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <h3 class="font-medium mb-3">Thông tin thanh toán</h3>
      
      <!-- Order Summary -->
      <div class="mb-4">
        @php
          // Tính toán lại các giá trị để đảm bảo chính xác
          $subtotal = $order->orderDetails->sum(function($detail) {
            return $detail->price * $detail->quantity;
          });
          
          $discount_amount = $order->discount_amount ?? 0;
          $shipping_fee = $order->shipping_fee ?? 30000; // Phí vận chuyển mặc định là 30.000đ
          $total_amount = $subtotal - $discount_amount + $shipping_fee;
        @endphp
        
        <div class="flex justify-between py-2 border-b border-gray-100">
          <span>Tạm tính ({{ $order->orderDetails->count() }} sản phẩm):</span>
          <span id="order-subtotal">{{ number_format($subtotal) }}₫</span>
        </div>

        @if($order->coupon && $discount_amount > 0)
        <div class="flex justify-between py-2 border-b border-gray-100" id="discount-row">
          <span>Giảm giá ({{ $order->coupon->code }}):</span>
          <span class="text-green-600" id="order-discount">-{{ number_format($discount_amount) }}₫</span>
        </div>
        @endif
        
        <div class="flex justify-between py-2 border-b border-gray-100">
          <span>Phí vận chuyển:</span>
          <span id="order-shipping">{{ $shipping_fee > 0 ? number_format($shipping_fee) . '₫' : 'Miễn phí' }}</span>
        </div>
        
        <div class="flex justify-between py-2 font-medium text-lg border-t border-gray-200 pt-2">
          <span>Tổng thanh toán:</span>
          <span class="text-blue-600" id="order-total">{{ number_format($total_amount) }}₫</span>
        </div>

        @if($discount_amount > 0)
        <div class="mt-2 text-sm text-green-600 bg-green-50 p-2 rounded" id="discount-info">
          <i class="fas fa-gift mr-1"></i>
          Bạn đã tiết kiệm được {{ number_format($discount_amount) }}₫ với mã giảm giá {{ $order->coupon->code }}
        </div>
        @endif
      </div>
      
      <!-- Payment Details -->
      <div class="mt-4 pt-4 border-t border-gray-200">
        <h4 class="font-medium mb-3 text-sm text-gray-700">Chi tiết thanh toán</h4>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-500">Phương thức thanh toán:</span>
            <span class="font-medium" id="payment-method">
              @switch($order->payment_method)
                @case('cod')
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-money-bill-wave mr-1"></i>
                    Thanh toán khi nhận hàng (COD)
                  </span>
                  @break
                @case('vnpay')
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-credit-card mr-1"></i>
                    VNPay
                  </span>
                  @break
                @case('momo')
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                    <i class="fas fa-mobile-alt mr-1"></i>
                    MoMo
                  </span>
                  @break
                @default
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <i class="fas fa-credit-card mr-1"></i>
                    {{ $order->payment_method }}
                  </span>
              @endswitch
            </span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-500">Trạng thái thanh toán:</span>
            <span class="font-medium" id="payment-status">
              @switch($order->payment_status)
                @case('paid')
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>
                    Đã thanh toán
                  </span>
                  @break
                @case('pending')
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-1"></i>
                    Chưa thanh toán
                  </span>
                  @break
                @case('cancelled')
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-1"></i>
                    Đã hủy
                  </span>
                  @break
                @default
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <i class="fas fa-question-circle mr-1"></i>
                    {{ $order->payment_status }}
                  </span>
              @endswitch
            </span>
          </div>
        </div>
      </div>
    </div>

      <!-- Additional Info -->
      @if($order->description)
      <div class="mt-4 pt-4 border-t border-gray-200">
        <h4 class="font-medium mb-2 text-sm text-gray-700">Ghi chú đơn hàng</h4>
        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $order->description }}</p>
                                                </div>
                                            @endif
                </div>

                <!-- Order Timeline -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mt-6">
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

        <!-- Cancel Order Button -->
    @if($order->status === 'pending' && $order->payment_status === 'pending')
    <div class="mt-6 text-center cancel-order-button">
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
    <div class="mt-6 text-center review-products-button">
      <button onclick="openReviewForm()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
        <i class="fas fa-star mr-2"></i>Đánh giá sản phẩm
                                </button>
      <p class="text-sm text-gray-600 mt-2">Chia sẻ trải nghiệm của bạn về sản phẩm</p>
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
          description: 'Đơn hàng đang được xử lý và chuẩn bị',
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
              ${isActive ? '<p class="text-xs text-blue-500 mt-1 font-medium">🔄 Đang xử lý...</p>' : ''}
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
        console.log('Updating timeline for status:', status);
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
      console.log('Payment status updated to:', paymentStatus);
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
      
      console.log('Order totals updated:', data);
    }

    // Enhanced updateOrderStatus function
    function updateOrderStatus(newStatus, additionalData = {}) {
      console.log('updateOrderStatus called with:', newStatus, 'Additional data:', additionalData);
      
      // Debug: Check all elements with status-card class
      const allStatusCards = document.querySelectorAll('.status-card');
      console.log('Found status cards:', allStatusCards.length);
      allStatusCards.forEach((card, index) => {
        console.log(`Status card ${index}:`, card);
      });
      
      // More robust DOM element selection with priority on ID
      let statusCard = document.getElementById('order-status-card') || document.querySelector('.status-card');
      if (!statusCard) {
        console.error('Status card not found');
        console.log('All elements with "status" in class:', document.querySelectorAll('[class*="status"]'));
        console.log('All div elements:', document.querySelectorAll('div'));
        return;
      }
      
      console.log('Found status card:', statusCard);
      console.log('Status card ID:', statusCard.id);
      
      let statusDescription = statusCard.querySelector('p');
      let statusBadge = statusCard.querySelector('.text-right span');
      
      // Fallback: try to find elements with different selectors
      if (!statusDescription) {
        statusDescription = statusCard.querySelector('.text-left p') || statusCard.querySelector('div p');
        console.log('Found status description with fallback selector');
      }
      
      if (!statusBadge) {
        statusBadge = statusCard.querySelector('.text-right .badge') || statusCard.querySelector('span.badge') || statusCard.querySelector('.text-right span');
        console.log('Found status badge with fallback selector');
      }
      
      if (!statusDescription || !statusBadge) {
        console.error('Status description or badge not found even with fallback selectors');
        console.log('Status card HTML:', statusCard.innerHTML);
        return;
      }
      
      console.log('Updating UI elements...');
      
      // Update status card immediately
      statusCard.className = statusCard.className.replace(/status-\w+/g, '') + ` status-${newStatus}`;
      statusBadge.className = statusBadge.className.replace(/bg-\w+-\d+/g, '');
      
      // Fast status config lookup
      const statusConfig = {
        'pending': { text: 'Chờ xử lý', color: 'bg-blue-500', description: 'Đơn hàng của bạn đã được đặt thành công' },
        'processing': { text: 'Đang xử lý', color: 'bg-yellow-500', description: 'Đơn hàng của bạn đang được xử lý' },
        'shipping': { text: 'Đang giao hàng', color: 'bg-blue-600', description: 'Đơn hàng của bạn đang được giao' },
        'delivered': { text: 'Đã giao hàng', color: 'bg-orange-500', description: 'Đơn hàng của bạn đã được giao' },
        'received': { text: 'Đã nhận hàng', color: 'bg-purple-500', description: 'Đơn hàng của bạn đã được nhận' },
        'completed': { text: 'Hoàn thành', color: 'bg-green-500', description: 'Đơn hàng của bạn đã hoàn thành' },
        'cancelled': { text: 'Đã hủy', color: 'bg-red-500', description: 'Đơn hàng của bạn đã được hủy' }
      };
      
      const config = statusConfig[newStatus] || statusConfig['pending'];
      console.log('Status config:', config);
      
      // Update UI immediately
      statusBadge.textContent = config.text;
      statusBadge.classList.add(config.color);
      statusDescription.textContent = config.description;
      
      console.log('UI updated successfully');
      
      // Update timeline
      updateTimeline(newStatus);
      
      // Update payment status if provided
      if (additionalData.payment_status) {
        updatePaymentStatus(additionalData.payment_status);
      }
      
      // Update order totals if provided
      if (additionalData.subtotal || additionalData.discount_amount || additionalData.shipping_fee || additionalData.total_amount) {
        updateOrderTotals(additionalData);
      }
      
      // Fast button updates with more robust selectors
      const cancelButton = document.querySelector('.cancel-order-button');
      if (cancelButton && newStatus !== 'pending') {
        cancelButton.style.display = 'none';
        console.log('Cancel button hidden');
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
        console.log('Confirm received button added');
      } else if (confirmReceivedButton && newStatus !== 'shipping' && newStatus !== 'delivered') {
        confirmReceivedButton.style.display = 'none';
        console.log('Confirm received button hidden');
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
        console.log('Review button added');
      } else if (reviewButton && newStatus !== 'completed') {
        reviewButton.style.display = 'none';
        console.log('Review button hidden');
      }
    }
    
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
        <div class="flex items-center text-white ${bgColor} p-3 rounded-lg">
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

    // Ensure DOM is fully loaded before running any JavaScript
    function initializeRealtimeUpdates() {
      console.log('Initializing realtime updates for order {{ $order->id }}');
      
      // Track current status to prevent duplicate updates
      let currentStatus = '{{ $order->status }}';
      
      // Function to wait for DOM elements to be ready with timeout
      function waitForElements() {
        return new Promise((resolve, reject) => {
          let attempts = 0;
          const maxAttempts = 50; // 5 seconds max
          
          const checkElements = () => {
            attempts++;
            console.log(`Attempt ${attempts}: Checking for status card...`);
            
            // Try multiple selectors with priority on ID
            const statusCard = document.getElementById('order-status-card') ||
                             document.querySelector('.status-card') || 
                             document.querySelector('[class*="status-card"]') ||
                             document.querySelector('div[class*="status"]');
            
            if (statusCard) {
              console.log('Status card found:', statusCard);
              console.log('Status card ID:', statusCard.id);
              console.log('Status card classes:', statusCard.className);
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
      
      // Initialize Pusher with optimized settings for minimal latency
      const pusher = new Pusher('{{ env("PUSHER_APP_KEY", "localkey123") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER", "mt1") }}',
        encrypted: false,
        wsHost: '{{ env("PUSHER_HOST", "127.0.0.1") }}',
        wsPort: {{ env("PUSHER_PORT", 6001) }},
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        // Optimize for speed
        activityTimeout: 30000,
        pongTimeout: 15000,
        maxReconnectionAttempts: 5,
        maxReconnectGap: 5000
      });

      // Subscribe to order channel immediately
      const channel = pusher.subscribe('private-order.{{ $order->id }}');
      
      // Listen for order status updates with immediate response
      channel.bind('App\\Events\\OrderStatusUpdated', function(data) {
        console.log('Order status updated via WebSocket:', data);
        console.log('Current order status: {{ $order->status }}');
        console.log('New status from WebSocket:', data.status);
        if (data.status && data.status !== currentStatus) {
          console.log('Updating status from', currentStatus, 'to', data.status);
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
            console.error('Failed to update status:', error);
          });
        } else {
          console.log('No status update needed - same status or no status data');
        }
      });

      // Add connection status monitoring
      pusher.connection.bind('connected', function() {
        console.log('WebSocket connected successfully');
      });

      pusher.connection.bind('error', function(err) {
        console.error('WebSocket connection error:', err);
      });

      pusher.connection.bind('disconnected', function() {
        console.log('WebSocket disconnected');
      });

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
          console.log('Polling response:', data);
          if (data.status && data.status !== currentStatus) {
            console.log('Status update via polling:', data.status);
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
            console.log('Initial status check:', data.status);
            currentStatus = data.status;
            updateOrderStatus(data.status);
          }
        })
        .catch(error => console.log('Error in initial status check:', error));
      }).catch(error => {
        console.error('Failed to initialize status check:', error);
      });
    }

    // Multiple ways to ensure DOM is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializeRealtimeUpdates);
    } else {
      // DOM is already loaded
      initializeRealtimeUpdates();
    }

    // Backup: also try on window load
    window.addEventListener('load', function() {
      console.log('Window loaded, checking if realtime updates are initialized...');
      if (typeof window.realtimeInitialized === 'undefined') {
        console.log('Initializing realtime updates from window load...');
        window.realtimeInitialized = true;
        initializeRealtimeUpdates();
      }
    });
</script>

  <!-- Include Review Form -->
  @include('client.order.review-form')

</body>
</html> 