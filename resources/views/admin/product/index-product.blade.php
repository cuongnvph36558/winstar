@extends('layouts.admin')

@section('title', 'Sản phẩm')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Danh sách sản phẩm</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
            </li>
            <li class="active">
                <strong>Sản phẩm</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action" style="margin-top: 20px;">
            <a href="{{ route('admin.product.create-product') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Thêm sản phẩm
            </a>
            <a href="{{ route('admin.product.restore-product') }}" class="btn btn-warning btn-sm">
                <i class="fa fa-recycle"></i> Khôi phục
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
                    <h5><i class="fa fa-filter"></i> Bộ lọc tìm kiếm</h5>
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
                                <label class="control-label" for="name">Tên sản phẩm</label>
                                <input type="text" id="name" name="name" value="{{ request('name') }}" 
                                       placeholder="Nhập tên sản phẩm..." class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="category_id">Danh mục</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Tất cả danh mục</option>
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
                                        <i class="fa fa-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.product.index-product') }}" class="btn btn-default">
                                        <i class="fa fa-refresh"></i> Làm mới
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
                    <h5><i class="fa fa-list"></i> Danh sách sản phẩm ({{ $products->count() }} sản phẩm)</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                            <thead>
                                <tr>
                                    <th width="30%">Tên sản phẩm</th>
                                    <th width="10%" class="text-center">Hình ảnh</th>
                                    <th width="15%">Danh mục</th>
                                    <th width="12%" class="text-right">Giá bán</th>
                                    <th width="8%" class="text-center">Tồn kho</th>
                                    <th width="10%" class="text-center">Ngày tạo</th>
                                    <th width="15%" class="text-center" data-sort-ignore="true">Thao tác</th>
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
                                                <br><small class="text-muted">{{ $product->variants->count() }} biến thể</small>
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
                                            <span class="label label-primary">{{ $product->category->name ?? 'Không có' }}</span>
                                        </td>
                                        <td class="text-right">
                                            @if($variant)
                                                <strong class="text-success">{{ number_format($variant->price, 0, ',', '.') }}₫</strong>
                                                @if($product->variants->count() > 1)
                                                    <br><small class="text-muted">Từ {{ number_format($product->variants->min('price'), 0, ',', '.') }}₫</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Chưa có giá</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($totalStock > 0)
                                                <span class="label label-success">{{ $totalStock }}</span>
                                            @else
                                                <span class="label label-danger">Hết hàng</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <small>{{ $product->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.show-product', $product->id) }}" 
                                                   class="btn btn-info btn-xs" title="Xem chi tiết">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.product.edit-product', $product->id) }}" 
                                                   class="btn btn-primary btn-xs" title="Chỉnh sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.product.delete', $product->id) }}" 
                                                      method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs" 
                                                            title="Xóa sản phẩm"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
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
                                                <h4>Không có sản phẩm nào</h4>
                                                <p>Hãy thêm sản phẩm đầu tiên của bạn</p>
                                                <a href="{{ route('admin.product.create-product') }}" class="btn btn-primary">
                                                    <i class="fa fa-plus"></i> Thêm sản phẩm
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
