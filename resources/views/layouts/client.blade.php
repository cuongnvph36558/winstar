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
    <link id="color-scheme" href="{{ asset('client/assets/css/colors/default.css') }}" rel="stylesheet">
    <!-- Thêm FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Page specific styles -->
    @yield('styles')
  </head>
  <body data-spy="scroll" data-target=".onpage-navigation" data-offset="60" @auth class="authenticated" @endauth>
    <!-- ĐẢM BẢO JQUERY LUÔN LÀ SCRIPT ĐẦU TIÊN TRONG BODY -->
    <script src="{{ asset('client/assets/lib/jquery/dist/jquery.js') }}"></script>
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
        
        {{-- Realtime Notifications --}}
        @include('client.partials.realtime-notifications')
        
        <script>
        console.log('🔍 Checking if realtime-notifications is loaded...');
        console.log('🔍 activity-list element:', document.getElementById('activity-list'));
        console.log('🔍 activity-toggle element:', document.getElementById('activity-toggle'));
        </script>
      </div>
      <div class="scroll-up"><a href="#totop"><i class="fa fa-angle-double-up"></i></a></div>
    </main>
    <!--  
    JavaScripts
    =============================================
    -->
    <!-- ĐÃ ĐƯỢC ĐƯA LÊN ĐẦU BODY
    <script src="{{ asset('client/assets/lib/jquery/dist/jquery.js') }}"></script>
    -->
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
    
    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency={{ config('paypal.currency') }}"></script>
    <!-- Pusher and Laravel Echo for realtime features -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Realtime Setup -->
    <script>
    // Debug mode
    window.pusherDebug = true;
    
    // Setup Echo for realtime broadcasting
    try {
        const broadcastDriver = '{{ config('broadcasting.default') }}';
        console.log('Broadcast driver:', broadcastDriver);
        
        @auth
        window.currentUserId = {{ auth()->user()->id }};
        @else
        window.currentUserId = null;
        @endauth
        
        if (broadcastDriver === 'pusher') {
            const pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
            const pusherHost = '{{ config('broadcasting.connections.pusher.options.host') }}';
            const pusherPort = Number('{{ config('broadcasting.connections.pusher.options.port') }}') || 6001;
            const pusherScheme = '{{ config('broadcasting.connections.pusher.options.scheme') }}';
            
            if (!pusherKey) {
                console.warn('⚠️ Pusher key is missing! Realtime will not work.');
            }
            if (pusherKey) {
                // Full Pusher setup for local WebSockets
                @auth
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: pusherKey,
                    cluster: 'mt1',
                    wsHost: pusherHost || '127.0.0.1',
                    wsPort: pusherPort || 6001,
                    wssPort: pusherPort || 6001,
                    forceTLS: false,
                    encrypted: false,
                    enabledTransports: ['ws', 'wss'],
                    disableStats: true,
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    authEndpoint: '/broadcasting/auth'
                });
                console.log('Echo initialized with local WebSockets for authenticated user:', window.currentUserId);
                @else
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: pusherKey,
                    cluster: 'mt1',
                    wsHost: pusherHost || '127.0.0.1',
                    wsPort: pusherPort || 6001,
                    wssPort: pusherPort || 6001,
                    forceTLS: false,
                    encrypted: false,
                    enabledTransports: ['ws', 'wss'],
                    disableStats: true
                });
                console.log('Echo initialized with local WebSockets for guest user');
                @endauth
                
                // Test connection
                window.Echo.connector.pusher.connection.bind('connected', function() {
                    console.log('✅ Pusher connected successfully!');
                    console.log('🔍 Connection state:', window.Echo.connector.pusher.connection.state);
                    console.log('🔍 Socket ID:', window.Echo.connector.pusher.connection.socket_id);
                    
                    // Trigger setup of listeners when connected
                    if (typeof setupEchoListeners === 'function') {
                        console.log('🎧 Triggering Echo listeners setup...');
                        setupEchoListeners();
                    }
                });
                
                window.Echo.connector.pusher.connection.bind('error', function(err) {
                    console.error('❌ Pusher connection error:', err);
                });
                
                window.Echo.connector.pusher.connection.bind('disconnected', function() {
                    console.warn('⚠️ Pusher disconnected');
                });
                
                window.Echo.connector.pusher.connection.bind('reconnected', function() {
                    console.log('🔄 Pusher reconnected');
                });
            } else {
                console.warn('⚠️ Pusher keys not configured, using mock Echo for testing');
                window.Echo = createMockEcho();
            }
        } else {
            console.log('⚠️ Using mock Echo for non-Pusher broadcasting (testing mode)');
            window.Echo = createMockEcho();
        }
        
    } catch (error) {
        console.error('❌ Failed to initialize Echo:', error);
        window.Echo = createMockEcho();
    }
    
    // Mock Echo for testing when Pusher is not available
    function createMockEcho() {
        return {
            channel: function(channelName) {
                console.log('📺 Mock Echo: Listening on channel:', channelName);
                return {
                    listen: function(eventName, callback) {
                        console.log('👂 Mock Echo: Listening for event:', eventName);
                        
                        // For testing, we can simulate events
                        window.mockBroadcast = function(eventName, data) {
                            console.log('🎭 Mock broadcast:', eventName, data);
                            callback(data);
                        };
                        
                        return this;
                    },
                    error: function(errorCallback) {
                        console.log('🚨 Mock Echo: Error handler registered');
                        return this;
                    }
                };
            },
            private: function(channelName) {
                return this.channel(channelName);
            },
            connector: {
                pusher: {
                    connection: {
                        bind: function(event, callback) {
                            console.log('🔗 Mock Echo: Connection event:', event);
                            if (event === 'connected') {
                                setTimeout(callback, 100); // Simulate connection
                            }
                        }
                    }
                }
            }
        };
    }
    
    // Setup realtime notification system
    window.RealtimeNotifications = {
        showToast: function(type, title, message) {
            try {
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'realtime-toast'
                        },
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    
                    Toast.fire({
                        icon: type,
                        title: title,
                        text: message
                    });
                } else {
                    console.warn('SweetAlert2 not available, using fallback notification');
                    // Fallback notification
                    const notification = document.createElement('div');
                    notification.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
                        color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
                        padding: 15px 20px;
                        border-radius: 8px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        z-index: 10000;
                        max-width: 300px;
                        font-size: 14px;
                        border-left: 4px solid ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
                    `;
                    notification.innerHTML = `<strong>${title}</strong><br>${message}`;
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 4000);
                }
            } catch (error) {
                console.error('Error showing toast notification:', error);
                alert(`${title}: ${message}`);
            }
        },
        
        updateFavoriteCount: function(productId, newCount) {
            try {
                // Update favorite count displays
                const countElements = document.querySelectorAll(`[data-product-id="${productId}"] .favorite-count, .product-${productId}-favorites`);
                countElements.forEach(el => {
                    el.textContent = newCount;
                    el.classList.add('updated');
                    setTimeout(() => el.classList.remove('updated'), 600);
                });
                
                // Refresh navbar counts if available
                if (window.refreshFavoriteCount) {
                    window.refreshFavoriteCount();
                }
            } catch (error) {
                console.error('Error updating favorite count:', error);
            }
        }
    };
    </script>
    
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