@extends('frontend.layouts.app')

@section('title', 'Shop')
@section('content')

{{-- @php

Log::info($_REQUEST);
@endphp --}}
<!--inner banner-->
<section class="bg-[#000000] px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] pt-[100px] xl:pt-[200px] pb-[50px] xl:pb-[100px] relative rounded-br-[30px] xl:rounded-br-[100px] rounded-bl-[30px] xl:rounded-bl-[100px] before:content-[''] before:absolute before:top-[-200%] xl:before:top-[-130%] before:left-[50%] before:translate-x-[-50%] before:w-[900px] before:h-[900px] before:bg-[#2161C7] before:rounded-full before:filter before:blur-[200px] before:z-[0] before:opacity-[0.7]">
	<div class="section-title mb-[30px] relative">
		<h3 class="w-full text-[30px] md:text-[50px] text-white font-bold uppercase text-center leading-[40px] md:leading-[50px] m-[0] md:mb-[0px]">
			{{ $page_content['title'] ?? 'Shop by Categories' }}
		</h3>
	</div>
	<div class="swiper cateswiper relative">
		<div class="swiper-wrapper">
			@php
				$categoryList = !empty($categorySlider) && count($categorySlider) ? $categorySlider : $categories->where('parent_id', 0);
			@endphp

			@foreach ($categoryList as $category)
			<div class="swiper-slide" data-swiper-autoplay="8000">
				<a href="{{ route('shop.category',$category->category_translations->first()->slug) }}" class="flex flex-col items-center justify-center gap-[15px]">
					<div class="category-thumb flex align-center bg-[#272930] p-[20px] lg:p-[20px] rounded-full h-[75px] lg:h-[80px] xl:h-[95px] w-[75px] lg:w-[80px] xl:w-[95px] overflow-hidden">
						<img src="{{ $category->iconImage ? Storage::url($category->iconImage->file_name) : asset('assets/images/placeholder.png') }}" alt="{{ $category->name }}" title="{{$category->name}}" class="w-full m-auto">
					</div>
					<h4 class="text-white text-center font-medium text-[12px] xl:text-[14px]">{{strtoupper($category->name)}}</h4>
				</a>
			</div>
			@endforeach
		</div>
	</div>
	<div class="controls absolute flex items-center justify-between gap-[40px] w-full top-[50%] left-[0] right-[0] z-[1]">
		<div class="swiper-button-prev !relative !left-[-100px] !right-auto !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
		<div class="swiper-button-next !relative !right-[-100px] !left-auto !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
	</div>
</section>
<!--//categories-->


<!--product listing-->
<section class="bg-[#0F161B]">

