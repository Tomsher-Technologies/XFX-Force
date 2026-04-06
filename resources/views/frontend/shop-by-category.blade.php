@extends('frontend.layouts.app')

@section('title', 'Shop - Category')
@section('content')

<input type="hidden" id="category-last-page" value="{{ $products->lastPage() }}">
<input type="hidden" id="category-current-page" value="{{ $products->currentPage() }}">
    <!--inner banner-->
    <section class="px-[16px] md:px-[140px] pt-[80px] md:pt-[150px] pb-[0px] relative">
        <div class="section-title mb-[0px] relative border-t-1 border-[#ffffff30] pt-[30px] md:pt-0 xl:pt-[50px]">
            <h3 class="w-full text-[30px] md:text-[50px] text-white font-bold text-center xl:text-left uppercase flex flex-col md:flex-row flex-start justify-center xl:justify-start items-center md:items-start gap-[0px] md:gap-[10px] m-0 leading-[30px] md:leading-[60px]">shop: {{ $category->name }}<span class="text-[18px] text-[#2A7CFF] top-[6px] tracking-[0px] relative font-sans h-[0px]" id="total-product-count">{{ $products->count() }}</span></h3>
        </div>
        <input type="hidden" id="current-category-id" value="{{ $category->category_translations->first()->slug }}">
    </section>
    <!--//categories-->



