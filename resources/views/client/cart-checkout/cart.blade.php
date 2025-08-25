@extends('layouts.client')

@section('title', 'Giỏ Hàng')

@section('styles')
<link href="{{ asset("assets/external/css/tailwind.min.css") }}" rel="stylesheet">
<style>
  .alert-warning {
    border-left: 4px solid #856404;
    background-color: #fff3cd;
  }
  
  .high-value-alert {
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(133, 100, 4, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(133, 100, 4, 0); }
    100% { box-shadow: 0 0 0 0 rgba(133, 100, 4, 0); }
  }
</style>
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
    
    /* Progress steps at top like checkout */
    .progress-container {
        background: white !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        padding: 2rem !important;
        margin-bottom: 2rem !important;
        border: none !important;
    }
    

    
    /* Header section like checkout */
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
    .section-title {
        border-bottom: 2px solid #007bff !important;
        padding-bottom: 0.5rem !important;
        margin-bottom: 1rem !important;
        color: #007bff !important;
        font-size: 1.5rem !important;
        font-weight: 600 !important;
    }
    .product-image {
        width: 100px !important;
        height: 100px !important;
        object-fit: cover !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
    }
    .highlight {
        background-color: #e3f2fd !important;
        border-left: 4px solid #007bff !important;
        padding-left: 1rem !important;
    }
    .button {
        background-color: #007bff !important;
        color: white !important;
        padding: 0.75rem 1.5rem !important;
        border-radius: 0.5rem !important;
        text-align: center !important;
        text-decoration: none !important;
        display: inline-block !important;
        transition: background-color 0.3s ease !important;
        border: none !important;
        font-weight: 600 !important;
    }
    .button:hover {
        background-color: #0056b3 !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    /* Override any existing styles */
    .container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
    }
    
    /* Ensure text colors are applied */
    .text-blue-600 {
        color: #007bff !important;
    }
    .text-gray-600 {
        color: #6c757d !important;
    }
    .text-gray-800 {
        color: #495057 !important;
    }
    .text-green-600 {
        color: #28a745 !important;
    }
    .text-yellow-600 {
        color: #ffc107 !important;
    }
    .text-red-600 {
        color: #dc3545 !important;
    }
    .text-purple-600 {
        color: #6f42c1 !important;
    }
    
    /* Ensure flexbox works */
    .flex {
        display: flex !important;
    }
    .items-center {
        align-items: center !important;
    }
    .justify-center {
        justify-content: center !important;
    }
    .mb-4 {
        margin-bottom: 1rem !important;
    }
    .mr-4 {
        margin-right: 1rem !important;
    }
    .mt-6 {
        margin-top: 1.5rem !important;
    }
    .ml-4 {
        margin-left: 1rem !important;
    }
    
    /* Ensure typography works */
    .text-4xl {
        font-size: 2.25rem !important;
    }
    .text-2xl {
        font-size: 1.5rem !important;
    }
    .text-xl {
        font-size: 1.25rem !important;
    }
    .font-bold {
        font-weight: 700 !important;
    }
    .font-semibold {
        font-weight: 600 !important;
    }
    
    /* Empty Cart Styling */
    .empty-cart {
        background: white !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        padding: 4rem 2rem !important;
        margin: 2rem 0 !important;
        text-align: center !important;
        border: none !important;
    }
    
    .empty-cart-icon {
        margin-bottom: 2rem !important;
    }
    
    .empty-cart-icon i {
        font-size: 8rem !important;
        color: #e9ecef !important;
        opacity: 0.7 !important;
        transition: all 0.3s ease !important;
    }
    
    .empty-cart:hover .empty-cart-icon i {
        opacity: 1 !important;
        transform: scale(1.05) !important;
    }
    
    .empty-cart h3 {
        font-size: 1.8rem !important;
        font-weight: 600 !important;
        color: #495057 !important;
        margin-bottom: 1rem !important;
    }
    
    .empty-cart p {
        font-size: 1.1rem !important;
        color: #6c757d !important;
        margin-bottom: 2rem !important;
        line-height: 1.6 !important;
    }
    
    .empty-cart .btn {
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
    
    .empty-cart .btn:hover {
        background: #0056b3 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4) !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    .empty-cart .btn i {
        font-size: 1rem !important;
    }
    
    /* Cart Section Styling */
    .cart-section {
        background: white !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        padding: 2rem !important;
        margin-bottom: 2rem !important;
        border: none !important;
    }
    
    .cart-section h4 {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        margin-bottom: 1.5rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
    }
    
    .cart-section h4 i {
        color: #007bff !important;
    }
    
    .badge-primary {
        background: #007bff !important;
        color: white !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 0.25rem !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
    }
    
    /* Cart Table Styling */
    .cart-table {
        margin-bottom: 0 !important;
        border: none !important;
    }
    
    .cart-table th {
        background: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
        font-weight: 600 !important;
        color: #495057 !important;
        padding: 1rem !important;
        text-align: center !important;
    }
    
    .cart-table td {
        padding: 1.5rem 1rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f8f9fa !important;
    }
    
    /* Product column styling */
    .cart-table td:nth-child(3) {
        text-align: left !important;
        min-width: 300px !important;
    }
    
    .cart-item:hover {
        background: #f8f9fa !important;
    }
    
    /* Product Image */
    .product-thumbnail {
        width: 80px !important;
        height: 80px !important;
        object-fit: cover !important;
        border-radius: 0.5rem !important;
        transition: transform 0.3s ease !important;
    }
    
    .product-thumbnail:hover {
        transform: scale(1.05) !important;
    }
    
    /* Product Info */
    .product-name {
        font-weight: 600 !important;
        margin-bottom: 0.5rem !important;
        font-size: 1.1rem !important;
        line-height: 1.4 !important;
    }
    
    .product-name a {
        color: #333 !important;
        text-decoration: none !important;
    }
    
    .product-name a:hover {
        color: #007bff !important;
    }
    
    .product-variants {
        font-size: 0.95rem !important;
        color: #6c757d !important;
        line-height: 1.5 !important;
        margin-top: 0.5rem !important;
    }
    
    .variant-item {
        display: inline-block !important;
        margin-right: 1rem !important;
        margin-bottom: 0.25rem !important;
        padding: 0.25rem 0.5rem !important;
        background: #f8f9fa !important;
        border-radius: 0.25rem !important;
        font-size: 0.85rem !important;
    }
    
    .variant-item {
        display: inline-block !important;
        margin-right: 1rem !important;
    }
    
    .color-preview {
        display: inline-block !important;
        width: 16px !important;
        height: 16px !important;
        border-radius: 50% !important;
        margin-right: 0.25rem !important;
        border: 2px solid #fff !important;
        box-shadow: 0 0 0 1px #dee2e6 !important;
    }
    
    /* Price Styling */
    .price {
        color: #dc3545 !important;
        font-weight: 600 !important;
        font-size: 1.1rem !important;
    }
    
    /* Quantity Controls */
    .quantity-controls {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
    }
    
    .btn-quantity-minus,
    .btn-quantity-plus {
        width: 32px !important;
        height: 32px !important;
        border: 1px solid #dee2e6 !important;
        background: white !important;
        border-radius: 0.25rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-quantity-minus:hover,
    .btn-quantity-plus:hover {
        background: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    .quantity-input {
        width: 60px !important;
        text-align: center !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.25rem !important;
        padding: 0.5rem !important;
    }
    
    /* Stock Warning */
    .stock-warning {
        color: #ffc107 !important;
        font-size: 0.8rem !important;
        margin-top: 0.5rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
    }
    
    /* Delete Button */
    .btn-remove {
        width: 32px !important;
        height: 32px !important;
        border: none !important;
        background: #f8d7da !important;
        color: #dc3545 !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-remove:hover {
        background: #dc3545 !important;
        color: white !important;
    }
    
    /* Order Summary Styling */
    .order-summary {
        background: white !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        padding: 2rem !important;
        border: none !important;
    }
    
    .order-summary h4 {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        margin-bottom: 1.5rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
    }
    
    .order-summary h4 i {
        color: #007bff !important;
    }
    
    .summary-details {
        margin-bottom: 2rem !important;
    }
    
    .summary-row {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 0.75rem 0 !important;
        font-size: 1rem !important;
    }
    
    .summary-row.total-row {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        border-top: 2px solid #dee2e6 !important;
        padding-top: 1rem !important;
        margin-top: 1rem !important;
    }
    
    .total-amount {
        color: #dc3545 !important;
        font-size: 1.3rem !important;
    }
    
    /* Checkout Actions */
    .checkout-actions {
        margin-top: 2rem !important;
    }
    
    .checkout-btn {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        color: white !important;
        padding: 1.25rem 2rem !important;
        padding-left: 44px !important;
        border-radius: 1rem !important;
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.75rem !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        border: none !important;
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3) !important;
        margin-bottom: 1.5rem !important;
        position: relative !important;
        overflow: hidden !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    .checkout-btn::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: -100% !important;
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent) !important;
        transition: left 0.5s !important;
    }
    
    .checkout-btn:hover::before {
        left: 100% !important;
    }
    
    .checkout-btn:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 12px 35px rgba(0, 123, 255, 0.4) !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    .checkout-btn:active {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3) !important;
    }
    
    .btn-outline-secondary {
        background: white !important;
        color: #6c757d !important;
        padding: 1rem 2rem !important;
        border-radius: 0.75rem !important;
        font-size: 1rem !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        transition: all 0.3s ease !important;
        border: 2px solid #e9ecef !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
    }
    
    .btn-outline-secondary:hover {
        background: #f8f9fa !important;
        color: #495057 !important;
        border-color: #007bff !important;
        text-decoration: none !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }
    
    /* Trust Badges */
    .trust-badges {
        margin-top: 2rem !important;
        padding-top: 1.5rem !important;
        border-top: 1px solid #e9ecef !important;
        text-align: center !important;
    }
    
    .trust-badges small {
        color: #6c757d !important;
        font-size: 0.9rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        font-weight: 500 !important;
    }
    
    .trust-badges small i {
        color: #28a745 !important;
        font-size: 1rem !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .success-container {
            padding: 20px 15px !important;
        }
        .page-title {
            font-size: 2rem !important;
        }
        .progress-steps {
            flex-direction: column !important;
            gap: 1rem !important;
        }
        .progress-steps::before {
            display: none !important;
        }
        .button {
            display: block !important;
            width: 100% !important;
            margin-bottom: 1rem !important;
        }
        
        /* Mobile checkout button improvements */
        .checkout-btn {
            padding: 1.5rem 1rem !important;
            font-size: 1rem !important;
            border-radius: 0.75rem !important;
        }
        
        .btn-outline-secondary {
            padding: 1.25rem 1rem !important;
            font-size: 0.95rem !important;
        }
        
        .trust-badges {
            margin-top: 1.5rem !important;
            padding-top: 1rem !important;
        }
        
        .trust-badges small {
            font-size: 0.85rem !important;
        }
        
        .empty-cart {
            padding: 3rem 1.5rem !important;
            margin: 1rem 0 !important;
        }
        
        .empty-cart-icon i {
            font-size: 6rem !important;
        }
        
        .empty-cart h3 {
            font-size: 1.5rem !important;
        }
        
        .empty-cart p {
            font-size: 1rem !important;
        }
        
        .empty-cart .btn {
            padding: 0.875rem 1.5rem !important;
            font-size: 1rem !important;
        }
        
        .cart-section,
        .order-summary {
            padding: 1.5rem !important;
        }
        
        .cart-table th,
        .cart-table td {
            padding: 0.75rem 0.5rem !important;
        }
        
        .product-thumbnail {
            width: 60px !important;
            height: 60px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="success-container">


    <!-- Page Header like success page -->
    <div class="page-header">
        <!-- Breadcrumbs -->
        <nav class="breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ route('client.home') }}">
                    <i class="fa fa-home"></i> Trang chủ
                </a>
            </div>
            <span class="breadcrumb-separator">/</span>
            <div class="breadcrumb-item">
                <a href="{{ route('client.product') }}">
                    <i class="fa fa-shopping-bag"></i> Sản phẩm
                </a>
            </div>
            <span class="breadcrumb-separator">/</span>
            <div class="breadcrumb-item active">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i> Giỏ hàng
                </a>
            </div>
        </nav>

        <!-- Page Title Section -->
        <div class="page-title-section">
            <h1 class="page-title">
                <i class="fa fa-shopping-cart"></i>
                Giỏ Hàng
            </h1>
            <p class="page-subtitle">Xem lại và chỉnh sửa đơn hàng của bạn</p>
        </div>
    </div>
      
      {{-- Success Message Banner --}}
      @if(session('cart_success'))
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <div class="alert alert-success alert-dismissible animate-in" 
               style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); 
                      border: none; border-radius: 15px; padding: 25px; margin: 20px 0; 
                      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15); position: relative; overflow: hidden;
                      z-index: 1050; margin-top: 30px;">
            <div class="success-pattern" style="position: absolute; top: -50%; right: -20px; width: 100px; height: 200%; 
                 background: rgba(255,255,255,0.1); transform: rotate(15deg); animation: shimmer 3s ease-in-out infinite;"></div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" 
                    style="font-size: 28px; color: #155724; opacity: 0.7; position: relative; z-index: 2;
                           transition: all 0.2s ease;">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="text-center" style="position: relative; z-index: 2;">
              <div class="success-icon mb-15">
                <i class="fa fa-check-circle" style="font-size: 60px; color: #28a745; margin-bottom: 15px; 
                   animation: iconPop 0.8s ease-out;"></i>
              </div>
              <h3 style="color: #155724; margin-bottom: 10px; font-weight: 600;">Thành công!</h3>
              <p style="font-size: 16px; color: #155724; margin: 0;">{{ session('cart_success') }}</p>
            </div>
          </div>
        </div>
      </div>
      @endif
      
      @if($cartItems->count() > 0)

      @if($isHighQuantityCart)
        <div class="alert alert-warning high-value-alert">
          <div class="d-flex align-items-center">
            <i class="fa fa-exclamation-triangle mr-3" style="font-size: 24px; color: #856404;"></i>
            <div>
              <h5 class="alert-heading mb-2">
                <strong>Thông báo quan trọng</strong>
              </h5>
              
              @if($isHighQuantityCart)
                <p class="mb-2">{{ $highQuantityMessage }}</p>
              @endif
              <p class="mb-0">
                <strong>Liên hệ tư vấn:</strong><br>
                <i class="fa fa-phone mr-2"></i>Hotline: 1900-xxxx<br>
                <i class="fa fa-envelope mr-2"></i>Email: support@winstar.com<br>
                <i class="fa fa-clock-o mr-2"></i>Thời gian: 8:00 - 22:00 (Thứ 2 - Chủ nhật)
              </p>
            </div>
          </div>
        </div>
      @endif
      
      <!-- Cart Items Section -->
      <div class="row">
        <div class="col-lg-8 col-md-12">
          <div class="cart-section bg-white rounded-lg shadow-sm p-30 mb-30">
            <h4 class="font-alt mb-25">
              <i class="fa fa-list mr-10"></i>Sản phẩm trong giỏ hàng
              <span class="badge badge-primary ml-10">{{ $cartItems->count() }}</span>
            </h4>
            
            <!-- Desktop Table View -->
            <div class="cart-table-wrapper hidden-xs hidden-sm">
              <table class="table cart-table">
                <thead>
                  <tr>
                    <th width="50" class="text-center">
                      <!-- Ẩn tích chọn tất cả để tránh lỗi -->
                      <!-- <input type="checkbox" id="select-all-items" class="select-all-checkbox"> -->
                    </th>
                    <th width="120">Hình ảnh</th>
                    <th>Sản phẩm</th>
                    <th width="120" class="text-center">Giá</th>
                    <th width="120" class="text-center">Số lượng</th>
                    <th width="80" class="text-center">Xóa</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cartItems as $item)
                  <tr class="cart-item" data-cart-id="{{ Auth::check() ? $item->id : $item->id }}">
                    <td class="text-center">
                      <input type="checkbox" class="item-checkbox" 
                             data-cart-id="{{ $item->id }}" 
                             data-price="{{ $item->price }}" 
                             data-quantity="{{ $item->quantity }}"
                             data-item-total="{{ $item->price * $item->quantity }}">
                    </td>
                    <td>
                      <div class="product-image-wrapper">
                        <a href="{{ route('client.single-product', $item->product->id) }}">
                          <img src="{{ \App\Helpers\ProductHelper::getProductImage($item->product) }}" 
                               alt="{{ $item->product->name }}" 
                               class="product-thumbnail"/>
                        </a>
                      </div>
                    </td>
                    <td>
                      <div class="product-info">
                        <h5 class="product-name">
                          <a href="{{ route('client.single-product', $item->product->id) }}">
                            {{ $item->product->name }}
                          </a>
                        </h5>
                        @if($item->variant)
                        <div class="product-variants">
                          @if($item->variant->storage && isset($item->variant->storage->capacity))
                          <span class="variant-item">
                            <i class="fa fa-hdd-o mr-5"></i>{{ \App\Helpers\StorageHelper::formatCapacity($item->variant->storage->capacity) }}
                          </span>
                          @endif
                          @if($item->variant->color)
                          <span class="variant-item ml-15">
                            @php
                              $colorCode = $item->variant->color->color_code ?? '#cccccc';
                              $colorName = $item->variant->color->name ?? 'Không xác định';
                              // Kiểm tra màu sáng/tối để điều chỉnh border
                              $isLightColor = false;
                              $isDarkColor = false;
                              if (preg_match('/^#[0-9A-F]{6}$/i', $colorCode)) {
                                  $hex = str_replace('#', '', $colorCode);
                                  $r = hexdec(substr($hex, 0, 2));
                                  $g = hexdec(substr($hex, 2, 2));
                                  $b = hexdec(substr($hex, 4, 2));
                                  $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                  $isLightColor = $brightness > 240;
                                  $isDarkColor = $brightness < 30;
                              }
                            @endphp
                            <span class="color-preview enhanced-color-preview" 
                                  style="background-color: {{ $colorCode }};"
                                  data-color="{{ $colorCode }}"
                                  data-brightness="{{ isset($brightness) ? $brightness : 128 }}"
                                  data-is-light="{{ $isLightColor ? 'true' : 'false' }}"
                                  data-is-dark="{{ $isDarkColor ? 'true' : 'false' }}"
                                  title="Màu: {{ $colorName }}"></span>
                            {{ $colorName }}
                          </span>
                          @endif
                        </div>
                        @else
                        <div class="product-variants">
                          <span class="variant-item">
                            <i class="fa fa-tag mr-5"></i>Sản phẩm chuẩn
                          </span>
                        </div>
                        @endif
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="price">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                    </td>
                    <td class="text-center">
                      <div class="quantity-controls">
                        <button type="button" class="btn-quantity-minus">
                          <i class="fa fa-minus"></i>
                        </button>
                        @php
                            $stockQuantity = $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity;
                        @endphp
                        <input class="quantity-input" type="number" 
                               value="{{ $item->quantity }}" 
                               max="{{ $stockQuantity }}" min="1"
                               data-cart-id="{{ Auth::check() ? $item->id : $item->id }}"
                               data-stock="{{ $stockQuantity }}"
                               data-variant-id="{{ $item->variant ? $item->variant->id : null }}"/>
                        <button type="button" class="btn-quantity-plus" 
                                {{ $item->quantity >= $stockQuantity ? 'disabled' : '' }}>
                          <i class="fa fa-plus"></i>
                        </button>
                      </div>
                      @if($stockQuantity <= 10)
                      <small class="stock-warning text-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        Chỉ còn {{ $stockQuantity }} sản phẩm
                      </small>
                      @endif
                    </td>
                    <td class="text-center">
                      <button class="btn-remove remove-item" 
                              data-cart-id="{{ Auth::check() ? $item->id : $item->id }}" 
                              title="Xóa sản phẩm">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- Mobile Card View -->
            <div class="cart-mobile-view visible-xs visible-sm">
              <!-- Mobile Select All - Ẩn để tránh lỗi -->
              <!-- <div class="mobile-select-all mb-3">
                <div class="row">
                  <div class="col-xs-2 text-center">
                    <input type="checkbox" id="mobile-select-all" class="select-all-checkbox">
                  </div>
                  <div class="col-xs-10">
                    <label for="mobile-select-all" class="mobile-select-all-label">
                      Chọn tất cả sản phẩm
                    </label>
                  </div>
                </div>
              </div> -->
              
              @foreach($cartItems as $item)
              <div class="cart-item-card" data-cart-id="{{ Auth::check() ? $item->id : $item->id }}">
                <div class="row">
                  <div class="col-xs-2 text-center">
                    <div class="mobile-checkbox-wrapper">
                      <input type="checkbox" class="item-checkbox" 
                             data-cart-id="{{ $item->id }}" 
                             data-price="{{ $item->price }}" 
                             data-quantity="{{ $item->quantity }}"
                             data-item-total="{{ $item->price * $item->quantity }}">
                    </div>
                  </div>
                  <div class="col-xs-2">
                    <div class="product-image-wrapper">
                      <a href="{{ route('client.single-product', $item->product->id) }}">
                        <img src="{{ \App\Helpers\ProductHelper::getProductImage($item->product) }}" 
                             alt="{{ $item->product->name }}" 
                             class="product-thumbnail"/>
                      </a>
                    </div>
                  </div>
                  <div class="col-xs-8">
                    <div class="product-info">
                      <h5 class="product-name">
                        <a href="{{ route('client.single-product', $item->product->id) }}">
                          {{ $item->product->name }}
                        </a>
                      </h5>
                      @if($item->variant)
                      <div class="product-variants">
                        <small>
                          @if($item->variant->storage && isset($item->variant->storage->capacity))
                            {{ \App\Helpers\StorageHelper::formatCapacity($item->variant->storage->capacity) }}
                            @if($item->variant->color && isset($item->variant->color->name) && $item->variant->color->name) • @endif
                          @endif
                          @if($item->variant->color)
                          @php
                            $mobileColorCode = $item->variant->color->color_code ?? '#cccccc';
                            $mobileColorName = $item->variant->color->name ?? 'Không xác định';
                            $mobileBrightness = 128;
                            $mobileIsLightColor = false;
                            $mobileIsDarkColor = false;
                            if (preg_match('/^#[0-9A-F]{6}$/i', $mobileColorCode)) {
                                $hex = str_replace('#', '', $mobileColorCode);
                                $r = hexdec(substr($hex, 0, 2));
                                $g = hexdec(substr($hex, 2, 2));
                                $b = hexdec(substr($hex, 4, 2));
                                $mobileBrightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
                                $mobileIsLightColor = $mobileBrightness > 240;
                                $mobileIsDarkColor = $mobileBrightness < 30;
                            }
                          @endphp
                          <span class="color-preview enhanced-color-preview mobile-color-preview" 
                                style="background-color: {{ $mobileColorCode }};"
                                data-color="{{ $mobileColorCode }}"
                                data-brightness="{{ $mobileBrightness }}"
                                data-is-light="{{ $mobileIsLightColor ? 'true' : 'false' }}"
                                data-is-dark="{{ $mobileIsDarkColor ? 'true' : 'false' }}"
                                title="Màu: {{ $mobileColorName }}"></span>
                          {{ $mobileColorName }}
                          @endif
                        </small>
                      </div>
                      @else
                      <div class="product-variants">
                        <small>
                          <i class="fa fa-tag mr-5"></i>Sản phẩm chuẩn
                        </small>
                      </div>
                      @endif
                      <div class="price-row mt-10">
                        <span class="price">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-15">
                  <div class="col-xs-8">
                    <div class="quantity-controls-mobile">
                      <button type="button" class="btn-quantity-minus">
                        <i class="fa fa-minus"></i>
                      </button>
                      @php
                        $mobileStockQuantity = $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity;
                      @endphp
                      <input class="quantity-input" type="number" 
                             value="{{ $item->quantity }}" 
                             max="{{ $mobileStockQuantity }}" min="1"
                             data-cart-id="{{ Auth::check() ? $item->id : $item->id }}"
                             data-stock="{{ $mobileStockQuantity }}"
                             data-variant-id="{{ $item->variant ? $item->variant->id : null }}"/>
                      <button type="button" class="btn-quantity-plus"
                              {{ $item->quantity >= $mobileStockQuantity ? 'disabled' : '' }}>
                        <i class="fa fa-plus"></i>
                      </button>
                    </div>
                    @if($mobileStockQuantity <= 10)
                    <small class="stock-warning text-warning mt-5">
                      <i class="fa fa-exclamation-triangle"></i>
                      Còn {{ $mobileStockQuantity }} sản phẩm
                    </small>
                    @endif
                  </div>
                  <div class="col-xs-4 text-right">
                    <div class="item-actions">
                      <button class="btn-remove remove-item" 
                              data-cart-id="{{ Auth::check() ? $item->id : $item->id }}" 
                              title="Xóa">
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
            
            <!-- Cart Actions -->
            <div class="cart-actions mt-30 pt-20 border-top">
              <div class="row">
                <div class="col-sm-12 text-center mb-20">
                  <small class="text-muted">
                    <i class="fa fa-info-circle mr-5"></i>
                    Giỏ hàng sẽ tự động cập nhật khi bạn thay đổi số lượng sản phẩm
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Order Summary Section -->
        <div class="col-lg-4 col-md-12">
          <div class="order-summary bg-white rounded-lg shadow-sm p-30 sticky-summary">
            <h4 class="font-alt mb-25">
              <i class="fa fa-calculator mr-10"></i>Tóm tắt đơn hàng
            </h4>
            
            <div class="summary-details">
              <div id="selection-notice" class="text-center mb-3" style="display: none;">
                <small class="text-muted">
                  <i class="fa fa-info-circle"></i> Chỉ tính cho sản phẩm được chọn
                </small>
              </div>
              <div class="summary-row">
                <span>Tạm tính:</span>
                <span id="subtotal" class="font-weight-600">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
              </div>
              <div class="summary-row">
                <span>Phí vận chuyển:</span>
                <span id="shipping" class="font-weight-600">{{ number_format($shipping, 0, ',', '.') }}đ</span>
              </div>
              <hr class="summary-divider">
              <div class="summary-row total-row">
                <span class="total-label">Tổng cộng:</span>
                <span id="total" class="total-amount">{{ number_format($total, 0, ',', '.') }}đ</span>
              </div>
            </div>
            
            <div class="checkout-actions">
              @if($isHighQuantityCart)
                <button id="checkout-selected-btn" class="checkout-btn" disabled style="background-color: #6c757d; border-color: #6c757d; cursor: not-allowed; opacity: 0.6;">
                  <i class="fa fa-phone"></i>LIÊN HỆ TƯ VẤN
                </button>
                <small class="text-muted d-block mt-2">
                  <i class="fa fa-exclamation-triangle"></i> 
                  Vui lòng liên hệ tư vấn cho đơn hàng có số lượng cao
                </small>
              @else
                <button id="checkout-selected-btn" class="checkout-btn" disabled>
                  <i class="fa fa-credit-card"></i>TIẾN HÀNH THANH TOÁN (<span id="selected-count">0</span> sản phẩm đã chọn)
                </button>
              @endif
              <a href="{{ route('client.product') }}" class="btn-outline-secondary">
                <i class="fa fa-arrow-left"></i>← TIẾP TỤC MUA SẮM
              </a>
            </div>
            
            <!-- Trust Badges -->
            <div class="trust-badges mt-30 pt-20 border-top">
              <div class="text-center">
                <small class="text-muted">
                  <i class="fa fa-shield mr-5"></i>Thanh toán an toàn & bảo mật
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      @else
      <!-- Empty Cart State -->
      <div class="card">
        <div class="empty-cart">
          <div class="empty-cart-icon">
            <i class="fa fa-shopping-bag"></i>
          </div>
          <h3>Giỏ hàng của bạn đang trống</h3>
          <p>
            Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và thêm chúng vào giỏ hàng.
          </p>
          <a href="{{ route('client.product') }}" class="btn">
            <i class="fa fa-lock"></i>KHÁM PHÁ SẢN PHẨM
          </a>
        </div>
      </div>
      @endif
    </div>
