// Product Stock Management JavaScript
// This file handles real-time stock checking for product pages

// Global variables
let currentStock = 0;
let currentCartQuantity = 0;
let availableToAdd = 0;
let isLoadingStock = false;

// Stock checking functions
function showStockError(message) {
    const stockInfo = document.getElementById('stock-info');
    if (stockInfo) {
        stockInfo.innerHTML = message;
        stockInfo.style.color = '#dc3545';
        stockInfo.style.display = 'block';
    }
}

function updateStockDisplay() {
    const stockInfo = document.getElementById('stock-info');
    if (!stockInfo) return;
    
    if (currentCartQuantity > 0) {
        if (availableToAdd > 0) {
            stockInfo.innerHTML = 
                `Còn ${currentStock} sản phẩm. Bạn đã có ${currentCartQuantity} trong giỏ, có thể thêm ${availableToAdd} nữa.`;
            stockInfo.style.color = availableToAdd <= 5 ? '#dc3545' : '#6c757d';
        } else {
            stockInfo.innerHTML = `Bạn đã có ${currentCartQuantity} sản phẩm trong giỏ (đạt giới hạn kho)`;
            stockInfo.style.color = '#dc3545';
        }
    } else {
        stockInfo.innerHTML = `Còn ${currentStock} sản phẩm trong kho.`;
        stockInfo.style.color = currentStock <= 5 ? '#dc3545' : '#6c757d';
    }
    stockInfo.style.display = 'block';
}

function fetchVariantStock(variantId, productId, stockCheckUrl) {
    if (isLoadingStock) return;
    
    isLoadingStock = true;
    
    // Show loading state
    const stockInfo = document.getElementById('stock-info');
    if (stockInfo) {
        stockInfo.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra kho...';
        stockInfo.style.display = 'block';
        stockInfo.style.color = '#6c757d';
    }
    
    // Prepare request data
    const requestData = {
        variant_id: variantId,
        product_id: productId
    };
    
    // Make AJAX request to get real-time stock data
    $.ajax({
        url: stockCheckUrl,
        method: 'GET',
        data: requestData,
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log('Stock check response:', response);
            
            if (response.success) {
                // Update global variables
                currentStock = response.current_stock;
                currentCartQuantity = response.cart_quantity;
                availableToAdd = response.available_to_add;
                
                // Update price if available
                if (response.price) {
                    const priceElement = document.getElementById('product-price');
                    if (priceElement) {
                        const formattedPrice = new Intl.NumberFormat('vi-VN').format(response.price) + 'đ';
                        priceElement.innerHTML = formattedPrice;
                    }
                }
                
                // Update stock display
                updateStockDisplay();
                
                // Update quantity constraints
                updateQuantityConstraints();
                
            } else {
                showStockError(response.message || 'Không thể kiểm tra kho hàng');
            }
        },
        error: function(xhr, status, error) {
            console.error('Stock check error:', error);
            console.error('Response:', xhr.responseText);
            
            let errorMessage = 'Không thể kiểm tra kho hàng';
            
            if (xhr.status === 404) {
                errorMessage = 'Sản phẩm hoặc phiên bản không tồn tại';
            } else if (xhr.status === 422) {
                errorMessage = 'Dữ liệu không hợp lệ';
            } else if (xhr.status === 500) {
                errorMessage = 'Lỗi server, vui lòng thử lại sau';
            } else if (status === 'timeout') {
                errorMessage = 'Hết thời gian kết nối, vui lòng thử lại';
            }
            
            showStockError(errorMessage);
        },
        complete: function() {
            isLoadingStock = false;
        }
    });
}

function fetchProductStock(productId, stockCheckUrl) {
    if (isLoadingStock) return;
    
    isLoadingStock = true;
    
    // Show loading state
    const stockInfo = document.getElementById('stock-info');
    if (stockInfo) {
        stockInfo.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra kho...';
        stockInfo.style.display = 'block';
        stockInfo.style.color = '#6c757d';
    }
    
    // Prepare request data
    const requestData = {
        product_id: productId
    };
    
    // Make AJAX request to get real-time stock data
    $.ajax({
        url: stockCheckUrl,
        method: 'GET',
        data: requestData,
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log('Product stock check response:', response);
            
            if (response.success) {
                // Update global variables
                currentStock = response.current_stock;
                currentCartQuantity = response.cart_quantity;
                availableToAdd = response.available_to_add;
                
                // Update stock display
                updateStockDisplay();
                
                // Update quantity constraints
                updateQuantityConstraints();
                
            } else {
                showStockError(response.message || 'Không thể kiểm tra kho hàng');
            }
        },
        error: function(xhr, status, error) {
            console.error('Product stock check error:', error);
            console.error('Response:', xhr.responseText);
            
            let errorMessage = 'Không thể kiểm tra kho hàng';
            
            if (xhr.status === 404) {
                errorMessage = 'Sản phẩm không tồn tại';
            } else if (xhr.status === 422) {
                errorMessage = 'Dữ liệu không hợp lệ';
            } else if (xhr.status === 500) {
                errorMessage = 'Lỗi server, vui lòng thử lại sau';
            } else if (status === 'timeout') {
                errorMessage = 'Hết thời gian kết nối, vui lòng thử lại';
            }
            
            showStockError(errorMessage);
        },
        complete: function() {
            isLoadingStock = false;
        }
    });
}

