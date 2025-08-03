@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <!-- Header Section -->
            <div class="ibox float-e-margins mb-4">
                <div class="ibox-title" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px 8px 0 0;">
                    <h5 style="color: white; margin: 0;">
                        <i class="fa fa-edit"></i> Chỉnh sửa nội dung giới thiệu
                    </h5>
                </div>
            </div>

            <!-- Form Section -->
            <div class="ibox float-e-margins">
                <div class="ibox-title" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 style="color: #495057; margin: 0;">
                        <i class="fa fa-edit"></i> Thông tin nội dung
                    </h5>
                </div>
                <div class="ibox-content" style="padding: 30px; background: white; border-radius: 0 0 8px 8px;">
                    @if ($errors->any())
                        <div class="alert alert-danger" style="border-radius: 8px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.about.update') }}" method="POST" id="aboutForm">
                        @csrf

                        <div class="form-group">
                            <label for="title" style="font-weight: 600; color: #2c3e50; margin-bottom: 8px;">
                                <i class="fa fa-header"></i> Tiêu đề
                            </label>
                            <input type="text" name="title" id="title" class="form-control" 
                                   value="{{ old('title', $about->title) }}" required 
                                   placeholder="Nhập tiêu đề cho trang giới thiệu..."
                                   style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease;">
                            <small class="form-text text-muted">
                                <i class="fa fa-info-circle"></i> Tiêu đề sẽ hiển thị ở đầu trang giới thiệu
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="content" style="font-weight: 600; color: #2c3e50; margin-bottom: 8px;">
                                <i class="fa fa-file-text-o"></i> Nội dung
                            </label>
                            <textarea name="content" id="content" class="form-control" 
                                      placeholder="Nhập nội dung giới thiệu chi tiết..."
                                      style="border-radius: 8px; border: 2px solid #e9ecef; min-height: 400px;">{{ old('content', $about->content) }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fa fa-lightbulb-o"></i> Sử dụng trình soạn thảo để định dạng nội dung đẹp mắt
                            </small>
                        </div>

                        <div class="form-actions" style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #f8f9fa;">
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 30px; border-radius: 25px; font-weight: 600; margin-right: 10px;">
                                <i class="fa fa-save"></i> Cập nhật nội dung
                            </button>
                            <a href="{{ route('admin.about.index') }}" class="btn btn-secondary" style="background: #6c757d; border: none; padding: 12px 30px; border-radius: 25px; font-weight: 600;">
                                <i class="fa fa-times"></i> Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="ibox float-e-margins mt-4">
                <div class="ibox-title" style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <h5 style="color: #495057; margin: 0;">
                        <i class="fa fa-eye"></i> Xem trước
                    </h5>
                </div>
                <div class="ibox-content" style="padding: 30px; background: white; border-radius: 0 0 8px 8px;">
                    <div id="preview-content" style="background: #f8f9fa; padding: 30px; border-radius: 8px; border-left: 4px solid #667eea; min-height: 200px;">
                        <div class="text-center text-muted">
                            <i class="fa fa-eye" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                            <p>Nội dung xem trước sẽ hiển thị ở đây khi bạn nhập thông tin</p>
                        </div>
                    </div>
                </div>
            </div>
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

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

#preview-content {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .form-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endsection

@push('scripts')
<!-- TinyMCE từ jsDelivr không cần API key -->
<script src="{{ asset("assets/external/js/tinymce.min.js") }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing TinyMCE...');

    tinymce.init({
        selector: 'textarea#content',
        height: 500,
        plugins: 'link image lists table code preview',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image | table | code | preview',
        menubar: false,
        content_style: 'body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; font-size: 16px; line-height: 1.6; color: #333; }',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        // Cấu hình link đơn giản
        link_list: [
            {title: 'Trang chủ', value: '/'},
            {title: 'Giới thiệu', value: '/about'},
            {title: 'Liên hệ', value: '/contact'}
        ],

        // Cấu hình ảnh với upload
        image_title: true,
        image_description: true,
        image_dimensions: true,
        automatic_uploads: true,

        // Upload handler cho ảnh
        images_upload_handler: function (blobInfo, success, failure) {
            console.log('Uploading image...', blobInfo.filename());

            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("admin.about.upload-image") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Upload response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Upload success:', data);
                if (data.url) {
                    success(data.url);
                } else {
                    failure('Upload failed: No URL returned');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                failure('Upload failed: ' + error.message);
            });
        },

        // File picker đơn giản
        file_picker_callback: function (callback, value, meta) {
            console.log('File picker called for:', meta.filetype);

            tinymce.activeEditor.windowManager.open({
                title: meta.filetype === 'image' ? 'Chèn hình ảnh' : 'Chèn file',
                body: {
                    type: 'panel',
                    items: [
                        {
                            type: 'input',
                            name: 'url',
                            label: 'URL:',
                            placeholder: meta.filetype === 'image' ? 'https://example.com/image.jpg' : 'https://example.com/file.pdf'
                        },
                        {
                            type: 'input',
                            name: 'alt',
                            label: 'Alt text (cho ảnh):',
                            placeholder: 'Mô tả hình ảnh'
                        }
                    ]
                },
                buttons: [
                    {
                        type: 'submit',
                        text: 'Chèn'
                    },
                    {
                        type: 'cancel',
                        text: 'Hủy'
                    }
                ],
                onSubmit: function (api) {
                    const data = api.getData();
                    if (data.url) {
                        if (meta.filetype === 'image') {
                            callback(data.url, {alt: data.alt || ''});
                        } else {
                            callback(data.url);
                        }
                    }
                    api.close();
                }
            });
        },

        setup: function (editor) {
            console.log('TinyMCE setup called');
            editor.on('init', function () {
                console.log('TinyMCE initialized successfully');
                document.getElementById('content').removeAttribute('required');
                updatePreview(); // Update preview with existing content
            });

            editor.on('change', function () {
                updatePreview();
            });
        },

        // Cấu hình khác
        toolbar_mode: 'sliding',
        contextmenu: 'link image table configurepermanentpen',
        auto_save: true,
        auto_save_interval: '30s',
        auto_save_retention: '1440m'
    });

    // Preview functionality
    function updatePreview() {
        const title = document.getElementById('title').value;
        const content = tinymce.get('content') ? tinymce.get('content').getContent() : '';
        const previewDiv = document.getElementById('preview-content');

        if (title || content) {
            let previewHtml = '';
            
            if (title) {
                previewHtml += `<h2 style="color: #2c3e50; font-size: 2.2rem; font-weight: 700; margin-bottom: 1rem; text-align: center;">${title}</h2>`;
                previewHtml += `<div style="width: 80px; height: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0 auto 2rem auto; border-radius: 2px;"></div>`;
            }
            
            if (content) {
                previewHtml += content;
            }
            
            previewDiv.innerHTML = previewHtml;
        } else {
            previewDiv.innerHTML = `
                <div class="text-center text-muted">
                    <i class="fa fa-eye" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>Nội dung xem trước sẽ hiển thị ở đây khi bạn nhập thông tin</p>
                </div>
            `;
        }
    }

    // Update preview when title changes
    document.getElementById('title').addEventListener('input', updatePreview);
});
</script>
@endpush
