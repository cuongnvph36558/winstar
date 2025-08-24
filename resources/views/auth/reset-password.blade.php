@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<section class="module bg-dark-30" data-background="{{ asset('client/assets/images/section-4.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h1 class="module-title font-alt mb-0">Reset Password</h1>
            </div>
        </div>
    </div>
</section>

<section class="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email', $email ?? '') }}" required autofocus />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Mật khẩu mới" required />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input class="form-control" type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu mới" required />
                    </div>

                    <div class="form-group">
                        <button class="btn btn-round btn-b" type="submit">
                            Đặt lại mật khẩu
                        </button>
                    </div>

                    <div class="form-group">
                        <a href="{{ route('login') }}">Quay lại đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection 
