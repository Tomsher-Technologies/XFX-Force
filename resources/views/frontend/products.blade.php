@extends('frontend.layouts.app')

@section('title', 'Shop')
@section('content')

@php

Log::info($_REQUEST);
@endphp
<!--inner banner-->
<section class="bg-[#000000] px-[16px] md:px-[250px] pt-[200px] pb-[100px] relative border rounded-br-[100px] rounded-bl-[100px] before:content-[''] before:absolute before:top-[-130%] before:left-[50%] before:translate-x-[-50%] before:w-[900px] before:h-[900px] before:bg-[#2161C7] before:rounded-full before:filter before:blur-[200px] before:z-[0] before:opacity-[0.7]">
    <div class="section-title mb-[30px] relative">
        <h3 class="text-[50px] text-[white] capitalize font-bold text-center uppercase text-center">Shop by Categories</h3>
    </div>
    <div class="swiper categoryswiper relative">
        <div class="swiper-wrapper">
            @foreach ($categories as $category)
            <div class="swiper-slide" data-swiper-autoplay="8000">
                <a href="#" class="flex flex-col items-center justify-center gap-[15px]">
                    <div class="category-thumb flex align-center bg-[#272930] p-[30px] rounded-full h-[130px] w-[130px]">
                        <img src="{{ $category->iconImage ? Storage::url($category->iconImage->file_name) : '' }}" alt="{{ $category->name }}" alt="Graphics Card" title="Graphics Card" class="w-full m-auto">
                    </div>
                    <h4 class="text-[white] text-center font-medium text-[16px] capitalize">{{$category->name}}</h4>
                </a>
            </div>
            @endforeach
        </div>
        <div class="controls absolute flex items-center justify-between gap-[40px] w-full top-[50%] left-[0] right-[0] z-[1]">
            <div class="swiper-button-prev !relative !left-[-100px] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
            <div class="swiper-button-next !relative !right-[-100px] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
        </div>
    </div>
</section>
<!--//categories-->


