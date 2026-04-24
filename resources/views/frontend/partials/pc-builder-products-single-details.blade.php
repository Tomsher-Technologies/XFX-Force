<div class="flex flex-row justify-between items-center p-[20px] md:p-[30px] border-b border-[#2E363E] flex-shrink-0">
    <div>
        <h2 class="text-white text-[20px] md:text-[25px] leading-[30px] font-medium">Details</h2>
        <span class="text-gray-500 text-[13px] md:text-[15px]">Component Specifications</span>
    </div>
    <button onclick="showDefaultView()" class="text-gray-500 hover:text-white transition-colors cursor-pointer bg-[#252B31] p-[10px] rounded-[10px] h-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<div class="flex-grow overflow-y-auto p-[20px] md:p-[30px] no-scrollbar">
    <div class="flex flex-col w-full">

            @php
                $image = asset('assets/img/placeholder.jpg'); // default placeholder

                if ($stock) {
                    // If stock has images
                    if (!empty($stock->image)) {
                        // Assume multiple images are comma-separated
                        $images = explode(',', $stock->image);
                        $firstImage = trim($images[0]);
                        if ($firstImage) {
                            $image = Storage::url($firstImage);
                        }
                    }
                    // Fallback to product thumbnail if no stock image
                    elseif (!empty($stock->product->thumbnail_img)) {
                        $image = Storage::url($stock->product->thumbnail_img);
                    }
                }
            @endphp
        <div class="bg-[#252B31] rounded-[20px] mb-6 flex justify-center items-center overflow-hidden border border-[#2E363E]">
            <img id="details-img" src="{{$image}}" alt="" title="" class="object-cover">
        </div>

        <h4 id="details-title" class="text-[20px] font-medium leading-[30px] text-white"> {{ $stock->title ?? $stock->product->name ?? '' }}</h4>
        
        <!--ratings-->
        @if($stock)
        @php
            $approvedReviews = $stock->product->reviews->where('status', 1);
            $rating = round(($approvedReviews->avg('rating') ?? 0) * 2) / 2;
            $totalReviews = $approvedReviews->count();

            $fullStars = floor($rating);
            $halfStar = ($rating - $fullStars) == 0.5;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        @endphp

        <a href="javascript:void(0)" class="flex items-center gap-[8px] -mt-2 cursor-default">
            <div class="flex items-center gap-[2px]">
                {{-- Full Stars --}}
                @for ($i = 0; $i < $fullStars; $i++)
                <svg class="w-3 h-3 md:w-4 md:h-4 text-[#FFB800] fill-current" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                @endfor

                {{-- Half Star --}}
                @if($halfStar)
                <div class="relative">
                    <svg class="w-3 h-3 md:w-4 md:h-4 text-gray-600 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>

                    <div class="absolute top-0 left-0 overflow-hidden w-[50%]">
                        <svg class="w-3 h-3 md:w-4 md:h-4 text-[#FFB800] fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                @endif


                {{-- Empty Stars --}}
                @for ($i = 0; $i < $emptyStars; $i++)
                <svg class="w-3 h-3 md:w-4 md:h-4 text-gray-600 fill-current" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                @endfor
            </div>

            <span class="text-white text-[12px] md:text-[14px] font-medium">
            {{ number_format($rating,1) }}
            </span>

            <span class="text-[#898989] text-[11px] md:text-[13px] font-medium">
            ({{ $totalReviews }} reviews)
            </span>
        </a>
        <!--//ratings-->
        @endif

        <div id="details-desc" class="text-gray-300 text-base mb-8 text-[15px]">
            @if($stock)
                {!! $stock->stock_description ?? $stock->product->description ?? '' !!}
            @endif
        </div>

        <div class="space-y-4 mb-10">
            <h5 class="text-[15px] text-white font-medium uppercase mb-5">Specifications</h5>

            @php
                $productSpecifications = \App\Models\ProductSpecification::where(
                'product_stock_id',
                $stock?->id
                )->with(['specification','specificationItem'])
                ->orderBy('sort_order')
                ->get();

                $specifications = $productSpecifications
                ->map(function ($ps) {
                if ($ps->specification && $ps->specificationItem) {
                return [
                'title' => $ps->specification->main_title,
                'value' => $ps->specificationItem->title,
                ];
                }
                })
                ->filter()
                ->values();
            @endphp
            @foreach ($specifications as $specification)
            <div class="flex justify-between items-center pb-3 border-b border-[#2E363E]">
                <span class="text-gray-400 text-[14px]">{{ $specification['title'] }}</span>
                <span id="spec-vram" class="text-white text-[14px] font-medium">{{ $specification['value'] }}</span>
            </div>
            @endforeach
        </div>
        @if($stock)
        <a href="{{ route('product.details', [$stock->product->slug, $stock->sku] ) }}" id="view-product-link" target="_blank" class="text-[15px] text-center px-4 py-3 rounded-[10px] text-gray-400 bg-[#252C33] hover:text-white transition-all duration-300">
            View Product Details
        </a>
        @endif
    </div>
</div>

<div class="flex flex-row justify-between items-center p-[20px] md:p-[30px] border-t border-[#2E363E] bg-[#1C2228] rounded-b-[20px]">
    <div class="w-full">
        <label class="text-[14px] text-gray-400 mb-[5px] block text-left">Price</label>
        <div class="price w-full flex flex-row items-end gap-[15px]">
            <h5 class="flex flex-row text-white text-left text-[20px] m-[0] font-medium items-center gap-[10px] leading-[35px]">
                <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff" />
                </svg>
                <span id="details-price" class="text-white text-left text-[20px] font-medium leading-[35px]">{{ number_format($stock?->offer_price, 2) }}</span>
            </h5>
        </div>
    </div>
    <div class="counter-container w-full block xl:hidden">
        <button onclick="selectProduct(this)" class="action-btn w-full text-center text-white uppercase text-[13px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] bg-transparent hover:bg-[#2A7CFF] hover:border-[#2A7CFF] transition-all duration-300">
            Select
        </button>

        <div class="counter-wrapper hidden items-center gap-2 bg-[#0B0F13] border border-gray-800 rounded-xl p-1 w-full">
            <button onclick="updateMultiQty(this, -1); event.stopPropagation();" class="minus-btn flex-1 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all active:scale-90">
                <span class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </span>
            </button>

            <input type="number" value="1" readonly class="qty-input w-12 h-10 text-center bg-[#282B34] text-white font-medium rounded-lg focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

            <button onclick="updateMultiQty(this, 1); event.stopPropagation();" class="flex-1 h-10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg transition-all active:scale-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    </div>
</div>