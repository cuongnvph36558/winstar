<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Realtime - Winstar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status {
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .status.connected {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.disconnected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status.connecting {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn.success {
            background: #28a745;
        }
        .btn.success:hover {
            background: #1e7e34;
        }
        .events {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            max-height: 300px;
            overflow-y: auto;
        }
        .event {
            padding: 8px;
            margin: 5px 0;
            background: white;
            border-radius: 3px;
            border-left: 4px solid #007bff;
        }
        .live-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
            margin-right: 10px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .test-section {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Test Realtime System</h1>
        
        <div class="test-section">
            <h3>📡 Connection Status</h3>
            <div id="connection-status" class="status connecting">
                <span class="live-indicator"></span>
                Đang kết nối...
            </div>
            <button class="btn" onclick="testConnection()">Test Connection</button>
        </div>

        <div class="test-section">
            <h3>🎯 Test Broadcast</h3>
            <button class="btn success" onclick="sendTestBroadcast()">Send Test Favorite Event</button>
            <button class="btn" onclick="sendCartBroadcast()">Send Test Cart Event</button>
            <button class="btn" onclick="sendOrderBroadcast()">Send Test Order Event</button>
        </div>

        <div class="test-section">
            <h3>📋 Received Events</h3>
            <div id="events" class="events">
                <div class="event">Chờ nhận events...</div>
            </div>
            <button class="btn" onclick="clearEvents()">Clear Events</button>
        </div>

        <div class="test-section">
            <h3>🔧 Debug Info</h3>
            <div id="debug-info">
                <p><strong>Pusher Key:</strong> <span id="pusher-key">Loading...</span></p>
                <p><strong>Host:</strong> <span id="pusher-host">Loading...</span></p>
                <p><strong>Port:</strong> <span id="pusher-port">Loading...</span></p>
            </div>
        </div>
    </div>

    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <script>
        let pusher;
        let eventCount = 0;

        // Initialize Pusher
        function initPusher() {
            try {
                // Get config from Laravel
                const pusherConfig = {
                    key: '{{ config("broadcasting.connections.pusher.key") }}',
                    wsHost: '{{ config("broadcasting.connections.pusher.options.host") }}',
                    wsPort: {{ config("broadcasting.connections.pusher.options.port") }},
                    forceTLS: {{ config("broadcasting.connections.pusher.options.useTLS") ? 'true' : 'false' }},
                    disableStats: true,
                    enabledTransports: ['ws', 'wss']
                };
                
                console.log('Pusher config:', pusherConfig);
                
                pusher = new Pusher(pusherConfig.key, {
                    wsHost: pusherConfig.wsHost,
                    wsPort: pusherConfig.wsPort,
                    forceTLS: pusherConfig.forceTLS,
                    disableStats: pusherConfig.disableStats,
                    enabledTransports: pusherConfig.enabledTransports
                });

                // Connection events
                pusher.connection.bind('connected', function() {
                    updateStatus('connected', '✅ Kết nối thành công!');
                    addEvent('Connected to Pusher successfully');
                });

                pusher.connection.bind('error', function(err) {
                    updateStatus('disconnected', '❌ Lỗi kết nối: ' + err.message);
                    addEvent('Connection error: ' + err.message);
                });

                pusher.connection.bind('disconnected', function() {
                    updateStatus('disconnected', '❌ Đã ngắt kết nối');
                    addEvent('Disconnected from Pusher');
                });

                // Subscribe to channels
                const favoritesChannel = pusher.subscribe('favorites');
                const cartChannel = pusher.subscribe('cart-updates');
                const ordersChannel = pusher.subscribe('orders');

                // Listen for events
                favoritesChannel.bind('FavoriteUpdated', function(data) {
                    addEvent('🔥 Favorite Updated: ' + data.user_name + ' ' + data.action + ' ' + data.product_name);
                });

                cartChannel.bind('CardUpdate', function(data) {
                    addEvent('🛒 Cart Updated: ' + data.user_name + ' ' + data.action + ' ' + data.product_name);
                });

                ordersChannel.bind('OrderStatusUpdated', function(data) {
                    addEvent('📦 Order Updated: ' + data.order_code + ' -> ' + data.status_text);
                });

                // Update debug info
                document.getElementById('pusher-key').textContent = pusherConfig.key;
                document.getElementById('pusher-host').textContent = pusherConfig.wsHost;
                document.getElementById('pusher-port').textContent = pusherConfig.wsPort;

            } catch (error) {
                updateStatus('disconnected', '❌ Lỗi khởi tạo Pusher: ' + error.message);
                addEvent('Failed to initialize Pusher: ' + error.message);
            }
        }

        function updateStatus(type, message) {
            const statusEl = document.getElementById('connection-status');
            statusEl.className = 'status ' + type;
            statusEl.innerHTML = '<span class="live-indicator"></span>' + message;
        }

        function addEvent(message) {
            const eventsEl = document.getElementById('events');
            const eventEl = document.createElement('div');
            eventEl.className = 'event';
            eventEl.innerHTML = `<strong>${new Date().toLocaleTimeString()}</strong>: ${message}`;
            eventsEl.appendChild(eventEl);
            eventsEl.scrollTop = eventsEl.scrollHeight;
            eventCount++;
        }

        function testConnection() {
            if (pusher && pusher.connection.state === 'connected') {
                addEvent('✅ Connection test successful');
            } else {
                addEvent('❌ Connection test failed');
            }
        }

        function sendTestBroadcast() {
            fetch('/test-broadcast', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    addEvent('📤 Sent test broadcast: ' + data.message);
                })
                .catch(error => {
                    addEvent('❌ Error sending broadcast: ' + error.message);
                });
        }

        function sendCartBroadcast() {
            fetch('/test-cart-broadcast', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    addEvent('📤 Sent cart broadcast: ' + data.message);
                })
                .catch(error => {
                    addEvent('❌ Error sending cart broadcast: ' + error.message);
                });
        }

        function sendOrderBroadcast() {
            fetch('/test-order-broadcast', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    addEvent('📤 Sent order broadcast: ' + data.message);
                })
                .catch(error => {
                    addEvent('❌ Error sending order broadcast: ' + error.message);
                });
        }

        function clearEvents() {
            document.getElementById('events').innerHTML = '<div class="event">Events cleared...</div>';
            eventCount = 0;
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initPusher();
        });
    </script>
</body>
</html> 