<!DOCTYPE html>
<html>
<head>
    <title>Simple Order Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .connected { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .event { background: #e2e3e5; padding: 5px; margin: 5px 0; border-radius: 3px; }
        button { padding: 10px 20px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        #events { max-height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; }
    </style>
</head>
<body>
    <h1>🔧 Simple Order Realtime Test</h1>
    
    <div id="status" class="status">⏳ Connecting...</div>
    
    <div>
        <button onclick="testBroadcast()">📡 Test Broadcast</button>
        <button onclick="clearEvents()">🗑️ Clear Events</button>
    </div>
    
    <h3>📋 Events Log:</h3>
    <div id="events"></div>
    
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Initialize Pusher
        const pusher = new Pusher('localkey123', {
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws', 'wss']
        });
        
        // Connection events
        pusher.connection.bind('connected', () => {
            document.getElementById('status').textContent = '✅ Connected to WebSocket!';
            document.getElementById('status').className = 'status connected';
            addEvent('✅ Connected to WebSocket');
        });
        
        pusher.connection.bind('error', (err) => {
            document.getElementById('status').textContent = '❌ Connection Error: ' + err.message;
            document.getElementById('status').className = 'status error';
            addEvent('❌ Connection error: ' + err.message);
        });
        
        pusher.connection.bind('disconnected', () => {
            document.getElementById('status').textContent = '⚠️ Disconnected from WebSocket';
            document.getElementById('status').className = 'status error';
            addEvent('⚠️ Disconnected from WebSocket');
        });
        
        // Subscribe to orders channel
        const ordersChannel = pusher.subscribe('orders');
        
        ordersChannel.bind('pusher:subscription_succeeded', () => {
            addEvent('✅ Subscribed to orders channel');
        });
        
        ordersChannel.bind('pusher:subscription_error', (error) => {
            addEvent('❌ Subscription error: ' + JSON.stringify(error));
        });
        
        ordersChannel.bind('OrderStatusUpdated', (data) => {
            addEvent('📦 OrderStatusUpdated: ' + JSON.stringify(data));
        });
        
        // Test broadcast function
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