<!--product listing-->
<section class="bg-[#0F161B]">

  <!-- Mobile filter dialog -->
  <el-dialog>
    <dialog id="mobile-filters" class="m-0 overflow-hidden p-0 backdrop:bg-transparent xl:hidden">
      <el-dialog-backdrop class="fixed inset-0 bg-black/25 transition-opacity duration-300 ease-linear data-[closed]:opacity-0"></el-dialog-backdrop>

      <div tabindex="0" class="fixed inset-0 flex focus:outline focus:outline-0">
        <el-dialog-panel class="relative ml-auto flex size-full max-w-xs transform flex-col overflow-y-auto bg-white pb-6 pt-4 shadow-xl transition duration-300 ease-in-out data-[closed]:translate-x-full">
          <div class="flex items-center justify-between px-4">
            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
            <button type="button" command="close" commandfor="mobile-filters" class="relative -mr-2 flex size-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <span class="absolute -inset-0.5"></span>
              <span class="sr-only">Close menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
            <div class="form-section"></div>
        </el-dialog-panel>
      </div>
    </dialog>
  </el-dialog>
  <!--//Mobile filter dialog -->

    <main class="px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[50px] xl:py-[80px]">
        <div class="flex flex-col xl:grid xl:grid-cols-4 xl:gap-[50px]">
          <!-- Desktop Filters -->
			<div class="desktop-filter">
                <form class="hidden xl:block">
                    <button id="clear-filters" 
                            class="inline-block w-full text-[#898989] text-[13px] md:text-[14px] font-medium 
                                px-3 py-1.5 mt-2 rounded-[6px] transition-all duration-200 
                                hover:bg-white/10 hover:text-white cursor-pointer flex items-center gap-2 mb-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear All
                    </button>
                    <!--categories filter-->
                    <div class="bg-black/30 backdrop-blur-[60px] px-[30px] py-[15px] rounded-[20px] mb-[10px]">
                        <button type="button" command="--toggle" commandfor="filter-section-categories" class="flex w-full items-center justify-between py-3 text-sm text-gray-400 hover:text-gray-500 cursor-pointer">
                            <span class="font-medium uppercase text-white">Categories</span>
                            <span class="ml-6 flex items-center">
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [[aria-expanded='true']_&]:hidden">
                                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [&:not([aria-expanded='true']_*)]:hidden">
                                    <path d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <el-disclosure id="filter-section-categories" hidden class="pt-6 [&:not([hidden])]:block border-t-1 border-[#282B34] pb-[20px]">

                            <div class="w-full">
                                <div class="space-y-4">
                                    @if($category->childs->count())
                                    <div class="relative mb-6">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </span>
                                        
                                        <input type="text" id="category-search" placeholder="Search Category" class="w-full bg-[#282B34] text-white text-sm rounded-[10px] focus:ring-[#3E81FF] focus:border-[#3E81FF] block pl-10 p-[15px] outline-none transition-all">
                                    </div>
                                    @endif

                                    @if($category->childs->count())
                                        @php
                                            $padding = 20;
                                        @endphp
                                        @foreach($category->childs as $child)
                                            @php
                                                $productCount = \App\Models\Product::where('category_id', $child->id)->count();
                                            @endphp
                                            <div class="flex gap-[15px] items-center category-item" data-name="{{ $child->category_translations->first()->name ?? $child->name }}" style="padding-left: {{ $padding }}px;">
                                                <div class="flex h-5 shrink-0 items-center">
                                                    <div class="group grid size-4 grid-cols-1 w-full">
                                                        <input type="checkbox" name="categories[]" value="{{ $child->id }}" class="category-checkbox h-[25px] w-[25px] col-start-1 row-start-1 appearance-none rounded border border-[#5F6370] bg-[#282B34] checked:border-indigo-600 checked:bg-[#2161C7]" id="filter-category-{{ $child->id }}" checked>
                                                    </div>
                                                </div>
                                                <label for="filter-category-{{ $child->id }}" class="relative top-[5px] text-[15px] text-white">
                                                    {{ $child->category_translations->first()->name ?? $child->name }}
                                                    <span class="text-[15px] text-[#50525C] ml-[10px]">{{ $productCount }}</span>
                                                </label>
                                            </div>
                                            
                                        @endforeach
                                    @else
                                        <div class="flex gap-[15px] items-center category-item" data-name="{{ $category->name }}">
                                            <div class="flex h-5 shrink-0 items-center">
                                                <div class="group grid size-4 grid-cols-1 w-full">
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox h-[25px] w-[25px] col-start-1 row-start-1 appearance-none rounded border border-[#5F6370] bg-[#282B34] checked:border-indigo-600 checked:bg-[#2161C7]" id="filter-category-{{ $category->id }}" checked style="pointer-events: none;">
                                                </div>
                                            </div>
                                            <label for="filter-category-{{ $category->id }}" class="relative top-[5px] text-[15px] text-white">
                                                {{ $category->category_translations->first()->name ?? $category->name }}
                                                <span class="text-[15px] text-[#50525C] ml-[10px]" id="category-count">{{ $productCount }}</span>
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </el-disclosure>
                    </div>
                    <!--//categories filter-->

                    <!--price range-->
                    <div class="bg-black/30 backdrop-blur-[60px] px-[30px] py-[15px] rounded-[20px] mb-[10px]">
                        <button type="button" command="--toggle" commandfor="filter-section-price" class="flex w-full items-center justify-between py-3 text-sm text-gray-400 hover:text-gray-500 cursor-pointer">
                            <span class="font-medium uppercase text-white">Shop by Price</span>
                            <span class="ml-6 flex items-center">
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [[aria-expanded='true']_&]:hidden">
                                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [&:not([aria-expanded='true']_*)]:hidden">
                                    <path d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <el-disclosure id="filter-section-price" hidden class="pt-6 [&:not([hidden])]:block border-t-1 border-[#282B34] pb-[30px]">
                            <div class="w-full price-filter">
                                <div class="flex justify-between items-center mb-8 gap-[20px] align-center">
                                    <div class="w-full">
                                        <span class="text-gray-400 text-xs block mb-2">Min</span>
                                        <div class="bg-[#282B34] rounded-[10px] border border-white/5 w-full">
                                            <input type="number" class="min-price w-full text-white bg-transparent font-medium text-[14px] focus:outline-none border-0" value="0" min="0" max="300000" step="100">
                                        </div>
                                    </div>
                                    <div class="h-[1px] w-4 bg-gray-600"></div>
                                    <div class="w-full">
                                        <span class="text-gray-400 text-xs block mb-2 text-right">Max</span>
                                        <div class="bg-[#282B34] rounded-[10px] border border-white/5 text-right w-full">
                                            <input type="number" class="max-price w-full text-white bg-transparent font-medium text-[14px] focus:outline-none text-right border-0" value="300000" min="0" max="300000" step="100">
                                        </div>
                                    </div>
                                </div>
                                <div class="relative h-1 w-full bg-[#282B34] rounded-lg">
                                    <div class="slider-progress absolute h-full bg-white rounded-lg left-0 right-0"></div>
                                    <input type="range" min="0" max="300000" value="0" step="100" class="range-min absolute w-full h-1 appearance-none bg-transparent pointer-events-none z-20">
                                    <input type="range" min="0" max="300000" value="300000" step="100" class="range-max absolute w-full h-1 appearance-none bg-transparent pointer-events-none z-20">
                                </div>
                            </div>

                        </el-disclosure>
                    </div>
                    <!--//price range-->

                    <!--brand filter-->
                    <div class="bg-black/30 backdrop-blur-[60px] px-[30px] py-[15px] rounded-[20px] mb-[10px]">
                        <button type="button" command="--toggle" commandfor="filter-section-brand" class="flex w-full items-center justify-between py-3 text-sm text-gray-400 hover:text-gray-500 cursor-pointer">
                            <span class="font-medium uppercase text-white">Brands</span>
                            <span class="ml-6 flex items-center">
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [[aria-expanded='true']_&]:hidden">
                                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [&:not([aria-expanded='true']_*)]:hidden">
                                    <path d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <el-disclosure id="filter-section-brand" hidden class="pt-6 [&:not([hidden])]:block border-t-1 border-[#282B34] pb-[20px]">

                            <div class="w-full">
                                <div id="brand-grid" class="space-y-4">
                                    <div class="relative mb-6">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </span>
                                        <input type="text" id="brand-search" placeholder="Search brands (e.g. Nvidia)" class="w-full bg-[#282B34] text-white text-sm rounded-[10px] focus:ring-[#3E81FF] focus:border-[#3E81FF] block pl-10 p-[15px] outline-none transition-all">
                                    </div>
                                
                                    @if(!empty($brands))
                                    @foreach ($brands as $brand)
                                    <div class="flex gap-[15px] align-center items-center brand-item" data-name="{{$brand->name}}">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1 w-full">
                                                <input id="filter-brand-0" type="checkbox" name="brands[]" value="{{$brand->id}}" class="h-[25px] w-[25px] col-start-1 row-start-1 appearance-none rounded border border-[#5F6370] bg-[#282B34] checked:border-indigo-600 checked:bg-[#2161C7] indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-brand-0" class="relative top-[5px] text-[15px] text-white">{{$brand->name}} <span class="text-[15px] text-[#50525C] ml-[10px]">{{ $brand->products_count }}</span></label>
                                    </div>
                                    @endforeach
                                    @endif
                                
                                <a href="{{ route('brands.list') }}" class="block mt-[30px] w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-[600ms] text-white hover:bg-white hover:text-black">view all brands</a>
                            </div>
                            </div>

                        </el-disclosure>
                    </div>
                    <!--//brand filter-->
                </form>
                <!--promotion banners-->
                <div class="swiper promobnrswiper relative overflow-hidden rounded-[20px]">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" data-swiper-autoplay="8000">
                            <a href="#"><img src="src/images/sidebar-ad-banner-01.webp" alt="" title=""></a>
                        </div>
                    </div>
                </div>
                <!--//promotion banners-->
            </div>
            <!-- Desktop Filters -->

            <div class="col-span-3" x-data="{ activeTab: '{{ request('view', 'gridview') }}' }">

                <div class="flex flex-col xl:flex-row items-center justify-between gap-[15px] xl:gap-[0px] w-full">
                    <span class="text-[#898989] text-[14px] w-full text-center xl:text-left"  id="product-count" data-per-page="{{ $products->perPage() }}" data-total="{{ $products->total() }}">
                      @if($products->count() > 0)
                          Items 1-{{ $products->count() }} of {{ $products->count() }}
                      @else
                          Items 0 of 0
                      @endif
                    </span>
                    <div class="flex flex-col xl:flex-row items-center gap-[15px] xl:gap-[20px] justify-end w-full">
                        <el-dropdown class="relative block text-left w-full xl:w-[230px]">
                            <button class="group flex border border-[#282B34] rounded-[10px] p-[20px] justify-between text-sm font-medium text-white !w-full">
                                <span class="mr-[10px] text-[14px] text-[#898989]">Sort by:
                                    @switch($sort)
                                    @case('oldest') Oldest @break
                                    @case('newest') Newest @break
                                    @case('price_low_high') Price: Low to High @break
                                    @case('price_high_low') Price: High to Low @break
                                    @default Newest
                                    @endswitch
                                </span>
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="-mr-1 ml-1 size-5 shrink-0 text-gray-400 group-hover:text-gray-500">
                                    <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </button>
                            <el-menu anchor="bottom end" popover class="m-0 w-40 origin-top-right rounded-md bg-white p-0 shadow-2xl ring-1 ring-black/5 transition [--anchor-gap:theme(spacing.2)] [transition-behavior:allow-discrete] focus:outline-none data-[closed]:scale-95 data-[closed]:transform data-[closed]:opacity-0 data-[enter]:duration-100 data-[leave]:duration-75 data-[enter]:ease-out data-[leave]:ease-in">
                                <div class="py-1">
                                    <a href="#" data-sort="newest" class="sort-option block px-4 py-2 text-sm font-medium text-gray-900 focus:bg-gray-100 focus:outline-none">Newest</a>
                                    <a href="#" data-sort="oldest" class="sort-option block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-none">Oldest</a>
                                    <a href="#" data-sort="price_low_high" class="sort-option block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-none">Price: Low to High</a>
                                    <a href="#" data-sort="price_high_low" class="sort-option block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-none">Price: High to Low</a>
                                </div>
                            </el-menu>
                        </el-dropdown>

                        <div id="view-switcher" class="fixed xl:static bottom-[30px] xl:bottom-[0] z-[99] bg-[#0f161b47] md:bg-transparent backdrop-blur-[60px] button-group p-[5px] border border-[#282B34] rounded-[10px] gap-[5px] flex flex-row right-0 left-0 mx-auto xl:m-0 w-fit">
                            <button @click="activeTab='gridview';filterProducts([], '', 'gridview')" :class="activeTab=='gridview' ? 'active bg-[#282B34]' : ''" type="button" class="view-btn group p-[15px] cursor-pointer hover:bg-[#282B34] rounded-[5px] active:bg-[#282B34] transition-all duration-[600ms]">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.25 9.75C6.04565 9.75 6.80848 10.0663 7.37109 10.6289C7.9337 11.1915 8.25 11.9544 8.25 12.75V15C8.25 15.7956 7.9337 16.5585 7.37109 17.1211C6.80848 17.6837 6.04565 18 5.25 18H3C2.20435 18 1.44152 17.6837 0.878906 17.1211C0.316297 16.5585 0 15.7956 0 15V12.75C0 11.9544 0.316297 11.1915 0.878906 10.6289C1.44152 10.0663 2.20435 9.75 3 9.75H5.25ZM15 9.75C15.7956 9.75 16.5585 10.0663 17.1211 10.6289C17.6837 11.1915 18 11.9544 18 12.75V15C18 15.7956 17.6837 16.5585 17.1211 17.1211C16.5585 17.6837 15.7956 18 15 18H12.75C11.9544 18 11.1915 17.6837 10.6289 17.1211C10.0663 16.5585 9.75 15.7956 9.75 15V12.75C9.75 11.9544 10.0663 11.1915 10.6289 10.6289C11.1915 10.0663 11.9544 9.75 12.75 9.75H15ZM3 11.25C2.60218 11.25 2.22076 11.4081 1.93945 11.6895C1.65815 11.9708 1.5 12.3522 1.5 12.75V15C1.5 15.3978 1.65815 15.7792 1.93945 16.0605C2.22076 16.3419 2.60218 16.5 3 16.5H5.25C5.64782 16.5 6.02924 16.3419 6.31055 16.0605C6.59185 15.7792 6.75 15.3978 6.75 15V12.75C6.75 12.3522 6.59185 11.9708 6.31055 11.6895C6.02924 11.4081 5.64782 11.25 5.25 11.25H3ZM12.75 11.25C12.3522 11.25 11.9708 11.4081 11.6895 11.6895C11.4081 11.9708 11.25 12.3522 11.25 12.75V15C11.25 15.3978 11.4081 15.7792 11.6895 16.0605C11.9708 16.3419 12.3522 16.5 12.75 16.5H15C15.3978 16.5 15.7792 16.3419 16.0605 16.0605C16.3419 15.7792 16.5 15.3978 16.5 15V12.75C16.5 12.3522 16.3419 11.9708 16.0605 11.6895C15.7792 11.4081 15.3978 11.25 15 11.25H12.75ZM5.25 0C6.04565 0 6.80848 0.316297 7.37109 0.878906C7.9337 1.44152 8.25 2.20435 8.25 3V5.25C8.25 6.04565 7.9337 6.80848 7.37109 7.37109C6.80848 7.9337 6.04565 8.25 5.25 8.25H3C2.20435 8.25 1.44152 7.9337 0.878906 7.37109C0.316297 6.80848 0 6.04565 0 5.25V3C0 2.20435 0.316297 1.44152 0.878906 0.878906C1.44152 0.316297 2.20435 0 3 0H5.25ZM15 0C15.7956 0 16.5585 0.316297 17.1211 0.878906C17.6837 1.44152 18 2.20435 18 3V5.25C18 6.04565 17.6837 6.80848 17.1211 7.37109C16.5585 7.9337 15.7956 8.25 15 8.25H12.75C11.9544 8.25 11.1915 7.9337 10.6289 7.37109C10.0663 6.80848 9.75 6.04565 9.75 5.25V3C9.75 2.20435 10.0663 1.44152 10.6289 0.878906C11.1915 0.316297 11.9544 0 12.75 0H15ZM3 1.5C2.60218 1.5 2.22076 1.65815 1.93945 1.93945C1.65815 2.22076 1.5 2.60218 1.5 3V5.25C1.5 5.64782 1.65815 6.02924 1.93945 6.31055C2.22076 6.59185 2.60218 6.75 3 6.75H5.25C5.64782 6.75 6.02924 6.59185 6.31055 6.31055C6.59185 6.02924 6.75 5.64782 6.75 5.25V3C6.75 2.60218 6.59185 2.22076 6.31055 1.93945C6.02924 1.65815 5.64782 1.5 5.25 1.5H3ZM12.75 1.5C12.3522 1.5 11.9708 1.65815 11.6895 1.93945C11.4081 2.22076 11.25 2.60218 11.25 3V5.25C11.25 5.64782 11.4081 6.02924 11.6895 6.31055C11.9708 6.59185 12.3522 6.75 12.75 6.75H15C15.3978 6.75 15.7792 6.59185 16.0605 6.31055C16.3419 6.02924 16.5 5.64782 16.5 5.25V3C16.5 2.60218 16.3419 2.22076 16.0605 1.93945C15.7792 1.65815 15.3978 1.5 15 1.5H12.75Z" fill="#898989" class="group-hover:fill-white transition-all duration-[600ms]" />
                                </svg>
                            </button>
                            <button @click="activeTab='listview';filterProducts([], '', 'listview')" :class="activeTab=='listview' ? 'active bg-[#282B34]' : ''" type="button" class="view-btn group p-[15px] cursor-pointer hover:bg-[#282B34] rounded-[5px] active:bg-[#282B34] transition-all duration-[600ms]">
                                <svg width="18" height="18" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 0H0V1.25H15V0Z" fill="#898989" class="group-hover:fill-white transition-all duration-[600ms]" />
                                    <path d="M15 6.25H0V7.50001H15V6.25Z" fill="#898989" class="group-hover:fill-white transition-all duration-[600ms]" />
                                    <path d="M15 12.5H0V13.75H15V12.5Z" fill="#898989" class="group-hover:fill-white transition-all duration-[600ms]" />
                                </svg>
                            </button>
                            <button type="button" class="mobile-filter-btn view-btn group p-[15px] hover:bg-[#282B34] rounded-[5px] active:bg-[#282B34] transition-all duration-[600ms] xl:hidden">
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5">
                                    <path d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd" fill-rule="evenodd" fill="#898989" class="group-hover:fill-white transition-all duration-[600ms]" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="product-list">
                    @if($products->isEmpty())
                    <div class="text-center text-white text-[20px] py-[50px]">
                        No products found.
                    </div>
                    @else
                    @include('frontend.partials.product-list', ['products' => $products])
                    @endif
                </div>
                <div id="product-loader" class="text-center my-3 text-white hidden">
                  <div class="loader"></div>
                </div>
                <div class="text-center mt-4 text-white" id="load-more-wrapper">
                  <button id="load-more-btn" class="btn btn-primary">
                    Load More...
                  </button>
                </div>
            </div>
        </div>

    </main>
</section>
<!--//product listing-->

<script>
    // FILTER SCRIPT
    /* GLOBAL STATE VARIABLES */
    let selectedBrands = [];
    let currentSort = "newest";
    let currentView = "gridview";


    /* PRICE RANGE FILTER */
    /*document.addEventListener('DOMContentLoaded', () => {
        const minSlider = document.getElementById('range-min');
        const maxSlider = document.getElementById('range-max');
        const minInput = document.getElementById('min-price');
        const maxInput = document.getElementById('max-price');
        const progress = document.getElementById('slider-progress');

        if (!minSlider || !maxSlider || !minInput || !maxInput || !progress) return;

        const maxPrice = parseInt(maxSlider.max);

        function updateProgress(minVal, maxVal) {
            const minPercent = (minVal / maxPrice) * 100;
            const maxPercent = 100 - (maxVal / maxPrice) * 100;
            progress.style.left = minPercent + "%";
            progress.style.right = maxPercent + "%";
        }

        // Update slider when input changes
        function inputChanged() {
            let minVal = parseInt(minInput.value) || 0;
            let maxVal = parseInt(maxInput.value) || maxPrice;
            // Clamp values
            minVal = Math.max(0, Math.min(minVal, maxPrice));
            maxVal = Math.max(0, Math.min(maxVal, maxPrice));
            minInput.value = minVal;
            maxInput.value = maxVal;
            minSlider.value = minVal;
            maxSlider.value = maxVal;
            updateProgress(minVal, maxVal);
            filterProducts();
        }

        // Update input when slider changes
        function sliderChanged() {
            let minVal = parseInt(minSlider.value);
            let maxVal = parseInt(maxSlider.value);

            minInput.value = minVal;
            maxInput.value = maxVal;

            updateProgress(minVal, maxVal);
            filterProducts();
        }

        minSlider.addEventListener('input', sliderChanged);
        maxSlider.addEventListener('input', sliderChanged);

        minInput.addEventListener('input', inputChanged);
        maxInput.addEventListener('input', inputChanged);

        // Initialize progress
        updateProgress(parseInt(minSlider.value), parseInt(maxSlider.value));
    });*/

    function initPriceFilter(container) {
		const minSlider = container.querySelector('.range-min');
		const maxSlider = container.querySelector('.range-max');
		const minInput = container.querySelector('.min-price');
		const maxInput = container.querySelector('.max-price');
		const progress = container.querySelector('.slider-progress');

		if (!minSlider || !maxSlider || !minInput || !maxInput || !progress) return;

		const maxPrice = parseInt(maxSlider.max);

		function updateProgress(minVal, maxVal) {
			const minPercent = (minVal / maxPrice) * 100;
			const maxPercent = 100 - (maxVal / maxPrice) * 100;
			progress.style.left = minPercent + "%";
			progress.style.right = maxPercent + "%";
		}

		function inputChanged() {
			let minVal = parseInt(minInput.value) || 0;
			let maxVal = parseInt(maxInput.value) || maxPrice;

			minVal = Math.max(0, Math.min(minVal, maxPrice));
			maxVal = Math.max(0, Math.min(maxVal, maxPrice));

			minInput.value = minVal;
			maxInput.value = maxVal;

			minSlider.value = minVal;
			maxSlider.value = maxVal;

			updateProgress(minVal, maxVal);
			filterProducts();
		}

		function sliderChanged() {
			let minVal = parseInt(minSlider.value);
			let maxVal = parseInt(maxSlider.value);

			minInput.value = minVal;
			maxInput.value = maxVal;

			updateProgress(minVal, maxVal);
			filterProducts();
		}

		minSlider.addEventListener('input', sliderChanged);
		maxSlider.addEventListener('input', sliderChanged);

		minInput.addEventListener('input', inputChanged);
		maxInput.addEventListener('input', inputChanged);

		updateProgress(parseInt(minSlider.value), parseInt(maxSlider.value));
	}

	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('.price-filter').forEach(initPriceFilter);
	});


    /* BRAND FILTER */
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('brand-search');
        const brandItems = document.querySelectorAll('.brand-item');
        if (!searchInput || brandItems.length === 0) {
            return;
        }
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            brandItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                if (name.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    /* CATEGORY FILTER SEARCH */
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('category-search');
        const categoryItems = document.querySelectorAll('.category-item');
        if (!searchInput || categoryItems.length === 0) {
            return;
        }

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            categoryItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                if (name.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    /* SORTING */
    document.addEventListener('DOMContentLoaded', () => {
        const sortButton = document.querySelector('el-dropdown button');
        const sortOptions = document.querySelectorAll('.sort-option');

        if (!sortButton || sortOptions.length === 0) return;

        sortOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                currentSort = option.dataset.sort;
                const sortText = option.textContent;
                sortButton.querySelector('span').textContent = `Sort by: ${sortText}`;
                filterProducts();
            });
        });
    });

    /* VIEW SWITCH */
    document.addEventListener('DOMContentLoaded', () => {
        const viewButtons = document.querySelectorAll('.view-switch');
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                currentView = this.dataset.view;
                filterProducts();
            });
        });
    });

    /* FILTER FUNCTION */
    function filterProducts() {
      	page = 1;
		document.getElementById('load-more-wrapper').style.display = 'block';

        const categories = Array.from(
            document.querySelectorAll('input[name="categories[]"]:checked')
        ).map(el => el.value);
        const selectedBrands = Array.from(
            document.querySelectorAll('input[name="brands[]"]:checked')
        ).map(el => el.value);


		const visibleFilter = [...document.querySelectorAll('.price-filter')]
			.find(el => el.offsetParent !== null);

		const min_price = parseInt(
			visibleFilter?.querySelector('.min-price')?.value
		) || 0;

		const max_price = parseInt(
			visibleFilter?.querySelector('.max-price')?.value
		) || 300000;


        const url = `/products`;
        const params = new URLSearchParams({
            min_price,
            max_price,
            sort: currentSort,
            view: currentView
        });

        categories.forEach(cat => params.append('categories[]', cat));
        selectedBrands.forEach(brand => params.append('brands[]', brand));

        fetch(`${url}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const wrapper = document.getElementById('product-list-wrapper');
                wrapper.innerHTML = html;

                // Scroll to top of product list
                const offsetTop = wrapper.getBoundingClientRect().top + window.pageYOffset - 100; // adjust 100px if header exists
                window.scrollTo({ top: offsetTop, behavior: 'smooth' });

                // Update product count dynamically
                const countSpan = document.getElementById('product-count');
                if (countSpan) {
                    const productCount = wrapper.querySelectorAll('.product-card').length;
                    if (productCount === 0) {
                        countSpan.textContent = `Items 0 of 0`;
                    } else {
                        countSpan.textContent = `Items 1-${productCount} of ${productCount}`;
                    }
                    document.getElementById('total-product-count').textContent = `${productCount}`;
                }
            })
            .catch(err => console.error('Filter products error:', err));
    }

    /* CLEAR FILTER */
    document.addEventListener('DOMContentLoaded', () => {
        const clearBtn = document.getElementById('clear-filters');
        if (!clearBtn) return;

        clearBtn.addEventListener('click', () => {
            // Reset sliders and inputs
			const minSlider = document.querySelector('.range-min');
			const maxSlider = document.querySelector('.range-max');
			const minInput = document.querySelector('.min-price');
			const maxInput = document.querySelector('.max-price');

            if (minSlider && maxSlider && minInput && maxInput) {
                minSlider.value = 0;
                maxSlider.value = maxSlider.max;
                minInput.value = 0;
                maxInput.value = maxSlider.max;
            }

            // Uncheck all categories and brands
            document.querySelectorAll('input[name="brands[]"]').forEach(cb => cb.checked = false);
            selectedBrands = [];

            // Reset sort and view if desired
            currentSort = "newest";
            currentView = "gridview";

            filterProducts();
        });
    });

    /* CATEGORY + BRAND CHECKBOX CHANGE */
	document.addEventListener('change', function(e) {
		if (e.target.matches('input[name="categories[]"]')) {
			filterProducts();
		}

		if (e.target.matches('input[name="brands[]"]')) {
			filterProducts();
		}
	});

    /* INITIAL LOAD */
    document.addEventListener('DOMContentLoaded', () => {
        filterProducts();
    });

	// CATEGORY + BRAND CHECKBOX CHANGE (desktop + mobile)
	document.addEventListener('change', function(e) {
		if (e.target.matches('.category-checkbox')) {
			const checkbox = e.target;

			// Scope everything to the parent form (desktop or mobile)
			const form = checkbox.closest('form') || document;

			// Child checkboxes inside the same form
			const childIds = checkbox.dataset.childIds ? checkbox.dataset.childIds.split(',') : [];
			childIds.forEach(id => {
				const childCheckbox = form.querySelector(`#filter-category-${id}`);
				if (childCheckbox) childCheckbox.checked = checkbox.checked;
			});

			// Ensure at least one category is selected in this form
			const checkedCount = form.querySelectorAll('.category-checkbox:checked').length;
			if (checkedCount === 0) {
				checkbox.checked = true;
				toastr.error("At least one category must be selected.");
			}

			// Trigger filterProducts (fetch values from the active filter)
			filterProducts();
		}

		// BRAND CHECKBOXES
		if (e.target.matches('input[name="brands[]"]')) {
			filterProducts();
		}
	});

   

  	// LOAD MORE SCRIPT
	let page = 1;
  	let loading = false;

  	document.getElementById('load-more-btn')?.addEventListener('click', async function () {
      	if (loading) return;
		loading = true;
		page++;
      	document.getElementById('product-loader').classList.remove('hidden');

      	try {
			const url = new URL(window.location.href);
			url.searchParams.set('page', page);
			url.searchParams.set('scroll', 1);
          	const res = await fetch(url, {
              	headers: {
                  	'X-Requested-With': 'XMLHttpRequest'
              	}
          	});
          	const html = await res.text();
          	document
              .getElementById('product-list-wrapper')
              .insertAdjacentHTML('beforeend', html);
			updateProductCount(); 
		} catch (e) {
			console.error(e);
		}
      	document.getElementById('product-loader').classList.add('hidden');
      	loading = false;
  	});

	function updateProductCount() {
		const countEl = document.getElementById('product-count');
		const total = parseInt(countEl.dataset.total);
		let visible = 0;

		// Check active view
		if (document.querySelector('[x-show="activeTab === \'gridview\'"]').offsetParent !== null) {
			visible = document.querySelectorAll('#product-list .product-card').length;
		} else {
			visible = document.querySelectorAll('#product-list .product-card-list').length;
		}
		countEl.innerText = `Items 1-${visible} of ${visible}`;
    	document.getElementById('total-product-count').innerText = `${visible}`;
    	document.getElementById('category-count').innerText = `${visible}`;
	}
</script>
@endsection