</section>

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
  <div class="loading-spinner">
    <i class="fa fa-spinner fa-spin"></i>
    <p>Đang xử lý...</p>
  </div>
</div>

<!-- Toast Container -->
<div id="toast-container"></div>

<!-- Custom Styles -->
<style>
/* Cart Styling */
.cart-section {
  border: 1px solid #e9ecef;
  transition: all 0.3s ease;
}

.cart-table {
  margin-bottom: 0;
}

.cart-table th {
  background: #f8f9fa;
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  color: #495057;
  padding: 15px 10px;
}

.cart-table td {
  padding: 20px 10px;
  vertical-align: middle;
  border-bottom: 1px solid #f8f9fa;
}

.cart-item:hover {
  background: #f8f9fa;
}

.product-thumbnail {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  transition: transform 0.3s ease;
}

.product-thumbnail:hover {
  transform: scale(1.05);
}

.product-name {
  font-weight: 600;
  margin-bottom: 8px;
}

.product-name a {
  color: #333;
  text-decoration: none;
}

.product-name a:hover {
  color: #007bff;
}

.product-variants {
  font-size: 13px;
  color: #6c757d;
}

.variant-item {
  display: inline-block;
}

.price {
  font-size: 16px;
  font-weight: 600;
  color: #dc3545;
}

