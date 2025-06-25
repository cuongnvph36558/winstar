@extends('layouts.client')

@section('title', 'Product')

@section('content')
  <!-- Link to custom product styles -->
  <link rel="stylesheet" href="{{ asset('client/assets/css/product-custom.css') }}">

  <section class="module shop-page-header" style="background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 50%, #000000 100%); min-height: 300px; display: flex; align-items: center;">
    <div class="container">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
      <h2 class="module-title font-alt" style="color: white;">Shop Products</h2>
      <div class="module-subtitle font-serif" style="color: #e0e0e0;">Khám phá bộ sưu tập sản phẩm chất lượng cao với nhiều lựa chọn đa dạng</div>
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
              <i class="fa fa-filter"></i>
              Tìm kiếm & Lọc sản phẩm
            </h3>
            <div class="search-summary">
              <span class="total-products">{{ $products->total() }} sản phẩm</span>
              @if(request()->hasAny(['name', 'category_id', 'min_price', 'max_price']))
                <a href="{{ route('client.product') }}" class="clear-all-btn">
                  <i class="fa fa-times"></i> Xóa tất cả
                </a>
              @endif
            </div>
          </div>

          <div class="search-content">
            <div class="row">
              <!-- Search by Name -->
              <div class="col-lg-4 col-md-6 col-sm-12">
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
              <div class="col-lg-3 col-md-6 col-sm-12">
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
              
              <!-- Price Range -->
              <div class="col-lg-4 col-md-8 col-sm-12">
                <div class="search-field">
                  <label class="field-label">
                    <i class="fa fa-money"></i>
                    Khoảng giá
                  </label>
                  <div class="price-filter">
                    <div class="price-inputs">
                      <input type="number" name="min_price" id="min_price" class="price-input" 
                             placeholder="Từ" value="{{ request('min_price') }}" min="0">
                      <span class="price-divider">-</span>
                      <input type="number" name="max_price" id="max_price" class="price-input" 
                             placeholder="Đến" value="{{ request('max_price') }}" min="0">
                    </div>
                    <div class="price-slider-wrapper">
                      <div class="dual-range">
                        <input type="range" id="min_range" class="range-slider range-min" 
                               min="0" max="{{ $maxPrice ?? 100000000 }}" 
                               value="{{ request('min_price', 0) }}" step="100000">
                        <input type="range" id="max_range" class="range-slider range-max" 
                               min="0" max="{{ $maxPrice ?? 100000000 }}" 
                               value="{{ request('max_price', $maxPrice ?? 100000000) }}" step="100000">
                      </div>
                      <div class="price-labels">
                        <span class="price-label-min">{{ number_format(request('min_price') ?: 0) }}đ</span>
                        <span class="price-label-max">{{ number_format(request('max_price') ?: $maxPrice) }}đ</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Search Actions -->
              <div class="col-lg-1 col-md-4 col-sm-12">
                <div class="search-actions">
                  <button type="submit" class="btn-primary-search" title="Tìm kiếm">
                    <i class="fa fa-search"></i>
                    <span class="btn-text">Tìm</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Search Results Summary -->
          @if(request()->hasAny(['name', 'category_id', 'min_price', 'max_price']))
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
                    <i class="fa fa-money"></i>
                    {{ number_format(request('min_price') ?: 0) }}đ - {{ number_format(request('max_price') ?: $maxPrice) }}đ
                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="remove-filter">×</a>
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
  
  <section class="module-small">
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
                  @if($product->variants->count() > 1)
                    <div class="product-badge">
                      <span class="badge badge-variants">{{ $product->variants->count() }} phiên bản</span>
                    </div>
                  @endif
                </div>
                <div class="shop-item-content">
                  <div class="product-category">{{ $product->category->name ?? 'Khác' }}</div>
                  <h4 class="shop-item-title font-alt">
                    <a href="{{ route('client.single-product', $product->id) }}">{{ $product->name }}</a>
                  </h4>
                  <div class="product-price">
                    @if($product->variants->count() > 1)
                      @php
                        $prices = $product->variants->pluck('price');
                        $productMinPrice = $prices->min();
                        $productMaxPrice = $prices->max();
                      @endphp
                      <span class="price-range">
                        {{ number_format($productMinPrice) }} - {{ number_format($productMaxPrice) }} VND
                      </span>
                    @else
                      <span class="price-single">{{ number_format($variant->price) }} VND</span>
                    @endif
                  </div>
                  <div class="product-stock">
                    @if($variant->stock_quantity > 0)
                      <span class="in-stock"><i class="fa fa-check"></i> Còn hàng</span>
                    @else
                      <span class="out-of-stock"><i class="fa fa-times"></i> Hết hàng</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="no-products-found">
                <div class="no-products-icon">
                  <i class="fa fa-search"></i>
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
    
    // Debug and ensure product image links work
    document.addEventListener('DOMContentLoaded', function() {
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
    
    document.addEventListener('DOMContentLoaded', function() {
      // Price slider elements
      const minPriceInput = document.getElementById('min_price');
      const maxPriceInput = document.getElementById('max_price');
      const minRange = document.getElementById('min_range');
      const maxRange = document.getElementById('max_range');
      const priceMinDisplay = document.querySelector('.price-label-min');
      const priceMaxDisplay = document.querySelector('.price-label-max');
      const searchForm = document.getElementById('searchForm');
      
      const maxPrice = {{ $maxPrice ?? 100000000 }};
      
      // Sync range sliders with number inputs
      function updatePriceInputs() {
        const min = parseInt(minRange.value);
        const max = parseInt(maxRange.value);
        
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
      
      // Sync number inputs with range sliders
      function updateRangeSliders() {
        const min = parseInt(minPriceInput.value) || 0;
        const max = parseInt(maxPriceInput.value) || maxPrice;
        
        minRange.value = min;
        maxRange.value = max;
        
        // Update display
        if (priceMinDisplay) {
          priceMinDisplay.textContent = new Intl.NumberFormat('vi-VN').format(min) + 'đ';
        }
        if (priceMaxDisplay) {
          priceMaxDisplay.textContent = new Intl.NumberFormat('vi-VN').format(max) + 'đ';
        }
      }
      
      // Event listeners for range sliders
      if (minRange && maxRange) {
        minRange.addEventListener('input', updatePriceInputs);
        maxRange.addEventListener('input', updatePriceInputs);
        
        // Auto submit on range change (with debounce)
        let rangeTimeout;
        function handleRangeChange() {
          clearTimeout(rangeTimeout);
          rangeTimeout = setTimeout(function() {
            searchForm.submit();
          }, 1000);
        }
        
        minRange.addEventListener('change', handleRangeChange);
        maxRange.addEventListener('change', handleRangeChange);
      }
      
      // Event listeners for number inputs
      if (minPriceInput && maxPriceInput) {
        minPriceInput.addEventListener('input', updateRangeSliders);
        maxPriceInput.addEventListener('input', updateRangeSliders);
        
        // Validation
        minPriceInput.addEventListener('blur', function() {
          if (this.value && maxPriceInput.value && parseInt(this.value) > parseInt(maxPriceInput.value)) {
            this.value = maxPriceInput.value;
            updateRangeSliders();
          }
        });
        
        maxPriceInput.addEventListener('blur', function() {
          if (this.value && minPriceInput.value && parseInt(this.value) < parseInt(minPriceInput.value)) {
            this.value = minPriceInput.value;
            updateRangeSliders();
          }
        });
        
        // Submit form when input values change
        minPriceInput.addEventListener('change', function() {
          setTimeout(function() {
            searchForm.submit();
          }, 500);
        });
        
        maxPriceInput.addEventListener('change', function() {
          setTimeout(function() {
            searchForm.submit();
          }, 500);
        });
      }
      
      // Auto-submit when category changes
      const categorySelect = document.querySelector('select[name="category_id"]');
      if (categorySelect) {
        categorySelect.addEventListener('change', function() {
          searchForm.submit();
        });
      }
      
      // Initialize display
      updatePriceInputs();
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
    
  </script>
@endsection