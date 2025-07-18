{{-- Realtime Notifications Container --}}
<div id="realtime-notifications" class="realtime-notifications-container">
    {{-- Live Activity Feed --}}
    <div id="activity-feed" class="activity-feed" style="display: none;">
        <div class="activity-header">
            <span class="live-indicator"></span>
            <strong>Thông báo</strong>
            <button class="close-activity" onclick="toggleActivityFeed()">&times;</button>
        </div>
        <div class="activity-list" id="activity-list">
            {{-- Activities will be added here dynamically --}}
        </div>
    </div>
    
    {{-- Activity Toggle Button --}}
    <button id="activity-toggle" class="activity-toggle" onclick="toggleActivityFeed()" title="Xem hoạt động realtime">
        <i class="fa fa-bell" style="font-size: 20px !important; line-height: 1 !important; color: white !important;"></i>
        <span class="activity-count" id="activity-count" style="display: none;">0</span>
    </button>
</div>

<style>
/* Realtime Notifications Styling */
.realtime-notifications-container {
    position: fixed !important;
    top: 70px !important;
    right: 15px !important;
    z-index: 9999 !important;
    visibility: visible !important;
    opacity: 1 !important;
    pointer-events: auto !important;
}

.activity-toggle {
    background: #e74c3c !important;
    color: white !important;
    border: none !important;
    border-radius: 50% !important;
    width: 50px !important;
    height: 50px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3) !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    visibility: visible !important;
    opacity: 1 !important;
    z-index: 2000 !important;
    outline: none !important;
    font-family: inherit !important;
}

.activity-toggle:hover {
    background: #c0392b;
    transform: scale(1.1);
}

.activity-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

.activity-feed {
    position: absolute;
    top: 60px;
    right: 0;
    width: 350px;
    max-height: 400px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    overflow: hidden;
    border: 1px solid #e0e0e0;
    z-index: 10001;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-20px);
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.activity-feed.visible {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.activity-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 16px 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 12px 12px 0 0;
}

.close-activity {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #666;
}

.activity-list {
    max-height: 300px;
    overflow-y: auto;
    padding: 0;
}

.activity-item {
    padding: 14px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    transition: all 0.2s ease;
    position: relative;
}

.activity-item:hover {
    background: #f8f9fa;
    transform: translateX(2px);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item.new-item {
    animation: newActivitySlide 0.5s ease;
    background: linear-gradient(90deg, rgba(40, 167, 69, 0.1) 0%, transparent 100%);
}

@keyframes newActivitySlide {
    0% {
        opacity: 0;
        transform: translateX(-20px) translateY(10px);
        filter: blur(2px);
    }
    50% {
        opacity: 0.5;
        transform: translateX(-10px) translateY(5px);
        filter: blur(1px);
    }
    100% {
        opacity: 1;
        transform: translateX(0) translateY(0);
        filter: blur(0);
    }
}

.activity-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    flex-shrink: 0;
}

.activity-icon.added {
    background: #28a745;
}

.activity-icon.removed {
    background: #6c757d;
}

.activity-icon.pending {
    background: #ffc107;
}

.activity-icon.processing {
    background: #17a2b8;
}

.activity-icon.shipping {
    background: #007bff;
}

.activity-icon.completed {
    background: #28a745;
}

.activity-icon.cancelled {
    background: #dc3545;
}

.activity-content {
    flex: 1;
    font-size: 13px;
}

.activity-user {
    font-weight: 600;
    color: #333;
}

.activity-text {
    color: #666;
    margin: 2px 0;
}

