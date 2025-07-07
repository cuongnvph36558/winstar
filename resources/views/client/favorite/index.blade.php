@extends('layouts.client')

@section('title', auth()->check() ? 'Sản phẩm yêu thích của tôi' : 'Top sản phẩm được yêu thích nhất')

@section('content')
    <section class="module bg-light" id="favorites">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    @auth
                        <h2 class="module-title font-alt">Sản phẩm yêu thích của tôi</h2>
                        <div class="module-subtitle font-serif">Danh sách những sản phẩm bạn đã đánh dấu yêu thích</div>
                    @else
                        <h2 class="module-title font-alt">Top sản phẩm được yêu thích nhất</h2>
                        <div class="module-subtitle font-serif">Danh sách những sản phẩm được người dùng đánh dấu yêu thích nhiều nhất</div>
                    @endauth
                </div>
            </div>
            <div class="row">
                @forelse($products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="shop-item">
                            <div class="shop-item-image">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('/images/no-image.png') }}"
                                     alt="{{ $product->name }}"
                                     style="width: 100%; height: 250px; object-fit: cover;">
                                <div class="shop-item-detail">
                                    <a href="{{ route('client.single-product', $product->id) }}" class="btn btn-round btn-b">
                                        <span class="fa fa-eye"></span> Xem chi tiết
                                    </a>
                                    @auth
                                        <button class="btn btn-round btn-danger remove-favorite" 
                                                data-product-id="{{ $product->id }}"
                                                style="margin-top: 5px;">
                                            <span class="fa fa-heart-o"></span> Bỏ yêu thích
                                        </button>
                                    @endauth
                                </div>
                            </div>
                            <h4 class="shop-item-title font-alt">
                                <a href="{{ route('client.single-product', $product->id) }}">{{ $product->name }}</a>
                            </h4>
                            <div class="shop-item-price">
                                @if($product->discount_price && $product->discount_price < $product->price)
                                    <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                    <span class="new-price">{{ number_format($product->discount_price, 0, ',', '.') }}₫</span>
                                @else
                                    <span class="price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                @endif
                            </div>
                            <div class="shop-item-stats">
                                <small>
                                    <i class="fa fa-heart text-danger"></i> {{ $product->favorites_count ?? 0 }} yêu thích
                                    | <i class="fa fa-eye"></i> {{ $product->view ?? 0 }} lượt xem
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-favorites-container">
                            @auth
                                <div class="empty-favorites">
                                    <div class="empty-icon">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <h3 class="empty-title">Chưa có sản phẩm yêu thích</h3>
                                    <p class="empty-description">Hãy khám phá và thêm những sản phẩm bạn yêu thích vào danh sách!</p>
                                    <div class="empty-actions">
                                        <a href="{{ route('client.product') }}" class="btn btn-primary btn-lg btn-explore">
                                            <i class="fa fa-search"></i> Khám phá sản phẩm
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="empty-favorites">
                                    <div class="empty-icon">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <h3 class="empty-title">Top sản phẩm được yêu thích</h3>
                                    <p class="empty-description">Hiện tại chưa có sản phẩm nào được yêu thích. Hãy <a href="{{ route('login') }}" class="login-link">đăng nhập</a> để bắt đầu tạo danh sách yêu thích của bạn!</p>
                                    <div class="empty-actions">
                                        <a href="{{ route('client.product') }}" class="btn btn-primary btn-lg btn-explore">
                                            <i class="fa fa-search"></i> Xem tất cả sản phẩm
                                        </a>
                                        <a href="{{ route('login') }}" class="btn btn-outline btn-lg">
                                            <i class="fa fa-sign-in"></i> Đăng nhập
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Enhanced remove from favorites with better UX
    $('.remove-favorite').on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const productId = button.data('product-id');
        const shopItem = button.closest('.col-sm-6, .col-md-4, .col-lg-3');
        const productName = shopItem.find('.shop-item-title a').text().trim();
        
        // Show confirmation dialog
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: `Bạn có chắc muốn bỏ "${productName}" khỏi danh sách yêu thích?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Có, xóa ngay',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performRemove();
                }
            });
        } else {
            if (confirm(`Bạn có chắc muốn bỏ "${productName}" khỏi danh sách yêu thích?`)) {
                performRemove();
            }
        }
        
        function performRemove() {
            // Disable button during request
            button.prop('disabled', true);
            button.html('<i class="fa fa-spinner fa-spin"></i> Đang xóa...');
            
            $.ajax({
                url: '{{ route("client.favorite.remove") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Add smooth removal animation
                        shopItem.addClass('removing');
                        shopItem.fadeOut(400, function() {
                            $(this).remove();
                            
                            // Check if no more products
                            if ($('.shop-item').length === 0) {
                                $('.row').append(`
                                    <div class="col-12">
                                        <div class="empty-favorites-container">
                                            <div class="empty-favorites">
                                                <div class="empty-icon">
                                                    <i class="fa fa-heart-o"></i>
                                                </div>
                                                <h3 class="empty-title">Chưa có sản phẩm yêu thích</h3>
                                                <p class="empty-description">Hãy khám phá và thêm những sản phẩm bạn yêu thích vào danh sách!</p>
                                                <div class="empty-actions">
                                                    <a href="{{ route('client.product') }}" class="btn btn-primary btn-lg btn-explore">
                                                        <i class="fa fa-search"></i> Khám phá sản phẩm
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).hide().fadeIn(500);
                            }
                        });
                        
                        // Show success message with better styling
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Đã xóa thành công',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                position: 'top-end',
                                toast: true
                            });
                        } else if (typeof toastr !== 'undefined') {
                            toastr.success(response.message, '', {
                                timeOut: 3000,
                                progressBar: true
                            });
                        } else {
                            alert(response.message);
                        }
                        
                        // Update favorite count in navbar if function exists
                        if (window.refreshFavoriteCount) {
                            window.refreshFavoriteCount();
                        }
                        
                    } else {
                        button.prop('disabled', false);
                        button.html('<span class="fa fa-heart-o"></span> Bỏ yêu thích');
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Lỗi!', response.message, 'error');
                        } else if (typeof toastr !== 'undefined') {
                            toastr.error(response.message);
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    button.prop('disabled', false);
                    button.html('<span class="fa fa-heart-o"></span> Bỏ yêu thích');
                    
                    const message = xhr.responseJSON?.message || 'Có lỗi xảy ra. Vui lòng thử lại.';
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Lỗi!', message, 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error(message);
                    } else {
                        alert(message);
                    }
                }
            });
        }
    });
    
    // Add smooth hover effects
    $('.shop-item').hover(
        function() {
            $(this).addClass('hover-effect');
        },
        function() {
            $(this).removeClass('hover-effect');
        }
    );
});
</script>

