@extends('layouts.admin')

@section('title', 'Sản phẩm')

@push('meta')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
@endpush

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Danh sách sản phẩm</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                </li>
                <li class="active">
                    <strong>Sản phẩm</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action" style="margin-top: 20px;">
                <!-- Mobile Buttons (< 768px) -->
                <div class="header-buttons-mobile d-block d-md-none mb-2">
                    <a href="{{ route('admin.product.import') }}" class="btn btn-primary btn-header-mobile">
                        <i class="fa fa-upload"></i> Import/Export
                    </a>
                    <a href="{{ route('admin.product.product-variant.variant.list-variant') }}" class="btn btn-info btn-header-mobile">
                        <i class="fa fa-list"></i> Biến thể
                    </a>
                    <a href="{{ route('admin.product.create-product') }}" class="btn btn-success btn-header-mobile">
                        <i class="fa fa-plus"></i> Thêm mới
                    </a>
                    <a href="{{ route('admin.product.restore-product') }}" class="btn btn-warning btn-header-mobile">
                        <i class="fa fa-recycle"></i> Khôi phục
                    </a>
                </div>
                <!-- Desktop Buttons (>= 768px) -->
                <div class="header-buttons d-none d-md-flex">
                    <a href="{{ route('admin.product.import') }}" class="btn btn-primary btn-header">
                        <i class="fa fa-upload"></i> Import/Export
                    </a>
                    <a href="{{ route('admin.product.product-variant.variant.list-variant') }}" class="btn btn-info btn-header">
                        <i class="fa fa-list"></i> Danh sách biến thể
                    </a>
                    <a href="{{ route('admin.product.create-product') }}" class="btn btn-success btn-header">
                        <i class="fa fa-plus"></i> Thêm sản phẩm
                    </a>
                    <a href="{{ route('admin.product.restore-product') }}" class="btn btn-warning btn-header">
                        <i class="fa fa-recycle"></i> Khôi phục
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        @if(session('success'))
            <div class="alert alert-success alert-dismissable fade in">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissable fade in">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox shadow-sm">
                    <div class="ibox-title bg-gradient">
                        <h5><i class="fa fa-filter text-primary"></i> Bộ lọc tìm kiếm</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form action="{{ route('admin.product.index-product') }}" method="get" class="form-search" data-submitted="false">
                            <div class="search-overlay" id="searchOverlay">
                                <div class="text-center">
                                    <div class="search-spinner">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">Đang tìm kiếm...</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold" for="name">
                                            <i class="fa fa-tag text-muted"></i> Tên sản phẩm
                                        </label>
                                        <input type="text" id="name" name="name" value="{{ request('name') }}"
                                            placeholder="Nhập tên sản phẩm..." class="form-control form-control-lg">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold" for="category_id">
                                            <i class="fa fa-folder text-muted"></i> Danh mục
                                        </label>
                                        <select name="category_id" id="category_id" class="form-control form-control-lg">
                                            <option value="">🏷️ Tất cả danh mục</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                    📁 {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        <div class="search-buttons">
                                            <button type="submit" class="btn btn-primary btn-search">
                                                <i class="fa fa-search"></i> Tìm kiếm
                                            </button>
                                            <a href="{{ route('admin.product.index-product') }}" class="btn btn-secondary btn-reset">
                                                <i class="fa fa-refresh"></i> Đặt lại
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox shadow-sm">
                    <div class="ibox-title bg-gradient d-flex justify-content-between align-items-center">
                        <h5><i class="fa fa-cube text-primary"></i> Danh sách sản phẩm 
                            <span class="badge badge-primary ml-2">{{ $products->count() }}</span>
                        </h5>
                        <div class="ibox-tools">
                            <span class="text-muted small">
                                Tổng: {{ $products->total() ?? $products->count() }} sản phẩm
                                @if(request('name') || request('category_id'))
                                    | Tìm kiếm: 
                                    @if(request('name'))
                                        "{{ request('name') }}"
                                    @endif
                                    @if(request('category_id'))
                                        (ID: {{ request('category_id') }})
                                    @endif
                                @endif
                            </span>
                            @if(config('app.debug'))
                                <a href="{{ request()->fullUrlWithQuery(['debug' => '1']) }}" class="btn btn-xs btn-warning ml-2" target="_blank">
                                    <i class="fa fa-bug"></i> Debug
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="ibox-content p-0">
                        @if($products->count() > 0)
                            <!-- Responsive Table View -->
                            <div class="table-responsive">
                                <table class="table table-hover table-striped m-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-top-0 product-col">
                                                <i class="fa fa-tag text-muted"></i> 
                                                <span class="d-none d-lg-inline">Sản phẩm</span>
                                                <span class="d-lg-none">SP</span>
                                            </th>
                                            <th class="text-center border-top-0 category-col d-none d-md-table-cell">
                                                <i class="fa fa-folder text-muted"></i> 
                                                <span class="d-none d-lg-inline">Danh mục</span>
                                                <span class="d-lg-none">DM</span>
                                            </th>
                                            <th class="text-right border-top-0 price-col">
                                                <i class="fa fa-money text-muted"></i> 
                                                <span class="d-none d-lg-inline">Giá bán</span>
                                                <span class="d-lg-none">Giá</span>
                                            </th>
                                            <th class="text-center border-top-0 stock-col d-none d-sm-table-cell">
                                                <i class="fa fa-cubes text-muted"></i> 
                                                <span class="d-none d-lg-inline">Kho</span>
                                                <span class="d-lg-none">SL</span>
                                            </th>
                                            <th class="text-center border-top-0 action-col">
                                                <i class="fa fa-cogs text-muted"></i>
                                                <span class="d-none d-lg-inline">Thao tác</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            @php
                                                $variant = $product->variants->first();
                                                $totalStock = $product->variants->sum('stock_quantity');
                                                $minPrice = $product->variants->min('price');
                                                $maxPrice = $product->variants->max('price');
                                            @endphp
                                            <tr class="product-row">
                                                <td class="product-info">
                                                    <div class="d-flex align-items-center">
                                                        <div class="product-image mr-2 mr-md-3">
                                                            @if($product->image)
                                                                <a href="{{ route('admin.product.show-product', $product->id) }}">
                                                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                                                         alt="{{ $product->name }}"
                                                                         class="product-thumb">
                                                                </a>
                                                            @else
                                                                <div class="product-thumb product-thumb-placeholder">
                                                                    <i class="fa fa-image"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="product-details flex-grow-1">
                                                            <a href="{{ route('admin.product.show-product', $product->id) }}" 
                                                               class="product-name mb-1">{{ Str::limit($product->name, 50) }}</a>
                                                            <div class="product-meta d-flex flex-wrap align-items-center">
                                                                <!-- Show category on mobile under product name -->
                                                                <span class="badge badge-primary badge-sm d-md-none mr-1 mb-1">
                                                                    {{ Str::limit($product->category->name ?? 'Chưa phân loại', 15) }}
                                                                </span>
                                                                @if($product->variants->count() > 1)
                                                                    <span class="badge badge-info badge-sm mr-1 mb-1">
                                                                        {{ $product->variants->count() }} biến thể
                                                                    </span>
                                                                @endif
                                                                <!-- Show stock on mobile -->
                                                                <span class="badge d-sm-none mb-1
                                                                    @if($totalStock > 10) badge-success
                                                                    @elseif($totalStock > 0) badge-warning
                                                                    @else badge-danger @endif">
                                                                    @if($totalStock > 0)
                                                                        SL: {{ $totalStock }}
                                                                    @else
                                                                        Hết hàng
                                                                    @endif
                                                                </span>
                                                                <small class="text-muted d-none d-lg-inline ml-2">
                                                                    <i class="fa fa-calendar"></i> {{ $product->created_at ? $product->created_at->format('d/m/Y') : 'N/A' }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center d-none d-md-table-cell">
                                                    <span class="badge badge-primary">
                                                        {{ Str::limit($product->category->name ?? 'Chưa phân loại', 20) }}
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    @if($variant)
                                                        <!-- Kiểm tra nếu sản phẩm có giá và giá khuyến mại -->
                                                        @if($product->price || $product->promotion_price)
                                                            <div class="price-display">
                                                                <!-- Hiển thị giá sản phẩm chính -->
                                                                @if($product->promotion_price)
                                                                    <div class="current-price">{{ number_format($product->promotion_price, 0, ',', '.') }} đ</div>
                                                                    @if($product->price && $product->price != $product->promotion_price)
                                                                        <div class="original-price">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                                                                        @if($product->price < $product->promotion_price)
                                                                            @php $discount = round((($product->promotion_price - $product->price) / $product->promotion_price) * 100) @endphp
                                                                            <span class="discount-badge">-{{ $discount }}%</span>
                                                                        @endif
                                                                    @endif
                                                                @elseif($product->price)
                                                                    <div class="current-price">{{ number_format($product->price, 0, ',', '.') }} đ</div>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <!-- Hiển thị giá biến thể -->
                                                            <div class="price-display">
                                                                @if($minPrice == $maxPrice)
                                                                    @if($variant->promotion_price)
                                                                        <div class="current-price">{{ number_format($variant->promotion_price, 0, ',', '.') }} đ</div>
                                                                        @if($variant->price && $variant->price != $variant->promotion_price)
                                                                            <div class="original-price">{{ number_format($variant->price, 0, ',', '.') }} đ</div>
                                                                            @if($variant->price < $variant->promotion_price)
                                                                                @php $discount = round((($variant->promotion_price - $variant->price) / $variant->promotion_price) * 100) @endphp
                                                                                <span class="discount-badge">-{{ $discount }}%</span>
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        <div class="current-price">{{ number_format($variant->price, 0, ',', '.') }} đ</div>
                                                                    @endif
                                                                @else
                                                                    <div class="price-range">
                                                                        <div class="d-none d-lg-block">
                                                                            <div class="range-label">Từ</div>
                                                                            <div class="range-min">{{ number_format($minPrice, 0, ',', '.') }} đ</div>
                                                                            <div class="range-max">đến {{ number_format($maxPrice, 0, ',', '.') }} đ</div>
                                                                        </div>
                                                                        <div class="d-lg-none">
                                                                            <div class="price-range-mobile">
                                                                                {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }} đ
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="no-price">
                                                            <i class="fa fa-ban text-muted"></i>
                                                            <span class="text-muted">Chưa có giá</span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center d-none d-sm-table-cell">
                                                    @if($totalStock > 10)
                                                        <span class="badge badge-success">{{ $totalStock }}</span>
                                                    @elseif($totalStock > 0)
                                                        <span class="badge badge-warning">{{ $totalStock }}</span>
                                                    @else
                                                        <span class="badge badge-danger">Hết hàng</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="action-buttons">
                                                        <a href="{{ route('admin.product.show-product', $product->id) }}"
                                                            class="btn btn-info btn-action" title="Xem chi tiết">
                                                            <i class="fa fa-eye"></i>
                                                            <span class="btn-text d-none d-xl-inline">Xem</span>
                                                        </a>
                                                        <a href="{{ route('admin.product.edit-product', $product->id) }}"
                                                            class="btn btn-primary btn-action" title="Chỉnh sửa">
                                                            <i class="fa fa-edit"></i>
                                                            <span class="btn-text d-none d-xl-inline">Sửa</span>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-action" 
                                                                title="Xóa sản phẩm"
                                                                onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')">
                                                            <i class="fa fa-trash"></i>
                                                            <span class="btn-text d-none d-xl-inline">Xóa</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    @if(request('name') || request('category_id'))
                                        <i class="fa fa-search"></i>
                                    @else
                                        <i class="fa fa-cube"></i>
                                    @endif
                                </div>
                                @if(request('name') || request('category_id'))
                                    <h4 class="empty-state-title">Không tìm thấy sản phẩm phù hợp</h4>
                                    <p class="empty-state-text">
                                        @if(request('name') && request('category_id'))
                                            Không có sản phẩm nào có tên "<strong>{{ request('name') }}</strong>" trong danh mục đã chọn
                                        @elseif(request('name'))
                                            Không có sản phẩm nào có tên "<strong>{{ request('name') }}</strong>"
                                        @else
                                            Không có sản phẩm nào trong danh mục đã chọn
                                        @endif
                                    </p>
                                    <div class="empty-state-actions">
                                        <a href="{{ route('admin.product.index-product') }}" class="btn btn-outline-primary">
                                            <i class="fa fa-refresh"></i> Xem tất cả sản phẩm
                                        </a>
                                        <a href="{{ route('admin.product.create-product') }}" class="btn btn-success ml-2">
                                            <i class="fa fa-plus"></i> Thêm sản phẩm mới
                                        </a>
                                    </div>
                                @else
                                    <h4 class="empty-state-title">Chưa có sản phẩm nào</h4>
                                    <p class="empty-state-text">Thêm sản phẩm đầu tiên của bạn để bắt đầu bán hàng</p>
                                    <a href="{{ route('admin.product.create-product') }}" class="btn btn-primary btn-lg">
                                        <i class="fa fa-plus"></i> Thêm sản phẩm đầu tiên
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if($products->hasPages())
                            <div class="pagination-wrapper">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="productName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced Styling */
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .bg-gradient {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .form-search .form-control-lg {
            border-radius: 6px;
            border: 1px solid #e1e5e9;
            transition: all 0.2s ease;
        }

        .form-search .form-control-lg:focus {
            border-color: #1ab394;
            box-shadow: 0 0 0 0.2rem rgba(26, 179, 148, 0.1);
        }

        /* Enhanced Product Table Styles */
        .product-row {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .product-row:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-left-color: #1ab394;
        }

        .table-hover tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
        }

        /* Responsive Table Columns */
        .product-col {
            width: 40%;
            min-width: 250px;
        }
        
        .category-col {
            width: 15%;
            min-width: 120px;
        }
        
        .price-col {
            width: 20%;
            min-width: 100px;
        }
        
        .stock-col {
            width: 10%;
            min-width: 80px;
        }
        
        .action-col {
            width: 15%;
            min-width: 120px;
        }

        /* Product image styles - optimized for all screen sizes */
        .product-thumb {
            width: 80px !important;
            height: 80px !important;
            min-width: 80px !important;
            max-width: 80px !important;
            min-height: 80px !important;
            max-height: 80px !important;
            border-radius: 10px;
            object-fit: cover;
            object-position: center;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: block;
        }

        .product-thumb:hover {
            transform: scale(1.08);
            border-color: #1ab394;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .product-thumb-placeholder {
            width: 80px !important;
            height: 80px !important;
            min-width: 80px !important;
            max-width: 80px !important;
            min-height: 80px !important;
            max-height: 80px !important;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .product-thumb-placeholder:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }

        .product-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px !important;
            text-decoration: none;
            transition: all 0.2s ease;
            display: block;
            line-height: 1.3;
        }

        .product-name:hover {
            color: #1ab394;
            text-decoration: none;
            transform: translateX(2px);
        }

        .product-name:focus {
            color: #1ab394;
            text-decoration: none;
            outline: none;
        }

        /* Product image containers */
        .product-image {
            width: 80px !important;
            height: 80px !important;
            min-width: 80px !important;
            max-width: 80px !important;
            min-height: 80px !important;
            max-height: 80px !important;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }

        .product-image a {
            display: block !important;
            text-decoration: none;
            border-radius: 10px;
            overflow: hidden;
            width: 100% !important;
            height: 100% !important;
        }

        .product-image a:hover {
            text-decoration: none;
        }

        .product-image a:focus {
            outline: 2px solid #1ab394;
            outline-offset: 2px;
        }

        .product-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        /* Enhanced Price Display */
        .price-display {
            min-height: 45px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            gap: 2px;
        }

        .current-price {
            font-weight: 700;
            color: #1ab394;
            font-size: 15px;
            line-height: 1.2;
            text-shadow: 0 1px 2px rgba(26, 179, 148, 0.1);
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 12px;
            font-weight: 500;
            opacity: 0.8;
            line-height: 1;
        }

        .discount-badge {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
            margin-top: 2px;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .price-range {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1px;
        }

        .range-label {
            font-size: 10px;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .range-min {
            font-weight: 700;
            color: #1ab394;
            font-size: 14px;
            line-height: 1.2;
        }

        .range-max {
            font-size: 11px;
            color: #6c757d;
            font-weight: 500;
        }

        .price-range-mobile {
            font-weight: 600;
            color: #1ab394;
            font-size: 13px;
            text-align: center;
        }

        .no-price {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px;
            opacity: 0.7;
        }

        .no-price i {
            font-size: 16px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state-title {
            color: #495057;
            margin-bottom: 10px;
        }

        .empty-state-text {
            margin-bottom: 25px;
        }

        .empty-state-actions {
            margin-top: 20px;
        }

        .empty-state-actions .btn {
            margin: 5px;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        /* Enhanced Badges and Meta */
        .badge-sm {
            font-size: 10px;
            padding: 4px 8px;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }

        .badge-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border: none;
        }

        .badge-success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border: none;
        }

        .badge-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
            border: none;
        }

        .badge-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
        }

        .product-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 4px;
        }

        .product-meta .badge {
            font-size: 9px;
            padding: 3px 6px;
            margin-bottom: 2px;
        }

        /* Enhanced Action Buttons */
        .action-buttons {
            display: flex;
            gap: 4px;
            justify-content: center;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-action {
            position: relative;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            box-shadow: 0 3px 8px rgba(0,0,0,0.12);
            min-width: 76px;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            overflow: hidden;
        }

        .btn-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-action:hover::before {
            left: 100%;
        }

        .btn-action:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            text-decoration: none;
        }

        .btn-action:active {
            transform: translateY(-1px) scale(0.98);
            transition: all 0.1s;
        }

        .btn-action i {
            font-size: 13px;
            transition: transform 0.3s ease;
        }

        .btn-action:hover i {
            transform: scale(1.1);
        }

        .btn-text {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Specific button colors with enhanced gradients */
        .btn-action.btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 50%, #117a8b 100%);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-action.btn-info:hover {
            background: linear-gradient(135deg, #138496 0%, #117a8b 50%, #0f6674 100%);
            color: white;
            border-color: rgba(255,255,255,0.2);
        }

        .btn-action.btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-action.btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #002752 100%);
            color: white;
            border-color: rgba(255,255,255,0.2);
        }

        .btn-action.btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 50%, #bd2130 100%);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-action.btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 50%, #a71e2a 100%);
            color: white;
            border-color: rgba(255,255,255,0.2);
        }

        /* Header Buttons */
        .header-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .header-buttons-mobile {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Ensure proper responsive display */
        @media (min-width: 768px) {
            .header-buttons-mobile {
                display: none !important;
            }
            .header-buttons {
                display: flex !important;
            }
        }

        @media (max-width: 767.98px) {
            .header-buttons {
                display: none !important;
            }
            .header-buttons-mobile {
                display: flex !important;
            }
            
            /* Enhanced Mobile table adjustments */
            .product-col {
                width: 55%;
                min-width: 220px;
            }
            
            .price-col {
                width: 30%;
                min-width: 100px;
            }
            
            .action-col {
                width: 15%;
                min-width: 90px;
            }
            
            .product-thumb, .product-thumb-placeholder {
                width: 65px !important;
                height: 65px !important;
                min-width: 65px !important;
                max-width: 65px !important;
                min-height: 65px !important;
                max-height: 65px !important;
            }
            
            .product-image {
                width: 65px !important;
                height: 65px !important;
                min-width: 65px !important;
                max-width: 65px !important;
                min-height: 65px !important;
                max-height: 65px !important;
            }
            
            .product-name {
                font-size: 13px;
                line-height: 1.4;
            }
            
            .btn-action {
                padding: 8px 10px;
                min-width: 60px;
                font-size: 11px;
            }
            
            .btn-action i {
                font-size: 12px;
            }
            
            .current-price {
                font-size: 14px;
            }
            
            .price-display {
                min-height: 40px;
            }
            
            .discount-badge {
                font-size: 9px;
                padding: 1px 4px;
            }
            
            .search-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .ibox-title h5 {
                font-size: 14px;
            }
            
            .empty-state {
                padding: 40px 15px;
            }
            
            .empty-state-icon {
                font-size: 3rem;
            }
        }

        @media (max-width: 575.98px) {
            .product-col {
                width: 60%;
                min-width: 180px;
            }
            
            .price-col {
                width: 25%;
                min-width: 80px;
            }
            
            .action-col {
                width: 15%;
                min-width: 70px;
            }
            
            .product-thumb, .product-thumb-placeholder {
                width: 60px !important;
                height: 60px !important;
                min-width: 60px !important;
                max-width: 60px !important;
                min-height: 60px !important;
                max-height: 60px !important;
            }
            
            .product-image {
                width: 60px !important;
                height: 60px !important;
                min-width: 60px !important;
                max-width: 60px !important;
                min-height: 60px !important;
                max-height: 60px !important;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
            
            .btn-action {
                padding: 5px 6px;
                min-width: 40px;
            }
        }

        .btn-header {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .btn-header:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            text-decoration: none;
        }

        .btn-header-mobile {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            width: 100%;
        }

        .btn-header-mobile:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            text-decoration: none;
        }

        /* Header button colors */
        .btn-header.btn-info, .btn-header-mobile.btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .btn-header.btn-info:hover, .btn-header-mobile.btn-info:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
            color: white;
        }

        .btn-header.btn-success, .btn-header-mobile.btn-success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
        }

        .btn-header.btn-success:hover, .btn-header-mobile.btn-success:hover {
            background: linear-gradient(135deg, #1e7e34, #155724);
            color: white;
        }

        .btn-header.btn-warning, .btn-header-mobile.btn-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
        }

        .btn-header.btn-warning:hover, .btn-header-mobile.btn-warning:hover {
            background: linear-gradient(135deg, #e0a800, #d39e00);
            color: #212529;
        }

        /* Additional responsive fixes */
        .title-action {
            min-height: 50px;
            display: flex;
            align-items: center;
        }

        /* Search Form Buttons */
        .search-buttons {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .btn-search, .btn-reset {
            flex: 1;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .btn-search:hover, .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            text-decoration: none;
        }

        .btn-search {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .btn-search:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            color: white;
        }

        .btn-reset {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #5a6268, #495057);
            color: white;
        }

        /* Search Form Loading States */
        .form-control:disabled {
            background-color: #f8f9fa;
            opacity: 0.7;
            cursor: wait;
        }

        .btn-search:disabled,
        .btn-reset:disabled {
            opacity: 0.7;
            cursor: wait;
            transform: none !important;
        }

        #category-loader {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced Form Feedback */
        .form-search {
            position: relative;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .search-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: 10px;
        }

        .search-overlay.active {
            display: flex;
        }

        .search-spinner {
            font-size: 28px;
            color: #1ab394;
            animation: spin 1s linear infinite;
            text-shadow: 0 2px 4px rgba(26, 179, 148, 0.3);
        }

        /* Clear search button styles */
        .form-group {
            position: relative;
        }

        .clear-search {
            position: absolute !important;
            right: 10px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 5 !important;
            padding: 2px 6px !important;
            color: #6c757d !important;
            font-size: 12px !important;
            border: none !important;
            background: transparent !important;
        }

        .clear-search:hover {
            color: #dc3545 !important;
            background: transparent !important;
        }

        /* Input field with clear button */
        .form-control-with-clear {
            padding-right: 35px !important;
        }

        /* Loading animation */
        .fade.in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Table improvements */
        .table-hover tbody tr:hover {
            background-color: rgba(26, 179, 148, 0.05);
        }

        .thead-light th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Force horizontal scroll on small screens */
        @media (max-width: 575.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                min-width: 600px;
            }
            
            .search-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .pagination-wrapper {
                padding: 15px 10px;
            }
        }
    </style>

    <script>
        function deleteProduct(productId, productName) {
            document.getElementById('productName').textContent = productName;
            document.getElementById('deleteForm').action = `/admin/product/delete/${productId}`;
            $('#deleteModal').modal('show');
        }

        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        // Simplified search form handling - no duplicate submissions
        $(document).ready(function() {
            var $searchForm = $('.form-search');
            var $categorySelect = $('#category_id');
            var $nameInput = $('#name');
            var isSubmitting = false;

            // Simple loading state
            function showLoading() {
                if (isSubmitting) return false;
                isSubmitting = true;
                $('#searchOverlay').addClass('active');
                $searchForm.find('input, select, button').prop('disabled', true);
                return true;
            }

            function hideLoading() {
                isSubmitting = false;
                $('#searchOverlay').removeClass('active');
                $searchForm.find('input, select, button').prop('disabled', false);
            }

            // Auto-submit on category change - ONCE only
            $categorySelect.on('change', function() {
                if (isSubmitting) return; // Prevent if already submitting
                
                showLoading();
                
                // Use a simple setTimeout to allow UI update
                setTimeout(function() {
                    if (isSubmitting) { // Double check
                        $searchForm[0].submit();
                    }
                }, 100);
            });

            // Regular form submit handling
            $searchForm.on('submit', function() {
                if (isSubmitting) {
                    return false; // Prevent if already submitting
                }
                showLoading();
            });

            // Reset button
            $('.btn-reset').on('click', function(e) {
                e.preventDefault();
                if (isSubmitting) return;
                
                showLoading();
                $nameInput.val('');
                $categorySelect.val('');
                
                setTimeout(function() {
                    if (isSubmitting) {
                        window.location.href = $searchForm.attr('action');
                    }
                }, 100);
            });

            // Enter key search
            $nameInput.on('keypress', function(e) {
                if (e.which === 13 && !isSubmitting) {
                    $searchForm.submit();
                }
            });

            // Force reset loading state after page load
            $(window).on('load', function() {
                hideLoading();
            });

            // Reset on page show (back button)
            $(window).on('pageshow', function() {
                hideLoading();
            });

            // Timeout failsafe
            setTimeout(function() {
                hideLoading();
            }, 3000);
        });
    </script>
@endsection
