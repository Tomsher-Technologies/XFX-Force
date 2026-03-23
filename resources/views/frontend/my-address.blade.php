@extends('frontend.layouts.app')

@section('title', 'My Orders')
@section('content')
    <title>PC Garage | My Orders</title>
    <!-- //Page Title -->

    <!--my address-->
    <section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[100px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
       <div class="text-white">
            <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px]">
                
                <aside class="w-full xl:w-80 flex-shrink-0 fixed left-0 px-4 z-[100] xl:relative xl:bottom-0 xl:px-0 xl:z-0">
                    <div class="bg-[#0f161b47] backdrop-blur-[60px] xl:bg-[#1C2228] border border-[#282B34] p-[5px] rounded-[15px] xl:rounded-[20px] xl:p-6 xl:sticky xl:top-[180px] shadow-2xl">
                        
                        <div class="hidden xl:flex items-center gap-4 mb-5 pb-6 border-b border-[#282B34]">
                            <div id="userAvatar" class="flex items-center justify-center w-12 h-12 rounded-full bg-[#282B34] border border-white/5 text-white font-bold text-xl">?</div>
                            <div class="flex flex-col gap-[2px]">
                                <span id="userName" class="font-medium text-[18px] text-white">Tom Hiddleston</span>
                                <p class="text-gray-500 text-[11px] uppercase tracking-wider">Registered Member</p>
                            </div>
                        </div>

                        <nav class="flex flex-row xl:flex-col gap-[5px] xl:space-y-0">
                            
                            <a href="my-account.html" class="w-full text-gray-400 flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 text-[#898989" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="text-[10px] lg:text-[15px]">Profile</span>
                            </a>

                            <a href="my-orders.html" class="w-full text-gray-400 flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg id="Layer_1" class="text-[#898989] w-5 h-5" fill="none" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path class="fill-[#99a1af] group-hover:fill-[#ffffff] transition-all duration-600" d="m19 0h-14a5.006 5.006 0 0 0 -5 5v14a5.006 5.006 0 0 0 5 5h14a5.006 5.006 0 0 0 5-5v-14a5.006 5.006 0 0 0 -5-5zm3 5h-7v-3h4a3 3 0 0 1 3 3zm-11-3h2v5a1 1 0 0 1 -2 0zm-6 0h4v3h-7a3 3 0 0 1 3-3zm14 20h-14a3 3 0 0 1 -3-3v-12h7a3 3 0 0 0 6 0h7v12a3 3 0 0 1 -3 3zm1-3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 1 1z"/></svg>
                                <span class="text-[10px] lg:text-[15px] text-gray-400">Orders</span>
                            </a>

                            <a href="my-address.html" class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] bg-[#2A7CFF] text-white font-medium transition-all group">
                                <svg class="w-5 h-5 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-[10px] lg:text-[15px] text-white">Address</span>
                            </a>

                            <a href="wishlist.html" class="w-full text-gray-400 flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <span class="text-[10px] lg:text-[15px] text-gray-400">Wishlist</span>
                            </a>

                            <a href="accounts-change-password.html" class="w-full text-gray-400 flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24"><path fill="#9F9FA9" class="group-hover:fill-white" d="M19,8.424V7A7,7,0,0,0,5,7V8.424A5,5,0,0,0,2,13v6a5.006,5.006,0,0,0,5,5H17a5.006,5.006,0,0,0,5-5V13A5,5,0,0,0,19,8.424ZM7,7A5,5,0,0,1,17,7V8H7ZM20,19a3,3,0,0,1-3,3H7a3,3,0,0,1-3-3V13a3,3,0,0,1,3-3H17a3,3,0,0,1,3,3Z"/><path fill="#9F9FA9" class="group-hover:fill-white" d="M12,14a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,12,14Z"/></svg>
                                <span class="text-[10px] lg:text-[15px] text-gray-400">Password</span>
                            </a>

                            <div class="w-full xl:border-t xl:border-[#282B34] xl:mt-4 hidden lg:flex">
                                <a href="#" class="w-full text-gray-400  flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-red-500/10 text-red-500/70 hover:text-red-500 transition-all group  xl:mt-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    <span class="text-[10px] lg:text-[15px]">Logout</span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </aside>

                <div class="h-20 xl:hidden"></div>

                <script>
                    (function() {
                        const nameElement = document.getElementById('userName');
                        if (nameElement) {
                            const name = nameElement.textContent.trim();
                            if (name) {
                                document.getElementById('userAvatar').textContent = name.charAt(0).toUpperCase();
                            }
                        }
                    })();
                </script>

                <main class="flex-grow xl:pb-0">
                    <div>
                        <div class="flex flex-col md:flex-row justify-between items-end mb-10 pb-[30px] gap-4 border-b border-[#252B31] text-center md:text-left">
                            <div class="w-full">
                                <h2 class="text-[20px] font-medium mb-2 text-white uppercase">Saved Address</h2>
                                <p class="text-gray-500">Manage your shipping and billing locations.</p>
                            </div>
                            <div class="w-full flex justify-center md:justify-end">
                                <button onclick="toggleModal('.address-modal', true)" class="w-fit flex items-center justify-center gap-2 bg-[#2A7CFF] hover:bg-[#1a66e5] text-white px-6 py-3 rounded-xl font-medium text-[13px] uppercase cursor-pointer transition-all active:scale-95 shadow-[0_0_20px_rgba(42,124,255,0.3)]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add New Address
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[30px]">

                            <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl overflow-hidden group hover:border-[#2A7CFF]/30 transition-all duration-300">
                                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-6">
                                    <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                                        <div>
                                            <div class="flex flex-row gap-[10px] justify-center md:justify-start">
                                                <h4 class="text-white font-medium text-lg mb-1 line-clamp-1">Home</h4>
                                                <div class="flex w-fit items-center px-3 py-[5px] h-fit bg-blue-500/10 border border-blue-500/20 rounded-full">
                                                    <span class="text-blue-500 text-[11px] font-semibold uppercase leading-none">Default</span>
                                                </div>
                                            </div>
                                            <p class="text-gray-500 text-sm">Tom Hiddleston<br>123 International City, Suite 405<br>Dubai, UAE</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-row gap-[15px]">
                                        <button onclick="toggleModal('.address-modal', true)" class="flex flex-row cursor-pointer items-center gap-[10px] w-full text-center bg-[#252C33] hover:bg-[#2A7CFF] text-white text-[13px] tracking-[0.5px] uppercase font-medium px-6 py-4 rounded-xl transition-all">
                                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.06225 2.46955C7.44287 2.55401 8.58718 2.86026 9.38256 3.64338C10.1665 4.41446 10.4778 5.51582 10.5671 5.90217C9.98386 6.54506 9.27096 7.29742 8.40698 8.16096C4.82477 11.7404 3.39283 12.6795 3.33374 12.7176C3.25164 12.7705 3.15773 12.8017 3.0603 12.8094L0.636471 12.9979C0.621313 12.9996 0.606133 12.9998 0.591549 12.9998C0.434944 12.9998 0.283705 12.9368 0.172604 12.825C0.0503889 12.7019 -0.0112895 12.5318 0.00170553 12.3582L0.190182 9.93732C0.197865 9.8386 0.230155 9.74226 0.283932 9.659C0.325253 9.5957 1.26836 8.16301 4.84546 4.58869C5.69033 3.7444 6.42826 3.04514 7.06225 2.46955ZM10.3953 0.00763642C10.5012 0.0244213 11.4443 0.191172 12.1277 0.864082C12.8078 1.55404 12.9752 2.49891 12.9919 2.60529C13.0144 2.74743 12.9856 2.89353 12.9099 3.01545C12.8842 3.05747 12.5011 3.66234 11.4851 4.85725C11.2593 4.23886 10.8658 3.44182 10.2078 2.79377C9.55179 2.1479 8.74277 1.76131 8.11694 1.53889C9.33083 0.505953 9.94742 0.117453 9.99194 0.0896677C10.1119 0.0147327 10.2552 -0.0155577 10.3953 0.00763642Z" fill="white"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="w-full xl:w-fit cursor-pointer flex-1 bg-red-500/5 border border-red-500/20 text-red-500 px-4 py-4 rounded-xl hover:bg-red-500 hover:text-white transition-all text-[13px] font-medium uppercase">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl overflow-hidden group hover:border-[#2A7CFF]/30 transition-all duration-300">
                                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-6">
                                    <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                                        <div>
                                            <div class="flex flex-row gap-[10px] justify-center md:justify-start">
                                                <h4 class="text-white font-medium text-lg mb-1 line-clamp-1">Work</h4>
                                            </div>
                                            <p class="text-gray-500 text-sm">Tom Hiddleston<br>123 Marina Drive, Suite 405<br>Dubai, UAE</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-row gap-[15px]">
                                        <button onclick="toggleModal('.address-modal', true)" class="flex flex-row cursor-pointer items-center gap-[10px] w-full text-center bg-[#252C33] hover:bg-[#2A7CFF] text-white text-[13px] tracking-[0.5px] uppercase font-medium px-6 py-4 rounded-xl transition-all">
                                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.06225 2.46955C7.44287 2.55401 8.58718 2.86026 9.38256 3.64338C10.1665 4.41446 10.4778 5.51582 10.5671 5.90217C9.98386 6.54506 9.27096 7.29742 8.40698 8.16096C4.82477 11.7404 3.39283 12.6795 3.33374 12.7176C3.25164 12.7705 3.15773 12.8017 3.0603 12.8094L0.636471 12.9979C0.621313 12.9996 0.606133 12.9998 0.591549 12.9998C0.434944 12.9998 0.283705 12.9368 0.172604 12.825C0.0503889 12.7019 -0.0112895 12.5318 0.00170553 12.3582L0.190182 9.93732C0.197865 9.8386 0.230155 9.74226 0.283932 9.659C0.325253 9.5957 1.26836 8.16301 4.84546 4.58869C5.69033 3.7444 6.42826 3.04514 7.06225 2.46955ZM10.3953 0.00763642C10.5012 0.0244213 11.4443 0.191172 12.1277 0.864082C12.8078 1.55404 12.9752 2.49891 12.9919 2.60529C13.0144 2.74743 12.9856 2.89353 12.9099 3.01545C12.8842 3.05747 12.5011 3.66234 11.4851 4.85725C11.2593 4.23886 10.8658 3.44182 10.2078 2.79377C9.55179 2.1479 8.74277 1.76131 8.11694 1.53889C9.33083 0.505953 9.94742 0.117453 9.99194 0.0896677C10.1119 0.0147327 10.2552 -0.0155577 10.3953 0.00763642Z" fill="white"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button class="w-full xl:w-fit cursor-pointer flex-1 bg-red-500/5 border border-red-500/20 text-red-500 px-4 py-4 rounded-xl hover:bg-red-500 hover:text-white transition-all text-[13px] font-medium uppercase">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </main>

            </div>
        </div>
        
    </section>
    <!--//my address-->

    <!--address modal-->
    <div onclick="toggleModal('.address-modal', false)" class="address-modal fixed inset-0 z-[9999] hidden opacity-0 bg-black/80 backdrop-blur-md flex justify-center items-start p-4 overflow-y-auto transition-all duration-300 ease-in-out">
        <div onclick="event.stopPropagation()" id="addr-modal-container" class="bg-[#0B0F13] border border-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl relative mt-4 mb-4 md:mt-10 md:mb-10 transform scale-95 opacity-0 transition-all duration-300 ease-out">
            
            <button onclick="toggleModal('.address-modal', false)" type="button" class="absolute top-4 right-4 text-gray-500 hover:text-white z-50 p-2 cursor-pointer transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-6 md:p-8">
                <h4 id="modal-title" class="text-xl font-medium text-white mb-6 uppercase tracking-wider">Add New Address</h4>
                
                <div class="relative w-full h-[220px] bg-[#161B22] rounded-xl mb-6 border border-gray-800 overflow-hidden">
                    <div id="google-map-placeholder" class="w-full h-full flex items-center justify-center text-gray-600 italic">Interactive Map</div>
                    <button type="button" class="absolute cursor-pointer bottom-3 right-3 bg-[#2A7CFF] p-3 rounded-full shadow-lg hover:bg-blue-600 transition-all active:scale-95 z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" id="addr-type" placeholder="Address Type (Home / Work etc)" class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all">
                    <input type="text" id="addr-building" placeholder="Building / Villa Name" class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all">
                    <input type="text" id="addr-street" placeholder="Street / Area" class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all">
                    <input type="text" id="addr-city" placeholder="City" class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all">
                    
                    <div class="relative w-full">
                        <select id="emirate-select" class="w-full bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] appearance-none cursor-pointer transition-all font-medium">
                            <option value="Dubai" selected>Dubai</option>
                            <option value="Abu Dhabi">Abu Dhabi</option>
                            <option value="Sharjah">Sharjah</option>
                            <option value="Ajman">Ajman</option>
                            <option value="Umm Al Quwain">Umm Al Quwain</option>
                            <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                            <option value="Fujairah">Fujairah</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <input type="text" disabled placeholder="United Arab Emirates" class="bg-[#161B22] border border-gray-900/50 p-4 rounded-xl text-gray-600 disabled:cursor-not-allowed">
                </div>

                <div class="mt-6 p-4 bg-[#161B22] border border-gray-800 rounded-xl flex items-center justify-between group">
                    <div class="flex flex-col">
                        <span class="text-white font-medium text-sm">Set as Default Address</span>
                        <span class="text-gray-500 text-[11px]">Automatically select this for bookings and orders.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="addr-default" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2A7CFF]"></div>
                    </label>
                </div>

                <div class="flex flex-col md:flex-row gap-3 mt-8">
                    <button type="submit" class="flex-1 bg-[#2A7CFF] text-white font-medium uppercase py-4 rounded-xl text-[14px] cursor-pointer transition-all duration-300 hover:bg-[#1447e6] shadow-lg shadow-blue-900/20">Save Address</button>
                    <button onclick="toggleModal('.address-modal', false)" type="button" class="flex-1 bg-transparent border border-gray-800 text-gray-500 font-medium py-4 rounded-xl uppercase text-[14px] cursor-pointer hover:bg-gray-800 hover:text-white transition-all">Discard</button>
                </div>
            </div>
        </div>
    </div>
    <!--//address modal-->

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

    <!--footer-->
    <footer class="bg-[#0F161B]">
        <div class="footer-top bg-[#000000] xl:bg-transparent xl:bg-[url(/src/images/footer-top.png)] bg-no-repeat px-[16px] md:px-[30px] xl:px-[140px] pt-[50px] xl:pt-[80px] z-[1] relative">
            <div class="flex flex-col md:grid md:grid-cols-3 gap-4 w-full">
                <div class="grid col-span-1 col-start-1 xl:col-start-2">
                    <div class="footer-logo w-full flex items-center md:items-start justify-start">
                        <img src="src/images/PC-Garage-Logo-white.svg" alt="PC Garage Logo" title="PC Garage Logo" class="w-[250px] h-auto m-auto">
                    </div>
                </div>
                <div class="grid col-span-1 col-start-3 justify-items-center border-hidden md:border-l-1 md:border-[#282B34]">
                    <div class="contact-details mt-[30px] md:mt-[0px]">
                        <span class="font-normal text-white text-[12px] xl:text-[14px] mb-[10px] uppercase block text-center md:text-left">Email
                            / Call us</span>
                        <a href="tel:+971585083085" class="block text-white text-[18px] xl:text-[25px] font-medium text-center md:text-left" title="Call PC Garage UAE Support">+971 58 508 3085</a>
                        <a href="mailto:example@pcgarage.com" title="Email PC Garage Support" aria-label="Email PC Garage Support" class="block text-white text-[18px] xl:text-[25px] font-medium text-center md:text-left">example@pcgarage.com</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-mid bg-black pt-[50px] lg:pt-[80px] px-[16px] md:px-[30px] xl:px-[140px] text-center md:text-left">
            <div class="footer-links border-y-1 border-[#282B34] py-[0px] md:py-[50px]">
                <div class="flex flex-col md:grid md:grid-cols-2 lg:grid-cols-5 gap-[0px] md:gap-[50px]">
                    <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                        <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">useful links</h6>
                        <ul>
                            <li><a href="#" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Home</a></li>
                            <li><a href="#" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">About</a></li>
                            <li><a href="#" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Build your PC</a></li>
                            <li><a href="#" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Pre-Built PC</a></li>
                            <li><a href="#" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Refurbished PC</a></li>
                            <li><a href="#" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Deals</a></li>
                            <li><a href="#" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Contact</a></li>
                        </ul>
                    </div>
                    <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                        <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">Shop</h6>
                        <ul>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">RDY
                                    Prebuilt Computers</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">PC
                                    Lab</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Customize
                                    Gaming PC</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Gaming
                                    PCs</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Gaming
                                    Laptops</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Gear
                                    Store</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Gaming
                                    Accessories</a></li>
                        </ul>
                    </div>
                    <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                        <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">my accounts</h6>
                        <ul>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Store
                                    Location</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Order
                                    History</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Wish
                                    List</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Newsletter</a>
                            </li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">Specials</a>
                            </li>
                        </ul>
                    </div>
                    <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                        <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">Top Selling Products
                        </h6>
                        <ul class="border-list">
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px] block w-full py-[10px] border-b border-[#282B34]">Asus
                                    ROG Strix XG49VQ 49”</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px] block w-full py-[10px] border-b border-[#282B34]">ViewSonic
                                    Elite XG341C-2K 34"</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px] block w-full py-[10px] border-b border-[#282B34]">XFX
                                    Stinger Gaming PC-RYZEN 7</a></li>
                            <li><a href="#"
                                    class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px] block w-full py-[10px] border-b border-[#282B34]">XFX
                                    Phantom Gaming PC– Intel I9</a></li>
                        </ul>
                    </div>
                    <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                        <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">Address</h6>
                        <p class="text-[#898989] text-[15px] leading-[30px]">PC Garage, Office 107, Computer Plaza Al
                            Mankhool, Dubai, United Arab Emirates</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom bg-black py-[30px] px-[16px] md:px-[30px] xl:px-[140px]">
            <div class="flex flex-col md:grid grid-cols-3 gap-[30px] md:gap-[0px]">
                <div class="flex align-center justify-center md:justify-start items-center">
                    <p class="text-white text-[15px] font-medium text-center md:text-left">© 2026 PC Garage, All rights
                        reserved</p>
                </div>
                <div class="flex align-center justify-center items-center">
                    <div class="flex flex-row gap-[30px] align-center justify-center">
                        <a href="#" target="_blank"><img src="src/images/instagram.svg" alt="Instagram"
                                class="w-[20px] h-[20px]"></a>
                        <a href="#" target="_blank"><img src="src/images/linkedin.svg" alt="Instagram"
                                class="w-[20px] h-[20px]"></a>
                        <a href="#" target="_blank"><img src="src/images/facebook.svg" alt="Instagram"
                                class="w-[20px] h-[20px]"></a>
                    </div>
                </div>
                <div class="flex align-center justify-center items-center">
                    <img src="src/images/payment-methods.png" alt="Payment Methods"
                        class="w-full md:w-[280px] h-auto object-contain object-center block ml-auto">
                </div>
            </div>
        </div>
    </footer>
    <!--//footer-->

    <!--script-->
    <script src="src/js/jquery-3.6.1.min.js"></script>
    <script src="src/js/swiper.min.js"></script>
    <script src="src/js/main-slider.js"></script>
    <script src="src/js/theme-script.js"></script>
    <script src="src/js/glightbox.min.js"></script>
    <script src="src/js/elements@1.js" type="module"></script>
    <script>
        $(window).scroll(function () {
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

        $(document).ready(function () {
            $('.view-btn').on('click', function () {
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
                item.addEventListener('click', function () {
                    this.classList.toggle('border-[#3E81FF]');
                    this.classList.toggle('bg-[#282B34]');
                    const img = this.querySelector('img');
                    img.classList.toggle('grayscale-0');
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
        $(window).scroll(function () {
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

        
        // 1. Define the function globally immediately
        window.toggleModal = function () {
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
        document.addEventListener('click', function (e) {
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
                setTimeout(() => { sOverlay.classList.add('hidden'); document.body.style.overflow = 'auto'; }, 300);
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
                setTimeout(() => { wOverlay.classList.add('hidden'); document.body.style.overflow = 'auto'; }, 300);
            }
        }

        // --- OUTSIDE CLICK CLOSING ---
        window.addEventListener('click', (e) => {
            if (e.target === sOverlay) toggleSpecModal();
            if (e.target === wOverlay) toggleWarrantyModal();
        });


        window.selectWarranty = function (selectedElement) {
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
        if(confirm("Remove this item?")) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
            console.log("Product removed from tracking.");
        }
        return;
    }

    let newVal = currentVal + change;
    if (newVal < 1) newVal = 1;
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


document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus(); // Move to next
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
            inputs[index - 1].focus(); // Move back on backspace
        }
    });
});



    </script>
    <!--//script-->


    <!--address modal js-->
    <script>
        function toggleModal(selector, isOpen) {
    const modal = document.querySelector(selector);
    const container = modal.querySelector('#addr-modal-container');

    if (isOpen) {
        // 1. Show the display first
        modal.classList.remove('hidden');
        
        // 2. Trigger animations in the next frame
        requestAnimationFrame(() => {
            modal.classList.add('opacity-100');
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        });
        
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    } else {
        // 1. Reverse animations
        modal.classList.remove('opacity-100');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        
        // 2. Wait for transition to finish before hiding
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scroll
        }, 300); // Must match your duration-300
    }
}

// Close on Escape Key
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const modal = document.querySelector('.address-modal');
        if (!modal.classList.contains('hidden')) {
            toggleModal('.address-modal', false);
        }
    }
});
    </script>
    <!--//address modal js-->

</body>

</html>