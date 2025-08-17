/**
 * Simple Realtime Handler
 * Updates order status without page reload
 */

class SimpleRealtimeHandler {
    constructor() {
        this.processedOrders = new Set(); // Track processed orders to prevent duplicates
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
                console.log('🛒 New order placed (orders channel):', data);
                this.handleNewOrder(data);
            });

            // Admin specific events
            adminOrdersChannel.bind('OrderStatusUpdated', (data) => {
                console.log('📦 Admin: Order status updated:', data);
                this.updateOrderStatus(data);
            });

            adminOrdersChannel.bind('NewOrderPlaced', (data) => {
                console.log('🛒 Admin: New order placed (admin.orders channel):', data);
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
        const paymentStatus = data.payment_status;
        const paymentStatusText = data.payment_status_text;
        
        console.log(`🔄 Updating order ${orderId} status to: ${newStatus}, payment status: ${paymentStatus}`);
        
        // Update status in order list (admin)
        this.updateOrderListStatus(orderId, newStatus, statusText);
        
        // Update payment status in order list (admin)
        this.updateOrderListPaymentStatus(orderId, paymentStatus, paymentStatusText);
        
        // Update status in order detail page
        this.updateOrderDetailStatus(orderId, newStatus, statusText);
        
        // Update payment status in order detail page
        this.updateOrderDetailPaymentStatus(orderId, paymentStatus, paymentStatusText);
        
        // Update status in order edit form
        this.updateOrderEditForm(orderId, newStatus);
        
        // Update cancel button visibility based on status
        this.updateCancelButtonVisibility(orderId, newStatus, data);
        
        // Show success message
        this.showStatusUpdateMessage(data);
    }

    updateOrderListStatus(orderId, newStatus, statusText) {
        // Find status elements in order list
        const statusElements = document.querySelectorAll(`[data-order-id="${orderId}"] .order-status, .order-${orderId} .status-badge, [data-order-id="${orderId}"] .status-badge`);
        
        statusElements.forEach(element => {
            // Clear and rebuild with icon
            element.innerHTML = '';
            
            // Add icon
            const icon = document.createElement('i');
            icon.className = `fa fa-${this.getStatusIcon(newStatus)} mr-10`;
            element.appendChild(icon);
            
            // Add text content
            element.appendChild(document.createTextNode(statusText));
            
            // Update classes
            element.className = `status-badge status-${newStatus} order-detail-status`;
            element.classList.add('status-updated');
            
            // Remove highlight after 3 seconds
            setTimeout(() => {
                element.classList.remove('status-updated');
            }, 3000);
        });
    }

    updateOrderListPaymentStatus(orderId, paymentStatus, paymentStatusText) {
        // Find payment status elements in order list
        const paymentStatusElements = document.querySelectorAll(`[data-order-id="${orderId}"] .payment-status-badge, .order-${orderId} .payment-status-badge, [data-order-id="${orderId}"] .payment-status-badge`);
        
        paymentStatusElements.forEach(element => {
            // Clear and rebuild with icon
            element.innerHTML = '';
            
            // Add icon
            const icon = document.createElement('i');
            icon.className = `fa fa-${this.getPaymentStatusIcon(paymentStatus)} mr-10`;
            element.appendChild(icon);
            
            // Add text content
            element.appendChild(document.createTextNode(paymentStatusText));
            
            // Update classes
            element.className = `payment-status-badge payment-status-${paymentStatus}`;
            element.classList.add('status-updated');
            
            // Remove highlight after 3 seconds
            setTimeout(() => {
                element.classList.remove('status-updated');
            }, 3000);
        });
    }

    updateOrderDetailStatus(orderId, newStatus, statusText) {
        // Prevent multiple simultaneous updates
        if (this.isUpdatingStatus) {
            console.log('⚠️ Status update already in progress, skipping...');
            return;
        }
        this.isUpdatingStatus = true;
        // Update status in order detail page - try multiple selectors
        const selectors = [
            '.order-detail-status',
            '.order-status-display', 
            '.status-badge',
            '[class*="status-"]'
        ];
        
        let statusElement = null;
        for (const selector of selectors) {
            const elements = document.querySelectorAll(selector);
            // Find the first element that contains status text
            for (const element of elements) {
                const text = element.textContent.trim();
                if (text.includes('Chờ xử lý') || text.includes('Đang chuẩn bị') || 
                    text.includes('Đang giao') || text.includes('Đã giao hàng') || 
                    text.includes('Đã nhận hàng') || text.includes('Hoàn thành') || 
                    text.includes('Đã hủy')) {
                    statusElement = element;
                    console.log(`✅ Found status element with selector: ${selector}`);
                    break;
                }
            }
            if (statusElement) break;
        }
        
        if (statusElement) {
            console.log('🔄 Updating status element:', statusElement);
            
            // Get icon element and update it
            const iconElement = statusElement.querySelector('i');
            
            // Update icon class
            if (iconElement) {
                const iconMap = {
                    'pending': 'fa-clock-o',
                    'processing': 'fa-cogs', 
                    'shipping': 'fa-truck',
                    'delivered': 'fa-check-square-o',
                    'received': 'fa-handshake-o',
                    'completed': 'fa-check-circle',
                    'cancelled': 'fa-times-circle'
                };
                iconElement.className = `fa ${iconMap[newStatus] || 'fa-question-circle'} mr-10`;
                console.log('✅ Updated icon to:', iconElement.className);
            }
            
            // Clear existing content and rebuild properly with icon
            const iconMap = {
                'pending': 'fa-clock-o',
                'processing': 'fa-cogs', 
                'shipping': 'fa-truck',
                'delivered': 'fa-check-square-o',
                'received': 'fa-handshake-o',
                'completed': 'fa-check-circle',
                'cancelled': 'fa-times-circle'
            };
            
            statusElement.innerHTML = '';
            
            // Add icon
            const icon = document.createElement('i');
            icon.className = `fa ${iconMap[newStatus] || 'fa-question-circle'} mr-10`;
            statusElement.appendChild(icon);
            
            // Add text content
            statusElement.appendChild(document.createTextNode(statusText));
            console.log('✅ Updated status content to:', statusText);
            
            // Update classes to match order detail page
            statusElement.className = `status-badge status-${newStatus} order-detail-status`;
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
                if (text.includes('Đang giao') || text.includes('Đã giao hàng') || text.includes('Hoàn thành') || text.includes('Chờ xử lý')) {
                    console.log('  - Found element with status text:', el.tagName, el.className, text);
                }
            });
        }
        
        // Reset update flag after a short delay
        setTimeout(() => {
            this.isUpdatingStatus = false;
        }, 1000);
    }

    updateOrderDetailPaymentStatus(orderId, paymentStatus, paymentStatusText) {
        // Find payment status elements in order detail page
        const paymentStatusElements = document.querySelectorAll('.payment-status-badge, [class*="payment-status"]');
        
        paymentStatusElements.forEach(element => {
            const text = element.textContent.trim();
            if (text.includes('Chờ TT') || text.includes('Đã TT') || text.includes('Đang xử lý') || 
                text.includes('Hoàn thành') || text.includes('Thất bại') || text.includes('Hoàn tiền') || 
                text.includes('Đã hủy') || text.includes('Chờ thanh toán') || text.includes('Đã thanh toán')) {
                
                console.log('🔄 Updating payment status element:', element);
                
                // Clear existing content and rebuild
                element.innerHTML = '';
                
                // Add icon
                const icon = document.createElement('i');
                icon.className = `fa fa-${this.getPaymentStatusIcon(paymentStatus)} mr-10`;
                element.appendChild(icon);
                
                // Add text content
                element.appendChild(document.createTextNode(paymentStatusText));
                
                // Update classes
                element.className = `payment-status-badge payment-status-${paymentStatus}`;
                element.classList.add('status-updated');
                
                setTimeout(() => {
                    element.classList.remove('status-updated');
                }, 3000);
            }
        });
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
        
        // Prevent duplicate handling using order ID tracking
        if (this.processedOrders.has(data.order_id)) {
            console.log('⚠️ Order already processed, skipping duplicate');
            return;
        }
        
        // Prevent duplicate handling by checking if order already exists in DOM
        const existingOrder = document.getElementById(`order-${data.order_id}`);
        if (existingOrder) {
            console.log('⚠️ Order already exists in table, skipping duplicate');
            return;
        }
        
        // Mark order as processed
        this.processedOrders.add(data.order_id);
        
        // Show notification for new order
        this.showNewOrderMessage(data);
        
        // Show notification badge
        this.showNewOrderBadge();
        
        // Add new order to admin orders list if on admin orders page
        this.addNewOrderToList(data);
        
        // Clean up processed orders set after 10 seconds to prevent memory leaks
        setTimeout(() => {
            this.processedOrders.delete(data.order_id);
        }, 10000);
    }

    addNewOrderToList(data) {
        console.log('🔍 addNewOrderToList called with data:', data);
        
        // Check if we're on admin orders list page
        const ordersTable = document.querySelector('#ordersTable tbody');
        console.log('🔍 ordersTable found:', ordersTable);
        
        if (!ordersTable) {
            console.log('ℹ️ Not on admin orders list page');
            return;
        }

        // Double-check for existing order in table
        const existingOrder = document.getElementById(`order-${data.order_id}`);
        console.log('🔍 existingOrder found:', existingOrder);
        
        if (existingOrder) {
            console.log('⚠️ Order already exists in table, skipping add');
            return;
        }

        console.log('📋 Adding new order to admin list:', data);
        console.log('📍 Address data received:', {
            billing_address: data.billing_address,
            billing_ward: data.billing_ward,
            billing_district: data.billing_district,
            billing_city: data.billing_city
        });
        
        // Create new row HTML
        const newRow = document.createElement('tr');
        newRow.id = `order-${data.order_id}`;
        newRow.setAttribute('data-order-id', data.order_id);
        newRow.className = 'new-order-row';
        
        // Get status text and classes
        const statusText = this.getStatusText(data.status || 'pending');
        const statusClass = `status-${data.status || 'pending'}`;
        
        // Format amount
        const formattedAmount = new Intl.NumberFormat('vi-VN').format(data.total_amount || 0);
        
        // Create row content based on actual admin table structure
        newRow.innerHTML = `
            <td class="text-center">
                <strong>${data.order_code || '#' + data.order_id}</strong>
            </td>
            <td>
                <div>${data.user_name || 'Khách hàng'}</div>
                <small class="text-muted">ID: ${data.user_id || 'N/A'}</small>
            </td>
            <td>${data.receiver_name || 'Khách hàng'}</td>
            <td>${data.user_phone || ''}</td>
            <td class="address-cell">
                <div title="${data.billing_address || ''}">
                    ${(data.billing_address || '').substring(0, 25)}${(data.billing_address || '').length > 25 ? '...' : ''}
                </div>
                <small class="text-muted">
                    ${this.formatAddressDetails(data.billing_ward, data.billing_district, data.billing_city)}
                </small>
            </td>
            <td class="text-center">
                <span class="status-badge status-${data.status || 'pending'} order-detail-status">
                    <i class="fa fa-${this.getStatusIcon(data.status || 'pending')} mr-10"></i>${statusText}
                </span>
            </td>
            <td class="text-center">
                <span class="payment-status-badge payment-status-${data.payment_status || 'pending'}">
                    <i class="fa fa-${this.getPaymentStatusIcon(data.payment_status || 'pending')} mr-10"></i>${this.getPaymentStatusText(data.payment_status || 'pending')}
                </span>
            </td>
            <td class="text-end">
                <strong>${formattedAmount}₫</strong>
            </td>
            <td class="text-center">
                <div>${new Date(data.created_at).toLocaleDateString('vi-VN')}</div>
                <small class="text-muted">${new Date(data.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}</small>
            </td>
            <td class="text-center">
                <div class="btn-group" role="group">
                    <a href="/admin/order/show/${data.order_id}" class="btn btn-xs btn-info" title="Xem chi tiết">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="/admin/order/edit/${data.order_id}" class="btn btn-xs btn-warning" title="Chuyển đổi trạng thái">
                        <i class="fa fa-exchange"></i>
                    </a>
                </div>
            </td>
        `;
        
        // Add to top of table
        ordersTable.insertBefore(newRow, ordersTable.firstChild);
        
        // Add highlight effect
        newRow.style.backgroundColor = '#d4edda';
        
        // Remove highlight after 5 seconds
        setTimeout(() => {
            newRow.style.backgroundColor = '';
        }, 5000);
        
        // Update order count
        const orderCountElement = document.querySelector('.ibox-tools .badge');
        if (orderCountElement) {
            const currentCount = parseInt(orderCountElement.textContent.match(/\d+/)[0]) || 0;
            orderCountElement.textContent = `${currentCount + 1} đơn hàng`;
        }
        
        console.log('✅ New order added to admin list');
    }

    getStatusText(status) {
        const statusTexts = {
            'pending': 'Chờ xử lý',
            'processing': 'Đang chuẩn bị hàng', 
            'shipping': 'Đang giao hàng',
            'delivered': 'Đã giao hàng',
            'received': 'Đã nhận hàng',
            'completed': 'Hoàn thành',
            'cancelled': 'Đã hủy'
        };
        return statusTexts[status] || status;
    }

    getStatusIcon(status) {
        const iconMap = {
            'pending': 'clock-o',
            'processing': 'cogs', 
            'shipping': 'truck',
            'delivered': 'check-square-o',
            'received': 'handshake-o',
            'completed': 'check-circle',
            'cancelled': 'times-circle'
        };
        return iconMap[status] || 'question-circle';
    }

    getPaymentStatusText(status) {
        const statusTexts = {
            'pending': 'Chờ TT',
            'paid': 'Đã TT',
            'processing': 'Đang xử lý',
            'completed': 'Hoàn thành',
            'failed': 'Thất bại',
            'refunded': 'Hoàn tiền',
            'cancelled': 'Đã hủy'
        };
        return statusTexts[status] || 'Chờ TT';
    }

    getPaymentStatusIcon(status) {
        const iconMap = {
            'pending': 'clock-o',
            'paid': 'check-circle',
            'processing': 'cogs',
            'completed': 'check-circle',
            'failed': 'times-circle',
            'refunded': 'undo',
            'cancelled': 'ban'
        };
        return iconMap[status] || 'clock-o';
    }

    formatAddressDetails(ward, district, city) {
        const parts = [];
        
        if (ward && ward.trim()) {
            parts.push(ward.trim());
        }
        if (district && district.trim()) {
            parts.push(district.trim());
        }
        if (city && city.trim()) {
            parts.push(city.trim());
        }
        
        return parts.length > 0 ? parts.join(', ') : 'Chưa có thông tin địa chỉ';
    }

    getStatusBadgeClass(status) {
        // trả về class đồng bộ với client order detail
        return `status-badge status-${status}`;
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

    showNewOrderBadge() {
        // Show notification badge
        const badge = document.getElementById('new-order-badge');
        if (badge) {
            badge.style.display = 'inline-block';
            badge.classList.add('pulse');
            
            // Remove pulse effect after 3 seconds
            setTimeout(() => {
                badge.classList.remove('pulse');
            }, 3000);
        }
    }

    updateCancelButtonVisibility(orderId, newStatus, data = null) {
        console.log(`🔄 Updating cancel button visibility for order ${orderId}, status: ${newStatus}`);
        
        // Find cancel buttons for this order - multiple selectors to catch all possible cancel buttons
        const selectors = [
            `[data-order-id="${orderId}"] .btn-cancel-order`,
            `.cancel-order-btn[data-order-id="${orderId}"]`,
            `button[onclick*="cancelOrder(${orderId})"]`,
            `.btn-danger[onclick*="cancelOrder"]`,
            `.btn-danger[onclick*="showCancellationModal(${orderId})"]`,
            `button[onclick*="showCancellationModal(${orderId})"]`
        ];
        
        const cancelButtons = document.querySelectorAll(selectors.join(', '));
        
        console.log(`Found ${cancelButtons.length} cancel buttons for order ${orderId}`);
        
        cancelButtons.forEach((button, index) => {
            console.log(`Processing cancel button ${index + 1}:`, button);
            
            if (newStatus === 'pending') {
                // Show cancel button only when status is pending
                button.style.display = 'inline-block';
                button.disabled = false;
                button.classList.remove('d-none');
                console.log(`✅ Showing cancel button ${index + 1} for pending order`);
            } else {
                // Hide cancel button for other statuses (including processing, shipping, etc.)
                button.style.display = 'none';
                button.disabled = true;
                button.classList.add('d-none');
                console.log(`❌ Hiding cancel button ${index + 1} for non-pending order (status: ${newStatus})`);
            }
        });
        
        // Also check for cancel button in order detail page with more specific selectors
        const detailCancelButtons = document.querySelectorAll(`
            .btn-danger[onclick*="cancelOrder"], 
            .btn-danger[onclick*="showCancellationModal"], 
            #cancelOrderForm,
            .btn-action[onclick*="cancelOrder"],
            .btn-block[onclick*="cancelOrder"]
        `);
        
        detailCancelButtons.forEach((button, index) => {
            console.log(`Processing detail cancel button ${index + 1}:`, button);
            
            if (newStatus === 'pending') {
                button.style.display = 'inline-block';
                button.disabled = false;
                button.classList.remove('d-none');
                console.log(`✅ Showing detail cancel button ${index + 1} for pending order`);
            } else {
                button.style.display = 'none';
                button.disabled = true;
                button.classList.add('d-none');
                console.log(`❌ Hiding detail cancel button ${index + 1} for non-pending order (status: ${newStatus})`);
            }
        });
        
        // Hide payment options section when status is not pending
        const paymentOptions = document.querySelector('.payment-options');
        if (paymentOptions) {
            if (newStatus === 'pending') {
                paymentOptions.style.display = 'block';
                console.log('✅ Showing payment options for pending order');
            } else {
                paymentOptions.style.display = 'none';
                console.log('❌ Hiding payment options for non-pending order');
            }
        }
        
        // Show success message for status changes
        if (newStatus !== 'pending') {
            this.showStatusUpdateMessage(data);
        }
        
        // Additional check for order list page cancel buttons
        this.updateOrderListCancelButtons(orderId, newStatus);
    }
    
    updateOrderListCancelButtons(orderId, newStatus) {
        // Find cancel buttons in order list/cards
        const orderCards = document.querySelectorAll(`[data-order-id="${orderId}"], .order-card[data-order-id="${orderId}"], .order-item[data-order-id="${orderId}"]`);
        
        orderCards.forEach(card => {
            const detailSelectors = [
                '.btn-danger[onclick*="cancelOrder"]',
                '.btn-danger[onclick*="showCancellationModal"]',
                'button[onclick*="cancelOrder"]',
                'button[onclick*="showCancellationModal"]',
                '.btn-action[onclick*="cancelOrder"]'
            ];
            
            const cancelButtons = card.querySelectorAll(detailSelectors.join(', '));
            
            cancelButtons.forEach(button => {
                if (newStatus === 'pending') {
                    button.style.display = 'inline-block';
                    button.disabled = false;
                    button.classList.remove('d-none');
                    console.log(`✅ Showing order list cancel button for pending order ${orderId}`);
                } else {
                    button.style.display = 'none';
                    button.disabled = true;
                    button.classList.add('d-none');
                    console.log(`❌ Hiding order list cancel button for non-pending order ${orderId} (status: ${newStatus})`);
                }
            });
        });
    }

    // Method to clear processed orders (useful for debugging)
    clearProcessedOrders() {
        this.processedOrders.clear();
        console.log('🧹 Cleared processed orders set');
    }

    // Method to get current processed orders count (useful for debugging)
    getProcessedOrdersCount() {
        return this.processedOrders.size;
    }

    // Method to test duplicate prevention
    testDuplicatePrevention() {
        console.log('🧪 Testing duplicate prevention...');
        console.log('📊 Current processed orders count:', this.getProcessedOrdersCount());
        console.log('📋 Processed orders:', Array.from(this.processedOrders));
        
        // Test with a fake order
        const testOrder = {
            order_id: 'test-' + Date.now(),
            order_code: 'TEST-' + Date.now(),
            user_name: 'Test User',
            total_amount: 100000
        };
        
        console.log('🔄 Testing first call...');
        this.handleNewOrder(testOrder);
        
        console.log('🔄 Testing second call (should be prevented)...');
        this.handleNewOrder(testOrder);
        
        console.log('📊 Final processed orders count:', this.getProcessedOrdersCount());
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.simpleRealtimeHandler = new SimpleRealtimeHandler();
    
    // Initialize cancel button visibility based on current order statuses
    initializeCancelButtonVisibility();
});

