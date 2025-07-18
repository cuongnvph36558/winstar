@extends('layouts.client')

@section('title', 'Thanh Toán')

@section('css')
<link rel="stylesheet" href="{{ asset('client/assets/css/checkout-custom.css') }}">
@endsection

@section('content')
<section class="module">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <ol class="breadcrumb font-alt">
          <li><a href="{{ route('client.home') }}"><i class="fa fa-home"></i></a></li>
          <li><a href="{{ route('client.product') }}">Sản phẩm</a></li>
          <li class="active">Thanh Toán</li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <h1 class="module-title font-alt">Thanh Toán</h1>
      </div>
    </div>
    <hr class="divider-w pt-20">

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
      <div class="row">
        <div class="col-sm-8">
          <!-- Danh sách sản phẩm -->
          <div class="cart-items bg-white p-30 mb-30">
            <h4 class="font-alt mb-25">Sản phẩm trong giỏ hàng</h4>
            <div class="table-responsive">
              <table class="table table-bordered checkout-table">
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-right">Giá</th>
                    <th class="text-right">Tổng</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cartItems as $item)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        @if($item->product && $item->product->images && $item->product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product->name }}" style="width: 80px; height: 80px; object-fit: cover; margin-right: 15px;">
                        @endif
                        <div>
                          <h5 class="mb-1">{{ optional($item->product)->name }}</h5>
                          <div class="product-variant">
                            @if($item->variant && $item->variant->color && $item->variant->storage)
                            <small class="variant-color">{{ $item->variant->color->name }}</small>
                            <span class="mx-1">|</span>
                            <small class="variant-storage">{{ $item->variant->storage->name }}</small>
                            @endif
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- Form thông tin giao hàng -->
          <div class="shipping-form bg-white p-30 mb-30">
            <h4 class="font-alt mb-25">Thông tin giao hàng</h4>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="receiver_name">Tên Người Nhận *</label>
                  <input class="form-control" id="receiver_name" type="text" name="receiver_name" value="{{ old('receiver_name', Auth::user()->name) }}" required />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="billing_city">Tỉnh/Thành phố *</label>
                  <select class="form-control" id="billing_city" name="billing_city" required data-old="{{ old('billing_city') }}">
                    <option value="">Chọn Tỉnh/Thành phố</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="billing_district">Quận/Huyện *</label>
                  <select class="form-control" id="billing_district" name="billing_district" required disabled data-old="{{ old('billing_district') }}">
                    <option value="">Chọn Quận/Huyện</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="billing_ward">Phường/Xã *</label>
                  <select class="form-control" id="billing_ward" name="billing_ward" required disabled data-old="{{ old('billing_ward') }}">
                    <option value="">Chọn Phường/Xã</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="billing_address">Số nhà, tên đường *</label>
                  <input class="form-control" id="billing_address" type="text" name="billing_address" value="{{ old('billing_address') }}" required />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="billing_phone">Số điện thoại *</label>
                  <input class="form-control" id="billing_phone" type="tel" name="billing_phone" value="{{ old('billing_phone') }}" required />
                </div>
              </div>
            </div>
            <div class="form-group mb-0">
              <label for="description">Ghi chú đơn hàng</label>
              <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="order-summary bg-white p-30 mb-30">
            <!-- Phần tổng quan giỏ hàng -->
            <h4 class="font-alt mb-25">Đơn hàng của bạn</h4>
            <div class="coupon-section mb-20">
              <div class="input-group">
                <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="Nhập mã giảm giá">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="button" id="apply_coupon">
                    <span class="coupon-text">Áp dụng</span>
                    <i class="fa fa-spinner fa-spin coupon-loading" style="display: none;"></i>
                  </button>
                </span>
              </div>
              <div id="coupon_message" class="mt-10"></div>
            </div>

            <table class="table table-bordered checkout-table mb-30">
              <tbody>
                <tr>
                  <th>Tạm tính:</th>
                  <td class="text-right">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                </tr>
                <tr>
                  <th>Phí vận chuyển:</th>
                  <td class="text-right">{{ number_format($shipping, 0, ',', '.') }}đ</td>
                </tr>
                @if(isset($couponDiscount) && $couponDiscount > 0)
                <tr class="discount">
                  <th>Giảm giá:</th>
                  <td class="text-right">-{{ number_format($couponDiscount, 0, ',', '.') }}đ</td>
                </tr>
                @endif
                <tr class="order-total">
                  <th>Tổng cộng:</th>
                  <td class="text-right">{{ number_format($total, 0, ',', '.') }}đ</td>
                </tr>
              </tbody>
            </table>

            <!-- Payment Methods Section -->
            <div class="payment-methods-container mb-20">
              <h5 class="font-alt mb-15">Phương thức thanh toán</h5>
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

            <button class="btn btn-lg btn-block btn-round btn-d" type="submit" id="place-order-btn">
              <span class="order-text">
                <i class="fa fa-check mr-5"></i>Đặt hàng
              </span>
              <span class="order-loading" style="display: none;">
                <i class="fa fa-spinner fa-spin mr-5"></i>Đang xử lý...
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
  .product-variant {
    margin-top: 8px;
  }

  .product-variant small {
    font-size: 13px;
    color: #666;
    background-color: #f8f9fa;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
  }

  .variant-color,
  .variant-storage {
    display: inline-block;
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

  .coupon-section {
    background: #fff;
    padding: 15px;
    border-radius: 4px;
    border: 1px solid #eee;
  }

  #coupon_message {
    font-size: 13px;
  }

  #coupon_message.success {
    color: #28a745;
  }

  #coupon_message.error {
    color: #dc3545;
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

      // 2. Gửi request đến route client.apply-coupon bằng phương thức POST
      fetch('{{ route("client.apply-coupon") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json', // QUAN TRỌNG: để Laravel trả về JSON thay vì HTML
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            code: code
          }) // Truyền mã giảm giá
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            messageDiv.innerHTML = `<span class="success">${data.message}</span>`;
            // Reload page to update prices
            // Cập nhật tạm tính
            const tamTinh = document.querySelector('.checkout-table tr:nth-child(1) td');
            if (tamTinh) tamTinh.innerText = `${data.subtotal}đ`;

            // Cập nhật hoặc thêm dòng giảm giá
            let discountRow = document.querySelector('.checkout-table .discount');
            if (!discountRow) {
              const row = document.createElement('tr');
              row.classList.add('discount');
              row.innerHTML = `<th>Giảm giá:</th><td class="text-right" id="discount-cell">-${data.discount_amount}đ</td>`;
              document.querySelector('.checkout-table .order-total').before(row);
            } else {
              document.querySelector('#discount-cell').innerText = `-${data.discount_amount}đ`;
            }

            // Cập nhật tổng cộng
            const tongCong = document.querySelector('.checkout-table .order-total td');
            if (tongCong) tongCong.innerText = `${data.updated_total}đ`;

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