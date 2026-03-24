<!--footer-->
<footer class="bg-[#0F161B]">
    <div style="background: url('{{ asset('assets/images/footer-top.png') }}')" class="footer-top bg-[#000000] xl:bg-transparent xl:bg-[url('/assets/images/footer-top.png')] bg-no-repeat px-[16px] md:px-[30px] xl:px-[140px] pt-[50px] xl:pt-[80px] z-[1] relative">
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
        <div class="footer-links border-y-1 border-[#282B34] py-[0px] md:py-[50px]">
            <div class="flex flex-col md:grid md:grid-cols-2 lg:grid-cols-5 gap-[0px] md:gap-[50px]">
                <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_one') }}</h6>
                    <ul>
                        @php
                            $sectionOneTitles = json_decode(get_setting('widget_one_labels'), true);
                            $sectionOneLinks  = json_decode(get_setting('widget_one_links'), true);
                        @endphp
                       
                        @if ($sectionOneTitles && $sectionOneLinks)
                            @foreach ($sectionOneTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionOneLinks[$index] ?? '#' }}"  class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_two') }}</h6>
                    <ul>
                        @php
                            $sectionTwoTitles = json_decode(get_setting('widget_two_labels'), true);
                            $sectionTwoLinks  = json_decode(get_setting('widget_two_links'), true);
                        @endphp
                       
                        @if ($sectionTwoTitles && $sectionTwoLinks)
                            @foreach ($sectionTwoTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionTwoLinks[$index] ?? '#' }}" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_three') }}</h6>
                    <ul>
                        @php
                            $sectionThreeTitles = json_decode(get_setting('widget_three_labels'), true);
                            $sectionThreeLinks  = json_decode(get_setting('widget_three_links'), true);
                        @endphp
                       
                        @if ($sectionThreeTitles && $sectionThreeLinks)
                            @foreach ($sectionThreeTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionThreeLinks[$index] ?? '#' }}" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('widget_four') }}</h6>
                    <ul class="border-list">
                        @php
                            $sectionFourTitles = json_decode(get_setting('widget_four_labels'), true);
                            $sectionFourLinks  = json_decode(get_setting('widget_four_links'), true);
                        @endphp

                        @if ($sectionFourTitles && $sectionFourLinks)
                            @foreach ($sectionFourTitles as $index => $title)
                                <li>
                                    <a href="{{ $sectionFourLinks[$index] ?? '#' }}" class="text-[#898989] text-[15px] transition-all duration-600 hover:text-blue-500 leading-[35px] block w-full py-[10px] border-b border-[#282B34]">
                                        {{ $title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="py-[50px] md:py-[0px] border-b-1 border-[#282B34] md:border-hidden">
                    <h6 class="text-white uppercase text-[14px] font-medium block mb-[20px]">{{ get_setting('footer_address_title') }}</h6>
                    <p class="text-[#898989] text-[15px] leading-[30px]">{{ get_setting('footer_address') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom bg-black py-[30px] px-[16px] md:px-[30px] xl:px-[140px]">
        <div class="flex flex-col md:grid grid-cols-3 gap-[30px] md:gap-[0px]">
            <div class="flex align-center justify-center md:justify-start items-center">
                <p class="text-white text-[15px] font-medium text-center md:text-left">
                    {{ str_replace('{year}', date('Y'), get_setting('frontend_copyright_text')) }} | Website by <a href="https://www.tomsher.com/" target="_blank">Tomsher</a>
                </p>
            </div>
            <div class="flex align-center justify-center items-center">
                <div class="flex flex-row gap-[30px] align-center justify-center">
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
                <img src="{{ uploaded_asset(get_setting('footer_payment_logo')) }}" alt="Payment Methods" class="w-full md:w-[280px] h-auto object-contain object-center block ml-auto">
            </div>
        </div>
    </div>
</footer>
<!--//footer-->