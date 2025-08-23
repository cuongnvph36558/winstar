@extends('layouts.client')

@section('title', 'Chi Tiết Sản Phẩm')

@section('styles')
<style>
    /* Toast Notification Styles */
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        pointer-events: none;
    }
    
    .toast {
        margin-bottom: 15px;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 350px;
        max-width: 500px;
        font-size: 16px;
        position: relative;
        z-index: 10000;
        pointer-events: auto;
        animation: slideInRight 0.4s ease-out;
    }
    
    .toast.success {
        background: #d4edda;
        border: 2px solid #28a745;
        color: #155724;
    }
    
    .toast.error {
        background: #f8d7da;
        border: 2px solid #dc3545;
        color: #721c24;
    }
    
    .toast.warning {
        background: #fff3cd;
        border: 2px solid #ffc107;
        color: #856404;
    }
    
    .toast.info {
        background: #d1ecf1;
        border: 2px solid #17a2b8;
        color: #0c5460;
    }
    
    .toast .close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        margin-left: 10px;
        opacity: 0.7;
        transition: opacity 0.3s;
    }
    
    .toast .close:hover {
        opacity: 1;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endsection

@section('content')

<!-- Demo Toast Button (Chỉ để test - có thể xóa sau) -->
<div style="position: fixed; top: 10px; left: 10px; z-index: 9998;">
    <button onclick="demoToast()" style="background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-size: 14px;">
        🍞 Test Toast
    </button>
</div>
<script>
    // Lưu trữ giá ban đầu
    let originalPriceHTML = '';
    
    // Biến global cho stock
    let currentStock = 0;
    let currentCartQuantity = 0;
    let availableToAdd = 0;
    let isLoadingStock = false;
    
    // Định nghĩa tất cả hàm ngay từ đầu để tránh lỗi
    window.updatePriceAndStock = function(select) {
        console.log('updatePriceAndStock called');
        const selectedOption = select.options[select.selectedIndex];
        const variantId = selectedOption.value;

        console.log('updatePriceAndStock called with variantId:', variantId);
        console.log('Selected option:', selectedOption);

        if (variantId) {
            // Hiển thị loading state
            const stockInfo = document.getElementById('stock-info');
            if (stockInfo) {
                stockInfo.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra kho...';
                stockInfo.style.display = 'block';
                stockInfo.style.color = '#6c757d';
            }

            // Cập nhật giá ngay lập tức
            updatePriceFromOption(selectedOption);
            
            // Gọi API để lấy thông tin stock real-time
            fetchVariantStock(variantId);
        } else {
            // Reset về giá ban đầu khi chưa chọn variant
            console.log('Resetting to default state');
            resetToDefaultState();
        }
    };

    // Định nghĩa các hàm helper
    window.updatePriceFromOption = function(option) {
        const priceElement = document.getElementById('product-price');
        if (!priceElement) {
            console.error('Price element not found');
            return;
        }
        
        console.log('Updating price from option:', option);
        
        // Lấy dữ liệu từ data attributes
        const price = parseFloat(option.getAttribute('data-price')) || 0;
        const promotionPrice = parseFloat(option.getAttribute('data-promotion-price')) || 0;
        
        console.log('Price:', price, 'Promotion price:', promotionPrice);
        
        if (promotionPrice > 0) {
            // Hiển thị cả giá khuyến mãi và giá gốc
            priceElement.innerHTML = `
                <span class="promotion-price">${formatPrice(promotionPrice)}đ</span>
                <span class="old-price ml-2">${formatPrice(price)}đ</span>
            `;
        } else {
            // Chỉ hiển thị giá gốc
            priceElement.innerHTML = `<span class="amount">${formatPrice(price)}đ</span>`;
        }
        
        // Thêm animation
        priceElement.classList.add('updated');
        setTimeout(() => {
            priceElement.classList.remove('updated');
        }, 500);
    };

    // Hàm helper để format giá
    window.formatPrice = function(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    };

    window.fetchVariantStock = function(variantId) {
        console.log('Fetching variant stock for:', variantId);
        // Gọi API để lấy thông tin stock real-time
        fetch(`{{ route('client.variant-stock') }}?variant_id=${variantId}&product_id={{ $product->id }}`)
            .then(response => response.json())
            .then(data => {
                console.log('Stock data received:', data);
                if (data.success) {
                    updateStockDisplay(data);
                } else {
                    showStockError(data.message || 'Không thể lấy thông tin kho');
                }
            })
            .catch(error => {
                console.error('Error fetching variant stock:', error);
                showStockError('Lỗi kết nối, vui lòng thử lại');
            });
    };

    window.updateStockDisplay = function(data) {
        const stockInfo = document.getElementById('stock-info');
        const quantityInput = document.getElementById('quantity-input');
        const addToCartBtn = document.querySelector('.add-to-cart-form button[type="submit"]');
        const buyNowBtn = document.querySelector('.btn-buy-now');
        
        if (!stockInfo) return;
        
        currentStock = data.current_stock || 0;
        currentCartQuantity = data.cart_quantity || 0;
        availableToAdd = currentStock - currentCartQuantity;
        
        // Cập nhật hiển thị stock
        if (currentStock <= 0) {
            stockInfo.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Hết hàng</span>';
            stockInfo.style.display = 'block';
            if (quantityInput) {
                quantityInput.disabled = true;
                quantityInput.value = 0;
            }
            if (addToCartBtn) addToCartBtn.disabled = true;
            if (buyNowBtn) buyNowBtn.disabled = true;
        } else if (currentStock <= 5) {
            stockInfo.innerHTML = `<span class="text-warning"><i class="fas fa-exclamation-circle"></i> Chỉ còn ${currentStock} sản phẩm trong kho</span>`;
            stockInfo.style.display = 'block';
            if (quantityInput) {
                quantityInput.disabled = false;
                // Reset quantity to 1 if it was 0 (from out-of-stock state)
                if (parseInt(quantityInput.value) <= 0) {
                    quantityInput.value = 1;
                }
            }
            if (addToCartBtn) addToCartBtn.disabled = false;
            if (buyNowBtn) buyNowBtn.disabled = false;
        } else {
            stockInfo.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> Còn ${currentStock} sản phẩm trong kho</span>`;
            stockInfo.style.display = 'block';
            if (quantityInput) {
                quantityInput.disabled = false;
                // Reset quantity to 1 if it was 0 (from out-of-stock state)
                if (parseInt(quantityInput.value) <= 0) {
                    quantityInput.value = 1;
                }
            }
            if (addToCartBtn) addToCartBtn.disabled = false;
            if (buyNowBtn) buyNowBtn.disabled = false;
        }
        
        // Cập nhật max quantity
        if (quantityInput) {
            quantityInput.max = availableToAdd;
            validateQuantity(); // Validate quantity after stock update
        }
    };

    window.showStockError = function(message) {
        const stockInfo = document.getElementById('stock-info');
        if (stockInfo) {
            stockInfo.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${message}</span>`;
            stockInfo.style.display = 'block';
        }
    };

    window.resetToDefaultState = function() {
        const stockInfo = document.getElementById('stock-info');
        const quantityInput = document.getElementById('quantity-input');
        const addToCartBtn = document.querySelector('.add-to-cart-form button[type="submit"]');
        const buyNowBtn = document.querySelector('.btn-buy-now');
        
        if (stockInfo) {
            stockInfo.style.display = 'none';
        }
        
        if (quantityInput) {
            quantityInput.disabled = false;
            quantityInput.max = 100;
            // Reset quantity to 1 when no variant is selected
            quantityInput.value = 1;
            validateQuantity(); // Validate quantity after reset
        }
        
        if (addToCartBtn) {
            addToCartBtn.disabled = false;
        }
        
        if (buyNowBtn) {
            buyNowBtn.disabled = false;
        }
        
        // Reset về giá ban đầu
        const priceElement = document.getElementById('product-price');
        if (priceElement && originalPriceHTML) {
            priceElement.innerHTML = originalPriceHTML;
        }
    };

    // Lưu giá ban đầu khi trang load
    document.addEventListener('DOMContentLoaded', function() {
        const priceElement = document.getElementById('product-price');
        if (priceElement) {
            originalPriceHTML = priceElement.innerHTML;
        }
    });

    // Xử lý form submit cho thêm vào giỏ hàng
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('add-to-cart-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Ngăn form submit thông thường
                
                const quantityInput = document.getElementById('quantity-input');
                const variantSelect = document.getElementById('variant-select');
                const addToCartBtn = document.querySelector('.btn-add-to-cart');
                
                // Validate variant selection if product has variants
                if (variantSelect && variantSelect.options.length > 1 && variantSelect.value === '') {
                    alert('Vui lòng chọn phiên bản sản phẩm');
                    return;
                }
                
                // Validate quantity
                if (quantityInput && parseInt(quantityInput.value) > availableToAdd) {
                    showToast(`Chỉ có thể thêm tối đa ${availableToAdd} sản phẩm vào giỏ hàng`, 'warning', 'Giới hạn số lượng');
                    return;
                }
                
                // Validate quantity against maximum limit
                if (quantityInput && parseInt(quantityInput.value) > 100) {
                    showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
                    return;
                }
                
                // Disable button to prevent double click
                if (addToCartBtn) {
                    addToCartBtn.disabled = true;
                    addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
                }
                
                // Create form data
                const formData = new FormData();
                formData.append('product_id', '{{ $product->id }}');
                formData.append('quantity', quantityInput ? quantityInput.value : 1);
                
                if (variantSelect && variantSelect.value) {
                    formData.append('variant_id', variantSelect.value);
                }
                
                formData.append('_token', '{{ csrf_token() }}');
                
                // Submit via AJAX
                fetch('{{ route("client.add-to-cart") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                        
                        // Reset quantity to 1
                        if (quantityInput) {
                            quantityInput.value = 1;
                        }
                        
                        // Update cart count if available
                        if (data.cart_count !== undefined) {
                            const cartCountElement = document.querySelector('.cart-count');
                            if (cartCountElement) {
                                cartCountElement.textContent = data.cart_count;
                                cartCountElement.classList.add('updated');
                                setTimeout(() => {
                                    cartCountElement.classList.remove('updated');
                                }, 600);
                            }
                        }
                        
                        // Update navbar cart count using global function
                        if (window.updateCartCount) {
                            window.updateCartCount(data.cart_count);
                        }
                        
                        // Update stock info if provided
                        if (data.stock_info) {
                            availableToAdd = data.stock_info.available_to_add;
                            currentCartQuantity = data.stock_info.cart_quantity;
                            currentStock = data.stock_info.current_stock;
                            updateStockDisplay();
                            updateQuantityConstraints();
                        }
                    } else {
                        // Hiển thị thông báo lỗi thân thiện
                        if (data.toast_type && data.toast_title) {
                            showToast(data.message, data.toast_type, data.toast_title);
                        } else {
                            showToast(data.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
                        }
                        
                        // Cập nhật số lượng có thể thêm nếu có
                        if (data.available_quantity !== undefined) {
                            availableToAdd = data.available_quantity;
                            updateQuantityConstraints();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
                })
                .finally(() => {
                    // Re-enable button
                    if (addToCartBtn) {
                        addToCartBtn.disabled = false;
                        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart"></i><span>Thêm vào giỏ hàng</span>';
                    }
                });
            });
        }
    });

    // Buy Now function
    function buyNow() {
        const quantityInput = document.getElementById('quantity-input');
        const variantSelect = document.getElementById('variant-select');
        const buyNowBtn = document.querySelector('.btn-buy-now');
        
        // Disable button to prevent double click
        if (buyNowBtn) {
            buyNowBtn.disabled = true;
            buyNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        }
        
        // Validate variant selection if product has variants
        if (variantSelect && variantSelect.options.length > 1 && variantSelect.value === '') {
            alert('Vui lòng chọn phiên bản sản phẩm');
            if (buyNowBtn) {
                buyNowBtn.disabled = false;
                buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i><span>Mua ngay</span>';
            }
            return;
        }
        
        // Validate quantity
        if (quantityInput && parseInt(quantityInput.value) > availableToAdd) {
            showToast(`Chỉ có thể mua tối đa ${availableToAdd} sản phẩm`, 'warning', 'Giới hạn số lượng');
            if (buyNowBtn) {
                buyNowBtn.disabled = false;
                buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i><span>Mua ngay</span>';
            }
            return;
        }
        
        // Validate quantity against maximum limit
        if (quantityInput && parseInt(quantityInput.value) > 100) {
            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
            if (buyNowBtn) {
                buyNowBtn.disabled = false;
                buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i><span>Mua ngay</span>';
            }
            return;
        }
        
        // Double check quantity before sending request
        const finalQuantity = quantityInput ? parseInt(quantityInput.value) : 1;
        if (finalQuantity > 100) {
            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
            if (buyNowBtn) {
                buyNowBtn.disabled = false;
                buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i><span>Mua ngay</span>';
            }
            return;
        }
        
        // Store quantity for error handling
        const requestQuantity = finalQuantity;
        
        // Create form data for buy now
        const formData = new FormData();
        formData.append('product_id', '{{ $product->id }}');
        formData.append('quantity', quantityInput ? quantityInput.value : 1);
        
        if (variantSelect && variantSelect.value) {
            formData.append('variant_id', variantSelect.value);
        }
        
        formData.append('buy_now', '1');
        formData.append('_token', '{{ csrf_token() }}');
        
        // Submit to buy now
        fetch('{{ route("client.buy-now") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(JSON.stringify(data));
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirect to checkout page
                window.location.href = '{{ route("client.checkout") }}';
            } else {
                // Handle authentication error
                if (data.redirect_to_login) {
                    if (confirm('Vui lòng đăng nhập để tiếp tục. Bạn có muốn chuyển đến trang đăng nhập?')) {
                        window.location.href = data.login_url;
                    }
                } else {
                    // Hiển thị thông báo lỗi thân thiện
                    if (data.toast_type && data.toast_title) {
                        showToast(data.message, data.toast_type, data.toast_title);
                    } else {
                        // Kiểm tra nếu có số lượng trong form data
                        const quantity = requestQuantity || (quantityInput ? parseInt(quantityInput.value) : 1);
                        if (quantity > 100) {
                            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
                        } else {
                            showToast(data.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
                        }
                    }
                }
                if (buyNowBtn) {
                    buyNowBtn.disabled = false;
                    buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i><span>Mua ngay</span>';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            try {
                const errorData = JSON.parse(error.message);
                console.log('Error data:', errorData);
                
                if (errorData.redirect_to_login) {
                    if (confirm('Vui lòng đăng nhập để tiếp tục. Bạn có muốn chuyển đến trang đăng nhập?')) {
                        window.location.href = errorData.login_url;
                    }
                } else {
                    // Hiển thị thông báo lỗi thân thiện
                    if (errorData.toast_type && errorData.toast_title) {
                        showToast(errorData.message, errorData.toast_type, errorData.toast_title);
                    } else {
                        // Kiểm tra nếu có số lượng trong form data
                        const quantity = requestQuantity || (quantityInput ? parseInt(quantityInput.value) : 1);
                        console.log('Quantity from input:', quantity);
                        if (quantity > 100) {
                            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
                        } else {
                            showToast(errorData.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
                        }
                    }
                }
            } catch (e) {
                console.log('Parse error:', e);
                // Fallback: kiểm tra số lượng và hiển thị thông báo phù hợp
                const quantity = requestQuantity || (quantityInput ? parseInt(quantityInput.value) : 1);
                console.log('Fallback quantity:', quantity);
                if (quantity > 100) {
                    showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
                } else {
                    showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
                }
            }
            if (buyNowBtn) {
                buyNowBtn.disabled = false;
                buyNowBtn.innerHTML = '<i class="fas fa-bolt"></i><span>Mua ngay</span>';
            }
        });
    }

    // Quantity change function
    function changeQuantity(delta) {
        const quantityInput = document.getElementById('quantity-input');
        const currentValue = getCurrentQuantity();
        const newValue = Math.max(1, Math.min(100, currentValue + delta));
        quantityInput.value = newValue;
        validateQuantity();
    }

    // Validate quantity input
    function validateQuantity() {
        const quantityInput = document.getElementById('quantity-input');
        const quantityError = document.getElementById('quantity-error');
        let value = parseInt(quantityInput.value) || 0;
        let isValid = true;
        
        // Reset error message
        if (quantityError) {
            quantityError.style.display = 'none';
            quantityError.textContent = '';
        }
        
        // Validate minimum value
        if (value < 1) {
            value = 1;
            quantityInput.value = value;
        }
        
        // Validate maximum value based on available stock
        const maxStock = availableToAdd > 0 ? availableToAdd : 100;
        if (value > maxStock) {
            value = maxStock;
            quantityInput.value = value;
            if (quantityError) {
                quantityError.style.display = 'block';
                quantityError.textContent = `Số lượng tối đa có thể mua là ${maxStock} sản phẩm`;
            }
            isValid = false;
        }
        
        // Validate against general maximum
        if (value > 100) {
            value = 100;
            quantityInput.value = value;
            if (quantityError) {
                quantityError.style.display = 'block';
                quantityError.textContent = 'Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn';
            }
            // Hiển thị toast notification
            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
            isValid = false;
        }
        
        return isValid;
    }

    // Get current quantity value
    function getCurrentQuantity() {
        const quantityInput = document.getElementById('quantity-input');
        return parseInt(quantityInput.value) || 1;
    }

    // Add event listener for quantity input
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity-input');
        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value) || 0;
                if (value > 100) {
                    // Hiển thị toast notification ngay khi nhập số lượng > 100
                    showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
                }
            });
            
            quantityInput.addEventListener('blur', function() {
                validateQuantity();
            });
        }
    });

    // Image navigation functions
    let currentImageIndex = 0;
    const images = [
        "{{ asset('storage/' . $product->image) }}",
                        @foreach ($product->variants as $variant)
                        @if ($variant->image_variant)
                        @php
                $variantImages = json_decode($variant->image_variant, true);
                        @endphp
                @if (is_array($variantImages))
                    @foreach ($variantImages as $image)
                        "{{ asset('storage/' . $image) }}",
                        @endforeach
                        @endif
                        @endif
                        @endforeach
    ];

    function showImage(index) {
        if (index >= 0 && index < images.length) {
            currentImageIndex = index;
            const mainImage = document.getElementById('main-image');
            const modalImage = document.getElementById('modalImage');
            const currentImageNumber = document.getElementById('currentImageNumber');
            
            if (mainImage) {
                mainImage.src = images[index];
            }
            
            if (modalImage) {
                modalImage.src = images[index];
            }
            
            if (currentImageNumber) {
                currentImageNumber.textContent = index + 1;
            }
            
            // Update thumbnail active state
            const thumbnails = document.querySelectorAll('.thumbnail-container');
            thumbnails.forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }
    }

    function previousImage() {
        const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
        showImage(newIndex);
    }

    function nextImage() {
        const newIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
        showImage(newIndex);
    }

    // Image modal functions
    function openImageModal() {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const currentImageNumber = document.getElementById('currentImageNumber');
        
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Set the current image in modal
            if (modalImage && images[currentImageIndex]) {
                modalImage.src = images[currentImageIndex];
            }
            
            if (currentImageNumber) {
                currentImageNumber.textContent = currentImageIndex + 1;
            }
        }
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowLeft') {
            previousImage();
        } else if (event.key === 'ArrowRight') {
            nextImage();
        } else if (event.key === 'Escape') {
            closeImageModal();
        }
    });
