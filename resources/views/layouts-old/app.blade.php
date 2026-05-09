<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @php
        $noIndexRoutes = ['logout', 'wishlist.index', 'orders.index','order-details','download-invoice','orders.returns','account','update-password','my-address','add-address','edit-address'];
        $routeName = Route::currentRouteName();
        $noIndex = auth()->check() && collect($noIndexRoutes)->contains(fn($r) => Str::is($r, $routeName));
    @endphp

    @if($noIndex)
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg" href="{{ asset('assets/img/favicon.ico') }}">
    {!! SEO::generate() !!}
    <?php
    $url = url()->full();
    ?>
    
    <link rel="canonical" href="{{ url()->current() }}"/>
    <link href="{{ asset('dist/assets/app-97de3ed1.css') }}" rel="stylesheet">
     <link href="{{ asset('dist/assets/app-afc193cb.css') }}" rel="stylesheet">
    <script type="module" src="{{ asset('dist/assets/app-f10b86b9.js') }}"></script>   

    @yield('style')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
   
    <style>
    .technical_specs td {
    padding: 15px;
    border: 1px solid #ccc;
}

.technical_specs tr > td:first-child {
  width: 25%;
}
       .swiper.product-thumbs-swiper .swiper-slide {
        width: 8.3vw!important;
           
       }
        .menu-active{
            color: rgb(65 182 232 / var(--tw-text-opacity, 1));
        }
        .text-danger{
            color: red;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
            background-color: #25d366;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        .whatsapp-float img {
            width: 40px;
            height: 40px;
        }
        
              @media (max-width: 768px) {
          
          .whatsapp-float {
    bottom: 100px;

}

}

    </style>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-M6ZEPB8HGP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-M6ZEPB8HGP');
