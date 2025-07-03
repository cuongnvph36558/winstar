@extends('layouts.admin')
@section('title', 'Add Product Variant')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Add Product Variant</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.product.index-product') }}">Products</a></li>
                <li><a href="{{ route('admin.product.show-product', $product->id) }}">{{ $product->name }}</a></li>
                <li class="active"><strong>Add Variant</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-plus-circle"></i> Add Variant for Product: <strong>{{ $product->name }}</strong>
                        </h5>
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

                        <form method="POST" action="{{ route('admin.product.product-variant.store') }}"
                            enctype="multipart/form-data" class="form-horizontal">
                            @csrf

                            <!-- Product Information -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Select Product <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                                    <span class="help-block m-b-none">Choose the product for this variant</span>
                                    @error('product_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Variant Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="variant_name" value="{{ old('variant_name') }}"
                                        class="form-control" placeholder="Enter variant name">
                                    <span class="help-block m-b-none">This is the name of the product variant</span>
                                    @error('variant_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Price <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="price" value="{{ old('price') }}" class="form-control"
                                        placeholder="Enter price" min="0" step="0.01">
                                    <span class="help-block m-b-none">Set the price for this variant</span>
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Stock Quantity <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}"
                                        class="form-control" placeholder="Enter stock quantity" min="0">
                                    <span class="help-block m-b-none">Number of items in stock</span>
                                    @error('stock_quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Color <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="color_id" class="form-control">
                                        <option value="">-- Select Color --</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="help-block m-b-none">Choose color for the variant</span>
                                    @error('color_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Storage <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="storage_id" class="form-control">
                                        <option value="">-- Select Storage --</option>
                                        @foreach($storages as $storage)
                                            <option value="{{ $storage->id }}" {{ old('storage_id') == $storage->id ? 'selected' : '' }}>
                                                {{ $storage->capacity }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="help-block m-b-none">Choose storage capacity for the variant</span>
                                    @error('storage_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Variant Images</label>
                                <div class="col-sm-9">
                                    <input type="file" name="image_variant[]" class="form-control" accept="image/*"
                                        multiple>
                                    <span class="help-block m-b-none">Upload variant images (JPG, PNG, WebP - Max: 2MB
                                        each)</span>
                                    @error('image_variant')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('image_variant.*')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <a href="{{ route('admin.product.show-product', $product->id) }}"
                                        class="btn btn-white btn-lg">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                    <button class="btn btn-primary btn-lg" type="submit">
                                        <i class="fa fa-save"></i> Save Variant
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