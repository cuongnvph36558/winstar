@extends('layouts.client')

@section('title', 'Chờ thanh toán')

@section('content')
<section class="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="alert alert-warning text-center" style="margin-top: 40px;">
                    <h3><i class="fa fa-clock-o"></i> Đơn hàng đang chờ thanh toán</h3>
                    <p>{{ isset(
$message) ? $message : 'Đơn hàng của bạn chưa được thanh toán thành công. Vui lòng kiểm tra lại hoặc thử lại thanh toán.' }}</p>
                    <a href="{{ route('client.order.list') }}" class="btn btn-primary mt-15">
                        <i class="fa fa-list"></i> Xem danh sách đơn hàng
                    </a>
                    <a href="{{ route('client.checkout') }}" class="btn btn-default mt-15">
                        <i class="fa fa-shopping-cart"></i> Quay lại thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 