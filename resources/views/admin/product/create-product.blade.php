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
        <div class="col-lg-8 col-lg-offset-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-plus-circle"></i> Add New Product</h5>
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

                    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Product Name <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required placeholder="Enter product name">
                                <span class="help-block m-b-none">This is the main name of your product</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Product Image</label>
                            <div class="col-sm-9">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                                <span class="help-block m-b-none">Upload product image (JPG, PNG, WebP - Max: 2MB)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" rows="4" class="form-control" placeholder="Enter product description">{{ old('description') }}</textarea>
                                <span class="help-block m-b-none">Describe your product features and benefits</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Category <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                        <i class="fa fa-circle text-success"></i> Active
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                                        <i class="fa fa-circle text-muted"></i> Inactive
                                    </label>
                                </div>
                                <span class="help-block m-b-none">Set product visibility status</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>



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


                        {{-- Submit --}}
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a href="{{ route('admin.product.index-product') }}" class="btn btn-white">Cancel</a>
                                <button class="btn btn-primary" type="submit">Save Product</button>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <a href="{{ route('admin.product.index-product') }}" class="btn btn-white btn-lg">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fa fa-save"></i> Save Product
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