/* Quantity Controls */
.quantity-controls {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
}

.quantity-controls-mobile {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 0;
}

.btn-quantity-minus,
.btn-quantity-plus {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-quantity-minus:hover,
.btn-quantity-plus:hover {
  background: #e9ecef;
  color: #007bff;
}

.btn-quantity-minus:disabled,
.btn-quantity-plus:disabled,
.btn-quantity-minus.disabled,
.btn-quantity-plus.disabled {
  background: #f8f9fa;
  color: #6c757d;
  cursor: not-allowed;
  opacity: 0.6;
}

.btn-quantity-minus:disabled:hover,
.btn-quantity-plus:disabled:hover,
.btn-quantity-minus.disabled:hover,
.btn-quantity-plus.disabled:hover {
  background: #f8f9fa;
  color: #6c757d;
  transform: none;
}

.quantity-input {
  width: 60px;
  height: 35px;
  text-align: center;
  border: 1px solid #dee2e6;
  border-left: none;
  border-right: none;
  background: white;
}

.quantity-input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.btn-remove {
  background: #fff5f5;
  border: 1px solid #feb2b2;
  color: #dc3545;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-remove:hover {
  background: #dc3545;
  color: white;
  transform: scale(1.1);
}

    /* Mobile Cart Cards */
    .cart-item-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .cart-item-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
    }
    
    /* Mobile product info improvements */
    .cart-item-card .product-name {
        font-size: 1rem !important;
        margin-bottom: 0.75rem !important;
    }
    
    .cart-item-card .product-variants {
        font-size: 0.85rem !important;
        margin-bottom: 0.75rem !important;
    }
    
    .cart-item-card .price {
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: #dc3545 !important;
    }

