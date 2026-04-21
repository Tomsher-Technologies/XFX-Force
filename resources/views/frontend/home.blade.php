@extends('frontend.layouts.app')

@section('title', 'Custom Gaming PCs & High-End Hardware in UAE')
@section('content')


@if(!empty($sliders))
<section class="home-slider">
    <div class="hero-slider">
        <div class="swiper-container swiper1">
            <div class="swiper-wrapper">
                @foreach($sliders as $slider)
                <div class="swiper-slide" data-swiper-autoplay="8000">
                    <div class="slide-inner slide-bg-image">

                    @if($slider->slider_type == 'image')
                        <picture class="h-full">
                            @if($slider->mobileImage)
                                <source media="(max-width: 600px)" srcset="{{ $slider->mobileImage ? Storage::url($slider->mobileImage->file_name) : '' }}">
                            @endif

                            @if($slider->mainImage)
                                <img src="{{ $slider->mainImage ? Storage::url($slider->mainImage->file_name) : '' }}" class="object-cover w-full h-full object-top" alt="{{ $slider->title }}">
                            @endif
                        </picture>
                    @elseif($slider->slider_type == 'video')

                        <video playsinline webkit-playsinline muted autoplay loop>
                            <source src="{{ $slider->mainVideo ? Storage::url($slider->mainVideo->file_name) : '' }}" type="video/mp4" />
                        </video>
                    @endif

                        <div class="meta absolute bottom-[15%] md:bottom-[25%] text-center w-full px-[16px]">
                            <div data-swiper-parallax="-400" class="slide-title flex flex-col items-center gap-[20px] justify-center">

                                <h1 class="banner-caption text-center text-[35px] md:text-[55px] lg:text-[65px] xl:text-[75px] 2xl:text-[85px] uppercase w-[80%] md:w-[50%] leading-[1]">{{ $slider->title ?? '' }}</h1>

                                @php
                                    switch ($slider->link_type) {
                                        case 'external':
                                            $url = $slider->link;
                                            break;

                                        case 'product':
                                            // Get product by ID to retrieve slug and default stock
                                            $product = \App\Models\Product::with('stocks')->find($slider->link_ref_id);

                                            if ($product && $product->stocks->count() > 0) {
                                                $defaultStock = $product->stocks->first(); // first stock as default
                                                $url = route('product.details', [
                                                    'slug' => $product->slug,
                                                    'sku'  => $defaultStock->sku
                                                ]);
                                            } else {
                                                $url = '#';
                                            }
                                            break;

                                        case 'category':
                                            $category = \App\Models\Category::with('category_translations')
                                                ->find($slider->link_ref_id);

                                            if ($category && $category->category_translations->isNotEmpty()) {
                                                $url = route(
                                                    'shop.category',
                                                    ['slug' => $category->category_translations->first()->slug ]
                                                    
                                                );
                                            } else {
                                                $url = '#';
                                            }
                                            break;
                                        
                                        case 'brand':
                                            $brand = \App\Models\Brand::find($slider->link_ref_id);

                                            if ($brand && $brand->slug) {
                                                $url = route('shop.brand', [
                                                    'slug' => $brand->slug
                                                ]);
                                            } else {
                                                $url = '#';
                                            }
                                            break;
                                            
                                        default:
                                            $url = '#';
                                    }
                                @endphp

                                <a href="{{ $url }}"
                                @if($slider->link_type === 'external') target="_blank" @endif
                                class="btn btn-cta !rounded-full !text-[#000000] !text-[15px] !uppercase !px-[30px] !py-[15px] !bg-white font-medium">
                                {{ $slider->btn_text ?? 'START BUILDING' }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination !bottom-[10%] block md:hidden m-auto"></div>
            <div class="controls hidden md:block">
                <div class="swiper-button-next !absolute !right-[8%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-prev !absolute !left-[8%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
    </div>
</section>
@endif
<!--//landning banner slider-->

<!--3seg section-->
@if(!empty($banners))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] rounded-t-[50px] relative z-[1] pt-[0px] pb-[0px] mt-[-50px]">
    <div class="flex flex-col xl:grid xl:grid-cols-[3fr_6fr_3fr] gap-[15px] top-[0px] md:top-[-100px] relative z-[1]">

        @foreach($banners as $banner)

            <div class="seg-card rounded-[20px] items-center flex flex-col gap-[20px] justify-end p-[30px] md:p-[40px] relative overflow-hidden min-h-[450px]">
                <img src="{{ $banner->mainImage ? Storage::url($banner->mainImage->file_name) : '' }}" alt="{{ $banner->title }}" class="absolute object-cover object-top w-full h-full">
                <div class="content relative z-[1] flex flex-col items-center justify-end h-full w-full">
                    <h2 class="text-[25px] md:text-[30px] text-[white] capitalize font-bold text-center">{{ $banner->title }}</h2>
                    <p class="text-[15px] text-[#ffffff] text-center">{{ $banner->sub_title }}</p>
                    <a href="{{ getBannerUrl($banner) }}" @if($banner->link_type === 'external') target="_blank" @endif class="btn btn-cta w-full md:w-fit text-center !rounded-full !text-[#000000] !text-[13px] !md:text-[14px] !uppercase !px-[30px] !py-[15px] !bg-white font-medium mt-[20px]" title="">{{ $banner->btn_text }}</a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
