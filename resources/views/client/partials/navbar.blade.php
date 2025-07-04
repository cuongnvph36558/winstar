<!-- Page Loader -->
<div class="page-loader">
    <div class="loader">Loading...</div>
</div>

<!-- Navigation Bar -->
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
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
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Orders</a></li>
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
    transition: all 0.3s ease-in-out;
    padding: 15px 0;
}

.navbar-custom .navbar-brand {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 24px;
    color: #fff;
}

.navbar-custom .nav li a {
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 10px 15px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.navbar-custom .nav li a:hover {
    color: #e74c3c;
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
}

.cart-count {
    position: absolute;
    top: -5px;
    right: 0;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    padding: 3px;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
    min-width: 22px;
    height: 22px;
    text-align: center;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    display: none;
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
    margin-top: 10px;
}

.dropdown-menu > li > a {
    color: #fff;
    padding: 12px 25px;
    font-size: 14px;
}

.dropdown-menu > li > a:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #e74c3c;
}

.navbar-toggle {
    border: none;
    background: transparent;
    margin-top: 8px;
}

.navbar-toggle .icon-bar {
    background-color: #fff;
    height: 2px;
}

.d-none {
    display: none;
}

@media (max-width: 991px) {
    .navbar-nav.navbar-center {
        position: relative;
        left: auto;
        transform: none;
    }
}
</style>

<script>
// Cart count management (số loại sản phẩm khác nhau, không phải tổng số lượng)
document.addEventListener('DOMContentLoaded', function() {
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