/* Order Summary */
.order-summary {
  border: 1px solid #e9ecef;
}

.sticky-summary {
  position: sticky;
  top: 20px;
}

.summary-details {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 20px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
}

.summary-divider {
  margin: 15px 0;
  border-color: #dee2e6;
}

.total-row {
  font-size: 18px;
  font-weight: 700;
}

.total-amount {
  color: #dc3545;
  font-size: 20px;
}

.checkout-btn {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  border: none;
  font-weight: 600;
  padding: 15px;
  transition: all 0.3s ease;
}

.checkout-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,123,255,0.3);
}

/* Loading Overlay */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.7);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 999999; /* Increased z-index to be above navbar and toast */
  backdrop-filter: blur(3px);
}

.loading-spinner {
  text-align: center;
  color: white;
  background: rgba(0,0,0,0.8);
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.loading-spinner i {
  font-size: 48px;
  margin-bottom: 15px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Toast Notifications */
#toast-container {
  position: fixed;
  top: 80px; /* Increased from 20px to avoid navbar */
  right: 20px;
  z-index: 99999; /* Increased z-index to ensure it's above navbar */
  max-width: 400px;
  pointer-events: none; /* Allow clicks to pass through container */
}

#toast-container .toast-notification {
  pointer-events: auto; /* Re-enable clicks on actual notifications */
}

/* Animations */
@keyframes bounceIn {
  0% { transform: scale(0.3); opacity: 0; }
  50% { transform: scale(1.05); }
  70% { transform: scale(0.9); }
  100% { transform: scale(1); opacity: 1; }
}

.animate-in {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translate3d(0, 30px, 0);
  }
  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

/* Toast Notification Animations */
@keyframes shimmer {
  0% { 
    transform: translateX(-100%) rotate(25deg); 
    opacity: 0.3;
  }
  50% { 
    opacity: 0.6;
  }
  100% { 
    transform: translateX(300%) rotate(25deg); 
    opacity: 0.3;
  }
}

@keyframes pulse {
  0%, 100% { 
    transform: scale(1);
    opacity: 0.8;
  }
  50% { 
    transform: scale(1.1);
    opacity: 1;
  }
}

@keyframes progressBar {
  0% { 
    width: 100%;
    opacity: 0.8;
  }
  100% { 
    width: 0%;
    opacity: 0.3;
  }
}

@keyframes slideInRight {
  0% {
    transform: translateX(100%);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOutRight {
  0% {
    transform: translateX(0);
    opacity: 1;
  }
  100% {
    transform: translateX(100%);
    opacity: 0;
  }
}

@keyframes iconPop {
  0% { 
    transform: scale(0.8);
  }
  50% { 
    transform: scale(1.2);
  }
  100% { 
    transform: scale(1);
  }
}

/* Responsive */
@media (max-width: 767px) {
  .module {
    padding: 40px 0;
  }
  
  .order-summary {
    margin-top: 30px;
  }
  
  .sticky-summary {
    position: static;
  }
  
  .quantity-controls-mobile .quantity-input {
    width: 50px;
  }
  
  .btn-quantity-minus,
  .btn-quantity-plus {
    width: 30px;
    height: 30px;
  }
  
  .product-thumbnail {
    width: 100%;
    height: 100px;
  }
  
  /* Toast responsive styles */
  #toast-container {
    top: 60px; /* Reduce top margin on mobile */
    right: 10px;
    left: 10px; /* Add left margin for mobile */
    max-width: none; /* Remove max-width constraint */
  }
  
  #toast-container .toast-notification {
    min-width: auto !important;
    max-width: none !important;
    width: 100% !important;
    margin-bottom: 10px;
    padding: 20px;
    font-size: 14px;
  }
  
  #toast-container .toast-notification .toast-icon i {
    font-size: 24px !important;
  }
  
  #toast-container .toast-notification .toast-close {
    width: 30px !important;
    height: 30px !important;
    font-size: 24px !important;
  }
}

/* Utility Classes */
.rounded-lg {
  border-radius: 12px !important;
}

.shadow-sm {
  box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
}

.font-weight-600 {
  font-weight: 600 !important;
}

.border-top {
  border-top: 1px solid #e9ecef !important;
}

.text-muted {
  color: #6c757d !important;
}

.badge {
  display: inline-block;
  padding: 0.25em 0.6em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.375rem;
}

.badge-primary {
  color: #fff;
  background-color: #007bff;
}

/* Stock warnings */
.stock-warning {
  display: block;
  margin-top: 5px;
  font-size: 11px;
  font-weight: 500;
}

.text-warning {
  color: #ffc107 !important;
}

/* Enhanced Color Preview */
.enhanced-color-preview {
  display: inline-block;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  margin-right: 8px;
  vertical-align: middle;
  position: relative;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid #ffffff;
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.1),
    0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Checkbox Styling */
.item-checkbox, .select-all-checkbox {
  width: 20px;
  height: 20px;
  cursor: pointer;
  accent-color: #007bff;
  transform: scale(1.2);
  transition: all 0.2s ease;
}

.item-checkbox:hover, .select-all-checkbox:hover {
  transform: scale(1.3);
}

.item-checkbox:checked, .select-all-checkbox:checked {
  transform: scale(1.2);
}

    /* Disabled checkout button */
    .checkout-btn:disabled {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
        cursor: not-allowed !important;
        opacity: 0.7 !important;
        transform: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        color: #e9ecef !important;
    }
    
    .checkout-btn:disabled:hover {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
        transform: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        color: #e9ecef !important;
    }
    
    .checkout-btn:disabled::before {
        display: none !important;
    }

/* Mobile checkbox wrapper */
.mobile-checkbox-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  padding: 10px 0;
}

/* Selection notice styling */
#selection-notice {
  background: #e3f2fd;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 15px;
  border-left: 4px solid #007bff;
}

/* Mobile select all styling */
.mobile-select-all {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  border: 1px solid #e9ecef;
}

.mobile-select-all-label {
  font-weight: 600;
  color: #495057;
  margin: 0;
  cursor: pointer;
  display: flex;
  align-items: center;
  height: 100%;
}

/* Mobile version - smaller size */
.mobile-color-preview {
  width: 16px;
  height: 16px;
  margin: 0 6px;
}

/* Base hover effect */
.enhanced-color-preview:hover {
  transform: scale(1.3);
  box-shadow: 
    0 0 0 2px #ffffff,
    0 0 0 4px rgba(0, 123, 255, 0.3),
    0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 10;
}

/* Light colors styling */
.enhanced-color-preview[data-is-light="true"] {
  border: 2px solid #e0e0e0;
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.25),
    0 2px 4px rgba(0, 0, 0, 0.1);
}

