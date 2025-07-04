@extends('layouts.client')

@section('title', 'Giỏ Hàng')

@section('content')
<section class="module bg-light">
    <div class="container">
      <!-- Breadcrumb Navigation -->
      <div class="row mb-30">
        <div class="col-sm-12">
          <ol class="breadcrumb font-alt">
            <li><a href="{{ route('client.home') }}"><i class="fa fa-home"></i></a></li>
            <li><a href="{{ route('client.product') }}">Sản phẩm</a></li>
            <li class="active">Giỏ Hàng</li>
          </ol>
        </div>
      </div>
      <!-- Page Header -->
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center">
          <h1 class="module-title font-alt mb-30">
            <i class="fa fa-shopping-cart mr-10"></i>Giỏ Hàng
          </h1>
          <p class="lead">Xem lại và chỉnh sửa đơn hàng của bạn</p>
        </div>
      </div>
      <hr class="divider-w pt-20 mb-40">
      
      {{-- Success Message Banner --}}
      @if(session('cart_success'))
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <div class="alert alert-success alert-dismissible animate-in" 
               style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); 
                      border: none; border-radius: 15px; padding: 25px; margin: 20px 0; 
                      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15); position: relative; overflow: hidden;
                      z-index: 1050; margin-top: 30px;">
            <div class="success-pattern" style="position: absolute; top: -50%; right: -20px; width: 100px; height: 200%; 
                 background: rgba(255,255,255,0.1); transform: rotate(15deg); animation: shimmer 3s ease-in-out infinite;"></div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" 
                    style="font-size: 28px; color: #155724; opacity: 0.7; position: relative; z-index: 2;
                           transition: all 0.2s ease;">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="text-center" style="position: relative; z-index: 2;">
              <div class="success-icon mb-15">
                <i class="fa fa-check-circle" style="font-size: 60px; color: #28a745; margin-bottom: 15px; 
                   animation: iconPop 0.8s ease-out;"></i>
              </div>
              <h3 style="color: #155724; margin-bottom: 10px; font-weight: 600;">Thành công!</h3>
              <p style="font-size: 16px; color: #155724; margin: 0;">{{ session('cart_success') }}</p>
            </div>
          </div>
        </div>
      </div>
      @endif
      
      @if($cartItems->count() > 0)
      
      <!-- Cart Items Section -->
      <div class="row">
        <div class="col-lg-8 col-md-12">
          <div class="cart-section bg-white rounded-lg shadow-sm p-30 mb-30">
            <h4 class="font-alt mb-25">
              <i class="fa fa-list mr-10"></i>Sản phẩm trong giỏ hàng
              <span class="badge badge-primary ml-10">{{ $cartItems->count() }}</span>
            </h4>
            
            <!-- Desktop Table View -->
            <div class="cart-table-wrapper hidden-xs hidden-sm">
              <table class="table cart-table">
                <thead>
                  <tr>
                    <th width="100">Hình ảnh</th>
                    <th>Sản phẩm</th>
                    <th width="120" class="text-center">Giá</th>
                    <th width="100" class="text-center">Số lượng</th>
                    <th width="120" class="text-center">Tổng</th>
                    <th width="50" class="text-center">Xóa</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cartItems as $item)
                  <tr class="cart-item" data-cart-id="{{ Auth::check() ? $item->id : $item->id }}">
                    <td>
                      <div class="product-image-wrapper">
                        <a href="{{ route('client.single-product', $item->product->id) }}">
                          <img src="{{ asset('storage/' . $item->product->image) }}" 
                               alt="{{ $item->product->name }}" 
                               class="product-thumbnail"/>
                        </a>
                      </div>
                    </td>
                    <td>
                      <div class="product-info">
                        <h5 class="product-name">
                          <a href="{{ route('client.single-product', $item->product->id) }}">
                            {{ $item->product->name }}
                          </a>
                        </h5>
                        <div class="product-variants">
                          <span class="variant-item">
                            <i class="fa fa-hdd-o mr-5"></i>{{ $item->variant->storage->capacity ?? 'N/A' }}
                          </span>
                          <span class="variant-item ml-15">
                            @php
                              $colorCode = $item->variant->color->color_code ?? '#cccccc';
                              $colorName = $item->variant->color->name ?? 'Không xác định';
                              // Kiểm tra màu sáng/tối để điều chỉnh border
                              $isLightColor = false;
                              $isDarkColor = false;
                              if (preg_match('/^#[0-9A-F]{6}$/i', $colorCode)) {
                                  $hex = str_replace('#', '', $colorCode);
                                  $r = hexdec(substr($hex, 0, 2));
                                  $g = hexdec(substr($hex, 2, 2));
                                  $b = hexdec(substr($hex, 4, 2));
                                  $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                  $isLightColor = $brightness > 240;
                                  $isDarkColor = $brightness < 30;
                              }
                            @endphp
                            <span class="color-preview enhanced-color-preview" 
                                  style="background-color: {{ $colorCode }};"
                                  data-color="{{ $colorCode }}"
                                  data-brightness="{{ isset($brightness) ? $brightness : 128 }}"
                                  data-is-light="{{ $isLightColor ? 'true' : 'false' }}"
                                  data-is-dark="{{ $isDarkColor ? 'true' : 'false' }}"
                                  title="Màu: {{ $colorName }}"></span>
                            {{ $colorName }}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="price">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                    </td>
                    <td class="text-center">
                      <div class="quantity-controls">
                        <button type="button" class="btn-quantity-minus">
                          <i class="fa fa-minus"></i>
                        </button>
                        <input class="quantity-input" type="number" 
                               value="{{ $item->quantity }}" 
                               max="{{ $item->variant->stock_quantity }}" min="1"
                               data-cart-id="{{ Auth::check() ? $item->id : $item->id }}"
                               data-stock="{{ $item->variant->stock_quantity }}"
                               data-variant-id="{{ $item->variant->id }}"/>
                        <button type="button" class="btn-quantity-plus" 
                                {{ $item->quantity >= $item->variant->stock_quantity ? 'disabled' : '' }}>
                          <i class="fa fa-plus"></i>
                        </button>
                      </div>
                      @if($item->variant->stock_quantity <= 10)
                      <small class="stock-warning text-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        Chỉ còn {{ $item->variant->stock_quantity }} sản phẩm
                      </small>
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="item-total font-weight-600">
                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                      </span>
                    </td>
                    <td class="text-center">
                      <button class="btn-remove remove-item" 
                              data-cart-id="{{ Auth::check() ? $item->id : $item->id }}" 
                              title="Xóa sản phẩm">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- Mobile Card View -->
            <div class="cart-mobile-view visible-xs visible-sm">
              @foreach($cartItems as $item)
              <div class="cart-item-card" data-cart-id="{{ Auth::check() ? $item->id : $item->id }}">
                <div class="row">
                  <div class="col-xs-4">
                    <div class="product-image-wrapper">
                      <a href="{{ route('client.single-product', $item->product->id) }}">
                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                             alt="{{ $item->product->name }}" 
                             class="product-thumbnail"/>
                      </a>
                    </div>
                  </div>
                  <div class="col-xs-8">
                    <div class="product-info">
                      <h5 class="product-name">
                        <a href="{{ route('client.single-product', $item->product->id) }}">
                          {{ $item->product->name }}
                        </a>
                      </h5>
                      <div class="product-variants">
                        <small>
                          {{ $item->variant->storage->capacity ?? 'N/A' }}
                          @if($item->variant->storage->capacity && $item->variant->color->name) - @endif
                          @php
                            $mobileColorCode = $item->variant->color->color_code ?? '#cccccc';
                            $mobileColorName = $item->variant->color->name ?? 'Không xác định';
                            $mobileBrightness = 128;
                            $mobileIsLightColor = false;
                            $mobileIsDarkColor = false;
                            if (preg_match('/^#[0-9A-F]{6}$/i', $mobileColorCode)) {
                                $hex = str_replace('#', '', $mobileColorCode);
                                $r = hexdec(substr($hex, 0, 2));
                                $g = hexdec(substr($hex, 2, 2));
                                $b = hexdec(substr($hex, 4, 2));
                                $mobileBrightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                $mobileIsLightColor = $mobileBrightness > 240;
                                $mobileIsDarkColor = $mobileBrightness < 30;
                            }
                          @endphp
                          <span class="color-preview enhanced-color-preview mobile-color-preview" 
                                style="background-color: {{ $mobileColorCode }};"
                                data-color="{{ $mobileColorCode }}"
                                data-brightness="{{ $mobileBrightness }}"
                                data-is-light="{{ $mobileIsLightColor ? 'true' : 'false' }}"
                                data-is-dark="{{ $mobileIsDarkColor ? 'true' : 'false' }}"
                                title="Màu: {{ $mobileColorName }}"></span>
                          {{ $mobileColorName }}
                        </small>
                      </div>
                      <div class="price-row mt-10">
                        <span class="price">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-15">
                  <div class="col-xs-6">
                    <div class="quantity-controls-mobile">
                      <button type="button" class="btn-quantity-minus">
                        <i class="fa fa-minus"></i>
                      </button>
                      <input class="quantity-input" type="number" 
                             value="{{ $item->quantity }}" 
                             max="{{ $item->variant->stock_quantity }}" min="1"
                             data-cart-id="{{ Auth::check() ? $item->id : $item->id }}"
                             data-stock="{{ $item->variant->stock_quantity }}"
                             data-variant-id="{{ $item->variant->id }}"/>
                      <button type="button" class="btn-quantity-plus"
                              {{ $item->quantity >= $item->variant->stock_quantity ? 'disabled' : '' }}>
                        <i class="fa fa-plus"></i>
                      </button>
                    </div>
                    @if($item->variant->stock_quantity <= 10)
                    <small class="stock-warning text-warning mt-5">
                      <i class="fa fa-exclamation-triangle"></i>
                      Còn {{ $item->variant->stock_quantity }} sản phẩm
                    </small>
                    @endif
                  </div>
                  <div class="col-xs-6 text-right">
                    <div class="item-actions">
                      <span class="item-total font-weight-600">
                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                      </span>
                      <button class="btn-remove remove-item ml-10" 
                              data-cart-id="{{ Auth::check() ? $item->id : $item->id }}" 
                              title="Xóa">
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
            
            <!-- Cart Actions -->
            <div class="cart-actions mt-30 pt-20 border-top">
              <div class="row">
                <div class="col-sm-12 text-center mb-20">
                  <small class="text-muted">
                    <i class="fa fa-info-circle mr-5"></i>
                    Giỏ hàng sẽ tự động cập nhật khi bạn thay đổi số lượng sản phẩm
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Order Summary Section -->
        <div class="col-lg-4 col-md-12">
          <div class="order-summary bg-white rounded-lg shadow-sm p-30 sticky-summary">
            <h4 class="font-alt mb-25">
              <i class="fa fa-calculator mr-10"></i>Tóm tắt đơn hàng
            </h4>
            
            <div class="summary-details">
              <div class="summary-row">
                <span>Tạm tính:</span>
                <span id="subtotal" class="font-weight-600">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
              </div>
              <div class="summary-row">
                <span>Phí vận chuyển:</span>
                <span id="shipping" class="font-weight-600">{{ number_format($shipping, 0, ',', '.') }}đ</span>
              </div>
              <hr class="summary-divider">
              <div class="summary-row total-row">
                <span class="total-label">Tổng cộng:</span>
                <span id="total" class="total-amount">{{ number_format($total, 0, ',', '.') }}đ</span>
              </div>
            </div>
            
            <div class="checkout-actions mt-30">
              <a href="{{ route('client.checkout') }}" class="btn btn-primary btn-lg btn-block checkout-btn">
                <i class="fa fa-credit-card mr-10"></i>Tiến hành thanh toán
              </a>
              <a href="{{ route('client.product') }}" class="btn btn-outline-secondary btn-block mt-15">
                <i class="fa fa-arrow-left mr-5"></i>Tiếp tục mua sắm
              </a>
            </div>
            
            <!-- Trust Badges -->
            <div class="trust-badges mt-30 pt-20 border-top">
              <div class="text-center">
                <small class="text-muted">
                  <i class="fa fa-shield mr-5"></i>Thanh toán an toàn & bảo mật
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      @else
      <!-- Empty Cart State -->
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
          <div class="empty-cart text-center bg-white rounded-lg shadow-sm p-50">
            <div class="empty-cart-icon mb-30">
              <i class="fa fa-shopping-cart" style="font-size: 120px; color: #e9ecef;"></i>
            </div>
            <h3 class="mb-20">Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted mb-30">
              Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và thêm chúng vào giỏ hàng.
            </p>
            <a href="{{ route('client.product') }}" class="btn btn-primary btn-lg">
              <i class="fa fa-shopping-bag mr-10"></i>Khám phá sản phẩm
            </a>
          </div>
        </div>
      </div>
      @endif
    </div>
