<!DOCTYPE html>
<html>
<head>
    <title>Client Order Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .connected { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .event { background: #e2e3e5; padding: 5px; margin: 5px 0; border-radius: 3px; }
        button { padding: 10px 20px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        #events { max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; }
        .debug-info { background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔧 Client Order Debug</h1>
    
    @if(auth()->check())
    <meta name="auth-user" content="{{ auth()->id() }}">
    @else
    <meta name="auth-user" content="not_logged_in">
    @endif
    
    <div class="debug-info">
        <h3>🔍 Debug Info:</h3>
        <p><strong>User ID:</strong> <span id="user-id">{{ auth()->check() ? auth()->id() : 'Not logged in' }}</span></p>
        <p><strong>Pusher Available:</strong> <span id="pusher-status">Checking...</span></p>
        <p><strong>WebSocket Status:</strong> <span id="status">⏳ Connecting...</span></p>
    </div>
    
    <div>
        <button onclick="testBroadcast()">📡 Test Broadcast</button>
        <button onclick="testSpecificOrder()">🎯 Test Specific Order</button>
        <button onclick="clearEvents()">🗑️ Clear Events</button>
    </div>
    
    <h3>📋 Events Log:</h3>
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
                addEvent('✅ Connected to WebSocket');
                console.log('✅ Connected to WebSocket');
            });
            
            pusher.connection.bind('error', (err) => {
                document.getElementById('status').textContent = '❌ Connection Error: ' + err.message;
                document.getElementById('status').className = 'status error';
                addEvent('❌ Connection error: ' + err.message);
                console.error('❌ Connection error:', err);
            });
            
            pusher.connection.bind('disconnected', () => {
                document.getElementById('status').textContent = '⚠️ Disconnected from WebSocket';
                document.getElementById('status').className = 'status error';
                addEvent('⚠️ Disconnected from WebSocket');
                console.log('⚠️ Disconnected from WebSocket');
            });
            
            // Subscribe to orders channel
            const ordersChannel = pusher.subscribe('orders');
            
            ordersChannel.bind('pusher:subscription_succeeded', () => {
                console.log('✅ Subscribed to orders channel');
                addEvent('✅ Subscribed to orders channel');
            });
            
            ordersChannel.bind('pusher:subscription_error', (error) => {
                console.error('❌ Subscription error:', error);
                addEvent('❌ Subscription error: ' + JSON.stringify(error));
            });
            
            ordersChannel.bind('OrderStatusUpdated', (data) => {
                console.log('📦 Received OrderStatusUpdated event:', data);
                addEvent('📦 OrderStatusUpdated: ' + JSON.stringify(data));
                
                // Check if this order belongs to current user or if no user is found (for testing)
                if (data.user_id == userId || userId === 'Not found' || userId === 'not_logged_in') {
                    console.log('✅ Order belongs to current user or testing mode!');
                    addEvent('✅ Order belongs to current user or testing mode - should update UI');
                    
                    // Simulate UI update
                    addEvent('🎨 Would update order ' + data.order_id + ' to ' + data.status_text);
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
        
        function testBroadcast() {
            addEvent('🔄 Testing broadcast...');
            fetch('/test-order-broadcast')
                .then(response => response.json())
                .then(data => {
                    addEvent('📡 Broadcast result: ' + JSON.stringify(data));
                })
                .catch(error => {
                    addEvent('❌ Broadcast error: ' + error.message);
                });
        }
        
        function testSpecificOrder() {
            const orderId = prompt('Enter order ID to test:');
            if (orderId) {
                addEvent('🔄 Testing specific order ' + orderId + '...');
                fetch('/test-order-broadcast/' + orderId)
                    .then(response => response.json())
                    .then(data => {
                        addEvent('📡 Specific broadcast result: ' + JSON.stringify(data));
                    })
                    .catch(error => {
                        addEvent('❌ Specific broadcast error: ' + error.message);
                    });
            }
        }
        
        function clearEvents() {
            document.getElementById('events').innerHTML = '';
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