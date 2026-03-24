@extends('frontend.layouts.app')

@section('title', 'Order Failed')
@section('content')
   
   
<!--order success-->
    <section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
       <div class="text-white">
            <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px] justify-center">
                
                <main class="mt-[50px] xl:mt-[0]">
                    <div class="max-w-2xl mx-auto">
                        
                        <div class="text-center mb-12">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-500/10 rounded-full mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" class="w-10" data-name="Layer 1" viewBox="0 0 24 24">
                                    <path fill="red" d="m22.242,5.272l-3.515-3.515c-1.133-1.133-2.64-1.757-4.242-1.757h-4.971c-1.603,0-3.109.624-4.242,1.757l-3.515,3.514c-1.134,1.133-1.758,2.64-1.758,4.243v4.971c0,1.603.624,3.11,1.758,4.243l3.515,3.515c1.133,1.133,2.64,1.757,4.242,1.757h4.971c1.603,0,3.109-.624,4.242-1.757l3.515-3.514c1.134-1.133,1.758-2.64,1.758-4.243v-4.971c0-1.603-.624-3.11-1.758-4.243Zm-.242,9.213c0,1.069-.416,2.073-1.172,2.829l-3.515,3.515c-.756.755-1.76,1.171-2.828,1.171h-4.971c-1.068,0-2.072-.416-2.828-1.171l-3.515-3.515c-.756-.755-1.172-1.759-1.172-2.828v-4.971c0-1.069.416-2.073,1.172-2.829l3.515-3.515c.756-.755,1.76-1.171,2.828-1.171h4.971c1.068,0,2.072.416,2.828,1.171l3.515,3.515c.756.755,1.172,1.759,1.172,2.828v4.971Zm-11-.485V6c0-.552.447-1,1-1s1,.448,1,1v8c0,.552-.447,1-1,1s-1-.448-1-1Zm2.5,3.5c0,.828-.672,1.5-1.5,1.5s-1.5-.672-1.5-1.5.672-1.5,1.5-1.5,1.5.672,1.5,1.5Z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-medium text-white uppercase mb-4">Order Failed</h2>
                            <p class="text-gray-400">Something went wrong with your transaction. Don't worry, no funds were deducted from your account.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('checkout') }}" class="flex-1 bg-[#2A7CFF] text-white text-center py-5 rounded-2xl font-medium uppercase text-[13px] hover:bg-white hover:text-black transition-all duration-300">
                                Try Again
                            </a>
                            <a href="contact.html" class="flex-1 border border-white/10 text-white text-center py-5 rounded-2xl font-medium uppercase text-[13px] hover:bg-white/5 transition-all">
                                Contact Support
                            </a>
                        </div>

                        <div class="mt-12 text-center">
                            <a href="{{ route('products') }}" class="w-full uppercase justify-center text-gray-500 hover:text-[#2A7CFF] transition-all flex items-center gap-2 text-sm font-medium mb-8 xl:mb-4 group">
                                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                Return to Shop
                            </a>
                        </div>
                    </div>
                </main>

            </div>
        </div>
        
    </section>
    <!--//order success-->

    <!--my account side panel-->
    <div id="side-panel-overlay" onclick="toggleSidePanel()" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] opacity-0 pointer-events-none transition-opacity duration-300"></div>
    <div id="account-side-panel" class="fixed top-0 right-0 h-full w-full max-w-[380px] bg-[#0B0F13] border-l border-white/5 z-[99999] translate-x-full transition-transform duration-300 ease-in-out shadow-[-20px_0_50px_rgba(0,0,0,0.5)]">
        <div class="flex flex-col h-full">
            <div class="p-6 border-b border-white/5 flex items-center justify-between bg-[#1C2228]/50">
                <div class="flex items-center gap-4">
                    <div id="userAvatar" class="flex items-center justify-center w-12 h-12 rounded-full bg-[#282B34] border border-white/5 text-white font-bold text-xl">?</div>
                    <div class="flex flex-col gap-[2px]">
                        <span id="userName" class="font-medium text-[18px] text-white">Tom Hiddleston</span>
                        <p class="text-gray-500 text-[11px] uppercase tracking-wider">Registered Member</p>
                    </div>
                </div>
                <button onclick="toggleSidePanel()" class="p-2 hover:bg-white/5 rounded-full text-gray-400 hover:text-white transition-all">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-grow overflow-y-auto p-6 space-y-8">
                <nav class="flex flex-col gap-[5px] xl:space-y-0">
                            <a href="my-account.html" class="w-full text-gray-400 flex flex-row items-start gap-4 p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
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
                <a href="/logout" class="flex items-center justify-center gap-3 w-full py-4 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-xl font-bold uppercase text-[11px] tracking-widest transition-all duration-300">
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
                    <a href="index.html" title="Home - PC Garage | Custom Gaming PCs & High-End Hardware in UAE">
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

    @endsection