function updateQuantityConstraints() {
    const quantityInput = document.getElementById('quantity-input');
    if (!quantityInput) return;
    
    // Check if product has variants
    const hasVariants = window.productHasVariants || false;
    
    if (hasVariants) {
        // For products with variants, use real-time stock data
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
    } else {
        // For products without variants, use static stock data
        const maxStock = window.productStockQuantity || 0;
        if (maxStock > 0) {
            quantityInput.max = Math.min(maxStock, 100);
            quantityInput.disabled = false;
            
            // Adjust current value if it exceeds available
            if (parseInt(quantityInput.value) > maxStock) {
                quantityInput.value = Math.min(maxStock, 1);
            }
        } else {
            quantityInput.max = 0;
            quantityInput.value = 0;
            quantityInput.disabled = true;
        }
    }
}

function validateQuantity() {
    const quantityInput = document.getElementById('quantity-input');
    const quantityError = document.getElementById('quantity-error');
    if (!quantityInput || !quantityError) return true;
    
    const quantity = parseInt(quantityInput.value) || 0;

    quantityError.style.display = 'none';
    quantityInput.style.borderColor = '';

    // Basic validation
    if (quantity < 1) {
        showQuantityError('Số lượng phải lớn hơn 0');
        quantityInput.value = 1;
        quantityInput.disabled = false;
        return false;
    }

    if (quantity > 100) {
        showQuantityError('Không thể mua quá 100 sản phẩm cùng lúc');
        quantityInput.value = 100;
        quantityInput.disabled = false;
        return false;
    }

    // Check if product has variants
    const hasVariants = window.productHasVariants || false;
    
    if (hasVariants) {
        // For products with variants, use real-time stock data
        if (availableToAdd === 0) {
            if (currentCartQuantity > 0) {
                showQuantityError(`Bạn đã có ${currentCartQuantity} sản phẩm trong giỏ (đạt giới hạn kho)`);
            } else {
                showQuantityError('Sản phẩm đã hết hàng');
            }
            quantityInput.value = 0;
            quantityInput.disabled = true;
            return false;
        }
        
        if (quantity > availableToAdd) {
            if (currentCartQuantity > 0) {
                showQuantityError(
                    `Chỉ có thể thêm tối đa ${availableToAdd} sản phẩm nữa (đã có ${currentCartQuantity} trong giỏ)`
                );
            } else {
                showQuantityError(`Chỉ còn ${availableToAdd} sản phẩm trong kho`);
            }
            quantityInput.value = availableToAdd;
            quantityInput.disabled = false;
            return false;
        }
    } else {
        // For products without variants, use static stock data
        const maxStock = window.productStockQuantity || 0;
        if (maxStock === 0) {
            showQuantityError('Sản phẩm đã hết hàng');
            quantityInput.value = 0;
            quantityInput.disabled = true;
            return false;
        }
        
        if (quantity > maxStock) {
            showQuantityError(`Chỉ còn ${maxStock} sản phẩm trong kho`);
            quantityInput.value = maxStock;
            quantityInput.disabled = false;
            return false;
        }
    }

    quantityInput.disabled = false;
    return true;
}

function showQuantityError(message) {
    const quantityError = document.getElementById('quantity-error');
    const quantityInput = document.getElementById('quantity-input');
    
    if (quantityError) {
        quantityError.innerHTML = message;
        quantityError.style.display = 'block';
    }
    
    if (quantityInput) {
        quantityInput.style.borderColor = '#dc3545';
    }
}

function resetToDefaultState() {
    // Get price values from the DOM instead of embedding PHP
    const priceElement = document.getElementById('product-price');
    if (priceElement) {
        const originalPrice = priceElement.getAttribute('data-original-price');
        if (originalPrice) {
            priceElement.innerHTML = originalPrice;
        }
    }

    // Check if product has variants
    const hasVariants = window.productHasVariants || false;
    
    if (hasVariants) {
        // Reset stock variables for products with variants
        currentStock = 0;
        currentCartQuantity = 0;
        availableToAdd = 0;
        
        // Reset UI
        const stockInfo = document.getElementById('stock-info');
        if (stockInfo) {
            stockInfo.style.display = 'none';
        }
    } else {
        // For products without variants, set static stock data
        currentStock = window.productStockQuantity || 0;
        currentCartQuantity = 0;
        availableToAdd = currentStock;
        
        // Show stock info for products without variants
        updateStockDisplay();
    }
    
    const quantityInput = document.getElementById('quantity-input');
    if (quantityInput) {
        quantityInput.max = 100;
        quantityInput.disabled = false;
        quantityInput.value = 1;
    }

    // Clear any error messages
    const quantityError = document.getElementById('quantity-error');
    if (quantityError) {
        quantityError.style.display = 'none';
    }
    
    if (quantityInput) {
        quantityInput.style.borderColor = '';
    }
    
    // Update quantity constraints
    updateQuantityConstraints();
}