.enhanced-color-preview[data-is-light="true"]:hover {
  border-color: #cccccc;
  box-shadow: 
    0 0 0 2px #cccccc,
    0 0 0 4px rgba(0, 123, 255, 0.3),
    0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Dark colors styling */
.enhanced-color-preview[data-is-dark="true"] {
  border: 2px solid #555555;
  box-shadow: 
    0 0 0 1px rgba(255, 255, 255, 0.3),
    0 2px 4px rgba(0, 0, 0, 0.3);
}

.enhanced-color-preview[data-is-dark="true"]:hover {
  border-color: #777777;
  box-shadow: 
    0 0 0 2px #777777,
    0 0 0 4px rgba(0, 123, 255, 0.4),
    0 4px 12px rgba(0, 0, 0, 0.25);
}

/* Special handling for pure white */
.enhanced-color-preview[data-color="#FFFFFF"],
.enhanced-color-preview[data-color="#ffffff"],
.enhanced-color-preview[data-color="#FFF"],
.enhanced-color-preview[data-color="#fff"] {
  border: 2px solid #ddd;
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.15),
    0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Special handling for pure black */
.enhanced-color-preview[data-color="#000000"],
.enhanced-color-preview[data-color="#000"] {
  border: 2px solid #444;
  background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%) !important;
  box-shadow: 
    0 0 0 1px rgba(255, 255, 255, 0.2),
    0 2px 6px rgba(0, 0, 0, 0.4);
}

/* Gray colors enhancement */
.enhanced-color-preview[data-color*="#808080"],
.enhanced-color-preview[data-color*="#696969"],
.enhanced-color-preview[data-color*="#D3D3D3"],
.enhanced-color-preview[data-color*="#C0C0C0"] {
  box-shadow: 
    0 0 0 1px rgba(0, 0, 0, 0.2),
    0 2px 4px rgba(0, 0, 0, 0.15);
}

/* Add a subtle inner highlight for better visibility */
.enhanced-color-preview::before {
  content: '';
  position: absolute;
  top: 2px;
  left: 2px;
  right: 2px;
  bottom: 2px;
  border-radius: 50%;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 50%);
  pointer-events: none;
  opacity: 0.6;
}

/* Hide highlight for very light colors */
.enhanced-color-preview[data-is-light="true"]::before {
  opacity: 0.2;
}

/* Enhanced highlight for dark colors */
.enhanced-color-preview[data-is-dark="true"]::before {
  opacity: 0.8;
}

/* Animation for color preview loading */
@keyframes colorPreviewPulse {
  0%, 100% { 
    opacity: 1;
    transform: scale(1);
  }
  50% { 
    opacity: 0.8;
    transform: scale(1.05);
  }
}

/* Loading state */
.enhanced-color-preview.loading {
  animation: colorPreviewPulse 1.5s ease-in-out infinite;
  background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%), 
              linear-gradient(-45deg, #f0f0f0 25%, transparent 25%), 
              linear-gradient(45deg, transparent 75%, #f0f0f0 75%), 
              linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
  background-size: 4px 4px;
  background-position: 0 0, 0 2px, 2px -2px, -2px 0px;
}
</style>


<script>
// Ensure jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded!');
    // Wait for jQuery to be available
    function waitForJQuery() {
        if (typeof jQuery !== 'undefined') {
            initCartScript();
        } else {
            setTimeout(waitForJQuery, 100);
        }
    }
    waitForJQuery();
} else {
    initCartScript();
}

// Alternative approach - also try to run when window loads
window.addEventListener('load', function() {
    // console.log removed
    if (typeof jQuery !== 'undefined') {
        // console.log removed
        if (typeof initCartScript === 'function') {
            // Script already initialized, just ensure everything is updated
            if (typeof updateSelectedItems === 'function') {
                // console.log removed
                updateSelectedItems();
            }
        }
    } else {
        console.error('jQuery still not available on window load');
    }
});

