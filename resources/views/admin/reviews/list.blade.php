@extends('layouts.admin')

@section('title', 'Danh sách đánh giá')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh sách đánh giá sản phẩm</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                </li>
                <li class="active">
                    <strong>Đánh giá</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        @if (session('success'))
            <div class="alert alert-success alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <i class="fa fa-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <i class="fa fa-times"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Search and Filter Form -->
        <div class="ibox-content m-b-sm border-bottom">
            <form action="{{ route('admin.reviews.list') }}" method="GET" class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Tên khách hàng, email, sản phẩm..." class="form-control">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">Sản phẩm</label>
                        <select name="product_id" class="form-control">
                            <option value="">Tất cả sản phẩm</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Đánh giá</label>
                        <select name="rating" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 sao</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 sao</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 sao</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 sao</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 sao</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Chờ duyệt</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('admin.reviews.list') }}" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Làm mới
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Danh sách đánh giá ({{ $reviews->total() }} đánh giá)</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Sản phẩm</th>
                                        <th>Đánh giá</th>
                                        <th>Nội dung</th>
                                        <th>Hình ảnh</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reviews as $review)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $review->name ?: ($review->user ? $review->user->name : 'N/A') }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $review->email ?: ($review->user ? $review->user->email : 'N/A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $review->product ? $review->product->name : 'Sản phẩm đã bị xóa' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fa fa-star"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                    <br>
                                                    <small>({{ $review->rating }}/5)</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="max-width: 200px;">
                                                    {{ Str::limit($review->content, 100) }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($review->image)
                                                    <img src="{{ asset('storage/' . $review->image) }}" alt="Review image"
                                                        class="img-thumbnail" style="max-width: 60px; max-height: 60px;">
                                                @else
                                                    <span class="text-muted">Không có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.reviews.updateStatus', $review->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-control input-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="0"
                                                            {{ $review->status == 0 ? 'selected' : '' }}>Chờ duyệt</option>
                                                        <option value="1"
                                                            {{ $review->status == 1 ? 'selected' : '' }}>Đã duyệt</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <small>{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal"
                                                        data-target="#reviewModal{{ $review->id }}">
                                                        <i class="fa fa-eye"></i> Xem chi tiết
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Review Detail Modal -->
                                        <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                        <h4 class="modal-title">Chi tiết đánh giá</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5>Thông tin khách hàng</h5>
                                                                <p><strong>Tên:</strong>
                                                                    {{ $review->name ?: ($review->user ? $review->user->name : 'N/A') }}
                                                                </p>
                                                                <p><strong>Email:</strong>
                                                                    {{ $review->email ?: ($review->user ? $review->user->email : 'N/A') }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5>Thông tin sản phẩm</h5>
                                                                <p><strong>Sản phẩm:</strong>
                                                                    {{ $review->product ? $review->product->name : 'N/A' }}
                                                                </p>
                                                                <p><strong>Đánh giá:</strong> {{ $review->rating }}/5 sao
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h5>Nội dung đánh giá</h5>
                                                                <p>{{ $review->content }}</p>
                                                            </div>
                                                        </div>
                                                        @if ($review->image)
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h5>Hình ảnh</h5>
                                                                    <img src="{{ asset('storage/' . $review->image) }}"
                                                                        alt="Review image" class="img-responsive"
                                                                        style="max-width: 300px;">
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h5>Thông tin khác</h5>
                                                                <p><strong>Ngày tạo:</strong>
                                                                    {{ $review->created_at->format('d/m/Y H:i:s') }}</p>
                                                                <p><strong>Trạng thái:</strong>
                                                                    <span
                                                                        class="label {{ $review->status == 1 ? 'label-primary' : 'label-warning' }}">
                                                                        {{ $review->status == 1 ? 'Đã duyệt' : 'Chờ duyệt' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="empty-state">
                                                    <i class="fa fa-comments-o fa-3x text-muted"></i>
                                                    <h4>Không có đánh giá nào</h4>
                                                    <p class="text-muted">Chưa có đánh giá nào được tạo trong hệ thống.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($reviews->hasPages())
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info">
                                        Hiển thị {{ $reviews->firstItem() }} đến {{ $reviews->lastItem() }}
                                        trong tổng số {{ $reviews->total() }} đánh giá
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                        {{ $reviews->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .empty-state {
            padding: 40px;
        }

        .table th {
            background-color: #f5f5f6;
            border-bottom: 2px solid #ddd;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .text-warning .fa-star {
            color: #f39c12;
        }

        .text-warning .fa-star-o {
            color: #ddd;
        }
    </style>
@endsection