// Function to initialize cancel button visibility on page load
function initializeCancelButtonVisibility() {
    console.log('🔍 Initializing cancel button visibility...');
    
    // Find all cancel buttons on the page
    const cancelButtons = document.querySelectorAll('.cancel-order-btn, .btn-danger[onclick*="cancelOrder"], .btn-danger[onclick*="showCancellationModal"]');
    
    cancelButtons.forEach(button => {
        const orderId = button.getAttribute('data-order-id');
        const currentStatus = button.getAttribute('data-status');
        
        if (orderId && currentStatus) {
            console.log(`🔍 Found cancel button for order ${orderId} with status: ${currentStatus}`);
            
            if (currentStatus !== 'pending') {
                // Hide button if status is not pending
                button.style.display = 'none';
                button.disabled = true;
                button.classList.add('d-none', 'cancel-button-hidden');
                console.log(`❌ Hidden cancel button for order ${orderId} (status: ${currentStatus})`);
            } else {
                // Show button if status is pending
                button.style.display = 'inline-block';
                button.disabled = false;
                button.classList.remove('d-none', 'cancel-button-hidden');
                button.classList.add('cancel-button-visible');
                console.log(`✅ Shown cancel button for order ${orderId} (status: ${currentStatus})`);
            }
        }
    });
}

