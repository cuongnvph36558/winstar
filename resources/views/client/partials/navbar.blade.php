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
            <a class="navbar-brand" href="{{ route('client.home') }}">Winstar</a>
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
                <!-- Shopping Cart Icon -->
                <!-- Hiển thị số loại sản phẩm khác nhau (không phải tổng số lượng) -->
                <li>
                    <a href="{{ route('client.cart') }}" class="cart-icon">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">{{ $globalCartCount ?? 0 }}</span>
                    </a>
                </li>

                <!-- User Authentication -->
                @auth
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
<<<<<<< HEAD
                            <li><a href="{{ route('profile') }}">Profile</a></li>
                            <li><a href="#">Orders</a></li>
=======
                            <li><a href="#">Profile</a></li>
                            <li><a href="{{ route('client.order.list') }}">Đơn hàng</a></li>
>>>>>>> main
                            <li>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
    background: rgba(0, 0, 0, 0.9);
    transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    padding: 18px 0;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
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
    font-weight: 700;
    text-transform: uppercase;
    font-size: 24px;
    color: #fff;
    transition: font-size 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    padding: 15px;
    line-height: 1.2;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

/* Brand khi navbar shrunk */
.navbar-custom.shrunk .navbar-brand {
    font-size: 20px;
    padding: 10px 15px;
}

.navbar-custom .nav li a {
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 12px 18px;
    font-weight: 500;
    font-size: 13px;
    transition: padding 0.3s cubic-bezier(0.25, 1, 0.5, 1), font-size 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    color: #fff;
    line-height: 1.4;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

/* Nav links khi navbar shrunk */
.navbar-custom.shrunk .nav li a {
    padding: 8px 15px;
    font-size: 12px;
}

.navbar-custom .nav li a:hover {
    color: #e74c3c;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
}

.navbar-nav.navbar-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

.cart-icon {
    position: relative;
    padding-right: 15px !important;
}

.cart-icon i {
    font-size: 20px;
    transition: font-size 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
}

/* Cart icon khi navbar shrunk */
.navbar-custom.shrunk .cart-icon i {
    font-size: 17px;
}

.cart-count {
    position: absolute;
    top: -6px;
    right: -1px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    padding: 3px;
    font-size: 11px;
    font-weight: bold;
    line-height: 1;
    min-width: 22px;
    height: 22px;
    text-align: center;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    transition: top 0.3s cubic-bezier(0.25, 1, 0.5, 1),
                right 0.3s cubic-bezier(0.25, 1, 0.5, 1),
                width 0.3s cubic-bezier(0.25, 1, 0.5, 1),
                height 0.3s cubic-bezier(0.25, 1, 0.5, 1),
                font-size 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    display: none;
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
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

.dropdown-menu {
    background: rgba(0, 0, 0, 0.95);
    border: none;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    padding: 8px 0;
    margin-top: 12px;
    transition: margin-top 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    backdrop-filter: blur(10px);
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
}

/* Dropdown khi navbar shrunk */
.navbar-custom.shrunk .dropdown-menu {
    margin-top: 8px;
}

.dropdown-menu > li > a {
    color: #fff;
    padding: 12px 25px;
    font-size: 14px;
    transition: all 0.3s ease;
    line-height: 1.4;
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
}

.dropdown-menu > li > a:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #e74c3c;
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
    
    .navbar-custom {
        padding: 12px 0;
    }
    
    .navbar-custom.shrunk {
        padding: 6px 0;
    }
    
    .navbar-custom .navbar-brand {
        font-size: 22px;
    }
    
    .navbar-custom.shrunk .navbar-brand {
        font-size: 18px;
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
// Navbar scroll effect
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');
    let isScrolled = false;
    let scrollTimer = null;

    function updateNavbar() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Thêm class 'shrunk' khi scroll xuống > 50px
        if (scrollTop > 50) {
            if (!isScrolled) {
                navbar.classList.add('shrunk');
                isScrolled = true;
            }
        } else {
            if (isScrolled) {
                navbar.classList.remove('shrunk');
                isScrolled = false;
            }
        }
    }

    function handleScroll() {
        if (scrollTimer) {
            clearTimeout(scrollTimer);
        }
        
        // Immediate update for responsiveness
        updateNavbar();
        
        // Debounce for performance
        scrollTimer = setTimeout(updateNavbar, 16); // ~60fps
    }

    // Listen to scroll events
    window.addEventListener('scroll', handleScroll, { passive: true });
    
    // Initial check
    updateNavbar();

    // Cart count management (số loại sản phẩm khác nhau, không phải tổng số lượng)
    const cartCountElement = document.getElementById('cartCount');

    console.log('Navbar loaded, initial cart count (distinct items):', cartCountElement.textContent);

    // Initialize cart count display
    updateCartCountDisplay();

    // Force refresh cart count from server on page load
    setTimeout(function() {
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

    // Auto-refresh cart count every 30 seconds for real-time updates
    setInterval(refreshCartCount, 30000);
});
</script>
