@extends('layouts.admin')
@section('title', 'Create Storage Variant')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Create Storage Variant</h2>
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
                <strong>Create Storage</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-hdd-o"></i> Create New Storage Variant</h5>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.product.product-variant.variant.store-storage') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="storage">
                        
                        <div class="form-group">
                            <label for="capacity" class="control-label">Storage Capacity <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" 
                                   name="capacity" 
                                   placeholder="Enter storage capacity (e.g., 64, 128, 256)" 
                                   value="{{ old('capacity') }}" 
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
                                    <i class="fa fa-save"></i> Create Storage Variant
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
