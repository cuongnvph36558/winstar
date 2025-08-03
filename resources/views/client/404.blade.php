<!DOCTYPE html>
<html lang="en-US" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--  
    Document Title
    =============================================
    -->
    <title>404 | Not Found</title>
    <!--  
    Favicons
    =============================================
    -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.svg') }}">
    <meta name="msapplication-TileColor" content="#231C30">
    <meta name="theme-color" content="#231C30">
    <!--  
    Stylesheets
    =============================================
    
    -->
    <!-- Default stylesheets-->
    <link href="{{ asset('client/assets/lib/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Template specific stylesheets-->
    <link href="{{ asset("assets/external/fonts/roboto-condensed.css") }}" rel="stylesheet">
    <link href="{{ asset("assets/external/fonts/volkhov.css") }}" rel="stylesheet">
    <link href="{{ asset("assets/external/fonts/open-sans.css") }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/animate.css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/components-font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/et-line-font/et-line-font.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/flexslider/flexslider.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/owl.carousel/dist/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/owl.carousel/dist/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/magnific-popup/dist/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('client/assets/lib/simple-text-rotator/simpletextrotator.css') }}" rel="stylesheet">
    <!-- Main stylesheet and color file-->
    <link href="{{ asset('client/assets/css/style.css') }}" rel="stylesheet">
    <link id="color-scheme" href="{{ asset('client/assets/css/colors/default.css') }}" rel="stylesheet">
  </head>
  <body data-spy="scroll" data-target=".onpage-navigation" data-offset="60">
    <main>
      <div class="page-loader">
        <div class="loader">Loading...</div>
      </div>
      <section class="home-section home-parallax home-fade home-full-height bg-dark bg-dark-30" id="home" data-background="{{ asset('client/assets/images/section-4.jpg') }}">
        <div class="titan-caption">
          <div class="caption-content">
            <div class="font-alt mb-30 titan-title-size-4">Error 404</div>
            <div class="font-alt">The requested URL was not found on this server.<br/>That is all we know.
            </div>
            <div class="font-alt mt-30"><a class="btn btn-border-w btn-round" href="{{ route('client.home') }}">Back to home page</a></div>
          </div>
        </div>
      </section>
    </main>
    <!--  
    JavaScripts
    =============================================
    -->
    <script src="{{ asset('client/assets/lib/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('client/assets/lib/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('client/assets/lib/wow/dist/wow.js') }}"></script>
    <script src="{{ asset('client/assets/lib/jquery.mb.ytplayer/dist/jquery.mb.YTPlayer.js') }}"></script>
    <script src="{{ asset('client/assets/lib/isotope/dist/isotope.pkgd.js') }}"></script>
    <script src="{{ asset('client/assets/lib/imagesloaded/imagesloaded.pkgd.js') }}"></script>
    <script src="{{ asset('client/assets/lib/flexslider/jquery.flexslider.js') }}"></script>
    <script src="{{ asset('client/assets/lib/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('client/assets/lib/smoothscroll.js') }}"></script>
    <script src="{{ asset('client/assets/lib/magnific-popup/dist/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('client/assets/lib/simple-text-rotator/jquery.simple-text-rotator.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('client/assets/js/main.js') }}"></script>
  </body>
</html>