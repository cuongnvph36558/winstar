<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" class="img-circle" src="{{ asset('admin/img/profile_small.jpg') }}" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold">{{ auth()->user()->name ?? 'Admin' }}</strong>
                            </span>
                            <span class="text-muted text-xs block">
                                {{ auth()->user()->roles->first()->name ?? 'Administrator' }}
                                <b class="caret"></b>
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="#">Hồ sơ</a></li>
                        <li><a href="#">Cài đặt</a></li>
                        <li class="divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit"
                                    style="background: none; border: none; color: inherit; cursor: pointer; width: 100%; text-align: left; padding: 3px 20px;">
                                    Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">
                    WS
                </div>
            </li>


            <!-- Dashboard -->
            @can('dashboard.view')
                <li class="{{ request()->is('admin') || request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-th-large"></i>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>
            @endcan

            <!-- Quản lý Người dùng -->
            @can('user.view')
                <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Quản lý Người dùng</span>
                    </a>
                </li>
            @endcan

            <!-- Phân Quyền -->
            @if (auth()->user()->hasAnyPermission(['role.view', 'permission.view']))
                <li class="{{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-shield"></i> <span class="nav-label">Phân Quyền</span><span
                            class="fa arrow"></span></a>
                    <ul
                        class="nav nav-second-level collapse {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'in' : '' }}">
                        @can('role.view')
                            <li class="{{ request()->is('admin/roles*') ? 'active' : '' }}">
                                <a href="{{ route('admin.roles.index') }}">
                                    <i class="fa fa-user-circle"></i> Quản lý Vai trò
                                </a>
                            </li>
                        @endcan
                        @can('permission.view')
                            <li class="{{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                <a href="{{ route('admin.permissions.index') }}">
                                    <i class="fa fa-key"></i> Quản lý Quyền
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- Quản lý Danh mục -->
            @can('category.view')
                <li class="{{ request()->is('admin/category*') ? 'active' : '' }}">
                    <a href="{{ route('admin.category.index-category') }}">
                        <i class="fa fa-list"></i>
                        <span class="nav-label">Quản lý Danh mục</span>
                    </a>
                </li>
            @endcan

            <!-- Sản phẩm -->
            @can('product.view')
                <li class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-cube"></i> <span class="nav-label">Sản phẩm</span><span
                            class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse {{ request()->is('admin/products*') ? 'in' : '' }}">
                        <li><a href="{{ route('admin.product.index-product') }}">Danh sách sản phẩm</a></li>
                        <li><a href="{{ route('admin.product.product-variant.variant.list-variant') }}">Thuộc tính sản
                                phẩm</a></li>
                    </ul>
                </li>
            @endcan

            <!-- Đơn hàng -->
            @can('order.view')
                <li class="{{ request()->is('admin/order*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="nav-label">Đơn hàng</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse {{ request()->is('admin/order*') ? 'in' : '' }}">
                        <li class="{{ request()->is('admin/order') ? 'active' : '' }}">
                            <a href="{{ route('admin.order.index') }}">Tất cả đơn hàng</a>
                        </li>
                        <li class="{{ request()->is('admin/order/create') ? 'active' : '' }}">
                            <a href="{{ route('admin.order.create') }}">Tạo đơn hàng</a>
                        </li>
                        <li class="{{ request()->is('admin/order/trash') ? 'active' : '' }}">
                            <a href="{{ route('admin.order.trash') }}">Đơn hàng đã xoá</a>
                        </li>
                    </ul>
                </li>
            @endcan

            <!-- Mã giảm giá -->
            @can('coupon.view')
                <li class="{{ request()->is('admin/coupon*') ? 'active' : '' }}">
                    <a href="{{ route('admin.coupon.index') }}">
                        <i class="fa fa-ticket"></i>
                        <span class="nav-label">Mã giảm giá</span>
                    </a>
                </li>
            @endcan

            <!-- Đánh giá -->
            @can('review.view')
                <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-star"></i>
                        <span class="nav-label">Đánh giá</span>
                    </a>
                </li>
            @endcan

            <!-- Banner -->
            @can('banner.view')
                <li class="{{ request()->is('admin/banner*') ? 'active' : '' }}">
                    <a href="{{ route('admin.banner.index-banner') }}">
                        <i class="fa fa-image"></i>
                        <span class="nav-label">Banner</span>
                    </a>
                </li>
            @endcan

            <!-- Báo cáo -->
            @can('report.view')
                <li class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Báo cáo</span><span
                            class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse {{ request()->is('admin/reports*') ? 'in' : '' }}">
                        <li><a href="#">Báo cáo doanh thu</a></li>
                        <li><a href="#">Báo cáo sản phẩm</a></li>
                        <li><a href="#">Báo cáo khách hàng</a></li>
                    </ul>
                </li>
            @endcan

            <!-- Cài đặt -->
            @can('setting.view')
                <li class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-cog"></i> <span class="nav-label">Cài đặt</span><span
                            class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse {{ request()->is('admin/settings*') ? 'in' : '' }}">
                        <li><a href="#">Cài đặt chung</a></li>
                        <li><a href="#">Cài đặt thanh toán</a></li>
                        <li><a href="#">Cài đặt email</a></li>
                    </ul>
                </li>
            @endcan


        </ul>
    </div>
</nav>
