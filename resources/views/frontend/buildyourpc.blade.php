@extends('frontend.layouts.app')

@section('title', 'Build your PC')
@section('content')
@php
$hideHeader = true;
$hideFooter = true;
@endphp


<section class="m-[16px] xl:m-0 bg-[#0F161B] h-screen p-[0px] xl:p-[15px] flex flex-col overflow-x-hidden">
    <main class="grid grid-cols-1 xl:grid-cols-12 h-full xl:h-[calc(100vh-15px)] overflow-y-auto rounded-[20px] custom-scrollbar">

        <!--left sidebar-->
        <div onclick="closeAllMobileSystems()" id="sidebar-overlay" data-backdrop="true" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[110] opacity-0 pointer-events-none transition-opacity duration-300 flex justify-end p-5">
            <button onclick="closeAllMobileSystems()" class="text-white h-10 w-10 flex items-center justify-center bg-white/10 rounded-full">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <aside id="mobile-sidebar" class="fixed xl:static left-[-100%] xl:left-0 col-span-2 bg-[#1C2228] border border-[#1E2529] rounded-[0px] md:rounded-[20px] flex flex-col overflow-hidden shadow-2xl transition-all duration-300 h-screen xl:h-[calc(100vh-30px)] top-0 z-[99999]">

            <div class="p-[30px] border-b border-[#2E363E]">
                <a href="{{ route('home') }}" class="items-center gap-[20px]">
                    @php
                        $logo = get_setting('header_logo');
                    @endphp
                    <img src="{{ uploaded_asset($logo) }}" alt="PC Garage Logo" title="PC Garage Logo" class="w-[200px]">
                    <div class="text-left ml-[45px]">
                        <span class="text-gray-500 text-[15px]">Build your dream PC</span>
                    </div>
                </a>
            </div>

            <nav class="flex-grow overflow-y-auto p-[20px] md:p-[30px] no-scrollbar justify-between">
                <input type="hidden" id="pcBuilderId" value="{{ $builder->id ?? '' }}">
                <div>
                    <div class="space-y-[5px]">
                        @foreach ($builderCategories as $builderCategory)
                        <a href="#" class="relative nav-item text-[15px] flex items-center gap-4 px-4 py-3 rounded-xl text-gray-400 hover:bg-[#252C33] hover:text-white transition-all duration-[600ms] group" data-category-id="{{ $builderCategory->category_id }}" data-min-select="{{ $builderCategory->min_select }}" data-max-select="{{ $builderCategory->max_select }}" data-category-name="{{ $builderCategory->category->name }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="icon" d="M12.4936 7.50636C12.5936 8.10091 12.7273 9.06545 12.7273 10C12.7273 10.9345 12.5945 11.8991 12.4936 12.4936C11.8991 12.5936 10.9345 12.7273 10 12.7273C9.06545 12.7273 8.10091 12.5945 7.50636 12.4936C7.40636 11.8991 7.27273 10.9345 7.27273 10C7.27273 9.06545 7.40545 8.10091 7.50636 7.50636C8.10091 7.40636 9.06545 7.27273 10 7.27273C10.9345 7.27273 11.8991 7.40545 12.4936 7.50636ZM20 11.8182C20 12.3209 19.5936 12.7273 19.0909 12.7273H18.04C17.9764 13.3845 17.8991 14.0027 17.82 14.5455H18.6364C19.1391 14.5455 19.5455 14.9518 19.5455 15.4545C19.5455 15.9573 19.1391 16.3636 18.6364 16.3636H17.5109C17.4682 16.5809 17.4391 16.7145 17.4336 16.74C17.3582 17.0873 17.0873 17.3582 16.74 17.4336C16.7145 17.4391 16.5809 17.4682 16.3636 17.5109V18.6364C16.3636 19.1391 15.9573 19.5455 15.4545 19.5455C14.9518 19.5455 14.5455 19.1391 14.5455 18.6364V17.82C14.0027 17.8991 13.3845 17.9764 12.7273 18.04V19.0909C12.7273 19.5936 12.3209 20 11.8182 20C11.3155 20 10.9091 19.5936 10.9091 19.0909V18.1627C10.6073 18.1736 10.3036 18.1818 10 18.1818C9.69636 18.1818 9.39273 18.1736 9.09091 18.1627V19.0909C9.09091 19.5936 8.68455 20 8.18182 20C7.67909 20 7.27273 19.5936 7.27273 19.0909V18.04C6.61545 17.9764 5.99727 17.8991 5.45455 17.82V18.6364C5.45455 19.1391 5.04818 19.5455 4.54545 19.5455C4.04273 19.5455 3.63636 19.1391 3.63636 18.6364V17.5109C3.41909 17.4682 3.28545 17.4391 3.26 17.4336C2.91273 17.3582 2.64182 17.0873 2.56636 16.74C2.56091 16.7145 2.53182 16.5809 2.48909 16.3636H1.36364C0.860909 16.3636 0.454545 15.9573 0.454545 15.4545C0.454545 14.9518 0.860909 14.5455 1.36364 14.5455H2.18C2.10091 14.0027 2.02364 13.3845 1.96 12.7273H0.909091C0.406364 12.7273 0 12.3209 0 11.8182C0 11.3155 0.406364 10.9091 0.909091 10.9091H1.83727C1.82636 10.6073 1.81818 10.3036 1.81818 10C1.81818 9.69636 1.82636 9.39273 1.83727 9.09091H0.909091C0.406364 9.09091 0 8.68455 0 8.18182C0 7.67909 0.406364 7.27273 0.909091 7.27273H1.96C2.02364 6.61545 2.10091 5.99727 2.18 5.45455H1.36364C0.860909 5.45455 0.454545 5.04818 0.454545 4.54545C0.454545 4.04273 0.860909 3.63636 1.36364 3.63636H2.48909C2.53182 3.41909 2.56091 3.28545 2.56636 3.26C2.64182 2.91273 2.91273 2.64182 3.26 2.56636C3.28545 2.56091 3.41909 2.53182 3.63636 2.48909V1.36364C3.63636 0.860909 4.04273 0.454545 4.54545 0.454545C5.04818 0.454545 5.45455 0.860909 5.45455 1.36364V2.18C5.99727 2.10091 6.61545 2.02364 7.27273 1.96V0.909091C7.27273 0.406364 7.67909 0 8.18182 0C8.68455 0 9.09091 0.406364 9.09091 0.909091V1.83727C9.39273 1.82636 9.69636 1.81818 10 1.81818C10.3036 1.81818 10.6073 1.82636 10.9091 1.83727V0.909091C10.9091 0.406364 11.3155 0 11.8182 0C12.3209 0 12.7273 0.406364 12.7273 0.909091V1.96C13.3845 2.02364 14.0027 2.10091 14.5455 2.18V1.36364C14.5455 0.860909 14.9518 0.454545 15.4545 0.454545C15.9573 0.454545 16.3636 0.860909 16.3636 1.36364V2.48909C16.5809 2.53182 16.7145 2.56091 16.74 2.56636C17.0873 2.64182 17.3582 2.91273 17.4336 3.26C17.4391 3.28545 17.4682 3.41909 17.5109 3.63636H18.6364C19.1391 3.63636 19.5455 4.04273 19.5455 4.54545C19.5455 5.04818 19.1391 5.45455 18.6364 5.45455H17.82C17.8991 5.99727 17.9764 6.61545 18.04 7.27273H19.0909C19.5936 7.27273 20 7.67909 20 8.18182C20 8.68455 19.5936 9.09091 19.0909 9.09091H18.1627C18.1736 9.39273 18.1818 9.69636 18.1818 10C18.1818 10.3036 18.1736 10.6073 18.1627 10.9091H19.0909C19.5936 10.9091 20 11.3155 20 11.8182ZM14.5455 10C14.5455 8.30546 14.1764 6.60455 14.16 6.53273C14.0836 6.18636 13.8136 5.91636 13.4673 5.84C13.3955 5.82364 11.6945 5.45455 10 5.45455C8.30546 5.45455 6.60455 5.82364 6.53273 5.84C6.18636 5.91636 5.91636 6.18636 5.84 6.53273C5.82364 6.60455 5.45455 8.30546 5.45455 10C5.45455 11.6945 5.82364 13.3955 5.84 13.4673C5.91636 13.8136 6.18636 14.0836 6.53273 14.16C6.60455 14.1764 8.30546 14.5455 10 14.5455C11.6945 14.5455 13.3955 14.1764 13.4673 14.16C13.8136 14.0836 14.0836 13.8136 14.16 13.4673C14.1764 13.3955 14.5455 11.6945 14.5455 10Z" fill="#9F9FA9" />
                            </svg>
                            <span>{{ $builderCategory->category->name }}</span>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">
                                   
                            </span>
                            <svg class="check-icon absolute right-[16px] top-[0px] bottom-[0px] m-auto hidden" width="15" height="11" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.84324 11C4.41746 11.0002 4.00911 10.8283 3.70828 10.5222L0.276913 7.03766C-0.0923043 6.66246 -0.0923043 6.05428 0.276913 5.67908C0.646249 5.30401 1.24494 5.30401 1.61428 5.67908L4.84324 8.95926L13.3857 0.281305C13.7551 -0.0937682 14.3538 -0.0937682 14.7231 0.281305C15.0923 0.656497 15.0923 1.26468 14.7231 1.63988L5.97821 10.5222C5.67738 10.8283 5.26903 11.0002 4.84324 11Z" fill="white" />
                            </svg>
                        </a>
                        @endforeach
                    </div>
                </div>

            </nav>

            <div class="p-[30px] border-t border-[#2E363E] flex flex-col gap-[20px]">
                <div>
                    <label class="text-[14px] text-gray-400 mb-[5px] block text-left md:text-left">Total Price</label>
                    <div class="price w-full flex flex-row items-end gap-[15px]">
                        <h5 class="price flex flex-row text-white text-left text-[20px] m-[0] font-medium align-center items-center gap-[10px] leading-[35px]">
                            <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff" />
                            </svg>
                            <span id="total_price_with_tax_left">0.00</span>
                        </h5>
                    </div>
                </div>
                <a href="javascript:void(0)" onclick="resetConfiguration()" class="text-[15px] flex items-center gap-4 px-4 py-3 rounded-xl text-gray-400 bg-[#252C33] hover:text-white transition-all duration-[600ms] group">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="group-hover:fill-[#ffffff] transition-all duration-[600ms]" d="M1.66876 9.99976C1.66223 11.9385 2.33049 13.8193 3.5592 15.3203C4.7879 16.8212 6.50069 17.8491 8.40448 18.2281C10.3083 18.607 12.2848 18.3134 13.9958 17.3976C15.7067 16.4817 17.0459 15.0005 17.7841 13.2074C18.5222 11.4143 18.6136 9.42067 18.0424 7.56772C17.4712 5.71478 16.2731 4.11764 14.653 3.04959C13.0329 1.98154 11.0915 1.50897 9.16098 1.71272C7.23049 1.91648 5.43089 2.7839 4.0701 4.16656H7.50939V5.83319H3.2182C2.8074 5.83275 2.41355 5.66957 2.12307 5.37946C1.83259 5.08935 1.6692 4.69601 1.66876 4.28573V0H3.33751V2.56494C5.04522 1.04118 7.22484 0.148957 9.51209 0.0373691C11.7993 -0.0742183 14.0557 0.601595 15.9039 1.95186C17.7522 3.30213 19.0805 5.24505 19.6667 7.45589C20.253 9.66674 20.0617 12.0116 19.1249 14.0985C18.1881 16.1854 16.5625 17.888 14.5197 18.9217C12.477 19.9554 10.1409 20.2575 7.90189 19.7777C5.66288 19.2978 3.6566 18.065 2.21835 16.2853C0.780105 14.5056 -0.00296646 12.2868 8.55458e-06 9.99976H1.66876Z" fill="#9F9FA9" />
                    </svg>
                    <span>Reset Configuration</span>
                </a>
            </div>

        </aside>
        <!--//left sidebar-->

        <!--middle section-->
        <section class="col-span-7 h-[calc(100vh-16px)] xl:h-[calc(100vh-15px)] overflow-y-auto no-scrollbar overflow-x-hidden px-0 xl:px-[15px] pb-[100px] xl:pb-0" id="products-list-page">

            <div class="sticky top-0 left-0 right-0 bg-[#1C2228] border border-[#1E2529] p-[15px] md:p-[30px] rounded-[20px] rounded-t-none flex flex-col justify-between group transition-all gap-[30px] z-[1]">

                <div class="flex flex-col md:flex-row justify-between w-full gap-[15px] md:gap-[0px]">
                    <div class="flex flex-col gap-[5px] w-full">
                        <h2 class="text-white text-[20px] font-medium category-heading"></h2>
                        <p class="text-gray-500 text-[15px]">Choose the best <span class="category-sub-heading"></span> for your gaming needs</p>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="w-full left-0 flex items-center justify-between md:justify-end mt-auto gap-[20px]">
                        <button id="prev-btn" onclick="handlePrevStep()" class="flex w-full md:w-fit cursor-pointer items-center justify-center gap-3 px-5 py-3 rounded-xl border border-[#2E363E] text-gray-400 font-medium uppercase text-[13px] hover:bg-[#252C33] hover:text-white transition-all duration-300 group">
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M-3.14713e-05 6.707C-0.000488758 6.44439 0.0509577 6.18428 0.151352 5.94162C0.251746 5.69896 0.399108 5.47853 0.584969 5.293L5.87897 0L7.29297 1.414L1.99997 6.707L7.29297 12L5.87897 13.414L0.585969 8.121C0.399928 7.93556 0.252382 7.71516 0.151814 7.4725C0.0512471 7.22983 -0.000357628 6.96968 -3.14713e-05 6.707Z" fill="white" />
                            </svg>
                            <span>Previous</span>
                        </button>
                        <button id="next-btn" onclick="handleNextStep()" class="flex w-full md:w-fit cursor-pointer items-center justify-center gap-3 px-5 py-3 rounded-xl bg-[#2A7CFF] text-white font-medium uppercase text-[13px] hover:bg-[#3E81FF] transition-all duration-[600ms] group">
                            <span>Next</span>
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.293 6.707C7.29346 6.96961 7.24201 7.22972 7.14162 7.47238C7.04122 7.71505 6.89386 7.93547 6.708 8.121L1.414 13.414L0 12L5.293 6.707L0 1.414L1.414 -9.53674e-07L6.707 5.293C6.89304 5.47845 7.04059 5.69884 7.14115 5.9415C7.24172 6.18417 7.29333 6.44432 7.293 6.707Z" fill="white" />
                            </svg>
                        </button>
                        <!--proceed button-->
                        <button id="proceed-to-order-btn" onclick="proceedToOrder()" class="hidden flex w-full md:w-fit cursor-pointer items-center justify-center gap-3 px-5 py-3 rounded-xl bg-[#077F09] text-white font-medium uppercase text-[13px] hover:bg-[#055E07] transition-all duration-[600ms] group">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.375 12.5C5.06522 12.5 5.62479 13.0598 5.625 13.75C5.625 14.4404 5.06535 15 4.375 15C3.68465 15 3.125 14.4404 3.125 13.75C3.12521 13.0598 3.68478 12.5 4.375 12.5ZM10.625 12.5C11.3152 12.5 11.8748 13.0598 11.875 13.75C11.875 14.4404 11.3154 15 10.625 15C9.93465 15 9.375 14.4404 9.375 13.75C9.37521 13.0598 9.93478 12.5 10.625 12.5ZM0.763672 0C1.22266 0.000213547 1.66578 0.168673 2.00879 0.473633C2.35176 0.778715 2.57121 1.19942 2.625 1.65527L2.65137 1.875H15L13.6475 9.375H3.53418L3.61523 10.0723C3.633 10.2244 3.70688 10.3649 3.82129 10.4668C3.93567 10.5685 4.08326 10.625 4.23633 10.625H12.5V11.875H4.23633C3.77734 11.8748 3.33422 11.7063 2.99121 11.4014C2.64824 11.0963 2.42879 10.6756 2.375 10.2197L1.38477 1.80176C1.36684 1.64975 1.29309 1.50991 1.17871 1.4082C1.06432 1.30649 0.916744 1.25002 0.763672 1.25H0V0H0.763672ZM3.38672 8.125H12.6025L13.5039 3.125H2.79883L3.38672 8.125Z" fill="white" />
                            </svg>
                            <span>proceed to order</span>
                        </button>
                        <!--//proceed button-->
                    </div>
                </div>

                <!-- Filters in list page -->
                <div class="hidden xl:flex items-center bg-[#252B31] border border-[#1E2529] rounded-[15px] h-[50px] relative">
                    <div class="relative h-full border-r border-[#2E3239] min-w-[200px]">
                        <button onclick="toggleDropdown('brand-menu')" class="flex items-center px-6 w-full h-full hover:bg-[#252C33] transition-colors rounded-[15px]" id="brand-btn">
                            <span class="text-gray-400 text-[14px]">Brand: <b id="brand-label" class="text-white ml-1 text-[14px] font-medium" data-id="0">All Brands</b></span>
                            <svg class="ml-auto text-gray-500" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </button>

                        <div id="brand-menu" class="hidden absolute top-[70px] left-0 w-64 bg-[#1C2228] border border-[#1E2529] rounded-xl shadow-2xl z-50 p-2">
                            <a href="javascript:void(0)" 
                            onclick="selectOption('brand', 'All', '0')" 
                            class="block px-4 py-3 text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg text-sm">
                            All Brands
                            </a>
                            @foreach ($brands as $brand)
                                <a href="javascript:void(0)" onclick="selectOption('brand', '{{ $brand->name }}', '{{ $brand->id }}')" class="block px-4 py-3 text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg text-sm transition-all">{{ $brand->name }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative h-full border-r border-[#2E3239] min-w-[180px]">
                        <button onclick="toggleDropdown('model-menu')" class="flex items-center px-6 w-full h-full hover:bg-[#252C33] transition-colors" id="model-btn">
                            <span class="text-gray-400 text-[14px]">Model: <b id="model-label" class="text-white ml-1 font-medium text-[14px]">All</b></span>
                            <svg class="ml-auto text-gray-500" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </button>

                        <div id="model-menu" class="hidden absolute top-[70px] left-0 w-64 bg-[#1C2228] border border-[#1E2529] rounded-xl shadow-2xl z-50 p-2">
                            <!-- <a href="javascript:void(0)" onclick="selectOption('model', 'RTX 4090')" class="block px-4 py-3 text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg text-sm">RTX 4090</a>
                            <a href="javascript:void(0)" onclick="selectOption('model', 'RTX 4080')" class="block px-4 py-3 text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg text-sm">RTX 4080</a> -->
                        </div>
                    </div>

                    <div class="flex-grow flex items-center px-6 gap-3 border-r border-[#2E3239]">
                        <svg class="text-gray-500" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" placeholder="Search products" class="bg-transparent border-none outline-none text-[14px] text-white w-full placeholder:text-gray-600" id="product-search">
                    </div>

                    <div class="flex items-center h-full px-6 gap-8">
                        <button onclick="toggleDropdown('mega-menu')" class="flex items-center gap-3 text-white font-medium text-[14px] hover:text-[#2A7CFF] transition-colors" id="mega-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M4 21v-7m0-4V3m8 18v-9m0-4V3m8 18v-5m0-4V3M2 14h4m4-6h4m4 8h4" />
                            </svg>
                            Sort by
                        </button>

                        <div id="mega-menu" class="hidden absolute top-[70px] right-0 w-auto bg-[#1C2228] border border-[#1E2529] rounded-[20px] shadow-2xl z-50 p-8">
                            <div>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 text-gray-400 text-[14px] cursor-pointer hover:text-white"><input type="checkbox" class="sort-checkbox accent-[#2A7CFF] transform scale-[25px]" data-sort="price_low_high"> Price: Low to High</label>
                                    <label class="flex items-center gap-3 text-gray-400 text-[14px] cursor-pointer hover:text-white"><input type="checkbox" class="sort-checkbox accent-[#2A7CFF] transform scale-[25px]" data-sort="price_high_low"> Price: High to Low</label>
                                </div>
                            </div>
                        </div>

                        <button class="text-gray-500 font-medium border-gray-600 hover:text-white hover:border-white transition-all cursor-pointer" id="reset-filters">
                            <svg class="w-[15px]" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="group-hover:fill-[#ffffff] transition-all duration-[600ms]" d="M1.66876 9.99976C1.66223 11.9385 2.33049 13.8193 3.5592 15.3203C4.7879 16.8212 6.50069 17.8491 8.40448 18.2281C10.3083 18.607 12.2848 18.3134 13.9958 17.3976C15.7067 16.4817 17.0459 15.0005 17.7841 13.2074C18.5222 11.4143 18.6136 9.42067 18.0424 7.56772C17.4712 5.71478 16.2731 4.11764 14.653 3.04959C13.0329 1.98154 11.0915 1.50897 9.16098 1.71272C7.23049 1.91648 5.43089 2.7839 4.0701 4.16656H7.50939V5.83319H3.2182C2.8074 5.83275 2.41355 5.66957 2.12307 5.37946C1.83259 5.08935 1.6692 4.69601 1.66876 4.28573V0H3.33751V2.56494C5.04522 1.04118 7.22484 0.148957 9.51209 0.0373691C11.7993 -0.0742183 14.0557 0.601595 15.9039 1.95186C17.7522 3.30213 19.0805 5.24505 19.6667 7.45589C20.253 9.66674 20.0617 12.0116 19.1249 14.0985C18.1881 16.1854 16.5625 17.888 14.5197 18.9217C12.477 19.9554 10.1409 20.2575 7.90189 19.7777C5.66288 19.2978 3.6566 18.065 2.21835 16.2853C0.780105 14.5056 -0.00296646 12.2868 8.55458e-06 9.99976H1.66876Z" fill="#9F9FA9" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-[5px] xl:gap-4 py-[15px] min-w-0 overflow-x-hidden relative z-[0]" id="products-list">
                @include('frontend.partials.pc-builder-products-list', ['products' => $products ?? []])
            </div>

            <!-- Optional: hidden spinner -->
            <div id="products-loader" class="hidden text-center p-4 text-white text-[15px] font-medium">
                Loading products...
            </div>

        </section>
        <!--//middle section-->

        <!--middle section (review)-->
        <section class="hidden col-span-7 h-[calc(100vh-30px)] md:h-full xl:h-[calc(100vh-60px)] overflow-y-auto no-scrollbar overflow-x-hidden px-[0px] xl:px-[30px] pb-[30px] xl:pb-0" id="products-review-page">
        
            @include('frontend.partials.pc-builder-products-review', ['reviewProducts' => $reviewProducts['products'] ?? []])
        </section>
        <!--//middle section (review)-->

        <!--right sidebar-->
        <div id="details-overlay" onclick="showDefaultView()" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[140] opacity-0 pointer-events-none transition-opacity duration-300 xl:hidden"></div>
        <aside id="details-sidebar" class=" fixed inset-x-4 inset-y-10 z-[150] translate-y-full opacity-0 pointer-events-none transition-all duration-300 xl:translate-y-0 xl:opacity-100 xl:pointer-events-auto xl:relative xl:inset-0 xl:z-auto xl:col-span-3 bg-[#1C2228] border border-[#1E2529] rounded-[20px] flex flex-col shadow-2xl h-[calc(100vh-80px)] xl:h-[calc(100vh-30px)] overflow-hidden">

            <!--default-->
            <div id="default-view" class="flex flex-col gap-[15px] justify-center items-center h-full p-6">
                <svg class="text-gray-600" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 8v4m0 4h.01" />
                </svg>
                <h2 class="text-[18px] text-gray-400 font-medium text-center">Select a component</h2>
                <p class="text-[14px] text-gray-600 font-medium text-center">Click on any item to view its details</p>
            </div>
            <!--//default-->

            <!--details-->
            <div id="details-view" class="hidden flex flex-col h-full">
                @include('frontend.partials.pc-builder-products-single-details' ,['stock' => null])
            </div>
            <!--//details-->

        </aside>
        <!--//right sidebar-->

        <!--summary right sidebar-->
        <aside id="summary-sidebar" class="hidden relative xl:fixed mb-[100px] pointer-events-none transition-all duration-300 xl:translate-y-0 xl:opacity-100 xl:pointer-events-auto xl:relative xl:inset-0 xl:z-auto xl:col-span-3 bg-[#1C2228] border border-[#1E2529] rounded-[20px] flex flex-col shadow-2xl h-fit overflow-hidden">
            <div class="flex flex-row justify-between items-center p-[20px] md:p-[30px] border-b border-[#2E363E] flex-shrink-0">
                <div>
                    <h2 class="text-white text-[20px] leading-[30px] font-medium">Summary</h2>
                    <span class="text-gray-500 text-[13px] md:text-[15px]">Summary of your items</span>
                </div>
            </div>
            <div class="p-[20px] md:p-[30px]">
                <ul>
                    <li class="py-[10px]">
                        <div class="flex flex-row justify-between">
                            <span class="text-[#99a1af] text-[15px] justify-start text-left">Subtotal</span>
                            <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[10px] text-[15px]">
                                <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                </svg>
                                <span id="summary-total-price">0.00</span>
                            </span>
                        </div>
                    </li>
                    <li class="py-[10px]">
                        <div class="flex flex-row justify-between">
                            <span class="text-[#99a1af] text-[15px] justify-start text-left">Discount</span>
                            <span class="flex flex-row text-[#29A706] items-center justify-end text-right gap-[10px] text-[15px]">
                                - 
                                <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path class="fill-[#29A706]" d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                </svg>
                                <span id="summary-total-discount-price">0.00</span>
                            </span>
                        </div>
                    </li>
                    <li class="py-[10px]">
                        <div class="flex flex-row justify-between">
                            <span class="text-[#99a1af] text-[15px] justify-start text-left">Estimated Tax</span>
                            <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[10px] text-[15px]">
                                <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                </svg>
                                <span id="total-tax">0.00</span>
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="flex flex-col gap-[20px] justify-between items-center p-[20px] md:p-[30px] border-t border-[#2E363E] bg-[#1C2228] rounded-b-[20px]">
                <div class="w-full">
                    <label class="text-[14px] text-gray-400 mb-[5px] block text-left">Total Price</label>
                    <div class="price w-full flex flex-row items-end gap-[15px]">
                        <h5 class="flex flex-row text-white text-left text-[20px] m-[0] font-medium items-center gap-[10px] leading-[35px]">
                            <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff" />
                            </svg>
                            <span id="total_price_with_tax" class="text-white text-left text-[20px] font-medium leading-[35px]">0.00</span>
                        </h5>
                    </div>
                </div>
                <div class="w-full">
                    <!--place order button-->
                    <button id="place-your-order-btn" onclick="placeYourOrder()" class="flex w-full cursor-pointer items-center justify-center gap-3 px-5 py-3 rounded-xl bg-[#077F09] text-white font-medium uppercase text-[13px] hover:bg-[#055E07] transition-all duration-[600ms] group">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.375 12.5C5.06522 12.5 5.62479 13.0598 5.625 13.75C5.625 14.4404 5.06535 15 4.375 15C3.68465 15 3.125 14.4404 3.125 13.75C3.12521 13.0598 3.68478 12.5 4.375 12.5ZM10.625 12.5C11.3152 12.5 11.8748 13.0598 11.875 13.75C11.875 14.4404 11.3154 15 10.625 15C9.93465 15 9.375 14.4404 9.375 13.75C9.37521 13.0598 9.93478 12.5 10.625 12.5ZM0.763672 0C1.22266 0.000213547 1.66578 0.168673 2.00879 0.473633C2.35176 0.778715 2.57121 1.19942 2.625 1.65527L2.65137 1.875H15L13.6475 9.375H3.53418L3.61523 10.0723C3.633 10.2244 3.70688 10.3649 3.82129 10.4668C3.93567 10.5685 4.08326 10.625 4.23633 10.625H12.5V11.875H4.23633C3.77734 11.8748 3.33422 11.7063 2.99121 11.4014C2.64824 11.0963 2.42879 10.6756 2.375 10.2197L1.38477 1.80176C1.36684 1.64975 1.29309 1.50991 1.17871 1.4082C1.06432 1.30649 0.916744 1.25002 0.763672 1.25H0V0H0.763672ZM3.38672 8.125H12.6025L13.5039 3.125H2.79883L3.38672 8.125Z" fill="white" />
                        </svg>
                        <span>Add to cart</span>
                    </button>
                    <!--//place order button-->
                </div>
            </div>
        </aside>
        <!--//summary right sidebar-->



        <!--mobile navigation-->
        <!--navigation menu-->
        <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] w-[92%] md:block xl:hidden">
            <div class="bg-[#1E2225]/90 backdrop-blur-lg border border-white/10 rounded-[10px] shadow-2xl grid grid-cols-4 h-[70px]">
                <button onclick="toggleMobileMenu(event)" class="flex flex-col items-center justify-center gap-1 text-gray-400 border-r border-white/5">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 12h18M3 6h18M3 18h18" />
                    </svg>
                    <span class="text-[9px] font-medium uppercase">Menu</span>
                </button>
                <button onclick="toggleMobileWidget('mobile-filter-panel', event)" class="flex flex-col items-center justify-center gap-1 text-gray-400 border-r border-white/5">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 21v-7m0-4V3m8 18v-9m0-4V3m8 18v-5m0-4V3M2 14h4m4-6h4m4 8h4" />
                    </svg>
                    <span class="text-[9px] font-medium uppercase">Filter</span>
                </button>
                <button onclick="toggleMobileWidget('mobile-search-panel', event)" class="flex flex-col items-center justify-center gap-1 text-gray-400 border-r border-white/5">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    <span class="text-[9px] font-medium uppercase">Search</span>
                </button>
                <button onclick="window.location.reload()" class="flex flex-col items-center justify-center gap-1 text-gray-400">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    <span class="text-[9px] font-medium uppercase">Reset</span>
                </button>
            </div>
        </div>
        <!--//navigation menu-->

        <!--mobile filter-->
        <div id="mobile-filter-panel" class="slide-up-panel fixed inset-0 z-[130] invisible pointer-events-none transition-all duration-300 flex flex-col justify-end">
            <div onclick="closeAllMobileSystems()" data-backdrop="true" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div class="relative bg-[#1E2225] rounded-t-[30px] p-8 transform translate-y-full transition-transform duration-300 ease-out border-t border-white/10 pointer-events-auto max-h-[90vh] overflow-y-auto no-scrollbar">
                <div class="w-12 h-1.5 bg-gray-700 rounded-full mx-auto mb-6"></div>

                <h4 class="text-white font-medium text-lg mb-6 text-center">Filter & Sort</h4>

                <div class="space-y-6">

                    <div class="relative">
                        <label class="text-gray-500 text-[12px] font-medium uppercase mb-3 block">Brand</label>

                        <div class="relative flex items-center">
                            <select 
                            id="mobile-brand"
                            onchange="selectOption('brand', this.options[this.selectedIndex].text, this.value)"
                            class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white"
                            >
                            <option value="0">All Brands</option>

                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach

                            </select>

                            <div class="absolute right-4 pointer-events-none text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="text-gray-500 text-[12px] font-medium uppercase mb-3 block">Model Series</label>

                        <div class="relative flex items-center">
                            <select 
                            id="mobile-model"
                            onchange="selectOption('model', this.value)"
                            class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white"
                            >
                            <option value="">All</option>
                            </select>

                            <div class="absolute right-4 pointer-events-none text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-gray-500 text-[12px] font-medium uppercase mb-3 block">Sort Results By</label>
                        <div id="mobile-sort-options" class="grid grid-cols-2 gap-3">
                            <button 
                            onclick="selectSort('price_low_high')" 
                            class="sort-btn py-3 px-2 bg-[#0B0F13] border border-[#2A7CFF] text-[#2A7CFF] rounded-xl text-[13px] font-medium transition-all active:scale-95"
                            >
                            Price: Low-High
                            </button>

                            <button 
                            onclick="selectSort('price_high_low')" 
                            class="sort-btn py-3 px-2 bg-[#0B0F13] border border-white/5 text-gray-400 rounded-xl text-[13px] font-medium hover:border-white/20 transition-all active:scale-95"
                            >
                            Price: High-Low
                            </button>
                        </div>
                    </div>

                    <button onclick="closeAllMobileSystems()" class="w-full bg-[#2A7CFF] py-5 rounded-2xl font-medium text-white text-[14px] uppercase mt-6 pointer-events-auto">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
        <!--//mobile filter-->

        <!--mobile search-->
        <div id="mobile-search-panel" class="slide-up-panel fixed inset-0 z-[130] invisible pointer-events-none transition-all duration-300 flex flex-col justify-end">
            <div onclick="closeAllMobileSystems()" data-backdrop="true" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative bg-[#1E2225] rounded-t-[30px] p-8 transform translate-y-full transition-transform duration-300 ease-out border-t border-white/10 pointer-events-auto">
                <div class="w-12 h-1.5 bg-gray-700 rounded-full mx-auto mb-6"></div>
                <h4 class="text-white font-medium text-lg mb-6 text-center">Search your item here</h4>
                <input type="text" placeholder="Search..." class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none mb-4">
                <button onclick="closeAllMobileSystems()" class="w-full bg-[#2A7CFF] py-4 rounded-xl font-medium text-white text-[14px] uppercase">Search Now</button>
            </div>
        </div>
        <!--//mobile search-->

        <!--//mobile navigation-->

    </main>
</section>



<script>
    let buildData = @json($buildData);

    const trashIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`;

    const minusIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4" /></svg>`;

    /***** Filter related scripts *******/
    let filterState = {
        sort: ""
    };

    


        function toggleDropdown(menuId) {
            const menus = ['brand-menu', 'model-menu', 'mega-menu'];
            menus.forEach(id => {
                const menu = document.getElementById(id);
                if (id === menuId) {
                    menu.classList.toggle('hidden');
                } else {
                    menu.classList.add('hidden');
                }
            });
        }

        document.addEventListener('click', function (event) {
            const menus = ['brand-menu', 'model-menu', 'mega-menu'];
            const buttons = ['brand-btn', 'model-btn', 'mega-btn']; // adjust IDs

            let clickedInside = false;

            menus.forEach((menuId, index) => {
                const menu = document.getElementById(menuId);
                const btn = document.getElementById(buttons[index]);

                if (menu?.contains(event.target) || btn?.contains(event.target)) {
                    clickedInside = true;
                }
            });

            if (!clickedInside) {
                menus.forEach(id => {
                    document.getElementById(id)?.classList.add('hidden');
                });
            }
        });

        function selectOption(type, value, brandId = 0) {
            const label = document.getElementById(`${type}-label`);

            if (label) {
                label.innerText = value;

                if (type === 'brand') {
                    label.dataset.id = brandId;
                }
            }

            /* Sync Mobile Dropdown */
            if(type === 'brand'){
                const mobileBrand = document.getElementById('mobile-brand');
                if(mobileBrand) mobileBrand.value = brandId;
            }

            if(type === 'model'){
                const mobileModel = document.getElementById('mobile-model');
                if(mobileModel) mobileModel.value = value;
            }

            const activeCategory = document.querySelector('.nav-item.active');
            if (!activeCategory) return;

            const categoryId = activeCategory.dataset.categoryId;

            const selectedBrandId = document.getElementById('brand-label').dataset.id || 0;

            const model =
                type === 'model' ? value :
                document.getElementById('model-label').innerText === "All" ? "" :
                document.getElementById('model-label').innerText;

            /* Desktop Search */
            const desktopSearch = document.getElementById('product-search')?.value || "";

            /* Mobile Search */
            const mobileSearch = document.getElementById('mobile-search-input')?.value || "";

            const search = desktopSearch || mobileSearch;

            console.log("called before selectoption");
            const sort = filterState.sort || "";
            loadProducts(categoryId, selectedBrandId, model, search, sort);

            if (type === 'brand') {
                document.getElementById('model-label').innerText = "All";
                loadModels(brandId, categoryId);
            }

            const menu = document.getElementById(`${type}-menu`);
            if(menu) menu.classList.add('hidden');
        }

        // product search text
        document.getElementById('product-search').addEventListener('keyup', function () {
            const search = this.value;
            const brandId = document.getElementById('brand-label').dataset.id || 0;
            const activeCategory = document.querySelector('.nav-item.active');

            if (!activeCategory) return;

            const categoryId = activeCategory.dataset.categoryId;

            const model = document.getElementById('model-label').innerText === "All"
                ? ""
                : document.getElementById('model-label').innerText;

            console.log("called when keyup in product search box");
            const sort = filterState.sort || "";
            loadProducts(categoryId, brandId, model, search, sort);
        });

        function loadModels(brandId, categoryId) {
            if(brandId == 0) {
                document.getElementById('model-menu').innerHTML = '';
                return;
            }

            fetch(`/builder/models?brand_id=${brandId}&category_id=${categoryId}`)
                .then(res => res.json())
                .then(models => {

                    let html = `<a href="javascript:void(0)" 
                        onclick="selectOption('model', 'All')"
                        class="block px-4 py-3 text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg text-sm">
                        All Models
                    </a>`;

                    models.forEach(model => {
                        html += `
                            <a href="javascript:void(0)" 
                            onclick="selectOption('model', '${model}', '${brandId}')"
                            class="block px-4 py-3 text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg text-sm">
                                ${model}
                            </a>`;
                    });

                    document.getElementById('model-menu').innerHTML = html;
                });
        }

        document.querySelectorAll('.sort-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function(){

                // Uncheck all others
                document.querySelectorAll('.sort-checkbox').forEach(cb => {
                    if(cb !== this) cb.checked = false;
                });

                const sort = this.checked ? this.dataset.sort : ''; // '' if unchecked

                const brandId = document.getElementById('brand-label').dataset.id || '';
                const activeCategory = document.querySelector('.nav-item.active');
                
                /* Desktop Search */
                const desktopSearch = document.getElementById('product-search')?.value || "";

                /* Mobile Search */
                const mobileSearch = document.getElementById('mobile-search-input')?.value || "";

                const search = desktopSearch || mobileSearch;

                const model = document.getElementById('model-label').innerText === "All" ? "" :
                    document.getElementById('model-label').innerText;

                if(activeCategory){
                    const categoryId = activeCategory.dataset.categoryId;
                    console.log("called when sorting option changed");
                    filterState.sort = sort;
                    loadProducts(categoryId, brandId, model, search, sort);
                }
            });
        });

        // Close if clicked outside
        window.onclick = function(event) {
            if (!event.target.closest('.relative')) {
                const menus = ['brand-menu', 'model-menu', 'mega-menu'];
                menus.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.classList.add('hidden');
                });
            }
        }

    /******end filter related functions****/


    // if the user has already selected some products under the given category, show those products with check icon.
    function viewSelectedPcBuildProducts() {   
        if (!buildData) return;

        // Remove all check-icon first.
        document.querySelectorAll('.nav-item .check-icon').forEach(icon => {
            icon.classList.add('hidden');
        });

        Object.keys(buildData).forEach(categoryId => {
            // Display check icon for selected categories.
            const categoryElement = document.querySelector(
                `.nav-item[data-category-id="${categoryId}"]`
            );
            if (categoryElement) {
                categoryElement.querySelector('.check-icon').classList.remove('hidden');
            }

            buildData[categoryId].forEach(product => {
                const productElement = document.querySelector(
                    `.product-card[data-product-id="${product.product_id}"][data-variant-id="${product.variant_id}"]`
                );

                if (productElement) {
                    const selectBtn = productElement.querySelector('.action-btn');
                    const counter = productElement.querySelector('.counter-wrapper');
                    const qtyInput = counter.querySelector('.qty-input');
                    const iconWrapper = productElement.querySelector('.icon-wrapper');

                    if (product.quantity > 0) {
                        selectBtn.classList.add('hidden');
                        counter.classList.remove('hidden');
                        counter.classList.add('flex');
                        qtyInput.value = product.quantity;
                    }

                    iconWrapper.innerHTML = (product.quantity > 1) ? minusIcon : trashIcon;
                }

            });
        });
    }

    // Get all navigation items
    const navItems = document.querySelectorAll('.nav-item');

    // Set initial active state for the first item
    if (navItems.length > 0) {
        navItems[0].classList.add('active');
        const categoryName = navItems[0].dataset.categoryName;
        document.querySelector('.category-heading').innerText = categoryName;
        document.querySelector('.category-sub-heading').innerText = categoryName;
    }

    // Load products when clicking on category
    function loadProducts(categoryId, brandId, model = "", search = "", sort = "") {
        const productsList = document.getElementById('products-list');
        const loader = document.getElementById('products-loader');

        productsList.innerHTML = '';
        loader.classList.remove('hidden');

        const url = `/buildyourpc/products/${categoryId}/${brandId}/${model}`
            + `?search=${encodeURIComponent(search)}&sort=${encodeURIComponent(sort)}`;
            console.log("Fetching products with URL:", url);

        fetch(url)
            .then(res => res.json())
            .then(data => {

                loader.classList.add('hidden');

                if (!data.html || data.html.trim() === '') {
                    productsList.innerHTML = `
                        <div class="text-center text-gray-400 py-10">
                            No Products Found
                        </div>
                    `;
                    return;
                }

                productsList.innerHTML = data.html;
                viewSelectedPcBuildProducts();
            });
    }

    // category click event
    navItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            
            const clickedCategoryId = this.dataset.categoryId;
            const activeItem = document.querySelector('.nav-item.active');
            const activeCategoryId = activeItem ? activeItem.dataset.categoryId : null;

            const categories = getCategories();

            const currentIndex = getActiveCategoryIndex();
            const clickedIndex = Array.from(categories).indexOf(this);

            const currentCategory = categories[currentIndex];
            const categoryId = currentCategory.dataset.categoryId;
            const categoryName = currentCategory.dataset.categoryName;

            // moving forward → validate
            if (clickedIndex > currentIndex) {
                if (!validateMin(categoryId, categoryName)) {
                    return;
                }
            }

            // moving backward → show warning only
            if (clickedIndex < currentIndex) {
                showCategoryInfo(categoryId);
            }

            // reset filters ONLY after validation passes
            if (activeCategoryId !== clickedCategoryId) {
                document.getElementById('reset-filters').click();
                // reset sort state
                filterState.sort = "";
            }

            // switch category
            categories.forEach(n => n.classList.remove('active'));
            this.classList.add('active');

            document.querySelector('.category-heading').innerText = this.dataset.categoryName;
            document.querySelector('.category-sub-heading').innerText = this.dataset.categoryName;

            console.log("called when click on a category");
            const model = document.getElementById('model-label').innerText === "All" ? "" :
                    document.getElementById('model-label').innerText;

            const sort = filterState.sort || "";
            

            /* Desktop Search */
            const desktopSearch = document.getElementById('product-search')?.value || "";

            /* Mobile Search */
            const mobileSearch = document.getElementById('mobile-search-input')?.value || "";

            const search = desktopSearch || mobileSearch;

            loadProducts(
                this.dataset.categoryId,
                document.getElementById('brand-label').dataset.id,
                model, search, sort
            );

            updateNavButtons();
            checkAllCategoriesCompleted();
        });
    });

    // handle next and previous step buttons
    function handleNextStep() {
        const categories = getCategories();
        const index = getActiveCategoryIndex();

        const currentCategory = categories[index];
        const categoryId = currentCategory.dataset.categoryId;
        const categoryName = currentCategory.dataset.categoryName;

        if (!validateMin(categoryId, categoryName)) {
            return;
        }

        if (index < categories.length - 1) {
            setActiveCategory(index + 1);
        }
    }

    function handlePrevStep() {
        const index = getActiveCategoryIndex();
        if (index > 0) {
            setActiveCategory(index - 1);
        }
    }

    // validate minimum selection for the category before moving to the next category
    function validateMin(categoryId, categoryName) {
        const categoryEl = document.querySelector(
            `.nav-item[data-category-id="${categoryId}"]`
        );

        const minSelect = parseInt(categoryEl?.dataset.minSelect || 0);

        // const selectedCount = buildData[categoryId] ? buildData[categoryId].length : 0;
        const selectedCount = buildData[categoryId]
            ? buildData[categoryId].reduce((sum, item) => {
                return sum + (parseInt(item.quantity) || 0);
            }, 0)
            : 0;

        if (minSelect && selectedCount < minSelect) {
            showWarning(`Please select at least ${minSelect} product(s) in ${categoryName}`);
            return false;
        }

        return true;
    }

    // validate maximum selection for the category before selecting a product
    function canSelectProduct() {
        const activeCategoryEl = document.querySelector('.nav-item.active');
        const categoryId = activeCategoryEl.dataset.categoryId;
        const categoryName = activeCategoryEl.dataset.categoryName;
        const maxSelect = activeCategoryEl.dataset.maxSelect;
        
        if (!maxSelect) return true;
        
        // const selectedCount = buildData[categoryId] ? buildData[categoryId].length + 1 : 0; // add 1 because we are checking before adding the product to the buildData

        const selectedCount = buildData[categoryId]
            ? buildData[categoryId].reduce((sum, item) => {
                return sum + (parseInt(item.quantity) || 0);
            }, 0)
            : 0;


        // block only if already reached max
        if (selectedCount >= maxSelect) {
            showWarning(`You can select only ${maxSelect} product(s) in ${categoryName}`);
            return false;
        }
        return true;
    }


    // update navigation buttons
    function updateNavButtons() {
        const categories = getCategories();
        const index = getActiveCategoryIndex();
        const prevBtn = document.getElementById('prev-btn');

        // Hide previous on first
        if (index === 0) {
            prevBtn.classList.add('hidden');
        } else {
            prevBtn.classList.remove('hidden');
        }
    }

    // show category selection info when user tries to go back without fulfilling the minimum requirement
    function showCategoryInfo(categoryId){
        const categoryEl = document.querySelector(
            `.nav-item[data-category-id="${categoryId}"]`
        );

        const min = categoryEl.dataset.minSelect;
        const max = categoryEl.dataset.maxSelect;
        const name = categoryEl.dataset.categoryName;

        // if(min && max){
        //     toastr.info(`Select ${min} - ${max} products in ${name}`);
        // }
        // else if(min){
        //     toastr.info(`Select at least ${min} products in ${name}`);
        // }
    }

    // get active category index
    function getActiveCategoryIndex() {
        const categories = getCategories();
        for (let i = 0; i < categories.length; i++) {
            if (categories[i].classList.contains('active')) {
                return i;
            }
        }
        return 0;
    }

    // Get category
    function getCategories() {
        return document.querySelectorAll('.nav-item');
    }

    // function to set active category by index (used in next and previous buttons)
    function setActiveCategory(index) {
        const categories = getCategories();
        categories.forEach(c => c.classList.remove('active'));

        if (categories[index]) {
            categories[index].classList.add('active');
            categories[index].click();
            const categoryName = categories[index].dataset.categoryName;
            document.querySelector('.category-heading').innerText = categoryName;
            document.querySelector('.category-sub-heading').innerText = categoryName;
        }

        updateNavButtons();
    }

    // Function to show warnings
    let isAlertVisible = false;

    function showWarning(message){
        if(isAlertVisible) return;

        isAlertVisible = true;

        toastr.warning(message);

        setTimeout(()=>{
            isAlertVisible = false;
        },1000);
    }

    // function to view product details
    function viewProductDetails(stockId) {
        // 1. Logic to populate data (Same as your previous script)
        document.getElementById('default-view').classList.add('hidden');
        document.getElementById('details-view').classList.remove('hidden');

        // 2. Mobile Modal Trigger
        if (window.innerWidth < 1024) { // Only run on mobile
            const sidebar = document.getElementById('details-sidebar');
            const overlay = document.getElementById('details-overlay');

            // Show Overlay
            overlay.classList.replace('opacity-0', 'opacity-100');
            overlay.classList.replace('pointer-events-none', 'pointer-events-auto');

            // Slide Sidebar Up
            sidebar.classList.remove('translate-y-full', 'opacity-0', 'pointer-events-none');
            sidebar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

            document.body.style.overflow = 'hidden';
        }

        fetch(`/buildyourpc/products/details/${stockId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('details-view').innerHTML = data.html;
            });
    }

    // Select product from the list and add to the build
    function selectProduct(buttonElement, qty = 1) {
        const categoryId = buttonElement.dataset.categoryId;
        const categoryName = buttonElement.closest('.product-card')
            .querySelector('.category-name')?.innerText || 'this category';

        if (!canSelectProduct()) {
            return;
        }

        const container = buttonElement.closest('.counter-container');
        const counter = container.querySelector('.counter-wrapper');

        const productId = buttonElement.dataset.productId;
        const variantId = buttonElement.dataset.stockId;
        const builderId = document.getElementById('pcBuilderId').value;

        buttonElement.classList.add('hidden');
        counter.classList.add('flex');
        counter.classList.remove('hidden');

        savePcBuilder(productId, variantId, categoryId, builderId, qty);
        updateAfterRemove(categoryId);
        getBuildItemTotal();
    }

    // save the selected products to the database and update the build summary
    function savePcBuilder(productId, variantId, categoryId, builderId, qty, callback = null){
        fetch(`/buildyourpc/savePcBuilder?productId=${productId}&variantId=${variantId}&categoryId=${categoryId}&builder_id=${builderId}&qty=${qty}`)
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    buildData = data.build_data;
                    if (!builderId && data.builder_id) {
                        document.getElementById('pcBuilderId').value = data.builder_id;
                    }
                    viewSelectedPcBuildProducts();
                    checkAllCategoriesCompleted();

                    if (callback) callback(data.build_data);
                } else {
                    console.error("Failed to save product");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

    
    function checkAllCategoriesCompleted() {
        let allCompleted = true;
        document.querySelectorAll('.nav-item').forEach(category => {
            const categoryId = category.dataset.categoryId;
            const minSelect = parseInt(category.dataset.minSelect || 0);
            // const selectedCount = buildData[categoryId] ? buildData[categoryId].length : 0; // no need to add 1 here because we are checking the buildData which is already updated after selection

            const selectedCount = buildData[categoryId]
                ? buildData[categoryId].reduce((sum, item) => {
                    return sum + (parseInt(item.quantity) || 0);
                }, 0)
                : 0;
            if (selectedCount < minSelect) {
                allCompleted = false;
            }
        });

        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const proceedBtn = document.getElementById('proceed-to-order-btn');

        if (allCompleted) {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
            proceedBtn.classList.remove('hidden');
        } else {
            proceedBtn.classList.add('hidden');
            updateNavButtons(); // handle prev/next normally
        }
    }

    function updateAfterRemove(categoryId) {
        checkAllCategoriesCompleted();
    }

    function validateAllCategories(){
        let valid = true;
        document.querySelectorAll('.nav-item').forEach(category => {
            const categoryId = category.dataset.categoryId;
            const categoryName = category.dataset.categoryName;
            const minSelect = parseInt(category.dataset.minSelect || 0);
            // const selectedCount = buildData[categoryId] ? buildData[categoryId].length : 0; // no need to add 1 here because we are checking the buildData which is already updated after selection

            const selectedCount = buildData[categoryId]
                ? buildData[categoryId].reduce((sum, item) => {
                    return sum + (parseInt(item.quantity) || 0);
                }, 0)
                : 0;
            
            if (selectedCount < minSelect) {
                showWarning(`Please complete ${categoryName}`);
                valid = false;
            }
        });

        return valid;
    }

    document.addEventListener('DOMContentLoaded', function () {
        viewSelectedPcBuildProducts();
        setTimeout(() => {
            const isValid = validateMinSelection();
            if (isValid) {
                proceedToOrder();
            } else {
                backToConfiguration();
            }
            // updateCategoryCheckIcons();
            checkAllCategoriesCompleted();
            updateNavButtons();

        }, 300);
    });

     // Validate min selected products for each category.
    function validateMinSelection() {
        let valid = true;
        document.querySelectorAll('.nav-item').forEach(category => {
            const categoryId = category.dataset.categoryId;
            const minLimit = parseInt(category.dataset.minSelect);
            // const selectedCount = buildData[categoryId] ? buildData[categoryId].length : 0; // no need to add 1 here because we are checking the buildData which is already updated after selection

            const selectedCount = buildData[categoryId]
                ? buildData[categoryId].reduce((sum, item) => {
                    return sum + (parseInt(item.quantity) || 0);
                }, 0)
                : 0;

            if (selectedCount < minLimit) {
                valid = false;
            }

        });
        return valid;
    }

    // Proceed to order function
    function proceedToOrder() {
        fetch('/buildyourpc/getBuildData')
            .then(res => res.json())
            .then(data => {
                console.log(data);
                document.getElementById('products-list-page').classList.add('hidden');
                document.getElementById('products-review-page').classList.remove('hidden');
                document.getElementById('products-review-page').innerHTML = data.html;

                document.getElementById('details-sidebar').classList.add('hidden');
                document.getElementById('summary-sidebar').classList.remove('hidden');
                document.querySelectorAll('.nav-item').forEach(cat=>{
                    cat.classList.add('pointer-events-none');
                    cat.classList.remove('active');
                }); // disable category navigation in review page

                
                document.getElementById('summary-total-price').innerText =
                    parseFloat(data.total_price).toLocaleString('en-AE', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                document.getElementById('summary-total-discount-price').innerText = parseFloat(data.total_discount).toLocaleString('en-AE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                document.getElementById('total-tax').innerText = parseFloat(data.total_tax).toLocaleString('en-AE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                document.getElementById('total_price_with_tax').innerText = parseFloat(data.total_with_tax).toLocaleString('en-AE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                document.getElementById('total_price_with_tax_left').innerText = parseFloat(data.total_with_tax).toLocaleString('en-AE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            });
    }

    // Back to configuration function
    function backToConfiguration() {
        document.getElementById('products-review-page').classList.add('hidden');
        document.getElementById('products-list-page').classList.remove('hidden');
        document.getElementById('summary-sidebar').classList.add('hidden');
        document.getElementById('details-sidebar').classList.remove('hidden');

        document.querySelectorAll('.nav-item').forEach(cat=>{
            cat.classList.remove('pointer-events-none');
            cat.classList.remove('active');
        }); // enable category navigation in list page

        // make first item active
        if (navItems.length > 0) {
            navItems[0].classList.add('active');
        }
                
    }

    // edit in review page
    function editBuilderCategory(element){
        backToConfiguration();
        let categoryId = element.dataset.categoryId;
        let selectedCategory =  document.querySelector(`.nav-item[data-category-id="${categoryId}"]`);

        if(selectedCategory){
            // remove active from all categories
            document.querySelectorAll('.nav-item').forEach(cat=>{
                cat.classList.remove('active');
            });

            // activate selected
            selectedCategory.classList.add('active');

            // trigger the category click event
            selectedCategory.click();
        }
    }

    function placeYourOrder(){
        const builderId = document.getElementById('pcBuilderId').value;
        fetch(`/buildyourpc/place-order?builder_id=${builderId}`)
        .then(response => response.json())
        .then(data => {

            if(data.status){
                window.location.href = "/cart";
            }else{
                toastr.error(data.message);
            }

        })
        .catch(error => {
            console.error(error);
        });

    }

    function resetConfiguration() {
        let builderId = document.getElementById('pcBuilderId').value;

        if (!builderId) {
            toastr.error('Builder not found');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to reset this configuration?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reset it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: "{{ route('pc.builder.reset') }}",
                type: "POST",
                data: {
                    builder_id: builderId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        location.reload();

                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('Something went wrong');
                }
            });
        });
    }

    document.getElementById('reset-filters').addEventListener('click', function () {
        // Reset Brand
        const brandLabel = document.getElementById('brand-label');
        brandLabel.innerText = 'All Brands';
        brandLabel.dataset.id = 0;

        // Reset Model
        const modelLabel = document.getElementById('model-label');
        modelLabel.innerText = 'All';

        // Reset Search
        document.getElementById('product-search').value = '';

        // Reset Sort
        document.querySelectorAll('.sort-checkbox').forEach(cb => {
            cb.checked = false;
        });

        // Reload models dropdown
        const activeCategory = document.querySelector('.nav-item.active');

        if (activeCategory) {
            const categoryId = activeCategory.dataset.categoryId;

            // reload model dropdown
            loadModels(0, categoryId);

            // reload products
            console.log("called when reset filters");
            loadProducts(categoryId, 0, "", "", "");
        }
    });

    function toggleNavigationButtons(currentIndex, totalTabs) {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        // Hide Previous on first category
        if (currentIndex === 0) {
            prevBtn.classList.add('hidden');
        } else {
            prevBtn.classList.remove('hidden');
        }

        // Hide Next on last category (optional)
        if (currentIndex === totalTabs - 1) {
            nextBtn.innerText = 'Finish';
        } else {
            nextBtn.innerText = 'Next';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.nav-item');
        toggleNavigationButtons(0, tabs.length);
    });

    function showDefaultView() {
        // 1. Reset Views
        document.getElementById('details-view').classList.add('hidden');
        document.getElementById('default-view').classList.remove('hidden');

        // 2. Mobile Modal Close
        if (window.innerWidth < 1024) {
            const sidebar = document.getElementById('details-sidebar');
            const overlay = document.getElementById('details-overlay');

            // Hide Overlay
            overlay.classList.replace('opacity-100', 'opacity-0');
            overlay.classList.replace('pointer-events-auto', 'pointer-events-none');

            // Slide Sidebar Down
            sidebar.classList.add('translate-y-full', 'opacity-0', 'pointer-events-none');
            sidebar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');

            document.body.style.overflow = 'auto'; // Restore scroll
        }
    }


    // function updateBuilderItemQty(buttonElement, change, source = 'list') {

    //     const container = buttonElement.closest('.counter-container');
    //     const input = container.querySelector('.qty-input');
    //     const iconWrapper = container.querySelector('.icon-wrapper');
    //     const counterWrapper = container.querySelector('.counter-wrapper');
    //     const actionBtn = container.querySelector('.action-btn');

    //     let currentVal = parseInt(input.value) || 0;
    //     let newVal = currentVal + change;

    //     const productId = container.dataset.productId;
    //     const variantId = container.dataset.stockId;
    //     const categoryId = container.dataset.categoryId;
    //     const builderId = document.getElementById('pcBuilderId').value;

    //     const reviewItem = document.querySelector(`.review-item[data-product-id="${productId}"][data-stock-id="${variantId}"]`);

    //     // check min and max limits allowed for that category
    //     const categoryElement = document.querySelector(`.nav-item[data-category-id="${categoryId}"]`);

    //     const maxLimit = categoryElement.dataset.maxSelect 
    //         ? parseInt(categoryElement.dataset.maxSelect) 
    //         : 0;

    //     const minLimit = parseInt(categoryElement.dataset.minSelect || 0);
    //     const categoryName = categoryElement.dataset.categoryName;

    //     // current total qty
    //     const currentTotal = (buildData?.[categoryId] || [])
    //         .reduce((sum, item) => sum + +item.quantity, 0);


    //     // simulate next total
    //     const nextTotal = currentTotal + change;

    //     // MAX check (increment)
    //     if (change > 0 && maxLimit > 0 && nextTotal > maxLimit) {
    //         toastr.warning(`Maximum ${maxLimit} items allowed in ${categoryName}`);
    //         return;
    //     }

    //     // MIN check (decrement / delete)
    //     if (change < 0 && nextTotal < minLimit) {
    //         toastr.warning(`Minimum ${minLimit} items required in ${categoryName}`);
    //         return;
    //     }


    //     // check stock quantity
    //     const stockQty = parseInt(container.dataset.stockQty) || 0;
    //     const cartQty = parseInt(container.dataset.cartQty) || 0;

    //     // Available qty user can select
    //     const availableQty = stockQty - cartQty;

    //     // Stop if exceeding stock
    //     if (newVal > availableQty) {
    //         toastr.warning(`Only ${availableQty} item(s) available`);
    //         return;
    //     }

    //     if (newVal < 0) return;

    //     if (newVal === 0) {

    //         Swal.fire({
    //             title: 'Remove Item?',
    //             text: 'Are you sure you want to remove this item?',
    //             icon: 'warning',
    //             showCancelButton: true,
    //             confirmButtonColor: '#d33',
    //             cancelButtonColor: '#6c757d',
    //             confirmButtonText: 'Yes, remove it'
    //         }).then((result) => {

    //             if (!result.isConfirmed) return;

    //             savePcBuilder(productId, variantId, categoryId, builderId, 0, function (freshBuildData) {

    //                 if(source === 'review'){
    //                     const categoryElement = document.querySelector(`.nav-item[data-category-id="${categoryId}"]`);

    //                     if (!categoryElement) return;

    //                     const minLimit = parseInt(categoryElement.dataset.minSelect || 0);
    //                     // const currentCount = freshBuildData?.[categoryId]?.length || 0;
    //                     const currentCount = (freshBuildData?.[categoryId] || [])
    //                         .reduce((sum, item) => sum + +item.quantity, 0);
    //                     const categoryName = categoryElement.dataset.categoryName;

    //                     if (currentCount < minLimit) {
    //                         toastr.warning(`Minimum ${minLimit} product(s) required in ${categoryName}`);
    //                         editBuilderCategory(categoryElement);
    //                         updateNavButtons();
    //                     }
    //                     // remove that item div here in review page
                        
    //                     if (reviewItem) {
    //                         reviewItem.remove();
    //                     }
    //                 } else{
    //                     // UI reset
    //                     input.value = 0;
    //                     counterWrapper.classList.add('hidden');
    //                     counterWrapper.classList.remove('flex');
    //                     actionBtn.classList.remove('hidden');
    //                 }
    //             });   
                    
    //         });

    //         return;
    //     }

    //     // update UI immediately (optimistic)
    //     input.value = newVal;
    //     iconWrapper.innerHTML = (newVal > 1) ? minusIcon : trashIcon;
    //     if(reviewItem){
    //         reviewItem.querySelector('.review-product-price').innerText = formatNumberUAE((newVal * parseFloat(reviewItem.querySelector('.review-item-offer-price').value))) ;
    //     }

    //     savePcBuilder(productId, variantId, categoryId, builderId, newVal);
    // }

    function updateBuilderItemQty(buttonElement, change, source = 'list') {

        const container = buttonElement.closest('.counter-container');
        const input = container.querySelector('.qty-input');
        const iconWrapper = container.querySelector('.icon-wrapper');
        const counterWrapper = container.querySelector('.counter-wrapper');
        const actionBtn = container.querySelector('.action-btn');

        const productId = container.dataset.productId;
        const variantId = container.dataset.stockId;
        const categoryId = container.dataset.categoryId;
        const builderId = document.getElementById('pcBuilderId').value;

        const reviewItem = document.querySelector(
            `.review-item[data-product-id="${productId}"][data-stock-id="${variantId}"]`
        );

        const categoryElement = document.querySelector(`.nav-item[data-category-id="${categoryId}"]`);
        if (!categoryElement) return;

        const categoryName = categoryElement.dataset.categoryName;

        const maxLimit = parseInt(categoryElement.dataset.maxSelect) || Infinity;
        const minLimit = parseInt(categoryElement.dataset.minSelect) || 0;

        const currentVal = parseInt(input.value) || 0;
        const newVal = currentVal + change;

        if (newVal < 0) return;

        // ---------- current total qty in category ----------
        const currentTotal = (buildData?.[categoryId] || [])
            .reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0);

        // IMPORTANT: remove old qty of this product before applying new one
        const currentItemQty = (buildData?.[categoryId] || [])
            .find(i => i.product_id == productId && i.variant_id == variantId)?.quantity || 0;

        const nextTotal = currentTotal - currentItemQty + newVal;

        // ---------- MAX validation ----------
        if (change > 0 && nextTotal > maxLimit) {
            toastr.warning(`Maximum ${maxLimit} items allowed in ${categoryName}`);
            return;
        }

        // ---------- MIN validation ----------
        if (change < 0 && nextTotal < minLimit) {
            toastr.warning(`Minimum ${minLimit} items required in ${categoryName}`);
            return;
        }

        // ---------- stock validation ----------
        const stockQty = parseInt(container.dataset.stockQty) || 0;
        const cartQty = parseInt(container.dataset.cartQty) || 0;
        const availableQty = stockQty - cartQty;

        if (newVal > availableQty) {
            toastr.warning(`Only ${availableQty} item(s) available`);
            return;
        }

        // ---------- DELETE ----------
        if (newVal === 0) {

            Swal.fire({
                title: 'Remove Item?',
                text: 'Are you sure you want to remove this item?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it'
            }).then((result) => {

                if (!result.isConfirmed) return;

                savePcBuilder(productId, variantId, categoryId, builderId, 0, function(freshBuildData) {

                    buildData = freshBuildData; // IMPORTANT sync

                    if (reviewItem) reviewItem.remove();

                    input.value = 0;
                    counterWrapper.classList.add('hidden');
                    counterWrapper.classList.remove('flex');
                    actionBtn.classList.remove('hidden');

                    updateNavButtons();
                    getBuildItemTotal();
                });

            });

            return;
        }

        // ---------- UI update ----------
        input.value = newVal;
        iconWrapper.innerHTML = (newVal > 1) ? minusIcon : trashIcon;

        if (reviewItem) {
            const price = parseFloat(reviewItem.querySelector('.review-item-offer-price').value) || 0;
            reviewItem.querySelector('.review-product-price').innerText =
                formatNumberUAE(newVal * price);
        }

        // ---------- persist ----------
        savePcBuilder(productId, variantId, categoryId, builderId, newVal, function (freshBuildData) {
            buildData = freshBuildData; // keep synced
            updateNavButtons();
        });
        getBuildItemTotal();
    }

    function onCategoryClick(navItem) {

        // reset filters every time category changes
        document.getElementById('reset-filters').click();

        let categoryId = navItem.dataset.categoryId;

        // your existing logic
        loadProducts(categoryId);
    }
    
    function formatNumberUAE(value) {
        return Number(value).toLocaleString('en-AE');
    }

    function getBuildItemTotal() {
        fetch('/buildyourpc/getBuildData')
            .then(res => res.json())
            .then(data => {
                console.log(data);
                document.getElementById('total_price_with_tax_left').innerText = parseFloat(data.total_with_tax).toLocaleString('en-AE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            })
    }
</script>
@endsection