@extends('layouts.client')

@section('title', 'Product')

@section('content')
  <!-- Load JavaScript fixes -->
  <script src="{{ asset('client/assets/js/product-page-fix.js') }}"></script>

  <!-- Link to custom product styles -->
  <link rel="stylesheet" href="{{ asset('client/assets/css/product-custom.css') }}">
  
  <!-- Hidden data for JavaScript -->
  <div data-min-price="{{ $minPrice }}" data-max-price="{{ $maxPrice }}" style="display: none;"></div>

  <!-- Banner Section -->
  <section class="module bg-dark-60" style="
    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('client/assets/images/section-6.jpg') }}');
    background-size: cover;
    background-position: center;
    min-height: 200px;
    display: flex;
    align-items: center;
    position: relative;
  ">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3 text-center">
          <h2 class="module-title font-alt" style="color: #fff; margin-bottom: 10px; font-size: 24px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Cửa Hàng Sản Phẩm</h2>
          <div class="module-subtitle font-serif" style="color: rgba(255,255,255,0.9); font-size: 14px; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Khám phá bộ sưu tập sản phẩm chất lượng cao với nhiều lựa chọn đa dạng</div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="module-small">
    <div class="container">
      <!-- Enhanced Search Form -->
      <div class="search-container bg-white rounded shadow-sm">
        <form method="GET" action="{{ route('client.product') }}" class="search-form" id="searchForm">
          <div class="search-header">
            <h3 class="search-title">
              <i class="fa fa-filter text-primary"></i>
              Tìm kiếm & Lọc sản phẩm
            </h3>
            <div class="search-summary">
              <span class="total-products badge badge-primary">{{ $products->total() }} sản phẩm</span>
              @if(request()->hasAny(['name', 'category_id', 'min_price', 'max_price']))
                <a href="{{ route('client.product') }}" class="clear-all-btn btn btn-outline-danger btn-sm">
                  <i class="fa fa-times"></i> Xóa tất cả
                </a>
              @endif
              <!-- Toggle Button for Collapse/Expand -->
              <button type="button" class="filter-toggle-btn btn btn-link" id="filterToggle" 
                      title="Thu gọn/Mở rộng bộ lọc" 
                      aria-expanded="true" 
                      aria-controls="searchContent"
                      aria-label="Thu gọn hoặc mở rộng bộ lọc tìm kiếm">
                <i class="fa fa-chevron-up" id="toggleIcon" aria-hidden="true"></i>
                <span class="toggle-text">Thu gọn</span>
              </button>
            </div>
          </div>

          <div class="search-content" id="searchContent">
            <div class="row">
              <!-- Search by Name -->
              <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="search-field">
                  <label class="field-label">
                    <i class="fa fa-search"></i>
                    Tìm kiếm sản phẩm
                  </label>
                  <div class="input-wrapper">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm..." 
                           value="{{ request('name') }}">
                    @if(request('name'))
                      <span class="input-clear" onclick="clearInput('name')">
                        <i class="fa fa-times"></i>
                      </span>
                    @endif
                  </div>
                </div>
              </div>
              
              <!-- Category Filter -->
              <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="search-field">
                  <label class="field-label">
                    <i class="fa fa-tags"></i>
                    Danh mục
                  </label>
                  <div class="select-wrapper">
                    <select name="category_id" class="form-control">
                      <option value="">Tất cả danh mục</option>
                      @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }}
                        </option>
                      @endforeach
                    </select>
                    <i class="fa fa-chevron-down select-arrow"></i>
                  </div>
                </div>
              </div>  
              <!-- Price Range Filter -->
              <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="search-field">
                  <label class="field-label">
                    <i class="fa fa-money-bill-alt"></i>
                    Khoảng giá (VNĐ)
                  </label>
                  <div class="price-range-container">
                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price') }}">
                    
                    <!-- Price display -->
                    <div class="price-display">
                      <span class="price-label-min">{{ number_format(request('min_price', $minPrice)) }}đ</span>
                      <span class="price-separator">-</span>
                      <span class="price-label-max">{{ number_format(request('max_price', $maxPrice)) }}đ</span>
                    </div>
                    
                    <!-- Range sliders - Separated for better interaction -->
                    <div class="range-sliders-separated">
                      <div class="slider-group">
                        <label class="slider-label">Giá tối thiểu:</label>
                        <input type="range" 
                               id="min_range" 
                               class="range-slider-single range-min"
                               min="{{ $minPrice }}" 
                               max="{{ $maxPrice }}" 
                               value="{{ request('min_price') ?: $minPrice }}" 
                               step="{{ max(1, ($maxPrice - $minPrice) / 100) }}">
                        <span class="slider-value" id="min_value_display">{{ number_format(request('min_price') ?: $minPrice) }}đ</span>
                      </div>
                      
                      <div class="slider-group">
                        <label class="slider-label">Giá tối đa:</label>
                        <input type="range" 
                               id="max_range" 
                               class="range-slider range-slider-single"
                               min="{{ $minPrice }}" 
                               max="{{ $maxPrice }}" 
                               value="{{ request('max_price') ?: $maxPrice }}" 
                               step="{{ max(1, ($maxPrice - $minPrice) / 100) }}">
                        <span class="slider-value" id="max_value_display">{{ number_format(request('max_price') ?: $maxPrice) }}đ</span>
                      </div>
                    </div>
                    
                    <!-- Manual input fields -->
                    <div class="price-inputs">
                      <div class="price-input-group">
                        <input type="number" 
                               class="form-control price-input price-input-min" 
                               placeholder="Từ {{ number_format($minPrice) }}" 
                               min="{{ $minPrice }}" 
                               max="{{ $maxPrice }}"
                               step="{{ max(1, ($maxPrice - $minPrice) / 1000) }}"
                               value="{{ request('min_price') }}">
                      </div>
                      <span class="price-input-separator">-</span>
                      <div class="price-input-group">
                        <input type="number" 
                               class="form-control price-input price-input-max" 
                               placeholder="Đến {{ number_format($maxPrice) }}" 
                               min="{{ $minPrice }}" 
                               max="{{ $maxPrice }}"
                               step="{{ max(1, ($maxPrice - $minPrice) / 1000) }}"
                               value="{{ request('max_price') }}">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Sort By -->
              <div class="col-lg-2 col-md-4 col-sm-12">
                <div class="search-field">
                  <label class="field-label">
                    <i class="fa fa-sort"></i>
                    Sắp xếp theo
                  </label>
                  <div class="select-wrapper">
                    <select name="sort_by" class="form-control">
                      <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                      <option value="price_low_high" {{ request('sort_by') == 'price_low_high' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                      <option value="price_high_low" {{ request('sort_by') == 'price_high_low' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                      <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên: A-Z</option>
                      <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên: Z-A</option>
                    </select>
                    <i class="fa fa-chevron-down select-arrow"></i>
                  </div>
                </div>
              </div>
              
              <!-- Search Actions -->
              <div class="col-lg-2 col-md-12 col-sm-12">
                <div class="search-actions">
                  <button type="submit" class="btn-primary-search" title="Tìm kiếm">
                    <i class="fa fa-search"></i>
                    <span class="btn-text">Tìm Kiếm</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Search Results Summary -->
          @if(request()->hasAny(['name', 'category_id', 'min_price', 'max_price', 'sort_by']))
            <div class="search-results-bar">
              <div class="active-filters">
                <span class="filter-label">Bộ lọc đang áp dụng:</span>
                
                @if(request('name'))
                  <span class="filter-tag">
                    <i class="fa fa-search"></i>
                    "{{ request('name') }}"
                    <a href="{{ request()->fullUrlWithQuery(['name' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('category_id'))
                  @php $selectedCategory = $categories->firstWhere('id', request('category_id')) @endphp
                  <span class="filter-tag">
                    <i class="fa fa-tag"></i>
                    {{ $selectedCategory->name ?? '' }}
                    <a href="{{ request()->fullUrlWithQuery(['category_id' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('min_price') || request('max_price'))
                  <span class="filter-tag">
                    <i class="fa fa-money-bill-alt"></i>
                    {{ number_format(request('min_price') ?: 0) }}đ - {{ number_format(request('max_price') ?: $maxPrice) }}đ
                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('sort_by') && request('sort_by') != 'latest')
                  <span class="filter-tag">
                    <i class="fa fa-sort"></i>
                    @switch(request('sort_by'))
                      @case('price_low_high')
                        Giá: Thấp đến Cao
                        @break
                      @case('price_high_low')  
                        Giá: Cao đến Thấp
                        @break
                      @case('name_asc')
                        Tên: A-Z
                        @break
                      @case('name_desc')
                        Tên: Z-A
                        @break
                    @endswitch
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
              </div>
              
              <div class="results-count">
                Tìm thấy <strong>{{ $products->total() }}</strong> kết quả
              </div>
            </div>
          @endif
        </form>
      </div>
    </div>
  </section>
  
  <hr class="divider-w">
  
  <section class="module-small products-section">
    <div class="container">
      <!-- Products Grid -->
      <div class="products-grid-wrapper">
        <div class="products-container">
          <div class="row">
            @forelse ($products as $product)
            @php
              $variant = $product->variants->first();
            @endphp
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="shop-item">
                <div class="shop-item-image">
                  <a href="{{ route('client.single-product', $product->id) }}">
                    @if($product->image)
                      <img src="{{ asset('storage/' . $product->image) }}" 
                           alt="{{ $product->name }}" 
                           class="product-image"
                           loading="lazy"
                           onerror="handleImageError(this)" />
                      <div class="product-image-placeholder" style="display: none;">
                        <i class="fa fa-image"></i>
                        <span>{{ $product->name }}</span>
                      </div>
                    @else
                      <div class="product-image-placeholder">
                        <i class="fa fa-image"></i>
                        <span>{{ $product->name }}</span>
                      </div>
                    @endif
                  </a>
                  
                  <!-- Product badges -->
                  @if($product->variants && $product->variants->count() > 1)
                    <div class="product-badge">
                      <span class="badge badge-info">{{ $product->variants->count() }} phiên bản</span>
                    </div>
                  @endif
                  
                  <!-- Hover overlay -->
                  <div class="shop-item-detail">
                  </div>
                </div>
                <div class="shop-item-content">
                  <h4 class="shop-item-title font-alt">
                    <a href="{{ route('client.single-product', $product->id) }}">{{ $product->name }}</a>
                  </h4>
                  <div class="shop-item-price">
                    @if($product->variants && $product->variants->count() > 0)
                      @if($variant->price && $variant->promotion_price && $variant->promotion_price < $variant->price)
                        <span class="old-price">{{ number_format($variant->price, 0, ',', '.') }}đ</span>
                        <span class="new-price">{{ number_format($variant->promotion_price, 0, ',', '.') }}đ</span>
                      @elseif($variant->price)
                        <span class="price">{{ number_format($variant->price, 0, ',', '.') }}đ</span>
                      @else
                        <span class="price">Liên hệ</span>
                      @endif
                    @else
                      @if($product->price && $product->promotion_price && $product->promotion_price < $product->price)
                        <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                        <span class="new-price">{{ number_format($product->promotion_price, 0, ',', '.') }}₫</span>
                      @elseif($product->price)
                        <span class="price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                      @else
                        <span class="price">Liên hệ</span>
                      @endif
                    @endif
                  </div>
                  <div class="shop-item-stats">
                    <div class="stock-status">
                      <small>
                        @php
                          $hasStock = false;
                          $totalStock = 0;
                          
                          if($product->variants && $product->variants->count() > 0) {
                            $totalStock = $product->variants->sum('stock_quantity');
                            $hasStock = $totalStock > 0;
                          } else {
                            $totalStock = $product->stock_quantity ?? 0;
                            $hasStock = $totalStock > 0;
                          }
                        @endphp
                        
                        @if($hasStock)
                          <i class="fa fa-check text-success"></i> Còn hàng 
                          @if($product->variants && $product->variants->count() > 1)
                            ({{ $totalStock }} - {{ $product->variants->count() }} phiên bản)
                          @else
                            ({{ $totalStock }})
                          @endif
                        @else
                          <i class="fa fa-times text-danger"></i> Hết hàng
                        @endif
                      </small>
                    </div>
                    
                    <!-- Favorite Count Display -->
                    <div class="favorite-stats" style="display: block !important; visibility: visible !important; text-align: center; margin: 8px 0; color: #666; font-size: 12px;">
                      <small style="display: inline-block !important; visibility: visible !important; background: #f8f9fa; padding: 4px 8px; border-radius: 4px; border: 1px solid #e9ecef;">
                        ❤️ <span class="product-{{ $product->id }}-favorites favorite-count-display" 
                              style="font-weight: bold !important; color: #6c757d !important; display: inline !important; visibility: visible !important;"
                              data-product-id="{{ $product->id }}"
                              data-count="{{ $product->favorites_count ?? 0 }}">{{ $product->favorites_count ?? 0 }}</span> yêu thích 
                        | 
                        👁️ <span style="font-weight: bold; color: #28a745;">{{ $product->view ?? 0 }}</span> lượt xem
                      </small>
                    </div>
                    
                    <!-- Product Action Buttons -->
                    <div class="product-actions" style="text-align: center; margin: 8px 0; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                      <!-- Favorite Button -->
                      @auth
                        @php
                          $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
                        @endphp
                        <button class="btn btn-xs {{ $isFavorited ? 'btn-danger remove-favorite' : 'btn-outline-danger add-favorite' }}" 
                                data-product-id="{{ $product->id }}"
                                title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                          <i class="fa {{ $isFavorited ? 'fa-heart' : 'fa-heart-o' }}"></i> {{ $isFavorited ? 'Bỏ thích' : 'Yêu thích' }}
                        </button>
                      @else
                        <a href="{{ route('login') }}" class="btn btn-xs btn-outline-danger" title="Đăng nhập để yêu thích">
                          <i class="fa fa-heart-o"></i> Yêu thích
                        </a>
                      @endauth
                      
                      <!-- Add to Cart Button -->
                      @if($hasStock)
                        @if($product->variants && $product->variants->count() > 1)
                          <a href="{{ route('client.single-product', $product->id) }}" class="btn btn-xs btn-info btn-select-variant" title="Chọn phiên bản">
                            <i class="fa fa-list-alt"></i> Phiên bản
                          </a>
                        @else
                          <form action="{{ route('client.add-to-cart') }}" method="POST" class="add-to-cart-form-quick" data-product-id="{{ $product->id }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @if($variant)
                              <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                            @endif
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-xs btn-success btn-add-cart" title="Thêm vào giỏ hàng">
                              <i class="fa fa-shopping-cart"></i> Mua ngay
                            </button>
                          </form>
                        @endif
                      @else
                        <span class="btn btn-xs btn-secondary disabled">
                          <i class="fa fa-times"></i> Hết hàng
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="empty-products-container">
                <div class="empty-products">
                  <div class="empty-icon">
                    <i class="fa fa-search"></i>
                  </div>
                  <h3 class="empty-title">Không tìm thấy sản phẩm</h3>
                  <p class="empty-description">Không có sản phẩm nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                  <div class="empty-actions">
                    <a href="{{ route('client.product') }}" class="btn btn-primary btn-lg btn-explore">
                      <i class="fa fa-search"></i> Xem tất cả sản phẩm
                    </a>
                  </div>
                </div>
              </div>
            </div>
          @endforelse
        </div>
      </div>
      
      <!-- Pagination -->
      @if($products->hasPages())
        <div class="pagination-wrapper">
          {{ $products->appends(request()->query())->links() }}
        </div>
      @endif
    </div>
  </section>

  <!-- JavaScript and remaining content -->
  <script>
    // Handle image loading errors
    function handleImageError(img) {
      console.log('Image failed to load:', img.src);
      img.style.display = 'none';
      const placeholder = img.nextElementSibling;
      if (placeholder && placeholder.classList.contains('product-image-placeholder')) {
        placeholder.style.display = 'flex';
      }
    }
    
    // Enhanced Add to Cart Functionality
    document.addEventListener('DOMContentLoaded', function() {
      console.log('✅ Add to Cart system initialized');
      
      // Check if jQuery is available
      if (typeof $ === 'undefined') {
        console.warn('jQuery not available, add to cart functionality disabled');
        return;
      }
      
      // Handle add to cart form submissions
      $(document).on('submit', '.add-to-cart-form-quick', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $form = $(this);
        const $button = $form.find('.btn-add-cart');
        const productId = $form.data('product-id');
        
        console.log('🛒 Add to cart clicked for product:', productId);
        
        // Prevent double clicks
        if ($button.hasClass('loading') || $button.prop('disabled')) {
          return;
        }
        
        // Add loading state
        $button.addClass('loading').prop('disabled', true);
        const originalHtml = $button.html();
        $button.html('<i class="fa fa-spinner fa-spin"></i> Đang thêm...');
        
        $.ajax({
          url: $form.attr('action'),
          method: 'POST',
          data: $form.serialize(),
          timeout: 10000,
          success: function(response) {
            $button.removeClass('loading').prop('disabled', false);
            
            if (response.success) {
              $button.html('<i class="fa fa-check"></i> Đã thêm!');
              $button.removeClass('btn-success').addClass('btn-info');
              
              if (window.updateCartCount) {
                window.updateCartCount();
              }
              
              if (window.RealtimeNotifications && window.RealtimeNotifications.showToast) {
                window.RealtimeNotifications.showToast(
                  'success',
                  'Thành công!',
                  response.message
                );
              }
              
              setTimeout(function() {
                $button.html(originalHtml);
                $button.removeClass('btn-info').addClass('btn-success');
              }, 3000);
              
            } else {
              $button.html(originalHtml);
              if (typeof Swal !== 'undefined') {
                Swal.fire('Lỗi!', response.message, 'error');
              } else {
                alert(response.message);
              }
            }
          },
          error: function(xhr) {
            $button.removeClass('loading').prop('disabled', false);
            $button.html(originalHtml);
            
            const message = xhr.responseJSON?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
            if (typeof Swal !== 'undefined') {
              Swal.fire('Lỗi!', message, 'error');
            } else {
              alert(message);
            }
          }
        });
      });
    });
    
    // Enhanced Favorite Button Functionality
    document.addEventListener('DOMContentLoaded', function() {
      console.log('✅ Favorite system initialized');
      
      // Check if jQuery is available
      if (typeof $ === 'undefined') {
        console.warn('jQuery not available, favorite functionality disabled');
        return;
      }
      
      // Remove all existing handlers to prevent conflicts
      $(document).off('click', '.add-favorite, .remove-favorite');
      $(document).off('click', '.toggle-favorite');
      $(document).off('click', '.btn-favorite-detail');
      
      // Handle favorite button clicks
      $(document).on('click', '.add-favorite, .remove-favorite', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        console.log('🎯 Favorite button clicked for product:', productId);
        
        // Prevent double clicks
        if ($button.hasClass('loading') || $button.prop('disabled')) {
          return;
        }
        
        // Add loading state
        $button.addClass('loading').prop('disabled', true);
        const originalHtml = $button.html();
        $button.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
        
        // Mark product as processing
        const productCard = $button.closest('.shop-item')[0];
        if (productCard) {
          window.handleProductVisibility(productCard, true);
        }
        
        // Determine action
        const isCurrentlyFavorited = $button.hasClass('remove-favorite');
        const url = isCurrentlyFavorited ? '{{ route("client.favorite.remove") }}' : '{{ route("client.favorite.add") }}';
        const action = isCurrentlyFavorited ? 'remove' : 'add';
        
        $.ajax({
          url: url,
          method: 'POST',
          data: {
            product_id: productId,
            _token: '{{ csrf_token() }}'
          },
          timeout: 10000,
          success: function(response) {
            $button.removeClass('loading').prop('disabled', false);
            
            // Remove processing state after success
            if (productCard) {
              window.handleProductVisibility(productCard, false);
            }
            
            if (response.success) {
              console.log('✅ Favorite action successful for product:', productId);
              
              // Update button state
              if (action === 'add') {
                $button.removeClass('add-favorite btn-outline-danger').addClass('remove-favorite btn-danger');
                $button.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                $button.html('<i class="fa fa-heart"></i> Bỏ yêu thích');
              } else {
                $button.removeClass('remove-favorite btn-danger').addClass('add-favorite btn-outline-danger');  
                $button.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                $button.html('<i class="fa fa-heart-o"></i> Yêu thích');
              }
              
              // Update all buttons for this product
              $(`[data-product-id="${productId}"]`).each(function() {
                const btn = $(this);
                // Chỉ áp dụng cho button, không phải span đếm số
                if (btn.is('button') || btn.hasClass('add-favorite') || btn.hasClass('remove-favorite')) {
                  if (action === 'add') {
                    btn.removeClass('add-favorite btn-outline-danger').addClass('remove-favorite btn-danger');
                    btn.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                    btn.html('<i class="fa fa-heart"></i> Bỏ yêu thích');
                  } else {
                    btn.removeClass('remove-favorite btn-danger').addClass('add-favorite btn-outline-danger');
                    btn.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                    btn.html('<i class="fa fa-heart-o"></i> Yêu thích');
                  }
                  btn.removeClass('loading').prop('disabled', false);
                }
              });
              
              // Update favorite count
              if (response.favorite_count !== undefined) {
                $(`.product-${productId}-favorites`).each(function() {
                  $(this).text(response.favorite_count).addClass('realtime-update');
                  setTimeout(() => {
                    $(this).removeClass('realtime-update');
                  }, 800);
                });
              }
              
              // Update navbar counter if available
              if (window.updateFavoriteCount) {
                window.updateFavoriteCount();
              } else if (window.refreshFavoriteCount) {
                window.refreshFavoriteCount();
              }
              
              // Show success message
              if (window.RealtimeNotifications && window.RealtimeNotifications.showToast) {
                window.RealtimeNotifications.showToast(
                  'success',
                  'Thành công!',
                  response.message
                );
              }
            } else {
              $button.html(originalHtml);
              if (typeof Swal !== 'undefined') {
                Swal.fire('Lỗi!', response.message, 'error');
              } else {
                alert(response.message);
              }
            }
          },
          error: function(xhr) {
            $button.removeClass('loading').prop('disabled', false);
            $button.html(originalHtml);
            
            // Remove processing state after error
            if (productCard) {
              window.handleProductVisibility(productCard, false);
            }
            
            console.log('❌ Favorite action failed for product:', productId);
            
            const message = xhr.responseJSON?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
            if (typeof Swal !== 'undefined') {
              Swal.fire('Lỗi!', message, 'error');
            } else {
              alert(message);
            }
          }
        });
      });
    });
  </script>
  
  <!-- DEBUG: Force refresh cache -->
  <script>
    // Global error handler for this page
    window.addEventListener('error', function(e) {
      console.warn('Product page error:', e.error);
      // Don't let errors break the page
      return true;
    });
    
    // Force CSS refresh
    try {
      const timestamp = new Date().getTime();
      const links = document.querySelectorAll('link[rel="stylesheet"]');
      links.forEach(link => {
        if (link.href.includes('product-custom.css')) {
          link.href = link.href + '?v=' + timestamp;
        }
      });
    } catch (error) {
      console.warn('CSS refresh failed:', error);
    }
    
    // Ensure currentUserId is available for product page
    @auth
    if (!window.currentUserId) {
      window.currentUserId = {{ auth()->user()->id }};
    }
    @else
    window.currentUserId = null;
    @endauth
    
    // Initialize page - most logic moved to product-page-fix.js
    document.addEventListener('DOMContentLoaded', function() {
      // Check and fix favorite count elements
       setTimeout(function() {
        try {
          ensureFavoriteCountsVisible();
        } catch (error) {
          console.warn('Failed to ensure favorite counts visible:', error);
        }
      }, 1000);
      
      // Re-run the visibility check periodically in case of CSS conflicts
      setInterval(function() {
        try {
          ensureFavoriteCountsVisible();
        } catch (error) {
          console.warn('Periodic favorite count check failed:', error);
        }
      }, 5000);
    });
   
    // Function to ensure favorite counts are visible
    function ensureFavoriteCountsVisible() {
      const favoriteCountElements = document.querySelectorAll('.favorite-count-display');
      
      favoriteCountElements.forEach(function(element) {
        const productId = element.getAttribute('data-product-id');
        const count = element.getAttribute('data-count') || '0';
        
        // Force styles
        element.style.display = 'inline';
        element.style.visibility = 'visible';
        element.style.opacity = '1';
        element.style.fontWeight = 'bold';
        element.style.color = '#6c757d';
        
        // Ensure text content
        if (!element.textContent || element.textContent.trim() === '') {
          element.textContent = count;
        }
        
        // Check parent containers
        let parent = element.parentElement;
        while (parent && parent !== document.body) {
          if (parent.style.display === 'none' || parent.style.visibility === 'hidden') {
            parent.style.display = '';
            parent.style.visibility = 'visible';
          }
          parent = parent.parentElement;
        }
      });
    }
  </script>

@endsection