@extends('frontend.layouts.app')

@section('title', 'Connection Error - 404')
@section('content')
<!--404-->
    <section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
       <div class="text-white">
            <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px] justify-center">
                <main class="flex items-center justify-center px-6 relative mt-[50px] xl:mt-[0]">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-[#2A7CFF]/10 blur-[120px] rounded-full pointer-events-none"></div>
                    <div class="max-w-5xl w-full text-center relative z-10">
                        <div class="relative inline-block">
                            <h1 class="text-[150px] md:text-[220px] font-black text-white leading-none tracking-wider opacity-10">404</h1>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-medium text-white mb-6 w-full md:w-[60%] m-auto">The page you're looking for has sailed away or crashed.</h2>
                        <p class="text-gray-500 max-w-lg mx-auto mb-12 text-sm md:text-base leading-relaxed">
                            The link might be broken, or the product is no longer in stock. 
                            Try searching for your hardware or browse our luxury fleet below.
                        </p>
                        <div class="flex flex-row justify-center">
                            <a href="{{ route('home') }}" class="w-fit bg-[#2A7CFF] text-white text-center py-5 px-10 rounded-2xl font-medium uppercase text-[13px] hover:bg-white hover:text-black transition-all duration-300">
                                Back to Home page
                            </a>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
    <!--//404-->
@endsection