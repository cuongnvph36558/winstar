<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="404 Page Not Found">
    <title>404 | Not Found</title>

    <!-- Combine CSS files -->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
</head>

<body class="gray-bg">
    <div class="middle-box text-center animated fadeInDown">
        <h1>404</h1>
        <h3 class="font-bold">Page Not Found</h3>

        <div class="error-desc">
            <p>Sorry, the page you are looking for could not be found. Please check the URL for errors or try refreshing the page.</p>
            
            <div class="mt-4">
                {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fa fa-home"></i> Về bảng điều khiển
                </a> --}}
            </div>
        </div>
    </div>

    <!-- Combine and minify JS -->
    <script src="{{ asset('admin/js/jquery-3.1.1.min.js') }}" defer></script>
    <script src="{{ asset('admin/js/bootstrap.min.js') }}" defer></script>
</body>
</html>
