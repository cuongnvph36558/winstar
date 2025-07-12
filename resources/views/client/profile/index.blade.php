@extends('layouts.client')

@section('title', 'Tài khoản của tôi')
@section('content')
<section class="module bg-dark-60 contact-page-header bg-dark" data-background="assets/images/contact_bg.jpg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2 class="module-title font-alt">Tài khoản của tôi</h2>
                <div class="module-subtitle font-serif">Quản lý thông tin cá nhân và cập nhật hồ sơ của bạn.</div>
            </div>
        </div>
    </div>
</section>

<section class="module">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="font-alt mb-4">Cập nhật thông tin cá nhân</h4>
                        <form method="post" action="{{ route('updateProfile') }}">
                            @method('put')
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                            </div>
                            <br
                            <div class="d-grid">
                                <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="map-section">
    <div id="map"></div>
</section>
@endsection