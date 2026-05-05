@extends('frontend.layouts.app')

@section('title', 'Order Failed')
@section('content')
   
   
<!--order fail-->
    <section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
       <div class="text-white">
            <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px] justify-center">
                
                <main class="mt-[50px] xl:mt-[0]">
                    <div class="max-w-2xl mx-auto">
                        
                        <div class="text-center mb-12">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-500/10 rounded-full mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" class="w-10" data-name="Layer 1" viewBox="0 0 24 24">
                                    <path fill="red" d="m22.242,5.272l-3.515-3.515c-1.133-1.133-2.64-1.757-4.242-1.757h-4.971c-1.603,0-3.109.624-4.242,1.757l-3.515,3.514c-1.134,1.133-1.758,2.64-1.758,4.243v4.971c0,1.603.624,3.11,1.758,4.243l3.515,3.515c1.133,1.133,2.64,1.757,4.242,1.757h4.971c1.603,0,3.109-.624,4.242-1.757l3.515-3.514c1.134-1.133,1.758-2.64,1.758-4.243v-4.971c0-1.603-.624-3.11-1.758-4.243Zm-.242,9.213c0,1.069-.416,2.073-1.172,2.829l-3.515,3.515c-.756.755-1.76,1.171-2.828,1.171h-4.971c-1.068,0-2.072-.416-2.828-1.171l-3.515-3.515c-.756-.755-1.172-1.759-1.172-2.828v-4.971c0-1.069.416-2.073,1.172-2.829l3.515-3.515c.756-.755,1.76-1.171,2.828-1.171h4.971c1.068,0,2.072.416,2.828,1.171l3.515,3.515c.756.755,1.172,1.759,1.172,2.828v4.971Zm-11-.485V6c0-.552.447-1,1-1s1,.448,1,1v8c0,.552-.447,1-1,1s-1-.448-1-1Zm2.5,3.5c0,.828-.672,1.5-1.5,1.5s-1.5-.672-1.5-1.5.672-1.5,1.5-1.5,1.5.672,1.5,1.5Z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-medium text-white uppercase mb-4">Order Failed</h2>
                            <p class="text-gray-400">Something went wrong with your transaction. Don't worry, no funds were deducted from your account.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('checkout') }}" class="flex-1 bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] text-white text-center px-5 py-3 rounded-xl font-medium uppercase text-[13px] hover:bg-white hover:text-black transition-all duration-300">
                                Try Again
                            </a>
                            <a href="contact.html" class="flex-1 border border-white/10 text-white text-center px-5 py-3 rounded-xl font-medium uppercase text-[13px] hover:bg-white/5 transition-all">
                                Contact Support
                            </a>
                        </div>

                        <div class="mt-12 text-center">
                            <a href="{{ route('products') }}" class="w-full uppercase justify-center text-gray-500 hover:text-[#2A7CFF] transition-all flex items-center gap-2 text-sm font-medium mb-8 xl:mb-4 group">
                                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                Return to Shop
                            </a>
                        </div>
                    </div>
                </main>

            </div>
        </div>
        
    </section>
    <!--//order fail-->
    @endsection