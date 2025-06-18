@extends('layouts.admin')
@section('title', 'Thêm sản phẩm')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Add New Product</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.product.index-product') }}">Product</a></li>
            <li class="active"><strong>Add New</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Add New Product</h5>
                </div>
                <div class="ibox-content">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf

                        {{-- Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Product Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" name="image" class="form-control" required>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h4>Product Variant</h4>
                        {{-- Variant Name --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Variant Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="variant_name" class="form-control" value="{{ old('variant_name') }}" required>
                            </div>
                        </div>
                        {{-- Image Variant --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Variant Image</label>
                            <div class="col-sm-10">
                                <input type="file" name="image_variant[]" class="form-control" multiple>
                            </div>
                        </div>

                        {{-- Storage --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Storage</label>
                            <div class="col-sm-10">
                                <input type="text" name="storage" class="form-control" value="{{ old('storage') }}">
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>
                        </div>

                        {{-- Stock Quantity --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Stock Quantity</label>
                            <div class="col-sm-10">
                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" required>
                            </div>
                        </div>

                        {{-- Size --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Size</label>
                            <div class="col-sm-10">
                                <input type="text" name="size" class="form-control" value="{{ old('size') }}">
                            </div>
                        </div>

                        {{-- Color --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Color</label>
                            <div class="col-sm-10">
                                <input type="text" name="color" class="form-control" value="{{ old('color') }}">
                            </div>
                        </div>

                        {{-- SKU --}}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">SKU</label>
                            <div class="col-sm-10">
                                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="{{ route('admin.product.index-product') }}" class="btn btn-white">Cancel</a>
                                <button class="btn btn-primary" type="submit">Save Product</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