</script>

<!-- Breadcrumb -->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.product') }}">Sản phẩm</a></li>
                    @if($product->category)
                    <li class="breadcrumb-item"><a href="#">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
                        </div>
                        
<!-- Product Detail Section -->
<div class="container">
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="product-gallery-container">
                <!-- Main Image -->
                <div class="main-image-container">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}"
                         class="main-product-image" 
                         id="main-image" />
                    
                    <!-- Image Controls -->
                    <div class="image-controls">
                        <button class="control-btn prev-btn" onclick="previousImage()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <button class="control-btn next-btn" onclick="nextImage()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    </div>
                    
                    <!-- Thumbnail Navigation -->
                    <div class="thumbnail-nav">
                        <div class="thumbnail-container active" onclick="showImage(0)">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="thumbnail-image" />
                        </div>
                        
                        @php $thumbIndex = 1; @endphp
                        @foreach ($product->variants as $variant)
                            @if ($variant->image_variant)
                                @php
                                $images = json_decode($variant->image_variant, true);
                                @endphp
                                @if (is_array($images))
                                    @foreach ($images as $image)
                                    <div class="thumbnail-container" onclick="showImage({{ $thumbIndex }})">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             alt="{{ $product->name }} - {{ ($variant->storage && isset($variant->storage->capacity)) ? $variant->storage->capacity : '' }} {{ ($variant->color && isset($variant->color->name)) ? $variant->color->name : '' }}"
                                             class="thumbnail-image" />
                                    </div>
                                    @php $thumbIndex++; @endphp
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

        <!-- Product Information -->
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="product-info-container">
                <!-- Product Title -->
                <h1 class="product-title">{{ $product->name }}</h1>

                <!-- Rating and Reviews -->
                <div class="product-rating-section">
                    <div class="rating-stars">
                            @if ($totalReviews > 0)
                                @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($averageRating))
                                    <i class="fas fa-star star"></i>
                                    @else
                                    <i class="fas fa-star star-off"></i>
                                    @endif
                                    @endfor
                                    <span class="rating-text">
                                {{ number_format($averageRating, 1) }}/5 
                                <a class="review-link" href="#reviews">({{ $totalReviews }} đánh giá)</a>
                                    </span>
                            @else
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star-off"></i>
                                @endfor
                                <span class="rating-text">
                                <a class="review-link" href="#reviews">({{ $totalReviews }} đánh giá)</a>
                                </span>
                            @endif
                        </div>
                    
                    <div class="buyers-info">
                        <i class="fas fa-users"></i> {{ $totalBuyers }} người đã mua
                    </div>


                    </div>

                <!-- Price Section -->
                <div class="price-section">
                        @if($product->variants->count() > 0)
                        @php
                        $minPromotion = $product->variants->where('promotion_price', '>', 0)->min('promotion_price');
                        $maxPromotion = $product->variants->where('promotion_price', '>', 0)->max('promotion_price');
                        $minPrice = $product->variants->min('price') ?? 0;
                        $maxPrice = $product->variants->max('price') ?? 0;
                        @endphp
                        <div class="price-display" id="product-price">
                                @if($minPromotion && $minPromotion > 0)
                                @if($minPromotion == $maxPromotion)
                                    <span class="current-price">{{ number_format($minPromotion, 0, ',', '.') }}đ</span>
                                    <span class="original-price">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                @else
                                    <span class="current-price">{{ number_format($minPromotion, 0, ',', '.') }}đ - {{ number_format($maxPromotion, 0, ',', '.') }}đ</span>
                                    <span class="original-price">{{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ</span>
                                @endif
                                @else
                                @if($minPrice == $maxPrice)
                                    <span class="current-price">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                @else
                                    <span class="current-price">{{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ</span>
                                @endif
                                @endif
                        </div>
                        @else
                        <div class="price-display" id="product-price">
                                @if($product->promotion_price && $product->promotion_price > 0)
                                <span class="current-price">{{ number_format($product->promotion_price, 0, ',', '.') }}đ</span>
                                <span class="original-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @else
                                <span class="current-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @endif
                        </div>
                        @endif
                    </div>

                                <!-- Purchase Form -->
                <div class="purchase-form">
                    <form action="{{ route('client.add-to-cart') }}" method="POST" class="add-to-cart-form" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                            @if($product->variants && $product->variants->count() > 0)
                        <!-- Variant Selection -->
                        <div class="form-group variant-selection">
                            <label class="form-label">Chọn phiên bản:</label>
                            <select class="form-control variant-select" name="variant_id" required
                                    onchange="updatePriceAndStock(this)" id="variant-select">
                                    <option value="">-- Chọn phiên bản --</option>
                                    @foreach ($product->variants->sortBy('price') as $variant)
                                    <option value="{{ $variant->id }}" 
                                        data-price="{{ $variant->price }}"
                                        data-promotion-price="{{ $variant->promotion_price ?? 0 }}"
                                        data-stock="{{ $variant->stock_quantity }}">
                                        {{ ($variant->storage && isset($variant->storage->capacity)) ? $variant->storage->capacity : '' }} - {{ ($variant->color && isset($variant->color->name)) ? $variant->color->name : '' }}
                                        @if($variant->promotion_price && $variant->promotion_price > 0)
                                        - {{ number_format($variant->promotion_price, 0, ',', '.') }}đ
                                        @else
                                        - {{ number_format($variant->price, 0, ',', '.') }}đ
                                        @endif
                                        @if (($variant->stock_quantity ?? 0) <= 0)
                                            (Hết hàng)
                                        @elseif (($variant->stock_quantity ?? 0) <= 5)
                                            (Còn {{ $variant->stock_quantity ?? 0 }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                        <!-- Action Buttons Container -->
                        <div class="action-buttons-container">
                            <div class="quantity-group">
                                <label for="quantity-input" class="form-label">Số lượng:</label>
                                <div class="quantity-input-wrapper">
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                                    <input type="number" id="quantity-input" name="quantity" value="1" min="1" class="quantity-input" onchange="validateQuantity()" oninput="validateQuantity()">
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                                </div>
                                <div id="quantity-error" class="text-danger mt-1" style="display: none;"></div>
                                <div class="stock-info" id="stock-info" style="display: @if($product->variants->count() == 0) block @else none @endif;">
                                    @if($product->variants->count() == 0)
                                        @if(($product->stock_quantity ?? 0) <= 0)
                                            <span class="stock-status out-of-stock"><i class="fas fa-exclamation-triangle"></i> Hết hàng</span>
                                        @elseif(($product->stock_quantity ?? 0) <= 5)
                                            <span class="stock-status low-stock"><i class="fas fa-exclamation-circle"></i> Còn {{ $product->stock_quantity ?? 0 }} sản phẩm trong kho</span>
                                        @else
                                            <span class="stock-status in-stock"><i class="fas fa-check-circle"></i> Còn {{ $product->stock_quantity ?? 0 }} sản phẩm trong kho</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="add-to-cart-group">
                                <button type="submit" class="btn-add-to-cart">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Thêm vào giỏ hàng</span>
                                </button>
                            </div>
                            <div class="buy-now-favorite-group">
                                <div class="buy-now-group">
                                    <button type="button" class="btn-buy-now" onclick="buyNow()">
                                        <i class="fas fa-bolt"></i>
                                        <span>Mua ngay</span>
                                    </button>
                                </div>
                                <div class="favorite-group">
                                    @php
                                    $isFavorited = auth()->check() && auth()->user()->favorites()->where('product_id', $product->id)->exists();
                                    @endphp
                                    <button type="button" class="btn {{ $isFavorited ? 'btn-danger remove-favorite' : 'btn-outline-danger add-favorite' }}"
                                            data-product-id="{{ $product->id }}"
                                            title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                        <i class="{{ $isFavorited ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                                <!-- Product Meta -->
                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Danh mục:</span>
                        <a href="#" class="meta-link">{{ $product->category->name ?? 'Không có danh mục' }}</a>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Mô tả:</span>
                        <div class="meta-description">
                            {{ $product->description }}
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Product Tabs Section -->
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="product-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#data-sheet" data-toggle="tab">
                            <i class="fas fa-list"></i> Thông số kỹ thuật
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reviews" data-toggle="tab">
                            <i class="fas fa-comments"></i> Đánh giá ({{ $totalReviews }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#comments" data-toggle="tab">
                            <i class="far fa-comments"></i> Bình luận
                        </a>
                    </li>
                </ul>

                <div class="tab-content">


                    <!-- Comments Tab -->
                    <div class="tab-pane fade" id="comments">
                        <div class="tab-content-body">
                            <h3>Bình luận</h3>

                            @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Comment Form -->
                            @auth
                            <div class="comment-form-container">
                            <form class="comment-form" method="POST" action="{{ route('client.comment.store') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="form-group">
                                        <textarea name="content" class="form-control" placeholder="Nhập bình luận của bạn..."
                                            required rows="4"></textarea>
                                </div>
                                    <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                            </form>
                            </div>
                            @else
                            <div class="auth-notice">
                                <i class="fas fa-info-circle"></i>
                                Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.
                            </div>
                            @endauth

                            <!-- Comments List -->
                            <div class="comments-list">
                            @if ($product->comments->count())
                            @foreach ($product->activeComments as $comment)
                            <div class="comment-item">
                                <div class="comment-header">
                                            <div class="comment-author">
                                                <i class="fas fa-user-circle"></i>
                                                <span class="author-name">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
                                            </div>
                                            <div class="comment-date">
                                                {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </div>
                                </div>
                                <div class="comment-content">
                                    {{ $comment->content }}
                                </div>
                            </div>
                            @endforeach
                            @else
                                    <div class="no-comments">
                                        <i class="far fa-comment"></i>
                                        <p>Chưa có bình luận nào.</p>
                                    </div>
                            @endif
                        </div>
                        </div>
                    </div>

                    <!-- Technical Specifications Tab -->
                    <div class="tab-pane fade" id="data-sheet">
                        <div class="tab-content-body">
                            @if(($variantStorages && $variantStorages->count() > 0) || ($variantColors && $variantColors->count() > 0))
                            <h3>Thông số kỹ thuật</h3>
                            <div class="specs-table">
                                <table class="table">
                            <tbody>
                                            @if($variantStorages && $variantStorages->count() > 0)
                                <tr>
                                            <th>Tùy chọn bộ nhớ</th>
                                    <td>
                                        @foreach ($variantStorages as $storage)
                                                <span class="spec-badge">{{ $storage->capacity }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                            @endif
                                            @if($variantColors && $variantColors->count() > 0)
                                <tr>
                                            <th>Màu sắc có sẵn</th>
                                    <td>
                                        @foreach ($variantColors as $color)
                                                <span class="spec-badge">{{ $color->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                            @endif
                            </tbody>
                        </table>
                            </div>
                            @else
                                <div class="no-specs">
                                    <i class="fas fa-info-circle"></i>
                                    <p>Chưa có thông số kỹ thuật chi tiết cho sản phẩm này.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews">
                        <div class="tab-content-body">
                            <!-- Rating Overview -->
                            <div class="rating-overview">
                                <div class="rating-container">
                                        <div class="rating-summary">
                                        <div class="average-rating">
                                            <span class="rating-number">{{ $totalReviews > 0 ? number_format($averageRating, 1) : '0.0' }}</span>
                                            <span class="rating-total">/5</span>
                                        </div>
                                            <div class="rating-stars">
                                            @if ($totalReviews > 0)
                                                @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= round($averageRating))
                                                    <i class="fas fa-star star"></i>
                                                    @else
                                                    <i class="fas fa-star star-off"></i>
                                                    @endif
                                                    @endfor
                                            @else
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star star-off"></i>
                                                @endfor
                                            @endif
                                        </div>
                                        <p class="rating-count">{{ $totalReviews }} đánh giá</p>
                                    </div>
                                    <div class="rating-breakdown">
                                        @for ($i = 5; $i >= 1; $i--)
                                        <div class="rating-item">
                                            <span class="star-count">{{ $i }} sao</span>
                                                <div class="progress-container">
                                                    @php
                                                    $percentage = $totalReviews > 0 ? round(($ratingStats[$i] / $totalReviews) * 100, 1) : 0;
                                                @endphp
                                                    <div class="progress-bar" style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <span class="star-count-number">({{ $ratingStats[$i] }})</span>
                                        </div>
                                        @endfor
                                </div>
                            </div>
                        </div>

                        @auth
                        @php
                        $userReview = $reviews->where('user_id', auth()->id())->first();
                        $hasPurchased = \App\Models\Order::where('user_id', auth()->id())
                            ->where('status', 'completed')
                            ->whereHas('orderDetails', function($query) use ($product) {
                                $query->where('product_id', $product->id);
                            })
                            ->exists();
                        @endphp

                        @if ($hasPurchased)
                            <div class="user-review-notice">
                            <div class="alert alert-success">
                                <i class="fas fa-star"></i>
                                @if ($userReview)
                                    Bạn có thể đánh giá lại sản phẩm này bất cứ lúc nào! Chia sẻ trải nghiệm mới nhất của bạn.
                                @else
                                    Bạn đã mua sản phẩm này! Hãy chia sẻ trải nghiệm của bạn với cộng đồng.
                                @endif
                            </div>
                        </div>
                        @endif

                            <!-- Reviews List -->
                            <div class="reviews-list">
                            <h4 class="font-alt mb-20">Đánh giá từ khách hàng</h4>
                            @foreach ($reviews as $review)
                                <div class="review-item">
                                <div class="review-avatar">
                                    @if ($review->user && $review->user->avatar)
                                        <img src="{{ asset('storage/' . $review->user->avatar) }}" alt="Ảnh đại diện" />
                                    @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($review->name ?? 'U', 0, 1)) }}
                                    </div>
                                    @endif
                                </div>
                                <div class="review-content">
                                    <div class="review-header">
                                            <h5 class="review-author">
                                            {{ $review->name ?? ($review->user->name ?? 'Khách hàng') }}
                                        </h5>
                                        <div class="review-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                <i class="fas fa-star star"></i>
                                                @else
                                                <i class="fas fa-star star-off"></i>
                                                @endif
                                                @endfor
                                                <span class="review-date">{{ $review->created_at ? $review->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <p class="review-text">{{ $review->content }}</p>
                                    @if ($review->user_id === auth()->id())
                                    <span class="review-badge">Đánh giá của bạn</span>
                                    @if ($review->status == 0)
                                        <span class="review-status-badge review-pending">(Chờ duyệt)</span>
                                    @else
                                        <span class="review-status-badge review-approved">(Đã duyệt)</span>
                                    @endif
                                    <span class="review-date-badge">({{ $review->created_at ? $review->created_at->format('d/m/Y H:i') : 'N/A' }})</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <p>Vui lòng đăng nhập để xem đánh giá.</p>
                            <a href="{{ route('login') }}" class="btn btn-round btn-d">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

 <!-- Related Products Section -->


 <!-- Image Modal -->
 <div id="imageModal" class="image-modal" style="display: none;">
     <div class="modal-content">
         <span class="close-modal" onclick="closeImageModal()">&times;</span>
         <div class="modal-image-container">
             <img id="modalImage" src="" alt="Product Image" />
         </div>
         <div class="modal-controls">
             <button class="modal-btn" onclick="previousImage()">
                 <i class="fas fa-chevron-left"></i>
             </button>
             <span class="image-counter">
                 <span id="currentImageNumber">1</span> / <span id="totalImages">{{ count($product->variants) + 1 }}</span>
             </span>
             <button class="modal-btn" onclick="nextImage()">
                 <i class="fas fa-chevron-right"></i>
             </button>
         </div>
     </div>
 </div>



<!-- Toast notifications -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<!-- JavaScript for product links -->
<script>
    // Ensure this runs after DOM is ready
    function setupProductLinks() {
        console.log('🔍 Setting up product link handlers...');
        // Ensure product image links work properly (only for related products)
        const productLinks = document.querySelectorAll('.shop-item .product-link');
        console.log('Found product links:', productLinks.length);

        productLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Prevent event bubbling that might interfere
                e.stopPropagation();
                e.preventDefault();

                // Get the href and navigate
                const href = this.getAttribute('href');
                console.log('Product link clicked:', href); // Debug log
                if (href) {
                    window.location.href = href;
                }
            });

            // Add cursor pointer to ensure clickable appearance
            link.style.cursor = 'pointer';
        });

        // Also handle clicks on the entire shop item (fallback)
        const shopItems = document.querySelectorAll('.shop-item');

        shopItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                console.log('Shop item clicked:', e.target);
                // Only if not clicking on a button or link already
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    const productLink = this.querySelector('.product-link');
                    if (productLink) {
                        const href = productLink.getAttribute('href');
                        console.log('Shop item fallback click:', href);
                        if (href) {
                            window.location.href = href;
                        }
                    }
                }
            });
        });
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupProductLinks);
    } else {
        setupProductLinks();
    }

    // Also run after jQuery is ready as backup
    $(document).ready(function() {
        console.log('jQuery ready - setting up product links backup');
        setupProductLinks();
        
        // Enhanced Tab Functionality
        setupEnhancedTabs();
    });

    // Enhanced Tab Functionality
    function setupEnhancedTabs() {
        const tabLinks = document.querySelectorAll('.nav-tabs .nav-link');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and panes
                tabLinks.forEach(tab => tab.classList.remove('active'));
                tabPanes.forEach(pane => {
                    pane.classList.remove('show', 'active');
                    pane.style.display = 'none';
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding pane with animation
                const targetId = this.getAttribute('href');
                const targetPane = document.querySelector(targetId);
                
                if (targetPane) {
                    targetPane.style.display = 'block';
                    targetPane.classList.add('show', 'active');
                    
                    // Add entrance animation
                    targetPane.style.animation = 'fadeInUp 0.4s ease-out';
                    
                    // Smooth scroll to tab content
                    setTimeout(() => {
                        targetPane.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 100);
                }
            });
        });
        
        // Initialize data-sheet tab as active by default
        const dataSheetTab = document.querySelector('.nav-tabs .nav-link[href="#data-sheet"]');
        const dataSheetPane = document.querySelector('#data-sheet');
        
        if (dataSheetTab && dataSheetPane) {
            // Remove active class from all tabs and panes
            tabLinks.forEach(tab => tab.classList.remove('active'));
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
                pane.style.display = 'none';
            });
            
            // Set data-sheet tab as active
            dataSheetTab.classList.add('active');
            dataSheetPane.style.display = 'block';
            dataSheetPane.classList.add('show', 'active');
        }
    }

