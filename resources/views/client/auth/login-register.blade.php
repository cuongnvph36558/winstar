@extends('layouts.client')

@section('title', 'Login/Register')

@section('content')
<section class="module bg-dark-30" data-background="{{ asset('assets/images/section-4.jpg') }}">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <h1 class="module-title font-alt mb-0">Login-Register</h1>
        </div>
      </div>
    </div>
  </section>
  <section class="module">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3 mb-sm-40">
          <ul class="nav nav-tabs font-alt" role="tablist">
            <li class="active">
              <a href="#login" data-toggle="tab">Login</a>
            </li>
            <li>
              <a href="#register" data-toggle="tab">Register</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="login">
              <h4 class="font-alt">Login</h4>
              <hr class="divider-w mb-10">
              <form class="form" action="{{ route('postLogin') }}" method="POST">
                @csrf
                <div class="form-group">
                  <input class="form-control"  type="text" name="phone" placeholder="Số điện thoại"/>
                  @error('phone')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="form-group">
                  <input class="form-control" id="password" type="password" name="password" placeholder="Password"/>
                  @error('password')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="form-group">
                  <button class="btn btn-round btn-b" type="submit">Login</button>
                </div>
                <div class="form-group"><a href="">Forgot Password?</a></div>
              </form>
            </div>
            <div class="tab-pane" id="register">
              <h4 class="font-alt">Register</h4>
              <hr class="divider-w mb-10">
              <form class="form" action="" method="POST">
                @csrf
                <div class="form-group">
                  <input class="form-control" id="E-mail" type="text" name="email" placeholder="Email"/>
                </div>
                <div class="form-group">
                  <input class="form-control" id="username" type="text" name="username" placeholder="Username"/>
                </div>
                <div class="form-group">
                  <input class="form-control" id="password" type="password" name="password" placeholder="Password"/>
                </div>
                <div class="form-group">
                  <input class="form-control" id="re-password" type="password" name="re-password" placeholder="Re-enter Password"/>
                </div>
                <div class="form-group">
                  <button class="btn btn-block btn-round btn-b">Register</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
