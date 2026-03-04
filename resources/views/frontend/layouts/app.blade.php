<!DOCTYPE html>
<html lang="en" class="!overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="robots" content="noindex, nofollow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
    <link rel="preconnect" href="https://pcgarage.me/" />
    <link rel="dns-prefetch" href="https://pcgarage.me/" />
    <link rel="preconnect" href="https://pcgarage.me/" />
    <link rel="dns-prefetch" href="https://pcgarage.me/" />

    <!-- Page Title -->
    <title>PC Garage | @yield('title', 'Default Site Title')</title>
    <!-- //Page Title -->

    <meta name="description" content="Elevate your gameplay with PC Garage, the UAE’s premier hub for custom gaming PCs. Shop NVIDIA RTX & AMD graphics cards, high-end processors, and pre-built rigs. Dominate the leaderboard with fast UAE delivery. Build your dream PC today!" />
    <meta name="keywords" content="custom gaming PC, gaming hardware UAE, RTX graphics card, AMD processor, pre-built gaming PC, high-end PC components, UAE gaming setup" />
    <meta name="theme-color" content="#1a1a1a" />
    <meta name="og:locale" content="en_US" />
    <meta name="author" content="PC Garage" />
    <meta name="publisher" content="PC Garage" />
    <meta name="copyright" content="© 2026 PC Garage. All rights reserved" />
    <meta name="revisit-after" content="7 days" />
    <meta name="distribution" content="global" />
    <meta name="google-site-verification" content="" />
    <meta name="google-adsense-account" content="ca-pub-" />
    <meta name="application-name" content="PC Garage" />
    <meta name="apple-mobile-web-app-title" content="PC Garage" />
    <link rel="alternate" hreflang="en" href="" />
    <meta name="canonical" content="https://pcgarage.me/" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="PC Garage | Custom Gaming PCs & High-End Hardware in UAE">
    <meta property="og:description" content="Elevate your gameplay with PC Garage, the UAE’s premier hub for custom gaming PCs. Shop NVIDIA RTX & AMD graphics cards, high-end processors, and pre-built rigs. Dominate the leaderboard with fast UAE delivery. Build your dream PC today!">
    <meta property="og:image" content="https://pcgarage.me/src/images/preview-image.webp">
    <meta property="og:url" content="https://pcgarage.me/">
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="PC Garage - Custom Gaming PCs & High-End Hardware in UAE" />
    <meta property="og:site_name" content="PC Garage" />
    <!--// Open Graph / Facebook -->

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="PC Garage | Custom Gaming PCs & High-End Hardware in UAE">
    <meta name="twitter:description" content="Elevate your gameplay with PC Garage, the UAE’s premier hub for custom gaming PCs. Shop NVIDIA RTX & AMD graphics cards, high-end processors, and pre-built rigs. Dominate the leaderboard with fast UAE delivery. Build your dream PC today!">
    <meta name="twitter:image" content="https://pcgarage.me/src/images/preview-image.webp">
    <!--// Twitter -->

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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>

    <!-- styles -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/glightbox.min.css') }}" /> -->
    
   @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!--//styles -->

</head>

