@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Product List</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="active">
                <strong>Products</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action" style="margin-top: 20px;">
            <a href="{{ route('admin.product.product-variant.variant.list-variant') }}" class="btn btn-info btn-sm">
                <i class="fa fa-list"></i> Variant List
            </a>
            <a href="{{ route('admin.product.create-product') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Product
            </a>
            <a href="{{ route('admin.product.restore-product') }}" class="btn btn-warning btn-sm">
                <i class="fa fa-recycle"></i> Restore
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    @if(session('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-filter"></i> Search Filter</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.product.index-product') }}" method="get" class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="name">Product Name</label>
                                <input type="text" id="name" name="name" value="{{ request('name') }}" 
                                       placeholder="Enter product name..." class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                    <a href="{{ route('admin.product.index-product') }}" class="btn btn-default">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-list"></i> Product List ({{ $products->count() }} products)</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                            <thead>
                                <tr>
                                    <th width="30%">Product Name</th>
                                    <th width="10%" class="text-center">Image</th>
                                    <th width="15%">Category</th>
                                    <th width="12%" class="text-right">Price</th>
                                    <th width="8%" class="text-center">Stock</th>
                                    <th width="10%" class="text-center">Created Date</th>
                                    <th width="15%" class="text-center" data-sort-ignore="true">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    @php
                                        $variant = $product->variants->first();
                                        $totalStock = $product->variants->sum('stock_quantity');
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->variants->count() > 1)
                                                <br><small class="text-muted">{{ $product->variants->count() }} variants</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     width="60" height="60" 
                                                     class="img-rounded" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-light border rounded" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="label label-primary">{{ $product->category->name ?? 'No Category' }}</span>
                                        </td>
                                        <td class="text-right">
                                            @if($variant)
                                                <strong class="text-success">${{ number_format($variant->price, 2) }}</strong>
                                                @if($product->variants->count() > 1)
                                                    <br><small class="text-muted">From ${{ number_format($product->variants->min('price'), 2) }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">No Price</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($totalStock > 0)
                                                <span class="label label-success">{{ $totalStock }}</span>
                                            @else
                                                <span class="label label-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <small>{{ $product->created_at->format('m/d/Y') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.show-product', $product->id) }}" 
                                                   class="btn btn-info btn-xs" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.product.edit-product', $product->id) }}" 
                                                   class="btn btn-primary btn-xs" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.product.delete', $product->id) }}" 
                                                      method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs" 
                                                            title="Delete Product"
                                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <div style="padding: 40px;">
                                                <i class="fa fa-inbox fa-3x"></i>
                                                <h4>No Products Found</h4>
                                                <p>Add your first product</p>
                                                <a href="{{ route('admin.product.create-product') }}" class="btn btn-primary">
                                                    <i class="fa fa-plus"></i> Add Product
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($products->hasPages())
                        <div class="text-center">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-group .btn {
    margin-right: 2px;
}
.table > tbody > tr > td {
    vertical-align: middle;
}
.label {
    font-size: 11px;
}
</style>
@endsection
