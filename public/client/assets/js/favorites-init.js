/**
 * Initialize FavoriteManager when page loads
 */
$(document).ready(function() {
    // Initialize favorite manager if it exists
    if (typeof FavoriteManager !== 'undefined') {
        window.favoriteManager = new FavoriteManager();
        console.log('✅ FavoriteManager initialized');
    } else {
        console.warn('⚠️ FavoriteManager not found');
    }
    
    // Setup page-specific functionality for favorites page
    if (window.location.pathname.includes('/favorite') || window.location.pathname.includes('/favorites')) {
        console.log('📍 On favorites page, setting up specific functionality');
        
        // Add confirmation dialog for remove favorite buttons
        // Lưu ý: KHÔNG bind sự kiện .remove-favorite ở favorites.js để tránh double event!
        $('.remove-favorite').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const productId = button.data('product-id');
            const shopItem = button.closest('.col-sm-6, .col-md-4, .col-lg-3, [class*="col-"]');
            const productName = shopItem.find('.shop-item-title a').text().trim();
            
            // Show confirmation dialog
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: `Bạn có chắc muốn bỏ "${productName}" khỏi danh sách yêu thích?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Có, xóa ngay',
                    cancelButtonText: 'Hủy',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use the favorite manager to handle removal
                        if (window.favoriteManager) {
                            window.favoriteManager.removeFromFavorite(button[0]);
                        }
                    }
                });
            } else {
                if (confirm(`Bạn có chắc muốn bỏ "${productName}" khỏi danh sách yêu thích?`)) {
                    // Use the favorite manager to handle removal
                    if (window.favoriteManager) {
                        window.favoriteManager.removeFromFavorite(button[0]);
                    }
                }
            }
        });
        
        // Add auto-reload preference toggle
        const autoReloadToggle = `
            <div class="auto-reload-toggle" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
                <button class="btn btn-sm btn-outline-secondary" id="toggleAutoReload" title="Bật/tắt tự động tải lại trang">
                    <i class="fa fa-refresh"></i>
                    <span id="autoReloadStatus">Tắt</span>
                </button>
            </div>
        `;
        
        $('body').append(autoReloadToggle);
        
        // Update auto-reload status
        function updateAutoReloadStatus() {
            const isEnabled = localStorage.getItem('favorite_auto_reload') === 'true';
            $('#autoReloadStatus').text(isEnabled ? 'Bật' : 'Tắt');
            $('#toggleAutoReload').toggleClass('btn-success', isEnabled).toggleClass('btn-outline-secondary', !isEnabled);
        }
        
        updateAutoReloadStatus();
        
        // Toggle auto-reload
        $('#toggleAutoReload').on('click', function() {
            const currentState = localStorage.getItem('favorite_auto_reload') === 'true';
            localStorage.setItem('favorite_auto_reload', (!currentState).toString());
            updateAutoReloadStatus();
            
            const message = !currentState ? 'Đã bật tự động tải lại trang' : 'Đã tắt tự động tải lại trang';
            if (window.favoriteManager) {
                window.favoriteManager.showInfo(message);
            }
        });
    }
}); 