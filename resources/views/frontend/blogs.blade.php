@extends('frontend.layouts.app')

@section('title', 'Blogs')
@section('content')

    <!--inner banner-->
    <section class="px-[16px] md:px-[30px] lg:px-[50px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[0px] relative">
        <div class="section-title mb-[0px] relative border-t border-[#ffffff30] pt-[30px] xl:pt-[50px]">
            <!--breadcrumb-->
            <nav class="flex text-gray-400 pb-[15px] md:pb-[30px]" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium hover:text-[#3E81FF] transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
                        </a>
                    </li>

                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('products') }}" class="ml-1 text-sm font-medium hover:text-[#3E81FF] md:ml-2 transition-colors">Blogs</a>
                        </div>
                    </li>


                </ol>
            </nav>
            <!--//breadcrumb-->
            <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center xl:text-left uppercase flex flex-col md:flex-row flex-start justify-center xl:justify-start items-center md:items-start gap-[0px] md:gap-[10px] m-0 leading-[30px] md:leading-[60px]">Blogs<span class="text-[18px] text-[#2A7CFF] top-[6px] tracking-[0px] relative font-sans h-[0px]" id="total-product-count">{{ $blogs->count() }}</span></h3>
        </div>

    </section>


    <!-- Blog Listing -->
    <x-blogList :blogs="$blogs" />
    <!-- //Blog Listing -->
@endsection