.activity-time {
    color: #999;
    font-size: 11px;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile responsive */
@media (max-width: 480px) {
    .realtime-notifications-container {
        top: 70px;
        right: 10px;
    }
    
    .activity-feed {
        width: calc(100vw - 40px);
        right: -15px;
    }
}
</style>

<script>
// Activity Feed Management
let activityCount = 0;
let activityFeedVisible = false;
let autoHideTimer = null;

function showActivityFeed() {
    const feed = document.getElementById('activity-feed');
    feed.style.display = 'block';
    
    // Trigger reflow before adding the visible class
    feed.offsetHeight;
    feed.classList.add('visible');
    activityFeedVisible = true;
    
    // Clear existing timer if any
    if (autoHideTimer) {
        clearTimeout(autoHideTimer);
    }
    
    // Set new timer to hide after 7 seconds
    autoHideTimer = setTimeout(() => {
        hideActivityFeed();
    },  5000);
}

function hideActivityFeed() {
    const feed = document.getElementById('activity-feed');
    feed.classList.remove('visible');
    
    // Wait for the transition to complete before hiding
    setTimeout(() => {
        if (!activityFeedVisible) {
            feed.style.display = 'none';
        }
    }, 400); // Match the transition duration
    
    activityFeedVisible = false;
}

function toggleActivityFeed() {
    if (activityFeedVisible) {
        hideActivityFeed();
        // Clear the auto-hide timer when manually closing
        if (autoHideTimer) {
            clearTimeout(autoHideTimer);
            autoHideTimer = null;
        }
    } else {
        showActivityFeed();
        // Reset count when opened manually
        resetActivityCount();
    }
}

// Thêm hàm để xử lý khi có thông báo mới
function handleNewNotification() {
    if (!activityFeedVisible) {
        showActivityFeed();
    }
    incrementActivityCount();
    
    // Add ripple effect to notification button
    const toggle = document.getElementById('activity-toggle');
    toggle.classList.add('new-notification');
    
    // Remove ripple effect after animation
    setTimeout(() => {
        toggle.classList.remove('new-notification');
    }, 2000);
}

function addActivityItem(data) {
    const list = document.getElementById('activity-list');
    const item = document.createElement('div');
    item.className = 'activity-item new-item';
    handleNewNotification();
    
    const timeAgo = getTimeAgo(new Date(data.timestamp));
    const actionIcon = data.action === 'added' ? 'fa-heart' : 'fa-heart-o';
    const actionText = data.action === 'added' ? 'đã thích' : 'đã bỏ thích';
    const actionEmoji = data.action === 'added' ? '💝' : '💔';
    
    item.innerHTML = `
        <div class="activity-icon ${data.action}">
            <i class="fa ${actionIcon}" style="font-family: FontAwesome; font-style: normal; font-weight: normal;">${actionIcon.includes('fa-heart') ? '' : actionEmoji}</i>
        </div>
        <div class="activity-content">
            <div class="activity-user">${data.user_name}</div>
            <div class="activity-text">${actionText} <strong>"${data.product_name}"</strong></div>
            <div class="activity-time">${timeAgo}</div>
        </div>
    `;
    
    // Add to top of list with animation
    list.insertBefore(item, list.firstChild);
    
    // Remove animation class after animation completes
    setTimeout(function() {
        item.classList.remove('new-item');
    }, 500);
    
    // Remove old items if more than 20
    while (list.children.length > 20) {
        list.removeChild(list.lastChild);
    }
    
    // Update count if feed is not visible
    if (!activityFeedVisible) {
        incrementActivityCount();
    }
    
    // Fix icons in the new item
    setTimeout(function() {
        const newIcon = item.querySelector('.fa');
        if (newIcon) {
            const computedStyle = window.getComputedStyle(newIcon, ':before');
            const content = computedStyle.getPropertyValue('content');
            
            if (!content || content === 'none' || content === '""') {
                newIcon.textContent = actionEmoji;
                newIcon.style.fontFamily = 'serif';
                newIcon.style.fontSize = '14px';
            }
        }
    }, 50);
}

function incrementActivityCount() {
    activityCount++;
    const countElement = document.getElementById('activity-count');
    countElement.textContent = activityCount;
    countElement.style.display = activityCount > 0 ? 'flex' : 'none';
}

function resetActivityCount() {
    activityCount = 0;
    const countElement = document.getElementById('activity-count');
    countElement.style.display = 'none';
}

function getTimeAgo(date) {
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    
    if (diff < 60) return 'Vừa xong';
    if (diff < 3600) return Math.floor(diff / 60) + ' phút trước';
    if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
    return Math.floor(diff / 86400) + ' ngày trước';
}

function addCartActivityItem(data) {
    const list = document.getElementById('activity-list');
    const item = document.createElement('div');
    item.className = 'activity-item new-item';
    handleNewNotification();

    const timeAgo = getTimeAgo(new Date(data.timestamp));
    const actionIcon = data.action === 'added' ? 'fa-shopping-cart' : 'fa-trash';
    const actionText = data.action === 'added' ? 'đã thêm vào giỏ hàng' : 'đã xóa khỏi giỏ hàng';
    const actionEmoji = data.action === 'added' ? '🛒' : '🗑️';

    item.innerHTML = `
        <div class="activity-icon ${data.action}">
            <i class="fa ${actionIcon}" style="font-family: FontAwesome; font-style: normal; font-weight: normal;">${actionIcon.includes('fa-shopping-cart') ? '' : actionEmoji}</i>
        </div>
        <div class="activity-content">
            <div class="activity-user">${data.user_name}</div>
            <div class="activity-text">${actionText} <strong>"${data.product_name}"</strong></div>
            <div class="activity-time">${timeAgo}</div>
        </div>
    `;

    list.insertBefore(item, list.firstChild);

    setTimeout(function() {
        item.classList.remove('new-item');
    }, 500);

    while (list.children.length > 20) {
        list.removeChild(list.lastChild);
    }

    if (!activityFeedVisible) {
        incrementActivityCount();
    }
}

function addOrderActivityItem(data) {
    const list = document.getElementById('activity-list');
    const item = document.createElement('div');
    item.className = 'activity-item new-item';

    const timeAgo = getTimeAgo(new Date(data.updated_at));
    let statusIcon = 'fa-info-circle';
    let statusColor = '#007bff';
    let statusEmoji = '🔄';

    switch (data.new_status) {
        case 'pending':
            statusIcon = 'fa-hourglass-start';
            statusColor = '#ffc107';
            statusEmoji = '⏳';
            break;
        case 'processing':
            statusIcon = 'fa-cogs';
            statusColor = '#17a2b8';
            statusEmoji = '⚙️';
            break;
        case 'shipping':
            statusIcon = 'fa-truck';
            statusColor = '#007bff';
            statusEmoji = '🚚';
            break;
        case 'completed':
            statusIcon = 'fa-check-circle';
            statusColor = '#28a745';
            statusEmoji = '✅';
            break;
        case 'cancelled':
            statusIcon = 'fa-times-circle';
            statusColor = '#dc3545';
            statusEmoji = '❌';
            break;
    }

    item.innerHTML = `
        <div class="activity-icon" style="background: ${statusColor};">
            <i class="fa ${statusIcon}" style="font-family: FontAwesome; font-style: normal; font-weight: normal;">${statusEmoji}</i>
        </div>
        <div class="activity-content">
            <div class="activity-user">${data.user_name}</div>
            <div class="activity-text">Đơn hàng <strong>#${data.order_code}</strong> ${data.status_text ? 'đã chuyển sang trạng thái' : 'cập nhật'} <strong>${data.status_text}</strong></div>
            <div class="activity-time">${timeAgo}</div>
        </div>
    `;

    list.insertBefore(item, list.firstChild);

    setTimeout(function() {
        item.classList.remove('new-item');
    }, 500);

    while (list.children.length > 20) {
        list.removeChild(list.lastChild);
    }

    if (!activityFeedVisible) {
        incrementActivityCount();
    }

    // Hiển thị toast popup khi có event OrderStatusUpdated
    if (typeof Swal !== 'undefined') {
        let icon = 'info';
        let title = 'Cập nhật đơn hàng';
        let text = data.message || '';
        if (data.new_status === 'processing') {
            icon = 'success';
            title = 'Mua hàng thành công!';
        } else if (data.new_status === 'cancelled' || data.new_status === 'failed') {
            icon = 'error';
            title = 'Mua hàng thất bại!';
        }
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            timer: 4000,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    } else {
        alert(data.message || 'Cập nhật đơn hàng!');
    }
}

// Close activity feed when clicking outside
document.addEventListener('click', function(event) {
    const container = document.getElementById('realtime-notifications');
    if (activityFeedVisible && !container.contains(event.target)) {
        toggleActivityFeed();
    }
});

// Setup realtime listening when Echo is available
document.addEventListener('DOMContentLoaded', function() {
    // Ensure notification button is visible and functional
    setTimeout(function() {
        const notificationButton = document.getElementById('activity-toggle');
        const notificationContainer = document.getElementById('realtime-notifications');
        
        if (notificationButton && notificationContainer) {
            // Force visibility with extensive fixes
            notificationContainer.style.cssText = `
                position: fixed !important;
                top: 70px !important;
                right: 15px !important;
                z-index: 9999 !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                pointer-events: auto !important;
            `;
            
            notificationButton.style.cssText = `
                background: #e74c3c !important;
                color: white !important;
                border: none !important;
                border-radius: 50% !important;
                width: 50px !important;
                height: 50px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
                position: relative !important;
                visibility: visible !important;
                opacity: 1 !important;
                z-index: 10000 !important;
                box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3) !important;
            `;
            
            // Fix the bell icon inside
            const bellIcon = notificationButton.querySelector('i');
            if (bellIcon) {
                bellIcon.style.cssText = `
                    font-family: FontAwesome !important;
                    font-style: normal !important;
                    font-weight: normal !important;
                    font-size: 20px !important;
                    line-height: 1 !important;
                    color: white !important;
                    display: inline-block !important;
                    text-rendering: auto !important;
                    -webkit-font-smoothing: antialiased !important;
                    -moz-osx-font-smoothing: grayscale !important;
                `;
                
                // Check if FontAwesome is working
                setTimeout(function() {
                    const computedStyle = window.getComputedStyle(bellIcon, ':before');
                    const content = computedStyle.getPropertyValue('content');
                    
                    if (!content || content === 'none' || content === '""') {
                        bellIcon.textContent = '🔔';
                        bellIcon.style.fontFamily = 'serif';
                        bellIcon.style.fontSize = '18px';
                    } else {
                        bellIcon.textContent = ''; // Clear any text content
                    }
                }, 100);
            }
        }
    }, 500);
    
    // Wait for Echo to be initialized
    setTimeout(function() {
        if (window.Echo) {
            // Listen to the favorites channel for all favorite updates
            window.Echo.channel('favorites')
                .listen('FavoriteUpdated', function(data) {
                    // Add to activity feed
                    addActivityItem({
                        user_name: data.user_name,
                        product_name: data.product_name,
                        action: data.action,
                        timestamp: data.timestamp
                    });
                    
                    // Show toast notification if it's not the current user
                    if (window.currentUserId && data.user_id !== window.currentUserId) {
                        const message = data.action === 'added' 
                            ? `${data.user_name} đã thích "${data.product_name}"`
                            : `${data.user_name} đã bỏ thích "${data.product_name}"`;
                            
                        if (window.RealtimeNotifications) {
                            window.RealtimeNotifications.showToast(
                                data.action === 'added' ? 'success' : 'info',
                                'Hoạt động mới',
                                message
                            );
                        }
                    }
                })
                .error(function(error) {
                    // Error handled silently
                });

            window.Echo.channel('cart-updates')
                .listen('CardUpdate', function(data) {
                    addCartActivityItem(data);

                    // Hiển thị toast nếu muốn
                    if (window.currentUserId && data.user_id !== window.currentUserId) {
                        const message = data.action === 'added' 
                            ? `${data.user_name} đã thêm "${data.product_name}" vào giỏ hàng`
                            : `${data.user_name} đã xóa "${data.product_name}" khỏi giỏ hàng`;

                        if (window.RealtimeNotifications) {
                            window.RealtimeNotifications.showToast(
                                data.action === 'added' ? 'success' : 'info',
                                'Hoạt động giỏ hàng',
                                message
                            );
                        }
                    }
                });

            // Listen for order status updates
            window.Echo.channel('orders')
                .listen('OrderStatusUpdated', function(data) {
                    addOrderActivityItem(data);
                })
                .error(function(error) {
                    // Error handled silently
                });

            // Lắng nghe private channel cho user hiện tại
            if (window.currentUserId) {
                window.Echo.private('user.' + window.currentUserId)
                    .listen('OrderStatusUpdated', function(data) {
                        addOrderActivityItem(data);
                    })
                    .error(function(error) {
                        // Error handled silently
                    });
            }
        }
    }, 1500); // Wait 1.5 seconds for Echo to initialize
});
</script>