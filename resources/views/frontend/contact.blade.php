@extends('frontend.layouts.app')

@section('title', 'Contact Us')
@section('content')
    <section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
       <div class="text-white">
            <div class="pt-0 justify-center">
                <main>
                    <div>
                        <div class="section-title mb-10 relative border-t border-[#ffffff30] pt-[50px]">
                            <h1 class="w-full text-[40px] md:text-[50px] text-white font-bold text-center uppercase flex flex-col md:flex-row flex-start justify-center lg:justify-start items-center md:items-start gap-[0px] md:gap-[10px] m-0 leading-[30px] md:leading-[60px]">
                                {{ $page_content['title'] ?? '' }}
                            </h1>
                            <p class="text-[#2A7CFF] uppercase text-sm text-center lg:text-left">
                                {{ $page_content['sub_title'] ?? '' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                            
                            <div class="lg:col-span-5 space-y-12">
                                
                                <div class="space-y-3 md:space-y-6">
                                    <div class="flex items-start gap-6 1C2228 border border-gray-800 p-8 rounded-3xl group hover:border-[#2A7CFF]/30 transition-all">
                                        <div class="w-12 h-12 rounded-2xl bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)]/10 flex items-center justify-center text-[#2A7CFF] flex-shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-medium text-gray-400 uppercase mb-1">
                                                {{ $page_content['address_heading'] ?? '' }}
                                            </p>
                                            <p class="text-white text-md lg:text-lg font-medium">
                                                {{ $page_content['address_content'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-6 border border-gray-800 p-8 rounded-3xl group hover:border-[#2A7CFF]/30 transition-all">
                                        <div class="w-12 h-12 rounded-2xl bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)]/10 flex items-center justify-center text-[#2A7CFF] flex-shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-medium text-gray-400 uppercase mb-1">
                                                {{ $page_content['phone_heading'] ?? '' }}
                                            </p>
                                            <a href="tel:{{ $page_content['phone_content'] ?? '' }}" class="text-white text-md lg:text-lg font-medium hover:text-[#2A7CFF]">
                                                {{ $page_content['phone_content'] ?? '' }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-6 border border-gray-800 p-8 rounded-3xl group hover:border-[#2A7CFF]/30 transition-all">
                                        <div class="w-12 h-12 rounded-2xl bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)]/10 flex items-center justify-center text-[#2A7CFF] flex-shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-medium text-gray-400 uppercase mb-1">
                                                {{ $page_content['email_heading'] ?? '' }}
                                            </p>
                                            <a href="mailto:{{ $page_content['email_content'] ?? '' }}" class="text-white text-md lg:text-lg font-medium hover:text-[#2A7CFF]">
                                                {{ $page_content['email_content'] ?? '' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full h-[300px] bg-white/5 rounded-3xl overflow-hidden border border-white/5 grayscale invert opacity-40">
                                    <iframe src="{{ $page_content['google_map_link'] ?? ''}}" class="w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                            </div>

                            <div class="lg:col-span-7">
                                <div class="bg-[#1C2228] backdrop-blur-md border border-white/10 p-8 md:p-10 rounded-[20px]">
                                    <h2 class="text-[20px] font-medium text-white uppercase mb-4 text-center lg:text-left">
                                        {{ $page_content['form_title'] ?? '' }}
                                    </h2>
                                    
                                    <form id="pc-contact-form" class="space-y-6" action="{{ route('contact.submit') }}" method="POST">
                                        @csrf
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-medium text-gray-400 uppercase mb-2">Name <span class="text-red-500">*</span></label>
                                            <input type="text"  class="w-full bg-white/5 border border-white/10 rounded-[10px] py-3 px-6 text-white focus:border-[#2A7CFF] outline-none" name="name" value="{{ old('name') }}" minlength="3" placeholder="Name">
                                            @error('name')
                                                <div class="text-red-600">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-medium text-gray-400 uppercase mb-2">Email <span class="text-red-500">*</span></label>
                                                <input type="email"  class="w-full bg-white/5 border border-white/10 rounded-[10px] py-3 px-6 text-white focus:border-[#2A7CFF] outline-none" name="email" value="{{ old('email') }}" placeholder="Email">
                                                @error('email')
                                                    <div class="text-red-600">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label class="text-[10px] font-medium text-gray-400 uppercase mb-2">Phone <span class="text-red-500">*</span></label>
                                                <input type="tel"  class="w-full bg-white/5 border border-white/10 rounded-[10px] py-3 px-6 text-white focus:border-[#2A7CFF] outline-none" name="phone" value="{{ old('phone') }}" placeholder="Phone">
                                                @error('phone')
                                                    <div class="text-red-600">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-[10px] font-medium text-gray-400 uppercase mb-2">Subject <span class="text-red-500">*</span></label>
                                            <input type="text" placeholder="Subject"  class="w-full bg-white/5 border border-white/10 rounded-[10px] py-3 px-6 text-white focus:border-[#2A7CFF] outline-none" name="subject" value="{{ old('subject') }}">
                                            @error('subject')
                                                <div class="text-red-600">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-[10px] font-medium text-gray-400 uppercase mb-2">Message <span class="text-red-500">*</span></label>
                                            <textarea rows="5"  class="w-full bg-white/5 border border-white/10 rounded-[10px] py-3 px-6 text-white focus:border-[#2A7CFF] outline-none resize-none" name="message">{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="text-red-600">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-red-600">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                        </div>

                                        <button type="submit" class="w-full bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] text-white py-4 rounded-[10px] font-medium uppercase text-[13px] cursor-pointer hover:bg-white hover:text-black transition-all">
                                            Submit
                                        </button>

                                        <div id="form-success" class="hidden flex items-center gap-3 p-4 bg-green-500/10 border border-green-500/20 rounded-[10px] text-green-500 text-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Message sent successfully! We will contact you shortly.
                                        </div>

                                        <div id="form-failed" class="hidden flex items-center gap-3 p-4 bg-red-500/10 border border-red-500/20 rounded-[10px] text-red-500 text-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Failed to send. Please check your connection and try again.
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection