/**
 * Admin Realtime Notifications
 * Handles realtime notifications for admin dashboard
 */

// Debug: Log when this file is loaded
console.log('🎯 Admin Realtime Notifications JS file loaded on:', window.location.pathname);

class AdminRealtimeNotifications {
    constructor() {
        // Singleton pattern - prevent multiple instances
        if (AdminRealtimeNotifications.instance) {
            return AdminRealtimeNotifications.instance;
        }
        
        this.pusher = null;
        this.adminChannel = null;
        this.notificationsChannel = null;
        this.notificationCount = 0;
        this.isInitialized = false;
        this.notificationHistory = [];
        this.processedEvents = new Set(); // Track processed events to prevent duplicates
        this.eventTimeout = 3000; // 3 seconds timeout for duplicate detection
        this.processingEvents = new Set(); // Track events currently being processed
        
        // Store the instance
        AdminRealtimeNotifications.instance = this;
        
        this.init();
    }

    init() {
        // THÔNG BÁO REALTIME ADMIN BẬT CHO CẬP NHẬT UI (KHÔNG CÓ POPUP NOTIFICATIONS)
        console.log('🎯 Đang khởi tạo Thông báo Realtime Admin cho cập nhật UI (không có popup notifications)...');
        
        try {
            // Initialize Pusher
            this.initializePusher();
            
            // Subscribe to channels
            this.subscribeToChannels();
            
            // Initialize UI elements (minimal)
            this.initializeUI();
            
            // Set up event listeners
            this.setupEventListeners();
            
            this.isInitialized = true;
            console.log('🎯 Thông báo Realtime Admin đã khởi tạo cho cập nhật UI (không có popup notifications)');
            
        } catch (error) {
            console.error('Failed to initialize Admin Realtime Notifications:', error);
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
            // Pusher connection error
            // this.updateConnectionStatus('error'); // Hidden in production
        });

