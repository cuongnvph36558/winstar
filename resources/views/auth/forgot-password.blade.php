@extends('layouts.client')

@section('title', 'Forgot Password')

@section('content')
<section class="module bg-dark-30" data-background="{{ asset('client/assets/images/section-4.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h1 class="module-title font-alt mb-0">Forgot Password</h1>
            </div>
        </div>
    </div>
</section>

<section class="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="module-subtitle font-serif">
                    Quên mật khẩu? Không sao cả! Hãy nhập email của bạn và chúng tôi sẽ gửi cho bạn liên kết đặt lại mật khẩu.
                </div>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button class="btn btn-round btn-b" type="submit">
                            Gửi liên kết đặt lại mật khẩu
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