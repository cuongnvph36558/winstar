@extends('layouts.admin')

@section('title', 'Sản phẩm')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Product List</h2>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="{{ route('admin.product.index-product') }}">Product</a></li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.product.restore-product') }}" class="btn btn-warning">
                <i class="fa fa-recycle"></i> Restore Products
            </a>
        </div>
        <div class="text-right" style="margin-top: 30px;">
            <a href="{{ route('admin.product.create-product') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Product
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    @if(session('success'))
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="ibox-content m-b-sm border-bottom">
        <form action="{{ route('admin.product.index-product') }}" method="get" class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Product Name"
                        class="form-control" onkeyup="this.form.submit()">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="10">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="text-right" data-sort-ignore="true">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                @php
                                    $variant = $product->variants->first();
                                @endphp
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="" width="100">
                                    </td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $variant ? number_format($variant->price, 0, ',', '.') . '₫' : 'N/A' }}</td>
                                    <td>{{ $variant ? $variant->stock_quantity : 'N/A' }}</td>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $product->updated_at->format('d/m/Y') }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.product.show-product', $product->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> View</a>
                                            <a href="{{ route('admin.product.edit-product', $product->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                            <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Bạn có chắc muốn xóa chứ???')"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8">
                                    <ul class="pagination float-right">
                                     
                                    </ul>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
