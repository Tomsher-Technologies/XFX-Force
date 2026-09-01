@extends('frontend.layouts.app')

@section('title', $page_content['title'] ?? 'Policy')
@section('content')

    <section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
        <div class="text-white">
            <div
                class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px]">

                <main class="flex-grow">
                    <div>
                        <div class="flex flex-col items-center xl:items-left justify-center xl:justify-left pt-12 xl:pt-0 border-b border-[#252B31] mb-0 lg:mb-12 text-center lg:text-left">
                            <!--breadcrumb-->
                            <div>
                                <nav class="flex text-gray-400 pb-[15px] md:pb-[30px] w-full"
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
                                                <span class="ml-1 text-sm font-medium text-white md:ml-2">{{ $page_content['title'] ?? 'Policy' }}</span>
                                            </div>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <!--//breadcrumb-->
                            <h1 class="text-[40px] font-medium text-white uppercase tracking-wider mb-1">
                                {{ $page_content['title'] ?? 'Policy' }}
                            </h1>

                        </div>

                        <div class="flex flex-col lg:flex-row gap-12 justify-center">

                            <div class="lg:w-3/4 space-y-16 text-gray-400 leading-relaxed">

                                <div class="mb-4 mt-2 leading-[30px] text-[15px] text-justify lg:text-left policy-page">
                                    {!! $page_content['description'] ?? 'No content available.' !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </main>

            </div>
        </div>

    </section>
@endsection
