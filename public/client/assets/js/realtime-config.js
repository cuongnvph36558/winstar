/**
 * Realtime Configuration
 * Global configuration for Pusher and realtime features
 */

// Pusher Configuration
window.PUSHER_APP_KEY = 'localkey123';
window.PUSHER_APP_CLUSTER = 'mt1';
window.PUSHER_HOST = '127.0.0.1';
window.PUSHER_PORT = 6001;
window.PUSHER_FORCE_TLS = false;

// Realtime Settings
window.REALTIME_SETTINGS = {
    enabled: true,
    reconnectAttempts: 5,
    reconnectInterval: 5000,
    activityTimeout: 30000,
    pongTimeout: 15000,
    maxReconnectGap: 5000,
    notificationSound: true,
    autoReconnect: true
};

// Channel Names
window.REALTIME_CHANNELS = {
    ORDERS: 'orders',
    ADMIN_ORDERS: 'admin.orders',
    ADMIN_NOTIFICATIONS: 'admin.notifications',
    USER_PREFIX: 'private-user.',
    ORDER_PREFIX: 'private-order.'
};

// Event Names
window.REALTIME_EVENTS = {
    ORDER_STATUS_UPDATED: 'App\\Events\\OrderStatusUpdated',
    ORDER_RECEIVED_CONFIRMED: 'OrderReceivedConfirmed',
    NEW_ORDER_PLACED: 'App\\Events\\NewOrderPlaced',
    ORDER_CANCELLED: 'App\\Events\\OrderCancelled'
};

// Notification Settings
window.NOTIFICATION_SETTINGS = {
    maxNotifications: 10,
    autoHideDelay: 10000,
    soundEnabled: true,
    desktopNotifications: true,
    priorityLevels: {
        low: 1,
        normal: 2,
        high: 3,
        urgent: 4
    }
};

// Debug Mode
window.REALTIME_DEBUG = false;

// Log function for debugging
window.realtimeLog = function(message, data = null) {
    if (window.REALTIME_DEBUG) {
        console.log(`[Realtime] ${message}`, data);
    }
};

// Error logging
window.realtimeError = function(message, error = null) {
    console.error(`[Realtime Error] ${message}`, error);
};

// Success logging
window.realtimeSuccess = function(message, data = null) {
    console.log(`[Realtime Success] ${message}`, data);
};

// Initialize global realtime state
window.realtimeState = {
    connected: false,
    lastConnectionTime: null,
    reconnectCount: 0,
    notifications: [],
    channels: new Set()
};

// Utility functions
window.realtimeUtils = {
    // Format timestamp
    formatTime: function(date) {
        const now = new Date();
        const diff = now - new Date(date);
        
        if (diff < 60000) return 'Vừa xong';
        if (diff < 3600000) return `${Math.floor(diff / 60000)} phút trước`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} giờ trước`;
        return new Date(date).toLocaleDateString('vi-VN');
    },

    // Generate unique ID
    generateId: function() {
        return Date.now() + Math.random().toString(36).substr(2, 9);
    },

    // Check if element exists
    elementExists: function(selector) {
        return document.querySelector(selector) !== null;
    },

    // Safe JSON parse
    safeJsonParse: function(str, defaultValue = null) {
        try {
            return JSON.parse(str);
        } catch (e) {
            return defaultValue;
        }
    },

    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // window.realtimeLog('Configuration loaded');
}); 