// Add CSS for status updates
const style = document.createElement('style');
style.textContent = `
.status-updated {
    animation: statusUpdate 0.5s ease-in-out;
    background-color: #d4edda !important;
    border-color: #c3e6cb !important;
}

.new-order-highlight {
    animation: newOrderHighlight 0.5s ease-in-out;
    background-color: #fff3cd !important;
    border-color: #ffeaa7 !important;
    box-shadow: 0 0 10px rgba(255, 193, 7, 0.3);
}

/* Cancel button visibility transitions */
.cancel-button-hidden {
    display: none !important;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cancel-button-visible {
    display: inline-block !important;
    opacity: 1;
    transition: opacity 0.3s ease;
}

/* Animation for status changes */
@keyframes statusUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes newOrderHighlight {
    0% { 
        transform: translateY(-20px);
        opacity: 0;
        background-color: #fff3cd;
    }
    50% { 
        transform: translateY(0);
        opacity: 1;
        background-color: #fff3cd;
    }
    100% { 
        transform: translateY(0);
        opacity: 1;
        background-color: transparent;
    }
}
    0% { 
        transform: translateY(-20px);
        opacity: 0;
        background-color: #fff3cd;
    }
    50% { 
        transform: translateY(0);
        opacity: 1;
        background-color: #fff3cd;
    }
    100% { 
        transform: translateY(0);
        opacity: 1;
        background-color: transparent;
    }
}

.pulse {
    animation: pulse 1s infinite;
}

@keyframes pulse {
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