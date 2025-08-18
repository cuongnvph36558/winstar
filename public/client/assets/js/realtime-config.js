/**
 * Realtime Configuration
 * Global configuration for Pusher and realtime features
 */

// Pusher Configuration
window.PUSHER_APP_KEY = '{{ env("PUSHER_APP_KEY", "localkey123") }}';
window.PUSHER_APP_CLUSTER = '{{ env("PUSHER_APP_CLUSTER", "mt1") }}';
window.PUSHER_HOST = '{{ env("PUSHER_HOST", "127.0.0.1") }}';
window.PUSHER_PORT = {{ env("PUSHER_PORT", 6001) }};
window.PUSHER_FORCE_TLS = {{ env("PUSHER_FORCE_TLS", false) }};

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
window.REALTIME_DEBUG = {{ env("APP_DEBUG", false) ? 'true' : 'false' }};

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
    },

    // Throttle function
    throttle: function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Check if element is in viewport
    isInViewport: function(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    },

    // Play notification sound
    playSound: function(frequency = 800, duration = 300) {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(frequency * 0.8, audioContext.currentTime + duration * 0.1);
            oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime + duration * 0.2);

            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration * 0.001);

            oscillator.start();
            oscillator.stop(audioContext.currentTime + duration * 0.001);
        } catch (error) {
            realtimeError('Could not play notification sound', error);
        }
    },

    // Show desktop notification
    showDesktopNotification: function(title, message, icon = null) {
        if (!window.NOTIFICATION_SETTINGS.desktopNotifications) return;

        if (Notification.permission === 'granted') {
            new Notification(title, {
                body: message,
                icon: icon || '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'realtime-notification'
            });
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    this.showDesktopNotification(title, message, icon);
                }
            });
        }
    },

    // Add notification to list
    addNotification: function(notification) {
        if (!window.realtimeState.notifications) {
            window.realtimeState.notifications = [];
        }

        window.realtimeState.notifications.unshift(notification);
        
        // Keep only max notifications
        if (window.realtimeState.notifications.length > window.NOTIFICATION_SETTINGS.maxNotifications) {
            window.realtimeState.notifications = window.realtimeState.notifications.slice(0, window.NOTIFICATION_SETTINGS.maxNotifications);
        }

        // Save to localStorage
        try {
            localStorage.setItem('realtime_notifications', JSON.stringify(window.realtimeState.notifications));
        } catch (error) {
            realtimeError('Could not save notifications to localStorage', error);
        }
    },

    // Load notifications from localStorage
    loadNotifications: function() {
        try {
            const saved = localStorage.getItem('realtime_notifications');
            if (saved) {
                window.realtimeState.notifications = JSON.parse(saved);
            }
        } catch (error) {
            realtimeError('Could not load notifications from localStorage', error);
        }
    },

    // Clear notifications
    clearNotifications: function() {
        window.realtimeState.notifications = [];
        try {
            localStorage.removeItem('realtime_notifications');
        } catch (error) {
            realtimeError('Could not clear notifications from localStorage', error);
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    realtimeLog('Realtime configuration loaded');
    realtimeUtils.loadNotifications();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        PUSHER_APP_KEY: window.PUSHER_APP_KEY,
        PUSHER_APP_CLUSTER: window.PUSHER_APP_CLUSTER,
        REALTIME_SETTINGS: window.REALTIME_SETTINGS,
        REALTIME_CHANNELS: window.REALTIME_CHANNELS,
        REALTIME_EVENTS: window.REALTIME_EVENTS,
        NOTIFICATION_SETTINGS: window.NOTIFICATION_SETTINGS,
        realtimeUtils: window.realtimeUtils
    };
} 