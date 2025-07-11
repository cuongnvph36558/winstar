@extends('layouts.client')

@section('title', 'Contact')
@section('content')
    <section class="module bg-dark-60 contact-page-header bg-dark" data-background="assets/images/contact_bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <h2 class="module-title font-alt">Contact Us</h2>
                    <div class="module-subtitle font-serif">A wonderful serenity has taken possession of my entire soul,
                        like these sweet mornings of spring which I enjoy with my whole heart.</div>
                </div>
            </div>
        </div>
    </section>
    <section class="module">
    <div class="container">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="font-alt">Tài khoản của tôi</h4><br />
            <form  method="post" action="">
                <div class="form-group">
                    <label class="sr-only" for="subject">Họ và tên</label>
                    <input class="form-control" type="text" id="subject" name="subject" value="{{ $user->name }}">
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="text-center">
                    <button class="btn btn-block btn-round btn-d" id="cfsubmit" type="submit">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>

    </section>
    <section id="map-section">
        <div id="map"></div>
    </section>
@endsection