        this.pusher.connection.bind('disconnected', () => {
            // console.log('🎯 Pusher disconnected');
            // this.updateConnectionStatus('disconnected'); // Hidden in production
        });
    }

    subscribeToChannels() {
        // Đăng ký kênh đơn hàng admin
        this.adminChannel = this.pusher.subscribe('admin.orders');
        // console.log('🎯 Đã đăng ký kênh admin.orders');

        // Đăng ký kênh thông báo admin
        this.notificationsChannel = this.pusher.subscribe('admin.notifications');
        // console.log('🎯 Đã đăng ký kênh admin.notifications');

        // Set up channel event listeners
        this.setupChannelListeners();
    }

    setupChannelListeners() {
        // Lắng nghe xác nhận đã nhận hàng
        this.adminChannel.bind('OrderReceivedConfirmed', (data) => {
            this.handleOrderReceivedConfirmation(data);
        });

        // Lắng nghe cập nhật trạng thái đơn hàng
        this.adminChannel.bind('OrderStatusUpdated', (data) => {
            this.handleOrderStatusUpdate(data);
        });

        // Lắng nghe đơn hàng mới
        this.adminChannel.bind('NewOrderPlaced', (data) => {
            this.handleNewOrder(data);
        });

        // Lắng nghe hủy đơn hàng
        this.adminChannel.bind('OrderCancelled', (data) => {
            this.handleOrderCancelled(data);
        });

        // Trạng thái đăng ký kênh
        this.adminChannel.bind('pusher:subscription_succeeded', () => {
            // console.log('🎯 Đã đăng ký thành công kênh admin.orders');
        });

        this.adminChannel.bind('pusher:subscription_error', (status) => {
            // Không thể đăng ký kênh admin.orders
        });
    }

    handleOrderReceivedConfirmation(data) {
        // Kiểm tra event trùng lặp
        if (this.isDuplicateEvent('OrderReceivedConfirmed', data)) {
            return;
        }
        
        // Cập nhật đơn hàng trong danh sách admin
        this.updateOrderInAdminList(data);
        
        // Show notification (DISABLED - Only UI updates, no popup notifications)
        // const message = `🎉 Khách hàng ${data.customer_name || 'đã'} xác nhận nhận hàng! Đơn hàng #${data.order_code || data.order_id} đã hoàn thành.`;
        // this.showNotification(message, 'success', 'order_received_confirmed');
        
        // Play sound (DISABLED - Only UI updates, no sound notifications)
        // this.playNotificationSound();
        
        // Show desktop notification (DISABLED - Only UI updates, no desktop notifications)
        // this.showDesktopNotification('Đơn hàng hoàn thành', message);
        
        // Update notification count (DISABLED - Only UI updates, no count updates)
        // this.incrementNotificationCount();
    }

    handleOrderStatusUpdate(data) {
        // Kiểm tra event trùng lặp
        if (this.isDuplicateEvent('OrderStatusUpdated', data)) {
            return;
        }
        
        // Cập nhật đơn hàng trong danh sách admin
        this.updateOrderInAdminList(data);
        
        // Hiển thị thông báo cho thay đổi trạng thái quan trọng (TẮT - Chỉ cập nhật UI)
        if (data.status === 'completed' && data.action_by === 'client') {
            // const message = `🎉 Khách hàng đã xác nhận nhận hàng! Đơn hàng #${data.order_code || data.order_id} đã hoàn thành.`;
            // this.showNotification(message, 'success', 'order_completed');
            // this.playNotificationSound();
            // this.incrementNotificationCount();
            console.log(`✅ Đơn hàng hoàn thành bởi khách hàng: ${data.order_code || data.order_id}`);
        } else {
            // Chỉ ghi log các cập nhật trạng thái khác
            console.log(`✅ Trạng thái đơn hàng đã cập nhật: ${data.order_code || data.order_id} - ${data.status}`);
        }
    }

    handleNewOrder(data) {
        // Kiểm tra event trùng lặp
        if (this.isDuplicateEvent('NewOrderPlaced', data)) {
            return;
        }
        
        // Thêm đơn hàng mới vào danh sách admin
        this.addNewOrderToAdminList(data);
        
        // Show notification (DISABLED - Only UI updates, no popup notifications)
        // const message = `🆕 Có đơn hàng mới! Đơn hàng #${data.order_code || data.order_id}`;
        // this.showNotification(message, 'info', 'new_order');
        
        // Play sound (DISABLED - Only UI updates, no sound notifications)
        // this.playNotificationSound();
        
        // Update notification count (DISABLED - Only UI updates, no count updates)
        // this.incrementNotificationCount();
    }

    handleOrderCancelled(data) {
        // Kiểm tra event trùng lặp
        if (this.isDuplicateEvent('OrderCancelled', data)) {
            return;
        }
        
        // Cập nhật đơn hàng trong danh sách admin
        this.updateOrderInAdminList(data);
        
        // Show notification (DISABLED - Only UI updates, no popup notifications)
        // const message = `❌ Đơn hàng #${data.order_code || data.order_id} đã bị hủy`;
        // this.showNotification(message, 'warning', 'order_cancelled');
        
        // Play sound (DISABLED - Only UI updates, no sound notifications)
        // this.playNotificationSound();
        
        // Update notification count (DISABLED - Only UI updates, no count updates)
        // this.incrementNotificationCount();
    }

    updateOrderInAdminList(data) {
        // Thử nhiều selector để tìm order row
        let orderRow = document.querySelector(`tr[data-order-id="${data.order_id}"]`);
        
        // Nếu không tìm thấy, thử với order ID dạng string
        if (!orderRow) {
            orderRow = document.querySelector(`tr[data-order-id="${data.order_id.toString()}"]`);
        }
        
        // Nếu vẫn không tìm thấy, thử với order ID trong id attribute
        if (!orderRow) {
            orderRow = document.querySelector(`tr[id="order-${data.order_id}"]`);
        }
        
        // Nếu vẫn không tìm thấy, thử với order code
        if (!orderRow && data.order_code) {
            orderRow = document.querySelector(`tr[data-order-id*="${data.order_code}"]`);
        }
        
        // Nếu vẫn không tìm thấy, thử với order ID trong bất kỳ attribute nào
        if (!orderRow) {
            orderRow = document.querySelector(`tr[id*="${data.order_id}"]`);
        }
        
        if (!orderRow) {
            // Hiển thị thông báo đơn hàng đã cập nhật nhưng không tìm thấy trong view hiện tại (TẮT - Chỉ log)
            // const message = `✅ Đơn hàng #${data.order_code || data.order_id} đã được cập nhật (có thể không hiển thị trong trang hiện tại)`;
            // this.showNotification(message, 'info', 'order_updated_not_found');
            console.log(`ℹ️ Đơn hàng #${data.order_code || data.order_id} đã cập nhật nhưng không tìm thấy trong view hiện tại`);
            
            // Đối với đơn hàng hoàn thành, có thể muốn refresh trang để hiển thị dữ liệu cập nhật
            if (data.status === 'completed') {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
            return;
        }
        
        // Cập nhật badge trạng thái
        const statusBadge = orderRow.querySelector('.status-badge');
        if (statusBadge && data.status) {
            statusBadge.className = `status-badge status-${data.status}`;
            
            // Cập nhật text trạng thái với icon
            if (data.status === 'completed') {
                statusBadge.innerHTML = '<i class="fa fa-check-circle mr-10"></i>Hoàn thành';
            } else if (data.status === 'pending') {
                statusBadge.innerHTML = '<i class="fa fa-clock-o mr-10"></i>Chờ xử lý';
            } else if (data.status === 'processing') {
                statusBadge.innerHTML = '<i class="fa fa-cogs mr-10"></i>Đang chuẩn bị hàng';
            } else if (data.status === 'shipping') {
                statusBadge.innerHTML = '<i class="fa fa-truck mr-10"></i>Đang giao hàng';
            } else if (data.status === 'delivered') {
                statusBadge.innerHTML = '<i class="fa fa-check-square-o mr-10"></i>Đã giao hàng';
            } else if (data.status === 'received') {
                statusBadge.innerHTML = '<i class="fa fa-handshake-o mr-10"></i>Đã nhận hàng';
            } else if (data.status === 'cancelled') {
                statusBadge.innerHTML = '<i class="fa fa-times-circle mr-10"></i>Đã hủy';
            } else {
                statusBadge.innerHTML = '<i class="fa fa-question-circle mr-10"></i>' + this.getStatusText(data.status);
            }
        }
        
        // Cập nhật badge trạng thái thanh toán
        const paymentBadge = orderRow.querySelector('.payment-status-badge');
        if (paymentBadge && data.payment_status) {
            paymentBadge.className = `payment-status-badge payment-status-${data.payment_status}`;
            
            // Cập nhật text trạng thái thanh toán với icon
            if (data.payment_status === 'paid') {
                paymentBadge.innerHTML = '<i class="fa fa-check-circle mr-10"></i>Đã TT';
            } else if (data.payment_status === 'pending') {
                paymentBadge.innerHTML = '<i class="fa fa-clock-o mr-10"></i>Chờ TT';
            } else if (data.payment_status === 'processing') {
                paymentBadge.innerHTML = '<i class="fa fa-cogs mr-10"></i>Đang xử lý';
            } else if (data.payment_status === 'completed') {
                paymentBadge.innerHTML = '<i class="fa fa-check-circle mr-10"></i>Hoàn thành';
            } else if (data.payment_status === 'failed') {
                paymentBadge.innerHTML = '<i class="fa fa-times-circle mr-10"></i>Thất bại';
            } else if (data.payment_status === 'refunded') {
                paymentBadge.innerHTML = '<i class="fa fa-undo mr-10"></i>Hoàn tiền';
            } else if (data.payment_status === 'cancelled') {
                paymentBadge.innerHTML = '<i class="fa fa-ban mr-10"></i>Đã hủy';
            } else {
                paymentBadge.innerHTML = '<i class="fa fa-question-circle mr-10"></i>' + this.getPaymentStatusText(data.payment_status);
            }
        }
        
        // Thêm visual feedback với màu sắc khác nhau dựa trên trạng thái
        if (data.status === 'completed') {
            orderRow.style.animation = 'pulse 2s ease-in-out';
            orderRow.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                orderRow.style.animation = '';
                orderRow.style.backgroundColor = '';
            }, 2000);
        } else {
            orderRow.style.animation = 'pulse 1s ease-in-out';
            setTimeout(() => {
                orderRow.style.animation = '';
            }, 1000);
        }
    }

    addNewOrderToAdminList(data) {
        // This would typically add a new row to the orders table
        // For now, just show a notification
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

    // Check if event is duplicate
    isDuplicateEvent(eventType, eventData) {
        // Create a simple key for duplicate detection
        const simpleKey = `${eventType}_${eventData.order_id}_${eventData.order_code || 'no_code'}`;
        
        // Check if already processed or currently being processed
        if (this.processedEvents.has(simpleKey) || this.processingEvents.has(simpleKey)) {
            return true;
        }
        
        // Mark as currently being processed
        this.processingEvents.add(simpleKey);
        
        // Add to processed events immediately and synchronously
        this.processedEvents.add(simpleKey);
        
        // Remove from processing events after a short delay
        setTimeout(() => {
            this.processingEvents.delete(simpleKey);
        }, 100);
        
        // Remove from processed events after timeout
        setTimeout(() => {
            this.processedEvents.delete(simpleKey);
        }, this.eventTimeout);
        
        return false;
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
                        transform: scale(1.01);
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
        
        // Clear processed events when page is about to unload
        window.addEventListener('beforeunload', () => {
            this.clearProcessedEvents();
        });
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

    // Clear processed events (useful for testing or when switching pages)
    clearProcessedEvents() {
        this.processedEvents.clear();
        this.processingEvents.clear();
    }

    // Show current processed events (for debugging)
    showProcessedEvents() {
        return {
            processed: Array.from(this.processedEvents),
            processing: Array.from(this.processingEvents)
        };
    }


    

}

// Initialize only once using singleton pattern
function initializeAdminNotifications() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            window.adminRealtimeNotifications = new AdminRealtimeNotifications();
        });
    } else {
        window.adminRealtimeNotifications = new AdminRealtimeNotifications();
    }
}

// Call the initialization function
initializeAdminNotifications();

// Global functions for testing (production ready)
window.clearAdminNotifications = () => window.adminRealtimeNotifications.clearNotifications();
window.clearProcessedEvents = () => window.adminRealtimeNotifications.clearProcessedEvents();
window.showProcessedEvents = () => window.adminRealtimeNotifications.showProcessedEvents();
