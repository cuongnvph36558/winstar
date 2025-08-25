<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Yêu cầu đổi hoàn hàng - Đơn hàng {{ $order->code_order ?? ('#' . $order->id) }}</title>
  <!-- Favicons -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v={{ time() }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}?v={{ time() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/return-exchange-enhanced.css') }}">
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
    
    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-control.is-invalid {
      border-color: #ef4444;
    }
    
    .invalid-feedback {
      color: #ef4444;
      font-size: 0.75rem;
      margin-top: 0.25rem;
    }
    
    .upload-preview {
      min-height: 120px;
      border: 2px dashed #d1d5db;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 0.75rem;
      transition: all 0.3s ease;
    }
    
    .upload-preview:hover {
      border-color: #3b82f6;
      background-color: #f8fafc;
    }
    
    .upload-preview.has-file {
      border-style: solid;
      border-color: #10b981;
      background-color: #f0fdf4;
    }
    
    .btn {
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 600;
      font-size: 0.875rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
      background: #6b7280;
      color: white;
    }
    
    .btn-secondary:hover {
      background: #4b5563;
      transform: translateY(-2px);
    }
    
    .required-badge {
      background: #ef4444;
      color: white;
      padding: 0.125rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.75rem;
      font-weight: 600;
      margin-left: 0.5rem;
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="container mx-auto px-4 py-8" style="max-width: 54rem;">
    <!-- Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
        <a href="{{ route('client.order.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
          <i class="fas fa-arrow-left mr-2"></i>
          Quay lại chi tiết đơn hàng
        </a>
        <a href="{{ route('client.order.list') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
          <i class="fas fa-list mr-2"></i>
          Danh sách đơn hàng
        </a>
      </div>
      <h1 class="text-2xl font-bold text-gray-900">Yêu cầu đổi hoàn hàng</h1>
      <div class="flex items-center mt-2">
        <span class="px-3 py-1 rounded-full bg-orange-600 text-white text-sm font-medium">
          Đơn hàng: {{ $order->code_order ?? ('#' . $order->id) }}
        </span>
      </div>
    </div>

    <!-- Order Summary Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <h3 class="font-medium mb-4 flex items-center">
        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
        Thông tin đơn hàng
      </h3>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
          <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-calendar text-white text-sm"></i>
          </div>
          <div>
            <p class="text-xs text-gray-500">Ngày đặt</p>
            <p class="font-medium">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
          </div>
        </div>
        
        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
          <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-money-bill text-white text-sm"></i>
          </div>
          <div>
            <p class="text-xs text-gray-500">Tổng tiền</p>
            <p class="font-medium">{{ $order->total_amount ? number_format($order->total_amount) : 0 }}đ</p>
          </div>
        </div>
        
        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
          <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-shopping-cart text-white text-sm"></i>
          </div>
          <div>
            <p class="text-xs text-gray-500">Số sản phẩm</p>
            <p class="font-medium">{{ $order->orderDetails ? $order->orderDetails->count() : 0 }} sản phẩm</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Return Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <h3 class="font-medium mb-6 flex items-center">
        <i class="fas fa-exchange-alt mr-2 text-orange-500"></i>
        Thông tin yêu cầu đổi hoàn hàng
      </h3>
      
      <form action="{{ route('client.return.request', $order->id) }}" method="POST" id="returnForm" enctype="multipart/form-data">
        @csrf
        
        <!-- Product Selection -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Chọn sản phẩm cần đổi hoàn hàng <span class="required-badge">Bắt buộc</span>
          </label>
          <div class="bg-gray-50 rounded-lg p-4">
            @foreach($order->orderDetails as $detail)
              <div class="flex items-center justify-between p-3 bg-white rounded-lg mb-3 border border-gray-200">
                <div class="flex items-center space-x-3">
                  <input type="checkbox" 
                         name="return_products[]" 
                         value="{{ $detail->id }}" 
                         id="product_{{ $detail->id }}"
                         class="return-product-checkbox"
                         data-product-id="{{ $detail->id }}"
                         data-max-quantity="{{ $detail->quantity }}"
                         data-price="{{ $detail->price }}">
                  
                  <div class="flex-shrink-0">
                    @if($detail->product && $detail->product->image)
                      <img src="{{ asset('storage/' . $detail->product->image) }}" 
                           alt="{{ $detail->product_name }}" 
                           class="w-16 h-16 object-cover rounded-lg">
                    @else
                      <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                      </div>
                    @endif
                  </div>
                  
                  <div class="flex-1">
                    <h4 class="font-medium text-gray-900">{{ $detail->product_name }}</h4>
                    @if($detail->variant)
                      <p class="text-sm text-gray-600">
                        {{ $detail->variant->color->name ?? '' }} 
                        {{ $detail->variant->storage->capacity ?? '' }}
                      </p>
                    @endif
                    <p class="text-sm text-gray-500">Số lượng: {{ $detail->quantity }} | Giá: {{ number_format($detail->price) }}đ</p>
                  </div>
                </div>
                
                <div class="flex items-center space-x-2">
                  <label class="text-sm text-gray-600">Số lượng hoàn:</label>
                  <input type="number" 
                         name="return_quantities[{{ $detail->id }}]" 
                         min="1" 
                         max="{{ $detail->quantity }}" 
                         value="0"
                         class="return-quantity-input w-16 px-2 py-1 border border-gray-300 rounded text-sm"
                         data-product-id="{{ $detail->id }}"
                         disabled>
                </div>
              </div>
            @endforeach
          </div>
          <div id="selected-products-summary" class="mt-3 p-3 bg-blue-50 rounded-lg hidden">
            <h5 class="font-medium text-blue-900 mb-2">Tổng quan sản phẩm được chọn:</h5>
            <div id="summary-content"></div>
          </div>
        </div>

        <!-- Return Method -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Phương thức đổi hoàn hàng <span class="required-badge">Bắt buộc</span>
          </label>
          <select name="return_method" id="return_method" class="form-control @error('return_method') is-invalid @enderror" required>
            <option value="">Chọn phương thức</option>
            <option value="points" {{ old('return_method') == 'points' ? 'selected' : '' }}>Đổi điểm</option>
            <option value="exchange" {{ old('return_method') == 'exchange' ? 'selected' : '' }}>Đổi hàng</option>
          </select>
          @error('return_method')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Return Reason -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Lý do đổi hoàn hàng <span class="required-badge">Bắt buộc</span>
          </label>
          <select name="return_reason" id="return_reason" class="form-control @error('return_reason') is-invalid @enderror" required>
            <option value="">Chọn lý do</option>
            <option value="Sản phẩm bị lỗi" {{ old('return_reason') == 'Sản phẩm bị lỗi' ? 'selected' : '' }}>Sản phẩm bị lỗi</option>
            <option value="Sản phẩm không đúng mô tả" {{ old('return_reason') == 'Sản phẩm không đúng mô tả' ? 'selected' : '' }}>Sản phẩm không đúng mô tả</option>
            <option value="Sản phẩm bị hư hỏng khi vận chuyển" {{ old('return_reason') == 'Sản phẩm bị hư hỏng khi vận chuyển' ? 'selected' : '' }}>Sản phẩm bị hư hỏng khi vận chuyển</option>
            <option value="Không vừa ý với sản phẩm" {{ old('return_reason') == 'Không vừa ý với sản phẩm' ? 'selected' : '' }}>Không vừa ý với sản phẩm</option>
            <option value="Đặt nhầm sản phẩm" {{ old('return_reason') == 'Đặt nhầm sản phẩm' ? 'selected' : '' }}>Đặt nhầm sản phẩm</option>
            <option value="Lý do khác" {{ old('return_reason') == 'Lý do khác' ? 'selected' : '' }}>Lý do khác</option>
          </select>
          @error('return_reason')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Return Description -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Mô tả chi tiết
          </label>
          <textarea name="return_description" id="return_description" rows="4" 
            class="form-control @error('return_description') is-invalid @enderror" 
            placeholder="Vui lòng mô tả chi tiết về vấn đề bạn gặp phải...">{{ old('return_description') }}</textarea>
          @error('return_description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Media Upload Section -->
        <div class="mb-6">
          <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
            <i class="fas fa-camera mr-2 text-blue-500"></i>
            Bằng chứng đổi hoàn hàng
          </h4>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Video Upload -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Video bóc hàng <span class="required-badge">Bắt buộc</span>
              </label>
              <input type="file" name="return_video" id="return_video" 
                     class="form-control @error('return_video') is-invalid @enderror" 
                     accept="video/*" required>
              @error('return_video')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="upload-preview" id="video-preview">
                <div class="text-center">
                  <i class="fas fa-video-camera text-2xl text-gray-400 mb-2"></i>
                  <p class="text-sm text-gray-500">Chưa có video</p>
                </div>
              </div>
            </div>

            <!-- Order Image Upload -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Ảnh đơn hàng <span class="required-badge">Bắt buộc</span>
              </label>
              <input type="file" name="return_order_image" id="return_order_image" 
                     class="form-control @error('return_order_image') is-invalid @enderror" 
                     accept="image/*" required>
              @error('return_order_image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="upload-preview" id="order-image-preview">
                <div class="text-center">
                  <i class="fas fa-image text-2xl text-gray-400 mb-2"></i>
                  <p class="text-sm text-gray-500">Chưa có ảnh</p>
                </div>
              </div>
            </div>

            <!-- Product Image Upload -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Ảnh sản phẩm <span class="required-badge">Bắt buộc</span>
              </label>
              <input type="file" name="return_product_image" id="return_product_image" 
                     class="form-control @error('return_product_image') is-invalid @enderror" 
                     accept="image/*" required>
              @error('return_product_image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="upload-preview" id="product-image-preview">
                <div class="text-center">
                  <i class="fas fa-cube text-2xl text-gray-400 mb-2"></i>
                  <p class="text-sm text-gray-500">Chưa có ảnh</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="mb-6">
          <div class="flex items-start">
            <input type="checkbox" id="agree_terms" class="mt-1 mr-3" required>
            <label for="agree_terms" class="text-sm text-gray-700">
              Tôi đồng ý với <a href="#" class="text-blue-600 hover:text-blue-800 underline">điều khoản đổi hoàn hàng</a>
              <span class="required-badge">Bắt buộc</span>
            </label>
          </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-center space-x-4">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i>
            Gửi yêu cầu đổi hoàn hàng
          </button>
          <a href="{{ route('client.order.show', $order->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Quay lại
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('returnForm');
      const submitBtn = form.querySelector('.btn-primary');
      
      // Product selection handlers
      const productCheckboxes = document.querySelectorAll('.return-product-checkbox');
      const quantityInputs = document.querySelectorAll('.return-quantity-input');
      const summaryDiv = document.getElementById('selected-products-summary');
      const summaryContent = document.getElementById('summary-content');
      
      // Handle product checkbox changes
      productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          const productId = this.dataset.productId;
          const quantityInput = document.querySelector(`input[name="return_quantities[${productId}]"]`);
          
          if (this.checked) {
            quantityInput.disabled = false;
            quantityInput.value = 1; // Default to 1
          } else {
            quantityInput.disabled = true;
            quantityInput.value = 0;
          }
          
          updateSummary();
        });
      });
      
      // Handle quantity input changes
      quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
          const productId = this.dataset.productId;
          const checkbox = document.querySelector(`#product_${productId}`);
          const maxQuantity = parseInt(checkbox.dataset.maxQuantity);
          
          if (parseInt(this.value) > maxQuantity) {
            this.value = maxQuantity;
          }
          
          if (parseInt(this.value) <= 0) {
            this.value = 0;
            checkbox.checked = false;
            this.disabled = true;
          }
          
          updateSummary();
        });
      });
      
      // Update summary
      function updateSummary() {
        const selectedProducts = [];
        let totalQuantity = 0;
        let totalValue = 0;
        
        productCheckboxes.forEach(checkbox => {
          if (checkbox.checked) {
            const productId = checkbox.dataset.productId;
            const quantityInput = document.querySelector(`input[name="return_quantities[${productId}]"]`);
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(checkbox.dataset.price) || 0;
            const productName = checkbox.closest('.flex').querySelector('h4').textContent;
            
            if (quantity > 0) {
              selectedProducts.push({
                name: productName,
                quantity: quantity,
                price: price,
                total: quantity * price
              });
              
              totalQuantity += quantity;
              totalValue += quantity * price;
            }
          }
        });
        
        if (selectedProducts.length > 0) {
          let summaryHTML = '<div class="space-y-2">';
          selectedProducts.forEach(product => {
            summaryHTML += `
              <div class="flex justify-between text-sm">
                <span>${product.name} (x${product.quantity})</span>
                <span class="font-medium">${product.total.toLocaleString()}đ</span>
              </div>
            `;
          });
          summaryHTML += `
            <hr class="my-2">
            <div class="flex justify-between font-medium">
              <span>Tổng cộng (${totalQuantity} sản phẩm):</span>
              <span class="text-blue-600">${totalValue.toLocaleString()}đ</span>
            </div>
          </div>`;
          
          summaryContent.innerHTML = summaryHTML;
          summaryDiv.classList.remove('hidden');
        } else {
          summaryDiv.classList.add('hidden');
        }
      }
      
      // File preview handlers
      const videoInput = document.getElementById('return_video');
      const orderImageInput = document.getElementById('return_order_image');
      const productImageInput = document.getElementById('return_product_image');
      
      // Video preview
      videoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('video-preview');
        
        if (file) {
          if (file.size > 50 * 1024 * 1024) {
            alert('Video quá lớn! Vui lòng chọn video nhỏ hơn 50MB.');
            this.value = '';
            return;
          }
          
          if (!file.type.startsWith('video/')) {
            alert('Vui lòng chọn file video hợp lệ!');
            this.value = '';
            return;
          }
          
          const video = document.createElement('video');
          video.controls = true;
          video.style.width = '100%';
          video.style.height = '100px';
          video.style.objectFit = 'cover';
          
          const source = document.createElement('source');
          source.src = URL.createObjectURL(file);
          source.type = file.type;
          
          video.appendChild(source);
          preview.innerHTML = '';
          preview.appendChild(video);
          preview.classList.add('has-file');
        } else {
          preview.innerHTML = `
            <div class="text-center">
              <i class="fas fa-video-camera text-2xl text-gray-400 mb-2"></i>
              <p class="text-sm text-gray-500">Chưa có video</p>
            </div>
          `;
          preview.classList.remove('has-file');
        }
      });
      
      // Order image preview
      orderImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('order-image-preview');
        
        if (file) {
          if (file.size > 5 * 1024 * 1024) {
            alert('Ảnh quá lớn! Vui lòng chọn ảnh nhỏ hơn 5MB.');
            this.value = '';
            return;
          }
          
          if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file ảnh hợp lệ!');
            this.value = '';
            return;
          }
          
          const img = document.createElement('img');
          img.src = URL.createObjectURL(file);
          img.style.width = '100%';
          img.style.height = '100px';
          img.style.objectFit = 'cover';
          img.style.borderRadius = '0.5rem';
          
          preview.innerHTML = '';
          preview.appendChild(img);
          preview.classList.add('has-file');
        } else {
          preview.innerHTML = `
            <div class="text-center">
              <i class="fas fa-image text-2xl text-gray-400 mb-2"></i>
              <p class="text-sm text-gray-500">Chưa có ảnh</p>
            </div>
          `;
          preview.classList.remove('has-file');
        }
      });
      
      // Product image preview
      productImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('product-image-preview');
        
        if (file) {
          if (file.size > 5 * 1024 * 1024) {
            alert('Ảnh quá lớn! Vui lòng chọn ảnh nhỏ hơn 5MB.');
            this.value = '';
            return;
          }
          
          if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file ảnh hợp lệ!');
            this.value = '';
            return;
          }
          
          const img = document.createElement('img');
          img.src = URL.createObjectURL(file);
          img.style.width = '100%';
          img.style.height = '100px';
          img.style.objectFit = 'cover';
          img.style.borderRadius = '0.5rem';
          
          preview.innerHTML = '';
          preview.appendChild(img);
          preview.classList.add('has-file');
        } else {
          preview.innerHTML = `
            <div class="text-center">
              <i class="fas fa-cube text-2xl text-gray-400 mb-2"></i>
              <p class="text-sm text-gray-500">Chưa có ảnh</p>
            </div>
          `;
          preview.classList.remove('has-file');
        }
      });
      
      // Form validation
      form.addEventListener('submit', function(e) {
        const returnMethod = document.getElementById('return_method').value;
        const returnReason = document.getElementById('return_reason').value;
        const agreeTerms = document.getElementById('agree_terms').checked;
        const video = document.getElementById('return_video').files[0];
        const orderImage = document.getElementById('return_order_image').files[0];
        const productImage = document.getElementById('return_product_image').files[0];
        
        // Check if any products are selected
        const selectedProducts = document.querySelectorAll('.return-product-checkbox:checked');
        if (selectedProducts.length === 0) {
          e.preventDefault();
          alert('Vui lòng chọn ít nhất một sản phẩm để đổi hoàn hàng!');
          return false;
        }
        
        // Check if quantities are valid
        let hasValidQuantity = false;
        selectedProducts.forEach(checkbox => {
          const productId = checkbox.dataset.productId;
          const quantityInput = document.querySelector(`input[name="return_quantities[${productId}]"]`);
          if (parseInt(quantityInput.value) > 0) {
            hasValidQuantity = true;
          }
        });
        
        if (!hasValidQuantity) {
          e.preventDefault();
          alert('Vui lòng nhập số lượng hợp lệ cho các sản phẩm được chọn!');
          return false;
        }
        
        if (!returnMethod || !returnReason || !agreeTerms) {
          e.preventDefault();
          alert('Vui lòng điền đầy đủ thông tin bắt buộc và đồng ý với điều khoản!');
          return false;
        }
        
        if (!video || !orderImage || !productImage) {
          e.preventDefault();
          alert('Vui lòng upload đầy đủ video và hình ảnh theo yêu cầu!');
          return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
      });
    });
  </script>
</body>
</html> 
