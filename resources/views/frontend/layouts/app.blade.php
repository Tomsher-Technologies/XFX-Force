<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://pcgarage.me/" />
    <link rel="dns-prefetch" href="https://pcgarage.me/" />
  
    <!-- Page Title -->
    <title>PC Garage | Custom Gaming PCs & High-End Hardware in UAE</title>
    <!-- //Page Title -->

     {!! SEO::generate() !!}
    <?php
    $url = url()->full();
    ?>
    
    <link rel="canonical" href="{{ url()->current() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    
    <!-- fav icon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/images/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!--// fav icon -->

    <meta name="theme-color" content="#000000" />
    <meta name="msapplication-navbutton-color" content="#000000">
    <meta name="apple-mobile-web-app-status-bar-style" content="#000000">

    <!-- styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/glightbox.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/output.css') }}" />
    <!--//styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('style')
</head>

<body class="m-[0] overflow-x-hidden">
    <!-- Header -->
    @include('frontend.layouts.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.layouts.footer')
    <!--script-->
    <script src="{{ asset('assets/js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper.min.js') }}"></script>
    <script src="{{ asset('assets/js/main-slider.js') }}"></script>
    <script src="{{ asset('assets/js/theme-script.js') }}"></script>
    <script src="{{ asset('assets/js/glightbox.min.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        $(window).scroll(function() {
            const header = $('#main-header');
            if ($(this).scrollTop() > 50) {
                // STYLE WHEN STICKY
                header.addClass('bg-[#0b0b0b] shadow-xl border-b border-[#1E2529]')
                    .removeClass('h-24 bg-transparent');
            } else {
                // ORIGINAL STYLE
                header.addClass('h-24 bg-transparent')
                    .removeClass('bg-[#0b0b0b] shadow-xl border-b border-[#1E2529]');
            }
        });

        $('.tab-btn').click(function() {

            var target = $(this).data('target');

            // Hide all tab contents
            $('#newArrivals, #popularItems').addClass('hidden');

            // Show selected content
            $(target).removeClass('hidden');

            // Update Swiper after showing
            setTimeout(function () {
                if (typeof Swiper !== 'undefined') {
                    document.querySelectorAll('.gamepcswiper').forEach(function (el) {
                        if (el.swiper) {
                            el.swiper.update();
                        }
                    });
                }
            }, 10);

            // // Reset all buttons
            // $('.tab-btn')
            //     .removeClass('bg-white text-black')
            //     .addClass('bg-transparent text-[#ffffff30]');

            // // Activate clicked button
            // $(this)
            //     .removeClass('bg-transparent text-[#ffffff30]')
            //     .addClass('bg-white text-black');

        });
    </script>
    <!--//script-->

    @yield('script')
</body>

</html>