@extends('layouts.client')

@section('title', 'Trang chủ - Website bán hàng')

@section('content')
    {{-- Banner --}}
    <section class="home-section home-fade home-full-height" id="home">
        <div class="hero-slider">
            <div class="slides-container">
                @foreach ($banners as $banner)
                    <div class="slide">
                        <div class="overlay"></div>
                        <div class="home-slider-container">
                            @if ($banner->image_url)
                                <img src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}"
                                    class="home-slider-image">
                            @else
                                <img src="{{ asset('client/assets/images/default-banner.jpg') }}" alt="Default Banner"
                                    class="home-slider-image">
                            @endif
                            <div class="hero-slider-content text-center">
                                @if ($banner->title)
                                    <h2 class="font-alt mb-20">{{ $banner->title }}</h2>
                                @endif
                                @if ($banner->description)
                                    <p class="lead mb-30">{{ $banner->description }}</p>
                                @endif
                                @if ($banner->button_text)
                                    <a class="btn btn-border-w btn-round" href="{{ $banner->button_link ?? '#' }}">
                                        {{ $banner->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Buttons -->
            <button class="slider-nav prev-slide">&#10094;</button>
            <button class="slider-nav next-slide">&#10095;</button>
        </div>
    </section>

    <div class="main">
        <!-- Featured Products Section -->
        <section class="module" id="products">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="module-title font-alt">Sản phẩm bán chạy</h2>
                        <div class="module-subtitle font-serif">Khám phá những sản phẩm chất lượng cao với giá cả hợp lý
                        </div>
                    </div>
                </div>
                <div class="row multi-columns-row">
                    @foreach($productBestSeller as $product)
                    <div class="col-md-4 col-sm-6 col-xs-12 mb-30">
                        <div class="product-item">
                            <div class="product-image">
                                @if($product->product && $product->product->image_url)
                                    <img src="{{ asset('storage/' . $product->product->image_url) }}" alt="{{ $product->product->name }}" />
                                @else
                                    <img src="{{ asset('client/assets/images/portfolio/grid-portfolio1.jpg') }}" alt="Default Product Image" />
                                @endif
                                <div class="product-overlay">
                                    @if($product->product)
                                        <a href="{{ route('client.single-product', $product->product->id) }}" class="btn btn-round btn-d">Xem chi tiết</a>
                                    @else
                                        <span class="btn btn-round btn-d disabled">Không khả dụng</span>
                                    @endif
                                </div>
                            </div>
                            <div class="product-info text-center mt-20">
                                @if($product->product)
                                    <h4 class="product-title font-alt">{{ $product->product->name }}</h4>
                                    <div class="product-price font-alt">
                                        @if($product->product && $product->product->price)
                                            <span class="price-new">{{ number_format($product->product->price, 0, ',', '.') }}đ</span>
                                        @endif
                                        @if($product->product->compare_price)
                                            <span class="price-old">{{ number_format($product->product->compare_price, 0, ',', '.') }}đ</span>
                                        @endif
                                    </div>
                                @else
                                    <h4 class="product-title font-alt">Sản phẩm không tồn tại</h4>
                                    <div class="product-price font-alt">
                                        <span class="price-new">Liên hệ</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="module bg-light" id="features">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="module-title font-alt">Tại sao chọn chúng tôi</h2>
                        <div class="module-subtitle font-serif">Cam kết mang đến trải nghiệm mua sắm tốt nhất cho khách hàng
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="alt-services-image align-center">
                            <img src="{{ asset('client/assets/images/promo.png') }}" alt="Dịch vụ">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="alt-features-item">
                                    <div class="alt-features-icon"><span class="icon-strategy"></span></div>
                                    <h3 class="alt-features-title font-alt">Chất lượng cao</h3>
                                    Sản phẩm được tuyển chọn kỹ lưỡng với chất lượng đảm bảo và giá cả hợp lý.
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="alt-features-item">
                                    <div class="alt-features-icon"><span class="icon-tools-2"></span></div>
                                    <h3 class="alt-features-title font-alt">Giao hàng nhanh</h3>
                                    Giao hàng toàn quốc với thời gian nhanh chóng và an toàn.
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="alt-features-item">
                                    <div class="alt-features-icon"><span class="icon-mobile"></span></div>
                                    <h3 class="alt-features-title font-alt">Thanh toán đa dạng</h3>
                                    Hỗ trợ nhiều hình thức thanh toán tiện lợi và bảo mật.
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="alt-features-item">
                                    <div class="alt-features-icon"><span class="icon-lifesaver"></span></div>
                                    <h3 class="alt-features-title font-alt">Hỗ trợ 24/7</h3>
                                    Đội ngũ chăm sóc khách hàng tận tình, hỗ trợ 24/7.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Favorite Products Section with 3 visible items and horizontal scroll -->
@if (isset($products) && $products->count())
    <section class="module bg-light" id="favorites">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <h2 class="module-title font-alt">Top sản phẩm được yêu thích</h2>
                <div class="module-subtitle font-serif">Dựa trên lượt yêu thích và lượt xem</div>
            </div>
        </div>

        <div class="row">
            <div class="favorite-products-wrapper">
                @foreach ($products as $product)
                    <div class="col-md-4 col-sm-6 mb-30">
                        <div class="favorite-product-item">
                            <div class="favorite-product-image">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('client/assets/images/portfolio/grid-portfolio1.jpg') }}" alt="Default Product Image">
                                @endif
                                <div class="favorite-product-overlay">
                                    <a href="{{ route('client.single-product', $product->id) }}" class="btn btn-round btn-d">Xem chi tiết</a>
                                </div>
                            </div>
                            <div class="favorite-product-info text-center">
                                <h4 class="favorite-product-title font-alt">{{ $product->name }}</h4>
                                <div class="favorite-product-price font-alt">
                                    @if($product->price)
                                        <span class="price-new">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        @if($product->compare_price)
                                            <span class="price-old">{{ number_format($product->compare_price, 0, ',', '.') }}đ</span>
                                        @endif
                                    @else
                                        <span class="price-new">Liên hệ</span>
                                    @endif
                                </div>
                                <div class="favorite-product-stats">
                                    <small>♥ {{ $product->favorites_count ?? 0 }} | 👁 {{ $product->view ?? 0 }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endif

        <!-- Blog Section -->
        <section class="module" id="news">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="module-title font-alt">Tin tức mới nhất</h2>
                        <div class="module-subtitle font-serif">Cập nhật những tin tức, xu hướng mới nhất trong ngành</div>
                    </div>
                </div>
                <div class="row multi-columns-row post-columns">
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="post mb-20">
                            <div class="post-thumbnail">
                                <a href="#">
                                    <img src="{{ asset('client/assets/images/post-1.jpg') }}"
                                        alt="Blog-post Thumbnail" />
                                </a>
                            </div>
                            <div class="post-header font-alt">
                                <h2 class="post-title"><a href="#">Xu hướng thời trang mùa hè 2024</a></h2>
                                <div class="post-meta">
                                    By <a href="#">Admin</a> | 23 November | 3 Comments
                                </div>
                            </div>
                            <div class="post-entry">
                                <p>Khám phá những xu hướng thời trang hot nhất trong mùa hè năm nay...</p>
                            </div>
                            <div class="post-more"><a class="more-link" href="#">Đọc thêm</a></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="post mb-20">
                            <div class="post-thumbnail">
                                <a href="#">
                                    <img src="{{ asset('client/assets/images/post-2.jpg') }}"
                                        alt="Blog-post Thumbnail" />
                                </a>
                            </div>
                            <div class="post-header font-alt">
                                <h2 class="post-title"><a href="#">Bí quyết phối đồ công sở</a></h2>
                                <div class="post-meta">
                                    By <a href="#">Admin</a> | 15 November | 5 Comments
                                </div>
                            </div>
                            <div class="post-entry">
                                <p>Những gợi ý hữu ích giúp bạn luôn tự tin và chuyên nghiệp nơi công sở...</p>
                            </div>
                            <div class="post-more"><a class="more-link" href="#">Đọc thêm</a></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="post mb-20">
                            <div class="post-thumbnail">
                                <a href="#">
                                    <img src="{{ asset('client/assets/images/post-3.jpg') }}"
                                        alt="Blog-post Thumbnail" />
                                </a>
                            </div>
                            <div class="post-header font-alt">
                                <h2 class="post-title"><a href="#">Chăm sóc trang phục đúng cách</a></h2>
                                <div class="post-meta">
                                    By <a href="#">Admin</a> | 8 November | 2 Comments
                                </div>
                            </div>
                            <div class="post-entry">
                                <p>Hướng dẫn chi tiết cách bảo quản và chăm sóc quần áo để giữ form dáng lâu...</p>
                            </div>
                            <div class="post-more"><a class="more-link" href="#">Đọc thêm</a></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-40">
                    <div class="col-sm-12 text-center">
                        <a href="{{ route('client.blog') }}" class="btn btn-border-d btn-round">Xem tất cả bài viết</a>
                    </div>
                </div>
            </div>
        </section>


        <!-- Video Section -->
        <section class="module bg-dark-60" data-background="{{ asset('client/assets/images/section-6.jpg') }}">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="video-box">
                            <div class="video-box-icon">
                                <a class="video-pop-up" href="https://www.youtube.com/watch?v=TTxZj3DZiIM">
                                    <span class="icon-video"></span>
                                </a>
                            </div>
                            <div class="video-title font-alt">Video giới thiệu</div>
                            <div class="video-subtitle font-alt">Khám phá thế giới sản phẩm của chúng tôi</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="module" id="services">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="module-title font-alt">Dịch vụ của chúng tôi</h2>
                        <div class="module-subtitle font-serif">Cam kết mang đến những dịch vụ chất lượng cao nhất cho
                            khách
                            hàng</div>
                    </div>
                </div>
                <div class="row multi-columns-row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="features-item">
                            <div class="features-icon"><span class="icon-basket"></span></div>
                            <h3 class="features-title font-alt">Mua sắm trực tuyến</h3>
                            <p>Trải nghiệm mua sắm tiện lợi, dễ dàng với giao diện thân thiện và quy trình đơn giản.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="features-item">
                            <div class="features-icon"><span class="icon-bike"></span></div>
                            <h3 class="features-title font-alt">Giao hàng tận nơi</h3>
                            <p>Dịch vụ giao hàng nhanh chóng, đảm bảo sản phẩm đến tay khách hàng trong thời gian sớm nhất.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="features-item">
                            <div class="features-icon"><span class="icon-tools"></span></div>
                            <h3 class="features-title font-alt">Bảo hành sản phẩm</h3>
                            <p>Chế độ bảo hành toàn diện, đổi trả linh hoạt đảm bảo quyền lợi tốt nhất cho khách hàng.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="features-item">
                            <div class="features-icon"><span class="icon-genius"></span></div>
                            <h3 class="features-title font-alt">Tư vấn chuyên nghiệp</h3>
                            <p>Đội ngũ tư vấn viên giàu kinh nghiệm, hỗ trợ khách hàng chọn lựa sản phẩm phù hợp nhất.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="features-item">
                            <div class="features-icon"><span class="icon-mobile"></span></div>
                            <h3 class="features-title font-alt">Ứng dụng di động</h3>
                            <p>Mua sắm mọi lúc mọi nơi với ứng dụng di động tiện lợi, tối ưu trải nghiệm người dùng.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="features-item">
                            <div class="features-icon"><span class="icon-lifesaver"></span></div>
                            <h3 class="features-title font-alt">Chăm sóc khách hàng</h3>
                            <p>Dịch vụ chăm sóc khách hàng tận tâm, giải đáp mọi thắc mắc và hỗ trợ kịp thời.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="module bg-dark-60 pt-0 pb-0 parallax-bg testimonial"
            data-background="{{ asset('client/assets/images/testimonial_bg.jpg') }}">
            <div class="testimonials-slider pt-140 pb-140">
                <ul class="slides">
                    <li>
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="module-icon"><span class="icon-quote"></span></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <blockquote class="testimonial-text font-alt">
                                        "Sản phẩm chất lượng tuyệt vời, dịch vụ chăm sóc khách hàng rất chu đáo. Tôi sẽ tiếp
                                        tục ủng hộ cửa hàng!"
                                    </blockquote>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <div class="testimonial-author">
                                        <div class="testimonial-caption font-alt">
                                            <div class="testimonial-title">Nguyễn Văn An</div>
                                            <div class="testimonial-descr">Khách hàng thân thiết</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="module-icon"><span class="icon-quote"></span></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <blockquote class="testimonial-text font-alt">
                                        "Giao hàng nhanh chóng, đóng gói cẩn thận. Website dễ sử dụng, thanh toán tiện lợi.
                                        Rất hài lòng!"
                                    </blockquote>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <div class="testimonial-author">
                                        <div class="testimonial-caption font-alt">
                                            <div class="testimonial-title">Trần Thị Mai</div>
                                            <div class="testimonial-descr">Khách hàng VIP</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <!-- Newsletter Section -->
        <div class="module-small bg-dark">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-4 col-lg-offset-2">
                        <div class="callout-text font-alt">
                            <h3 class="callout-title">Đăng ký nhận tin</h3>
                            <p>Nhận thông tin khuyến mãi và sản phẩm mới nhất</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="callout-btn-box">
                            <form id="subscription-form" role="form" method="post" action="php/subscribe.php">
                                <div class="input-group">
                                    <input class="form-control" type="email" id="semail" name="semail"
                                        placeholder="Địa chỉ email của bạn"
                                        data-validation-required-message="Vui lòng nhập địa chỉ email."
                                        required="required" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-g btn-round" id="subscription-form-submit" type="submit">
                                            Đăng ký
                                        </button>
                                    </span>
                                </div>
                            </form>
                            <div class="text-center" id="subscription-response"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <section class="module" id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="module-title font-alt">Liên hệ với chúng tôi</h2>
                        <div class="module-subtitle font-serif">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <form id="contactForm" role="form" method="post" action="php/contact.php">
                            <div class="form-group">
                                <label class="sr-only" for="name">Họ tên</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    placeholder="Họ và tên*" required="required"
                                    data-validation-required-message="Vui lòng nhập họ tên của bạn." />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email"
                                    placeholder="Địa chỉ email*" required="required"
                                    data-validation-required-message="Vui lòng nhập địa chỉ email." />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="7" id="message" name="message" placeholder="Nội dung tin nhắn*"
                                    required="required" data-validation-required-message="Vui lòng nhập nội dung tin nhắn."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-block btn-round btn-d" id="cfsubmit" type="submit">Gửi tin
                                    nhắn</button>
                            </div>
                        </form>
                        <div class="ajax-response font-alt" id="contactFormResponse"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .product-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-5px);
        }

        .product-image {
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-item:hover .product-image img {
            transform: scale(1.05);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-item:hover .product-overlay {
            opacity: 1;
        }

        .product-info {
            padding: 15px;
            background: #fff;
        }

        .product-title {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .product-price .price-new {
            color: #e74c3c;
            font-weight: 700;
            font-size: 18px;
        }

        .product-price .price-old {
            color: #999;
            text-decoration: line-through;
            margin-left: 10px;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .home-slider-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .home-slider-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-slider-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            color: #fff;
            z-index: 2;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .slides-container {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            position: relative;
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.3);
            color: white;
            padding: 15px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            z-index: 3;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }

        .slider-nav:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .prev-slide {
            left: 20px;
        }

        .next-slide {
            right: 20px;
        }

        /* Favorite Products Styles */
        .favorite-products-wrapper {
            width: 100%;
        }

        .favorite-product-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
            margin-bottom: 30px;
        }

        .favorite-product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .favorite-product-image {
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .favorite-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .favorite-product-item:hover .favorite-product-image img {
            transform: scale(1.05);
        }

        .favorite-product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .favorite-product-item:hover .favorite-product-overlay {
            opacity: 1;
        }

        .favorite-product-info {
            padding: 20px 15px;
        }

        .favorite-product-title {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .favorite-product-price {
            margin-bottom: 10px;
        }

        .favorite-product-price .price-new {
            color: #e74c3c;
            font-weight: 700;
            font-size: 18px;
        }

        .favorite-product-price .price-old {
            color: #999;
            text-decoration: line-through;
            margin-left: 10px;
            font-size: 14px;
        }

        .favorite-product-stats {
            color: #666;
            font-size: 12px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .favorite-product-item,
            .product-item {
                margin-bottom: 20px;
            }
            
            .favorite-product-image,
            .product-image {
                height: 200px;
            }
            
            .favorite-product-title,
            .product-title {
                font-size: 14px;
            }
            
            .favorite-product-price .price-new,
            .product-price .price-new {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .favorite-product-info,
            .product-info {
                padding: 15px 10px;
            }
        }
    }

    .carousel-nav button {
        background: black;
        color: white;
        padding: 5px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slidesContainer = document.querySelector('.slides-container');
            const slides = document.querySelectorAll('.slide');
            const prevButton = document.querySelector('.prev-slide');
            const nextButton = document.querySelector('.next-slide');

            let currentSlide = 0;
            const totalSlides = slides.length;

            function updateSlidePosition() {
                if (currentSlide >= totalSlides) {
                    currentSlide = 0;
                } else if (currentSlide < 0) {
                    currentSlide = totalSlides - 1;
                }
                slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
            }

            function nextSlide() {
                currentSlide++;
                updateSlidePosition();
            }

            function prevSlide() {
                currentSlide--;
                updateSlidePosition();
            }

            // Event listeners
            nextButton.addEventListener('click', nextSlide);
            prevButton.addEventListener('click', prevSlide);

            // Auto slide every 5 seconds
            setInterval(nextSlide, 5000);
        });

        // slide sp yêu thích
        function scrollCarousel(direction) {
        const carousel = document.getElementById('productCarousel');
        const scrollAmount = carousel.offsetWidth * 1 + 20; // Width of 1 item + gap
        carousel.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    }
    </script>
@endsection