<!-- Mobile filter dialog -->
  <el-dialog>
    <dialog id="mobile-filters" class="m-0 overflow-hidden p-0 backdrop:bg-transparent xl:hidden">
      <el-dialog-backdrop class="fixed inset-0 bg-black/50 backdrop-blur-[10px] transition-opacity duration-300 ease-linear data-[closed]:opacity-0"></el-dialog-backdrop>

      <div tabindex="0" class="fixed inset-0 flex focus:outline focus:outline-0">
        <el-dialog-panel class="relative ml-auto flex size-full max-w-xs transform flex-col overflow-y-auto bg-black pb-6 p-4 shadow-xl transition duration-300 ease-in-out data-[closed]:translate-x-full">
          <div class="flex items-center justify-between px-4">
            <h2 class="text-lg font-medium text-white uppercase">Filters</h2>
            <button type="button" command="close" commandfor="mobile-filters" class="relative -mr-2 flex size-10 items-center justify-center rounded-md p-2 text-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <span class="absolute -inset-0.5"></span>
              <span class="sr-only">Close menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
            <div id="mobile-filter"></div>
        </el-dialog-panel>
      </div>
    </dialog>
  </el-dialog>
  <!--//Mobile filter dialog -->

	<main class="px-[16px] md:px-[30px] lg:px-[50px] xl:px-[100px] 2xl:px-[140px] py-[50px] xl:py-[100px]">
		<div class="flex flex-col xl:grid xl:grid-cols-4 xl:gap-[50px]">
			<div>
				<div id="filter-wrapper">
					<form class="hidden xl:block">
						<!-- <div id="clear-filters" class="text-[#898989] text-[14px] text-right mt-2 cursor-pointer">Clear All</div> -->
						<button id="clear-filters" type="button"
							class="inline-block w-full text-[#898989] text-[13px] md:text-[14px] font-medium 
									px-3 py-1.5 mt-2 rounded-[6px] transition-all duration-200 
									hover:bg-white/10 hover:text-white cursor-pointer flex items-center gap-2 mb-[10px]">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
							</svg>
							Clear All
						</button>
						<!--categories filter-->
						<div class="bg-black/30 backdrop-blur-[60px] px-[30px] py-[15px] rounded-[20px] mb-[10px] category-box">
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
							<el-disclosure id="filter-section-categories" hidden class="pt-6 [&:not([hidden])]:block border-t border-transparent xl:border-[#282B34] pb-[20px]">

								<div class="w-full">
									<div class="space-y-4">

										<div class="relative mb-6">
											<span class="absolute inset-y-0 left-0 flex items-center pl-3">
												<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
												</svg>
											</span>
											<input type="text" id="category-search" placeholder="Search Category" class="w-full bg-[#282B34] text-white text-sm rounded-[10px] focus:ring-[#3E81FF] focus:border-[#3E81FF] block pl-10 p-[10px] outline-none transition-all border-none">
										</div>

										<div class="max-h-[300px] overflow-y-auto custom-scrollbar px-2 space-y-4">
											@php
											if (!function_exists('renderCategories')) {
											function renderCategories($categories, $groupedCategories, $level = 0) {
											foreach ($categories as $category) {
											// Calculate sum of products for this category + all its children
											$childIds = getAllChildIds($category->id, $groupedCategories);
											$allIds = array_merge([$category->id], $childIds);

											$productCount = \App\Models\Product::whereIn('category_id', $allIds)->count();

											$padding = $level * 20; // indentation
											@endphp

											<div class="flex gap-[15px] items-center category-item" data-name="{{ $category->name }}" style="padding-left: {{ $padding }}px;">
												<div class="flex h-5 shrink-0 items-center">
													<div class="group grid size-4 grid-cols-1 w-full">
														<input
															id="filter-category-{{ $category->id }}"
															type="checkbox"
															name="categories[]"
															value="{{ $category->id }}"
															class="category-checkbox h-[20px] w-[20px] col-start-1 row-start-1 appearance-none rounded bg-[#282B34] checked:bg-[#2161C7] border-none cursor-pointer !outline-none !ring-0 !ring-offset-0 transition-all duration-200"
															data-child-ids="{{ implode(',', $childIds) }}" />
													</div>
												</div>
												<label for="filter-category-{{ $category->id }}" class="relative top-[5px] text-[15px] text-white">
													{{ $category->name }}
													<span class="text-[15px] text-[#50525C] ml-[10px]">{{ $productCount }}</span>
												</label>
											</div>

											@php
											if (isset($groupedCategories[$category->id])) {
											renderCategories($groupedCategories[$category->id], $groupedCategories, $level + 1);
											}
											}
											}
											}

											// Helper to get all child IDs recursively
											if (!function_exists('getAllChildIds')) {
											function getAllChildIds($parentId, $groupedCategories) {
											$ids = [];
											if (isset($groupedCategories[$parentId])) {
											foreach ($groupedCategories[$parentId] as $child) {
											$ids[] = $child->id;
											$ids = array_merge($ids, getAllChildIds($child->id, $groupedCategories));
											}
											}
											return $ids;
											}
											}
											@endphp

											{{-- Group categories by parent_id --}}
											@php
											$groupedCategories = $categories->groupBy('parent_id');
											renderCategories($groupedCategories[0] ?? [], $groupedCategories);
											@endphp
										</div>

									</div>
								</div>

							</el-disclosure>
						</div>
						<!--//categories filter-->

						<!--price range-->
						<div class="bg-black/30 backdrop-blur-[60px] px-[30px] py-[15px] rounded-[20px] mb-[10px] price-box">
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
							<el-disclosure id="filter-section-price" hidden class="pt-6 [&:not([hidden])]:block border-t border-transparent xl:border-[#282B34] pb-[30px]">
								<div class="w-full price-filter">
									<div class="flex justify-between items-center mb-8 gap-[20px] align-center">
										<div class="w-full">
											<span class="text-gray-400 text-xs block mb-[10px]">Min</span>
											<div class="bg-[#282B34] rounded-[10px] w-full price-input-box">
												<input type="number" class="min-price w-full bg-transparent font-medium text-[14px] !text-white focus:outline-none border-none" value="0" min="0" max="300000" step="100">
											</div>
										</div>
										
										<div class="w-full">
											<span class="text-gray-400 text-xs block mb-[10px] text-right">Max</span>
											<div class="bg-[#282B34] rounded-[10px] text-right w-full price-input-box">
												<input type="number" class="max-price w-full !text-white bg-transparent font-medium text-[14px] focus:outline-none text-right !border-none" value="300000" min="0" max="300000" step="100">
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
						<div class="bg-black/30 backdrop-blur-[60px] px-[30px] py-[15px] rounded-[20px] mb-[10px] brand-box">
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
							<el-disclosure id="filter-section-brand" hidden class="pt-6 [&:not([hidden])]:block border-t border-transparent xl:border-[#282B34] pb-[20px]">

								<div class="w-full">
									<div id="brand-grid" class="space-y-4">
										<div class="relative mb-6">
											<span class="absolute inset-y-0 left-0 flex items-center pl-3">
												<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
												</svg>
											</span>
											<input type="text" id="brand-search" placeholder="Search brands (e.g. Nvidia)" class="w-full bg-[#282B34] text-white text-sm rounded-[10px] focus:ring-[#3E81FF] focus:border-[#3E81FF] block pl-10 p-[10px] outline-none transition-all border-none">
										</div>

										@if(!empty($brands))
										@foreach ($brands as $brand)
										<div class="flex gap-[15px] align-center items-center brand-item" data-name="{{$brand->name}}">
											<div class="flex h-5 shrink-0 items-center">
												<div class="group grid size-4 grid-cols-1 w-full">
													<input id="filter-brand-{{ $brand->id }}" type="checkbox" name="brands[]" value="{{$brand->id}}" class="category-checkbox h-[20px] w-[20px] col-start-1 row-start-1 appearance-none rounded bg-[#282B34] checked:bg-[#2161C7] border-none cursor-pointer !outline-none !ring-0 !ring-offset-0 transition-all duration-200" />
													<svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
														<path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
														<path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
													</svg>
												</div>
											</div>
											<label for="filter-brand-{{ $brand->id }}" class="relative top-[5px] text-[15px] text-white">{{$brand->name}} <span class="text-[15px] text-[#50525C] ml-[10px]">{{ $brand->products_count }}</span></label>
										</div>
										@endforeach
										@endif
										<a href="{{ route('brands.list') }}" class="block mt-[30px] w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 text-white hover:bg-white hover:text-black">view all brands</a>
											
									</div>
								</div>

							</el-disclosure>
						</div>
						<!--//brand filter-->
					</form>
				</div>

				<!-- Desktop Filters -->
				<div id="desktop-filter"></div>
				<!--// Desktop Filters -->

				<!--promotion banners-->
				@if(!empty($banners))
				<div class="!hidden xl:!block swiper promobnrswiper relative">
                    <div class="swiper-wrapper">
						@foreach($banners as $banner)
						<div class="swiper-slide" data-swiper-autoplay="8000">
                            <a href="{{ getBannerUrl($banner) }}">
								<img src="{{ $banner->mainImage ? Storage::url($banner->mainImage->file_name) : '' }}" class="w-full" alt="{{ $banner->title }}" title="{{ $banner->title }}">
							</a>
                        </div>
						@endforeach
					</div>
					<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
				</div>
				@endif
				<!--//promotion banners-->
			</div>

			<div class="col-span-3" x-data="{ activeTab: '{{ request('view', 'gridview') }}' }">

				<div class="flex flex-col xl:flex-row items-center justify-between gap-[15px] xl:gap-[0px] w-full">
					<h1 class="text-[30px] 2xl:text-[50px] text-white font-bold text-center xl:text-left uppercase w-full">
						{{ $page_content['listing_title'] ?? 'All Products' }} 
					</h1>
					<div class="flex flex-col xl:flex-row items-center justify-between gap-[15px] xl:gap-[15px] w-full">
						<span class="text-[#898989] text-[14px] w-full text-center xl:text-right" id="product-count" data-per-page="{{ $products->perPage() }}" data-total="{{ $products->total() }}">
							@if($products->count() > 0)
								Items {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }}
							@else
								Items 0 of 0
							@endif
						</span>
						<el-dropdown class="relative block text-left w-full xl:w-[600px]">
							<button class="group flex border border-[#282B34] rounded-[10px] p-[20px] justify-between text-sm font-medium text-white !w-full md:w-[230px]">
								<span class="mr-[10px] text-[14px] text-white">Sort by:
									<span class="pl-3 inline-flex">
										@switch($sort)
										@case('oldest') Oldest @break
										@case('newest') Newest @break
										@case('price_low_high') Price: Low to High @break
										@case('price_high_low') Price: High to Low @break
										@default Newest
										@endswitch
									</span>
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

						<div id="view-switcher" class="fixed xl:static bottom-[75px] xl:bottom-[0] z-[99] bg-[#212328] xl:bg-transparent button-group p-[5px] border border-[#282B34] rounded-[10px] gap-[5px] flex flex-row right-0 left-0 mx-auto w-fit">
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
					<div id="product-loader" class="text-center my-3 text-white hidden">
						<div class="loader"></div>
					</div>
					<div class="text-center mt-4 text-white" id="load-more-wrapper">
						<button id="load-more-btn" class="mt-[30px] w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-[600ms] text-white hover:bg-white/5" title="Click to View More Products">
							Load More
						</button>
					</div>
					@endif
				</div>
				
			</div>
			
		</div>

	</main>
