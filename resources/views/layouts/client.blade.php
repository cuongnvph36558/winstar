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
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}">
    <meta name="msapplication-TileColor" content="#231C30">
    <meta name="theme-color" content="#231C30">
    <!--  
    Stylesheets
    =============================================
    
    -->
    <!-- Default stylesheets-->
    <link href="{{ asset('client/assets/lib/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Template specific stylesheets-->
    <link href="{{ asset("assets/external/fonts/roboto-condensed.css") }}" rel="stylesheet">
    <link href="{{ asset("assets/external/fonts/volkhov.css") }}" rel="stylesheet">
    <link href="{{ asset("assets/external/fonts/open-sans.css") }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/animate.css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/components-font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/et-line-font/et-line-font.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/magnific-popup/dist/magnific-popup.css') }}" rel="stylesheet">

    <!-- Main stylesheet and color file-->
    <link href="{{ asset('client/assets/css/style.css') }}" rel="stylesheet">
    <!-- Navbar spacing to prevent content overlap -->
    <link href="{{ asset('client/assets/css/navbar-spacing.css') }}" rel="stylesheet">
    <!-- Banner background styles -->
    <link href="{{ asset('client/assets/css/dark-banner-background.css') }}" rel="stylesheet">
    <link id="color-scheme" href="{{ asset('client/assets/css/colors/default.css') }}" rel="stylesheet">
    <!-- Sharp images CSS -->
    <link href="{{ asset('client/assets/css/sharp-images.css') }}" rel="stylesheet">
    <!-- Enhanced Product Detail CSS -->
    <link href="{{ asset('client/assets/css/product-detail-enhanced.css') }}" rel="stylesheet">
    <!-- Letter Spacing Adjustments -->
    <link href="{{ asset('client/assets/css/letter-spacing-adjustments.css') }}" rel="stylesheet">
    <!-- Thêm FontAwesome -->
    <link rel="stylesheet" href="{{ asset("assets/external/css/font-awesome.min.css") }}">
    <!-- Font Awesome 5 for new icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Toast Notification CSS -->
    <link href="{{ asset('css/toast.css') }}" rel="stylesheet">
    
    <!-- Chatbot CSS -->
    <link href="{{ asset('css/chatbot.css') }}" rel="stylesheet">
    
    <!-- Inline CSS for chatbot testing -->
    <style>
    .chat-widget-toggle {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        width: 60px !important;
        height: 60px !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3) !important;
        transition: transform 0.3s, box-shadow 0.3s !important;
        z-index: 9999 !important;
        font-size: 24px !important;
    }
    
    .chat-widget {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        width: 350px !important;
        height: 500px !important;
        background: #fff !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
        display: none !important;
        flex-direction: column !important;
        z-index: 10000 !important;
        border: 1px solid #e0e0e0 !important;
        overflow: hidden !important;
    }
    
    .chat-widget.active {
        display: flex !important;
    }
    </style>
    
    <!-- Page specific styles -->
    @yield('styles')
    
    <!-- Ẩn scrollbar toàn bộ website -->
    <style>
    /* Ẩn scrollbar cho tất cả trình duyệt */
    html, body {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* Internet Explorer 10+ */
    }
    
    /* Ẩn scrollbar cho Webkit browsers (Chrome, Safari, Edge) */
    html::-webkit-scrollbar,
    body::-webkit-scrollbar,
    *::-webkit-scrollbar {
        display: none;
    }
    
    /* Đảm bảo scroll vẫn hoạt động */
    html, body {
        overflow-x: hidden;
        overflow-y: auto;
    }
    
    /* Ẩn scrollbar cho tất cả elements có scroll */
    .main::-webkit-scrollbar,
    .container::-webkit-scrollbar,
    .row::-webkit-scrollbar,
    .col::-webkit-scrollbar,
    .col-sm::-webkit-scrollbar,
    .col-md::-webkit-scrollbar,
    .col-lg::-webkit-scrollbar,
    .col-xl::-webkit-scrollbar,
    .module::-webkit-scrollbar,
    .section::-webkit-scrollbar,
    .content::-webkit-scrollbar,
    
    /* Loại trừ chatbot khỏi việc ẩn scrollbar */
    .chat-widget-messages::-webkit-scrollbar {
        display: block !important;
        width: 8px !important;
    }
    
    .chat-widget-messages::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 4px !important;
    }
    
    .chat-widget-messages::-webkit-scrollbar-thumb {
        background: #c1c1c1 !important;
        border-radius: 4px !important;
    }
    
    .chat-widget-messages::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8 !important;
    }
    
    /* Đảm bảo chatbot có thể scroll */
    .chat-widget-messages {
        overflow-y: scroll !important;
        scrollbar-width: thin !important;
        scrollbar-color: #c1c1c1 #f1f1f1 !important;
    }
    .sidebar::-webkit-scrollbar,
    .checkout-section::-webkit-scrollbar,
    .checkout-main::-webkit-scrollbar,
    .checkout-sidebar::-webkit-scrollbar,
    .product-carousel::-webkit-scrollbar,
    #productCarousel::-webkit-scrollbar {
        display: none;
    }
    
    /* Firefox cho tất cả elements */
    .main,
    .container,
    .row,
    .col,
    .col-sm,
    .col-md,
    .col-lg,
    .col-xl,
    .module,
    .section,
    .content,
    .sidebar,
    .checkout-section,
    .checkout-main,
    .checkout-sidebar,
    .product-carousel,
    #productCarousel {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    /* Đảm bảo tất cả elements có scroll đều ẩn scrollbar */
    * {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    *::-webkit-scrollbar {
        display: none;
    }
    </style>
  </head>
  <body data-spy="scroll" data-target=".onpage-navigation" data-offset="60" @auth class="authenticated" @endauth>
    <main>
        {{-- Navbar --}}
      @include('client.partials.navbar')
      
      <div class="main">
        {{-- Session Messages --}}
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
        @if(!request()->routeIs('login'))
            @include('client.partials.footer')
        @endif
        
        {{-- Chat Widget --}}
        <div class="chat-widget" id="chatWidget">
            <div class="chat-widget-header">
                <h5><i class="fa fa-comments"></i> Chat Support</h5>
                <button class="btn-close" id="closeChatWidget">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="chat-widget-messages" id="chatWidgetMessages">
                <div class="welcome-message">
                    <p>Xin chào! Bạn cần hỗ trợ gì không?</p>
                </div>
            </div>
            <div class="chat-widget-input">
                <input type="text" id="widgetMessageInput" placeholder="Nhập tin nhắn...">
                <button id="widgetSendBtn">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <div class="chat-widget-toggle" id="chatWidgetToggle">
            <i class="fa fa-comments"></i>
            <span class="notification-badge" id="widgetNotificationBadge" style="display: none;">0</span>
        </div>
        
        {{-- Realtime handled by Pusher in script --}}
      </div>
      <div class="scroll-up"><a href="#totop"><i class="fa fa-angle-double-up"></i></a></div>
    </main>
    <!--  
    JavaScripts
    =============================================
    -->
    <script src="{{ asset('client/assets/lib/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset("assets/external/js/popper.min.js") }}"></script>
    <script src="{{ asset('client/assets/lib/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('client/assets/lib/magnific-popup/dist/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('client/assets/lib/wow/dist/wow.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('client/assets/js/main.js') }}"></script>
    <script src="{{ asset('client/assets/js/favorites.js') }}"></script>
    <script src="{{ asset('client/assets/js/favorites-init.js') }}"></script>
    <script src="{{ asset('client/assets/js/banner-effects.js') }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('client/assets/js/realtime-config.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/assets/js/realtime-notifications.js') }}?v={{ time() }}"></script>
    
    
    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency={{ config('paypal.currency') }}"></script>
    <script src="{{ asset("assets/external/js/sweetalert2.min.js") }}"></script>
    
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
    
    @stack('scripts')
    
    @auth
    <script>
    $(document).ready(function() {
        // function helper để scroll xuống dưới
        function scrollToBottom() {
            const widgetMessages = $('#chatWidgetMessages');
            setTimeout(function() {
                if (widgetMessages.length > 0 && widgetMessages[0]) {
                    widgetMessages.scrollTop(widgetMessages[0].scrollHeight);
                }
            }, 200);
        }

        // function để scroll mượt mà
        function smoothScrollToBottom() {
            const widgetMessages = $('#chatWidgetMessages');
            if (widgetMessages.length > 0 && widgetMessages[0]) {
                widgetMessages[0].scrollTo({
                    top: widgetMessages[0].scrollHeight,
                    behavior: 'smooth'
                });
            }
        }

        // chatbot functionality đã được xử lý bởi chatbot.js

        // cập nhật số tin nhắn chưa đọc
        function updateUnreadCount() {
            // Chỉ gọi API nếu user đã đăng nhập
            @auth
            $.ajax({
                url: '{{ route("client.chat.unread-count") }}',
                method: 'GET',
                success: function(response) {
                    if (response.count > 0) {
                        $('#widgetNotificationBadge').text(response.count).show();
                    } else {
                        $('#widgetNotificationBadge').hide();
                    }
                },
                error: function(xhr, status, error) {
                    // Silently handle errors - user might not be authenticated
                    console.log('Chat unread count error:', status, error);
                    $('#widgetNotificationBadge').hide();
                }
            });
            @else
            // User chưa đăng nhập, ẩn badge
            $('#widgetNotificationBadge').hide();
            @endauth
        }

        // xử lý click vào suggestion buttons
        $(document).on('click', '.suggestion-btn', function() {
            const suggestionText = $(this).text();
            $('#widgetMessageInput').val(suggestionText);
            sendWidgetMessage();
        });

        // cập nhật số tin nhắn chưa đọc mỗi 10 giây (chỉ khi đã đăng nhập)
        @auth
        setInterval(updateUnreadCount, 10000);
        updateUnreadCount();
        @endauth
    });
    </script>
    @endauth
    
    <!-- Auth Check JS -->
    <script src="{{ asset('js/auth-check.js') }}"></script>
    
    <!-- Chatbot JS -->
    <script src="{{ asset('js/chatbot-simple.js') }}"></script>
    
    <!-- Set Auth Status -->
    <script>
        // Set trạng thái đăng nhập từ server
        @auth
            setAuthStatus(true);
        @else
            setAuthStatus(false);
        @endauth
    </script>
    
    <!-- Toast Notification JS -->
    <script src="{{ asset('js/toast.js') }}"></script>
    
    <!-- Flash Messages to Toast -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.success('Thành công!', '{{ session('success') }}');
            });
        </script>
    @endif
    
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('toast_type') && session('toast_title'))
                    Toast.show('{{ session('toast_type') }}', '{{ session('toast_title') }}', '{{ session('error') }}');
                @else
                    Toast.error('Lỗi!', '{{ session('error') }}');
                @endif
            });
        </script>
    @endif
    
    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.warning('Cảnh báo!', '{{ session('warning') }}');
            });
        </script>
    @endif
    
    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.info('Thông tin!', '{{ session('info') }}');
            });
        </script>
    @endif
    
    @yield('scripts')
  </body>
</html>