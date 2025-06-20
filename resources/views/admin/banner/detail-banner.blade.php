@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title d-flex justify-content-between align-items-center">
                    <h5>Chi tiết Banner</h5>
                    <div>
                        <a href="{{ route('admin.banner.index-banner') }}" class="btn btn-white btn-sm">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="{{ route('admin.banner.edit-banner', $banner->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Sửa
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-bold">Tiêu đề:</label>
                                <p>{{ $banner->title }}</p>
                            </div>

                            <div class="form-group">
                                <label class="font-bold">Liên kết:</label>
                                <p>{{ $banner->link ?: 'Không có' }}</p>
                            </div>

                            <div class="form-group">
                                <label class="font-bold">Trạng thái:</label>
                                <p>
                                    <span class="label {{ $banner->status == '1' ? 'label-primary' : 'label-default' }}">
                                        {{ $banner->status == '1' ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="font-bold">Thời gian:</label>
                                <p>
                                    {{ $banner->start_date ? 'Từ ' . $banner->start_date : '' }}
                                    {{ $banner->end_date ? ' đến ' . $banner->end_date : '' }}
                                    {{ !$banner->start_date && !$banner->end_date ? 'Không giới hạn' : '' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-bold">Hình ảnh:</label>
                                <div class="image-container">
                                    <img src="{{ asset('storage/' . $banner->image_url) }}" 
                                         alt="{{ $banner->title }}"
                                         class="img-fluid rounded"
                                         style="max-width: 400px; max-height: 300px; width: 100%; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
