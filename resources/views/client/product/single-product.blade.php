@extends('layouts.client')

@section('title', 'Chi Tiết Sản Phẩm')

@section('content')
<section class="module">
    <div class="container">
        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-sm-6 mb-sm-40">
                <div class="product-images">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="img-responsive main-product-image" style="cursor:pointer;" />
                    <ul class="product-gallery list-unstyled">
                        @foreach ($product->variants as $variant)
                        @if ($variant->image_variant)
                        @php
                        $images = json_decode($variant->image_variant, true);
                        @endphp
                        @if (is_array($images))
                        @foreach ($images as $image)
                        <li>
                            <img src="{{ asset('storage/' . $image) }}"
                                alt="{{ $product->name }} - {{ ($variant->storage && isset($variant->storage->capacity)) ? $variant->storage->capacity : '' }} {{ ($variant->color && isset($variant->color->name)) ? $variant->color->name : '' }}"
                                class="gallery-thumbnail" style="cursor:pointer;" />
                        </li>
                        @endforeach
                        @endif
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-sm-6">
                <div class="product-info">
                    <h1 class="product-title font-alt mb-20">{{ $product->name }}</h1>

                    <!-- Đánh giá và Yêu thích -->
                    <div class="product-rating mb-20">
                        <div class="stars">
                            @if ($totalReviews > 0)
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <=round($averageRating))
                                <i class="fas fa-star star"></i>
                                @else
                                <i class="fas fa-star star-off"></i>
                                @endif
                                @endfor
                                <span class="rating-text">
                                    ({{ number_format($averageRating, 1) }}/5 - <a class="review-link"
                                        href="#reviews">{{ $totalReviews }}
                                        đánh giá</a>)
                                </span>
                                @else
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star-off"></i>
                                    @endfor
                                    <span class="rating-text">
                                        (<a class="review-link" href="#reviews">Chưa có đánh giá</a>)
                                    </span>
                                    @endif
                        </div>

                        <!-- Favorite Button -->
                        <div class="product-favorite-action">
                            @php
                            $isFavorited = auth()->check() && auth()->user()->favorites()->where('product_id', $product->id)->exists();
                            @endphp
                            <button
                                class="btn-favorite-detail {{ $isFavorited ? 'favorited remove-favorite' : 'add-favorite' }}"
                                data-product-id="{{ $product->id }}"
                                title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                <i class="{{ $isFavorited ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                                <span class="btn-text">{{ $isFavorited ? 'Đã yêu thích' : 'Yêu thích' }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Giá -->
                    <div class="product-price mb-20">
                        @if($product->variants->count() > 0)
                        @php
                        $minPromotion = $product->variants->where('promotion_price', '>', 0)->min('promotion_price');
                        $maxPromotion = $product->variants->where('promotion_price', '>', 0)->max('promotion_price');
                        $minPrice = $product->variants->min('price') ?? 0;
                        $maxPrice = $product->variants->max('price') ?? 0;
                        @endphp
                        <div class="price font-alt">
                            <span class="amount" id="product-price"
                                data-original-price="@if($minPromotion && $minPromotion > 0)
                                            @if($minPromotion == $maxPromotion)
                                                <span class='promotion-price'>{{ number_format($minPromotion, 0, ',', '.') }}đ</span>
                                                <span class='old-price ml-2'>{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                            @else
                                                <span class='promotion-price'>{{ number_format($minPromotion, 0, ',', '.') }}đ - {{ number_format($maxPromotion, 0, ',', '.') }}đ</span>
                                                <span class='old-price ml-2'>{{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ</span>
                                            @endif
                                        @else
                                            @if($minPrice == $maxPrice)
                                                {{ number_format($minPrice, 0, ',', '.') }}đ
                                            @else
                                                {{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ
                                            @endif
                                        @endif">
                                @if($minPromotion && $minPromotion > 0)
                                @if($minPromotion == $maxPromotion)
                                <span class="promotion-price">{{ number_format($minPromotion, 0, ',', '.') }}đ</span>
                                <span class="old-price ml-2">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                                @else
                                <span class="promotion-price">{{ number_format($minPromotion, 0, ',', '.') }}đ - {{ number_format($maxPromotion, 0, ',', '.') }}đ</span>
                                <span class="old-price ml-2">{{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ</span>
                                @endif
                                @else
                                @if($minPrice == $maxPrice)
                                {{ number_format($minPrice, 0, ',', '.') }}đ
                                @else
                                {{ number_format($minPrice, 0, ',', '.') }}đ - {{ number_format($maxPrice, 0, ',', '.') }}đ
                                @endif
                                @endif
                            </span>
                        </div>
                        @else
                        <div class="price font-alt">
                            <span class="amount" id="product-price"
                                data-original-price="@if($product->promotion_price && $product->promotion_price > 0)
                                            <span class='promotion-price'>{{ number_format($product->promotion_price, 0, ',', '.') }}đ</span>
                                            <span class='old-price ml-2'>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        @else
                                            {{ number_format($product->price, 0, ',', '.') }}đ
                                        @endif">
                                @if($product->promotion_price && $product->promotion_price > 0)
                                <span class="promotion-price">{{ number_format($product->promotion_price, 0, ',', '.') }}đ</span>
                                <span class="old-price ml-2">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @else
                                {{ number_format($product->price, 0, ',', '.') }}đ
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Mô tả ngắn -->
                    <div class="product-description mb-20">
                        <p>{{ $product->description }}</p>
                    </div>
                    <!-- Form mua hàng -->
                    <form action="{{ route('client.add-to-cart') }}" method="POST" class="add-to-cart-form" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="row mb-20">
                            @if($product->variants && $product->variants->count() > 0)
                            <!-- Chọn phiên bản -->
                            <div class="col-sm-12 mb-20">
                                <label class="font-alt">Chọn phiên bản:</label>
                                <select class="form-control input-lg" name="variant_id" required
                                    onchange="updatePriceAndStock(this)" id="variant-select">
                                    <option value="">-- Chọn phiên bản --</option>
                                    @foreach ($product->variants->sortBy('price') as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ ($variant->storage && isset($variant->storage->capacity)) ? $variant->storage->capacity : '' }} - {{ ($variant->color && isset($variant->color->name)) ? $variant->color->name : '' }} -
                                        @if($variant->promotion_price && $variant->promotion_price > 0)
                                        {{ number_format($variant->promotion_price, 0, ',', '.') }}đ (giá gốc: {{ number_format($variant->price, 0, ',', '.') }}đ)
                                        @else
                                        {{ number_format($variant->price, 0, ',', '.') }}đ
                                        @endif
                                        @if ($variant->stock_quantity <= 5)
                                            (Còn {{ $variant->stock_quantity }} sản phẩm)
                                            @endif
                                            </option>
                                            @endforeach
                                </select>
                            </div>

                            @endif

                            <!-- Số lượng -->
                            <div class="col-sm-4 mb-20">
                                <label class="font-alt">Số lượng:</label>
                                <input class="form-control input-lg" type="number" name="quantity" value="1" max="100"
                                    min="1" required="required" id="quantity-input" />
                                <small class="text-muted" id="stock-info" style="display: none;"></small>
                                <small class="text-danger" id="quantity-error" style="display: none;"></small>
                            </div>

                            <!-- Nút thêm vào giỏ -->
                            <div class="col-sm-8">
                                <label class="font-alt">&nbsp;</label>
                                <button type="submit" class="btn btn-lg btn-block btn-round btn-b">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Meta -->
                    <div class="product-meta">
                        <div class="product-category">
                            Danh mục: <a href="#" class="font-alt">{{ $product->category->name ?? 'Không có danh mục' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs thông tin chi tiết -->
        <div class="row mt-70">
            <div class="col-sm-12">
                <ul class="nav nav-tabs font-alt" role="tablist">
                    <li class="active">
                        <a href="#description" data-toggle="tab">
                            <i class="fas fa-file-alt"></i> Mô tả
                        </a>
                    </li>
                    <li>
                        <a href="#data-sheet" data-toggle="tab">
                            <i class="fas fa-list"></i> Thông số kỹ thuật
                        </a>
                    </li>
                    <li>
                        <a href="#reviews" data-toggle="tab">
                            <i class="fas fa-comments"></i> Đánh giá ({{ $totalReviews }})
                        </a>
                    </li>
                    <li>
                        <a href="#commen" data-toggle="tab">
                            <i class="far fa-comments"></i> Bình luận
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab mô tả -->
                    <div class="tab-pane active" id="description">
                        <div class="panel-body">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>

                    {{-- Trang bình luận --}}
                    <div class="tab-pane" id="commen">
                        <div class="comment-section">
                            <h2>Bình luận</h2>

                            {{-- Thông báo khi gửi bình luận thành công --}}
                            @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Form bình luận -->
                            @auth
                            <form class="comment-form" method="POST" action="{{ route('client.comment.store') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="form-input-wrapper">
                                    <textarea name="content" placeholder="Nhập bình luận của bạn..."
                                        required></textarea>
                                    <button type="submit">Gửi bình luận</button>
                                </div>
                            </form>
                            @else
                            <div class="alert alert-warning mt-2">
                                Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.
                            </div>
                            @endauth

                            <hr>

                            <!-- Danh sách bình luận -->
                            @if ($product->comments->count())
                            @foreach ($product->activeComments as $comment)
                            <div class="comment-item">
                                <div class="comment-header">
                                    <span><strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong></span>
                                    <span class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="comment-content">
                                    {{ $comment->content }}
                                </div>
                            </div>
                            @endforeach
                            @else
                            <p class="mt-3">Chưa có bình luận nào.</p>
                            @endif
                        </div>

                    </div>


                    <!-- Tab thông số -->
                    <div class="tab-pane" id="data-sheet">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <th class="w-25">Thông số</th>
                                    <th>Chi tiết</th>
                                </tr>
                                <tr>
                                    <td>Tùy chọn bộ nhớ</td>
                                    <td>
                                        @foreach ($variantStorages as $storage)
                                        <span class="badge">{{ $storage->capacity }}</span>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Màu sắc có sẵn</td>
                                    <td>
                                        @foreach ($variantColors as $color)
                                        <span class="badge">{{ $color->name }}</span>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tab đánh giá -->
                    <div class="tab-pane" id="reviews">
                        <!-- Thống kê rating tổng quan -->
                        @if ($totalReviews > 0)
                        <div class="rating-overview mb-30">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="rating-summary text-center">
                                        <div class="average-rating">
                                            <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
                                            <span class="rating-total">/5</span>
                                        </div>
                                        <div class="rating-stars mb-10">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <=round($averageRating))
                                                <i class="fas fa-star star"></i>
                                                @else
                                                <i class="fas fa-star star-off"></i>
                                                @endif
                                                @endfor
                                        </div>
                                        <p class="rating-count">{{ $totalReviews }} đánh giá</p>
                                    </div>
                                </div>
                                <div class="col-md-9 col-sm-6">
                                    <div class="rating-breakdown">
                                        @for ($i = 5; $i >= 1; $i--)
                                        <div class="rating-item">
                                            <span class="star-count">{{ $i }} sao</span>
                                            <div class="progress-bar-container">
                                                @php
                                                $percentage =
                                                $totalReviews > 0
                                                ? round(
                                                ($ratingStats[$i] / $totalReviews) * 100,
                                                1,
                                                )
                                                : 0;
                                                @endphp
                                                <div class="progress-bar"
                                                    style="width: '{{ $percentage }}%; min-width: {{ $percentage > 0 ? '2px' : '0' }};">
                                                </div>
                                            </div>
                                            <span class="star-count-number">({{ $ratingStats[$i] }})</span>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @endif

                        @auth
                        <!-- Form thêm đánh giá (chỉ hiển thị nếu chưa đánh giá) -->
                        @php
                        $userReview = $reviews->where('user_id', auth()->id())->first();
                        @endphp

                        @if (!$userReview)
                        <div class="review-form mb-40">
                            <h4 class="review-form-title font-alt mb-20">Thêm đánh giá của bạn</h4>
                            <form id="review-form" method="post" action="{{ route('client.add-review', $product->id) }}"
                                class="form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="review-name">Tên hiển thị</label>
                                            <input class="form-control" type="text" id="review-name" name="name"
                                                value="{{ auth()->user()->name }}" placeholder="Tên của bạn" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="review-email">Email</label>
                                            <input class="form-control" type="email" id="review-email" name="email"
                                                value="{{ auth()->user()->email }}" placeholder="Email của bạn" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="review-rating">Đánh giá <span
                                                    class="text-danger">*</span></label>
                                            <div class="rating-input" id="rating-input">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="rating-star" data-rating="{{ $i }}">
                                                    <i class="fas fa-star"></i>
                                                    </span>
                                                    @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="selected-rating" required>
                                            <small class="form-text text-muted">Nhấp vào sao để đánh giá</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="review-content">Nội dung đánh giá <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="review-content" name="content" rows="4"
                                                placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."
                                                required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="review-image">Hình ảnh đánh giá</label>
                                            <input type="file" class="form-control" id="review-image" name="image"
                                                accept="image/*">
                                            <small class="form-text text-muted">Bạn có thể đính kèm hình ảnh để
                                                chia sẻ trải nghiệm thực tế
                                                (không bắt buộc)</small>
                                        </div>
                                    </div>
                                    <input type="hidden" name="status" value="0">
                                    <div class="col-sm-12">
                                        <button class="btn btn-round btn-d" type="submit" id="submit-review-btn">
                                            <i class="fas fa-paper-plane"></i> Gửi đánh giá
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr>
                        @else
                        <div class="user-review-notice mb-30">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Bạn đã đánh giá sản phẩm này rồi. Cảm ơn bạn đã chia sẻ!
                            </div>
                        </div>
                        @endif

                        <!-- Danh sách đánh giá -->
                        @if ($reviews->count() > 0)
                        <div class="reviews">
                            <h4 class="font-alt mb-20">Đánh giá từ khách hàng</h4>
                            @foreach ($reviews as $review)
                            <div class="review-item clearfix mb-30">
                                <div class="review-avatar">
                                    @if ($review->user && $review->user->avatar)
                                    <img src="{{ asset('storage/' . $review->user->avatar) }}" alt="Ảnh đại diện"
                                        class="img-circle" />
                                    @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($review->name ?? 'U', 0, 1)) }}
                                    </div>
                                    @endif
                                </div>
                                <div class="review-content">
                                    <div class="review-header">
                                        <h5 class="review-author font-alt">
                                            {{ $review->name ?? ($review->user->name ?? 'Khách hàng') }}
                                        </h5>
                                        <div class="review-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <=$review->rating)
                                                <i class="fas fa-star star"></i>
                                                @else
                                                <i class="fas fa-star star-off"></i>
                                                @endif
                                                @endfor
                                                <span
                                                    class="review-date font-alt">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    <p class="review-text">{{ $review->content }}</p>
                                    @if ($review->image)
                                    <div class="review-image mt-2">
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Ảnh đánh giá"
                                            class="review-img"
                                            style="max-width: 200px; max-height: 200px; border-radius: 8px; cursor: pointer;" />
                                    </div>
                                    @endif
                                    @if ($review->user_id === auth()->id())
                                    <span class="review-badge">Đánh giá của bạn</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="no-reviews text-center py-5">
                            <i class="far fa-comments" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                            <h4 class="font-alt text-muted">Chưa có đánh giá nào</h4>
                            <p class="text-muted">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                        </div>
                        @endif
                        @else
                        <div class="text-center py-5">
                            <p>Vui lòng đăng nhập để xem và viết đánh giá.</p>
                            <a href="{{ route('login') }}" class="btn btn-round btn-d">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="divider-w">

<!-- Sản phẩm liên quan -->
<section class="module-small">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h2 class="module-title font-alt">Sản phẩm liên quan</h2>
            </div>
        </div>
        <div class="row multi-columns-row">
            @if (count($productAsCategory) > 0)
            @foreach ($productAsCategory as $relatedProduct)
            <div class="col-sm-6 col-md-3 col-lg-3">
                <div class="shop-item">
                    <div class="shop-item-image">
                        <a href="{{ route('client.single-product', $relatedProduct->id) }}" class="product-link">
                            <img src="{{ asset('storage/' . $relatedProduct->image) }}"
                                alt="{{ $relatedProduct->name }}" class="img-responsive related-product-image" />
                        </a>
                    </div>
                    <div class="shop-item-content">
                        <h4 class="shop-item-title font-alt">
                            <a
                                href="{{ route('client.single-product', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a>
                        </h4>
                        @if ($relatedProduct->variants && $relatedProduct->variants->count() > 0)
                        @php
                        $minPrice = $relatedProduct->variants->min('price');
                        $maxPrice = $relatedProduct->variants->max('price');
                        @endphp
                        <div class="shop-item-price">
                            @if ($minPrice == $maxPrice)
                            <span class="price-badge">{{ number_format($minPrice, 0, ',', '.') }}đ</span>
                            @else
                            <span class="price-badge">{{ number_format($minPrice, 0, ',', '.') }}đ -
                                {{ number_format($maxPrice, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        @else
                        <div class="shop-item-price">
                            @if($relatedProduct->price && $relatedProduct->price > 0)
                            <span class="price-badge">{{ number_format($relatedProduct->price, 0, ',', '.') }}đ</span>
                            @else
                            <span class="price-badge">Liên hệ</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="col-sm-12">
                <div class="text-center py-5">
                    <i class="fa fa-info-circle" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h4 class="font-alt text-muted">Không có sản phẩm liên quan nào</h4>
                    <p class="text-muted">Hiện tại chưa có sản phẩm nào khác trong danh mục này.</p>
                    <a href="{{ route('client.product') }}" class="btn btn-round btn-d mt-3">
                        <i class="fa fa-arrow-left"></i> Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<hr class="divider-w">
<!-- Toast notifications -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<!-- JavaScript for product links -->
<script>
    // Ensure this runs after DOM is ready
    function setupProductLinks() {
        console.log('🔍 Setting up product link handlers...');
        // Ensure product image links work properly (only for related products)
        const productLinks = document.querySelectorAll('.shop-item .product-link');
        console.log('Found product links:', productLinks.length);

        productLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Prevent event bubbling that might interfere
                e.stopPropagation();
                e.preventDefault();

                // Get the href and navigate
                const href = this.getAttribute('href');
                console.log('Product link clicked:', href); // Debug log
                if (href) {
                    window.location.href = href;
                }
            });

            // Add cursor pointer to ensure clickable appearance
            link.style.cursor = 'pointer';
        });

        // Also handle clicks on the entire shop item (fallback)
        const shopItems = document.querySelectorAll('.shop-item');

        shopItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                console.log('Shop item clicked:', e.target);
                // Only if not clicking on a button or link already
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    const productLink = this.querySelector('.product-link');
                    if (productLink) {
                        const href = productLink.getAttribute('href');
                        console.log('Shop item fallback click:', href);
                        if (href) {
                            window.location.href = href;
                        }
                    }
                }
            });
        });
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupProductLinks);
    } else {
        setupProductLinks();
    }

    // Also run after jQuery is ready as backup
    $(document).ready(function() {
        console.log('jQuery ready - setting up product links backup');
        setupProductLinks();
    });
