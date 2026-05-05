<!DOCTYPE html>
<html lang="en" class="!overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="robots" content="noindex, nofollow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
    <link rel="preconnect" href="https://pcgarage.me/" />
    <link rel="dns-prefetch" href="https://pcgarage.me/" />
  
    <!-- Page Title -->
    <title>PC Garage | @yield('title', 'Default Site Title')</title>
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
   
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('style')

    <style>
        .swiper-button-next,.swiper-container-rtl .swiper-button-prev {
            left: auto;
            background-image: url("{{ asset('assets/images/arrow-right.svg') }}"); 
        }
        .swiper-button-prev,
        .swiper-container-rtl .swiper-button-next {
            right: auto;
            background-image: url("{{ asset('assets/images/arrow-left.svg') }}"); 
        }

        button.variant-btn.active {
            border: 1px solid #2a7cff;
        }
    </style>
</head>

<body class="m-[0] !overflow-x-hidden bg-[#0F161B] relative">
    <!-- Header -->
     @if(!isset($hideHeader) || !$hideHeader)
        @include('frontend.layouts.header')
    @endif  

    <!-- Main Content -->
    <main>
        @yield('content')


    


    </main>

    <!-- Footer -->
     @if(!isset($hideFooter) || !$hideFooter)
        @include('frontend.layouts.footer')
    @endif
    
    <!--script-->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('assets/js/elements@1.js') }}" type="module"></script>
    <!--//script-->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if (session('success'))
                toastr.success('{{ session("success") }}');
            @endif

            @if (session('error'))
                toastr.error('{{ session("error") }}');
            @endif

            document.addEventListener('click', function(e) {
                const button = e.target.closest('.wishlist-toggle');
                if (!button) return;

                e.preventDefault();

                if (!{{ auth('frontend')->check() ? 'true' : 'false' }}) {
                    toastr.error('Please log in to manage your wishlist.');
                    return;
                }
                const productId = button.dataset.productId;
                const stockId = button.dataset.stockId;
                const page = button.dataset.page;
                const icon = button.querySelector('svg');
                
                fetch("/wishlist/toggle", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        stock_id: stockId
                    })
                })
                .then(res => res.json())
                .then(data => {

                    const allButtons = document.querySelectorAll(
                        `.wishlist-toggle[data-product-id="${productId}"][data-stock-id="${stockId}"]`
                    );

                    allButtons.forEach(btn => {
                        const icon = btn.querySelector('svg');

                        if (data.status === 'added') {
                            icon.setAttribute('fill', 'currentColor');
                            btn.classList.add('text-red-500');
                            btn.classList.remove('text-white', 'bg-black/20');
                        } else {
                            icon.setAttribute('fill', 'none');
                            btn.classList.remove('text-red-500');
                            btn.classList.add('text-white', 'bg-black/20');
                        }
                    });


                    if (data.status === 'added') {
                        toastr.success('Product added to wishlist.');      
                    } else {
                        toastr.info('Product removed from wishlist.');
                        if(page === 'wishlist') {
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            });
            
        });

        (function() {
            const nameElement = document.getElementById('userNameSidebar');
            if (nameElement) {
                const name = nameElement.textContent.trim();
                if (name) {
                    document.getElementById('userAvatarSidebar').textContent = name.charAt(0).toUpperCase();
                }
            }
        })();
    </script>
    @yield('script')
    <div id="global-loader"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-[999999] hidden">
        <div class="loader"></div>
    </div>
</body>

</html>