/**
 * Simple Realtime Handler
 * Updates order status without page reload
 */

class SimpleRealtimeHandler {
    constructor() {
        this.init();
    }

    init() {
        // Only setup if realtime is enabled
        if (window.isRealtimeEnabled && window.isRealtimeEnabled()) {
            this.setupPusher();
        } else {
            console.log('ℹ️ Realtime disabled - skipping setup');
        }
    }

    setupPusher() {
        // Check if realtime is enabled first
        if (!window.isRealtimeEnabled || !window.isRealtimeEnabled()) {
            console.log('ℹ️ Realtime disabled - skipping pusher setup');
            return;
        }
        
        // Check if Pusher is available
        if (typeof Pusher === 'undefined') {
            console.log('ℹ️ Pusher not loaded');
            return;
        }
        
        // Check config
        if (!window.REALTIME_CONFIG || !window.REALTIME_CONFIG.enabled) {
            console.log('ℹ️ Realtime config disabled');
            return;
        }

        try {
            // Get config
            const config = window.getPusherConfig();
            if (!config) {
                console.warn('⚠️ Pusher config not found');
                return;
            }
            
            // Initialize Pusher
            window.pusher = new Pusher(config.key, {
                cluster: config.cluster,
                wsHost: config.wsHost,
                wsPort: config.wsPort,
                forceTLS: config.forceTLS,
                enabledTransports: config.enabledTransports,
                disabledTransports: config.disabledTransports
            });

            // Connection events
            window.pusher.connection.bind('connected', () => {
                console.log('✅ Realtime connected');
                this.subscribeToChannels();
            });

            window.pusher.connection.bind('error', (err) => {
                console.warn('⚠️ Realtime connection error:', err);
            });

            window.pusher.connection.bind('disconnected', () => {
                console.log('⚠️ Realtime disconnected');
            });

            // Connect
            window.pusher.connect();

        } catch (error) {
            console.warn('⚠️ Failed to setup Pusher:', error);
        }
    }

    subscribeToChannels() {
        if (!window.pusher || window.pusher.connection.state !== 'connected') {
            console.warn('⚠️ Pusher not connected');
            return;
        }

        try {
            // Subscribe to order updates
            const ordersChannel = window.pusher.subscribe('orders');
            const adminOrdersChannel = window.pusher.subscribe('admin.orders');

            // Listen for order status updates
            ordersChannel.bind('OrderStatusUpdated', (data) => {
                console.log('📦 Order status updated:', data);
                this.updateOrderStatus(data);
            });

            // Listen for new orders
            ordersChannel.bind('NewOrderPlaced', (data) => {
                console.log('🛒 New order placed:', data);
                this.handleNewOrder(data);
            });

            // Admin specific events
            adminOrdersChannel.bind('OrderStatusUpdated', (data) => {
                console.log('📦 Admin: Order status updated:', data);
                this.updateOrderStatus(data);
            });

            adminOrdersChannel.bind('NewOrderPlaced', (data) => {
                console.log('🛒 Admin: New order placed:', data);
                this.handleNewOrder(data);
            });

            console.log('✅ Channels subscribed successfully');
        } catch (error) {
            console.warn('⚠️ Failed to subscribe to channels:', error);
        }
    }

    updateOrderStatus(data) {
        const orderId = data.order_id;
        const newStatus = data.new_status;
        const statusText = this.getStatusText(newStatus);
        
        console.log(`🔄 Updating order ${orderId} status to: ${newStatus}`);
        
        // Update status in order list (admin)
        this.updateOrderListStatus(orderId, newStatus, statusText);
        
        // Update status in order detail page
        this.updateOrderDetailStatus(orderId, newStatus, statusText);
        
        // Update status in order edit form
        this.updateOrderEditForm(orderId, newStatus);
        
        // Show success message
        this.showStatusUpdateMessage(data);
    }

    updateOrderListStatus(orderId, newStatus, statusText) {
        // Find status elements in order list
        const statusElements = document.querySelectorAll(`[data-order-id="${orderId}"] .order-status, .order-${orderId} .status-badge`);
        
        statusElements.forEach(element => {
            element.textContent = statusText;
            element.className = element.className.replace(/status-\w+/, `status-${newStatus}`);
            element.classList.add('status-updated');
            
            // Remove highlight after 3 seconds
            setTimeout(() => {
                element.classList.remove('status-updated');
            }, 3000);
        });
    }

