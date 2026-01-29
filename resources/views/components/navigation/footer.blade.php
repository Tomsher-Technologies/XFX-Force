<section class="marquee-wrapper overflow-hidden">
    <div class="marquee overflow-hidden">
        @php
            $moving_text = get_setting('footer_moving_text');
        @endphp
        <div class="marquee-content">
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
        </div>
        <div class="marquee-content">
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
            <span>{{ $moving_text }} -</span>
        </div>
    </div>
</section>

<style>
    /* Marquee Wrapper */
    .marquee-wrapper {
        overflow: hidden;
        background-color: white;
        padding: 20px 0;
        width: 100%;
        white-space: nowrap;
        position: relative;
    }

    /* Marquee Container */
    .marquee {
        display: flex;
        width: 200%;
        animation: marquee-scroll 20s linear infinite;
    }

    /* Marquee Content */
    .marquee-content {
        display: flex;
        gap: 5rem;
        font-size: 4rem;
        /* Large font */
        font-weight: 400;
        color: #4A4A4A;
        /* Gray text */
        flex-shrink: 0;
        min-width: 100%;
    }

    /* Smooth Scroll Animation */
    @keyframes marquee-scroll {
        from {
            transform: translateX(0%);
        }

        to {
            transform: translateX(-100%);
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const marquee = document.querySelector(".marquee");
        const content = document.querySelector(".marquee-content");

        // Clone content for seamless looping
        const clone = content.cloneNode(true);
        marquee.appendChild(clone);
    });
</script>

<footer
    class="container-fluid mx-4 sm:mx-6 md:mx-8 mb-4 md:mb-10 bg-[#1F1F1F] text-white py-12 rounded-3xl overflow-hidden">

    <div class="mx-auto px-6 lg:px-12 mb-5 xl-mb-0">

        <!-- Top Section: Logo & Subscribe in One Row -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b border-gray-600 pb-6">
            <!-- Logo & Tagline -->
            <div class="flex flex-col items-center md:items-start space-y-2">
                <a href="{{ route('home') }}">
                    <img src="{{ get_setting('footer_logo') ? uploaded_asset(get_setting('footer_logo')) : asset('images/logo.svg') }}" alt="HomeIQ Logo" class="h-10">
                </a>
                <p class="text-white text-sm">{!! get_setting('about_us_description', null, $lang) !!}</p>
            </div>

            <form id="newsletter-form">
                <!-- Subscribe Section -->
                <div class="flex flex-col md:flex-row md:items-center md:space-x-6 mt-6 md:mt-0">

                    @csrf
                    <div class="flex flex-col space-y-3 text-center md:text-left">
                        <h3 class="text-white font-semibold text-sm">{{ get_setting('footer_newsletter_title') }}</h3>
                        <input type="email" name="newsletter_email" id="newsletter_email" placeholder="Your Mail"
                            class="px-4 py-2 w-full bg-white text-black rounded-full focus:outline-none focus:ring-2 focus:ring-[#41B6E8] placeholder-gray-400">
                        <p class="text-gray-400 text-sm">{{ get_setting('footer_newsletter_subtitle') }}</p>
                    </div>

                    <button
                        class="mt-4 md:mt-0 px-5 py-3 border border-white text-white rounded-full text-sm hover:bg-white hover:text-black transition-all"
                        type="submit">
                        {{ get_setting('footer_newsletter_button') }}
                    </button>
                </div>
                <p id="messageNewsletter" class="mt-2 ml-[3rem]"></p>
            </form>
        </div>


        <!-- Middle Section: Contact & Links in One Row -->
        <div class="grid grid-cols-1  sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-8 items-start">
            <!-- Contact & Address (Left Side) with Right Border -->
            <div class="col-span-1 border-0 md:border-r lg:border-r xl:border-r border-gray-600 pr-6 ">
                <h3 class="text-gray-300 font-semibold text-sm">{{ get_setting('footer_contact_title') }}</h3>
                <a href="tel:{{ get_setting('footer_phone') }}" class="text-gray-400 text-sm mt-1">
                    {{ get_setting('footer_phone') }}
                </a>
                <br>
                <a href="mailto:{{ get_setting('footer_email') }}"
                    class="text-[#41B6E8] text-sm font-medium hover:underline">{{ get_setting('footer_email') }}</a>

                <h3 class="text-gray-300 font-semibold text-sm mt-6">{{ get_setting('footer_address_title') }}</h3>
                <p class="text-gray-400 text-sm mt-1">
                    {!! nl2br(e(get_setting('footer_address'))) !!}
                </p>
            </div>

            @php
                $details = getFooter();
            @endphp

            <!-- Smart Solutions -->
            <div class="ps-0 xl:ps-l">
                <h3 class="text-gray-300 font-semibold text-sm ">{{ get_setting('footer_category_title_1') }}</h3>
                <ul class="text-gray-400 text-sm mt-3 space-y-2">
                    @if(!empty($details['footer_categories']))
                        @foreach($details['footer_categories'] as $footer_categories)
                            <li><a href="{{ route('products.index',['category' => $footer_categories->getTranslation('slug', $lang)]) }}"  class="hover:text-white">{{ $footer_categories->getTranslation('name', $lang) }}</a></li>
                        @endforeach
                    @endif  
                </ul>
            </div>

            <!-- Shop -->
            <div class="pt-6 sm:pt-6 md:pt-6 lg:pt-0 xl:pt-0 lg:pt-0 xl:pt-0 2xl:pt-0">
                <h3 class="text-gray-300 font-semibold text-sm">{{ get_setting('footer_category_title_2') }}</h3>
                <ul class="text-gray-400 text-sm mt-3 space-y-2">
                    
                    @if(!empty($details['footer_services']))
                        @foreach($details['footer_services'] as $footer_services)
                            <li><a href="{{ route('services.show',['slug' => $footer_services->slug]) }}"  class="hover:text-white">{{ $footer_services->getTranslation('name', $lang) }}</a></li>
                        @endforeach
                    @endif  
                </ul>
            </div>

           
            <!-- Resources & Company -->
            <div class="pt-6 sm:pt-6 md:pt-6 lg:pt-0 xl:pt-0 lg:pt-0 xl:pt-0 2xl:pt-0">
                <h3 class="text-gray-300 font-semibold text-sm">{{ get_setting('footer_category_title_3') }}</h3>
                <ul class="text-gray-400 text-sm mt-3 space-y-2">
                    <li><a href="{{ route('faq') }}" class="hover:text-white">FAQ</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:text-white">Blog</a></li>
                    <li><a href="{{ route('privacy-policy') }}" class="hover:text-white">Privacy Policy</a></li>
                    <li><a href="{{ route('terms-conditions') }}" class="hover:text-white">Terms of Service</a></li>
                    <li><a href="{{ route('return-policy') }}" class="hover:text-white">Return Policy</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-gray-300 font-semibold text-sm">{{ get_setting('footer_category_title_4') }}</h3>
                <ul class="text-gray-400 text-sm mt-3 space-y-2">
                    <li><a href="{{ route('about-us') }}" class="hover:text-white">About Us</a></li>
                    <li><a href="{{ route('brand-listing') }}" class="hover:text-white">Partners</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact Us</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Section: Copyright & Links -->
        <div
            class="mt-7 border-t border-gray-600 pt-4 flex flex-col md:flex-row md:items-center md:justify-between text-sm text-gray-400 space-y-2 md:space-y-0">
            <p class="text-center md:text-left">
                {{ str_replace('{year}', date('Y'), get_setting('frontend_copyright_text', null, $lang)) }} | Website by
                <a href="https://www.tomsher.com/" target="_blank">Tomsher</a></p>
            <div class="flex flex-wrap justify-center md:justify-end space-x-4 md:space-x-6 pt-2 xl:pt-0">
                <a href="{{ route('privacy-policy') }}" class="hover:text-white">Privacy Policy</a>
                <a href="{{ route('terms-conditions') }}" class="hover:text-white">Terms of Service</a>
                <a href="{{ route('return-policy') }}" class="hover:text-white">Return Policy</a>
            </div>
        </div>

    </div>