function initCartScript() {
$(document).ready(function() {
    // console.log removed
    
    // Debug flag - set to true to enable debug logs in console
    const DEBUG_ENABLED = true;
    
    function debugLog(message) {
        if (DEBUG_ENABLED) {
            // console.log removed
        }
    }
    
    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Animate success banner if exists
    @if(session('cart_success'))
    $('.alert-success').hide().fadeIn(800).delay(8000).fadeOut(1000, function() {
        $(this).remove();
    });
    
    // Add hover effect to close button
    $('.alert-success .close').hover(
        function() {
            $(this).css({
                'opacity': '1',
                'transform': 'scale(1.1)',
                'background': 'rgba(255,255,255,0.2)',
                'border-radius': '50%'
            });
        },
        function() {
            $(this).css({
                'opacity': '0.7',
                'transform': 'scale(1)',
                'background': 'none'
            });
        }
    );
    @endif

    // Helper function để format currency  
    function formatCurrency(amount) {
        // Không làm tròn, giữ nguyên giá trị chính xác
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }

    // Helper function để parse currency về number
    function parseCurrency(currencyString) {
        const cleaned = currencyString.replace(/[^\d]/g, '');
        const result = parseFloat(cleaned) || 0;
        return result;
    }
    
    // Debug function để kiểm tra tính toán
    function debugCalculation(items) {
        debugLog('=== DEBUG CALCULATION ===');
        let total = 0;
        items.forEach((item, index) => {
            debugLog(`Item ${index + 1}: ${item.price} x ${item.quantity} = ${item.itemTotal}`);
            total += item.itemTotal;
        });
        debugLog(`Total calculated: ${total}`);
        debugLog('========================');
        return total;
    }

    // Hiển thị toast notification
    function showToast(message, type = 'success') {
        const config = {
            success: {
                bgColor: 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)',
                borderColor: '#28a745',
                textColor: '#155724',
                icon: 'fa-check-circle',
                iconColor: '#28a745',
                title: 'Thành công!'
            },
            error: {
                bgColor: 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)',
                borderColor: '#dc3545',
                textColor: '#721c24',
                icon: 'fa-exclamation-triangle',
                iconColor: '#dc3545',
                title: 'Lỗi!'
            },
            info: {
                bgColor: 'linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%)',
                borderColor: '#17a2b8',
                textColor: '#0c5460',
                icon: 'fa-info-circle',
                iconColor: '#17a2b8',
                title: 'Thông báo!'
            }
        };
        
        const settings = config[type] || config.info;
        
        const toast = $(`
            <div class="toast-notification" 
                 style="margin-bottom: 15px; padding: 25px; border-radius: 16px; 
                        background: ${settings.bgColor}; 
                        border: 2px solid ${settings.borderColor}; 
                        color: ${settings.textColor};
                        box-shadow: 0 12px 35px rgba(0,0,0,0.15); 
                        min-width: 380px; max-width: 420px; font-size: 15px;
                        position: relative; overflow: hidden;
                        backdrop-filter: blur(10px);
                        transform: translateX(100%);
                        opacity: 0;">
                <!-- Animated pattern overlay -->
                <div class="toast-pattern" style="position: absolute; top: -50%; right: -20px; width: 80px; height: 200%; 
                     background: rgba(255,255,255,0.1); transform: rotate(25deg); animation: shimmer 2s ease-in-out infinite;"></div>
                     
                <div style="display: flex; align-items: flex-start; position: relative; z-index: 2;">
                    <div class="toast-icon" style="margin-right: 15px; margin-top: 2px;">
                        <i class="fa ${settings.icon}" 
                           style="font-size: 28px; color: ${settings.iconColor}; 
                                  animation: pulse 2s ease-in-out infinite;"></i>
                    </div>
                    <div style="flex: 1; line-height: 1.4;">
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 5px;">
                            ${settings.title}
                        </div>
                        <div style="font-size: 14px; opacity: 0.9;">
                            ${message}
                        </div>
                    </div>
                    <button type="button" class="toast-close" 
                            style="background: none; border: none; font-size: 28px; cursor: pointer; 
                                   color: ${settings.textColor}; margin-left: 15px; opacity: 0.7;
                                   transition: all 0.2s ease; border-radius: 50%; width: 35px; height: 35px;
                                   display: flex; align-items: center; justify-content: center;">
                        <span>&times;</span>
                    </button>
                </div>
                
                <!-- Progress bar -->
                <div class="toast-progress" style="position: absolute; bottom: 0; left: 0; height: 4px; 
                     background: ${settings.borderColor}; width: 100%; opacity: 0.7;
                     animation: progressBar 5s linear forwards;"></div>
            </div>
        `);
        
        // Close button hover effect
        toast.find('.toast-close').hover(
            function() {
                $(this).css({
                    'background': 'rgba(0,0,0,0.1)',
                    'opacity': '1',
                    'transform': 'scale(1.1)'
                });
            },
            function() {
                $(this).css({
                    'background': 'none',
                    'opacity': '0.7',
                    'transform': 'scale(1)'
                });
            }
        );
        
        // Close button click handler
        toast.find('.toast-close').on('click', function() {
            $(this).closest('.toast-notification').css({
                'transform': 'translateX(100%)',
                'opacity': '0'
            }).delay(300).queue(function() {
                $(this).remove();
            });
        });
        
        // Add to container and animate in
        $('#toast-container').append(toast);
        
        // Slide in animation
        setTimeout(() => {
            toast.css({
                'transform': 'translateX(0)',
                'opacity': '1',
                'transition': 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)'
            });
        }, 50);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.is(':visible')) {
                toast.css({
                    'transform': 'translateX(100%)',
                    'opacity': '0'
                }).delay(300).queue(function() {
                    $(this).remove();
                });
            }
        }, 5000);
    }

    // Show loading overlay
    function showLoading() {
        $('#loading-overlay').fadeIn(200);
    }

    // Hide loading overlay
    function hideLoading() {
        $('#loading-overlay').fadeOut(200);
    }

    // Quantity controls
    $(document).on('click', '.btn-quantity-minus', function() {
        const $input = $(this).siblings('.quantity-input');
        const currentValue = parseInt($input.val());
        const stock = parseInt($input.data('stock')) || 0;
        if (stock === 0) {
            handleOutOfStock($input);
            return;
        }
        if (currentValue > 1) {
            $input.val(currentValue - 1).trigger('change');
            // Enable plus button when quantity decreases
            updateQuantityButtons($input);
        }
    });

    $(document).on('click', '.btn-quantity-plus', function() {
        const $button = $(this);
        const $input = $button.siblings('.quantity-input');
        const currentValue = parseInt($input.val());
        const stock = parseInt($input.data('stock')) || 0;
        
        debugLog(`Plus clicked: current=${currentValue}, stock=${stock}`);
        
        if (stock === 0) {
            handleOutOfStock($input);
            return;
        }
        // Kiểm tra stock trước khi tăng
        if (currentValue >= stock) {
            showToast(`Không thể tăng thêm! Chỉ còn ${stock} sản phẩm trong kho.`, 'error');
            return;
        }
        
        if (currentValue < stock) {
            $input.val(currentValue + 1).trigger('change');
            updateQuantityButtons($input);
        }
    });

    // Function to update quantity button states
    function updateQuantityButtons($input) {
        const currentValue = parseInt($input.val());
        const stock = parseInt($input.data('stock')) || 0;
        const $plusBtn = $input.siblings('.btn-quantity-plus');
        const $minusBtn = $input.siblings('.btn-quantity-minus');
        
        debugLog(`Updating buttons: current=${currentValue}, stock=${stock}`);
        
        if (stock === 0) {
            $input.prop('disabled', true);
            $plusBtn.prop('disabled', true).addClass('disabled');
            $minusBtn.prop('disabled', true).addClass('disabled');
            return;
        }
        // Update plus button
        if (currentValue >= stock) {
            $plusBtn.prop('disabled', true).addClass('disabled');
        } else {
            $plusBtn.prop('disabled', false).removeClass('disabled');
        }
        // Update minus button
        if (currentValue <= 1) {
            $minusBtn.prop('disabled', true).addClass('disabled');
        } else {
            $minusBtn.prop('disabled', false).removeClass('disabled');
        }
    }

    // Hàm xử lý khi hết hàng
    function handleOutOfStock($input) {
        $input.val(0);
        $input.prop('disabled', true);
        $input.siblings('.btn-quantity-plus').prop('disabled', true).addClass('disabled');
        $input.siblings('.btn-quantity-minus').prop('disabled', true).addClass('disabled');
        showToast('Sản phẩm đã hết hàng, không thể mua thêm!', 'error');
    }

    // Kiểm tra số lượng khi input thay đổi
    $(document).on('input', '.quantity-input', function() {
        const $input = $(this);
        const quantity = parseInt($input.val()) || 0;
        
        if (quantity > 100) {
            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
        }
        
        // Cập nhật ngay lập tức data attributes của checkbox khi người dùng nhập
        const $row = $input.closest('tr, .cart-item-card');
        const $checkbox = $row.find('.item-checkbox');
        if ($checkbox.length > 0 && quantity > 0) {
            const price = parseFloat($checkbox.data('price')) || 0;
            const itemTotal = price * quantity;
            $checkbox.data('quantity', quantity);
            $checkbox.data('item-total', itemTotal);
            
            debugLog(`Input changed: Item ${$checkbox.data('cart-id')} - Quantity: ${quantity}, Total: ${itemTotal}`);
            
            // Cập nhật tổng tiền ngay lập tức nếu checkbox đang được chọn
            if ($checkbox.is(':checked')) {
                updateSelectedItems();
            }
        }
    });

    // Cập nhật số lượng khi input thay đổi
    $(document).on('change', '.quantity-input', function() {
        const $input = $(this);
        const cartId = $input.data('cart-id');
        const quantity = parseInt($input.val());
        const stock = parseInt($input.data('stock')) || 0;
        
        if (stock === 0) {
            handleOutOfStock($input);
            return;
        }
        if (quantity < 1) {
            $input.val(1);
            updateQuantityButtons($input);
            return;
        }
        if (quantity > stock) {
            showToast(`Không thể đặt số lượng ${quantity}! Chỉ còn ${stock} sản phẩm trong kho.`, 'error');
            $input.val(stock);
            updateQuantityButtons($input);
            return;
        }
        if (quantity > 100) {
            showToast('Do số lượng đơn hàng quá lớn, vui lòng liên hệ hỗ trợ để được tư vấn', 'warning', 'Giới hạn số lượng');
            $input.val(100);
            updateQuantityButtons($input);
            return;
        }
        
        // Cập nhật data attributes của checkbox ngay lập tức
        const $row = $input.closest('tr, .cart-item-card');
        const $checkbox = $row.find('.item-checkbox');
        if ($checkbox.length > 0) {
            const price = parseFloat($checkbox.data('price')) || 0;
            const itemTotal = price * quantity;
            $checkbox.data('quantity', quantity);
            $checkbox.data('item-total', itemTotal);
            debugLog(`Quantity changed: Updated checkbox data - Price: ${price}, Quantity: ${quantity}, Total: ${itemTotal}`);
        }
        
        updateCartItem(cartId, quantity, $row, $input);
    });

    // Xóa sản phẩm khỏi giỏ hàng
    $(document).on('click', '.remove-item', function(e) {
        e.preventDefault();
        const cartId = $(this).data('cart-id');
        const $row = $(this).closest('tr, .cart-item-card');
        
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            removeCartItem(cartId, $row);
        }
    });

    // Cập nhật một item trong giỏ hàng
    function updateCartItem(cartId, quantity, $row, $input) {
        showLoading();
        
        $.ajax({
            url: '{{ route("client.update-cart") }}',
            method: 'POST',
            data: {
                cart_detail_id: cartId,
                quantity: quantity
            },
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    debugLog('Cart update successful, updating UI...');
                    
                    // Cập nhật checkbox data attributes
                    const $checkbox = $row.find('.item-checkbox');
                    if ($checkbox.length > 0) {
                        const price = parseFloat($checkbox.data('price')) || 0;
                        const itemTotal = price * quantity;
                        $checkbox.data('quantity', quantity);
                        $checkbox.data('item-total', itemTotal);
                        debugLog(`Cart updated: Item ${$checkbox.data('cart-id')} - Quantity: ${quantity}, Total: ${itemTotal}`);
                    }
                    
                    // Cập nhật button states
                    if ($input) {
                        updateQuantityButtons($input);
                    }
                    
                    // Cập nhật tổng tiền đơn hàng dựa trên trạng thái chọn
                    updateSelectedItems();
                    
                    // Delay cart count update để đảm bảo database đã được cập nhật
                    setTimeout(function() {
                        debugLog('Updating navbar cart count after delay...');
                        updateNavbarCartCount();
                    }, 500);
                    
                    showToast(response.message);
                } else {
                    // Hiển thị thông báo lỗi thân thiện
                    if (response.toast_type && response.toast_title) {
                        showToast(response.message, response.toast_type, response.toast_title);
                    } else {
                        showToast(response.message, 'error');
                    }
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                
                // Handle stock validation errors from server
                if (xhr.status === 400 && xhr.responseJSON) {
                    const errorResponse = xhr.responseJSON;
                    if (errorResponse.current_stock === 0 && $input) {
                        handleOutOfStock($input);
                    }
                    // Hiển thị thông báo lỗi thân thiện
                    if (errorResponse.toast_type && errorResponse.toast_title) {
                        showToast(errorResponse.message, errorResponse.toast_type, errorResponse.toast_title);
                    } else {
                        showToast(errorResponse.message, 'error');
                    }
                    
                    // Reset quantity to max available if server provides it
                    if (errorResponse.max_quantity && $input) {
                        $input.val(errorResponse.max_quantity);
                        updateQuantityButtons($input);
                        
                        // Recalculate item total with corrected quantity
                        const $checkbox = $row.find('.item-checkbox');
                        if ($checkbox.length > 0) {
                            const price = parseInt($checkbox.data('price')) || 0;
                            const correctedTotal = price * errorResponse.max_quantity;
                            $checkbox.data('quantity', errorResponse.max_quantity);
                            $checkbox.data('item-total', correctedTotal);
                            debugLog(`Server corrected quantity: Item ${$checkbox.data('cart-id')} - Quantity: ${errorResponse.max_quantity}, Total: ${correctedTotal}`);
                        }
                        updateSelectedItems();
                    }
                } else {
                    showToast('Có lỗi xảy ra khi cập nhật giỏ hàng!', 'error');
                }
            }
        });
    }

    // Xóa item khỏi giỏ hàn
    function removeCartItem(cartId, $row) {
        showLoading();
        
        $.ajax({
            url: '{{ route("client.remove-from-cart") }}',
            method: 'POST',
            data: {
                cart_detail_id: cartId
            },
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    debugLog('Item removed successfully, starting fadeOut...');
                    
                    $row.fadeOut(400, function() {
                        debugLog('FadeOut complete, removing element...');
                        $(this).remove();
                        
                                // Cập nhật tổng tiền ngay sau khi remove element
        setTimeout(() => {
            updateSelectedItems(); // Cập nhật trạng thái chọn sản phẩm và tổng tiền
            // updateSelectAllCheckbox(); // Đã ẩn vì không còn tích chọn tất cả
            
            // Kiểm tra nếu giỏ hàng trống sau khi cập nhật
            const remainingItems = $('.cart-item:visible, .cart-item-card:visible');
            debugLog(`Remaining items after removal: ${remainingItems.length}`);
            
            if (remainingItems.length === 0) {
                debugLog('Cart is now empty, reloading page...');
                setTimeout(() => location.reload(), 1000);
            }
        }, 100);
                    });
                    
                    // Delay cart count update để đảm bảo database đã được cập nhật
                    setTimeout(function() {
                        debugLog('Updating navbar cart count after item removal...');
                        updateNavbarCartCount();
                    }, 500);
                    
                    showToast(response.message);
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function() {
                hideLoading();
                showToast('Có lỗi xảy ra khi xóa sản phẩm!', 'error');
            }
        });
    }

    // Cập nhật số lượng giỏ hàng trong navbar
    function updateNavbarCartCount() {
        debugLog('=== UPDATING NAVBAR CART COUNT ===');
        
        // Force refresh cart count from server
        $.ajax({
            url: '{{ route("client.cart-count") }}',
            method: 'GET',
            success: function(response) {
                debugLog('Cart count from server:', response.count);
                
                // Update navbar cart count if global function available
                if (window.updateCartCount) {
                    debugLog('Using global updateCartCount function');
                    window.updateCartCount(response.count);
                } else {
                    debugLog('Using fallback cart count update');
                    // Fallback to updating any cart count elements
                    $('.cart-count, .cart-counter, #cart-count, #cartCount').each(function() {
                        debugLog('Updating element:', this, 'new count:', response.count);
                        $(this).text(response.count);
                    });
                }
                
                debugLog('Cart count update completed');
            },
            error: function(xhr, status, error) {
                debugLog('Error updating cart count:', error);
            }
        });
    }

    // Cập nhật tổng tiền đơn hàng
    function updateOrderTotal() {
        // Check if we should calculate based on selected items
        const selectedItems = getSelectedItems();
        if (selectedItems.length > 0) {
            // Calculate based on selected items only
            updateOrderSummaryForSelectedItems(selectedItems);
            return;
        }
        
        // Nếu không có sản phẩm nào được chọn, tính tổng tất cả sản phẩm
        let subtotal = 0;
        let itemCount = 0;
        
        debugLog('=== DEBUG: Calculating Order Total (All Items) ===');
        
        // Kiểm tra xem còn sản phẩm nào trong giỏ hàng không
        const cartItems = $('.cart-item:visible, .cart-item-card:visible');
        debugLog(`Number of visible cart items: ${cartItems.length}`);
        
        if (cartItems.length === 0) {
            debugLog('Cart is empty, setting all totals to 0');
            subtotal = 0;
        } else {
            // Tính tổng từ data attributes của checkbox (tất cả sản phẩm)
            cartItems.each(function(index) {
                const $checkbox = $(this).find('.item-checkbox');
                if ($checkbox.length > 0) {
                    const itemTotal = parseInt($checkbox.data('item-total')) || 0;
                    debugLog(`Item ${index + 1}: itemTotal from data -> ${itemTotal}`);
                    subtotal += itemTotal;
                    itemCount++;
                }
            });
        }
        
        const shipping = subtotal > 0 ? 30000 : 0;
        const total = subtotal + shipping;
        
        debugLog(`Items counted: ${itemCount}, Subtotal: ${subtotal}, Shipping: ${shipping}, Total: ${total}`);
        debugLog('=====================================');
        
        // Cập nhật UI
        if (cartItems.length === 0) {
            resetTotalsToZero();
        } else {
            animateNumber($('#subtotal'), subtotal);
            animateNumber($('#shipping'), shipping);
            animateNumber($('#total'), total);
        }
    }

    // Reset totals to zero
    function resetTotalsToZero() {
        debugLog('Resetting all totals to zero...');
        $('#subtotal').text(formatCurrency(0));
        $('#shipping').text(formatCurrency(0));
        $('#total').text(formatCurrency(0));
        $('#selection-notice').hide();
    }

    // Animate number changes
    function animateNumber($element, targetValue) {
        // Stop any existing animations first
        $element.stop(true, true);
        
        const currentValue = parseCurrency($element.text());
        
        debugLog(`Animate: current=${currentValue}, target=${targetValue}`);
        
        // If values are the same, no need to animate
        if (currentValue === targetValue) {
            $element.text(formatCurrency(targetValue));
            return;
        }
        
        $({ value: currentValue }).animate({ value: targetValue }, {
            duration: 300,
            step: function() {
                $element.text(formatCurrency(this.value));
            },
            complete: function() {
                $element.text(formatCurrency(targetValue));
            }
        });
    }

    // Áp dụng mã giảm giá
    $('#apply-coupon').on('click', function() {
        const couponCode = $('#coupon').val().trim();
        const $button = $(this);
        const originalText = $button.html();
        
        if (couponCode === '') {
            showToast('Vui lòng nhập mã giảm giá!', 'error');
            return;
        }
        
        $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-5"></i>Đang kiểm tra...');
        
        // Simulate API call (replace with actual implementation)
        setTimeout(() => {
            $button.prop('disabled', false).html(originalText);
            showToast('Tính năng mã giảm giá sẽ được cập nhật sớm!', 'info');
        }, 1500);
    });

    // Initialize tooltips (if using Bootstrap)
    if (typeof $().tooltip === 'function') {
        $('[title]').tooltip();
    }

    // Test currency functions
    function testCurrencyFunctions() {
        if (!DEBUG_ENABLED) return;
        
        debugLog('=== TESTING CURRENCY FUNCTIONS ===');
        
        // Test round trip conversion
        const testValues = [1000000, 30000, 0];
        testValues.forEach(value => {
            const formatted = formatCurrency(value);
            const parsed = parseCurrency(formatted);
            debugLog(`Round trip: ${value} -> "${formatted}" -> ${parsed} | OK: ${value === parsed}`);
        });
        
        debugLog('===================================');
    }

    // Test cart count function
    function testCartCount() {
        debugLog('=== TESTING CART COUNT ===');
        updateNavbarCartCount();
    }
    
    // Make test function globally available
    window.testCartCount = testCartCount;
    
    // Initialize quantity button states
    function initializeQuantityButtons() {
        $('.quantity-input').each(function() {
            const $input = $(this);
            const stock = parseInt($input.data('stock')) || 0;
            if (stock === 0) {
                handleOutOfStock($input);
            } else {
                updateQuantityButtons($input);
            }
        });
    }

    // Function to enhance color preview with tooltips and accessibility
    function enhanceColorPreviews() {
        $('.enhanced-color-preview').each(function() {
            const $this = $(this);
            const colorCode = $this.data('color');
            const brightness = $this.data('brightness');
            const colorName = $this.attr('title');
            
            // Add click event to show color details
            $this.on('click', function(e) {
                e.preventDefault();
                const colorInfo = `Màu: ${colorName}\nMã màu: ${colorCode}\nĐộ sáng: ${Math.round(brightness)}`;
                showToast(colorInfo, 'info');
            });
            
            // Add keyboard accessibility
            $this.attr('tabindex', '0').attr('role', 'button');
            $this.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).click();
                }
            });
        });
    }

    // Initialize cart on page load
    setTimeout(function() {
        testCurrencyFunctions();
        initializeQuantityButtons();
        enhanceColorPreviews(); // Initialize enhanced color previews
        initializeItemSelection(); // Initialize item selection functionality
        
        // Khởi tạo và đồng bộ hóa tất cả checkbox data attributes
        initializeCheckboxData();
        
        // Debug cart items calculation
        debugCartItemsCalculation();
        
        // Test cart count on page load
        debugLog('Page loaded, testing cart count...');
        testCartCount();
        
        // Initialize tooltips if Bootstrap is available
        if (typeof $().tooltip === 'function') {
            $('.enhanced-color-preview').tooltip({
                placement: 'top',
                trigger: 'hover',
                delay: { show: 300, hide: 100 }
            });
        }
    }, 100);
    
    // Debug function để kiểm tra tính toán cart items
    function debugCartItemsCalculation() {
        debugLog('=== DEBUG CART ITEMS CALCULATION ===');
        $('.item-checkbox').each(function(index) {
            const $checkbox = $(this);
            const price = parseFloat($checkbox.data('price')) || 0;
            const quantity = parseInt($checkbox.data('quantity')) || 0;
            const itemTotal = parseFloat($checkbox.data('item-total')) || 0;
            const calculatedTotal = price * quantity;
            
            debugLog(`Item ${index + 1}: Price=${price}, Qty=${quantity}, DataTotal=${itemTotal}, CalculatedTotal=${calculatedTotal}`);
            
            if (calculatedTotal !== itemTotal) {
                debugLog(`⚠️ MISMATCH: ${price} x ${quantity} = ${calculatedTotal}, but data shows ${itemTotal}`);
            }
        });
        debugLog('=====================================');
    }

    // Initialize item selection functionality
    function initializeItemSelection() {
        // Select all checkbox functionality (desktop) - Đã ẩn để tránh lỗi
        // $('#select-all-items').on('change', function() {
        //     const isChecked = $(this).is(':checked');
        //     $('.item-checkbox').prop('checked', isChecked);
        //     
        //     // Không cần cập nhật data attributes ở đây vì getSelectedItems() sẽ tự động lấy từ input
        //     debugLog(`Select all changed: ${isChecked ? 'checked' : 'unchecked'}`);
        //     
        //     updateSelectedItems();
        // });

        // Mobile select all checkbox functionality - Đã ẩn để tránh lỗi
        // $('#mobile-select-all').on('change', function() {
        //     const isChecked = $(this).is(':checked');
        //     $('.item-checkbox').prop('checked', isChecked);
        //     
        //     // Không cần cập nhật data attributes ở đây vì getSelectedItems() sẽ tự động lấy từ input
        //     debugLog(`Mobile select all changed: ${isChecked ? 'checked' : 'unchecked'}`);
        //     
        //     updateSelectedItems();
        // });

        // Individual item checkbox functionality
        $(document).on('change', '.item-checkbox', function() {
            // Cập nhật data attributes trước khi tính toán
            const $checkbox = $(this);
            const $row = $checkbox.closest('tr, .cart-item-card');
            const $quantityInput = $row.find('.quantity-input');
            
            if ($quantityInput.length > 0) {
                const price = parseFloat($checkbox.data('price')) || 0;
                const quantity = parseInt($quantityInput.val()) || 0;
                const itemTotal = price * quantity;
                
                $checkbox.data('quantity', quantity);
                $checkbox.data('item-total', itemTotal);
            }
            
            updateSelectedItems();
            // updateSelectAllCheckbox(); // Đã ẩn vì không còn tích chọn tất cả
            
            // Show feedback when items are selected/deselected
            const selectedCount = $('.item-checkbox:checked').length;
            if (selectedCount === 1) {
                showToast('Đã chọn 1 sản phẩm để thanh toán', 'info');
            } else if (selectedCount > 1) {
                showToast(`Đã chọn ${selectedCount} sản phẩm để thanh toán`, 'info');
            }
        });

        // Checkout selected items
        $('#checkout-selected-btn').on('click', function() {
            const selectedItems = getSelectedItems();
            if (selectedItems.length === 0) {
                showToast('Vui lòng chọn ít nhất một sản phẩm để thanh toán!', 'error');
                return;
            }
            
            // Get selected cart IDs
            const selectedCartIds = selectedItems.map(item => item.cartId);
            
            // Create form and submit to checkout-selected route
            const form = $('<form>', {
                'method': 'POST',
                'action': '{{ route("client.checkout-selected") }}'
            });
            
            // Add CSRF token
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': $('meta[name="csrf-token"]').attr('content')
            }));
            
            // Add selected items
            selectedCartIds.forEach(function(cartId) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'selected_items[]',
                    'value': cartId
                }));
            });
            
            // Submit form
            $('body').append(form);
            form.submit();
        });

        // Initialize on page load
        initializeCheckboxData();
        updateSelectedItems();
        
        // Double-check after a short delay to ensure everything is loaded
        setTimeout(function() {
            // console.log removed
            initializeCheckboxData();
            updateSelectedItems();
        }, 500);
    }

    // Initialize checkbox data attributes
    function initializeCheckboxData() {
        $('.item-checkbox').each(function() {
            const $checkbox = $(this);
            const $row = $checkbox.closest('tr, .cart-item-card');
            const $quantityInput = $row.find('.quantity-input');
            
            if ($quantityInput.length > 0) {
                const price = parseFloat($checkbox.data('price')) || 0;
                const quantity = parseInt($quantityInput.val()) || 0;
                const itemTotal = price * quantity;
                
                // LUÔN cập nhật data attributes với số lượng thực tế từ input
                $checkbox.data('quantity', quantity);
                $checkbox.data('item-total', itemTotal);
                
                debugLog(`Initialized checkbox ${$checkbox.data('cart-id')}: Price=${price}, Quantity=${quantity}, Total=${itemTotal}`);
            }
        });
    }
    
    // Sync all checkbox data attributes with current quantity inputs
    function syncAllCheckboxData() {
        $('.item-checkbox').each(function() {
            const $checkbox = $(this);
            const $row = $checkbox.closest('tr, .cart-item-card');
            const $quantityInput = $row.find('.quantity-input');
            
            if ($quantityInput.length > 0) {
                const price = parseFloat($checkbox.data('price')) || 0;
                const quantity = parseInt($quantityInput.val()) || 0;
                const itemTotal = price * quantity;
                
                // Luôn cập nhật với số lượng thực tế từ input
                $checkbox.data('quantity', quantity);
                $checkbox.data('item-total', itemTotal);
            }
        });
        
        debugLog('Synced all checkbox data attributes with current quantity inputs');
    }

    // Update selected items count and totals
    function updateSelectedItems() {
        const selectedItems = getSelectedItems();
        const selectedCount = selectedItems.length;
        
        // Tính tổng số lượng sản phẩm (quantity) thay vì số items
        const totalQuantity = selectedItems.reduce((sum, item) => sum + item.quantity, 0);
        
        // Debug log để kiểm tra
        debugLog('Selected count (items):', selectedCount);
        debugLog('Total quantity:', totalQuantity);
        
        // Update selected count display - hiển thị tổng quantity
        $('#selected-count').text(totalQuantity);
        
        // Update checkout button state
        const $checkoutBtn = $('#checkout-selected-btn');
        @if($isHighQuantityCart)
        // Nếu số lượng giỏ hàng quá cao, luôn disable button
        $checkoutBtn.prop('disabled', true);
        $checkoutBtn.html('<i class="fa fa-phone"></i>LIÊN HỆ TƯ VẤN');
        @else
        if (selectedCount === 0) {
            $checkoutBtn.prop('disabled', true);
            // Khi không có sản phẩm nào được chọn, tính tổng tất cả sản phẩm
            updateOrderTotal();
        } else {
            $checkoutBtn.prop('disabled', false);
            // Khi có sản phẩm được chọn, chỉ tính tổng sản phẩm được chọn
            updateOrderSummaryForSelectedItems(selectedItems);
        }
        @endif
    }

    // Get selected items data
    function getSelectedItems() {
        const selectedItems = [];
        const checkedCheckboxes = $('.item-checkbox:checked');
        
        checkedCheckboxes.each(function() {
            const $checkbox = $(this);
            const $row = $checkbox.closest('tr, .cart-item-card');
            const $quantityInput = $row.find('.quantity-input');
            
            // Lấy giá trị từ data attributes của checkbox
            let price = parseFloat($checkbox.data('price')) || 0;
            let quantity = 0;
            let itemTotal = 0;
            
            // LUÔN lấy số lượng thực tế từ input quantity để đảm bảo chính xác
            if ($quantityInput.length > 0) {
                quantity = parseInt($quantityInput.val()) || 0;
                itemTotal = price * quantity;
                
                // Cập nhật lại data attributes với số lượng thực tế
                $checkbox.data('quantity', quantity);
                $checkbox.data('item-total', itemTotal);
                
                debugLog(`Item ${$checkbox.data('cart-id')}: Price=${price}, Quantity=${quantity}, Total=${itemTotal}`);
            } else {
                // Fallback nếu không tìm thấy input
                quantity = parseInt($checkbox.data('quantity')) || 0;
                itemTotal = parseFloat($checkbox.data('item-total')) || 0;
                debugLog(`Item ${$checkbox.data('cart-id')}: Using fallback data - Price=${price}, Quantity=${quantity}, Total=${itemTotal}`);
            }
            
            if (quantity > 0) {
                selectedItems.push({
                    cartId: $checkbox.data('cart-id'),
                    price: price,
                    quantity: quantity,
                    itemTotal: itemTotal
                });
            }
        });
        
        return selectedItems;
    }

    // Update select all checkbox state - Đã ẩn vì không còn tích chọn tất cả
    // function updateSelectAllCheckbox() {
    //     const totalItems = $('.item-checkbox').length;
    //     const checkedItems = $('.item-checkbox:checked').length;
    //     
    //     if (checkedItems === 0) {
    //         $('#select-all-items, #mobile-select-all').prop('indeterminate', false).prop('checked', false);
    //     } else if (checkedItems === totalItems) {
    //         $('#select-all-items, #mobile-select-all').prop('indeterminate', false).prop('checked', true);
    //     } else {
    //         $('#select-all-items, #mobile-select-all').prop('indeterminate', true).prop('checked', false);
    //     }
    // }

    // Update order summary for selected items only
    function updateOrderSummaryForSelectedItems(selectedItems) {
        let subtotal = 0;
        
        // Debug calculation
        debugCalculation(selectedItems);
        
        selectedItems.forEach(function(item) {
            const itemTotal = parseFloat(item.itemTotal) || 0;
            subtotal += itemTotal;
        });
        
        // Không làm tròn, giữ nguyên giá trị chính xác
        const shipping = subtotal > 0 ? 30000 : 0;
        const total = subtotal + shipping;
        
        debugLog(`Selected items subtotal: ${subtotal}, shipping: ${shipping}, total: ${total}`);
        
        // Update summary display
        animateNumber($('#subtotal'), subtotal);
        animateNumber($('#shipping'), shipping);
        animateNumber($('#total'), total);
        
        // Show selection notice when items are selected
        $('#selection-notice').show();
    }

    // === Realtime Cart Notification ===
    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('cart-updates')
            .listen('CardUpdate', function(data) {
                // Xử lý hiển thị thông báo
            });
    }
});
} // Close initCartScript function
</script>
    </div> <!-- Close success-container -->
@endsection
