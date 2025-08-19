@extends('layouts.admin')
@section('title', 'Tạo biến thể màu sắc')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Tạo biến thể màu sắc</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a>
            </li>
            <li>
                <a href="{{ route('admin.product.index-product') }}">Sản phẩm</a>
            </li>
            <li>
                <a href="{{ route('admin.product.product-variant.variant.list-variant') }}">Quản lý biến thể</a>
            </li>
            <li class="active">
                <strong>Tạo màu sắc</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-palette"></i> Tạo biến thể màu sắc mới</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.product.product-variant.variant.store-color') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="color">
                        
                        <div class="form-group">
                            <label for="name" class="control-label">Tên màu sắc <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Nhập tên màu sắc (ví dụ: Đỏ, Xanh, Đen)" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Tạo biến thể màu sắc
                                </button>
                                <a href="{{ route('admin.product.product-variant.variant.list-variant') }}" 
                                   class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Quay lại biến thể
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
