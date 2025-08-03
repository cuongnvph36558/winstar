// Product Page JavaScript - Enhanced functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('🚀 Product page JavaScript loaded');
  
  // Setup price range sliders
  const minPriceInput = document.getElementById('min_price');
  const maxPriceInput = document.getElementById('max_price');
  const minRange = document.getElementById('min_range');
  const maxRange = document.getElementById('max_range');
  const minManualInput = document.querySelector('.price-input-min');
  const maxManualInput = document.querySelector('.price-input-max');
  const priceMinDisplay = document.querySelector('.price-label-min');
  const priceMaxDisplay = document.querySelector('.price-label-max');
  const searchForm = document.getElementById('searchForm');
  
  // Get price limits from data attributes or defaults
  const maxPrice = parseInt(document.querySelector('[data-max-price]')?.dataset.maxPrice) || 10000000;
  const minPrice = parseInt(document.querySelector('[data-min-price]')?.dataset.minPrice) || 0;
  
  // Sync all price inputs
  function syncAllInputs() {
    const min = parseInt(minRange?.value) || minPrice;
    const max = parseInt(maxRange?.value) || maxPrice;
    
    console.log('Syncing inputs:', { min, max, minPrice, maxPrice });
    
    // Update hidden inputs
    if (minPriceInput) minPriceInput.value = min;
    if (maxPriceInput) maxPriceInput.value = max;
    
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
  }
  
  // Event listeners for range sliders
  if (minRange && maxRange) {
    minRange.addEventListener('input', function() {
      if (parseInt(this.value) > parseInt(maxRange.value)) {
        this.value = maxRange.value;
      }
      syncAllInputs();
    });
    
    maxRange.addEventListener('input', function() {
      if (parseInt(this.value) < parseInt(minRange.value)) {
        this.value = minRange.value;
      }
      syncAllInputs();
    });
    
    // Auto submit on range change with debounce
    let rangeTimeout;
    function handleRangeChange() {
      clearTimeout(rangeTimeout);
      rangeTimeout = setTimeout(function() {
        syncAllInputs();
        if (searchForm) searchForm.submit();
      }, 1000);
    }
    
    minRange.addEventListener('change', handleRangeChange);
    maxRange.addEventListener('change', handleRangeChange);
    
    // Initialize values
    const initialMin = minPriceInput?.value ? parseInt(minPriceInput.value) : minPrice;
    const initialMax = maxPriceInput?.value ? parseInt(maxPriceInput.value) : maxPrice;
    
    minRange.value = initialMin;
    maxRange.value = initialMax;
    syncAllInputs();
  }
  
  // Event listeners for manual inputs
  if (minManualInput && maxManualInput && searchForm) {
    minManualInput.addEventListener('change', function() {
      let value = parseInt(this.value) || minPrice;
      if (value < minPrice) value = minPrice;
      if (value > maxPrice) value = maxPrice;
      
      if (minPriceInput) minPriceInput.value = value;
      if (minRange) minRange.value = value;
      syncAllInputs();
      setTimeout(() => searchForm.submit(), 500);
    });
    
    maxManualInput.addEventListener('change', function() {
      let value = parseInt(this.value) || maxPrice;
      if (value < minPrice) value = minPrice;
      if (value > maxPrice) value = maxPrice;
      
      if (maxPriceInput) maxPriceInput.value = value;
      if (maxRange) maxRange.value = value;
      syncAllInputs();
      setTimeout(() => searchForm.submit(), 500);
    });
  }
  
  // Auto-submit for other form fields
  const nameInput = document.querySelector('input[name="name"]');
  const categorySelect = document.querySelector('select[name="category_id"]');
  const sortSelect = document.querySelector('select[name="sort_by"]');
  
  if (nameInput && searchForm) {
    let nameTimeout;
    nameInput.addEventListener('input', function() {
      const value = this.value.trim();
      clearTimeout(nameTimeout);
      
      if (value.length >= 2 || value.length === 0) {
        nameTimeout = setTimeout(() => searchForm.submit(), 800);
      }
    });
    
    nameInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(nameTimeout);
        searchForm.submit();
      }
    });
  }
  
  if (categorySelect && searchForm) {
    categorySelect.addEventListener('change', () => searchForm.submit());
  }
  
  if (sortSelect && searchForm) {
    sortSelect.addEventListener('change', () => searchForm.submit());
  }
});

// Clear input function
window.clearInput = function(inputName) {
  const input = document.querySelector(`input[name="${inputName}"]`);
  if (input) {
    input.value = '';
    const searchForm = document.getElementById('searchForm');
    if (searchForm) searchForm.submit();
  }
};

// Realtime setup
function updateRealtimeStatus(status, message) {
  const statusElement = document.getElementById('realtimeStatus');
  const liveIndicator = document.getElementById('productPageLiveIndicator');
  
  if (statusElement) {
    statusElement.textContent = message;
  }
  
  if (liveIndicator) {
    if (status === 'connected') {
      liveIndicator.style.display = 'inline-block';
      liveIndicator.style.background = '#28a745';
    } else if (status === 'connecting') {
      liveIndicator.style.display = 'inline-block';  
      liveIndicator.style.background = '#ffc107';
    } else {
      liveIndicator.style.display = 'none';
    }
  }
}

