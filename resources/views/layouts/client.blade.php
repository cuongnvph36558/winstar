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
    <!-- Thêm FontAwesome -->
    <link rel="stylesheet" href="{{ asset("assets/external/css/font-awesome.min.css") }}">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Page specific styles -->
    @yield('styles')
    
    <style>
    /* Chat Widget Styles */
    .chat-widget {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 300px;
        height: 400px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        display: none;
        flex-direction: column;
        z-index: 1000;
    }

    .chat-widget.show {
        display: flex;
    }

    .chat-widget-header {
        background: #2196f3;
        color: white;
        padding: 15px;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-widget-header h5 {
        margin: 0;
        font-size: 14px;
    }

    .btn-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }

    .chat-widget-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        overflow-x: hidden;
        background: #f8f9fa;
        max-height: 300px;
        min-height: 200px;
        scrollbar-width: thin;
        scrollbar-color: #ccc #f8f9fa;
        word-wrap: break-word;
        word-break: break-word;
        -webkit-overflow-scrolling: touch;
    }

    .chat-widget-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-widget-messages::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 3px;
    }

    .chat-widget-messages::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    .chat-widget-messages::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    .welcome-message {
        text-align: center;
        color: #666;
        font-size: 14px;
    }

    .chat-widget-input {
        padding: 15px;
        display: flex;
        gap: 10px;
        background: white;
        border-radius: 0 0 10px 10px;
    }

    .chat-widget-input input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 8px 15px;
        font-size: 14px;
    }

    .chat-widget-input button {
        background: #2196f3;
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-widget-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: #2196f3;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        z-index: 999;
        transition: transform 0.2s;
    }

    .chat-widget-toggle:hover {
        transform: scale(1.1);
    }

    .chat-widget-toggle i {
        font-size: 24px;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #f44336;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .chat-widget {
            width: 100%;
            height: 100%;
            bottom: 0;
            right: 0;
            border-radius: 0;
        }
        
        .chat-widget-header {
            border-radius: 0;
        }
        
        .chat-widget-input {
            border-radius: 0;
        }

        .chat-widget-messages {
            max-height: calc(100vh - 120px);
            min-height: 200px;
        }
    }
    </style>
    
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
        @auth
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
        @endauth
        
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
    
    @yield('scripts')
    
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
            }, 100);
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

        // chat widget functionality
        $('#chatWidgetToggle').click(function() {
            $('#chatWidget').toggleClass('show');
        });

        $('#closeChatWidget').click(function() {
            $('#chatWidget').removeClass('show');
        });

        $('#widgetSendBtn').click(sendWidgetMessage);
        $('#widgetMessageInput').keypress(function(e) {
            if (e.which == 13) {
                sendWidgetMessage();
            }
        });

        function sendWidgetMessage() {
            const content = $('#widgetMessageInput').val().trim();
            if (!content) return;

            // thêm tin nhắn của user vào widget
            const messageHtml = `
                <div style="text-align: right; margin-bottom: 10px;">
                    <div style="background: #2196f3; color: white; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%;">
                        ${content}
                    </div>
                </div>
            `;
            
            $('#chatWidgetMessages').append(messageHtml);
            $('#widgetMessageInput').val('');
            
                                // scroll xuống dưới
            scrollToBottom();
            
            // gửi tin nhắn đến chatbot
            $.ajax({
                url: '{{ route("client.chatbot.process") }}',
                method: 'POST',
                data: {
                    message: content,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // hiển thị phản hồi từ chatbot
                    let responseHtml = '';
                    
                    if (response.type === 'text') {
                        responseHtml = `
                            <div style="text-align: left; margin-bottom: 10px;">
                                <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                                    ${response.content.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        `;
                    } else if (response.type === 'product_list') {
                        responseHtml = `
                            <div style="text-align: left; margin-bottom: 10px;">
                                <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                                    ${response.content.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        `;
                    } else if (response.type === 'category_list') {
                        responseHtml = `
                            <div style="text-align: left; margin-bottom: 10px;">
                                <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                                    ${response.content.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        `;
                    }
                    
                    $('#chatWidgetMessages').append(responseHtml);
                    scrollToBottom();
                    
                    // hiển thị suggestions nếu có
                    if (response.suggestions && response.suggestions.length > 0) {
                        let suggestionsHtml = '<div style="text-align: left; margin-bottom: 10px;">';
                        suggestionsHtml += '<div style="background: #f8f9fa; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">';
                        suggestionsHtml += '<div style="font-size: 12px; color: #666; margin-bottom: 5px;">Gợi ý:</div>';
                        
                        response.suggestions.forEach(function(suggestion) {
                            suggestionsHtml += `<button class="suggestion-btn" style="background: #e3f2fd; border: 1px solid #2196f3; color: #2196f3; padding: 4px 8px; margin: 2px; border-radius: 12px; font-size: 11px; cursor: pointer;">${suggestion}</button>`;
                        });
                        
                        suggestionsHtml += '</div></div>';
                        $('#chatWidgetMessages').append(suggestionsHtml);
                        scrollToBottom();
                    }
                },
                error: function() {
                    // phản hồi mặc định nếu có lỗi
                    const responseHtml = `
                        <div style="text-align: left; margin-bottom: 10px;">
                            <div style="background: white; color: #333; padding: 8px 12px; border-radius: 15px; display: inline-block; max-width: 80%; border: 1px solid #ddd;">
                                xin lỗi, tôi đang gặp sự cố. bạn có thể thử lại sau hoặc liên hệ trực tiếp với chúng tôi.
                            </div>
                        </div>
                    `;
                    
                    $('#chatWidgetMessages').append(responseHtml);
                    scrollToBottom();
                }
            });
        }

        // cập nhật số tin nhắn chưa đọc
        function updateUnreadCount() {
            $.ajax({
                url: '{{ route("client.chat.unread-count") }}',
                method: 'GET',
                success: function(response) {
                    if (response.count > 0) {
                        $('#widgetNotificationBadge').text(response.count).show();
                    } else {
                        $('#widgetNotificationBadge').hide();
                    }
                }
            });
        }

        // xử lý click vào suggestion buttons
        $(document).on('click', '.suggestion-btn', function() {
            const suggestionText = $(this).text();
            $('#widgetMessageInput').val(suggestionText);
            sendWidgetMessage();
        });

        // cập nhật số tin nhắn chưa đọc mỗi 10 giây
        setInterval(updateUnreadCount, 10000);
        updateUnreadCount();
    });
    </script>
    @endauth
  </body>
</html>