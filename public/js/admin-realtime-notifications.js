/**
 * Admin Realtime Notifications
 * Handles realtime notifications for admin dashboard
 */

class AdminRealtimeNotifications {
    constructor() {
        this.pusher = null;
        this.adminChannel = null;
        this.notificationsChannel = null;
        this.notificationCount = 0;
        this.isInitialized = false;
        this.notificationHistory = [];
        
        this.init();
    }

    init() {
        // console.log('🎯 Initializing Admin Realtime Notifications...');
        
        try {
            // Initialize Pusher
            this.initializePusher();
            
            // Subscribe to channels
            this.subscribeToChannels();
            
            // Initialize UI elements
            this.initializeUI();
            
            // Set up event listeners
            this.setupEventListeners();
            
            this.isInitialized = true;
            // console.log('🎯 Admin Realtime Notifications initialized successfully');
            
        } catch (error) {
            console.error('🎯 Failed to initialize Admin Realtime Notifications:', error);
        }
    }

    initializePusher() {
        // Get Pusher configuration from global variables or use defaults
        const pusherKey = window.PUSHER_APP_KEY || 'localkey123';
        const pusherCluster = window.PUSHER_APP_CLUSTER || 'mt1';
        const pusherHost = window.PUSHER_HOST || '127.0.0.1';
        const pusherPort = window.PUSHER_PORT || 6001;

        // console.log('🎯 Initializing Pusher with config:', {
        //     key: pusherKey,
        //     cluster: pusherCluster,
        //     host: pusherHost,
        //     port: pusherPort
        // });

        this.pusher = new Pusher(pusherKey, {
            cluster: pusherCluster,
            encrypted: false,
            wsHost: pusherHost,
            wsPort: pusherPort,
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
            activityTimeout: 30000,
            pongTimeout: 15000,
            maxReconnectionAttempts: 5,
            maxReconnectGap: 5000
        });

        // Monitor connection status (hidden in production)
        this.pusher.connection.bind('connected', () => {
            // console.log('🎯 Pusher connected successfully');
            // this.updateConnectionStatus('connected'); // Hidden in production
        });

        this.pusher.connection.bind('error', (err) => {
            console.error('🎯 Pusher connection error:', err);
            // this.updateConnectionStatus('error'); // Hidden in production
        });

        this.pusher.connection.bind('disconnected', () => {
            // console.log('🎯 Pusher disconnected');
            // this.updateConnectionStatus('disconnected'); // Hidden in production
        });
    }

    subscribeToChannels() {
        // Subscribe to admin orders channel
        this.adminChannel = this.pusher.subscribe('admin.orders');
        // console.log('🎯 Subscribed to admin.orders channel');

        // Subscribe to admin notifications channel
        this.notificationsChannel = this.pusher.subscribe('admin.notifications');
        // console.log('🎯 Subscribed to admin.notifications channel');

        // Set up channel event listeners
        this.setupChannelListeners();
    }

    setupChannelListeners() {
        // Listen for order received confirmation
        this.adminChannel.bind('OrderReceivedConfirmed', (data) => {
            console.log('🎯 Received OrderReceivedConfirmed event:', data);
            this.handleOrderReceivedConfirmation(data);
        });

        // Listen for order status updates
        this.adminChannel.bind('OrderStatusUpdated', (data) => {
            console.log('🎯 Received OrderStatusUpdated event:', data);
            this.handleOrderStatusUpdate(data);
        });

        // Listen for new orders
        this.adminChannel.bind('NewOrderPlaced', (data) => {
            console.log('🎯 Received NewOrderPlaced event:', data);
            this.handleNewOrder(data);
        });

        // Listen for order cancellations
        this.adminChannel.bind('OrderCancelled', (data) => {
            console.log('🎯 Received OrderCancelled event:', data);
            this.handleOrderCancelled(data);
        });

        // Channel subscription status
        this.adminChannel.bind('pusher:subscription_succeeded', () => {
            // console.log('🎯 Successfully subscribed to admin.orders channel');
        });

        this.adminChannel.bind('pusher:subscription_error', (status) => {
            console.error('🎯 Failed to subscribe to admin.orders channel:', status);
        });
    }

