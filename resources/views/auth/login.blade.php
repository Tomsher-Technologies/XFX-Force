@extends('frontend.layouts.app')

@section('title', 'Login - '.env('APP_NAME'))

@section('content')

<section class="bg-[#0F161B] max-w-6xl mx-auto py-[50px] pt-[100px] md:pt-[200px] md:pb[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-[#1C2228] rounded-[20px] border border-white/5 p-8 md:p-10 shadow-2xl">
            <div class="text-center mb-10">
                <h2 class="text-white text-[20px] font-semibold mb-1 uppercase">Login</h2>
                <p class="text-gray-400 text-sm">Log in to manage your orders and hardware</p>
            </div>

            <form action="{{ url('login') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($checkout))
                <input type="hidden" name="checkout" value="true">
                @endif
                <div>
                    <label class="text-gray-500 text-[12px] font-medium uppercase mb-1 block tracking-wider">
                        Email Address <span class="text-red-600">*</span>
                    </label>
                    <input type="email" placeholder="example@gmail.com" id="email" 
                        class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all placeholder:text-gray-700" name="email" value="{{ old('email') }}">
                    @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ show: false }" class="space-y-2">
                    <div class="flex justify-between">
                        <label class="text-gray-500 text-[12px] font-medium uppercase block tracking-wider">Password  <span class="text-red-600">*</span></label>
                        <a href="{{ route('forgot-password') }}" class="text-[#2A7CFF] text-[12px] font-medium uppercase hover:underline">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <!-- Password Input -->
                        <input :type="show ? 'text' : 'password'" placeholder="••••••••" id="password" name="password" autocomplete="new-password" class="w-full bg-[#0B0F13] border border-white/5 p-4 pr-12 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all" >
                
                        <!-- Toggle Icon Inside Input on the Right -->
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-5 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <!-- Show Icon -->
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                
                            <!-- Hide Icon -->
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.964 9.964 0 012.184-3.293m1.43-1.43A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.969 9.969 0 01-4.138 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    
                    @error('password')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="cursor-pointer transition-all duration-[600ms] w-full bg-[#2A7CFF] py-4 rounded-xl font-medium text-white text-[14px] uppercase hover:bg-[#1A6BFF] transition-all shadow-lg shadow-[#2A7CFF]/20 active:scale-[0.98]">
                    Log In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-500 text-sm">You don't have any account yet?  
                    <a href="{{ route('register') }}" class="text-[#2A7CFF] font-medium hover:underline block pt-[15px]">Create Account</a>
                </p>
            </div>
        </div>
    </div>
</section>



@endsection
