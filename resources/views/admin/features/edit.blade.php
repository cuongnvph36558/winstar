@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chỉnh sửa</h5>
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

                    <form action="{{ route('admin.features.update', $feature->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ $feature->title }}" required>
                        </div>
                        <div class="form-group">
                            <label>Phụ đề</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ $feature->subtitle }}">
                        </div>
                        <div class="form-group">
                            <label>Ảnh hiện tại</label><br>
                            @if($feature->image)
                                <img src="{{ asset('storage/' . $feature->image) }}" alt="Hình ảnh hiện tại" style="max-width: 200px" class="mb-2">
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Chọn hình ảnh mới (nếu muốn thay)</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <hr>
                        <h4>Các mục nổi bật</h4>
                        <div id="feature-items">
                            @foreach($feature->items as $index => $item)
                                <div class="feature-item border p-2 mb-2">
                                    <div class="form-group">
                                        <label>Icon</label>
                                        <input type="text" name="items[{{ $index }}][icon]" class="form-control" value="{{ $item->icon }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tiêu đề</label>
                                        <input type="text" name="items[{{ $index }}][title]" class="form-control" value="{{ $item->title }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả</label>
                                        <textarea name="items[{{ $index }}][description]" class="form-control" required>{{ $item->description }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-secondary btn-sm" onclick="addFeatureItem()">
                            <i class="fa fa-plus"></i> Thêm mục
                        </button>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.features.index') }}" class="btn btn-default">Hủy</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let featureIndex = {{ $feature->items->count() }};
    function addFeatureItem() {
        const html = `
            <div class="feature-item border p-2 mb-2">
                <div class="form-group">
                    <label>Icon</label>
                    <input type="text" name="items[\${featureIndex}][icon]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tiêu đề</label>
                    <input type="text" name="items[\${featureIndex}][title]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="items[\${featureIndex}][description]" class="form-control" required></textarea>
                </div>
            </div>
        `;
        document.getElementById('feature-items').insertAdjacentHTML('beforeend', html);
        featureIndex++;
    }
</script>
@endsection