</section>

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
  <div class="loading-spinner">
    <i class="fa fa-spinner fa-spin"></i>
    <p>Đang xử lý...</p>
  </div>
</div>

<!-- Toast Container -->
<div id="toast-container"></div>

<!-- Custom Styles -->
<style>
/* Cart Styling */
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

.product-thumbnail {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  transition: transform 0.3s ease;
}

.product-thumbnail:hover {
  transform: scale(1.05);
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
}

.variant-item {
  display: inline-block;
}

.price {
  font-size: 16px;
  font-weight: 600;
  color: #dc3545;
}

/* Quantity Controls */
.quantity-controls {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
}

.quantity-controls-mobile {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 0;
}

.btn-quantity-minus,
.btn-quantity-plus {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-quantity-minus:hover,
.btn-quantity-plus:hover {
  background: #e9ecef;
  color: #007bff;
}

.btn-quantity-minus:disabled,
.btn-quantity-plus:disabled,
.btn-quantity-minus.disabled,
.btn-quantity-plus.disabled {
  background: #f8f9fa;
  color: #6c757d;
  cursor: not-allowed;
  opacity: 0.6;
}

.btn-quantity-minus:disabled:hover,
.btn-quantity-plus:disabled:hover,
.btn-quantity-minus.disabled:hover,
.btn-quantity-plus.disabled:hover {
  background: #f8f9fa;
  color: #6c757d;
  transform: none;
}

.quantity-input {
  width: 60px;
  height: 35px;
  text-align: center;
  border: 1px solid #dee2e6;
  border-left: none;
  border-right: none;
  background: white;
}

.quantity-input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.btn-remove {
  background: #fff5f5;
  border: 1px solid #feb2b2;
  color: #dc3545;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-remove:hover {
  background: #dc3545;
  color: white;
  transform: scale(1.1);
}

/* Mobile Cart Cards */
.cart-item-card {
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 15px;
  transition: all 0.3s ease;
}

.cart-item-card:hover {
  border-color: #007bff;
  box-shadow: 0 4px 12px rgba(0,123,255,0.15);
}

/* Order Summary */
.order-summary {
  border: 1px solid #e9ecef;
}

.sticky-summary {
  position: sticky;
  top: 20px;
}

.summary-details {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 20px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
}

.summary-divider {
  margin: 15px 0;
  border-color: #dee2e6;
}

.total-row {
  font-size: 18px;
  font-weight: 700;
}

.total-amount {
  color: #dc3545;
  font-size: 20px;
}

.checkout-btn {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  border: none;
  font-weight: 600;
  padding: 15px;
  transition: all 0.3s ease;
}

.checkout-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,123,255,0.3);
}