    handleOrderReceivedConfirmation(data) {
        console.log('🎯 Handling order received confirmation:', data);
        
        // Update order in admin list
        this.updateOrderInAdminList(data);
        
        // Show notification
        const message = `🎉 Khách hàng ${data.customer_name || 'đã'} xác nhận nhận hàng! Đơn hàng #${data.order_code || data.order_id} đã hoàn thành.`;
        this.showNotification(message, 'success', 'order_received_confirmed');
        
        // Play sound
        this.playNotificationSound();
        
        // Show desktop notification
        this.showDesktopNotification('Đơn hàng hoàn thành', message);
        
        // Update notification count
        this.incrementNotificationCount();
        
        // Force refresh the page after 3 seconds to ensure UI is updated
        setTimeout(() => {
            console.log('🎯 Refreshing admin orders page to ensure UI is updated');
            window.location.reload();
        }, 3000);
    }

    handleOrderStatusUpdate(data) {
        console.log('🎯 Handling order status update:', data);
        
        // Update order in admin list
        this.updateOrderInAdminList(data);
        
        // Show notification for client actions
        if (data.action_by === 'client' || data.action_type === 'client_confirmed_received') {
            const message = `✅ Khách hàng đã xác nhận nhận hàng! Đơn hàng #${data.order_code || data.order_id}`;
            this.showNotification(message, 'success', 'order_status_updated');
            this.playNotificationSound();
        }
    }

    handleNewOrder(data) {
        console.log('🎯 Handling new order:', data);
        
        // Add new order to admin list
        this.addNewOrderToAdminList(data);
        
        // Show notification
        const message = `🆕 Có đơn hàng mới! Đơn hàng #${data.order_code || data.order_id}`;
        this.showNotification(message, 'info', 'new_order');
        
        // Play sound
        this.playNotificationSound();
        
        // Update notification count
        this.incrementNotificationCount();
    }

    handleOrderCancelled(data) {
        console.log('🎯 Handling order cancelled:', data);
        
        // Update order in admin list
        this.updateOrderInAdminList(data);
        
        // Show notification
        const message = `❌ Đơn hàng #${data.order_code || data.order_id} đã bị hủy`;
        this.showNotification(message, 'warning', 'order_cancelled');
        
        // Play sound
        this.playNotificationSound();
    }

    updateOrderInAdminList(data) {
        // Try multiple selectors to find order row
        let orderRow = document.querySelector(`tr[data-order-id="${data.order_id}"]`);
        
        // If not found, try with order ID as string
        if (!orderRow) {
            orderRow = document.querySelector(`tr[data-order-id="${data.order_id.toString()}"]`);
        }
        
        // If still not found, try with order code
        if (!orderRow && data.order_code) {
            orderRow = document.querySelector(`tr[data-order-id*="${data.order_code}"]`);
        }
        
        // If still not found, try by order ID in any attribute
        if (!orderRow) {
            orderRow = document.querySelector(`tr[id*="${data.order_id}"]`);
        }
        
        if (!orderRow) {
            // Show notification that order was updated but not found in current view
            const message = `✅ Đơn hàng #${data.order_code || data.order_id} đã được cập nhật (có thể không hiển thị trong trang hiện tại)`;
            this.showNotification(message, 'info', 'order_updated_not_found');
            return;
        }
        
        // Update status badge
        const statusBadge = orderRow.querySelector('.status-badge');
        if (statusBadge && data.status) {
            statusBadge.className = `status-badge status-${data.status}`;
            statusBadge.textContent = this.getStatusText(data.status);
        }
        
        // Update payment status badge
        const paymentBadge = orderRow.querySelector('.payment-status-badge');
        if (paymentBadge && data.payment_status) {
            paymentBadge.className = `payment-status-badge payment-status-${data.payment_status}`;
            paymentBadge.textContent = this.getPaymentStatusText(data.payment_status);
        }
        
        // Add visual feedback
        orderRow.style.animation = 'pulse 1s ease-in-out';
        setTimeout(() => {
            orderRow.style.animation = '';
        }, 1000);
    }

