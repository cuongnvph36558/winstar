@extends('layouts.client')

@section('title', 'Thanh Toán')

@section('css')
<link rel="stylesheet" href="{{ asset('client/assets/css/checkout-custom.css') }}">
<style>
  .alert-warning {
    border-left: 4px solid #856404;
    background-color: #fff3cd;
  }
  
  .btn-place-order.disabled {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    cursor: not-allowed !important;
    opacity: 0.6;
  }
  
  .btn-place-order.disabled:hover {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    transform: none !important;
  }
  
  .high-value-alert {
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(133, 100, 4, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(133, 100, 4, 0); }
    100% { box-shadow: 0 0 0 0 rgba(133, 100, 4, 0); }
  }
  
  /* Fix for coupon apply button */
  #apply_coupon {
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 1000 !important;
    position: relative !important;
  }
  
  #apply_coupon:disabled {
    cursor: not-allowed !important;
    opacity: 0.6 !important;
  }
  
  #apply_coupon:not(:disabled):hover {
    background-color: #0056b3 !important;
  }
  
  /* Ensure button is clickable */
  .input-group-btn .btn {
    cursor: pointer !important;
    pointer-events: auto !important;
  }
  

  
  /* Force show container */
  .container {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 15px !important;
  }
  
  /* Force show checkout section */
  .checkout-section {
    background: #fff;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    min-height: 100vh !important;
    padding: 40px 0 !important;
  }
  
  /* Ensure form rows display correctly */
  .form-row {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 15px !important;
    margin-bottom: 15px !important;
  }
  
  .form-row .form-group {
    flex: 1 !important;
    min-width: 200px !important;
  }
  
  .form-row .form-group.full-width {
    flex: 1 1 100% !important;
  }
</style>
@endsection

