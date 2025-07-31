<!DOCTYPE html>
<html lang="en-US" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="auth-user" content="{{ auth()->user()->id }}">
    @endauth
    <!--  
    Document Title
    =============================================
    -->
    <title>Winstar | @yield('title')</title>
    <!--  
    Favicons
    =============================================
    -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('client/assets/images/favicons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('client/assets/images/favicons/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('client/assets/images/favicons/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('client/assets/images/favicons/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('client/assets/images/favicons/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('client/assets/images/favicons/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('client/assets/images/favicons/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('client/assets/images/favicons/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('client/assets/images/favicons/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('client/assets/images/favicons/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('client/assets/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('client/assets/images/favicons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('client/assets/images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('client/assets/images/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!--  
    Stylesheets
    =============================================
    
    -->
    <!-- Default stylesheets-->
    <link href="{{ asset('client/assets/lib/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Template specific stylesheets-->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Volkhov:400i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/animate.css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/components-font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/et-line-font/et-line-font.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/flexslider/flexslider.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/owl.carousel/dist/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/owl.carousel/dist/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/magnific-popup/dist/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/simple-text-rotator/simpletextrotator.css') }}" rel="stylesheet">
    <!-- Main stylesheet and color file-->
    <link href="{{ asset('client/assets/css/style.css') }}" rel="stylesheet">
    <!-- Navbar spacing to prevent content overlap -->
    <link href="{{ asset('client/assets/css/navbar-spacing.css') }}" rel="stylesheet">
    <!-- Banner background styles -->
    <link href="{{ asset('client/assets/css/dark-banner-background.css') }}" rel="stylesheet">
    <link id="color-scheme" href="{{ asset('client/assets/css/colors/default.css') }}" rel="stylesheet">
    <!-- Sharp images CSS -->
    <link href="{{ asset('client/assets/css/sharp-images.css') }}" rel="stylesheet">
    <!-- Thêm FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Page specific styles -->
    @yield('styles')
  </head>
  <body data-spy="scroll" data-target=".onpage-navigation" data-offset="60" @auth class="authenticated" @endauth>
    <main>
        {{-- Navbar --}}
      @include('client.partials.navbar')
      
      <div class="main">
        {{-- Session Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="margin: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" style="margin: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible" style="margin: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(23, 162, 184, 0.15);">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <i class="fa fa-info-circle"></i> {{ session('info') }}
        </div>
        @endif

        {{-- Content --}}
        @yield('content')
        {{-- Footer --}}
        @include('client.partials.footer')
        
        {{-- Đã xóa realtime-notifications để tránh xung đột Echo --}}
      </div>
      <div class="scroll-up"><a href="#totop"><i class="fa fa-angle-double-up"></i></a></div>
    </main>
    <!--  
    JavaScripts
    =============================================
    -->
    <script src="{{ asset('client/assets/lib/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('client/assets/lib/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('client/assets/lib/wow/dist/wow.js') }}"></script>
    <script src="{{ asset('client/assets/lib/jquery.mb.ytplayer/dist/jquery.mb.YTPlayer.js') }}"></script>
    <script src="{{ asset('client/assets/lib/isotope/dist/isotope.pkgd.js') }}"></script>
    <script src="{{ asset('client/assets/lib/imagesloaded/imagesloaded.pkgd.js') }}"></script>
    <script src="{{ asset('client/assets/lib/flexslider/jquery.flexslider.js') }}"></script>
    <script src="{{ asset('client/assets/lib/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('client/assets/lib/smoothscroll.js') }}"></script>
    <script src="{{ asset('client/assets/lib/magnific-popup/dist/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('client/assets/lib/simple-text-rotator/jquery.simple-text-rotator.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('client/assets/js/main.js') }}"></script>
    <script src="{{ asset('client/assets/js/favorites.js') }}"></script>
    <script src="{{ asset('client/assets/js/favorites-init.js') }}"></script>
    <script src="{{ asset('client/assets/js/banner-effects.js') }}"></script>
    
    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency={{ config('paypal.currency') }}"></script>
    <!-- Pusher for realtime features -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
      // Pusher config from PHP
      window.pusherConfig = {
        key: '{{ config("broadcasting.connections.pusher.key") }}',
        host: '{{ config("broadcasting.connections.pusher.options.host") }}',
        port: {{ config("broadcasting.connections.pusher.options.port") }},
        useTLS: {{ config("broadcasting.connections.pusher.options.useTLS") ? 'true' : 'false' }}
      };
      
      try {
        // Initialize Pusher with same config as debug page
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
          console.log('✅ WebSocket connected successfully!');
        });
        
        window.pusher.connection.bind('error', function(err) {
          console.error('❌ WebSocket connection error:', err);
        });
        
        window.pusher.connection.bind('disconnected', function() {
          console.log('⚠️ WebSocket disconnected, attempting to reconnect...');
          setTimeout(function() {
            console.log('🔄 Attempting to reconnect...');
            window.pusher.connect();
          }, 1000);
        });
        
        // Subscribe to all realtime channels
        console.log('🔧 Subscribing to realtime channels...');
        
        // Orders channel
        const ordersChannel = window.pusher.subscribe('orders');
        ordersChannel.bind('OrderStatusUpdated', function(data) {
          console.log('📦 Order status updated:', data);
          location.reload();
        });
        
        // Favorites channel
        const favoritesChannel = window.pusher.subscribe('favorites');
        favoritesChannel.bind('FavoriteUpdated', function(data) {
          console.log('❤️ Favorite updated:', data);
          location.reload();
        });
        
        // Cart updates channel
        const cartChannel = window.pusher.subscribe('cart-updates');
        cartChannel.bind('CardUpdate', function(data) {
          console.log('🛒 Cart updated:', data);
          location.reload();
        });
        
        // Comments channel
        const commentsChannel = window.pusher.subscribe('comments');
        commentsChannel.bind('CommentAdded', function(data) {
          console.log('💬 Comment added:', data);
          location.reload();
        });
        
        // Product stock channel
        const stockChannel = window.pusher.subscribe('product-stock');
        stockChannel.bind('ProductStockUpdated', function(data) {
          console.log('📦 Stock updated:', data);
          location.reload();
        });
        
        // User activity channel
        const activityChannel = window.pusher.subscribe('user-activity');
        activityChannel.bind('UserActivity', function(data) {
          console.log('👤 User activity:', data);
          location.reload();
        });
        
        // Product specific channels
        if (window.currentProductId) {
          const productChannel = window.pusher.subscribe('product.' + window.currentProductId);
          productChannel.bind('CommentAdded', function(data) {
            console.log('💬 Product comment added:', data);
            location.reload();
          });
          productChannel.bind('ProductStockUpdated', function(data) {
            console.log('📦 Product stock updated:', data);
            location.reload();
          });
        }
        
        console.log('✅ All realtime channels subscribed successfully!');
        
      } catch (error) {
        console.error('❌ Failed to initialize Pusher:', error);
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Auto hide session messages --}}
    <script>
    $(document).ready(function() {
        // Auto hide session alerts after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
        
        // Hide session alerts when AJAX favorite actions are successful
        $(document).on('favoriteActionSuccess', function() {
            $('.alert-dismissible').fadeOut(300, function() {
                $(this).remove();
            });
        });
        
        // Also hide alerts when any AJAX request completes successfully
        $(document).ajaxSuccess(function(event, xhr, settings) {
            // Only for favorite-related URLs
            if (settings.url && settings.url.includes('/favorite')) {
                setTimeout(function() {
                    $('.alert-dismissible').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 500); // Slight delay to let AJAX toast show first
            }
        });
    });
    </script>
    
    @yield('scripts')
  </body>
</html>