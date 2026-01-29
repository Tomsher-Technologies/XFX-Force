<header class="bg-white shadow-md w-full fixed top-0 z-50">
    <div class="mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/logo.svg') }}" alt="{{env('APP_NAME')}} Logo" class="h-[50px]">
        </a>
        <div class="relative hidden md:flex w-[25%] lg:flex  lg:w-[35%] xl:w-[18%] 2xl:w-[25%] flex-col">
            <!-- Search Bar -->
            <form class="search-popup__form w-full" action="{{ route('products.index') }}" method="get">
                <div class="flex items-center bg-gray-100 px-2 py-1 rounded-full w-full shadow-sm hover:shadow-md transition-shadow duration-300">
                    <input type="text" name="search" id="search-input" value="{{ request()->get('search') ?? '' }}" placeholder="Search products.." class="bg-transparent py-1 w-full text-gray-700 placeholder-gray-500 border-none outline-none focus:ring-0">
                    <button class="bg-primary text-white p-3 rounded-full hover:bg-secondary transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-[20px] h-[20px]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
           <!-- Suggestion List -->
           <ul id="suggestion-list" class="absolute left-0 top-full w-full bg-white rounded-md shadow-lg z-50 hidden max-h-80 overflow-y-auto">
           <!-- Dynamic results via JS -->
            </ul>
        </div>
        <!-- Navigation Links -->
        <nav class="hidden sm:hidden md:hidden lg:hidden xl:flex space-x-6 text-secondary font-medium ">
            <a href="{{ route('home') }}" class="{{ is_active('home') }} hover:text-primary transition-colors duration-300">Home</a>


            <!-- About Us Link -->
            <a href="{{ route('about-us') }}" class="{{ is_active('about-us') }} hover:text-primary transition-colors duration-300">About Us</a>


            <!-- Products Mega Menu -->
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                <a href="{{ route('products.index') }}" 
                class="{{ is_active(['products.index','products.show']) }} hover:text-primary transition-colors duration-300 flex items-center space-x-2">
                    <span>Products</span>
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg> -->
                </a>

            </div>


            <a href="{{ route('services.index') }}" class="{{ is_active(['services.index','services.show']) }} hover:text-primary transition duration-300">Services</a>


            <a href="{{ route('blog') }}" class="{{ is_active(['blog','blog.details']) }} hover:text-primary transition-colors duration-300">Blog</a>
            <a href="{{ route('contact') }}" class="{{ is_active('contact') }} hover:text-primary transition-colors duration-300">Contact Us</a>
        </nav>
     
        <!-- Wishlist, Cart, Login/Register -->
        <div class="flex items-center space-x-4">
            <!-- Wishlist Icon -->
            <a href="{{ route('wishlist.index') }}">
                <button class="bg-gray-200 w-[45px] h-[45px] flex justify-center items-center rounded-full hover:bg-primary transition-all duration-300 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <path d="M17.3689 4.46447C19.2545 6.35393 19.3192 9.36417 17.5656 11.3275L10.5 18.4042L3.43456 11.3275C1.68095 9.36417 1.7465 6.34918 3.63121 4.46447C5.51806 2.57763 8.53774 2.51406 10.5009 4.27378C12.4584 2.51667 15.4834 2.575 17.3689 4.46447ZM4.80972 5.64298C3.56827 6.88443 3.50595 8.87275 4.65002 10.186L10.5 16.0453L16.3502 10.186C17.4947 8.87225 17.4326 6.88771 16.1892 5.64175C14.9498 4.39983 12.9549 4.34005 11.6453 5.48647L8.14306 8.98908L6.96455 7.81053L9.31841 5.455L9.25025 5.39751C7.93796 4.34331 6.01943 4.43328 4.80972 5.64298Z" fill="black"/>
                    </svg>
                    <span class="wishlist_count absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 w-[20px] h-[20px] bg-primary text-white text-[12px] leading-[20px] text-center rounded-full">{{ wishlistCount() }}</span>
                </button>
            </a>

            <!-- Cart Icon -->
            <button id="cart-btn" class="open-canvas-cart hidden xl:flex  bg-gray-200 w-[45px] h-[45px] flex justify-center items-center rounded-full hover:bg-primary transition-all duration-300 transform hover:scale-105 ">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                    <path d="M1.64099 11.3333V1.61905H0V0H2.46148C2.91463 0 3.28198 0.36244 3.28198 0.809524V10.5238H13.4876L15.1286 4.04762H4.92297V2.42857H16.1795C16.6327 2.42857 17 2.79101 17 3.2381C17 3.30428 16.9918 3.37023 16.9755 3.43444L14.9242 11.5296C14.833 11.89 14.5048 12.1429 14.1283 12.1429H2.46148C2.00834 12.1429 1.64099 11.7804 1.64099 11.3333ZM3.28198 17C2.37568 17 1.64099 16.2752 1.64099 15.381C1.64099 14.4868 2.37568 13.7619 3.28198 13.7619C4.18827 13.7619 4.92297 14.4868 4.92297 15.381C4.92297 16.2752 4.18827 17 3.28198 17ZM13.1279 17C12.2216 17 11.4869 16.2752 11.4869 15.381C11.4869 14.4868 12.2216 13.7619 13.1279 13.7619C14.0342 13.7619 14.7689 14.4868 14.7689 15.381C14.7689 16.2752 14.0342 17 13.1279 17Z" fill="black"/>
                </svg>
                <span class="cart_count absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 w-[20px] h-[20px] bg-primary text-white text-[12px] leading-[20px] text-center rounded-full">{{ cartCount() }}</span>
            </button>

            <!-- Login/Register Button -->
        

            @if(auth()->check())
                <a id="accont-btn" href="#" class="bg-primary text-white w-[125px] h-[45px] flex justify-center items-center rounded-full hover:bg-[#3498db] transition-all duration-300 transform hover:scale-105 font-medium text-sm">
                    My Account
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-primary text-white w-[125px] h-[45px] flex justify-center items-center rounded-full hover:bg-[#3498db] transition-all duration-300 transform hover:scale-105 font-medium text-sm">
                    Login/Register
                </a>
            @endif
        </div>
    </div>

    <x-cart-drawer />
    <x-account-drawer />
</header>