    addNewOrderToAdminList(data) {
        console.log('🎯 Adding new order to admin list:', data);
        
        // This would typically add a new row to the orders table
        // For now, just show a notification
        console.log('🎯 New order would be added to list:', data);
    }

    getStatusText(status) {
        const statusMap = {
            'pending': 'Chờ xử lý',
            'processing': 'Đang chuẩn bị hàng',
            'shipping': 'Đang giao hàng',
            'delivered': 'Đã giao hàng',
            'received': 'Đã nhận hàng',
            'completed': 'Hoàn thành',
            'cancelled': 'Đã hủy'
        };
        return statusMap[status] || status;
    }

    getPaymentStatusText(status) {
        const statusMap = {
            'pending': 'Chờ TT',
            'paid': 'Đã TT',
            'processing': 'Đang xử lý',
            'completed': 'Hoàn thành',
            'failed': 'Thất bại',
            'refunded': 'Hoàn tiền',
            'cancelled': 'Đã hủy'
        };
        return statusMap[status] || status;
    }

    showNotification(message, type = 'info', category = 'general') {
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `admin-notification ${category} ${type}`;
        notification.innerHTML = `
            <div class="notification-header">
                <span class="notification-title">${this.getNotificationTitle(type)}</span>
                <span class="notification-time">${new Date().toLocaleTimeString('vi-VN')}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
            <div class="notification-body">
                <p>${message}</p>
            </div>
        `;
        
        // Add to notifications container
        const container = this.getNotificationsContainer();
        container.appendChild(notification);
        
        // Auto remove after 10 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 10000);
        
        // Add to history
        this.addToNotificationHistory(message, type, category);
    }

    getNotificationTitle(type) {
        const titles = {
            'success': '✅ Thành công',
            'error': '❌ Lỗi',
            'warning': '⚠️ Cảnh báo',
            'info': 'ℹ️ Thông tin'
        };
        return titles[type] || 'ℹ️ Thông báo';
    }

    getNotificationsContainer() {
        let container = document.querySelector('.admin-notifications-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'admin-notifications-container';
            document.body.appendChild(container);
        }
        return container;
    }

