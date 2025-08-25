<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Chỉnh sửa đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}</title>
  <!-- Favicons -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v={{ time() }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}?v={{ time() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .max-w-4xl {
      max-width: 56rem;
    }
    
    .section-container {
      margin: 1.5rem auto;
      padding: 1.5rem;
    }
    
    .mx-auto {
      margin-left: auto;
      margin-right: auto;
    }

    .form-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .form-header {
      border-bottom: 2px solid #f3f4f6;
      padding-bottom: 1rem;
      margin-bottom: 2rem;
    }

    .form-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 0.5rem;
    }

    .form-subtitle {
      color: #6b7280;
      font-size: 0.875rem;
    }

    .time-warning {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      border: 1px solid #f59e0b;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .time-warning i {
      color: #d97706;
      font-size: 1.25rem;
    }

    .time-warning .warning-text {
      color: #92400e;
      font-weight: 500;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      font-weight: 600;
      color: #374151;
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
    }

    .form-label.required::after {
      content: " *";
      color: #ef4444;
    }

    .form-input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .form-input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.875rem;
      min-height: 100px;
      resize: vertical;
      transition: all 0.2s ease;
    }

    .form-textarea:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-select {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.875rem;
      transition: all 0.2s ease;
      background-color: white;
      cursor: pointer;
    }

    .form-select:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-select:disabled {
      background-color: #f9fafb;
      cursor: not-allowed;
      opacity: 0.6;
    }

    .form-help {
      font-size: 0.75rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.875rem;
      text-decoration: none;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
      gap: 0.5rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
      background: #f3f4f6;
      color: #374151;
      border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
      background: #e5e7eb;
      transform: translateY(-1px);
    }

    .btn-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
    }

    .btn-danger:hover {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .order-summary {
      background: #f8fafc;
      border-radius: 8px;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .order-summary h3 {
      font-size: 1.125rem;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 1rem;
    }

    .order-info {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.875rem;
    }

    .info-item i {
      color: #6b7280;
      width: 16px;
    }

    .info-label {
      font-weight: 500;
      color: #374151;
    }

    .info-value {
      color: #1f2937;
    }

    .alert {
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .alert-success {
      background: #d1fae5;
      border: 1px solid #10b981;
      color: #065f46;
    }

    .alert-danger {
      background: #fee2e2;
      border: 1px solid #ef4444;
      color: #991b1b;
    }

    .alert i {
      font-size: 1.125rem;
    }

    @media (max-width: 768px) {
      .section-container {
        padding: 1rem;
      }
      
      .form-container {
        padding: 1.5rem;
      }
      
      .order-info {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="max-w-4xl mx-auto section-container">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-edit text-blue-600 mr-2"></i>
            Chỉnh sửa đơn hàng
          </h1>
          <p class="text-gray-600 mt-1">
            Đơn hàng: <span class="font-semibold">{{ $order->code_order ?? ('#' . $order->id) }}</span>
          </p>
        </div>
        <a href="{{ route('client.order.show', $order->id) }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
          Quay lại
        </a>
      </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
      </div>
    @endif

    <!-- Time Warning -->
    @php
      $orderCreatedTime = $order->created_at;
      $timeLimit = now()->subMinutes(15);
      $remainingTime = $orderCreatedTime->addMinutes(15)->diff(now());
    @endphp
    
    @if($orderCreatedTime->gt($timeLimit))
      <div class="time-warning">
        <i class="fas fa-clock"></i>
        <div>
          <div class="warning-text">Thời gian chỉnh sửa còn lại</div>
          <div class="text-sm">
            @if($remainingTime->h > 0)
              {{ $remainingTime->h }} giờ {{ $remainingTime->i }} phút
            @else
              {{ $remainingTime->i }} phút {{ $remainingTime->s }} giây
            @endif
          </div>
        </div>
      </div>
    @endif

    <!-- Order Summary -->
    <div class="order-summary">
      <h3><i class="fas fa-info-circle text-blue-600 mr-2"></i>Thông tin đơn hàng hiện tại</h3>
      <div class="order-info">
        <div class="info-item">
          <i class="fas fa-user"></i>
          <span class="info-label">Người nhận:</span>
          <span class="info-value">{{ $order->receiver_name }}</span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span class="info-label">Số điện thoại:</span>
          <span class="info-value">{{ $order->phone }}</span>
        </div>
        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <span class="info-label">Địa chỉ:</span>
          <span class="info-value">{{ $order->billing_address }}, {{ $order->billing_ward }}, {{ $order->billing_district }}, {{ $order->billing_city }}</span>
        </div>
        <div class="info-item">
          <i class="fas fa-shopping-cart"></i>
          <span class="info-label">Tổng tiền:</span>
          <span class="info-value font-semibold text-green-600">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
        </div>
        <div class="info-item">
          <i class="fas fa-credit-card"></i>
          <span class="info-label">Phương thức:</span>
          <span class="info-value">
            @if($order->payment_method === 'cod')
              Thanh toán khi nhận hàng
            @elseif($order->payment_method === 'vnpay')
              VNPay
            @else
              {{ $order->payment_method }}
            @endif
          </span>
        </div>
        <div class="info-item">
          <i class="fas fa-clock"></i>
          <span class="info-label">Trạng thái:</span>
          <span class="info-value">
            @switch($order->status)
              @case('pending')
                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Chờ xử lý</span>
                @break
              @case('processing')
                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Đang chuẩn bị hàng</span>
                @break
              @default
                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">{{ $order->status }}</span>
            @endswitch
          </span>
        </div>
      </div>
    </div>

    <!-- Edit Form -->
    <div class="form-container">
      <div class="form-header">
        <h2 class="form-title">Chỉnh sửa thông tin giao hàng</h2>
        <p class="form-subtitle">Bạn có thể thay đổi thông tin người nhận và địa chỉ giao hàng</p>
      </div>

      <form method="POST" action="{{ route('client.order.update', $order->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Receiver Name -->
          <div class="form-group md:col-span-2">
            <label for="receiver_name" class="form-label required">Họ tên người nhận</label>
            <input 
              type="text" 
              id="receiver_name" 
              name="receiver_name" 
              class="form-input @error('receiver_name') border-red-500 @enderror"
              value="{{ old('receiver_name', $order->receiver_name) }}"
              required
              maxlength="255"
            >
            @error('receiver_name')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Phone -->
          <div class="form-group">
            <label for="billing_phone" class="form-label required">Số điện thoại</label>
            <input 
              type="tel" 
              id="billing_phone" 
              name="billing_phone" 
              class="form-input @error('billing_phone') border-red-500 @enderror"
              value="{{ old('billing_phone', $order->phone) }}"
              required
              maxlength="20"
            >
            @error('billing_phone')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- City -->
          <div class="form-group">
            <label for="billing_city" class="form-label required">Tỉnh/Thành phố</label>
            <select 
              id="billing_city" 
              name="billing_city" 
              class="form-select @error('billing_city') border-red-500 @enderror"
              required
              data-old="{{ old('billing_city', $order->billing_city) }}"
            >
              <option value="">Chọn Tỉnh/Thành phố</option>
            </select>
            @error('billing_city')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- District -->
          <div class="form-group">
            <label for="billing_district" class="form-label required">Quận/Huyện</label>
            <select 
              id="billing_district" 
              name="billing_district" 
              class="form-select @error('billing_district') border-red-500 @enderror"
              required
              disabled
              data-old="{{ old('billing_district', $order->billing_district) }}"
            >
              <option value="">Chọn Quận/Huyện</option>
            </select>
            @error('billing_district')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Ward -->
          <div class="form-group">
            <label for="billing_ward" class="form-label required">Phường/Xã</label>
            <select 
              id="billing_ward" 
              name="billing_ward" 
              class="form-select @error('billing_ward') border-red-500 @enderror"
              required
              disabled
              data-old="{{ old('billing_ward', $order->billing_ward) }}"
            >
              <option value="">Chọn Phường/Xã</option>
            </select>
            @error('billing_ward')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Address -->
          <div class="form-group md:col-span-2">
            <label for="billing_address" class="form-label required">Địa chỉ chi tiết</label>
            <input 
              type="text" 
              id="billing_address" 
              name="billing_address" 
              class="form-input @error('billing_address') border-red-500 @enderror"
              value="{{ old('billing_address', $order->billing_address) }}"
              required
              maxlength="500"
              placeholder="Ví dụ: Số 123, Đường ABC, Tòa nhà XYZ"
            >
            @error('billing_address')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
            <div class="form-help">
              <i class="fas fa-info-circle"></i>
              Nhập địa chỉ chi tiết: số nhà, tên đường, tòa nhà, căn hộ...
            </div>
          </div>

          <!-- Description -->
          <div class="form-group md:col-span-2">
            <label for="description" class="form-label">Ghi chú đơn hàng</label>
            <textarea 
              id="description" 
              name="description" 
              class="form-textarea @error('description') border-red-500 @enderror"
              maxlength="1000"
              placeholder="Ghi chú thêm về đơn hàng (không bắt buộc)"
            >{{ old('description', $order->description) }}</textarea>
            @error('description')
              <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
            <div class="form-help">
              <i class="fas fa-info-circle"></i>
              Ghi chú thêm về đơn hàng, hướng dẫn giao hàng, v.v.
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
          <button type="submit" class="btn btn-primary flex-1">
            <i class="fas fa-save"></i>
            Cập nhật thông tin
          </button>
          
          <a href="{{ route('client.order.show', $order->id) }}" class="btn btn-secondary">
            <i class="fas fa-times"></i>
            Hủy bỏ
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Auto-save form data to localStorage
    const form = document.querySelector('form');
    const formInputs = form.querySelectorAll('input, textarea, select');
    
    // Load saved data on page load
    document.addEventListener('DOMContentLoaded', function() {
      formInputs.forEach(input => {
        const savedValue = localStorage.getItem(`order_edit_${input.name}`);
        if (savedValue && !input.value) {
          input.value = savedValue;
        }
      });
    });
    
    // Save data on input change
    formInputs.forEach(input => {
      input.addEventListener('input', function() {
        localStorage.setItem(`order_edit_${this.name}`, this.value);
      });
      
      // Also save on select change
      if (input.tagName === 'SELECT') {
        input.addEventListener('change', function() {
          localStorage.setItem(`order_edit_${this.name}`, this.value);
        });
      }
    });
    
    // Clear saved data on form submit
    form.addEventListener('submit', function() {
      formInputs.forEach(input => {
        localStorage.removeItem(`order_edit_${input.name}`);
      });
    });
    
    // Clear saved data on page unload if form is not submitted
    window.addEventListener('beforeunload', function() {
      if (!form.classList.contains('submitted')) {
        formInputs.forEach(input => {
          localStorage.removeItem(`order_edit_${input.name}`);
        });
      }
    });
    
    // Mark form as submitted
    form.addEventListener('submit', function() {
      form.classList.add('submitted');
    });
  </script>

  <!-- Address Handler Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Get old values from data attributes
      const oldCity = document.getElementById('billing_city').dataset.old || '';
      const oldDistrict = document.getElementById('billing_district').dataset.old || '';
      const oldWard = document.getElementById('billing_ward').dataset.old || '';

      // Load provinces
      fetch('https://provinces.open-api.vn/api/p/')
        .then(response => response.json())
        .then(data => {
          const provinceSelect = document.getElementById('billing_city');
          data.forEach(province => {
            const option = document.createElement('option');
            option.value = province.name; // Lưu tên thay vì code
            option.textContent = province.name;
            option.dataset.code = province.code; // Lưu code để dùng cho API
            provinceSelect.appendChild(option);
          });

          // Restore old province selection if exists
          if (oldCity) {
            provinceSelect.value = oldCity;
            provinceSelect.dispatchEvent(new Event('change'));
          }
        })
        .catch(error => console.error('Error loading provinces:', error));

      // Province change event
      document.getElementById('billing_city').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceCode = selectedOption.dataset.code;
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
                  option.value = district.name; // Lưu tên thay vì code
                  option.textContent = district.name;
                  option.dataset.code = district.code; // Lưu code để dùng cho API
                  districtSelect.appendChild(option);
                });

                // Restore old district selection if exists
                if (oldDistrict) {
                  districtSelect.value = oldDistrict;
                  districtSelect.dispatchEvent(new Event('change'));
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
        const selectedOption = this.options[this.selectedIndex];
        const districtCode = selectedOption.dataset.code;
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
                  option.value = ward.name; // Lưu tên thay vì code
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
    });
  </script>
</body>
</html>