</section>
<!--//product listing-->

<script>
	// FILTER SCRIPT
	/* ===============================
	GLOBAL STATE VARIABLES
	=============================== */

	let selectedBrands = [];
	let currentSort = "newest";
	let currentView = "gridview";


	/* PRICE RANGE FILTER */
	function initPriceFilter(container) {
		const minSlider = container.querySelector('.range-min');
		const maxSlider = container.querySelector('.range-max');
		const minInput = container.querySelector('.min-price');
		const maxInput = container.querySelector('.max-price');
		const progress = container.querySelector('.slider-progress');

		if (!minSlider || !maxSlider || !minInput || !maxInput || !progress) return;

		minInput.addEventListener('keydown', function (e) {
			if (e.key === 'Enter') {
				e.preventDefault();
				this.blur(); // triggers input update safely
			}
		});

		maxInput.addEventListener('keydown', function (e) {
			if (e.key === 'Enter') {
				e.preventDefault();
				this.blur();
			}
		});


		const maxPrice = parseInt(maxSlider.max);

		function updateProgress(minVal, maxVal) {
			const minPercent = (minVal / maxPrice) * 100;
			const maxPercent = 100 - (maxVal / maxPrice) * 100;
			progress.style.left = minPercent + "%";
			progress.style.right = maxPercent + "%";
		}

		function inputChanged(e) {

			const isMin = e.target.classList.contains('min-price');
			const isMax = e.target.classList.contains('max-price');

			let minVal = minInput.value.trim();
			let maxVal = maxInput.value.trim();

			// allow empty typing state
			if (minVal === '' || maxVal === '') {
				return;
			}

			minVal = parseInt(minVal);
			maxVal = parseInt(maxVal);

			if (isNaN(minVal)) minVal = 0;
			if (isNaN(maxVal)) maxVal = maxPrice;

			minVal = Math.max(0, Math.min(minVal, maxPrice));
			maxVal = Math.max(0, Math.min(maxVal, maxPrice));

			minInput.value = minVal;
			maxInput.value = maxVal;

			// optional: prevent crossing
			if (minVal > maxVal) {
				if (isMin) {
					maxVal = minVal;
				} else {
					minVal = maxVal;
				}
			}

			minSlider.value = minVal;
			maxSlider.value = maxVal;

			updateProgress(minVal, maxVal);

			filterProducts();
		}

		function normalizeInputs() {

			let minVal = parseInt(minInput.value);
			let maxVal = parseInt(maxInput.value);

			if (isNaN(minVal)) minVal = 0;
			if (isNaN(maxVal)) maxVal = maxPrice;

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

		minInput.addEventListener('blur', normalizeInputs);
		maxInput.addEventListener('blur', normalizeInputs);

		updateProgress(parseInt(minSlider.value), parseInt(maxSlider.value));
	}

	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('.price-filter').forEach(initPriceFilter);
	});

	/* ===============================
	BRAND FILTER
	=============================== */

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
		// document.getElementById('load-more-wrapper').style.display = 'block';
		// document.getElementById('product-loader').classList.remove('hidden');
		showLoader();

		const categories = Array.from(
			document.querySelectorAll('input[name="categories[]"]:checked')
		).map(el => el.value);

		const selectedBrands = Array.from(
			document.querySelectorAll('input[name="brands[]"]:checked')
		).map(el => el.value);

		// const visibleFilter = [...document.querySelectorAll('.price-filter')]
		// 	.find(el => el.offsetParent !== null);

		const activeFilterWrapper = document.querySelector(
			'#filter-wrapper.is-mobile, #filter-wrapper.is-desktop'
		);

		const visibleFilter = activeFilterWrapper?.querySelector('.price-filter');

		const min_price = parseInt(
			visibleFilter?.querySelector('.min-price')?.value
		) || 0;

		const max_price = parseInt(
			visibleFilter?.querySelector('.max-price')?.value
		) || 300000;

		const url = `/products`;

		// Store filters globally
		currentFilters = new URLSearchParams({
			min_price,
			max_price,
			sort: currentSort,
			view: currentView,
			search: '{{ request('search', '') }}',
			condition: '{{ request('condition', '') }}',
		});

		categories.forEach(cat => currentFilters.append('categories[]', cat));
		selectedBrands.forEach(brand => currentFilters.append('brands[]', brand));

		return fetch(`${url}?${currentFilters.toString()}`, {
				method: 'GET',
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
			.then(res => res.json())
			.then(data => {
				// document.getElementById('product-loader').classList.add('hidden');
				
				const wrapper = document.getElementById('product-list-wrapper');
				
				// Show / Hide load more
				const loadMore = document.getElementById('load-more-wrapper');

				if (!data.html.trim()) {
					loadMore.style.display = 'none';

					wrapper.innerHTML = `
						<div class="text-white text-center py-10">
							No Products Found!
						</div>
					`;
				} else {
					wrapper.innerHTML = data.html;
					loadMore.style.display = data.hasMore ? 'block' : 'none';
				}

				hideLoader();
				
				updateProductCount();

				// Scroll
				// const offsetTop = wrapper.getBoundingClientRect().top + window.pageYOffset - 100;
				// window.scrollTo({ top: offsetTop, behavior: 'smooth' });

				
			})
			.catch(err => console.error('Filter products error:', err));
	}

	document.addEventListener('DOMContentLoaded', () => {
		const clearBtn = document.getElementById('clear-filters');
		if (!clearBtn) return;

		clearBtn.addEventListener('click', (e) => {
			e.preventDefault(); // IMPORTANT because button inside form

			const minSlider = document.querySelector('.range-min');
			const maxSlider = document.querySelector('.range-max');
			const minInput = document.querySelector('.min-price');
			const maxInput = document.querySelector('.max-price');

			const maxVal = parseInt(maxSlider?.max || 300000);

			if (minSlider && maxSlider && minInput && maxInput) {
				minSlider.value = 0;
				maxSlider.value = maxVal;
				minInput.value = 0;
				maxInput.value = maxVal;
			}

			// update slider bar
			updateProgress(0, maxVal);

			// Reset checkboxes
			document.querySelectorAll('input[name="categories[]"], input[name="brands[]"]')
				.forEach(cb => cb.checked = false);

			selectedBrands = [];

			currentSort = "newest";
			currentView = "gridview";

			$request->merge([
				'search' => '',
				'condition' => '',
			]);
			

			// reset URL clean
			window.history.replaceState({}, '', '/products');

			filterProducts();
		});
	});

	function updateProgress(minVal, maxVal) {
		const container = document.querySelector('.price-filter');
		if (!container) return;

		const progress = container.querySelector('.slider-progress');
		const maxPrice = parseInt(container.querySelector('.range-max')?.max || 0);

		if (!progress || !maxPrice) return;

		const minPercent = (minVal / maxPrice) * 100;
		const maxPercent = 100 - (maxVal / maxPrice) * 100;

		progress.style.left = minPercent + "%";
		progress.style.right = maxPercent + "%";
	}

	/* CATEGORY + BRAND CHECKBOX CHANGE */
	document.addEventListener('change', function (e) {
		if (
			e.target.matches('input[name="categories[]"]') ||
			e.target.matches('input[name="brands[]"]')
		) {
			filterProducts().then(() => {
				const wrapper = document.getElementById('product-list-wrapper');
				const offsetTop =
					wrapper.getBoundingClientRect().top +
					window.pageYOffset - 100;
				window.scrollTo({
					top: offsetTop,
					behavior: 'smooth'
				});
			});
		}
	});

	/* INITIAL LOAD */

	document.addEventListener('DOMContentLoaded', () => {
		filterProducts();
		hideLoader();
	});

	// checkbox checked function in filter
	document.addEventListener('change', function(e) {
		if (e.target.matches('input[id^="filter-category-"]')) {
			const checkbox = e.target;
			const childIds = checkbox.dataset.childIds 
				? checkbox.dataset.childIds.split(',') 
				: [];

			childIds.forEach(id => {
				document.querySelectorAll(`#filter-category-${id}`).forEach(child => {
					child.checked = checkbox.checked;
				});
			});

			filterProducts();
		}
	});	
	// FILTER SCRIPT

	// LOAD MORE SCRIPT
	let page = 1;
	let lastPage = {{ $products->lastPage() }};
	let loading = false;

	document.getElementById('load-more-btn').addEventListener('click', async function() {
		if (loading || page >= lastPage) return;
		loading = true;
		page++;

		await loadMoreProducts(page);
		loading = false;

		if (page >= lastPage) {
			document.getElementById('load-more-wrapper').style.display = 'none';
		}
	});

	async function loadMoreProducts(page) {
		const loader = document.getElementById('product-loader');
		loader.classList.remove('hidden');

		try {

			currentFilters.set('page', page);

			const res = await fetch(`/products?${currentFilters.toString()}`, {
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			});

			const data = await res.json();

			if (!data.html.trim()) {
				document.getElementById('load-more-wrapper').style.display = 'none';
				return;
			}

			document.getElementById('product-list-wrapper')
				.insertAdjacentHTML('beforeend', data.html);

			if (!data.hasMore) {
				document.getElementById('load-more-wrapper').style.display = 'none';
			}

			updateProductCount();

		} catch (err) {
			console.error(err);
		} finally {
			loader.classList.add('hidden');
		}
	}
	

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

		if (visible === 0) {
			countEl.innerText = `Items 0 of 0`;
			document.getElementById('product-list-wrapper').innerHTML = `
						<div class="text-white text-center py-10">
							No Products Found!
						</div>`;
			return;
		}
		countEl.innerText = `Items 1-${visible} of ${total}`;
	}

	function showLoader() {
		document.getElementById('global-loader').classList.remove('hidden');
	}

	function hideLoader() {
		document.getElementById('global-loader').classList.add('hidden');
	}
</script>
@endsection