</footer>

    
    <!-- Mobile Bottom Navigation -->
    <div class="xl:hidden fixed z-30 bottom-0 bg-primary flex items-center justify-between w-full h-16 px-3 sm:px-10">
        <!-- Menu Icon -->
        <button id="menu-button" class="flex items-center justify-center h-auto relative focus:outline-none menu-icon">
        <span class="text-xl text-white">
            <svg class="w-6 h-6 drop-shadow-xl" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none"
            stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <line x1="17" y1="10" x2="3" y2="10"></line>
            <line x1="21" y1="6" x2="3" y2="6"></line>
            <line x1="21" y1="14" x2="3" y2="14"></line>
            <line x1="17" y1="18" x2="3" y2="18"></line>
            </svg>
        </span>
        </button>

        <!-- Home Icon -->
        <a class="text-xl text-white" rel="noreferrer" aria-label="Home" href="{{ route('home') }}">
        <svg class="w-6 h-6 drop-shadow-xl" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none"
            stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        </a>

        <!-- Cart Icon -->
        <button class="h-9 w-9 relative inline-flex items-center justify-center text-white text-lg">
        <svg class="w-6 h-6 drop-shadow-xl" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none"
            stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        </button>

        <!-- User Icon -->
        <button type="button" class="text-xl h-9 w-9 text-white indicator justify-center">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>

        </button>
    </div>

    <!-- Drawer Menu -->
    <div id="drawer-menu" class="fixed xl:hidden top-0 left-0 w-64 h-full bg-white shadow-xl z-50 p-5">
        <button id="close-menu" class="text-xl absolute top-5 right-5">
        <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        </button>


         <nav class=" flex flex-col space-y-6 text-secondary font-medium ">
            <a href="{{ route('home') }}" class="{{ is_active('home') }} hover:text-primary transition-colors duration-300 mb-0">Home</a>


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

    </div>

    <!-- Drawer Overlay -->
    <div id="drawer-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden"></div>

 
