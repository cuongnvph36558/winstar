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

            <!-- ==================== DASHBOARD ==================== -->
            @can('dashboard.view')
            <li class="{{ request()->is('admin') || request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span class="nav-label">Bảng điều khiển</span>
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

            <!-- Quản lý Điểm Tích Lũy -->
            <li class="{{ request()->is('admin/points*') ? 'active' : '' }}">
                <a href="{{ route('admin.points.index') }}">
                    <i class="fa fa-star text-warning"></i>
                    <span class="nav-label">Quản lý Điểm Tích Lũy</span>
                </a>
            </li>

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

            <!-- Sản phẩm -->
            @can('product.view')
            <li class="{{ request()->is('admin/products*') || request()->is('admin/category*') || request()->is('admin/favorite') ? 'active' : '' }}">
                <a href="#"><i class="fa fa-cube"></i> <span class="nav-label">Sản phẩm</span><span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse {{ request()->is('admin/products*') || request()->is('admin/category*') || request()->is('admin/favorite') ? 'in' : '' }}">
                    <li class="{{ request()->is('admin/product.index-product') ? 'active' : '' }}">
                        <a href="{{ route('admin.product.index-product') }}">Danh sách sản phẩm</a>
                    </li>
                    <li class="{{ request()->is('admin/category*') ? 'active' : '' }}">
                        <a href="{{ route('admin.category.index-category') }}">Quản lý Danh mục</a>
                    </li>
                    <li class="{{ request()->is('admin/product.product-variant.variant.list-variant') ? 'active' : '' }}">
                        <a href="{{ route('admin.product.product-variant.variant.list-variant') }}">Thuộc tính sản phẩm</a>
                    </li>
                    <li class="{{ request()->is('admin/favorite') ? 'active' : '' }}">
                        <a href="{{ route('admin.favorite.index') }}">Sản phẩm yêu thích</a>
                    </li>
                </ul>
            </li>
            @endcan

            <!-- Bài viết -->
            @can('post.create')
            <li class="{{ request()->is('admin/posts*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-file-text-o"></i>
                    <span class="nav-label">Bài viết</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse {{ request()->is('admin/posts*') ? 'in' : '' }}">
                    <li><a href="{{ route('admin.posts.create') }}">Thêm bài viết</a></li>
                    <li><a href="{{ route('admin.posts.index') }}">Danh sách bài viết</a></li>
                </ul>
            </li>
            @endcan

            <!-- Banner & Quảng bá -->
            @can('banner.view')
            <li class="{{ request()->is('admin/banner*') || request()->is('admin/features*') || request()->is('admin/video*') || request()->is('admin/services*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-image"></i>
                    <span class="nav-label">Trang quảng bá</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ request()->is('admin/banner*') ? 'active' : '' }}">
                        <a href="{{ route('admin.banner.index-banner') }}">Banner</a>
                    </li>
                    <li class="{{ request()->is('admin/features*') ? 'active' : '' }}">
                        <a href="{{ route('admin.features.index') }}">Content 1</a>
                    </li>
                    <li class="{{ request()->is('admin/video*') ? 'active' : '' }}">
                        <a href="{{ route('admin.video.index') }}">Video</a>
                    </li>
                    <li class="{{ request()->is('admin/services*') ? 'active' : '' }}">
                        <a href="{{ route('admin.services.index') }}">Dịch vụ</a>
                    </li>
                </ul>
            </li>
            @endcan

            <!-- Giới thiệu (About) -->
            @can('about.view')
            <li class="{{ request()->is('admin/about*') ? 'active' : '' }}">
                <a href="{{ route('admin.about.index') }}">
                    <i class="fa fa-info-circle"></i>
                    <span class="nav-label">About</span>
                </a>
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
                    <li class="{{ request()->is('admin/order/trash') ? 'active' : '' }}">
                        <a href="{{ route('admin.order.trash') }}">Đơn hàng đã xoá</a>
                    </li>
                </ul>
            </li>
            @endcan

            <!-- Đổi hoàn hàng -->
            @can('order.view')
            <li class="{{ request()->is('admin/return-exchange*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-exchange text-warning"></i>
                    <span class="nav-label">Đổi hoàn hàng</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse {{ request()->is('admin/return-exchange*') ? 'in' : '' }}">
                    <li class="{{ request()->is('admin/return-exchange') && !request()->is('admin/return-exchange/statistics') ? 'active' : '' }}">
                        <a href="{{ route('admin.return-exchange.index') }}">
                            <i class="fa fa-list"></i> Danh sách yêu cầu
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/return-exchange/statistics') ? 'active' : '' }}">
                        <a href="{{ route('admin.return-exchange.statistics') }}">
                            <i class="fa fa-bar-chart"></i> Thống kê
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            <!-- Mã giảm giá -->
            @can('coupon.view')
            <li class="{{ request()->is('admin/coupon*') || request()->is('admin/coupon-user*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-ticket"></i>
                    <span class="nav-label">Mã giảm giá</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse {{ request()->is('admin/coupon*') || request()->is('admin/coupon-user*') ? 'in' : '' }}">
                    <li class="{{ request()->is('admin/coupon*') && !request()->is('admin/coupon-user*') ? 'active' : '' }}">
                        <a href="{{ route('admin.coupon.index') }}">Quản lý mã giảm giá</a>
                    </li>
                    <li class="{{ request()->is('admin/coupon-user*') ? 'active' : '' }}">
                        <a href="{{ route('admin.coupon-user.index') }}">Người dùng sử dụng</a>
                    </li>
                </ul>
            </li>
            @endcan



            <!-- Quản lý Bình luận -->
            @can('comment.view')
            <li class="{{ request()->is('admin/comment*') ? 'active' : '' }}">
                <a href="{{ route('admin.comment.index-comment') }}">
                    <i class="fa fa-comments"></i>
                    <span class="nav-label">Quản lý Bình luận</span>
                </a>
            </li>
            @endcan

            <!-- Quản lý Liên hệ -->
            <li class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                <a href="{{ route('contacts.index') }}">
                    <i class="fa fa-envelope"></i>
                    <span class="nav-label">Quản lý Liên hệ</span>
                </a>
            </li>

            <!-- Đánh giá -->
            <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                <a href="{{ route('admin.reviews.list') }}">
                    <i class="fa fa-star"></i>
                    <span class="nav-label">Đánh giá</span>
                </a>
            </li>







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
