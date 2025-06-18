@extends('layouts.admin')
@section('title', 'Sửa biến thể sản phẩm')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Edit Product Variant</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.product.index-product') }}">Product</a></li>
            <li class="active"><strong>Edit Variant</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-edit"></i> Edit Product Variant</h5>
                </div>
                <div class="ibox-content">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <ul class="m-b-none">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fa fa-exclamation-circle"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.product.variant.update', $variant->id) }}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Product</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $variant->product->name }}" class="form-control" readonly>
                                <span class="help-block m-b-none">Product cannot be changed</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Variant Name <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="variant_name" value="{{ old('variant_name', $variant->variant_name) }}" class="form-control" required placeholder="Enter variant name">
                                <span class="help-block m-b-none">This is the name of the product variant</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Price <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="price" value="{{ old('price', $variant->price) }}" class="form-control" required placeholder="Enter price" min="0" step="0.01">
                                <span class="help-block m-b-none">Set the price for this variant</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Stock Quantity <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $variant->stock_quantity) }}" class="form-control" required placeholder="Enter stock quantity" min="0">
                                <span class="help-block m-b-none">Number of items in stock</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">SKU <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" class="form-control" required placeholder="Enter SKU">
                                <span class="help-block m-b-none">Stock Keeping Unit identifier</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Color</label>
                            <div class="col-sm-9">
                                <input type="text" name="color" value="{{ old('color', $variant->color) }}" class="form-control" placeholder="Enter color">
                                <span class="help-block m-b-none">Color of the variant (optional)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Size</label>
                            <div class="col-sm-9">
                                <input type="text" name="size" value="{{ old('size', $variant->size) }}" class="form-control" placeholder="Enter size">
                                <span class="help-block m-b-none">Size of the variant (optional)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Storage</label>
                            <div class="col-sm-9">
                                <input type="text" name="storage" value="{{ old('storage', $variant->storage) }}" class="form-control" placeholder="Enter storage">
                                <span class="help-block m-b-none">Storage capacity (optional)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ảnh hiện tại</label>
                            <div class="col-sm-9">
                                @if($variant->image_variant)
                                    @php
                                        $images = is_string($variant->image_variant) ? json_decode($variant->image_variant, true) : $variant->image_variant;
                                    @endphp
                                    @if($images && count($images) > 0)
                                        <div class="row">
                                            @foreach($images as $image)
                                                <div class="col-md-3 mb-2">
                                                    <div class="image-container" style="position: relative; margin-bottom: 10px;">
                                                        <img src="{{ asset('storage/' . $image) }}" alt="Ảnh biến thể sản phẩm" class="img-responsive" style="max-height: 120px; width: 100%; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">Chưa có ảnh nào được tải lên</p>
                                    @endif
                                @else
                                    <p class="text-muted">Chưa có ảnh nào được tải lên</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Update Variant Images</label>
                            <div class="col-sm-9">
                                <input type="file" name="image_variant[]" class="form-control" accept="image/*" multiple>
                                <span class="help-block m-b-none">Upload new variant images (JPG, PNG, WebP - Max: 2MB each). This will replace existing images.</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <a href="{{ route('admin.product.index-product') }}" class="btn btn-white btn-lg">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fa fa-save"></i> Update Variant
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.help-block {
    font-size: 11px;
    color: #676a6c;
}
.form-group {
    margin-bottom: 25px;
}
.control-label {
    font-weight: 600;
}
.text-danger {
    color: #ed5565;
}
.radio-inline {
    margin-right: 20px;
}
.radio-inline label {
    font-weight: normal;
}
</style>
@endsection
