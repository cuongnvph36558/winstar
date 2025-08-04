<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">Chào mừng đến với hệ thống quản trị WINSTAR.</span>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="new-orders-notification">
                    <i class="fa fa-shopping-cart"></i> <span class="label label-warning" id="new-orders-count">0</span>
                </a>
                <ul class="dropdown-menu dropdown-messages" id="new-orders-list">
                    <li>
                        <div class="text-center link-block">
                            <a href="{{ route('admin.order.index') }}">
                                <i class="fa fa-shopping-cart"></i> <strong>Xem tất cả đơn hàng</strong>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" id="new-reviews-notification">
                    <i class="fa fa-star"></i> <span class="label label-primary" id="new-reviews-count">0</span>
                </a>
                <ul class="dropdown-menu dropdown-alerts" id="new-reviews-list">
                    <li>
                        <div class="text-center link-block">
                            <a href="{{ route('admin.reviews.list') }}">
                                <i class="fa fa-star"></i> <strong>Xem tất cả đánh giá</strong>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i> Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>