@section('content')
<section class="checkout-section">
  <div class="container">
    <!-- Header Section -->
    <div class="checkout-header">
    <div class="row">
      <div class="col-sm-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ route('client.home') }}">
                  <i class="fa fa-home"></i> Trang chủ
                </a>
              </li>
              <li class="breadcrumb-item">
                <a href="{{ route('client.product') }}">
                  <i class="fa fa-shopping-bag"></i> Sản phẩm
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                <i class="fa fa-credit-card"></i> Thanh toán
              </li>
        </ol>
          </nav>
      </div>
    </div>
      
    <div class="row">
        <div class="col-sm-12 text-center">
          <h1 class="checkout-title">
            <i class="fa fa-shopping-cart mr-3"></i>
            Thanh Toán Đơn Hàng
          </h1>
          <p class="checkout-subtitle">Hoàn tất thông tin để đặt hàng thành công</p>
      </div>
    </div>
    </div>

    <!-- Progress Steps -->
    <div class="checkout-progress mb-4" style="display: block !important; visibility: visible !important;">
      <div class="progress-steps" style="display: flex !important; visibility: visible !important;">
        <div class="step active" style="display: flex !important; visibility: visible !important;">
          <div class="step-icon" style="display: flex !important; visibility: visible !important;">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <div class="step-label" style="display: block !important; visibility: visible !important;">Giỏ hàng</div>
        </div>
        <div class="step active" style="display: flex !important; visibility: visible !important;">
          <div class="step-icon" style="display: flex !important; visibility: visible !important;">
            <i class="fa fa-credit-card"></i>
          </div>
          <div class="step-label" style="display: block !important; visibility: visible !important;">Thanh toán</div>
        </div>
        <div class="step" style="display: flex !important; visibility: visible !important;">
          <div class="step-icon" style="display: flex !important; visibility: visible !important;">
            <i class="fa fa-check"></i>
          </div>
          <div class="step-label" style="display: block !important; visibility: visible !important;">Hoàn thành</div>
        </div>
      </div>
    </div>

    @if($cartItems->isEmpty())
    <div class="row">
      <div class="col-sm-8 col-sm-offset-2">
        <div class="alert alert-warning text-center">
          <h4><i class="fa fa-exclamation-circle"></i> Giỏ hàng trống</h4>
          <p>Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.</p>
          <a href="{{ route('client.product') }}" class="btn btn-primary mt-15">
            <i class="fa fa-shopping-bag mr-5"></i>Tiếp tục mua sắm
          </a>
        </div>
      </div>
    </div>
    @else
    <form id="checkout-form" action="{{ route('client.place-order') }}" method="POST">
      @csrf
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if($isHighQuantityOrder)
        <div class="alert alert-warning high-value-alert">
          <div class="d-flex align-items-center">
            <i class="fa fa-exclamation-triangle mr-3" style="font-size: 24px; color: #856404;"></i>
            <div>
              <h5 class="alert-heading mb-2">
                <strong>Thông báo quan trọng</strong>
              </h5>
              
              @if($isHighQuantityOrder)
                <p class="mb-2">{{ $highQuantityMessage }}</p>
              @endif
              <p class="mb-0">
                <strong>Liên hệ tư vấn:</strong><br>
                <i class="fa fa-phone mr-2"></i>Hotline: 1900-xxxx<br>
                <i class="fa fa-envelope mr-2"></i>Email: support@winstar.com<br>
                <i class="fa fa-clock-o mr-2"></i>Thời gian: 8:00 - 22:00 (Thứ 2 - Chủ nhật)
              </p>
            </div>
          </div>
        </div>
      @endif
      <div class="checkout-layout">
        <div class="checkout-main">
          <!-- Danh sách sản phẩm -->
          <div class="cart-items-section">
            <div class="section-header">
              <h3 class="section-title">
                <i class="fa fa-shopping-bag mr-2"></i>
                Sản phẩm trong giỏ hàng
                <span class="item-count">({{ $cartItems->count() }} sản phẩm)</span>
              </h3>
            </div>
            
            <div class="cart-items-container">
                  @foreach($cartItems as $item)
              <div class="cart-item-card">
                <div class="item-image">
                        @if($item->product)
                    <img src="{{ \App\Helpers\ProductHelper::getProductImage($item->product) }}" 
                         alt="{{ $item->product->name }}" 
                         class="product-image">
                  @else
                    <div class="no-image">
                      <i class="fa fa-image"></i>
                    </div>
                        @endif
                </div>
                
                <div class="item-details">
                  <div class="item-info">
                    <h4 class="product-name">{{ optional($item->product)->name }}</h4>
                            @if($item->variant)
                      <div class="product-variants">
                        @if($item->variant->storage && isset($item->variant->storage->capacity))
                        <span class="variant-badge storage-variant">
                          <i class="fa fa-hdd-o"></i> {{ $item->variant->storage->capacity }}GB
                        </span>
                        @endif
                        @if($item->variant->color)
                        <span class="variant-badge color-variant">
                          <i class="fa fa-palette"></i> {{ $item->variant->color->name }}
                        </span>
                        @endif
                      </div>
                            @endif
                  </div>
                  
                  <div class="item-quantity">
                    <div class="quantity-display">
                      <span class="quantity-label">Số lượng:</span>
                      <span class="quantity-value">{{ $item->quantity }}</span>
                      </div>
                  </div>
                </div>
              </div>
                  @endforeach
            </div>
          </div>

          <!-- Form thông tin giao hàng -->
          <div class="shipping-form-section">
            <div class="section-header">
              <h3 class="section-title">
                <i class="fa fa-truck mr-2"></i>
                Thông tin giao hàng
              </h3>
              <p class="section-subtitle">Vui lòng điền đầy đủ thông tin để đảm bảo giao hàng chính xác</p>
              

            </div>
            
            <div class="form-container">
              <div class="form-row">
                <div class="form-group full-width">
                  <label for="receiver_name" class="form-label">
                    <i class="fa fa-user mr-1"></i>
                    Tên người nhận <span class="required">*</span>
                  </label>
                  <input class="form-control" 
                         id="receiver_name" 
                         type="text" 
                         name="receiver_name" 
                         value="{{ old('receiver_name', Auth::user()->name) }}" 
                         placeholder="Nhập tên người nhận hàng"
                         required />
              </div>
            </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="billing_city" class="form-label">
                    <i class="fa fa-map-marker mr-1"></i>
                    Tỉnh/Thành phố <span class="required">*</span>
                  </label>
                  <select class="form-control" 
                          id="billing_city" 
                          name="billing_city" 
                          required 
                          data-old="{{ old('billing_city', $userAddress['city'] ?? '') }}"
                          title="Chọn Tỉnh/Thành phố">
                    <option value="">Chọn Tỉnh/Thành phố</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="billing_district" class="form-label">
                    <i class="fa fa-map mr-1"></i>
                    Quận/Huyện <span class="required">*</span>
                  </label>
                  <select class="form-control" 
                          id="billing_district" 
                          name="billing_district" 
                          required 
                          disabled 
                          data-old="{{ old('billing_district', $userAddress['district'] ?? '') }}"
                          title="Chọn Quận/Huyện">
                    <option value="">Chọn Quận/Huyện</option>
                  </select>
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="billing_ward" class="form-label">
                    <i class="fa fa-map-pin mr-1"></i>
                    Phường/Xã <span class="required">*</span>
                  </label>
                  <select class="form-control" 
                          id="billing_ward" 
                          name="billing_ward" 
                          required 
                          disabled 
                          data-old="{{ old('billing_ward', $userAddress['ward'] ?? '') }}"
                          title="Chọn Phường/Xã">
                    <option value="">Chọn Phường/Xã</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="billing_address" class="form-label">
                    <i class="fa fa-home mr-1"></i>
                    Số nhà, tên đường <span class="required">*</span>
                  </label>
                  <input class="form-control" 
                         id="billing_address" 
                         type="text" 
                         name="billing_address" 
                         value="{{ old('billing_address', $userAddress['address'] ?? '') }}" 
                         placeholder="Ví dụ: Cổng Ong, Tòa nhà FPT Polytechnic"
                         required />
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="billing_phone" class="form-label">
                    <i class="fa fa-phone mr-1"></i>
                    Số điện thoại <span class="required">*</span>
                  </label>
                  <input class="form-control" 
                         id="billing_phone" 
                         type="tel" 
                         name="billing_phone" 
                         value="{{ old('billing_phone', $userAddress['phone'] ?? '') }}" 
                         placeholder="Nhập số điện thoại 10 số"
                         pattern="[0-9]{10}"
                         required />
                  <small class="form-text">Số điện thoại để liên hệ khi giao hàng</small>
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group full-width">
                  <label for="description" class="form-label">
                    <i class="fa fa-sticky-note mr-1"></i>
                    Ghi chú đơn hàng
                  </label>
                  <textarea class="form-control" 
                            id="description" 
                            name="description" 
                            rows="3"
                            placeholder="Ghi chú thêm về đơn hàng (không bắt buộc)">{{ old('description') }}</textarea>
                  <small class="form-text">Ví dụ: Giao hàng giờ hành chính, gọi điện trước khi giao...</small>
            </div>
              </div>
            </div>
          </div>
        </div>

        <div class="checkout-sidebar">
          <div class="order-summary-section">
            <!-- Phần tổng quan giỏ hàng -->
            <div class="summary-header">
              <h4 class="summary-title">
                <i class="fa fa-shopping-cart mr-2"></i>
                Đơn hàng của bạn
              </h4>
            </div>
            
            <!-- Mã giảm giá section - Đơn giản hóa -->
            <div class="coupon-section mb-15">
              <div class="coupon-header">
                <h5 class="font-alt mb-10">
                  <i class="fa fa-ticket mr-2"></i>Mã giảm giá
                </h5>
              </div>

              <!-- Applied coupon - Hiển thị khi có mã được áp dụng -->
              @if(session('coupon_code'))
                <div class="applied-coupon mb-10">
                  <div class="coupon-card coupon-applied">
                    <div class="coupon-content">
                      <div class="coupon-info">
                        <div class="coupon-code">
                          <i class="fa fa-check-circle text-success mr-2"></i>
                          <strong>{{ session('coupon_code') }}</strong>
                        </div>
                        <div class="coupon-discount text-success">
                          Giảm {{ number_format(session('discount', 0), 0, ',', '.') }}đ
                        </div>
                      </div>
                      <div class="coupon-actions">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="remove_coupon" onclick="removeCouponManually()">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              @endif

              <!-- Available coupons - Chỉ hiển thị khi chưa có mã được áp dụng -->
              @if(!session('coupon_code'))
                <div class="available-coupons mb-10">
                  @if(($availableCoupons ?? collect())->isNotEmpty())
                    <div class="coupon-selector">
                      <select class="form-control coupon-select" id="coupon_select" onchange="applySelectedCoupon()">
                        <option value="">-- Chọn mã giảm giá --</option>
                        @foreach($availableCoupons ?? [] as $coupon)
                          <option value="{{ $coupon->code }}" 
                                  data-discount-type="{{ $coupon->discount_type }}"
                                  data-discount-value="{{ $coupon->discount_value }}"
                                  data-min-order="{{ number_format($coupon->min_order_value, 0, ',', '.') }}"
                                  data-max-discount="{{ $coupon->max_discount_value ? number_format($coupon->max_discount_value, 0, ',', '.') : 'Không giới hạn' }}"
                                  data-end-date="{{ $coupon->end_date->format('d/m/Y') }}">
                            {{ $coupon->code }} - 
                            @if($coupon->discount_type == 'percentage')
                              Giảm {{ $coupon->discount_value }}%
                            @else
                              Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                            @endif
                          </option>
                        @endforeach
                      </select>

                    </div>
                  @endif
                </div>
                
                <!-- Manual coupon input -->
                <div class="manual-coupon">
                  <div class="input-group">
                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" 
                           value="{{ old('coupon_code') }}" 
                           placeholder="Nhập mã giảm giá">
                    <span class="input-group-btn">
                      <button class="btn btn-primary" type="button" id="apply_coupon" onclick="applyCouponManually()">
                        <span class="coupon-text">Áp dụng</span>
                        <i class="fa fa-spinner fa-spin coupon-loading" style="display: none;"></i>
                      </button>
                    </span>
                  </div>
                  <div id="coupon_message" class="mt-10"></div>
                </div>
              @endif
            </div>

            <!-- Points Exchange Section - Đơn giản hóa -->
            <div class="points-section mb-15">
              <div class="points-header">
                <h5 class="font-alt mb-10">
                  <i class="fa fa-star mr-2"></i>Quy đổi điểm tích lũy
                </h5>
              </div>

              <!-- Applied points - Hiển thị khi có điểm được áp dụng -->
              @if(session('points_used'))
                <div class="applied-points mb-10">
                  <div class="points-card points-applied">
                    <div class="points-content">
                      <div class="points-info">
                        <div class="points-used">
                          <i class="fa fa-check-circle text-success mr-2"></i>
                          <strong>{{ number_format(session('points_value', 0)) }} điểm</strong>
                        </div>
                        <div class="points-value text-success">
                          Giảm {{ number_format(session('points_value', 0), 0, ',', '.') }}đ
                        </div>
                      </div>
                      <div class="points-actions">
                        <button type="button" class="btn btn-sm btn-outline-danger" id="remove_points" onclick="removePointsManually()">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              @endif

              <!-- Available points - Chỉ hiển thị khi chưa có điểm được áp dụng và có điểm khả dụng -->
              @if(!session('points_used') && $availablePoints > 0)
                <div class="available-points mb-10">
                  <div class="points-info-display">
                    <div class="points-balance">
                      <strong>{{ number_format($availablePoints) }} điểm</strong>
                      <span class="text-muted">({{ number_format($pointsValue, 0, ',', '.') }}đ)</span>
                    </div>
                  </div>
                  
                  <div class="points-input mt-10">
                    <div class="input-group">
                      <input type="number" class="form-control" id="points_to_use" name="points_to_use" 
                             min="1" max="{{ min($availablePoints, $maxPointsForOrder) }}"
                             placeholder="Nhập số điểm">
                      <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="apply_points">
                          <span class="points-text">Áp dụng</span>
                          <i class="fa fa-spinner fa-spin points-loading" style="display: none;"></i>
                        </button>
                      </span>
                    </div>
                    <div id="points_message" class="mt-10"></div>
                  </div>
                </div>
              @endif
            </div>

            <div class="order-summary-content">
              <div class="summary-header">
                <h4 class="summary-title">
                  <i class="fa fa-calculator mr-2"></i>
                  Tổng quan đơn hàng
                </h4>
              </div>
              
              <table class="summary-table">
              <tbody>
                <tr>
                  <th>Tạm tính:</th>
                    <td data-subtotal="{{ $subtotal }}">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                </tr>
                <tr>
                  <th>Phí vận chuyển:</th>
                    <td>{{ number_format($shipping, 0, ',', '.') }}đ</td>
                </tr>
                @if(session('coupon_code') && session('discount', 0) > 0)
                  <tr class="discount" id="discount-row">
                  <th>Giảm giá:</th>
                    <td id="discount-amount">-{{ number_format(session('discount', 0), 0, ',', '.') }}đ</td>
                </tr>
                @endif
                @if(session('points_used') && session('points_value', 0) > 0)
                  <tr class="points-discount" id="points-discount-row">
                  <th>Giảm điểm ({{ number_format(session('points_value', 0)) }} điểm):</th>
                    <td id="points-discount-amount">-{{ number_format(session('points_value', 0), 0, ',', '.') }}đ</td>
                </tr>
                @endif
                  <tr class="total-row">
                  <th>Tổng cộng:</th>
                    <td data-total="{{ $total }}" id="total-amount">{{ number_format($total, 0, ',', '.') }}đ</td>
                </tr>
              </tbody>
            </table>
            </div>

            <!-- Payment Methods Section -->
            <div class="payment-methods-container">
              <div class="section-header">
                <h4 class="section-title">
                  <i class="fa fa-credit-card mr-2"></i>
                  Phương thức thanh toán <span style="color: #dc3545;">*</span>
                </h4>
                <p class="section-subtitle">Chọn phương thức thanh toán phù hợp</p>
              </div>
              
              @php
                $paymentOptions = \App\Helpers\PaymentHelper::getPaymentMethodOptions();
              @endphp
              
              @foreach($paymentOptions as $method => $option)
              <div class="payment-method-item">
                <div class="payment-radio">
                  <input type="radio" name="payment_method" value="{{ $option['value'] }}" id="{{ $option['value'] }}" {{ $loop->first ? 'checked' : '' }}>
                  <label for="{{ $option['value'] }}" class="payment-label">
                    <x-payment-method-display :paymentMethod="$option['value']" :showDescription="true" />
                  </label>
                </div>
              </div>
              @endforeach
            </div>

                      </div>
        </div>
      </div>

      <!-- Submit Button Section - Tách riêng -->
      <div class="submit-section">
        <div class="container">
          <div class="submit-container">
            <button class="submit-button" type="submit" id="place-order-btn">
              <span class="order-text">
                <i class="fa fa-check mr-2"></i>
                Đặt hàng ngay
              </span>
              <span class="order-loading" style="display: none;">
                <i class="fa fa-spinner fa-spin mr-2"></i>
                Đang xử lý...
              </span>
            </button>
          </div>
        </div>
      </div>
    </form>
    @endif
  </div>