</script>

<!-- Custom CSS for synchronized image sizes -->
<style>
    /* Đồng bộ kích thước hình ảnh chính */
    .main-product-image {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .main-product-image:hover {
        transform: scale(1.02);
    }

    .thumbnail-image {
        width: 75%;
        height: 100%;
        border-radius: 4px;
    }

    /* Enhanced Tab Styling */
    .nav-tabs {
        border: none;
        background: #f8f9fa;
        border-radius: 12px 12px 0 0;
        padding: 0;
        margin: 0;
        display: flex;
        overflow: hidden;
    }

    .nav-tabs .nav-item {
        flex: 1;
        margin: 0;
    }

    .nav-tabs .nav-link {
        border: none;
        background: transparent;
        color: #6c757d;
        font-weight: 500;
        font-size: 14px;
        padding: 20px 24px;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        border-radius: 0;
        margin: 0;
    }

    .nav-tabs .nav-link:hover {
        background: rgba(0, 123, 255, 0.05);
        color: #007bff;
        transform: translateY(-1px);
    }

    .nav-tabs .nav-link.active {
        background: #fff;
        color: #007bff;
        font-weight: 600;
        box-shadow: 0 -2px 0 #007bff;
        transform: translateY(-1px);
    }

    .nav-tabs .nav-link.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #0056b3);
        border-radius: 0 0 2px 2px;
    }

    .nav-tabs .nav-link i {
        font-size: 16px;
        transition: transform 0.3s ease;
    }

    .nav-tabs .nav-link:hover i {
        transform: scale(1.1);
    }

    .nav-tabs .nav-link.active i {
        transform: scale(1.1);
        color: #007bff;
    }

    /* Tab Content Styling */
    .tab-content {
        background: #fff;
        border-radius: 0 0 12px 12px;
        border: 1px solid #e9ecef;
        border-top: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .tab-pane {
        padding: 30px;
        animation: fadeInUp 0.4s ease-out;
    }

    .tab-pane.fade {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .tab-pane.fade.show {
        opacity: 1;
    }

    .tab-pane.fade.in {
        opacity: 1;
    }

    /* Animation for tab transitions */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Tab content body styling */
    .tab-content-body {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive tab styling */
    @media (max-width: 768px) {
        .nav-tabs {
            flex-direction: column;
            border-radius: 12px 12px 0 0;
        }

        .nav-tabs .nav-item {
            flex: none;
        }

        .nav-tabs .nav-link {
            padding: 16px 20px;
            font-size: 13px;
            border-radius: 0;
        }

        .nav-tabs .nav-link.active {
            box-shadow: 0 -2px 0 #007bff;
        }

        .tab-pane {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .nav-tabs .nav-link {
            padding: 14px 16px;
            font-size: 12px;
            gap: 6px;
        }

        .nav-tabs .nav-link i {
            font-size: 14px;
        }

        .tab-pane {
            padding: 16px;
        }
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





    /* Đồng bộ layout shop-item với trang sản phẩm */
    .shop-item {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
    }

    .shop-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }

    .shop-item-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .product-link {
        display: block;
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 1;
        text-decoration: none;
    }

    .product-link img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
        max-width: 100%;
        max-height: 100%;
        display: block;
    }

    .shop-item:hover .product-link img {
        transform: scale(1.05);
    }



    /* Đồng bộ phần content với trang sản phẩm - override bootstrap */
    .shop-item-content {
        padding: 15px !important;
    }

    .shop-item-title {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        margin-bottom: 8px !important;
        line-height: 1.3 !important;
        color: #2c3e50 !important;
        display: block !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .shop-item-title a {
        color: #2c3e50 !important;
        text-decoration: none !important;
        transition: color 0.3s ease !important;
        display: block !important;
        width: 100% !important;
    }

    .shop-item-title a:hover {
        color: #667eea !important;
    }

    .shop-item-price {
        margin-bottom: 10px !important;
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
    }

    /* Badge cho giá - đồng bộ với trang sản phẩm */
    .price-badge {
        color: #e74c3c !important;
        font-weight: 700 !important;
        font-size: 1.2rem !important;
        display: inline-block !important;
    }

    /* CSS cho action buttons - đồng bộ với trang sản phẩm */
    .action-buttons {
        display: flex !important;
        gap: 6px !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: nowrap !important;
        margin-top: 10px !important;
        width: 100% !important;
    }
    
    .action-buttons .btn {
        margin: 0 !important;
        font-size: 11px !important;
        padding: 6px 8px !important;
        border-radius: 6px !important;
        transition: all 0.3s ease !important;
        font-weight: 500 !important;
        height: 32px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        border: 1px solid transparent !important;
        flex: 1 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
        text-decoration: none !important;
    }
    
    .action-buttons .btn-success {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
    }
    
    .action-buttons .btn-success:hover {
        background-color: #218838 !important;
        border-color: #1e7e34 !important;
        color: white !important;
    }
    
    .action-buttons .btn-primary {
        background-color: #007bff !important;
        border-color: #007bff !important;
        color: white !important;
    }
    
    .action-buttons .btn-primary:hover {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
        color: white !important;
    }
    
    .action-buttons .btn-outline-danger {
        color: #dc3545 !important;
        border-color: #dc3545 !important;
        background-color: transparent !important;
    }
    
    .action-buttons .btn-outline-danger:hover {
        color: white !important;
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    
    .action-buttons .btn-danger {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
    }
    
    .action-buttons .btn-danger:hover {
        background-color: #c82333 !important;
        border-color: #bd2130 !important;
        color: white !important;
    }
    
    .action-buttons .btn i {
        margin-right: 4px !important;
        font-size: 11px !important;
    }
    


    /* Action buttons container styling */
    .action-buttons-container {
        display: flex !important;
        gap: 15px !important;
        align-items: flex-start !important;
        flex-wrap: wrap !important;
        margin-top: 25px !important;
        padding: 20px !important;
        background: #f8f9fa !important;
        border-radius: 12px !important;
        border: 1px solid #e9ecef !important;
    }

    .quantity-group {
        flex: 1 !important;
        min-width: 200px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .quantity-group label {
        font-weight: 600 !important;
        color: #333 !important;
        margin-bottom: 0 !important;
        white-space: nowrap !important;
    }

    .quantity-group .quantity-input-group {
        display: flex !important;
        align-items: center !important;
        border: 2px solid #e9ecef !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        background: white !important;
        height: 48px !important;
    }

    .quantity-group .quantity-input-group input {
        border: none !important;
        text-align: center !important;
        font-weight: 600 !important;
        padding: 10px 15px !important;
        min-width: 60px !important;
    }

    .quantity-group .quantity-input-group button {
        border: none !important;
        background: #f8f9fa !important;
        padding: 10px 15px !important;
        font-weight: bold !important;
        color: #495057 !important;
        transition: all 0.3s ease !important;
    }

    .quantity-group .quantity-input-group button:hover {
        background: #e9ecef !important;
        color: #212529 !important;
    }

    .add-to-cart-group {
        flex: 1 !important;
        min-width: 180px !important;
    }

    .buy-now-favorite-group {
        display: flex !important;
        gap: 10px !important;
        align-items: stretch !important;
        flex: 1 !important;
        min-width: 200px !important;
    }

    .buy-now-group {
        flex: 1 !important;
    }

    .favorite-group {
        flex: none !important;
    }

    /* Enhanced button styling for better balance */
    .add-to-cart-group .btn,
    .buy-now-group .btn {
        width: 100% !important;
        justify-content: center !important;
        padding: 15px 25px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        height: 48px !important;
        display: flex !important;
        align-items: center !important;
        box-sizing: border-box !important;
    }

    .add-to-cart-group .btn:hover,
    .buy-now-group .btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    }

    .add-to-cart-group .btn i,
    .buy-now-group .btn i {
        margin-right: 8px !important;
        font-size: 16px !important;
    }

    /* Favorite button styling */
    .favorite-group .btn {
        width: 48px !important;
        height: 48px !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
        font-size: 18px !important;
    }

    .favorite-group .btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3) !important;
    }

    .favorite-group .btn i {
        margin: 0 !important;
        font-size: 18px !important;
    }

    /* Specific styling for add to cart button */
    .btn-add-to-cart {
        margin-top: 16px !important;
        padding: 10px !important;
    }



    /* Mobile responsive for action buttons */
    @media (max-width: 768px) {
        .action-buttons-container {
            flex-direction: column !important;
            gap: 15px !important;
            padding: 15px !important;
        }

        .quantity-group,
        .add-to-cart-group,
        .buy-now-favorite-group {
            width: 100% !important;
            min-width: auto !important;
        }

        .buy-now-favorite-group {
            flex-direction: row !important;
            gap: 10px !important;
        }

        .quantity-group {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 8px !important;
        }

        .quantity-group label {
            text-align: center !important;
        }

        .quantity-group .quantity-input-group {
            height: 56px !important;
        }

        .add-to-cart-group .btn,
        .buy-now-group .btn {
            padding: 18px 30px !important;
            font-size: 16px !important;
            height: 56px !important;
        }

        .favorite-group .btn {
            width: 56px !important;
            height: 56px !important;
            font-size: 20px !important;
        }
    }



    /* No related products section styling */
    .py-5 {
        padding: 50px 0;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    /* Đồng bộ hiệu ứng loading và transitions */
    .shop-item-image img {
        transition: all 0.3s ease;
        backface-visibility: hidden;
    }

    .shop-item:hover .shop-item-image img {
        filter: brightness(1.1);
    }

    /* Đồng bộ spacing và alignment */
    .multi-columns-row {
        justify-content: flex-start;
        align-items: stretch;
    }

    /* Skeleton loading effect for images */
    .related-product-image {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    .related-product-image[src] {
        background: none;
        animation: none;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Enhanced hover states */
    .shop-item {
        cursor: pointer;
    }

    .shop-item:hover .shop-item-title a {
        color: #007bff;
        text-decoration: none;
    }

    /* Grid equalizer - đảm bảo tất cả items có cùng chiều cao */
    .multi-columns-row::after {
        content: '';
        flex: auto;
    }

    /* Tooltip for prices */
    .price-badge:hover::after {
        content: 'Giá có thể thay đổi theo phiên bản';
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
    }

    .price-badge {
        position: relative;
    }

    /* CSS cho hệ thống đánh giá - đơn giản */
    .rating-overview {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .rating-container {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }

    /* Product Favorite Button trong Chi tiết */
    .product-favorite-action {
        margin-top: 15px;
        display: inline-block;
    }

    .btn-favorite-detail {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border: 2px solid #e74c3c;
        background: white;
        color: #e74c3c;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    }

    .btn-favorite-detail:hover {
        background: #e74c3c;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .btn-favorite-detail.favorited {
        background: #e74c3c;
        color: white;
        border-color: #e74c3c;
    }

    .btn-favorite-detail.favorited:hover {
        background: #c0392b;
        border-color: #c0392b;
        color: white;
    }

    .btn-favorite-detail:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-favorite-detail i {
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .btn-favorite-detail:hover i {
        transform: scale(1.1);
    }

    .btn-favorite-detail.favorited i {
        animation: heartBeat 0.6s ease;
    }

    /* Product rating layout improvement */
    .product-rating {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .stars {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rating-summary {
        flex: 0 0 200px;
        text-align: center;
        padding: 10px;
    }

    .average-rating {
        margin-bottom: 10px;
    }

    .rating-number {
        font-size: 36px;
        font-weight: bold;
        color: #007bff;
    }

    .rating-total {
        font-size: 18px;
        color: #6c757d;
    }

    .rating-stars {
        font-size: 18px;
        margin-bottom: 5px;
    }

    .rating-count {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
    }

    /* Rating Breakdown - đơn giản */
    .rating-breakdown {
        flex: 1;
        padding: 10px 0;
    }

    .rating-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .star-count {
        width: 50px;
        color: #495057;
        font-weight: 500;
    }

    .progress-container {
        flex: 1;
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        margin: 0 10px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        height: 100%;
        background: #ffc107;
        border-radius: 3px;
        transition: width 0.3s ease;
        position: absolute;
        top: 0;
        left: 0;
    }

    .star-count-number {
        width: 35px;
        text-align: right;
        color: #6c757d;
        font-size: 12px;
    }



    /* Rating Input */
    .rating-input {
        display: flex;
        gap: 5px;
        margin: 10px 0;
    }

    .rating-star {
        font-size: 24px;
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rating-star:hover,
    .rating-star.active {
        color: #ffc107;
        transform: scale(1.1);
    }

    .rating-star:hover~.rating-star {
        color: #ddd;
    }

    /* Review Items - đơn giản */
    .review-item {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
        margin-bottom: 15px;
        display: flex;
        gap: 15px;
    }

    .review-avatar {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
    }

    .review-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
    }

    .review-content {
        flex: 1;
    }



    /* No description styling */
    .no-description {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .no-description i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #dee2e6;
    }

    .no-description p {
        font-size: 16px;
        margin: 0;
    }

    /* No specs styling */
    .no-specs {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .no-specs i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #dee2e6;
    }

    .no-specs p {
        font-size: 16px;
        margin: 0;
    }

    .review-header {
        margin-bottom: 8px;
    }

    .review-author {
        margin: 0 0 5px 0;
        font-size: 16px;
        color: #333;
        font-weight: 600;
    }

    .review-rating {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .review-date {
        color: #6c757d;
        font-size: 12px;
    }

    .review-text {
        color: #495057;
        line-height: 1.5;
        margin-bottom: 8px;
    }

    .review-badge {
        background: #28a745;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .review-status-badge {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        margin-left: 5px;
    }

    .review-pending {
        background: #ffc107;
        color: #212529;
    }

    .review-approved {
        background: #28a745;
        color: white;
    }

    .review-date-badge {
        color: #6c757d;
        font-size: 10px;
        margin-left: 5px;
    }

    /* Review Form */
    .review-form {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .review-form-title {
        color: #333;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    /* User Review Notice */
    .user-review-notice .alert {
        border-radius: 4px;
        border: none;
        background: #d1ecf1;
        color: #0c5460;
        padding: 10px 15px;
        margin-bottom: 20px;
    }

    /* No Reviews */
    .no-reviews {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 40px 20px;
        border: 2px dashed #dee2e6;
    }

    /* Rating text styling */
    .rating-text {
        margin-left: 10px;
        color: #6c757d;
        font-size: 14px;
    }

    .rating-text a {
        color: #007bff;
        text-decoration: none;
    }

    .rating-text a:hover {
        text-decoration: underline;
    }

    /* Buyers count styling */
    .buyers-count {
        margin-left: 15px;
        color: #28a745;
        font-size: 14px;
        font-weight: 500;
    }

    .buyers-count i {
        margin-right: 5px;
        color: #28a745;
    }

    /* Responsive cho đánh giá */
    @media (max-width: 768px) {
        .rating-overview {
            padding: 15px;
        }

        .rating-container {
            flex-direction: column;
            gap: 20px;
        }

        .rating-summary {
            flex: none;
            text-align: center;
            margin-bottom: 0;
        }

        .rating-number {
            font-size: 32px;
        }

        .rating-total {
            font-size: 16px;
        }

        .rating-breakdown {
            padding: 0;
        }

        .review-item {
            flex-direction: column;
            gap: 10px;
        }

        .review-avatar {
            width: 40px;
            height: 40px;
        }

        .review-avatar img,
        .avatar-placeholder {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .review-form {
            padding: 20px 15px;
        }

        .rating-star {
            font-size: 20px;
        }
    }

    /* Product stock info styling */
    .product-stock-info {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        margin-top: 10px;
    }

    .product-stock-info label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
        display: block;
    }

    #product-stock-display {
        font-size: 14px;
        font-weight: 500;
        padding: 8px 0;
    }

    #product-stock-display i {
        margin-right: 8px;
    }

    /* Responsive cho product stock */
    @media (max-width: 768px) {
        .product-stock-info {
            padding: 12px;
            margin-top: 8px;
        }

        #product-stock-display {
            font-size: 13px;
        }
    }

    /* css form bình luận */

    .comment-section {
        width: 100%;

        margin: 0;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #f3f4f6;
        border-radius: 10px;
        font-family: Arial, sans-serif;
    }

    .comment-section h2 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-input-wrapper {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .form-input-wrapper textarea {
        flex: 1;
        height: 50px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        resize: vertical;
    }

    .form-input-wrapper button {
        padding: 10px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        height: 50px;
    }

    .form-input-wrapper button:hover {
        background-color: #0056b3;
    }

    .comment-item {
        background-color: #ffffff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #555;
        margin-bottom: 6px;
    }

    .comment-content {
        font-size: 16px;
        color: #333;
        padding-left: 10px;
    }

    .old-price {
        text-decoration: line-through;
        color: #888;
        font-size: 16px;
        margin-left: 8px;
    }

    .promotion-price {
        color: #e74c3c;
        font-weight: bold;
        font-size: 20px;
    }

    /* Animation for price updates */
    @keyframes priceUpdate {
        0% {
            transform: scale(1);
            background: rgba(52, 152, 219, 0.2);
        }
        50% {
            transform: scale(1.05);
            background: rgba(52, 152, 219, 0.4);
        }
        100% {
            transform: scale(1);
            background: transparent;
        }
    }

    .product-price {
        transition: all 0.3s ease;
    }

    .product-price.updated {
        animation: priceUpdate 0.5s ease-in-out;
    }
</style>


<script>
    // Global variables
    let currentStock = @if($product->variants->count() == 0) {{ $product->stock_quantity ?? 0 }} @else 0 @endif;
    let currentCartQuantity = 0;
    let availableToAdd = @if($product->variants->count() == 0) {{ $product->stock_quantity ?? 0 }} @else 0 @endif;
    let isLoadingStock = false;

    // Image zoom variables
    let scale = 1;
    let translateX = 0;
    let translateY = 0;
    let isDragging = false;
    let startX, startY;

    // Khởi tạo thông tin stock cho sản phẩm không có variant
    @if($product->variants->count() == 0)
        // Gọi API để lấy thông tin stock real-time cho sản phẩm
        fetch(`{{ route('client.variant-stock') }}?product_id={{ $product->id }}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentStock = data.current_stock;
                    currentCartQuantity = data.cart_quantity;
                    availableToAdd = data.available_to_add;
                    updateStockDisplay(data);
                }
            })
            .catch(error => {
                console.error('Error fetching product stock:', error);
            });
    @endif

    // Thêm event listener cho variant select
    document.addEventListener('DOMContentLoaded', function() {
        const variantSelect = document.getElementById('variant-select');
        if (variantSelect) {
            console.log('Variant select found, adding event listener');
            variantSelect.addEventListener('change', function() {
                console.log('Variant select changed');
                updatePriceAndStock(this);
            });
        } else {
            console.log('Variant select not found');
        }
    });



    function updateQuantityConstraints() {
        const quantityInput = document.getElementById('quantity-input');
        @if($product->variants->count() == 0)
            quantityInput.max = Math.min(currentStock, 100);
            if (currentStock > 0) {
                quantityInput.disabled = false;
                if (parseInt(quantityInput.value) > currentStock) {
                    quantityInput.value = currentStock;
                }
            } else {
                quantityInput.disabled = true;
                quantityInput.value = 0;
            }
        @else
            if (availableToAdd > 0) {
                quantityInput.max = Math.min(availableToAdd, 100);
                quantityInput.disabled = false;
                if (parseInt(quantityInput.value) > availableToAdd) {
                    quantityInput.value = availableToAdd;
                }
            } else {
                quantityInput.max = 0;
                quantityInput.value = 0;
                quantityInput.disabled = true;
            }
        @endif
    }


            } else {
                showQuantityError('Sản phẩm đã hết hàng');
            }
            return false;
        }

            const quantityInput = document.getElementById('quantity-input');
            const quantityError = document.getElementById('quantity-error');
            const quantity = parseInt(quantityInput.value) || 0;

            quantityError.style.display = 'none';
            quantityInput.style.borderColor = '';

            @if($product->variants->count() == 0)
                let maxStock = {{ $product->stock_quantity ?? 0 }};
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
            @else
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
            @endif

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
            quantityInput.disabled = false;
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
    function showToast(message, type = 'success', title = null) {
        console.log('=== SHOWING TOAST ===');
        console.log('Message:', message);
        console.log('Type:', type);
        console.log('Title:', title);

        // Xác định icon và title dựa trên type
        let icon, toastTitle;
        switch(type) {
            case 'success':
                icon = 'fa-check-circle';
                toastTitle = title || 'Thành công!';
                break;
            case 'error':
                icon = 'fa-exclamation-triangle';
                toastTitle = title || 'Lỗi!';
                break;
            case 'warning':
                icon = 'fa-exclamation-circle';
                toastTitle = title || 'Cảnh báo!';
                break;
            case 'info':
                icon = 'fa-info-circle';
                toastTitle = title || 'Thông báo!';
                break;
            default:
                icon = 'fa-info-circle';
                toastTitle = title || 'Thông báo!';
        }

        // Đảm bảo toast container tồn tại
        if ($('#toast-container').length === 0) {
            $('body').append('<div id="toast-container"></div>');
        }

        const toast = $(`
            <div class="toast ${type}" style="display: none;">
                <div style="display: flex; align-items: center;">
                    <i class="fa ${icon}" style="font-size: 24px; margin-right: 15px;"></i>
                    <div style="flex: 1;">
                        <strong>${toastTitle}</strong><br>
                        ${message}
                    </div>
                    <button type="button" class="close" onclick="$(this).closest('.toast').fadeOut()">
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

    // Demo toast function để test
    function demoToast() {
        showToast('Đây là thông báo thành công!', 'success', 'Thành công');
        setTimeout(() => {
            showToast('Đây là thông báo lỗi!', 'error', 'Lỗi');
        }, 1000);
        setTimeout(() => {
            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
        }, 2000);
        setTimeout(() => {
            showToast('Đây là thông báo thông tin!', 'info', 'Thông tin');
        }, 3000);
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
                },
                401: function(xhr) {
                    // Handle authentication errors
                    console.log('=== HTTP 401 - Authentication Required ===');
                    console.log('Response:', xhr.responseJSON);

                    if (xhr.responseJSON && xhr.responseJSON.redirect_to_login && xhr.responseJSON
                        .login_url) {
                        showToast(xhr.responseJSON.message || 'Vui lòng đăng nhập để tiếp tục!', 'info');

                    if (status === 'timeout') {
                        errorMessage = 'Request timeout! Vui lòng thử lại.';
                    } else if (xhr.status === 401) {
                        // Authentication required - already handled in statusCode, but adding fallback
                        if (xhr.responseJSON && xhr.responseJSON.redirect_to_login && xhr.responseJSON
                            .login_url) {
                            showToast(xhr.responseJSON.message || 'Vui lòng đăng nhập để tiếp tục!', 'info');
                            setTimeout(function() {
                                window.location.href = xhr.responseJSON.login_url;
                            }, 1000);
                            return; // Don't show error toast
                        } else {
                            errorMessage = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!';
                        }
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
                    url: "{{ route('client.cart-count') }}",
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
            console.log('🔍 Single product page loaded');
            
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
                const $variantSelect = $form.find('select[name="variant_id"]');
                if ($variantSelect.length && !$variantSelect.val()) {
                    showToast('Vui lòng chọn phiên bản sản phẩm!', 'error');
                    return;
                }

                // Kiểm tra số lượng hợp lệ
                if (!validateQuantity()) {
                    return;
                }

                // GỌI AJAX THÊM VÀO GIỎ HÀNG
                submitAddToCart($form, $submitBtn, originalText);
            });

            // Xử lý click vào link đánh giá để cuộn xuống tab reviews
            $('.review-link').on('click', function(e) {
                e.preventDefault();

                // Kích hoạt tab reviews
                $('a[href="#reviews"]').tab('show');

                // Smooth scroll đến phần tab
                $('html, body').animate({
                    scrollTop: $('#reviews').offset().top - 100
                }, 800);
            });

            // Debug helper - click anywhere on page to test route generation
            $(document).on('dblclick', function() {
                console.log('=== DEBUG INFO ===');
                console.log('Cart route:', "{{ route('client.cart') }}");
                console.log('Add to cart route:', "{{ route('client.add-to-cart') }}");
                console.log('Current URL:', window.location.href);
                console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
                console.log('Form action:', $('#add-to-cart-form').attr('action'));

                // Test route directly
                fetch("{{ route('client.cart') }}")
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


            success: function(response) {
                console.log('=== AJAX SUCCESS ===');
                console.log('Full response:', response);
                console.log('Response type:', typeof response);
                console.log('Response success field:', response.success);

                if (response.success === true) {
                    console.log('Success! Showing success message and updating cart count');

                    // Show success toast
                    showToast(response.message || 'Đã thêm sản phẩm vào giỏ hàng!', 'success');

                    // Update cart count if available
                    if (response.cart_count !== undefined) {
                        if (window.updateCartCount) {
                            window.updateCartCount(response.cart_count);
                        }
                        
                        // Update local cart count display
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = response.cart_count;
                            cartCountElement.classList.add('updated');
                            setTimeout(() => {
                                cartCountElement.classList.remove('updated');
                            }, 600);
                        }
                    }

                    // Update stock info if provided
                    if (response.stock_info) {
                        availableToAdd = response.stock_info.available_to_add;
                        currentCartQuantity = response.stock_info.cart_quantity;
                        currentStock = response.stock_info.current_stock;
                        updateStockDisplay();
                        updateQuantityConstraints();
                    }

                    // Reset quantity to 1
                    const quantityInput = document.getElementById('quantity-input');
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }

                    // KHÔNG REDIRECT - chỉ hiển thị thông báo và cập nhật UI

                } else if (response.success === false) {
                    // Check if this is a login redirect response
                    if (response.redirect_to_login === true && response.login_url) {
                        console.log('Login required, redirecting to login page...');
                        showToast(response.message || 'Vui lòng đăng nhập để tiếp tục!', 'info');

                        // Redirect to login page after 1 second
                        setTimeout(function() {
                            window.location.href = response.login_url;
                        }, 1000);
                        return;
                    }

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

                    // Hiển thị toast notification với thông tin chi tiết
                    if (response.toast_type && response.toast_title) {
                        Toast.show(response.toast_type, response.toast_title, response.message);
                    } else {
                        showToast(response.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
                    }
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
                } else if (xhr.status === 401) {
                    // Authentication required - already handled in statusCode, but adding fallback
                    if (xhr.responseJSON && xhr.responseJSON.redirect_to_login && xhr.responseJSON
                        .login_url) {
                        showToast(xhr.responseJSON.message || 'Vui lòng đăng nhập để tiếp tục!', 'info');
                        setTimeout(function() {
                            window.location.href = xhr.responseJSON.login_url;
                        }, 1000);
                        return; // Don't show error toast
                    } else {
                        errorMessage = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!';
                    }
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
                url: "{{ route('client.cart-count') }}",
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
        console.log('🔍 Single product page loaded');

        // CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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

            // Kiểm tra số lượng hợp lệ
            if (!validateQuantity()) {
                return;
            }

            // GỌI AJAX THÊM VÀO GIỎ HÀNG
            submitAddToCart($form, $submitBtn, originalText);
        });

        // Xử lý click vào link đánh giá để cuộn xuống tab reviews
        $('.review-link').on('click', function(e) {
            e.preventDefault();

            // Kích hoạt tab reviews
            $('a[href="#reviews"]').tab('show');

            // Smooth scroll đến phần tab
            $('html, body').animate({
                scrollTop: $('#reviews').offset().top - 100
            }, 800);
        });

        // Debug helper - click anywhere on page to test route generation
        $(document).on('dblclick', function() {
            console.log('=== DEBUG INFO ===');
            console.log('Cart route:', "{{ route('client.cart') }}");
            console.log('Add to cart route:', "{{ route('client.add-to-cart') }}");
            console.log('Current URL:', window.location.href);
            console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
            console.log('Form action:', $('#add-to-cart-form').attr('action'));

            // Test route directly
            fetch("{{ route('client.cart') }}")
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



        // Review form submission
        $('#review-form').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $submitBtn = $('#submit-review-btn');
            const originalText = $submitBtn.html();

            // Validate rating
            const rating = $('#selected-rating').val();
            if (!rating || rating < 1 || rating > 5) {
                showToast('Vui lòng chọn số sao đánh giá!', 'error');
                return;
            }

            // Validate content
            const content = $('#review-content').val().trim();
            if (!content) {
                showToast('Vui lòng nhập nội dung đánh giá!', 'error');
                return;
            }

            if (content.length > 1000) {
                showToast('Nội dung đánh giá không được quá 1000 ký tự!', 'error');
                return;
            }

            // Disable submit button
            $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang gửi...');

            // Submit via AJAX with FormData for file upload
            const formData = new FormData($form[0]);

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Review submission response:', response);

                    if (response.success) {
                        showToast(response.message || 'Đánh giá đã được thêm thành công!',
                            'success');

                        // Redirect after success
                        setTimeout(function() {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.reload();
                            }
                        }, 1500);
                    } else {
                        showToast(response.message || 'Có lỗi xảy ra khi thêm đánh giá!',
                            'error');
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Review submission error:', xhr.responseJSON);

                    let errorMessage = 'Có lỗi xảy ra khi thêm đánh giá!';

                    if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON
                        .redirect_to_login) {
                        errorMessage = xhr.responseJSON.message ||
                            'Vui lòng đăng nhập để thêm đánh giá!';
                        showToast(errorMessage, 'info');

                        setTimeout(function() {
                            window.location.href = xhr.responseJSON.login_url ||
                                '/login';
                        }, 1500);
                        return;
                    } else if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON
                        .message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON
                        .errors) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = [];
                        for (let field in errors) {
                            errorMessages.push(errors[field][0]);
                        }
                        errorMessage = errorMessages.join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    showToast(errorMessage, 'error');
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Character counter for review content
        $('#review-content').on('input', function() {
            const maxLength = 1000;
            const currentLength = $(this).val().length;
            const remaining = maxLength - currentLength;

            // Find or create character counter
            let $counter = $(this).siblings('.char-counter');
            if ($counter.length === 0) {
                $counter = $('<small class="char-counter text-muted"></small>');
                $(this).after($counter);
            }

            if (remaining < 0) {
                $counter.text(`Quá ${Math.abs(remaining)} ký tự`).removeClass('text-muted').addClass(
                    'text-danger');
                $(this).addClass('is-invalid');
            } else if (remaining < 100) {
                $counter.text(`Còn ${remaining} ký tự`).removeClass('text-danger').addClass(
                    'text-warning');
                $(this).removeClass('is-invalid');
            } else {
                $counter.text(`${currentLength}/${maxLength} ký tự`).removeClass(
                    'text-danger text-warning').addClass('text-muted');
                $(this).removeClass('is-invalid');
            }
        });



        // Image zoom overlay với zoom và pan

        // Phóng to ảnh sản phẩm khi click (dùng overlay riêng)
        console.log('Setting up image zoom handlers...');
        $('.main-product-image, .gallery-thumbnail').css('cursor', 'pointer').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var src = $(this).attr('src');
            console.log('Zooming image:', src); // Debug log
            $('#zoomed-image').attr('src', src);
            $('#image-zoom-overlay').addClass('active').fadeIn(100);
            resetZoom(); // Reset zoom khi mở
        });

        // Zoom bằng scroll wheel
        $('#zoomed-image').on('wheel', function(e) {
            e.preventDefault();
            const delta = e.originalEvent.deltaY > 0 ? -0.1 : 0.1;
            zoomImage(delta);
        });

        // Drag để di chuyển ảnh
        $('#zoomed-image').on('mousedown', function(e) {
            if (scale > 1) {
                isDragging = true;
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
                $(this).addClass('dragging');
                e.preventDefault();
            }
        });

        $(document).on('mousemove', function(e) {
            if (isDragging) {
                translateX = e.clientX - startX;
                translateY = e.clientY - startY;
                updateTransform();
            }
        });

        $(document).on('mouseup', function() {
            isDragging = false;
            $('#zoomed-image').removeClass('dragging');
        });

        // Double click để zoom in/out nhanh
        $('#zoomed-image').on('dblclick', function(e) {
            e.stopPropagation();
            if (scale === 1) {
                scale = 2;
            } else {
                resetZoom();
            }
            updateTransform();
        });

        // Đóng overlay khi click ra ngoài
        $('#image-zoom-overlay').on('click', function(e) {
            if (e.target === this) {
                $(this).removeClass('active').fadeOut(100);
                $('#zoomed-image').attr('src', '');
                resetZoom();
            }
        });

        // Đóng bằng phím ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#image-zoom-overlay').removeClass('active').fadeOut(100);
                $('#zoomed-image').attr('src', '');
                resetZoom();
            }
        });

        // Global functions cho zoom controls
        window.zoomImage = function(delta) {
            scale = Math.max(0.5, Math.min(5, scale + delta));
            updateTransform();
        };

        window.resetZoom = function() {
            scale = 1;
            translateX = 0;
            translateY = 0;
            updateTransform();
        };

        function updateTransform() {
            $('#zoomed-image').css('transform', `translate(${translateX}px, ${translateY}px) scale(${scale})`);
            $('#zoom-level').text(Math.round(scale * 100) + '%');
        }
    });



    // Test FontAwesome icons và favorite functionality
    $(document).ready(function() {
        console.log('🔍 Testing FontAwesome icons on single product page...');

        // Test critical icons
        const testIcons = ['fas fa-heart', 'far fa-heart', 'fas fa-star', 'fas fa-shopping-cart'];
        let allWorking = true;

        testIcons.forEach(iconClass => {
            const testEl = document.createElement('i');
            testEl.className = iconClass;
            testEl.style.cssText = 'position: absolute; top: -9999px; left: -9999px;';
            document.body.appendChild(testEl);

            const style = window.getComputedStyle(testEl, '::before');
            const hasContent = style.content && style.content !== 'none' && style.content !== '""';

            if (!hasContent) {
                console.error(`❌ Icon ${iconClass} not working!`);
                allWorking = false;
            } else {
                console.log(`✅ Icon ${iconClass} working`);
            }

            document.body.removeChild(testEl);
        });

        if (!allWorking) {
            console.warn('⚠️ Some icons not working - adding fallbacks');

            // Add fallbacks for broken icons
            setTimeout(() => {
                $('.btn-favorite-detail i, .btn-favorite i, .btn-favorite-small i').each(function() {
                    const $icon = $(this);
                    const style = window.getComputedStyle(this, '::before');
                    const hasContent = style.content && style.content !== 'none' && style.content !== '""';

                    if (!hasContent) {
                        const $btn = $icon.closest('button, a');
                        if ($btn.length) {
                            const isFavorited = $btn.hasClass('favorited');
                            $icon.text(isFavorited ? '♥' : '♡');
                            $icon.css({
                                'font-family': 'inherit',
                                'font-size': '16px'
                            });
                            console.log('Added fallback icon to button:', $btn[0]);
                        }
                    }
                });
            }, 1000);
        }

        // Test favorite manager
        if (window.favoriteManager) {
            console.log('✅ Favorite manager loaded');

            // Test that favorite buttons are properly set up
            const favoriteButtons = $('.btn-favorite-detail, .btn-favorite, .btn-favorite-small');
            console.log(`Found ${favoriteButtons.length} favorite buttons on page`);

            favoriteButtons.each(function(index) {
                const $btn = $(this);
                const productId = $btn.data('product-id');
                const hasIcon = $btn.find('i').length > 0;
                const hasProductId = productId ? true : false;

                console.log(`Button ${index + 1}:`, {
                    productId: productId,
                    hasIcon: hasIcon,
                    hasProductId: hasProductId,
                    classes: $btn.attr('class'),
                    iconClasses: $btn.find('i').attr('class')
                });

                if (!hasProductId) {
                    console.warn(`⚠️ Button ${index + 1} missing product-id:`, $btn[0]);
                }
                if (!hasIcon) {
                    console.warn(`⚠️ Button ${index + 1} missing icon:`, $btn[0]);
                }
            });
        } else {
            console.error('❌ Favorite manager not found!');
        }

        // Final check - make sure FontAwesome CSS is loaded
        const faLoaded = Array.from(document.styleSheets).some(sheet => {
            try {
                return sheet.href && sheet.href.includes('font-awesome');
            } catch (e) {
                return false;
            }
        });

        console.log(faLoaded ? '✅ FontAwesome CSS loaded' : '❌ FontAwesome CSS not found');

        // Show summary
        setTimeout(() => {
            const workingButtons = $('.btn-favorite-detail, .btn-favorite, .btn-favorite-small').filter(function() {
                const $icon = $(this).find('i');
                if ($icon.length === 0) return false;

                const style = window.getComputedStyle($icon[0], '::before');
                return style.content && style.content !== 'none' && style.content !== '""';
            });

            console.log(`📊 Summary: ${workingButtons.length}/${$('.btn-favorite-detail, .btn-favorite, .btn-favorite-small').length} buttons have working icons`);
        }, 2000);
    });
} // Close initProductScript function
</script>

<!-- Image Zoom Overlay -->
<div id="image-zoom-overlay" style="display:none;">
    <img id="zoomed-image" src="" alt="Zoomed image" />
    <div class="zoom-controls"
        style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:14px;display:flex;align-items:center;gap:15px;">
        <button onclick="zoomImage(-0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">-</button>
        <span id="zoom-level">100%</span>
        <button onclick="zoomImage(0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">+</button>
        <button onclick="resetZoom()"
            style="background:none;border:none;color:white;font-size:12px;cursor:pointer;padding:5px 10px;border-left:1px solid #555;margin-left:10px;">Reset</button>
    </div>
    <div class="zoom-hint"
        style="position:absolute;top:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:13px;">
        <i class="fas fa-mouse-pointer"></i> Kéo để di chuyển • <i class="fas fa-search-plus"></i> Scroll để zoom •
        Double click để zoom nhanh
    </div>
</div>

<!-- Image Zoom Overlay (đặt cuối file, ngoài mọi section) -->
<div id="image-zoom-overlay" style="display:none; position:fixed; z-index:99999; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); justify-content:center; align-items:center;">
    <img id="zoomed-image" src="" alt="Zoomed image" style="max-width:90vw; max-height:90vh; border-radius:10px; box-shadow:0 8px 40px rgba(0,0,0,0.5); background:#fff; display:block; margin:auto;" />
    <div class="zoom-controls"
        style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:14px;display:flex;align-items:center;gap:15px;">
        <button onclick="zoomImage(-0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">-</button>
        <span id="zoom-level">100%</span>
        <button onclick="zoomImage(0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">+</button>
        <button onclick="resetZoom()"
            style="background:none;border:none;color:white;font-size:12px;cursor:pointer;padding:5px 10px;border-left:1px solid #555;margin-left:10px;">Reset</button>
    </div>
    <div class="zoom-hint"
        style="position:absolute;top:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:13px;">
        <i class="fas fa-mouse-pointer"></i> Kéo để di chuyển • <i class="fas fa-search-plus"></i> Scroll để zoom •
        Double click để zoom nhanh
    </div>
</div>

<style>
    #image-zoom-overlay {
        display: none;
        position: fixed;
        z-index: 99999;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        justify-content: center;
        align-items: center;
    }

    #image-zoom-overlay.active {
        display: flex !important;
    }

    #zoomed-image {
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 10px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
        background: #fff;
    }
</style>

<script>
    // Biến zoom toàn cục
    let scale = 1,
        translateX = 0,
        translateY = 0,
        isDragging = false,
        startX, startY;

    // Đảm bảo không bị chồng sự kiện
    $(document).off('click.zoomImage').on('click.zoomImage', '.main-product-image, .gallery-thumbnail', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var src = $(this).attr('src');
        console.log('[ZOOM] Clicked image:', src);
        $('#zoomed-image').attr('src', src);
        $('#image-zoom-overlay').addClass('active').fadeIn(100);
        resetZoom();
    });

    // Zoom bằng scroll wheel
    $('#zoomed-image').off('wheel').on('wheel', function(e) {
        e.preventDefault();
        const delta = e.originalEvent.deltaY > 0 ? -0.1 : 0.1;
        zoomImage(delta);
    });

    // Drag để di chuyển ảnh
    $('#zoomed-image').off('mousedown').on('mousedown', function(e) {
        if (scale > 1) {
            isDragging = true;
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
            $(this).addClass('dragging');
            e.preventDefault();
        }
    });

    $(document).off('mousemove.zoomImage').on('mousemove.zoomImage', function(e) {
        if (isDragging) {
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            updateTransform();
        }
    });

    $(document).off('mouseup.zoomImage').on('mouseup.zoomImage', function() {
        isDragging = false;
        $('#zoomed-image').removeClass('dragging');
    });

    // Double click để zoom in/out nhanh
    $('#zoomed-image').off('dblclick').on('dblclick', function(e) {
        e.stopPropagation();
        if (scale === 1) {
            scale = 2;
        } else {
            resetZoom();
        }
        updateTransform();
    });

    // Đóng overlay khi click ra ngoài
    $('#image-zoom-overlay').off('click').on('click', function(e) {
        if (e.target === this) {
            $(this).removeClass('active').fadeOut(100);
            $('#zoomed-image').attr('src', '');
            resetZoom();
        }
    });

    // Đóng bằng phím ESC
    $(document).off('keydown.zoomImage').on('keydown.zoomImage', function(e) {
        if (e.key === 'Escape') {
            $('#image-zoom-overlay').removeClass('active').fadeOut(100);
            $('#zoomed-image').attr('src', '');
            resetZoom();
        }
    });

    // Hàm zoom
    window.zoomImage = function(delta) {
        scale = Math.max(0.5, Math.min(5, scale + delta));
        updateTransform();
    };
    window.resetZoom = function() {
        scale = 1;
        translateX = 0;
        translateY = 0;
        updateTransform();
    };

    function updateTransform() {
        $('#zoomed-image').css('transform', `translate(${translateX}px, ${translateY}px) scale(${scale})`);
        $('#zoom-level').text(Math.round(scale * 100) + '%');
    }
</script>

<script>
    // Favorite routes for the page
    window.favoriteRoutes = {
        add: "{{ route('client.favorite.add') }}",
        remove: "{{ route('client.favorite.remove') }}",
        toggle: "{{ route('client.favorite.toggle') }}"
    };

    // Debug favorite button initialization
    $(document).ready(function() {
        console.log('🔍 Checking favorite buttons on product detail page...');
        const favoriteButtons = $('.add-favorite, .remove-favorite');
        console.log(`Found ${favoriteButtons.length} favorite buttons:`, favoriteButtons);
        
        favoriteButtons.each(function(index) {
            const $btn = $(this);
            console.log(`Button ${index + 1}:`, {
                productId: $btn.data('product-id'),
                classes: $btn.attr('class'),
                text: $btn.text().trim()
            });
        });
    });
    </script>

    <!-- Image Modal with Zoom -->
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        
        <!-- Zoom Controls -->
        <div class="zoom-controls">
            <button class="zoom-btn-modal" onclick="zoomIn()" title="Phóng to">
                <i class="fas fa-search-plus"></i>
            </button>
            <button class="zoom-btn-modal" onclick="zoomOut()" title="Thu nhỏ">
                <i class="fas fa-search-minus"></i>
            </button>
            <button class="zoom-btn-modal" onclick="resetZoom()" title="Khôi phục">
                <i class="fas fa-undo"></i>
            </button>
            <button class="zoom-btn-modal" onclick="rotateImage()" title="Xoay ảnh">
                <i class="fas fa-redo"></i>
            </button>
        </div>
        
        <!-- Zoom Level Indicator -->
        <div class="zoom-level">
            <span id="zoomLevel">100%</span>
        </div>
        
        <div class="image-modal-content">
            <div class="image-container">
                <img id="modalImage" src="" alt="Product Image">
            </div>
        </div>
    </div>

    <script>
        // Product Image Slider
        let currentImageIndex = 0;
        let totalImages = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Count total images
            totalImages = document.querySelectorAll('.product-main-image').length;
            
            // Auto-play slider
            setInterval(function() {
                if (totalImages > 1) {
                    changeImage(1);
                }
            }, 5000);
        });

        function changeImage(direction) {
            const images = document.querySelectorAll('.product-main-image');
            const thumbnails = document.querySelectorAll('.thumbnail-container');
            
            if (images.length === 0) return;
            
            // Remove active class from current image and thumbnail
            images[currentImageIndex].classList.remove('active');
            thumbnails[currentImageIndex].classList.remove('active');
            
            // Calculate new index
            currentImageIndex += direction;
            
            // Handle loop
            if (currentImageIndex >= totalImages) {
                currentImageIndex = 0;
            } else if (currentImageIndex < 0) {
                currentImageIndex = totalImages - 1;
            }
            
            // Add active class to new image and thumbnail
            images[currentImageIndex].classList.add('active');
            thumbnails[currentImageIndex].classList.add('active');
        }

        function showImage(index) {
            const images = document.querySelectorAll('.product-main-image');
            const thumbnails = document.querySelectorAll('.thumbnail-container');
            
            if (index >= 0 && index < images.length) {
                // Remove active class from current image and thumbnail
                images[currentImageIndex].classList.remove('active');
                thumbnails[currentImageIndex].classList.remove('active');
                
                // Update current index
                currentImageIndex = index;
                
                // Add active class to new image and thumbnail
                images[currentImageIndex].classList.add('active');
                thumbnails[currentImageIndex].classList.add('active');
            }
        }

        // Image modal and zoom functions
        let currentScale = 1;
        let currentRotation = 0;
        let isDragging = false;
        let startX = 0;
        let startY = 0;
        let translateX = 0;
        let translateY = 0;

        function openImageModal() {
            const activeImage = document.querySelector('.product-main-image.active');
            if (!activeImage) return;
            
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modalImg.src = activeImage.src;
            modalImg.alt = activeImage.alt;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Reset zoom and position
            resetZoom();
            
            // Add event listeners for dragging
            modalImg.addEventListener('mousedown', startDragging);
            modalImg.addEventListener('touchstart', startDragging);
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Remove event listeners
            const modalImg = document.getElementById('modalImage');
            modalImg.removeEventListener('mousedown', startDragging);
            modalImg.removeEventListener('touchstart', startDragging);
        }

        function startDragging(e) {
            if (currentScale <= 1) return;
            
            isDragging = true;
            const touch = e.touches ? e.touches[0] : e;
            startX = touch.clientX - translateX;
            startY = touch.clientY - translateY;
            
            e.preventDefault();
        }

        function drag(e) {
            if (!isDragging) return;
            
            const touch = e.touches ? e.touches[0] : e;
            translateX = touch.clientX - startX;
            translateY = touch.clientY - startY;
            
            updateImageTransform();
            e.preventDefault();
        }

        function stopDragging() {
            isDragging = false;
        }

        function updateImageTransform() {
            const modalImg = document.getElementById('modalImage');
            modalImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentScale}) rotate(${currentRotation}deg)`;
        }

        function zoomIn() {
            if (currentScale < 5) {
                currentScale += 0.5;
                updateZoomLevel();
                updateImageTransform();
            }
        }

        function zoomOut() {
            if (currentScale > 0.5) {
                currentScale -= 0.5;
                updateZoomLevel();
                updateImageTransform();
            }
        }

        function resetZoom() {
            currentScale = 1;
            currentRotation = 0;
            translateX = 0;
            translateY = 0;
            updateZoomLevel();
            updateImageTransform();
        }

        function rotateImage() {
            currentRotation += 90;
            if (currentRotation >= 360) {
                currentRotation = 0;
            }
            updateImageTransform();
        }

        function updateZoomLevel() {
            const zoomLevel = document.getElementById('zoomLevel');
            zoomLevel.textContent = Math.round(currentScale * 100) + '%';
        }

        // Mouse wheel zoom
        document.addEventListener('wheel', function(e) {
            if (document.getElementById('imageModal').style.display === 'block') {
                e.preventDefault();
                if (e.deltaY < 0) {
                    zoomIn();
                } else {
                    zoomOut();
                }
            }
        });

        // Touch events for mobile
        document.addEventListener('touchmove', drag);
        document.addEventListener('touchend', stopDragging);
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', stopDragging);

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                closeImageModal();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

        // Keyboard navigation for slider and zoom
        document.addEventListener('keydown', function(event) {
            if (document.getElementById('imageModal').style.display === 'block') {
                // Zoom controls when modal is open
                if (event.key === '+' || event.key === '=') {
                    zoomIn();
                } else if (event.key === '-') {
                    zoomOut();
                } else if (event.key === '0') {
                    resetZoom();
                } else if (event.key === 'r' || event.key === 'R') {
                    rotateImage();
                }
            } else {
                // Slider navigation when modal is closed
                if (event.key === 'ArrowLeft') {
                    changeImage(-1);
                } else if (event.key === 'ArrowRight') {
                    changeImage(1);
                }
            }
        });

        // Progress Bar Animation
        function animateProgressBars() {
            const progressBars = document.querySelectorAll('.progress-bar');
            
            progressBars.forEach((bar, index) => {
                const targetWidth = bar.style.width;
                bar.style.width = '0%';
                
                setTimeout(() => {
                    bar.style.width = targetWidth;
                    bar.style.transition = 'width 1s ease-in-out';
                }, index * 200); // Stagger animation
            });
        }

        // Initialize progress bar animation when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars after a short delay
            setTimeout(animateProgressBars, 500);
            
            // Add hover effects to progress bars
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                bar.addEventListener('mouseenter', function() {
                    this.style.transform = 'scaleY(1.2)';
                    this.style.transition = 'transform 0.2s ease';
                });
                
                bar.addEventListener('mouseleave', function() {
                    this.style.transform = 'scaleY(1)';
                });
            });
        });



        // Toast notification function
        function showToast(type, message) {
            // Remove existing toast if any
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            // Add to page
            document.body.appendChild(toast);

            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, 3000);
        }
    </script>

    <!-- Progress Bar Enhancement CSS -->
    <link rel="stylesheet" href="{{ asset('client/assets/css/progress-bar-enhancement.css') }}">
    
    <!-- Progress Bar Animation JavaScript -->
    <script src="{{ asset('client/assets/js/progress-bar-animation.js') }}"></script>
    
    <!-- jQuery Fallback Script -->
    <script>
    // Ensure jQuery is available for add to cart functionality
    if (typeof jQuery === 'undefined') {
        console.error('jQuery not available, waiting...');
        function waitForJQuery() {
            if (typeof jQuery !== 'undefined') {
                console.log('jQuery loaded, re-initializing add to cart...');
                // Re-initialize add to cart form
                $('#add-to-cart-form').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    console.log('Add to cart form submitted');
                    
                    const $form = $(this);
                    const $submitBtn = $form.find('button[type="submit"]');
                    const originalText = $submitBtn.html();
                    
                    // Check variant selection
                    const variantId = $form.find('select[name="variant_id"]').val();
                    if (!variantId) {
                        alert('Vui lòng chọn phiên bản sản phẩm!');
                        return;
                    }
                    
                    // Submit form
                    $.ajax({
                        url: $form.attr('action'),
                        method: 'POST',
                        data: $form.serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $submitBtn.prop('disabled', true);
                            $submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Đang thêm...');
                        },
                        success: function(response) {
                            if (response.success) {
                                // Show success message instead of redirect
                                showToast(response.message || 'Đã thêm sản phẩm vào giỏ hàng!', 'success');
                                
                                // Update cart count if available
                                if (response.cart_count !== undefined) {
                                    if (window.updateCartCount) {
                                        window.updateCartCount(response.cart_count);
                                    }
                                    
                                    const cartCountElement = document.querySelector('.cart-count');
                                    if (cartCountElement) {
                                        cartCountElement.textContent = response.cart_count;
                                        cartCountElement.classList.add('updated');
                                        setTimeout(() => {
                                            cartCountElement.classList.remove('updated');
                                        }, 600);
                                    }
                                }
                            } else {
                                showToast(response.message || 'Có lỗi xảy ra!', 'error');
                            }
                        },
                        error: function(xhr) {
                            showToast('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
                        },
                        complete: function() {
                            $submitBtn.prop('disabled', false);
                            $submitBtn.html(originalText);
                        }
                    });
                });
            } else {
                setTimeout(waitForJQuery, 100);
            }
        }
        waitForJQuery();
    }
    </script>
@endsection