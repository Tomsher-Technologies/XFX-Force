@extends('frontend.layouts.app')

@section('title', 'Shop - Single')
@section('content')

<!--breadcrumb-->
<section class="px-[16px] md:px-[140px] pt-[80px] md:pt-[150px] pb-[0px] relative">
    <nav class="flex text-gray-400 py-[15px] md:py-[30px] border-t-1 border-[#ffffff30]" aria-label="Breadcrumb">
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
                    <a href="{{ route('shop.category', $product->category->id) }}" class="ml-1 text-sm font-medium hover:text-[#3E81FF] md:ml-2 transition-colors">{{$product->category->name}}</a>
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
<section class="bg-[#0F161B] px-[16px] md:px-[140px] py-[50px] md:pt-[0px] md:mb-[100px]">
    <div class="flex flex-col md:grid md:grid-cols-11 gap-[30px] md:gap-[100px]">
        <div class="col-span-5 overflow-hidden h-[50vh] md:h-[70vh] w-full">
            <div class="swiper singleprdswiper relative overflow-hidden h-full w-full">
                <input type="hidden" value="{{$product->id}}" id="main_product_id">
                <input type="hidden" value="{{$stockId}}" id="selected_stock_id">
                
                <div class="swiper-wrapper">
                    @php $hasVariantImage = false; @endphp
                    @foreach($product->stocks as $stock)
                    <input type="hidden" value="{{ $stock->stock_title }}" id="variant_type">
                    @if($stock->image)
                    @php $hasVariantImage = true; @endphp
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        <a href="{{ Storage::url($stock->image) }}" class="glightbox">
                            <img src="{{ Storage::url($stock->image) }}" alt="" title="" class="w-full h-full object-cover object-center">
                        </a>
                    </div>
                    @endif
                    @endforeach

                    @if(!$hasVariantImage && $product->thumbnail_img)
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        <a href="{{ Storage::url($product->thumbnail_img) }}" class="glightbox">
                            <img src="{{ Storage::url($product->thumbnail_img) }}" alt="" title="" class="w-full h-full object-cover object-center">
                        </a>
                    </div>
                    @endif

                </div>
                <div class="swiper-button-next !absolute right-[0%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-prev !absolute left-[0%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
        <div class="product-info flex flex-col gap-[20px] md:gap-[50px] md:col-span-6 w-full">
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
                    class="bg-[#077F09] text-white text-[12px] font-medium px-[15px] py-[5px] rounded-full capitalize">
                    {{ $firstStock['offer_tag'] }}</badge>
            @endif
            <h1 class="text-white text-[20px] md:text-[30px] leading-[30px] 
                md:leading-[45px] text-center 
                md:text-left tracking-[0px] variant-title mt-[15px]">
                {{ $firstStock->stock_title ?? $product->name }}
            </h1>
            <p class="text-[12px] text-[#ffffff50]">
                @if($product->brand) {{ $product->brand->name }} | @endif
                @if($firstStock->model) {{ $firstStock->model }} @endif
                @if($firstStock->sku) | {{ $firstStock->sku }} @endif
            </p>
            </div>
            <input type="hidden" value="{{ $firstStock->id}}" id="stock_id">

            <!--varients-->
            <div class="flex flex-col gap-[30px] md:gap-6 text-center md:text-left border-t-1 md:border-t-0 border-[#ffffff30] mt-[30px] md:mt-[0px] py-[30px] md:py-[0px]">
                @foreach($groupedAttributes as $attributeName => $attrRows)                
                <div class="variant-group">
                    <label class="text-[12px] uppercase text-[#ffffff50] mb-3 block">{{ $attributeName }}</label>
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start variant-list">
                        
                        @foreach(
                        $attrRows
                        ->unique('attribute_value_id')
                        ->values()
                        as $attrRow
                        )
                        @php
                        $isActive = $firstStock && in_array($attrRow->attribute_value_id, $firstVariantValues) ? 'active border-1 border-[#2A7CFF] bg-[#2A7CFF]/10 text-white font-medium' : 'border border-[#ffffff10] bg-[#161B22] text-gray-400 hover:border-[#ffffff30]';
                        @endphp
                        <button class="variant-btn px-4 py-3 rounded-xl text-[13px] transition-all  {{ $isActive }}" data-attr-id="{{ $attrRow->attribute_id }}" data-value-id="{{ $attrRow->value->id }}" data-product-id="{{$product->id}}" data-attr-index="{{ $loop->parent->index }}">
                            {{ $attrRow->value->value }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <!--//varients-->

            <div class="flex flex-col md:flex-row gap-[30px] border-y-1 border-[#ffffff30] py-[30px] w-full justify-between flex-end items-center md:items-end">
                <div>
                    @php
                    // Get the first stock for this product
                    $firstStock = $product->stocks->first();
                    @endphp
                    <label class="text-[15px] text-white mb-[15px] block text-center md:text-left">Price</label>
                    <div class="price w-full flex flex-row items-end gap-[15px]">
                        <h5 class="price flex flex-row text-[#2A7CFF] text-left text-[25px] m-[0] font-bold align-center items-center gap-[10px] leading-[35px]">
                            <img src="{{ asset('assets/images/aed.svg') }}" class="w-[22px] h-[22px]" alt="AED" title="Symbol of AED">
                            <span class="offer-price">{{ number_format($firstStock->offer_price, 2) }}</span>
                        </h5>
                        @if(filled($firstStock->offer_tag))
                        <span class="text-[#898989] font-medium line-through text-[20px] main-price">{{ $firstStock->price }} </span>
                        @endif
                    </div>
                </div>

                <!-- When item exist-->
                <div class="button-group flex flex-col md:grid grid-cols-2 gap-[15px] h-fit w-full md:w-fit add-to-cart-block">
                    
                    <button onclick="window.location.href='{{ route('cart') }}'" class="go-to-cart w-full flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-600 text-white hover:bg-[#2A7CFF] hover:text-white cursor-pointer"><img src="{{ asset('assets/images/cart.svg') }}" alt="" title="" class="mr-[15px]">Go to cart</button>
                   
                    <button class="add-to-cart w-full flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-600 text-white hover:bg-[#2A7CFF] hover:text-white cursor-pointer"><img src="{{ asset('assets/images/cart.svg') }}" alt="" title="" class="mr-[15px]">Add to cart</button>
                    
                    
                    <a href="#" class="w-full text-center bg-white md:bg-transparent text-black md:text-white uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 hover:bg-white hover:text-black">Add to Wishlist</a>
                </div>
                <!-- When item exist -->

                <!--when the item is out of stock-->
                <div class="button-group flex flex-col md:grid grid-cols-2 gap-[15px] h-fit w-full md:w-fit out-of-stock-block hide" style="display: none;">
                    <div class="flex justify-center items-center gap-2 px-4 py-2 bg-[#c0392b20] border border-[#c0392b50] rounded-[15px] w-full h-full mx-auto md:mx-0 align-center">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#c0392b]"></span>
                            </span>
                            <span class="text-[#e74c3c] text-[12px] font-medium uppercase tracking-wider">Out of Stock</span>
                        </div>
                    <a href="#" class="w-full text-center bg-white md:bg-transparent text-black md:text-white uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 hover:bg-white hover:text-black">Add to Wishlist</a>
                </div>
                <!--//when the item is out of stock-->
            </div>
            <div class="flex flex-col md:flex-row justify-between">
                <a href="#" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b-1 md:border-hidden border-[#ffffff30]">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/make-your-order.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Make your Order</h4>
                        <span class="text-[15px] text-[#ffffff50] underline decoration-wavy underline-offset-8">Ways to receive</span>
                    </div>
                </a>
                <a href="#" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b-1 md:border-hidden border-[#ffffff30]">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/configurator.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Configurator</h4>
                        <span class="text-[15px] text-[#ffffff50]">Create your dream PC</span>
                    </div>
                </a>
                <a href="#" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b-1 md:border-hidden border-[#ffffff30]">
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
<div class="bg-[#0F161B] text-gray-300" x-data="{ activeTab: 'overview' }">

    <nav class="sticky top-[79px] md:top-[148px] z-50 w-full border-b border-gray-800 bg-[#0F151D] backdrop-blur-md">
        <div class="max-w-6xl mx-auto flex overflow-x-auto no-scrollbar whitespace-nowrap px-4 justify-start md:justify-center">
            <a href="javascript:void(0)" @click="activeTab='overview'" :class="activeTab === 'overview' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Overview</a>
            <a href="javascript:void(0)" @click="activeTab='specs'" :class="activeTab === 'specs' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Specifications</a>
            <!-- <a href="javascript:void(0)" @click="activeTab='equipment'" :class="activeTab === 'equipment' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Equipments</a> -->
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-10">

        <section x-show="activeTab === 'overview'" x-transition class="tab-panel" id="overview" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px] md:py-[100px]">
            <h2 class="text-[18px] md:text-[20px] text-left uppercase font-bold text-white pb-[20px] border-b-2 border-[#2A7CFF]">Overview</h2>
            <div class="mt-[30px]">
                <div class="text-[15px] md:text-[18px] text-justify leading-[30px] text-[#ffffff50]">{{ $product->description }}</div>
            </div>
        </section>

        <section x-show="activeTab === 'specs'" x-transition class="tab-panel" id="specs" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px]">
            <h2 class="text-[18px] md:text-[20px] text-left uppercase font-bold text-white pb-[20px] border-b-2 border-[#2A7CFF]">Specifications</h2>
            <div class="mt-[30px]">
                <div class="specifications">
                    @php
                    $productSpecifications = \App\Models\ProductSpecification::where(
                    'product_id',
                    $product->id
                    )->with(['specification','specificationItem'])->get();

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
                    <ul class="flex flex-col gap-[5px]">
                        @foreach ($specifications as $specification)
                        <li class="bg-[#282B3450] flex flex-row px-[15px] rounded-[5px] py-[15px] justify-between gap-[15px] md:gap-[0px]">
                            <div class="title flex flex-row gap-[20px] w-full">
                                <img src="{{ asset('assets/images/processor-icon.svg') }}" alt="" title="" class="w-[20px] h-[20px]">
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
    </main>
</div>
<!--//specification-->

<!--related products-->
<section class="bg-[#0F161B] px-[16px] md:px-[140px] py-[50px] md:py-[100px] relative">
    <div class="section-title mb-[30px] md:mb-[50px] relative flex flex-col md:flex-row items-center md:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white capitalize font-bold text-center uppercase text-center md:text-left leading-[40px] md:leading-[50px] m-[0] mb-[30px] md:mb-[0px]">Related Products</h3>
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
        <div class="swiper-pagination !relative flex flex-start mt-[50px] hidden md:block"></div>
        <div class="controls relative md:!absolute right-[0px] m-auto mt-[30px] md:mt-[0px] md:top-[-80px] flex items-center gap-[20px] justify-center md:justify-end">
            <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
            <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
        </div>
    </div>
</section>
<!--//related products-->
@endsection