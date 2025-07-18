// Product Page JavaScript Fixes
// This file contains fixed and optimized JavaScript for the product page

// Global error handler
window.addEventListener('error', function(e) {
  console.warn('Product page error:', e.error);
  return true;
});

// Define global functions first
window.handleProductVisibility = function(productCard, isProcessing = false) {
  if (!productCard) return;
  
  if (isProcessing) {
    productCard.classList.add('processing');
  } else {
    productCard.classList.remove('processing');
    productCard.style.removeProperty('display');
    productCard.style.removeProperty('visibility');
    productCard.style.removeProperty('opacity');
    productCard.classList.remove('hidden', 'invisible', 'fade-out');
  }
};

window.fixFontAwesomeIcons = function() {
  try {
    const testIcon = document.createElement('i');
    testIcon.className = 'fa fa-heart';
    testIcon.style.position = 'absolute';
    testIcon.style.left = '-9999px';
    document.body.appendChild(testIcon);
    
    const computedStyle = window.getComputedStyle(testIcon, ':before');
    const content = computedStyle.getPropertyValue('content');
    
    document.body.removeChild(testIcon);
    
    if (!content || content === 'none' || content === '""') {
      // Apply fallback icons
      document.querySelectorAll('.fa-heart').forEach(function(icon) {
        if (!icon.textContent || icon.textContent.trim() === '') {
          icon.textContent = '♥';
          icon.style.fontFamily = 'serif';
        }
      });
      
      document.querySelectorAll('.fa-heart-o').forEach(function(icon) {
        if (!icon.textContent || icon.textContent.trim() === '') {
          icon.textContent = '♡';
          icon.style.fontFamily = 'serif';
        }
      });
      
      document.querySelectorAll('.fa-shopping-cart').forEach(function(icon) {
        if (!icon.textContent || icon.textContent.trim() === '') {
          icon.textContent = '🛒';
          icon.style.fontFamily = 'serif';
        }
      });
    }
  } catch (error) {
    console.warn('FontAwesome fix failed:', error);
  }
};

window.fixProductActionButtons = function() {
  try {
    const productActionContainers = document.querySelectorAll('.product-actions');
    
    productActionContainers.forEach(function(container) {
      container.style.display = 'flex';
      container.style.justifyContent = 'center';
      container.style.alignItems = 'center';
      container.style.gap = '6px';
      container.style.flexWrap = 'wrap';
      container.style.margin = '8px 0';
      container.style.padding = '0 8px';
      
      // Fix buttons inside
      const buttons = container.querySelectorAll('.btn');
      buttons.forEach(function(button) {
        button.style.fontSize = '10px';
        button.style.padding = '6px 8px';
        button.style.borderRadius = '4px';
        button.style.flex = '1 1 auto';
        button.style.minWidth = '60px';
        button.style.maxWidth = '90px';
        button.style.textAlign = 'center';
        button.style.display = 'inline-flex';
        button.style.alignItems = 'center';
        button.style.justifyContent = 'center';
        button.style.whiteSpace = 'nowrap';
        button.style.lineHeight = '1.2';
      });
    });
  } catch (error) {
    console.warn('Product action buttons fix failed:', error);
  }
};

// Clear input function
window.clearInput = function(inputName) {
  try {
    const input = document.querySelector(`input[name="${inputName}"]`);
    if (input) {
      input.value = '';
      const form = document.getElementById('searchForm');
      if (form) form.submit();
    }
  } catch (error) {
    console.warn('Clear input failed:', error);
  }
};

// Realtime status update
function updateRealtimeStatus(status, message) {
  try {
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
  } catch (error) {
    console.warn('Failed to update realtime status:', error);
  }
}

// Filter toggle functionality
function initFilterToggle() {
  try {
    const filterToggle = document.getElementById('filterToggle');
    const searchContent = document.getElementById('searchContent');
    const toggleIcon = document.getElementById('toggleIcon');
    const toggleText = document.querySelector('.toggle-text');
    const searchForm = document.getElementById('searchForm');
    
    // Silently return if elements don't exist (might be on a different page)
    if (!filterToggle || !searchContent || !toggleIcon || !toggleText) {
      return;
    }
    
    console.log('✅ Filter toggle elements found, initializing...');
    
    const isCollapsed = localStorage.getItem('filterCollapsed') === 'true';
    
    function updateToggleState(collapsed) {
      if (collapsed) {
        searchContent.classList.add('collapsed');
        toggleIcon.className = 'fa fa-chevron-down';
        toggleText.textContent = 'Mở rộng';
        filterToggle.setAttribute('aria-expanded', 'false');
      } else {
        searchContent.classList.remove('collapsed');
        toggleIcon.className = 'fa fa-chevron-up';
        toggleText.textContent = 'Thu gọn';
        filterToggle.setAttribute('aria-expanded', 'true');
      }
    }
    
    updateToggleState(isCollapsed);
    
    filterToggle.addEventListener('click', function(e) {
      e.preventDefault();
      if (filterToggle.disabled) return;
      
      const isCurrentlyCollapsed = searchContent.classList.contains('collapsed');
      const newState = !isCurrentlyCollapsed;
      
      filterToggle.disabled = true;
      updateToggleState(newState);
      localStorage.setItem('filterCollapsed', newState.toString());
      
      setTimeout(() => {
        filterToggle.disabled = false;
      }, 450);
    });
    
    console.log('✅ Filter toggle initialized successfully');
    
  } catch (error) {
    console.warn('Filter toggle initialization failed:', error);
  }
}

// Initialize price range sliders 
function initPriceSliders() {
  try {
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    const minRange = document.getElementById('min_range');
    const maxRange = document.getElementById('max_range');
    const searchForm = document.getElementById('searchForm');
    
    if (!minRange || !maxRange || !minPriceInput || !maxPriceInput) {
      return;
    }
    
    console.log('✅ Price sliders found, initializing...');
    
    // Sync all price inputs
    function syncAllInputs() {
      try {
        const min = parseInt(minRange.value) || 0;
        const max = parseInt(maxRange.value) || 10000000;
        
        minPriceInput.value = min;
        maxPriceInput.value = max;
        
        // Update display elements
        const priceMinDisplay = document.querySelector('.price-label-min');
        const priceMaxDisplay = document.querySelector('.price-label-max');
        
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
      } catch (error) {
        console.warn('Price sync failed:', error);
      }
    }
    
    // Event listeners for range sliders
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
    syncAllInputs();
    
    console.log('✅ Price sliders initialized successfully');
    
  } catch (error) {
    console.warn('Price sliders initialization failed:', error);
  }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  console.log('✅ Product page JavaScript fixes loaded');
  
  // Run initial fixes
  try {
    window.fixFontAwesomeIcons();
    window.fixProductActionButtons();
    initFilterToggle();
    initPriceSliders();
  } catch (error) {
    console.warn('Initial fixes failed:', error);
  }
  
  // Set up periodic fixes
  setInterval(function() {
    try {
      window.fixFontAwesomeIcons();
      window.fixProductActionButtons();
    } catch (error) {
      console.warn('Periodic fixes failed:', error);
    }
  }, 10000);
}); 