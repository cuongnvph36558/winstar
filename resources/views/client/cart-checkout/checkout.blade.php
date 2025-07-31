@extends('layouts.client')

@section('title', 'Thanh Toán')

@section('css')
<link rel="stylesheet" href="{{ asset('client/assets/css/checkout-custom.css') }}">
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
    <div class="checkout-progress mb-4">
      <div class="progress-steps">
        <div class="step active">
          <div class="step-icon">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <div class="step-label">Giỏ hàng</div>
        </div>
        <div class="step active">
          <div class="step-icon">
            <i class="fa fa-credit-card"></i>
          </div>
          <div class="step-label">Thanh toán</div>
        </div>
        <div class="step">
          <div class="step-icon">
            <i class="fa fa-check"></i>
          </div>
          <div class="step-label">Hoàn thành</div>
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
                        @if($item->product && $item->product->images && $item->product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" 
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
                            @if($item->variant && $item->variant->color && $item->variant->storage)
                      <div class="product-variants">
                        <span class="variant-badge color-variant">
                          <i class="fa fa-palette"></i> {{ $item->variant->color->name }}
                        </span>
                        <span class="variant-badge storage-variant">
                          <i class="fa fa-hdd-o"></i> {{ $item->variant->storage->name }}
                        </span>
                      </div>
                            @endif
                    <div class="product-price">
                      <span class="price-label">Đơn giá:</span>
                      <span class="price-value">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                          </div>
                        </div>
                  
                  <div class="item-quantity">
                    <div class="quantity-display">
                      <span class="quantity-label">Số lượng:</span>
                      <span class="quantity-value">{{ $item->quantity }}</span>
                      </div>
                  </div>
                  
                  <div class="item-total">
                    <div class="total-label">Tổng:</div>
                    <div class="total-value">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</div>
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
                          data-old="{{ old('billing_city') }}"
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
                          data-old="{{ old('billing_district') }}"
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
                          data-old="{{ old('billing_ward') }}"
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
                         value="{{ old('billing_address') }}" 
                         placeholder="Ví dụ: 123 Đường ABC"
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
                         value="{{ old('billing_phone') }}" 
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
            
            <!-- Mã giảm giá section - Luôn hiển thị -->
            <div class="coupon-section mb-20">
              <div class="coupon-header">
                <h5 class="font-alt mb-15">
                  <i class="fa fa-ticket mr-2"></i>Mã giảm giá
                  @if(($availableCoupons ?? collect())->isNotEmpty())
                    <span class="badge badge-success ml-2">{{ $availableCoupons->count() }} mã khả dụng</span>
                  @endif
                </h5>
              </div>

              <!-- Applied coupon - Hiển thị khi có mã được áp dụng -->
              @if(session('coupon_code'))
                <div class="applied-coupon mb-15">
                  <div class="coupon-card coupon-applied">
                    <div class="coupon-content">
                      <div class="coupon-info">
                        <div class="coupon-code">
                          <i class="fa fa-check-circle text-success mr-2"></i>
                          <strong>{{ session('coupon_code') }}</strong>
                      </div>
                        <div class="coupon-discount text-success">
                          <i class="fa fa-minus-circle mr-1"></i>
                          Giảm {{ number_format(session('discount', 0), 0, ',', '.') }}đ
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
              @endif

              <!-- Available coupons - Luôn hiển thị -->
                <div class="available-coupons mb-15">
                    @if(($availableCoupons ?? collect())->isNotEmpty())
                  <div class="coupon-selector">
                    <label for="coupon_select" class="form-label mb-2">
                      <i class="fa fa-gift mr-1"></i>Chọn mã giảm giá có sẵn:
                  </label>
                    <select class="form-control coupon-select" id="coupon_select">
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
                          @if($coupon->max_discount_value)
                            (Tối đa {{ number_format($coupon->max_discount_value, 0, ',', '.') }}đ)
                          @endif
                        @else
                          Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                        @endif
                        - Đơn tối thiểu {{ number_format($coupon->min_order_value, 0, ',', '.') }}đ
                      </option>
                    @endforeach
                  </select>
                  </div>
                @else
                  <div class="no-coupons">
                    <div class="alert alert-info">
                      <i class="fa fa-info-circle mr-2"></i>
                      <strong>Không có mã giảm giá nào khả dụng</strong> cho đơn hàng này
                    </div>
                  </div>
                @endif

                <!-- Other coupons info -->
                @if(($allCoupons ?? collect())->isNotEmpty() && ($availableCoupons ?? collect())->isEmpty())
                  <div class="other-coupons mt-10">
                    <div class="coupon-info-disabled">
                      <h6 class="text-info mb-2">
                          <i class="fa fa-info-circle"></i> 
                          Các mã giảm giá khác cần đơn hàng tối thiểu cao hơn:
                      </h6>
                      <div class="coupon-list">
                          @foreach($allCoupons as $coupon)
                          <div class="coupon-item-disabled">
                            <div class="coupon-code-disabled">
                              <strong>{{ $coupon->code }}</strong>
                            </div>
                            <div class="coupon-details-disabled">
                              @if($coupon->discount_type == 'percentage')
                                Giảm {{ $coupon->discount_value }}%
                                @if($coupon->max_discount_value)
                                  (Tối đa {{ number_format($coupon->max_discount_value, 0, ',', '.') }}đ)
                                @endif
                              @else
                                Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                              @endif
                              - Cần đơn tối thiểu {{ number_format($coupon->min_order_value, 0, ',', '.') }}đ
                              - Hết hạn: {{ $coupon->end_date->format('d/m/Y') }}
                            </div>
                            </div>
                          @endforeach
                      </div>
                        </div>
                      </div>
                  @endif
                </div>
                
              <!-- Manual coupon input - Luôn hiển thị -->
              <div class="manual-coupon">
                <div class="input-group">
                  <input type="text" class="form-control" id="coupon_code" name="coupon_code" 
                         value="{{ old('coupon_code') }}" 
                         placeholder="Hoặc nhập mã giảm giá khác">
                  <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" id="apply_coupon">
                      <span class="coupon-text">Áp dụng</span>
                      <i class="fa fa-spinner fa-spin coupon-loading" style="display: none;"></i>
                    </button>
                  </span>
                </div>
                <div id="coupon_message" class="mt-10"></div>
              </div>
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
                @if(isset($couponDiscount) && $couponDiscount > 0)
                  <tr class="discount" id="discount-row">
                  <th>Giảm giá:</th>
                    <td id="discount-amount">-{{ number_format($couponDiscount, 0, ',', '.') }}đ</td>
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
                  Phương thức thanh toán
                </h4>
                <p class="section-subtitle">Chọn phương thức thanh toán phù hợp</p>
              </div>
              <!-- COD -->
              <div class="payment-method-item">
                <div class="payment-radio">
                  <input type="radio" name="payment_method" value="cod" id="cod">
                  <label for="cod" class="payment-label">
                    <div class="payment-icon">
                      <i class="fa fa-truck"></i>
                    </div>
                    <div class="payment-info">
                      <h6>Thanh toán khi nhận hàng (COD)</h6>
                    </div>
                  </label>
                </div>
              </div>

              <!-- MoMo -->
              <div class="payment-method-item">
                <div class="payment-radio">
                  <input type="radio" name="payment_method" value="momo" id="momo_payment">
                  <label for="momo_payment" class="payment-label">
                    <div class="payment-icon payment-logo">
                      <img src="https://static.mservice.io/img/logo-momo.png" alt="MoMo" style="border-radius: 8px;">
                    </div>
                    <div class="payment-info">
                      <h6>Ví MoMo</h6>
                    </div>
                  </label>
                </div>
              </div>
              <!-- VNPay -->
              <div class="payment-method-item">
                <div class="payment-radio">
                  <input type="radio" name="payment_method" value="vnpay" id="vnpay">
                  <label for="vnpay" class="payment-label">
                    <div class="payment-icon payment-logo">
                      <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png" alt="VNPay" style="border-radius: 8px;">
                    </div>
                    <div class="payment-info">
                      <h6>VNPay</h6>
                    </div>
                  </label>
                </div>
              </div>
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
  }

  /* Layout */
  .checkout-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 420px;
    gap: 40px;
    max-width: 1400px;
    margin: 35px auto 0;
    align-items: stretch;
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
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  }

  .progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
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
    padding: 20px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    align-items: center;
    min-height: 120px;
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
    gap: 20px;
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

  /* Enhanced Coupon Section Styles */
  .coupon-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .coupon-header h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
  }

  .coupon-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
  }

  .coupon-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  .coupon-applied {
    border: 2px solid #28a745;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
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
    font-size: 16px;
    font-weight: 600;
    color: #155724;
    margin-bottom: 5px;
  }

  .coupon-discount {
    font-size: 14px;
    font-weight: 500;
  }

  .coupon-actions {
    margin-left: 15px;
  }

  .coupon-selector {
    margin-bottom: 15px;
  }

  .coupon-select {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
  }

  .coupon-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
  }

  .no-coupons .alert {
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
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
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .manual-coupon .form-control {
    border: 2px solid #dee2e6;
    border-right: none;
    padding: 12px 15px;
    font-size: 14px;
  }

  .manual-coupon .form-control:focus {
    border-color: #007bff;
    box-shadow: none;
  }

  .manual-coupon .btn {
    border: 2px solid #007bff;
    border-left: none;
    padding: 12px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
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
    background: linear-gradient(45deg, #28a745, #20c997);
    color: #fff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
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
</style>

<script>
  // Global functions để có thể gọi từ onclick
  function removeCoupon() {
    console.log('removeCoupon function called'); // Debug log
    
    const button = document.getElementById('remove_coupon');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    if (!button) {
      console.error('Remove button not found');
      return;
    }

    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }

    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xóa...';

    console.log('Sending remove coupon request...'); // Debug log

    fetch('{{ route("client.remove-coupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.content
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Cập nhật UI động thay vì reload trang
        const couponSection = document.querySelector('.coupon-section');
        if (couponSection) {
          // Xóa phần applied coupon
          const appliedCoupon = couponSection.querySelector('.applied-coupon');
          if (appliedCoupon) {
            appliedCoupon.remove();
          }
          
          // Hiển thị lại phần available coupons và manual input
          const availableCoupons = couponSection.querySelector('.available-coupons');
          const manualCoupon = couponSection.querySelector('.manual-coupon');
          
          if (availableCoupons) availableCoupons.style.display = 'block';
          if (manualCoupon) manualCoupon.style.display = 'block';
          
          // Reset form
          const couponSelect = document.getElementById('coupon_select');
          const couponCode = document.getElementById('coupon_code');
          const couponMessage = document.getElementById('coupon_message');
          
          if (couponSelect) couponSelect.value = '';
          if (couponCode) couponCode.value = '';
          if (couponMessage) couponMessage.innerHTML = '';
          
          // Hiển thị thông báo thành công
          showToast('Đã xóa mã giảm giá thành công!', 'success');
        }
      } else {
        alert('Lỗi: ' + data.message);
        button.disabled = false;
        button.innerHTML = '<i class="fa fa-times"></i> Xóa';
      }
    })
    .catch(error => {
      console.error('Remove coupon error:', error);
      alert('Đã có lỗi xảy ra khi xóa mã giảm giá');
      button.disabled = false;
      button.innerHTML = '<i class="fa fa-times"></i> Xóa';
    });
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
  function updateTotalAmount(discount) {
    const subtotalElement = document.querySelector('td[data-subtotal]');
    const totalElement = document.getElementById('total-amount');
    const discountRow = document.getElementById('discount-row');
    
    if (subtotalElement && totalElement) {
      const subtotal = parseFloat(subtotalElement.getAttribute('data-subtotal'));
      const shipping = 30000; // Phí vận chuyển cố định
      const newTotal = subtotal + shipping - discount;
      
      // Cập nhật tổng tiền
      totalElement.textContent = newTotal.toLocaleString('vi-VN') + 'đ';
      totalElement.setAttribute('data-total', newTotal);
      
      // Thêm hoặc cập nhật dòng giảm giá
      if (discount > 0) {
        if (!discountRow) {
          // Tạo dòng giảm giá mới
          const discountHTML = `
            <tr class="discount" id="discount-row">
              <th>Giảm giá:</th>
              <td class="text-right" id="discount-amount">-${discount.toLocaleString('vi-VN')}đ</td>
            </tr>
          `;
          const shippingRow = document.querySelector('tr:has(td:contains("Phí vận chuyển"))');
          if (shippingRow) {
            shippingRow.insertAdjacentHTML('afterend', discountHTML);
          }
        } else {
          // Cập nhật dòng giảm giá hiện có
          const discountAmount = document.getElementById('discount-amount');
          if (discountAmount) {
            discountAmount.textContent = '-' + discount.toLocaleString('vi-VN') + 'đ';
          }
        }
      } else {
        // Xóa dòng giảm giá nếu discount = 0
        if (discountRow) {
          discountRow.remove();
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
    console.log('Testing removeCoupon function...');
    removeCoupon();
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Old values from Laravel
    const oldCity = '{{ old("billing_city") }}';
    const oldDistrict = '{{ old("billing_district") }}';
    const oldWard = '{{ old("billing_ward") }}';

    // Load provinces
    fetch('https://provinces.open-api.vn/api/p/')
      .then(response => response.json())
      .then(data => {
        const provinceSelect = document.getElementById('billing_city');
        data.forEach(province => {
          const option = document.createElement('option');
          option.value = province.name;
          option.textContent = province.name;
          option.dataset.code = province.code;
          provinceSelect.appendChild(option);
        });

        // Restore old province selection if exists
        if (oldCity) {
          provinceSelect.value = oldCity;
          provinceSelect.dispatchEvent(new Event('change')); // Trigger change event to load districts
        }
      })
      .catch(error => console.error('Error loading provinces:', error));

    // Province change event
    document.getElementById('billing_city').addEventListener('change', function() {
      const provinceCode = this.selectedOptions[0].dataset.code;
      const districtSelect = document.getElementById('billing_district');
      const wardSelect = document.getElementById('billing_ward');

      // Reset districts and wards
      districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
      wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

      if (provinceCode) {
        districtSelect.disabled = false;
        // Load districts
        fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
          .then(response => response.json())
          .then(data => {
            if (data && data.districts) {
              data.districts.forEach(district => {
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
          })
          .catch(error => console.error('Error loading districts:', error));
      } else {
        districtSelect.disabled = true;
        wardSelect.disabled = true;
      }
    });

    // District change event
    document.getElementById('billing_district').addEventListener('change', function() {
      const districtCode = this.selectedOptions[0].dataset.code;
      const wardSelect = document.getElementById('billing_ward');

      // Reset wards
      wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

      if (districtCode) {
        wardSelect.disabled = false;
        // Load wards
        fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
          .then(response => response.json())
          .then(data => {
            if (data && data.wards) {
              data.wards.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward.name;
                option.textContent = ward.name;
                wardSelect.appendChild(option);
              });

              // Restore old ward selection if exists
              if (oldWard) {
                wardSelect.value = oldWard;
              }
            }
          })
          .catch(error => console.error('Error loading wards:', error));
      } else {
        wardSelect.disabled = true;
      }
    });

    // Enhanced Payment Method Handling
    const paymentMethodItems = document.querySelectorAll('.payment-method-item');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const applyButton = document.getElementById('apply_coupon');

    // Initialize payment methods
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

    // Enhanced form submission with loading states
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
      e.preventDefault();

      // Show loading state
      placeOrderBtn.disabled = true;
      placeOrderBtn.classList.add('loading');

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

      if (!isValid) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        placeOrderBtn.disabled = false;
        placeOrderBtn.classList.remove('loading');
        return false;
      }

      // Phone validation
      const phone = document.getElementById('billing_phone').value;
      if (!/^[0-9]{10}$/.test(phone)) {
        alert('Số điện thoại không hợp lệ!');
        document.getElementById('billing_phone').classList.add('is-invalid');
        placeOrderBtn.disabled = false;
        placeOrderBtn.classList.remove('loading');
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
      setTimeout(() => {
        this.submit();
      }, 500);
    });

    // Enhanced coupon application with loading state
    document.getElementById('apply_coupon').addEventListener('click', function() {
      const code = document.getElementById('coupon_code').value;
      const button = this;
      const messageDiv = document.getElementById('coupon_message');

      if (!code) {
        messageDiv.innerHTML = '<span class="error">Vui lòng nhập mã giảm giá!</span>';
        return;
      }

      // Show loading state
      button.disabled = true;
      button.classList.add('loading');

      // Gửi request đến route client.apply-coupon bằng phương thức POST
      fetch('{{ route("client.apply-coupon") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            coupon_code: code
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            messageDiv.innerHTML = `<span class="success">${data.message}</span>`;
            
            // Cập nhật UI động thay vì reload trang
            setTimeout(() => {
              // Cập nhật giao diện với mã giảm giá mới
              updateCouponUI(data.coupon_code, data.discount);
            }, 1000);
            
          } else {
            messageDiv.innerHTML = `<span class="error">${data.message}</span>`;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          messageDiv.innerHTML = '<span class="error">Đã có lỗi xảy ra!</span>';
        })
        .finally(() => {
          button.disabled = false;
          button.classList.remove('loading');
        });
    });

    // Add event listener for remove coupon button (initial load)
    const initialRemoveBtn = document.getElementById('remove_coupon');
    if (initialRemoveBtn) {
      console.log('Initial remove button found'); // Debug log
    } else {
      console.log('Initial remove button not found'); // Debug log
    }

    // Event listener cho dropdown chọn mã giảm giá
    const couponSelect = document.getElementById('coupon_select');
    if (couponSelect) {
      couponSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
          // Tự động điền mã vào input
          document.getElementById('coupon_code').value = this.value;
          
          // Hiển thị thông tin mã giảm giá
          const discountType = selectedOption.getAttribute('data-discount-type');
          const discountValue = selectedOption.getAttribute('data-discount-value');
          const minOrder = selectedOption.getAttribute('data-min-order');
          const maxDiscount = selectedOption.getAttribute('data-max-discount');
          const endDate = selectedOption.getAttribute('data-end-date');
          
          let details = `<strong>Mã: ${this.value}</strong><br>`;
          if (discountType === 'percentage') {
            details += `Giảm: ${discountValue}%`;
            if (maxDiscount !== 'Không giới hạn') {
              details += ` (Tối đa ${maxDiscount}đ)`;
            }
          } else {
            details += `Giảm: ${parseInt(discountValue).toLocaleString('vi-VN')}đ`;
          }
          details += `<br>Đơn tối thiểu: ${minOrder}đ`;
          details += `<br>Hết hạn: ${endDate}`;
          
          const messageDiv = document.getElementById('coupon_message');
          messageDiv.innerHTML = `<div class="alert alert-info"><small>${details}</small></div>`;
          
          // Tự động áp dụng mã sau 1 giây
          setTimeout(() => {
            document.getElementById('apply_coupon').click();
          }, 1000);
        } else {
          // Xóa input và message khi chọn "-- Chọn mã giảm giá --"
          document.getElementById('coupon_code').value = '';
          document.getElementById('coupon_message').innerHTML = '';
        }
      });
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

    // Enhanced validation feedback
    document.querySelectorAll('input, select').forEach(element => {
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

    // Initialize tooltips if needed
    if (typeof bootstrap !== 'undefined') {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  });
</script>
@endif
@endsection

@section('scripts')
<script src="{{ asset('client/assets/js/address-handler.js') }}"></script>
@endsection