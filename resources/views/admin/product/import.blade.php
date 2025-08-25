@extends('layouts.admin')
@section('title', 'Import sản phẩm và biến thể')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2><i class="fa fa-upload text-primary"></i> Import Sản Phẩm & Biến Thể</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.product.index-product') }}"><i class="fa fa-home"></i> Sản Phẩm</a></li>
                <li class="active"><strong>Import</strong></li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a href="{{ route('admin.product.index-product') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-exclamation-triangle"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Import Products Section -->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-cube"></i> Import Sản Phẩm</h5>
                        <div class="ibox-tools">
                            <a href="{{ route('admin.product.download-template', 'products') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-download"></i> Tải Template
                            </a>
                            <a href="{{ route('admin.product.export-products') }}" class="btn btn-info btn-sm">
                                <i class="fa fa-download"></i> Xuất Excel
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-8">
                                <form method="POST" action="{{ route('admin.product.import-products') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="product_file">Chọn file Excel sản phẩm:</label>
                                        <input type="file" name="file" id="product_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                        <span class="help-block">Hỗ trợ định dạng: .xlsx, .xls, .csv (Tối đa 10MB)</span>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-upload"></i> Import Sản Phẩm
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h6><i class="fa fa-info-circle"></i> Cấu trúc file sản phẩm:</h6>
                                    <ul class="mb-0">
                                        <li><strong>name:</strong> Tên sản phẩm (bắt buộc)</li>
                                        <li><strong>category_name:</strong> Tên danh mục (bắt buộc)</li>
                                        <li><strong>description:</strong> Mô tả (tùy chọn)</li>
                                        <li><strong>status:</strong> Trạng thái (active/inactive)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Import Variants Section -->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-list"></i> Import Biến Thể Sản Phẩm</h5>
                        <div class="ibox-tools">
                            <a href="{{ route('admin.product.download-template', 'variants') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-download"></i> Tải Template
                            </a>
                            <a href="{{ route('admin.product.export-variants') }}" class="btn btn-info btn-sm">
                                <i class="fa fa-download"></i> Xuất Excel
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-8">
                                <form method="POST" action="{{ route('admin.product.import-variants') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="variant_file">Chọn file Excel biến thể:</label>
                                        <input type="file" name="file" id="variant_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                        <span class="help-block">Hỗ trợ định dạng: .xlsx, .xls, .csv (Tối đa 10MB)</span>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-upload"></i> Import Biến Thể
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h6><i class="fa fa-info-circle"></i> Cấu trúc file biến thể:</h6>
                                    <ul class="mb-0">
                                        <li><strong>product_name:</strong> Tên sản phẩm (bắt buộc)</li>
                                        <li><strong>price:</strong> Giá (bắt buộc)</li>
                                        <li><strong>promotion_price:</strong> Giá khuyến mãi (tùy chọn)</li>
                                        <li><strong>stock_quantity:</strong> Số lượng (bắt buộc)</li>
                                        <li><strong>color_name:</strong> Tên màu (tùy chọn)</li>
                                        <li><strong>storage_capacity:</strong> Dung lượng (tùy chọn)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reference Data Section -->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-database"></i> Dữ Liệu Tham Khảo</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-4">
                                <h6><i class="fa fa-tags"></i> Danh Mục Hiện Có:</h6>
                                <ul class="list-unstyled">
                                    @foreach($categories as $category)
                                        <li><span class="badge badge-primary">{{ $category->name }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="fa fa-palette"></i> Màu Sắc Hiện Có:</h6>
                                <ul class="list-unstyled">
                                    @foreach($colors as $color)
                                        <li><span class="badge badge-success">{{ $color->name }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="fa fa-hdd-o"></i> Dung Lượng Hiện Có:</h6>
                                <ul class="list-unstyled">
                                    @foreach($storages as $storage)
                                        <li><span class="badge badge-info">{{ $storage->capacity }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions Section -->
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-question-circle"></i> Hướng Dẫn Sử Dụng</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fa fa-lightbulb-o"></i> Lưu ý quan trọng:</h6>
                                <ul>
                                    <li>Đảm bảo tên danh mục, màu sắc, dung lượng phải chính xác</li>
                                    <li>Giá khuyến mãi phải nhỏ hơn giá gốc</li>
                                    <li>Số lượng phải là số nguyên dương</li>
                                    <li>File Excel phải có header row (dòng tiêu đề)</li>
                                    <li>Nếu sản phẩm chưa tồn tại, biến thể sẽ không được import</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fa fa-exclamation-triangle"></i> Xử lý lỗi:</h6>
                                <ul>
                                    <li>Các dòng có lỗi sẽ được bỏ qua</li>
                                    <li>Kiểm tra log để xem chi tiết lỗi</li>
                                    <li>Đảm bảo định dạng dữ liệu đúng</li>
                                    <li>Không để trống các trường bắt buộc</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ibox {
            margin-bottom: 20px;
        }
        .ibox-title {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .alert {
            border-radius: 3px;
        }
        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }
        .help-block {
            font-size: 12px;
            color: #666;
        }
    </style>
@endsection
