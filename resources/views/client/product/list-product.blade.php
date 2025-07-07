@extends('layouts.client')

@section('title', 'Product')

@section('content')
  <!-- Link to custom product styles -->
  <link rel="stylesheet" href="{{ asset('client/assets/css/product-custom.css') }}">

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
      <div class="search-container">
        <form method="GET" action="{{ route('client.product') }}" class="search-form" id="searchForm">
          <div class="search-header">
            <h3 class="search-title">
              <i class="fas fa-filter"></i>
              Tìm kiếm & Lọc sản phẩm
            </h3>
            <div class="search-summary">
              <span class="total-products">{{ $products->total() }} sản phẩm</span>
              @if(request()->hasAny(['name', 'category_id', 'min_price', 'max_price']))
                <a href="{{ route('client.product') }}" class="clear-all-btn">
                  <i class="fas fa-times"></i> Xóa tất cả
                </a>
              @endif
              <!-- Toggle Button for Collapse/Expand -->
              <button type="button" class="filter-toggle-btn" id="filterToggle" 
                      title="Thu gọn/Mở rộng bộ lọc" 
                      aria-expanded="true" 
                      aria-controls="searchContent"
                      aria-label="Thu gọn hoặc mở rộng bộ lọc tìm kiếm">
                <i class="fas fa-chevron-up" id="toggleIcon" aria-hidden="true"></i>
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
                    <i class="fas fa-search"></i>
                    Tìm kiếm sản phẩm
                  </label>
                  <div class="input-wrapper">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm..." 
                           value="{{ request('name') }}">
                    @if(request('name'))
                      <span class="input-clear" onclick="clearInput('name')">
                        <i class="fas fa-times"></i>
                      </span>
                    @endif
                  </div>
                </div>
              </div>
              
              <!-- Category Filter -->
              <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="search-field">
                  <label class="field-label">
                    <i class="fas fa-tags"></i>
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
                    <i class="fas fa-chevron-down select-arrow"></i>
                  </div>
                </div>
              </div>  
              <!-- Price Range Filter -->
              <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="search-field">
                  <label class="field-label">
                    <i class="fas fa-money-bill-alt"></i>
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
                    <i class="fas fa-sort"></i>
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
                    <i class="fas fa-chevron-down select-arrow"></i>
                  </div>
                </div>
              </div>
              
              <!-- Search Actions -->
              <div class="col-lg-2 col-md-12 col-sm-12">
                <div class="search-actions">
                  <button type="submit" class="btn-primary-search" title="Tìm kiếm">
                    <i class="fas fa-search"></i>
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
                    <i class="fas fa-search"></i>
                    "{{ request('name') }}"
                    <a href="{{ request()->fullUrlWithQuery(['name' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('category_id'))
                  @php $selectedCategory = $categories->firstWhere('id', request('category_id')) @endphp
                  <span class="filter-tag">
                    <i class="fas fa-tag"></i>
                    {{ $selectedCategory->name ?? '' }}
                    <a href="{{ request()->fullUrlWithQuery(['category_id' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('min_price') || request('max_price'))
                  <span class="filter-tag">
                    <i class="fas fa-money-bill-alt"></i>
                    {{ number_format(request('min_price') ?: 0) }}đ - {{ number_format(request('max_price') ?: $maxPrice) }}đ
                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('sort_by') && request('sort_by') != 'latest')
                  <span class="filter-tag">
                    <i class="fas fa-sort"></i>
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
      <div class="products-container">
        <div class="row multi-columns-row">
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
                           loading="lazy"
                           onerror="handleImageError(this)" />
                      <div class="product-image-placeholder" style="display: none;">
                        <i class="fas fa-image"></i>
                        <span>{{ $product->name }}</span>
                      </div>
                    @else
                      <div class="product-image-placeholder">
                        <i class="fas fa-image"></i>
                        <span>{{ $product->name }}</span>
                      </div>
                    @endif
                  </a>
                  
                  <!-- Product badges -->
                  @if($product->variants->count() > 1)
                    <div class="product-badge">
                      <span class="badge badge-variants">{{ $product->variants->count() }} phiên bản</span>
                    </div>
                  @endif
                  
                  <!-- Favorite button -->
                  <div class="product-favorite-btn">
                    @php
                      $isFavorited = auth()->check() && auth()->user()->favorites()->where('product_id', $product->id)->exists();
                    @endphp
                    <button class="btn-favorite {{ $isFavorited ? 'favorited remove-favorite' : 'add-favorite' }}" 
                            data-product-id="{{ $product->id }}"
                            title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                      <i class="{{ $isFavorited ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                    </button>
                  </div>
                  
                  <!-- Hover overlay -->
                  <div class="shop-item-detail">
                    <a href="{{ route('client.single-product', $product->id) }}" class="btn btn-round btn-b">
                      <span class="fas fa-eye"></span> Xem chi tiết
                    </a>
                  </div>
                </div>
                <div class="shop-item-content">
                  <div class="product-category">{{ $product->category->name ?? 'Khác' }}</div>
                  <h4 class="shop-item-title font-alt">
                    <a href="{{ route('client.single-product', $product->id) }}">{{ $product->name }}</a>
                  </h4>
                  <div class="product-price">
                    @if($product->variants->count() > 0)
                      @php
                        $productMinPrice = $product->variants->min('price');
                      @endphp
                      <span class="price-range">
                        {{ number_format($productMinPrice) }} VND
                      </span>
                    @else
                      <span class="price-single">{{ number_format($product->price) }} VND</span>
                    @endif
                  </div>
                  <div class="product-stock">
                    @if($variant && $variant->stock_quantity > 0)
                      <span class="in-stock"><i class="fas fa-check"></i> Còn hàng</span>
                    @else
                      <span class="out-of-stock"><i class="fas fa-times"></i> Hết hàng</span>
                    @endif
                  </div>
                  
                  <!-- Additional favorite button in content area -->
                  <div class="product-actions">
                    @php
                      $isFavorited = auth()->check() && auth()->user()->favorites()->where('product_id', $product->id)->exists();
                    @endphp
                    <button class="btn-favorite-small {{ $isFavorited ? 'favorited remove-favorite' : 'add-favorite' }}" 
                            data-product-id="{{ $product->id }}"
                            title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                      <i class="{{ $isFavorited ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                      <span class="btn-text">{{ $isFavorited ? 'Yêu thích' : 'Yêu thích' }}</span>
                    </button>
                    <a href="{{ route('client.single-product', $product->id) }}" class="btn-view-details">
                      <i class="fas fa-eye"></i>
                      <span class="btn-text">Xem chi tiết</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="no-products-found">
                <div class="no-products-icon">
                  <i class="fas fa-search"></i>
                </div>
                <h3>Không tìm thấy sản phẩm</h3>
                <p>Không có sản phẩm nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                <a href="{{ route('client.product') }}" class="btn btn-primary">Xem tất cả sản phẩm</a>
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

  <!-- Remove noUiSlider CSS -->
  
  <style>
    /* Favorite Button Styling - Enhanced Visibility */
    .product-favorite-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
      opacity: 1;
      visibility: visible;
    }

    .btn-favorite {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 3px solid rgba(255, 255, 255, 1);
      background: rgba(255, 255, 255, 0.95);
      color: #666;
      font-size: 20px;
      display: flex !important;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 15;
    }

    .btn-favorite:hover {
      background: rgba(255, 255, 255, 1);
      border-color: #e74c3c;
      color: #e74c3c;
      transform: scale(1.15);
      box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
    }

    /* Always visible favorite button */
    .shop-item-image:hover .product-favorite-btn {
      opacity: 1;
      visibility: visible;
      animation: heartPulse 2s infinite;
    }

    @keyframes heartPulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    /* Make button more prominent on mobile */
    .product-favorite-btn::before {
      content: '';
      position: absolute;
      top: -5px;
      left: -5px;
      right: -5px;
      bottom: -5px;
      background: rgba(231, 76, 60, 0.1);
      border-radius: 50%;
      z-index: -1;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .btn-favorite:hover::before {
      opacity: 1;
    }

    /* Product Actions Section */
    .product-actions {
      padding: 10px 15px 15px;
      display: flex;
      gap: 8px;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid #f0f0f0;
      background: rgba(249, 249, 249, 0.8);
      margin-top: 10px;
    }

    .btn-favorite-small, .btn-view-details {
      flex: 1;
      padding: 8px 12px;
      border: none;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      cursor: pointer;
      text-align: center;
    }

    .btn-favorite-small {
      background: #fff;
      color: #666;
      border: 1px solid #ddd;
    }

    .btn-favorite-small:hover {
      background: #e74c3c;
      color: white;
      border-color: #e74c3c;
      transform: translateY(-1px);
    }

    .btn-favorite-small.favorited {
      background: #e74c3c;
      color: white;
      border-color: #e74c3c;
    }

    .btn-favorite-small.favorited:hover {
      background: #c0392b;
      border-color: #c0392b;
    }

    .btn-view-details {
      background: #007bff;
      color: white;
      border: 1px solid #007bff;
    }

    .btn-view-details:hover {
      background: #0056b3;
      border-color: #0056b3;
      transform: translateY(-1px);
      text-decoration: none;
      color: white;
    }

    .btn-favorite-small i, .btn-view-details i {
      font-size: 12px;
    }

    .btn-favorite-small .btn-text, .btn-view-details .btn-text {
      font-size: 12px;
    }

    .btn-favorite.favorited {
      background: #e74c3c;
      border-color: #e74c3c;
      color: white;
    }

    .btn-favorite.favorited:hover {
      background: #c0392b;
      border-color: #c0392b;
      color: white;
    }

    .btn-favorite:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none !important;
    }

    .btn-favorite i {
      transition: all 0.2s ease;
    }

    .btn-favorite:hover i {
      transform: scale(1.2);
    }

    /* Animation for heart beat effect */
    .btn-favorite.favorited i {
      animation: heartBeat 0.6s ease;
    }

    @keyframes heartBeat {
      0% { transform: scale(1); }
      14% { transform: scale(1.3); }
      28% { transform: scale(1); }
      42% { transform: scale(1.3); }
      70% { transform: scale(1); }
    }

    /* Loading state for favorite button */
    .btn-favorite .fa-spinner {
      color: #666;
    }

    .btn-favorite.favorited .fa-spinner {
      color: white;
    }

    /* Enhanced loading state */
    .btn-favorite.loading {
      pointer-events: none;
      opacity: 0.8;
      position: relative;
    }

    .btn-favorite.loading::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      animation: pulse 1.5s infinite;
    }

    .btn-favorite-small.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.3; }
      50% { opacity: 0.7; }
    }

    /* Ensure icons are properly sized and positioned */
    .btn-favorite i,
    .btn-favorite-small i {
      font-size: inherit;
      line-height: 1;
      vertical-align: middle;
      display: inline-block;
      width: auto;
      text-align: center;
    }

    /* Fix spinner animation */
    .btn-favorite .fa-spinner,
    .btn-favorite-small .fa-spinner {
      animation: fa-spin 1s linear infinite;
    }

    @keyframes fa-spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Enhanced hover effects */
    .btn-favorite.hover-effect {
      transform: scale(1.15);
      box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
    }

    .btn-favorite.favorited.hover-effect {
      box-shadow: 0 8px 25px rgba(231, 76, 60, 0.5);
    }

    /* Pulse animation for newly favorited items */
    .btn-favorite.just-favorited {
      animation: favoritePulse 0.8s ease;
    }

    @keyframes favoritePulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.3); box-shadow: 0 0 0 10px rgba(231, 76, 60, 0.4); }
      100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); }
    }

    /* Tooltip styling for favorite button */
    .btn-favorite[title]:hover::after {
      content: attr(title);
      position: absolute;
      bottom: -35px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 5px 8px;
      border-radius: 4px;
      font-size: 12px;
      white-space: nowrap;
      z-index: 100;
      animation: tooltipFadeIn 0.3s ease;
    }

    @keyframes tooltipFadeIn {
      from { opacity: 0; transform: translateX(-50%) translateY(5px); }
      to { opacity: 1; transform: translateX(-50%) translateY(0); }
    }

    /* Success feedback animation */
    .btn-favorite.success-feedback {
      animation: successBounce 0.6s ease;
    }

    @keyframes successBounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-10px); }
      60% { transform: translateY(-5px); }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .product-favorite-btn {
        top: 8px;
        right: 8px;
      }
      
      .btn-favorite {
        width: 45px;
        height: 45px;
        font-size: 18px;
        border-width: 2px;
      }

      .btn-favorite[title]:hover::after {
        display: none; /* Hide tooltips on mobile */
      }

      /* Always show on mobile for better UX */
      .product-favorite-btn {
        opacity: 1 !important;
        visibility: visible !important;
      }

      .product-actions {
        padding: 8px 10px 10px;
        gap: 6px;
      }

      .btn-favorite-small, .btn-view-details {
        padding: 6px 8px;
        font-size: 12px;
      }

      .btn-favorite-small .btn-text, .btn-view-details .btn-text {
        font-size: 11px;
      }
    }

    @media (max-width: 480px) {
      .product-favorite-btn {
        top: 5px;
        right: 5px;
      }
      
      .btn-favorite {
        width: 42px;
        height: 42px;
        font-size: 16px;
      }
    }
  </style>
  
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
    
    // Filter Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
      const filterToggle = document.getElementById('filterToggle');
      const searchContent = document.getElementById('searchContent');
      const toggleIcon = document.getElementById('toggleIcon');
      const toggleText = document.querySelector('.toggle-text');
      const searchForm = document.getElementById('searchForm');
      
      // Check if all elements exist
      if (!filterToggle || !searchContent || !toggleIcon || !toggleText) {
        console.error('Missing filter toggle elements:', {
          filterToggle: !!filterToggle,
          searchContent: !!searchContent,
          toggleIcon: !!toggleIcon,
          toggleText: !!toggleText
        });
        return;
      }
      
      // Check localStorage for saved state
      const isCollapsed = localStorage.getItem('filterCollapsed') === 'true';
      
      // Function to update icon and text
      function updateToggleState(collapsed) {
        if (collapsed) {
          // Collapsed state - show down arrow
          searchContent.classList.add('collapsed');
          filterToggle.classList.add('collapsed');
          if (searchForm) searchForm.classList.add('filter-collapsed');
          
          // Change icon to down arrow
          toggleIcon.classList.remove('fas', 'fa-chevron-up');
          toggleIcon.classList.add('fas', 'fa-chevron-down');
          toggleText.textContent = 'Mở rộng';
          filterToggle.setAttribute('aria-expanded', 'false');
          filterToggle.setAttribute('title', 'Mở rộng bộ lọc');
          
          console.log('✅ Set to collapsed state - down arrow');
        } else {
          // Expanded state - show up arrow
          searchContent.classList.remove('collapsed');
          filterToggle.classList.remove('collapsed');
          if (searchForm) searchForm.classList.remove('filter-collapsed');
          
          // Change icon to up arrow  
          toggleIcon.classList.remove('fas', 'fa-chevron-down');
          toggleIcon.classList.add('fas', 'fa-chevron-up');
          toggleText.textContent = 'Thu gọn';
          filterToggle.setAttribute('aria-expanded', 'true');
          filterToggle.setAttribute('title', 'Thu gọn bộ lọc');
          
          console.log('✅ Set to expanded state - up arrow');
        }
        
        // Force icon update
        console.log('Icon classes after update:', toggleIcon.className);
      }
      
      // Apply initial state
      updateToggleState(isCollapsed);
      
      // Force icon class reset to ensure proper display
      setTimeout(function() {
        const currentState = searchContent.classList.contains('collapsed');
        if (currentState) {
          toggleIcon.className = 'fas fa-chevron-down';
        } else {
          toggleIcon.className = 'fas fa-chevron-up';
        }
        console.log('Forced icon reset. Final classes:', toggleIcon.className);
      }, 50);
      
      // Toggle functionality with enhanced UX
      filterToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Prevent multiple clicks during animation
        if (filterToggle.disabled) {
          console.log('Button disabled, ignoring click');
          return;
        }
        
        const isCurrentlyCollapsed = searchContent.classList.contains('collapsed');
        console.log('Current state:', isCurrentlyCollapsed ? 'collapsed' : 'expanded');
        
        // Disable button during animation
        filterToggle.disabled = true;
        filterToggle.style.pointerEvents = 'none';
        
        // Toggle state
        const newCollapsedState = !isCurrentlyCollapsed;
        updateToggleState(newCollapsedState);
        
        // Save state to localStorage
        localStorage.setItem('filterCollapsed', newCollapsedState.toString());
        console.log('Saved state to localStorage:', newCollapsedState);
        
        // Scroll to form if expanding and needed
        if (!newCollapsedState) {
          setTimeout(function() {
            const formRect = searchContent.getBoundingClientRect();
            if (formRect.bottom > window.innerHeight) {
              searchContent.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
              });
            }
          }, 100);
        }
        
        // Re-enable button after animation completes
        setTimeout(function() {
          filterToggle.disabled = false;
          filterToggle.style.pointerEvents = 'auto';
          console.log('Button re-enabled');
        }, 450); // Slightly longer than CSS animation
      });
      
      // Debug: Log current state
      console.log('Filter toggle initialized. Current state:', {
        collapsed: searchContent.classList.contains('collapsed'),
        iconClass: toggleIcon.className,
        buttonText: toggleText.textContent,
        ariaExpanded: filterToggle.getAttribute('aria-expanded')
      });
      
      // Find all product image links
      const productImageLinks = document.querySelectorAll('.shop-item-image a');
      console.log('Found product image links:', productImageLinks.length);
      
      productImageLinks.forEach((link, index) => {
        console.log(`Link ${index}:`, link.href);
        
        // Ensure click event works
        link.addEventListener('click', function(e) {
          console.log('Product image clicked:', this.href);
          // Force navigation if needed
          if (this.href) {
            window.location.href = this.href;
          }
        });
        
        // Add visual feedback
        link.style.cursor = 'pointer';
        link.style.display = 'block';
        link.style.width = '100%';
        link.style.height = '100%';
      });
    });
    
    // Clear input function for the clear buttons
    function clearInput(inputName) {
      const input = document.querySelector(`input[name="${inputName}"]`);
      if (input) {
        input.value = '';
        // Submit form after clearing
        document.getElementById('searchForm').submit();
      }
    }
    
    // Debug function to check form before submit
    function debugFormData() {
      const form = document.getElementById('searchForm');
      const formData = new FormData(form);
      console.log('=== FORM DEBUG ===');
      for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
      }
      console.log('Min price input:', document.getElementById('min_price').value);
      console.log('Max price input:', document.getElementById('max_price').value);
      console.log('Min range:', document.getElementById('min_range').value);
      console.log('Max range:', document.getElementById('max_range').value);
      console.log('Form action:', form.action);
      console.log('Form method:', form.method);
      console.log('=== END FORM DEBUG ===');
      return formData;
    }
    
    // Enhanced Favorite Button Functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Ensure favorites.js functionality works
      console.log('✅ Favorite system initialized');
      
      // Add visual indicator for favorite buttons
      setTimeout(function() {
        const favoriteButtons = document.querySelectorAll('.btn-favorite, .btn-favorite-small, .btn-favorite-detail');
        console.log(`Found ${favoriteButtons.length} favorite buttons on the page`);
        
        // Add a subtle glow effect to make buttons more visible
        favoriteButtons.forEach(function(btn, index) {
          btn.style.outline = '2px solid transparent';
          btn.style.outlineOffset = '2px';
          
          // Add a subtle animation on page load
          setTimeout(function() {
            btn.style.outline = '2px solid rgba(231, 76, 60, 0.3)';
            setTimeout(function() {
              btn.style.outline = '2px solid transparent';
            }, 1000);
          }, index * 200);
        });
      }, 500);
      
      // Handle favorite button clicks with improved feedback
      $(document).on('click', '.btn-favorite, .btn-favorite-small, .btn-favorite-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        console.log('🎯 Favorite button clicked:', {
          productId: productId,
          isLoading: $button.hasClass('loading'),
          isFavorited: $button.hasClass('favorited'),
          buttonClasses: $button.attr('class'),
          iconClasses: $button.find('i').attr('class')
        });
        
        // Prevent double clicks
        if ($button.hasClass('loading') || $button.prop('disabled')) {
          console.log('⚠️ Button is loading or disabled, ignoring click');
          return;
        }
        
        // Check if user is authenticated
        @guest
          console.log('🔒 User not authenticated, showing login prompt');
          // Show login required message
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              title: 'Cần đăng nhập',
              text: 'Vui lòng đăng nhập để sử dụng tính năng yêu thích',
              icon: 'info',
              showCancelButton: true,
              confirmButtonText: 'Đăng nhập',
              cancelButtonText: 'Hủy',
              confirmButtonColor: '#007bff'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '{{ route("login") }}';
              }
            });
          } else {
            alert('Vui lòng đăng nhập để sử dụng tính năng này');
            window.location.href = '{{ route("login") }}';
          }
          return;
        @endguest
        
        // Let favorites.js handle the actual functionality
        if (window.favoriteManager) {
          console.log('✅ Delegating to favoriteManager');
          
          try {
            if ($button.hasClass('add-favorite')) {
              console.log('➕ Adding to favorites');
              window.favoriteManager.addToFavorite($button[0]);
            } else if ($button.hasClass('remove-favorite')) {
              console.log('➖ Removing from favorites');
              window.favoriteManager.removeFromFavorite($button[0]);
            } else {
              console.log('🔄 Toggling favorite status');
              window.favoriteManager.toggleFavorite($button[0]);
            }
          } catch (error) {
            console.error('❌ Error in favorite manager:', error);
            
            // Show error message
            if (typeof toastr !== 'undefined') {
              toastr.error('Có lỗi xảy ra khi xử lý yêu thích. Vui lòng thử lại.');
            } else {
              alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
          }
        } else {
          console.error('❌ favoriteManager not found!');
          alert('Chức năng yêu thích đang được tải. Vui lòng thử lại sau ít giây.');
        }
      });
      
      // Add hover effects for better UX
      $('.btn-favorite, .btn-favorite-small, .btn-favorite-detail').hover(
        function() {
          $(this).addClass('hover-effect');
        },
        function() {
          $(this).removeClass('hover-effect');
        }
      );
    });
    
    // Test function để submit form với giá trị test  
    function testPriceFilter(min = {{ round($minPrice + ($maxPrice - $minPrice) * 0.3) }}, max = {{ round($minPrice + ($maxPrice - $minPrice) * 0.7) }}) {
      console.log('Testing price filter with:', { min, max });
      
      const minPriceInput = document.getElementById('min_price');
      const maxPriceInput = document.getElementById('max_price');
      const minRange = document.getElementById('min_range');
      const maxRange = document.getElementById('max_range');
      
      if (minPriceInput && maxPriceInput && minRange && maxRange) {
        minPriceInput.value = min;
        maxPriceInput.value = max;
        minRange.value = min;
        maxRange.value = max;
        
        // Sync display
        if (window.syncAllInputs) {
          window.syncAllInputs();
        }
        
        // Debug và submit
        debugFormData();
        document.getElementById('searchForm').submit();
      } else {
        console.error('Could not find required form elements for test');
      }
    }
    
    // Make functions globally accessible for testing
    window.debugFormData = debugFormData;
    window.testPriceFilter = testPriceFilter;

    document.addEventListener('DOMContentLoaded', function() {
      // Price slider elements
      const minPriceInput = document.getElementById('min_price');
      const maxPriceInput = document.getElementById('max_price');
      const minRange = document.getElementById('min_range');
      const maxRange = document.getElementById('max_range');
      const minManualInput = document.querySelector('.price-input-min');
      const maxManualInput = document.querySelector('.price-input-max');
      const priceMinDisplay = document.querySelector('.price-label-min');
      const priceMaxDisplay = document.querySelector('.price-label-max');
      const searchForm = document.getElementById('searchForm');
      
      const maxPrice = {{ $maxPrice ?? 100000000 }};
      const minPrice = {{ $minPrice ?? 0 }};
      
      // Sync range sliders with number inputs
      function updatePriceInputs() {
        const min = parseInt(minRange.value) || 0;
        const max = parseInt(maxRange.value) || maxPrice;
        
        // Prevent min from being greater than max
        if (min > max) {
          minRange.value = max;
        }
        if (max < min) {
          maxRange.value = min;
        }
        
        minPriceInput.value = minRange.value;
        maxPriceInput.value = maxRange.value;
        
        // Update display
        if (priceMinDisplay) {
          priceMinDisplay.textContent = new Intl.NumberFormat('vi-VN').format(minRange.value) + 'đ';
        }
        if (priceMaxDisplay) {
          priceMaxDisplay.textContent = new Intl.NumberFormat('vi-VN').format(maxRange.value) + 'đ';
        }
      }
      
      // Initialize display and sync all inputs
      function syncAllInputs() {
        const min = parseInt(minRange.value) || minPrice;
        const max = parseInt(maxRange.value) || maxPrice;
        
        console.log('Syncing inputs:', { min, max, minPrice, maxPrice });
        
        // Update hidden inputs
        minPriceInput.value = min;
        maxPriceInput.value = max;
        
        // Update manual inputs
        if (minManualInput) minManualInput.value = min;
        if (maxManualInput) maxManualInput.value = max;
        
        // Update display
        if (priceMinDisplay) {
          priceMinDisplay.textContent = new Intl.NumberFormat('vi-VN').format(min) + 'đ';
        }
        if (priceMaxDisplay) {
          priceMaxDisplay.textContent = new Intl.NumberFormat('vi-VN').format(max) + 'đ';
        }
        
        // Update slider value displays
        const minValueDisplay = document.getElementById('min_value_display');
        const maxValueDisplay = document.getElementById('max_value_display');
        if (minValueDisplay) {
          minValueDisplay.textContent = new Intl.NumberFormat('vi-VN').format(min) + 'đ';
        }
        if (maxValueDisplay) {
          maxValueDisplay.textContent = new Intl.NumberFormat('vi-VN').format(max) + 'đ';
        }
        
        console.log('After sync:', {
          hiddenMin: minPriceInput.value,
          hiddenMax: maxPriceInput.value,
          manualMin: minManualInput?.value,
          manualMax: maxManualInput?.value
        });
      }
      
      // Event listeners for range sliders
      if (minRange && maxRange) {
        // Simplified event handling for separated sliders
        minRange.addEventListener('input', function() {
          // Ensure min doesn't exceed max
          if (parseInt(this.value) > parseInt(maxRange.value)) {
            this.value = maxRange.value;
          }
          syncAllInputs();
          console.log('✅ Min slider working! Value:', this.value);
        });
        
        maxRange.addEventListener('input', function() {
          // Ensure max doesn't go below min
          if (parseInt(this.value) < parseInt(minRange.value)) {
            this.value = minRange.value;
          }
          syncAllInputs();
          console.log('✅ Max slider working! Value:', this.value);
        });
        
        // Change events for form submission
        minRange.addEventListener('change', function() {
          console.log('Min range changed to:', this.value);
          syncAllInputs();
        });
        
        maxRange.addEventListener('change', function() {
          console.log('Max range changed to:', this.value);  
          syncAllInputs();
        });
        
        // Auto submit on range change (with longer debounce to prevent conflicts)
        let rangeTimeout;
        function handleRangeChange(event) {
          console.log('Range changed - Min:', minRange.value, 'Max:', maxRange.value);
          clearTimeout(rangeTimeout);
          rangeTimeout = setTimeout(function() {
            console.log('Submitting form due to range change');
            // Ensure the form data is correct before submission
            syncAllInputs();
            debugFormData(); // Debug form data before submit
            
            // Show loading state
            const submitBtn = document.querySelector('.btn-primary-search');
            if (submitBtn) {
              submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tìm...';
              submitBtn.disabled = true;
            }
            
            searchForm.submit();
          }, 1500); // Reasonable delay
        }
        
        minRange.addEventListener('change', handleRangeChange);
        maxRange.addEventListener('change', handleRangeChange);
        
        // Additional event for touch devices - use longer delay
        minRange.addEventListener('touchend', function() {
          setTimeout(handleRangeChange, 300);
        });
        maxRange.addEventListener('touchend', function() {
          setTimeout(handleRangeChange, 300);
        });
      }
      
      // Event listeners for manual number inputs
      if (minManualInput && maxManualInput) {
        minManualInput.addEventListener('input', function() {
          let value = parseInt(this.value) || minPrice;
          if (value < minPrice) value = minPrice;
          if (value > maxPrice) value = maxPrice;
          
          minPriceInput.value = value;
          minRange.value = value;
          updatePriceInputs();
        });
        
        maxManualInput.addEventListener('input', function() {
          let value = parseInt(this.value) || maxPrice;
          if (value < minPrice) value = minPrice;
          if (value > maxPrice) value = maxPrice;
          
          maxPriceInput.value = value;
          maxRange.value = value;
          updatePriceInputs();
        });
        
        // Submit form when manual input values change
        minManualInput.addEventListener('change', function() {
          setTimeout(function() {
            debugFormData(); // Debug form data before submit
            searchForm.submit();
          }, 500);
        });
        
        maxManualInput.addEventListener('change', function() {
          setTimeout(function() {
            debugFormData(); // Debug form data before submit
            searchForm.submit();
          }, 500);
        });
      }
      
      // Auto-submit when name input changes (with debounce)
      const nameInput = document.querySelector('input[name="name"]');
      if (nameInput) {
        let nameTimeout;
        let searchIndicator;
        
        // Create search indicator
        function showSearchIndicator() {
          if (!searchIndicator) {
            searchIndicator = document.createElement('div');
            searchIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...';
            searchIndicator.style.cssText = `
              position: absolute; 
              top: 100%; 
              left: 0; 
              background: #333; 
              color: white; 
              padding: 5px 10px; 
              border-radius: 4px; 
              font-size: 12px;
              z-index: 1000;
              white-space: nowrap;
              margin-top: 5px;
            `;
            nameInput.parentElement.style.position = 'relative';
            nameInput.parentElement.appendChild(searchIndicator);
          }
          searchIndicator.style.display = 'block';
        }
        
        function hideSearchIndicator() {
          if (searchIndicator) {
            searchIndicator.style.display = 'none';
          }
        }
        
        nameInput.addEventListener('input', function() {
          const value = this.value.trim();
          console.log('Name input changed to:', value);
          clearTimeout(nameTimeout);
          hideSearchIndicator();
          
          if (value.length >= 2) { // Only search if 2+ characters
            nameTimeout = setTimeout(function() {
              console.log('Submitting form due to name search');
              showSearchIndicator();
              searchForm.submit();
            }, 800); // Wait 800ms after user stops typing
          } else if (value.length === 0) {
            // Clear search immediately if input is empty
            nameTimeout = setTimeout(function() {
              console.log('Clearing search - empty input');
              showSearchIndicator();
              searchForm.submit();
            }, 300);
          }
        });
        
        // Also submit on Enter key press
        nameInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(nameTimeout);
            hideSearchIndicator();
            console.log('Enter pressed, submitting form');
            showSearchIndicator();
            searchForm.submit();
          }
        });
        
        // Add placeholder enhancement
        nameInput.setAttribute('autocomplete', 'off');
        nameInput.setAttribute('spellcheck', 'false');
      }

      // Auto-submit when category changes
      const categorySelect = document.querySelector('select[name="category_id"]');
      if (categorySelect) {
        categorySelect.addEventListener('change', function() {
          console.log('Category changed to:', this.value);
          searchForm.submit();
        });
      }
      
      // Auto-submit when sort changes
      const sortSelect = document.querySelector('select[name="sort_by"]');
      if (sortSelect) {
        sortSelect.addEventListener('change', function() {
          console.log('Sort changed to:', this.value);
          searchForm.submit();
        });
      }
      
      // Initialize values and display
      if (minRange && maxRange && minPriceInput && maxPriceInput) {
        console.log('Initializing price range...', {
          hiddenMinValue: minPriceInput.value,
          hiddenMaxValue: maxPriceInput.value,
          minPrice,
          maxPrice
        });
        
        // Set initial values for sliders from request or defaults
        const initialMin = minPriceInput.value ? parseInt(minPriceInput.value) : minPrice;
        const initialMax = maxPriceInput.value ? parseInt(maxPriceInput.value) : maxPrice;
        
        minRange.value = initialMin;
        maxRange.value = initialMax;
        
        console.log('Set slider values:', {
          minRangeValue: minRange.value,
          maxRangeValue: maxRange.value
        });
        
        // Sync all inputs and display
        syncAllInputs();
        
        console.log('Price range initialization complete!');
        
        // Make syncAllInputs globally accessible
        window.syncAllInputs = syncAllInputs;
      } else {
        console.error('Missing elements:', {
          minRange: !!minRange,
          maxRange: !!maxRange,
          minPriceInput: !!minPriceInput,
          maxPriceInput: !!maxPriceInput
        });
      }
    });
    
    // Add smooth animations to cards on page load
    setTimeout(function() {
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };
      
      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);
      
      // Observe all shop items for animation
      document.querySelectorAll('.shop-item').forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(item);
      });
    }, 100);
    
    // FontAwesome Icon Test and Debug
    document.addEventListener('DOMContentLoaded', function() {
      // Create a small debug panel to test icons
      console.log('🔍 Testing FontAwesome icons...');
      
      // Test basic icons
      const testIcons = [
        'fas fa-heart',
        'far fa-heart', 
        'fas fa-search',
        'fas fa-filter',
        'fas fa-eye',
        'fas fa-check',
        'fas fa-times'
      ];
      
      let allIconsWorking = true;
      testIcons.forEach(iconClass => {
        const testElement = document.createElement('i');
        testElement.className = iconClass;
        testElement.style.cssText = 'position: absolute; top: -9999px; left: -9999px;';
        document.body.appendChild(testElement);
        
        // Check if icon loaded
        const computedStyle = window.getComputedStyle(testElement, '::before');
        const hasContent = computedStyle.content && computedStyle.content !== 'none' && computedStyle.content !== '""';
        
        if (!hasContent) {
          console.warn(`❌ Icon ${iconClass} not working properly`);
          allIconsWorking = false;
        } else {
          console.log(`✅ Icon ${iconClass} working`);
        }
        
        // Clean up
        document.body.removeChild(testElement);
      });
      
      if (allIconsWorking) {
        console.log('🎉 All FontAwesome icons working correctly!');
      } else {
        console.warn('⚠️ Some FontAwesome icons may not be displaying correctly');
        
        // Show a visual indicator if icons are not working
        setTimeout(() => {
          const favoriteButtons = document.querySelectorAll('.btn-favorite, .btn-favorite-small, .btn-favorite-detail');
          favoriteButtons.forEach(btn => {
            const icon = btn.querySelector('i');
            if (icon) {
              // Add fallback text if icon is not showing
              const computedStyle = window.getComputedStyle(icon, '::before');
              const hasContent = computedStyle.content && computedStyle.content !== 'none';
              if (!hasContent) {
                // Add text fallback
                if (!btn.querySelector('.icon-fallback')) {
                  const fallback = document.createElement('span');
                  fallback.className = 'icon-fallback';
                  fallback.textContent = btn.classList.contains('favorited') ? '♥' : '♡';
                  fallback.style.cssText = 'font-size: 16px; color: inherit;';
                  icon.style.display = 'none';
                  btn.insertBefore(fallback, icon);
                }
              }
            }
          });
        }, 1000);
      }
      
      // Also test if FontAwesome CSS is loaded
      const fontAwesomeLoaded = Array.from(document.styleSheets).some(sheet => {
        try {
          return sheet.href && (
            sheet.href.includes('font-awesome') || 
            sheet.href.includes('fontawesome')
          );
        } catch (e) {
          return false;
        }
      });
      
      if (fontAwesomeLoaded) {
        console.log('✅ FontAwesome CSS is loaded');
      } else {
        console.warn('⚠️ FontAwesome CSS may not be loaded properly');
      }
      
      // Ensure favoriteManager is available
      setTimeout(function() {
        if (!window.favoriteManager) {
          console.warn('⚠️ favoriteManager not found, attempting to initialize...');
          
          // Try to initialize if jQuery is available
          if (typeof $ !== 'undefined') {
            try {
              // Load favorites.js functionality if it exists
              const script = document.createElement('script');
              script.src = '{{ asset("client/assets/js/favorites.js") }}';
              script.onload = function() {
                console.log('✅ favorites.js reloaded');
              };
              script.onerror = function() {
                console.error('❌ Failed to load favorites.js');
              };
              document.head.appendChild(script);
            } catch (error) {
              console.error('❌ Error initializing favoriteManager:', error);
            }
          }
        } else {
          console.log('✅ favoriteManager is ready');
          
          // Test favoriteManager methods
          if (typeof window.favoriteManager.addToFavorite === 'function') {
            console.log('✅ favoriteManager.addToFavorite is available');
          } else {
            console.warn('⚠️ favoriteManager.addToFavorite method missing');
          }
        }
      }, 2000);
    });
    
  </script>
@endsection