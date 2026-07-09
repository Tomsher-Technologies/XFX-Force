<div class="relative lg:sticky top-0 bg-[#1C2228] border border-[#1E2529] p-[15px] md:p-[30px] rounded-[20px] flex flex-col justify-between group transition-all gap-[30px] z-[1]">
    <div class="flex flex-col md:flex-row justify-between w-full gap-[15px] md:gap-[0px]">
        <div class="flex flex-col gap-[5px] w-full">
            <h2 class="text-white text-[20px] font-medium">Complete Your Order</h2>
            <p class="text-gray-500 text-[15px]">Review your build and enter shipping details</p>
        </div>
        <div class="w-full left-0 flex items-center justify-between md:justify-end mt-auto gap-[20px]">
            <button onclick="backToConfiguration()" class="flex w-full md:w-fit cursor-pointer items-center justify-center gap-3 px-6 py-4 rounded-xl border border-[#2E363E] text-gray-400 font-medium uppercase text-[13px] hover:bg-[#252C33] hover:text-white transition-all duration-300 group">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M-3.14713e-05 6.707C-0.000488758 6.44439 0.0509577 6.18428 0.151352 5.94162C0.251746 5.69896 0.399108 5.47853 0.584969 5.293L5.87897 0L7.29297 1.414L1.99997 6.707L7.29297 12L5.87897 13.414L0.585969 8.121C0.399928 7.93556 0.252382 7.71516 0.151814 7.4725C0.0512471 7.22983 -0.000357628 6.96968 -3.14713e-05 6.707Z" fill="white" />
                </svg>
                <span>Back to Configuration</span>
            </button>
        </div>
    </div>
