@extends('layouts.admin')
@section('title', 'Chi tiết sản phẩm')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Product Details</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('admin.product.index-product') }}">Products</a>
            </li>
            <li class="active">
                <strong>{{ $product->name }}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action" style="margin-top: 20px;">
            <a href="{{ route('admin.product.edit-product', $product->id) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-edit"></i> Edit Product
            </a>
            <a href="{{ route('admin.product.variant.restore') }}" class="btn btn-warning btn-sm">
                <i class="fa fa-recycle"></i> Khôi phục biến thể
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <!-- Product Information -->
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-cube"></i> Product Information</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-5">
                            @if($product->image)
                                <div class="product-image-main text-center">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-responsive" 
                                         style="max-width: 100%; max-height: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                </div>
                            @else
                                <div class="text-center p-5 bg-light border rounded">
                                    <i class="fa fa-image fa-4x text-muted"></i>
                                    <p class="text-muted mt-3">No image available</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h2 class="product-title font-bold text-navy">{{ $product->name }}</h2>
                            
                            <div class="m-t-md">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="stat-percent font-bold text-info">
                                            <i class="fa fa-eye"></i> {{ $product->view }}
                                            <small>Views</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="stat-percent font-bold">
                                            <span class="label {{ $product->status ? 'label-primary' : 'label-default' }}">
                                                {{ $product->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="m-t-lg">
                                <h4 class="text-navy">Description</h4>
                                <p class="text-muted">
                                    {{ $product->description ?? 'No description available for this product.' }}
                                </p>
                            </div>

                            <div class="m-t-lg">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <small class="text-muted">Category</small>
                                        <h5 class="font-bold">{{ $product->category->name }}</h5>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted">Total Variants</small>
                                        <h5 class="font-bold">{{ $product->variants->count() }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Variants -->
            @if($product->variants->count() > 0)
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-list"></i> Product Variants ({{ $product->variants->count() }})</h5>
                        <div class="ibox-tools">
                            <a href="{{ route('admin.product.variant.create', $product->id) }}" class="btn btn-success btn-xs">
                                <i class="fa fa-plus"></i> Thêm biến thể mới
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        @foreach($product->variants as $index => $variant)
                            <div class="product-variant {{ $index > 0 ? 'border-top m-t-lg p-t-lg' : '' }}">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="font-bold text-navy">{{ $variant->variant_name }}</h4>
                                        
                                        <div class="row m-t-sm">
                                            <div class="col-sm-3">
                                                <small class="text-muted">Price</small>
                                                <h5 class="font-bold text-success">{{ number_format($variant->price, 0, ',', '.') }} VND</h5>
                                            </div>
                                            <div class="col-sm-3">
                                                <small class="text-muted">Stock</small>
                                                <h5 class="font-bold {{ $variant->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $variant->stock_quantity }}
                                                </h5>
                                            </div>
                                            <div class="col-sm-6">
                                                <small class="text-muted">SKU</small>
                                                <h5 class="font-bold">{{ $variant->sku }}</h5>
                                            </div>
                                        </div>

                                        <div class="row m-t-sm">
                                            @if($variant->storage)
                                                <div class="col-sm-4">
                                                    <small class="text-muted">Storage</small>
                                                    <p class="font-bold">{{ $variant->storage }}</p>
                                                </div>
                                            @endif
                                            @if($variant->size)
                                                <div class="col-sm-4">
                                                    <small class="text-muted">Size</small>
                                                    <p class="font-bold">{{ $variant->size }}</p>
                                                </div>
                                            @endif
                                            @if($variant->color)
                                                <div class="col-sm-4">
                                                    <small class="text-muted">Color</small>
                                                    <p class="font-bold">{{ $variant->color }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row m-t-sm">
                                            <div class="col-sm-12">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.product.variant.edit', $variant->id) }}" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-edit"></i> Chỉnh sửa
                                                    </a>
                                                    <form action="{{ route('admin.product.variant.delete', $variant->id) }}" method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs">
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
                                                    <small class="text-muted">Variant Images</small>
                                                    <div class="row m-t-xs">
                                                        @foreach(array_slice($images, 0, 4) as $image)
                                                            @if($image)
                                                                <div class="col-xs-6 col-sm-3 col-md-6 m-b-xs">
                                                                    <img src="{{ asset('storage/' . $image) }}" 
                                                                         alt="Variant Image" 
                                                                         class="img-responsive img-thumbnail" 
                                                                         style="height: 60px; width: 100%; object-fit: cover;">
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @if(count($images) > 4)
                                                            <div class="col-xs-6 col-sm-3 col-md-6">
                                                                <div class="text-center" style="height: 60px; line-height: 60px; background: #f8f9fa; border: 1px dashed #ddd;">
                                                                    <small class="text-muted">+{{ count($images) - 4 }} more</small>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-center p-3 bg-light border rounded">
                                                <i class="fa fa-image text-muted"></i>
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
                            <i class="fa fa-exclamation-triangle fa-2x"></i>
                            <h4>No Variants Available</h4>
                            <p>This product doesn't have any variants configured yet.</p>
                            <a href="{{ route('admin.product.variant.create', $product->id) }}" class="btn btn-success btn-sm m-t-sm">
                                <i class="fa fa-plus"></i> Tạo biến thể đầu tiên
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Product Meta Information -->
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-info-circle"></i> Product Meta</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="m-b-md">
                                <small class="text-muted">Product ID</small>
                                <h5 class="font-bold">#{{ $product->id }}</h5>
                            </div>
                            
                            <div class="m-b-md">
                                <small class="text-muted">Category</small>
                                <h5 class="font-bold">
                                    <span class="label label-info">{{ $product->category->name }}</span>
                                </h5>
                            </div>

                            <div class="m-b-md">
                                <small class="text-muted">Status</small>
                                <h5 class="font-bold">
                                    <span class="label {{ $product->status ? 'label-primary' : 'label-default' }}">
                                        <i class="fa {{ $product->status ? 'fa-check' : 'fa-times' }}"></i>
                                        {{ $product->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </h5>
                            </div>

                            <div class="m-b-md">
                                <small class="text-muted">Total Views</small>
                                <h5 class="font-bold text-info">{{ number_format($product->view) }}</h5>
                            </div>

                            <hr>

                            <div class="m-b-md">
                                <small class="text-muted">Created At</small>
                                <h5 class="font-bold">{{ $product->created_at->format('M d, Y') }}</h5>
                                <small class="text-muted">{{ $product->created_at->format('H:i A') }}</small>
                            </div>

                            <div class="m-b-md">
                                <small class="text-muted">Last Updated</small>
                                <h5 class="font-bold">{{ $product->updated_at->format('M d, Y') }}</h5>
                                <small class="text-muted">{{ $product->updated_at->format('H:i A') }}</small>
                            </div>
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
                        <a href="{{ route('admin.product.index-product') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Products
                        </a>
                        <a href="{{ route('admin.product.edit-product', $product->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit Product
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fa fa-trash"></i> Delete Product
                        </button>
                    </div>
                    
                    <form id="delete-form" action="{{ route('admin.product.delete', $product->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
