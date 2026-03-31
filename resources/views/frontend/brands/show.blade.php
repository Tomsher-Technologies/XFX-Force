@extends('frontend.layouts.app')

@section('title', 'About Us')
@section('content')
    <!--about banner intro-->
    <section
        class="min-h-screen relative justify-between w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px] border-b-1 border-gray-800 xl:border-none">

        <img src="{{ uploaded_asset($page_content['image1'] ?? '') }}" alt="" title=""
            class="absolute left-0 top-0 w-full h-full object-cover object-center z-[-1]">

        <!--breadcrumb-->
        <div>
            <nav class="flex text-gray-400 py-[15px] md:py-[30px] border-t-1 border-[#ffffff30] w-full"
                aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center text-sm font-medium hover:text-[#3E81FF] transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>Home
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-white md:ml-2">About us</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <!--//breadcrumb-->

        <div class="text-white w-full xl:w-6xl mx-auto flex flex-col justify-between h-full">
            <div class="flex flex-col xl:flex-row justify-between gap-[20px] xl:gap-[100px]">
                <div class="w-full">
                    <h1 class="about-title text-[25px] xl:text-[40px] leading-[35px] xl:leading-[55px] text-center xl:text-left">
                        {{ $   ['title'] ?? 'About Us' }}</h1>
                </div>
                <div class="w-full">
                    <p
                        class="text-[#ffffff80] text-[15px] xl:text-[20px] leading-[30px] xl:leading-[40px] text-center xl:text-left">
                        
                        {{ $page_content['description'] ?? '' }}</p>
                </div>
            </div>
        </div>

    </section>
    <!--//about banner intro-->

    <!--about-->
    <section
        class="h-full xl:min-h-screen relative flex flex-col bg-black justify-start xl:justify-between w-full px-[16px] md:px-[30px] xl:px-[250px] py-[40px] xl:py-[100px] border-b-1 border-gray-800 xl:border-none">

        <img src="{{ uploaded_asset($page_content['image2'] ?? '') }}" alt="" title=""
            class="hidden xl:block absolute left-0 top-0 w-full h-full object-contain object-center z-[0]">

        <div class="w-full xl:w-1/2 text-left relative z-1 mb-[30px] xl:mb-0">
            <h2
                class="about-title text-[25px] xl:text-[40px] leading-[35px] xl:leading-[55px] text-white text-center xl:text-left">
                {{ $page_content['mid_title'] ?? '' }}
            </h2>
        </div>

        <div class="w-full xl:w-1/2 self-end text-center xl:text-left px-0 xl:px-[50px] relative z-1">
            <h3
                class="about-title text-[18px] xl:text-[25px] leading-[30px] xl:leading-[35px] text-white mb-4 w-full xl:w-sm">
                {{ $page_content['mid_sub_title'] ?? '' }}
            </h3>
            <p
                class="text-[#ffffff90] text-[15px] xl:text-[18px] leading-[30px] xl:leading-[35px] full xl:max-w-2xl xl:ml-auto font-medium">
                {{ $page_content['mid_description'] ?? '' }}
            </p>
        </div>

    </section>
    <!--//about-->

    <!--why choose us-->
    <section
        class="h-full xl:min-h-screen relative flex flex-col bg-gradient-to-b from-[#000000] to-[#0f161b] justify-between w-full px-[16px] md:px-[30px] xl:px-[250px] py-[40px] xl:py-[100px] gap-[30px] xl:gap-[50px]">
        <div
            class="text-white w-full xl:w-2xl mx-auto flex flex-col justify-between h-full gap-[20px] md:gap-[30px] text-center">
            <div class="w-full">
                <h1 class="about-title text-[25px] xl:text-[40px] leading-[35px] xl:leading-[55px] text-center">
                    
                    {{ $page_content['last_title'] ?? '' }}</h1>
            </div>
            <div class="w-full">
                <p class="text-[#ffffff80] text-[15px] xl:text-[20px] leading-[30px] xl:leading-[40px]">Where raw power
                    {{ $page_content['last_description'] ?? '' }}
                </p>
            </div>
        </div>
        <img src="{{ uploaded_asset($page_content['image3'] ?? '') }}" alt="" title="" class="m-auto w-full">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-[30px] xl:gap-[80px]">
            <div class="border-t-2 border-white pt-[30px]">
                <h3 class="text-white text-[20px] text-center xl:text-left mb-[15px] about-title">
                    {{ $page_content['section1_title'] ?? '' }}
                </h3>
                <p
                    class="text-[#ffffff90] text-[15px] xl:text-[18px] leading-[30px] xl:leading-[35px] xl:ml-auto font-medium text-center xl:text-left">
                    {{ $page_content['section1_description'] ?? '' }}
                </p>
            </div>
            <div class="border-t-2 border-white pt-[30px]">
                <h3 class="text-white text-[20px] text-center xl:text-left mb-[15px] about-title">
                    {{ $page_content['section2_title'] ?? '' }}
                </h3>
                <p
                    class="text-[#ffffff90] text-[15px] xl:text-[18px] leading-[30px] xl:leading-[35px] xl:ml-auto font-medium text-center xl:text-left">
                    {{ $page_content['section2_description'] ?? '' }}
                </p>
            </div>
            <div class="border-t-2 border-white pt-[30px]">
                <h3 class="text-white text-[20px] text-center xl:text-left mb-[15px] about-title">
                    {{ $page_content['section3_title'] ?? '' }}
                </h3>
                <p
                    class="text-[#ffffff90] text-[15px] xl:text-[18px] leading-[30px] xl:leading-[35px] xl:ml-auto font-medium text-center xl:text-left">
                    {{ $page_content['section3_description'] ?? '' }}
                </p>
            </div>

        </div>
    </section>
    <!--//why choose us-->
@endsection
