<!DOCTYPE html>
<html lang="en" class="!overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
    <link rel="preconnect" href="{{ url('/') }}" />
    <link rel="dns-prefetch" href="{{ url('/') }}" />

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
        .policy-page ol, .policy-page ul, .policy-page menu {
            list-style: revert !important;
            margin: revert !important;
            padding: revert !important;
        }
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
            if (typeof toastr !== 'undefined') {
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
            }

            if (typeof $ !== 'undefined') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }

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

    @php
        $whatsapp_number = get_setting('whatsapp_floating_number') ?? get_setting('footer_phone') ?? '971500000000';
        $whatsapp_number = preg_replace('/[^0-9]/', '', $whatsapp_number);
        if (strpos($whatsapp_number, '00') === 0) {
            $whatsapp_number = substr($whatsapp_number, 2);
        }
        if (strpos($whatsapp_number, '0') === 0) {
            $whatsapp_number = '971' . substr($whatsapp_number, 1);
        }
    @endphp

    <!-- WhatsApp Floating Icon -->
    <a href="https://wa.me/{{ $whatsapp_number }}" target="_blank" class="whatsapp-float" title="Chat on WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
        </svg>
    </a>

    <style>
        .whatsapp-float {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 99999;
            width: 55px;
            height: 55px;
            background-color: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(37, 211, 102, 0.3), 0 8px 24px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            animation: whatsapp-pulse 2s infinite;
        }
        
        .whatsapp-float:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4), 0 12px 32px rgba(0, 0, 0, 0.25);
            background-color: #20ba5a;
        }
        
        @keyframes whatsapp-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.5);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }
        
        @media (min-width: 768px) {
            .whatsapp-float {
                bottom: 20px;
            }
        }
    </style>

    <div id="global-loader"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-[999999] hidden">
        <div class="loader"></div>
    </div>
</body>

</html>