/* Loading Overlay */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.7);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 999999; /* Increased z-index to be above navbar and toast */
  backdrop-filter: blur(3px);
}

.loading-spinner {
  text-align: center;
  color: white;
  background: rgba(0,0,0,0.8);
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.loading-spinner i {
  font-size: 48px;
  margin-bottom: 15px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Toast Notifications */
#toast-container {
  position: fixed;
  top: 80px; /* Increased from 20px to avoid navbar */
  right: 20px;
  z-index: 99999; /* Increased z-index to ensure it's above navbar */
  max-width: 400px;
  pointer-events: none; /* Allow clicks to pass through container */
}

#toast-container .toast-notification {
  pointer-events: auto; /* Re-enable clicks on actual notifications */
}

/* Animations */
@keyframes bounceIn {
  0% { transform: scale(0.3); opacity: 0; }
  50% { transform: scale(1.05); }
  70% { transform: scale(0.9); }
  100% { transform: scale(1); opacity: 1; }
}

.animate-in {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translate3d(0, 30px, 0);
  }
  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

/* Toast Notification Animations */
@keyframes shimmer {
  0% { 
    transform: translateX(-100%) rotate(25deg); 
    opacity: 0.3;
  }
  50% { 
    opacity: 0.6;
  }
  100% { 
    transform: translateX(300%) rotate(25deg); 
    opacity: 0.3;
  }
}

