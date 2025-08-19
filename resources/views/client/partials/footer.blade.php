<!-- Footer Section -->
<footer class="footer-section">
  <!-- Main Footer -->
  <div class="footer-main">
    <div class="container">
      <div class="row">
        <!-- Company Info -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="footer-widget">
            <div class="footer-logo mb-3">
              <h3 class="text-white font-weight-bold">Winstar</h3>
            </div>
            <p class="footer-description">
              Winstar - Cửa hàng sản phẩm chất lượng cao với nhiều lựa chọn đa dạng. 
              Chúng tôi cam kết mang đến những sản phẩm tốt nhất cho khách hàng với 
              dịch vụ chăm sóc khách hàng tận tâm.
            </p>
            <div class="contact-info">
              <div class="contact-item">
                <i class="fas fa-phone-alt"></i>
                <span>Điện thoại: <strong>0567899999</strong></span>
              </div>
              <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <span>Email: <a href="mailto:winstar@gmail.com">winstar@gmail.com</a></span>
              </div>
              <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>Cổng Ong, Tòa nhà FPT Polytechnic<br>13 Trịnh Văn Bô, Phường Xuân Phương, TP Hà Nội</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer Service -->
        <div class="col-lg-2 col-md-6 mb-4">
          <div class="footer-widget">
            <h5 class="widget-title">Dịch vụ khách hàng</h5>
            <ul class="footer-links">
              <li><a href="{{ route('client.about') }}"><i class="fas fa-angle-right"></i> Giới thiệu</a></li>
              <li><a href="{{ route('client.contact') }}"><i class="fas fa-angle-right"></i> Liên hệ</a></li>
              <li><a href="{{ route('client.product') }}"><i class="fas fa-angle-right"></i> Sản phẩm</a></li>
              <li><a href="{{ route('client.blog') }}"><i class="fas fa-angle-right"></i> Tin tức</a></li>
              <li><a href="#"><i class="fas fa-angle-right"></i> Chính sách bảo hành</a></li>
              <li><a href="#"><i class="fas fa-angle-right"></i> Đổi trả & Hoàn tiền</a></li>
              <li><a href="#"><i class="fas fa-angle-right"></i> Hướng dẫn mua hàng</a></li>
            </ul>
          </div>
        </div>

        <!-- Product Categories -->
        <div class="col-lg-2 col-md-6 mb-4">
          <div class="footer-widget">
            <h5 class="widget-title">Thương hiệu</h5>
            <ul class="footer-links">
              <li><a href="{{ route('client.product') }}"><i class="fas fa-angle-right"></i> Tất cả sản phẩm</a></li>
              <li><a href="{{ route('client.product') }}?category_id=1"><i class="fas fa-angle-right"></i> Apple iPhone</a></li>
              <li><a href="{{ route('client.product') }}?category_id=2"><i class="fas fa-angle-right"></i> Samsung</a></li>
              <li><a href="{{ route('client.product') }}?category_id=3"><i class="fas fa-angle-right"></i> Xiaomi</a></li>
              <li><a href="{{ route('client.product') }}?category_id=4"><i class="fas fa-angle-right"></i> OPPO</a></li>
              <li><a href="{{ route('client.product') }}?category_id=5"><i class="fas fa-angle-right"></i> Vivo</a></li>
            </ul>
          </div>
        </div>

        <!-- Latest News -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="footer-widget">
            <h5 class="widget-title">Tin tức công nghệ</h5>
            <div class="latest-news">
              @php
                $latestPosts = \App\Models\Post::with('author')
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->orderByDesc('published_at')
                    ->limit(2)
                    ->get();
              @endphp
              
              @forelse($latestPosts as $post)
                <div class="news-item">
                  <div class="news-image">
                    @if($post->image)
                      <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid">
                    @else
                      <img src="{{ asset('client/assets/images/rp-1.jpg') }}" alt="{{ $post->title }}" class="img-fluid">
                    @endif
                  </div>
                  <div class="news-content">
                    <h6><a href="{{ route('client.posts.show', $post->id) }}">{{ Str::limit($post->title, 40) }}</a></h6>
                    <span class="news-date">
                      <i class="far fa-calendar-alt"></i> 
                      {{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Chưa đăng' }}
                    </span>
                  </div>
                </div>
              @empty
                <div class="news-item">
                  <div class="news-image">
                    <img src="{{ asset('client/assets/images/rp-1.jpg') }}" alt="Tin tức" class="img-fluid">
                  </div>
                  <div class="news-content">
                    <h6><a href="{{ route('client.blog') }}">Chưa có tin tức mới</a></h6>
                    <span class="news-date"><i class="far fa-calendar-alt"></i> Vui lòng quay lại sau</span>
                  </div>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</footer>