</script>

<!-- Custom CSS for synchronized image sizes -->
<style>
    /* Đồng bộ kích thước hình ảnh chính */
    .main-product-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .main-product-image:hover {
        transform: scale(1.02);
    }

    /* Đồng bộ kích thước gallery thumbnails */
    .gallery-thumbnail {
        width: 80px !important;
        height: 80px !important;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #ddd;
        transition: border-color 0.3s ease, transform 0.2s ease;
        cursor: pointer;
        display: block;
    }

    .gallery-thumbnail:hover {
        border-color: #007bff;
        transform: scale(1.05);
    }

    /* Đồng bộ layout cho gallery */
    .product-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
        padding: 0;
    }

    .product-gallery li {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    /* Đồng bộ kích thước sản phẩm liên quan */
    .multi-columns-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 0 -10px;
    }

    .multi-columns-row .col-sm-6.col-md-3.col-lg-3 {
        flex: 0 0 calc(25% - 15px);
        max-width: calc(25% - 15px);
        padding: 0;
        margin-bottom: 30px;
    }

    .related-product-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        pointer-events: auto;
    }

    .shop-item:hover .related-product-image {
        transform: scale(1.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Đồng bộ layout shop-item với chiều cao cố định */
    .shop-item {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 380px;
        margin-bottom: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background: #fff;
        border: 1px solid #f0f0f0;
        cursor: pointer;
        position: relative;
    }

    .shop-item:hover {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        transform: translateY(-8px);
        border-color: #007bff;
    }

    .shop-item-image {
        position: relative;
        overflow: hidden;
        flex: 0 0 280px;
        /* Fixed height */
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        cursor: pointer;
    }

    .product-link {
        display: block;
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 1;
        text-decoration: none;
        cursor: pointer !important;
    }

    .product-link img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
        cursor: pointer;
        pointer-events: auto;
    }

    .product-link:hover img {
        transform: scale(1.05);
    }

    /* Debug styles to ensure clickability */
    .shop-item {
        user-select: none;
    }

    .shop-item-image {
        user-select: none;
    }

    .product-link {
        user-select: none;
    }

    /* Ensure clickable areas are clearly defined */
    .shop-item-image a {
        outline: none;
        border: none;
        cursor: pointer !important;
        pointer-events: auto !important;
    }

    .shop-item-image a:focus {
        outline: 2px solid #007bff;
        outline-offset: 2px;
    }

    /* Ensure all clickable elements work */
    .shop-item * {
        pointer-events: auto;
    }

    .shop-item-image * {
        pointer-events: auto;
    }

    /* Better visual feedback for entire item - already defined above */

    .shop-item-detail {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.9), rgba(0, 86, 179, 0.9));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
        pointer-events: none;
    }

    .shop-item:hover .shop-item-detail {
        opacity: 1;
        pointer-events: auto;
    }

    .shop-item-detail .btn {
        color: white;
        border: 2px solid white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        pointer-events: auto;
    }

    .shop-item-detail .btn:hover {
        background: white;
        color: #007bff;
    }

    /* Đồng bộ phần content với flex-grow */
    .shop-item-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
        min-height: 100px;
    }

    .shop-item-title {
        flex: 1;
        margin: 0 0 10px 0;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 44px;
        /* 2 lines minimum */
    }

    .shop-item-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
        display: block;
    }

    .shop-item-title a:hover {
        color: #007bff;
    }

    .shop-item-price {
        margin-top: auto;
        padding: 0;
        font-size: 18px;
        font-weight: bold;
        color: #e74c3c;
        border-top: 1px solid #f0f0f0;
        padding-top: 15px;
    }

    /* Badge cho giá */
    .price-badge {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: 600;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
    }

    /* Responsive adjustments cho sản phẩm liên quan */
    @media (max-width: 1200px) {
        .multi-columns-row .col-sm-6.col-md-3.col-lg-3 {
            flex: 0 0 calc(33.333% - 15px);
            max-width: calc(33.333% - 15px);
        }
    }

    @media (max-width: 992px) {
        .multi-columns-row .col-sm-6.col-md-3.col-lg-3 {
            flex: 0 0 calc(50% - 15px);
            max-width: calc(50% - 15px);
        }

        .related-product-image {
            height: 240px;
        }

        .shop-item {
            min-height: 340px;
        }

        .shop-item-image {
            flex: 0 0 240px;
        }
    }

    @media (max-width: 768px) {
        .main-product-image {
            height: 300px;
        }

        .related-product-image {
            height: 220px;
        }

        .shop-item {
            min-height: 320px;
        }

        .shop-item-image {
            flex: 0 0 220px;
        }

        .gallery-thumbnail {
            width: 60px !important;
            height: 60px !important;
        }

        .multi-columns-row {
            gap: 15px;
            margin: 0 -7.5px;
        }
    }

    @media (max-width: 576px) {
        .multi-columns-row .col-sm-6.col-md-3.col-lg-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .related-product-image {
            height: 200px;
        }

        .shop-item {
            min-height: 300px;
        }

        .shop-item-image {
            flex: 0 0 200px;
        }
    }

    @media (max-width: 480px) {
        .main-product-image {
            height: 250px;
        }

        .related-product-image {
            height: 180px;
        }

        .shop-item {
            min-height: 280px;
        }

        .shop-item-image {
            flex: 0 0 180px;
        }

        .gallery-thumbnail {
            width: 50px !important;
            height: 50px !important;
        }
    }

    /* No related products section styling */
    .py-5 {
        padding: 50px 0;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    /* Đồng bộ hiệu ứng loading và transitions */
    .shop-item-image img {
        transition: all 0.3s ease;
        backface-visibility: hidden;
    }

    .shop-item:hover .shop-item-image img {
        filter: brightness(1.1);
    }

    /* Đồng bộ spacing và alignment */
    .multi-columns-row {
        justify-content: flex-start;
        align-items: stretch;
    }

    /* Skeleton loading effect for images */
    .related-product-image {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    .related-product-image[src] {
        background: none;
        animation: none;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Enhanced hover states */
    .shop-item {
        cursor: pointer;
    }

    .shop-item:hover .shop-item-title a {
        color: #007bff;
        text-decoration: none;
    }

    /* Grid equalizer - đảm bảo tất cả items có cùng chiều cao */
    .multi-columns-row::after {
        content: '';
        flex: auto;
    }

    /* Tooltip for prices */
    .price-badge:hover::after {
        content: 'Giá có thể thay đổi theo phiên bản';
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
    }

    .price-badge {
        position: relative;
    }

    /* CSS cho hệ thống đánh giá */
    /* Rating Overview */
    .rating-overview {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    /* Product Favorite Button trong Chi tiết */
    .product-favorite-action {
        margin-top: 15px;
        display: inline-block;
    }

    .btn-favorite-detail {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border: 2px solid #e74c3c;
        background: white;
        color: #e74c3c;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    }

    .btn-favorite-detail:hover {
        background: #e74c3c;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .btn-favorite-detail.favorited {
        background: #e74c3c;
        color: white;
        border-color: #e74c3c;
    }

    .btn-favorite-detail.favorited:hover {
        background: #c0392b;
        border-color: #c0392b;
        color: white;
    }

    .btn-favorite-detail:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-favorite-detail i {
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .btn-favorite-detail:hover i {
        transform: scale(1.1);
    }

    .btn-favorite-detail.favorited i {
        animation: heartBeat 0.6s ease;
    }

    /* Product rating layout improvement */
    .product-rating {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .stars {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rating-summary {
        padding: 20px;
    }

    .average-rating {
        margin-bottom: 15px;
    }

    .rating-number {
        font-size: 48px;
        font-weight: bold;
        color: #007bff;
        line-height: 1;
    }

    .rating-total {
        font-size: 24px;
        color: #6c757d;
        font-weight: 600;
    }

    .rating-stars {
        font-size: 20px;
    }

    .rating-count {
        color: #6c757d;
        font-size: 16px;
        margin: 0;
    }

    /* Rating Breakdown */
    .rating-breakdown {
        padding: 20px;
    }

    .rating-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .star-count {
        width: 60px;
        color: #495057;
        font-weight: 500;
    }

    .progress-bar-container {
        flex: 1 !important;
        height: 8px !important;
        background: #e9ecef !important;
        border-radius: 4px !important;
        margin: 0 15px !important;
        overflow: hidden !important;
        position: relative !important;
    }

    .progress-bar {
        height: 100% !important;
        background: linear-gradient(90deg, #ffc107, #f39c12) !important;
        border-radius: 4px !important;
        transition: width 0.3s ease !important;
        display: block !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
    }

    .star-count-number {
        width: 40px;
        text-align: right;
        color: #6c757d;
        font-size: 13px;
    }

    /* Rating Input */
    .rating-input {
        display: flex;
        gap: 5px;
        margin: 10px 0;
    }

    .rating-star {
        font-size: 24px;
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rating-star:hover,
    .rating-star.active {
        color: #ffc107;
        transform: scale(1.1);
    }

    .rating-star:hover~.rating-star {
        color: #ddd;
    }

    /* Review Items */
    .review-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        background: #fff;
        transition: box-shadow 0.3s ease;
    }

    .review-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .review-avatar {
        width: 60px;
        height: 60px;
        float: left;
        margin-right: 20px;
    }

    .review-avatar img {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
    }

    .review-content {
        overflow: hidden;
    }

    .review-header {
        margin-bottom: 10px;
    }

    .review-author {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: #333;
    }

    .review-rating {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .review-date {
        color: #6c757d;
        font-size: 13px;
    }

    .review-text {
        color: #495057;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .review-badge {
        background: #28a745;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Review Form */
    .review-form {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .review-form-title {
        color: #333;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    /* User Review Notice */
    .user-review-notice .alert {
        border-radius: 8px;
        border: none;
        background: #d1ecf1;
        color: #0c5460;
        padding: 15px 20px;
    }

    /* No Reviews */
    .no-reviews {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 40px 20px;
        border: 2px dashed #dee2e6;
    }

    /* Rating text styling */
    .rating-text {
        margin-left: 10px;
        color: #6c757d;
        font-size: 14px;
    }

    .rating-text a {
        color: #007bff;
        text-decoration: none;
    }

    .rating-text a:hover {
        text-decoration: underline;
    }

    /* Responsive cho đánh giá */
    @media (max-width: 768px) {
        .rating-overview {
            padding: 15px;
        }

        .rating-number {
            font-size: 36px;
        }

        .rating-total {
            font-size: 18px;
        }

        .review-avatar {
            width: 50px;
            height: 50px;
            margin-right: 15px;
        }

        .review-avatar img,
        .avatar-placeholder {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .review-form {
            padding: 20px 15px;
        }

        .rating-star {
            font-size: 20px;
        }
    }

    /* Product stock info styling */
    .product-stock-info {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        margin-top: 10px;
    }

    .product-stock-info label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
        display: block;
    }

    #product-stock-display {
        font-size: 14px;
        font-weight: 500;
        padding: 8px 0;
    }

    #product-stock-display i {
        margin-right: 8px;
    }

    /* Responsive cho product stock */
    @media (max-width: 768px) {
        .product-stock-info {
            padding: 12px;
            margin-top: 8px;
        }

        #product-stock-display {
            font-size: 13px;
        }
    }

    /* css form bình luận */

    .comment-section {
        width: 100%;

        margin: 0;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #f3f4f6;
        border-radius: 10px;
        font-family: Arial, sans-serif;
    }

    .comment-section h2 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-input-wrapper {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .form-input-wrapper textarea {
        flex: 1;
        height: 50px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        resize: vertical;
    }

    .form-input-wrapper button {
        padding: 10px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        height: 50px;
    }

    .form-input-wrapper button:hover {
        background-color: #0056b3;
    }

    .comment-item {
        background-color: #ffffff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #555;
        margin-bottom: 6px;
    }

    .comment-content {
        font-size: 16px;
        color: #333;
        padding-left: 10px;
    }

    .old-price {
        text-decoration: line-through;
        color: #888;
        font-size: 16px;
        margin-left: 8px;
    }

    .promotion-price {
        color: #e74c3c;
        font-weight: bold;
        font-size: 20px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Global variables
    let currentStock = 0;
    let currentCartQuantity = 0;
    let availableToAdd = 0;
    let isLoadingStock = false;

    // Image zoom variables
    let scale = 1;
    let translateX = 0;
    let translateY = 0;
    let isDragging = false;
    let startX, startY;

    function updatePriceAndStock(select) {
        const selectedOption = select.options[select.selectedIndex];
        const variantId = selectedOption.value;

        if (variantId) {
            // Hiển thị loading state
            const stockInfo = document.getElementById('stock-info');
            stockInfo.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra kho...';
            stockInfo.style.display = 'block';
            stockInfo.style.color = '#6c757d';

            // Fetch real-time stock data
            fetchVariantStock(variantId);
        } else {
            // Reset về giá ban đầu khi chưa chọn variant
            resetToDefaultState();
        }
    }

    function fetchVariantStock(variantId) {
        if (isLoadingStock) return;

        isLoadingStock = true;

        $.ajax({
            url: "{{ route('client.variant-stock') }}",
            method: 'GET',
            data: {
                variant_id: variantId
            },
            success: function(response) {
                if (response.success) {
                    // Cập nhật thông tin stock
                    currentStock = response.current_stock;
                    currentCartQuantity = response.cart_quantity;
                    availableToAdd = response.available_to_add;

                    // Cập nhật giá
                    document.getElementById('product-price').innerHTML =
                        new Intl.NumberFormat('vi-VN').format(response.price) + 'đ';

                    // Cập nhật thông tin stock display
                    updateStockDisplay();

                    // Cập nhật quantity input constraints
                    updateQuantityConstraints();

                    // Validate lại quantity hiện tại
                    validateQuantity();
                } else {
                    showStockError('Không thể lấy thông tin kho');
                }
            },
            error: function() {
                showStockError('Lỗi khi kiểm tra kho');
            },
            complete: function() {
                isLoadingStock = false;
            }
        });
    }

    function updateStockDisplay() {
        const stockInfo = document.getElementById('stock-info');

        if (currentStock <= 0) {
            stockInfo.innerHTML = 'Hết hàng';
            stockInfo.style.color = '#dc3545';
        } else if (currentCartQuantity > 0) {
            if (availableToAdd > 0) {
                stockInfo.innerHTML =
                    `Còn ${currentStock} sản phẩm. Bạn đã có ${currentCartQuantity} trong giỏ, có thể thêm ${availableToAdd} nữa.`;
                stockInfo.style.color = availableToAdd <= 5 ? '#dc3545' : '#6c757d';
            } else {
                stockInfo.innerHTML = `Bạn đã có ${currentCartQuantity} sản phẩm trong giỏ (đạt giới hạn kho)`;
                stockInfo.style.color = '#dc3545';
            }
        } else {
            stockInfo.innerHTML = `Còn ${currentStock} sản phẩm trong kho.`;
            stockInfo.style.color = currentStock <= 5 ? '#dc3545' : '#6c757d';
        }
        stockInfo.style.display = 'block';
    }

    function updateQuantityConstraints() {
        const quantityInput = document.getElementById('quantity-input');

        if (availableToAdd > 0) {
            quantityInput.max = Math.min(availableToAdd, 100);
            quantityInput.disabled = false;

            // Adjust current value if it exceeds available
            if (parseInt(quantityInput.value) > availableToAdd) {
                quantityInput.value = Math.min(availableToAdd, 1);
            }
        } else {
            quantityInput.max = 0;
            quantityInput.value = 0;
            quantityInput.disabled = true;
        }
    }

    function resetToDefaultState() {
        // Get price values from the DOM instead of embedding PHP
        const priceElement = document.getElementById('product-price');
        const originalPrice = priceElement.getAttribute('data-original-price');

        // Reset to original price display
        priceElement.innerHTML = originalPrice;

        // Reset stock variables
        currentStock = 0;
        currentCartQuantity = 0;
        availableToAdd = 0;

        // Reset UI
        document.getElementById('stock-info').style.display = 'none';
        const quantityInput = document.getElementById('quantity-input');
        quantityInput.max = 100;
        quantityInput.disabled = false;
        quantityInput.value = 1;

        // Clear any error messages
        document.getElementById('quantity-error').style.display = 'none';
        quantityInput.style.borderColor = '';
    }

    function showStockError(message) {
        const stockInfo = document.getElementById('stock-info');
        stockInfo.innerHTML = message;
        stockInfo.style.color = '#dc3545';
        stockInfo.style.display = 'block';
    }

    // Test redirect function
    function testRedirect() {
        console.log('=== TESTING REDIRECT ===');
        const cartUrl = "{{ route('client.cart') }}";
        console.log('Trying to redirect to:', cartUrl);

        try {
            window.location.href = cartUrl;
        } catch (error) {
            console.error('Redirect test failed:', error);
            alert('Redirect failed: ' + error.message);
        }
    }

    // Test toast function
    function testToast() {
        console.log('=== TESTING TOAST ===');

        showToast('Test success toast!', 'success');

        setTimeout(function() {
            showToast('Test error toast!', 'error');
        }, 1000);

        setTimeout(function() {
            showToast('Test info toast!', 'info');
        }, 2000);
    }

    function validateQuantity() {
        const quantityInput = document.getElementById('quantity-input');
        const quantityError = document.getElementById('quantity-error');
        const quantity = parseInt(quantityInput.value) || 0;

        quantityError.style.display = 'none';
        quantityInput.style.borderColor = '';

        if (quantity < 1) {
            showQuantityError('Số lượng phải lớn hơn 0');
            return false;
        }

        if (quantity > 100) {
            showQuantityError('Không thể mua quá 100 sản phẩm cùng lúc');
            return false;
        }

        if (availableToAdd === 0) {
            if (currentCartQuantity > 0) {
                showQuantityError(`Bạn đã có ${currentCartQuantity} sản phẩm trong giỏ (đạt giới hạn kho)`);
            } else {
                showQuantityError('Sản phẩm đã hết hàng');
            }
            return false;
        }

        if (quantity > availableToAdd) {
            if (currentCartQuantity > 0) {
                showQuantityError(
                    `Chỉ có thể thêm tối đa ${availableToAdd} sản phẩm nữa (đã có ${currentCartQuantity} trong giỏ)`
                );
            } else {
                showQuantityError(`Chỉ còn ${availableToAdd} sản phẩm trong kho`);
            }
            return false;
        }

        return true;
    }

    function showQuantityError(message) {
        const quantityError = document.getElementById('quantity-error');
        const quantityInput = document.getElementById('quantity-input');

        quantityError.innerHTML = message;
        quantityError.style.display = 'block';
        quantityInput.style.borderColor = '#dc3545';
    }

    // Global toast notification function
    function showToast(message, type = 'success') {
        console.log('=== SHOWING TOAST ===');
        console.log('Message:', message);
        console.log('Type:', type);

        const bgColor = type === 'success' ? '#d4edda' : (type === 'error' ? '#f8d7da' : '#d1ecf1');
        const borderColor = type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : '#17a2b8');
        const textColor = type === 'success' ? '#155724' : (type === 'error' ? '#721c24' : '#0c5460');
        const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-triangle' :
            'fa-info-circle');
        const title = type === 'success' ? 'Thành công!' : (type === 'error' ? 'Lỗi!' : 'Thông báo!');

        // Đảm bảo toast container tồn tại
        if ($('#toast-container').length === 0) {
            $('body').append(
                '<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
        }

        const toast = $(`
      <div class="toast alert" 
       style="display: none; margin-bottom: 15px; padding: 20px; border-radius: 8px; 
      background: ${bgColor}; border: 2px solid ${borderColor}; color: ${textColor};
      box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 350px; max-width: 500px; font-size: 16px;
      position: relative; z-index: 10000;">
      <div style="display: flex; align-items: center;">
      <i class="fa ${icon}" style="font-size: 24px; margin-right: 15px;"></i>
      <div style="flex: 1;">
      <strong>${title}</strong><br>
      ${message}
      </div>
      <button type="button" class="close" onclick="$(this).closest('.toast').fadeOut()" 
      style="background: none; border: none; font-size: 24px; cursor: pointer; color: ${textColor}; margin-left: 10px;">
      <span>&times;</span>
      </button>
      </div>
      </div>
      `);

        $('#toast-container').append(toast);
        toast.fadeIn(400).delay(6000).fadeOut(600, function() {
            $(this).remove();
        });

        console.log('Toast added to container');
    }

    // Global function để xử lý submit add to cart
    function submitAddToCart($form, $submitBtn, originalText) {
        let isRedirecting = false;

        console.log('=== AJAX REQUEST START ===');
        console.log('URL:', $form.attr('action'));
        console.log('Method: POST');
        console.log('Data:', $form.serialize());
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            timeout: 10000, // 10 second timeout
            statusCode: {
                400: function(xhr) {
                    // Handle business logic errors (like stock issues)
                    console.log('=== HTTP 400 - Business Logic Error ===');
                    console.log('Response:', xhr.responseJSON);

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Cập nhật stock info nếu có
                        if (xhr.responseJSON.current_stock !== undefined) {
                            currentStock = xhr.responseJSON.current_stock;
                            currentCartQuantity = xhr.responseJSON.cart_quantity || 0;
                            availableToAdd = xhr.responseJSON.available_to_add || 0;
                            updateStockDisplay();
                            updateQuantityConstraints();
                        }

                        showToast(xhr.responseJSON.message, 'error');
                    } else {
                        showToast('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
                    }
                },
                401: function(xhr) {
                    // Handle authentication errors
                    console.log('=== HTTP 401 - Authentication Required ===');
                    console.log('Response:', xhr.responseJSON);

                    if (xhr.responseJSON && xhr.responseJSON.redirect_to_login && xhr.responseJSON
                        .login_url) {
                        showToast(xhr.responseJSON.message || 'Vui lòng đăng nhập để tiếp tục!', 'info');

                        // Redirect to login page after 1 second
                        setTimeout(function() {
                            window.location.href = xhr.responseJSON.login_url;
                        }, 1000);
                    } else {
                        showToast('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'error');
                    }
                }
            },
            success: function(response) {
                console.log('=== AJAX SUCCESS ===');
                console.log('Full response:', response);
                console.log('Response type:', typeof response);
                console.log('Response success field:', response.success);

                if (response.success === true) {
                    console.log('Success! Updating cart count and redirecting...'); // Debug log
                    console.log('Redirect URL from response:', response.redirect); // Debug log

                    isRedirecting = true;

                    // Update cart count immediately
                    updateCartCount();

                    try {
                        // Redirect ngay lập tức đến trang giỏ hàng
                        const redirectUrl = response.redirect || "{{ route('client.cart') }}" || '/cart';
                        console.log('Final redirect URL:', redirectUrl); // Debug log

                        // Thử nhiều cách redirect
                        if (window.location.replace) {
                            window.location.replace(redirectUrl);
                        } else {
                            window.location.href = redirectUrl;
                        }

                        // Backup redirect sau 500ms nếu chưa redirect
                        setTimeout(function() {
                            if (window.location.pathname !== '/cart') {
                                console.log('Backup redirect triggered'); // Debug log
                                window.location.href = '/cart';
                            }
                        }, 500);

                    } catch (redirectError) {
                        console.error('Redirect error:', redirectError); // Debug log
                        // Fallback thủ công
                        window.location.href = '/cart';
                    }

                } else if (response.success === false) {
                    // Check if this is a login redirect response
                    if (response.redirect_to_login === true && response.login_url) {
                        console.log('Login required, redirecting to login page...');
                        showToast(response.message || 'Vui lòng đăng nhập để tiếp tục!', 'info');

                        // Redirect to login page after 1 second
                        setTimeout(function() {
                            window.location.href = response.login_url;
                        }, 1000);
                        return;
                    }

                    // Server trả về success: false (business logic error)
                    console.log('Business logic error:', response.message);

                    // Cập nhật stock info nếu có
                    if (response.current_stock !== undefined) {
                        currentStock = response.current_stock;
                        currentCartQuantity = response.cart_quantity || 0;
                        availableToAdd = response.available_to_add || 0;
                        updateStockDisplay();
                        updateQuantityConstraints();
                    }

                    showToast(response.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
                } else {
                    // Response không có success field hoặc unexpected format
                    console.log('Unexpected response format:', response);
                    showToast('Phản hồi từ server không đúng định dạng!', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.log('=== AJAX ERROR ===');
                console.log('XHR Status:', xhr.status);
                console.log('Status Text:', status);
                console.log('Error:', error);
                console.log('Response Text:', xhr.responseText);
                console.log('Response JSON:', xhr.responseJSON);

                let errorMessage = 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!';

                if (status === 'timeout') {
                    errorMessage = 'Request timeout! Vui lòng thử lại.';
                } else if (xhr.status === 0) {
                    errorMessage = 'Không thể kết nối đến server! Kiểm tra kết nối mạng.';
                } else if (xhr.status === 401) {
                    // Authentication required - already handled in statusCode, but adding fallback
                    if (xhr.responseJSON && xhr.responseJSON.redirect_to_login && xhr.responseJSON
                        .login_url) {
                        showToast(xhr.responseJSON.message || 'Vui lòng đăng nhập để tiếp tục!', 'info');
                        setTimeout(function() {
                            window.location.href = xhr.responseJSON.login_url;
                        }, 1000);
                        return; // Don't show error toast
                    } else {
                        errorMessage = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!';
                    }
                } else if (xhr.status === 419) {
                    errorMessage = 'CSRF token expired! Vui lòng refresh trang và thử lại.';
                } else if (xhr.status === 422) {
                    // Validation errors từ Laravel
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = [];
                        for (let field in errors) {
                            errorMessages.push(errors[field][0]);
                        }
                        errorMessage = errorMessages.join('<br>');
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    // Error message từ controller
                    errorMessage = xhr.responseJSON.message;

                    // Nếu là lỗi stock (status 400), refresh stock data
                    if (xhr.status === 400) {
                        console.log('Stock error detected, updating stock info...');

                        if (xhr.responseJSON.current_stock !== undefined) {
                            currentStock = xhr.responseJSON.current_stock;
                            currentCartQuantity = xhr.responseJSON.cart_quantity || 0;
                            availableToAdd = xhr.responseJSON.available_to_add || 0;
                            updateStockDisplay();
                            updateQuantityConstraints();

                            console.log('Updated stock info:', {
                                currentStock,
                                currentCartQuantity,
                                availableToAdd
                            });
                        }
                    }
                } else if (xhr.status === 404) {
                    errorMessage = 'Sản phẩm hoặc phiên bản không tồn tại!';
                } else if (xhr.status === 500) {
                    errorMessage = 'Lỗi server! Vui lòng thử lại sau.';
                }

                showToast(errorMessage, 'error');
            },
            complete: function() {
                console.log('=== AJAX COMPLETE ===');
                console.log('Is redirecting:', isRedirecting);

                // Chỉ re-enable button nếu không redirect (tức là có lỗi)
                if (!isRedirecting) {
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            }
        }).fail(function(xhr, status, error) {
            console.log('=== AJAX FAIL (alternative handler) ===');
            console.log('Status:', status);
            console.log('Error:', error);

            // Fallback: Submit form thông thường nếu AJAX fail hoàn toàn
            if (status === 'timeout' || xhr.status === 0) {
                console.log('AJAX failed completely, trying normal form submission...');
                showToast('Đang thử phương thức khác...', 'info');

                setTimeout(function() {
                    // Remove AJAX handler temporarily
                    $form.off('submit');

                    // Add hidden field to indicate fallback
                    $form.append('<input type="hidden" name="fallback_submit" value="1">');

                    // Submit form normally
                    $form.get(0).submit();
                }, 1000);
            }
        });
    }

    // Global function cập nhật số lượng giỏ hàng - sử dụng function từ navbar
    function updateCartCount() {
        // Use global refresh function if available
        if (window.refreshCartCount) {
            window.refreshCartCount();
        } else {
            // Fallback to local implementation
            $.ajax({
                url: "{{ route('client.cart-count') }}",
                method: 'GET',
                success: function(response) {
                    // Cập nhật số lượng trong header (nếu có)
                    $('.cart-count, .cart-counter, #cart-count').text(response.count);

                    // Update navbar cart count if available
                    if (window.updateCartCount) {
                        window.updateCartCount(response.count);
                    }
                }
            });
        }
    }

    $(document).ready(function() {
        console.log('🔍 Single product page loaded');

        // CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Thêm event listener cho quantity input
        $('#quantity-input').on('input change', function() {
            validateQuantity();
        });

        // Xử lý form thêm vào giỏ hàng
        $('#add-to-cart-form').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.html();

            // Kiểm tra xem đã chọn variant chưa
            const variantId = $form.find('select[name="variant_id"]').val();
            if (!variantId) {
                showToast('Vui lòng chọn phiên bản sản phẩm!', 'error');
                return;
            }

            // Kiểm tra số lượng hợp lệ
            if (!validateQuantity()) {
                return;
            }

            // GỌI AJAX THÊM VÀO GIỎ HÀNG
            submitAddToCart($form, $submitBtn, originalText);
        });

        // Xử lý click vào link đánh giá để cuộn xuống tab reviews
        $('.review-link').on('click', function(e) {
            e.preventDefault();

            // Kích hoạt tab reviews
            $('a[href="#reviews"]').tab('show');

            // Smooth scroll đến phần tab
            $('html, body').animate({
                scrollTop: $('#reviews').offset().top - 100
            }, 800);
        });

        // Debug helper - click anywhere on page to test route generation
        $(document).on('dblclick', function() {
            console.log('=== DEBUG INFO ===');
            console.log('Cart route:', "{{ route('client.cart') }}");
            console.log('Add to cart route:', "{{ route('client.add-to-cart') }}");
            console.log('Current URL:', window.location.href);
            console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
            console.log('Form action:', $('#add-to-cart-form').attr('action'));

            // Test route directly
            fetch("{{ route('client.cart') }}")
                .then(response => {
                    console.log('Cart route test - Status:', response.status);
                    console.log('Cart route test - OK:', response.ok);
                })
                .catch(error => {
                    console.log('Cart route test - Error:', error);
                });
        });

        // Periodic stock refresh (mỗi 30 giây) nếu đã chọn variant
        setInterval(function() {
            const variantId = $('#variant-select').val();
            if (variantId && !isLoadingStock) {
                fetchVariantStock(variantId);
            }
        }, 30000); // 30 seconds

        // Refresh stock khi user focus lại vào tab/window
        $(window).on('focus', function() {
            const variantId = $('#variant-select').val();
            if (variantId && !isLoadingStock) {
                fetchVariantStock(variantId);
            }
        });

        // =================
        // REVIEW FUNCTIONALITY
        // =================

        // Rating input functionality
        $('.rating-star').on('click', function() {
            const rating = $(this).data('rating');
            $('#selected-rating').val(rating);

            // Update visual state
            $('.rating-star').removeClass('active');
            $('.rating-star').each(function() {
                if ($(this).data('rating') <= rating) {
                    $(this).addClass('active');
                }
            });
        });

        // Rating hover effect
        $('.rating-star').on('mouseenter', function() {
            const rating = $(this).data('rating');
            $('.rating-star').removeClass('hover');
            $('.rating-star').each(function() {
                if ($(this).data('rating') <= rating) {
                    $(this).addClass('hover').css('color', '#ffc107');
                } else {
                    $(this).removeClass('hover').css('color', '#ddd');
                }
            });
        });

        $('.rating-input').on('mouseleave', function() {
            $('.rating-star').removeClass('hover');
            const selectedRating = $('#selected-rating').val();
            $('.rating-star').each(function() {
                if ($(this).data('rating') <= selectedRating) {
                    $(this).css('color', '#ffc107');
                } else {
                    $(this).css('color', '#ddd');
                }
            });
        });

        // Review form submission
        $('#review-form').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $submitBtn = $('#submit-review-btn');
            const originalText = $submitBtn.html();

            // Validate rating
            const rating = $('#selected-rating').val();
            if (!rating || rating < 1 || rating > 5) {
                showToast('Vui lòng chọn số sao đánh giá!', 'error');
                return;
            }

            // Validate content
            const content = $('#review-content').val().trim();
            if (!content) {
                showToast('Vui lòng nhập nội dung đánh giá!', 'error');
                return;
            }

            if (content.length > 1000) {
                showToast('Nội dung đánh giá không được quá 1000 ký tự!', 'error');
                return;
            }

            // Disable submit button
            $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang gửi...');

            // Submit via AJAX with FormData for file upload
            const formData = new FormData($form[0]);

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Review submission response:', response);

                    if (response.success) {
                        showToast(response.message || 'Đánh giá đã được thêm thành công!',
                            'success');

                        // Redirect after success
                        setTimeout(function() {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.reload();
                            }
                        }, 1500);
                    } else {
                        showToast(response.message || 'Có lỗi xảy ra khi thêm đánh giá!',
                            'error');
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Review submission error:', xhr.responseJSON);

                    let errorMessage = 'Có lỗi xảy ra khi thêm đánh giá!';

                    if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON
                        .redirect_to_login) {
                        errorMessage = xhr.responseJSON.message ||
                            'Vui lòng đăng nhập để thêm đánh giá!';
                        showToast(errorMessage, 'info');

                        setTimeout(function() {
                            window.location.href = xhr.responseJSON.login_url ||
                                '/login';
                        }, 1500);
                        return;
                    } else if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON
                        .message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON
                        .errors) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = [];
                        for (let field in errors) {
                            errorMessages.push(errors[field][0]);
                        }
                        errorMessage = errorMessages.join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    showToast(errorMessage, 'error');
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Character counter for review content
        $('#review-content').on('input', function() {
            const maxLength = 1000;
            const currentLength = $(this).val().length;
            const remaining = maxLength - currentLength;

            // Find or create character counter
            let $counter = $(this).siblings('.char-counter');
            if ($counter.length === 0) {
                $counter = $('<small class="char-counter text-muted"></small>');
                $(this).after($counter);
            }

            if (remaining < 0) {
                $counter.text(`Quá ${Math.abs(remaining)} ký tự`).removeClass('text-muted').addClass(
                    'text-danger');
                $(this).addClass('is-invalid');
            } else if (remaining < 100) {
                $counter.text(`Còn ${remaining} ký tự`).removeClass('text-danger').addClass(
                    'text-warning');
                $(this).removeClass('is-invalid');
            } else {
                $counter.text(`${currentLength}/${maxLength} ký tự`).removeClass(
                    'text-danger text-warning').addClass('text-muted');
                $(this).removeClass('is-invalid');
            }
        });

        // Image preview for review upload
        $('#review-image').on('change', function() {
            const file = this.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showToast('Ảnh không được vượt quá 2MB!', 'error');
                    this.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Remove existing preview
                    $('.image-preview').remove();

                    // Add new preview
                    const preview = $(`
          <div class="image-preview mt-2">
            <img src="${e.target.result}" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #ddd;">
            <div class="mt-1">
            <small class="text-muted">Preview: ${file.name}</small>
            <button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="removeImagePreview()">
                                                    <i class="fas fa-times"></i> Xóa
            </button>
            </div>
          </div>
          `);
                    $('#review-image').after(preview);
                };
                reader.readAsDataURL(file);
            }
        });

        // Image zoom overlay với zoom và pan

        // Phóng to ảnh sản phẩm khi click (dùng overlay riêng)
        console.log('Setting up image zoom handlers...');
        $('.main-product-image, .gallery-thumbnail').css('cursor', 'pointer').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var src = $(this).attr('src');
            console.log('Zooming image:', src); // Debug log
            $('#zoomed-image').attr('src', src);
            $('#image-zoom-overlay').addClass('active').fadeIn(100);
            resetZoom(); // Reset zoom khi mở
        });

        // Zoom bằng scroll wheel
        $('#zoomed-image').on('wheel', function(e) {
            e.preventDefault();
            const delta = e.originalEvent.deltaY > 0 ? -0.1 : 0.1;
            zoomImage(delta);
        });

        // Drag để di chuyển ảnh
        $('#zoomed-image').on('mousedown', function(e) {
            if (scale > 1) {
                isDragging = true;
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
                $(this).addClass('dragging');
                e.preventDefault();
            }
        });

        $(document).on('mousemove', function(e) {
            if (isDragging) {
                translateX = e.clientX - startX;
                translateY = e.clientY - startY;
                updateTransform();
            }
        });

        $(document).on('mouseup', function() {
            isDragging = false;
            $('#zoomed-image').removeClass('dragging');
        });

        // Double click để zoom in/out nhanh
        $('#zoomed-image').on('dblclick', function(e) {
            e.stopPropagation();
            if (scale === 1) {
                scale = 2;
            } else {
                resetZoom();
            }
            updateTransform();
        });

        // Đóng overlay khi click ra ngoài
        $('#image-zoom-overlay').on('click', function(e) {
            if (e.target === this) {
                $(this).removeClass('active').fadeOut(100);
                $('#zoomed-image').attr('src', '');
                resetZoom();
            }
        });

        // Đóng bằng phím ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#image-zoom-overlay').removeClass('active').fadeOut(100);
                $('#zoomed-image').attr('src', '');
                resetZoom();
            }
        });

        // Global functions cho zoom controls
        window.zoomImage = function(delta) {
            scale = Math.max(0.5, Math.min(5, scale + delta));
            updateTransform();
        };

        window.resetZoom = function() {
            scale = 1;
            translateX = 0;
            translateY = 0;
            updateTransform();
        };

        function updateTransform() {
            $('#zoomed-image').css('transform', `translate(${translateX}px, ${translateY}px) scale(${scale})`);
            $('#zoom-level').text(Math.round(scale * 100) + '%');
        }
    });

    // Global functions for image handling
    function removeImagePreview() {
        $('.image-preview').remove();
        $('#review-image').val('');
    }

    function showImageModal(src) {
        // Create modal if not exists
        if ($('#imageModal').length === 0) {
            const modal = $(`
          <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Ảnh đánh giá</h4>
            </div>
            <div class="modal-body text-center">
            <img id="modalImage" src="" style="max-width: 100%; height: auto;">
            </div>
          </div>
          </div>
          </div>
          `);
            $('body').append(modal);
        }

        $('#modalImage').attr('src', src);
        $('#imageModal').modal('show');
    }

    // Test FontAwesome icons và favorite functionality
    $(document).ready(function() {
        console.log('🔍 Testing FontAwesome icons on single product page...');

        // Test critical icons
        const testIcons = ['fas fa-heart', 'far fa-heart', 'fas fa-star', 'fas fa-shopping-cart'];
        let allWorking = true;

        testIcons.forEach(iconClass => {
            const testEl = document.createElement('i');
            testEl.className = iconClass;
            testEl.style.cssText = 'position: absolute; top: -9999px; left: -9999px;';
            document.body.appendChild(testEl);

            const style = window.getComputedStyle(testEl, '::before');
            const hasContent = style.content && style.content !== 'none' && style.content !== '""';

            if (!hasContent) {
                console.error(`❌ Icon ${iconClass} not working!`);
                allWorking = false;
            } else {
                console.log(`✅ Icon ${iconClass} working`);
            }

            document.body.removeChild(testEl);
        });

        if (!allWorking) {
            console.warn('⚠️ Some icons not working - adding fallbacks');

            // Add fallbacks for broken icons
            setTimeout(() => {
                $('.btn-favorite-detail i, .btn-favorite i, .btn-favorite-small i').each(function() {
                    const $icon = $(this);
                    const style = window.getComputedStyle(this, '::before');
                    const hasContent = style.content && style.content !== 'none' && style.content !== '""';

                    if (!hasContent) {
                        const $btn = $icon.closest('button, a');
                        if ($btn.length) {
                            const isFavorited = $btn.hasClass('favorited');
                            $icon.text(isFavorited ? '♥' : '♡');
                            $icon.css({
                                'font-family': 'inherit',
                                'font-size': '16px'
                            });
                            console.log('Added fallback icon to button:', $btn[0]);
                        }
                    }
                });
            }, 1000);
        }

        // Test favorite manager
        if (window.favoriteManager) {
            console.log('✅ Favorite manager loaded');

            // Test that favorite buttons are properly set up
            const favoriteButtons = $('.btn-favorite-detail, .btn-favorite, .btn-favorite-small');
            console.log(`Found ${favoriteButtons.length} favorite buttons on page`);

            favoriteButtons.each(function(index) {
                const $btn = $(this);
                const productId = $btn.data('product-id');
                const hasIcon = $btn.find('i').length > 0;
                const hasProductId = productId ? true : false;

                console.log(`Button ${index + 1}:`, {
                    productId: productId,
                    hasIcon: hasIcon,
                    hasProductId: hasProductId,
                    classes: $btn.attr('class'),
                    iconClasses: $btn.find('i').attr('class')
                });

                if (!hasProductId) {
                    console.warn(`⚠️ Button ${index + 1} missing product-id:`, $btn[0]);
                }
                if (!hasIcon) {
                    console.warn(`⚠️ Button ${index + 1} missing icon:`, $btn[0]);
                }
            });
        } else {
            console.error('❌ Favorite manager not found!');
        }

        // Final check - make sure FontAwesome CSS is loaded
        const faLoaded = Array.from(document.styleSheets).some(sheet => {
            try {
                return sheet.href && sheet.href.includes('font-awesome');
            } catch (e) {
                return false;
            }
        });

        console.log(faLoaded ? '✅ FontAwesome CSS loaded' : '❌ FontAwesome CSS not found');

        // Show summary
        setTimeout(() => {
            const workingButtons = $('.btn-favorite-detail, .btn-favorite, .btn-favorite-small').filter(function() {
                const $icon = $(this).find('i');
                if ($icon.length === 0) return false;

                const style = window.getComputedStyle($icon[0], '::before');
                return style.content && style.content !== 'none' && style.content !== '""';
            });

            console.log(`📊 Summary: ${workingButtons.length}/${$('.btn-favorite-detail, .btn-favorite, .btn-favorite-small').length} buttons have working icons`);
        }, 2000);
    });
</script>

<!-- Image Zoom Overlay -->
<div id="image-zoom-overlay" style="display:none;">
    <img id="zoomed-image" src="" alt="Zoomed image" />
    <div class="zoom-controls"
        style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:14px;display:flex;align-items:center;gap:15px;">
        <button onclick="zoomImage(-0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">-</button>
        <span id="zoom-level">100%</span>
        <button onclick="zoomImage(0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">+</button>
        <button onclick="resetZoom()"
            style="background:none;border:none;color:white;font-size:12px;cursor:pointer;padding:5px 10px;border-left:1px solid #555;margin-left:10px;">Reset</button>
    </div>
    <div class="zoom-hint"
        style="position:absolute;top:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:13px;">
        <i class="fas fa-mouse-pointer"></i> Kéo để di chuyển • <i class="fas fa-search-plus"></i> Scroll để zoom •
        Double click để zoom nhanh
    </div>
</div>

<!-- Image Zoom Overlay (đặt cuối file, ngoài mọi section) -->
<div id="image-zoom-overlay" style="display:none; position:fixed; z-index:99999; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); justify-content:center; align-items:center;">
    <img id="zoomed-image" src="" alt="Zoomed image" style="max-width:90vw; max-height:90vh; border-radius:10px; box-shadow:0 8px 40px rgba(0,0,0,0.5); background:#fff; display:block; margin:auto;" />
    <div class="zoom-controls"
        style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:14px;display:flex;align-items:center;gap:15px;">
        <button onclick="zoomImage(-0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">-</button>
        <span id="zoom-level">100%</span>
        <button onclick="zoomImage(0.1)"
            style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:5px 10px;">+</button>
        <button onclick="resetZoom()"
            style="background:none;border:none;color:white;font-size:12px;cursor:pointer;padding:5px 10px;border-left:1px solid #555;margin-left:10px;">Reset</button>
    </div>
    <div class="zoom-hint"
        style="position:absolute;top:20px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:white;padding:10px 20px;border-radius:25px;font-size:13px;">
        <i class="fas fa-mouse-pointer"></i> Kéo để di chuyển • <i class="fas fa-search-plus"></i> Scroll để zoom •
        Double click để zoom nhanh
    </div>
</div>

<style>
    #image-zoom-overlay {
        display: none;
        position: fixed;
        z-index: 99999;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        justify-content: center;
        align-items: center;
    }

    #image-zoom-overlay.active {
        display: flex !important;
    }

    #zoomed-image {
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 10px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
        background: #fff;
    }
