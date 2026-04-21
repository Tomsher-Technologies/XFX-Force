<div class="product-card product-item w-full relative border-hidden rounded-[10px] overflow-hidden bg-[#1E2225] flex flex-col items-start justify-start transition-all duration-[600ms]">
        
        @php
            // Get product object
            $product = \App\Models\Product::with('stocks')->find($prodData['product_id']);

            // Get the stock by the given stock_id
            $stock = $product
                ? $product->stocks->where('id', $prodData['stock_id'])->first()
                : null;
        @endphp

        @include('frontend.partials.wishlist-icon', [
            'product' => $prodData['product_id'],
            'stock' => $prodData['stock_id'],
            'page' => $prodData['page'] ?? null,
        ])

        <a href="{{ $stock ? route('product.details', ['slug' => $product->slug, 'sku' => $stock->sku]) : '#' }}"
            class="product-img h-[180px] w-full relative z-[1] bg-white">
            <img src="{{ get_product_image($prodData['thumbnail_img'],'300') }}"
                class="absolute object-contain object-center w-full h-full" alt="{{ $prodData['name'] ?? '' }}"
                title="{{ $prodData['name'] ?? '' }}">
            @if (filled($prodData['offer_tag']))
                <badge class="absolute top-[20px] left-[20px] bg-[#077F09] text-white text-[10px] md:text-[12px] font-medium px-[15px] py-[5px] rounded-full capitalize">
                    {{ $prodData['offer_tag'] }}</badge>
            @endif
        </a>
        
        <div class="product-content w-full p-[15px] md:p-[20px] flex flex-col gap-[10px] md:gap-[15px] z-[1]">
            <h4 class="text-white text-[13px] md:text-[18px] leading-[20px] md:leading-[25px] font-medium line-clamp-2 min-h-[40px] cursor-pointer" onclick="window.location= '{{ $stock ? route('product.details', ['slug' => $product->slug, 'sku' => $stock->sku]) : '#' }}'">
                {{ $prodData['name'] ?? '' }}</h4>

            
            @php
                $approvedReviews = $product->reviews->where('status', 1);
                $rating = round(($approvedReviews->avg('rating') ?? 0) * 2) / 2;
                $totalReviews = $approvedReviews->count();

                $fullStars = floor($rating);
                $halfStar = ($rating - $fullStars) == 0.5;
                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
            @endphp

            <div class="flex items-center gap-[8px] -mt-2">
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
            </div>
            

            <h5 class="price flex flex-row text-[#2A7CFF] text-[13px] md:text-[18px] leading-[20px] m-[0] font-bold align-center items-center gap-[5px]">
                <svg width="15px" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 13"><path d="M1.3 0v.1q.5.4.6 1.3v3.2H.7L0 4.2v.7l.3.5.6.4H2v1.3H.4l-.3-.2-.1-.1v.7q.3.7 1 1h1v1.3l-.1 1.9q-.2.8-.5 1.2H8a7 7 0 0 0 2.2-.9h.2l.3-.2.1-.1a5 5 0 0 0 2-3.2h1.8l.3.2q.1.3.1-.2v-.3l-.1-.3q-.3-.6-.7-.7H13V5.8h1.4q.3 0 .5.2l.1.1v-.8q-.3-.5-.7-.7l-.9-.1h-.6v-.3A6 6 0 0 0 10.2 1l-.1-.1-.7-.3q-1-.4-2-.5H1.2m5 .7 1.2.1a4 4 0 0 1 2.7 2.4l.3 1v.2l.1.1H3.9v-4h2.4m4.4 5.2V7H4V6h6.8m-.1 2.6-.1.5-.7 1.5a4 4 0 0 1-2 1.5l-1.3.3H4v-4h6.7" fill="#2a7cff"/></svg>
                
                <span style="margin-top:1px;">{{ $prodData['offer_price'] ?? $prodData['price'] }}</span>
                @if ($prodData['offer_price'] != $prodData['price'])
                    <span class="text-[#898989] font-medium line-through">{{ $prodData['price'] }}</span>
                @endif
            </h5>
            <a href="{{ $stock ? route('product.details', ['slug' => $product->slug, 'sku' => $stock->sku]) : '#' }}"
                class="prd-card-btn w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-[600ms] text-white hidden md:block">View Details</a>
        </div>

    </div>