    playNotificationSound() {
        try {
            // Create audio context
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.2);
            
            // Sound played successfully
        } catch (error) {
            // Could not play notification sound
        }
    }

    showDesktopNotification(title, message) {
        if (!('Notification' in window)) {
            return;
        }
        
        if (Notification.permission === 'granted') {
            new Notification(title, {
                body: message,
                icon: '/favicon.ico',
                badge: '/favicon.ico'
            });
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    new Notification(title, {
                        body: message,
                        icon: '/favicon.ico',
                        badge: '/favicon.ico'
                    });
                }
            });
        }
    }

    // Connection status functions (hidden in production)
    /*
    updateConnectionStatus(status) {
        let statusElement = document.querySelector('.connection-status');
        if (!statusElement) {
            statusElement = document.createElement('div');
            statusElement.className = 'connection-status';
            document.body.appendChild(statusElement);
        }
        
        statusElement.className = `connection-status ${status}`;
        statusElement.textContent = this.getConnectionStatusText(status);
    }

    getConnectionStatusText(status) {
        const texts = {
            'connected': '🟢 Đã kết nối',
            'error': '🔴 Lỗi kết nối',
            'disconnected': '🟡 Mất kết nối'
        };
        return texts[status] || '❓ Không xác định';
    }
    */

    incrementNotificationCount() {
        this.notificationCount++;
        let counter = document.querySelector('.notification-counter');
        if (!counter) {
            counter = document.createElement('div');
            counter.className = 'notification-counter';
            document.body.appendChild(counter);
        }
        counter.textContent = this.notificationCount;
    }

    addToNotificationHistory(message, type, category) {
        this.notificationHistory.push({
            message,
            type,
            category,
            timestamp: new Date().toISOString()
        });
        
        // Keep only last 50 notifications
        if (this.notificationHistory.length > 50) {
            this.notificationHistory.shift();
        }
    }

    initializeUI() {
        // Add CSS if not already present
        if (!document.querySelector('#admin-realtime-styles')) {
            const style = document.createElement('style');
            style.id = 'admin-realtime-styles';
            style.textContent = `
                .admin-notifications-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    width: 400px;
                    max-height: 80vh;
                    overflow-y: auto;
                    z-index: 9999;
                    pointer-events: none;
                }
                
                .admin-notification {
                    background: white;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    margin-bottom: 10px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    pointer-events: auto;
                    animation: slideInRight 0.3s ease-out;
                }
                
                .admin-notification.success {
                    border-left: 4px solid #51cf66;
                    background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
                }
                
                .admin-notification.error {
                    border-left: 4px solid #ff6b6b;
                    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
                }
                
                .admin-notification.warning {
                    border-left: 4px solid #ff922b;
                    background: linear-gradient(135deg, #fff8f0 0%, #ffe8d0 100%);
                }
                
                .admin-notification.info {
                    border-left: 4px solid #339af0;
                    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
                }
                
                .notification-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 12px 16px;
                    border-bottom: 1px solid #eee;
                }
                
                .notification-body {
                    padding: 12px 16px;
                }
                
                .notification-close {
                    background: none;
                    border: none;
                    font-size: 18px;
                    cursor: pointer;
                    color: #999;
                }
                
                /* Connection status styles (hidden in production)
                .connection-status {
                    position: fixed;
                    top: 10px;
                    right: 10px;
                    padding: 8px 12px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: bold;
                    z-index: 10001;
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(10px);
                    border: 1px solid #ddd;
                }
                
                .connection-status.connected {
                    background: rgba(81, 207, 102, 0.1);
                    border-color: #51cf66;
                    color: #51cf66;
                }
                
                .connection-status.error {
                    background: rgba(255, 107, 107, 0.1);
                    border-color: #ff6b6b;
                    color: #ff6b6b;
                }
                
                .connection-status.disconnected {
                    background: rgba(255, 193, 7, 0.1);
                    border-color: #ffc107;
                    color: #ffc107;
                }
                */
                
                .notification-counter {
                    position: fixed;
                    top: 10px;
                    right: 10px;
                    background: #ff6b6b;
                    color: white;
                    border-radius: 50%;
                    width: 24px;
                    height: 24px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 12px;
                    font-weight: bold;
                    z-index: 10002;
                    animation: bounce 1s ease-in-out;
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
                
                @keyframes pulse {
                    0%, 100% {
                        opacity: 1;
                        transform: scale(1);
                    }
                    50% {
                        opacity: 0.8;
                        transform: scale(1.02);
                    }
                }
                
                @keyframes bounce {
                    0%, 20%, 53%, 80%, 100% {
                        transform: translate3d(0,0,0);
                    }
                    40%, 43% {
                        transform: translate3d(0, -8px, 0);
                    }
                    70% {
                        transform: translate3d(0, -4px, 0);
                    }
                    90% {
                        transform: translate3d(0, -2px, 0);
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    setupEventListeners() {
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    // Public methods for external use
    getNotificationHistory() {
        return this.notificationHistory;
    }

    clearNotifications() {
        const container = document.querySelector('.admin-notifications-container');
        if (container) {
            container.innerHTML = '';
        }
        this.notificationCount = 0;
        const counter = document.querySelector('.notification-counter');
        if (counter) {
            counter.remove();
        }
    }


    

}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.adminRealtimeNotifications = new AdminRealtimeNotifications();
});

// Also initialize if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        window.adminRealtimeNotifications = new AdminRealtimeNotifications();
    });
} else {
    window.adminRealtimeNotifications = new AdminRealtimeNotifications();
}

// Global functions for testing (production ready)
window.clearAdminNotifications = () => window.adminRealtimeNotifications.clearNotifications();
