<!DOCTYPE html>
<html>
<head>
    <title>Debug Realtime Test</title>
</head>
<body>
    <h1>Debug Realtime Test</h1>
    
    <div id="status">Initializing...</div>
    <div id="config"></div>
    <div id="events"></div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Debug config
        const config = {
            key: '{{ config("broadcasting.connections.pusher.key") }}',
            host: '{{ config("broadcasting.connections.pusher.options.host") }}',
            port: {{ config("broadcasting.connections.pusher.options.port") }},
            useTLS: {{ config("broadcasting.connections.pusher.options.useTLS") ? 'true' : 'false' }}
        };
        
        document.getElementById('config').innerHTML = `
            <h3>Config:</h3>
            <p>Key: ${config.key}</p>
            <p>Host: ${config.host}</p>
            <p>Port: ${config.port}</p>
            <p>UseTLS: ${config.useTLS}</p>
        `;
        
        console.log('Pusher config:', config);
        
        try {
            const pusher = new Pusher(config.key, {
                wsHost: config.host,
                wsPort: config.port,
                forceTLS: config.useTLS,
                disableStats: true,
                enabledTransports: ['ws', 'wss']
            });
            
            console.log('Pusher instance created');
            
            pusher.connection.bind('connecting', () => {
                document.getElementById('status').innerHTML = '🔄 Connecting...';
                console.log('Connecting...');
            });
            
            pusher.connection.bind('connected', () => {
                document.getElementById('status').innerHTML = '✅ Connected!';
                console.log('Connected!');
            });
            
            pusher.connection.bind('error', (err) => {
                document.getElementById('status').innerHTML = '❌ Error: ' + err.message;
                console.error('Connection error:', err);
            });
            
            pusher.connection.bind('disconnected', () => {
                document.getElementById('status').innerHTML = '❌ Disconnected';
                console.log('Disconnected');
            });
            
            const channel = pusher.subscribe('favorites');
            channel.bind('FavoriteUpdated', (data) => {
                const events = document.getElementById('events');
                events.innerHTML += '<div>' + new Date().toLocaleTimeString() + ': ' + JSON.stringify(data) + '</div>';
                console.log('Received event:', data);
            });
            
        } catch (error) {
            document.getElementById('status').innerHTML = '❌ Failed to create Pusher: ' + error.message;
            console.error('Failed to create Pusher:', error);
        }
    </script>
</body>
</html> 