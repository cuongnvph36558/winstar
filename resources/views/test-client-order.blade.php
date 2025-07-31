<!DOCTYPE html>
<html>
<head>
    <title>Client Order Test</title>
    @if(auth()->check())
    <meta name="auth-user" content="{{ auth()->id() }}">
    @endif
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .connected { background: #d4edda; color: #155724; }
        .connecting { background: #fff3cd; color: #856404; }
        .error { background: #f8d7da; color: #721c24; }
        .event { padding: 5px; margin: 5px 0; background: #f8f9fa; border-left: 3px solid #007bff; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Client Order Realtime Test</h1>
    
    <div class="debug">
        <h3>🔧 Debug Info:</h3>
        <p><strong>Pusher Key:</strong> localkey123</p>
        <p><strong>Host:</strong> 127.0.0.1</p>
        <p><strong>Port:</strong> 6001</p>
        <p><strong>User ID:</strong> <span id="user-id">Loading...</span></p>
        <p><strong>Pusher Available:</strong> <span id="pusher-status">Loading...</span></p>
    </div>
    
    <div id="status" class="status connecting">🔄 Connecting to WebSocket...</div>
    
    <h3>📋 Received Events:</h3>
    <div id="events"></div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Debug info
        const userId = document.querySelector('meta[name="auth-user"]') ? 
            document.querySelector('meta[name="auth-user"]').getAttribute('content') : 'Not found';
        document.getElementById('user-id').textContent = userId;
        document.getElementById('pusher-status').textContent = typeof Pusher !== 'undefined' ? 'Yes' : 'No';
        
        console.log('🔧 Debug info:');
        console.log('User ID from meta:', userId);
        console.log('Pusher available:', typeof Pusher !== 'undefined');
        
        // Initialize Pusher
        try {
            const pusher = new Pusher('localkey123', {
                cluster: 'mt1',
                wsHost: '127.0.0.1',
                wsPort: 6001,
                forceTLS: false,
                disableStats: true,
                enabledTransports: ['ws', 'wss']
            });
            
            console.log('✅ Pusher initialized');
            
            // Connection events
            pusher.connection.bind('connected', () => {
                document.getElementById('status').textContent = '✅ Connected to WebSocket!';
                document.getElementById('status').className = 'status connected';
                console.log('✅ Connected to WebSocket');
            });
            
            pusher.connection.bind('error', (err) => {
                document.getElementById('status').textContent = '❌ Connection Error: ' + err.message;
                document.getElementById('status').className = 'status error';
                console.error('❌ Connection error:', err);
            });
            
            // Subscribe to orders channel
            const ordersChannel = pusher.subscribe('orders');
            
            ordersChannel.bind('pusher:subscription_succeeded', () => {
                console.log('✅ Subscribed to orders channel');
                addEvent('✅ Subscribed to orders channel');
            });
            
            ordersChannel.bind('OrderStatusUpdated', (data) => {
                console.log('📦 Received OrderStatusUpdated event:', data);
                addEvent('📦 OrderStatusUpdated: ' + JSON.stringify(data));
                
                        // Check if this order belongs to current user
        if (data.user_id == userId || userId === 'Not found') {
            console.log('✅ Order belongs to current user!');
            addEvent('✅ Order belongs to current user - should update UI');
        } else {
            console.log('❌ Order does not belong to current user');
            addEvent('❌ Order does not belong to current user');
        }
            });
            
        } catch (error) {
            console.error('❌ Failed to initialize Pusher:', error);
            document.getElementById('status').textContent = '❌ Failed to initialize Pusher: ' + error.message;
            document.getElementById('status').className = 'status error';
        }
        
        function addEvent(message) {
            const eventsEl = document.getElementById('events');
            const eventEl = document.createElement('div');
            eventEl.className = 'event';
            eventEl.innerHTML = `<strong>${new Date().toLocaleTimeString()}</strong>: ${message}`;
            eventsEl.appendChild(eventEl);
            eventsEl.scrollTop = eventsEl.scrollHeight;
        }
    </script>
</body>
</html> 