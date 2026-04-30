@extends('frontend.layouts.app')
@section('title', 'Register - '.env('APP_NAME'))
@section('content')

<section class="bg-[#0F161B] max-w-6xl mx-auto py-[50px] pt-[100px] xl:pt-[200px] md:pb[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-[#1C2228] rounded-[30px] border border-white/5 p-8 md:p-10 shadow-2xl">
        
            <div class="text-center mb-10">
                <h2 class="text-white text-[20px] font-semibold mb-1 uppercase">Create Account</h2>
                <p class="text-gray-400 text-sm">Create an account for exclusive hardware deals</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5" autocomplete="off">
                @csrf
                <div>
                    <label class="text-gray-500 text-[12px] font-medium uppercase mb-1 block tracking-wider">Full Name <span class="text-red-600">*</span></label>
                    <input type="text" placeholder="Enter your name"  id="name" name="name" value="{{ old('name') }}"
                        class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all placeholder:text-gray-700" pattern="[A-Za-z\s]+" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    
                    @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-gray-500 text-[12px] font-medium uppercase mb-1 block tracking-wider">Email Address <span class="text-red-600">*</span></label>
                    <input type="email" placeholder="example@gmail.com"  id="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all placeholder:text-gray-700">
                    
                    @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-gray-500 text-[12px] font-medium uppercase mb-1 block tracking-wider">Phone Number <span class="text-red-600">*</span></label>
                    <input type="tel" name="phone" id="phone" autocomplete="off" class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all placeholder:text-gray-700"  value="{{ old('phone') }}" placeholder="971xxxxxxxxxx">
                    @error('phone') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>

                
                <div x-data="{ show: false }" class="mb-4">
                    <label for="password" class="text-gray-500 text-[12px] font-medium uppercase block tracking-wider mb-1">Password <span class="text-red-600">*</span></label>
                
                    <div class="relative">
                        <!-- Password Input -->
                        <input :type="show ? 'text' : 'password'" id="password" name="password" class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all placeholder:text-gray-700" placeholder="••••••••" autocomplete="new-password" >
                
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
                
                
                <div x-data="{ show: false }" class="mb-4">
                    <label for="password" class="text-gray-500 text-[12px] font-medium uppercase block tracking-wider mb-1">Confirm Password <span class="text-red-600">*</span></label>
                
                    <div class="relative">
                        <!-- Password Input -->
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                            class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all placeholder:text-gray-700" placeholder="••••••••" autocomplete="new-password" >
                
                        <!-- Toggle Icon Inside Input on the Right -->
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-5 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" >
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
                
                    @if ($errors->has('password_confirmation'))
                        <p class="text-red-600">{{ $errors->first('password_confirmation') }}</p>
                    @elseif ($errors->has('password'))
                        {{-- @if (str_contains($errors->first('password'), 'confirmation'))
                            <p class="text-red-600">{{ $errors->first('password') }}</p>
                        @endif --}}
                    @endif
                </div>

                <button type="submit" class="cursor-pointer transition-all duration-[600ms] w-full bg-[#2A7CFF] py-4 rounded-xl font-medium text-white text-[14px] uppercase hover:bg-[#1A6BFF] transition-all shadow-lg shadow-[#2A7CFF]/20 mt-4 active:scale-[0.98]">
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-500 text-sm">Do you have an account already? 
                    <a href="{{ route('login') }}" class="text-[#2A7CFF] font-medium hover:underline">Log In</a>
                </p>
            </div>
        </div>
    </div>
</section>



@endsection

@section('script')
    <script>
        document.querySelector('input[name="phone"]').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        });
    </script>
@endsection
