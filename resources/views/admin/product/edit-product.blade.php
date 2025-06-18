@extends('layouts.admin')
@section('title', 'Sửa sản phẩm')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Edit Product</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.product.index-product') }}">Product</a></li>
            <li class="active"><strong>Edit</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-edit"></i> Edit Product</h5>
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

                    <form method="POST" action="{{ route('admin.product.update-product', $product->id) }}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Product Name <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required placeholder="Enter product name">
                                <span class="help-block m-b-none">This is the name of the product</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Current Image</label>
                            <div class="col-sm-9">
                                @if($product->image)
                                    <div class="product-image-preview">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                             style="max-width: 200px; height: auto; border-radius: 4px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                @else
                                    <div class="text-center p-3 bg-light border rounded">
                                        <i class="fa fa-image fa-2x text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">No image available</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Change Image</label>
                            <div class="col-sm-9">
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <span class="help-block m-b-none">
                                    <i class="fa fa-info-circle"></i> 
                                    Accepted formats: JPG, JPEG, PNG, WEBP (max 2MB)
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" rows="4" class="form-control" placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                                <span class="help-block m-b-none">Brief description of the product</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Category <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="help-block m-b-none">Choose the appropriate category for this product</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-9">
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>
                                        <i class="fa fa-check"></i> Active
                                    </option>
                                    <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>
                                        <i class="fa fa-times"></i> Inactive
                                    </option>
                                </select>
                                <span class="help-block m-b-none">Set product visibility status</span>
                            </div>
                        </div>


                        <hr>
                        <h4>Product Variant</h4>

                        @php $variant = $product->variants->first(); @endphp

                        {{-- Variant Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Variant Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="variant_name" class="form-control" value="{{ old('variant_name', $variant->variant_name ?? '') }}">
                            </div>
                        </div>

                        {{-- Variant Image --}}

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Variant Images</label>
                            <div class="col-sm-10">
                                {{-- Hiển thị ảnh nếu có --}}
                                @if($variant && $variant->images)
                                    @foreach (json_decode($variant->images) as $img)
                                        <img src="{{ asset('storage/' . $img) }}" width="100" class="mr-2">
                                    @endforeach
                                @endif
                                <input type="file" name="image_variant[]" class="form-control mt-2" multiple>
                            </div>
                        </div>


                        {{-- Price --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <input type="number" name="price" class="form-control" value="{{ old('price', $variant->price ?? '') }}">
                            </div>
                        </div>

                        {{-- Stock Quantity --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Stock Quantity</label>
                            <div class="col-sm-10">
                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $variant->stock_quantity ?? '') }}">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-3">
                                <a href="{{ route('admin.product.index-product') }}" class="btn btn-white">
                                    <i class="fa fa-arrow-left"></i> Cancel
                                </a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-save"></i> Update Product
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
