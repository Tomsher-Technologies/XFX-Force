@extends('frontend.layouts.app')

@section('title', 'Change Password')

@section('content')

<section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
       <div class="text-white">
            <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t-none xl:border-t border-[#252b31] pt-0 xl:pt-[80px]">
                
                @include('frontend.layouts.sidebar')
                
                <main class="flex-grow xl:pb-0">
                    <div>
                        <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-8 pb-[30px] gap-4 border-b border-[#252B31] text-center md:text-left">
                            <div class="w-full">
                                <h2 class="text-[20px] md:text-[24px] font-medium mb-1 text-white uppercase tracking-tight">Change Password</h2>
                                <p class="text-gray-500 text-sm md:text-base">Update your password to keep your account secure.</p>
                            </div>
                        </div>

                        <div class="bg-[#0f161b47] backdrop-blur-[60px] xl:bg-[#1C2228] border border-[#282B34] p-4 md:p-6 rounded-[20px] shadow-2xl">
                            <form  action="{{ route('account.changePassword') }}" method="POST"   class="space-y-6" autocomplete="off">
                                @csrf
                                <div class="space-y-2">
                                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] px-1">Current Password</label>
                                    <div class="relative">
                                        <input type="password" id="current_password" name="current_password" autocomplete="current-password" class="w-full bg-[#0B0F13] border border-[#282B34] rounded-xl p-4 text-white outline-none focus:border-[#2A7CFF] transition-all pr-12 text-sm md:text-base" placeholder="••••••••">
                                        <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-white transition-colors cursor-pointer p-1">
                                            <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg class="eye-off-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.222 4.222m15.556 15.556L14.121 14.121M21.543 12C20.268 7.943 16.478 5 12 5c-1.123 0-2.185.187-3.175.532m6.175 6.175l3.535 3.536" />
                                            </svg>
                                        </button>
                                    </div>

                                    @error('current_password')
                                        <span class="text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <hr class="border-[#282B34] my-2">

                                <div class="space-y-2">
                                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] px-1">New Password</label>
                                    <div class="relative">
                                        <input type="password" id="new_password" name="new_password" autocomplete="new-password"  oninput="checkStrength(this.value)" class="w-full bg-[#0B0F13] border border-[#282B34] rounded-xl p-4 text-white outline-none focus:border-[#2A7CFF] transition-all pr-12 text-sm md:text-base" placeholder="Min. 8 characters">
                                        <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-white transition-colors cursor-pointer p-1">
                                            <!-- Show Icon (Visible by default) -->
                                            <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            
                                            <!-- Hide Icon (Hidden by default using inline style to prevent flickering) -->
                                            <svg class="eye-off-icon w-5 h-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.222 4.222m15.556 15.556L14.121 14.121M21.543 12C20.268 7.943 16.478 5 12 5c-1.123 0-2.185.187-3.175.532m6.175 6.175l3.535 3.536" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex gap-1.5 mt-4 px-1">
                                        <div id="bar-1" class="h-1 flex-1 bg-gray-800 rounded-full transition-all duration-500"></div>
                                        <div id="bar-2" class="h-1 flex-1 bg-gray-800 rounded-full transition-all duration-500"></div>
                                        <div id="bar-3" class="h-1 flex-1 bg-gray-800 rounded-full transition-all duration-500"></div>
                                        <div id="bar-4" class="h-1 flex-1 bg-gray-800 rounded-full transition-all duration-500"></div>
                                    </div>
                                    <p id="strength-text" class="text-[10px] text-gray-500 uppercase font-bold mt-2 px-1 tracking-wider">Strength: Too Weak</p>

                                    @error('new_password')
                                        <span class="text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] px-1">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password" class="w-full bg-[#0B0F13] border border-[#282B34] rounded-xl p-4 text-white outline-none focus:border-[#2A7CFF] transition-all pr-12 text-sm md:text-base" placeholder="Repeat new password">
                                        <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-white transition-colors cursor-pointer p-1">
                                            <!-- Show Icon -->
                                            <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            
                                            <!-- Hide Icon (Use inline style to prevent the flash/overlap) -->
                                            <svg class="eye-off-icon w-5 h-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.222 4.222m15.556 15.556L14.121 14.121M21.543 12C20.268 7.943 16.478 5 12 5c-1.123 0-2.185.187-3.175.532m6.175 6.175l3.535 3.536" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('new_password_confirmation')
                                        <span class="text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="bg-[#0B0F13]/50 rounded-xl p-4 border border-white/5">
                                    <h5 class="text-white text-[11px] font-bold uppercase mb-1 tracking-wide">
                                        Security Tip:
                                    </h5>

                                    <ul class="text-gray-500 text-xs space-y-2">

                                        <li class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] rounded-full"></div>
                                            Use at least <strong>8 characters</strong>.
                                        </li>

                                        <li class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] rounded-full"></div>
                                            Include at least <strong>one uppercase letter (A–Z)</strong>.
                                        </li>

                                        <li class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] rounded-full"></div>
                                            Add at least <strong>one number (0–9)</strong>.
                                        </li>

                                        <li class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] rounded-full"></div>
                                            Include a <strong>special character</strong> like @, $, !, %, *, #, ?, &.
                                        </li>

                                    </ul>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                                    <button type="submit" class="flex-1 bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] text-white font-medium uppercase py-4 rounded-xl cursor-pointer text-[13px] hover:bg-[#1a66e5] transition-all shadow-lg shadow-blue-900/20 active:scale-95">Update Password</button>
                                    <a href="{{ route('update-password') }}" class="flex-1 bg-transparent border border-[#282B34] text-gray-500 font-medium text-center cursor-pointer uppercase py-4 rounded-xl text-[13px] hover:bg-white/5 transition-all">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>

            </div>
        </div>
        
    </section>

@endsection


@section('script')
    @if(session()->has('message'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                toastr.{{ session('alert-type', 'info') }}("{{ session('message') }}");
            });
        </script>
    @endif

<script>
    function checkStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[@$!%*#?&]/)) strength++;

        const bars = [
            document.getElementById('bar-1'),
            document.getElementById('bar-2'),
            document.getElementById('bar-3'),
            document.getElementById('bar-4')
        ];

        const text = document.getElementById('strength-text');

        const colors = ['bg-red-500','bg-orange-500','bg-yellow-500','bg-green-500'];
        const labels = ['Too Weak','Weak','Good','Strong'];

        bars.forEach(bar => {
            bar.className = 'h-1 flex-1 bg-gray-800 rounded-full transition-all duration-500';
        });

        if (password.length > 0) {
            for (let i = 0; i < strength; i++) {
                bars[i].classList.replace('bg-gray-800', colors[strength - 1]);
            }

            text.innerText = `Strength: ${labels[strength - 1]}`;
            text.className = `text-[10px] uppercase font-bold mt-2 px-1 tracking-wider ${colors[strength - 1].replace('bg-', 'text-')}`;
        } else {
            text.innerText = 'Strength: Too Weak';
            text.className = 'text-[10px] text-gray-500 uppercase font-bold mt-2 px-1 tracking-wider';
        }
    }
</script>
@endsection