@extends('frontend.layouts.app')

@section('title', 'My Orders')
@section('content')

<!--my orders-->
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

                            <a href="{{ route('orders.index') }}" class="w-full flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] bg-[#2A7CFF] text-white font-medium transition-all group">
                                <svg id="Layer_1" class="text-white w-5 h-5" fill="none" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path class="fill-[#ffffff] group-hover:fill-[#ffffff] transition-all duration-600" d="m19 0h-14a5.006 5.006 0 0 0 -5 5v14a5.006 5.006 0 0 0 5 5h14a5.006 5.006 0 0 0 5-5v-14a5.006 5.006 0 0 0 -5-5zm3 5h-7v-3h4a3 3 0 0 1 3 3zm-11-3h2v5a1 1 0 0 1 -2 0zm-6 0h4v3h-7a3 3 0 0 1 3-3zm14 20h-14a3 3 0 0 1 -3-3v-12h7a3 3 0 0 0 6 0h7v12a3 3 0 0 1 -3 3zm1-3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 1 1z"/></svg>
                                <span class="text-[10px] lg:text-[15px] text-white">Orders</span>
                            </a>

                            <a href="{{ route('my-address') }}" class="w-full text-gray-400 flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
                                <svg class="w-5 h-5 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-[10px] lg:text-[15px] text-gray-400">Address</span>
                            </a>

                            <a href="{{ route('wishlist') }}" class="w-full text-gray-400 flex flex-col xl:flex-row items-center xl:items-start gap-1 xl:gap-4 p-[12px] xl:p-3 rounded-[10px] hover:bg-[#252C33] text-[#898989] hover:text-white transition-all group">
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
                                <h2 class="text-[20px] font-medium mb-2 text-white uppercase">Order History</h2>
                                <p class="text-gray-500">Track your current builds and view past purchases.</p>
                            </div>
                            <div class="w-full relative group flex flex-row justify-end">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-[#2A7CFF] transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" placeholder="Search orders..." class="w-full bg-[#1C2228] border border-[#282B34] rounded-xl py-3 pl-12 pr-5 text-sm text-white focus:border-[#2A7CFF] focus:bg-[#1c2228]/80 outline-none transition-all placeholder:text-gray-600" id="orderSearch" >
                            </div>
                        </div>
                        <div class="space-y-6">
                            @foreach ($orders as $order)
                            <div class="order-item  bg-[#1C2228] border border-[#282B34] rounded-2xl overflow-hidden group hover:border-[#2A7CFF]/30 transition-all duration-300">
                                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-6">
                                    <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                                        <div>
                                            <span class="order-code text-[#2A7CFF] text-[15px] font-medium uppercase block mb-1"># {{ $order->code }}</span>
                                            <p class="order-date text-gray-500 text-sm">Ordered on {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center md:items-start gap-2">
                                        @php
                                            $badgeColor = 'blue-500'; //pending

                                            if($order->delivery_status == 'delivered' || $order->delivery_status == 'confirmed'){
                                                $badgeColor = 'green-500';
                                            } elseif ($order->delivery_status == 'cancelled') {
                                                $badgeColor = 'red-500';
                                            } elseif ($order->delivery_status == 'on_the_way' || $order->delivery_status == 'picked_up') {
                                                $badgeColor = 'yellow-500';
                                            }

                                        @endphp
                                        <div class="flex items-center gap-2 px-3 py-1 bg-{{ $badgeColor }}/10 border border-blue-500/20 rounded-full">
                                            <span class="w-2 h-2 bg-{{ $badgeColor }} rounded-full animate-pulse"></span>
                                            <span class="order-status text-{{ $badgeColor }} text-[11px] font-medium uppercase">{{ trans('messages.' . $order->delivery_status) }}</span>
                                        </div>

                                        <div class="price w-full flex flex-row items-center gap-[10px]">
                                            <h5 class="price flex flex-row text-white text-left text-[20px] m-[0] font-medium align-center items-center gap-[10px]">
                                                <svg class="w-[20px]" width="18" height="15" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff"></path>
                                            </svg>   
                                            {{format_price($order->grand_total)}}</h5>
                                        </div>
                                    </div>

                                    <div class="w-full md:w-fit gap-[15px]">
                                        <a href="{{ route('orders.show', encrypt($order->id)) }}" class="block w-full text-center bg-[#252C33] hover:bg-[#2A7CFF] text-white text-[13px] tracking-[0.5px] uppercase font-medium px-8 py-4 rounded-xl transition-all">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </main>

            </div>
        </div>
        
    </section>
    <!--//my orders-->

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('orderSearch');
    const orders = document.querySelectorAll('.order-item'); // add a class to each order div

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();

        orders.forEach(order => {
            const code = order.querySelector('.order-code').textContent.toLowerCase();
            const status = order.querySelector('.order-status').textContent.toLowerCase();
            const date = order.querySelector('.order-date').textContent.toLowerCase();

            if(code.includes(query) || status.includes(query) || date.includes(query)) {
                order.style.display = '';
            } else {
                order.style.display = 'none';
            }
        });
    });
});
</script>
    @endsection
   