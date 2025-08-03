@extends('layouts.admin')
@section('title', 'Sửa sản phẩm')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Sửa sản phẩm</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.product.index-product') }}">Sản phẩm</a></li>
                <li class="active"><strong>Sửa</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-edit"></i> Sửa sản phẩm</h5>
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

                        <form method="POST" action="{{ route('admin.product.update-product', $product->id) }}"
                            enctype="multipart/form-data" class="form-horizontal">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tên sản phẩm <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                        class="form-control" required placeholder="Nhập tên sản phẩm">
                                    <span class="help-block m-b-none">Đây là tên của sản phẩm</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Hình ảnh hiện tại</label>
                                <div class="col-sm-9">
                                    @if($product->image)
                                        <div class="product-image-preview">
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                style="max-width: 200px; height: auto; border-radius: 4px; border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                    @else
                                        <div class="text-center p-3 bg-light border rounded">
                                            <i class="fa fa-image fa-2x text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">Không có hình ảnh</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Thay đổi hình ảnh</label>
                                <div class="col-sm-9">
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <span class="help-block m-b-none">
                                        <i class="fa fa-info-circle"></i>
                                        Định dạng được chấp nhận: JPG, JPEG, PNG, WEBP (tối đa 2MB)
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Giá sản phẩm</label>
                                <div class="col-sm-9">
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" required
                                        placeholder="Nhập giá sản phẩm">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Giá khuyến mãi</label>
                                <div class="col-sm-9">
                                    <input type="number" name="promotion_price" value="{{ old('promotion_price', $product->promotion_price) }}" class="form-control"
                                        placeholder="Nhập giá khuyến mãi">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mô tả</label>
                                <div class="col-sm-9">
                                    <textarea name="description" rows="4" class="form-control"
                                        placeholder="Nhập mô tả sản phẩm">{{ old('description', $product->description) }}</textarea>
                                    <span class="help-block m-b-none">Mô tả ngắn gọn về sản phẩm</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Danh mục <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="help-block m-b-none">Chọn danh mục phù hợp cho sản phẩm này</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Trạng thái</label>
                                <div class="col-sm-9">
                                    <select name="status" class="form-control">
                                        <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>
                                            Hoạt động
                                        </option>
                                        <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>
                                            Không hoạt động
                                        </option>
                                    </select>
                                    <span class="help-block m-b-none">Đặt trạng thái hiển thị sản phẩm</span>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-3">
                                    <a href="{{ route('admin.product.index-product') }}" class="btn btn-white">
                                        <i class="fa fa-arrow-left"></i> Hủy
                                    </a>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-save"></i> Cập nhật sản phẩm
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