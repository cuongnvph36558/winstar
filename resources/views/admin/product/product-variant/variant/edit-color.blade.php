@extends('layouts.admin')
@section('title', 'Edit Color Variant')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Edit Color Variant</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('admin.product.index-product') }}">Products</a>
            </li>
            <li>
                <a href="{{ route('admin.product.product-variant.variant.list-variant') }}">Variants Management</a>
            </li>
            <li class="active">
                <strong>Edit Color</strong>
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
                    <h5><i class="fa fa-palette"></i> Edit Color Variant</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.product.product-variant.variant.update-color', $color->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name" class="control-label">Color Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Enter color name (e.g., Red, Blue, Black)" 
                                   value="{{ old('name', $color->name) }}" 
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
                                    <i class="fa fa-save"></i> Update Color Variant
                                </button>
                                <a href="{{ route('admin.product.product-variant.variant.list-variant') }}" 
                                   class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Back to Variants
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
