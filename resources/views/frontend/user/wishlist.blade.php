@extends('frontend.layouts.app')

@section('title', 'My Account')

@section('content')

<section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[100px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="text-white">
        <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px]">
            
            @include('frontend.layouts.sidebar')

            <main class="flex-grow xl:pb-0">
                <div>
                    <div class="flex flex-col md:flex-row justify-between items-end mb-10 pb-[30px] gap-4 border-b border-[#252B31] text-center md:text-left">
                        <div class="w-full">
                            <h2 class="text-[20px] font-medium mb-1 text-white uppercase">My Wishlist</h2>
                            <p class="text-gray-500">Manage your shipping and billing locations.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-[5px] md:gap-[15px]">

                        {{-- <div class="product-card group w-full relative border-hidden rounded-[20px] overflow-hidden bg-[#1E2225] flex flex-col items-start justify-start transition-all duration-500 hover:translate-y-[-5px] hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)]">
                            <button class="wishlist-toggle absolute top-[20px] right-[20px] z-[10] w-[35px] h-[35px] md:w-[40px] md:h-[40px] bg-black/20 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center text-red-500 transition-all duration-300 hover:bg-transparent hover:text-red-500 cursor-pointer group/heart">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-active/heart:scale-125" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            <a href="shop-single.html" class="product-img h-[180px] w-full relative z-[1] bg-[#0B0F13] bg-gradient-to-t from-[#0B0F13] to-[#1E2225] overflow-hidden">
                                <img src="src/images/product-01.webp" class="absolute object-cover object-center w-full h-full transition-transform duration-700 group-hover:scale-110" alt="Product Name">
                                <badge class="absolute top-[20px] left-[20px] bg-[#2A7CFF] text-white text-[10px] md:text-[12px] font-medium px-[15px] py-[5px] rounded-full capitalize">new arrival</badge>
                            </a>
                            <div class="product-content p-[15px] md:p-[20px] flex flex-col gap-[10px] md:gap-[20px] z-[1] w-full">
                                <h4 class="text-white text-[13px] md:text-[18px] leading-[20px] md:leading-[25px] font-medium line-clamp-2 h-[40px] md:h-[50px]">Obsidian Digital V3: Pro-Workstation with Real-Time Telemetry</h4>
                                <div class="flex flex-col gap-4">
                                    <h5 class="price flex flex-row text-[#2A7CFF] text-[13px] md:text-[18px] leading-[20px] m-[0] font-bold align-center items-center gap-[10px]">
                                        <img src="src/images/aed.svg" class="w-[15px] h-[15px]" alt="AED">899.00 
                                        <span class="text-[#898989] font-medium line-through text-[11px] md:text-[14px]">950.00</span>
                                    </h5>
                                    <button class="cursor-pointer w-full text-center text-white uppercase text-[12px] md:text-[14px] font-medium px-[20px] py-[12px] md:py-[15px] rounded-[15px] border border-[#282B34] bg-transparent group-hover:bg-[#2A7CFF] group-hover:border-[#2A7CFF] transition-all duration-300 hidden md:block">
                                        Buy now
                                    </button>
                                </div>
                            </div>
                        </div> --}}
                       
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script>
        
    </script>
@endsection