@keyframes pulse {
  0%, 100% { 
    transform: scale(1);
    opacity: 0.8;
  }
  50% { 
    transform: scale(1.1);
    opacity: 1;
  }
}

@keyframes progressBar {
  0% { 
    width: 100%;
    opacity: 0.8;
  }
  100% { 
    width: 0%;
    opacity: 0.3;
  }
}

@keyframes slideInRight {
  0% {
    transform: translateX(100%);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOutRight {
  0% {
    transform: translateX(0);
    opacity: 1;
  }
  100% {
    transform: translateX(100%);
    opacity: 0;
  }
}

@keyframes iconPop {
  0% { 
    transform: scale(0.8);
  }
  50% { 
    transform: scale(1.2);
  }
  100% { 
    transform: scale(1);
  }
}

/* Responsive */
@media (max-width: 767px) {
  .module {
    padding: 40px 0;
  }
  
  .order-summary {
    margin-top: 30px;
  }
  
  .sticky-summary {
    position: static;
  }
  
  .quantity-controls-mobile .quantity-input {
    width: 50px;
  }
  
  .btn-quantity-minus,
  .btn-quantity-plus {
    width: 30px;
    height: 30px;
  }
  
  .product-thumbnail {
    width: 100%;
    height: 100px;
  }
  
  /* Toast responsive styles */
  #toast-container {
    top: 60px; /* Reduce top margin on mobile */
    right: 10px;
    left: 10px; /* Add left margin for mobile */
    max-width: none; /* Remove max-width constraint */
  }
  
  #toast-container .toast-notification {
    min-width: auto !important;
    max-width: none !important;
    width: 100% !important;
    margin-bottom: 10px;
    padding: 20px;
    font-size: 14px;
  }
  
  #toast-container .toast-notification .toast-icon i {
    font-size: 24px !important;
  }
  
  #toast-container .toast-notification .toast-close {
    width: 30px !important;
    height: 30px !important;
    font-size: 24px !important;
  }
}

/* Utility Classes */
.rounded-lg {
  border-radius: 12px !important;
}

.shadow-sm {
  box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
}

.font-weight-600 {
  font-weight: 600 !important;
}

.border-top {
  border-top: 1px solid #e9ecef !important;
}

.text-muted {
  color: #6c757d !important;
}

.badge {
  display: inline-block;
  padding: 0.25em 0.6em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.375rem;
}

.badge-primary {
  color: #fff;
  background-color: #007bff;
}

/* Stock warnings */
.stock-warning {
  display: block;
  margin-top: 5px;
  font-size: 11px;
  font-weight: 500;
}

.text-warning {
  color: #ffc107 !important;
}

/* Enhanced Color Preview */
.enhanced-color-preview {
  display: inline-block;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  margin-right: 8px;
  vertical-align: middle;
  position: relative;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid #ffffff;
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.1),
    0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Mobile version - smaller size */
.mobile-color-preview {
  width: 16px;
  height: 16px;
  margin: 0 6px;
}

/* Base hover effect */
.enhanced-color-preview:hover {
  transform: scale(1.3);
  box-shadow: 
    0 0 0 2px #ffffff,
    0 0 0 4px rgba(0, 123, 255, 0.3),
    0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 10;
}

/* Light colors styling */
.enhanced-color-preview[data-is-light="true"] {
  border: 2px solid #e0e0e0;
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.25),
    0 2px 4px rgba(0, 0, 0, 0.1);
}

