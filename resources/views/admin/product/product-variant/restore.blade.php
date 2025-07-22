@extends('layouts.admin')

@section('title', 'Khôi phục biến thể sản phẩm')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><i class="fa fa-recycle text-warning"></i> Khôi phục biến thể sản phẩm</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.product.index-product') }}">Sản phẩm</a>
            </li>   
            <li class="active">
                <strong>Khôi phục biến thể</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="title-action" style="margin-top: 30px;">
            <a href="{{ route('admin.product.index-product') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
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
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-list"></i> Danh sách biến thể đã xóa</h5>
                    <div class="ibox-tools">
                        <span class="label label-warning-light">
                            Tổng: {{ count($variants) }} biến thể
                        </span>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(count($variants) > 0)
                        <div class="table-responsive">
                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                    <tr>
                                        <th data-toggle="true"><i class="fa fa-tag"></i> Tên biến thể</th>
                                        <th data-hide="phone"><i class="fa fa-cube"></i> Sản phẩm</th>
                                        <th data-hide="phone,tablet">SKU</th>
                                        <th data-hide="phone"><i class="fa fa-money"></i> Giá</th>
                                        <th data-hide="phone,tablet">Giá khuyến mãi</th>
                                        <th data-hide="phone,tablet">Màu sắc</th>
                                        <th data-hide="phone,tablet">Kích thước</th>
                                        <th data-hide="phone,tablet">Bộ nhớ</th>
                                        <th data-sort-ignore="true" class="text-right">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variants as $variant)
                                    <tr>
                                        <td>
                                            <span class="font-bold text-navy">{{ $variant->variant_name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $variant->product->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="label label-default">{{ $variant->sku }}</span>
                                        </td>
                                        <td>
                                            <span class="font-bold text-success">{{ number_format($variant->price, 0, ',', '.') }} VND</span>
                                        </td>
                                        <td>
                                            <span class="font-bold text-success">{{ number_format($variant->promotion_price, 0, ',', '.') }} VND</span>
                                        </td>
                                        <td>
                                            @if($variant->color)
                                                <span class="label label-info">{{ $variant->color }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($variant->size)
                                                <span class="label label-primary">{{ $variant->size }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($variant->storage)
                                                <span class="label label-warning">{{ $variant->storage }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.product-variant.restore', $variant->id) }}" 
                                                   class="btn btn-warning btn-xs" 
                                                   title="Khôi phục biến thể">
                                                    <i class="fa fa-recycle"></i> Khôi phục
                                                </a>
                                                <form action="{{ route('admin.product.product-variant.force-delete', $variant->id) }}" 
                                                      method="GET" 
                                                      style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-xs" 
                                                            onclick="return confirm('⚠️ Cảnh báo!\n\nHành động này sẽ xóa vĩnh viễn biến thể và không thể hoàn tác.\n\nBạn có chắc chắn muốn tiếp tục?')"
                                                            title="Xóa vĩnh viễn">
                                                        <i class="fa fa-trash"></i> Xóa vĩnh viễn
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8">
                                            <div class="text-center">
                                                <small class="text-muted">
                                                    <i class="fa fa-info-circle"></i> 
                                                    Hiển thị {{ count($variants) }} biến thể đã xóa
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center p-lg">
                            <div class="empty-state">
                                <i class="fa fa-recycle fa-5x text-muted"></i>
                                <h3 class="text-muted">Không có biến thể nào cần khôi phục</h3>
                                <p class="text-muted">Tất cả biến thể đều đang hoạt động bình thường.</p>
                                <a href="{{ route('admin.product.index-product') }}" class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i> Quay lại danh sách sản phẩm
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.empty-state {
    padding: 60px 20px;
}
.empty-state i {
    margin-bottom: 20px;
}
.empty-state h3 {
    margin-bottom: 10px;
}
.empty-state p {
    margin-bottom: 30px;
}
.btn-group .btn {
    margin-right: 5px;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
.label {
    font-size: 10px;
    padding: 3px 8px;
}
.font-bold {
    font-weight: 600;
}
</style>
@endsection
