@foreach($products as $product)
@if($product->stocks->isNotEmpty())
@foreach($product->stocks as $stock)
@if($stock->qty > 0)
<div>
    <article onclick="viewProductDetails({{ $stock->id }})" class="group product-card w-full relative border border-transparent rounded-[20px] overflow-hidden bg-[#1E2225] flex flex-col items-start justify-start transition-all duration-300 cursor-pointer" data-product-id = "{{ $product->id }}" data-variant-id = "{{ $stock->id }}" data-category-id="{{ $product->category_id }}">
        <div class="group product-img h-[130px] md:h-[150px] w-full relative z-[1] bg-[#0B0F13] bg-gradient-to-t from-[#0B0F13] to-[#1E2225]" >
            @php
                $image = asset('assets/img/placeholder.jpg'); // default placeholder

                // If stock has images
                if (!empty($stock->image)) {
                    // Split by comma in case of multiple images and take the first one
                    $stockImages = explode(',', $stock->image);
                    $firstStockImage = trim($stockImages[0]);
                    if ($firstStockImage) {
                        $image = Storage::url($firstStockImage);
                    }
                }
                
                // If stock has no image, fallback to product thumbnail
                if (empty($stock->image) && !empty($product->thumbnail_img)) {
                    $image = Storage::url($product->thumbnail_img);
                }
            @endphp
            <img src="{{ $image }}" class="absolute object-contain object-center w-full h-full" alt="{{ $stock->stock_title ?? $product->name }}" title="{{ $stock->stock_title ?? $product->name }}">
            @if(filled($stock->offer_tag))
            <badge class="absolute top-[20px] left-[20px] bg-[#077F09] text-white text-[10px] md:text-[11px] font-medium px-[15px] py-[5px] rounded-full capitalize">
                {{$stock->offer_tag}}
            </badge>
            @endif
        </div>
        <div class="product-content p-[15px] md:p-[20px] flex flex-col gap-[10px] md:gap-[15px] z-[1] w-full">
            <h4 class="text-white text-[13px] md:text-[18px] leading-[20px] md:leading-[25px] font-medium line-clamp-2 h-[50px] cursor-pointer">
               {{ $stock->stock_title ?? $product->name }}
            </h4>

            <!--ratings-->
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
            <!--//ratings-->

            <h5 class="price flex flex-row text-[#2A7CFF] text-[13px] md:text-[15px] leading-[20px] m-[0] font-bold align-center items-center gap-[10px]">
                <img src="{{ asset('assets/images/aed.svg') }}" class="w-[15px] h-[15px]" alt="AED" title="Symbol of AED">{{ number_format($stock->offer_price, 2) }} 
                @if(filled($stock->offer_tag))
                <span class="text-[#898989] font-medium line-through">{{ number_format($stock->price, 2) }}</span>
                @endif
            </h5>
            <div class="counter-container w-full hidden xl:block"  data-product-id = "{{ $product->id }}" data-stock-id = "{{ $stock->id }}" data-category-id = "{{ $product->category_id }}" data-stock-qty="{{ $stock->qty }}" data-cart-qty="{{ checkCartQuantityPerVariant($stock->id) }}">
                <button onclick="selectProduct(this)" class="action-btn w-full text-center text-white uppercase text-[13px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] bg-transparent group-hover:bg-[#2A7CFF] hover:border-[#2A7CFF] transition-all duration-300 cursor-pointer" data-product-id = "{{ $product->id }}" data-stock-id = "{{ $stock->id }}" data-category-id = "{{ $product->category_id }}">
                    Select
                </button>

                <div class="counter-wrapper hidden items-center gap-2 bg-[#0B0F13] border border-gray-800 rounded-xl p-1 w-full">
                    <button onclick="updateBuilderItemQty(this, -1); event.stopPropagation();" class="cursor-pointer minus-btn flex-1 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all active:scale-90">
                        <span class="icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </span>
                    </button>

                    <input type="number" value="1" readonly class="qty-input w-12 h-10 text-center bg-[#282B34] text-white font-bold rounded-lg focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                    <button onclick="updateBuilderItemQty(this, 1); event.stopPropagation();" class="cursor-pointer flex-1 h-10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg transition-all active:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </article>
</div>
@endif
@endforeach
@endif
@endforeach