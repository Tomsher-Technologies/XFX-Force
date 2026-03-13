<!--header-->
    <header id="main-header" class="w-full fixed bg-transparent z-[9999] px-[16px] md:px-[140px] transition-all duration-600">

        <div class="top-bar w-full flex items-center justify-center sm:justify-content-center hidden md:block">
            <div
                class="w-full m-auto flex items-center justify-center md:justify-between border-b-1 border-[#393939] pt-[20px] pb-[20px]">

                <div class="contact-info">
                    <a href="#" target="_blank" class="text-[#ffffff] no-underline flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/location-marker.svg') }}" alt="address" title="address" /> <span
                            class="hidden md:block">Office 107, Computer Plaza Al Mankhool, Dubai, UAE</span>
                    </a>
                </div>

                <div class="top-links flex items-center gap-[0px] md:gap-[50px] xl:gap-[100px] justify-between sm:justify-center">
                    <a href="mailto:example@pcgarage.com"
                        class="text-[#ffffff] no-underline flex items-center gap-[10px]"><img src="{{ asset('assets/images/email.svg') }}"
                            alt="Email icon for PC Garage contact information" title="email" /><span
                            class="hidden md:block">example@pcgarage.com</span></a>
                    <a href="tel:+971585083085" target="_blank"
                        class="text-[#ffffff] no-underline flex items-center gap-[10px]"><img src="{{ asset('assets/images/phone.svg') }}"
                            alt="Phone icon for PC Garage contact information" title="phone" /><span
                            class="hidden md:block">+971 58 508 3085</span></a>
                </div>

                <div class="profile-info block md:hidden">
                    <a href="#" class="text-[#ffffff] no-underline flex items-center gap-[10px]"><img
                            src="{{ asset('assets/images/account.svg') }}" alt="User Icon" class="img-fluid" title="User"></a>
                </div>

            </div>
        </div>

        <nav class="main-nav w-full py-[20px]">

            <div
                class="m-auto nav-wrapper grid grid-cols-2 md:grid-cols-[auto_auto_auto] items-center justify-between w-full">

                <div class="logo">
                    <a href="{{ route('home') }}" title="Home - PC Garage | Custom Gaming PCs & High-End Hardware in UAE">
                        <img src="{{ asset('assets/images/PC-Garage-Logo-white.svg') }}" alt="PC Garage Logo" title="PC Garage Logo"
                            class="w-[200px] white ">
                    </a>
                </div>

                <ul class="nav-menu items-center gap-[30px] list-none hidden md:flex items-center">
                    <li><a href="{{ route('home') }}" class="text-[#ffffff] uppercase text-[14px] no-underline">Home</a></li>
                    <li class="has-mega">
                        <a href="#" class="text-[#ffffff] uppercase text-[14px] no-underline flex gap-[10px]">Shop <img
                                src="{{ asset('assets/images/caret-down.svg') }}" alt="Caret Down Icon" class="img-fluid"></a>
                        <div class="mega-menu">
                            <div class="mega-grid">
                                <div class="mega-col">
                                    <h3>Series</h3>
                                    <a href="#">Pro Gamer Series</a>
                                    <a href="#">Streamer Edition</a>
                                    <a href="#">Workstation Pro</a>
                                </div>
                                <div class="mega-col">
                                    <h3>Components</h3>
                                    <a href="#">NVIDIA RTX 50-Series</a>
                                    <a href="#">Intel Core Ultra</a>
                                    <a href="#">AMD Ryzen 9000</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="{{ route('buildyourpc') }}" class="text-[#ffffff] uppercase text-[14px] no-underline">Build your pc</a></li>
                    <li><a href="#" class="text-[#ffffff] uppercase text-[14px] no-underline">Pre-built pc</a></li>
                    <li><a href="#" class="text-[#ffffff] uppercase text-[14px] no-underline">Components</a></li>
                    <li><a href="#" class="text-[#ffffff] uppercase text-[14px] no-underline">Deals</a></li>
                    <li><a href="#" class="text-[#ffffff] uppercase text-[14px] no-underline">About us</a></li>
                </ul>

                <div class="nav-actions flex items-center gap-[30px] md:gap-[50px] justify-end">
                    <!--burger menu trigger-->
                    <button class="burger-menu gap-[6px] flex flex-col bg-transparent border-hidden cursor-pointer"
                        id="menuTrigger">
                        <span class="w-[20px] h-[1.5px] flex bg-[#ffffff]"></span>
                        <span class="w-[20px] h-[1.5px] flex bg-[#ffffff]"></span>
                        <span class="w-[20px] h-[1.5px] flex bg-[#ffffff]"></span>
                    </button>
                    <!--//burger menu trigger-->

                    <!--search trigger-->
                    <button class="action-btn search-trigger bg-transparent border-hidden cursor-pointer">
                        <img src="{{ asset('assets/images/search.svg') }}" alt="Search Icon" class="img-fluid" title="Search">
                    </button>
                    <!--//search trigger-->

                    <!--cart trigger-->
                    <a href="{{ route('cart') }}" class="action-btn cart-icon no-underline relative">
                        <img src="{{ asset('assets/images/cart.svg') }}" alt="Cart Icon" class="img-fluid" title="Cart">
                        <span
                            class="count bg-[#2a7cff] p-[5px] leading-[1] rounded-full text-[#ffffff] text-[12px] w-[10px] h-[10px] absolute top-[-15px] right-[10px] md:right-[-15px] text-center justify-center inline-table align-center items-center" id="total-cart-count-top">{{ \App\Models\Cart::cartItemsCount() }}</span>
                    </a>
                    <!--//cart trigger-->

                    <a href="#" class="action-btn hidden md:block">
                        <img src="{{ asset('assets/images/account.svg') }}" alt="User Icon" class="img-fluid" title="User">
                    </a>

                </div>
            </div>
        </nav>

    </header>
    <div class="mega-overlay" id="megaOverlay">
        <div class="overlay-content">
            <div class="container">
                <div class="mega-grid">
                    <div class="mega-column">
                        <h3>Build Your Rig</h3>
                        <a href="/custom-pc">Custom PC Builder</a>
                        <a href="/pre-built">Pre-built Desktops</a>
                        <a href="/workstations">Workstations</a>
                    </div>
                    <div class="mega-column">
                        <h3>Hardware</h3>
                        <a href="/gpu">NVIDIA RTX 50-Series</a>
                        <a href="/cpu">Intel Core Ultra</a>
                        <a href="/ram">DDR5 Memory</a>
                    </div>
                    <div class="mega-column">
                        <h3>Services</h3>
                        <a href="/repair">Repair & Cleaning</a>
                        <a href="/upgrade">GPU Upgrade Program</a>
                        <a href="/contact">Visit Showroom</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--//header-->