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
                
                // Prevent page unload from closing connection
                window.addEventListener('beforeunload', function() {
                    console.log('🔄 Page unloading, preserving WebSocket connection...');
                });
                
                // Keep connection alive
                setInterval(function() {
                    if (window.pusher.connection.state === 'connected') {
                        console.log('💓 Keeping WebSocket connection alive...');
                    }
                }, 30000); // Every 30 seconds
                
                console.log('🔧 Admin Pusher initialized');
            } else {
                console.error('❌ Pusher library not loaded for admin');
            }
        } catch (error) {
            console.error('❌ Failed to initialize Admin Pusher:', error);
        }
    </script>

    @stack('scripts')
</body>

</html>
