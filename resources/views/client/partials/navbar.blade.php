<!-- Page Loader -->
<div class="page-loader">
    <div class="loader">Loading...</div>
</div>

<!-- Navigation Bar -->
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation" id="mainNavbar">
    <div class="container">
        <!-- Navbar Header -->
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#custom-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('client.home') }}">
                <img src="{{ asset('logo.svg') }}" alt="Winstar" class="brand-logo">
            </a>
        </div>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="custom-collapse">
            <ul class="nav navbar-nav navbar-center">
                <!-- Main Navigation -->
                <li><a href="{{ route('client.home') }}">Home</a></li>
                <li><a href="{{ route('client.product') }}">Products</a></li>
                <li><a href="{{ route('client.about') }}">About</a></li>
                <li><a href="{{ route('client.blog') }}">Blog</a></li>
                <li><a href="{{ route('client.contact') }}">Contact</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <!-- Favorite Icon -->
                <li>
                    <a href="{{ route('client.favorite.index') }}" class="favorite-icon">
                        <i class="fa fa-heart" style="font-family: FontAwesome !important; font-style: normal !important; font-weight: normal !important;"></i>
                        @auth
                            <span class="favorite-count" id="favoriteCount">{{ auth()->user()->favorites()->count() }}</span>
                        @endauth
                    </a>
                </li>

                <!-- Shopping Cart Icon -->
                <!-- Hiển thị số loại sản phẩm khác nhau (không phải tổng số lượng) -->
                <li>
                    <a href="{{ route('client.cart') }}" class="cart-icon">
                        <i class="fa fa-shopping-cart" style="font-family: FontAwesome !important; font-style: normal !important; font-weight: normal !important;"></i>
                        @auth
                            <span class="cart-count" id="cartCount">{{ $globalCartCount ?? 0 }}</span>
                        @endauth
                    </a>
                </li>

                <!-- User Authentication -->
                @auth
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile') }}">Profile</a></li>
                            <li><a href="{{ route('client.order.list') }}">Đơn hàng</a></li>
                            <li><a href="{{ route('client.points.index') }}">
                                <i class="fa fa-star text-warning"></i> Điểm tích lũy
                            </a></li>
                            <li>
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-custom {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        padding: 20px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15), 0 1px 3px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        will-change: padding, background-color, box-shadow;
        transform: translate3d(0, 0, 0);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        backface-visibility: hidden;
    }

    /* Navbar khi cuộn xuống (shrunk) */
    .navbar-custom.shrunk {
        padding: 8px 0;
        background: rgba(0, 0, 0, 0.96);
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        image-rendering: crisp-edges;
    }

    .navbar-custom .navbar-brand {
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        padding: 10px 15px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        border-radius: 8px;
    }

    .navbar-custom .navbar-brand .brand-logo {
        height: 35px;
        width: auto;
        transition: all 0.3s ease;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .navbar-custom .navbar-brand::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s ease;
        z-index: 1;
        border-radius: 8px;
    }

    .navbar-custom .navbar-brand:hover::before {
        left: 100%;
    }

    .navbar-custom .navbar-brand:hover .brand-logo {
        transform: scale(1.05);
        filter: drop-shadow(0 4px 8px rgba(231, 76, 60, 0.4));
    }

    @keyframes logoGlow {
        from {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
        to {
            filter: drop-shadow(0 4px 8px rgba(231, 76, 60, 0.3));
        }
    }

    .navbar-custom .navbar-brand .brand-logo {
        animation: logoGlow 3s ease-in-out infinite alternate;
    }

    /* Brand khi navbar shrunk */
    .navbar-custom.shrunk .navbar-brand {
        font-size: 20px;
        padding: 10px 15px;
    }

    .navbar-custom .nav li a {
        text-transform: uppercase;
        letter-spacing: 1.2px;
        padding: 14px 20px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        color: #fff;
        line-height: 1.4;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        position: relative;
        border-radius: 8px;
        margin: 0 2px;
    }

    /* Nav links khi navbar shrunk */
    .navbar-custom.shrunk .nav li a {
        padding: 8px 15px;
        font-size: 12px;
    }

    .navbar-custom .nav li a:hover {
        color: #fff;
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.8) 0%, rgba(192, 57, 43, 0.8) 100%);
        border-radius: 8px;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .navbar-custom .nav li a::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #e74c3c, #c0392b);
        transition: all 0.3s ease;
        transform: translateX(-50%);
        border-radius: 1px;
    }

    .navbar-custom .nav li a:hover::before {
        width: 80%;
    }

    .navbar-nav.navbar-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .favorite-icon {
        position: relative;
        padding-right: 15px !important;
    }

    .favorite-icon i {
        font-size: 22px;
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
        color: #fff;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .favorite-icon:hover i {
        color: #e74c3c;
        transform: scale(1.1);
        filter: drop-shadow(0 4px 8px rgba(231, 76, 60, 0.4));
    }

    /* Favorite icon khi navbar shrunk */
    .navbar-custom.shrunk .favorite-icon i {
        font-size: 17px;
    }

    .cart-icon {
        position: relative;
        padding-right: 15px !important;
    }

    .cart-icon i {
        font-size: 22px;
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
        color: #fff;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .cart-icon:hover i {
        color: #e74c3c;
        transform: scale(1.1);
        filter: drop-shadow(0 4px 8px rgba(231, 76, 60, 0.4));
    }

    /* Cart icon khi navbar shrunk */
    .navbar-custom.shrunk .cart-icon i {
        font-size: 17px;
    }

    .favorite-count {
        position: absolute;
        top: -8px;
        right: -3px;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 50%;
        padding: 4px;
        font-size: 12px;
        font-weight: bold;
        line-height: 1;
        min-width: 24px;
        height: 24px;
        text-align: center;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4), 0 2px 4px rgba(0,0,0,0.3);
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
        animation: glow 2s ease-in-out infinite alternate;
    }

    .cart-count {
        position: absolute;
        top: -8px;
        right: -3px;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 50%;
        padding: 4px;
        font-size: 12px;
        font-weight: bold;
        line-height: 1;
        min-width: 24px;
        height: 24px;
        text-align: center;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4), 0 2px 4px rgba(0,0,0,0.3);
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
        animation: glow 2s ease-in-out infinite alternate;
    }

    /* Favorite count khi navbar shrunk */
    .navbar-custom.shrunk .favorite-count {
        top: -4px;
        right: 1px;
        min-width: 18px;
        height: 18px;
        font-size: 10px;
        padding: 2px;
    }

    .favorite-count.show {
        display: flex;
        align-items: center;
        justify-content: center;
        animation: bounceIn 0.5s ease;
    }

    .favorite-count.updated {
        animation: pulse 0.6s ease;
    }

    /* Cart count khi navbar shrunk */
    .navbar-custom.shrunk .cart-count {
        top: -4px;
        right: 1px;
        min-width: 18px;
        height: 18px;
        font-size: 10px;
        padding: 2px;
    }

    .cart-count.show {
        display: flex;
        align-items: center;
        justify-content: center;
        animation: bounceIn 0.5s ease;
    }

    .cart-count.updated {
        animation: pulse 0.6s ease;
    }

    @keyframes bounceIn {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.2); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); background: #c0392b; }
        100% { transform: scale(1); }
    }

    @keyframes glow {
        from {
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4), 0 2px 4px rgba(0,0,0,0.3);
        }
        to {
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.6), 0 4px 8px rgba(0,0,0,0.4);
        }
    }

    .dropdown-menu {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(20, 20, 20, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.4), 0 4px 10px rgba(0,0,0,0.2);
        padding: 12px 0;
        margin-top: 15px;
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        backdrop-filter: blur(15px);
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
        transform: translateY(-5px);
        opacity: 0;
        visibility: hidden;
    }

    .dropdown.open .dropdown-menu {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }

    /* Dropdown khi navbar shrunk */
    .navbar-custom.shrunk .dropdown-menu {
        margin-top: 8px;
    }

    .dropdown-menu > li > a {
        color: #fff;
        padding: 14px 28px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        line-height: 1.4;
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
        border-radius: 8px;
        margin: 2px 8px;
        position: relative;
        overflow: hidden;
    }

    .dropdown-menu > li > a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(231, 76, 60, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .dropdown-menu > li > a:hover::before {
        left: 100%;
    }

    .dropdown-menu > li > a:hover {
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.2) 0%, rgba(192, 57, 43, 0.2) 100%);
        color: #fff;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    }

    .navbar-toggle {
        border: none;
        background: transparent;
        margin-top: 10px;
        transition: margin-top 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    }

    /* Toggle button khi navbar shrunk */
    .navbar-custom.shrunk .navbar-toggle {
        margin-top: 6px;
    }

    .navbar-toggle .icon-bar {
        background-color: #fff;
        height: 2px;
        border-radius: 1px;
    }

    .d-none {
        display: none;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .navbar-nav.navbar-center {
            position: relative;
            left: auto;
            transform: none;
        }

    }

    @media (max-width: 767px) {
        .navbar-custom {
            padding: 10px 0;
        }

        .navbar-custom.shrunk {
            padding: 6px 0;
        }

        .navbar-custom .navbar-brand {
            font-size: 20px;
        }

        .navbar-custom.shrunk .navbar-brand {
            font-size: 16px;
        }
    }
