@extends('layouts.client')

@section('title', auth()->check() ? 'Sản phẩm yêu thích của tôi' : 'Top sản phẩm được yêu thích nhất')

@section('styles')
<link rel="stylesheet" href="{{ asset('client/assets/css/favorites.css') }}">
@endsection

@section('content')
    <section class="module bg-light" id="favorites">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    @auth
                        <h2 class="module-title font-alt">
                            <span class="live-indicator"></span>Sản phẩm yêu thích của tôi
                        </h2>
                        <div class="module-subtitle font-serif">Danh sách những sản phẩm bạn đã đánh dấu yêu thích • <small class="text-muted">Cập nhật realtime</small></div>
                    @else
                        <h2 class="module-title font-alt">
                            <span class="live-indicator"></span>Top sản phẩm được yêu thích nhất
                        </h2>
                        <div class="module-subtitle font-serif">Danh sách những sản phẩm được người dùng đánh dấu yêu thích nhiều nhất • <small class="text-muted">Cập nhật realtime</small></div>
                    @endauth
                </div>
            </div>
            <div class="row products-container">
                @forelse($products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="shop-item">
                            <div class="shop-item-image">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('/images/no-image.png') }}"
                                     alt="{{ $product->name }}"
                                     class="product-image">
                                <div class="shop-item-detail">
                                    <a href="{{ route('client.single-product', $product->id) }}" class="btn btn-round btn-b">
                                        <span class="fa fa-eye"></span> Xem chi tiết
                                    </a>
                                    @auth
                                        <button class="btn btn-round btn-danger remove-favorite" 
                                                data-product-id="{{ $product->id }}">
                                            <span class="fa fa-heart-o"></span> Bỏ yêu thích
                                        </button>
                                    @endauth
                                </div>
                            </div>
                            <div class="shop-item-content">
                                <h4 class="shop-item-title font-alt">
                                    <a href="{{ route('client.single-product', $product->id) }}">{{ $product->name }}</a>
                                </h4>
                                <div class="shop-item-price">
                                    @if($product->price && $product->promotion_price && $product->promotion_price < $product->price)
                                        <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                        <span class="new-price">{{ number_format($product->promotion_price, 0, ',', '.') }}₫</span>
                                    @elseif($product->price)
                                        <span class="price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                    @else
                                        <span class="price">Liên hệ</span>
                                    @endif
                                </div>
                                                <div class="shop-item-stats">
                    <small>
                        <i class="fa fa-heart text-danger"></i> <span class="favorite-count product-{{ $product->id }}-favorites">{{ $product->favorites_count ?? 0 }}</span> yêu thích
                        | <i class="fa fa-eye"></i> {{ $product->view ?? 0 }} lượt xem
                    </small>
                </div>
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
    // Setup realtime favorite updates
    if (window.Echo) {
        console.log('Setting up Echo listeners...');
        
        // Listen to general favorite updates
        window.Echo.channel('favorites')
            .listen('FavoriteUpdated', (e) => {
                console.log('✅ Favorite update received:', e);
                
                // Don't show notifications for current user's actions
                if (window.currentUserId && e.user_id !== window.currentUserId) {
                    // Show realtime notification
                    window.RealtimeNotifications.showToast(
                        e.action === 'added' ? 'success' : 'info',
                        'Cập nhật realtime',
                        e.message
                    );
                    
                    // Add to activity feed
                    if (typeof addActivityItem === 'function') {
                        addActivityItem(e);
                    }
                }
                
                // Update favorite count for all users
                window.RealtimeNotifications.updateFavoriteCount(e.product_id, e.favorite_count);
            })
            .error((error) => {
                console.error('❌ Error listening to favorites channel:', error);
            });
            
        console.log('Echo listeners setup complete');
    } else {
        console.error('❌ Echo not initialized');
    }
});
</script>

<style>
/* Main Grid Layout */
.products-container {
    min-height: 300px;
}

/* Shop Item Styling */
.shop-item {
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 30px;
    transition: all 0.3s ease, opacity 0.3s ease, transform 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.shop-item:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

/* Product Image */
.shop-item-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.shop-item:hover .product-image {
    transform: scale(1.05);
}

/* Overlay Detail */
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
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.shop-item-image:hover .shop-item-detail {
    opacity: 1;
}

/* Content Area */
.shop-item-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.shop-item-title {
    margin: 0 0 15px 0;
    font-size: 16px;
    line-height: 1.4;
    flex: 1;
}

.shop-item-title a {
    color: #333;
    text-decoration: none;
    display: block;
}

.shop-item-title a:hover {
    color: #e74c3c;
}

/* Price Styling */
.shop-item-price {
    margin-bottom: 10px;
}

.old-price {
    text-decoration: line-through;
    color: #999;
    margin-right: 10px;
    font-size: 14px;
}

.new-price, .price {
    color: #e74c3c;
    font-weight: bold;
    font-size: 18px;
}

.shop-item-stats {
    color: #666;
    font-size: 12px;
}

/* Button Styling */
.remove-favorite {
    margin-top: 5px;
}

.remove-favorite:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Animation Classes */
.fade-out {
    opacity: 0 !important;
    transform: scale(0.95) !important;
    transition: all 0.3s ease !important;
    pointer-events: none !important;
}

/* Loading State */
.page-loading {
    position: relative;
    overflow: hidden;
}

.page-loading::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.page-loading::after {
    content: 'Đang tải...';
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10000;
    font-size: 18px;
    color: #333;
    background: white;
    padding: 20px 40px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Improved fade-out animation */
.shop-item.fade-out {
    opacity: 0;
    transform: scale(0.9) translateY(-20px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Success feedback animation */
.shop-item.success-feedback {
    animation: successPulse 0.6s ease-in-out;
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Empty State Styling */
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

/* Button Styles */
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

/* Responsive Design */
@media (max-width: 768px) {
    .shop-item {
        margin-bottom: 20px;
    }
    
    .shop-item-image {
        height: 200px;
    }
    
    .shop-item-content {
        padding: 15px;
    }
    
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
    
    .shop-item-image {
        height: 180px;
    }
}

/* Spinner Animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

/* Realtime Updates Styling */
.favorite-count.updated {
    animation: favoriteUpdate 0.6s ease-in-out;
    color: #e74c3c !important;
    font-weight: bold;
}

@keyframes favoriteUpdate {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); color: #e74c3c; }
}

/* Realtime Toast Styling */
.realtime-toast {
    font-size: 14px !important;
    border-left: 4px solid #e74c3c !important;
}

.realtime-toast .swal2-title {
    font-size: 16px !important;
    color: #333 !important;
}

.realtime-toast .swal2-content {
    color: #666 !important;
}

/* Realtime notification badge */
.realtime-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(231, 76, 60, 0); }
    100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); }
}

/* Live activity indicator */
.live-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    margin-right: 5px;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}
</style>
@endsection
