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
    <link rel="stylesheet" href="{{ asset("assets/external/css/font-awesome.min.css") }}">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
  </body>
</html>