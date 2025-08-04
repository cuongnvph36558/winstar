/**
 * Realtime Configuration
 * Control realtime features without affecting email verification
 */

window.REALTIME_CONFIG = {
    // Enable/disable realtime features
    enabled: true,
    
    // Pusher configuration
    pusher: {
        key: 'localkey123',
        cluster: 'mt1',
        wsHost: '127.0.0.1',
        wsPort: 6001,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        disabledTransports: ['xhr_streaming', 'xhr_polling']
    },
    
    // Notification settings
    notifications: {
        maxCount: 5,
        duration: 8000,
        sound: true
    },
    
    // Debug mode
    debug: false
};

// Helper function to check if realtime is enabled
window.isRealtimeEnabled = function() {
    return window.REALTIME_CONFIG && window.REALTIME_CONFIG.enabled;
};

// Helper function to get pusher config
window.getPusherConfig = function() {
    return window.REALTIME_CONFIG ? window.REALTIME_CONFIG.pusher : null;
}; 