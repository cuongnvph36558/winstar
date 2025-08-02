@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chỉnh sửa nội dung Giới thiệu</h5>
                </div>
                <div class="ibox-content">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.about.update') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $about->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="content">Nội dung</label>
                            <textarea name="content" id="content" class="form-control" rows="10">{{ old('content', $about->content) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.about.index') }}" class="btn btn-default">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <!-- TinyMCE từ jsDelivr không cần API key -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        // Đợi trang load xong
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing TinyMCE...');

            tinymce.init({
                selector: 'textarea#content',
                height: 500,
                plugins: 'link image lists table code',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table | code',
                menubar: false,
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
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

                // Upload handler cho ảnh - đơn giản hơn
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

                // File picker đơn giản - chỉ cho phép nhập URL
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
                    });

                    editor.on('change', function () {
                        console.log('Content changed');
                    });
                },

                // Cấu hình khác
                toolbar_mode: 'sliding',
                contextmenu: 'link image table configurepermanentpen',
                auto_save: true,
                auto_save_interval: '30s',
                auto_save_retention: '1440m'
            });
        });
    </script>
@endpush
