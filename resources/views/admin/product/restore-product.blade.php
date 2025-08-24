@extends('layouts.admin')

@section('title', 'Khôi phục sản phẩm')

@section('styles')
<style>
.search-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

.btn-group .btn {
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.bulk-actions-container {
    min-height: 40px;
    display: flex;
    align-items: center;
}

.stats-info {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.input-group {
    max-width: 300px;
}

.table th {
    background-color: #f8f9fa;
    border-top: 2px solid #dee2e6;
}

.product-checkbox {
    transform: scale(1.2);
    margin: 0;
}

#select-all-checkbox {
    transform: scale(1.2);
    margin: 0;
}

@media (max-width: 768px) {
    .search-section .col-md-4 {
        margin-bottom: 15px;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 5px;
        margin-right: 0;
    }
}
</style>
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Khôi phục sản phẩm</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.product.index-product') }}">Sản phẩm</a>
                </li>
                <li class="active">
                    <strong>Khôi phục</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="text-right" style="margin-top: 30px;">
                <a href="{{ route('admin.product.index-product') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        @if(session('success'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <!-- Search and Filter Section -->
                        <div class="search-section">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Tìm kiếm:</label>
                                    <form method="GET" action="{{ route('admin.product.restore-product') }}" class="form-inline">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." 
                                                   value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Thao tác hàng loạt:</label>
                                    <div class="bulk-actions-container">
                                        <div class="btn-group" id="bulk-actions" style="display: none;">
                                            <button type="button" class="btn btn-danger" onclick="bulkDelete()">
                                                <i class="fa fa-trash"></i> Xóa vĩnh viễn đã chọn
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="bulkRestore()">
                                                <i class="fa fa-recycle"></i> Khôi phục đã chọn
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Chọn sản phẩm:</label>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default" onclick="selectAll()">
                                            <i class="fa fa-check-square-o"></i> Chọn tất cả
                                        </button>
                                        <button type="button" class="btn btn-default" onclick="deselectAll()">
                                            <i class="fa fa-square-o"></i> Bỏ chọn tất cả
                                        </button>
                                    </div>
                                    <div class="stats-info mt-2">
                                        <i class="fa fa-info-circle"></i> Tổng: {{ $products->total() }} sản phẩm
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="select-all-checkbox" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Ảnh</th>
                                    <th>Ngày xóa</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'Không có' }}</td>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" width="50">
                                            @else
                                                Không có ảnh
                                            @endif
                                        </td>
                                        <td>{{ $product->deleted_at ? $product->deleted_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.product.restore', $product->id) }}"
                                                    class="btn btn-warning btn-xs">
                                                    <i class="fa fa-recycle"></i> Khôi phục
                                                </a>
                                                <button class="btn btn-danger btn-xs"
                                                    onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')">
                                                    <i class="fa fa-trash"></i> Xóa vĩnh viễn
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fa fa-info-circle"></i> Không có sản phẩm nào trong thùng rác
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <div class="pull-right">
                                            {{ $products->appends(request()->query())->links() }}
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Form -->
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.product.bulk-force-delete') }}" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="product_ids" id="bulk-delete-ids">
    </form>

    <!-- Bulk Restore Form -->
    <form id="bulk-restore-form" method="POST" action="{{ route('admin.product.bulk-restore') }}" style="display: none;">
        @csrf
        <input type="hidden" name="product_ids" id="bulk-restore-ids">
    </form>
@endsection

@section('scripts')
<script>
    // Toggle select all checkbox
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        
        updateBulkActions();
    }

    // Select all products
    function selectAll() {
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        selectAllCheckbox.checked = true;
        
        updateBulkActions();
    }

    // Deselect all products
    function deselectAll() {
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
        
        updateBulkActions();
    }

    // Update bulk actions visibility
    function updateBulkActions() {
        const productCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        
        if (productCheckboxes.length > 0) {
            bulkActions.style.display = 'inline-block';
        } else {
            bulkActions.style.display = 'none';
        }
    }

    // Bulk delete products
    function bulkDelete() {
        const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        const productIds = Array.from(selectedCheckboxes).map(cb => cb.value);
        
        if (productIds.length === 0) {
            alert('Vui lòng chọn ít nhất một sản phẩm để xóa!');
            return;
        }
        
        const confirmMessage = `Bạn có chắc chắn muốn xóa vĩnh viễn ${productIds.length} sản phẩm đã chọn?`;
        if (confirm(confirmMessage)) {
            document.getElementById('bulk-delete-ids').value = productIds.join(',');
            document.getElementById('bulk-delete-form').submit();
        }
    }

    // Bulk restore products
    function bulkRestore() {
        const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        const productIds = Array.from(selectedCheckboxes).map(cb => cb.value);
        
        if (productIds.length === 0) {
            alert('Vui lòng chọn ít nhất một sản phẩm để khôi phục!');
            return;
        }
        
        const confirmMessage = `Bạn có chắc chắn muốn khôi phục ${productIds.length} sản phẩm đã chọn?`;
        if (confirm(confirmMessage)) {
            document.getElementById('bulk-restore-ids').value = productIds.join(',');
            document.getElementById('bulk-restore-form').submit();
        }
    }

    // Delete single product
    function deleteProduct(id, name) {
        if (confirm(`Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm "${name}"?`)) {
            window.location.href = `{{ route('admin.product.force-delete', '') }}/${id}`;
        }
    }

    // Add event listeners to checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });
    });
</script>
@endsection