<!--3seg section-->

<!--categories-->
@if(!empty($categories))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] pt-[50px] md:pt-0 lg:pt-0 xl:pt-[50px] pb-[50px] md:pb-[50px] lg:pb-[50px] xl:pb-[100px] relative border-b border-[#ffffff10] xl:border-hidden">
    <div class="section-title mb-[30px] relative">
        <h3 class="text-[30px] md:text-[50px] text-[white] font-bold text-center xl:text-left uppercase">{{ $page_content['category_title'] ?? ''}}</h3>
    </div>
    <div class="relative group">
        <div class="swiper cateswiper relative overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach ($categories as $category)
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        <a href="{{ route('shop.category', $category->category_translations->first()->slug) }}" class="flex flex-col items-center justify-center gap-[15px]">
                            <div class="category-thumb flex align-center bg-[#272930] p-[20px] lg:p-[20px] rounded-full h-[100px] lg:h-[80px] xl:h-[95px] w-[100px] lg:w-[80px] xl:w-[95px] overflow-hidden">
                                <img src="{{ $category->iconImage ? Storage::url($category->iconImage->file_name) : '' }}" alt="{{ $category->name }}" title="{{ $category->name }}" class="w-full md:w-[85%] m-auto">
                            </div>
                            <h4 class="text-white text-center font-medium text-[15px] xl:text-[16px] capitalize">{{ $category->name }}</h4>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- <div class="swiper-pagination !relative mt-[50px]  block xl:hidden m-auto"></div> -->
        <div class="controls relative md:absolute right-[0px] left-[0px] m-auto mt-[30px]mt-[0px] md:top-[-80px] items-center gap-[30px] justify-center md:justify-end hidden xl:flex flex-row">
            <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
        </div>
    </div>
</section>
@endif
<!--//categories-->

<!--special gaming pc-->
@if(!empty($newArrivals) || !empty($popularItems))
@php
    // Determine which tab should be active by default
    if(!empty($newArrivals)) {
        $defaultTab = 'newFeatured';
    } elseif(!empty($popularItems)) {
        $defaultTab = 'popularFeatured';
    } else {
        $defaultTab = null; // no tab to show
    }
@endphp
<section x-data="{ activeTab: '{{ $defaultTab }}' }" class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] pt-[50px] pb-[50px] md:pb-[50px] lg:pb-[50px] xl:pb-[100px] relative border-b border-[#ffffff10] xl:border-hidden">

    <div class="section-title mb-[30px] relative flex flex-col xl:flex-row items-center xl:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center uppercase text-center xl:text-left leading-[40px] xl:leading-[50px] m-[0] mb-[30px] xl:mb-[0px]">{{$page_content['featured_products_title'] ?? ''}}</h3>
        <div class="w-full action-group flex flex-row items-center gap-[30px] mr-[0px] xl:mr-[150px] justify-center xl:justify-end align-center">
            <div class="flex gap-[20px] tab-container relative z-[1]">
                @if(!empty($newArrivals))
                <button  @click="activeTab='newFeatured'" class="tab-btn border rounded-full transition-all duration-[300ms] border-[#ffffff30] bg-white text-black text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer active-tab" data-active="true">New Arrivals</button>
                @endif
                @if(!empty($popularItems))
                <button @click="activeTab='popularFeatured'" class="tab-btn border rounded-full transition-all duration-[300ms] border-[#ffffff30] bg-transparent text-[#ffffff30] text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer" data-active="false">Popular Items</button>
                @endif
            </div>
            <div class="divider w-[1px] h-[30px] bg-[#ffffff30] hidden xl:block"></div>
        </div>
    </div>

    

    <div x-show="activeTab === 'newFeatured'" x-transition class="tab-panel">
        <div class="relative group">
            <div class="swiper gamepcswiper relative overflow-x-hidden">
                <div class="swiper-wrapper">
                    @foreach($newArrivals as $item)
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        <div class="gamepc-card relative rounded-[20px] overflow-hidden min-h-[500px]">
                            <img src="{{ $newUploads[$item['featured_new_image']] ? Storage::url($newUploads[$item['featured_new_image']]->file_name) : asset('assets/img/placeholder.jpg') }}" class="absolute object-cover object-center w-full h-full"
                                alt="{{ $item['featured_new_title'] ?? '' }}" title="{{ $item['featured_new_title'] ?? '' }}">
                            <div class="content h-full w-full z-[1] absolute flex flex-col items-start justify-end gap-[20px] p-[30px]">
                                <div class="title flex flex-col items-start gap-[15px]">
                                    <span class="text-white text-[15px] uppercase text-left font-bold">{{ $item['featured_new_sub_title'] ?? '' }}</span>
                                    <h4 class="text-white text-[40px] font-[Juan-cock] uppercase leading-[40px]">{{ $item['featured_new_title'] ?? '' }}</h4>
                                </div>
                                @php
                                $productSpecifications = \App\Models\ProductSpecification::where(
                                    'product_id',
                                    $item['featured_new_product_id']
                                )->with(['specification','specificationItem'])
                                ->orderBy('sort_order')
                                ->get();

                                $product = \App\Models\Product::with('stocks')->find($item['featured_new_product_id']);
                                $firstStock = $product?->stocks?->first();
                                @endphp
                                <div class="specifications w-full transition-all duration-[600ms]">
                                    <ul class="m-[0] w-full divide-y divide-[#ffffff30]">
                                        @foreach ($productSpecifications as $key=> $productSpecification)
                                            @if($productSpecification->specification && $key < 3) {{-- Show only first 4 specifications --}}
                                            <li class="text-white w-full uppercase text-[15px] font-medium py-[10px]">
                                                {{ $productSpecification->specification->main_title }} 
                                                @if($productSpecification->specificationItem)
                                                    {{ $productSpecification->specificationItem->title }}
                                                @endif
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @if($product && $firstStock)
                                <a href="{{ route('product.details', [$product->slug, $product->stocks->first()->sku]) }}"
                                    class="w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] bg-white rounded-full transition-all duration-[600ms] hover:bg-[#2a7cff] hover:text-white">shop
                                    now</a>
                                    @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination !relative mt-[50px] hidden m-auto"></div>
            <div class="controls relative xl:absolute right-[0px] left-[0px] m-auto mt-[30px] xl:mt-[0px] xl:top-[-80px] flex flex-row items-center gap-[30px] justify-center xl:justify-end">
                <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'popularFeatured'" x-transition class="tab-panel">
        <div class="relative group">
            <div class="swiper gamepcswiper relative overflow-x-hidden">
                <div class="swiper-wrapper">
                    @foreach($popularItems as $item)
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        <div class="gamepc-card relative rounded-[20px] overflow-hidden min-h-[500px]">
                            <img src="{{ $popularUploads[$item['featured_popular_image']] ? Storage::url($popularUploads[$item['featured_popular_image']]->file_name) : asset('assets/img/placeholder.jpg') }}" class="absolute object-cover object-center w-full h-full"
                                alt="{{ $item['featured_popular_title'] ?? '' }}" title="{{ $item['featured_popular_title'] ?? '' }}">
                            <div
                                class="content h-full w-full z-[1] absolute flex flex-col items-start justify-end gap-[20px] p-[30px]">
                                <div class="title flex flex-col items-start gap-[15px]">
                                    <span class="text-white text-[15px] uppercase text-left font-bold">{{ $item['featured_popular_sub_title'] ?? '' }}</span>
                                    <h4 class="text-white text-[40px] font-[Juan-cock] uppercase leading-[40px]">{{ $item['featured_popular_title'] ?? '' }}</h4>
                                </div>
                                
                                @php
                                $productSpecifications = \App\Models\ProductSpecification::where(
                                    'product_id',
                                    $item['featured_popular_product_id']
                                )->with('specification')
                                ->orderBy('sort_order')
                                ->get();

                                $product = \App\Models\Product::with('stocks')->find($item['featured_popular_product_id']);
                                $firstStock = $product?->stocks?->first();
                                @endphp

                                <div class="specifications w-full transition-all duration-[600ms]">
                                    <ul class="m-[0] w-full divide-y divide-[#ffffff30]">
                                        @foreach ($productSpecifications as $key=> $productSpecification)
                                            @if($productSpecification->specification&& $key < 3) {{-- Show only first 4 specifications --}}
                                            <li
                                                class="text-white w-full uppercase text-[15px] font-medium py-[10px]">
                                                {{ $productSpecification->specification->main_title }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @if($product && $firstStock)
                                <a href="{{ route('product.details', [$product->slug, $product->stocks->first()->sku]) }}"
                                    class="w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] bg-white rounded-full transition-all duration-[600ms] hover:bg-[#2a7cff] hover:text-white">shop
                                    now</a>
                                    @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination !relative mt-[50px] hidden m-auto"></div>
            <div class="controls relative xl:absolute right-[0px] left-[0px] m-auto mt-[30px] xl:mt-[0px] xl:top-[-80px] flex flex-row items-center gap-[30px] justify-center xl:justify-end">
                <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
        
    </div>
</section>
@endif
<!--//special gaming pc-->

<!--upcoming products-->
@if(!empty($upcomingNewProducts) || !empty($upcomingPopularProducts))
@php
    // Determine which tab should be active by default
    if(!empty($upcomingNewProducts)) {
        $defaultTab = 'newUpcoming';
    } elseif(!empty($upcomingPopularProducts)) {
        $defaultTab = 'popularUpcoming';
    } else {
        $defaultTab = null; // no tab to show
    }
@endphp
<section x-data="{ activeTab: '{{ $defaultTab }}' }" class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] pt-[50px] xl:pt-0 relative" >

    <div class="section-title mb-[30px] relative flex flex-col xl:flex-row items-center xl:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center uppercase text-center xl:text-left leading-[40px] xl:leading-[50px] m-[0] mb-[30px] xl:mb-[0px]">{{$page_content['upcoming_products_title'] ?? ''}}</h3>
        <div class="w-full action-group flex flex-row items-center gap-[30px] mr-[0px] xl:mr-[150px] justify-center xl:justify-end align-center">
            <div class="flex gap-[20px] tab-container z-20">
                @if(!empty($upcomingNewProducts))
                <button @click="activeTab='newUpcoming'"
                        class="tab-btn border rounded-full transition-all duration-[300ms] border-[#ffffff30] bg-white text-black text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer z-20" data-active="true">New Arrivals</button>
                @endif
                @if(!empty($upcomingPopularProducts))
                    <button @click="activeTab='popularUpcoming'" 
                    class="tab-btn border rounded-full transition-all duration-[300ms] border-[#ffffff30] bg-transparent text-[#ffffff30] text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer z-20" data-active="false">Popular Items</button>
                @endif
            </div>
            <div class="divider w-[1px] h-[30px] bg-[#ffffff30] hidden xl:block"></div>
        </div>
    </div>

    <div x-show="activeTab === 'newUpcoming'" x-transition class="tab-panel">
        <div class="relative group">
            <div class="swiper productswiper relative overflow-x-hidden">
                <div class="swiper-wrapper">
                    @foreach($upcomingNewProducts as $product)
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
                                    'page' => 'home-list',
                                ]
                    @endphp
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        @include('frontend.partials.product_card', ['prodData' => $prodData])
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination !relative mt-[50px] hidden m-auto"></div>
            <div class="controls relative xl:absolute right-[0px] left-[0px] m-auto mt-[30px] xl:mt-[0px] xl:top-[-80px] flex flex-row items-center gap-[30px] justify-center xl:justify-end">
                <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'popularUpcoming'" x-transition class="tab-panel">
        <div class="relative group">
            <div class="swiper productswiper relative overflow-x-hidden">
                <div class="swiper-wrapper">
                    @foreach($upcomingPopularProducts as $product)
                    @php
                        // Get the first stock for this product
                        $firstStock = $product->stocks->first();
                        $prodData1 = [
                                    'product_id' => $product->id ?? null,
                                    'stock_id' => $firstStock->id ?? null,
                                    'thumbnail_img' => $product->thumbnail_img ?? null,
                                    'offer_tag' => $firstStock->offer_tag ?? null,
                                    'name' => $product->name ?? null,
                                    'offer_price' => $firstStock->offer_price ?? null,
                                    'price' => $firstStock->price ?? null,
                                    'page' => 'home-list',
                                ];
                    @endphp
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        @include('frontend.partials.product_card', ['prodData' => $prodData1])
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination !relative mt-[50px] hidden m-auto"></div>
            <div class="controls relative xl:absolute right-[0px] left-[0px] m-auto mt-[30px] xl:mt-[0px] xl:top-[-80px] flex flex-row items-center gap-[30px] justify-center xl:justify-end">
                <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
    </div>
