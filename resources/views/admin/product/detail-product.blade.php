@extends('layouts.admin')
@section('title', 'Chi tiết sản phẩm')

@section('styles')
    <script>
        // Define functions immediately in head to ensure availability
        window.confirmDelete = function() {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.')) {
                document.getElementById('delete-form').submit();
            }
        };

        window.deleteVariant = function(variantId) {
            if (confirm('Bạn có chắc chắn muốn xóa biến thể này?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/product/delete-variant/${variantId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        };
    </script>
@endsection

@section('content')
    <!-- WordPress-style Product Header -->
    <div class="wp-product-header" style="background: #fff !important; border-bottom: 1px solid #ccd0d4 !important; padding: 0 !important; margin: 0 0 20px 0 !important; box-shadow: 0 1px 1px rgba(0,0,0,.04) !important;">
        <div class="wp-product-header-content" style="display: flex !important; justify-content: space-between !important; align-items: center !important; padding: 15px 20px !important; max-width: 1200px !important; margin: 0 auto !important;">
            <div class="wp-product-header-left">
                <h1 class="wp-product-heading" style="font-size: 23px !important; font-weight: 400 !important; margin: 0 !important; color: #444 !important; display: flex !important; align-items: center !important; gap: 10px !important;">
                    <i class="fa fa-cube"></i>
                    Chi tiết sản phẩm
                </h1>
                <div class="wp-product-breadcrumb" style="margin-top: 8px !important; font-size: 13px !important; color: #666 !important;">
                    <a href="{{ route('admin.product.index-product') }}" style="color: #0073aa !important; text-decoration: none !important;">
                        <i class="fa fa-arrow-left"></i>
                        Quay lại danh sách
                    </a>
                    <span class="separator" style="margin: 0 8px !important;">›</span>
                    <span class="current" style="color: #444 !important; font-weight: 600 !important;">{{ $product->name }}</span>
                </div>
            </div>
            <div class="wp-product-header-right">
                <div class="wp-product-actions" style="display: flex !important; gap: 10px !important;">
                    <a href="{{ route('admin.product.edit-product', $product->id) }}" class="wp-product-btn wp-product-btn-primary" style="display: inline-flex !important; align-items: center !important; gap: 5px !important; padding: 8px 16px !important; border: 1px solid #0073aa !important; border-radius: 3px !important; background: #0073aa !important; color: #fff !important; text-decoration: none !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important; line-height: normal !important;">
                        <i class="fa fa-edit"></i>
                        Chỉnh sửa
                    </a>
                    <button type="button" class="wp-product-btn wp-product-btn-secondary" onclick="window.confirmDelete()" style="display: inline-flex !important; align-items: center !important; gap: 5px !important; padding: 8px 16px !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; background: #f7f7f7 !important; color: #444 !important; text-decoration: none !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important; line-height: normal !important;">
                        <i class="fa fa-trash"></i>
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- WordPress-style Product Content -->
    <div class="wp-product-content" style="max-width: 1200px !important; margin: 0 auto !important; padding: 0 20px !important;">
        <div class="wp-product-content-area" style="display: grid !important; grid-template-columns: 1fr 300px !important; gap: 20px !important;">
            <!-- Main Product Section -->
            <div class="wp-product-main" style="min-width: 0 !important;">
                <!-- Product Overview Card -->
                <div class="wp-product-card" style="background: #fff !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; margin-bottom: 20px !important; box-shadow: 0 1px 1px rgba(0,0,0,.04) !important;">
                    <div class="wp-product-card-header" style="border-bottom: 1px solid #ccd0d4 !important; padding: 15px 20px !important; display: flex !important; justify-content: space-between !important; align-items: center !important; background: #f9f9f9 !important;">
                        <h2 class="wp-product-card-title" style="font-size: 16px !important; font-weight: 600 !important; margin: 0 !important; color: #444 !important; display: flex !important; align-items: center !important; gap: 8px !important;">
                            <i class="fa fa-info-circle"></i>
                            Thông tin sản phẩm
                        </h2>
                        <div class="wp-product-card-actions" style="display: flex !important; align-items: center !important; gap: 10px !important;">
                            <span class="wp-product-status {{ $product->status ? 'status-active' : 'status-inactive' }}" style="padding: 4px 8px !important; border-radius: 12px !important; font-size: 11px !important; font-weight: 600 !important; text-transform: uppercase !important; {{ $product->status ? 'background: rgba(70, 180, 80, 0.1) !important; color: #46b450 !important;' : 'background: rgba(220, 50, 50, 0.1) !important; color: #dc3232 !important;' }}">
                                <i class="fa {{ $product->status ? 'fa-check' : 'fa-times' }}"></i>
                                {{ $product->status ? 'Đang hoạt động' : 'Tạm ngưng' }}
                            </span>
                        </div>
                    </div>
                    <div class="wp-product-card-body" style="padding: 20px !important;">
                        <div class="wp-product-row" style="display: grid !important; grid-template-columns: 1fr 2fr !important; gap: 20px !important;">
                            <div class="wp-product-col-4">
                                <div class="wp-product-image-container" style="position: relative !important; border-radius: 3px !important; overflow: hidden !important; border: 1px solid #ccd0d4 !important;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="wp-product-main-image"
                                             data-image-url="{{ asset('storage/' . $product->image) }}"
                                             style="width: 100% !important; height: 250px !important; object-fit: cover !important; display: block !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important;">
                                        <div class="wp-product-image-overlay" style="position: absolute !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; background: rgba(0, 0, 0, 0.5) !important; display: flex !important; align-items: center !important; justify-content: center !important; opacity: 0 !important; transition: all 0.15s ease-in-out !important;">
                                            <i class="fa fa-search" style="color: #fff !important; font-size: 24px !important;"></i>
                                        </div>
                                    @else
                                        <div class="wp-product-no-image" style="height: 250px !important; background: #f9f9f9 !important; border: 2px dashed #ccd0d4 !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; color: #666 !important;">
                                            <i class="fa fa-image" style="font-size: 48px !important; margin-bottom: 10px !important; opacity: 0.5 !important;"></i>
                                            <p>Chưa có hình ảnh</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="wp-product-col-8">
                                <div class="wp-product-details">
                                    <h3 class="wp-product-title" style="font-size: 24px !important; font-weight: 600 !important; margin: 0 0 15px 0 !important; color: #444 !important;">{{ $product->name }}</h3>
                                    
                                    <div class="wp-product-meta" style="margin-bottom: 20px !important;">
                                        <div class="wp-product-meta-item" style="display: flex !important; align-items: center !important; gap: 8px !important; margin-bottom: 8px !important; font-size: 14px !important;">
                                            <i class="fa fa-tag" style="color: #666 !important; font-size: 16px !important; width: 16px !important;"></i>
                                            <strong>Danh mục:</strong> {{ $product->category->name }}
                                        </div>
                                        <div class="wp-product-meta-item" style="display: flex !important; align-items: center !important; gap: 8px !important; margin-bottom: 8px !important; font-size: 14px !important;">
                                            <i class="fa fa-eye" style="color: #666 !important; font-size: 16px !important; width: 16px !important;"></i>
                                            <strong>Lượt xem:</strong> {{ number_format($product->view) }}
                                        </div>
                                        <div class="wp-product-meta-item" style="display: flex !important; align-items: center !important; gap: 8px !important; margin-bottom: 8px !important; font-size: 14px !important;">
                                            <i class="fa fa-calendar" style="color: #666 !important; font-size: 16px !important; width: 16px !important;"></i>
                                            <strong>Ngày tạo:</strong> {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </div>

                                        @if($product->promotion_price)
                                            <div class="wp-product-meta-item" style="display: flex !important; align-items: center !important; gap: 8px !important; margin-bottom: 8px !important; font-size: 14px !important;">
                                                <i class="fa fa-percent" style="color: #666 !important; font-size: 16px !important; width: 16px !important;"></i>
                                                <strong>Giá khuyến mãi:</strong> 
                                                <span style="color: #dc3232 !important; font-weight: 600 !important;">{{ number_format($product->promotion_price, 0, ',', '.') }} VND</span>
                                            </div>
                                        @endif
                                        <div class="wp-product-meta-item" style="display: flex !important; align-items: center !important; gap: 8px !important; margin-bottom: 8px !important; font-size: 14px !important;">
                                            <i class="fa fa-cubes" style="color: #666 !important; font-size: 16px !important; width: 16px !important;"></i>
                                            <strong>Tổng tồn kho:</strong> 
                                            <span style="color: {{ $product->variants->sum('stock_quantity') > 0 ? '#46b450' : '#dc3232' }} !important; font-weight: 600 !important;">{{ number_format($product->variants->sum('stock_quantity')) }}</span>
                                        </div>
                                        <div class="wp-product-meta-item" style="display: flex !important; align-items: center !important; gap: 8px !important; margin-bottom: 8px !important; font-size: 14px !important;">
                                            <i class="fa fa-shopping-cart" style="color: #666 !important; font-size: 16px !important; width: 16px !important;"></i>
                                            <strong>Đã bán:</strong> 
                                            <span style="color: #0073aa !important; font-weight: 600 !important;">{{ $product->orderDetails->sum('quantity') }} sản phẩm</span>
                                        </div>
                                    </div>

                                    @if($product->description)
                                        <div class="wp-product-description" style="margin-top: 20px !important;">
                                            <h4 style="font-size: 16px !important; font-weight: 600 !important; margin: 0 0 10px 0 !important; color: #444 !important;">Mô tả sản phẩm</h4>
                                            <div class="wp-product-description-content" style="background: #f9f9f9 !important; padding: 15px !important; border-radius: 3px !important; border-left: 4px solid #0073aa !important; line-height: 1.6 !important; color: #444 !important;">
                                                {{ $product->description }}
                                            </div>
                                        </div>
                                    @endif


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variants Section -->
                @if($product->variants->count() > 0)
                    <div class="wp-product-card" style="background: #fff !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; margin-bottom: 20px !important; box-shadow: 0 1px 1px rgba(0,0,0,.04) !important;">
                        <div class="wp-product-card-header" style="border-bottom: 1px solid #ccd0d4 !important; padding: 15px 20px !important; display: flex !important; justify-content: space-between !important; align-items: center !important; background: #f9f9f9 !important;">
                            <h2 class="wp-product-card-title" style="font-size: 16px !important; font-weight: 600 !important; margin: 0 !important; color: #444 !important; display: flex !important; align-items: center !important; gap: 8px !important;">
                                <i class="fa fa-list"></i>
                                Biến thể sản phẩm
                                <span class="wp-product-variant-count" style="color: #666 !important;">({{ $product->variants->count() }})</span>
                            </h2>
                            <div class="wp-product-card-actions" style="display: flex !important; align-items: center !important; gap: 10px !important;">
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}" 
                                   class="wp-product-btn wp-product-btn-secondary" style="display: inline-flex !important; align-items: center !important; gap: 5px !important; padding: 8px 16px !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; background: #f7f7f7 !important; color: #444 !important; text-decoration: none !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important; line-height: normal !important;">
                                    <i class="fa fa-plus"></i>
                                    Thêm biến thể
                                </a>
                            </div>
                        </div>
                        <div class="wp-product-card-body" style="padding: 20px !important;">
                            <div class="wp-product-table-container" style="overflow-x: auto !important;">
                                <table class="wp-product-table" style="width: 100% !important; border-collapse: collapse !important; background: #fff !important; border: 1px solid #ccd0d4 !important;">
                                    <thead>
                                        <tr>
                                            <th style="background: #f9f9f9 !important; border-bottom: 1px solid #ccd0d4 !important; padding: 12px 15px !important; text-align: left !important; font-weight: 600 !important; font-size: 13px !important; color: #444 !important;">Hình ảnh</th>
                                            <th style="background: #f9f9f9 !important; border-bottom: 1px solid #ccd0d4 !important; padding: 12px 15px !important; text-align: left !important; font-weight: 600 !important; font-size: 13px !important; color: #444 !important;">Tên biến thể</th>
                                            <th style="background: #f9f9f9 !important; border-bottom: 1px solid #ccd0d4 !important; padding: 12px 15px !important; text-align: left !important; font-weight: 600 !important; font-size: 13px !important; color: #444 !important;">Giá bán</th>
                                            <th style="background: #f9f9f9 !important; border-bottom: 1px solid #ccd0d4 !important; padding: 12px 15px !important; text-align: left !important; font-weight: 600 !important; font-size: 13px !important; color: #444 !important;">Giá khuyến mãi</th>
                                            <th style="background: #f9f9f9 !important; border-bottom: 1px solid #ccd0d4 !important; padding: 12px 15px !important; text-align: left !important; font-weight: 600 !important; font-size: 13px !important; color: #444 !important;">Tồn kho</th>
                                            <th style="background: #f9f9f9 !important; border-bottom: 1px solid #ccd0d4 !important; padding: 12px 15px !important; text-align: left !important; font-weight: 600 !important; font-size: 13px !important; color: #444 !important;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $variant)
                                            <tr style="border-bottom: 1px solid #ccd0d4 !important;">
                                                <td style="padding: 12px 15px !important; vertical-align: top !important; font-size: 13px !important;">
                                                    @if($variant->image_variant)
                                                        @php
                                                            $images = json_decode($variant->image_variant, true);
                                                            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : $variant->image_variant;
                                                        @endphp
                                                        <div class="wp-product-variant-image" style="width: 60px !important; height: 60px !important; border-radius: 3px !important; overflow: hidden !important; border: 1px solid #ccd0d4 !important;">
                                                            <img src="{{ asset('storage/' . $firstImage) }}" 
                                                                 alt="{{ $variant->variant_name }}"
                                                                 style="width: 100% !important; height: 100% !important; object-fit: cover !important; cursor: pointer !important;"
                                                                 onclick="showImageModal('{{ asset('storage/' . $firstImage) }}')">
                                                        </div>
                                                    @else
                                                        <div class="wp-product-variant-no-image" style="width: 60px !important; height: 60px !important; background: #f9f9f9 !important; border: 2px dashed #ccd0d4 !important; border-radius: 3px !important; display: flex !important; align-items: center !important; justify-content: center !important; color: #666 !important;">
                                                            <i class="fa fa-image" style="font-size: 20px !important; opacity: 0.5 !important;"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td style="padding: 12px 15px !important; vertical-align: top !important; font-size: 13px !important;">
                                                    <strong>{{ $variant->variant_name }}</strong>
                                                    <div class="wp-product-variant-attributes" style="margin-top: 5px !important;">
                                                        @if($variant->storage)
                                                            <span class="wp-product-attribute storage" style="display: inline-flex !important; align-items: center !important; gap: 4px !important; padding: 2px 8px !important; border-radius: 12px !important; font-size: 11px !important; font-weight: 600 !important; margin-right: 5px !important; background: rgba(0, 160, 210, 0.1) !important; color: #00a0d2 !important;">
                                                                <i class="fa fa-hdd-o"></i>
                                                                {{ \App\Helpers\StorageHelper::formatCapacity($variant->storage->capacity) }}
                                                            </span>
                                                        @endif
                                                        @if($variant->color)
                                                            <span class="wp-product-attribute color" style="display: inline-flex !important; align-items: center !important; gap: 4px !important; padding: 2px 8px !important; border-radius: 12px !important; font-size: 11px !important; font-weight: 600 !important; margin-right: 5px !important; background: rgba(255, 185, 0, 0.1) !important; color: #ffb900 !important;">
                                                                <i class="fa fa-palette"></i>
                                                                {{ $variant->color->name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td style="padding: 12px 15px !important; vertical-align: top !important; font-size: 13px !important;">
                                                    <span class="wp-product-price" style="font-weight: 600 !important; color: #46b450 !important;">{{ number_format($variant->price, 0, ',', '.') }} VND</span>
                                                </td>
                                                <td style="padding: 12px 15px !important; vertical-align: top !important; font-size: 13px !important;">
                                                    @if($variant->promotion_price)
                                                        <span class="wp-product-promotion-price" style="font-weight: 600 !important; color: #dc3232 !important;">{{ number_format($variant->promotion_price, 0, ',', '.') }} VND</span>
                                                    @else
                                                        <span class="wp-product-no-promotion" style="color: #666 !important; font-style: italic !important;">Không có</span>
                                                    @endif
                                                </td>
                                                <td style="padding: 12px 15px !important; vertical-align: top !important; font-size: 13px !important;">
                                                    <span class="wp-product-stock {{ $variant->stock_quantity > 0 ? 'in-stock' : 'out-of-stock' }}" style="font-weight: 600 !important; {{ $variant->stock_quantity > 0 ? 'color: #46b450 !important;' : 'color: #dc3232 !important;' }}">
                                                        {{ $variant->stock_quantity }}
                                                    </span>
                                                </td>
                                                <td style="padding: 12px 15px !important; vertical-align: top !important; font-size: 13px !important;">
                                                    <div class="wp-product-row-actions" style="font-size: 12px !important;">
                                                        <a href="{{ route('admin.product.product-variant.edit', $variant->id) }}" 
                                                           class="wp-product-action-edit" style="background: none !important; border: none !important; color: #0073aa !important; text-decoration: none !important; cursor: pointer !important; padding: 0 !important; font-size: 12px !important; display: inline-flex !important; align-items: center !important; gap: 3px !important;">
                                                            <i class="fa fa-edit"></i>
                                                            Chỉnh sửa
                                                        </a>
                                                        <span class="separator" style="margin: 0 5px !important; color: #666 !important;">|</span>
                                                        <button type="button" class="wp-product-action-delete" 
                                                                onclick="window.deleteVariant({{ $variant->id }})" style="background: none !important; border: none !important; color: #dc3232 !important; text-decoration: none !important; cursor: pointer !important; padding: 0 !important; font-size: 12px !important; display: inline-flex !important; align-items: center !important; gap: 3px !important;">
                                                            <i class="fa fa-trash"></i>
                                                            Xóa
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="wp-product-card" style="background: #fff !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; margin-bottom: 20px !important; box-shadow: 0 1px 1px rgba(0,0,0,.04) !important;">
                        <div class="wp-product-card-body" style="padding: 20px !important;">
                            <div class="wp-product-empty-state" style="text-align: center !important; padding: 40px 20px !important; color: #666 !important;">
                                <i class="fa fa-list" style="font-size: 64px !important; margin-bottom: 20px !important; opacity: 0.5 !important;"></i>
                                <h3 style="font-size: 20px !important; font-weight: 600 !important; margin: 0 0 10px 0 !important; color: #444 !important;">Chưa có biến thể</h3>
                                <p style="margin: 0 0 20px 0 !important; font-size: 14px !important;">Sản phẩm này chưa có biến thể nào được cấu hình.</p>
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}" 
                                   class="wp-product-btn wp-product-btn-primary" style="display: inline-flex !important; align-items: center !important; gap: 5px !important; padding: 8px 16px !important; border: 1px solid #0073aa !important; border-radius: 3px !important; background: #0073aa !important; color: #fff !important; text-decoration: none !important; font-size: 13px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important; line-height: normal !important;">
                                    <i class="fa fa-plus"></i>
                                    Tạo biến thể đầu tiên
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Sidebar -->
            <div class="wp-product-sidebar" style="min-width: 0 !important;">
                <!-- Quick Actions -->
                <div class="wp-product-card" style="background: #fff !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; margin-bottom: 20px !important; box-shadow: 0 1px 1px rgba(0,0,0,.04) !important;">
                    <div class="wp-product-card-header" style="border-bottom: 1px solid #ccd0d4 !important; padding: 15px 20px !important; display: flex !important; justify-content: space-between !important; align-items: center !important; background: #f9f9f9 !important;">
                        <h3 class="wp-product-card-title" style="font-size: 16px !important; font-weight: 600 !important; margin: 0 !important; color: #444 !important; display: flex !important; align-items: center !important; gap: 8px !important;">
                            <i class="fa fa-cogs"></i>
                            Thao tác nhanh
                        </h3>
                    </div>
                    <div class="wp-product-card-body" style="padding: 20px !important;">
                        <div class="wp-product-button-group" style="display: flex !important; flex-direction: column !important; gap: 10px !important;">
                            <a href="{{ route('admin.product.edit-product', $product->id) }}" 
                               class="wp-product-btn wp-product-btn-primary wp-product-btn-large" style="display: inline-flex !important; align-items: center !important; gap: 5px !important; padding: 12px 20px !important; border: 1px solid #0073aa !important; border-radius: 3px !important; background: #0073aa !important; color: #fff !important; text-decoration: none !important; font-size: 14px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important; line-height: normal !important;">
                                <i class="fa fa-edit"></i>
                                Chỉnh sửa sản phẩm
                            </a>
                            
                            @if($product->variants->count() == 0)
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}" 
                                   class="wp-product-btn wp-product-btn-secondary wp-product-btn-large" style="display: inline-flex !important; align-items: center !important; gap: 5px !important; padding: 12px 20px !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; background: #f7f7f7 !important; color: #444 !important; text-decoration: none !important; font-size: 14px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important; line-height: normal !important;">
                                    <i class="fa fa-plus"></i>
                                    Thêm biến thể
                                </a>
                            @endif
                            
                            <a href="{{ route('admin.product.product-variant.trash') }}" 
                               class="wp-product-btn wp-product-btn-secondary wp-product-btn-large" style="display: inline-flex !important; align-items: center !important; gap: 5px !important; padding: 12px 20px !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; background: #f7f7f7 !important; color: #444 !important; text-decoration: none !important; font-size: 14px !important; font-weight: 600 !important; cursor: pointer !important; transition: all 0.15s ease-in-out !important; line-height: normal !important;">
                                <i class="fa fa-recycle"></i>
                                Khôi phục biến thể
                            </a>
                            
                            
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="wp-product-card" style="background: #fff !important; border: 1px solid #ccd0d4 !important; border-radius: 3px !important; margin-bottom: 20px !important; box-shadow: 0 1px 1px rgba(0,0,0,.04) !important;">
                    <div class="wp-product-card-header" style="border-bottom: 1px solid #ccd0d4 !important; padding: 15px 20px !important; display: flex !important; justify-content: space-between !important; align-items: center !important; background: #f9f9f9 !important;">
                        <h3 class="wp-product-card-title" style="font-size: 16px !important; font-weight: 600 !important; margin: 0 !important; color: #444 !important; display: flex !important; align-items: center !important; gap: 8px !important;">
                            <i class="fa fa-info-circle"></i>
                            Thông tin chi tiết
                        </h3>
                    </div>
                    <div class="wp-product-card-body" style="padding: 20px !important;">
                        <div class="wp-product-meta-list" style="display: flex !important; flex-direction: column !important; gap: 15px !important;">
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-barcode" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Mã sản phẩm:</strong>
                                <span style="color: #444 !important;">#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-tag" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Danh mục:</strong>
                                <span style="color: #444 !important;">{{ $product->category->name }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-check-circle" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Trạng thái:</strong>
                                <span class="wp-product-status {{ $product->status ? 'status-active' : 'status-inactive' }}" style="padding: 4px 8px !important; border-radius: 12px !important; font-size: 11px !important; font-weight: 600 !important; text-transform: uppercase !important; {{ $product->status ? 'background: rgba(70, 180, 80, 0.1) !important; color: #46b450 !important;' : 'background: rgba(220, 50, 50, 0.1) !important; color: #dc3232 !important;' }}">
                                    {{ $product->status ? 'Đang hoạt động' : 'Tạm ngưng' }}
                                </span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-cubes" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Số biến thể:</strong>
                                <span style="color: #444 !important;">{{ $product->variants->count() }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-cubes" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Tổng tồn kho:</strong>
                                <span style="color: {{ $product->variants->sum('stock_quantity') > 0 ? '#46b450' : '#dc3232' }} !important;">{{ number_format($product->variants->sum('stock_quantity')) }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-shopping-cart" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Đã bán:</strong>
                                <span style="color: #0073aa !important;">{{ $product->orderDetails->sum('quantity') }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-star" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Đánh giá:</strong>
                                <span style="color: #ffb900 !important;">{{ $product->reviews->count() }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-heart" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Yêu thích:</strong>
                                <span style="color: #dc3232 !important;">{{ $product->favorites->count() }}</span>
                            </div>
                            
                            @if($product->promotion_price)
                                <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                    <i class="fa fa-tags" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                    <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Tiết kiệm:</strong>
                                    <span style="color: #ffb900 !important;">{{ number_format($product->price - $product->promotion_price, 0, ',', '.') }} VND</span>
                                </div>
                                
                                <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                    <i class="fa fa-percent" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                    <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">% Giảm giá:</strong>
                                    <span style="color: #dc3232 !important;">{{ round((($product->price - $product->promotion_price) / $product->price) * 100, 1) }}%</span>
                                </div>
                            @endif
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-eye" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Lượt xem:</strong>
                                <span style="color: #444 !important;">{{ number_format($product->view) }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-calendar" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Ngày tạo:</strong>
                                <span style="color: #444 !important;">{{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 15px !important; border-bottom: 1px solid #ccd0d4 !important;">
                                <i class="fa fa-clock-o" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Cập nhật cuối:</strong>
                                <span style="color: #444 !important;">{{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>
                            
                            <div class="wp-product-meta-item" style="display: flex !important; align-items: flex-start !important; gap: 8px !important; padding-bottom: 0 !important; border-bottom: none !important;">
                                <i class="fa fa-history" style="color: #666 !important; font-size: 16px !important; margin-top: 2px !important; width: 16px !important;"></i>
                                <strong style="font-weight: 600 !important; color: #444 !important; min-width: 80px !important;">Thời gian tồn tại:</strong>
                                <span style="color: #444 !important;">{{ $product->created_at ? $product->created_at->diffForHumans() : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="wp-product-modal" id="imageModal" tabindex="-1" role="dialog" style="display: none !important; position: fixed !important; z-index: 100000 !important; left: 0 !important; top: 0 !important; width: 100% !important; height: 100% !important; background-color: rgba(0, 0, 0, 0.5) !important;">
        <div class="wp-product-modal-content" style="background: #fff !important; border-radius: 3px !important; box-shadow: 0 1px 3px rgba(0,0,0,.13) !important; max-width: 90% !important; max-height: 90% !important; overflow: hidden !important;">
            <div class="wp-product-modal-header" style="background: #f9f9f9 !important; border-bottom: 1px solid #ccd0d4 !important; padding: 15px 20px !important; display: flex !important; justify-content: space-between !important; align-items: center !important;">
                <h3 class="wp-product-modal-title" style="font-size: 16px !important; font-weight: 600 !important; margin: 0 !important; color: #444 !important; display: flex !important; align-items: center !important; gap: 8px !important;">
                    <i class="fa fa-image"></i>
                    Xem hình ảnh
                </h3>
                <button type="button" class="wp-product-modal-close" data-dismiss="modal" style="background: none !important; border: none !important; font-size: 20px !important; cursor: pointer !important; color: #666 !important; padding: 0 !important; width: 30px !important; height: 30px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 3px !important;">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="wp-product-modal-body" style="padding: 20px !important; text-align: center !important;">
                <img id="modalImage" src="" alt="Hình ảnh sản phẩm" class="wp-product-modal-image" style="max-width: 100% !important; max-height: 70vh !important; border-radius: 3px !important; box-shadow: 0 1px 1px rgba(0,0,0,.04) !important;">
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="delete-form" action="{{ route('admin.product.delete', $product->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>

        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            const modal = document.getElementById('imageModal');
            modal.style.display = 'flex';
        }

        // Handle image clicks
        $(document).on('click', '.wp-product-main-image', function() {
            const imageUrl = $(this).data('image-url');
            showImageModal(imageUrl);
        });

        // Close modal when clicking outside
        $(document).on('click', '.wp-product-modal', function(e) {
            if (e.target === this) {
                $(this).css('display', 'none');
            }
        });

        // Close modal with close button
        $(document).on('click', '.wp-product-modal-close', function() {
            $(this).closest('.wp-product-modal').css('display', 'none');
        });

        // Enhanced tooltips
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Add loading states to buttons
        $(document).on('click', '.wp-product-btn', function() {
            if (!$(this).hasClass('loading')) {
                $(this).addClass('loading');
                setTimeout(() => {
                    $(this).removeClass('loading');
                }, 1000);
            }
        });

        // WordPress-style interactions
        $(document).ready(function() {
            // Add hover effects
            $('.wp-product-card').hover(
                function() {
                    $(this).addClass('hovered');
                },
                function() {
                    $(this).removeClass('hovered');
                }
            );
        });
    </script>
@endpush
