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
                      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15); position: relative; overflow: hidden;">
            <div class="success-pattern" style="position: absolute; top: -50%; right: -20px; width: 100px; height: 200%; 
                 background: rgba(255,255,255,0.1); transform: rotate(15deg);"></div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" 
                    style="font-size: 28px; color: #155724; opacity: 0.7; position: relative; z-index: 2;">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="text-center" style="position: relative; z-index: 2;">
              <div class="success-icon mb-15">
                <i class="fa fa-check-circle" style="font-size: 60px; color: #28a745; margin-bottom: 15px; 
                   animation: bounceIn 0.8s ease-out;"></i>
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
                          <img src="{{ asset($item->product->image) }}" 
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
                            <i class="fa fa-hdd-o mr-5"></i>{{ $item->variant->storage->capacity ?? '' }}
                          </span>
                          <span class="variant-item ml-15">
                            <i class="fa fa-circle mr-5" style="color: {{ $item->variant->color->color_code ?? '#ccc' }}"></i>
                            {{ $item->variant->color->name ?? '' }}
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
                        <img src="{{ asset($item->product->image) }}" 
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
                        <small>{{ $item->variant->storage->capacity ?? '' }} - {{ $item->variant->color->name ?? '' }}</small>
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
              <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                  <div class="coupon-section text-center">
                    <h5 class="font-alt mb-15">Mã giảm giá</h5>
                    <div class="input-group">
                      <input class="form-control" type="text" id="coupon" name="coupon" 
                             placeholder="Nhập mã giảm giá"/>
                      <span class="input-group-btn">
                        <button class="btn btn-outline-secondary" type="button" id="apply-coupon">
                          <i class="fa fa-tag mr-5"></i>Áp dụng
                        </button>
                      </span>
                    </div>
                  </div>
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
  z-index: 9999;
}

.loading-spinner {
  text-align: center;
  color: white;
}

.loading-spinner i {
  font-size: 48px;
  margin-bottom: 15px;
}

/* Toast Notifications */
#toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  max-width: 400px;
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
    $('.alert-success').hide().fadeIn(800).delay(5000).fadeOut(1000, function() {
        $(this).remove();
    });
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
        const bgColor = type === 'success' ? '#d4edda' : (type === 'error' ? '#f8d7da' : '#d1ecf1');
        const borderColor = type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : '#17a2b8');
        const textColor = type === 'success' ? '#155724' : (type === 'error' ? '#721c24' : '#0c5460');
        const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle');
        
        const toast = $(`
            <div class="toast-notification" 
                 style="margin-bottom: 15px; padding: 20px; border-radius: 12px; 
                        background: ${bgColor}; border: 2px solid ${borderColor}; color: ${textColor};
                        box-shadow: 0 8px 25px rgba(0,0,0,0.15); min-width: 350px; font-size: 16px;
                        position: relative; overflow: hidden;">
                <div style="display: flex; align-items: center;">
                    <i class="fa ${icon}" style="font-size: 24px; margin-right: 15px;"></i>
                    <div style="flex: 1;">
                        <strong>${type === 'success' ? 'Thành công!' : (type === 'error' ? 'Lỗi!' : 'Thông báo!')}</strong><br>
                        ${message}
                    </div>
                    <button type="button" class="toast-close" onclick="$(this).closest('.toast-notification').fadeOut()" 
                            style="background: none; border: none; font-size: 24px; cursor: pointer; color: ${textColor}; margin-left: 10px;">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        toast.hide().slideDown(300).delay(4000).slideUp(300, function() {
            $(this).remove();
        });
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

    // Xóa item khỏi giỏ hàng
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

    // Initialize cart on page load
    setTimeout(function() {
        testCurrencyFunctions();
        updateOrderTotal();
        initializeQuantityButtons();
        
        // Test cart count on page load
        debugLog('Page loaded, testing cart count...');
        testCartCount();
    }, 100);
});
</script>

@endsection
