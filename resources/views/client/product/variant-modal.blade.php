<div class="variant-selection">
    <div class="product-info mb-4">
        <div class="row align-items-center">
            <div class="col-md-5">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: cover; width: 100%;">
            </div>
            <div class="col-md-7">
                <h4 class="font-weight-bold text-primary mb-2">{{ $product->name }}</h4>
                <p class="text-muted mb-3">
                    <i class="fa fa-tag mr-1"></i>
                    {{ $product->category->name ?? 'Không có danh mục' }}
                </p>
                
                <!-- Rating và Sales -->
                <div class="product-stats mb-3">
                    <div class="stats-row">
                        <div class="rating-info">
                            <div class="stars">
                                @php
                                    $avgRating = $product->reviews ? $product->reviews->avg('rating') : 0;
                                    $fullStars = floor($avgRating);
                                    $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                @endphp
                                
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $fullStars)
                                        <i class="fa fa-star text-warning"></i>
                                    @elseif($i == $fullStars + 1 && $hasHalfStar)
                                        <i class="fa fa-star-half-o text-warning"></i>
                                    @else
                                        <i class="fa fa-star-o text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-text ml-2">
                                <strong>{{ number_format($avgRating, 1) }}</strong>
                                <span class="text-muted">({{ $product->reviews ? $product->reviews->count() : 0 }} đánh giá)</span>
                            </span>
                        </div>
                        <div class="sales-info">
                            <i class="fa fa-shopping-cart text-success mr-1"></i>
                            <span class="sales-text">
                                <strong>{{ $product->orderDetails ? $product->orderDetails->sum('quantity') : 0 }}</strong>
                                <span class="text-muted"> lượt mua</span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="product-description text-muted mb-3">
                    {!! Str::limit($product->description, 150) !!}
                </div>
                <div class="price-display-section">
                    <div class="selected-price-info" id="selectedPriceInfo" style="display: none;">
                        <h5 class="font-weight-bold text-dark mb-2">
                            <i class="fa fa-tag mr-2 text-primary"></i>
                            Giá sản phẩm
                        </h5>
                        <div class="price-details">
                            <div class="current-price mb-2">
                                <span class="text-danger font-weight-bold" style="font-size: 24px;" id="currentPrice">0đ</span>
                            </div>
                            <div class="original-price" id="originalPrice" style="display: none;">
                                <span class="text-muted text-decoration-line-through" style="font-size: 16px;">0đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($product->variants && $product->variants->count() > 0)
        <div class="variants-section">
            <h5 class="font-weight-bold mb-4 text-dark">
                <i class="fa fa-list-ul mr-2 text-primary"></i>
                Chọn phiên bản
            </h5>
            
                         <form id="variantSelectionForm" action="{{ route('client.add-to-cart') }}" method="POST">
                 @csrf
                 <input type="hidden" name="product_id" value="{{ $product->id }}">
                 <input type="hidden" name="variant_id" value="">
                 <input type="hidden" name="quantity" value="1">
                
                <div class="variant-selection-section">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="variantSelect" class="font-weight-bold text-dark mb-2">
                                    <i class="fa fa-list-ul mr-2 text-primary"></i>
                                    Chọn phiên bản
                                </label>
                                <select class="form-control" id="variantSelect" onchange="selectVariantFromDropdown()">
                                    <option value="">-- Chọn phiên bản --</option>
                                    @foreach($product->variants as $variant)
                                        @if($variant->stock > 0)
                                            <option value="{{ $variant->id }}" 
                                                    data-price="{{ $variant->price }}"
                                                    data-promotion-price="{{ $variant->promotion_price ?: 0 }}"
                                                    data-stock="{{ $variant->stock }}"
                                                    data-color="{{ $variant->color ? $variant->color->name : '' }}"
                                                    data-storage="{{ $variant->storage ? $variant->storage->name : '' }}">
                                                @if($variant->color)
                                                    {{ $variant->color->name }}
                                                @endif
                                                @if($variant->storage)
                                                    - {{ $variant->storage->name }}
                                                @endif
                                                @if(!$variant->color && !$variant->storage)
                                                    Phiên bản {{ $loop->iteration }}
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity" class="font-weight-bold text-dark mb-2">
                                    <i class="fa fa-sort-numeric-up mr-2 text-primary"></i>
                                    Số lượng
                                </label>
                                <div class="quantity-input-group">
                                    <button type="button" class="quantity-btn quantity-minus" onclick="changeQuantity(-1)">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="99">
                                    <button type="button" class="quantity-btn quantity-plus" onclick="changeQuantity(1)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="stock-info mt-2" id="stockInfoSection" style="display: none;">
                        <span class="text-success" id="stockInfo">
                            <i class="fa fa-check-circle"></i> Còn <span id="stockQuantity">0</span> sản phẩm
                        </span>
                    </div>
                </div>

                <div class="action-buttons mt-4 d-flex gap-3">
                    <button type="button" class="btn btn-success flex-fill" id="buyNowBtn" disabled>
                        <i class="fa fa-bolt mr-2"></i> 
                        Mua ngay
                    </button>
                    <button type="submit" class="btn btn-primary flex-fill" id="addToCartBtn" disabled>
                        <i class="fa fa-shopping-cart mr-2"></i> 
                        Thêm vào giỏ hàng
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times mr-2"></i> 
                        Đóng
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> Sản phẩm này không có phiên bản khác.
        </div>
    @endif
