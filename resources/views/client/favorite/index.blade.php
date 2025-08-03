@extends('layouts.client')

@section('title', auth()->check() ? 'Sản phẩm yêu thích của tôi' : 'Top sản phẩm được yêu thích nhất')

@section('styles')
<link href="{{ asset("assets/external/css/tailwind.min.css") }}" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa !important;
        font-family: 'Arial', sans-serif !important;
    }
    .success-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 40px 20px !important;
    }
    
    /* Header section like other pages */
    .page-header {
        background: white !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        padding: 2rem !important;
        margin-bottom: 2rem !important;
        border: none !important;
    }
    
    .breadcrumb {
        background: transparent !important;
        padding: 0 !important;
        margin-bottom: 2rem !important;
        border-radius: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        font-size: 0.9rem !important;
    }
    
    .breadcrumb-item {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
    }
    
    .breadcrumb-item a {
        color: #6c757d !important;
        text-decoration: none !important;
        transition: color 0.3s ease !important;
    }
    
    .breadcrumb-item a:hover {
        color: #007bff !important;
    }
    
    .breadcrumb-item.active a {
        color: #007bff !important;
        font-weight: 600 !important;
    }
    
    .breadcrumb-separator {
        color: #6c757d !important;
        margin: 0 0.5rem !important;
    }
    
    .page-title-section {
        text-align: center !important;
        margin-bottom: 1rem !important;
    }
    
    .page-title {
        font-size: 2.5rem !important;
        font-weight: 700 !important;
        color: #2c3e50 !important;
        margin-bottom: 0.5rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 1rem !important;
    }
    
    .page-title i {
        font-size: 2.5rem !important;
        color: #007bff !important;
    }
    
    .page-subtitle {
        font-size: 1.1rem !important;
        color: #6c757d !important;
        margin-bottom: 0 !important;
    }
    
    .card {
        background-color: white !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        padding: 1.5rem !important;
        margin-bottom: 1.5rem !important;
        border: none !important;
    }
    
    /* Products Grid */
    .products-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
        gap: 2rem !important;
        margin-top: 2rem !important;
    }
    
    .product-card {
        background: white !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
        transition: all 0.3s ease !important;
        border: none !important;
    }
    
    .product-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
    }
    
    .product-image-container {
        position: relative !important;
        overflow: hidden !important;
        height: 200px !important;
    }
    
    .product-image {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.3s ease !important;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05) !important;
    }
    
    .product-overlay {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        background: rgba(0, 0, 0, 0.7) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 1rem !important;
        opacity: 0 !important;
        transition: opacity 0.3s ease !important;
    }
    
    .product-card:hover .product-overlay {
        opacity: 1 !important;
    }
    
    .overlay-btn {
        background: #007bff !important;
        color: white !important;
        padding: 0.75rem 1rem !important;
        border-radius: 0.5rem !important;
        text-decoration: none !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        border: none !important;
    }
    
    .overlay-btn:hover {
        background: #0056b3 !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    .overlay-btn.danger {
        background: #dc3545 !important;
    }
    
    .overlay-btn.danger:hover {
        background: #c82333 !important;
    }
    
    .product-content {
        padding: 1.5rem !important;
    }
    
    .product-title {
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        margin-bottom: 0.75rem !important;
        line-height: 1.4 !important;
    }
    
    .product-title a {
        color: inherit !important;
        text-decoration: none !important;
    }
    
    .product-title a:hover {
        color: #007bff !important;
    }
    
    .product-price {
        margin-bottom: 1rem !important;
    }
    
    .price {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: #dc3545 !important;
    }
    
    .old-price {
        font-size: 1rem !important;
        color: #6c757d !important;
        text-decoration: line-through !important;
        margin-right: 0.5rem !important;
    }
    
    .new-price {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: #dc3545 !important;
    }
    
    .product-stats {
        font-size: 0.85rem !important;
        color: #6c757d !important;
        display: flex !important;
        align-items: center !important;
        gap: 1rem !important;
    }
    
    .product-stats i {
        margin-right: 0.25rem !important;
    }
    
    .favorite-count {
        color: #dc3545 !important;
        font-weight: 600 !important;
    }
    
    /* Empty State */
    .empty-favorites {
        text-align: center !important;
        padding: 4rem 2rem !important;
    }
    
    .empty-icon {
        margin-bottom: 2rem !important;
    }
    
    .empty-icon i {
        font-size: 6rem !important;
        color: #e9ecef !important;
        opacity: 0.7 !important;
    }
    
    .empty-title {
        font-size: 1.8rem !important;
        font-weight: 600 !important;
        color: #495057 !important;
        margin-bottom: 1rem !important;
    }
    
    .empty-description {
        font-size: 1.1rem !important;
        color: #6c757d !important;
        margin-bottom: 2rem !important;
        line-height: 1.6 !important;
    }
    
    .btn-explore {
        background: #007bff !important;
        color: white !important;
        padding: 1rem 2rem !important;
        border-radius: 0.5rem !important;
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        transition: all 0.3s ease !important;
        border: none !important;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3) !important;
    }
    
    .btn-explore:hover {
        background: #0056b3 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4) !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .success-container {
            padding: 20px 15px !important;
        }
        .page-title {
            font-size: 2rem !important;
        }
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
            gap: 1rem !important;
        }
        .empty-favorites {
            padding: 3rem 1.5rem !important;
        }
        .empty-icon i {
            font-size: 4rem !important;
        }
        .empty-title {
            font-size: 1.5rem !important;
        }
        .empty-description {
            font-size: 1rem !important;
        }
    }