</div>
<div class="p-[30px] min-w-0 overflow-x-hidden relative z-[0] bg-[#1C2228] rounded-[20px] mt-[15px]">
    <div class="flex flex-col gap-[5px] w-full">
        <h2 class="text-white text-[20px] font-medium">Selected Items</h2>
        <p class="text-gray-500 text-[15px]">Review your selected items</p>
    </div>
    <div class="w-full flex flex-col mt-[30px]" id="review_block">


        @if(!empty($reviewProducts))

        @foreach($reviewProducts as $item)
        @php
        $image = asset('assets/img/placeholder.jpg'); // default placeholder

        if (!empty($item['image'])) {
            // If multiple images, take the first one
            $images = explode(',', $item['image']);
            $firstImage = trim($images[0]);
            if ($firstImage) {
                $image = Storage::url($firstImage);
            }
        }
        @endphp
        <div class="grid grid-cols-12 gap-[20px] md:gap-[30px] items-center border-t border-[#2E363E] py-[20px] review-item" data-product-id="{{ $item['product_id'] }}" data-stock-id="{{ $item['variant_id'] }}" data-category-id="{{ $item['category_id'] }}"  >
            <div class="col-span-12 md:col-span-5 flex flex-row gap-[20px] items-center">
                <div>

                    @php
                        $productId = $item['product_id'] ?? null;
                        $variantId = $item['variant_id'] ?? null;

                        // default page link
                        $pagelink = '#';

                        if ($productId && $variantId) {
                            // Fetch product with stocks
                            $product = \App\Models\Product::with('stocks')->find($productId);

                            if ($product) {
                                // Find the stock by given variant_id
                                $stock = $product->stocks->where('id', $variantId)->first();

                                if ($stock) {
                                    $pagelink = route('product.details', [
                                        'slug' => $product->slug,
                                        'sku'  => $stock->sku
                                    ]);
                                }
                            }
                        }
                    @endphp

                    <a href="{{ $pagelink }}" target="_blank" class="w-20 h-20 bg-white rounded-lg flex items-center justify-center">
                        <img src="{{ $image }}" alt="{{ $item['variant_name'] ?: $item['product_name'] }}" title="{{ $item['variant_name'] ?: $item['product_name'] }}" class="w-full">
                    </a>
                </div>
                <a href="{{ $pagelink }}" target="_blank">
                    <label class="text-[12px] uppercase text-gray-500 mb-[5px] block">{{ $item['category_name']?? '' }}</label>
                    <h4 class="text-white text-[15px] mb-[0px]">{{ $item['variant_name'] ?: $item['product_name'] }}</h4>
                </a>
            </div>
            <div class="col-span-6 md:col-span-3 counter-container review-product-item" data-product-id="{{ $item['product_id'] }}" data-stock-id="{{ $item['variant_id'] }}" data-category-id="{{ $item['category_id'] }}" data-stock-qty="{{ $stock->qty }}" data-cart-qty="{{ checkCartQuantityPerVariant($stock->id) }}">
                <div class="flex flex-row items-center gap-2 bg-[#0B0F13] border border-gray-800 rounded-xl p-1 w-full">
                    <button onclick="updateBuilderItemQty(this,-1,'review')" class="cursor-pointer minus-btn flex-1 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all active:scale-90">
                        <span class="icon-wrapper">
                            @if ($item['quantity'] > 1)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4" />
                            </svg>

                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            @endif

                        </span>
                    </button>

                    <input type="number" value="{{ $item['quantity'] }}" readonly class="qty-input w-12 h-10 text-center bg-[#282B34] text-white font-medium rounded-lg focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none border-none">

                    <button onclick="updateBuilderItemQty(this,1,'review')" class="cursor-pointer flex-1 h-10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] rounded-lg transition-all active:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="col-span-6 md:col-span-2 flex flex-col hidden">
                <div class="price w-full flex flex-row items-center gap-[15px]">
                    <h5 class="price flex flex-row text-white text-left text-[15px] m-[0] font-medium align-center items-center gap-[10px]">
                        <svg class="w-[15px]" width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff"></path>
                        </svg>
                        <input type="hidden" class="review-item-offer-price" value="{{ $item['offer_price'] }}">
                        <span class="">{{ number_format($item['offer_price'], 2) }}</span>
                    </h5>
                </div>
                @if(filled($item['offer_tag']))
                <h6 class="text-[12px] text-gray-500 mt-2 block flex flex-row align-center items-center">
                    <span class="text-[#898989] font-medium line-through">{{ number_format($item['price'], 2) }}</span>
                </h6>
                @endif
            </div>

            <div class="col-span-6 md:col-span-3 flex flex-col">
                <div class="price w-full flex flex-row items-center gap-[15px]">
                    <h5 class="price flex flex-row text-white text-left text-[15px] m-[0] font-medium align-center items-center gap-[10px]">
                        <svg class="w-[15px]" width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff"></path>
                        </svg>
                        <span class="review-product-price">{{ number_format($item['total_offer_price'], 2) }}</span>
                    </h5>
                </div>
            </div>

            <div class="col-span-12 md:col-span-1 flex justify-end w-full">
                <button onclick="editBuilderCategory(this)" data-category-id="{{ $item['category_id'] }}" class="flex w-full md:w-fit cursor-pointer items-center justify-center gap-3 px-4 py-4 rounded-xl border border-[#2E363E] text-gray-400 font-medium uppercase text-[13px] hover:bg-[#252C33] hover:text-white transition-all duration-300 group" fdprocessedid="x22w2b">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.06225 2.46955C7.44287 2.55401 8.58718 2.86026 9.38256 3.64338C10.1665 4.41446 10.4778 5.51582 10.5671 5.90217C9.98386 6.54506 9.27096 7.29742 8.40698 8.16096C4.82477 11.7404 3.39283 12.6795 3.33374 12.7176C3.25164 12.7705 3.15773 12.8017 3.0603 12.8094L0.636471 12.9979C0.621313 12.9996 0.606133 12.9998 0.591549 12.9998C0.434944 12.9998 0.283705 12.9368 0.172604 12.825C0.0503889 12.7019 -0.0112895 12.5318 0.00170553 12.3582L0.190182 9.93732C0.197865 9.8386 0.230155 9.74226 0.283932 9.659C0.325253 9.5957 1.26836 8.16301 4.84546 4.58869C5.69033 3.7444 6.42826 3.04514 7.06225 2.46955ZM10.3953 0.00763642C10.5012 0.0244213 11.4443 0.191172 12.1277 0.864082C12.8078 1.55404 12.9752 2.49891 12.9919 2.60529C13.0144 2.74743 12.9856 2.89353 12.9099 3.01545C12.8842 3.05747 12.5011 3.66234 11.4851 4.85725C11.2593 4.23886 10.8658 3.44182 10.2078 2.79377C9.55179 2.1479 8.74277 1.76131 8.11694 1.53889C9.33083 0.505953 9.94742 0.117453 9.99194 0.0896677C10.1119 0.0147327 10.2552 -0.0155577 10.3953 0.00763642Z" fill="white" />
                    </svg>
                    <!-- <span>Edit</span> -->
                </button>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>