</div>



<style>
.variant-selection-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.variant-selection-section .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    background: white;
}

.variant-selection-section .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    background: white;
}

.price-display-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 20px;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.selected-price-info {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.price-details .current-price {
    margin-bottom: 8px;
}

.price-details .current-price span {
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.price-details .original-price {
    margin-bottom: 12px;
}

.product-stats {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    padding: 12px;
    border: 1px solid #e9ecef;
}

.stats-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.rating-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.stars {
    display: inline-flex;
    align-items: center;
}

.stars i {
    font-size: 14px;
    margin-right: 1px;
}

.stars i.text-warning {
    color: #ffc107 !important;
}

.stars i.text-muted {
    color: #6c757d !important;
}

.rating-text {
    font-size: 13px;
}

.rating-text strong {
    color: #212529;
}

.sales-info {
    display: flex;
    align-items: center;
    margin-left: auto;
}

.sales-text {
    font-size: 13px;
}

.sales-text strong {
    color: #28a745;
}

.stock-info {
    font-size: 14px;
    font-weight: 500;
    padding: 8px 12px;
    background: rgba(40,167,69,0.1);
    border-radius: 6px;
    display: inline-block;
    animation: fadeIn 0.3s ease-in;
}

.stock-info .text-success {
    color: #28a745 !important;
}

.quantity-input-group {
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 38px;
}

.quantity-input-group:focus-within {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.quantity-btn {
    width: 38px;
    height: 38px;
    border: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.quantity-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s;
}

.quantity-btn:hover::before {
    left: 100%;
}

.quantity-btn:hover {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.quantity-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(0,123,255,0.2);
}

.quantity-btn i {
    font-size: 12px;
    font-weight: 700;
}

.quantity-input {
    flex: 1;
    height: 38px;
    border: none;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
    color: #212529;
    background: white;
    outline: none;
    padding: 0 12px;
    min-width: 60px;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}

.action-buttons .btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.action-buttons .btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.action-buttons .btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .variant-selection-section .row > div {
        margin-bottom: 15px;
    }
    
    .price-display-section {
        margin-top: 15px;
    }
    
    .variant-selection-section .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .quantity-input-group {
        max-width: 150px;
        margin: 0 auto;
    }
    
    .stats-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .sales-info {
        margin-left: 0;
    }
}
</style>

<script>
// Global variables
window.selectedVariantId = null;
window.selectedVariantPrice = 0;
window.selectedVariantPromotionPrice = 0;

// Make functions globally available
window.selectVariantFromDropdown = function() {
    const select = document.getElementById('variantSelect');
    const selectedOption = select.options[select.selectedIndex];
    
    if (select.value === '') {
        // Không có variant nào được chọn
        window.selectedVariantId = null;
        window.selectedVariantPrice = 0;
        window.selectedVariantPromotionPrice = 0;
        
        // Ẩn thông tin giá
        document.getElementById('selectedPriceInfo').style.display = 'none';
        
        // Ẩn thông tin tồn kho
        document.getElementById('stockInfoSection').style.display = 'none';
        
                 // Disable cả hai nút
         const addToCartBtn = document.getElementById('addToCartBtn');
         const buyNowBtn = document.getElementById('buyNowBtn');
         if (addToCartBtn) {
             addToCartBtn.disabled = true;
         }
         if (buyNowBtn) {
             buyNowBtn.disabled = true;
         }
        
        return;
    }
    
    // Lấy thông tin từ option được chọn
    const variantId = parseInt(select.value);
    const price = parseInt(selectedOption.dataset.price);
    const promotionPrice = parseInt(selectedOption.dataset.promotionPrice);
    const stock = parseInt(selectedOption.dataset.stock);
    const color = selectedOption.dataset.color;
    const storage = selectedOption.dataset.storage;
    
    console.log('Variant selected:', { variantId, price, promotionPrice, stock, color, storage });
    
         // Lưu thông tin variant đã chọn
     window.selectedVariantId = variantId;
     window.selectedVariantPrice = price;
     window.selectedVariantPromotionPrice = promotionPrice;
     
     console.log('Global variables updated:', {
         selectedVariantId: window.selectedVariantId,
         selectedVariantPrice: window.selectedVariantPrice,
         selectedVariantPromotionPrice: window.selectedVariantPromotionPrice
     });
    
    // Hiển thị thông tin giá
    displayPriceInfo(price, promotionPrice, stock);
    
         // Enable cả hai nút
     const addToCartBtn = document.getElementById('addToCartBtn');
     const buyNowBtn = document.getElementById('buyNowBtn');
     if (addToCartBtn) {
         addToCartBtn.disabled = false;
         console.log('Add to cart button enabled');
     }
     if (buyNowBtn) {
         buyNowBtn.disabled = false;
         console.log('Buy now button enabled');
     }
};

window.displayPriceInfo = function(price, promotionPrice, stock) {
    const priceInfo = document.getElementById('selectedPriceInfo');
    const currentPriceEl = document.getElementById('currentPrice');
    const originalPriceEl = document.getElementById('originalPrice');
    const stockInfoSection = document.getElementById('stockInfoSection');
    const stockQuantityEl = document.getElementById('stockQuantity');
    
    // Hiển thị section giá
    priceInfo.style.display = 'block';
    
    // Cập nhật giá hiện tại
    if (promotionPrice > 0) {
        currentPriceEl.textContent = formatPrice(promotionPrice);
        currentPriceEl.className = 'text-danger font-weight-bold';
        
        // Hiển thị giá gốc
        originalPriceEl.style.display = 'block';
        originalPriceEl.querySelector('span').textContent = formatPrice(price);
    } else {
        currentPriceEl.textContent = formatPrice(price);
        currentPriceEl.className = 'text-primary font-weight-bold';
        
        // Ẩn giá gốc
        originalPriceEl.style.display = 'none';
    }
    
    // Hiển thị thông tin tồn kho dưới quantity
    stockInfoSection.style.display = 'block';
    stockQuantityEl.textContent = stock;
};

window.formatPrice = function(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
};

window.changeQuantity = function(delta) {
    const quantityInput = document.getElementById('quantity');
    const hiddenQuantityInput = document.querySelector('input[name="quantity"]');
    
    if (quantityInput) {
        let currentQuantity = parseInt(quantityInput.value) || 1;
        let newQuantity = currentQuantity + delta;
        
        if (newQuantity < 1) newQuantity = 1;
        if (newQuantity > 99) newQuantity = 99;
        
        quantityInput.value = newQuantity;
        
        // Cập nhật hidden input
        if (hiddenQuantityInput) {
            hiddenQuantityInput.value = newQuantity;
        }
        
        console.log('Quantity changed to:', newQuantity);
        console.log('Hidden input value:', hiddenQuantityInput ? hiddenQuantityInput.value : 'not found');
    }
};



// Initialize when document is ready
$(document).ready(function() {
    console.log('Modal content loaded, initializing...');
    
    // Ensure modal is properly initialized
    if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
        console.log('Bootstrap modal is available');
    } else {
        console.error('Bootstrap modal is not available');
    }
    
    // Sync quantity input with hidden input
    $('#quantity').on('input', function() {
        const value = $(this).val();
        $('input[name="quantity"]').val(value);
        console.log('Quantity input changed to:', value);
    });
    

    
         // Handle buy now button
     $(document).on('click', '#buyNowBtn', function(e) {
         e.preventDefault();
         console.log('Buy now clicked');
         console.log('Selected variant ID:', window.selectedVariantId);
         console.log('Selected variant price:', window.selectedVariantPrice);
         
         if (!window.selectedVariantId) {
             alert('Vui lòng chọn một phiên bản!');
             return;
         }
         
         const $button = $(this);
         const originalText = $button.html();
         
         $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
         
         const formData = {
             product_id: '{{ $product->id }}',
             variant_id: window.selectedVariantId,
             quantity: $('#quantity').val(),
             _token: $('meta[name="csrf-token"]').attr('content')
         };
         
         console.log('Buy now form data:', formData);
         
         $.ajax({
             url: '{{ route("client.buy-now") }}',
             method: 'POST',
             data: formData,
             success: function(response) {
                 if (response.success) {
                     $button.html('<i class="fa fa-check"></i> Thành công!');
                     setTimeout(() => {
                         $('#variantModal').modal('hide');
                         window.location.href = response.redirect_url || '{{ route("client.checkout") }}';
                     }, 1000);
                 } else {
                     alert(response.message || 'Có lỗi xảy ra!');
                     $button.prop('disabled', false).html(originalText);
                 }
             },
             error: function() {
                 alert('Có lỗi xảy ra khi xử lý mua ngay!');
                 $button.prop('disabled', false).html(originalText);
             }
         });
     });
     
     // Handle form submit (add to cart)
     $(document).on('submit', '#variantSelectionForm', function(e) {
         e.preventDefault();
         console.log('Form submitted');
         console.log('Selected variant ID:', window.selectedVariantId);
         console.log('Selected variant price:', window.selectedVariantPrice);
         
         if (!window.selectedVariantId) {
             alert('Vui lòng chọn một phiên bản!');
             return;
         }
         
                             // Cập nhật form data
          const $form = $(this);
          $form.find('input[name="variant_id"]').val(window.selectedVariantId);
          $form.find('input[name="quantity"]').val($('#quantity').val());
          
          console.log('Updated form data:', {
              variant_id: window.selectedVariantId,
              quantity: $('#quantity').val(),
              product_id: '{{ $product->id }}'
          });
          
          // Kiểm tra variant_id có được set đúng không
          const variantIdInput = $form.find('input[name="variant_id"]');
          console.log('Variant ID input value:', variantIdInput.val());
          console.log('Form data:', $form.serialize());
         
         const $button = $('#addToCartBtn');
         const originalText = $button.html();
         
         $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang thêm...');
         
         $.ajax({
             url: $form.attr('action'),
             method: 'POST',
             data: $form.serialize(),
             success: function(response) {
                 if (response.success) {
                     $button.html('<i class="fa fa-check"></i> Đã thêm!');
                     setTimeout(() => {
                         $('#variantModal').modal('hide');
                         location.reload();
                     }, 1000);
                 } else {
                     alert(response.message || 'Có lỗi xảy ra!');
                     $button.prop('disabled', false).html(originalText);
                 }
             },
             error: function() {
                 alert('Có lỗi xảy ra khi thêm vào giỏ hàng!');
                 $button.prop('disabled', false).html(originalText);
             }
         });
     });
    
    console.log('Modal initialization complete');
});
</script> 