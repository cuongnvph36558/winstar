@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('content')
<section class="module bg-dark-30" data-background="{{ asset('client/assets/images/section-4.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h1 class="module-title font-alt mb-0">Đăng nhập</h1>
            </div>
        </div>
    </div>
</section>

<section class="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 mb-sm-40">
                <div class="text-center mb-30">
                    <h4 class="font-alt">Đăng nhập vào tài khoản</h4>
                    <hr class="divider-w mb-10">
                </div>
                
                <!-- Google Login Button -->
                <div class="form-group">
                    <a href="{{ route('auth.google') }}" class="btn btn-google btn-block btn-round">
                        <i class="fa fa-google-plus"></i> Đăng nhập với Google
                    </a>
                </div>
                
                <div class="text-center mb-20">
                    <span class="font-alt">hoặc</span>
                </div>
                
                <div class="modal-header">
                    <h4 class="modal-title">Đăng nhập bằng Email</h4>
                </div>
                <div class="modal-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    {{-- <div class="alert alert-info" style="font-size: 12px;">
                        <strong>🔑 Tài khoản test:</strong><br>
                        Email: <code>admin@winstar.com</code> hoặc <code>user@winstar.com</code><br>
                        Mật khẩu: <code>password123</code>
                    </div> --}}
                    
                <form class="form" action="{{ route('postLogin') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Nhập địa chỉ email của bạn" value="{{ old('email') }}" required/>
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Nhập mật khẩu" required/>
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn btn-round btn-b btn-block" type="submit">Đăng nhập</button>
                    </div>
                </form>
                
                <div class="text-center">
                    <p class="font-alt">
                        <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    </p>
                    <p class="font-alt">
                        Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<style>
.btn-google {
    background-color: #dd4b39;
    color: white;
    border: none;
    position: relative;
    padding-left: 50px;
}
.btn-google:hover {
    background-color: #c23321;
    color: white;
}
.btn-google i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
}
</style>