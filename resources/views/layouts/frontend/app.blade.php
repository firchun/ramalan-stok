<!DOCTYPE html>

<html lang="en">

<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>{{ $title ?? '' }} | {{ env('APP_NAME') ?? 'Laravel' }}</title>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Construction Html5 Template">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="author" content="Themefisher">
    <meta name="generator" content="Themefisher Constra HTML Template v1.0">

    <!-- theme meta -->
    <meta name="theme-name" content="aviato" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/frontend_theme/') }}/images/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.0.1/css/ionicons.min.css"
        integrity="sha512-pEEXf+G92p3tgg8PmdvczNEIbChpgL4CZulz1P2f8VnNf1iU+mr0qW1UYD0d+GnN0Ck+AXRJYO9mcQG2E7YfDQ=="
        crossorigin="anonymous" />

    <!-- Themefisher Icon font -->
    <link rel="stylesheet" href="{{ asset('/frontend_theme/') }}/plugins/themefisher-font/style.css">
    <!-- bootstrap.min css -->
    <link rel="stylesheet" href="{{ asset('/frontend_theme/') }}/plugins/bootstrap/css/bootstrap.min.css">

    <!-- Animate css -->
    <link rel="stylesheet" href="{{ asset('/frontend_theme/') }}/plugins/animate/animate.css">
    <!-- Slick Carousel -->
    <link rel="stylesheet" href="{{ asset('/frontend_theme/') }}/plugins/slick/slick.css">
    <link rel="stylesheet" href="{{ asset('/frontend_theme/') }}/plugins/slick/slick-theme.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('/frontend_theme/') }}/css/style.css">

</head>

<body id="body">
    @include('layouts.frontend.header')
    @yield('content')
    @include('layouts.frontend.footer')
    <!--
    Essential Scripts
    =====================================-->

    <!-- Main jQuery -->
    <script src="{{ asset('/frontend_theme/') }}/plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="{{ asset('/frontend_theme/') }}/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Bootstrap Touchpin -->
    <script src="{{ asset('/frontend_theme/') }}/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js">
    </script>
    <!-- Instagram Feed Js -->
    <script src="{{ asset('/frontend_theme/') }}/plugins/instafeed/instafeed.min.js"></script>
    <!-- Video Lightbox Plugin -->
    <script src="{{ asset('/frontend_theme/') }}/plugins/ekko-lightbox/dist/ekko-lightbox.min.js"></script>
    <!-- Count Down Js -->
    <script src="{{ asset('/frontend_theme/') }}/plugins/syo-timer/build/jquery.syotimer.min.js"></script>

    <!-- slick Carousel -->
    <script src="{{ asset('/frontend_theme/') }}/plugins/slick/slick.min.js"></script>
    <script src="{{ asset('/frontend_theme/') }}/plugins/slick/slick-animation.min.js"></script>

    <!-- Google Mapl -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC72vZw-6tGqFyRhhg5CkF2fqfILn2Tsw"></script>
    <script type="text/javascript" src="{{ asset('/frontend_theme/') }}/plugins/google-map/gmap.js"></script>

    <!-- Main Js File -->
    <script src="{{ asset('/frontend_theme/') }}/js/script.js"></script>
</body>

</html>