</style>
@endsection

@section('content')
<div class="success-container">
    <!-- Page Header -->
    <div class="page-header">
        <!-- Breadcrumbs -->
        <nav class="breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('client.home') }}">
                    <i class="fa fa-home"></i> Trang chủ
                </a>
            </div>
            <span class="breadcrumb-separator">/</span>
            <div class="breadcrumb-item active">
                <a href="#">
                    <i class="fa fa-heart"></i> Sản phẩm yêu thích
                </a>
            </div>
        </nav>

        <!-- Page Title Section -->
        <div class="page-title-section">
            @auth
                <h1 class="page-title">
                    <i class="fa fa-heart"></i>
                    Sản phẩm yêu thích của tôi
                </h1>
                <p class="page-subtitle">Danh sách những sản phẩm bạn đã đánh dấu yêu thích</p>
            @else
                <h1 class="page-title">
                    <i class="fa fa-heart"></i>
                    Top sản phẩm được yêu thích nhất
                </h1>
                <p class="page-subtitle">Danh sách những sản phẩm được người dùng đánh dấu yêu thích nhiều nhất</p>
            @endauth
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('/images/no-image.png') }}"
                             alt="{{ $product->name }}"
                             class="product-image">
                        <div class="product-overlay">
                            <a href="{{ route('client.single-product', $product->id) }}" class="overlay-btn">
                                <i class="fa fa-eye"></i> Xem chi tiết
                            </a>
                            @auth
                                <button class="overlay-btn danger remove-favorite" 
                                        data-product-id="{{ $product->id }}">
                                    <i class="fa fa-heart-o"></i> Bỏ yêu thích
                                </button>
                            @endauth
                        </div>
                    </div>
                    <div class="product-content">
                        <h3 class="product-title">
                            <a href="{{ route('client.single-product', $product->id) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="product-price">
                            @php
                                $minVariant = null;
                                if ($product->relationLoaded('variants') && $product->variants->count()) {
                                    $minVariant = $product->variants->where('promotion_price', '>', 0)->sortBy('promotion_price')->first();
                                    if (!$minVariant) {
                                        $minVariant = $product->variants->sortBy('price')->first();
                                    }
                                }
                            @endphp
                            @if($minVariant)
                                @if($minVariant->promotion_price && $minVariant->promotion_price < $minVariant->price)
                                    <span class="old-price">{{ number_format($minVariant->price, 0, ',', '.') }}₫</span>
                                    <span class="new-price">{{ number_format($minVariant->promotion_price, 0, ',', '.') }}₫</span>
                                @else
                                    <span class="price">{{ number_format($minVariant->price, 0, ',', '.') }}₫</span>
                                @endif
                            @elseif($product->price)
                                <span class="price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @else
                                <span class="price">Liên hệ</span>
                            @endif
                        </div>
                        <div class="product-stats">
                            <span>
                                <i class="fa fa-heart text-danger"></i> 
                                <span class="favorite-count product-{{ $product->id }}-favorites">{{ $product->favorites_count ?? 0 }}</span> yêu thích
                            </span>
                            <span>
                                <i class="fa fa-eye"></i> {{ $product->view ?? 0 }} lượt xem
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="empty-favorites">
                @auth
                    <div class="empty-icon">
                        <i class="fa fa-heart-o"></i>
                    </div>
                    <h3 class="empty-title">Chưa có sản phẩm yêu thích</h3>
                    <p class="empty-description">Hãy khám phá và thêm những sản phẩm bạn yêu thích vào danh sách!</p>
                    <a href="{{ route('client.product') }}" class="btn-explore">
                        <i class="fa fa-search"></i> Khám phá sản phẩm
                    </a>
                @else
                    <div class="empty-icon">
                        <i class="fa fa-heart-o"></i>
                    </div>
                    <h3 class="empty-title">Chưa có sản phẩm được yêu thích</h3>
                    <p class="empty-description">Hãy đăng nhập để xem danh sách sản phẩm yêu thích của bạn!</p>
                    <a href="{{ route('client.product') }}" class="btn-explore">
                        <i class="fa fa-search"></i> Khám phá sản phẩm
                    </a>
                @endauth
            </div>
        </div>
    @endif
</div>

<!-- Remove Favorite Modal -->
<div class="modal fade" id="removeFavoriteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn bỏ yêu thích sản phẩm này?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmRemove">Bỏ yêu thích</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Remove favorite functionality
    $('.remove-favorite').on('click', function() {
        const productId = $(this).data('product-id');
        const $card = $(this).closest('.product-card');
        
        $('#removeFavoriteModal').modal('show');
        
        $('#confirmRemove').off('click').on('click', function() {
            $.ajax({
                url: '{{ route("client.favorite.remove") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $card.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if no more products
                            if ($('.product-card').length === 0) {
                                location.reload();
                            }
                        });
                        
                        // Update navbar favorite count
                        if (response.favorite_count !== undefined) {
                            $('.favorite-count-navbar').text(response.favorite_count);
                        }
                    } else {
                        alert('Có lỗi xảy ra: ' + response.message);
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi xử lý yêu cầu!');
                }
            });
            
            $('#removeFavoriteModal').modal('hide');
        });
    });
});
</script>
@endsection