    updateOrderDetailStatus(orderId, newStatus, statusText) {
        // Update status in order detail page - try multiple selectors
        const selectors = [
            '.order-detail-status',
            '.order-status-display', 
            '.status-badge',
            '[class*="status-"]'
        ];
        
        let statusElement = null;
        for (const selector of selectors) {
            statusElement = document.querySelector(selector);
            if (statusElement) {
                console.log(`✅ Found status element with selector: ${selector}`);
                break;
            }
        }
        
        if (statusElement) {
            console.log('🔄 Updating status element:', statusElement);
            
            // Update icon and text based on status
            const iconElement = statusElement.querySelector('i');
            
            // Update icon
            if (iconElement) {
                const iconMap = {
                    'pending': 'fa-clock-o',
                    'processing': 'fa-cogs', 
                    'shipping': 'fa-truck',
                    'completed': 'fa-check-circle',
                    'cancelled': 'fa-times-circle'
                };
                iconElement.className = `fa ${iconMap[newStatus] || 'fa-question-circle'} mr-10`;
                console.log('✅ Updated icon to:', iconElement.className);
            }
            
            // Update text content - find text node after icon
            const textNodes = Array.from(statusElement.childNodes).filter(node => node.nodeType === Node.TEXT_NODE);
            if (textNodes.length > 0) {
                // Update text node directly
                textNodes[0].textContent = statusText;
                console.log('✅ Updated text node to:', statusText);
            } else {
                // If no text node, update innerHTML
                const icon = statusElement.querySelector('i');
                statusElement.innerHTML = '';
                if (icon) statusElement.appendChild(icon);
                statusElement.appendChild(document.createTextNode(statusText));
                console.log('✅ Updated innerHTML with text:', statusText);
            }
            
            // Update classes
            statusElement.className = statusElement.className.replace(/status-\w+/, `status-${newStatus}`);
            statusElement.classList.add('status-updated');
            console.log('✅ Updated classes to:', statusElement.className);
            
            setTimeout(() => {
                statusElement.classList.remove('status-updated');
            }, 3000);
        } else {
            console.warn('⚠️ No status element found with any selector');
            console.log('🔍 Available elements with status classes:');
            document.querySelectorAll('[class*="status"]').forEach(el => {
                console.log('  -', el.className, el.textContent.trim());
            });
            
            // Try to find any element with status text
            console.log('🔍 Searching for elements with status text:');
            document.querySelectorAll('*').forEach(el => {
                const text = el.textContent.trim();
                if (text.includes('Đang giao') || text.includes('Hoàn thành') || text.includes('Chờ xử lý')) {
                    console.log('  - Found element with status text:', el.tagName, el.className, text);
                }
            });
        }
    }

    // Test function to manually update status
    testUpdateStatus() {
        console.log('🧪 Testing manual status update...');
        const testData = {
            order_id: 43,
            order_code: 'WS1754299593113',
            new_status: 'completed'
        };
        this.updateOrderStatus(testData);
    }

    updateOrderEditForm(orderId, newStatus) {
        // Update select dropdown in edit form
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = newStatus;
            
            // Update current status display
            const currentStatusElement = document.querySelector('.current-status');
            if (currentStatusElement) {
                currentStatusElement.textContent = this.getStatusText(newStatus);
            }
        }
    }

    handleNewOrder(data) {
        console.log('🆕 New order received:', data);
        
        // Show notification for new order
        this.showNewOrderMessage(data);
        
        // If on orders list page, could add new row (but simpler to just show message)
    }

    getStatusText(status) {
        const statusTexts = {
            'pending': 'Chờ xử lý',
            'processing': 'Đang xử lý', 
            'shipping': 'Đang giao hàng',
            'completed': 'Hoàn thành',
            'cancelled': 'Đã hủy'
        };
        return statusTexts[status] || status;
    }

    showStatusUpdateMessage(data) {
        // Create or update status message
        let messageElement = document.getElementById('realtime-status-message');
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.id = 'realtime-status-message';
            messageElement.className = 'alert alert-success alert-dismissible';
            messageElement.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            document.body.appendChild(messageElement);
        }
        
        messageElement.innerHTML = `
            <button type="button" class="close" onclick="this.parentElement.remove()">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-check-circle"></i>
            <strong>Cập nhật trạng thái:</strong><br>
            Đơn hàng #${data.order_code} đã được cập nhật thành <strong>${this.getStatusText(data.new_status)}</strong>
        `;
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageElement.parentElement) {
                messageElement.remove();
            }
        }, 5000);
    }

    showNewOrderMessage(data) {
        let messageElement = document.getElementById('realtime-new-order-message');
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.id = 'realtime-new-order-message';
            messageElement.className = 'alert alert-info alert-dismissible';
            messageElement.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            document.body.appendChild(messageElement);
        }
        
        messageElement.innerHTML = `
            <button type="button" class="close" onclick="this.parentElement.remove()">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-shopping-cart"></i>
            <strong>Đơn hàng mới:</strong><br>
            Đơn hàng #${data.order_code} vừa được đặt
        `;
        
        setTimeout(() => {
            if (messageElement.parentElement) {
                messageElement.remove();
            }
        }, 5000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.simpleRealtimeHandler = new SimpleRealtimeHandler();
});

// Add CSS for status updates
const style = document.createElement('style');
style.textContent = `
.status-updated {
    animation: statusUpdate 0.5s ease-in-out;
    background-color: #d4edda !important;
    border-color: #c3e6cb !important;
}

@keyframes statusUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

#realtime-status-message,
#realtime-new-order-message {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
}
`;
document.head.appendChild(style); 