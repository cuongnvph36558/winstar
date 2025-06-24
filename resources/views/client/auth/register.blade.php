@extends('layouts.client')

@section('title', 'Đăng ký')

@section('content')
<section class="module bg-dark-30" data-background="{{ asset('client/assets/images/section-4.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h1 class="module-title font-alt mb-0">Đăng ký</h1>
            </div>
        </div>
    </div>
</section>

<section class="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 mb-sm-40">
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="text-center mb-30">
                    <h4 class="font-alt">Tạo tài khoản mới</h4>
                    <hr class="divider-w mb-10">
                </div>
                
                <!-- Google Register Button -->
                <div class="form-group">
                    <a href="{{ route('auth.google') }}" class="btn btn-google btn-block btn-round">
                        <i class="fa fa-google-plus"></i> Đăng ký với Google
                    </a>
                </div>
                
                <div class="text-center mb-20">
                    <span class="font-alt">hoặc</span>
                </div>
                
                <form class="form" action="{{ route('postRegister') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder="Họ và tên" value="{{ old('name') }}" required/>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required/>
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="phone" placeholder="Số điện thoại" value="{{ old('phone') }}" required/>
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Mật khẩu" required/>
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required/>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-inline">
                            <input type="checkbox" required> Tôi đồng ý với <a href="#">Điều khoản sử dụng</a> và <a href="#">Chính sách bảo mật</a>
                        </label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-block btn-round btn-b" type="submit">Đăng ký</button>
                    </div>
                </form>
                
                <div class="text-center">
                    <p class="font-alt">
                        Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
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
.checkbox-inline {
    font-size: 14px;
    color: #666;
}
.checkbox-inline a {
    color: #337ab7;
}
</style> 