@extends('layouts.client')

@section('title', 'Top Sản phẩm được yêu thích nhất')

@section('content')
    <section class="module bg-light" id="top-favorites">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="module-title font-alt">Top sản phẩm được yêu thích nhiều nhất</h2>
                    <div class="module-subtitle font-serif">Danh sách những sản phẩm được người dùng đánh dấu yêu thích nhiều nhất</div>
                </div>
            </div>
            <div class="row">
                @forelse($products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="shop-item">
                            <div class="shop-item-image">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('/images/no-image.png') }}"
                                     alt="{{ $product->name }}">
                            </div>
                            <h4 class="shop-item-title font-alt">{{ $product->name }}</h4>
                            <p><strong>Lượt yêu thích:</strong> {{ $product->favorites_count }}</p>
                            <p><strong>Lượt xem:</strong> {{ $product->view }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Không có sản phẩm nào được yêu thích.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