</section>

@if(!$cartItems->isEmpty())
<style>
  /* Modern Checkout Styles */
  .checkout-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 40px 0;
    position: relative;
    overflow-x: hidden;
  }
  
  /* Fix container overflow */
  .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 15px;
    position: relative;
    z-index: 1;
  }

  /* Layout */
  .checkout-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 420px;
    gap: 40px;
    max-width: 1400px;
    margin: 35px auto 0;
    align-items: start;
    position: relative;
    z-index: 1;
  }
  
  /* Responsive layout */
  @media (max-width: 1200px) {
    .checkout-layout {
      grid-template-columns: 1fr;
      gap: 30px;
    }
  }
  
  @media (max-width: 768px) {
    .checkout-layout {
      gap: 20px;
      margin: 20px auto 0;
    }
  }

  .checkout-main {
    display: flex;
    flex-direction: column;
    gap: 30px;
    min-width: 0;
  }

  .checkout-sidebar {
    position: sticky;
    top: 20px;
    height: fit-content;
    min-width: 0;
  }

  .checkout-header {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  }

  .checkout-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
  }

  .checkout-subtitle {
    font-size: 1.1rem;
    color: #7f8c8d;
    margin-bottom: 0;
  }

  .breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 20px;
  }

  .breadcrumb-item a {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
  }

  .breadcrumb-item.active {
    color: #2c3e50;
    font-weight: 600;
  }

  /* Progress Steps */
  .checkout-progress {
    background: white !important;
    border-radius: 1rem !important;
    padding: 5rem !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
    position: relative;
    z-index: 2;
    margin-bottom: 4rem !important;
    border: none !important;
    display: block !important;
    visibility: visible !important;
    min-height: 200px !important;
    width: 100% !important;
  }

  .progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    flex-wrap: wrap;
    gap: 20px;
    min-height: 80px;
  }
  
  @media (max-width: 768px) {
    .progress-steps {
      justify-content: center;
      gap: 15px;
      flex-wrap: nowrap;
    }
  }

  .progress-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
  }

  .step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1;
    min-width: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    transition: all 0.3s ease;
    position: relative;
    z-index: 3;
  }

  .step.active .step-icon {
    background: linear-gradient(45deg, #3498db, #2980b9);
    color: #fff;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
  }

  .step-label {
    font-size: 14px;
    font-weight: 600;
    color: #7f8c8d;
  }

  .step.active .step-label {
    color: #2c3e50;
  }

  /* Section Styles */
  .cart-items-section,
  .shipping-form-section,
  .order-summary-section {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    height: fit-content;
    position: relative;
    z-index: 1;
  }

  .cart-items-section {
    margin-bottom: 25px;
  }

  .shipping-form-section {
    margin-bottom: 0;
    margin-top: -39px;
  }

  .section-header {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
  }

  .section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
  }

  .section-subtitle {
    color: #7f8c8d;
    margin-bottom: 0;
    font-size: 0.95rem;
  }

  .item-count {
    font-size: 0.9rem;
    color: #7f8c8d;
    font-weight: 400;
  }

  /* Cart Items */
  .cart-items-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .cart-item-card {
    display: flex;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    align-items: center;
    min-height: 100px;
  }

  .cart-item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }

  .item-image {
    flex-shrink: 0;
    margin-right: 20px;
  }

  .product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #fff;
  }

  .no-image {
    width: 80px;
    height: 80px;
    background: #e9ecef;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 24px;
  }

  .item-details {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
  }

  .item-info {
    flex: 1;
    min-width: 0;
  }

  .product-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
  }

  .product-variants {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
  }

  .variant-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
  }

  .color-variant {
    background: #e8f5e8;
    color: #27ae60;
  }

  .storage-variant {
    background: #e3f2fd;
    color: #1976d2;
  }

  .product-price {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .price-label {
    font-size: 0.9rem;
    color: #7f8c8d;
  }

  .price-value {
    font-weight: 600;
    color: #e74c3c;
  }

  .item-quantity,
  .item-total {
    text-align: center;
    min-width: 120px;
    flex-shrink: 0;
  }

  .quantity-label,
  .total-label {
    font-size: 0.9rem;
    color: #7f8c8d;
    margin-bottom: 4px;
  }

  .quantity-value {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1.1rem;
  }

  .total-value {
    font-weight: 700;
    color: #e74c3c;
    font-size: 1.2rem;
  }

  /* Form Styles */
  .form-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: start;
  }

  .form-row:last-child {
    margin-bottom: 0;
  }

  .form-group.full-width {
    grid-column: 1 / -1;
  }

  .form-group {
    min-width: 0;
  }

  .form-group select {
    min-width: 0;
    max-width: 100%;
  }

  .form-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
  }

  .required {
    color: #e74c3c;
    margin-left: 2px;
  }

  .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    width: 100%;
    box-sizing: border-box;
    overflow: visible;
    text-overflow: ellipsis;
    white-space: nowrap;
    min-height: 38px;
    background-color: #fff;
  }

  .form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
  }

  /* Fix for select dropdowns */
  select.form-control {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    min-height: 38px;
    background-color: #fff;
    border: 2px solid #e9ecef;
  }

  select.form-control option {
    white-space: normal;
    word-wrap: break-word;
    padding: 8px 12px;
    font-size: 14px;
  }

  /* Ensure dropdown shows full text */
  select.form-control:focus {
    overflow: visible;
  }

  select.form-control:focus option {
    background: white;
    color: #333;
  }

  /* Tooltip for truncated text */
  .form-control[title] {
    position: relative;
  }

  .form-control[title]:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 0;
    background: #333;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 5px;
  }

  /* Better dropdown styling */
  select.form-control {
    cursor: pointer;
  }

  select.form-control:not([size]) {
    padding-right: 12px;
    background-color: #fff;
  }

  .form-text {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 2px;
    margin-bottom: 0;
  }

  /* Adjust textarea padding */
  textarea.form-control {
    padding: 8px 12px;
    min-height: 60px;
    resize: vertical;
    white-space: normal;
  }

  /* Order Summary */
  .order-summary-section {
    position: sticky;
    top: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

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

  /* Payment Methods */
  .payment-methods-container {
    margin: 0;
    transition: all 0.3s ease;
  }
  
  .payment-methods-container.error {
    border: 2px solid #dc3545;
    background-color: #fff5f5;
    animation: shake 0.5s ease-in-out;
  }
  
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
  }

  .payment-method-item {
    margin-bottom: 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
  }

  .payment-method-item:last-child {
    margin-bottom: 0;
  }

  .payment-method-item:hover {
    border-color: #3498db;
  }

  .payment-radio {
    position: relative;
  }

  .payment-radio input[type="radio"] {
    position: absolute;
    opacity: 0;
  }

  .payment-radio input[type="radio"]:checked + .payment-label {
    border-color: #3498db;
    background: #f8f9ff;
  }

  .payment-label {
    display: flex;
    align-items: center;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    border-radius: 10px;
  }

  .payment-icon {
    width: 40px;
    height: 40px;
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #3498db;
  }

  .payment-logo img {
    width: 40px;
    height: 40px;
    object-fit: contain;
  }

  .payment-info h6 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
  }

  /* Submit Button */
  .submit-button {
    width: 100%;
    padding: 15px;
    background: linear-gradient(45deg, #3498db, #2980b9);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .submit-button:hover {
    background: linear-gradient(45deg, #2980b9, #1f5f8b);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
  }



  /* Ensure consistent spacing and alignment */
  * {
    box-sizing: border-box;
  }

  /* Fix global layout issues */
  body {
    overflow-x: hidden;
  }
  

  
  /* Ensure progress steps are visible */
  .checkout-progress,
  .progress-steps,
  .step,
  .step-icon,
  .step-label {
    display: block !important;
    visibility: visible !important;
  }
  
  .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
  }

  /* Fix any potential overflow issues */
  .checkout-section {
    overflow-x: hidden;
  }

  /* Ensure proper text wrapping */
  .product-name {
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  /* Consistent button sizing */
  .submit-button {
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Submit Section - Tách riêng */
  .submit-section {
    background: transparent;
    padding: 25px 0;
    margin-top: 30px;
    position: relative;
    z-index: 10;
  }

  .submit-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .submit-button {
    width: 100%;
    max-width: 300px;
    min-height: 55px;
    font-size: 1.1rem;
    font-weight: 600;
  }

  /* Responsive Design */
  @media (max-width: 1200px) {
    .checkout-layout {
      grid-template-columns: minmax(0, 1fr) 380px;
      gap: 30px;
    }
  }

  @media (max-width: 992px) {
    .checkout-layout {
      grid-template-columns: 1fr;
      gap: 25px;
    }

    .checkout-sidebar {
      position: static;
      order: -1;
    }

    .cart-items-section,
    .shipping-form-section {
      margin-bottom: 25px;
    }
  }

  @media (max-width: 768px) {
    .checkout-title {
      font-size: 2rem;
    }

    .checkout-layout {
      gap: 20px;
    }
    
    .checkout-progress {
      padding: 3rem !important;
      margin-bottom: 2rem !important;
    }
    


    .form-row {
      grid-template-columns: 1fr;
      gap: 20px;
    }

    .item-details {
      flex-direction: column;
      align-items: flex-start;
      gap: 15px;
    }

    .item-quantity,
    .item-total {
      text-align: left;
      min-width: auto;
      width: 100%;
    }

    .order-summary-section {
      position: static;
    }

    .cart-items-section,
    .shipping-form-section,
    .order-summary-section {
      padding: 20px;
    }

    .cart-item-card {
      min-height: auto;
      padding: 15px;
    }
  }

  @media (max-width: 480px) {
    .checkout-header {
      padding: 20px;
    }
    
    .checkout-section {
      padding: 20px 0;
    }
    
    .progress-steps {
      gap: 10px;
      justify-content: space-between;
      flex-wrap: nowrap;
    }
    
    .step {
      min-width: 60px;
    }
    
    .step-icon {
      width: 40px;
      height: 40px;
    }
    
    .step-label {
      font-size: 12px;
    }

    .checkout-title {
      font-size: 1.5rem;
    }

    .cart-items-section,
    .shipping-form-section,
    .order-summary-section {
      padding: 15px;
    }

    .cart-item-card {
      padding: 15px;
    }

    .product-image {
      width: 60px;
      height: 60px;
    }

    .submit-section {
      padding: 20px 0;
      margin-top: 30px;
    }

    .submit-container {
      padding: 0 15px;
    }
  }

  .mx-1 {
    margin: 0 5px;
    color: #999;
  }

  .checkout-table {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
  }

  .checkout-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px 15px;
    font-weight: 600;
    color: #333;
  }

  .checkout-table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #eee;
  }

  .shop-Cart-totalbox {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #eee;
  }

  .shop-Cart-totalbox h4 {
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
  }

  .payment-method {
    margin: 20px 0;
    padding: 15px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
  }

  .payment-method .radio {
    margin: 10px 0;
  }

  .payment-method .radio label {
    display: block;
    padding: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .payment-method .radio label:hover {
    background: #f8f9fa;
  }

  .payment-method .radio input[type="radio"]:checked+label {
    background: #e9ecef;
    border-color: #007bff;
  }

  .btn-d {
    background: #007bff;
    color: #fff !important;
    border: none;
    transition: all 0.3s ease;
  }

  .btn-d:hover {
    background: #0056b3;
    transform: translateY(-1px);
  }

  .mb-20 {
    margin-bottom: 20px;
  }

  .mt-10 {
    margin-top: 10px;
  }

  /* Enhanced Coupon Section Styles - Compact & Simple */
  .coupon-section {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }

  .coupon-header h5 {
    color: #333;
    font-weight: 500;
    margin-bottom: 10px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 13px;
  }

  .coupon-card {
    background: #fff;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
  }

  .coupon-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .coupon-applied {
    border: 1px solid #28a745;
    background: #d4edda;
  }

  .coupon-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .coupon-info {
    flex: 1;
  }

  .coupon-code {
    font-size: 13px;
    font-weight: 500;
    color: #155724;
    margin-bottom: 4px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .coupon-discount {
    font-size: 12px;
    font-weight: 400;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .coupon-actions {
    margin-left: 15px;
  }

  .coupon-selector {
    margin-bottom: 15px;
  }

  .coupon-select {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 8px;
    font-size: 13px;
    transition: all 0.2s ease;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .coupon-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.1rem rgba(0,123,255,0.25);
  }

  .no-coupons .alert {
    border-radius: 6px;
    border: none;
    background: #d1ecf1;
    padding: 12px;
    font-size: 12px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .other-coupons {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #dee2e6;
  }

  .coupon-item-disabled {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
  }

  .coupon-item-disabled:hover {
    background: #e9ecef;
    border-color: #adb5bd;
  }

  .coupon-code-disabled {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 5px;
  }

  .coupon-details-disabled {
    color: #6c757d;
    font-size: 12px;
  }

  .manual-coupon {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
  }

  .manual-coupon .input-group {
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }

  .manual-coupon .form-control {
    border: 1px solid #dee2e6;
    border-right: none;
    font-size: 13px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 12px 15px;
    font-size: 14px;
  }

  .manual-coupon .form-control:focus {
    border-color: #007bff;
    box-shadow: none;
  }

  .manual-coupon .btn {
    border: 1px solid #007bff;
    border-left: none;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 12px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .manual-coupon .btn:hover {
    background: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
  }

  #coupon_message {
    font-size: 13px;
    padding: 8px 12px;
    border-radius: 6px;
    margin-top: 10px;
  }

  #coupon_message.success {
    color: #155724;
    background: #d4edda;
    border: 1px solid #c3e6cb;
  }

  #coupon_message.error {
    color: #721c24;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
  }

  .badge-success {
    background: #28a745;
    color: #fff;
    padding: 3px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 500;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .badge-secondary {
    background: #6c757d;
    color: #fff;
    padding: 3px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 500;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .badge-warning {
    background: #ffc107;
    color: #212529;
    padding: 3px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 500;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  /* Toast Notification Styles */
  .toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    padding: 15px 20px;
    min-width: 300px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    border-left: 4px solid #28a745;
  }

  .toast-notification.show {
    transform: translateX(0);
  }

  .toast-notification.toast-error {
    border-left-color: #dc3545;
  }

  .toast-content {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .toast-content i {
    font-size: 18px;
    color: #28a745;
  }

  .toast-error .toast-content i {
    color: #dc3545;
  }

  .toast-content span {
    font-size: 14px;
    font-weight: 500;
    color: #333;
  }

  /* Responsive Design for Coupon Section */
  @media (max-width: 768px) {
    .coupon-section {
      padding: 15px;
      margin-bottom: 15px;
    }

    .coupon-content {
      flex-direction: column;
      align-items: flex-start;
    }

    .coupon-actions {
      margin-left: 0;
      margin-top: 10px;
      width: 100%;
    }

    .coupon-actions .btn {
      width: 100%;
    }

    .manual-coupon .input-group {
      flex-direction: column;
    }

    .manual-coupon .input-group .form-control {
      border-right: 2px solid #dee2e6;
      border-bottom: none;
      border-radius: 8px 8px 0 0;
    }

    .manual-coupon .input-group .btn {
      border-left: 2px solid #007bff;
      border-top: none;
      border-radius: 0 0 8px 8px;
    }

    .coupon-item-disabled {
      padding: 8px;
      margin-bottom: 6px;
    }
  }

  /* Enhanced Payment Methods Styles */
  .payment-methods-container {
    margin: 20px 0;
  }

  .payment-method-item {
    margin-bottom: 8px;
    border: 1px solid #eee;
    border-radius: 4px;
    transition: all 0.2s ease;
  }

  .payment-method-item:hover {
    border-color: #007bff;
  }

  .payment-method-item.selected {
    border-color: #007bff;
    background: #f8f9ff;
  }

  .payment-radio {
    position: relative;
  }

  .payment-radio input[type="radio"] {
    position: absolute;
    opacity: 0;
  }

  .payment-label {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    margin: 0;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .payment-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    border-radius: 4px;
    background: #f8f9fa;
  }

  .payment-icon.payment-logo {
    background: transparent;
    padding: 2px;
  }

  .payment-icon i {
    font-size: 14px;
    color: #6c757d;
  }

  .payment-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .payment-info {
    flex: 1;
  }

  .payment-info h6 {
    margin: 0;
    font-size: 13px;
    font-weight: 500;
    color: #333;
  }

  .payment-details {
    display: none;
    padding: 10px 12px;
    border-top: 1px solid #eee;
    background: #f8f9fa;
    font-size: 13px;
  }

  .payment-details table {
    margin: 0;
  }

  .payment-details td {
    padding: 4px 0;
    border: none;
  }

  .payment-method-item.selected .payment-details {
    display: block;
  }

  /* Responsive Adjustments */
  @media (max-width: 767px) {
    .payment-label {
      padding: 8px 10px;
    }

    .payment-icon {
      width: 20px;
      height: 20px;
      margin-right: 8px;
    }

    .payment-info h6 {
      font-size: 12px;
    }

    .payment-details {
      padding: 8px 10px;
      font-size: 12px;
    }
  }

  /* Form Styling */
  .shipping-form {
    border: 1px solid #eee;
    border-radius: 8px;
  }

  /* Order Summary */
  .order-summary {
    border: 1px solid #eee;
    border-radius: 8px;
  }

  .checkout-table th {
    background: #f8f9fa;
    font-weight: 600;
  }

  .checkout-table .order-total {
    background: #f8f9fa;
    font-weight: 600;
  }

  .checkout-table .discount {
    color: #28a745;
  }

  /* Payment Methods */
  .payment-methods {
    margin-top: 30px;
  }

  .payment-method-item {
    margin-bottom: 10px;
    border: 1px solid #eee;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .payment-method-item:hover {
    border-color: #007bff;
  }

  .payment-method-item.selected {
    border-color: #007bff;
    background: #f8f9ff;
  }

  .payment-radio {
    position: relative;
  }

  .payment-radio input[type="radio"] {
    position: absolute;
    opacity: 0;
  }

  .payment-label {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    margin: 0;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .payment-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
  }

  .payment-icon i {
    font-size: 16px;
    color: #6c757d;
  }

  .payment-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .payment-info {
    flex: 1;
  }

  .payment-info h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #333;
  }

  .payment-details {
    display: none;
    padding: 15px;
    border-top: 1px solid #eee;
    background: #f8f9fa;
  }

  .payment-method-item.selected .payment-details {
    display: block;
  }

  /* Responsive */
  @media (max-width: 767px) {

    .shipping-form,
    .order-summary {
      margin-bottom: 20px;
    }

    .payment-label {
      padding: 10px;
    }

    .payment-icon {
      width: 28px;
      height: 28px;
      margin-right: 10px;
    }

    .payment-info h6 {
      font-size: 13px;
    }
  }

  /* Enhanced Points Section Styles - Compact & Simple */
  .points-section {
    background: #fff3cd;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #ffc107;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }

  .points-header h5 {
    color: #856404;
    font-weight: 500;
    margin-bottom: 10px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 13px;
  }

  .points-card {
    background: #fff;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
  }

  .points-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .points-applied {
    border: 1px solid #28a745;
    background: #d4edda;
  }

  .points-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .points-info {
    flex: 1;
  }

  .points-used {
    font-size: 13px;
    font-weight: 500;
    color: #155724;
    margin-bottom: 4px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .points-value {
    font-size: 12px;
    font-weight: 400;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .points-actions {
    margin-left: 15px;
  }

  .points-info-display {
    background: #fff;
    border-radius: 4px;
    padding: 8px;
    margin-bottom: 8px;
    border: 1px solid #dee2e6;
  }

  .points-balance {
    font-size: 12px;
    font-weight: 500;
    color: #856404;
    margin-bottom: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .points-input {
    background: #fff;
    border-radius: 6px;
    padding: 12px;
    border: 1px solid #dee2e6;
  }

  .points-input .input-group {
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }

  .points-input .form-control {
    border: 1px solid #dee2e6;
    border-right: none;
    font-size: 13px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 12px 15px;
    font-size: 14px;
  }

  .points-input .form-control:focus {
    border-color: #ffc107;
    box-shadow: none;
  }

  .points-input .btn {
    border: 1px solid #ffc107;
    border-left: none;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
    background: #ffc107;
    color: #212529;
    font-size: 12px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .points-input .btn:hover {
    background: #e0a800;
    border-color: #e0a800;
    transform: translateY(-1px);
  }

  #points_message {
    font-size: 13px;
    padding: 8px 12px;
    border-radius: 6px;
    margin-top: 10px;
  }

  #points_message.success {
    color: #155724;
    background: #d4edda;
    border: 1px solid #c3e6cb;
  }

  #points_message.error {
    color: #721c24;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
  }

  .no-points .alert {
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
  }

  /* Responsive Design for Points Section */
  @media (max-width: 768px) {
    .points-section {
      padding: 15px;
      margin-bottom: 15px;
    }

    .points-content {
      flex-direction: column;
      align-items: flex-start;
    }

    .points-actions {
      margin-left: 0;
      margin-top: 10px;
      width: 100%;
    }

    .points-actions .btn {
      width: 100%;
    }

    .points-input .input-group {
      flex-direction: column;
    }

    .points-input .input-group .form-control {
      border-right: 2px solid #dee2e6;
      border-bottom: none;
      border-radius: 8px 8px 0 0;
    }

    .points-input .input-group .btn {
      border-left: 2px solid #ffc107;
      border-top: none;
      border-radius: 0 0 8px 8px;
    }

    .points-info-display {
      padding: 10px;
    }

    .points-balance {
      font-size: 14px;
    }

    .points-rules {
      font-size: 11px;
    }
  }
</style>

<script>




  // Function để cập nhật UI điểm đã sử dụng
  function updatePointsUI(pointsUsed, pointsValueFromServer = null) {
    const pointsSection = document.querySelector('.points-section');
    if (!pointsSection) return;

    // Ẩn phần available points
    const availablePoints = pointsSection.querySelector('.available-points');
    if (availablePoints) availablePoints.style.display = 'none';

    // Sử dụng giá trị từ server nếu có,否则 tính toán (1 điểm = 1 VND)
    const pointsValue = pointsValueFromServer !== null ? pointsValueFromServer : (pointsUsed * 1);

    // Tạo phần applied points mới
    const appliedPointsHTML = `
      <div class="applied-points mb-15">
        <div class="points-card points-applied">
          <div class="points-content">
            <div class="points-info">
              <div class="points-used">
                <i class="fa fa-check-circle text-success mr-2"></i>
                <strong>Đã sử dụng ${parseInt(pointsValue).toLocaleString('vi-VN')} điểm</strong>
              </div>
              <div class="points-value text-success">
                <i class="fa fa-minus-circle mr-1"></i>
                Giảm ${pointsValue.toLocaleString('vi-VN')}đ
              </div>
            </div>
            <div class="points-actions">
              <button type="button" class="btn btn-sm btn-outline-danger" id="remove_points" onclick="removePoints()">
                <i class="fa fa-times"></i> Xóa
              </button>
            </div>
          </div>
        </div>
      </div>
    `;

    // Thêm vào đầu points section
    const pointsHeader = pointsSection.querySelector('.points-header');
    if (pointsHeader) {
      pointsHeader.insertAdjacentHTML('afterend', appliedPointsHTML);
    }

    // Cập nhật tổng tiền
    updateTotalAmount(pointsValue, 'points');
  }

  // Function để cập nhật UI mã giảm giá
  function updateCouponUI(couponCode, discount) {
    const couponSection = document.querySelector('.coupon-section');
    if (!couponSection) return;

    // Ẩn phần available coupons và manual input
    const availableCoupons = couponSection.querySelector('.available-coupons');
    const manualCoupon = couponSection.querySelector('.manual-coupon');
    
    if (availableCoupons) availableCoupons.style.display = 'none';
    if (manualCoupon) manualCoupon.style.display = 'none';

    // Tạo phần applied coupon mới
    const appliedCouponHTML = `
      <div class="applied-coupon mb-15">
        <div class="coupon-card coupon-applied">
          <div class="coupon-content">
            <div class="coupon-info">
              <div class="coupon-code">
                <i class="fa fa-check-circle text-success mr-2"></i>
                <strong>${couponCode}</strong>
              </div>
              <div class="coupon-discount text-success">
                <i class="fa fa-minus-circle mr-1"></i>
                Giảm ${parseInt(discount).toLocaleString('vi-VN')}đ
              </div>
            </div>
            <div class="coupon-actions">
              <button type="button" class="btn btn-sm btn-outline-danger" id="remove_coupon" onclick="removeCoupon()">
                <i class="fa fa-times"></i> Xóa
              </button>
            </div>
          </div>
        </div>
      </div>
    `;

    // Thêm vào đầu coupon section
    const couponHeader = couponSection.querySelector('.coupon-header');
    if (couponHeader) {
      couponHeader.insertAdjacentHTML('afterend', appliedCouponHTML);
    }

    // Cập nhật tổng tiền nếu có
    updateTotalAmount(discount);
  }

  // Function để cập nhật tổng tiền
  function updateTotalAmount(discount, type = 'coupon') {
    const subtotalElement = document.querySelector('td[data-subtotal]');
    const totalElement = document.getElementById('total-amount');
    const discountRow = document.getElementById('discount-row');
    const pointsDiscountRow = document.getElementById('points-discount-row');
    
    if (subtotalElement && totalElement) {
      const subtotal = parseFloat(subtotalElement.getAttribute('data-subtotal'));
      const shipping = 30000; // Phí vận chuyển cố định
      
      // Lấy giá trị giảm giá hiện tại
      let currentCouponDiscount = 0;
      let currentPointsDiscount = 0;
      
      if (discountRow) {
        const discountText = discountRow.querySelector('td').textContent;
        currentCouponDiscount = parseInt(discountText.replace(/[^\d]/g, '')) || 0;
      }
      
      if (pointsDiscountRow) {
        const pointsDiscountText = pointsDiscountRow.querySelector('td').textContent;
        currentPointsDiscount = parseInt(pointsDiscountText.replace(/[^\d]/g, '')) || 0;
      }
      
      // Cập nhật giá trị giảm giá theo loại
      if (type === 'coupon') {
        currentCouponDiscount = discount;
      } else if (type === 'points') {
        currentPointsDiscount = discount;
      }
      
      const newTotal = Math.max(0, subtotal + shipping - currentCouponDiscount - currentPointsDiscount);
      
      // Cập nhật tổng tiền
      totalElement.textContent = newTotal.toLocaleString('vi-VN') + 'đ';
      totalElement.setAttribute('data-total', newTotal);
      
      // Thêm hoặc cập nhật dòng giảm giá coupon
      if (currentCouponDiscount > 0) {
        if (!discountRow) {
          // Tạo dòng giảm giá coupon mới
          const discountHTML = `
            <tr class="discount" id="discount-row">
              <th>Giảm giá:</th>
              <td id="discount-amount">-${currentCouponDiscount.toLocaleString('vi-VN')}đ</td>
            </tr>
          `;
          // Tìm dòng phí vận chuyển và thêm dòng giảm giá sau nó
          const summaryTable = document.querySelector('.summary-table tbody');
          const shippingRow = summaryTable.querySelector('tr:nth-child(2)'); // Dòng thứ 2 là phí vận chuyển
          if (shippingRow) {
            shippingRow.insertAdjacentHTML('afterend', discountHTML);
          }
        } else {
          // Cập nhật dòng giảm giá coupon hiện có
          const discountAmount = document.getElementById('discount-amount');
          if (discountAmount) {
            discountAmount.textContent = '-' + currentCouponDiscount.toLocaleString('vi-VN') + 'đ';
          }
        }
      } else {
        // Xóa dòng giảm giá coupon nếu discount = 0
        if (discountRow) {
          discountRow.remove();
        }
      }
      
      // Thêm hoặc cập nhật dòng giảm điểm
      if (currentPointsDiscount > 0) {
        if (!pointsDiscountRow) {
          // Tạo dòng giảm điểm mới
          const pointsDiscountHTML = `
            <tr class="points-discount" id="points-discount-row">
              <th>Giảm điểm (${currentPointsDiscount.toLocaleString('vi-VN')} điểm):</th>
              <td id="points-discount-amount">-${currentPointsDiscount.toLocaleString('vi-VN')}đ</td>
            </tr>
          `;
          // Tìm dòng giảm giá coupon hoặc phí vận chuyển để thêm sau
          const summaryTable = document.querySelector('.summary-table tbody');
          const lastDiscountRow = summaryTable.querySelector('#discount-row');
          const insertAfterRow = lastDiscountRow || summaryTable.querySelector('tr:nth-child(2)');
          if (insertAfterRow) {
            insertAfterRow.insertAdjacentHTML('afterend', pointsDiscountHTML);
          }
        } else {
          // Cập nhật dòng giảm điểm hiện có
          const pointsDiscountRow = document.getElementById('points-discount-row');
          const pointsDiscountAmount = document.getElementById('points-discount-amount');
          if (pointsDiscountRow && pointsDiscountAmount) {
            pointsDiscountRow.querySelector('th').textContent = `Giảm điểm (${currentPointsDiscount.toLocaleString('vi-VN')} điểm):`;
            pointsDiscountAmount.textContent = '-' + currentPointsDiscount.toLocaleString('vi-VN') + 'đ';
          }
        }
      } else {
        // Xóa dòng giảm điểm nếu discount = 0
        if (pointsDiscountRow) {
          pointsDiscountRow.remove();
        }
      }
    }
  }

  // Function hiển thị toast notification
  function showToast(message, type = 'success') {
    // Tạo toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
      <div class="toast-content">
        <i class="fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
      </div>
    `;
    
    // Thêm vào body
    document.body.appendChild(toast);
    
    // Hiển thị toast
    setTimeout(() => {
      toast.classList.add('show');
    }, 100);
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => {
        document.body.removeChild(toast);
      }, 300);
    }, 3000);
  }

  // Test function để kiểm tra removeCoupon
  function testRemoveCoupon() {

    removeCoupon();
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Old values from Laravel and user address
    const oldCity = '{{ old("billing_city", $userAddress["city"] ?? "") }}';
    const oldDistrict = '{{ old("billing_district", $userAddress["district"] ?? "") }}';
    const oldWard = '{{ old("billing_ward", $userAddress["ward"] ?? "") }}';

    // Load provinces
    // Ensure all functions are globally available
    window.loadProvincesFromAPI = function() {
      return fetch('{{ asset("assets/external/data/vietnam-provinces.json") }}')
        .then(response => {

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {

          // Transform the data to match our expected format
          return data.map(province => ({
            name: province.Name,
            code: province.Id,
            districts: province.Districts.map(district => ({
              name: district.Name,
              code: district.Id,
              wards: district.Wards.map(ward => ({
                name: ward.Name,
                code: ward.Id
              }))
            }))
          }));
        });
    }
    
    window.loadProvincesFromLocal = function() {
      return fetch('{{ asset("client/assets/js/vietnam-provinces.json") }}')
        .then(response => response.json())
        .then(data => {

          // Transform the data to match our expected format
          return data.map(province => ({
            name: province.Name,
            code: province.Id,
            districts: province.Districts.map(district => ({
              name: district.Name,
              code: district.Id,
              wards: district.Wards.map(ward => ({
                name: ward.Name,
                code: ward.Id
              }))
            }))
          }));
        });
    }
    
    // Ensure populateProvinces function is globally available FIRST
    window.populateProvinces = function(data) {
      // Store the data globally for use in district/ward loading
      window.vietnamData = data;
      
      const provinceSelect = document.getElementById('billing_city');
      if (!provinceSelect) {
        return;
      }
      
      // Clear existing options except the first one
      while (provinceSelect.options.length > 1) {
        provinceSelect.remove(1);
      }
      
      // Add provinces
      data.forEach(province => {
        const option = document.createElement('option');
        option.value = province.name;
        option.textContent = province.name;
        option.dataset.code = province.code;
        provinceSelect.appendChild(option);
      });


      
      // Restore old city selection if exists
      if (oldCity) {
        provinceSelect.value = oldCity;
        provinceSelect.dispatchEvent(new Event('change')); // Trigger change event to load districts
      }
    };
    
    // Force load function
    window.forceLoadProvinces = function() {
      return window.loadProvincesFromAPI()
        .then(data => {

          window.populateProvinces(data);
        })
        .catch(error => {

          return window.loadProvincesFromLocal();
        })
        .then(data => {
          if (data) {

            window.populateProvinces(data);
          }
        })
        .catch(error => {

          // Fallback: create basic provinces list
          const basicProvinces = [
            { name: 'Hà Nội', code: '01' },
            { name: 'TP. Hồ Chí Minh', code: '79' },
            { name: 'Đà Nẵng', code: '48' },
            { name: 'Hải Phòng', code: '31' },
            { name: 'Cần Thơ', code: '92' }
          ];
          window.populateProvinces(basicProvinces);
        });
    };


    


    // Store the complete data globally
    let vietnamData = null;



    // Province change event
    document.getElementById('billing_city').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const provinceCode = selectedOption.dataset.code;
      const districtSelect = document.getElementById('billing_district');
      const wardSelect = document.getElementById('billing_ward');

      // Reset districts and wards
      districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
      wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

      if (provinceCode && window.vietnamData) {
        districtSelect.disabled = false;
        // Find the selected province and load its districts
        const province = window.vietnamData.find(p => p.code === provinceCode);
        
        if (province && province.districts) {
          province.districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district.name;
            option.textContent = district.name;
            option.dataset.code = district.code;
            districtSelect.appendChild(option);
          });

          // Restore old district selection if exists
          if (oldDistrict) {
            districtSelect.value = oldDistrict;
            districtSelect.dispatchEvent(new Event('change')); // Trigger change event to load wards
          }
        }
      } else {
        districtSelect.disabled = true;
        wardSelect.disabled = true;
      }
    });

    // District change event
    document.getElementById('billing_district').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const districtCode = selectedOption.dataset.code;
      const wardSelect = document.getElementById('billing_ward');

      // Reset wards
      wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

      if (districtCode && window.vietnamData) {
        wardSelect.disabled = false;
        // Find the selected district and load its wards
        for (const province of window.vietnamData) {
          const district = province.districts.find(d => d.code === districtCode);
          if (district && district.wards) {
            district.wards.forEach(ward => {
              const option = document.createElement('option');
              option.value = ward.name;
              option.textContent = ward.name;
              wardSelect.appendChild(option);
            });

            // Restore old ward selection if exists
            if (oldWard) {
              wardSelect.value = oldWard;
            }
            break;
          }
        }
      } else {
        wardSelect.disabled = true;
      }
    });

    // Enhanced Payment Method Handling - ADD NULL CHECKS
    const paymentMethodItems = document.querySelectorAll('.payment-method-item');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const applyButton = document.getElementById('apply_coupon');

    // Initialize payment methods - ADD NULL CHECK
    if (paymentRadios && paymentRadios.length > 0) {
      paymentRadios.forEach(radio => {
      const parentItem = radio.closest('.payment-method-item');
      const details = parentItem.querySelector('.payment-details');

      // Set initial state
      if (radio.checked) {
        parentItem.classList.add('selected');
        if (details) details.style.display = 'block';
      }

      // Add click event to radio
      radio.addEventListener('change', function() {
        // Remove selected class from all items
        paymentMethodItems.forEach(item => {
          item.classList.remove('selected');
          const itemDetails = item.querySelector('.payment-details');
          if (itemDetails) itemDetails.style.display = 'none';
        });

        // Add selected class to current item
        if (this.checked) {
          parentItem.classList.add('selected');
          if (details) {
            details.style.display = 'block';
          }
          
          // Remove error highlight when payment method is selected
          const paymentContainer = document.querySelector('.payment-methods-container');
          if (paymentContainer) {
            paymentContainer.style.border = '';
            paymentContainer.style.borderRadius = '';
          }
        }
      });

      // Add click event to label
      const label = parentItem.querySelector('.payment-label');
      label.addEventListener('click', function(e) {
        e.preventDefault();
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
      });
      });
    } else {
      // console.log removed
    }

        // Enhanced form submission with loading states - REMOVE DUPLICATE EVENT LISTENER
    // This is handled in DOMContentLoaded listener above
    /*const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
      checkoutForm.addEventListener('submit', function(e) {
        // console.log removed
        e.preventDefault();

      // Show loading state
      if (placeOrderBtn) {
        placeOrderBtn.disabled = true;
        placeOrderBtn.classList.add('loading');
      }

      // Basic validation
      let isValid = true;

      
      this.querySelectorAll('input[required], select[required]').forEach(element => {

        if (!element.value) {
          isValid = false;
          element.classList.add('is-invalid');

        } else {
          element.classList.remove('is-invalid');

        }
      });
      


      // Payment method validation
      const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');

      
      if (!selectedPaymentMethod) {
        alert('Vui lòng chọn phương thức thanh toán!');
        // Highlight payment methods section
        const paymentContainer = document.querySelector('.payment-methods-container');
        if (paymentContainer) {
          paymentContainer.style.border = '2px solid #dc3545';
          paymentContainer.style.borderRadius = '8px';
          paymentContainer.style.backgroundColor = '#fff5f5';
          
          // Add error message
          let errorMsg = paymentContainer.querySelector('.payment-error-msg');
          if (!errorMsg) {
            errorMsg = document.createElement('div');
            errorMsg.className = 'payment-error-msg';
            errorMsg.style.color = '#dc3545';
            errorMsg.style.fontSize = '14px';
            errorMsg.style.marginTop = '10px';
            errorMsg.style.fontWeight = 'bold';
            errorMsg.innerHTML = '<i class="fa fa-exclamation-circle"></i> Vui lòng chọn phương thức thanh toán!';
            paymentContainer.appendChild(errorMsg);
          }
        }
        
        // Scroll to payment methods section
        paymentContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        if (placeOrderBtn) {
          placeOrderBtn.disabled = false;
          placeOrderBtn.classList.remove('loading');
        }
        return false;
      } else {
        // Remove highlight if payment method is selected
        const paymentContainer = document.querySelector('.payment-methods-container');
        if (paymentContainer) {
          paymentContainer.style.border = '';
          paymentContainer.style.borderRadius = '';
          paymentContainer.style.backgroundColor = '';
          
          // Remove error message
          const errorMsg = paymentContainer.querySelector('.payment-error-msg');
          if (errorMsg) {
            errorMsg.remove();
          }
        }
      }

      if (!isValid) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        if (placeOrderBtn) {
          placeOrderBtn.disabled = false;
          placeOrderBtn.classList.remove('loading');
        }
        return false;
      }

      // Phone validation
      const phone = document.getElementById('billing_phone').value;

      
      if (!/^[0-9]{10}$/.test(phone)) {
        alert('Số điện thoại không hợp lệ!');
        document.getElementById('billing_phone').classList.add('is-invalid');
        if (placeOrderBtn) {
          placeOrderBtn.disabled = false;
          placeOrderBtn.classList.remove('loading');
        }
        return false;
      }

      // Get full address before submit
      const city = document.getElementById('billing_city').value;
      const district = document.getElementById('billing_district').value;
      const ward = document.getElementById('billing_ward').value;
      const street = document.getElementById('billing_address').value;



      // Create hidden input for full address
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'full_address';
      hiddenInput.value = `${street}, ${ward}, ${district}, ${city}`;
      this.appendChild(hiddenInput);
      


      // Submit form for all payment methods



      
      // Log form fields for debugging
      const formData = new FormData(this);
      for (let [key, value] of formData.entries()) {
        // console.log removed
      }
      
      // Add debug alert before submit
      // console.log removed
      
      // Show loading state
      if (placeOrderBtn) {
        const orderText = placeOrderBtn.querySelector('.order-text');
        const orderLoading = placeOrderBtn.querySelector('.order-loading');
        
        if (orderText && orderLoading) {
          orderText.style.display = 'none';
          orderLoading.style.display = 'inline-block';
        }
      }
      
            // Basic validation before submit
      const requiredFields = ['receiver_name', 'billing_city', 'billing_district', 'billing_ward', 'billing_address', 'billing_phone'];
      let hasError = false;
      
      for (const fieldName of requiredFields) {
        const field = document.getElementById(fieldName);
        // console.log removed
        if (!field || !field.value || field.value.trim() === '') {
          console.error(`Validation failed for field: ${fieldName}`, field ? `value: "${field.value}"` : 'field not found');
          hasError = true;
          if (field) field.classList.add('is-invalid');
        } else {
          if (field) field.classList.remove('is-invalid');
        }
      }
      
      // Check payment method
      const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
      // console.log removed
      if (!paymentMethod) {
        console.error('No payment method selected');
        hasError = true;
        alert('Vui lòng chọn phương thức thanh toán!');
      }
      
      if (hasError) {
        console.error('Form validation failed, preventing submission');
        if (placeOrderBtn) {
          placeOrderBtn.disabled = false;
          placeOrderBtn.classList.remove('loading');
        }
        return false;
      }
      
      // console.log removed
      
      // Submit the form
      // console.log removed
      // console.log removed
      // console.log removed
      
      try {
        this.submit();
      } catch (error) {
        console.error('Form submission error:', error);
        // Fallback: try to submit without JavaScript
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.action;
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Add all form data
        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = key;
          input.value = value;
          form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
      }
      
      // This line should not execute if form submits successfully
      // console.log removed
      // console.log removed
      });*/
          /*} else {
        // console.log removed
        console.error('Form with ID "checkout-form" not found in DOM');
        console.error('Available forms:', document.querySelectorAll('form'));
      }*/

    // Logic mới cho áp dụng mã giảm giá - FIXED VERSION
    function initializeCouponHandlers() {
      // console.log removed
      
      // Xử lý dropdown chọn mã giảm giá
      const couponSelect = document.getElementById('coupon_select');
      if (couponSelect) {
        // console.log removed
        couponSelect.addEventListener('change', function() {
          const couponInput = document.getElementById('coupon_code');
          if (couponInput) {
            couponInput.value = this.value;
          }
        });
      }

      // Xử lý nút áp dụng mã giảm giá - ADD NULL CHECK
      const applyButtonCoupon = document.getElementById('apply_coupon');
      // console.log removed
      
      if (applyButtonCoupon) {
        // Remove any existing event listeners
        const newButton = applyButtonCoupon.cloneNode(true);
        applyButtonCoupon.parentNode.replaceChild(newButton, applyButtonCoupon);
        
        // Add new event listener
        newButton.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          // console.log removed
          
          const code = document.getElementById('coupon_code').value;
          const messageDiv = document.getElementById('coupon_message');

          // console.log removed

          if (!code) {
            messageDiv.innerHTML = '<span style="color: red;">Vui lòng nhập mã giảm giá!</span>';
            return;
          }

          // Disable button và hiển thị loading
          this.disabled = true;
          this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';

          // console.log removed

          // Gửi request
          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
          if (!csrfToken) {
            console.error('CSRF token not found');
            messageDiv.innerHTML = '<span style="color: red;">Lỗi bảo mật. Vui lòng tải lại trang.</span>';
            this.disabled = false;
            this.innerHTML = '<span class="coupon-text">Áp dụng</span>';
            return;
          }
          
          fetch('{{ route("client.apply-coupon") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ coupon_code: code })
          })
          .then(response => {
            // console.log removed
            return response.json();
          })
          .then(data => {
            // console.log removed
            if (data.success) {
              messageDiv.innerHTML = '<span style="color: green;">' + data.message + '</span>';
              // Reload trang sau 1 giây
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            } else {
              messageDiv.innerHTML = '<span style="color: red;">' + data.message + '</span>';
            }
          })
          .catch(error => {
            console.error('Error:', error);
            messageDiv.innerHTML = '<span style="color: red;">Có lỗi xảy ra khi áp dụng mã giảm giá.</span>';
          })
          .finally(() => {
            // Restore button
            this.disabled = false;
            this.innerHTML = '<span class="coupon-text">Áp dụng</span>';
          });
        });
        
        // console.log removed
      } else {
        // console.log removed
      }
    }

    // Initialize on DOM ready with proper error handling
    function safeInitializeCouponHandlers() {
      try {
        initializeCouponHandlers();
      } catch (error) {
        console.error('Error initializing coupon handlers:', error);
      }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', safeInitializeCouponHandlers);
    } else {
      safeInitializeCouponHandlers();
    }



    // Enhanced points application with loading state - ADD NULL CHECK
    const applyPointsButton = document.getElementById('apply_points');
    if (applyPointsButton) {
      applyPointsButton.addEventListener('click', function() {
      const points = document.getElementById('points_to_use').value;
      const button = this;
      const messageDiv = document.getElementById('points_message');

      if (!points || points < 1 || points > {{ min($availablePoints, $maxPointsForOrder) }}) {
        messageDiv.innerHTML = '<span class="error">Vui lòng nhập số điểm hợp lệ (1 đến {{ min($availablePoints, $maxPointsForOrder) }}).</span>';
        return;
      }

      // Show loading state
      button.disabled = true;
      button.classList.add('loading');

      // Gửi request đến route client.apply-points bằng phương thức POST
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      if (!csrfToken) {
        console.error('CSRF token not found');
        messageDiv.innerHTML = '<span class="error">Lỗi bảo mật. Vui lòng tải lại trang.</span>';
        button.disabled = false;
        button.classList.remove('loading');
        return;
      }
      
      fetch('{{ route("client.apply-points") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            points_to_use: points
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            messageDiv.innerHTML = `<span class="success">${data.message}</span>`;
            
            // Cập nhật UI động thay vì reload trang
            setTimeout(() => {
              // Cập nhật giao diện với số điểm đã sử dụng từ server response
              updatePointsUI(data.points_used || points, data.points_value);
            }, 1000);
            
          } else {
            messageDiv.innerHTML = `<span class="error">${data.message}</span>`;
          }
          button.disabled = false;
          button.classList.remove('loading');
        })
        .catch(error => {
          console.error('Error applying points:', error);
          messageDiv.innerHTML = '<span class="error">Có lỗi xảy ra khi áp dụng điểm tích lũy.</span>';
          button.disabled = false;
          button.classList.remove('loading');
        });
      });
    } else {
      // console.log removed
    }



    // Add smooth scroll to error fields
    function scrollToErrorField() {
      const errorField = document.querySelector('.is-invalid');
      if (errorField) {
        errorField.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        errorField.focus();
      }
    }

    // Enhanced validation feedback - ADD NULL CHECK
    const formElements = document.querySelectorAll('input, select');
    if (formElements && formElements.length > 0) {
      formElements.forEach(element => {
      element.addEventListener('change', function() {
        this.classList.remove('is-invalid');

        // Real-time phone validation
        if (this.id === 'billing_phone') {
          const phone = this.value;
          if (phone && !/^[0-9]{10}$/.test(phone)) {
            this.classList.add('is-invalid');
          }
        }
      });

      // Add focus/blur events for better UX
      element.addEventListener('focus', function() {
        this.classList.add('focused');
      });

      element.addEventListener('blur', function() {
        this.classList.remove('focused');
      });
      });
    } else {
      // console.log removed
    }

    // Disable place order button if high quantity order
    @if($isHighQuantityOrder)
      document.addEventListener('DOMContentLoaded', function() {
        const placeOrderBtn = document.querySelector('.btn-place-order');
        if (placeOrderBtn) {
          placeOrderBtn.disabled = true;
          placeOrderBtn.classList.add('disabled');
          placeOrderBtn.title = 'Vui lòng liên hệ tư vấn cho đơn hàng có số lượng cao';
          
          // Thay đổi text của button
          const orderText = placeOrderBtn.querySelector('.order-text');
          if (orderText) {
            orderText.textContent = 'Liên hệ tư vấn';
          }
        }
      });
    @endif

    // Initialize tooltips if needed
    if (typeof bootstrap !== 'undefined') {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }

    // Debug coupon application
    // console.log removed
    
    // Check if elements exist
    const couponInput = document.getElementById('coupon_code');
    const applyButtonDebug = document.getElementById('apply_coupon');
    const messageDiv = document.getElementById('coupon_message');
    
    // console.log removed
    // console.log removed
    // console.log removed
    // console.log removed
    
    // Check CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    // console.log removed
    
    if (csrfToken) {
        // console.log removed
    }

  });




</script>
@endif
@endsection

@section('scripts')
<!-- Address handler is already included inline -->
<script>
// Ensure function is defined globally and attached to window
window.applyCouponManually = function() {
  // console.log removed
  
  const code = document.getElementById('coupon_code')?.value || '';
  const messageDiv = document.getElementById('coupon_message');
  const button = document.getElementById('apply_coupon');

  // console.log removed

  if (!code) {
    if (messageDiv) {
      messageDiv.innerHTML = '<span style="color: red;">Vui lòng nhập mã giảm giá!</span>';
    }
    return;
  }

  if (!button || !messageDiv) {
    console.error('Required elements not found for coupon application');
    return;
  }

  // Disable button và hiển thị loading
  button.disabled = true;
  button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';

  // console.log removed

  // Gửi request
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  if (!csrfToken) {
    console.error('CSRF token not found');
    messageDiv.innerHTML = '<span style="color: red;">Lỗi bảo mật. Vui lòng tải lại trang.</span>';
    button.disabled = false;
    button.innerHTML = '<span class="coupon-text">Áp dụng</span>';
    return;
  }
  
  fetch('{{ route("client.apply-coupon") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({ coupon_code: code })
  })
  .then(response => {
    // console.log removed
    return response.json();
  })
  .then(data => {
    // console.log removed
    if (data.success) {
      messageDiv.innerHTML = '<span style="color: green;">' + data.message + '</span>';
      // Reload trang sau 1 giây
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    } else {
      messageDiv.innerHTML = '<span style="color: red;">' + data.message + '</span>';
    }
  })
  .catch(error => {
    console.error('Error:', error);
    messageDiv.innerHTML = '<span style="color: red;">Có lỗi xảy ra khi áp dụng mã giảm giá.</span>';
  })
  .finally(() => {
    // Restore button
    button.disabled = false;
    button.innerHTML = '<span class="coupon-text">Áp dụng</span>';
  });
}

// console.log removed

// Function to remove coupon
window.removeCouponManually = function() {
  // console.log removed
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  if (!csrfToken) {
    console.error('CSRF token not found');
    alert('Lỗi bảo mật. Vui lòng tải lại trang.');
    return;
  }
  
  fetch('{{ route("client.remove-coupon") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken
    }
  })
  .then(response => response.json())
  .then(data => {
    // console.log removed
    if (data.success) {
      window.location.reload();
    } else {
      alert('Có lỗi xảy ra khi xóa mã giảm giá');
    }
  })
  .catch(error => {
    console.error('Error removing coupon:', error);
    alert('Có lỗi xảy ra khi xóa mã giảm giá');
  });
}

// Function to remove points
window.removePointsManually = function() {
  // console.log removed
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  if (!csrfToken) {
    console.error('CSRF token not found');
    alert('Lỗi bảo mật. Vui lòng tải lại trang.');
    return;
  }
  
  fetch('{{ route("client.remove-points") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken
    }
  })
  .then(response => response.json())
  .then(data => {
    // console.log removed
    if (data.success) {
      window.location.reload();
    } else {
      alert('Có lỗi xảy ra khi xóa điểm tích lũy');
    }
  })
  .catch(error => {
    console.error('Error removing points:', error);
    alert('Có lỗi xảy ra khi xóa điểm tích lũy');
  });
}

// console.log removed

// Function to apply selected coupon from dropdown
window.applySelectedCoupon = function() {
  // console.log removed
  
  const select = document.getElementById('coupon_select');
  const messageDiv = document.getElementById('coupon_message');
  
  if (!select || !messageDiv) {
    console.error('Required elements not found for selected coupon application');
    return;
  }
  
  const selectedCode = select.value;
  // console.log removed
  
  if (!selectedCode) {
    // console.log removed
    return;
  }
  
  // Show loading message
  messageDiv.innerHTML = '<span style="color: blue;"><i class="fa fa-spinner fa-spin"></i> Đang áp dụng mã giảm giá...</span>';
  
  // Disable select during processing
  select.disabled = true;
  
  // console.log removed
  
  // Send request
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  if (!csrfToken) {
    console.error('CSRF token not found');
    messageDiv.innerHTML = '<span style="color: red;">Lỗi bảo mật. Vui lòng tải lại trang.</span>';
    select.disabled = false;
    return;
  }
  
  fetch('{{ route("client.apply-coupon") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({ coupon_code: selectedCode })
  })
  .then(response => {
    // console.log removed
    return response.json();
  })
  .then(data => {
    // console.log removed
    if (data.success) {
      messageDiv.innerHTML = '<span style="color: green;">' + data.message + '</span>';
      // Reload page after 1 second
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    } else {
      messageDiv.innerHTML = '<span style="color: red;">' + data.message + '</span>';
      // Reset select to empty
      select.value = '';
    }
  })
  .catch(error => {
    console.error('Error:', error);
    messageDiv.innerHTML = '<span style="color: red;">Có lỗi xảy ra khi áp dụng mã giảm giá.</span>';
    // Reset select to empty
    select.value = '';
  })
  .finally(() => {
    // Re-enable select
    select.disabled = false;
  });
}

// console.log removed

          // Force load provinces immediately
      if (typeof window.forceLoadProvinces === 'function') {
        // console.log removed
        window.forceLoadProvinces().then(() => {
          // console.log removed
        }).catch(error => {
          console.error('Failed to load provinces:', error);
          // Fallback: try to load basic provinces
          if (typeof window.loadProvincesFromLocal === 'function') {
            window.loadProvincesFromLocal().then(data => {
              if (data && typeof window.populateProvinces === 'function') {
                window.populateProvinces(data);
              }
            }).catch(e => {
              console.error('Failed to load local provinces:', e);
            });
          }
        });
      } else {
        console.error('forceLoadProvinces function not found');
        // Try to load directly
        if (typeof window.loadProvincesFromAPI === 'function') {
          window.loadProvincesFromAPI().then(data => {
            if (data && typeof window.populateProvinces === 'function') {
              window.populateProvinces(data);
            }
          }).catch(error => {
            console.error('Failed to load provinces from API:', error);
          });
        }
      }

    // Initialize form
    document.addEventListener('DOMContentLoaded', function() {
      // Add form submit listener for debugging
      const form = document.getElementById('checkout-form');
      if (form) {
        // console.log removed
        form.addEventListener('submit', function(e) {
          // console.log removed
          e.preventDefault();
          
          // Get place order button
          const placeOrderBtn = document.getElementById('place-order-btn');
          
          // Show loading state
          if (placeOrderBtn) {
            placeOrderBtn.disabled = true;
            placeOrderBtn.classList.add('loading');
          }
          
          // Basic validation before submit
          const requiredFields = ['receiver_name', 'billing_city', 'billing_district', 'billing_ward', 'billing_address', 'billing_phone'];
          let hasError = false;
          
          for (const fieldName of requiredFields) {
            const field = document.getElementById(fieldName);
            // console.log removed
            if (!field || !field.value || field.value.trim() === '') {
              console.error(`Validation failed for field: ${fieldName}`, field ? `value: "${field.value}"` : 'field not found');
              hasError = true;
              if (field) field.classList.add('is-invalid');
            } else {
              if (field) field.classList.remove('is-invalid');
            }
          }
          
          // Check payment method
          const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
          // console.log removed
          if (!paymentMethod) {
            console.error('No payment method selected');
            hasError = true;
            alert('Vui lòng chọn phương thức thanh toán!');
          }
          
          if (hasError) {
            console.error('Form validation failed, preventing submission');
            if (placeOrderBtn) {
              placeOrderBtn.disabled = false;
              placeOrderBtn.classList.remove('loading');
            }
            return false;
          }
          
          // console.log removed
          
          // Submit the form
          // console.log removed
          // console.log removed
          // console.log removed
          
          // Log all form data
          const formData = new FormData(this);
          // console.log removed
          for (let [key, value] of formData.entries()) {
            // console.log removed
          }
          
          try {
            this.submit();
          } catch (error) {
            console.error('Form submission error:', error);
            if (placeOrderBtn) {
              placeOrderBtn.disabled = false;
              placeOrderBtn.classList.remove('loading');
            }
          }
        });
      } else {
        console.error('Checkout form not found in DOMContentLoaded');
        console.error('Available forms:', document.querySelectorAll('form'));
        console.error('Forms with IDs:', Array.from(document.querySelectorAll('form')).map(f => f.id));
      }
    });

                    // Final check after all scripts are loaded
      window.addEventListener('load', function() {
        // Force load provinces if not loaded yet
        if (!window.vietnamData || window.vietnamData.length === 0) {
          if (typeof window.forceLoadProvinces === 'function') {
            window.forceLoadProvinces();
          }
        }

  

  

  

        // Basic initialization complete
});
</script>
@endsection
