@extends('frontend.layouts.app')

@section('title', 'Return Policy')
@section('content')
    <section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
        <div class="text-white">
            <div
                class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px]">

                <main class="flex-grow">
                    <div>
                        <div class="flex flex-col items-center xl:items-left justify-center xl:justify-left pt-12 xl:pt-0 border-b border-[#252B31] mb-0 lg:mb-12 text-center lg:text-left">
                           
                            <h1 class="text-[40px] font-medium text-white uppercase tracking-wider mb-1">
                                {{ $page_content['title'] ?? 'Return Policy' }}
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