@extends('layouts.admin')
@section('title', 'Edit Storage Variant')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Edit Storage Variant</h2>
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
                <strong>Edit Storage</strong>
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
                    <h5><i class="fa fa-hdd-o"></i> Edit Storage Variant</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.product.product-variant.variant.update-storage', $storage->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="capacity" class="control-label">Storage Capacity <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" 
                                   name="capacity" 
                                   placeholder="Enter storage capacity (e.g., 64, 128, 256)" 
                                   value="{{ old('capacity', $storage->capacity) }}" 
                                   required>
                            @error('capacity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Storage Variant
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
