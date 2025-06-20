@extends('layouts.admin')
@section('title', 'Product Variants Management')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Product Variants Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('admin.product.index-product') }}">Products</a>
            </li>
            <li class="active">
                <strong>Variants Management</strong>
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
        <!-- Color Variants Section -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-palette"></i> Color Variants</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.product.product-variant.variant.create-color') }}" class="btn btn-primary btn-xs" title="Add Color Variant">
                            <i class="fa fa-plus"></i> Add Color
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Color Name</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($colors as $key => $color)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <span class="label label-primary">{{ $color->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.product-variant.variant.edit-color', $color->id) }}" 
                                                   class="btn btn-warning btn-xs" 
                                                   title="Edit Color">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.product.product-variant.variant.delete-color', $color->id) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this color variant?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-xs" 
                                                            title="Delete Color">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <i class="fa fa-info-circle"></i> No color variants found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Variants Section -->
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-hdd-o"></i> Storage Variants</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.product.product-variant.variant.create-storage') }}" class="btn btn-primary btn-xs" title="Add Storage Variant">
                            <i class="fa fa-plus"></i> Add Storage
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Storage Capacity</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storages as $key => $storage)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <span class="label label-info">{{ $storage->capacity }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.product-variant.variant.edit-storage', $storage->id) }}" 
                                                   class="btn btn-warning btn-xs" 
                                                   title="Edit Storage">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.product.product-variant.variant.delete-storage', $storage->id) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this storage variant?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-xs" 
                                                            title="Delete Storage">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <i class="fa fa-info-circle"></i> No storage variants found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