</section>
@endif
<!--//upcoming products-->

<!--ads slider 01-->
@if(!empty($middleBanners))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[80px] xl:py-[100px] relative">
    <div class="swiper adswipertwo overflow-hidden">
        <div class="swiper-wrapper">
            @foreach($middleBanners as $banner)
            <div class="swiper-slide" data-swiper-autoplay="8000">
                <a href="{{ getBannerUrl($banner) }}" @if($banner->link_type === 'external') target="_blank" @endif>
                    <img src="{{ $banner->mainImage ? Storage::url($banner->mainImage->file_name) : '' }}" class="w-full h-full" alt="{{ $banner->name}}">
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!--//ads slider 01-->

<!--Pre-built items-->
@if(!empty($middleNewProducts) || !empty($middlePopularProducts))
@php
    // Determine which tab should be active by default
    if(!empty($middleNewProducts)) {
        $defaultTab = 'newMiddleProducts';
    } elseif(!empty($middlePopularProducts)) {
        $defaultTab = 'popularMiddleProducts';
    } else {
        $defaultTab = null; // no tab to show
    }
@endphp
<section x-data="{ activeTab: '{{ $defaultTab }}' }" class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-0 relative">

    <div class="section-title mb-[30px] relative flex flex-col xl:flex-row items-center xl:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center uppercase text-center xl:text-left leading-[40px] xl:leading-[50px] m-[0] mb-[30px] xl:mb-[0px]">{{ $page_content['middle_featured_products_title'] ?? ''}}</h3>
        <div class="w-full action-group flex flex-row items-center gap-[30px] mr-[0px] xl:mr-[150px] justify-center xl:justify-end align-center">
            <div class="flex gap-[20px] tab-container z-20">
                @if(!empty($middleNewProducts))
                <button @click="activeTab='newMiddleProducts'" class="tab-btn border rounded-full transition-all duration-[300ms] border-[#ffffff30] bg-white text-black text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer" data-active="true">New Arrivals</button>
                @endif
                @if(!empty($middlePopularProducts))
                <button @click="activeTab='popularMiddleProducts'" class="tab-btn border rounded-full transition-all duration-[300ms] border-[#ffffff30] bg-transparent text-[#ffffff30] text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer" data-active="false">Popular Items</button>
                @endif
            </div>
            <div class="divider w-[1px] h-[30px] bg-[#ffffff30] hidden xl:block"></div>
        </div>
    </div>

    <!-- new arrival -->
    <div x-show="activeTab === 'newMiddleProducts'" x-transition class="tab-panel">
        <div class="relative group">
            <div class="swiper productswiper relative overflow-x-hidden">
                <div class="swiper-wrapper">
                    @foreach($middleNewProducts as $product)
                    @php
                        // Get the first stock for this product
                        $firstStock = $product->stocks->first();
                        $prodDataM = [
                                    'product_id' => $product->id ?? null,
                                    'stock_id' => $firstStock->id ?? null,
                                    'thumbnail_img' => $product->thumbnail_img ?? null,
                                    'offer_tag' => $firstStock->offer_tag ?? null,
                                    'name' => $product->name ?? null,
                                    'offer_price' => $firstStock->offer_price ?? null,
                                    'price' => $firstStock->price ?? null,
                                    'page' => 'home-list',
                                ];
                    @endphp
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        @include('frontend.partials.product_card', ['prodData' => $prodDataM])
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination !relative mt-[50px] hidden m-auto"></div>
            <div class="controls relative xl:absolute right-[0px] left-[0px] m-auto mt-[30px] xl:mt-[0px] xl:top-[-80px] flex flex-row items-center gap-[30px] justify-center xl:justify-end">
                <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
    </div>

    <!-- popular -->
    <div x-show="activeTab === 'popularMiddleProducts'" x-transition class="tab-panel">
        <div class="relative group">
            <div class="swiper productswiper relative overflow-x-hidden">
                <div class="swiper-wrapper">
                    @foreach($middlePopularProducts as $product)
                    @php
                        // Get the first stock for this product
                        $firstStock = $product->stocks->first();
                        $prodDataP = [
                                    'product_id' => $product->id ?? null,
                                    'stock_id' => $firstStock->id ?? null,
                                    'thumbnail_img' => $product->thumbnail_img ?? null,
                                    'offer_tag' => $firstStock->offer_tag ?? null,
                                    'name' => $product->name ?? null,
                                    'offer_price' => $firstStock->offer_price ?? null,
                                    'price' => $firstStock->price ?? null,
                                    'page' => 'home-list',
                                ];
                    @endphp
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        @include('frontend.partials.product_card', ['prodData' => $prodDataP])
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination !relative mt-[50px] hidden m-auto"></div>
            <div class="controls relative xl:absolute right-[0px] left-[0px] m-auto mt-[30px] xl:mt-[0px] xl:top-[-80px] flex flex-row items-center gap-[30px] justify-center xl:justify-end">
                <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
    </div>
