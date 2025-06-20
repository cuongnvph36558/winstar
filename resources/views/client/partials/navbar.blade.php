<div class="page-loader">
    <div class="loader">Loading...</div>
</div>
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation" style="background: rgba(0, 0, 0, 0.9);">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#custom-collapse"><span
                    class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span
                    class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand"
                href="">Titan</a>
        </div>
        <div class="collapse navbar-collapse" id="custom-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('client.home') }}">Home</a></li>
                <li><a href="{{ route('client.about') }}">About</a></li>
                <li><a href="{{ route('client.blog') }}">Blog</a></li>
                <li><a href="{{ route('client.contact') }}">Contact</a></li>
                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Shop</a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('client.product') }}">Products</a></li>
                        <li><a href="{{ route('client.cart') }}">Cart</a></li>
                        <li><a href="{{ route('client.checkout') }}">Checkout</a></li>
                    </ul>
                </li>
                @auth
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">{{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Orders</a></li>
                            <li>
                                <a href="#" onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li><a href="">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>