.enhanced-color-preview[data-is-light="true"]:hover {
  border-color: #cccccc;
  box-shadow: 
    0 0 0 2px #cccccc,
    0 0 0 4px rgba(0, 123, 255, 0.3),
    0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Dark colors styling */
.enhanced-color-preview[data-is-dark="true"] {
  border: 2px solid #555555;
  box-shadow: 
    0 0 0 1px rgba(255, 255, 255, 0.3),
    0 2px 4px rgba(0, 0, 0, 0.3);
}

.enhanced-color-preview[data-is-dark="true"]:hover {
  border-color: #777777;
  box-shadow: 
    0 0 0 2px #777777,
    0 0 0 4px rgba(0, 123, 255, 0.4),
    0 4px 12px rgba(0, 0, 0, 0.25);
}

/* Special handling for pure white */
.enhanced-color-preview[data-color="#FFFFFF"],
.enhanced-color-preview[data-color="#ffffff"],
.enhanced-color-preview[data-color="#FFF"],
.enhanced-color-preview[data-color="#fff"] {
  border: 2px solid #ddd;
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.15),
    0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Special handling for pure black */
.enhanced-color-preview[data-color="#000000"],
.enhanced-color-preview[data-color="#000"] {
  border: 2px solid #444;
  background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%) !important;
  box-shadow: 
    0 0 0 1px rgba(255, 255, 255, 0.2),
    0 2px 6px rgba(0, 0, 0, 0.4);
}

/* Gray colors enhancement */
.enhanced-color-preview[data-color*="#808080"],
.enhanced-color-preview[data-color*="#696969"],
.enhanced-color-preview[data-color*="#D3D3D3"],
.enhanced-color-preview[data-color*="#C0C0C0"] {
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.2),
    0 2px 4px rgba(0, 0, 0, 0.15);
}

/* Add a subtle inner highlight for better visibility */
.enhanced-color-preview::before {
  content: '';
  position: absolute;
  top: 2px;
  left: 2px;
  right: 2px;
  bottom: 2px;
  border-radius: 50%;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 50%);
  pointer-events: none;
  opacity: 0.6;
}

/* Hide highlight for very light colors */
.enhanced-color-preview[data-is-light="true"]::before {
  opacity: 0.2;
}

/* Enhanced highlight for dark colors */
.enhanced-color-preview[data-is-dark="true"]::before {
  opacity: 0.8;
}

/* Animation for color preview loading */
@keyframes colorPreviewPulse {
  0%, 100% { 
    opacity: 1;
    transform: scale(1);
  }
  50% { 
    opacity: 0.8;
    transform: scale(1.05);
  }
}

