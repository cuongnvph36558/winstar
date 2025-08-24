@extends('layouts.admin')
@section('title', 'Chỉnh Sửa Sản Phẩm')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2><i class="fa fa-edit text-primary"></i> Chỉnh Sửa Sản Phẩm</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.product.index-product') }}"><i class="fa fa-home"></i> Sản Phẩm</a></li>
                <li class="active"><strong>{{ $product->name }}</strong></li>
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
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-cube"></i> Thông Tin Sản Phẩm</h5>
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

                        <form method="POST" action="{{ route('admin.product.update-product', $product->id) }}"
                            enctype="multipart/form-data" class="form-horizontal" id="editProductForm">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information Section -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-info-circle"></i> Thông Tin Cơ Bản</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                                        <input type="text" name="name" 
                                                            value="{{ old('name', $product->name) }}"
                                                            class="form-control" required 
                                                            placeholder="Nhập tên sản phẩm">
                                                    </div>
                                                    <span class="help-block m-b-none">Tên hiển thị của sản phẩm</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Danh Mục <span class="text-danger">*</span></label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-folder"></i></span>
                                                        <select name="category_id" class="form-control" required>
                                                            <option value="">-- Chọn danh mục --</option>
                                                            @foreach ($categories as $cat)
                                                                <option value="{{ $cat->id }}" 
                                                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                                                    {{ $cat->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <span class="help-block m-b-none">Danh mục phù hợp cho sản phẩm</span>
                                                </div>
                                            </div>

                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Mô Tả</label>
                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-align-left"></i></span>
                                                        <textarea name="description" rows="4" class="form-control"
                                                            placeholder="Nhập mô tả chi tiết về sản phẩm">{{ old('description', $product->description) }}</textarea>
                                                    </div>
                                                    <span class="help-block m-b-none">Mô tả chi tiết về tính năng và đặc điểm sản phẩm</span>
                                </div>
                            </div>

                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Trạng Thái</label>
                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-toggle-on"></i></span>
                                                        <select name="status" class="form-control">
                                                            <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>
                                                                <i class="fa fa-check"></i> Hoạt động
                                                            </option>
                                                            <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>
                                                                <i class="fa fa-times"></i> Không hoạt động
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <span class="help-block m-b-none">Trạng thái hiển thị sản phẩm</span>
                                                </div>
                                            </div>

                                            
                                        </div>
                                    </div>
                                </div>

                                                                 <div class="col-md-4">
                                     <div class="panel panel-info">
                                         <div class="panel-heading">
                                             <h3 class="panel-title"><i class="fa fa-info-circle"></i> Thông Tin Bổ Sung</h3>
                                         </div>
                                         <div class="panel-body">
                                             <div class="info-box">
                                                 <div class="info-item">
                                                     <i class="fa fa-lightbulb-o text-warning"></i>
                                                     <div class="info-content">
                                                         <h5>Lưu ý</h5>
                                                         <p>Sản phẩm này được quản lý dưới dạng sản phẩm cơ bản. Giá cả sẽ được thiết lập thông qua biến thể sản phẩm.</p>
                                                     </div>
                                                 </div>
                                                 
                                                 <div class="info-item">
                                                     <i class="fa fa-cogs text-primary"></i>
                                                     <div class="info-content">
                                                         <h5>Quản lý giá</h5>
                                                         <p>Để thiết lập giá cho sản phẩm:</p>
                                                         <ul class="feature-list">
                                                             <li>Tạo biến thể sản phẩm</li>
                                                             <li>Thiết lập giá cho từng biến thể</li>
                                                             <li>Quản lý tồn kho theo biến thể</li>
                                                         </ul>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                            </div>

                            <!-- Image Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-image"></i> Hình Ảnh Sản Phẩm</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <!-- Current Image -->
                                                <div class="col-md-6">
                            <div class="form-group">
                                                        <label class="control-label">Ảnh Hiện Tại</label>
                                                        @if($product->image)
                                                            <div class="current-image-container">
                                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                                    alt="{{ $product->name }}"
                                                                    class="img-responsive img-thumbnail product-image"
                                                                    data-toggle="tooltip" 
                                                                    title="Ảnh hiện tại">
                                                                <div class="image-overlay">
                                                                    <span class="image-label">Ảnh hiện tại</span>
                                </div>
                            </div>
                                                        @else
                                                            <div class="no-image-placeholder">
                                                                <i class="fa fa-image fa-3x text-muted"></i>
                                                                <p class="text-muted mt-2">Chưa có ảnh</p>
                                                            </div>
                                                        @endif
                                </div>
                            </div>

                                                <!-- Upload New Image -->
                                                <div class="col-md-6">
                            <div class="form-group">
                                                        <label class="control-label">Tải Ảnh Mới</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-upload"></i></span>
                                                            <input type="file" name="image" 
                                                                class="form-control" accept="image/*"
                                                                id="imageUpload">
                                                        </div>
                                                        <span class="help-block m-b-none">
                                                            <i class="fa fa-info-circle"></i> 
                                                            Định dạng: JPG, JPEG, PNG, WEBP (tối đa 2MB)
                                                        </span>
                                                        
                                                        <!-- Image Preview -->
                                                        <div id="imagePreview" class="mt-3" style="display: none;">
                                                            <h5><i class="fa fa-eye"></i> Xem Trước Ảnh</h5>
                                                            <div class="preview-container">
                                                                <img id="previewImage" src="" 
                                                                     alt="Preview" 
                                                                     class="img-responsive img-thumbnail">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Statistics -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Thống Kê Sản Phẩm</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-6">
                                                    <div class="stat-item">
                                                        <div class="stat-icon">
                                                            <i class="fa fa-cubes text-primary"></i>
                                                        </div>
                                                        <div class="stat-content">
                                                            <div class="stat-number">{{ $product->variants->count() }}</div>
                                                            <div class="stat-label">Biến Thể</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6">
                                                    <div class="stat-item">
                                                        <div class="stat-icon">
                                                            <i class="fa fa-eye text-success"></i>
                                                        </div>
                                                        <div class="stat-content">
                                                            <div class="stat-number">{{ $product->views ?? 0 }}</div>
                                                            <div class="stat-label">Lượt Xem</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6">
                                                    <div class="stat-item">
                                                        <div class="stat-icon">
                                                            <i class="fa fa-calendar text-warning"></i>
                                                        </div>
                                                        <div class="stat-content">
                                                            <div class="stat-number">{{ $product->created_at ? $product->created_at->format('d/m/Y') : 'N/A' }}</div>
                                                            <div class="stat-label">Ngày Tạo</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6">
                                                    <div class="stat-item">
                                                        <div class="stat-icon">
                                                            <i class="fa fa-clock-o text-info"></i>
                                                        </div>
                                                        <div class="stat-content">
                                                            <div class="stat-number">{{ $product->updated_at ? $product->updated_at->format('d/m/Y') : 'N/A' }}</div>
                                                            <div class="stat-label">Cập Nhật Cuối</div>
                                                        </div>
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
                                            <a href="{{ route('admin.product.index-product') }}"
                                                class="btn btn-default btn-lg">
                                                <i class="fa fa-times"></i> Hủy Bỏ
                                            </a>
                                            <button class="btn btn-primary btn-lg" type="submit" id="submitBtn">
                                                <i class="fa fa-save"></i> Cập Nhật Sản Phẩm
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
        
        .current-image-container {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .current-image-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
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
        
        .current-image-container:hover .image-overlay {
            opacity: 1;
        }
        
        .image-label {
            color: white;
            font-weight: bold;
            font-size: 16px;
            background: rgba(0,0,0,0.7);
            padding: 8px 16px;
            border-radius: 20px;
        }
        
        .no-image-placeholder {
            text-align: center;
            padding: 40px 20px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            color: #6c757d;
        }
        
        .preview-container {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        #previewImage {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
                 .info-box {
             background: #f8f9fa;
             border-radius: 6px;
             padding: 15px;
         }
         
         .info-item {
             display: flex;
             align-items: flex-start;
             margin-bottom: 20px;
             padding: 15px;
             background: white;
             border-radius: 6px;
             box-shadow: 0 1px 3px rgba(0,0,0,0.1);
         }
         
         .info-item:last-child {
             margin-bottom: 0;
         }
         
         .info-item i {
             font-size: 20px;
             margin-right: 15px;
             margin-top: 2px;
         }
         
         .info-content h5 {
             margin: 0 0 8px 0;
             font-weight: 600;
             color: #333;
         }
         
         .info-content p {
             margin: 0 0 10px 0;
             color: #666;
             line-height: 1.5;
         }
         
         .feature-list {
             margin: 0;
             padding-left: 20px;
             color: #666;
         }
         
         .feature-list li {
             margin-bottom: 5px;
             line-height: 1.4;
         }
        
        .stat-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .stat-icon {
            font-size: 24px;
            margin-right: 15px;
            width: 40px;
            text-align: center;
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 2px;
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
            
            .stat-item {
                margin-bottom: 15px;
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
                const file = this.files[0];
                const imagePreview = $('#imagePreview');
                const previewImage = $('#previewImage');
                
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.attr('src', e.target.result);
                        imagePreview.show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.hide();
                }
            });
            
            // Form submission with loading state
            $('#editProductForm').submit(function() {
                const submitBtn = $('#submitBtn');
                submitBtn.addClass('btn-loading').prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Đang Cập Nhật...');
            });
            
                         // Form validation
             $('#editProductForm').on('submit', function(e) {
                 const name = $('input[name="name"]').val().trim();
                 const category = $('select[name="category_id"]').val();
                 
                 if (!name) {
                     alert('Vui lòng nhập tên sản phẩm');
                     e.preventDefault();
                     return false;
                 }
                 
                 if (!category) {
                     alert('Vui lòng chọn danh mục sản phẩm');
                     e.preventDefault();
                     return false;
                 }
             });
        });
    </script>
@endsection
