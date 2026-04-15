@extends('frontend.layouts.app')

@section('title', 'Shop - Single')
@section('content')

<!--breadcrumb-->
<section class="px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[0px] relative">
    <nav class="flex text-gray-400 py-[15px] md:py-[30px] border-t border-[#ffffff30]" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium hover:text-[#3E81FF] transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Home
                </a>
            </li>

            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('products') }}" class="ml-1 text-sm font-medium hover:text-[#3E81FF] md:ml-2 transition-colors">Shop</a>
                </div>
            </li>

            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('shop.category', $product->category->category_translations->first()->slug) }}" class="ml-1 text-sm font-medium hover:text-[#3E81FF] md:ml-2 transition-colors">{{$product->category->name}}</a>
                </div>
            </li>

            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-white md:ml-2">{{$product->name}}</span>
                </div>
            </li>
        </ol>
    </nav>
</section>
<!--//breadcrumb-->
<div id="flash-message"></div>
<!--product single intro-->
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[50px] md:pt-[0px] xl:mb-[100px]">
    <div class="flex flex-col xl:grid xl:grid-cols-11 gap-[30px] xl:gap-[100px]">
        <div class="col-span-5 overflow-hidden h-[50vh] xl:h-[70vh] w-full relative bg-[#0B0F13] bg-gradient-to-t from-[#0B0F13] to-[#1E2225]">
            @php
                $firstStock = $selectedStock; // This should be the stock user has selected
                $slides = [];
            @endphp

            <button data-product-id="{{ $product->id }}" data-page="product-details" data-stock-id="{{ $stockId }}" class="wishlist-toggle absolute top-[20px] right-[20px] z-[10] mt-2 w-[35px] h-[35px] md:w-[40px] md:h-[40px] bg-black/20 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center text-white bg-black/20 transition-all duration-300 hover:bg-transparent hover:text-red-500 cursor-pointer group/heart" id="wishlist-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-active/heart:scale-125" fill="none"  viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
            <div class="swiper singleprdswiper relative overflow-hidden h-full w-full">
                <input type="hidden" value="{{$product->id}}" id="main_product_id">
                <input type="hidden" value="{{$stockId}}" id="selected_stock_id">
                
                
                <div class="swiper-wrapper">
                    <!-- Product video -->
                    @if(!empty($product->video_link))
                    <!-- <div class="swiper-slide" data-swiper-autoplay="8000">
                        <a href="{{ $product->video_link }}" class="glightbox" data-gallery="product-gallery">
                            <div class="w-full h-full flex items-center justify-center bg-black relative">
                                
                                {{-- Video Thumbnail (Optional placeholder image) --}}
                                <img src="{{ $product->thumbnail_img ? Storage::url($product->thumbnail_img) : '' }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">

                                {{-- Play Icon --}}
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="white">
                                        <circle cx="12" cy="12" r="12" fill="rgba(0,0,0,0.6)"/>
                                        <polygon points="10,8 16,12 10,16" fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div> -->
                    @endif

                    {{-- Variant/stock images --}}
                    @if($firstStock && $firstStock->image)
                        @php 
                            $variant_images = explode(',', $firstStock->image); 
                            $slides = array_merge($slides, $variant_images);
                        @endphp
                    @endif

                    {{-- If no stock images, fallback to product gallery --}}
                    @if(empty($slides) && $product->photos)
                        @php 
                            $product_images = explode(',', $product->photos); 
                            $slides = $product_images;
                        @endphp
                    @endif

                    {{-- If still empty, fallback to thumbnail --}}
                    @if(empty($slides) && $product->thumbnail_img)
                        @php $slides = [$product->thumbnail_img]; @endphp
                    @endif

                    {{-- Render slides --}}
                    @foreach($slides as $img)
                        <div class="swiper-slide" data-swiper-autoplay="8000">
                            <a href="{{ Storage::url($img) }}" class="glightbox">
                                <img src="{{ Storage::url($img) }}" 
                                    alt="{{ $product->name }}" 
                                    title="{{ $product->name }}" 
                                    class="w-full h-full object-contain object-center">
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next !absolute right-[0%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-prev !absolute left-[0%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
        <div class="product-info flex flex-col gap-[20px] xl:gap-[50px] xl:col-span-6 w-full">
            @php
            $firstStock = $selectedStock;
        
            $groupedAttributes = $product->stocks
            ->flatMap(function ($stock) {
            return $stock->attributes;
            })
            ->groupBy(function ($attrRow) {
            return $attrRow->attribute->name;
            });
            // Prepare first variant values for comparison
            $firstVariantValues = $firstStock
            ? $firstStock->attributes->pluck('attribute_value_id', 'attribute_id')->toArray()
            : [];
            @endphp

            <div>
            @if (filled($firstStock['offer_tag']))
                <badge
                    class="flex w-fit bg-[#077F09] text-white text-[12px] font-medium px-[15px] py-[5px] rounded-full capitalize m-auto xl:m-0">
                    {{ $firstStock['offer_tag'] }}</badge>
            @endif
            <h1 class="text-white text-[20px] md:text-[30px] leading-[30px] 
                md:leading-[45px] text-center 
                xl:text-left tracking-[0px] variant-title mt-[15px]">
                {{ $firstStock->stock_title ?? $product->name }}
            </h1>
            <p class="text-[12px] text-[#ffffff50] text-center xl:text-left py-1">
                @if($product->brand) {{ $product->brand->name }} | @endif
                @if($firstStock->model) {{ $firstStock->model }} @endif
                @if($firstStock->sku) | {{ $firstStock->sku }} @endif
            </p>


            
            @php
                $approvedReviews = $product->reviews->where('status', 1);
                $rating = $approvedReviews->avg('rating') ?? 0;
                $totalReviews = $approvedReviews->count();

                $fullStars = floor($rating);
                $halfStar = ($rating - $fullStars) >= 0.5;
                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
            @endphp

            @if($approvedReviews->isNotEmpty())
            <!-- rating -->
            <a href="javascript:void(0)" 
            onclick="window.dispatchEvent(new CustomEvent('open-reviews-tab'));" 
            class="flex items-center gap-[8px] my-2 justify-center xl:justify-start">

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
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.570-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>

                        <div class="absolute top-0 left-0 overflow-hidden w-[50%]">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-[#FFB800] fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.570-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    @endif

                    {{-- Empty Stars --}}
                    @for ($i = 0; $i < $emptyStars; $i++)
                    <svg class="w-3 h-3 md:w-4 md:h-4 text-gray-600 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.570-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor

                </div>

                <span class="text-white text-[12px] md:text-[14px] font-medium">
                    {{ number_format($rating, 1) }}
                </span>

                <span class="text-[#898989] text-[11px] md:text-[13px] font-medium">
                    ({{ $totalReviews }} reviews)
                </span>

            </a>
            <!-- //rating -->
            @endif

            </div>
            <input type="hidden" value="{{ $firstStock->id}}" id="stock_id">

            

            <!--varients-->
            <div class="flex flex-col gap-[30px] md:gap-6 text-center md:text-left border-t md:border-t-0 border-[#ffffff30] mt-[30px] md:mt-[0px] py-[30px] md:py-[0px]">
                @foreach($groupedAttributes as $attributeName => $attrRows)                
                    @php
                        $level = $loop->index; // 0 = first level
                    @endphp
                    <div class="variant-group">
                        <label class="text-[12px] uppercase text-[#ffffff50] mb-3 block">{{ $attributeName }}</label>
                        <div class="flex flex-wrap gap-3 justify-center md:justify-start variant-list">
                            @foreach($attrRows->unique('attribute_value_id')->values() as $attrRow)
                                @php
                                    $attrId = $attrRow->attribute_id;
                                    $valueId = $attrRow->value->id;

                                    // Enable logic
                                    if($level == 0){
                                        $isEnabled = true; // first level always enabled
                                    } else {
                                        // Check if this value exists in allowed variants for this level
                                        $isEnabled = false;
                                        foreach($variantsById as $v){
                                            // match parent levels up to current
                                            $parentValues = array_slice($selectedLevelValues, 0, $level, true);
                                            $match = true;
                                            foreach($parentValues as $pid => $pval){
                                                if(($v[$pid] ?? null) != $pval){
                                                    $match = false;
                                                    break;
                                                }
                                            }
                                            if($match && ($v[$attrId] ?? null) == $valueId){
                                                $isEnabled = true;
                                                break;
                                            }
                                        }
                                    }

                                    // Active logic: exact match with selected SKU
                                    $isActive = isset($selectedLevelValues[$attrId]) && $selectedLevelValues[$attrId] == $valueId
                                                ? 'active border-1 border-[#2A7CFF] bg-[#2A7CFF]/10 text-white font-medium'
                                                : '';

                                    $btnClass = $isEnabled
                                        ? 'border border-[#ffffff10] bg-[#161B22] text-gray-400 hover:border-[#ffffff30]'
                                        : 'opacity-30 cursor-not-allowed border border-[#ffffff10] bg-[#161B22] text-gray-400 hover:border-[#ffffff30]';
                                @endphp

                                <button
                                    class="variant-btn px-4 py-3 rounded-xl text-[13px] transition-all {{ $btnClass }} {{ $isActive }}"
                                    data-attr-id="{{ $attrId }}"
                                    data-value-id="{{ $valueId }}"
                                    data-product-id="{{ $product->id }}"
                                    data-attr-index="{{ $loop->parent->index }}"
                                    {{ $isEnabled ? '' : 'disabled' }}
                                >
                                    {{ $attrRow->value->value }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <!--//varients-->
            <div id="price-btn-block" class="flex flex-col md:flex-row gap-[30px] border-y border-[#ffffff30] py-[30px] w-full justify-between flex-end items-center md:items-end">
                <div>
                    <label class="text-[15px] text-white mb-[15px] block text-center md:text-left">Price</label>
                    <div class="price w-full flex flex-row items-end gap-[15px]">
                        <h5 class="price flex flex-row text-[#2A7CFF] text-left text-[25px] m-[0] font-bold align-center items-center gap-[10px] leading-[35px]">
                            <img src="{{ asset('assets/images/aed.svg') }}" class="w-[22px] h-[22px]" alt="AED" title="Symbol of AED">
                            <span class="offer-price">{{ format_price($firstStock->offer_price, 2) }}</span>
                        </h5>
                        @if(filled($firstStock->offer_tag))
                        <span class="text-[#898989] font-medium line-through text-[20px] main-price">{{ $firstStock->price }} </span>
                        @endif
                    </div>
                </div>

                <!-- When item exist-->
                <div class="button-group flex flex-col md:grid grid-cols-2 gap-[15px] h-fit w-full md:w-fit add-to-cart-block  {{ ($cartQty < $firstStock->qty) ? '' : 'hidden' }}">
                    <!--counter-->
                    <div class="counter-wrapper product-item flex items-center gap-4 bg-[#0B0F13] border border-gray-800 rounded-xl p-1 shadow-inner w-full {{ ($cartQty > 0 && $cartQty < $firstStock->qty) ? '' : 'hidden' }}" data-variant-id="{{ $firstStock->id }}" data-cart-id="{{ $cartId }}">
                        <button onclick="updateMultiQty(this, -1)" class="decrement-btn w-full h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all active:scale-90">
                            <span class="icon-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 minus-btn" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 12H4" /></svg>
                                
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 trash-btn" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </span>
                        </button>
                        <input type="number" value="{{ $cartQty }}" readonly class="qty-input w-full h-full text-center bg-[#282B34] text-white font-medium focus:outline-none text-[15px] p-[10px] rounded-lg">
                        <button onclick="updateMultiQty(this, 1)" class="w-full h-10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <!--//counter-->
                    
                    <!-- Add to cart button -->
                    <button class="add-to-cart w-full flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-600 text-white hover:bg-[#2A7CFF] hover:text-white cursor-pointer {{ ($cartQty == 0 && $cartQty < $firstStock->qty) ? '' : 'hidden' }}"><img src="{{ asset('assets/images/cart.svg') }}" alt="" title="" class="mr-[15px]">Add to cart</button>
                        
                    
                    <!-- Buy now button -->
                    <button onclick="buyNow(this)" class="buy-now w-full flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-600 text-white hover:bg-[#2A7CFF] hover:text-white cursor-pointer {{ ($cartQty < $firstStock->qty) ? '' : 'hidden' }}"><img src="{{ asset('assets/images/cart.svg') }}" alt="" title="" class="mr-[15px]">Buy Now</button>

                    <!-- Wishlist button -->
                    
                </div>
                <!-- When item exist -->

                <!--when the item is out of stock-->
                <div class="button-group flex flex-col xl:grid xl:grid-cols-2 gap-[15px] h-fit w-full md:w-fit out-of-stock-block   {{ ($cartQty === $firstStock->qty && $firstStock->qty == 0) ? '' : '!hidden' }}">
                    <div class="flex justify-center items-center gap-2 px-4 py-2 bg-[#c0392b20] border border-[#c0392b50] rounded-[15px] w-full h-full mx-auto md:mx-0 align-center">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#c0392b]"></span>
                        </span>
                        <span class="text-[#e74c3c] text-[12px] font-medium uppercase tracking-wider">Out of Stock</span>
                    </div>
                </div>
                <!--//when the item is out of stock-->
            </div>
            <div class="flex flex-col md:flex-row justify-between">
                @php
                    $conditionMap = [
                        0 => 'New',
                        1 => 'Refurbished',
                        2 => 'Open Box',
                    ];
                @endphp 
                <a href="javascript::void(0)" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b md:border-hidden border-[#ffffff30] cursor-default">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/make-your-order.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Condition</h4>
                        <span class="text-[15px] text-[#ffffff50] underline decoration-wavy underline-offset-8">
                            {{ $conditionMap[$product->condition] ?? 'New' }}
                        </span>
                    </div>
                </a>
                <a href="{{ route('buildyourpc') }}" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b md:border-hidden border-[#ffffff30]">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/configurator.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Configurator</h4>
                        <span class="text-[15px] text-[#ffffff50]">Create your dream PC</span>
                    </div>
                </a>
                <a href="#" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b md:border-hidden border-[#ffffff30]">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/need-help.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Need help?</h4>
                        <span class="text-[15px] text-[#ffffff50]">Ask for advice</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<!--//product single intro-->

<!--specification-->
@php
    $productSpecifications = \App\Models\ProductSpecification::where(
    'product_stock_id',
    $firstStock->id
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

    $productTabs = $product->tabs;

    $productWarrantis = $product->warranties;
    $overviewContent = $selectedStock->stock_description ?? $product->description;

    $defaultTab = null;

    if(!empty($overviewContent)) {
        $defaultTab = 'overview';
    } elseif($productSpecifications->isNotEmpty()) {
        $defaultTab = 'specs';
    } elseif($productWarrantis->isNotEmpty()) {
        $defaultTab = 'services';
    } elseif($productTabs->isNotEmpty()) {
        $defaultTab = 'tab-0';
    }
    @endphp

<div class="bg-[#0F161B] text-gray-300" x-data="{ activeTab: '{{ $defaultTab }}' }"  x-init="
        window.addEventListener('open-reviews-tab', () => {
            activeTab = 'reviews';
            $nextTick(() => {
                document.getElementById('reviews')?.scrollIntoView({ behavior: 'smooth' });
            });
        })
     ">
    <nav class="sticky top-[79px] md:top-[148px] z-50 w-full border-b border-gray-800 bg-[#0F151D] backdrop-blur-md">
        <div class="max-w-6xl mx-auto flex overflow-x-auto no-scrollbar whitespace-nowrap px-4 justify-start md:justify-center" id="tabs-section">
            @if(!empty($overviewContent))
                <a href="javascript:void(0)" @click="activeTab='overview'" :class="activeTab === 'overview' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Overview</a>
            @endif
            @if($productSpecifications->isNotEmpty())
                <a href="javascript:void(0)" @click="activeTab='specs'" :class="activeTab === 'specs' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Specifications</a>
            @endif
            @if($productWarrantis->isNotEmpty())
                <a href="javascript:void(0)" @click="activeTab='services'" :class="activeTab === 'services' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Services</a>
            @endif
            @if($productTabs->isNotEmpty())
                @foreach($productTabs as $tab)
                    <a href="javascript:void(0)" @click="activeTab='tab-{{ $loop->index }}'" :class="activeTab === 'tab-{{ $loop->index }}' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">{{ $tab->heading }}</a>
                @endforeach
            @endif

            @if($approvedReviews->isNotEmpty())
                <a href="javascript:void(0)" @click="activeTab='reviews'" :class="activeTab === 'reviews' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Reviews</a>
            @endif
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-[16px] md:px-[30px] lg:px-[50px] py-10">
        <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @if(!empty($overviewContent))
        <section x-show="activeTab === 'overview'" x-transition class="tab-panel" id="overview" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px] md:py-[100px]">
            <div class="mt-[30px]">
                <div class="text-[15px] md:text-[18px] text-justify leading-[30px] text-[#ffffff50]">{!! $overviewContent !!}</div>
            </div>
        </section>
        @endif

        @if($productSpecifications->isNotEmpty())
        <section x-show="activeTab === 'specs'" x-transition class="tab-panel" id="specs" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px]">
            <div class="mt-[30px]">
                <div class="specifications">
                    
                    <ul class="flex flex-col gap-[5px]">
                        @foreach ($specifications as $specification)
                        <li class="bg-[#282B3450] flex flex-row px-[15px] rounded-[5px] py-[15px] justify-between gap-[15px] md:gap-[0px]">
                            <div class="title flex flex-row gap-[20px] w-full">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="icon" d="M12.4936 7.50636C12.5936 8.10091 12.7273 9.06545 12.7273 10C12.7273 10.9345 12.5945 11.8991 12.4936 12.4936C11.8991 12.5936 10.9345 12.7273 10 12.7273C9.06545 12.7273 8.10091 12.5945 7.50636 12.4936C7.40636 11.8991 7.27273 10.9345 7.27273 10C7.27273 9.06545 7.40545 8.10091 7.50636 7.50636C8.10091 7.40636 9.06545 7.27273 10 7.27273C10.9345 7.27273 11.8991 7.40545 12.4936 7.50636ZM20 11.8182C20 12.3209 19.5936 12.7273 19.0909 12.7273H18.04C17.9764 13.3845 17.8991 14.0027 17.82 14.5455H18.6364C19.1391 14.5455 19.5455 14.9518 19.5455 15.4545C19.5455 15.9573 19.1391 16.3636 18.6364 16.3636H17.5109C17.4682 16.5809 17.4391 16.7145 17.4336 16.74C17.3582 17.0873 17.0873 17.3582 16.74 17.4336C16.7145 17.4391 16.5809 17.4682 16.3636 17.5109V18.6364C16.3636 19.1391 15.9573 19.5455 15.4545 19.5455C14.9518 19.5455 14.5455 19.1391 14.5455 18.6364V17.82C14.0027 17.8991 13.3845 17.9764 12.7273 18.04V19.0909C12.7273 19.5936 12.3209 20 11.8182 20C11.3155 20 10.9091 19.5936 10.9091 19.0909V18.1627C10.6073 18.1736 10.3036 18.1818 10 18.1818C9.69636 18.1818 9.39273 18.1736 9.09091 18.1627V19.0909C9.09091 19.5936 8.68455 20 8.18182 20C7.67909 20 7.27273 19.5936 7.27273 19.0909V18.04C6.61545 17.9764 5.99727 17.8991 5.45455 17.82V18.6364C5.45455 19.1391 5.04818 19.5455 4.54545 19.5455C4.04273 19.5455 3.63636 19.1391 3.63636 18.6364V17.5109C3.41909 17.4682 3.28545 17.4391 3.26 17.4336C2.91273 17.3582 2.64182 17.0873 2.56636 16.74C2.56091 16.7145 2.53182 16.5809 2.48909 16.3636H1.36364C0.860909 16.3636 0.454545 15.9573 0.454545 15.4545C0.454545 14.9518 0.860909 14.5455 1.36364 14.5455H2.18C2.10091 14.0027 2.02364 13.3845 1.96 12.7273H0.909091C0.406364 12.7273 0 12.3209 0 11.8182C0 11.3155 0.406364 10.9091 0.909091 10.9091H1.83727C1.82636 10.6073 1.81818 10.3036 1.81818 10C1.81818 9.69636 1.82636 9.39273 1.83727 9.09091H0.909091C0.406364 9.09091 0 8.68455 0 8.18182C0 7.67909 0.406364 7.27273 0.909091 7.27273H1.96C2.02364 6.61545 2.10091 5.99727 2.18 5.45455H1.36364C0.860909 5.45455 0.454545 5.04818 0.454545 4.54545C0.454545 4.04273 0.860909 3.63636 1.36364 3.63636H2.48909C2.53182 3.41909 2.56091 3.28545 2.56636 3.26C2.64182 2.91273 2.91273 2.64182 3.26 2.56636C3.28545 2.56091 3.41909 2.53182 3.63636 2.48909V1.36364C3.63636 0.860909 4.04273 0.454545 4.54545 0.454545C5.04818 0.454545 5.45455 0.860909 5.45455 1.36364V2.18C5.99727 2.10091 6.61545 2.02364 7.27273 1.96V0.909091C7.27273 0.406364 7.67909 0 8.18182 0C8.68455 0 9.09091 0.406364 9.09091 0.909091V1.83727C9.39273 1.82636 9.69636 1.81818 10 1.81818C10.3036 1.81818 10.6073 1.82636 10.9091 1.83727V0.909091C10.9091 0.406364 11.3155 0 11.8182 0C12.3209 0 12.7273 0.406364 12.7273 0.909091V1.96C13.3845 2.02364 14.0027 2.10091 14.5455 2.18V1.36364C14.5455 0.860909 14.9518 0.454545 15.4545 0.454545C15.9573 0.454545 16.3636 0.860909 16.3636 1.36364V2.48909C16.5809 2.53182 16.7145 2.56091 16.74 2.56636C17.0873 2.64182 17.3582 2.91273 17.4336 3.26C17.4391 3.28545 17.4682 3.41909 17.5109 3.63636H18.6364C19.1391 3.63636 19.5455 4.04273 19.5455 4.54545C19.5455 5.04818 19.1391 5.45455 18.6364 5.45455H17.82C17.8991 5.99727 17.9764 6.61545 18.04 7.27273H19.0909C19.5936 7.27273 20 7.67909 20 8.18182C20 8.68455 19.5936 9.09091 19.0909 9.09091H18.1627C18.1736 9.39273 18.1818 9.69636 18.1818 10C18.1818 10.3036 18.1736 10.6073 18.1627 10.9091H19.0909C19.5936 10.9091 20 11.3155 20 11.8182ZM14.5455 10C14.5455 8.30546 14.1764 6.60455 14.16 6.53273C14.0836 6.18636 13.8136 5.91636 13.4673 5.84C13.3955 5.82364 11.6945 5.45455 10 5.45455C8.30546 5.45455 6.60455 5.82364 6.53273 5.84C6.18636 5.91636 5.91636 6.18636 5.84 6.53273C5.82364 6.60455 5.45455 8.30546 5.45455 10C5.45455 11.6945 5.82364 13.3955 5.84 13.4673C5.91636 13.8136 6.18636 14.0836 6.53273 14.16C6.60455 14.1764 8.30546 14.5455 10 14.5455C11.6945 14.5455 13.3955 14.1764 13.4673 14.16C13.8136 14.0836 14.0836 13.8136 14.16 13.4673C14.1764 13.3955 14.5455 11.6945 14.5455 10Z" fill="#9F9FA9"></path>
                                </svg>
                                <h5 class="text-[14px] md:text-[15px] text-[#636671] uppercase">{{ $specification['title'] }}</h5>
                            </div>
                            <div class="value w-full">
                                <h6 class="text-[14px] md:text-[15px] text-[#C4C4C4] text-left">{{ $specification['value'] }}</h6>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
        @endif
        @if($productWarrantis->isNotEmpty())
         <section x-show="activeTab === 'services'" x-transition class="tab-panel" id="services" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px]">
            <div class="mt-[50px]">
                <ul class="flex flex-col gap-[5px]">
                    <li class="bg-[#282B3450] flex flex-col md:flex-row p-[30px] rounded-[5px] justify-between gap-[30px] md:gap-[0px]">
                        <div class="title flex flex-row gap-[20px] w-full">
                            <h5 class="text-[15px] text-[#636671] uppercase"> Warranty</h5>
                        </div>
                        <div class="value w-full">
                            <h6 class="text-[15px] text-[#C4C4C4] text-left mb-[30px]">Optimal Warranty Package</h6>
                            <ul class="list-outside list-disc px-[15px] text-[#C4C4C4] text-[15px] leading-[30px]">
                                @foreach ($productWarrantis as $warranty)
                                <li>{{$warranty->title}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        @endif
        @if($productTabs->isNotEmpty())
            @foreach($productTabs as $tab)
                <section x-show="activeTab === 'tab-{{ $loop->index }}'" x-transition class="tab-panel" id="tab-{{ $loop->index }}" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px]">
                    <div class="mt-[30px]">
                        <div class="text-[15px] md:text-[18px] text-justify leading-[30px] text-[#ffffff50]">{!! $tab->content !!}</div>
                    </div>
                </section>
            @endforeach
        @endif

        @if($approvedReviews->isNotEmpty())
        <section x-show="activeTab === 'reviews'" x-transition class="tab-panel scroll-mt-[130px] md:scroll-mt-[200px] py-[50px]" id="reviews">
            <div class="w-full" x-data="{ activeReview: null }">
                <svg width="0" height="0" class="absolute">
                    <defs>
                        <linearGradient id="half-star-grad">
                            <stop offset="50%" stop-color="#FBBF24" />
                            <stop offset="50%" stop-color="#374151" />
                        </linearGradient>
                    </defs>
                </svg>

                <div class="grid grid-cols-1">
                    @foreach ($approvedReviews as $index => $review)
                    <div class="border-b border-gray-800 overflow-hidden transition-all duration-300">
                        <div class="py-5 cursor-pointer flex items-start justify-between" @click="activeReview === {{ $index }} ? activeReview = null : activeReview = {{ $index }}">
                            <div class="flex gap-4">
                                
                                @php
                                    $name = $review->user->name ?? 'Guest User';
                                    $words = explode(' ', trim($name));
                                    $initials = strtoupper(substr($words[0] ?? 'G', 0, 1) . substr($words[1] ?? '', 0, 1));
                                @endphp

                                <div class="w-12 h-12 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center text-blue-500 font-medium shrink-0">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <h4 class="text-white font-medium">{{ $review->user->name ?? 'Guest User' }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex text-[#FBBF24]">
                                            <!-- <template x-for="i in 4"><svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg></template>
                                            <svg class="w-3 h-3" viewBox="0 0 20 20"><path fill="url(#half-star-grad)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg> -->

                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <svg class="w-3 h-3 text-[#FFB800] fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3 text-gray-600 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        {{ $review->rating }} <span class="text-gray-500 text-xs block" title="{{ $review->created_at->format('d M Y • h:i A') }}">| {{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-400 text-sm mt-3 transition-all" :class="activeReview === {{ $index }} ? 'hidden' : 'line-clamp-1'">
                                        {{ $review->comment }}
                                    </p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="activeReview === {{ $index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>

                        <div x-show="activeReview === {{ $index }}" x-collapse x-cloak class="bg-white/5 rounded-xl mb-4">
                            <div class="p-6">
                                <p class="text-gray-300 text-sm leading-relaxed">
                                    {{ $review->comment }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </main>
</div>
<!--//specification-->

<!--related products-->
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[50px] md:py-[100px] relative">
    <div class="section-title mb-[30px] relative flex flex-col xl:flex-row items-center xl:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center uppercase text-center xl:text-left leading-[40px] xl:leading-[50px] m-[0] mb-[30px] xl:mb-[0px]">Related Products</h3>
    </div>
    <div class="relative group">
        <div class="swiper productswiper relative overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach($relatedProducts as $product)
                    @php
                        // Get the first stock for this product
                        $firstStock = $product->stocks->first();
                        $prodData = [
                                'product_id' => $product->id ?? null,
                                'stock_id' => $firstStock->id ?? null,
                                'thumbnail_img' => $product->thumbnail_img ?? null,
                                'offer_tag' => $firstStock->offer_tag ?? null,
                                'name' => $product->name ?? null,
                                'offer_price' => $firstStock->offer_price ?? null,
                                'price' => $firstStock->price ?? null,
                                'page' => 'product-list',
                            ]
                    @endphp
                <div class="swiper-slide" data-swiper-autoplay="8000">
                    @include('frontend.partials.product_card', ['prodData' => $prodData])
                </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-pagination !relative flex flex-start mt-[50px] hidden xl:block"></div>
        <div class="controls relative xl:!absolute right-[0px] m-auto mt-[30px] xl:mt-[0px] xl:top-[-80px] flex items-center gap-[30px] justify-center xl:justify-end">
            <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
            <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
        </div>
    </div>
</section>
<!--//related products-->
@endsection