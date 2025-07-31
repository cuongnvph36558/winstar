<!DOCTYPE html>
<html>
<head>
    <title>Working Realtime Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .connected { background: #d4edda; color: #155724; }
        .connecting { background: #fff3cd; color: #856404; }
        .error { background: #f8d7da; color: #721c24; }
        .event { padding: 5px; margin: 5px 0; background: #f8f9fa; border-left: 3px solid #007bff; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🚀 Working Realtime Test</h1>
    
    <div class="debug">
        <h3>🔧 Debug Info:</h3>
        <p><strong>Pusher Key:</strong> localkey123</p>
        <p><strong>Cluster:</strong> mt1</p>
        <p><strong>Host:</strong> 127.0.0.1</p>
        <p><strong>Port:</strong> 6001</p>
        <p><strong>UseTLS:</strong> false</p>
    </div>
    
    <div id="status" class="status connecting">🔄 Connecting to WebSocket...</div>
    <button onclick="sendTest()">📤 Send Test Event</button>
    <button onclick="checkConnection()">🔍 Check Connection</button>
    <button onclick="retryConnection()">🔄 Retry Connection</button>
    
    <h3>📋 Received Events:</h3>
    <div id="events"></div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        let pusher = null;
        let connectionStatus = 'initializing';

        // Initialize Pusher
        function initPusher() {
            try {
                console.log('Initializing Pusher...');
                
                // Check if Pusher is loaded
                if (typeof Pusher === 'undefined') {
                    throw new Error('Pusher library not loaded');
                }
                
                // Create Pusher instance
                pusher = new Pusher('localkey123', {
                    cluster: 'mt1',
                    wsHost: '127.0.0.1',
                    wsPort: 6001,
                    forceTLS: false,
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                    timeout: 10000
                });

                console.log('Pusher instance created:', pusher);

                // Connection events
                pusher.connection.bind('connecting', () => {
                    connectionStatus = 'connecting';
                    updateStatus('🔄 Connecting to WebSocket...', 'connecting');
                    console.log('🔄 Connecting...');
                });

                pusher.connection.bind('connected', () => {
                    connectionStatus = 'connected';
                    updateStatus('✅ Connected to WebSocket!', 'connected');
                    console.log('✅ Connected successfully!');
                });

                pusher.connection.bind('error', (err) => {
                    connectionStatus = 'error';
                    updateStatus('❌ Connection Error: ' + (err.message || 'Unknown error'), 'error');
                    console.error('❌ Connection error:', err);
                });

                pusher.connection.bind('disconnected', () => {
                    connectionStatus = 'disconnected';
                    updateStatus('❌ Disconnected from WebSocket', 'error');
                    console.log('❌ Disconnected');
                });

                // Subscribe to channels
                const favoritesChannel = pusher.subscribe('favorites');
                
                favoritesChannel.bind('FavoriteUpdated', (data) => {
                    addEvent('🔥 Favorite Updated: ' + JSON.stringify(data));
                    console.log('🔥 Received FavoriteUpdated event:', data);
                });

                console.log('Pusher initialized successfully');
                
            } catch (error) {
                connectionStatus = 'error';
                const errorMessage = error.message || 'Unknown error occurred';
                updateStatus('❌ Failed to initialize Pusher: ' + errorMessage, 'error');
                console.error('❌ Failed to initialize Pusher:', error);
            }
        }

        function updateStatus(message, className) {
            const statusEl = document.getElementById('status');
            statusEl.textContent = message;
            statusEl.className = 'status ' + className;
        }

        function addEvent(message) {
            const eventsEl = document.getElementById('events');
            const eventEl = document.createElement('div');
            eventEl.className = 'event';
            eventEl.innerHTML = `<strong>${new Date().toLocaleTimeString()}</strong>: ${message}`;
            eventsEl.appendChild(eventEl);
            eventsEl.scrollTop = eventsEl.scrollHeight;
        }

        function checkConnection() {
            if (pusher) {
                const state = pusher.connection.state;
                console.log('Current connection state:', state);
                updateStatus(`🔍 Connection State: ${state}`, state === 'connected' ? 'connected' : 'error');
            } else {
                updateStatus('❌ Pusher not initialized', 'error');
            }
        }

        function retryConnection() {
            console.log('Retrying connection...');
            if (pusher) {
                pusher.disconnect();
            }
            setTimeout(() => {
                initPusher();
            }, 1000);
        }

        function sendTest() {
            if (connectionStatus !== 'connected') {
                updateStatus('❌ Not connected to WebSocket', 'error');
                return;
            }

            updateStatus('📤 Sending test event...', 'connecting');
            
            fetch('/test-broadcast', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateStatus('📤 Event sent: ' + data.message, 'connected');
                console.log('📤 Event sent successfully:', data);
            })
            .catch(error => {
                updateStatus('❌ Send Error: ' + error.message, 'error');
                console.error('❌ Send error:', error);
            });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing Pusher...');
            setTimeout(() => {
                initPusher();
            }, 500);
        });
    </script>
</body>
</html> 