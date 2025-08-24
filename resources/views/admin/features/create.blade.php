@extends('layouts.admin')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Thêm mới</h5>
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

                        <form action="{{ route('admin.features.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Tiêu đề</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Phụ đề</label>
                                <input type="text" name="subtitle" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Chọn hình ảnh</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            <hr>
                            <h4>Các mục nổi bật</h4>
                            <div id="feature-items">
                                <div class="feature-item border p-2 mb-2">
                                    <div class="form-group">
                                        <label>Icon Class (ví dụ: fa fa-star, icon-strategy)</label>
                                        <input type="text" name="items[0][icon]" class="form-control" placeholder="fa fa-star" required>
                                        <small class="form-text text-muted">Nhập tên class của icon (FontAwesome, ET Line, etc.)</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Tiêu đề</label>
                                        <input type="text" name="items[0][title]" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả</label>
                                        <textarea name="items[0][description]" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary btn-sm" onclick="addFeatureItem()">
                                <i class="fa fa-plus"></i> Thêm mục
                            </button>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Lưu</button>
                                <a href="{{ route('admin.features.index') }}" class="btn btn-default">Hủy</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let featureIndex = 1;
        function addFeatureItem() {
            if (featureIndex >= 4) {
                alert('Chỉ được thêm tối đa 4 icon.');
                return;
            }

            const html = `
                <div class="feature-item border p-2 mb-2">
                    <div class="form-group">
                        <label>Icon Class (ví dụ: fa fa-star, icon-strategy)</label>
                        <input type="text" name="items[${featureIndex}][icon]" class="form-control" placeholder="fa fa-star" required>
                        <small class="form-text text-muted">Nhập tên class của icon (FontAwesome, ET Line, etc.)</small>
                    </div>
                    <div class="form-group">
                        <label>Tiêu đề</label>
                        <input type="text" name="items[${featureIndex}][title]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="items[${featureIndex}][description]" class="form-control" required></textarea>
                    </div>
                </div>
            `;
            document.getElementById('feature-items').insertAdjacentHTML('beforeend', html);
            featureIndex++;
        }
    </script>
@endsection