</style>

<script>
    // Biến zoom toàn cục
    let scale = 1,
        translateX = 0,
        translateY = 0,
        isDragging = false,
        startX, startY;

    // Đảm bảo không bị chồng sự kiện
    $(document).off('click.zoomImage').on('click.zoomImage', '.main-product-image, .gallery-thumbnail', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var src = $(this).attr('src');
        console.log('[ZOOM] Clicked image:', src);
        $('#zoomed-image').attr('src', src);
        $('#image-zoom-overlay').addClass('active').fadeIn(100);
        resetZoom();
    });

    // Zoom bằng scroll wheel
    $('#zoomed-image').off('wheel').on('wheel', function(e) {
        e.preventDefault();
        const delta = e.originalEvent.deltaY > 0 ? -0.1 : 0.1;
        zoomImage(delta);
    });

    // Drag để di chuyển ảnh
    $('#zoomed-image').off('mousedown').on('mousedown', function(e) {
        if (scale > 1) {
            isDragging = true;
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
            $(this).addClass('dragging');
            e.preventDefault();
        }
    });

    $(document).off('mousemove.zoomImage').on('mousemove.zoomImage', function(e) {
        if (isDragging) {
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            updateTransform();
        }
    });

    $(document).off('mouseup.zoomImage').on('mouseup.zoomImage', function() {
        isDragging = false;
        $('#zoomed-image').removeClass('dragging');
    });

    // Double click để zoom in/out nhanh
    $('#zoomed-image').off('dblclick').on('dblclick', function(e) {
        e.stopPropagation();
        if (scale === 1) {
            scale = 2;
        } else {
            resetZoom();
        }
        updateTransform();
    });

    // Đóng overlay khi click ra ngoài
    $('#image-zoom-overlay').off('click').on('click', function(e) {
        if (e.target === this) {
            $(this).removeClass('active').fadeOut(100);
            $('#zoomed-image').attr('src', '');
            resetZoom();
        }
    });

    // Đóng bằng phím ESC
    $(document).off('keydown.zoomImage').on('keydown.zoomImage', function(e) {
        if (e.key === 'Escape') {
            $('#image-zoom-overlay').removeClass('active').fadeOut(100);
            $('#zoomed-image').attr('src', '');
            resetZoom();
        }
    });

    // Hàm zoom
    window.zoomImage = function(delta) {
        scale = Math.max(0.5, Math.min(5, scale + delta));
        updateTransform();
    };
    window.resetZoom = function() {
        scale = 1;
        translateX = 0;
        translateY = 0;
        updateTransform();
    };

    function updateTransform() {
        $('#zoomed-image').css('transform', `translate(${translateX}px, ${translateY}px) scale(${scale})`);
        $('#zoom-level').text(Math.round(scale * 100) + '%');
    }
