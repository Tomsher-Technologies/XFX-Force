<!--footer-->
<footer class="bg-[#0F161B] relative z-[1]">
    <div style="background: url('{{ asset('assets/images/footer-top.png') }}')" class="footer-top bg-black xl:bg-transparent xl:bg-[url(/src/images/footer-top.png)] bg-no-repeat !bg-cover px-[16px] md:px-[30px] xl:px-[140px] pt-[50px] xl:pt-[80px] z-[1] relative" style="background-size: cover;">
        <div class="flex flex-col md:grid md:grid-cols-3 gap-4 w-full">
                <div class="grid col-span-1 col-start-1 xl:col-start-2">
                    <div class="footer-logo w-full flex items-center md:items-start justify-start">
                    @php
                        $logo = get_setting('footer_logo');
                       
                    @endphp
                    <img src="{{ uploaded_asset($logo) }}" alt="Logo" title="Logo" class="w-[250px] h-auto m-auto">
                </div>
            </div>
            <div class="grid col-span-1 col-start-3 justify-items-center border-hidden md:border-l-1 md:border-[#282B34]">
                    <div class="contact-details mt-[30px] md:mt-[0px]">
                        <span class="font-normal text-white text-[12px] xl:text-[14px] mb-[10px] uppercase block text-center md:text-left">{{ get_setting('footer_contact_title') }}</span>
                    <a href="tel:{{ get_setting('footer_phone') }}" class="block text-white text-[18px] xl:text-[25px] font-medium text-center md:text-left" title="Call PC Garage UAE Support">{{ get_setting('footer_phone') }}</a>
                    <a href="mailto:{{ get_setting('footer_email') }}" title="Email PC Garage Support" aria-label="Email PC Garage Support" class="block text-white text-[18px] xl:text-[25px] font-medium text-center md:text-left">{{ get_setting('footer_email') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-mid bg-black pt-[50px] lg:pt-[80px] px-[16px] md:px-[30px] xl:px-[140px] text-center md:text-left">
        <div class="footer-links border-y border-[#282B34] py-[0px] md:py-[50px]">
            <div class="flex flex-col md:grid md:grid-cols-2 lg:grid-cols-5 gap-[0px] md:gap-[50px]">
                <div class="py-[50px] md:py-[0px] border-b border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_one') }}</h6>
                    <ul>
                        @php
                            $sectionOneTitles = json_decode(get_setting('widget_one_labels'), true);
                            $sectionOneLinks  = json_decode(get_setting('widget_one_links'), true);
                        @endphp
                       
                        @if ($sectionOneTitles && $sectionOneLinks)
                            @foreach ($sectionOneTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionOneLinks[$index] ?? '#' }}"  class="text-[#898989] text-[15px] transition-all duration-[600ms] hover:text-blue-500 leading-[35px]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_two') }}</h6>
                    <ul>
                        @php
                            $sectionTwoTitles = json_decode(get_setting('widget_two_labels'), true);
                            $sectionTwoLinks  = json_decode(get_setting('widget_two_links'), true);
                        @endphp
                       
                        @if ($sectionTwoTitles && $sectionTwoLinks)
                            @foreach ($sectionTwoTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionTwoLinks[$index] ?? '#' }}" class="text-[#898989] text-[15px] transition-all duration-[600ms] hover:text-blue-500 leading-[35px]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_three') }}</h6>
                    <ul>
                        @php
                            $sectionThreeTitles = json_decode(get_setting('widget_three_labels'), true);
                            $sectionThreeLinks  = json_decode(get_setting('widget_three_links'), true);
                        @endphp
                       
                        @if ($sectionThreeTitles && $sectionThreeLinks)
                            @foreach ($sectionThreeTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionThreeLinks[$index] ?? '#' }}" class="text-[#898989] text-[15px] transition-all duration-[600ms] hover:text-blue-500 leading-[35px]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_four') }}</h6>
                    <ul class="border-list">
                        @php
                            $sectionFourTitles = json_decode(get_setting('widget_four_labels'), true);
                            $sectionFourLinks  = json_decode(get_setting('widget_four_links'), true);
                        @endphp

                        @if ($sectionFourTitles && $sectionFourLinks)
                            @foreach ($sectionFourTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionFourLinks[$index] ?? '#' }}" class="text-[#898989] text-[15px] transition-all duration-[600ms] hover:text-blue-500 leading-[35px]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('footer_address_title') }}</h6>
                    <p class="text-[#898989] text-[15px] leading-[30px]">{{ get_setting('footer_address') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom bg-black py-[30px] px-[16px] md:px-[30px] xl:px-[140px] pb-[100px] md:pb-[30px] xl:pb-0">
        <div class="flex flex-col lg:grid grid-cols-3 gap-[30px] xl:gap-[0px]">
            <div class="flex align-center justify-center xl:justify-start items-center">
                <p class="text-white text-[14px] font-medium text-center md:text-left">
                    {{ str_replace('{year}', date('Y'), get_setting('frontend_copyright_text')) }} | Website by <a href="https://www.tomsher.com/" target="_blank">Tomsher</a>
                </p>
            </div>
            <div class="flex align-center justify-center items-center">
                <div class="flex flex-row gap-[10px] xl:gap-[30px] align-center justify-center">
                    @if(get_setting('instagram_link'))
                        <a href="{{ get_setting('instagram_link') }}" target="_blank"><img src="{{ asset('assets/images/instagram.svg') }}" alt="Instagram" class="w-[20px] h-[20px]"></a>
                    @endif

                    @if(get_setting('linkedin_link'))
                        <a href="{{ get_setting('linkedin_link') }}" target="_blank"><img src="{{ asset('assets/images/linkedin.svg') }}" alt="LinkedIn" class="w-[20px] h-[20px]"></a>
                    @endif

                    @if(get_setting('facebook_link'))
                        <a href="{{ get_setting('facebook_link') }}" target="_blank"><img src="{{ asset('assets/images/facebook.svg') }}" alt="Facebook" class="w-[20px] h-[20px]"></a>
                    @endif

                    @if(get_setting('twitter_link'))
                        <a href="{{ get_setting('twitter_link') }}" target="_blank"><img src="{{ asset('assets/images/twitter.svg') }}" alt="Twitter" class="w-[20px] h-[20px]"></a>
                    @endif

                    @if(get_setting('youtube_link'))
                        <a href="{{ get_setting('youtube_link') }}" target="_blank"><img src="{{ asset('assets/images/youtube.svg') }}" alt="YouTube" class="w-[20px] h-[20px]"></a>
                    @endif

                    @if(get_setting('whatsapp_link'))
                        <a href="{{ get_setting('whatsapp_link') }}" target="_blank"><img src="{{ asset('assets/images/whatsapp.svg') }}" alt="WhatsApp" class="w-[20px] h-[20px]"></a>
                    @endif
                </div>
            </div>
            <div class="flex align-center justify-center items-center">
                <img src="{{ uploaded_asset(get_setting('footer_payment_logo')) }}" alt="Payment Methods" class="w-full md:w-[280px] h-auto object-contain object-center block m-auto xl:ml-auto">
            </div>
        </div>
    </div>
</footer>
<!--//footer-->


<!--mobile footer navigation-->
<div class="fixed bottom-0 left-0 right-0 bg-[#212328] border-t border-[#2A7CFF]/30 px-6 py-3 z-[50] md:hidden">
    <ul class="flex justify-between items-center">
        <li>
            <a href="/" class="flex flex-col items-center gap-1 text-[#2A7CFF]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="text-[10px] font-medium uppercase">Home</span>
            </a>
        </li>

        <li>
            <a href="/products" class="flex flex-col items-center gap-1 text-gray-400 hover:text-[#2A7CFF] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="text-[10px] font-medium uppercase tracking-wider">Products</span>
            </a>
        </li>

        <li>
            <a href="{{ route('cart') }}" class="flex flex-col items-center gap-1 text-gray-400 hover:text-[#2A7CFF] transition-colors relative">
                <span class="count bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] text-white text-[10px] font-bold h-5 w-5 inline-flex leading-[25px] p-[5px] items-center justify-center rounded-full absolute -top-1 -right-1 border-2 border-transparent">10</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <span class="text-[10px] font-medium uppercase tracking-wider">Cart</span>
            </a>
        </li>

        <li>
            <a href="/account" class="flex flex-col items-center gap-1 text-gray-400 hover:text-[#2A7CFF] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span class="text-[10px] font-medium uppercase tracking-wider">Account</span>
            </a>
        </li>
    </ul>
</div>
<!--//mobile footer navigation-->