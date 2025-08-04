<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Winstar | @yield('title', 'Hệ thống quản trị')</title>

    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <!-- FooTable -->
    <link href="{{ asset('admin/css/plugins/footable/footable.core.css') }}" rel="stylesheet">

    <!-- DatePicker -->
    <link href="{{ asset('admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

    <link href="{{ asset('admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/et-line-font/et-line-font.css') }}" rel="stylesheet">
    
    <!-- Toastr for notifications -->
    <link href="{{ asset("assets/external/css/toastr.min.css") }}" rel="stylesheet">

    @yield('styles')

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

    <!-- Toastr for notifications -->
    <script src="{{ asset("assets/external/js/toastr.min.js") }}"></script>

    <!-- Realtime features (disabled) -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('client/assets/js/realtime-config.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/assets/js/realtime-notifications.js') }}?v={{ time() }}"></script>
    <script>
      // Configure toastr
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };
      
      console.log('ℹ️ Admin realtime disabled');
    </script>

    <!-- Page-Level Scripts -->
    <script>
        // Đảm bảo jQuery đã được load
        if (typeof $ !== 'undefined') {
            $(document).ready(function () {
                $('.footable').footable();
                
                // Sửa lỗi scroll cho sidebar
                fixSidebarScroll();
            });
        } else {
            console.error('❌ jQuery chưa được load trong admin layout');
        }
        
        // Hàm sửa lỗi scroll cho sidebar
        function fixSidebarScroll() {
            const sidebar = document.querySelector('.navbar-static-side');
            if (!sidebar) return;
            
            // Thiết lập chiều cao cố định cho sidebar
            sidebar.style.height = '100vh';
            sidebar.style.position = 'fixed';
            sidebar.style.top = '0';
            sidebar.style.left = '0';
            sidebar.style.overflowY = 'auto';
            sidebar.style.overflowX = 'hidden';
            
            // Thêm hiệu ứng scroll mượt
            sidebar.style.scrollBehavior = 'smooth';
            
            // Xử lý khi thay đổi kích thước cửa sổ
            window.addEventListener('resize', function() {
                sidebar.style.height = '100vh';
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
