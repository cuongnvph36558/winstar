@extends('layouts.admin')
@section('title', 'Product Details')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Chi tiết sản phẩm</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="{{ route('admin.product.index-product') }}">Product</a></li>
                <li class="active"><strong>Detail</strong></li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action" style="margin-top: 20px;">
                <a href="{{ route('admin.product.edit-product', $product->id) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit Product
                </a>
                <a href="{{ route('admin.product.product-variant.trash') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-recycle"></i> Restore Variants
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
                        <h5><i class="fa fa-cube"></i> Product Information</h5>
                        <div class="ibox-tools">
                            <span class="label {{ $product->status ? 'label-primary' : 'label-default' }}">
                                <i class="fa {{ $product->status ? 'fa-check' : 'fa-times' }}"></i>
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-4">
                                @if($product->image)
                                    <div class="product-image-main text-center">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="img-responsive"
                                            style="max-width: 100%; max-height: 250px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    </div>
                                @else
                                    <div class="text-center p-4 bg-light border rounded">
                                        <i class="fa fa-image fa-3x text-muted"></i>
                                        <p class="text-muted mt-2">No image available</p>
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
                                            <small class="text-muted">Views</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="stat-box text-center">
                                            <i class="fa fa-list text-success fa-2x"></i>
                                            <h4 class="font-bold text-success m-t-xs">{{ $product->variants->count() }}</h4>
                                            <small class="text-muted">Variants</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="stat-box text-center">
                                            <i class="fa fa-tag text-warning fa-2x"></i>
                                            <h4 class="font-bold text-warning m-t-xs">{{ $product->category->name }}</h4>
                                            <small class="text-muted">Category</small>
                                        </div>
                                    </div>
                                </div>

                                @if($product->description)
                                    <div class="m-t-lg">
                                        <h5 class="text-navy font-bold">Product Description</h5>
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
                            <h5><i class="fa fa-list"></i> Product Variants ({{ $product->variants->count() }})</h5>
                            <div class="ibox-tools">
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}"
                                    class="btn btn-success btn-xs">
                                    <i class="fa fa-plus"></i> Add Variant
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
                                                            <small class="text-muted">Price</small>
                                                            <h5 class="font-bold text-success">
                                                                {{ number_format($variant->price, 0, ',', '.') }} VND
                                                            </h5>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <small class="text-muted">Stock</small>
                                                            <h5
                                                                class="font-bold {{ $variant->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $variant->stock_quantity }}
                                                            </h5>
                                                        </div>
                                                    </div>

                                                    <div class="row m-t-sm">
                                                        @if($variant->storage)
                                                            <div class="col-sm-6">
                                                                <small class="text-muted">Storage</small>
                                                                <span
                                                                    class="label label-info">{{ $variant->storage->capacity }}GB</span>
                                                            </div>
                                                        @endif
                                                        @if($variant->color)
                                                            <div class="col-sm-6">
                                                                <small class="text-muted">Color</small>
                                                                <span class="label label-default">{{ $variant->color->name }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="btn-group-vertical btn-block">
                                                        <a href="{{ route('admin.product.product-variant.edit', $variant->id) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.product.product-variant.delete', $variant->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this variant?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm btn-block">
                                                                <i class="fa fa-trash"></i> Delete
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
                                                        <small class="text-muted">Variant Images</small>
                                                        <div class="image-gallery m-t-xs">
                                                            @foreach(array_slice($images, 0, 4) as $imageIndex => $image)
                                                                @if($image)
                                                                    <div class="gallery-item {{ $imageIndex >= 2 ? 'm-t-xs' : '' }}"
                                                                        style="display: inline-block; width: 48%; margin-right: 2%;">
                                                                        <img src="{{ asset('storage/' . $image) }}" alt="Variant Image"
                                                                            class="img-responsive img-thumbnail image-clickable"
                                                                            style="height: 60px; width: 100%; object-fit: cover; cursor: pointer;"
                                                                            data-image-url="{{ asset('storage/' . $image) }}">
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if(count($images) > 4)
                                                                <div class="gallery-item m-t-xs" style="display: inline-block; width: 48%;">
                                                                    <div class="text-center bg-light border rounded"
                                                                        style="height: 60px; line-height: 60px;">
                                                                        <small class="text-muted">+{{ count($images) - 4 }} more</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center p-3 bg-light border rounded">
                                                    <i class="fa fa-image text-muted fa-2x"></i>
                                                    <p class="text-muted small m-t-xs">No variant images</p>
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
                                <h4 class="m-t-md">No Variants Available</h4>
                                <p class="text-muted">This product doesn't have any variants configured yet.</p>
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}"
                                    class="btn btn-success btn-lg m-t-md">
                                    <i class="fa fa-plus"></i> Create First Variant
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
                        <h5><i class="fa fa-info-circle"></i> Product Details</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="profile-info">
                            <div class="info-item m-b-md">
                                <small class="text-muted">Product ID</small>
                                <h5 class="font-bold text-navy">#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</h5>
                            </div>

                            <div class="info-item m-b-md">
                                <small class="text-muted">Category</small>
                                <h5 class="font-bold">
                                    <span class="label label-info">{{ $product->category->name }}</span>
                                </h5>
                            </div>

                            <div class="info-item m-b-md">
                                <small class="text-muted">Status</small>
                                <h5 class="font-bold">
                                    <span class="label {{ $product->status ? 'label-primary' : 'label-default' }}">
                                        <i class="fa {{ $product->status ? 'fa-check' : 'fa-times' }}"></i>
                                        {{ $product->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </h5>
                            </div>

                            <div class="info-item m-b-md">
                                <small class="text-muted">Total Views</small>
                                <h5 class="font-bold text-info">{{ number_format($product->view) }}</h5>
                            </div>

                            <hr class="hr-line-dashed">

                            <div class="info-item m-b-md">
                                <small class="text-muted">Created Date</small>
                                <h5 class="font-bold">{{ $product->created_at->format('M d, Y') }}</h5>
                                <small class="text-muted">{{ $product->created_at->format('H:i A') }}</small>
                            </div>

                            <div class="info-item">
                                <small class="text-muted">Last Updated</small>
                                <h5 class="font-bold">{{ $product->updated_at->format('M d, Y') }}</h5>
                                <small class="text-muted">{{ $product->updated_at->format('H:i A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-cogs"></i> Actions</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="btn-group-vertical btn-block">
                            <a href="{{ route('admin.product.index-product') }}" class="btn btn-default btn-lg">
                                <i class="fa fa-arrow-left"></i> Back to Products
                            </a>
                            <a href="{{ route('admin.product.edit-product', $product->id) }}"
                                class="btn btn-primary btn-lg">
                                <i class="fa fa-edit"></i> Edit Product
                            </a>
                            @if($product->variants->count() == 0)
                                <a href="{{ route('admin.product.product-variant.create', $product->id) }}"
                                    class="btn btn-success btn-lg">
                                    <i class="fa fa-plus"></i> Add Variant
                                </a>
                            @endif
                            <button type="button" class="btn btn-danger btn-lg" onclick="confirmDelete()">
                                <i class="fa fa-trash"></i> Delete Product
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
                    <h4 class="modal-title">View Image</h4>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Product Image" class="img-responsive"
                        style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Enhanced styling for better display */
        .product-image-main img {
            width: 100%;
            height: auto;
            max-height: 280px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-image-main img:hover {
            transform: scale(1.02);
        }

        .stat-box {
            padding: 20px;
            background: linear-gradient(145deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-box:hover {
            background: linear-gradient(145deg, #e9ecef, #dee2e6);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-variant {
            padding: 25px;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid #e3e6f0;
            margin-bottom: 20px;
            background: #fff;
        }

        .product-variant:hover {
            background: #f8f9fa;
            border-color: #1ab394;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .image-gallery {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 10px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            border-color: #1ab394;
            transform: scale(1.02);
        }

        .gallery-item img {
            width: 100%;
            height: 70px;
            object-fit: cover;
            display: block;
            transition: all 0.3s ease;
        }

        .gallery-item img:hover {
            opacity: 0.9;
            filter: brightness(1.1);
        }

        .profile-info .info-item {
            border-left: 4px solid #1ab394;
            padding-left: 20px;
            margin-bottom: 20px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .hr-line-dashed {
            border-top: 2px dashed #e7eaec;
            margin: 25px 0;
        }

        .btn-group-vertical .btn {
            margin-bottom: 10px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-group-vertical .btn:last-child {
            margin-bottom: 0;
        }

        .btn-group-vertical .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Responsive improvements */
        @media (max-width: 768px) {

            .col-md-4,
            .col-md-8 {
                margin-bottom: 20px;
            }

            .stat-box {
                height: 80px;
                padding: 15px;
            }

            .product-variant {
                padding: 15px;
            }

            .image-gallery {
                grid-template-columns: 1fr;
            }

            .gallery-item img {
                height: 100px;
            }
        }

        /* Card styling improvements */
        .ibox {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            margin-bottom: 30px;
        }

        .ibox-title {
            background: linear-gradient(135deg, #1ab394, #13855c);
            color: white;
            padding: 15px 20px;
            border-bottom: none;
        }

        .ibox-title h5 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .ibox-content {
            padding: 25px;
        }

        /* Label improvements */
        .label {
            font-size: 11px;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        .label-info {
            background: linear-gradient(135deg, #5bc0de, #31b0d5);
        }

        .label-primary {
            background: linear-gradient(135deg, #337ab7, #286090);
        }

        .label-default {
            background: linear-gradient(135deg, #777, #555);
        }

        /* Modal improvements */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #1ab394, #13855c);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            color: white;
        }

        .close {
            color: white;
            opacity: 0.8;
        }

        .close:hover {
            color: white;
            opacity: 1;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
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

        // Enhanced tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush