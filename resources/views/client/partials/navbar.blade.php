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
            <ul class="nav navbar-nav navbar-right">
                <!-- Main Navigation -->
                <li><a href="{{ route('client.home') }}">Home</a></li>
                <li><a href="{{ route('client.about') }}">About</a></li>
                <li><a href="{{ route('client.blog') }}">Blog</a></li>
                <li><a href="{{ route('client.contact') }}">Contact</a></li>

                <!-- Shop Dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">Shop</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('client.product') }}">Products</a></li>
                        <li><a href="{{ route('client.cart') }}">Cart</a></li>
                        <li><a href="{{ route('client.checkout') }}">Checkout</a></li>
                    </ul>
                </li>

                <!-- Shopping Cart Icon -->
                <li>
                    <a href="{{ route('client.cart') }}" class="cart-icon">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
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
}

.navbar-custom .navbar-brand {
    font-weight: 700;
    text-transform: uppercase;
}

.navbar-custom .nav li a {
    text-transform: uppercase;
    letter-spacing: 1px;
}

.cart-icon {
    position: relative;
    padding-right: 8px;
}

.cart-count {
    position: absolute;
    top: 0;
    right: 0;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    line-height: 1;
}

.dropdown-menu {
    background: rgba(0, 0, 0, 0.9);
    border: none;
    border-radius: 0;
}

.dropdown-menu > li > a {
    color: #fff;
    padding: 10px 20px;
}

.dropdown-menu > li > a:hover {
    background: rgba(255, 255, 255, 0.1);
}

.navbar-toggle {
    border: none;
    background: transparent;
}

.navbar-toggle .icon-bar {
    background-color: #fff;
}

.d-none {
    display: none;
}
</style>