<!--product listing-->
<section class="bg-[#0F161B]">
    <!-- Mobile filter dialog -->
    <el-dialog>
        <dialog id="mobile-filters" class="m-0 overflow-hidden p-0 backdrop:bg-transparent lg:hidden">
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

                    <!-- Filters -->
                    <form class="mt-4 border-t border-gray-200">
                        <h3 class="sr-only">Categories</h3>
                        <ul role="list" class="px-2 py-3 font-medium text-gray-900">
                            <li>
                                <a href="#" class="block px-2 py-3">Totes</a>
                            </li>
                            <li>
                                <a href="#" class="block px-2 py-3">Backpacks</a>
                            </li>
                            <li>
                                <a href="#" class="block px-2 py-3">Travel Bags</a>
                            </li>
                            <li>
                                <a href="#" class="block px-2 py-3">Hip Bags</a>
                            </li>
                            <li>
                                <a href="#" class="block px-2 py-3">Laptop Sleeves</a>
                            </li>
                        </ul>

                        <div class="border-t border-gray-200 px-4 py-6">
                            <h3 class="-mx-2 -my-3 flow-root">
                                <button type="button" command="--toggle" commandfor="filter-section-mobile-color" class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                    <span class="font-medium text-gray-900">Color</span>
                                    <span class="ml-6 flex items-center">
                                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [[aria-expanded='true']_&]:hidden">
                                            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                        </svg>
                                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [&:not([aria-expanded='true']_*)]:hidden">
                                            <path d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" fill-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <el-disclosure id="filter-section-mobile-color" hidden class="pt-6 [&:not([hidden])]:block">
                                <div class="space-y-6">
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-color-0" type="checkbox" name="color[]" value="white" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-color-0" class="min-w-0 flex-1 text-gray-500">White</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-color-1" type="checkbox" name="color[]" value="beige" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-color-1" class="min-w-0 flex-1 text-gray-500">Beige</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-color-2" type="checkbox" name="color[]" value="blue" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-color-2" class="min-w-0 flex-1 text-gray-500">Blue</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-color-3" type="checkbox" name="color[]" value="brown" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-color-3" class="min-w-0 flex-1 text-gray-500">Brown</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-color-4" type="checkbox" name="color[]" value="green" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-color-4" class="min-w-0 flex-1 text-gray-500">Green</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-color-5" type="checkbox" name="color[]" value="purple" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-color-5" class="min-w-0 flex-1 text-gray-500">Purple</label>
                                    </div>
                                </div>
                            </el-disclosure>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-6">
                            <h3 class="-mx-2 -my-3 flow-root">
                                <button type="button" command="--toggle" commandfor="filter-section-mobile-category" class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                    <span class="font-medium text-gray-900">Category</span>
                                    <span class="ml-6 flex items-center">
                                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [[aria-expanded='true']_&]:hidden">
                                            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                        </svg>
                                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [&:not([aria-expanded='true']_*)]:hidden">
                                            <path d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" fill-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <el-disclosure id="filter-section-mobile-category" hidden class="pt-6 [&:not([hidden])]:block">
                                <div class="space-y-6">
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-category-0" type="checkbox" name="category[]" value="new-arrivals" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-category-0" class="min-w-0 flex-1 text-gray-500">New Arrivals</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-category-1" type="checkbox" name="category[]" value="sale" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-category-1" class="min-w-0 flex-1 text-gray-500">Sale</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-category-2" type="checkbox" name="category[]" value="travel" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-category-2" class="min-w-0 flex-1 text-gray-500">Travel</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-category-3" type="checkbox" name="category[]" value="organization" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-category-3" class="min-w-0 flex-1 text-gray-500">Organization</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-category-4" type="checkbox" name="category[]" value="accessories" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-category-4" class="min-w-0 flex-1 text-gray-500">Accessories</label>
                                    </div>
                                </div>
                            </el-disclosure>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-6">
                            <h3 class="-mx-2 -my-3 flow-root">
                                <button type="button" command="--toggle" commandfor="filter-section-mobile-size" class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                    <span class="font-medium text-gray-900">Size</span>
                                    <span class="ml-6 flex items-center">
                                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [[aria-expanded='true']_&]:hidden">
                                            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                        </svg>
                                        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 [&:not([aria-expanded='true']_*)]:hidden">
                                            <path d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" fill-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <el-disclosure id="filter-section-mobile-size" hidden class="pt-6 [&:not([hidden])]:block">
                                <div class="space-y-6">
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-size-0" type="checkbox" name="size[]" value="2l" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-size-0" class="min-w-0 flex-1 text-gray-500">2L</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-size-1" type="checkbox" name="size[]" value="6l" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-size-1" class="min-w-0 flex-1 text-gray-500">6L</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-size-2" type="checkbox" name="size[]" value="12l" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-size-2" class="min-w-0 flex-1 text-gray-500">12L</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-size-3" type="checkbox" name="size[]" value="18l" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-size-3" class="min-w-0 flex-1 text-gray-500">18L</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-size-4" type="checkbox" name="size[]" value="20l" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-size-4" class="min-w-0 flex-1 text-gray-500">20L</label>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1">
                                                <input id="filter-mobile-size-5" type="checkbox" name="size[]" value="40l" class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-mobile-size-5" class="min-w-0 flex-1 text-gray-500">40L</label>
                                    </div>
                                </div>
                            </el-disclosure>
                        </div>
                    </form>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>

    <main class="px-[16px] md:px-[140px] py-[100px]">
        <div class="grid grid-cols-4 gap-[50px]">
            <div>
                <!-- Filters -->
                <form class="hidden lg:block">

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

                                    <div class="relative mb-6">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </span>
                                        <input type="text" id="category-search" placeholder="Search Category" class="w-full bg-[#282B34] text-white text-sm rounded-[10px] focus:ring-[#3E81FF] focus:border-[#3E81FF] block pl-10 p-[15px] outline-none transition-all">
                                    </div>

                                    @foreach ($categories as $category)
                                    <div class="flex gap-[15px] align-center items-center category-item" data-name="{{$category->name}}">
                                        <div class="flex h-5 shrink-0 items-center">
                                            <div class="group grid size-4 grid-cols-1 w-full">
                                                <input id="filter-category-0" type="checkbox" name="categories[]" value="{{$category->id}}" class="h-[25px] w-[25px] col-start-1 row-start-1 appearance-none rounded border border-[#5F6370] bg-[#282B34] checked:border-indigo-600 checked:bg-[#2161C7] indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        <label for="filter-category-0" class="relative top-[5px] text-[15px] text-white">{{$category->name}} <span class="text-[15px] text-[#50525C] ml-[10px]">{{ $category->products_count }}</span></label>
                                    </div>
                                    @endforeach
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

                            <div class="w-full">
                                <div class="flex justify-between items-center mb-8 gap-[20px] align-center">
                                    <div class="w-full">
                                        <span class="text-gray-400 text-xs block mb-2">Min</span>
                                        <div class="bg-[#282B34] py-[10px] px-[15px] rounded-[10px] border border-white/5 w-full">
                                            <span id="min-price" class="text-white font-medium text-[14px]">0</span>
                                        </div>
                                    </div>
                                    <div class="h-[1px] w-4 bg-gray-600"></div>
                                    <div class="w-full">
                                        <span class="text-gray-400 text-xs block mb-2 text-right">Max</span>
                                        <div class="bg-[#282B34] py-[10px] px-[15px] rounded-[10px] border border-white/5 text-right w-full">
                                            <span id="max-price" class="text-white font-medium text-[14px]">300000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative h-1 w-full bg-[#282B34] rounded-lg">
                                    <div id="slider-progress" class="absolute h-full bg-white rounded-lg left-0 right-0"></div>
                                    <input type="range" id="range-min" min="0" max="300000" value="0" step="100" class="absolute w-full h-1 appearance-none bg-transparent pointer-events-none z-20">
                                    <input type="range" id="range-max" min="0" max="300000" value="300000" step="100" class="absolute w-full h-1 appearance-none bg-transparent pointer-events-none z-20">
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
                                <div class="relative mb-6">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </span>
                                    <input type="text" id="brand-search" placeholder="Search brands (e.g. Nvidia)" class="w-full bg-[#282B34] text-white text-sm rounded-[10px] focus:ring-[#3E81FF] focus:border-[#3E81FF] block pl-10 p-[15px] outline-none transition-all">
                                </div>

                                <div id="brand-grid" class="grid grid-cols-2 max-h-[300px] overflow-y-auto custom-scrollbar divide-x-1 divide-[#1E2529]">
                                    @if(!empty($brands))
                                        @foreach ($brands as $brand)
                                        <div class="brand-item w-full cursor-pointer group p-4 transition-all flex items-center min-h-[90px] border border-[#1E2529] hover:bg-[#1E2529]/30" data-name="{{$brand->name}}" data-id="{{ $brand->id }}">
                                            <img src="{{ $brand->logo ? Storage::url($brand->logo) : asset('assets/img/placeholder.jpg') }}" class="m-auto opacity-[0.5] group-hover:opacity-[1] transition-all duration-600">
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                <a href="#" class="block mt-[30px] w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 text-white hover:bg-white hover:text-black">view all brands</a>
                            </div>

                        </el-disclosure>
                    </div>
                    <!--//brand filter-->
                </form>
                <!--// Filters -->

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

            <div class="col-span-3" x-data="{ activeTab: '{{ request('view', 'gridview') }}' }">

                <div class="flex items-center justify-between">
                    <h1 class="text-4xl font-bold tracking-tight text-white uppercase">All Products</h1>
                    <div class="flex items-center gap-[30px]">
                        <span class="text-[#898989] text-[14px]">Items 1-{{ $products->count() }} of {{ $products->count() }}</span>
                        <el-dropdown class="relative inline-block text-left">
                            <button class="group inline-flex border border-[#282B34] rounded-[10px] p-[20px] justify-between text-sm font-medium text-white min-w-[230px]">
                                <span class="mr-[10px] text-[14px] text-[#898989]">Sort by: 
                                    @switch($sort)
                                        @case('oldest') Oldest @break
                                        @case('newest') Newest @break
                                        @case('price_low_high') Price: Low to High @break
                                        @case('price_high_low') Price: High to Low @break
                                        @default Oldest
                                    @endswitch
                                </span> 
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="-mr-1 ml-1 size-5 shrink-0 text-gray-400 group-hover:text-gray-500">
                                    <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </button>
                            <el-menu anchor="bottom end" popover class="m-0 w-40 origin-top-right rounded-md bg-white p-0 shadow-2xl ring-1 ring-black/5 transition [--anchor-gap:theme(spacing.2)] [transition-behavior:allow-discrete] focus:outline-none data-[closed]:scale-95 data-[closed]:transform data-[closed]:opacity-0 data-[enter]:duration-100 data-[leave]:duration-75 data-[enter]:ease-out data-[leave]:ease-in">
                                <div class="py-1">
                                    <a href="{{ route('products', ['sort'=>'oldest','view'=>request('view','gridview')]) }}" class="block px-4 py-2 text-sm font-medium text-gray-900 focus:bg-gray-100 focus:outline-none">Oldest</a>
                                    <a href="{{ route('products', ['sort'=>'newest','view'=>request('view','gridview')]) }}" class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-none">Newest</a>
                                    <a href="{{ route('products', ['sort'=>'price_low_high','view'=>request('view','gridview')]) }}" class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-none">Price: Low to High</a>
                                    <a href="{{ route('products', ['sort'=>'price_high_low','view'=>request('view','gridview')]) }}" class="block px-4 py-2 text-sm text-gray-500 focus:bg-gray-100 focus:outline-none">Price: High to Low</a>
                                </div>
                            </el-menu>
                        </el-dropdown>

                        <div id="view-switcher" class="button-group p-[5px] border border-[#282B34] rounded-[10px] gap-[5px]">
                            <button @click="
                                        activeTab='gridview';
                                        window.location.search = new URLSearchParams({
                                            sort: '{{ request('sort', 'oldest') }}',
                                            view: 'gridview'
                                        });
                                    " type="button" class="view-btn active bg-[#282B34] group p-[15px] cursor-pointer hover:bg-[#282B34] rounded-[5px] active:bg-[#282B34] transition-all duration-600">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.25 9.75C6.04565 9.75 6.80848 10.0663 7.37109 10.6289C7.9337 11.1915 8.25 11.9544 8.25 12.75V15C8.25 15.7956 7.9337 16.5585 7.37109 17.1211C6.80848 17.6837 6.04565 18 5.25 18H3C2.20435 18 1.44152 17.6837 0.878906 17.1211C0.316297 16.5585 0 15.7956 0 15V12.75C0 11.9544 0.316297 11.1915 0.878906 10.6289C1.44152 10.0663 2.20435 9.75 3 9.75H5.25ZM15 9.75C15.7956 9.75 16.5585 10.0663 17.1211 10.6289C17.6837 11.1915 18 11.9544 18 12.75V15C18 15.7956 17.6837 16.5585 17.1211 17.1211C16.5585 17.6837 15.7956 18 15 18H12.75C11.9544 18 11.1915 17.6837 10.6289 17.1211C10.0663 16.5585 9.75 15.7956 9.75 15V12.75C9.75 11.9544 10.0663 11.1915 10.6289 10.6289C11.1915 10.0663 11.9544 9.75 12.75 9.75H15ZM3 11.25C2.60218 11.25 2.22076 11.4081 1.93945 11.6895C1.65815 11.9708 1.5 12.3522 1.5 12.75V15C1.5 15.3978 1.65815 15.7792 1.93945 16.0605C2.22076 16.3419 2.60218 16.5 3 16.5H5.25C5.64782 16.5 6.02924 16.3419 6.31055 16.0605C6.59185 15.7792 6.75 15.3978 6.75 15V12.75C6.75 12.3522 6.59185 11.9708 6.31055 11.6895C6.02924 11.4081 5.64782 11.25 5.25 11.25H3ZM12.75 11.25C12.3522 11.25 11.9708 11.4081 11.6895 11.6895C11.4081 11.9708 11.25 12.3522 11.25 12.75V15C11.25 15.3978 11.4081 15.7792 11.6895 16.0605C11.9708 16.3419 12.3522 16.5 12.75 16.5H15C15.3978 16.5 15.7792 16.3419 16.0605 16.0605C16.3419 15.7792 16.5 15.3978 16.5 15V12.75C16.5 12.3522 16.3419 11.9708 16.0605 11.6895C15.7792 11.4081 15.3978 11.25 15 11.25H12.75ZM5.25 0C6.04565 0 6.80848 0.316297 7.37109 0.878906C7.9337 1.44152 8.25 2.20435 8.25 3V5.25C8.25 6.04565 7.9337 6.80848 7.37109 7.37109C6.80848 7.9337 6.04565 8.25 5.25 8.25H3C2.20435 8.25 1.44152 7.9337 0.878906 7.37109C0.316297 6.80848 0 6.04565 0 5.25V3C0 2.20435 0.316297 1.44152 0.878906 0.878906C1.44152 0.316297 2.20435 0 3 0H5.25ZM15 0C15.7956 0 16.5585 0.316297 17.1211 0.878906C17.6837 1.44152 18 2.20435 18 3V5.25C18 6.04565 17.6837 6.80848 17.1211 7.37109C16.5585 7.9337 15.7956 8.25 15 8.25H12.75C11.9544 8.25 11.1915 7.9337 10.6289 7.37109C10.0663 6.80848 9.75 6.04565 9.75 5.25V3C9.75 2.20435 10.0663 1.44152 10.6289 0.878906C11.1915 0.316297 11.9544 0 12.75 0H15ZM3 1.5C2.60218 1.5 2.22076 1.65815 1.93945 1.93945C1.65815 2.22076 1.5 2.60218 1.5 3V5.25C1.5 5.64782 1.65815 6.02924 1.93945 6.31055C2.22076 6.59185 2.60218 6.75 3 6.75H5.25C5.64782 6.75 6.02924 6.59185 6.31055 6.31055C6.59185 6.02924 6.75 5.64782 6.75 5.25V3C6.75 2.60218 6.59185 2.22076 6.31055 1.93945C6.02924 1.65815 5.64782 1.5 5.25 1.5H3ZM12.75 1.5C12.3522 1.5 11.9708 1.65815 11.6895 1.93945C11.4081 2.22076 11.25 2.60218 11.25 3V5.25C11.25 5.64782 11.4081 6.02924 11.6895 6.31055C11.9708 6.59185 12.3522 6.75 12.75 6.75H15C15.3978 6.75 15.7792 6.59185 16.0605 6.31055C16.3419 6.02924 16.5 5.64782 16.5 5.25V3C16.5 2.60218 16.3419 2.22076 16.0605 1.93945C15.7792 1.65815 15.3978 1.5 15 1.5H12.75Z" fill="#898989" class="group-hover:fill-white transition-all duration-600" />
                                </svg>
                            </button>
                            <button @click="
                                        activeTab='listview';
                                        window.location.search = new URLSearchParams({
                                            sort: '{{ request('sort', 'oldest') }}',
                                            view: 'listview'
                                        });
                                    " type="button" class="view-btn group p-[15px] cursor-pointer hover:bg-[#282B34] rounded-[5px] active:bg-[#282B34] transition-all duration-600">
                                <svg width="18" height="18" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 0H0V1.25H15V0Z" fill="#898989" class="group-hover:fill-white transition-all duration-600" />
                                    <path d="M15 6.25H0V7.50001H15V6.25Z" fill="#898989" class="group-hover:fill-white transition-all duration-600" />
                                    <path d="M15 12.5H0V13.75H15V12.5Z" fill="#898989" class="group-hover:fill-white transition-all duration-600" />
                                </svg>
                            </button>
                            <button type="button" command="show-modal" commandfor="mobile-filters" class="view-btn group p-[15px] hover:bg-[#282B34] rounded-[5px] active:bg-[#282B34] transition-all duration-600 sm:ml-6 lg:hidden">
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5">
                                    <path d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd" fill-rule="evenodd" fill="#898989" class="group-hover:fill-white transition-all duration-600" />
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
            </div>
        </div>

    </main>
</section>
<!--//product listing-->

@endsection