</section>
@endif
<!--//Pre-built items-->

<!--ads slider 02-->
@if(!empty($middleFullBanners))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[80px] xl:py-[100px] relative">
    <div class="swiper adswiperone overflow-hidden">
        <div class="swiper-wrapper">
            @foreach($middleFullBanners as $banner)
            <div class="swiper-slide" data-swiper-autoplay="8000">
                <a href="{{ getBannerUrl($banner) }}" @if($banner->link_type === 'external') target="_blank" @endif>
                    <img src="{{ $banner->mainImage ? Storage::url($banner->mainImage->file_name) : '' }}" class="w-full h-full" alt="{{ $banner->name }}">
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!--//ads slider 02-->

<!--Best Deals-->
@if(!empty($bestDealsProducts))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] pb-[50px] xl:pb-0 relative border-b border-[#ffffff10] xl:border-hidden">

    <div class="section-title mb-[30px] relative flex flex-col md:flex-row items-center md:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center uppercase text-center md:text-left leading-[40px] md:leading-[50px] m-[0] md:mb-[0px]">{{$page_content['best_deals_title'] ?? ''}}</h3>
    </div>

    <div class="relative group">
        <div class="swiper productswiper relative overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach($bestDealsProducts as $product)
                @php
                    // Get the first stock for this product
                    $firstStock = $product->stocks->first();
                    $prodDataB = [
                                    'product_id' => $product->id ?? null,
                                    'stock_id' => $firstStock->id ?? null,
                                    'thumbnail_img' => $product->thumbnail_img ?? null,
                                    'offer_tag' => $firstStock->offer_tag ?? null,
                                    'name' => $product->name ?? null,
                                    'offer_price' => $firstStock->offer_price ?? null,
                                    'price' => $firstStock->price ?? null,
                                    'page' => 'home-list',
                                ];
                @endphp
                <div class="swiper-slide" data-swiper-autoplay="8000">
                    @include('frontend.partials.product_card', ['prodData' => $prodDataB])
                </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-pagination !relative flex flex-start mt-[50px] hidden"></div>
        <div class="controls relative md:absolute right-[0px] m-auto mt-[30px] md:mt-[0px] md:top-[-80px] flex items-center gap-[30px] justify-center md:justify-end">
            <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
        </div>
    </div>
</section>
@endif
<!--//Best Deals-->

<!--pro built items-->
@if(!empty($popularGalleryProducts))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[50px] xl:py-[100px] relative border-b border-[#ffffff10] xl:border-hidden">
    <div class="section-title mb-[30px] relative flex items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center uppercase text-center xl:text-left leading-[40px] xl:leading-[50px] m-[0] mb-[30px] xl:mb-[0px]">{{$page_content['product_gallery_title'] ?? ''}}</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[15px]">
        @foreach($popularGalleryProducts as $product)
        <div class="group ftr-card relative rounded-[20px] overflow-hidden min-h-[300px] xl:min-h-[400px] cursor-pointer" onclick="window.location.href='{{ route('product.details', [$product->slug, $product->stocks->first()->sku]) }}'">
            <img src="{{ get_product_image($product->thumbnail_img) }}" class="absolute object-center object-cover w-full h-full top-0 left-0 transition-all duration-[600ms] group-hover:scale-110" alt="{{ $product->name }}" title="{{ $product->name }}">
            <div class="content flex flex-col xl:flex-row items-end justify-end xl:justify-between gap-[20px] xl:gap-[30px] relative z-[1] w-full h-full bg-gradient-to-b from-transparent to-[#0000008a] p-[30px]">
                <h6 class="text-white text-[20px] font-medium w-full xl:w-[50%] text-center xl:text-left line-clamp-2">{{ $product->name }}</h6>
                <a href="{{ route('product.details', [$product->slug, $product->stocks->first()->sku]) }}" class="w-full xl:w-fit text-center text-black text-[13px] xl:text-[14px] font-medium uppercase bg-white border border-transparent px-[30px] py-[15px] rounded-full transition-all duration-[600ms] group-hover:bg-[#2A7CFF] group-hover:text-white">Shop Now</a>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
<!--//pro built items-->

<!--graphic cards-->
@if(!empty($graphicCardProducts))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[50px] lg:py-0 relative border-b border-[#ffffff10] xl:border-hidden">

    <div class="section-title mb-[30px] relative flex flex-col md:flex-row items-center md:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold uppercase text-center md:text-left leading-[40px] md:leading-[50px] m-[0] md:mb-[0px]">{{$page_content['graphic_cards_title'] ?? ''}}</h3>
    </div>

    <div class="relative group">
        <div class="swiper productswiper relative overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach($graphicCardProducts as $product)
                    @php
                        // Get the first stock for this product
                        $firstStock = $product->stocks->first();
                        $prodDataGC = [
                                        'product_id' => $product->id ?? null,
                                        'stock_id' => $firstStock->id ?? null,
                                        'thumbnail_img' => $product->thumbnail_img ?? null,
                                        'offer_tag' => $firstStock->offer_tag ?? null,
                                        'name' => $product->name ?? null,
                                        'offer_price' => $firstStock->offer_price ?? null,
                                        'price' => $firstStock->price ?? null,
                                        'page' => 'home-list',
                                    ];
                    @endphp
                <div class="swiper-slide" data-swiper-autoplay="8000">
                    @include('frontend.partials.product_card', ['prodData' => $prodDataGC])
                </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-pagination !relative flex flex-start mt-[50px] hidden"></div>
        <div class="controls relative md:absolute right-[0px] m-auto mt-[30px] md:mt-[0px] md:top-[-80px] flex items-center gap-[30px] justify-center md:justify-end">
            <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
            <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
        </div>
    </div>

    

</section>
@endif
<!--//graphic cards-->

<!--testimonials-->
@if(!empty($testimonialsVideo) || !empty($testimonialsText))
<section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] relative">
    <div class="border-y-1 border-[#ffffff10] py-[50px] xl:py-[100px]">
        <div class="section-title mb-[30px] relative flex items-center justify-center">
            <h3 class="text-[30px] md:text-[50px] text-[white] font-bold uppercase text-center md:text-left leading-[40px] md:leading-[50px] m-[0]">{{$page_content['testimonials_title'] ?? ''}}</h3>
        </div>
        <div class="flex flex-col xl:flex-row gap-[30px] max-w-6xl m-auto">
            <div>
                <div class="flex flex-col xl:grid grid-cols-2 gap-[15px] xl:gap-[30px]">
                    <div class="swiper video-testimonials w-full h-[250px] xl:h-full overflow-hidden relative cursor-none rounded-[20px]">
                        @if(!empty($testimonialsVideo))
                        <div class="swiper-wrapper rounded-[20px] h-full">
                            @foreach($testimonialsVideo as $testimonial)
                            <div class="swiper-slide">
                                @if($testimonial->video_source === 'youtube')
                                    @php
                                        $videoId = basename(parse_url($testimonial->embed_link, PHP_URL_PATH));
                                        $embedUrl = $testimonial->embed_link;
                                        $separator = str_contains($embedUrl, '?') ? '&' : '?';

                                        $embedUrl .= $separator . "autoplay=1&mute=1&loop=1&playlist={$videoId}&controls=0&modestbranding=1&rel=0";
                                    @endphp
                                    <a href="{{ $testimonial->common_link }}" class="glightbox block relative group h-full w-full">
                                        <iframe src="{{ $embedUrl }}" class="h-full w-full object-cover pointer-events-none scale-[160%]" allow="autoplay"></iframe>

                                @elseif($testimonial->video_source === 'upload')
                                <a href="{{ Storage::url($testimonial->video_path) }}" class="glightbox block relative group h-full w-full">
                                    <video autoplay muted loop class="h-full w-full object-cover pointer-events-none scale-[160%]">
                                        <source src="{{ Storage::url($testimonial->video_path) }}" type="video/webm">
                                    </video>
                                @endif
                                    <div class="content absolute px-[30px] py-[30px] z-[1] bottom-0 left-0 w-full bg-gradient-to-t from-[#0000008a] to-transparent flex flex-row justify-between items-center gap-[30px] before:content-[''] before:absolute before:left-0 before:bottom-0 before:w-full before:h-[50%] md:before:h-[100%] before:z-[-1] before:bg-gradient-to-t before:from-transparent before:to-transparent before:backdrop-blur-[15px] before:[mask-image:linear-gradient(to_top,black,black,transparent)] before:[webkit-mask-image:linear-gradient(to_top,black,black,transparent)] before:transition-all before:duration-[500ms] before:ease-in-out hover:before:opacity-100">
                                        <div>
                                            <h6 class="text-white text-[20px] md:text-[25px] font-medium">{{$testimonial->name}}</h6>
                                            <p class="text-white text-[15px] font-normal">{{$testimonial->sub_title}}</p>
                                        </div>
                                        <button class="bg-[#ffffff30] border-hidden rounded-full p-[10px] flex align-center items-center justify-center h-[50px] w-[50px] transition-all duration-[600ms] group-hover:bg-[#2a7cff]"><img src="{{ asset('assets/images/play.svg') }}" alt="Play Button" class="w-[10px] h-[10px]"></button>
                                    </div>
                                </a>
                            </div>
                            @endforeach           
                        </div>
                        @endif
                        <div class="swiper-pagination absolute flex flex-start mt-[50px] !bottom-[30px] hidden md:block"></div>
                    </div>

                    <div class="flex flex-col gap-[30px]">
                        <div class="rating-box flex flex-row items-center gap-[10px] md:gap-[50px] border rounded-[30px] border-[#272930] p-[30px] md:p-[40px]">
                            <div class="flex flex-col gap-[10px] w-full justify-center items-center">
                                <div class="flex flex-row gap-[10px] justify-center xl:justify-start items-center">
                                    <img src="{{ asset('assets/images/rating.svg') }}" alt="Rating" class="w-[25px] md:w-[40px] h-[40px]">
                                    <h6 class="text-white text-[30px] md:text-[40px] font-medium leading-none">{{$page_content['testimonials_rating_count'] ?? ''}}</h6>
                                </div>
                                <span class="text-white text-[15px] font-normal">{{$page_content['testimonials_rating_title'] ?? ''}}</span>
                            </div>
                            <div class="divider w-[1px] bg-[#272930] h-full"></div>
                            <div class="flex flex-col gap-[10px] w-full justify-center items-center">
                                <div class="flex flex-row gap-[10px] justify-center xl:justify-start items-center">
                                    <h6 class="text-white text-[30px] xl:text-[40px] font-medium leading-none">{{$page_content['testimonials_customer_count'] ?? ''}}</h6>
                                </div>
                                <span class="text-white text-[15px] font-normal">{{$page_content['testimonials_customer_title'] ?? ''}}</span>
                            </div>
                        </div>
                        <div class="g-review border rounded-[30px] border-[#272930] p-[30px] md:p-[40px] w-full h-full overflow-hidden">
                            <div class="swiper g-testimonials relative h-full">
                                @if(!empty($testimonialsText))
                                <div class="swiper-wrapper h-full">
                                    @foreach ($testimonialsText as $testimonialsText)    
                                    <div class="swiper-slide">
                                        <div class="flex flex-col gap-[30px] xl:gap-[50px] justify-between h-full">
                                            <p class="text-white text-center lg:text-left text-[15px] font-normal leading-[30px]">{{$testimonialsText->comment}}</p>
                                            <div class="flex flex-row gap-[20px] justify-center xl:justify-start">
                                                <div id="userAvatar" class="flex items-center justify-center w-12 h-12 rounded-full bg-[#393B42] text-white font-bold text-xl">{{ strtoupper(substr($testimonialsText->name, 0, 1)) }}</div>
                                                <div>
                                                    <span id="userName" class="text-white font-medium text-[18px]">{{$testimonialsText->name}}</span>
                                                    <p class="text-[#898989] font-medium text-[15px]">{{$testimonialsText->sub_title}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                <div class="swiper-pagination !relative flex justify-center mt-[50px] block md:hidden"></div>
                                <div class="hidden controls relative md:absolute mt-[30px] md:mt-[0px] right-[0px] bottom-[0px] items-center gap-[20px] justify-center md:justify-end">
                                    <div class="swiper-button-prev !top-[0] !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                                    <div class="swiper-button-next !top-[0] !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-[300ms] !hover:bg-white/20 !mt-[0px]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--//testimonials-->

<!--about & Brands-->
@if(!empty($homePageFooters))
    <section class="bg-[#0F161B] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] relative">
        <div class="swiper aboutswiper overflow-hidden min-h-full">
            <div class="swiper-wrapper">
            @foreach($homePageFooters as $footer)
                @php
                    // Get the image file from uploads
                    $footerImage = $footerUploads[$footer['footer_image']] ?? null;
                    $footerImageUrl = $footerImage ? Storage::url($footerImage->file_name) : asset('assets/images/about-img.webp');
                @endphp    
                <div class="swiper-slide">
                    <div class="flex flex-col xl:grid md:grid-cols-3">
                        <div class="section-title mb-0">
                            <h3 class="text-[30px] md:text-[50px] text-[white] font-bold uppercase text-center xl:text-left leading-[40px] md:leading-[50px] m-[0]">{{$footer['footer_title']}}</h3>
                        </div>
                        <img src="{{$footerImageUrl}}" alt="About PC Garage" title="About PC Garage" class="w-full h-auto relative left-[0px] xl:left-[-25%] right-0 m-auto z-[0]">
                        <div class="grid col-span-3 col-start-3 mt-0 md:mt-[100px] mb-[50px] xl:mb-[100px]">
                            <div style="color:rgb(255 255 255) !important;" class="text-white text-[15px] xl:text-[18px] font-normal leading-[30px] xl:leading-[35px] mb-[30px] text-center xl:text-left">{!! $footer['footer_content'] !!}</div>
                            <a href="{{$footer['footer_button_link']}}" class="w-full md:w-fit m-auto xl:m-0 h-fit text-center text-black uppercase text-[13px] md:text-[14px] font-medium px-[30px] py-[15px] bg-white rounded-full transition-all duration-[600ms] hover:bg-[#2a7cff] hover:text-white">{{$footer['footer_button_text']}}</a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
@endif
<!--//about & Brands-->
@endsection
