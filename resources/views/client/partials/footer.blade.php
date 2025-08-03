<div class="module-small bg-dark">
    <div class="container">
      <div class="row">
        <div class="col-sm-3">
          <div class="widget">
            <h5 class="widget-title font-alt">Về Winstar</h5>
            <p>Winstar - Cửa hàng sản phẩm chất lượng cao với nhiều lựa chọn đa dạng. Chúng tôi cam kết mang đến những sản phẩm tốt nhất cho khách hàng.</p>
            <p><i class="fa fa-phone"></i> Hotline: 1900 1234</p>
            <p><i class="fa fa-envelope"></i> Email: <a href="mailto:info@winstar.com">info@winstar.com</a></p>
            <p><i class="fa fa-map-marker"></i> Địa chỉ: 123 Đường ABC, Quận 1, TP.HCM</p>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="widget">
            <h5 class="widget-title font-alt">Dịch vụ khách hàng</h5>
            <ul class="icon-list">
              <li><a href="{{ route('client.about') }}">Giới thiệu</a></li>
              <li><a href="{{ route('client.contact') }}">Liên hệ</a></li>
              <li><a href="{{ route('client.product') }}">Sản phẩm</a></li>
              <li><a href="{{ route('client.blog') }}">Tin tức</a></li>
              <li><a href="#">Chính sách bảo hành</a></li>
              <li><a href="#">Hướng dẫn mua hàng</a></li>
            </ul>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="widget">
            <h5 class="widget-title font-alt">Danh mục sản phẩm</h5>
            <ul class="icon-list">
              <li><a href="{{ route('client.product') }}">Tất cả sản phẩm</a></li>
              <li><a href="{{ route('client.product') }}?category=1">Sản phẩm nổi bật</a></li>
              <li><a href="{{ route('client.product') }}?category=2">Sản phẩm mới</a></li>
              <li><a href="{{ route('client.product') }}?category=3">Sản phẩm khuyến mãi</a></li>
              <li><a href="{{ route('client.product') }}?category=4">Sản phẩm bán chạy</a></li>
            </ul>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="widget">
            <h5 class="widget-title font-alt">Tin tức mới nhất</h5>
            <ul class="widget-posts">
              <li class="clearfix">
                <div class="widget-posts-image"><a href="{{ route('client.blog') }}"><img src="{{ asset('client/assets/images/rp-1.jpg') }}" alt="Tin tức"/></a></div>
                <div class="widget-posts-body">
                  <div class="widget-posts-title"><a href="{{ route('client.blog') }}">Sản phẩm mới ra mắt</a></div>
                  <div class="widget-posts-meta">23 tháng 1</div>
                </div>
              </li>
              <li class="clearfix">
                <div class="widget-posts-image"><a href="{{ route('client.blog') }}"><img src="{{ asset('client/assets/images/rp-2.jpg') }}" alt="Tin tức"/></a></div>
                <div class="widget-posts-body">
                  <div class="widget-posts-title"><a href="{{ route('client.blog') }}">Khuyến mãi đặc biệt</a></div>
                  <div class="widget-posts-meta">15 tháng 2</div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="divider-d">
  <footer class="footer bg-dark">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <p class="copyright font-alt">&copy; {{ date('Y') }}&nbsp;<a href="{{ route('client.home') }}">Winstar</a>, Tất cả quyền được bảo lưu</p>
        </div>
        <div class="col-sm-6">
          <div class="footer-social-links">
            <a href="#" title="Facebook"><i class="fa fa-facebook"></i></a>
            <a href="#" title="Instagram"><i class="fa fa-instagram"></i></a>
            <a href="#" title="YouTube"><i class="fa fa-youtube"></i></a>
            <a href="#" title="Zalo"><i class="fa fa-comments"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>
