@extends('layouts.admin')
@section('title', 'Thêm sản phẩm')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2><i class="fa fa-plus-circle text-primary"></i> Thêm Sản Phẩm Mới</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.product.index-product') }}"><i class="fa fa-home"></i> Sản Phẩm</a></li>
                <li class="active"><strong>Thêm Mới</strong></li>
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

                        <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data"
                            class="form-horizontal" id="createProductForm">
                            @csrf

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
                                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required
                                                            placeholder="Nhập tên sản phẩm" maxlength="255">
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
                                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                                            placeholder="Nhập mô tả chi tiết về sản phẩm">{{ old('description') }}</textarea>
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
                                                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                                                Hoạt động
                                                            </option>
                                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                                                Không hoạt động
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
                                                         <p>Sản phẩm này sẽ được tạo dưới dạng sản phẩm cơ bản. Bạn có thể thêm biến thể và giá cả sau khi tạo sản phẩm.</p>
                                                     </div>
                                                 </div>
                                                 
                                                 <div class="info-item">
                                                     <i class="fa fa-cogs text-primary"></i>
                                                     <div class="info-content">
                                                         <h5>Bước tiếp theo</h5>
                                                         <p>Sau khi tạo sản phẩm, bạn có thể:</p>
                                                                                                                   <ul class="feature-list">
                                                              <li>Thêm biến thể sản phẩm</li>
                                                              <li>Thiết lập giá cả</li>
                                                              <li>Thêm hình ảnh chi tiết</li>
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
                                                <!-- Upload Image -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Tải Ảnh Sản Phẩm</label>
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
                                                    </div>
                                                </div>

                                                <!-- Image Preview -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Xem Trước Ảnh</label>
                                                        <div id="imagePreview" class="mt-3" style="display: none;">
                                                            <div class="preview-container">
                                                                <img id="previewImage" src="" 
                                                                     alt="Preview" 
                                                                     class="img-responsive img-thumbnail">
                                                            </div>
                                                        </div>
                                                        <div id="noImagePlaceholder" class="no-image-placeholder">
                                                            <i class="fa fa-image fa-3x text-muted"></i>
                                                            <p class="text-muted mt-2">Chưa chọn ảnh</p>
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
                                                <i class="fa fa-save"></i> Tạo Sản Phẩm
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
            // Image preview functionality
            $('#imageUpload').change(function() {
                const file = this.files[0];
                const imagePreview = $('#imagePreview');
                const previewImage = $('#previewImage');
                const noImagePlaceholder = $('#noImagePlaceholder');
                
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.attr('src', e.target.result);
                        imagePreview.show();
                        noImagePlaceholder.hide();
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.hide();
                    noImagePlaceholder.show();
                }
            });
            
            // Form submission with loading state
            $('#createProductForm').submit(function() {
                const submitBtn = $('#submitBtn');
                submitBtn.addClass('btn-loading').prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Đang Tạo...');
            });
            
                         // Form validation
             $('#createProductForm').on('submit', function(e) {
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
