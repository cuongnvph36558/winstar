@extends('layouts.client')

@section('title', 'Product')

@section('content')
  <!-- Link to custom product styles -->
  <link rel="stylesheet" href="{{ asset('client/assets/css/product-custom.css') }}">

  <section class="module bg-dark-60" style="
    background-image: url('{{ asset('client/assets/images/section-6.jpg') }}');
    background-size: cover;
    background-position: center;
    min-height: 300px;
    display: flex;
    align-items: center;
  ">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <h2 class="module-title font-alt">Cửa Hàng Sản Phẩm</h2>
          <div class="module-subtitle font-serif">Khám phá bộ sưu tập sản phẩm chất lượng cao với nhiều lựa chọn đa dạng</div>
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
              <!-- Toggle Button for Collapse/Expand -->
              <button type="button" class="filter-toggle-btn" id="filterToggle" 
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
                    <i class="fa fa-money"></i>
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
                               class="range-slider-single range-max"
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
                    <i class="fa fa-money"></i>
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
                    @if($product->variants->count() > 0)
                      @php
                        $prices = $product->variants->pluck('price');
                        $productMinPrice = $prices->min();
                        $productMaxPrice = $prices->max();
                      @endphp
                      <span class="price-range">
                        {{ number_format($productMinPrice) }} - {{ number_format($productMaxPrice) }} VND
                      </span>
                    @else
                      <span class="price-single">{{ number_format($product->price) }} VND</span>
                    @endif
                  </div>
                  <div class="product-stock">
                    @if($variant && $variant->stock_quantity > 0)
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
    // Filter Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
      const filterToggle = document.getElementById('filterToggle');
      const searchContent = document.getElementById('searchContent');
      const toggleIcon = document.getElementById('toggleIcon');
      const toggleText = document.querySelector('.toggle-text');
      
            // Check localStorage for saved state
      const isCollapsed = localStorage.getItem('filterCollapsed') === 'true';
      const searchForm = document.getElementById('searchForm');
      
      // Apply initial state
      if (isCollapsed) {
        searchContent.classList.add('collapsed');
        filterToggle.classList.add('collapsed');
        searchForm.classList.add('filter-collapsed');
        toggleIcon.className = 'fa fa-chevron-down';
        toggleText.textContent = 'Mở rộng';
        filterToggle.setAttribute('aria-expanded', 'false');
        filterToggle.setAttribute('title', 'Mở rộng bộ lọc');
      }
      
      // Toggle functionality with enhanced UX
      filterToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Prevent multiple clicks during animation
        if (filterToggle.disabled) return;
        
        const isCurrentlyCollapsed = searchContent.classList.contains('collapsed');
        
        // Disable button during animation
        filterToggle.disabled = true;
        
        if (isCurrentlyCollapsed) {
          // Expand
          searchContent.classList.remove('collapsed');
          filterToggle.classList.remove('collapsed');
          searchForm.classList.remove('filter-collapsed');
          toggleIcon.className = 'fa fa-chevron-up';
          toggleText.textContent = 'Thu gọn';
          filterToggle.setAttribute('aria-expanded', 'true');
          filterToggle.setAttribute('title', 'Thu gọn bộ lọc');
          localStorage.setItem('filterCollapsed', 'false');
          
          // Scroll to form if needed
          setTimeout(function() {
            const formRect = searchContent.getBoundingClientRect();
            if (formRect.bottom > window.innerHeight) {
              searchContent.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
              });
            }
          }, 100);
          
        } else {
          // Collapse
          searchContent.classList.add('collapsed');
          filterToggle.classList.add('collapsed');
          searchForm.classList.add('filter-collapsed');
          toggleIcon.className = 'fa fa-chevron-down';
          toggleText.textContent = 'Mở rộng';
          filterToggle.setAttribute('aria-expanded', 'false');
          filterToggle.setAttribute('title', 'Mở rộng bộ lọc');
          localStorage.setItem('filterCollapsed', 'true');
        }
        
        // Re-enable button after animation
        setTimeout(function() {
          filterToggle.disabled = false;
        }, 400);
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
              submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang tìm...';
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
            searchIndicator.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang tìm kiếm...';
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
    
  </script>
@endsection