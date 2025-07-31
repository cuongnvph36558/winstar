# 🔧 Realtime Notification Troubleshooting Guide

## 🚨 Common Issues & Solutions

### 1. **Notifications Not Showing**

**Symptoms:**
- No toast notifications appear
- Activity feed doesn't update
- No console errors

**Solutions:**
```bash
# 1. Check if SweetAlert2 is loaded
# Open browser console and type:
typeof Swal

# Should return "function" not "undefined"

# 2. Check if RealtimeNotifications object exists
typeof window.RealtimeNotifications

# Should return "object"

# 3. Test manual notification
window.RealtimeNotifications.showToast('success', 'Test', 'Hello World')
```

### 2. **Echo Not Connecting**

**Symptoms:**
- Console shows "Echo not initialized"
- No realtime updates
- WebSocket connection errors

**Solutions:**
```bash
# 1. Check WebSockets server is running
php artisan websockets:serve

# 2. Check queue worker is running
php artisan queue:work

# 3. Verify .env configuration
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=local-app
PUSHER_APP_KEY=localkey123
PUSHER_APP_SECRET=localsecret123
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
QUEUE_CONNECTION=database

# 4. Clear config cache
php artisan config:clear
php artisan config:cache
```

### 3. **Events Not Broadcasting**

**Symptoms:**
- Actions don't trigger notifications
- No events in WebSockets dashboard
- Console shows no broadcast events

**Solutions:**
```bash
# 1. Test broadcasting manually
php artisan test:broadcast

# 2. Check if events implement ShouldBroadcast
# All events should implement ShouldBroadcast interface

# 3. Verify channels are properly defined
# Check routes/channels.php

# 4. Check if queue is processing
# Look for queue worker output
```

### 4. **Activity Feed Not Working**

**Symptoms:**
- Notification button not visible
- Activity feed doesn't open
- No activity items added

**Solutions:**
```javascript
// 1. Check if notification elements exist
document.getElementById('realtime-notifications')
document.getElementById('activity-toggle')
document.getElementById('activity-feed')

// 2. Test activity feed manually
toggleActivityFeed()

// 3. Test adding activity item
addActivityItem({
    user_name: 'Test User',
    product_name: 'Test Product',
    action: 'added',
    timestamp: new Date().toISOString()
})
```

## 🧪 Testing Steps

### 1. **Basic Notification Test**
```javascript
// Test if SweetAlert2 works
Swal.fire({
    icon: 'success',
    title: 'Test',
    text: 'Hello World',
    toast: true,
    position: 'top-end'
})

// Test if RealtimeNotifications works
window.RealtimeNotifications.showToast('success', 'Test', 'Hello World')
```

### 2. **Echo Connection Test**
```javascript
// Check Echo status
console.log('Echo available:', typeof window.Echo !== 'undefined')
console.log('Echo connection:', window.Echo.connector.pusher.connection.state)

// Test channel subscription
window.Echo.channel('test')
    .listen('TestEvent', (e) => {
        console.log('Test event received:', e)
    })
```

### 3. **Broadcast Test**
```bash
# Test broadcasting from command line
php artisan test:broadcast

# Test broadcasting from web interface
# Visit: /test-notifications
```

## 🔍 Debug Mode

### Enable Debug Logging
```javascript
// Add to browser console
window.pusherDebug = true;
window.EchoDebug = true;

// Check for detailed logs
console.log('=== DEBUG INFO ===');
console.log('SweetAlert2:', typeof Swal);
console.log('Echo:', typeof window.Echo);
console.log('RealtimeNotifications:', typeof window.RealtimeNotifications);
console.log('Current User:', window.currentUserId);
```

### Check WebSockets Dashboard
```
URL: http://localhost:8000/laravel-websockets

Look for:
- Active connections
- Channel subscriptions
- Event logs
- Error messages
```

## 🛠️ Manual Fixes

### 1. **Force Notification Button Visibility**
```javascript
// Run in browser console
const btn = document.getElementById('activity-toggle');
if (btn) {
    btn.style.cssText = `
        position: fixed !important;
        top: 70px !important;
        right: 15px !important;
        z-index: 9999 !important;
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        background: #e74c3c !important;
        color: white !important;
        border: none !important;
        border-radius: 50% !important;
        width: 50px !important;
        height: 50px !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
    `;
}
```

### 2. **Reinitialize Echo**
```javascript
// Force reinitialize Echo
if (window.Echo) {
    window.Echo.disconnect();
    // Echo will be reinitialized on next page load
}
```

### 3. **Test Fallback Notifications**
```javascript
// Test fallback notification system
const notification = document.createElement('div');
notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: #d4edda;
    color: #155724;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10000;
`;
notification.textContent = 'Test notification';
document.body.appendChild(notification);

setTimeout(() => notification.remove(), 4000);
```

## 📋 Checklist

### Environment Setup
- [ ] `.env` has correct broadcasting settings
- [ ] WebSockets server is running (`php artisan websockets:serve`)
- [ ] Queue worker is running (`php artisan queue:work`)
- [ ] Configuration cache is cleared (`php artisan config:clear`)

### Frontend Setup
- [ ] SweetAlert2 is loaded
- [ ] Echo is initialized
- [ ] RealtimeNotifications object exists
- [ ] Notification button is visible
- [ ] Activity feed elements exist

### Backend Setup
- [ ] Events implement ShouldBroadcast
- [ ] Channels are properly defined
- [ ] Broadcasting routes are accessible
- [ ] Queue tables exist

### Testing
- [ ] Manual notifications work
- [ ] Echo connects successfully
- [ ] Broadcast events are sent
- [ ] Real-time updates work
- [ ] Activity feed functions properly

## 🆘 Emergency Fixes

### If Nothing Works
1. **Restart all services:**
   ```bash
   # Stop all processes
   # Clear all caches
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   
   # Restart services
   php artisan websockets:serve
   php artisan queue:work
   php artisan serve
   ```

2. **Check browser console for errors**
3. **Verify all dependencies are installed**
4. **Test with a fresh browser session**
5. **Check firewall/antivirus settings**

## 📞 Support

If issues persist:
1. Check browser console for errors
2. Check Laravel logs (`storage/logs/laravel.log`)
3. Check WebSockets dashboard for connection issues
4. Verify all environment variables are set correctly 