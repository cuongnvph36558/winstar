@extends('layouts.client')

@section('title', 'Trang chủ - Website bán hàng')

@section('content')
{{-- Banner --}}
<section class="home-section home-fade home-full-height" id="home">
    <!-- Floating Particles -->
    <div class="banner-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="hero-slider">
        <div class="slides-container">
            @foreach ($banners as $banner)
            <div class="slide">
                <div class="home-slider-container">
                    @if ($banner->image_url)
                    <img src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}"
                        class="home-slider-image">
                    @else
                    <img src="{{ asset('client/assets/images/default-banner.jpg') }}" alt="Default Banner"
                        class="home-slider-image">
                    @endif
                    <div class="hero-slider-content text-center">
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
                    <div class="module-subtitle font-serif">
                        Khám phá những sản phẩm chất lượng cao với giá cả hợp lý
                    </div>
                </div>
            </div>
            <div class="row multi-columns-row">
                @foreach($productBestSeller->take(6) as $product)
                <div class="col-md-4 col-sm-6 col-xs-12 mb-30">
                    <div class="product-item product-item-box">
                        <div class="product-image" style="height: 220px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            @if($product && $product->image_url)
                            <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" style="height: 100%; width: 100%; object-fit: cover;" />
                            @else
                            <img src="{{ asset('client/assets/images/portfolio/grid-portfolio1.jpg') }}" alt="Default Product Image" style="height: 100%; width: 100%; object-fit: cover;" />
                            @endif
                            <div class="product-overlay">
                                <a href="{{ route('client.single-product', $product->id) }}" class="btn btn-round btn-d">Xem chi tiết</a>
                            </div>
                        </div>
                        <div class="product-info text-center mt-20">
                            <h4 class="product-title font-alt">{{ $product->name }}</h4>
                            <div class="product-price font-alt">
                                @php
                                $minPrice = $product->variants ? $product->variants->min('price') : 0;
                                @endphp
                                @if($product->variants && $product->variants->count() > 0 && $minPrice > 0)
                                <span class="price-new">{{ number_format($minPrice, 0, '.', '.') }}₫</span>
                                @elseif($product->price > 0)
                                <span class="price-new">{{ number_format($product->price, 0, '.', '.') }}₫</span>
                                @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="price-old">{{ number_format($product->compare_price, 0, '.', '.') }}₫</span>
                                @endif
                                @else
                                <span class="price-new text-muted">Liên hệ</span>
                                @endif
                            </div>
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


    <!-- Content 1 -->
    <section class="module bg-light" id="features" style="margin-top: 100px; padding-top: 80px; padding-bottom: 80px;">
        <div class="container">
            <div class="row mb-4 text-center">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="module-title font-alt mb-3">{{ $feature->title }}</h2>
                    <p class="module-subtitle font-serif">{{ $feature->subtitle }}</p>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="text-center position-relative">
                        <div class="feature-image-container" style="position: relative; display: inline-block; width: 100%;">
                            <img src="{{ asset('storage/' . $feature->image) }}" class="img-responsive rounded shadow feature-main-image"
                                alt="Dịch vụ" style="width: 100%; height: auto; max-height: 600px; object-fit: cover; position: relative; z-index: 2;">
                            <div class="feature-image-bg" style="position: absolute; top: 15px; left: 15px; right: -15px; bottom: -15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; z-index: 1; opacity: 0.08;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3">
                        @if($feature && $feature->items)
                        @foreach($feature->items as $item)
                        <div class="col-sm-6 mb-3">
                            <div class="alt-features-item text-center p-4 border rounded h-100" style="background: #fff; box-shadow: 0 3px 12px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #f0f0f0;">
                                <div class="alt-features-icon mb-3">
                                    <span class="{{ $item->icon ?? 'fa fa-star' }} fa-2x text-primary"></span>
                                </div>
                                <h5 class="alt-features-title font-alt mb-3" style="font-size: 1rem; font-weight: 600;">{{ $item->title ?? 'Feature' }}</h5>
                                <p class="text-muted" style="font-size: 0.85rem; line-height: 1.4;">{{ $item->description ?? 'Description' }}</p>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Favorite Products Section with 3 visible items and horizontal scroll -->
    <section class="module" id="favorites">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 text-center">
                    <h2 class="module-title font-alt">Top sản phẩm được yêu thích</h2>
                    <div class="module-subtitle font-serif">Dựa trên lượt yêu thích và lượt xem</div>
                </div>
            </div>

            <div class="position-relative overflow-hidden">
                <div class="product-carousel d-flex px-2 py-3" id="productCarousel"
                    style="scroll-snap-type: x mandatory; overflow-x: auto; gap: 120px; -webkit-overflow-scrolling: touch;">
                    @foreach ($productsFavorite as $product)
                    @if ($product)
                    <div class="flex-shrink-0" style="scroll-snap-align: start; width: 33.3333%; min-width: 300px; max-width: 33.3333%;">
                        <div class="product-item" style="box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-radius: 8px; background: #fff; overflow: hidden;">
                            <div class="product-image" style="height: 220px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('/images/no-image.png') }}" alt="{{ $product->name }}" style="height: 100%; width: 100%; object-fit: cover;" />
                                <div class="product-overlay">
                                    <a href="{{ route('client.single-product', $product->id) }}" class="btn btn-round btn-d">Xem chi tiết</a>
                                </div>
                            </div>
                            <div class="product-info text-center mt-20 p-3">
                                <h4 class="product-title font-alt">{{ $product->name }}</h4>
                                <div class="product-price font-alt">
                                    @php
                                    $minPrice = $product->variants ? $product->variants->min('price') : 0;
                                    @endphp
                                    @if($product->variants && $product->variants->count() > 0 && $minPrice > 0)
                                    <span class="price-new">{{ number_format($minPrice, 0, '.', '.') }}₫</span>
                                    @elseif($product->price > 0)
                                    <span class="price-new">{{ number_format($product->price, 0, '.', '.') }}₫</span>
                                    @if($product->compare_price && $product->compare_price > $product->price)
                                    <span class="price-old">{{ number_format($product->compare_price, 0, '.', '.') }}₫</span>
                                    @endif
                                    @else
                                    <span class="price-new text-muted">Liên hệ</span>
                                    @endif
                                </div>
                                <small class="text-muted">Yêu thích: {{ $product->favorites_count }} | Lượt xem: {{ $product->view }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

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
                @foreach($latestPosts as $post)
                <div class="col-sm-6 col-md-4 col-lg-4">
                    <div class="post mb-20">
                        <div class="post-thumbnail">
                            <a href="{{ route('client.posts.show', $post->id) }}">
                                <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('client/assets/images/default.jpg') }}"
                                    alt="{{ $post->title }}" class="img-fluid w-100"
                                    style="height: 220px; object-fit: cover; border-radius: 4px;" />

                            </a>
                        </div>
                        <div class="post-header font-alt">
                            <h2 class="post-title">
                                <a href="{{ route('client.posts.show', $post->id) }}">{{ $post->title }}</a>
                            </h2>
                            <div class="post-meta">
                                By <a href="#">{{ $post->author->name ?? 'Admin' }}</a> |
                                {{ $post->published_at->format('d F') }} |
                                {{ $post->comments_count ?? 0 }} Comments
                            </div>
                        </div>
                        <div class="post-entry">
                            <p>{{ Str::limit(strip_tags($post->content), 100) }}</p>
                        </div>
                        <div class="post-more">
                            <a class="more-link" href="{{ route('client.posts.show', $post->id) }}">Đọc thêm</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row mt-40">
                <div class="col-sm-12 text-center">
                    <a href="{{ route('client.blog') }}" class="btn btn-border-d btn-round">Xem tất cả bài viết</a>
                </div>
            </div>
        </div>
    </section>


    <!-- Video Section -->
    @if ($mainVideo && $mainVideo->video_path)
    <section class="module video-section-fullscreen" data-background="{{ asset('client/assets/images/section-6.jpg') }}">
        <!-- Background Video Reflection -->
        <div class="video-background-reflection">
            <video class="background-video" autoplay muted loop>
                <source src="{{ asset('storage/' . $mainVideo->video_path) }}" type="video/mp4">
            </video>
        </div>

        <div class="video-container-fullscreen">
            <!-- Video Header -->
            <div class="video-header-fullscreen">
                <h2 class="video-title-fullscreen">Khám phá Winstar</h2>
                <p class="video-subtitle-fullscreen">Trải nghiệm thế giới công nghệ hiện đại với những sản phẩm chất lượng cao</p>
            </div>

            <!-- Video Player -->
            <div class="video-player-fullscreen">
                <div class="video-play-overlay-fullscreen" id="video-play-overlay">
                    <div class="play-button-fullscreen" onclick="playVideo();">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="M8 5v14l11-7z" fill="currentColor" />
                        </svg>
                    </div>
                </div>
                <video id="main-video"
                    class="video-player-fullscreen-element"
                    poster="{{ $mainVideo->background ? asset('storage/' . $mainVideo->background) : asset('client/assets/images/section-6.jpg') }}"
                    autoplay muted playsinline loop>
                    <source src="{{ asset('storage/' . $mainVideo->video_path) }}" type="video/mp4">
                    Trình duyệt không hỗ trợ video.
                </video>
            </div>
        </div>
    </section>
    @endif

    <!-- Services Section -->
    <section class="module" id="services">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="module-title font-alt">Dịch vụ của chúng tôi</h2>
                    <div class="module-subtitle font-serif">Cam kết mang đến những dịch vụ chất lượng cao nhất cho khách hàng</div>
                </div>
            </div>
            <div class="row multi-columns-row">
                @forelse($services as $service)
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="features-item">
                            <div class="features-icon">
                                <span class="{{ $service->icon }} fa-2x text-dark"></span>
                            </div>
                            <h3 class="features-title font-alt">{{ $service->title }}</h3>
                            <p>{{ $service->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Chưa có dịch vụ nào được cập nhật.</p>
                    </div>
                @endforelse
            </div>
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
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="contactForm" role="form" method="post" action="{{ route('client.contact.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="sr-only" for="subject">Tiêu đề</label>
                            <input class="form-control @error('subject') is-invalid @enderror" type="text" id="subject" name="subject" placeholder="Tiêu đề tin nhắn*"
                                required="required" value="{{ old('subject') }}" />
                            @error('subject')
                                <p class="help-block text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="message">Nội dung</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" rows="7" id="message" name="message"
                                placeholder="Nội dung tin nhắn*" required="required">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="help-block text-danger">{{ $message }}</p>
                            @enderror
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
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        image-rendering: pixelated;
    }

    .product-item:hover .product-image img {
        transform: scale(1.05);
    }

    /* Ensure all images are sharp */
    img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        image-rendering: pixelated;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
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
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        image-rendering: pixelated;
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

    .slide img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        width: 100%;
        height: 100%;
        display: block;
        margin: 0 auto;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        image-rendering: pixelated;
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

    .product-carousel {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        gap: 20px;
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
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        image-rendering: pixelated;
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

    .carousel-nav button {
        background: black;
        color: white;
        padding: 5px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    #productCarousel.dragging {
        cursor: grabbing;
        scroll-behavior: auto;
    }

    /* Ẩn thanh scrollbar của carousel */
    #productCarousel::-webkit-scrollbar {
        display: none;
    }
    
    #productCarousel {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .product-item {
        box-shadow: none !important;
        border: none !important;
    }

    .video-section-fullscreen {
        position: relative;
        height: 100vh;
        width: 100%;
        overflow: hidden;
    }

    .video-background-reflection {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
    }

    .background-video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%) scale(1.2);
        filter: brightness(0.3) contrast(1.2);
        opacity: 0.6;
        object-fit: cover;
    }

    .video-container-fullscreen {
        position: relative;
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        text-align: center;
        z-index: 2;
        padding-top: 40px;
    }

    .video-header-fullscreen {
        position: relative;
        z-index: 10;
        max-width: 600px;
        padding: 0 20px;
        margin-bottom: 40px;
    }

    .video-title-fullscreen {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-bottom: 15px;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
        letter-spacing: -1px;
        background: linear-gradient(45deg, #ffffff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .video-subtitle-fullscreen {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 400;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.7);
    }

    .video-player-fullscreen {
        position: relative;
        width: 100%;
        max-width: 1200px;
        height: 600px;
        z-index: 1;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
    }

    .video-player-fullscreen-element {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .video-play-overlay-fullscreen {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 5;
    }

    .video-play-overlay-fullscreen:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    .play-button-fullscreen {
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        color: #000;
    }

    .play-button-fullscreen:hover {
        transform: scale(1.1);
        background: white;
        box-shadow: 0 16px 50px rgba(0, 0, 0, 0.5);
    }

    .play-button-fullscreen svg {
        margin-left: 4px;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .video-title-fullscreen {
            font-size: 3.5rem;
        }

        .video-subtitle-fullscreen {
            font-size: 1.3rem;
        }

        .video-player-fullscreen {
            max-width: 800px;
            height: 450px;
        }
    }

    @media (max-width: 992px) {
        .video-container-fullscreen {
            padding-top: 60px;
        }

        .video-title-fullscreen {
            font-size: 3rem;
        }

        .video-subtitle-fullscreen {
            font-size: 1.2rem;
        }

        .video-player-fullscreen {
            max-width: 700px;
            height: 400px;
        }

        .play-button-fullscreen {
            width: 100px;
            height: 100px;
        }
    }

    @media (max-width: 768px) {
        .video-container-fullscreen {
            padding-top: 40px;
        }

        .video-header-fullscreen {
            margin-bottom: 40px;
        }

        .video-title-fullscreen {
            font-size: 2.5rem;
        }

        .video-subtitle-fullscreen {
            font-size: 1.1rem;
            padding: 0 20px;
        }

        .video-player-fullscreen {
            max-width: 100%;
            height: 350px;
            margin: 0 15px;
        }

        .play-button-fullscreen {
            width: 80px;
            height: 80px;
        }
    }

    @media (max-width: 480px) {
        .video-container-fullscreen {
            padding-top: 30px;
        }

        .video-title-fullscreen {
            font-size: 2rem;
        }

        .video-subtitle-fullscreen {
            font-size: 1rem;
        }

        .video-player-fullscreen {
            height: 300px;
            border-radius: 12px;
        }

        .play-button-fullscreen {
            width: 70px;
            height: 70px;
        }
    }
</style>


<script>
    function playVideo() {
        const video = document.getElementById('main-video');
        const overlay = document.getElementById('video-play-overlay');

        if (video) {
            video.play();
            overlay.style.display = 'none';
        }
    }

    // Ẩn overlay play button nếu có
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('video-play-overlay');
        if (overlay) overlay.style.display = 'none';
    });

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

    /* CSS suggestions to add to your stylesheet */
    const style = document.createElement('style');
    style.innerHTML = `
                      .hero-slider {
                        overflow: hidden;
                        position: relative;
                        height: 100vh;
                      }
                      .slides-container {
                        display: flex;
                        transition: transform 0.6s ease-in-out;
                        width: 100%;
                        height: 100%;
                      }
                      .slide {
                        min-width: 100%;
                        flex-shrink: 0;
                        position: relative;
                        height: 100%;
                      }
                      .slide img {
                        max-width: 100%;
                        max-height: 100%;
                        object-fit: contain;
                        width: 100%;
                        height: 100%;
                        display: block;
                        margin: 0 auto;
                      }
                    `;
    document.head.appendChild(style);

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
    setInterval(nextSlide, 3000);


    // Auto-scroll cho sản phẩm yêu thích (không có thanh progress)
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('productCarousel');
        if (!container) return;
        
        const items = Array.from(container.children);
        if (items.length === 0) return;
        
        const itemWidth = items[0].offsetWidth + 120; // includes gap
        let position = 0;

        // Clone items for infinite scroll effect
        items.forEach(item => {
            const clone = item.cloneNode(true);
            container.appendChild(clone);
        });

        function scrollCarousel() {
            position += itemWidth;
            container.scrollTo({
                left: position,
                behavior: 'smooth'
            });

            // Reset position if we've scrolled past all original items
            if (position >= itemWidth * items.length) {
                setTimeout(() => {
                    container.scrollLeft = 0;
                    position = 0;
                }, 400); // wait for smooth scroll to finish
            }
        }

        let interval = setInterval(scrollCarousel, 3000);

        // Pause on hover
        container.addEventListener('mouseenter', () => {
            clearInterval(interval);
        });
        
        container.addEventListener('mouseleave', () => {
            interval = setInterval(scrollCarousel, 3000);
        });
    });
</script>
@endsection