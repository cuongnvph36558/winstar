@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Sản phẩm được yêu thích nhiều nhất</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('admin.favorite.create') }}" class="btn btn-primary btn-xs">
                            <i class="fa fa-plus"></i> Thêm sản phẩm yêu thích
                        </a>
                    </div>

                </div>
                <div class="ibox-content">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <th>Lượt yêu thích</th>
                                    <th>Lượt xem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="img-thumbnail"
                                            style="max-width: 100px;">
                                        @endif
                                    </td>
                                    <td>{{ $product->favorites_count }}</td>
                                    <td>{{ $product->view }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có sản phẩm nào được yêu thích.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection