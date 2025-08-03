/**
 * Favorites functionality for client-side
 * Handles add/remove/toggle favorites with AJAX
 */

class FavoriteManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Toggle favorite buttons
        $(document).on('click', '.toggle-favorite', (e) => {
            e.preventDefault();
            this.toggleFavorite(e.currentTarget);
        });

        // Add to favorite buttons (include all button types)
        $(document).on('click', '.add-favorite, .btn-favorite-detail.add-favorite', (e) => {
            e.preventDefault();
            this.addToFavorite(e.currentTarget);
        });
        // Đã bỏ sự kiện .remove-favorite ở đây để tránh xóa khi chưa xác nhận
    }

    toggleFavorite(button) {
        const $button = $(button);
        const productId = $button.data('product-id');

        if (!productId) {
            this.showError('Product ID không hợp lệ');
            return;
        }

        // Check if user is authenticated
        if (!this.isAuthenticated()) {
            this.showLoginRequired();
            return;
        }

        this.setButtonLoading($button, true);

        $.ajax({
            url: '/favorite/toggle',
            method: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                this.setButtonLoading($button, false);
                
                if (response.success) {
                    // Xử lý action từ toggleFavorite API
                    if (response.action === 'added') {
                        this.updateButtonState($button, 'added');
                        this.showSuccess('Đã thêm vào danh sách yêu thích');
                    } else if (response.action === 'removed') {
                        this.updateButtonState($button, 'removed');
                        this.showSuccess('Đã xóa khỏi danh sách yêu thích');
                    } else {
                        // Fallback cho các action khác
                        this.updateButtonState($button, response.action);
                        this.showSuccess(response.message);
                    }
                    // Refresh favorite count in navbar
                    this.refreshFavoriteCount();
                    // Trigger event to hide session messages
                    $(document).trigger('favoriteActionSuccess');
                } else {
                    this.showError(response.message);
                }
            },
            error: (xhr) => {
                this.setButtonLoading($button, false);
                this.handleError(xhr);
            }
        });
    }

    addToFavorite(button) {
        const $button = $(button);
        const productId = $button.data('product-id');

        if (!productId) {
            this.showError('Product ID không hợp lệ');
            return;
        }

        if (!this.isAuthenticated()) {
            this.showLoginRequired();
            return;
        }

        this.setButtonLoading($button, true);

        $.ajax({
            url: '/favorite/add',
            method: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                this.setButtonLoading($button, false);
                
                if (response.success) {
                    // Kiểm tra action từ server
                    if (response.action === 'already_exists') {
                        // Nếu đã tồn tại, cập nhật UI nhưng hiển thị thông báo khác
                        this.updateButtonState($button, 'added');
                        this.showInfo('Sản phẩm đã có trong danh sách yêu thích');
                    } else {
                        // Thêm mới thành công
                        this.updateButtonState($button, 'added');
                        this.showSuccess(response.message);
                    }
                    // Refresh favorite count in navbar
                    this.refreshFavoriteCount();
                    // Trigger event to hide session messages
                    $(document).trigger('favoriteActionSuccess');
                } else {
                    // Nếu không có message (ví dụ: đã có trong danh sách yêu thích), không hiển thị thông báo
                    if (typeof response.message !== 'undefined' && response.message) {
                        this.showError(response.message);
                    }
                    // Nếu không có message, không làm gì cả
                }
            },
            error: (xhr) => {
                this.setButtonLoading($button, false);
                this.handleError(xhr);
            }
        });
    }

    removeFromFavorite(button) {
        const $button = $(button);
        const productId = $button.data('product-id');

        if (!productId) {
            this.showError('Product ID không hợp lệ');
            return;
        }

        if (!this.isAuthenticated()) {
            this.showLoginRequired();
            return;
        }

        this.setButtonLoading($button, true);

        $.ajax({
            url: '/favorite/remove',
            method: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                this.setButtonLoading($button, false);
                
                if (response.success) {
                    this.updateButtonState($button, 'removed');
                    this.showSuccess(response.message);
                    
                    // Refresh favorite count in navbar
                    this.refreshFavoriteCount();
                    
                    // Trigger event to hide session messages
                    $(document).trigger('favoriteActionSuccess');
                    
                    // Check if we're on the favorites page
                    if (window.location.pathname.includes('/favorite') || window.location.pathname.includes('/favorites')) {
                        // Remove the product item from DOM with animation
                        this.removeProductFromFavoritesPage($button, productId);
                    }
                } else {
                    this.showError(response.message);
                }
            },
            error: (xhr) => {
                this.setButtonLoading($button, false);
                this.handleError(xhr);
            }
        });
    }

    /**
     * Remove product from favorites page with animation and optional reload
     */
    removeProductFromFavoritesPage($button, productId) {
        const $shopItem = $button.closest('.col-sm-6, .col-md-4, .col-lg-3, [class*="col-"]');
        const productName = $shopItem.find('.shop-item-title a').text().trim();
        
        if ($shopItem.length === 0) {
            console.warn('Shop item not found, reloading page...');
            this.reloadPage();
            return;
        }

        // Show success message first
        this.showSuccess(`Đã xóa "${productName}" khỏi danh sách yêu thích`);
        
        // Add fade-out animation
        $shopItem.addClass('fade-out');
        
        setTimeout(() => {
            // Remove the element from DOM
            if ($shopItem.length > 0) {
                $shopItem.remove();
            }
            
            // Check if no more products left
            const remainingProducts = $('.products-container .shop-item').length;
            if (remainingProducts === 0) {
                this.showEmptyFavoritesState();
            }
            
            // Ask user if they want to reload the page
            this.askForPageReload();
        }, 300);
    }

    /**
     * Show empty favorites state
     */
    showEmptyFavoritesState() {
        const emptyStateHtml = `
            <div class="col-12">
                <div class="empty-favorites-container">
                    <div class="empty-favorites">
                        <div class="empty-icon">
                            <i class="fa fa-heart-o"></i>
                        </div>
                        <h3 class="empty-title">Chưa có sản phẩm yêu thích</h3>
                        <p class="empty-description">Hãy khám phá và thêm những sản phẩm bạn yêu thích vào danh sách!</p>
                        <div class="empty-actions">
                            <a href="/product" class="btn btn-primary btn-lg btn-explore">
                                <i class="fa fa-search"></i> Khám phá sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('.products-container').html(emptyStateHtml);
        $('.empty-favorites-container').hide().fadeIn(300);
    }

    /**
     * Ask user if they want to reload the page
     */
    askForPageReload() {
        // Check if user has enabled auto-reload preference
        const autoReload = localStorage.getItem('favorite_auto_reload') === 'true';
        
        if (autoReload) {
            // Auto reload after 2 seconds
            setTimeout(() => {
                this.reloadPage();
            }, 2000);
            return;
        }

        // Show confirmation dialog
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Cập nhật trang?',
                text: 'Bạn có muốn tải lại trang để cập nhật dữ liệu mới nhất?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Có, tải lại',
                cancelButtonText: 'Không, giữ nguyên',
                showDenyButton: true,
                denyButtonText: 'Tự động tải lại',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.reloadPage();
                } else if (result.isDenied) {
                    // Enable auto-reload for future
                    localStorage.setItem('favorite_auto_reload', 'true');
                    this.showInfo('Đã bật tự động tải lại trang cho lần sau');
                }
            });
        } else {
            // Fallback for browsers without SweetAlert
            if (confirm('Bạn có muốn tải lại trang để cập nhật dữ liệu mới nhất?')) {
                this.reloadPage();
            }
        }
    }

    /**
     * Reload the current page
     */
    reloadPage() {
        // Show loading indicator
        this.showInfo('Đang tải lại trang...');
        
        // Add loading class to body
        $('body').addClass('page-loading');
        
        // Reload after a short delay to show the message
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }

    updateButtonState($button, action) {
        console.log('Updating button state:', action, 'for product:', $button.data('product-id'));
        
        const $icon = $button.find('i');
        const productId = $button.data('product-id');
        
        // Update ALL buttons with the same product ID
        const $allButtons = $(`.btn-favorite[data-product-id="${productId}"], .btn-favorite-small[data-product-id="${productId}"], .btn-favorite-detail[data-product-id="${productId}"]`);
        
        $allButtons.each(function() {
            const $currentButton = $(this);
            const $currentIcon = $currentButton.find('i');
            
            if (action === 'added') {
                // Change to filled heart and add class
                $currentIcon.removeClass('far fa-heart').addClass('fas fa-heart');
                $currentButton.removeClass('add-favorite').addClass('remove-favorite');
                $currentButton.addClass('favorited');
                
                // Update title attribute
                $currentButton.attr('title', 'Bỏ yêu thích');
                
                // Update text if exists
                const $text = $currentButton.find('.btn-text');
                if ($text.length) {
                    $text.text('Bỏ yêu thích');
                }
                
                console.log('✅ Updated to favorited state:', $currentButton[0]);
                
            } else if (action === 'removed') {
                // Change to empty heart and remove class
                $currentIcon.removeClass('fas fa-heart').addClass('far fa-heart');
                $currentButton.removeClass('remove-favorite').addClass('add-favorite');
                $currentButton.removeClass('favorited');
                
                // Update title attribute
                $currentButton.attr('title', 'Thêm vào yêu thích');
                
                // Update text if exists
                const $text = $currentButton.find('.btn-text');
                if ($text.length) {
                    $text.text('Yêu thích');
                }
                
                console.log('✅ Updated to unfavorited state:', $currentButton[0]);
            }
        });
        
        // Add success animation only to the clicked button
        if (action === 'added') {
            $button.addClass('just-favorited success-feedback');
            setTimeout(() => {
                $button.removeClass('just-favorited success-feedback');
            }, 800);
        } else if (action === 'removed') {
            $button.addClass('success-feedback');
            setTimeout(() => {
                $button.removeClass('success-feedback');
            }, 600);
        }
    }

    setButtonLoading($button, loading) {
        const productId = $button.data('product-id');
        console.log('Setting loading state:', loading, 'for product:', productId);
        
        if (loading) {
            // Store current icon state before changing to spinner
            const $icon = $button.find('i');
            const currentIconClasses = $icon.attr('class') || '';
            $button.data('original-icon-classes', currentIconClasses);
            
            $button.prop('disabled', true);
            $button.addClass('loading');
            
            // Change to spinner
            $icon.removeClass('fas fa-heart far fa-heart').addClass('fas fa-spinner fa-spin');
            
            console.log('Set loading, stored icon classes:', currentIconClasses);
            
        } else {
            $button.prop('disabled', false);
            $button.removeClass('loading');
            
            const $icon = $button.find('i');
            
            // Remove spinner classes
            $icon.removeClass('fas fa-spinner fa-spin');
            
            // Restore original icon or determine correct icon based on button state
            if ($button.hasClass('favorited') || $button.hasClass('remove-favorite')) {
                $icon.addClass('fas fa-heart');
                console.log('Restored to filled heart');
            } else {
                $icon.addClass('far fa-heart');
                console.log('Restored to empty heart');
            }
        }
    }

    isAuthenticated() {
        // Check if user is authenticated by looking for auth meta tag or other indicators
        return $('meta[name="auth-user"]').length > 0 || 
               $('body').hasClass('authenticated') || 
               document.body.classList.contains('authenticated');
    }

    showLoginRequired() {
        const message = 'Vui lòng đăng nhập để sử dụng tính năng này';
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Cần đăng nhập',
                text: message,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Đăng nhập',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.info(message);
        } else {
            alert(message);
        }
    }

    showSuccess(message) {
        if (typeof toastr !== 'undefined') {
            toastr.success(message, '', {
                timeOut: 3000,
                extendedTimeOut: 1000,
                closeButton: true,
                progressBar: true
            });
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                text: message,
                icon: 'success',
                timer: 2500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#d4edda',
                color: '#155724'
            });
        } else {
            // Create a custom toast notification
            this.showCustomToast(message, 'success');
        }
    }

    showInfo(message) {
        if (typeof toastr !== 'undefined') {
            toastr.info(message, '', {
                timeOut: 3000,
                extendedTimeOut: 1000,
                closeButton: true,
                progressBar: true
            });
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                text: message,
                icon: 'info',
                timer: 2500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#d1ecf1',
                color: '#0c5460'
            });
        } else {
            // Create a custom toast notification
            this.showCustomToast(message, 'info');
        }
    }

    showError(message) {
        if (typeof toastr !== 'undefined') {
            toastr.error(message, '', {
                timeOut: 4000,
                extendedTimeOut: 1000,
                closeButton: true,
                progressBar: true
            });
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                text: message,
                icon: 'error',
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#f8d7da',
                color: '#721c24'
            });
        } else {
            this.showCustomToast(message, 'error');
        }
    }

    handleError(xhr) {
        let message = 'Có lỗi xảy ra. Vui lòng thử lại.';
        
        if (xhr.status === 401) {
            this.showLoginRequired();
            return;
        }
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }
        
        this.showError(message);
    }

    /**
     * Refresh favorite count in navbar
     */
    refreshFavoriteCount() {
        if (typeof window.refreshFavoriteCount === 'function') {
            window.refreshFavoriteCount();
        } else {
            // fallback: reload page if function not found
            console.warn('window.refreshFavoriteCount not found, reloading page');
            window.location.reload();
        }
    }

    showCustomToast(message, type = 'info') {
        // Create custom toast notification
        const toast = document.createElement('div');
        toast.className = `custom-toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Add toast styles if not exists
        if (!document.getElementById('custom-toast-styles')) {
            const styles = document.createElement('style');
            styles.id = 'custom-toast-styles';
            styles.innerHTML = `
                .custom-toast {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    min-width: 300px;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                    z-index: 10000;
                    animation: toastSlideIn 0.3s ease;
                    border-left: 4px solid;
                }
                .toast-success { border-left-color: #28a745; }
                .toast-error { border-left-color: #dc3545; }
                .toast-info { border-left-color: #17a2b8; }
                .toast-content {
                    display: flex;
                    align-items: center;
                    padding: 15px;
                    gap: 10px;
                }
                .toast-content i:first-child {
                    font-size: 18px;
                    flex-shrink: 0;
                }
                .toast-success .toast-content i:first-child { color: #28a745; }
                .toast-error .toast-content i:first-child { color: #dc3545; }
                .toast-info .toast-content i:first-child { color: #17a2b8; }
                .toast-message {
                    flex: 1;
                    margin: 0;
                    font-size: 14px;
                    color: #333;
                }
                .toast-close {
                    background: none;
                    border: none;
                    font-size: 12px;
                    color: #999;
                    cursor: pointer;
                    padding: 0;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .toast-close:hover { color: #666; }
                @keyframes toastSlideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes toastSlideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(styles);
        }

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'toastSlideOut 0.3s ease';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }, 3000);
    }
}

// Initialize when document is ready
$(document).ready(function() {
    window.favoriteManager = new FavoriteManager();
});

// Helper function to create favorite button
function createFavoriteButton(productId, isFavorited = false, showText = true) {
    const heartClass = isFavorited ? 'fas fa-heart' : 'far fa-heart';
    const buttonClass = isFavorited ? 'remove-favorite favorited' : 'add-favorite';
    const buttonText = showText ? (isFavorited ? 'Bỏ yêu thích' : 'Yêu thích') : '';
    
    return `
        <button class="btn btn-round btn-outline ${buttonClass}" data-product-id="${productId}">
            <i class="${heartClass}"></i>
            ${buttonText ? `<span class="btn-text">${buttonText}</span>` : ''}
        </button>
    `;
} 