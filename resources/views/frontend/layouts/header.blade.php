<header id="main-header" class="w-full fixed bg-transparent z-[9999] px-[16px] md:px-[30px] xl:px-[140px] transition-all duration-600">
        <div class="top-bar w-full flex items-center justify-center sm:justify-content-center hidden xl:block">
            <div class="w-full m-auto flex items-center justify-center md:justify-between border-b-1 border-[#393939] pt-[20px] pb-[20px]">
                <div class="contact-info">
                    <a href="#" target="_blank" class="text-[#ffffff] no-underline flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/location-marker.svg') }}" alt="address" title="address" /> <span class="hidden md:block">Office 107, Computer Plaza Al Mankhool, Dubai, UAE</span>
                    </a>
                </div>
                <div class="top-links flex items-center gap-[0px] md:gap-[50px] xl:gap-[100px] justify-between sm:justify-center">
                    <a href="mailto:example@pcgarage.com" class="text-[#ffffff] no-underline flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/email.svg') }}" alt="Email icon for PC Garage contact information" title="email" />
                        <span class="hidden md:block">example@pcgarage.com</span>
                    </a>
                    <a href="tel:+971585083085" target="_blank" class="text-[#ffffff] no-underline flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/phone.svg') }}" alt="Phone icon for PC Garage contact information" title="phone" />
                        <span class="hidden md:block">+971 58 508 3085</span>
                    </a>
                </div>
                <div class="profile-info block md:hidden">
                    <a href="#" class="text-[#ffffff] no-underline flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/account.svg') }}" alt="User Icon" class="img-fluid" title="User">
                    </a>
                </div>
            </div>
        </div>
        <nav class="main-nav w-full py-[20px]">
            <div class="m-auto nav-wrapper grid grid-cols-2 xl:grid-cols-[auto_auto_auto] items-center justify-between w-full">
                <div class="logo">
                    <a href="{{  route('home') }}" title="Home - PC Garage | Custom Gaming PCs & High-End Hardware in UAE">
                        <img src="{{ asset('assets/images/PC-Garage-Logo-white.svg') }}" alt="PC Garage Logo" title="PC Garage Logo" class="w-[200px] white ">
                    </a>
                </div>
                <ul class="nav-menu items-center gap-[30px] list-none hidden xl:flex items-center">
                    <li><a href="#" class="text-[#ffffff] uppercase text-[14px] no-underline">Home</a></li>
                    <li class="has-mega">
                        <a href="shop.html" class="text-[#ffffff] uppercase text-[14px] no-underline flex gap-[10px]">Shop <img src="{{ asset('assets/images/caret-down.svg') }}" alt="Caret Down Icon" class="img-fluid"></a>
                        <div class="mega-menu absolute top-full m-auto left-0 right-0 w-5xl bg-white p-10 shadow-[0_10px_30px_rgba(0,0,0,0.1)] opacity-0 invisible translate-y-[0px] transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:visible group-hover:translate-y-[55px]">
                            <div class="mega-grid w-full mx-auto grid grid-cols-2 gap-[50px]">
                                <div>
                                    <h4 class="text-sm text-gray-400 uppercase pb-[10px] mb-[15px] border-b-1 border-gray-300">Shop by Categories</h4>
                                    <ul class="grid grid-cols-2 md:grid-cols-2 gap-x-8">
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Graphics Cards</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Processors</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Motherboard</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">RAM</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Storage</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Power Supply</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Fans</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Cooling System</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Cases</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Accessories</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-sm text-gray-400 uppercase pb-[10px] mb-[15px] border-b-1 border-gray-300">Shop by Brands</h4>
                                    <ul class="grid grid-cols-2 md:grid-cols-2 gap-x-8">
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Acer</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">AMD</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Benq</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Glorious</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Intel</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Samsung</a></li>
                                        <li><a href="shop-category.html" class="transition-all duration-600 hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">XFX</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="build-your-pc.html" class="text-[#ffffff] uppercase text-[14px] no-underline">Build your pc</a></li>
                    <li><a href="shop-category.html" class="text-[#ffffff] uppercase text-[14px] no-underline">Pre-built pc</a></li>
                    <li><a href="shop-category.html" class="text-[#ffffff] uppercase text-[14px] no-underline">Components</a></li>
                    <li><a href="shop.html" class="text-[#ffffff] uppercase text-[14px] no-underline">Deals</a></li>
                    <li><a href="about.html" class="text-[#ffffff] uppercase text-[14px] no-underline">About us</a></li>
                </ul>
                <div class="nav-actions flex items-center gap-[30px] md:gap-[50px] justify-end">
                    <!--burger menu trigger-->
                    <button onclick="toggleMobileMenu()" class="burger-menu gap-[6px] flex xl:hidden flex-col bg-transparent border-hidden cursor-pointer">
                        <span class="w-[20px] h-[1.5px] flex bg-[#ffffff]"></span>
                        <span class="w-[20px] h-[1.5px] flex bg-[#ffffff]"></span>
                        <span class="w-[20px] h-[1.5px] flex bg-[#ffffff]"></span>
                    </button>
                    <!--//burger menu trigger-->

                    <!--search trigger-->
                    <button onclick="toggleSearch()" class="action-btn search-trigger bg-transparent border-hidden cursor-pointer">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.9999 14.1167L11.0861 10.2029C12.1028 8.95957 12.6026 7.373 12.4823 5.77142C12.3619 4.16984 11.6306 2.67578 10.4396 1.59827C9.24859 0.520763 7.68898 -0.0577488 6.08339 -0.0176039C4.4778 0.022541 2.94905 0.678271 1.81337 1.81395C0.677691 2.94963 0.0219612 4.47838 -0.0181837 6.08397C-0.0583286 7.68956 0.520183 9.24917 1.59769 10.4402C2.6752 11.6312 4.16926 12.3625 5.77084 12.4828C7.37242 12.6032 8.95899 12.1033 10.2024 11.0867L14.1161 15.0004L14.9999 14.1167ZM6.24986 11.2504C5.26096 11.2504 4.29426 10.9572 3.47201 10.4078C2.64977 9.85838 2.0089 9.07749 1.63047 8.16386C1.25203 7.25023 1.15301 6.2449 1.34594 5.27499C1.53886 4.30509 2.01507 3.41417 2.71433 2.71491C3.41359 2.01565 4.30451 1.53944 5.27441 1.34652C6.24432 1.15359 7.24965 1.25261 8.16328 1.63105C9.07691 2.00948 9.8578 2.65035 10.4072 3.47259C10.9566 4.29484 11.2499 5.26154 11.2499 6.25044C11.2484 7.57607 10.7211 8.84697 9.78375 9.78433C8.84639 10.7217 7.57549 11.249 6.24986 11.2504Z" fill="white"/>
                        </svg>
                    </button>
                    <!--//search trigger-->

                    <!--cart trigger-->
                    <a href="cart.html" class="action-btn cart-icon no-underline relative bg-transparent border-none cursor-pointer flex items-center justify-center p-2 hover:bg-white/5 rounded-lg transition-all">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.375 12.5C5.06522 12.5 5.62479 13.0598 5.625 13.75C5.625 14.4404 5.06535 15 4.375 15C3.6847 14.9999 3.125 14.4403 3.125 13.75C3.12521 13.0599 3.68483 12.5001 4.375 12.5ZM10.625 12.5C11.3152 12.5 11.8748 13.0598 11.875 13.75C11.875 14.4404 11.3154 15 10.625 15C9.93465 15 9.375 14.4404 9.375 13.75C9.37521 13.0598 9.93478 12.5 10.625 12.5ZM0.763672 0C1.22266 0.000213547 1.66578 0.168673 2.00879 0.473633C2.35176 0.778715 2.57121 1.19942 2.625 1.65527L2.65137 1.875H15L13.6475 9.375H3.53418L3.61523 10.0723C3.633 10.2244 3.70688 10.3649 3.82129 10.4668C3.93567 10.5685 4.08326 10.625 4.23633 10.625H12.5V11.875H4.23633C3.77734 11.8748 3.33422 11.7063 2.99121 11.4014C2.64824 11.0963 2.42879 10.6756 2.375 10.2197L1.38477 1.80176C1.36684 1.64975 1.29309 1.50991 1.17871 1.4082C1.06432 1.30649 0.916744 1.25002 0.763672 1.25H0V0H0.763672ZM3.38672 8.125H12.6025L13.5039 3.125H2.79883L3.38672 8.125Z" fill="white"/>
                        </svg>
                        <span class="count bg-[#2a7cff] text-white text-[10px] font-bold h-5 w-5 inline-flex leading-[25px] p-[5px] items-center justify-center rounded-full absolute -top-1 -right-1 border-2 border-[#0B0F13]">10</span>
                    </a>
                    <!--//cart trigger-->

                    
                    <!--profile trigger-->
                        @if(Auth('frontend')->check())
                            <button type="button"  onclick="toggleSidePanel()" id="profile-trigger" class="cursor-pointer action-btn hidden md:flex items-center justify-center hover:bg-white/5 p-2 rounded-lg transition-all">
                                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.15234 8.74992C8.97353 8.75104 9.76112 9.07746 10.3418 9.65813C10.9225 10.2388 11.2489 11.0264 11.25 11.8476V14.9999H10V11.8476C9.99938 11.3578 9.80437 10.8883 9.45801 10.5419C9.11164 10.1956 8.64217 10.0005 8.15234 9.99992H3.09863C2.60865 10.0004 2.13847 10.1954 1.79199 10.5419C1.44568 10.8883 1.25062 11.3578 1.25 11.8476V14.9999H0V11.8476C0.00111301 11.0264 0.328507 10.2388 0.90918 9.65813C1.48992 9.0776 2.27748 8.75092 3.09863 8.74992H8.15234ZM4.89355 0.0721893C5.62096 -0.0725011 6.37534 0.0012594 7.06055 0.28508C7.74554 0.568873 8.33118 1.04948 8.74316 1.66594C9.15521 2.28261 9.375 3.00826 9.375 3.74992C9.37396 4.74408 8.97837 5.69733 8.27539 6.40031C7.57238 7.10323 6.61915 7.49893 5.625 7.49992C4.88352 7.4999 4.15855 7.27996 3.54199 6.86809C2.92533 6.45604 2.44495 5.8697 2.16113 5.18449C1.87737 4.49941 1.80266 3.74577 1.94727 3.01848C2.09194 2.29115 2.44929 1.62296 2.97363 1.09856C3.49805 0.574143 4.16618 0.21689 4.89355 0.0721893ZM5.625 1.24992C5.13068 1.24995 4.64736 1.39623 4.23633 1.67082C3.82525 1.9455 3.50465 2.33613 3.31543 2.79289C3.12625 3.24961 3.07647 3.75238 3.17285 4.23723C3.26931 4.72217 3.5078 5.16788 3.85742 5.5175C4.20704 5.86711 4.65277 6.10561 5.1377 6.20207C5.62253 6.29844 6.12534 6.24866 6.58203 6.05949C7.03878 5.87027 7.42943 5.54967 7.7041 5.1386C7.97869 4.72758 8.12496 4.24423 8.125 3.74992C8.125 3.08691 7.86139 2.45117 7.39258 1.98235C6.92375 1.51352 6.28802 1.24992 5.625 1.24992Z" fill="white"/>
                                </svg>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="action-btn hidden md:flex items-center justify-center hover:bg-white/5 p-2 rounded-lg transition-all">
                                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.15234 8.74992C8.97353 8.75104 9.76112 9.07746 10.3418 9.65813C10.9225 10.2388 11.2489 11.0264 11.25 11.8476V14.9999H10V11.8476C9.99938 11.3578 9.80437 10.8883 9.45801 10.5419C9.11164 10.1956 8.64217 10.0005 8.15234 9.99992H3.09863C2.60865 10.0004 2.13847 10.1954 1.79199 10.5419C1.44568 10.8883 1.25062 11.3578 1.25 11.8476V14.9999H0V11.8476C0.00111301 11.0264 0.328507 10.2388 0.90918 9.65813C1.48992 9.0776 2.27748 8.75092 3.09863 8.74992H8.15234ZM4.89355 0.0721893C5.62096 -0.0725011 6.37534 0.0012594 7.06055 0.28508C7.74554 0.568873 8.33118 1.04948 8.74316 1.66594C9.15521 2.28261 9.375 3.00826 9.375 3.74992C9.37396 4.74408 8.97837 5.69733 8.27539 6.40031C7.57238 7.10323 6.61915 7.49893 5.625 7.49992C4.88352 7.4999 4.15855 7.27996 3.54199 6.86809C2.92533 6.45604 2.44495 5.8697 2.16113 5.18449C1.87737 4.49941 1.80266 3.74577 1.94727 3.01848C2.09194 2.29115 2.44929 1.62296 2.97363 1.09856C3.49805 0.574143 4.16618 0.21689 4.89355 0.0721893ZM5.625 1.24992C5.13068 1.24995 4.64736 1.39623 4.23633 1.67082C3.82525 1.9455 3.50465 2.33613 3.31543 2.79289C3.12625 3.24961 3.07647 3.75238 3.17285 4.23723C3.26931 4.72217 3.5078 5.16788 3.85742 5.5175C4.20704 5.86711 4.65277 6.10561 5.1377 6.20207C5.62253 6.29844 6.12534 6.24866 6.58203 6.05949C7.03878 5.87027 7.42943 5.54967 7.7041 5.1386C7.97869 4.72758 8.12496 4.24423 8.125 3.74992C8.125 3.08691 7.86139 2.45117 7.39258 1.98235C6.92375 1.51352 6.28802 1.24992 5.625 1.24992Z" fill="white"/>
                                </svg>
                                <span class="text-white ml-1">Login</span>
                            </a>
                        @endif
                    <!--profile trigger-->
                </div>
            </div>
        </nav>
    </header>

     <!--my account side panel-->
    <div id="side-panel-overlay" onclick="toggleSidePanel()" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] opacity-0 pointer-events-none transition-opacity duration-300"></div>
    <div id="account-side-panel" class="fixed top-0 right-0 h-full w-full max-w-[380px] bg-[#0B0F13] border-l border-white/5 z-[99999] translate-x-full transition-transform duration-300 ease-in-out shadow-[-20px_0_50px_rgba(0,0,0,0.5)]">
        <div class="flex flex-col h-full">
            <div class="p-6 border-b border-white/5 flex items-center justify-between bg-[#1C2228]/50">
                <div class="flex items-center gap-4">
                    <div id="userAvatar" class="flex items-center justify-center w-12 h-12 rounded-full bg-[#282B34] border border-white/5 text-white font-bold text-xl">?</div>
                    <div class="flex flex-col gap-[2px]">
                        <span id="userName" class="font-medium text-[18px] text-white">{{ auth('frontend')->user()->name ?? '' }}</span>
                        <p class="text-gray-500 text-[11px] uppercase tracking-wider">{{ auth('frontend')->user()->email ?? '' }}</p>
                    </div>
                </div>
                <button onclick="toggleSidePanel()" class="p-2 hover:bg-white/5 rounded-full text-gray-400 hover:text-white transition-all">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-grow overflow-y-auto p-6 space-y-8">
                <nav class="flex flex-col gap-[5px] xl:space-y-0">
                            <a href="{{ route('account') }}" class="w-full text-gray-400 flex flex-row items-start gap-4 p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 text-[#ffffff]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="text-[15px]">Profile</span>
                            </a>

                            <a href="my-orders.html" class="w-full text-gray-400 flex flex-row items-start gap-4 p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg id="Layer_1" class="text-[#898989] w-5 h-5 group-hover:text-white" fill="none" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path class="fill-[#99a1af] group-hover:fill-[#ffffff] transition-all duration-600" d="m19 0h-14a5.006 5.006 0 0 0 -5 5v14a5.006 5.006 0 0 0 5 5h14a5.006 5.006 0 0 0 5-5v-14a5.006 5.006 0 0 0 -5-5zm3 5h-7v-3h4a3 3 0 0 1 3 3zm-11-3h2v5a1 1 0 0 1 -2 0zm-6 0h4v3h-7a3 3 0 0 1 3-3zm14 20h-14a3 3 0 0 1 -3-3v-12h7a3 3 0 0 0 6 0h7v12a3 3 0 0 1 -3 3zm1-3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 1 1z"/></svg>
                                <span class="text-[15px]">Orders</span>
                            </a>

                            <a href="my-address.html" class="w-full text-gray-400 flex flex-row items-start gap-4 p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-[15px]">Address</span>
                            </a>

                            <a href="wishlist.html" class="w-full text-gray-400 flex flex-row items-start gap-4 p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <span class="text-[15px]">Wishlist</span>
                            </a>

                            <a href="accounts-change-password.html" class="w-full text-gray-400 flex flex-row items-start gap-4 p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24"><path fill="#9F9FA9" class="group-hover:fill-white" d="M19,8.424V7A7,7,0,0,0,5,7V8.424A5,5,0,0,0,2,13v6a5.006,5.006,0,0,0,5,5H17a5.006,5.006,0,0,0,5-5V13A5,5,0,0,0,19,8.424ZM7,7A5,5,0,0,1,17,7V8H7ZM20,19a3,3,0,0,1-3,3H7a3,3,0,0,1-3-3V13a3,3,0,0,1,3-3H17a3,3,0,0,1,3,3Z"/><path fill="#9F9FA9" class="group-hover:fill-white" d="M12,14a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,12,14Z"/></svg>
                                <span class="text-[15px]">Password</span>
                            </a>
                        </nav>                           
            </div>
            <div class="p-6 border-t border-white/5 bg-[#0B0F13]">
                <a href="{{ route('logout') }}" class="flex items-center justify-center gap-3 w-full py-4 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-xl font-bold uppercase text-[11px] tracking-widest transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Sign Out
                </a>
            </div>
        </div>
    </div>
    <!--//my account side panel-->

    <!--search panel-->
    <div id="search-overlay" onclick="toggleSearch()" class="fixed inset-0 bg-black/40 backdrop-blur-md z-[90] opacity-0 pointer-events-none transition-all duration-500"></div>
    <div id="search-mega-menu" class="absolute top-0 left-0 pt-[150px] w-full bg-[#0B0F13] border-b border-white/5 z-[100] -translate-y-full transition-transform duration-500 ease-in-out invisible">
        <div class="container mx-auto px-6 py-12">
            <div class="max-w-4xl mx-auto mb-12">
                <div class="relative group">
                    <input type="text" id="mega-search-input" placeholder="What are you looking for?" class="w-full bg-transparent border-b-2 border-white/10 py-6 text-2xl md:text-4xl text-white placeholder-gray-700 focus:outline-none focus:border-[#2A7CFF] transition-all">
                    <div class="absolute right-0 bottom-6 text-[#2A7CFF] opacity-0 group-focus-within:opacity-100 transition-opacity">
                        <span class="text-[12px] font-medium uppercase tracking-widest">Press Enter to Search</span>
                    </div>
                </div>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="flex flex-col">
                    <a href="/product/rtx-5090" class="group flex items-center justify-between py-5 border-b border-white/5 hover:bg-white/[0.02] px-4 transition-all">
                        <div class="flex items-center gap-6">
                            <svg class="w-4 h-4 text-gray-600 group-hover:text-[#2A7CFF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"/></svg>
                            <span class="text-white text-sm font-medium group-hover:text-[#2A7CFF] transition-all">NVIDIA GeForce RTX 5090 Founders Edition</span>
                        </div>
                    </a>
                    <a href="/product/i9-14900k" class="group flex items-center justify-between py-5 border-b border-white/5 hover:bg-white/[0.02] px-4 transition-all">
                        <div class="flex items-center gap-6">
                            <svg class="w-4 h-4 text-gray-600 group-hover:text-[#2A7CFF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"/></svg>
                            <span class="text-white text-sm font-medium group-hover:text-[#2A7CFF] transition-all">Intel Core i9-14900K Processor</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--//search panel-->

    <!--mobile burger menu-->
    <div id="mobile-menu-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] opacity-0 pointer-events-none transition-opacity duration-300"></div>
    <div id="mobile-side-panel" class="fixed top-0 left-0 h-full w-[300px] bg-[#0B0F13] z-[99999] -translate-x-full transition-transform duration-300 ease-in-out border-r border-white/5">
        <div class="flex flex-col h-full">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <div class="logo">
                    <a href="{{  route('home') }}" title="Home - PC Garage | Custom Gaming PCs & High-End Hardware in UAE">
                        <img src="src/images/PC-Garage-Logo-white.svg" alt="PC Garage Logo" title="PC Garage Logo" class="w-[200px] white ">
                    </a>
                </div>
                <button onclick="toggleMobileMenu()" class="text-gray-400">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-grow overflow-y-auto p-4 custom-scrollbar">
                <ul class="space-y-2">
                    <li><a href="#" class="block p-3 text-white uppercase text-sm font-medium hover:bg-white/5 rounded-lg">Home</a></li>
                    
                    <li class="group">
                        <button onclick="toggleSubMenu('shop-sub')" class="w-full flex items-center justify-between p-3 text-white uppercase text-sm font-medium hover:bg-white/5 rounded-lg transition-all">
                            Shop
                            <svg id="shop-caret" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        
                        <div id="shop-sub" class="hidden overflow-hidden pl-4 mt-2 space-y-4">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase mb-3">By Categories</p>
                                <ul class="grid grid-cols-1 gap-1">
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Graphics Cards</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Processors</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Motherboard</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">RAM</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Storage</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Power Supply</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Fans</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Cooling System</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Cases</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Accessories</a></li>
                                </ul>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase mb-3">By Brands</p>
                                <ul class="grid grid-cols-1 gap-1">
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Acer</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">AMD</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Benq</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Glorious</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Intel</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">Samsung</a></li>
                                    <li><a href="shop-category.html" class="transition-all duration-600 text-white hover:text-[#2A7CFF] hover:pl-[10px] py-[5px] w-full inline-block">XFX</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li><a href="#" class="block p-3 text-white uppercase text-sm font-medium hover:bg-white/5 rounded-lg">Build Your PC</a></li>
                    <li><a href="#" class="block p-3 text-white uppercase text-sm font-medium hover:bg-white/5 rounded-lg">Pre-built PC</a></li>
                    <li><a href="#" class="block p-3 text-white uppercase text-sm font-medium hover:bg-white/5 rounded-lg">Components</a></li>
                    <li><a href="#" class="block p-3 text-white uppercase text-sm font-medium hover:bg-white/5 rounded-lg text-[#2A7CFF]">Deals</a></li>
                    <li><a href="#" class="block p-3 text-white uppercase text-sm font-medium hover:bg-white/5 rounded-lg">About Us</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <!--//mobile burger menu-->