<body class="m-[0] bg-[#0F161B]">
    <!-- Header -->
    @include('frontend.layouts.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.layouts.footer')
    <!--script-->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('assets/js/jquery-3.6.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- <script src="{{ asset('assets/js/swiper.min.js') }}"></script>
    <script src="{{ asset('assets/js/main-slider.js') }}"></script>
    <script src="{{ asset('assets/js/theme-script.js') }}"></script>
    <script src="{{ asset('assets/js/glightbox.min.js') }}"></script> -->
    <script src="{{ asset('assets/js/elements@1.js') }}" type="module"></script>
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

        $(function() {
            $('.view-btn').on('click', function() {
                // 1. Add active class and background to the one you clicked
                $(this).addClass('active bg-[#282B34]');

                // 2. Remove those same classes from all other buttons in the same parent
                $(this).siblings().removeClass('active bg-[#282B34]');
            });
        });
        
        document.addEventListener('DOMContentLoaded', () => {
            const minInput = document.getElementById('range-min');
            const maxInput = document.getElementById('range-max');
            const minLabel = document.getElementById('min-price');
            const maxLabel = document.getElementById('max-price');
            const progress = document.getElementById('slider-progress');

            const priceGap = 500; // Minimum gap between handles

            function updateSlider() {
                let minVal = parseInt(minInput.value);
                let maxVal = parseInt(maxInput.value);

                // Logic to prevent handles from crossing
                if (maxVal - minVal < priceGap) {
                    if (this.id === 'range-min') {
                        minInput.value = maxVal - priceGap;
                    } else {
                        maxInput.value = minVal + priceGap;
                    }
                } else {
                    minLabel.textContent = minInput.value;
                    maxLabel.textContent = maxInput.value;
                    filterProducts();

                    // Calculate percentage for the blue progress bar
                    const minPercent = (minInput.value / minInput.max) * 100;
                    const maxPercent = 100 - (maxInput.value / maxInput.max) * 100;

                    progress.style.left = minPercent + "%";
                    progress.style.right = maxPercent + "%";
                }

            }

            [minInput, maxInput].forEach(input => {
                input.addEventListener('input', updateSlider);
            });

            // Run once on load to set initial state
            updateSlider();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('brand-search');
            const brandItems = document.querySelectorAll('.brand-item');
            selectedBrands = [];

            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                brandItems.forEach(item => {
                    const brandName = item.getAttribute('data-name').toLowerCase();

                    // Check if search query matches the brand name
                    if (brandName.includes(query)) {
                        item.style.display = 'flex'; // Show match
                        item.classList.add('animate-fade-in'); // Optional: add a quick fade effect
                    } else {
                        item.style.display = 'none'; // Hide non-match
                    }
                });
            });

            // Handle selection (Active State)
            brandItems.forEach(item => {
                item.addEventListener('click', function() {
                    let brandId = item.getAttribute('data-id');
                    if (!brandId) return;

                    if (selectedBrands.includes(brandId)) {
                        selectedBrands = selectedBrands.filter(id => id != brandId);
                    } else {
                        selectedBrands.push(brandId);
                    }

                    this.classList.toggle('border-[#3E81FF]');
                    this.classList.toggle('bg-[#282B34]');
                    const img = this.querySelector('img');
                    img.classList.toggle('grayscale-0');
                    filterProducts(selectedBrands);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('category-search');
            const brandItems = document.querySelectorAll('.category-item');

            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();

                brandItems.forEach(item => {
                    const brandName = item.getAttribute('data-name').toLowerCase();

                    // Check if search query matches the brand name
                    if (brandName.includes(query)) {
                        item.style.display = 'flex'; // Show match
                        item.classList.add('animate-fade-in'); // Optional: add a quick fade effect
                    } else {
                        item.style.display = 'none'; // Hide non-match
                    }
                });
            });
        });

        function filterProducts(brands = []) {
            let categories = [];

            $('input[name="categories[]"]:checked').each(function() {
                categories.push($(this).val());
            });

            let min_price = parseInt($('#min-price').text()) || 0;
            let max_price = parseInt($('#max-price').text()) || 300000;
            let sort = new URLSearchParams(window.location.search).get('sort');
            let view = new URLSearchParams(window.location.search).get('view');

            $.ajax({
                url: "{{ route('products') }}",
                type: "GET",
                data: {
                    categories: categories,
                    brands: brands,
                    min_price: min_price,
                    max_price: max_price,
                    sort: sort,
                    view: view
                },
                success: function(response) {
                    $('#product-list-wrapper').html(response);
                }
            });
        }

        $(function() {
            $(document).on('change', 'input[name="categories[]"], input[name="brands[]"]', function() {
                filterProducts();
            });

            filterProducts();
        });

        // 1. Define the function globally immediately
        window.toggleModal = function() {
            const overlay = document.getElementById('modal-overlay');
            const container = document.getElementById('modal-container');

            if (!overlay || !container) {
                console.error("Modal elements not found in DOM!");
                return;
            }

            const isHidden = overlay.classList.contains('hidden');

            if (isHidden) {
                console.log("Opening Modal...");
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Small delay to trigger Tailwind transitions
                setTimeout(() => {
                    overlay.classList.add('opacity-100');
                    container.classList.add('scale-100');
                    container.classList.remove('scale-95');
                }, 10);
            } else {
                console.log("Closing Modal...");
                overlay.classList.remove('opacity-100');
                container.classList.add('scale-95');
                container.classList.remove('scale-100');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }
        };

        // 2. Attach the click listener safely
        document.addEventListener('click', function(e) {
            const overlay = document.getElementById('modal-overlay');
            const container = document.getElementById('modal-container');

            // Check if the user clicked EXACTLY the overlay (the dark part)
            // and NOT the container (the white/dark box)
            if (e.target === overlay && !container.contains(e.target)) {
                window.toggleModal();
            }
        });



        // --- SPECIFICATION MODAL LOGIC ---
        const sOverlay = document.getElementById('spec-modal-overlay');
        const sContainer = document.getElementById('spec-modal-container');

        function toggleSpecModal() {
            const isHidden = sOverlay.classList.contains('hidden');
            if (isHidden) {
                sOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    sOverlay.classList.add('opacity-100');
                    sContainer.classList.add('scale-100');
                    sContainer.classList.remove('scale-95');
                }, 10);
            } else {
                sOverlay.classList.remove('opacity-100');
                sContainer.classList.add('scale-95');
                sContainer.classList.remove('scale-100');
                setTimeout(() => {
                    sOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }
        }

        // --- WARRANTY MODAL LOGIC ---
        const wOverlay = document.getElementById('warranty-modal-overlay');
        const wContainer = document.getElementById('warranty-modal-container');

        function toggleWarrantyModal() {
            const isHidden = wOverlay.classList.contains('hidden');
            if (isHidden) {
                wOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    wOverlay.classList.add('opacity-100');
                    wContainer.classList.add('scale-100');
                    wContainer.classList.remove('scale-95');
                }, 10);
            } else {
                wOverlay.classList.remove('opacity-100');
                wContainer.classList.add('scale-95');
                wContainer.classList.remove('scale-100');
                setTimeout(() => {
                    wOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }
        }

        // --- OUTSIDE CLICK CLOSING ---
        window.addEventListener('click', (e) => {
            if (e.target === sOverlay) toggleSpecModal();
            if (e.target === wOverlay) toggleWarrantyModal();
        });


        window.selectWarranty = function(selectedElement) {
            // 1. Get all warranty cards
            const cards = document.querySelectorAll('.warranty-card');

            cards.forEach(card => {
                // 2. Reset Styles to "Unselected"
                card.classList.remove('border-2', 'border-[#2A7CFF]', 'bg-[#161B22]');
                card.classList.add('border', 'border-gray-800', 'bg-[#282B3450]');

                // 3. Hide Checkmark icons
                const icon = card.querySelector('.check-icon');
                if (icon) icon.classList.add('hidden');
            });

            // 4. Apply "Selected" Styles to the clicked element
            selectedElement.classList.add('border-2', 'border-[#2A7CFF]', 'bg-[#161B22]');
            selectedElement.classList.remove('border', 'border-gray-800', 'bg-[#282B3450]');

            // 5. Show the checkmark for the selected plan
            const activeIcon = selectedElement.querySelector('.check-icon');
            if (activeIcon) activeIcon.classList.remove('hidden');

            console.log("Selected Warranty Plan:", selectedElement.querySelector('span').innerText);
        };



        const MINUS_ICON = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 12H4" /></svg>`;
        const TRASH_ICON = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`;

        window.updateMultiQty = function(btn, change) {
            // Find the container for THIS specific product
            const container = btn.closest('.product-item');
            const input = container.querySelector('.qty-input');
            const iconWrapper = container.querySelector('.icon-wrapper');
            const minusBtn = container.querySelector('.minus-btn');

            let currentVal = parseInt(input.value);

            // DELETE LOGIC: If qty is 1 and user clicks minus
            if (currentVal === 1 && change === -1) {
                if (confirm("Remove this item?")) {
                    container.style.opacity = '0.5';
                    container.style.pointerEvents = 'none';
                    console.log("Product removed from tracking.");
                }
                return;
            }
            const productId  = container.dataset.productId;
            const variantId  = container.dataset.variantId;

            let newVal = currentVal + change;
            if (newVal < 1) newVal = 1;
            addtoCart(productId,variantId,newVal);
            input.value = newVal;

            

            // UI UPDATES for this specific card
            if (newVal === 1) {
                iconWrapper.innerHTML = TRASH_ICON;
                minusBtn.classList.add('hover:text-red-500', 'hover:bg-red-500/10');
                minusBtn.classList.remove('hover:bg-[#2A7CFF]', 'hover:text-white');
            } else {
                iconWrapper.innerHTML = MINUS_ICON;
                minusBtn.classList.remove('hover:text-red-500', 'hover:bg-red-500/10');
                minusBtn.classList.add('hover:bg-[#2A7CFF]', 'hover:text-white');
            }

            // Pulse animation
            input.classList.add('text-[#2A7CFF]', 'scale-110');
            setTimeout(() => input.classList.remove('text-[#2A7CFF]', 'scale-110'), 150);
        };

        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('billing-toggle');
            const list = document.getElementById('address-list-container');
            const overlay = document.getElementById('addr-modal-overlay');
            const modal = document.getElementById('addr-modal-container');

            toggle.addEventListener('change', () => {
                list.classList.toggle('hidden', toggle.checked);
            });

            window.openModal = () => {
                overlay.classList.remove('hidden');
                // FIX: Lock the body to prevent double scrollbars
                document.body.style.overflow = 'hidden';

                setTimeout(() => {
                    overlay.classList.add('opacity-100');
                    modal.classList.add('scale-100', 'opacity-100');
                }, 10);
            };

            window.closeModal = () => {
                overlay.classList.remove('opacity-100');
                modal.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    // FIX: Restore scroll when closed
                    document.body.style.overflow = 'auto';
                }, 300);
            };

            document.addEventListener('click', (e) => {
                if (e.target.closest('#open-address-modal')) openModal();
                if (e.target.closest('#close-modal-x') || e.target.closest('#discard-btn') || e.target === overlay) closeModal();
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('brand-search');
            const categorySelect = document.getElementById('category-select');
            const brandItems = document.querySelectorAll('.brand-item');

            const runFilters = () => {
                const query = searchInput.value.toLowerCase();
                const selectedCat = categorySelect.value;

                brandItems.forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    const cat = item.getAttribute('data-category');

                    const searchMatch = name.includes(query);
                    const catMatch = (selectedCat === 'all' || cat === selectedCat);

                    if (searchMatch && catMatch) {
                        item.style.display = 'flex';
                        item.classList.add('animate-fade-in');
                    } else {
                        item.style.display = 'none';
                    }
                });
            };

            searchInput.addEventListener('input', runFilters);
            categorySelect.addEventListener('change', runFilters);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const variantButtons = document.querySelectorAll('.variant-btn');

            variantButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Find all buttons in the same group (GPU or RAM)
                    const group = btn.closest('.variant-group');
                    const siblingButtons = group.querySelectorAll('.variant-btn');

                    // Reset all buttons in this group
                    siblingButtons.forEach(s => {
                        s.classList.remove('active', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'border-1');
                        s.classList.add('border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'border');
                        s.classList.remove('text-white', 'font-bold');
                    });

                    // Set active state for clicked button
                    btn.classList.add('active', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'border-1');
                    btn.classList.remove('border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'border');
                    btn.classList.add('text-white', 'font-bold');

                    // Trigger Price Update (SEO/Analytics Hook)
                    updateProductPrice();
                });
            });

            function updateProductPrice() {
                console.log("Recalculating price based on selections...");
                // Here you would logic to change the 1299.00 text
            }
        });

        $(function() {
            var swiperInstance = $('.singleprdswiper')[0].swiper;
            $('.variant-btn').on('click', function() {
                var attributeId = $(this).data('attr-id');
                var attributeValueId = $(this).data('value-id');
                var productId = $(this).data('product-id');

                // activate the clicked button
                $(this).removeClass('border border-[#ffffff10] bg-[#161B22] text-gray-400 hover:border-[#ffffff30]').addClass('active border-1 border-[#2A7CFF] bg-[#2A7CFF]/10 text-white font-medium');
                //deactivate the remaining
                $(this).siblings().removeClass('active border-1 border-[#2A7CFF] bg-[#2A7CFF]/10 text-white font-medium').addClass('border border-[#ffffff10] bg-[#161B22] text-gray-400 hover:border-[#ffffff30]');

                // Activate clicked button and deactivate siblings
                var clickedIndex = $(this).data('attr-index');

                // Disable all lower levels
                $('.variant-btn').each(function() {
                    var btnIndex = $(this).data('attr-index');
                    if (btnIndex > clickedIndex) {
                        $(this).prop('disabled', true)
                            .removeClass('active border-1 border-[#2A7CFF] bg-[#2A7CFF]/10 text-white font-medium')
                            .addClass('border border-[#ffffff10] bg-[#161B22] text-gray-400 hover:border-[#ffffff30]')
                            .css({
                                'opacity': 0.3,
                                'cursor': 'not-allowed'
                            });
                    }
                });

                $.ajax({
                    url: '/get-variants-by-value',
                    type: 'GET',
                    data: {
                        attribute_id: attributeId,
                        value_id: attributeValueId,
                        product_id: productId,
                        selectedAttributes: getSelectedAttributes()
                    },
                    success: function(response) {
                        if (response.data && response.data.length > 0) {
                            var activatedRows = {}; // track which row has active
                            // Group the data by attribute index (level)
                            var grouped = {};
                            response.data.forEach(function(v) {
                                var $btn = $('.variant-btn[data-attr-id="' + v.attribute_id + '"][data-value-id="' + v.value_id + '"]');
                                var btnIndex = $btn.data('attr-index');
                                if (!grouped[btnIndex]) grouped[btnIndex] = [];
                                grouped[btnIndex].push($btn);
                            });

                            // Loop per level
                            Object.keys(grouped).forEach(function(level) {
                                var buttons = grouped[level];
                                // Check if any button is already active in this level
                                var hasActive = buttons.some(function($b) {
                                    return $b.hasClass('active');
                                });

                                // If none active, activate the first
                                if (!hasActive && buttons.length > 0) {
                                    var $firstBtn = buttons[0];
                                    $firstBtn.addClass('active border-1 border-[#2A7CFF] bg-[#2A7CFF]/10 text-white font-medium')
                                        .removeClass('border border-[#ffffff10] bg-[#161B22] text-gray-400 hover:border-[#ffffff30]');
                                }

                                // Enable all buttons in this level
                                buttons.forEach(function($b) {
                                    $b.prop('disabled', false).css({
                                        'opacity': 1,
                                        'cursor': 'pointer'
                                    });
                                });
                            });
                        }
                        $('.variant-btn.active').each(function() {
                            var $btn = $(this);
                            var btnIndex = $btn.data('attr-index');
                            if (btnIndex > clickedIndex) {
                                $btn.trigger('click');
                            }
                        });
                        getVarientDetails(productId);
                    },
                    error: function(err) {
                        console.error('AJAX error:', err);
                    }
                });
            });

            // Function to get the varient details.
            function getVarientDetails(productId) {
                var selectedAttributes = {};
                $(".variant-list").find('.variant-btn.active').each(function() {
                    selectedAttributes[$(this).data('attr-id')] = $(this).data('value-id');
                });
                $.ajax({
                    url: '/getVarientDetails',
                    type: 'GET',
                    data: {
                        productId: productId,
                        selectedAttributes: selectedAttributes,
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.data.image) {
                                swiperInstance.removeAllSlides();
                                swiperInstance.appendSlide(`
                                <div class="swiper-slide" data-stock-id="${response.data.id}">
                                    <a href="/storage/${response.data.image}" class="glightbox">
                                        <img src="/storage/${response.data.image}" 
                                            class="w-full h-full object-cover object-center">
                                    </a>
                                </div>
                            `);
                                swiperInstance.slideTo(0);
                            }
                            $('.price span.main-price').text(response.data.price);
                            $('.offer-price').text(response.data.offer_price);
                            $('.variant-title').text(response.data.title);
                            $('#stock_id').val(response.data.variant_id)
                            
                            if(response.data.availableQty <= 0){
                                $(".out-of-stock-block").show();
                                $(".add-to-cart-block").hide();
                            } else {
                                $(".out-of-stock-block").hide();
                                $(".add-to-cart-block").show();
                            }
                        }
                    }
                });
            }

            // Function to get the selected attributes.
            function getSelectedAttributes() {
                var selected = {};
                $('.variant-btn.active').each(function() {
                    selected[$(this).data('attr-id')] = $(this).data('value-id');
                });
                return selected;
            }

            // get the varient details of default selected.
            getVarientDetails($('#main_product_id').val());

            var $firstBtn = $('.variant-group').first().find('.variant-btn').first();
            if ($firstBtn.length) {
                $firstBtn.trigger('click');
            }
        });

        function addtoCart(productId,variantId,quantity){
            $.ajax({
                url: '/addProductToCart',
                type: 'GET',
                data: {
                    productId: productId,
                    variantId: variantId,
                    quantity: quantity
                },
                success: function (response) {
                    console.log(response.data);
                   if(response.success) {
                        toastr.success(response.message, 'Success Title');
                    } else {
                        toastr.error(response.message, 'Error Title');
                    }

                    if(response.availableQty <= 0){
                        $(".out-of-stock-block").show();
                        $(".add-to-cart-block").hide();
                    } else {
                        $(".out-of-stock-block").hide();
                        $(".add-to-cart-block").show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Display an error toast if the request fails
                    toastr.error(errorThrown, 'Error Title');
                }
            });
        };

        $('.add-to-cart').on('click', function () {
            var productId = $('#main_product_id').val();
            var variantId = $('#stock_id').val();
            addtoCart(productId,variantId,1)
        });
    </script>
    <!--//script-->
</body>

</html>