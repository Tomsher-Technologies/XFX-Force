<aside class="w-full xl:w-80 flex-shrink-0 fixed left-0 px-4 z-[100] xl:relative xl:bottom-0 xl:px-0 xl:z-0">
    <div class="bg-[#0f161b47] backdrop-blur-[60px] xl:bg-[#1C2228] border border-[#282B34] p-[5px] rounded-[15px] xl:rounded-[20px] xl:p-6 xl:sticky xl:top-[180px] shadow-2xl">
        
        <div class="hidden xl:flex items-center gap-4 mb-5 pb-6 border-b border-[#282B34]">
            <div id="userAvatarSidebar" class="flex items-center justify-center w-12 h-12 rounded-full bg-[#282B34] border border-white/5 text-white font-bold text-xl">?</div>
            <div class="flex flex-col gap-[2px]">
                <span id="userNameSidebar" class="font-medium text-[18px] text-white">{{ auth('frontend')->user()->name ?? '' }}</span>
                <p class="text-gray-500 text-[11px] uppercase tracking-wider">{{ auth('frontend')->user()->email ?? '' }}</p>
            </div>
        </div>

        <nav class="flex flex-row xl:flex-col gap-[5px] xl:space-y-0">
            
            <a href="{{ route('account') }}" class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px]  transition-all group  {{ request()->routeIs('account*') ? 'bg-[#2A7CFF] text-white font-medium' : 'hover:bg-[#252C33] text-[#898989] text-gray-400 hover:text-white' }} ">
                <svg class="w-5 h-5 {{ request()->routeIs('account*') ? 'text-[#ffffff]' : 'text-[#898989]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span class="text-[10px] lg:text-[15px]">Profile</span>
            </a>

            <a href="my-orders.html" class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px]  transition-all group {{ request()->routeIs('order*') ? 'bg-[#2A7CFF] text-white font-medium' : 'hover:bg-[#252C33] text-[#898989] text-gray-400 hover:text-white' }} ">
                <svg id="Layer_1" class=" {{ request()->routeIs('order*') ? 'text-[#ffffff]' : 'text-[#898989]' }} w-5 h-5 group-hover:text-white" fill="none" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path class="fill-[#99a1af] group-hover:fill-[#ffffff] transition-all duration-600" d="m19 0h-14a5.006 5.006 0 0 0 -5 5v14a5.006 5.006 0 0 0 5 5h14a5.006 5.006 0 0 0 5-5v-14a5.006 5.006 0 0 0 -5-5zm3 5h-7v-3h4a3 3 0 0 1 3 3zm-11-3h2v5a1 1 0 0 1 -2 0zm-6 0h4v3h-7a3 3 0 0 1 3-3zm14 20h-14a3 3 0 0 1 -3-3v-12h7a3 3 0 0 0 6 0h7v12a3 3 0 0 1 -3 3zm1-3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 1 1z"/></svg>
                <span class="text-[10px] lg:text-[15px] ">Orders</span>
            </a>

            <a href="{{ route('my-address') }}" class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px]  transition-all group  {{ request()->routeIs('my-address*') ? 'bg-[#2A7CFF] text-white font-medium' : 'hover:bg-[#252C33] text-[#898989] text-gray-400 hover:text-white' }} ">
                <svg class="w-5 h-5 group-hover:text-white {{ request()->routeIs('my-address*') ? 'text-[#ffffff]' : 'text-[#898989]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span class="text-[10px] lg:text-[15px] ">Address</span>
            </a>

            <a href="{{ route('wishlist') }}" class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px]  transition-all group {{ request()->routeIs('wishlist*') ? 'bg-[#2A7CFF] text-white font-medium' : 'hover:bg-[#252C33] text-[#898989] text-gray-400 hover:text-white' }} ">
                <svg class="w-5 h-5 group-hover:text-white  {{ request()->routeIs('wishlist*') ? 'text-[#ffffff]' : 'text-[#898989]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                <span class="text-[10px] lg:text-[15px] ">Wishlist</span>
            </a>

            <a href="{{ route('update-password') }}" class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px]  transition-all group  {{ request()->routeIs('update-password*') ? 'bg-[#2A7CFF] text-white font-medium' : 'hover:bg-[#252C33] text-[#898989] text-gray-400 hover:text-white' }} ">
                <svg class="w-5 h-5 group-hover:text-white {{ request()->routeIs('update-password*') ? 'text-[#ffffff]' : 'text-[#898989]' }}" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('update-password*') ? '#fff' : '#898989'}} " class="group-hover:fill-white" d="M19,8.424V7A7,7,0,0,0,5,7V8.424A5,5,0,0,0,2,13v6a5.006,5.006,0,0,0,5,5H17a5.006,5.006,0,0,0,5-5V13A5,5,0,0,0,19,8.424ZM7,7A5,5,0,0,1,17,7V8H7ZM20,19a3,3,0,0,1-3,3H7a3,3,0,0,1-3-3V13a3,3,0,0,1,3-3H17a3,3,0,0,1,3,3Z"/><path fill="{{ request()->routeIs('update-password*') ? '#fff' : '#898989' }}" class="group-hover:fill-white" d="M12,14a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,12,14Z"/></svg>
                <span class="text-[10px] lg:text-[15px] ">Password</span>
            </a>

            <div class="w-full xl:border-t xl:border-[#282B34] xl:mt-4 hidden lg:flex">
                <a href="{{ route('logout')  }}" class="w-full text-gray-400  flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-red-500/10 text-red-500/70 hover:text-red-500 transition-all group  xl:mt-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="text-[10px] lg:text-[15px]">Logout</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

<div class="h-20 xl:hidden"></div>