/* Loading state */
.enhanced-color-preview.loading {
  animation: colorPreviewPulse 1.5s ease-in-out infinite;
  background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%), 
              linear-gradient(-45deg, #f0f0f0 25%, transparent 25%), 
              linear-gradient(45deg, transparent 75%, #f0f0f0 75%), 
              linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
  background-size: 4px 4px;
  background-position: 0 0, 0 2px, 2px -2px, -2px 0px;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Debug flag - set to true to enable debug logs in console
    const DEBUG_ENABLED = false;
    
    function debugLog(message) {
        if (DEBUG_ENABLED) {
            console.log(message);
        }
    }
    
    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Animate success banner if exists
    @if(session('cart_success'))
    $('.alert-success').hide().fadeIn(800).delay(8000).fadeOut(1000, function() {
        $(this).remove();
    });
    
    // Add hover effect to close button
    $('.alert-success .close').hover(
        function() {
            $(this).css({
                'opacity': '1',
                'transform': 'scale(1.1)',
                'background': 'rgba(255,255,255,0.2)',
                'border-radius': '50%'
            });
        },
        function() {
            $(this).css({
                'opacity': '0.7',
                'transform': 'scale(1)',
                'background': 'none'
            });
        }
    );
    @endif

    // Helper function để format currency  
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }

    // Helper function để parse currency về number
    function parseCurrency(currencyString) {
        const cleaned = currencyString.replace(/[^\d]/g, '');
        const result = parseFloat(cleaned) || 0;
        return result;
    }

    // Hiển thị toast notification
    function showToast(message, type = 'success') {
        const config = {
            success: {
                bgColor: 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)',
                borderColor: '#28a745',
                textColor: '#155724',
                icon: 'fa-check-circle',
                iconColor: '#28a745',
                title: 'Thành công!'
            },
            error: {
                bgColor: 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)',
                borderColor: '#dc3545',
                textColor: '#721c24',
                icon: 'fa-exclamation-triangle',
                iconColor: '#dc3545',
                title: 'Lỗi!'
            },
            info: {
                bgColor: 'linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%)',
                borderColor: '#17a2b8',
                textColor: '#0c5460',
                icon: 'fa-info-circle',
                iconColor: '#17a2b8',
                title: 'Thông báo!'
            }
        };
        
        const settings = config[type] || config.info;
        
        const toast = $(`
            <div class="toast-notification" 
                 style="margin-bottom: 15px; padding: 25px; border-radius: 16px; 
                        background: ${settings.bgColor}; 
                        border: 2px solid ${settings.borderColor}; 
                        color: ${settings.textColor};
                        box-shadow: 0 12px 35px rgba(0,0,0,0.15); 
                        min-width: 380px; max-width: 420px; font-size: 15px;
                        position: relative; overflow: hidden;
                        backdrop-filter: blur(10px);
                        transform: translateX(100%);
                        opacity: 0;">
                <!-- Animated pattern overlay -->
                <div class="toast-pattern" style="position: absolute; top: -50%; right: -20px; width: 80px; height: 200%; 
                     background: rgba(255,255,255,0.1); transform: rotate(25deg); animation: shimmer 2s ease-in-out infinite;"></div>
                     
                <div style="display: flex; align-items: flex-start; position: relative; z-index: 2;">
                    <div class="toast-icon" style="margin-right: 15px; margin-top: 2px;">
                        <i class="fa ${settings.icon}" 
                           style="font-size: 28px; color: ${settings.iconColor}; 
                                  animation: pulse 2s ease-in-out infinite;"></i>
                    </div>
                    <div style="flex: 1; line-height: 1.4;">
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 5px;">
                            ${settings.title}
                        </div>
                        <div style="font-size: 14px; opacity: 0.9;">
                            ${message}
                        </div>
                    </div>
                    <button type="button" class="toast-close" 
                            style="background: none; border: none; font-size: 28px; cursor: pointer; 
                                   color: ${settings.textColor}; margin-left: 15px; opacity: 0.7;
                                   transition: all 0.2s ease; border-radius: 50%; width: 35px; height: 35px;
                                   display: flex; align-items: center; justify-content: center;">
                        <span>&times;</span>
                    </button>
                </div>
                
                <!-- Progress bar -->
                <div class="toast-progress" style="position: absolute; bottom: 0; left: 0; height: 4px; 
                     background: ${settings.borderColor}; width: 100%; opacity: 0.7;
                     animation: progressBar 5s linear forwards;"></div>
            </div>
        `);
        
        // Close button hover effect
        toast.find('.toast-close').hover(
            function() {
                $(this).css({
                    'background': 'rgba(0,0,0,0.1)',
                    'opacity': '1',
                    'transform': 'scale(1.1)'
                });
            },
            function() {
                $(this).css({
                    'background': 'none',
                    'opacity': '0.7',
                    'transform': 'scale(1)'
                });
            }
        );
        
        // Close button click handler
        toast.find('.toast-close').on('click', function() {
            $(this).closest('.toast-notification').css({
                'transform': 'translateX(100%)',
                'opacity': '0'
            }).delay(300).queue(function() {
                $(this).remove();
            });
        });
        
        // Add to container and animate in
        $('#toast-container').append(toast);
        
        // Slide in animation
        setTimeout(() => {
            toast.css({
                'transform': 'translateX(0)',
                'opacity': '1',
                'transition': 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)'
            });
        }, 50);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.is(':visible')) {
                toast.css({
                    'transform': 'translateX(100%)',
                    'opacity': '0'
                }).delay(300).queue(function() {
                    $(this).remove();
                });
            }
        }, 5000);
    }

    // Show loading overlay
    function showLoading() {
        $('#loading-overlay').fadeIn(200);
    }

    // Hide loading overlay
    function hideLoading() {
        $('#loading-overlay').fadeOut(200);
    }

    // Quantity controls
    $(document).on('click', '.btn-quantity-minus', function() {
        const $input = $(this).siblings('.quantity-input');
        const currentValue = parseInt($input.val());
        if (currentValue > 1) {
            $input.val(currentValue - 1).trigger('change');
            // Enable plus button when quantity decreases
            updateQuantityButtons($input);
        }
    });

    $(document).on('click', '.btn-quantity-plus', function() {
        const $button = $(this);
        const $input = $button.siblings('.quantity-input');
        const currentValue = parseInt($input.val());
        const stock = parseInt($input.data('stock')) || 0;
        
        debugLog(`Plus clicked: current=${currentValue}, stock=${stock}`);
        
        // Kiểm tra stock trước khi tăng
        if (currentValue >= stock) {
            showToast(`Không thể tăng thêm! Chỉ còn ${stock} sản phẩm trong kho.`, 'error');
            return;
        }
        
        if (currentValue < stock) {
            $input.val(currentValue + 1).trigger('change');
            updateQuantityButtons($input);
        }
    });

    // Function to update quantity button states
    function updateQuantityButtons($input) {
        const currentValue = parseInt($input.val());
        const stock = parseInt($input.data('stock')) || 0;
        const $plusBtn = $input.siblings('.btn-quantity-plus');
        const $minusBtn = $input.siblings('.btn-quantity-minus');
        
        debugLog(`Updating buttons: current=${currentValue}, stock=${stock}`);
        
        // Update plus button
        if (currentValue >= stock) {
            $plusBtn.prop('disabled', true).addClass('disabled');
        } else {
            $plusBtn.prop('disabled', false).removeClass('disabled');
        }
        
        // Update minus button
        if (currentValue <= 1) {
            $minusBtn.prop('disabled', true).addClass('disabled');
        } else {
            $minusBtn.prop('disabled', false).removeClass('disabled');
        }
    }

    // Cập nhật số lượng khi input thay đổi
    $(document).on('change', '.quantity-input', function() {
        const $input = $(this);
        const cartId = $input.data('cart-id');
        const quantity = parseInt($input.val());
        const stock = parseInt($input.data('stock')) || 0;
        
        if (quantity < 1) {
            $input.val(1);
            updateQuantityButtons($input);
            return;
        }

        if (quantity > stock) {
            showToast(`Không thể đặt số lượng ${quantity}! Chỉ còn ${stock} sản phẩm trong kho.`, 'error');
            $input.val(stock);
            updateQuantityButtons($input);
            return;
        }

        const $row = $input.closest('tr, .cart-item-card');
        updateCartItem(cartId, quantity, $row, $input);
    });

    // Xóa sản phẩm khỏi giỏ hàng
    $(document).on('click', '.remove-item', function(e) {
        e.preventDefault();
        const cartId = $(this).data('cart-id');
        const $row = $(this).closest('tr, .cart-item-card');
        
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            removeCartItem(cartId, $row);
        }
    });

    // Cập nhật một item trong giỏ hàng
    function updateCartItem(cartId, quantity, $row, $input) {
        showLoading();
        
        $.ajax({
            url: '{{ route("client.update-cart") }}',
            method: 'POST',
            data: {
                cart_detail_id: cartId,
                quantity: quantity
            },
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    debugLog('Cart update successful, updating UI...');
                    
                    // Cập nhật tổng tiền cho item
                    const price = parseCurrency($row.find('.price').text());
                    const itemTotal = price * quantity;
                    $row.find('.item-total').text(formatCurrency(itemTotal));
                    
                    // Cập nhật button states
                    if ($input) {
                        updateQuantityButtons($input);
                    }
                    
                    // Cập nhật tổng tiền đơn hàng
                    updateOrderTotal();
                    
                    // Delay cart count update để đảm bảo database đã được cập nhật
                    setTimeout(function() {
                        debugLog('Updating navbar cart count after delay...');
                        updateNavbarCartCount();
                    }, 500);
                    
                    showToast(response.message);
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                
                // Handle stock validation errors from server
                if (xhr.status === 400 && xhr.responseJSON) {
                    const errorResponse = xhr.responseJSON;
                    showToast(errorResponse.message, 'error');
                    
                    // Reset quantity to max available if server provides it
                    if (errorResponse.max_quantity && $input) {
                        $input.val(errorResponse.max_quantity);
                        updateQuantityButtons($input);
                        
                        // Recalculate item total with corrected quantity
                        const price = parseCurrency($row.find('.price').text());
                        const correctedTotal = price * errorResponse.max_quantity;
                        $row.find('.item-total').text(formatCurrency(correctedTotal));
                        updateOrderTotal();
                    }
                } else {
                    showToast('Có lỗi xảy ra khi cập nhật giỏ hàng!', 'error');
                }
            }
        });
    }

    // Xóa item khỏi giỏ hàn
    function removeCartItem(cartId, $row) {
        showLoading();
        
        $.ajax({
            url: '{{ route("client.remove-from-cart") }}',
            method: 'POST',
            data: {
                cart_detail_id: cartId
            },
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    debugLog('Item removed successfully, starting fadeOut...');
                    
                    $row.fadeOut(400, function() {
                        debugLog('FadeOut complete, removing element...');
                        $(this).remove();
                        
                        // Cập nhật tổng tiền ngay sau khi remove element
                        setTimeout(() => {
                            updateOrderTotal();
                            
                            // Kiểm tra nếu giỏ hàng trống sau khi cập nhật
                            const remainingItems = $('.cart-item:visible, .cart-item-card:visible');
                            debugLog(`Remaining items after removal: ${remainingItems.length}`);
                            
                            if (remainingItems.length === 0) {
                                debugLog('Cart is now empty, reloading page...');
                                setTimeout(() => location.reload(), 1000);
                            }
                        }, 100);
                    });
                    
                    // Delay cart count update để đảm bảo database đã được cập nhật
                    setTimeout(function() {
                        debugLog('Updating navbar cart count after item removal...');
                        updateNavbarCartCount();
                    }, 500);
                    
                    showToast(response.message);
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function() {
                hideLoading();
                showToast('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
            }
        });
    }

    // Cập nhật số lượng giỏ hàng trong navbar
    function updateNavbarCartCount() {
        debugLog('=== UPDATING NAVBAR CART COUNT ===');
        
        // Force refresh cart count from server
        $.ajax({
            url: '{{ route("client.cart-count") }}',
            method: 'GET',
            success: function(response) {
                debugLog('Cart count from server:', response.count);
                
                // Update navbar cart count if global function available
                if (window.updateCartCount) {
                    debugLog('Using global updateCartCount function');
                    window.updateCartCount(response.count);
                } else {
                    debugLog('Using fallback cart count update');
                    // Fallback to updating any cart count elements
                    $('.cart-count, .cart-counter, #cart-count, #cartCount').each(function() {
                        debugLog('Updating element:', this, 'new count:', response.count);
                        $(this).text(response.count);
                    });
                }
                
                debugLog('Cart count update completed');
            },
            error: function(xhr, status, error) {
                debugLog('Error updating cart count:', error);
            }
        });
    }

    // Cập nhật tổng tiền đơn hàng
    function updateOrderTotal() {
        let subtotal = 0;
        let itemCount = 0;
        
        debugLog('=== DEBUG: Calculating Order Total ===');
        
        // Kiểm tra xem còn sản phẩm nào trong giỏ hàng không
        const cartItems = $('.cart-item:visible, .cart-item-card:visible');
        debugLog(`Number of visible cart items: ${cartItems.length}`);
        
        if (cartItems.length === 0) {
            debugLog('Cart is empty, setting all totals to 0');
            subtotal = 0;
        } else {
            // Tính tổng từ tất cả item-total có thể nhìn thấy
            cartItems.each(function(index) {
                const $itemTotal = $(this).find('.item-total');
                if ($itemTotal.length > 0) {
                    const itemText = $itemTotal.text();
                    const itemTotal = parseCurrency(itemText);
                    debugLog(`Item ${index + 1}: "${itemText}" -> ${itemTotal}`);
                    subtotal += itemTotal;
                    itemCount++;
                }
            });
        }
        
        const shipping = subtotal > 0 ? 30000 : 0;
        const total = subtotal + shipping;
        
        debugLog(`Items counted: ${itemCount}, Subtotal: ${subtotal}, Shipping: ${shipping}, Total: ${total}`);
        debugLog('=====================================');
        
        // Cập nhật UI
        if (cartItems.length === 0) {
            resetTotalsToZero();
        } else {
            animateNumber($('#subtotal'), subtotal);
            animateNumber($('#shipping'), shipping);
            animateNumber($('#total'), total);
        }
    }

    // Reset totals to zero
    function resetTotalsToZero() {
        debugLog('Resetting all totals to zero...');
        $('#subtotal').text(formatCurrency(0));
        $('#shipping').text(formatCurrency(0));
        $('#total').text(formatCurrency(0));
    }

    // Animate number changes
    function animateNumber($element, targetValue) {
        // Stop any existing animations first
        $element.stop(true, true);
        
        const currentValue = parseCurrency($element.text());
        
        // If values are the same, no need to animate
        if (currentValue === targetValue) {
            $element.text(formatCurrency(targetValue));
            return;
        }
        
        $({ value: currentValue }).animate({ value: targetValue }, {
            duration: 300,
            step: function() {
                $element.text(formatCurrency(Math.round(this.value)));
            },
            complete: function() {
                $element.text(formatCurrency(targetValue));
            }
        });
    }

    // Áp dụng mã giảm giá
    $('#apply-coupon').on('click', function() {
        const couponCode = $('#coupon').val().trim();
        const $button = $(this);
        const originalText = $button.html();
        
        if (couponCode === '') {
            showToast('Vui lòng nhập mã giảm giá!', 'error');
            return;
        }
        
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-5"></i>Đang kiểm tra...');
        
        // Simulate API call (replace with actual implementation)
        setTimeout(() => {
            $button.prop('disabled', false).html(originalText);
            showToast('Tính năng mã giảm giá sẽ được cập nhật sớm!', 'info');
        }, 1500);
    });

    // Initialize tooltips (if using Bootstrap)
    if (typeof $().tooltip === 'function') {
        $('[title]').tooltip();
    }

    // Test currency functions
    function testCurrencyFunctions() {
        if (!DEBUG_ENABLED) return;
        
        debugLog('=== TESTING CURRENCY FUNCTIONS ===');
        
        // Test round trip conversion
        const testValues = [1000000, 30000, 0];
        testValues.forEach(value => {
            const formatted = formatCurrency(value);
            const parsed = parseCurrency(formatted);
            debugLog(`Round trip: ${value} -> "${formatted}" -> ${parsed} | OK: ${value === parsed}`);
        });
        
        debugLog('===================================');
    }

    // Test cart count function
    function testCartCount() {
        debugLog('=== TESTING CART COUNT ===');
        updateNavbarCartCount();
    }
    
    // Make test function globally available
    window.testCartCount = testCartCount;
    
    // Initialize quantity button states
    function initializeQuantityButtons() {
        $('.quantity-input').each(function() {
            updateQuantityButtons($(this));
        });
    }

    // Function to enhance color preview with tooltips and accessibility
    function enhanceColorPreviews() {
        $('.enhanced-color-preview').each(function() {
            const $this = $(this);
            const colorCode = $this.data('color');
            const brightness = $this.data('brightness');
            const colorName = $this.attr('title');
            
            // Add click event to show color details
            $this.on('click', function(e) {
                e.preventDefault();
                const colorInfo = `Màu: ${colorName}\nMã màu: ${colorCode}\nĐộ sáng: ${Math.round(brightness)}`;
                showToast(colorInfo, 'info');
            });
            
            // Add keyboard accessibility
            $this.attr('tabindex', '0').attr('role', 'button');
            $this.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).click();
                }
            });
        });
    }

    // Initialize cart on page load
    setTimeout(function() {
        testCurrencyFunctions();
        updateOrderTotal();
        initializeQuantityButtons();
        enhanceColorPreviews(); // Initialize enhanced color previews
        
        // Test cart count on page load
        debugLog('Page loaded, testing cart count...');
        testCartCount();
        
        // Initialize tooltips if Bootstrap is available
        if (typeof $().tooltip === 'function') {
            $('.enhanced-color-preview').tooltip({
                placement: 'top',
                trigger: 'hover',
                delay: { show: 300, hide: 100 }
            });
        }
    }, 100);
});
</script>

@endsection
