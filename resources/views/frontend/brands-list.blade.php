@extends('frontend.layouts.app')

@section('title', 'Brands')
@section('content')

    <!--inner banner-->
    <section class="px-[16px] md:px-[140px] pt-[80px] md:pt-[150px] pb-[0px] relative">
        <div class="section-title mb-[0px] relative border-t-1 border-[#282B34] pt-[50px]">
            <h3 class="w-full text-[40px] md:text-[50px] text-white capitalize font-bold text-center uppercase flex flex-col md:flex-row flex-start justify-center md:justify-start items-center md:items-start gap-[0px] md:gap-[10px] m-0 leading-[30px] md:leading-[60px]">Brands</h3>
        </div>
    </section>
    <!--//categories-->

    <!--brands-->
    <section class="bg-[#0F161B] max-w-6xl mx-auto px-4 py-[50px] md:py-[100px] flex flex-col gap-[30px] md:gap-[50px]">

        <div class="w-full">
            <div class="flex flex-col md:flex-row items-stretch w-full bg-[#161B22] border border-gray-800 rounded-xl overflow-hidden shadow-2xl">
                <!-- <div class="relative flex items-center min-w-[300px] border-b md:border-b-0 md:border-r border-gray-800 px-4 py-3 md:py-0">
                    <span class="text-[10px] font-medium text-gray-500 uppercase mr-2 whitespace-nowrap">Category:</span>
                    <select id="category-select" class="w-full bg-transparent text-white text-sm outline-none appearance-none cursor-pointer pr-8 py-3">
                        <option value="all" class="text-black">All Brands</option>
                        <option value="gpu" class="text-black">Graphics Cards</option>
                        <option value="cpu" class="text-black">Processors</option>
                        <option value="ram" class="text-black">Memory</option>
                        <option value="storage" class="text-black">Storage</option>
                    </select>
                    <div class="pointer-events-none absolute right-4 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div> -->

                <div class="relative flex-grow flex items-center px-4">
                    <span class="text-gray-500 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" id="brand-search" placeholder="Search your brand here" class="w-full bg-transparent text-white text-sm py-4 outline-none placeholder:text-gray-600">
                </div>
            </div>
        </div>

        <div id="brand-grid" class="grid grid-cols-2 md:grid-cols-4 divide-x-1 divide-[#1E2529]">
            @foreach($brands as $brand)
                <div class="brand-item w-full cursor-pointer group p-4 transition-all flex items-center min-h-[200px] border border-[#1E2529] hover:bg-[#1E2529]/30" 
                data-name="{{ $brand->name }}'">
                    <img src="{{ $brand->logo ? Storage::url($brand->logo) : asset('assets/img/placeholder.jpg') }}" class="m-auto opacity-[0.5] group-hover:opacity-[1] transition-all duration-[600ms] w-[55%]" title="{{ $brand->name }}" alt="{{ $brand->name }}">
                </div>
            @endforeach
            
        </div>
    </section>
    <!--//brands-->
@endsection