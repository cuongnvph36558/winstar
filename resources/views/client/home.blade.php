@extends('layouts.client')

@section('title', 'Trang chủ - Website bán hàng')

@section('content')



    <section class="home-section home-full-height bg-dark-30" id="home"
        data-background="{{ asset('client/assets/images/section-5.jpg') }}">
        <div class="titan-caption">
            <div class="caption-content">
                <div class="font-alt mb-30 titan-title-size-1">Chào mừng đến với</div>
                <div class="font-alt mb-40 titan-title-size-4">Cửa hàng của chúng tôi</div>
                <a class="section-scroll btn btn-border-w btn-round" href="#products">Khám phá sản phẩm</a>
            </div>
        </div>
    </section>

    <div class="main">
        <!-- Featured Products Section -->
        <section class="module" id="products">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <h2 class="module-title font-alt">Sản phẩm nổi bật</h2>
                        <div class="module-subtitle font-serif">Khám phá những sản phẩm chất lượng cao với giá cả hợp lý</div>
                    </div>
                </div>
                <div class="row multi-columns-row">
                    <div class="col-md-4 col-sm-6 col-xs-12 mb-30">
                        <div class="product-item">
                            <div class="product-image">
                                <img src="{{ asset('client/assets/images/portfolio/grid-portfolio1.jpg') }}" alt="Sản phẩm 1" />
                                <div class="product-overlay">
                                    <a href="#" class="btn btn-round btn-d">Xem chi tiết</a>
                                </div>
                            </div>
                            <div class="product-info text-center mt-20">
                                <h4 class="product-title font-alt">Sản phẩm công nghệ</h4>
                                <div class="product-price font-alt">
                                    <span class="price-new">999.000đ</span>
                                    <span class="price-old">1.299.000đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 mb-30">
                        <div class="product-item">
                            <div class="product-image">
                                <img src="{{ asset('client/assets/images/portfolio/grid-portfolio2.jpg') }}" alt="Sản phẩm 2" />
                                <div class="product-overlay">
                                    <a href="#" class="btn btn-round btn-d">Xem chi tiết</a>
                                </div>
                            </div>
                            <div class="product-info text-center mt-20">
                                <h4 class="product-title font-alt">Phụ kiện thời trang</h4>
                                <div class="product-price font-alt">
                                    <span class="price-new">299.000đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 mb-30">
                        <div class="product-item">
                            <div class="product-image">
                                <img src="{{ asset('client/assets/images/portfolio/grid-portfolio3.jpg') }}" alt="Sản phẩm 3" />
                                <div class="product-overlay">
                                    <a href="#" class="btn btn-round btn-d">Xem chi tiết</a>
                                </div>
                            </div>
                            <div class="product-info text-center mt-20">
                                <h4 class="product-title font-alt">Sản phẩm thiết kế</h4>
                                <div class="product-price font-alt">
                                    <span class="price-new">599.000đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <a href="{{ route('client.product') }}" class="btn btn-border-d btn-round">Xem tất cả sản phẩm</a>
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
                        <div class="module-subtitle font-serif">Cam kết mang đến trải nghiệm mua sắm tốt nhất cho khách hàng</div>
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
                        <div class="module-subtitle font-serif">Cam kết mang đến những dịch vụ chất lượng cao nhất cho khách hàng</div>
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
                            <p>Dịch vụ giao hàng nhanh chóng, đảm bảo sản phẩm đến tay khách hàng trong thời gian sớm nhất.</p>
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
                                        "Sản phẩm chất lượng tuyệt vời, dịch vụ chăm sóc khách hàng rất chu đáo. Tôi sẽ tiếp tục ủng hộ cửa hàng!"
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
                                        "Giao hàng nhanh chóng, đóng gói cẩn thận. Website dễ sử dụng, thanh toán tiện lợi. Rất hài lòng!"
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
                                <input class="form-control" type="text" id="name" name="name" placeholder="Họ và tên*"
                                    required="required" data-validation-required-message="Vui lòng nhập họ tên của bạn." />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Địa chỉ email*"
                                    required="required"
                                    data-validation-required-message="Vui lòng nhập địa chỉ email." />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="7" id="message" name="message"
                                    placeholder="Nội dung tin nhắn*" required="required"
                                    data-validation-required-message="Vui lòng nhập nội dung tin nhắn."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-block btn-round btn-d" id="cfsubmit" type="submit">Gửi tin nhắn</button>
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
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        background: rgba(0,0,0,0.7);
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
    </style>
@endsection