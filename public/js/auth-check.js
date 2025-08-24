/**
 * Auth Check Helper Functions
 * Sử dụng để kiểm tra đăng nhập trước khi thực hiện các chức năng mua hàng
 */

// Biến global để kiểm tra trạng thái đăng nhập
let isUserLoggedIn = false;

// Function để set trạng thái đăng nhập
function setAuthStatus(loggedIn) {
    isUserLoggedIn = loggedIn;
}

// Function để kiểm tra đăng nhập và redirect
function checkAuthAndRedirect(action = 'thực hiện chức năng này', redirectUrl = '/login') {
    if (!isUserLoggedIn) {
        // Hiển thị thông báo
        if (typeof showToast === 'function') {
            showToast(`Vui lòng đăng nhập để ${action}!`, 'info');
        } else {
            alert(`Vui lòng đăng nhập để ${action}!`);
        }
        
        // Redirect sau 1.5 giây
        setTimeout(function() {
            window.location.href = redirectUrl;
        }, 1500);
        return false;
    }
    return true;
}

// Function để kiểm tra đăng nhập cho thêm giỏ hàng
function checkAuthForAddToCart() {
    return checkAuthAndRedirect('thêm sản phẩm vào giỏ hàng');
}

// Function để kiểm tra đăng nhập cho mua hàng
function checkAuthForPurchase() {
    return checkAuthAndRedirect('mua hàng');
}

// Function để kiểm tra đăng nhập cho yêu thích
function checkAuthForFavorite() {
    return checkAuthAndRedirect('thêm vào yêu thích');
}

// Function để kiểm tra đăng nhập cho bình luận
function checkAuthForComment() {
    return checkAuthAndRedirect('bình luận');
}

// Function để kiểm tra đăng nhập cho đánh giá
function checkAuthForReview() {
    return checkAuthAndRedirect('đánh giá sản phẩm');
}

// Auto-initialize khi document ready
$(document).ready(function() {
    // Kiểm tra xem có phải user đã đăng nhập không bằng cách kiểm tra class authenticated trên body
    const isAuthenticated = $('body').hasClass('authenticated');
    
    setAuthStatus(isAuthenticated);
    
    // Auth status initialized
});

// Export functions để sử dụng trong các file khác
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        setAuthStatus,
        checkAuthAndRedirect,
        checkAuthForAddToCart,
        checkAuthForPurchase,
        checkAuthForFavorite,
        checkAuthForComment,
        checkAuthForReview
    };
} 