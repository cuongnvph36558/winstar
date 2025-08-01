<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Winstar | @yield('title')</title>

    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <!-- FooTable -->
    <link href="{{ asset('admin/css/plugins/footable/footable.core.css') }}" rel="stylesheet">

    <!-- DatePicker -->
    <link href="{{ asset('admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

    <link href="{{ asset('admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/et-line-font/et-line-font.css') }}" rel="stylesheet">


</head>

<body>

    <div id="wrapper">

        @include('admin.partials.sidebar')

        <div id="page-wrapper" class="gray-bg">
            {{-- Header --}}
            @include('admin.partials.header')

            {{-- Content --}}
            @yield('content')

            {{-- Footer --}}
            @include('admin.partials.footer')

        </div>
    </div>



    <!-- Mainly scripts -->
    <script src="{{ asset('admin/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('admin/js/inspinia.js') }}"></script>
    <script src="{{ asset('admin/js/plugins/pace/pace.min.js') }}"></script>

    <!-- FooTable -->
    <script src="{{ asset('admin/js/plugins/footable/footable.all.min.js') }}"></script>

    <!-- DatePicker -->
    <script src="{{ asset('admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

    <!-- Page-Level Scripts -->
    <script>
        // Ensure jQuery is loaded
        if (typeof $ !== 'undefined') {
            $(document).ready(function () {
                $('.footable').footable();
            });
        } else {
            console.error('❌ jQuery not loaded in admin layout');
        }
    </script>

    <!-- Pusher for Realtime -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Initialize Pusher for Admin
        try {
            if (typeof Pusher !== 'undefined') {
                window.pusher = new Pusher('localkey123', {
                    cluster: 'mt1',
                    wsHost: '127.0.0.1',
                    wsPort: 6001,
                    forceTLS: false,
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                    // Persistent connection settings
                    activityTimeout: 30000,
                    pongTimeout: 15000,
                    maxReconnectionAttempts: 10,
                    maxReconnectGap: 5000
                });

                // Connection events
                window.pusher.connection.bind('connected', function() {
                    console.log('✅ Admin WebSocket connected successfully!');
                });

                window.pusher.connection.bind('error', function(err) {
                    console.error('❌ Admin WebSocket connection error:', err);
                });

                window.pusher.connection.bind('disconnected', function() {
                    console.log('⚠️ Admin WebSocket disconnected, attempting to reconnect...');
                    setTimeout(function() {
                        console.log('🔄 Admin attempting to reconnect...');
                        window.pusher.connect();
                    }, 1000);
                });

                // Subscribe to all realtime channels for admin
                console.log('🔧 Admin subscribing to realtime channels...');

                // Orders channel
                const ordersChannel = window.pusher.subscribe('orders');
                ordersChannel.bind('OrderStatusUpdated', function(data) {
                    console.log('📦 Order status updated:', data);
                    showAdminNotification(data.message, 'info');
                });

                // Favorites channel
                const favoritesChannel = window.pusher.subscribe('favorites');
                favoritesChannel.bind('FavoriteUpdated', function(data) {
                    console.log('❤️ Favorite updated:', data);
                    showAdminNotification(data.message, 'success');
                });

                // Cart updates channel
                const cartChannel = window.pusher.subscribe('cart-updates');
                cartChannel.bind('CardUpdate', function(data) {
                    console.log('🛒 Cart updated:', data);
                    showAdminNotification(data.message, 'info');
                });

                // Comments channel
                const commentsChannel = window.pusher.subscribe('comments');
                commentsChannel.bind('CommentAdded', function(data) {
                    console.log('💬 Comment added:', data);
                    showAdminNotification(data.message, 'success');
                });

                // Product stock channel
                const stockChannel = window.pusher.subscribe('product-stock');
                stockChannel.bind('ProductStockUpdated', function(data) {
                    console.log('📦 Stock updated:', data);
                    showAdminNotification(data.message, 'warning');
                });

                // User activity channel
                const activityChannel = window.pusher.subscribe('user-activity');
                activityChannel.bind('UserActivity', function(data) {
                    console.log('👤 User activity:', data);
                    showAdminNotification(data.message, 'info');
                });

                console.log('✅ Admin realtime channels subscribed successfully!');

            } else {
                console.error('❌ Pusher library not loaded for admin');
            }
        } catch (error) {
            console.error('❌ Failed to initialize Admin Pusher:', error);
        }

        // Admin notification function
        function showAdminNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible admin-realtime-notification`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 350px;
                max-width: 500px;
                animation: slideInRight 0.5s ease-out;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border: none;
            `;

            notification.innerHTML = `
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>🔔 Client Activity:</strong> ${message}
            `;

            document.body.appendChild(notification);

            // Auto remove after 8 seconds
            setTimeout(function() {
                if (notification.parentNode) {
                    notification.style.animation = 'slideOutRight 0.5s ease-out';
                    setTimeout(function() {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 500);
                }
            }, 8000);
        }

        // Add CSS animations for admin
        const adminStyle = document.createElement('style');
        adminStyle.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .admin-realtime-notification {
                font-size: 14px;
                padding: 15px 20px;
            }
        `;
        document.head.appendChild(adminStyle);
    </script>

    @stack('scripts')
</body>

</html>
