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
            <h4 class="font-alt">Get in touch</h4><br />
            <form  method="post" action="{{ route('client.contact.store') }}">
                @csrf

                {{-- SUBJECT --}}
                <div class="form-group">
                    <label class="sr-only" for="subject">Subject</label>
                    <input class="form-control" type="text" id="subject" name="subject" placeholder="Subject*"
                        required data-validation-required-message="Please enter a subject." />
                    @error('subject')
                        <p class="help-block text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- MESSAGE --}}
                <div class="form-group">
                    <textarea class="form-control" rows="7" id="message" name="message" placeholder="Your Message*"
                        required data-validation-required-message="Please enter your message."></textarea>
                    @error('message')
                        <p class="help-block text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="text-center">
                    <button class="btn btn-block btn-round btn-d" id="cfsubmit" type="submit">Submit</button>
                </div>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
        </div>

        {{-- INFO SIDE --}}
        <div class="col-sm-6">
            <h4 class="font-alt">Additional info</h4><br />
            <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls
                like mine. I am so happy, my dear friend.</p>
            <hr />
            <h4 class="font-alt">Business Hours</h4><br />
            <ul class="list-unstyled">
                <li>Mo - Fr: 8am to 6pm</li>
                <li>Sa, Su: 10am to 2pm</li>
            </ul>
        </div>
    </div>
</div>

    </section>
    <section id="map-section">
        <div id="map"></div>
    </section>
@endsection