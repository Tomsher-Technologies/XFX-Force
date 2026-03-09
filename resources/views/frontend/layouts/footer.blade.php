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
                        <a href="{{ get_setting('twitter_link') }}" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="rgba(255,255,255,1)"><path d="M10.4883 14.651L15.25 21H22.25L14.3917 10.5223L20.9308 3H18.2808L13.1643 8.88578L8.75 3H1.75L9.26086 13.0145L2.31915 21H4.96917L10.4883 14.651ZM16.25 19L5.75 5H7.75L18.25 19H16.25Z"></path></svg>
                        </a>
                    @endif

                    @if(get_setting('youtube_link'))
                        <a href="{{ get_setting('youtube_link') }}" target="_blank"><img src="{{ asset('assets/images/youtube.svg') }}" alt="YouTube" class="w-[20px] h-[20px]"></a>
                    @endif

                    @if(get_setting('whatsapp_link'))
                        <a href="{{ get_setting('whatsapp_link') }}" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="rgba(255,255,255,1)"><path d="M7.25361 18.4944L7.97834 18.917C9.18909 19.623 10.5651 20 12.001 20C16.4193 20 20.001 16.4183 20.001 12C20.001 7.58172 16.4193 4 12.001 4C7.5827 4 4.00098 7.58172 4.00098 12C4.00098 13.4363 4.37821 14.8128 5.08466 16.0238L5.50704 16.7478L4.85355 19.1494L7.25361 18.4944ZM2.00516 22L3.35712 17.0315C2.49494 15.5536 2.00098 13.8345 2.00098 12C2.00098 6.47715 6.47813 2 12.001 2C17.5238 2 22.001 6.47715 22.001 12C22.001 17.5228 17.5238 22 12.001 22C10.1671 22 8.44851 21.5064 6.97086 20.6447L2.00516 22ZM8.39232 7.30833C8.5262 7.29892 8.66053 7.29748 8.79459 7.30402C8.84875 7.30758 8.90265 7.31384 8.95659 7.32007C9.11585 7.33846 9.29098 7.43545 9.34986 7.56894C9.64818 8.24536 9.93764 8.92565 10.2182 9.60963C10.2801 9.76062 10.2428 9.95633 10.125 10.1457C10.0652 10.2428 9.97128 10.379 9.86248 10.5183C9.74939 10.663 9.50599 10.9291 9.50599 10.9291C9.50599 10.9291 9.40738 11.0473 9.44455 11.1944C9.45903 11.25 9.50521 11.331 9.54708 11.3991C9.57027 11.4368 9.5918 11.4705 9.60577 11.4938C9.86169 11.9211 10.2057 12.3543 10.6259 12.7616C10.7463 12.8783 10.8631 12.9974 10.9887 13.108C11.457 13.5209 11.9868 13.8583 12.559 14.1082L12.5641 14.1105C12.6486 14.1469 12.692 14.1668 12.8157 14.2193C12.8781 14.2457 12.9419 14.2685 13.0074 14.2858C13.0311 14.292 13.0554 14.2955 13.0798 14.2972C13.2415 14.3069 13.335 14.2032 13.3749 14.1555C14.0984 13.279 14.1646 13.2218 14.1696 13.2222V13.2238C14.2647 13.1236 14.4142 13.0888 14.5476 13.097C14.6085 13.1007 14.6691 13.1124 14.7245 13.1377C15.2563 13.3803 16.1258 13.7587 16.1258 13.7587L16.7073 14.0201C16.8047 14.0671 16.8936 14.1778 16.8979 14.2854C16.9005 14.3523 16.9077 14.4603 16.8838 14.6579C16.8525 14.9166 16.7738 15.2281 16.6956 15.3913C16.6406 15.5058 16.5694 15.6074 16.4866 15.6934C16.3743 15.81 16.2909 15.8808 16.1559 15.9814C16.0737 16.0426 16.0311 16.0714 16.0311 16.0714C15.8922 16.159 15.8139 16.2028 15.6484 16.2909C15.391 16.428 15.1066 16.5068 14.8153 16.5218C14.6296 16.5313 14.4444 16.5447 14.2589 16.5347C14.2507 16.5342 13.6907 16.4482 13.6907 16.4482C12.2688 16.0742 10.9538 15.3736 9.85034 14.402C9.62473 14.2034 9.4155 13.9885 9.20194 13.7759C8.31288 12.8908 7.63982 11.9364 7.23169 11.0336C7.03043 10.5884 6.90299 10.1116 6.90098 9.62098C6.89729 9.01405 7.09599 8.4232 7.46569 7.94186C7.53857 7.84697 7.60774 7.74855 7.72709 7.63586C7.85348 7.51651 7.93392 7.45244 8.02057 7.40811C8.13607 7.34902 8.26293 7.31742 8.39232 7.30833Z"></path></svg>
                        </a>
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