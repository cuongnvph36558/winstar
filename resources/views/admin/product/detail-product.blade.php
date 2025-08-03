@extends('layouts.admin')
@section('title', 'Chi tiết sản phẩm')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2><i class="fa fa-cube text-primary"></i> Chi tiết sản phẩm</h2>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i> Trang chủ</a></li>
                <li><a href="{{ route('admin.product.index-product') }}"><i class="fa fa-list"></i> Danh sách sản phẩm</a></li>
                <li class="active"><strong><i class="fa fa-eye"></i> Chi tiết</strong></li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action" style="margin-top: 20px;">
                <a href="{{ route('admin.product.edit-product', $product->id) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.product.product-variant.trash') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-recycle"></i> Khôi phục biến thể
                </a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <!-- Main Product Information -->
            <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-info-circle"></i> Thông tin sản phẩm</h5>
                        <div class="ibox-tools">
                            <span class="label {{ $product->status ? 'label-primary' : 'label-default' }}">
                                <i class="fa {{ $product->status ? 'fa-check' : 'fa-times' }}"></i>
                                {{ $product->status ? 'Đang hoạt động' : 'Tạm ngưng' }}
                            </span>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-4">
                                @if($product->image)
                                    <div class="product-image-main text-center">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="img-responsive product-main-image"
                                            data-image-url="{{ asset('storage/' . $product->image) }}">
                                    </div>
                                @else
                                    <div class="text-center p-4 bg-light border rounded no-image-placeholder">
                                        <i class="fa fa-image fa-3x text-muted"></i>
                                        <p class="text-muted mt-2">Chưa có hình ảnh</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h2 class="product-title font-bold text-navy m-b-md">{{ $product->name }}</h2>

                                <div class="row m-b-md">
                                    <div class="col-sm-4">
                                        <div class="stat-box text-center">
                                            <i class="fa fa-eye text-info fa-2x"></i>
                                            <h4 class="font-bold text-info m-t-xs">{{ number_format($product->view) }}</h4>
                                            <small class="text-muted">Lượt xem</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="stat-box text-center">
                                            <i class="fa fa-list text-success fa-2x"></i>
                                            <h4 class="font-bold text-success m-t-xs">{{ $product->variants->count() }}</h4>
                                            <small class="text-muted">Biến thể</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="stat-box text-center">
                                            <i class="fa fa-tag text-warning fa-2x"></i>
                                            <h4 class="font-bold text-warning m-t-xs">{{ $product->category->name }}</h4>
                                            <small class="text-muted">Danh mục</small>
                                        </div>
                                    </div>
                                </div>

                                @if($product->description)
                                    <div class="m-t-lg">
                                        <h5 class="text-navy font-bold"><i class="fa fa-file-text"></i> Mô tả sản phẩm</h5>
                                        <p class="text-muted">{{ $product->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variants List -->
                @if($product->variants->count() > 0)
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5><i class="fa fa-list"></i> Biến thể sản phẩm ({{ $product->variants->count() }})</h5>
                            <div class="ibox-tools">
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}"
                                    class="btn btn-success btn-xs">
                                    <i class="fa fa-plus"></i> Thêm biến thể
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            @foreach($product->variants as $index => $variant)
                                <div class="product-variant {{ $index > 0 ? 'border-top m-t-lg p-t-lg' : '' }}">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <h4 class="font-bold text-navy m-b-sm">{{ $variant->variant_name }}</h4>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <small class="text-muted"><i class="fa fa-money"></i> Giá bán</small>
                                                            <h5 class="font-bold text-success">
                                                                {{ number_format($variant->price, 0, ',', '.') }} VND
                                                                </h5>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <small class="text-muted"><i class="fa fa-tags"></i> Giá khuyến mãi</small>
                                                            <h5 class="font-bold text-success">
                                                                {{ number_format($variant->promotion_price ?? 0, 0, ',', '.') }} VND
                                                            </h5>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <small class="text-muted"><i class="fa fa-cubes"></i> Tồn kho</small>
                                                            <h5
                                                                class="font-bold {{ $variant->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $variant->stock_quantity }}
                                                            </h5>
                                                        </div>
                                                    </div>

                                                    <div class="row m-t-sm">
                                                        @if($variant->storage)
                                                            <div class="col-sm-6">
                                                                <small class="text-muted"><i class="fa fa-hdd-o"></i> Dung lượng</small>
                                                                <span
                                                                    class="label label-info">{{ $variant->storage->capacity }}GB</span>
                                                            </div>
                                                        @endif
                                                        @if($variant->color)
                                                            <div class="col-sm-6">
                                                                <small class="text-muted"><i class="fa fa-palette"></i> Màu sắc</small>
                                                                <span class="label label-default">{{ $variant->color->name }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="btn-group-vertical btn-block">
                                                        <a href="{{ route('admin.product.product-variant.edit', $variant->id) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i> Chỉnh sửa
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.product.product-variant.delete', $variant->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm btn-block">
                                                                <i class="fa fa-trash"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            @if($variant->image_variant)
                                                @php
                                                    $images = is_string($variant->image_variant) ? json_decode($variant->image_variant, true) : $variant->image_variant;
                                                @endphp
                                                @if($images && is_array($images) && count($images) > 0)
                                                    <div class="variant-images">
                                                        <small class="text-muted"><i class="fa fa-images"></i> Hình ảnh biến thể</small>
                                                        <div class="image-gallery m-t-xs">
                                                            @foreach(array_slice($images, 0, 4) as $imageIndex => $image)
                                                                @if($image)
                                                                    <div class="gallery-item {{ $imageIndex >= 2 ? 'm-t-xs' : '' }}"
                                                                        style="display: inline-block; width: 48%; margin-right: 2%;">
                                                                        <img src="{{ asset('storage/' . $image) }}" alt="Variant Image"
                                                                            class="img-responsive img-thumbnail image-clickable"
                                                                            data-image-url="{{ asset('storage/' . $image) }}">
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if(count($images) > 4)
                                                                <div class="gallery-item m-t-xs" style="display: inline-block; width: 48%;">
                                                                    <div class="text-center bg-light border rounded"
                                                                        style="height: 60px; line-height: 60px;">
                                                                        <small class="text-muted">+{{ count($images) - 4 }} ảnh khác</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center p-3 bg-light border rounded">
                                                    <i class="fa fa-image text-muted fa-2x"></i>
                                                    <p class="text-muted small m-t-xs">Chưa có hình ảnh</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="alert alert-warning text-center">
                                <i class="fa fa-exclamation-triangle fa-3x text-warning"></i>
                                <h4 class="m-t-md">Chưa có biến thể</h4>
                                <p class="text-muted">Sản phẩm này chưa có biến thể nào được cấu hình.</p>
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}"
                                    class="btn btn-success btn-lg m-t-md">
                                    <i class="fa fa-plus"></i> Tạo biến thể đầu tiên
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Meta Information and Actions -->
            <div class="col-lg-4">
                <!-- Product Details -->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-info-circle"></i> Chi tiết sản phẩm</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="profile-info">
                            <div class="info-item m-b-md">
                                <small class="text-muted"><i class="fa fa-barcode"></i> Mã sản phẩm</small>
                                <h5 class="font-bold text-navy">#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</h5>
                            </div>

                            <div class="info-item m-b-md">
                                <small class="text-muted"><i class="fa fa-tag"></i> Danh mục</small>
                                <h5 class="font-bold">
                                    <span class="label label-info">{{ $product->category->name }}</span>
                                </h5>
                            </div>

                            <div class="info-item m-b-md">
                                <small class="text-muted"><i class="fa fa-toggle-on"></i> Trạng thái</small>
                                <h5 class="font-bold">
                                    <span class="label {{ $product->status ? 'label-primary' : 'label-default' }}">
                                        <i class="fa {{ $product->status ? 'fa-check' : 'fa-times' }}"></i>
                                        {{ $product->status ? 'Đang hoạt động' : 'Tạm ngưng' }}
                                    </span>
                                </h5>
                            </div>

                            <div class="info-item m-b-md">
                                <small class="text-muted"><i class="fa fa-eye"></i> Tổng lượt xem</small>
                                <h5 class="font-bold text-info">{{ number_format($product->view) }}</h5>
                            </div>

                            <hr class="hr-line-dashed">

                            <div class="info-item m-b-md">
                                <small class="text-muted"><i class="fa fa-calendar-plus-o"></i> Ngày tạo</small>
                                <h5 class="font-bold">{{ $product->created_at->format('d/m/Y') }}</h5>
                                <small class="text-muted">{{ $product->created_at->format('H:i') }}</small>
                            </div>

                            <div class="info-item">
                                <small class="text-muted"><i class="fa fa-calendar-check-o"></i> Cập nhật lần cuối</small>
                                <h5 class="font-bold">{{ $product->updated_at->format('d/m/Y') }}</h5>
                                <small class="text-muted">{{ $product->updated_at->format('H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-cogs"></i> Thao tác</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="btn-group-vertical btn-block">
                            <a href="{{ route('admin.product.index-product') }}" class="btn btn-default btn-lg">
                                <i class="fa fa-arrow-left"></i> Quay lại danh sách
                            </a>
                            <a href="{{ route('admin.product.edit-product', $product->id) }}"
                                class="btn btn-primary btn-lg">
                                <i class="fa fa-edit"></i> Chỉnh sửa sản phẩm
                            </a>
                            @if($product->variants->count() == 0)
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}"
                                    class="btn btn-success btn-lg">
                                    <i class="fa fa-plus"></i> Thêm biến thể
                                </a>
                            @endif
                            <button type="button" class="btn btn-danger btn-lg" onclick="confirmDelete()">
                                <i class="fa fa-trash"></i> Xóa sản phẩm
                            </button>
                        </div>

                        <form id="delete-form" action="{{ route('admin.product.delete', $product->id) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-image"></i> Xem hình ảnh</h4>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Hình ảnh sản phẩm" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Enhanced Product Image Styling */
        .product-main-image {
            max-width: 100%;
            max-height: 280px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 4px solid #fff;
            cursor: pointer;
            position: relative;
        }

        .product-main-image:hover {
            transform: scale(1.08) translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border-color: #1ab394;
        }

        .product-main-image::after {
            content: '🔍 Xem ảnh';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .product-main-image:hover::after {
            opacity: 1;
        }

        .no-image-placeholder {
            border-radius: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 3px dashed #dee2e6;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .no-image-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(26, 179, 148, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .no-image-placeholder:hover {
            background: linear-gradient(135deg, #e9ecef, #dee2e6);
            border-color: #1ab394;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .no-image-placeholder:hover::before {
            left: 100%;
        }

        /* Enhanced Stat Boxes */
        .stat-box {
            padding: 25px 20px;
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            margin-bottom: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid #e9ecef;
            height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1ab394, #13855c);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-box::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26, 179, 148, 0.05), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: #1ab394;
        }

        .stat-box:hover::before {
            transform: scaleX(1);
        }

        .stat-box:hover::after {
            opacity: 1;
        }

        .stat-box i {
            margin-bottom: 12px;
            transition: all 0.3s ease;
            font-size: 2.2em;
        }

        .stat-box:hover i {
            transform: scale(1.2) rotate(5deg);
        }

        .stat-box h4 {
            font-size: 1.8em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-box small {
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Enhanced Product Variants */
        .product-variant {
            padding: 30px;
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid #e3e6f0;
            margin-bottom: 25px;
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            position: relative;
            overflow: hidden;
        }

        .product-variant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #1ab394, #13855c);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .product-variant:hover {
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            border-color: #1ab394;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .product-variant:hover::before {
            transform: scaleY(1);
        }

        /* Enhanced Image Gallery */
        .image-gallery {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fff;
        }

        .gallery-item:hover {
            border-color: #1ab394;
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            display: block;
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .gallery-item img:hover {
            opacity: 0.9;
            filter: brightness(1.1) contrast(1.1);
        }

        /* Enhanced Profile Info */
        .profile-info .info-item {
            border-left: 4px solid #1ab394;
            padding: 20px 25px;
            margin-bottom: 25px;
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 0 12px 12px 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .profile-info .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26, 179, 148, 0.05), rgba(19, 133, 92, 0.05));
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .profile-info .info-item:hover::before {
            transform: translateX(0);
        }

        .profile-info .info-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .hr-line-dashed {
            border-top: 2px dashed #e7eaec;
            margin: 30px 0;
            position: relative;
        }

        .hr-line-dashed::after {
            content: '';
            position: absolute;
            top: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 2px;
            background: #1ab394;
            border-radius: 1px;
        }

        /* Enhanced Buttons */
        .btn-group-vertical .btn {
            margin-bottom: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .btn-group-vertical .btn:last-child {
            margin-bottom: 0;
        }

        .btn-group-vertical .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-group-vertical .btn:hover::before {
            left: 100%;
        }

        .btn-group-vertical .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        /* Enhanced Cards */
        .ibox {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 2px solid #e3e6f0;
            margin-bottom: 35px;
            transition: all 0.3s ease;
        }

        .ibox:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .ibox-title {
            background: linear-gradient(135deg, #1ab394, #13855c);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }

        .ibox-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .ibox-title:hover::before {
            transform: translateX(0);
        }

        .ibox-title h5 {
            color: white;
            margin: 0;
            font-weight: 700;
            font-size: 16px;
        }

        .ibox-content {
            padding: 30px;
        }

        /* Enhanced Labels */
        .label {
            font-size: 12px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .label-info {
            background: linear-gradient(135deg, #5bc0de, #31b0d5);
            color: white;
        }

        .label-primary {
            background: linear-gradient(135deg, #337ab7, #286090);
            color: white;
        }

        .label-default {
            background: linear-gradient(135deg, #777, #555);
            color: white;
        }

        /* Enhanced Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #1ab394, #13855c);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 20px 25px;
        }

        .modal-title {
            color: white;
            font-weight: 700;
        }

        .close {
            color: white;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .close:hover {
            color: white;
            opacity: 1;
            transform: scale(1.1);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-body img {
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .col-md-4,
            .col-md-8 {
                margin-bottom: 25px;
            }

            .stat-box {
                height: 100px;
                padding: 20px 15px;
            }

            .product-variant {
                padding: 20px;
            }

            .image-gallery {
                grid-template-columns: 1fr;
            }

            .gallery-item img {
                height: 120px;
            }

            .ibox-content {
                padding: 20px;
            }

            .profile-info .info-item {
                padding: 15px 20px;
            }
        }

        /* Animation Classes */
        .animated {
            animation-duration: 0.6s;
            animation-fill-mode: both;
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

        .fadeInUp {
            animation-name: fadeInUp;
        }

        /* Loading States */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.')) {
                document.getElementById('delete-form').submit();
            }
        }

        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            $('#imageModal').modal('show');
        }

        // Handle image clicks with data attributes
        $(document).on('click', '.image-clickable', function () {
            const imageUrl = $(this).data('image-url');
            showImageModal(imageUrl);
        });

        // Handle main product image click
        $(document).on('click', '.product-main-image', function () {
            const imageUrl = $(this).data('image-url');
            showImageModal(imageUrl);
        });

        // Enhanced tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Add loading states to buttons
        $(document).on('click', '.btn', function() {
            if (!$(this).hasClass('btn-loading')) {
                $(this).addClass('loading');
                setTimeout(() => {
                    $(this).removeClass('loading');
                }, 1000);
            }
        });

        // Smooth scroll animations
        $(document).ready(function() {
            $('.ibox').addClass('animated fadeInUp');
            
            // Stagger animation for stat boxes
            $('.stat-box').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });
    </script>
@endpush