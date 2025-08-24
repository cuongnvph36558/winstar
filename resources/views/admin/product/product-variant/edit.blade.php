@extends('layouts.admin')
@section('title', 'Chỉnh Sửa Biến Thể Sản Phẩm')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2><i class="fa fa-edit text-primary"></i> Chỉnh Sửa Biến Thể Sản Phẩm</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.product.index-product') }}"><i class="fa fa-home"></i> Sản Phẩm</a></li>
                <li><a href="{{ route('admin.product.show-product', $variant->product_id) }}">{{ $variant->product->name }}</a></li>
                <li class="active"><strong>Chỉnh Sửa Biến Thể</strong></li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a href="{{ route('admin.product.show-product', $variant->product_id) }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-cube"></i> Thông Tin Biến Thể</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="fa fa-exclamation-triangle"></i> Có lỗi xảy ra!</h4>
                                <ul class="m-b-none">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fa fa-exclamation-circle"></i> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.product.product-variant.update', $variant->id) }}"
                            enctype="multipart/form-data" class="form-horizontal" id="editVariantForm">
                            @csrf
                            @method('PUT')

                            <!-- Product Information Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-info-circle"></i> Thông Tin Biến Thể</h3>
                                        </div>
                                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tên Biến Thể</label>
                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-cube"></i></span>
                                    <input type="text" value="{{ $variant->variant_name }}" class="form-control" readonly>
                                                    </div>
                                                    <span class="help-block m-b-none text-muted">Tên biến thể được tạo tự động từ tên sản phẩm + màu sắc + dung lượng</span>
                                                </div>
                                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sản Phẩm Gốc</label>
                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                    <input type="text" value="{{ $variant->product->name }}" class="form-control" readonly>
                                                    </div>
                                                    <span class="help-block m-b-none text-muted">Sản phẩm không thể thay đổi</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Variant Details Section -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-cog"></i> Thông Tin Cơ Bản</h3>
                                        </div>
                                        <div class="panel-body">


                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Giá Gốc <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                        <input type="number" name="price" 
                                                            value="{{ old('price', $variant->price) }}"
                                                            class="form-control" required 
                                                            placeholder="0" min="0" step="1000">
                                                        <span class="input-group-addon">VNĐ</span>
                                                    </div>
                                                    <span class="help-block m-b-none">Giá bán của biến thể</span>
                                </div>
                            </div>

                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Giá Khuyến Mãi</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                                        <input type="number" name="promotion_price" 
                                                            value="{{ old('promotion_price', $variant->promotion_price) }}"
                                                            class="form-control" 
                                                            placeholder="0" min="0" step="1000">
                                                        <span class="input-group-addon">VNĐ</span>
                                                    </div>
                                                    <span class="help-block m-b-none">Giá khuyến mãi (tùy chọn)</span>
                                </div>
                            </div>

                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Số Lượng <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                    <input type="number" name="stock_quantity"
                                                            value="{{ old('stock_quantity', $variant->stock_quantity) }}" 
                                                            class="form-control" required 
                                                            placeholder="0" min="0">
                                                        <span class="input-group-addon">cái</span>
                                                    </div>
                                                    <span class="help-block m-b-none">Số lượng tồn kho</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-palette"></i> Thuộc Tính</h3>
                            </div>
                                        <div class="panel-body">
                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Màu Sắc <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-palette"></i></span>
                                    <select name="color_id" class="form-control" required>
                                                            <option value="">Chọn màu sắc</option>
                                        @foreach($colors as $color)
                                                                <option value="{{ $color->id }}" 
                                                                    {{ old('color_id', $variant->color_id) == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                                    </div>
                                                    <span class="help-block m-b-none">Màu sắc của biến thể</span>
                                </div>
                            </div>

                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Dung Lượng <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-hdd-o"></i></span>
                                    <select name="storage_id" class="form-control" required>
                                                            <option value="">Chọn dung lượng</option>
                                        @foreach($storages as $storage)
                                                                <option value="{{ $storage->id }}" 
                                                                    {{ old('storage_id', $variant->storage_id) == $storage->id ? 'selected' : '' }}>
                                                {{ $storage->capacity }}
                                            </option>
                                        @endforeach
                                    </select>
                                                    </div>
                                                    <span class="help-block m-b-none">Dung lượng bộ nhớ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Images Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-image"></i> Hình Ảnh Biến Thể</h3>
                                        </div>
                                        <div class="panel-body">
                                            <!-- Current Images -->
                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Ảnh Hiện Tại</label>
                                <div class="col-sm-9">
                                    @if($variant->image_variant)
                                        @php
                                            $images = is_string($variant->image_variant) ? json_decode($variant->image_variant, true) : $variant->image_variant;
                                        @endphp
                                        @if($images && count($images) > 0)
                                            <div class="row">
                                                                @foreach($images as $index => $image)
                                                                    <div class="col-md-3 col-sm-4 col-xs-6 mb-3">
                                                                        <div class="image-preview-container">
                                                                            <img src="{{ asset('storage/' . $image) }}" 
                                                                                alt="Product variant image {{ $index + 1 }}"
                                                                                class="img-responsive img-thumbnail variant-image"
                                                                                data-toggle="tooltip" 
                                                                                title="Ảnh {{ $index + 1 }}">
                                                                            <div class="image-overlay">
                                                                                <span class="image-number">{{ $index + 1 }}</span>
                                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                                            <div class="alert alert-info">
                                                                <i class="fa fa-info-circle"></i> Chưa có ảnh nào được tải lên
                                                            </div>
                                        @endif
                                    @else
                                                        <div class="alert alert-info">
                                                            <i class="fa fa-info-circle"></i> Chưa có ảnh nào được tải lên
                                                        </div>
                                    @endif
                                </div>
                            </div>

                                            <!-- Upload New Images -->
                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Tải Ảnh Mới</label>
                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-upload"></i></span>
                                                        <input type="file" name="image_variant[]" 
                                                            class="form-control" accept="image/*" multiple
                                                            id="imageUpload">
                                                    </div>
                                                    <span class="help-block m-b-none">
                                                        <i class="fa fa-info-circle"></i> 
                                                        Tải lên ảnh mới (JPG, PNG, WebP - Tối đa 2MB mỗi ảnh). 
                                                        Ảnh mới sẽ thay thế ảnh cũ.
                                                    </span>
                                                    
                                                    <!-- Image Preview -->
                                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                                        <h5><i class="fa fa-eye"></i> Xem Trước Ảnh</h5>
                                                        <div class="row" id="previewContainer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-body text-center">
                                    <a href="{{ route('admin.product.show-product', $variant->product_id) }}"
                                                class="btn btn-default btn-lg">
                                                <i class="fa fa-times"></i> Hủy Bỏ
                                    </a>
                                            <button class="btn btn-primary btn-lg" type="submit" id="submitBtn">
                                                <i class="fa fa-save"></i> Cập Nhật Biến Thể
                                    </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .panel {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .panel-heading {
            border-radius: 8px 8px 0 0;
            padding: 15px 20px;
        }
        
        .panel-title {
            font-weight: 600;
            margin: 0;
        }
        
        .panel-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .control-label {
            font-weight: 600;
            color: #333;
            padding-top: 8px;
        }
        
        .input-group-addon {
            background-color: #f8f9fa;
            border-color: #ddd;
            color: #555;
        }
        
        .form-control {
            border-radius: 4px;
            border: 1px solid #ddd;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            border-color: #5bc0de;
            box-shadow: 0 0 0 0.2rem rgba(91, 192, 222, 0.25);
        }
        
        .help-block {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .text-danger {
            color: #dc3545;
        }
        
        .image-preview-container {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .image-preview-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .variant-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .image-preview-container:hover .image-overlay {
            opacity: 1;
        }
        
        .image-number {
            color: white;
            font-weight: bold;
            font-size: 18px;
            background: rgba(0,0,0,0.7);
            padding: 5px 10px;
            border-radius: 20px;
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 10px 20px;
            margin: 0 5px;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .btn-lg {
            padding: 12px 30px;
            font-size: 16px;
        }
        
        .alert {
            border-radius: 6px;
            border: none;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 8px 0;
            margin-bottom: 0;
        }
        
        .breadcrumb > li + li:before {
            content: "›";
            color: #999;
        }
        
        .page-heading {
            padding: 20px 0;
            border-bottom: 1px solid #e7eaec;
        }
        
        .title-action {
            padding-top: 15px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-sm-3.control-label {
                text-align: left;
                padding-bottom: 5px;
            }
            
            .col-sm-9 {
                margin-bottom: 15px;
            }
            
            .btn-lg {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
        
        /* Loading state */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        
        .btn-loading:after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Image preview functionality
            $('#imageUpload').change(function() {
                const files = this.files;
                const previewContainer = $('#previewContainer');
                const imagePreview = $('#imagePreview');
                
                if (files.length > 0) {
                    imagePreview.show();
                    previewContainer.empty();
                    
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewHtml = `
                                    <div class="col-md-3 col-sm-4 col-xs-6 mb-3">
                                        <div class="image-preview-container">
                                            <img src="${e.target.result}" 
                                                 alt="Preview ${i + 1}"
                                                 class="img-responsive img-thumbnail variant-image">
                                            <div class="image-overlay">
                                                <span class="image-number">${i + 1}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                previewContainer.append(previewHtml);
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                } else {
                    imagePreview.hide();
                }
            });
            
            // Form submission with loading state
            $('#editVariantForm').submit(function() {
                const submitBtn = $('#submitBtn');
                submitBtn.addClass('btn-loading').prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Đang Cập Nhật...');
            });
            
            // Price validation
            $('input[name="price"]').on('input', function() {
                const price = parseFloat($(this).val()) || 0;
                const promotionPrice = parseFloat($('input[name="promotion_price"]').val()) || 0;
                
                if (promotionPrice > 0 && promotionPrice >= price) {
                    $('input[name="promotion_price"]').addClass('is-invalid');
                    $('input[name="promotion_price"]').next('.help-block').html(
                        '<i class="fa fa-exclamation-triangle text-danger"></i> Giá khuyến mãi phải nhỏ hơn giá gốc'
                    );
                } else {
                    $('input[name="promotion_price"]').removeClass('is-invalid');
                    $('input[name="promotion_price"]').next('.help-block').html(
                        '<i class="fa fa-info-circle"></i> Giá khuyến mãi (tùy chọn)'
                    );
                }
            });
            
            $('input[name="promotion_price"]').on('input', function() {
                const promotionPrice = parseFloat($(this).val()) || 0;
                const price = parseFloat($('input[name="price"]').val()) || 0;
                
                if (promotionPrice > 0 && promotionPrice >= price) {
                    $(this).addClass('is-invalid');
                    $(this).next('.help-block').html(
                        '<i class="fa fa-exclamation-triangle text-danger"></i> Giá khuyến mãi phải nhỏ hơn giá gốc'
                    );
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.help-block').html(
                        '<i class="fa fa-info-circle"></i> Giá khuyến mãi (tùy chọn)'
                    );
                }
            });
        });
    </script>
@endsection
