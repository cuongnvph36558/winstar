@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <!-- Header Section -->
            <div class="ibox float-e-margins mb-4">
                <div class="ibox-title" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px 8px 0 0;">
                    <h5 style="color: white; margin: 0;">
                        <i class="fa fa-info-circle"></i> Quản lý trang giới thiệu
                    </h5>
                    <div class="ibox-tools">
                        @if($about)
                            <a href="{{ route('admin.about.edit') }}" class="btn btn-light btn-xs" style="color: #667eea;">
                                <i class="fa fa-edit"></i> Chỉnh sửa nội dung
                            </a>
                        @else
                            <a href="{{ route('admin.about.create') }}" class="btn btn-light btn-xs" style="color: #667eea;">
                                <i class="fa fa-plus"></i> Thêm mới nội dung
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissable" style="border-radius: 8px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Content Section -->
            <div class="ibox float-e-margins">
                <div class="ibox-title" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 style="color: #495057; margin: 0;">
                        <i class="fa fa-file-text-o"></i> Nội dung hiện tại
                    </h5>
                </div>
                <div class="ibox-content" style="padding: 30px; background: white; border-radius: 0 0 8px 8px;">
                    @if($about)
                        <div class="about-preview">
                            <div class="about-header text-center mb-4">
                                <h2 style="color: #2c3e50; font-size: 2.2rem; font-weight: 700; margin-bottom: 1rem;">
                                    {{ $about->title }}
                                </h2>
                                <div style="width: 80px; height: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0 auto; border-radius: 2px;"></div>
                            </div>
                            
                            <div class="about-content" style="font-size: 1.1rem; line-height: 1.8; color: #555; text-align: justify; background: #f8f9fa; padding: 30px; border-radius: 8px; border-left: 4px solid #667eea;">
                                {!! $about->content !!}
                            </div>
                            
                            <div class="about-meta mt-4" style="background: #e9ecef; padding: 20px; border-radius: 8px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-calendar"></i> Ngày tạo:</strong> 
                                        {{ $about->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fa fa-edit"></i> Cập nhật lần cuối:</strong> 
                                        {{ $about->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state text-center" style="padding: 60px 20px;">
                            <div style="font-size: 4rem; color: #dee2e6; margin-bottom: 1rem;">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <h3 style="color: #6c757d; margin-bottom: 1rem;">Chưa có nội dung giới thiệu</h3>
                            <p style="color: #6c757d; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                                Hiện tại trang giới thiệu chưa có nội dung. Vui lòng thêm nội dung để người dùng có thể xem thông tin về website.
                            </p>
                            <a href="{{ route('admin.about.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 30px; border-radius: 25px; font-weight: 600;">
                                <i class="fa fa-plus"></i> Thêm nội dung giới thiệu
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            @if($about)
                <div class="ibox float-e-margins mt-4">
                    <div class="ibox-content text-center" style="background: #f8f9fa; border-radius: 8px; padding: 20px;">
                        <a href="{{ route('admin.about.edit') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 30px; border-radius: 25px; font-weight: 600; margin-right: 10px;">
                            <i class="fa fa-edit"></i> Chỉnh sửa nội dung
                        </a>
                        <a href="{{ route('admin.about.create') }}" class="btn btn-success" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; padding: 12px 30px; border-radius: 25px; font-weight: 600;">
                            <i class="fa fa-plus"></i> Tạo nội dung mới
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.ibox {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
}

.ibox-title {
    border-radius: 8px 8px 0 0;
    border: none;
}

.ibox-content {
    border-radius: 0 0 8px 8px;
}

.about-preview {
    max-width: 800px;
    margin: 0 auto;
}

.about-content h1, .about-content h2, .about-content h3 {
    color: #2c3e50;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.about-content p {
    margin-bottom: 1rem;
}

.about-content ul, .about-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.about-content li {
    margin-bottom: 0.5rem;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.empty-state {
    background: white;
    border-radius: 8px;
}

@media (max-width: 768px) {
    .about-header h2 {
        font-size: 1.8rem;
    }
    
    .about-content {
        font-size: 1rem;
        padding: 20px;
    }
    
    .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endsection