</script>
<meta name="google-site-verification" content="tQoJrgDrbZnN1pg0CXA5JbMDyBapbMJDSU5-OSuDS9A" />
</head>
<body class="bg-gray-50">

    @include('components.navigation.header')

    <main class="pt-[90px]">
        @yield('content')
    </main>


    @include('components.navigation.footer') 
    <a href="https://wa.me/{{get_setting('default_service_whatsapp')}}" class="whatsapp-float" target="_blank" rel="noopener">
        <img src="{{ asset('assets/images/whatsapp.png') }}" alt="WhatsApp Chat" />
    </a>
      
    @yield('script')

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

            $(document).ready(function () {
                $('.counter-input').each(function () {
                    let productId = $(this).attr('id').split('_')[1]; // Extract ID from `quantity-field_{id}`
                    let maxQuantity = parseInt($('.increment-button[data-id="' + productId + '"]').data('max-quantity')) || Infinity;
                    updateButtonState(productId, maxQuantity);
                });
            });

            $('#newsletter-form').on('submit', function(e){
                e.preventDefault();
        
                let newsletter_email = $('#newsletter_email').val();
                let _token = $('input[name="_token"]').val();
        
                $.ajax({
                    url: "{{ route('newsletter.subscribe') }}",
                    type: "POST",
                    data: { newsletter_email: newsletter_email, _token: _token },
                    success: function(response) {
                        $('#messageNewsletter').text(response.success).css('color', '#00dc00');
                        $('#newsletter_email').val('');
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON.errors.newsletter_email[0];
                        $('#messageNewsletter').text(error).css('color', 'red');
                    }
                });
            });

            $(document).on('click', '.add-to-cart-btn', function (event) {
                const productSlug = $(this).data('product-slug');
                const productSku = $(this).data('product-sku');
                var quantity = $('#product_quantity').val() ?? 1;
                
                let btn = event.target.closest(".add-to-cart-btn");

                $.ajax({
                    url: '/cart/add', // Laravel route
                    type: 'POST',
                    data: {
                        product_slug: productSlug,
                        sku : productSku,
                        quantity: quantity, // Default quantity
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        $('.cart_count').text(response.cart_count);
                        $('.canvasCartcount').text(response.cart_count);
                        if (response.status == true) {
                            toastr.success(response.message, "success");
                            // Check if #detailsCartButton exists on the page
                            if ($('#detailsCartButton').length) {
                                $('#detailsCartButton').html(`<a href="{{ route('cart') }}" class="bg-primary whitespace-nowrap text-white px-6 py-3 rounded-lg text-lg font-medium hover:bg-secondary transition-all w-[70%] lg:w-[50%] text-center">
                                    Go to Cart
                                </a>`);
                            }
                            
                            let parentElement = btn.closest(".cart_button_edit");

                            if (parentElement) {
                                $(parentElement).html(`<button class="w-[35px] h-[35px] flex items-center justify-center bg-[#22a914] hover:bg-[#41B6E8] text-white hover:text-white rounded-full shadow-md transition duration-[300ms]" aria-label="Add to Cart">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="white">
                                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/>
                                        </svg>
                                    </button>`);
                            }

                        } else {
                            toastr.error(response.message, "error");
                        }
                        
                        
                    },
                    error: function (xhr, status, error) {
                        toastr.error("{{trans('messages.product_add_cart_failed')}}", 'Error');
                    },
                });
            });

            // Event listener for the "Remove" button
            $(document).on('click', '.remove-cart-item', function() {
                var cartItemId = $(this).data('id'); // Get the cart item ID
                var row = $(this).closest('div'); // Get the closest row to remove

                // Send an Ajax request to remove the item from the cart
                $.ajax({
                    url: '/cart/' + cartItemId,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === true) {
                            // Remove the row from the table
                            $('#cart_item'+cartItemId).remove();
                            $('.cart_count').text(response.cart_count);
                            $('.canvasCartcount').text(response.cart_count);
                            $('.cart_sub_total').html(response.updatedCartSummary.sub_total)
                            // Optionally, you can update the cart summary here
                            toastr.success(response.message, "{{trans('messages.success')}}");
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);

                        } else {
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error("{{trans('messages.cart_remove_error')}}", "{{trans('messages.error')}}");
                    }
                });
            });

            $(document).on('click', '.change_quantity', function() {

                var quantity = 0;
                let action = $(this).data('action');
                let cartItemId = $(this).data('id');
                let maxQuantity = parseInt($(this).data('max-quantity')) || Infinity; // Default to infinity if no max
                let inputField = $('#quantity-field_'+cartItemId);
                let currentValue = parseInt(inputField.val()) || 0;

                if (action === 'plus' && currentValue < maxQuantity) {
                    quantity = currentValue + 1;
                } else if (action === 'minus' && currentValue > 1) {
                    quantity = currentValue - 1;
                }
               
                
                updateButtonState(cartItemId, maxQuantity);

                $.ajax({
                    url: '/cart/change_quantity',
                    type: 'POST',
                    data: {
                        cart_id: cartItemId,
                        action : action,
                        quantity : quantity
                    },
                    success: function(response) {
                        if (response.status === true) {
                            // Optionally, you can update the cart summary here
                            inputField.val(quantity);
                            toastr.success(response.message, "{{trans('messages.success')}}");
                            window.location.reload();
                        } else {
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error("{{trans('messages.cart_remove_error')}}", "{{trans('messages.error')}}");
                    }
                });
            });

            // Function to enable/disable buttons based on quantity
            function updateButtonState(productId, maxQuantity) {
                let inputField = $('#quantity-field_' + productId);
                let currentValue = parseInt(inputField.val()) || 0;
                
                let decrementButton = $('.decrement-button[data-id="' + productId + '"]');
                let incrementButton = $('.increment-button[data-id="' + productId + '"]');

                // Disable decrement button if quantity is 1
                if (currentValue <= 1) {
                    decrementButton.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                } else {
                    decrementButton.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                }

                // Disable increment button if max quantity is reached
                if (currentValue >= maxQuantity) {
                    incrementButton.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                } else {
                    incrementButton.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                }
            }
      
            $(document).on('click', '.wishlist-btn', function () {
                const productSlug = $(this).data('product-slug');
                const productSku = $(this).data('product-sku');
                const url = '/wishlist/store';
                const wishButton = $(this);

                $.ajax({
                    url: '/check-login-status',  // Endpoint to check login status
                    type: 'GET',
                    success: function (response) {
                        if (response.is_logged_in) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    productSlug: productSlug, 
                                    productSku: productSku, 
                                    _token: $('meta[name="csrf-token"]').attr('content') 
                                },
                                success: function(response) {
                                    if(response.status == true){
                                        $('.wishlist_count').text(response.wishlist_count);
                                        toastr.success(response.message, "{{trans('messages.success')}}");
                                        if(response.wishlist_status == 1){
                                            wishButton.find("span").html('Remove from wishlist');
                                            wishButton.find("svg").addClass('text-red-500').attr("fill", "red");;
                                            $('.wishlist_msg').html("{{trans('messages.remove_wishlist')}}");
                                        }else{
                                            wishButton.find("span").html('Add to wishlist');
                                            wishButton.find("svg").removeClass('text-red-500').attr("fill", "none");;
                                            $('.wishlist_msg').html("{{trans('messages.add_wishlist')}}");
                                        }
                                    
                                    }else{
                                        toastr.error(response.message, "{{trans('messages.error')}}");
                                    }
                                },
                                error: function(xhr, status, error) {
                                    toastr.error("{{trans('messages.wishlist_remove_error')}}", "{{trans('messages.error')}}");
                                }
                            });
                        } else {
                            // Show alert if not logged in
                            toastr.error("{{trans('messages.login_msg')}}", "{{trans('messages.error')}}");
                        }
                    },
                    error: function () {
                        toastr.error("{{trans('messages.error_try_again')}}", "{{trans('messages.error')}}");
                    }
                });

            });

            $('.proceedToCheckout').on('click', function () {
                $.ajax({
                    url: '/check-login-status',  // Endpoint to check login status
                    type: 'GET',
                    success: function (response) {
                        if (response.is_logged_in) {
                            // Redirect to checkout page
                            window.location.href = '/checkout';
                        } else {
                            // Show alert if not logged in
                            toastr.error("{{trans('messages.login_msg')}}", "{{trans('messages.error')}}");
                        }
                    },
                    error: function () {
                        toastr.error("{{trans('messages.error_try_again')}}", "{{trans('messages.error')}}");
                    }
                });
            });

            var productDetailRoute = "{{ route('products.show', ['slug' => '__slug__']) }}"; // this will be a placeholder
            $(".open-canvas-cart").on("click", function() {

                $.get('{{ route('cart.index') }}', {
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    console.log();
                    // location.reload();
                    var productRow = '';
                    $.each(data.products, function(index, product) {
                        
                        var productLink = productDetailRoute.replace('__slug__', product.product.slug).replace('__sku__', product.product.sku);
                        var  price = '';
                        if(product.stroked_price != product.main_price){
                            price = `<span class="text-gray-500 line-through text-xs"> &nbsp;AED ${product.stroked_price }</span>`;
                        }
        
                        productRow += `<div class="flex items-center space-x-4"  id="cart_item${product.id}">
                                            <a href="${productLink}"> 
                                                <img src="${product.product.image}" alt="${product.product.name}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                            </a>
                                            <div class="flex-1">
                                                <h4 class="text-gray-800 font-medium">
                                                    <a href="${productLink}"> ${product.product.name} </a>
                                                </h4>
                                                <p class="text-gray-600 text-sm">Quantity: ${product.quantity}</p>
                                                <p class="text-primary font-semibold">
                                                    AED ${product.main_price} ${price}
                                                </p>
                                            </div>
                                            
                                            <button class="remove-cart-item text-red-500 hover:text-red-700 focus:outline-none"  data-id="${product.id}">
                                                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18 17.94 6M18 18 6.06 6" />
                                                </svg>
                                            </button>
                                        </div>`;
                        
                    });

                    if(productRow == ''){
                        var cart_empty = "{{ trans('messages.cart_empty') }}";
                        productRow += `
                                <div class="flex items-center space-x-4">
                                    <p>${cart_empty}</p>
                                    </div>
                                    `;
                    }

                    $('#cart-canvas-content').html(productRow);
                                
                    $('.cart_sub_total').html(data.summary.after_discount);
                });



                $(".js-canvas-cart").addClass("active");
                $("body").css("overflow", "hidden");
                return false;
            });


            $('#applyCouponForm').on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting the traditional way

                var couponCode = $('#couponCode').val(); // Get the coupon code from the input
                var coupon_action = $('#coupon_action').val();
                coupon_action = coupon_action.trim(); 

                var url = '';
                if(String(coupon_action) === "add"){
                    url = "{{ route('coupon-apply')}}";
                }else{
                    url = "{{ route('coupon-remove')}}";
                }

                $.ajax({
                    url: url,  // URL to your backend endpoint
                    type: 'POST',
                    data: {
                        coupon: couponCode,
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#couponMessage').html('<span style="color: green;">' + response.message + '</span>');
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        } else {
                            $('#couponMessage').html('<span style="color: red;">' + response.message + '</span>');
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#couponMessage').html('<span style="color: red;">An error occurred. Please try again.</span>');
                    }
                });
            });

            let typingTimer;
            let doneTypingInterval = 300;

            $('input[name="search"]').on('keyup', function () {
                clearTimeout(typingTimer);
                const query = $(this).val();

                if (query.length > 1) {
                    typingTimer = setTimeout(function () {
                        $.ajax({
                            url: '{{ route("search.suggestions") }}',
                            data: { search: query },
                            success: function (response) {
                                let suggestionList = $('#suggestion-list');
                                suggestionList.empty();

                                if (response.length > 0) {
                                    response.forEach(function (product) {
                                        var prodUrl = '{{ route("products.show", ":slug") }}'.replace(':slug', product.slug);
                                        let imageUrl = "{{ asset('/') }}/" + product.thumbnail_img;
                                        suggestionList.append(`
                                            <li class="flex items-center gap-2 p-2 hover:bg-gray-100 cursor-pointer border-b">
                                                <a href="${prodUrl}" class="flex">
                                                    <img src="${imageUrl}" class="w-10 h-10 object-cover rounded" alt="${product.name}">
                                                    <span>${product.name}</span>
                                                </a>
                                            </li>
                                        `);
                                    });
                                    suggestionList.removeClass('hidden');
                                } else {
                                    suggestionList.addClass('hidden');
                                }
                            }
                        });
                    }, doneTypingInterval);
                } else {
                    $('#suggestion-list').addClass('hidden');
                }
            });

            // Hide when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.search-popup__form, #suggestion-list').length) {
                    $('#suggestion-list').addClass('hidden');
                }
            });

        });

      
        
    </script>
    <script>
        const menuButton = document.getElementById('menu-button');
        const drawerMenu = document.getElementById('drawer-menu');
        const drawerOverlay = document.getElementById('drawer-overlay');
        const closeMenuButton = document.getElementById('close-menu');
    
        // Open menu
        menuButton.addEventListener('click', () => {
          drawerMenu.classList.add('open');
          drawerOverlay.classList.remove('hidden');
        });
    
        // Close menu
        closeMenuButton.addEventListener('click', () => {
          drawerMenu.classList.remove('open');
          drawerOverlay.classList.add('hidden');
        });
    
        // Close menu when overlay is clicked
        drawerOverlay.addEventListener('click', () => {
          drawerMenu.classList.remove('open');
          drawerOverlay.classList.add('hidden');
        });
      </script>
</body>
</html>