</style>

<script>
    // Navbar scroll effect - DISABLED to keep navbar always the same size
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.getElementById('mainNavbar');
        
        // Remove any existing shrunk class to ensure navbar stays full size
        navbar.classList.remove('shrunk');
        
        // Disable scroll effect - navbar will always stay the same size
        // function updateNavbar() {
        //     const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        //     // Thêm class 'shrunk' khi scroll xuống > 50px
        //     if (scrollTop > 50) {
        //         if (!isScrolled) {
        //             navbar.classList.add('shrunk');
        //             isScrolled = true;
        //         }
        //     } else {
        //         if (isScrolled) {
        //             navbar.classList.remove('shrunk');
        //             isScrolled = false;
        //         }
        //     }
        // }

        // function handleScroll() {
        //     if (scrollTimer) {
        //         clearTimeout(scrollTimer);
        //     }

        //     // Immediate update for responsiveness
        //     updateNavbar();

        //     // Debounce for performance
        //     scrollTimer = setTimeout(updateNavbar, 16); // ~60fps
        // }

        // // Listen to scroll events
        // window.addEventListener('scroll', handleScroll, { passive: true });

        // // Initial check
        // updateNavbar();

        // Cart count management (số loại sản phẩm khác nhau, không phải tổng số lượng)
        const cartCountElement = document.getElementById('cartCount');
        if (cartCountElement) {
            console.log('Navbar loaded, initial cart count (distinct items):', cartCountElement.textContent);

            // Initialize cart count display
            updateCartCountDisplay();

            // Force refresh cart count from server on page load
            setTimeout(function () {
                console.log('Refreshing cart count from server...');
                refreshCartCount();
            }, 1000);

            // Function to update cart count display
            function updateCartCountDisplay() {
                const count = parseInt(cartCountElement.textContent) || 0;
                if (count > 0) {
                    cartCountElement.classList.add('show');
                } else {
                    cartCountElement.classList.remove('show');
                }
            }

            // Function to fetch and update cart count from server
            function refreshCartCount() {
                fetch('{{ route("client.cart-count") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.count !== undefined) {
                            updateCartCount(data.count);
                        }
                    })
                    .catch(error => {
                        console.log('Error fetching cart count:', error);
                    });
            }

            // Function to update cart count with animation
            function updateCartCount(newCount) {
                const currentCount = parseInt(cartCountElement.textContent) || 0;
                cartCountElement.textContent = newCount;

                if (newCount > 0) {
                    cartCountElement.classList.add('show');
                    if (newCount !== currentCount) {
                        cartCountElement.classList.add('updated');
                        setTimeout(() => {
                            cartCountElement.classList.remove('updated');
                        }, 600);
                    }
                } else {
                    cartCountElement.classList.remove('show');
                }
            }

            // Make functions globally available
            window.updateCartCount = updateCartCount;
            window.refreshCartCount = refreshCartCount;

            // Auto-refresh cart count every 2 seconds for real-time updates
            setInterval(refreshCartCount, 3000);
        }

        // Favorite count management (số sản phẩm yêu thích)
        const favoriteCountElement = document.getElementById('favoriteCount');
        if (favoriteCountElement) {
            function updateFavoriteCountDisplay() {
                const count = parseInt(favoriteCountElement.textContent) || 0;
                if (count > 0) {
                    favoriteCountElement.classList.add('show');
                } else {
                    favoriteCountElement.classList.remove('show');
                }
            }

            function refreshFavoriteCount() {
                fetch('{{ route("client.favorite-count") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.count !== undefined) {
                            updateFavoriteCount(data.count);
                        }
                    })
                    .catch(error => {
                        console.log('Error fetching favorite count:', error);
                    });
            }

            function updateFavoriteCount(newCount) {
                const currentCount = parseInt(favoriteCountElement.textContent) || 0;
                favoriteCountElement.textContent = newCount;

                if (newCount > 0) {
                    favoriteCountElement.classList.add('show');
                    if (newCount !== currentCount) {
                        favoriteCountElement.classList.add('updated');
                        setTimeout(() => {
                            favoriteCountElement.classList.remove('updated');
                        }, 600);
                    }
                } else {
                    favoriteCountElement.classList.remove('show');
                }
            }

            window.updateFavoriteCount = updateFavoriteCount;
            window.refreshFavoriteCount = refreshFavoriteCount;
            setInterval(refreshFavoriteCount, 3000);
        }
    });
</script>
