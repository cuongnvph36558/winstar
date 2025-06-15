@extends('layouts.admin')
@section('title', 'Chi tiết sản phẩm')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chi tiết sản phẩm</h2>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="{{ route('admin.product.index-product') }}">Product</a></li>
            <li class="active"><strong>Detail</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        {{-- Ảnh và thumbnail --}}
                        <div class="col-md-6">
                            <div>
                                <img style="width:400px; height:400px; object-fit:cover;" src="{{ asset('storage/' . $product->image) }}" alt="">
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                @foreach($product->variants as $variant)
                                    @php
                                        $images = json_decode($variant->image_variant ?? '[]');
                                    @endphp
                                    @foreach($images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" style="width:100px; height:100px; object-fit:cover; border:1px solid #ccc; border-radius:4px; margin-right:10px;">
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        {{-- Thông tin sản phẩm --}}
                        <div class="col-md-6">
                            <h2 class="font-bold m-b-xs">Tên sản phẩm: {{ $product->name }}</h2>
                            <hr>
                            <p><strong>Giá tiền:</strong> {{ $variant ? number_format($variant->price, 0, ',', '.') . '₫' : 'N/A' }}</p>
                            <p><strong>Số lượng:</strong> {{ $variant ? $variant->stock_quantity : 'N/A' }}</p>
                            <p><strong>Lượt xem:</strong> {{ $product->view ?? 0 }}</p>
                            <p><strong>Ngày nhập:</strong> {{ $product->created_at->format('d/m/Y') }}</p>
                            <p><strong>Danh mục:</strong> {{ $product->category?->name }}</p>
                            <p><strong>Trạng thái:</strong> {{ $product->status ? 'Hoạt động' : 'không hoạt động' }}</p>
                            <p><strong>Mô tả:</strong> {{ $product->description }}</p>
                        </div>
                    </div><br>

                    {{-- Nút điều hướng --}}
                    <div class="mt-4">
                        <a href="{{ route("admin.product.index-product") }}" class="btn btn-white btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                        <a href="{{ route('admin.product.edit-product', $product->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