// Initialize stock management
function initStockManagement(config) {
    // Set global configuration
    window.productHasVariants = config.hasVariants || false;
    window.productStockQuantity = config.stockQuantity || 0;
    window.stockCheckUrl = config.stockCheckUrl || '';
    window.productId = config.productId || 0;
    window.isLoggedIn = config.isLoggedIn || false;
    
    // Initialize stock data for products without variants
    if (!config.hasVariants) {
        // Set initial static values
        currentStock = config.stockQuantity || 0;
        currentCartQuantity = 0;
        availableToAdd = currentStock;
        updateStockDisplay();
        updateQuantityConstraints();
        
        // Also fetch real-time data to get current cart quantity (only for logged in users)
        if (config.isLoggedIn) {
            setTimeout(function() {
                fetchProductStock(config.productId, config.stockCheckUrl);
            }, 1000);
        }
    }
    
    // Add event listeners
    setupEventListeners();
}

function setupEventListeners() {
    // Add event listener for quantity input
    $('#quantity-input').on('input change', function() {
        validateQuantity();
        
        // Refresh stock data when quantity changes (for both variants and simple products) - only for logged in users
        if (window.isLoggedIn) {
            if (window.productHasVariants) {
                const variantId = $('#variant-select').val();
                if (variantId && !isLoadingStock) {
                    // Debounce the stock check to avoid too many requests
                    clearTimeout(window.stockCheckTimeout);
                    window.stockCheckTimeout = setTimeout(function() {
                        fetchVariantStock(variantId, window.productId, window.stockCheckUrl);
                    }, 500);
                }
            } else {
                // For products without variants, also check stock real-time
                if (!isLoadingStock) {
                    // Debounce the stock check to avoid too many requests
                    clearTimeout(window.stockCheckTimeout);
                    window.stockCheckTimeout = setTimeout(function() {
                        fetchProductStock(window.productId, window.stockCheckUrl);
                    }, 500);
                }
            }
        }
    });
    
    // Add event listener for variant selection
    $('#variant-select').on('change', function() {
        const variantId = $(this).val();
        
        if (variantId) {
            if (window.isLoggedIn) {
                fetchVariantStock(variantId, window.productId, window.stockCheckUrl);
            } else {
                // For non-logged in users, just update the display with static data
                resetToDefaultState();
            }
        } else {
            resetToDefaultState();
        }
    });
    
    // Periodic stock refresh (mỗi 30 giây) cho cả variant và simple products (chỉ cho user đã đăng nhập)
    if (window.isLoggedIn) {
        setInterval(function() {
            if (window.productHasVariants) {
                const variantId = $('#variant-select').val();
                if (variantId && !isLoadingStock) {
                    fetchVariantStock(variantId, window.productId, window.stockCheckUrl);
                }
            } else {
                if (!isLoadingStock) {
                    fetchProductStock(window.productId, window.stockCheckUrl);
                }
            }
        }, 30000); // 30 seconds

        // Refresh stock khi user focus lại vào tab/window
        $(window).on('focus', function() {
            if (window.productHasVariants) {
                const variantId = $('#variant-select').val();
                if (variantId && !isLoadingStock) {
                    fetchVariantStock(variantId, window.productId, window.stockCheckUrl);
                }
            } else {
                if (!isLoadingStock) {
                    fetchProductStock(window.productId, window.stockCheckUrl);
                }
            }
        });
    }
    
    // Function to update price and stock when variant is selected
    function updatePriceAndStock(select) {
        const selectedOption = select.options[select.selectedIndex];
        const variantId = selectedOption.value;

        if (variantId) {
            // Show loading state
            const stockInfo = document.getElementById('stock-info');
            if (stockInfo) {
                stockInfo.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra kho...';
                stockInfo.style.display = 'block';
                stockInfo.style.color = '#6c757d';
            }

            // Fetch real-time stock data
            fetchVariantStock(variantId, window.productId, window.stockCheckUrl);
        } else {
            // Reset to original price when no variant is selected
            resetToDefaultState();
        }
    }
    
    // Make updatePriceAndStock globally available
    window.updatePriceAndStock = updatePriceAndStock;
} 