</script>

<script>
    // Xử lý xóa sản phẩm khỏi yêu thích (AJAX) kèm confirm
    $(document).on('click', '.remove-favorite', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var productId = $btn.data('product-id');
        if (!productId) return;
        if (!confirm('Bạn có chắc chắn muốn bỏ sản phẩm này khỏi danh sách yêu thích?')) return;
        console.log('[FAVORITE] Click remove favorite:', productId);
        $btn.prop('disabled', true);
        $.ajax({
            url: (typeof removeFavoriteUrl !== 'undefined' && removeFavoriteUrl) ? removeFavoriteUrl : '/client/favorite/remove',
            method: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('[FAVORITE] Remove response:', response);
                if (response.success) {
                    // Cập nhật lại nút thành "Yêu thích"
                    $btn.removeClass('favorited remove-favorite').addClass('add-favorite');
                    $btn.find('i').removeClass('fas fa-heart').addClass('far fa-heart');
                    $btn.find('.btn-text').text('Yêu thích');
                    if (typeof showToast === 'function') showToast('Đã bỏ khỏi yêu thích!', 'success');
                } else {
                    if (typeof showToast === 'function') showToast(response.message || 'Có lỗi xảy ra!', 'error');
                    else alert(response.message || 'Có lỗi xảy ra!');
                }
            },
            error: function(xhr) {
                console.log('[FAVORITE] Remove error:', xhr);
                if (typeof showToast === 'function') showToast('Không thể kết nối server!', 'error');
                else alert('Không thể kết nối server!');
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
</script>

<script>
    var removeFavoriteUrl = "{{ route('client.favorite.remove') }}";
</script>

@endsection