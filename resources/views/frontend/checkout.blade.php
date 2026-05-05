@extends('frontend.layouts.app')

@section('title', 'Checkout')
@section('content')


<!--inner banner-->
<section class="px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] md:pt-[100px] xl:pt-[150px] pb-[0px] relative">
    <!-- <div class="section-title mb-[0px] relative border-t border-[#282B34] pt-[50px] flex flex-col gap-[30px] "> -->
        <div class="w-full section-title mb-[0px] relative border-t border-[#282B34] pt-[30px] flex items-center justify-between">

        <h3 class="text-[40px] xl:text-[50px] text-white font-bold text-center uppercase flex flex-col md:flex-row flex-start justify-center md:justify-start items-center xl:items-start gap-[0px] xl:gap-[10px] m-0 leading-[30px] md:leading-[0px] xl:leading-[60px]">Checkout</h3>
        <a href="{{ route('cart') }}" class="border w-fit border-white/10 text-white text-center px-6 py-3 rounded-xl font-medium uppercase text-[13px] hover:bg-white/5 transition-all">
                                Back to Cart
        </a>
    </div>
</section>
<!--//categories-->

<!-- Confirm guest login -->
 @if(empty(auth('frontend')->user()))
 <div id="checkout-login-box" class="checkout-login-box px-[16px] md:px-[30px] xl:px-[140px] pt-[30px]">
    <div class="flex gap-2 text-white">
        <a href="{{ route('login', ['checkout' => 1]) }}" class="mt-[0px] flex flex-row justify-center align-center items-center text-center text-black uppercase text-[13px] font-medium px-6 py-3 rounded-xl bg-[#2A7CFF] border border-[#282B34] transition-all duration-[600ms] text-white hover:bg-[#1447e6] hover:text-white cursor-pointer">
            Login
        </a>

        <button type="button" onclick="continueGuest()" class="mt-[0px] flex flex-row justify-center align-center items-center text-center text-black uppercase text-[13px] font-medium px-6 py-3 rounded-xl bg-[#2A7CFF] border border-[#282B34] transition-all duration-[600ms] text-white hover:bg-[#1447e6] hover:text-white cursor-pointer">
            Continue as Guest
        </button>
    </div>
</div>
@endif

