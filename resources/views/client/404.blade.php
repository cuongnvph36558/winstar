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
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('client/assets/images/favicons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('client/assets/images/favicons/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('client/assets/images/favicons/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('client/assets/images/favicons/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('client/assets/images/favicons/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('client/assets/images/favicons/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('client/assets/images/favicons/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('client/assets/images/favicons/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('client/assets/images/favicons/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('client/assets/images/favicons/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('client/assets/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('client/assets/images/favicons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('client/assets/images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('client/assets/images/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!--  
    Stylesheets
    =============================================
    
    -->
    <!-- Default stylesheets-->
    <link href="{{ asset('client/assets/lib/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Template specific stylesheets-->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Volkhov:400i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
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