if (window.Echo) {
  console.log('Setting up realtime listeners for product page...');
  updateRealtimeStatus('connecting', '• Đang kết nối realtime...');
  
  window.Echo.channel('favorites')
    .listen('FavoriteUpdated', (e) => {
      console.log('🔥 Realtime favorite update received:', e);
      
      const productFavoriteElements = document.querySelectorAll(`.product-${e.product_id}-favorites`);
      productFavoriteElements.forEach(element => {
        element.classList.add('realtime-update');
        element.textContent = e.favorite_count;
        
        setTimeout(() => {
          element.classList.remove('realtime-update');
        }, 800);
      });
      
      // Highlight product card
      const productButtons = document.querySelectorAll(`[data-product-id="${e.product_id}"]`);
      productButtons.forEach(button => {
        const productCard = button.closest('.shop-item');
        if (productCard) {
          productCard.classList.add('live-updated');
          setTimeout(() => {
            productCard.classList.remove('live-updated');
          }, 1000);
        }
      });
      
      // Show notification for others' actions
      if (window.currentUserId && e.user_id !== window.currentUserId) {
        if (window.RealtimeNotifications && window.RealtimeNotifications.showToast) {
          window.RealtimeNotifications.showToast(
            e.action === 'added' ? 'success' : 'info',
            'Cập nhật realtime',
            `${e.user_name} ${e.action === 'added' ? 'đã thích' : 'đã bỏ thích'} "${e.product_name}"`
          );
        }
      }
    })
    .error((error) => {
      console.error('❌ Error listening to favorites channel:', error);
      updateRealtimeStatus('error', '• Lỗi kết nối realtime');
    });
    
  // Monitor connection status
  if (window.Echo.connector && window.Echo.connector.pusher) {
    window.Echo.connector.pusher.connection.bind('connected', function() {
      console.log('✅ Product page - Pusher connected');
      updateRealtimeStatus('connected', '• Cập nhật realtime');
    });
    
    window.Echo.connector.pusher.connection.bind('disconnected', function() {
      console.warn('⚠️ Product page - Pusher disconnected');
      updateRealtimeStatus('connecting', '• Đang kết nối lại...');
    });
    
    if (window.Echo.connector.pusher.connection.state === 'connected') {
      updateRealtimeStatus('connected', '• Cập nhật realtime');
    }
  }
}

// Enhanced Favorite Button Functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('✅ Favorite system initialized');
  
  // Handle favorite button clicks with jQuery
  if (typeof $ !== 'undefined') {
    $(document).on('click', '.add-favorite, .remove-favorite', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const $button = $(this);
      const productId = $button.data('product-id');
      
      console.log('🎯 Favorite button clicked:', {
        productId: productId,
        isLoading: $button.hasClass('loading'),
        isRemoveFavorite: $button.hasClass('remove-favorite')
      });
      
      // Prevent double clicks
      if ($button.hasClass('loading') || $button.prop('disabled')) {
        return;
      }
      
      // Add loading state
      $button.addClass('loading').prop('disabled', true);
      const originalHtml = $button.html();
      $button.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
      
      // Determine action
      const isCurrentlyFavorited = $button.hasClass('remove-favorite');
      const action = isCurrentlyFavorited ? 'remove' : 'add';
      const url = window.favoriteRoutes ? 
        (isCurrentlyFavorited ? window.favoriteRoutes.remove : window.favoriteRoutes.add) :
        (isCurrentlyFavorited ? '/favorite/remove' : '/favorite/add');
      
      $.ajax({
        url: url,
        method: 'POST',
        data: {
          product_id: productId,
          _token: window.csrfToken || $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          $button.removeClass('loading').prop('disabled', false);
          
          if (response.success) {
            // Update button state
            if (action === 'add') {
              $button.removeClass('add-favorite').addClass('remove-favorite');
              $button.find('i').removeClass('fa-heart-o').addClass('fa-heart');
              $button.html('<i class="fa fa-heart"></i> Bỏ yêu thích');
            } else {
              $button.removeClass('remove-favorite').addClass('add-favorite');  
              $button.find('i').removeClass('fa-heart').addClass('fa-heart-o');
              $button.html('<i class="fa fa-heart-o"></i> Yêu thích');
            }
            
            // Update all buttons for this product
            $(`[data-product-id="${productId}"]`).each(function() {
              const btn = $(this);
              if (action === 'add') {
                btn.removeClass('add-favorite').addClass('remove-favorite');
                btn.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                if (btn.hasClass('btn-round')) {
                  btn.html('<i class="fa fa-heart"></i> Bỏ yêu thích');
                }
              } else {
                btn.removeClass('remove-favorite').addClass('add-favorite');
                btn.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                if (btn.hasClass('btn-round')) {
                  btn.html('<i class="fa fa-heart-o"></i> Yêu thích');
                }
              }
              btn.removeClass('loading').prop('disabled', false);
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
          
          const message = xhr.responseJSON?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
          if (typeof Swal !== 'undefined') {
            Swal.fire('Lỗi!', message, 'error');
          } else {
            alert(message);
          }
        }
      });
    });
    
    // Add hover effects
    $('.add-favorite, .remove-favorite').hover(
      function() {
        $(this).addClass('hover-effect');
      },
      function() {
        $(this).removeClass('hover-effect');
      }
    );
  }
});

// Image error handling
window.handleImageError = function(img) {
  console.log('Image failed to load:', img.src);
  img.style.display = 'none';
  const placeholder = img.nextElementSibling;
  if (placeholder && placeholder.classList.contains('product-image-placeholder')) {
    placeholder.style.display = 'flex';
  }
};

console.log('✅ Product page JavaScript setup complete'); 