<!--checkout-->
<section class="bg-[#0F161B]">

    <main class="px-[16px] md:px-[30px] xl:px-[140px] pt-[50px] pb-[50px] xl:pb-[100px]">
        <div class="flex flex-col gap-[15px] xl:col-col xl:grid xl:grid-cols-12 xl:gap-[30px] w-full">
            <div class="col-span-8 flex flex-col gap-[10px]">
                <form id="checkout-form">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id" value="">
                    <div class="flex flex-col min-h-screen bg-black/30 backdrop-blur-[60px] p-[20px] md:p-[40px] rounded-[20px] mb-[10px] text-white gap-[50px]">
                        <section>
                            <h2 class="flex items-center text-[18px] md:text-[20px] uppercase mb-[25px] pb-[20px] border-b border-[#282B34] gap-3">
                                <span class="w-8 h-8 bg-[#2A7CFF] rounded-full flex items-center justify-center text-sm">1</span> Billing Information
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $fullName = auth('frontend')->user()->name ?? '';
                                    $nameParts = explode(' ', $fullName, 2);
                                    $firstName = $nameParts[0] ?? '';
                                    $lastName = $nameParts[1] ?? '';
                                @endphp     
                                <div>
                                    <input type="text" placeholder="First Name *" class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl focus:border-[#2A7CFF] outline-none transition-all w-full" name="first_name" value="{{ $firstName }}">
                                    <span class="text-red-400 text-xs mt-1 error-span error-first_name"></span>
                                </div>
                                <div>
                                    <input type="text" placeholder="Last Name" class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl focus:border-[#2A7CFF] outline-none transition-all w-full" name="last_name" value="{{ $lastName }}">
                                    <span class="text-red-400 text-xs mt-1 error-span error-last_name"></span>
                                </div>
                                <div>
                                    <input type="email" placeholder="Email Address *" class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl focus:border-[#2A7CFF] outline-none transition-all w-full" name="billing_email" value="{{ auth('frontend')->user()->email ?? '' }}">
                                    <span class="text-red-400 text-xs mt-1 error-span error-billing_email"></span>
                                </div>
                                <div>
                                    {{-- <div class="flex flex-col gap-2 w-full h-full">
                                        <div class="h-full flex items-center bg-[#161B22] border border-gray-800 rounded-xl focus-within:border-[#2A7CFF] transition-all overflow-hidden group">
                                            <div class="bg-[#1c2128] px-4 py-4 border-r border-gray-800 flex items-center gap-2 h-full">
                                                <span class="text-[#6a7282] font-medium text-[15px] h-full">+971</span>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <input type="number" id="uae-phone-input" placeholder="971xxxxxxxxx" class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl focus:border-[#2A7CFF] outline-none transition-all w-full" name="billing_phone" value="{{ auth('frontend')->user()->phone ?? '' }}">
                                    <span class="text-red-400 text-xs mt-1 error-span error-billing_phone"></span>
                                </div>
                                <div>
                                    <input type="text" placeholder="Building / Apartment, Street *" class="md:col-span-2 bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl focus:border-[#2A7CFF] outline-none transition-all w-full" name="billing_address">
                                    <span class="text-red-400 text-xs mt-1 error-span error-billing_address"></span>
                                </div>
                                <div>
                                    <input type="text" placeholder="City *" class="w-full bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl focus:border-[#2A7CFF] outline-none transition-all" name="billing_city">
                                    <span class="text-red-400 text-xs mt-1 error-span error-billing_city"></span>
                                </div>
                                <div>
                                    <div class="relative w-full">
                                        <select id="emirate-select" class="w-full bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white outline-none focus:border-[#2A7CFF] appearance-none cursor-pointer transition-all font-medium bg-image-none" name="billing_state">
                                            <option value="" disabled selected>Select Emirate</option>
                                            <option value="Abu Dhabi">Abu Dhabi</option>
                                            <option value="Ajman">Ajman</option>
                                            <option value="Dubai">Dubai</option>
                                            <option value="Fujairah">Fujairah</option>
                                            <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                                            <option value="Sharjah">Sharjah</option>
                                            <option value="Umm Al Quwain">Umm Al Quwain</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="text-red-400 text-xs mt-1 error-span error-billing_state"></span>
                                </div>
                                <input type="text" readonly placeholder="United Arab Emirates" class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-gray-500 disabled:cursor-not-allowed disabled:border-gray-900" name="billing_country" value="United Arab Emirates">
                            </div>
                        </section>

                        <section class="w-full">
                            <h2 class="flex items-center text-[18px] md:text-[20px] uppercase mb-[25px] pb-[20px] border-b border-[#282B34] gap-3 font-medium text-white">
                                <span class="w-8 h-8 bg-[#2A7CFF] rounded-full flex items-center justify-center text-sm">2</span> Fulfillment Information
                            </h2>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="fulfillment_method" value="delivery" id="delivery-choice" checked class="peer hidden">
                                    <div class="px-6 py-3 rounded-xl border-2 border-gray-800 bg-[#161B22] peer-checked:border-[#2A7CFF] peer-checked:bg-[#2A7CFF]/5 transition-all text-center">
                                        <span class="block text-white font-medium uppercase text-[13px]">Delivery</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="fulfillment_method" value="pickup" id="pickup-choice" class="peer hidden">
                                    <div class="px-6 py-3 rounded-xl border-2 border-gray-800 bg-[#161B22] peer-checked:border-[#2A7CFF] peer-checked:bg-[#2A7CFF]/5 transition-all text-center">
                                        <span class="block text-white font-medium uppercase text-[13px]">Store Pickup</span>
                                    </div>
                                </label>
                            </div>

                            <div id="delivery-section" class="block space-y-6">
                                <div class="mb-6">
                                    <label class="flex items-center gap-3 cursor-pointer group w-fit">
                                        <div class="relative">
                                            <input type="checkbox" id="billing-toggle" checked class="peer hidden" name="same_as_billing">
                                            <div class="w-6 h-6 border-2 border-gray-700 rounded-md peer-checked:bg-[#2A7CFF] peer-checked:border-[#2A7CFF] transition-all flex items-center justify-center">
                                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25">
                                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
                                                    <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <span class="text-[15px] text-gray-400 group-hover:text-white transition-colors">Same as billing address</span>
                                    </label>
                                </div>
                                <input type="hidden" name="is_guest" value="{{ auth('frontend')->check() ? 0 : 1 }}">
                                <div id="address-list-container" class="hidden space-y-4">
                                    <h2 class="shipping-heading flex items-center text-[18px] md:text-[20px] uppercase mb-[25px] pb-[20px] border-b border-[#282B34] gap-3 font-medium text-white">
                                        <span class="w-8 h-8 bg-[#2A7CFF] rounded-full flex items-center justify-center text-sm">3</span> 
                                        Shipping Information
                                    </h2>
                                    @if(!auth('frontend')->check())
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <input type="text" name="shipping_first_name"
                                                placeholder="First Name *"
                                                class="bg-[#161B22] border border-gray-800 p-4 rounded-xl w-full">
                                                <span class="text-red-400 text-xs mt-1 error-span error-shipping_first_name"></span>
                                            </div>
                                            <div>
                                                <input type="text" name="shipping_last_name"
                                                placeholder="Last Name *"
                                                class="bg-[#161B22] border border-gray-800 p-4 rounded-xl w-full">
                                                <span class="text-red-400 text-xs mt-1 error-span error-shipping_last_name"></span>
                                            </div>
                                            
                                            <div>
                                                <input type="text" name="shipping_phone"
                                                placeholder="Phone *"
                                                class="bg-[#161B22] border border-gray-800 p-4 rounded-xl w-full">
                                                <span class="text-red-400 text-xs mt-1 error-span error-shipping_phone"></span>
                                            </div>
                                            
                                            <div>
                                                <input type="text" name="shipping_address"
                                                placeholder="Address *"
                                                class="md:col-span-2 bg-[#161B22] border border-gray-800 p-4 rounded-xl w-full">
                                                <span class="text-red-400 text-xs mt-1 error-span error-shipping_address"></span>
                                            </div>
                                            
                                            <div>
                                                <input type="text" name="shipping_city"
                                                placeholder="City *"
                                                class="bg-[#161B22] border border-gray-800 p-4 rounded-xl w-full">
                                                <span class="text-red-400 text-xs mt-1 error-span error-shipping_city"></span>
                                            </div>
                                            
                                            <div>
                                                <select name="shipping_state"
                                                        class="bg-[#161B22] border border-gray-800 p-4 rounded-xl w-full">
                                                    <option value="">Select Emirate</option>
                                                    <option>Dubai</option>
                                                    <option>Abu Dhabi</option>
                                                    <option>Sharjah</option>
                                                    <option>Ajman</option>
                                                    <option>Fujairah</option>
                                                    <option>Ras Al Khaimah</option>
                                                    <option>Umm Al Quwain</option>
                                                </select>
                                                <span class="text-red-400 text-xs mt-1 error-span error-shipping_state"></span>
                                            </div>
                                            
                                            <div>
                                                <input type="text" readonly value="United Arab Emirates"
                                                class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-gray-500 w-full" name="shipping_country">
                                            </div>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                                            @foreach ($addresses as $address)
                                            <label class="relative p-4 bg-[#161B22] border border-gray-800 rounded-xl cursor-pointer flex justify-between hover:border-gray-600 transition-all">
                                                <div>
                                                    <span class="block text-white font-bold text-sm">{{ ucfirst($address->type) ?? $address->type }}</span>
                                                    <span class="block text-gray-500 text-xs mt-1">
                                                        {{ $address->name }}<br>
                                                        {!! nl2br($address->address) !!}<br>
                                                        {{ $address->city }}, {{ $address->state_name }},<br> 
                                                        {{ $address->country_name }} {{ $address->postal_code ? '- '. $address->postal_code : '' }}
                                                    </span>
                                                </div>
                                                <input type="checkbox" name="selected_addr" {{ $address->set_default == 1 ? 'checked' : '' }} class="accent-[#2A7CFF] w-4 h-4 rounded-circle" value="{{ $address->id }}">
                                            </label>
                                            @endforeach
                                            <button type="button" id="open-address-modal" class="cursor-pointer md:col-span-2 p-4 border-2 border-dashed border-gray-800 rounded-xl text-gray-500 hover:text-[#2A7CFF] hover:border-[#2A7CFF]/50 transition-all font-medium uppercase text-[12px] tracking-wider">
                                                + Add New Address
                                            </button>
                                        </div>
                                    @endauth
                                </div>
                            </div>

                            <div id="pickup-section" class="hidden animate-fade-in">
                                <div class="p-3 xl:p-6 bg-[#161B22] border border-gray-800 rounded-xl flex items-center gap-4">
                                    <div class="p-3 bg-[#2A7CFF]/10 rounded-lg">
                                        <svg class="w-6 h-6 text-[#2A7CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold">PC Garage Main Showroom</h4>
                                        <p class="text-gray-500 text-[13px] leading-relaxed">{{ get_setting('pickup_address') }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h2 class="flex items-center text-[18px] md:text-[20px] uppercase mb-[25px] pb-[20px] border-b border-[#282B34] gap-3">
                                <span class="w-8 h-8 bg-[#2A7CFF] rounded-full flex items-center justify-center text-sm">4</span> Payment Methods
                            </h2>
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-0 xl:mb-8">

                                <label class="payment-option flex items-center justify-between p-4 bg-[#161B22] border border-gray-800 rounded-xl cursor-pointer  transition-all">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="pay" checked class="accent-[#2A7CFF]" value="cash_on_delivery">
                                        <span>Cash On Delivery</span>
                                    </div>
                                </label>

                                <label class="payment-option flex items-center justify-between p-4 bg-[#161B22] border border-gray-800 rounded-xl cursor-pointer  transition-all">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="pay" class="accent-[#2A7CFF]" value="card">
                                        <span>Credit / Debit Card</span>
                                    </div>
                                    <div class="hidden md:flex gap-2">
                                        <img src="{{ asset('assets/images/payment-methods.png') }}" class="w-full md:w-[280px] h-auto object-contain ml-auto">
                                    </div>
                                </label>
                            </div>
                        </section>
                    </div>
                </form>
            </div>

            <div class="col-span-4">
                <!--summary widget-->
                <div class="sticky top-[180px] w-full bg-black/30 backdrop-blur-[60px] p-[20px] md:p-[40px] rounded-[20px] mb-[10px]">
                    <h2 class="text-white text-[20px] uppercase mb-[25px] pb-[20px] border-b border-[#282B34]">Order Summary</h2>

                    <div>
                    @foreach($cartItems as $item)
                        @php
                            $image = asset('assets/img/placeholder.jpg'); // default placeholder

                            if (!empty($item->product_stock?->image)) {
                                // If multiple images, split by comma and take the first
                                $stockImages = explode(',', $item->product_stock->image);
                                $firstStockImage = trim($stockImages[0]);
                                if ($firstStockImage) {
                                    $image = Storage::url($firstStockImage);
                                }
                            } elseif (!empty($item->product?->thumbnail_img)) {
                                $image = Storage::url($item->product->thumbnail_img);
                            }
                        @endphp
                    <div class="flex gap-4 mb-6">
                        <div class="w-20 h-20 bg-[#0B0F13] bg-white rounded-lg border border-gray-800 flex items-center justify-center">
                            <a href="{{route('product.details', [$item->product->slug,$item->product_stock->sku])}}">
                                <img src="{{ $image }}" alt="{{ $item->product_stock->stock_title ?? $item->product->name ?? '' }}" title="{{ $item->product_stock->stock_title ?? $item->product->name ?? '' }}" class="w-full">
                            </a>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-white text-[13px] leading-[20px] font-medium line-clamp-2 cursor-pointer" onclick="window.location='{{route('product.details', [$item->product->slug,$item->product_stock->sku])}}'">{{ $item->product_stock->stock_title ?? $item->product->name ?? '' }}</h4>
                            <p class="text-[10px] text-[#ffffff50] text-center xl:text-left">  
                                @if($item->product_stock && $item->product_stock->attributes && $item->product_stock->attributes->count())
                                    <span class="text-gray-400 text-sm">
                                        
                                        @foreach($item->product_stock->attributes as $attr)
                                            {{ $attr->attribute?->name ?? '' }}:
                                            {{ $attr->value?->value ?? '' }}

                                            @if(!$loop->last) | @endif
                                        @endforeach
                                        
                                    </span>
                                @endif
                            </p>
                            <div class="flex gap-[15px] items-center mt-2 items-center divide-x divide-[#282B34] justify-start w-full">
                                <p class="text-[15px] text-gray-500 mt-[5px]">Qty: {{ $item->quantity }}</p>
                                <span class="grid pl-[15px]">
                                    <span class="flex flex-row text-gray-500 items-center justify-end gap-[10px] text-[15px] mt-[5px]">
                                        <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#6a7282" />
                                        </svg>
                                        {{ format_price($item->offer_price) }} 
                                    </span>
                                    @if (filled($item->offer_tag))
                                    <span class="line-through flex flex-row text-gray-500 items-center justify-end gap-[10px] text-[15px] mt-[5px]"> <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#6a7282" />
                                    </svg> {{ format_price($item->price) }} </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>

                    <ul class="border-y border-[#282B34] py-[20px] mt-[20px] border-b-0">
                        <li class="py-[10px]">
                            <div class="flex flex-row justify-between">
                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Subtotal</span>
                                <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[10px] text-[15px]">
                                    <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                    </svg>
                                    {{ format_price($sub_total) }}
                                </span>
                            </div>
                        </li>
                        <li class="py-[10px]">
                            <div class="flex flex-row justify-between">
                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Discount</span>
                                <span class="flex flex-row text-[#29A706] items-center justify-end text-right gap-[10px] text-[15px]">
                                    - <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path class="fill-[#29A706]" d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                    </svg>
                                    {{ format_price($discount_sum) }}
                                </span>
                            </div>
                        </li>
                        @if($couponDiscount > 0)
                        <li class="py-[10px]">
                            <div class="flex flex-row justify-between">
                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Coupon Discount</span>
                                <span class="flex flex-row text-[#29A706] items-center justify-end text-right gap-[10px] text-[15px]">
                                    - <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path class="fill-[#29A706]" d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                    </svg>
                                    {{ format_price($couponDiscount) }}
                                </span>
                            </div>
                        </li>
                        @endif

                        @if($has_warranty)
                            <li class="py-[10px]">
                                <div class="flex flex-row justify-between">

                                    <span class="text-[#99a1af] text-[15px] justify-start text-left">Warranty (Premium Care+)</span>

                                    <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[10px] text-[15px]">

                                        
                                        @if($warranty_sum > 0)
                                            + <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                        </svg>
                                        {{ format_price($warranty_sum) }}
                                        @else
                                            <span class="text-black uppercase font-bold text-[10px] bg-[#29A706] px-2 py-1 rounded">
                                                Free
                                            </span>
                                        @endif

                                    </span>
                                </div>
                            </li>
                        @endif
                        <li class="py-[10px]">
                            <div class="flex flex-row justify-between">
                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Estimated Tax</span>
                                <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[10px] text-[15px]">
                                    <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                    </svg>
                                    {{ format_price($tax) }}
                                </span>
                            </div>
                        </li>
                        
                        <li class="py-[10px]">
                            <div class="flex flex-row justify-between">
                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Shipping & Handling</span>
                                <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[10px] text-[15px]">
                                    @if($shipping == 0 )
                                    <span class="text-black uppercase font-bold text-[10px] bg-[#29A706] px-2 py-1 rounded">Free</span>
                                    @else
                                    <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af" />
                                    </svg>
                                    <span id="cart-shipping">{{ $sub_total > 0 ? format_price($shipping) : '0.00' }}</span>
                                    @endif
                                </span>

                            </div>
                        </li>
                        
                        
                        <li class="border-y border-[#282B34] py-[20px] mt-[20px] border-b-0">
                            <div class="flex flex-row justify-between">
                                <span class="text-white text-[25px] justify-start text-left uppercase font-bold">TOTAL</span>
                                <span class="flex flex-row text-white items-center justify-end text-right gap-[10px] text-[25px] font-bold">
                                    <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="white" />
                                    </svg>
                                    <span id="final-total">{{ format_price($total) }}</span>
                                </span>
                            </div>
                        </li>
                    </ul>
                    <a href="javascript:void(0)" onclick="completeYourOrder(event, this)" class="mt-[0px] w-full flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-[600ms] text-white hover:bg-[#1447e6] hover:text-white cursor-pointer">Place Your Order</a>
                </div>
                <!--//categories filter-->
            </div>
        </div>

    </main>
</section>
<!--//checkout-->

<!--address modal-->
<div id="addr-modal-overlay" class="fixed inset-0 z-[9999] hidden opacity-0 bg-black/80 backdrop-blur-md flex justify-center items-start p-4 overflow-y-auto transition-opacity duration-300 ease-in-out">
    <div id="addr-modal-container" class="bg-[#0B0F13] border border-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl relative mt-4 mb-4 md:mt-10 md:mb-10 transform scale-95 opacity-0 transition-all duration-300 ease-out">

        <button id="close-modal-x" type="button" class="absolute top-4 right-4 text-gray-500 hover:text-white z-50 p-2 cursor-pointer transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-0 xl:p-5 md:p-8">
            
            <form id="addressForm" action="{{ route('save-address') }}" method="POST">
                @csrf

                <input type="hidden" name="address_id" value="0">

                <div class="p-5 md:p-8">

                    <h4 class="text-xl font-medium text-white mb-6 uppercase tracking-wider">
                        Add New Address
                    </h4>

                    <div class="relative w-full h-[220px] bg-[#161B22] rounded-xl mb-6 border border-gray-800 overflow-hidden">

                        <div id="map" class="w-full h-full"></div>

                        <button type="button" onclick="getCurrentLocation()" class="absolute cursor-pointer bottom-3 right-3 bg-[#2A7CFF] p-3 rounded-full shadow-lg hover:bg-blue-600 transition-all active:scale-95 z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Full Name --}}
                        <div>
                            <input type="text" name="name" id="name" placeholder="Full Name *"
                                value=""
                                class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                            <p class="text-red-400 text-xs mt-1 error-name"></p>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <input type="text" name="phone" id="phone"
                                placeholder="971xxxxxxxxx"
                                value=""
                                class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                            <p class="text-red-400 text-xs mt-1 error-phone"></p>
                        </div>

                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <input type="text" name="address" id="address"
                                placeholder="Address (Building, Street, Area etc.) *"
                                value=""
                                class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                            <p class="text-red-400 text-xs mt-1 error-address"></p>
                        </div>

                        {{-- City --}}
                        <div>
                            <input type="text" name="city" id="city" placeholder="City *"
                                value=""
                                class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                            <p class="text-red-400 text-xs mt-1 error-city"></p>
                        </div>

                        {{-- Emirate / State --}}
                        <div class="relative w-full">

                            <select class="w-full bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white outline-none focus:border-[#2A7CFF] appearance-none cursor-pointer transition-all font-medium bg-image-none" name="state" id="state">
                                <option value="Abu Dhabi">Abu Dhabi</option>
                                <option value="Ajman">Ajman</option>
                                <option value="Dubai" selected>Dubai</option>
                                <option value="Fujairah">Fujairah</option>
                                <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                                <option value="Sharjah">Sharjah</option>
                                <option value="Umm Al Quwain">Umm Al Quwain</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <p class="text-red-400 text-xs mt-1 error-state"></p>
                        </div>

                        {{-- ZIP --}}
                        <div>
                            <input type="text" name="zipcode" id="zipcode" placeholder="ZIP Code"
                                value=""
                                class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">
                        </div>

                        {{-- Country --}}
                        <div>
                            <input type="text" name="country" id="country" placeholder="Country *"
                                value="United Arab Emirates" readonly
                                class="bg-[#161B22] border border-gray-800 px-5 py-3 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">
                            <p class="text-red-400 text-xs mt-1 error-country"></p>
                        </div>

                    </div>

                    {{-- Address Type --}}
                    <div class="mt-6 flex gap-4">

                        <label class="flex items-center gap-2 text-gray-300">
                            <input type="radio" name="address_type" value="home" checked>
                            Home
                        </label>

                        <label class="flex items-center gap-2 text-gray-300">
                            <input type="radio" name="address_type" value="work">
                            Work
                        </label>

                        <label class="flex items-center gap-2 text-gray-300">
                            <input type="radio" name="address_type" value="other">
                            Other
                        </label>

                    </div>

                    {{-- Default Address --}}
                    <div class="mt-6 p-4 bg-[#161B22] border border-gray-800 rounded-xl flex items-center justify-between">

                        <div class="flex flex-col">
                            <span class="text-white text-sm font-medium">Set as Default Address</span>
                            <span class="text-gray-500 text-xs">Automatically select this address for bookings and orders.</span>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">

                            <input type="checkbox" name="default" value="1" class="sr-only peer">

                            <div class="w-11 h-6 bg-gray-800 rounded-full peer-checked:bg-[#2A7CFF] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full">
                            </div>

                        </label>

                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col md:flex-row gap-3 mt-8">

                        <button type="submit"
                            class="flex-1 bg-[#2A7CFF] text-white font-medium uppercase py-4 rounded-xl text-[14px] hover:bg-[#1447e6]">
                            Save Address
                        </button>

                        <button onclick="closeCheckoutAddressModal()" type="button"
                        class="flex-1 bg-transparent border border-gray-800 text-gray-500 font-medium py-4 rounded-xl uppercase text-[14px] cursor-pointer hover:bg-gray-800 hover:text-white transition-all">Discard</button>

                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<!--//address modal-->

<script>
window.__pendingAddressMapInit = false;
window.__initAddressMapCallback = function () {
    if (window.initMap && window.initMap.__ready) {
        window.initMap();
        return;
    }

    window.__pendingAddressMapInit = true;
};
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=__initAddressMapCallback&loading=async" async defer></script>

<script>
let timer;
const checkoutForm = document.getElementById('checkout-form');

window.openCheckoutAddressModal = function () {
    const overlay = document.getElementById('addr-modal-overlay');
    const container = document.getElementById('addr-modal-container');

    if (!overlay || !container) {
        return;
    }

    overlay.classList.remove('hidden');

    requestAnimationFrame(() => {
        overlay.classList.add('opacity-100');
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    });

    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        if (window.refreshAddressMapPosition) {
            window.refreshAddressMapPosition();
        }
    }, 320);
}

window.closeCheckoutAddressModal = function () {
    const overlay = document.getElementById('addr-modal-overlay');
    const container = document.getElementById('addr-modal-container');

    if (!overlay || !container) {
        return;
    }

    overlay.classList.remove('opacity-100');
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

function completeYourOrder(e, btn) {
    e.preventDefault(); // stop default submit immediately
    clearTimeout(timer);

    timer = setTimeout(() => {

        $('.error-span').text(''); // clear previous errors

        // check shipping address 
        const billingSame = document.getElementById('billing-toggle')?.checked;
        const selectedAddress = document.querySelector('input[name="selected_addr"]:checked');

        const isGuest = document.querySelector('[name="is_guest"]')?.value === "1";

        
        if (!isGuest && !billingSame) {

            if (!selectedAddress) {
                toastr.error('Please select shipping address');
                return;
            }
        }

        // Serialize form data
        const formData = new FormData(checkoutForm);

        // Convert FormData to URLSearchParams
        const params = new URLSearchParams();
        for (const pair of formData) {
            params.append(pair[0], pair[1]);
        }

        // Send AJAX request
        fetch("/checkout/placeOrder", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: params
        })
        .then(async (response) => {

            const text = await response.text();

            if (!text || text.trim().startsWith('<!DOCTYPE')) {
                throw new Error("Invalid response from server");
            }

            return JSON.parse(text);
        })
        .then(data => {

            // validation errors (Laravel)
            if (data.status === 'error') {
                $.each(data.errors, function (key, value) {
                    $(".error-" + key).text(value[0]);
                });
                return;
            }

            // business failure
            if (data.status === false) {
                if (data.errors) {
                    data.errors.forEach(msg => toastr.error(msg));
                } else {
                    toastr.error(data.message || 'Order failed');
                }

                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000); // ⏱ 2 sec delay
                }
                return;
            }

            // success
            btn.disabled = true;
            btn.classList.add('opacity-50','cursor-not-allowed');
            btn.innerHTML = 'Processing...';

            window.location.href = data.redirect;
        })
        .catch(error => {
            console.log('An error occurred while placing your order. Please try again.');
            // window.location.href = "/order-fail";
        });

    }, 500); // reduced delay (optional)
}


document.addEventListener('DOMContentLoaded', () => {
    // Selectors
    const deliveryChoice = document.getElementById('delivery-choice');
    const pickupChoice = document.getElementById('pickup-choice');
    const deliverySection = document.getElementById('delivery-section');
    const pickupSection = document.getElementById('pickup-section');
    const billingToggle = document.getElementById('billing-toggle');
    const addressListContainer = document.getElementById('address-list-container');
    const cartShipping = document.getElementById('cart-shipping');
    const openAddressModalButton = document.getElementById('open-address-modal');
    const closeModalXButton = document.getElementById('close-modal-x');
    const modalOverlay = document.getElementById('addr-modal-overlay');

    let originalShipping = cartShipping 
    ? parseFloat(cartShipping.textContent.replace(/,/g, '')) 
    : 0;
    let originalTotal = parseFloat(document.getElementById('final-total').textContent.replace(/,/g,''));


    // 1. Fulfillment Switcher Logic
    const handleFulfillmentChange = () => {
        let shipping = originalShipping; // reset shipping each time
        let subTotal = originalTotal - originalShipping;
        if (deliveryChoice.checked) {
            deliverySection.classList.remove('hidden');
            pickupSection.classList.add('hidden');
        } else {
            deliverySection.classList.add('hidden');
            pickupSection.classList.remove('hidden');
            shipping = 0; // Set shipping to 0 for pickup;
            // openModal(); // Requirement: Show modal when pickup is selected
        }

        // Update shipping on page
        if(cartShipping) cartShipping.innerText = shipping.toFixed(2);

    // Recalculate total
    const newTotal = subTotal + shipping;
    document.getElementById('final-total').innerText = newTotal.toFixed(2);
         
    };

    deliveryChoice.addEventListener('change', handleFulfillmentChange);
    pickupChoice.addEventListener('change', handleFulfillmentChange);

    // 2. Your Original Billing Toggle Logic
    billingToggle.addEventListener('change', () => {
        if (billingToggle.checked) {
            addressListContainer.classList.add('hidden');
        } else {
            addressListContainer.classList.remove('hidden');
        }
    });

    if (openAddressModalButton) {
        openAddressModalButton.addEventListener('click', () => {
            const form = document.getElementById('addressForm');

            if (form) {
                form.reset();
                form.address_id.value = 0;
                form.querySelector('h4').innerText = 'Add New Address';
            }

            if (window.defaultMapLocation && window.setMapPosition) {
                window.setMapPosition(window.defaultMapLocation);
            }

            window.openCheckoutAddressModal();
        });
    }

    if (closeModalXButton) {
        closeModalXButton.addEventListener('click', window.closeCheckoutAddressModal);
    }

    if (modalOverlay) {
        modalOverlay.addEventListener('click', function (event) {
            if (event.target === modalOverlay) {
                window.closeCheckoutAddressModal();
            }
        });
    }
});


document.addEventListener('DOMContentLoaded', () => {
            $.ajaxSetup({
                headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#addressForm").submit(function(e){
                e.preventDefault();
                $(".text-red-400").html("");
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    processData:false,
                    contentType:false,
                    success:function(response){
                        if(response.status === 'error'){                      
                            $.each(response.errors,function(key,value){
                                $(".error-"+key).text(value[0]);
                            });
                        }else{
                            toastr.success("Address saved successfully");
                            
                            setTimeout(function() {
                                window.closeCheckoutAddressModal();
                                $("#address-list-container").load(location.href + " #address-list-container>*", "");
                            }, 3000);

                        }
                    },
                    error:function(xhr){
                        if(xhr.responseJSON && xhr.responseJSON.errors){
                            let errors = xhr.responseJSON.errors;
                            $.each(errors,function(key,value){
                                $(".error-"+key).text(value[0]);
                            });
                        }
                    }
                });
            });

            // Checkbox script
            const checkboxes = document.querySelectorAll('input[name="selected_addr"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {

                    if(this.checked){
                        checkboxes.forEach(function(item){
                            if(item !== checkbox){
                                item.checked = false;
                            }
                        });
                    }
                });
            });
        });
       
    

</script>
<script id="pay-radio-script">
    const options = document.querySelectorAll('.payment-option');

    function updateBorders() {
        options.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            
            if (radio.checked) {
                label.classList.remove('border-gray-800');
                label.classList.add('border-[#2A7CFF]');
            } else {
                label.classList.remove('border-[#2A7CFF]');
                label.classList.add('border-gray-800');
            }
        });
    }

    // Run on load
    updateBorders();

    // Listen for change
    options.forEach(label => {
        label.querySelector('input').addEventListener('change', updateBorders);
    });
</script>
@endsection