<style>
.shop-item {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
    margin-bottom: 30px;
}

.shop-item:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.shop-item-image {
    position: relative;
    overflow: hidden;
}

.shop-item-detail {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.shop-item-image:hover .shop-item-detail {
    opacity: 1;
}

.shop-item-title {
    padding: 15px;
    margin: 0;
    font-size: 16px;
}

.shop-item-title a {
    color: #333;
    text-decoration: none;
}

.shop-item-title a:hover {
    color: #e74c3c;
}

.shop-item-price {
    padding: 0 15px;
    margin-bottom: 10px;
}

.old-price {
    text-decoration: line-through;
    color: #999;
    margin-right: 10px;
}

.new-price, .price {
    color: #e74c3c;
    font-weight: bold;
    font-size: 18px;
}

.shop-item-stats {
    padding: 0 15px 15px;
    color: #666;
}

/* Empty Favorites Styling */
.empty-favorites-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
    padding: 40px 20px;
}

.empty-favorites {
    text-align: center;
    max-width: 500px;
    background: white;
    border-radius: 16px;
    padding: 50px 40px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid #f0f0f0;
}

.empty-icon {
    margin-bottom: 25px;
}

.empty-icon i {
    font-size: 80px;
    color: #e0e0e0;
    transition: all 0.3s ease;
}

.empty-favorites:hover .empty-icon i {
    color: #e74c3c;
    transform: scale(1.1);
}

.empty-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    line-height: 1.4;
}

.empty-description {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
}

.empty-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: center;
}

.btn-explore {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    border: none;
    color: white;
    padding: 12px 30px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
}

.btn-explore:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
    color: white;
    text-decoration: none;
}

.btn-outline {
    background: transparent;
    border: 2px solid #e74c3c;
    color: #e74c3c;
    padding: 10px 25px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-outline:hover {
    background: #e74c3c;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.login-link {
    color: #e74c3c;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 1px solid transparent;
    transition: all 0.3s ease;
}

.login-link:hover {
    color: #c0392b;
    border-bottom-color: #c0392b;
    text-decoration: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .empty-favorites {
        padding: 30px 20px;
        margin: 0 10px;
    }
    
    .empty-icon i {
        font-size: 60px;
    }
    
    .empty-title {
        font-size: 20px;
    }
    
    .empty-description {
        font-size: 14px;
    }
    
    .empty-actions {
        gap: 10px;
    }
    
    .btn-explore, .btn-outline {
        width: 100%;
        max-width: 250px;
    }
}

@media (max-width: 480px) {
    .empty-favorites-container {
        min-height: 300px;
        padding: 20px 10px;
    }
    
    .empty-favorites {
        padding: 25px 15px;
    }
}

.btn-danger.remove-favorite {
    background: #e74c3c;
    border-color: #e74c3c;
}

.btn-danger.remove-favorite:hover {
    background: #c0392b;
    border-color: #c0392b;
}

/* Shop item animations */
.shop-item.hover-effect {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.shop-item.removing {
    transform: scale(0.95);
    opacity: 0.7;
}

/* Loading state for remove button */
.remove-favorite:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.remove-favorite .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Enhanced card styling */
.shop-item {
    position: relative;
    overflow: hidden;
}

.shop-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
    z-index: 1;
}

.shop-item:hover::before {
    left: 100%;
}
</style>
@endsection
