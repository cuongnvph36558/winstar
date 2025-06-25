@extends('layouts.client')

@section('title', 'Chi Tiết Sản Phẩm')

@section('content')
<section class="module">
    <div class="container">
      <div class="row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-sm-6 mb-sm-40">
          <div class="product-images">
            <a class="gallery main-image" href="{{ asset('storage/' . $product->image) ?? 'client/assets/images/shop/product-8.jpg' }}">
              <img src="{{ asset('storage/' . $product->image) ?? 'client/assets/images/shop/product-8.jpg' }}" alt="{{ $product->name }}" class="img-responsive main-product-image"/>
            </a>
            <ul class="product-gallery list-unstyled">
              @foreach($product->variants as $variant)
                <li>
                  <a class="gallery" href="{{ asset('storage/' . $variant->image_variant) ?? 'client/assets/images/shop/product-8.jpg' }}">
                    <img src="{{ asset('storage/' . $variant->image_variant) ?? 'client/assets/images/shop/product-8.jpg' }}" alt="{{ $product->name }} - {{ $variant->storage->capacity }} {{ $variant->color->name }}" class="gallery-thumbnail"/>
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-sm-6">
          <div class="product-info">
            <h1 class="product-title font-alt mb-20">{{ $product->name }}</h1>
            
            <!-- Đánh giá -->
            <div class="product-rating mb-20">
              <div class="stars">
                <i class="fa fa-star star"></i>
                <i class="fa fa-star star"></i>
                <i class="fa fa-star star"></i>
                <i class="fa fa-star star"></i>
                <i class="fa fa-star star-off"></i>
                <a class="review-link" href="#reviews">2 đánh giá</a>
              </div>
            </div>

            <!-- Giá -->
            <div class="product-price mb-20">
              <div class="price font-alt">
                <span class="amount" id="product-price">
                  @php
                    $minPrice = $product->variants->min('price');
                    $maxPrice = $product->variants->max('price');
                  @endphp
                  @if($minPrice == $maxPrice)
                    {{ number_format($minPrice, 0, ',', '.') }}đ
                  @else
                    {{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ
                  @endif
                </span>
              </div>
            </div>

            <!-- Mô tả ngắn -->
            <div class="product-description mb-20">
              <p>{{ $product->description }}</p>
            </div>
            <!-- Form mua hàng -->
            <form action="{{ route('client.add-to-cart') }}" method="POST" class="add-to-cart-form" id="add-to-cart-form">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <div class="row mb-20">
                <!-- Chọn phiên bản -->
                <div class="col-sm-12 mb-20">
                  <label class="font-alt">Chọn phiên bản:</label>
                  <select class="form-control input-lg" name="variant_id" required onchange="updatePriceAndStock(this)" id="variant-select">
                    <option value="">-- Chọn phiên bản --</option>
                    @foreach($product->variants->sortBy('price') as $variant)
                      <option value="{{ $variant->id }}" 
                              data-price="{{ $variant->price }}" 
                              data-stock="{{ $variant->stock_quantity }}">
                        {{ $variant->storage->capacity }} - {{ $variant->color->name }} - {{ number_format($variant->price, 0, ',', '.') }}đ
                        @if($variant->stock_quantity <= 5)
                          (Còn {{ $variant->stock_quantity }} sản phẩm)
                        @endif
                      </option>
                    @endforeach
                  </select>
                </div>

                <!-- Số lượng -->
                <div class="col-sm-4 mb-20">
                  <label class="font-alt">Số lượng:</label>
                  <input class="form-control input-lg" type="number" name="quantity" value="1" max="100" min="1" required="required" id="quantity-input"/>
                  <small class="text-muted" id="stock-info" style="display: none;"></small>
                  <small class="text-danger" id="quantity-error" style="display: none;"></small>
                </div>

                <!-- Nút thêm vào giỏ -->
                <div class="col-sm-8">
                  <label class="font-alt">&nbsp;</label>
                  <button type="submit" class="btn btn-lg btn-block btn-round btn-b">
                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                  </button>
                  
                  <!-- Test button tạm thời -->
                  <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="testRedirect()" style="width: 48%; margin-right: 2%;">
                    <i class="fa fa-bug"></i> Test Redirect
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-info mt-2" onclick="testToast()" style="width: 48%;">
                    <i class="fa fa-bell"></i> Test Toast
                  </button>
                </div>
              </div>
            </form>

            <!-- Meta -->
            <div class="product-meta">
              <div class="product-category">
                Danh mục: <a href="#" class="font-alt">{{ $product->category->name }}</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs thông tin chi tiết -->
      <div class="row mt-70">
        <div class="col-sm-12">
          <ul class="nav nav-tabs font-alt" role="tablist">
            <li class="active">
              <a href="#description" data-toggle="tab">
                <i class="fa fa-file-text"></i> Mô tả
              </a>
            </li>
            <li>
              <a href="#data-sheet" data-toggle="tab">
                <i class="fa fa-list"></i> Thông số kỹ thuật
              </a>
            </li>
            <li>
              <a href="#reviews" data-toggle="tab">
                <i class="fa fa-comments"></i> Đánh giá (2)
              </a>
            </li>
          </ul>

          <div class="tab-content">
            <!-- Tab mô tả -->
            <div class="tab-pane active" id="description">
              <div class="panel-body">
                <p>{{ $product->description }}</p>
              </div>
            </div>

            <!-- Tab thông số -->
            <div class="tab-pane" id="data-sheet">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th class="w-25">Thông số</th>
                    <th>Chi tiết</th>
                  </tr>
                  <tr>
                    <td>Tùy chọn bộ nhớ</td>
                    <td>
                      @foreach($variantStorages as $storage)
                        <span class="badge">{{ $storage->capacity }}</span>
                        @if(!$loop->last), @endif
                      @endforeach
                    </td>
                  </tr>
                  <tr>
                    <td>Màu sắc có sẵn</td>
                    <td>
                      @foreach($variantColors as $color)
                        <span class="badge">{{ $color->name }}</span>
                        @if(!$loop->last), @endif
                      @endforeach
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Tab đánh giá -->
            <div class="tab-pane" id="reviews">
              @auth
              <div class="reviews">
                <!-- Review item 1 -->
                <div class="review-item clearfix">
                  <div class="review-avatar">
                    <img src="" alt="Ảnh đại diện" class="img-circle"/>
                  </div>
                  <div class="review-content">
                    <h4 class="review-author font-alt">Nguyễn Văn A</h4>
                    <div class="review-rating">
                      <i class="fa fa-star star"></i>
                      <i class="fa fa-star star"></i>
                      <i class="fa fa-star star"></i>
                      <i class="fa fa-star star"></i>
                      <i class="fa fa-star star-off"></i>
                    </div>
                    <p class="review-text">Sản phẩm rất tốt, chất lượng cao và đáng giá tiền. Tôi rất hài lòng với trải nghiệm mua hàng tại đây.</p>
                    <span class="review-date font-alt">Hôm nay, 14:55</span>
                  </div>
                </div>

                <!-- Review item 2 -->
                <div class="review-item clearfix">
                  <div class="review-avatar">
                    <img src="" alt="Ảnh đại diện" class="img-circle"/>
                  </div>
                  <div class="review-content">
                    <h4 class="review-author font-alt">Trần Thị B</h4>
                    <div class="review-rating">
                      <i class="fa fa-star star"></i>
                      <i class="fa fa-star star"></i>
                      <i class="fa fa-star star"></i>
                      <i class="fa fa-star star-off"></i>
                      <i class="fa fa-star star-off"></i>
                    </div>
                    <p class="review-text">Dịch vụ khách hàng tốt, giao hàng nhanh. Sản phẩm đúng như mô tả.</p>
                    <span class="review-date font-alt">Hôm nay, 14:59</span>
                  </div>
                </div>

                <!-- Review form -->
                <div class="review-form mt-30">
                  <h4 class="review-form-title font-alt">Thêm đánh giá</h4>
                  <form method="post" class="form">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input class="form-control" type="text" name="name" placeholder="Tên"/>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input class="form-control" type="email" name="email" placeholder="Email"/>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <select class="form-control">
                            <option selected disabled>Đánh giá</option>
                            <option value="1">1 sao</option>
                            <option value="2">2 sao</option>
                            <option value="3">3 sao</option>
                            <option value="4">4 sao</option>
                            <option value="5">5 sao</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <textarea class="form-control" rows="4" placeholder="Nội dung đánh giá"></textarea>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <button class="btn btn-round btn-d" type="submit">
                          <i class="fa fa-paper-plane"></i> Gửi đánh giá
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              @else
              <div class="text-center py-5">
                <p>Vui lòng đăng nhập để xem và viết đánh giá.</p>
                <a href="{{ route('login') }}" class="btn btn-round btn-d">
                  <i class="fa fa-sign-in"></i> Đăng nhập
                </a>
              </div>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <hr class="divider-w">

  <!-- Sản phẩm liên quan -->
  <section class="module-small">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <h2 class="module-title font-alt">Sản phẩm liên quan</h2>
        </div>
      </div>
      <div class="row multi-columns-row">
        @if(count($productAsCategory) > 0)
          @foreach($productAsCategory as $relatedProduct)
          <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="shop-item">
              <div class="shop-item-image">
                <img src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="img-responsive related-product-image"/>
                <div class="shop-item-detail">
                  <a href="{{ route('client.single-product', $relatedProduct->id) }}" class="btn btn-round btn-b">
                    <i class="fa fa-search"></i> Xem chi tiết
                  </a>
                </div>
              </div>
              <h4 class="shop-item-title font-alt">
                <a href="{{ route('client.single-product', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a>
              </h4>
            </div>
          </div>
          @endforeach
        @else
          <div class="col-sm-12">
            <div class="text-center py-5">
              <i class="fa fa-info-circle" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
              <h4 class="font-alt text-muted">Không có sản phẩm liên quan nào</h4>
              <p class="text-muted">Hiện tại chưa có sản phẩm nào khác trong danh mục này.</p>
              <a href="{{ route('client.list-product') }}" class="btn btn-round btn-d mt-3">
                <i class="fa fa-arrow-left"></i> Xem tất cả sản phẩm
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  <hr class="divider-w">

  <!-- Toast notifications -->
  <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

  <!-- Custom CSS for synchronized image sizes -->
  <style>
    /* Đồng bộ kích thước hình ảnh chính */
    .main-product-image {
      width: 100%;
      height: 400px;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    
    .main-product-image:hover {
      transform: scale(1.02);
    }
    
    /* Đồng bộ kích thước gallery thumbnails */
    .gallery-thumbnail {
      width: 80px !important;
      height: 80px !important;
      object-fit: cover;
      border-radius: 6px;
      border: 2px solid #ddd;
      transition: border-color 0.3s ease, transform 0.2s ease;
      cursor: pointer;
      display: block;
    }
    
    .gallery-thumbnail:hover {
      border-color: #007bff;
      transform: scale(1.05);
    }
    
    /* Đồng bộ layout cho gallery */
    .product-gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 15px;
      padding: 0;
    }
    
    .product-gallery li {
      list-style: none;
      margin: 0;
      padding: 0;
    }
    
    /* Đồng bộ kích thước sản phẩm liên quan */
    .related-product-image {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 8px;
      transition: transform 0.3s ease;
    }
    
    .shop-item:hover .related-product-image {
      transform: scale(1.05);
    }
    
    /* Cải thiện layout shop-item */
    .shop-item {
      margin-bottom: 30px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease, transform 0.3s ease;
      background: #fff;
    }
    
    .shop-item:hover {
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      transform: translateY(-5px);
    }
    
    .shop-item-image {
      position: relative;
      overflow: hidden;
    }
    
    .shop-item-detail {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .shop-item:hover .shop-item-detail {
      opacity: 1;
    }
    
    .shop-item-title {
      padding: 15px;
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }
    
    .shop-item-title a {
      color: #333;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    
    .shop-item-title a:hover {
      color: #007bff;
    }
    
    .shop-item-price {
      padding: 0 15px 15px;
      font-size: 18px;
      font-weight: bold;
      color: #e74c3c;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .main-product-image {
        height: 300px;
      }
      
      .related-product-image {
        height: 200px;
      }
      
      .gallery-thumbnail {
        width: 60px !important;
        height: 60px !important;
      }
    }
    
    @media (max-width: 480px) {
      .main-product-image {
        height: 250px;
      }
      
      .related-product-image {
        height: 180px;
      }
      
      .gallery-thumbnail {
        width: 50px !important;
        height: 50px !important;
      }
    }
    
    /* No related products section styling */
    .py-5 {
      padding: 50px 0;
    }
    
    .mt-3 {
      margin-top: 1rem;
    }
  </style>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    let currentStock = 0;
    let currentCartQuantity = 0;
    let availableToAdd = 0;
    let isLoadingStock = false;
    
    function updatePriceAndStock(select) {
      const selectedOption = select.options[select.selectedIndex];
      const variantId = selectedOption.value;
      
      if (variantId) {
        // Hiển thị loading state
        const stockInfo = document.getElementById('stock-info');
        stockInfo.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang kiểm tra kho...';
        stockInfo.style.display = 'block';
        stockInfo.style.color = '#6c757d';
        
        // Fetch real-time stock data
        fetchVariantStock(variantId);
      } else {
        // Reset về giá ban đầu khi chưa chọn variant
        resetToDefaultState();
      }
    }
    
    function fetchVariantStock(variantId) {
      if (isLoadingStock) return;
      
      isLoadingStock = true;
      
      $.ajax({
        url: '{{ route("client.variant-stock") }}',
        method: 'GET',
        data: { variant_id: variantId },
        success: function(response) {
          if (response.success) {
            // Cập nhật thông tin stock
            currentStock = response.current_stock;
            currentCartQuantity = response.cart_quantity;
            availableToAdd = response.available_to_add;
            
            // Cập nhật giá
            document.getElementById('product-price').innerHTML = 
              new Intl.NumberFormat('vi-VN').format(response.price) + 'đ';
            
            // Cập nhật thông tin stock display
            updateStockDisplay();
            
            // Cập nhật quantity input constraints
            updateQuantityConstraints();
            
            // Validate lại quantity hiện tại
            validateQuantity();
          } else {
            showStockError('Không thể lấy thông tin kho');
          }
        },
        error: function() {
          showStockError('Lỗi khi kiểm tra kho');
        },
        complete: function() {
          isLoadingStock = false;
        }
      });
    }
    
    function updateStockDisplay() {
      const stockInfo = document.getElementById('stock-info');
      
      if (currentStock <= 0) {
        stockInfo.innerHTML = 'Hết hàng';
        stockInfo.style.color = '#dc3545';
      } else if (currentCartQuantity > 0) {
        if (availableToAdd > 0) {
          stockInfo.innerHTML = `Còn ${currentStock} sản phẩm. Bạn đã có ${currentCartQuantity} trong giỏ, có thể thêm ${availableToAdd} nữa.`;
          stockInfo.style.color = availableToAdd <= 5 ? '#dc3545' : '#6c757d';
        } else {
          stockInfo.innerHTML = `Bạn đã có ${currentCartQuantity} sản phẩm trong giỏ (đạt giới hạn kho: ${currentStock})`;
          stockInfo.style.color = '#dc3545';
        }
      } else {
        stockInfo.innerHTML = `Còn lại ${currentStock} sản phẩm trong kho`;
        stockInfo.style.color = currentStock <= 5 ? '#dc3545' : '#6c757d';
      }
      
      stockInfo.style.display = 'block';
    }
    
    function updateQuantityConstraints() {
      const quantityInput = document.getElementById('quantity-input');
      
      if (availableToAdd > 0) {
        quantityInput.max = Math.min(availableToAdd, 100);
        quantityInput.disabled = false;
        
        // Adjust current value if it exceeds available
        if (parseInt(quantityInput.value) > availableToAdd) {
          quantityInput.value = Math.min(availableToAdd, 1);
        }
      } else {
        quantityInput.max = 0;
        quantityInput.value = 0;
        quantityInput.disabled = true;
      }
    }
    
    function resetToDefaultState() {
      const minPrice = {{ $minPrice }};
      const maxPrice = {{ $maxPrice }};
      
      if(minPrice === maxPrice) {
        document.getElementById('product-price').innerHTML = new Intl.NumberFormat('vi-VN').format(minPrice) + 'đ';
      } else {
        document.getElementById('product-price').innerHTML = new Intl.NumberFormat('vi-VN').format(minPrice) + 'đ - ' + new Intl.NumberFormat('vi-VN').format(maxPrice) + 'đ';
      }
      
      // Reset stock variables
      currentStock = 0;
      currentCartQuantity = 0;
      availableToAdd = 0;
      
      // Reset UI
      document.getElementById('stock-info').style.display = 'none';
      const quantityInput = document.getElementById('quantity-input');
      quantityInput.max = 100;
      quantityInput.disabled = false;
      quantityInput.value = 1;
      
      // Clear any error messages
      document.getElementById('quantity-error').style.display = 'none';
      quantityInput.style.borderColor = '';
    }
    
    function showStockError(message) {
      const stockInfo = document.getElementById('stock-info');
      stockInfo.innerHTML = message;
      stockInfo.style.color = '#dc3545';
      stockInfo.style.display = 'block';
    }
    
    // Test redirect function
    function testRedirect() {
      console.log('=== TESTING REDIRECT ===');
      const cartUrl = '{{ route("client.cart") }}';
      console.log('Trying to redirect to:', cartUrl);
      
      try {
        window.location.href = cartUrl;
      } catch (error) {
        console.error('Redirect test failed:', error);
        alert('Redirect failed: ' + error.message);
      }
    }
    
    // Test toast function
    function testToast() {
      console.log('=== TESTING TOAST ===');
      
      showToast('Test success toast!', 'success');
      
      setTimeout(function() {
        showToast('Test error toast!', 'error');
      }, 1000);
      
      setTimeout(function() {
        showToast('Test info toast!', 'info');
      }, 2000);
    }
    
    function validateQuantity() {
      const quantityInput = document.getElementById('quantity-input');
      const quantityError = document.getElementById('quantity-error');
      const quantity = parseInt(quantityInput.value) || 0;
      
      quantityError.style.display = 'none';
      quantityInput.style.borderColor = '';
      
      if (quantity < 1) {
        showQuantityError('Số lượng phải lớn hơn 0');
        return false;
      }
      
      if (quantity > 100) {
        showQuantityError('Không thể mua quá 100 sản phẩm cùng lúc');
        return false;
      }
      
      if (availableToAdd === 0) {
        if (currentCartQuantity > 0) {
          showQuantityError(`Bạn đã có ${currentCartQuantity} sản phẩm trong giỏ (đạt giới hạn kho)`);
        } else {
          showQuantityError('Sản phẩm đã hết hàng');
        }
        return false;
      }
      
      if (quantity > availableToAdd) {
        if (currentCartQuantity > 0) {
          showQuantityError(`Chỉ có thể thêm tối đa ${availableToAdd} sản phẩm nữa (đã có ${currentCartQuantity} trong giỏ)`);
        } else {
          showQuantityError(`Chỉ còn ${availableToAdd} sản phẩm trong kho`);
        }
        return false;
      }
      
      return true;
    }
    
    function showQuantityError(message) {
      const quantityError = document.getElementById('quantity-error');
      const quantityInput = document.getElementById('quantity-input');
      
      quantityError.innerHTML = message;
      quantityError.style.display = 'block';
      quantityInput.style.borderColor = '#dc3545';
    }

    // Global toast notification function
    function showToast(message, type = 'success') {
        console.log('=== SHOWING TOAST ===');
        console.log('Message:', message);
        console.log('Type:', type);
        
        const bgColor = type === 'success' ? '#d4edda' : (type === 'error' ? '#f8d7da' : '#d1ecf1');
        const borderColor = type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : '#17a2b8');
        const textColor = type === 'success' ? '#155724' : (type === 'error' ? '#721c24' : '#0c5460');
        const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle');
        const title = type === 'success' ? 'Thành công!' : (type === 'error' ? 'Lỗi!' : 'Thông báo!');
        
        // Đảm bảo toast container tồn tại
        if ($('#toast-container').length === 0) {
            $('body').append('<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
        }
        
        const toast = $(`
            <div class="toast alert" 
                 style="display: none; margin-bottom: 15px; padding: 20px; border-radius: 8px; 
                        background: ${bgColor}; border: 2px solid ${borderColor}; color: ${textColor};
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 350px; max-width: 500px; font-size: 16px;
                        position: relative; z-index: 10000;">
                <div style="display: flex; align-items: center;">
                    <i class="fa ${icon}" style="font-size: 24px; margin-right: 15px;"></i>
                    <div style="flex: 1;">
                        <strong>${title}</strong><br>
                        ${message}
                    </div>
                    <button type="button" class="close" onclick="$(this).closest('.toast').fadeOut()" 
                            style="background: none; border: none; font-size: 24px; cursor: pointer; color: ${textColor}; margin-left: 10px;">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        toast.fadeIn(400).delay(6000).fadeOut(600, function() {
            $(this).remove();
        });
        
        console.log('Toast added to container');
    }

    // Global function để xử lý submit add to cart
    function submitAddToCart($form, $submitBtn, originalText) {
            let isRedirecting = false;
            
            console.log('=== AJAX REQUEST START ===');
            console.log('URL:', $form.attr('action'));
            console.log('Method: POST');
            console.log('Data:', $form.serialize());
            console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
            
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                timeout: 10000, // 10 second timeout
                statusCode: {
                    400: function(xhr) {
                        // Handle business logic errors (like stock issues)
                        console.log('=== HTTP 400 - Business Logic Error ===');
                        console.log('Response:', xhr.responseJSON);
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            // Cập nhật stock info nếu có
                            if (xhr.responseJSON.current_stock !== undefined) {
                                currentStock = xhr.responseJSON.current_stock;
                                currentCartQuantity = xhr.responseJSON.cart_quantity || 0;
                                availableToAdd = xhr.responseJSON.available_to_add || 0;
                                updateStockDisplay();
                                updateQuantityConstraints();
                            }
                            
                            showToast(xhr.responseJSON.message, 'error');
                        } else {
                            showToast('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
                        }
                    }
                },
                success: function(response) {
                    console.log('=== AJAX SUCCESS ===');
                    console.log('Full response:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response success field:', response.success);
                    
                    if (response.success === true) {
                        console.log('Success! Updating cart count and redirecting...'); // Debug log
                        console.log('Redirect URL from response:', response.redirect); // Debug log
                        
                        isRedirecting = true;
                        
                        // Update cart count immediately
                        updateCartCount();
                        
                        try {
                            // Redirect ngay lập tức đến trang giỏ hàng
                            const redirectUrl = response.redirect || '{{ route("client.cart") }}' || '/cart';
                            console.log('Final redirect URL:', redirectUrl); // Debug log
                            
                            // Thử nhiều cách redirect
                            if (window.location.replace) {
                                window.location.replace(redirectUrl);
                            } else {
                                window.location.href = redirectUrl;
                            }
                            
                            // Backup redirect sau 500ms nếu chưa redirect
                            setTimeout(function() {
                                if (window.location.pathname !== '/cart') {
                                    console.log('Backup redirect triggered'); // Debug log
                                    window.location.href = '/cart';
                                }
                            }, 500);
                            
                        } catch (redirectError) {
                            console.error('Redirect error:', redirectError); // Debug log
                            // Fallback thủ công
                            window.location.href = '/cart';
                        }
                        
                    } else if (response.success === false) {
                        // Server trả về success: false (business logic error)
                        console.log('Business logic error:', response.message);
                        
                        // Cập nhật stock info nếu có
                        if (response.current_stock !== undefined) {
                            currentStock = response.current_stock;
                            currentCartQuantity = response.cart_quantity || 0;
                            availableToAdd = response.available_to_add || 0;
                            updateStockDisplay();
                            updateQuantityConstraints();
                        }
                        
                        showToast(response.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
                    } else {
                        // Response không có success field hoặc unexpected format
                        console.log('Unexpected response format:', response);
                        showToast('Phản hồi từ server không đúng định dạng!', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('=== AJAX ERROR ===');
                    console.log('XHR Status:', xhr.status);
                    console.log('Status Text:', status);
                    console.log('Error:', error);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response JSON:', xhr.responseJSON);
                    
                    let errorMessage = 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!';
                    
                    if (status === 'timeout') {
                        errorMessage = 'Request timeout! Vui lòng thử lại.';
                    } else if (xhr.status === 0) {
                        errorMessage = 'Không thể kết nối đến server! Kiểm tra kết nối mạng.';
                    } else if (xhr.status === 419) {
                        errorMessage = 'CSRF token expired! Vui lòng refresh trang và thử lại.';
                    } else if (xhr.status === 422) {
                        // Validation errors từ Laravel
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessages = [];
                            for (let field in errors) {
                                errorMessages.push(errors[field][0]);
                            }
                            errorMessage = errorMessages.join('<br>');
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Error message từ controller
                        errorMessage = xhr.responseJSON.message;
                        
                        // Nếu là lỗi stock (status 400), refresh stock data
                        if (xhr.status === 400) {
                            console.log('Stock error detected, updating stock info...');
                            
                            if (xhr.responseJSON.current_stock !== undefined) {
                                currentStock = xhr.responseJSON.current_stock;
                                currentCartQuantity = xhr.responseJSON.cart_quantity || 0;
                                availableToAdd = xhr.responseJSON.available_to_add || 0;
                                updateStockDisplay();
                                updateQuantityConstraints();
                                
                                console.log('Updated stock info:', {
                                    currentStock,
                                    currentCartQuantity,
                                    availableToAdd
                                });
                            }
                        }
                    } else if (xhr.status === 404) {
                        errorMessage = 'Sản phẩm hoặc phiên bản không tồn tại!';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Lỗi server! Vui lòng thử lại sau.';
                    }
                    
                    showToast(errorMessage, 'error');
                },
                complete: function() {
                    console.log('=== AJAX COMPLETE ===');
                    console.log('Is redirecting:', isRedirecting);
                    
                    // Chỉ re-enable button nếu không redirect (tức là có lỗi)
                    if (!isRedirecting) {
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                }
            }).fail(function(xhr, status, error) {
                console.log('=== AJAX FAIL (alternative handler) ===');
                console.log('Status:', status);
                console.log('Error:', error);
                
                // Fallback: Submit form thông thường nếu AJAX fail hoàn toàn
                if (status === 'timeout' || xhr.status === 0) {
                    console.log('AJAX failed completely, trying normal form submission...');
                    showToast('Đang thử phương thức khác...', 'info');
                    
                    setTimeout(function() {
                        // Remove AJAX handler temporarily
                        $form.off('submit');
                        
                        // Add hidden field to indicate fallback
                        $form.append('<input type="hidden" name="fallback_submit" value="1">');
                        
                        // Submit form normally
                        $form.get(0).submit();
                                         }, 1000);
                }
            });
        }
        
    // Global function cập nhật số lượng giỏ hàng - sử dụng function từ navbar
    function updateCartCount() {
        // Use global refresh function if available
        if (window.refreshCartCount) {
            window.refreshCartCount();
        } else {
            // Fallback to local implementation
            $.ajax({
                url: '{{ route("client.cart-count") }}',
                method: 'GET',
                success: function(response) {
                    // Cập nhật số lượng trong header (nếu có)
                    $('.cart-count, .cart-counter, #cart-count').text(response.count);
                    
                    // Update navbar cart count if available
                    if (window.updateCartCount) {
                        window.updateCartCount(response.count);
                    }
                }
            });
        }
    }

    $(document).ready(function() {
        // CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Thêm event listener cho quantity input
        $('#quantity-input').on('input change', function() {
            validateQuantity();
        });
        
        // Xử lý form thêm vào giỏ hàng
        $('#add-to-cart-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.html();
            
            // Kiểm tra xem đã chọn variant chưa
            const variantId = $form.find('select[name="variant_id"]').val();
            if (!variantId) {
                showToast('Vui lòng chọn phiên bản sản phẩm!', 'error');
                return;
            }
            
            // Disable button và hiển thị checking state
            $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang kiểm tra...');
            
            // Refresh stock real-time trước khi submit
            $.ajax({
                url: '{{ route("client.variant-stock") }}',
                method: 'GET',
                data: { variant_id: variantId },
                success: function(stockResponse) {
                    if (stockResponse.success) {
                        // Cập nhật thông tin stock mới nhất
                        currentStock = stockResponse.current_stock;
                        currentCartQuantity = stockResponse.cart_quantity;
                        availableToAdd = stockResponse.available_to_add;
                        
                        // Cập nhật UI với thông tin mới
                        updateStockDisplay();
                        updateQuantityConstraints();
                        
                        // Validate lại với stock mới nhất
                        if (!validateQuantity()) {
                            showToast('Số lượng không hợp lệ với tình trạng kho hiện tại!', 'error');
                            $submitBtn.prop('disabled', false).html(originalText);
                            return;
                        }
                        
                        if (availableToAdd <= 0) {
                            showToast('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng kiểm tra lại!', 'error');
                            $submitBtn.prop('disabled', false).html(originalText);
                            return;
                        }
                        
                        // Proceed with adding to cart
                        $submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Đang thêm...');
                        submitAddToCart($form, $submitBtn, originalText);
                        
                    } else {
                        showToast('Không thể kiểm tra tình trạng kho. Vui lòng thử lại!', 'error');
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                },
                error: function() {
                    showToast('Lỗi khi kiểm tra kho. Vui lòng thử lại!', 'error');
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Load cart count khi trang được tải
        updateCartCount();
        
        // Debug helper - click anywhere on page to test route generation
        $(document).on('dblclick', function() {
            console.log('=== DEBUG INFO ===');
            console.log('Cart route:', '{{ route("client.cart") }}');
            console.log('Add to cart route:', '{{ route("client.add-to-cart") }}');
            console.log('Current URL:', window.location.href);
            console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
            console.log('Form action:', $('#add-to-cart-form').attr('action'));
            
            // Test route directly
            fetch('{{ route("client.cart") }}')
                .then(response => {
                    console.log('Cart route test - Status:', response.status);
                    console.log('Cart route test - OK:', response.ok);
                })
                .catch(error => {
                    console.log('Cart route test - Error:', error);
                });
        });
        
        // Periodic stock refresh (mỗi 30 giây) nếu đã chọn variant
        setInterval(function() {
            const variantId = $('#variant-select').val();
            if (variantId && !isLoadingStock) {
                fetchVariantStock(variantId);
            }
        }, 30000); // 30 seconds
        
        // Refresh stock khi user focus lại vào tab/window
        $(window).on('focus', function() {
            const variantId = $('#variant-select').val();
            if (variantId && !isLoadingStock) {
                fetchVariantStock(variantId);
            }
        });
    });
  </script>
@endsection
