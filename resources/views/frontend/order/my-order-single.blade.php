@extends('frontend.layouts.app')

@section('title', 'My Orders')
@section('content')


<!--Order Single-->
<section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="text-white">
        <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[30px] border-t border-[#252b31] pt-0 xl:pt-[80px]">

            <main class="flex-grow pt-[30px] xl:pt-0 pb-0 px-4 md:px-0">
                <div class="mx-auto">

                    <div class="mb-8">
                        <a href="{{ route('orders.index') }}" class="w-full xl:w-fit justify-center xl:justify-start text-gray-500 hover:text-[#2A7CFF] transition-all flex items-center gap-2 text-sm font-medium mb-8 xl:mb-4 group">
                            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            BACK TO ORDERS
                        </a>

                        <div class="flex flex-col xl:flex-row justify-between items-center text-center xl:text-left xl:items-start gap-6 w-full">
                            <div class="w-full">
                                <h2 class="text-[20px] font-medium uppercase text-white mb-1 text-center xl:text-left">Order #{{ $order->code }}</h2>
                                <p class="text-gray-500 text-sm">Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}
                                    • {{ $order->orderDetails->count() }} Items Total</p>
                            </div>

                            <div class="flex flex-col xl:flex-row xl:flex-wrap items-start gap-3 w-full">
                                @if($order->delivery_status == 'pending') 
                                <button
                                    class="cancel-order-btn w-full xl:w-fit cursor-pointer flex-1 bg-red-500/5 border border-red-500/20 text-red-500 px-6 py-4 rounded-xl hover:bg-red-500 hover:text-white transition-all text-[13px] font-medium uppercase tracking-wider" data-id="{{ $order->id }}">
                                    Cancel Order
                                </button>
                                @endif         
                                @php
                                    // Default return period from settings (in days)
                                    $returnDays = get_setting('default_return_time') ?? 0;

                                    // Calculate remaining days
                                    $orderDate = \Carbon\Carbon::parse($order->created_at);
                                    $expiryDate = $orderDate->copy()->addDays($returnDays);
                                    $remainingDays = \Carbon\Carbon::now()->diffInDays($expiryDate, false); // false to get negative if past
                                @endphp
                                @if($order->delivery_status == 'delivered')
                                    @if($remainingDays > 0)
                                        @php
                                            $allFullyReturned = true; // flag to track if all items are fully returned
                                        @endphp

                                        @foreach($order->orderDetails as $detail)
                                            @php
                                                $totalReturnedQty = $detail->returns->where('status','approved')->sum('return_qty');
                                                if ($totalReturnedQty < $detail->quantity) {
                                                    $allFullyReturned = false; // at least one item not fully returned
                                                }
                                            @endphp
                                        @endforeach
                                        @if(!$allFullyReturned)
                                            <div class="w-full xl:w-fit flex flex-col gap-2 flex-1">
                                                <button id="openReturnBtn" class="bg-[#282B34] border border-white/5 text-white px-6 py-4 rounded-xl hover:bg-[#2A7CFF] transition-all text-[13px] font-medium uppercase tracking-wider  @if($allReturned) cursor-not-allowed @else cursor-pointer @endif" @if($allReturned) disabled @endif>
                                                        {{ $allReturned ? 'Returned' : 'Return Order' }}
                                                    </button>
                                                
                                                <p class="text-[10px] text-gray-500 italic text-center lg:text-left">
                                                    * Return possible within {{ $remainingDays }} day{{ $remainingDays > 1 ? 's' : '' }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                <button class="w-full cursor-pointer flex-1 bg-[#282B34] border border-white/5 text-white px-6 py-4 rounded-xl hover:bg-[#2A7CFF] transition-all text-[13px] font-medium flex items-center justify-center gap-2 uppercase tracking-wider"  onclick="window.location ='{{ route('invoice.download', $order->id) }}'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Invoice
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        <div class="lg:col-span-2 space-y-8">

                            @php
                            $steps = ['pending', 'confirmed', 'picked_up', 'on_the_way', 'delivered'];

                            $currentStatus = $order->delivery_status;

                            $isCancelled = $currentStatus === 'cancelled';

                            $currentIndex = array_search($currentStatus, $steps);
                            @endphp

                            <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl p-6 md:p-8">

                                <!-- Header -->
                                <div class="flex justify-between items-end mb-10">
                                    <div>
                                        <p class="text-[#2A7CFF] text-[12px] font-medium uppercase mb-1">Current Status</p>

                                        <h4 class="text-white text-[18px] font-medium">
                                            {{ $isCancelled ? __('messages.cancelled') : __('messages.' . $currentStatus) }}
                                        </h4>
                                    </div>

                                    <div class="hidden md:block text-right">
                                        <p class="text-gray-500 text-sm mb-[5px]">Estimated Delivery</p>
                                        <p class="text-white font-medium">
                                            {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('F d, Y') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- CANCELLED UI -->
                                @if($isCancelled)

                                <div class="relative">

                                    <!-- Red Line -->
                                    <div class="hidden md:block absolute top-5 left-0 w-full h-[2px] bg-red-500/10"></div>
                                    <div class="hidden md:block absolute top-5 left-0 w-[50%] h-[2px] bg-red-500"></div>

                                    <div class="flex flex-col md:flex-row justify-between gap-8 md:gap-0 relative">

                                        @foreach(['pending','cancelled'] as $index => $step)

                                        <div class="flex flex-row md:flex-col items-start md:items-center gap-4 md:gap-2">

                                            <div class="w-10 h-10 rounded-full flex items-center justify-center z-10 border-4 border-[#1C2228]
                                                            {{ $step == 'cancelled' ? 'bg-red-500' : 'bg-green-500' }}">

                                                @if($step == 'cancelled')
                                                ✖
                                                @else
                                                ✔
                                                @endif
                                            </div>

                                            <div class="text-left md:text-center">
                                                <p class="text-sm font-medium {{ $step == 'cancelled' ? 'text-red-500' : 'text-white' }}">
                                                    {{ __('messages.' . $step) }}
                                                </p>
                                                @php
                                                    $statusDate = $trackingHistory[$step]->created_at ?? null;
                                                @endphp
                                                <p class="text-gray-500 text-[11px]">
                                                    {{ $statusDate ? \Carbon\Carbon::parse($statusDate)->format('M d') : '-- --' }}
                                                </p>

                                                <p class="text-gray-600 text-[10px] mt-0.5">
                                                    {{ $statusDate ? \Carbon\Carbon::parse($statusDate)->format('h:i A') : '--:--' }}
                                                </p>
                                            </div>

                                        </div>

                                        @endforeach

                                        <!-- Disabled Future Steps -->
                                        @foreach(['picked_up','on_the_way','delivered'] as $step)

                                        <div class="flex flex-row md:flex-col items-start md:items-center gap-4 md:gap-2 opacity-20">

                                            <div class="w-10 h-10 rounded-full bg-[#282B34] flex items-center justify-center z-10 border-4 border-[#1C2228]">
                                                <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                                            </div>

                                            <div class="text-left md:text-center">
                                                <p class="text-white text-sm font-medium line-through">
                                                    {{ __('messages.' . $step) }}
                                                </p>
                                                <p class="text-gray-500 text-[11px]">-- --</p>
                                            </div>

                                        </div>

                                        @endforeach

                                    </div>
                                </div>

                                @else

                                <!-- NORMAL FLOW -->
                                <div class="relative">

                                    <!-- Progress Line -->
                                    <div class="hidden md:block absolute top-5 left-0 w-full h-[2px] bg-[#282B34]"></div>

                                    <div class="hidden md:block absolute top-5 left-0 h-[2px] bg-[#2A7CFF]"
                                        style="width: {{ ($currentIndex / (count($steps)-1)) * 100 }}%">
                                    </div>

                                    <div class="flex flex-col md:flex-row justify-between gap-8 md:gap-0 relative">

                                        @foreach($steps as $index => $step)
                                        @php
                                        $isCompleted = $index < $currentIndex;
                                            $isCurrent=$index==$currentIndex;
                                            $isPending=$index> $currentIndex;
                                            @endphp

                                            <div class="flex flex-row md:flex-col items-start md:items-center gap-4 md:gap-2 {{ $isPending ? 'opacity-30' : '' }}">

                                                <!-- Icon -->
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center z-10 border-4 border-[#1C2228]
                                                        {{ $isCompleted ? 'bg-green-500' : ($isCurrent ? 'bg-[#2A7CFF]' : 'bg-[#282B34]') }}">

                                                    @if($isCompleted)
                                                    ✔
                                                    @elseif($isCurrent)
                                                    <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                                                    @else
                                                    <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                                                    @endif
                                                </div>

                                                <!-- Text -->
                                                <div class="text-left md:text-center">
                                                    <p class="text-sm font-medium {{ $isCurrent ? 'text-[#2A7CFF]' : 'text-white' }}">
                                                        {{ __('messages.' . $step) }}
                                                    </p>

                                                    @php
                                                        $statusDate = $trackingHistory[$step]->created_at ?? null;
                                                    @endphp

                                                    <p class="text-gray-500 text-[11px]">
                                                        {{ $statusDate ? \Carbon\Carbon::parse($statusDate)->format('M d') : '-- --' }}
                                                    </p>

                                                    <p class="text-gray-600 text-[10px] mt-0.5">
                                                        {{ $statusDate ? \Carbon\Carbon::parse($statusDate)->format('h:i A') : '--:--' }}
                                                    </p>
                                                </div>

                                            </div>
                                            @endforeach

                                    </div>
                                </div>

                                @endif

                            </div>

                            <!--normal order card-->
                            <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl overflow-hidden">
                                <div class="p-6 border-b border-[#282B34] flex justify-between items-center">
                                    <h4 class="text-white font-medium uppercase">Items Ordered</h4>
                                    <span class="text-gray-500 text-xs">
                                        {{ $order->orderDetails->count() }} Products
                                    </span>
                                </div>

                                <div class="">
                                    @php
                                        $totalReturnedPrice = 0; // Initialize before the loop
                                        $pcBuilderItems = $order->orderDetails->where('is_pc_builder', 1);
                                        $normalItems = $order->orderDetails->where('is_pc_builder', 0);
                                    @endphp
                                    
                                    <!-- PC builder items -->
                                    @if($pcBuilderItems->count() > 0)
                                    <div class="border-b border-white/5 space-y-6 pb-6 ">
                                        <h3 class="text-white text-lg font-semibold p-6">PC Builder Items</h3>
                                        @foreach($pcBuilderItems as $item)
                                            @php
                                                $image = asset('assets/img/placeholder.jpg');

                                                if (!empty($item->product_stock?->image)) {
                                                $image = Storage::url($item->product_stock->image);
                                                } elseif (!empty($item->product?->thumbnail_img)) {
                                                $image = Storage::url($item->product->thumbnail_img);
                                                }
                                                

                                                $itemReturns = $item->returns ?? collect(); // make sure OrderDetail has returns relation
                                                $totalReturnedQty = $itemReturns->where('status', 'approved')->sum('return_qty');
                                                $totalPendingQty = $itemReturns->where('status', 'pending')->sum('return_qty');
                                                $totalRejectedQty = $itemReturns->where('status', 'rejected')->sum('return_qty');
                                                $remainingQty = $item->quantity - ($totalReturnedQty + $totalPendingQty);

                                                $returnedPrice = $totalReturnedQty * $item->offer_price;
                                                $totalReturnedPrice += $returnedPrice;
                                            @endphp
                                        
                                        <a href="{{route('product.details', [$item->product->slug,$item->product_stock->sku])}}" class="p-6 flex items-center gap-6 group">

                                            <!-- Image -->
                                            <div class="w-20 h-20 bg-[#0f161b] rounded-xl border border-white/5 flex-shrink-0 flex items-center justify-center p-2">
                                                <img src="{{ $image }}" class="w-full h-full object-cover" alt="{{ $item->product_stock->stock_title  ?? '' }}" title="{{ $item->product_stock->stock_title  ?? '' }}">
                                            </div>

                                            <!-- Details -->
                                            <div class="flex-grow w-full">
                                                <h4 class="text-white font-medium group-hover:text-[#2A7CFF] transition-colors line-clamp-1">
                                                    {{ $item->product->name ?? 'Product Name' }}
                                                </h4>

                                                <p class="text-gray-500 text-xs mt-1">
                                                    {{ $item->product_stock->stock_title  ?? '' }}
                                                </p>
                                                <div>
                                                    @if($totalPendingQty > 0)
                                                        
                                                        <button onclick="openStatusModal()" class="mt-[10px] cursor-pointer flex items-center gap-2 px-3 py-1 bg-yellow-500/5 border border-yellow-500/20 rounded-full hover:bg-yellow-500 transition-all duration-600 group/btn">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse font-medium group-hover:bg-white transition-all duration-600"></span>
                                                        <span class="text-yellow-500 text-[10px] font-bold uppercase group-hover:text-white transition-all duration-600">
                                                            Return Requested {{ $totalPendingQty }}@if($totalPendingQty <  $item->quantity) out of {{ $item->quantity }} @endif
                                                        </span>
                                                    </button>
                                                    @endif

                                                    @if($totalReturnedQty > 0)
                                                        <button class="px-2 py-1 text-green-500 bg-green-500/10 rounded-full text-[10px] font-bold uppercase">
                                                            Returned {{ $totalReturnedQty }}@if($totalReturnedQty <  $item->quantity) out of {{ $item->quantity }} @endif
                                                        </button>
                                                    @endif

                                                    @if($totalRejectedQty > 0)
                                                        <button class="mt-[10px] cursor-pointer flex items-center gap-2 px-3 py-1 bg-red-500/5 border border-red-500/20 rounded-full hover:bg-red-500 transition-all duration-600 group/btn">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse font-medium group-hover:bg-white transition-all duration-600"></span>
                                                        <span class="text-red-500 text-[10px] font-bold uppercase group-hover:text-white transition-all duration-600">Return Rejected  {{ $totalRejectedQty }}@if($totalRejectedQty <  $item->quantity) out of {{ $item->quantity }} @endif
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Mobile price -->
                                                <div class="mt-2 text-sm md:hidden">
                                                    <h5 class="text-white text-[15px] font-medium flex flex-row align-center items-center gap-[10px]">
                                                        <svg class="w-[14px] lg:w-[20px]" width="18" height="15" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff"></path>
                                                        </svg>
                                                        {{ number_format(($item->price / $item->quantity ), 2) }}
                                                    </h5>
                                                </div>
                                            </div>

                                            <!-- Desktop price -->
                                            <div class="hidden md:flex flex-col text-right w-full items-end">
                                                <h5 class="text-white text-[20px] font-medium flex flex-row align-center items-center gap-[10px]">
                                                    <svg class="w-[14px] lg:w-[20px]" width="18" height="15" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff"></path>
                                                    </svg>
                                                    {{ number_format(($item->price / $item->quantity ), 2) }}
                                                </h5>

                                                <p class="text-gray-500 text-xs uppercase">
                                                    Qty: {{ $item->quantity }}
                                                </p>
                                            </div>
                                        </a>
                                        @endforeach
                                    </div>
                                    @endif
                                    
                                    <!-- Normal Items -->
                                    <div class="divide-y divide-white/5">
                                        @if($pcBuilderItems->count() > 0)
                                        <h3 class="text-white text-lg font-semibold p-6">Other Products</h3>
                                        @endif
                                    @foreach($normalItems as $item)
                                        @php
                                            $image = asset('assets/img/placeholder.jpg');

                                            if (!empty($item->product_stock?->image)) {
                                            $image = Storage::url($item->product_stock->image);
                                            } elseif (!empty($item->product?->thumbnail_img)) {
                                            $image = Storage::url($item->product->thumbnail_img);
                                            }
                                            

                                            $itemReturns = $item->returns ?? collect(); // make sure OrderDetail has returns relation
                                            $totalReturnedQty = $itemReturns->where('status', 'approved')->sum('return_qty');
                                            $totalPendingQty = $itemReturns->where('status', 'pending')->sum('return_qty');
                                            $totalRejectedQty = $itemReturns->where('status', 'rejected')->sum('return_qty');
                                            $remainingQty = $item->quantity - ($totalReturnedQty + $totalPendingQty);

                                            $returnedPrice = $totalReturnedQty * $item->offer_price;
                                            $totalReturnedPrice += $returnedPrice;
                                        @endphp

                                    
                                        <a href="{{route('product.details', [$item->product->slug,$item->product_stock->sku])}}" class="p-6 flex items-center gap-6 group">

                                            <!-- Image -->
                                            <div class="w-20 h-20 bg-[#0f161b] rounded-xl border border-white/5 flex-shrink-0 flex items-center justify-center p-2">
                                                <img src="{{ $image }}" class="w-full h-full object-cover" alt="{{ $item->product_stock->stock_title  ?? '' }}" title="{{ $item->product_stock->stock_title  ?? '' }}">
                                            </div>

                                            <!-- Details -->
                                            <div class="flex-grow w-full">
                                                <h4 class="text-white font-medium group-hover:text-[#2A7CFF] transition-colors line-clamp-1">
                                                    {{ $item->product->name ?? 'Product Name' }}
                                                </h4>

                                                <p class="text-gray-500 text-xs mt-1">
                                                    {{ $item->product_stock->stock_title  ?? '' }}
                                                </p>
                                                <div>
                                                    @if($totalPendingQty > 0)
                                                        
                                                        <button onclick="openStatusModal()" class="mt-[10px] cursor-pointer flex items-center gap-2 px-3 py-1 bg-yellow-500/5 border border-yellow-500/20 rounded-full hover:bg-yellow-500 transition-all duration-600 group/btn">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse font-medium group-hover:bg-white transition-all duration-600"></span>
                                                        <span class="text-yellow-500 text-[10px] font-bold uppercase group-hover:text-white transition-all duration-600">
                                                            Return Requested {{ $totalPendingQty }}@if($totalPendingQty <  $item->quantity) out of {{ $item->quantity }} @endif
                                                        </span>
                                                    </button>
                                                    @endif

                                                    @if($totalReturnedQty > 0)
                                                        <button class="px-2 py-1 text-green-500 bg-green-500/10 rounded-full text-[10px] font-bold uppercase">
                                                            Returned {{ $totalReturnedQty }}@if($totalReturnedQty <  $item->quantity) out of {{ $item->quantity }} @endif
                                                        </button>
                                                    @endif

                                                    @if($totalRejectedQty > 0)
                                                        <button class="mt-[10px] cursor-pointer flex items-center gap-2 px-3 py-1 bg-red-500/5 border border-red-500/20 rounded-full hover:bg-red-500 transition-all duration-600 group/btn">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse font-medium group-hover:bg-white transition-all duration-600"></span>
                                                        <span class="text-red-500 text-[10px] font-bold uppercase group-hover:text-white transition-all duration-600">Return Rejected  {{ $totalRejectedQty }}@if($totalRejectedQty <  $item->quantity) out of {{ $item->quantity }} @endif
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Mobile price -->
                                                <div class="mt-2 text-sm md:hidden">
                                                    <h5 class="text-white text-[15px] font-medium flex flex-row align-center items-center gap-[10px]">
                                                        <svg class="w-[14px] lg:w-[20px]" width="18" height="15" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff"></path>
                                                        </svg>
                                                        {{ number_format(($item->price / $item->quantity ), 2) }}
                                                    </h5>
                                                </div>
                                            </div>

                                            <!-- Desktop price -->
                                            <div class="hidden md:flex flex-col text-right w-full items-end">
                                                <h5 class="text-white text-[20px] font-medium flex flex-row align-center items-center gap-[10px]">
                                                    <svg class="w-[14px] lg:w-[20px]" width="18" height="15" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1.3245 0.0149425C1.3305 0.023908 1.3635 0.0642529 1.395 0.103103C1.6245 0.375057 1.797 0.817356 1.89 1.37471C1.9515 1.7408 1.9545 1.85586 1.9545 3.25149V4.55149H1.3275C0.7545 4.55149 0.6885 4.54851 0.576 4.52609C0.399 4.48874 0.216 4.38862 0.093 4.26012C-0.0045 4.15701 -0.0015 4.15103 0.0045 4.46333C0.012 4.72184 0.015 4.75023 0.0525 4.89069C0.1125 5.11333 0.195 5.2792 0.3195 5.42713C0.489 5.63034 0.6615 5.74391 0.9075 5.82012C0.96 5.83506 1.071 5.84103 1.464 5.84402L1.9545 5.85149V6.49851V7.14701L1.263 7.14253L0.5685 7.13805L0.4485 7.09023C0.306 7.03345 0.2415 6.99161 0.102 6.86759L0 6.77644L0.006 7.06184C0.0135 7.32632 0.015 7.35621 0.0525 7.49069C0.183 7.96586 0.498 8.30506 0.9135 8.41563C1.017 8.44402 1.0575 8.44552 1.491 8.45149L1.9545 8.45747V9.79632C1.9545 10.6047 1.95 11.2009 1.9425 11.3025C1.935 11.3952 1.911 11.5685 1.89 11.6895C1.7925 12.2469 1.617 12.6668 1.365 12.9387L1.314 12.994H3.8505C5.367 12.994 6.501 12.988 6.6675 12.9806C6.96 12.9656 7.6125 12.9014 7.7595 12.87C7.806 12.861 7.893 12.8476 7.95 12.8386C8.0715 12.8207 8.2725 12.7789 8.562 12.7056C8.97 12.604 9.342 12.477 9.7065 12.3156C9.8205 12.2648 10.1475 12.099 10.2345 12.0467C10.281 12.0198 10.3365 11.9869 10.3575 11.9764C10.416 11.9451 10.5135 11.8823 10.656 11.7807C10.7265 11.7299 10.797 11.6806 10.812 11.6701C10.875 11.6283 11.0925 11.4475 11.1915 11.3563C11.568 11.0111 11.883 10.6271 12.1275 10.2162C12.162 10.1564 12.207 10.0817 12.2265 10.0503C12.276 9.96667 12.48 9.54828 12.4995 9.48552C12.5085 9.45713 12.5205 9.42724 12.5265 9.42126C12.5655 9.37046 12.7905 8.66517 12.8175 8.51126C12.8265 8.46195 12.831 8.45448 12.8685 8.44701C12.8925 8.44253 13.242 8.44253 13.6455 8.44552C14.4525 8.45149 14.4525 8.45149 14.631 8.53368C14.7315 8.58 14.7615 8.60092 14.8725 8.70103C15.018 8.83103 15.0045 8.85195 14.9955 8.52621C14.9895 8.33494 14.982 8.2169 14.9685 8.16908C14.9175 7.98529 14.9055 7.94644 14.8605 7.85379C14.7135 7.53402 14.4675 7.3054 14.1525 7.19632L14.0295 7.15149L13.5285 7.14552L13.029 7.13805L13.035 6.96322C13.041 6.7331 13.041 6.27736 13.0335 6.04276L13.0275 5.85448L13.6965 5.85149C14.2695 5.84851 14.376 5.85149 14.439 5.86793C14.628 5.92023 14.7555 5.99195 14.9115 6.13391L14.9985 6.2146V5.99345C14.9985 5.73046 14.985 5.61391 14.931 5.44057C14.8245 5.08943 14.6145 4.82793 14.3145 4.66655C14.1195 4.56195 14.1075 4.55897 13.437 4.55448C13.044 4.55149 12.8385 4.54552 12.828 4.53655C12.819 4.52759 12.8115 4.51264 12.8115 4.50069C12.8115 4.48874 12.789 4.3946 12.759 4.29299C12.408 3.05724 11.7525 2.07552 10.794 1.34782C10.6635 1.2477 10.344 1.03701 10.215 0.965287C10.1655 0.936897 10.1115 0.907011 10.098 0.898046C10.035 0.863678 9.6735 0.687356 9.5835 0.65C9.5295 0.626092 9.459 0.596207 9.4275 0.584253C8.898 0.355632 8.01 0.138966 7.332 0.0717241C7.221 0.0612644 7.074 0.0448276 7.0065 0.0388506C6.7005 0.00448276 6.276 0 3.8655 0C1.8285 0 1.317 0.00448276 1.3245 0.0149425ZM6.285 0.661954C6.792 0.691839 7.104 0.73069 7.4685 0.818851C8.5815 1.08184 9.3645 1.6377 9.933 2.56713C9.9855 2.65379 10.2075 3.10506 10.2405 3.19621C10.398 3.61908 10.4745 3.87012 10.542 4.20184C10.5585 4.28253 10.581 4.39012 10.5915 4.44092C10.602 4.49023 10.6065 4.53655 10.602 4.54103C10.5945 4.54701 9.0885 4.55 7.2525 4.54851L3.915 4.54552L3.9105 2.6254C3.909 1.57046 3.9105 0.693333 3.915 0.676897L3.921 0.648506H4.9875C5.5725 0.648506 6.1575 0.654483 6.285 0.661954ZM10.7475 5.89632C10.758 5.96057 10.758 7.05138 10.7475 7.10517L10.7385 7.14552L7.326 7.14253L3.915 7.13805L3.912 6.50448C3.909 6.15632 3.912 5.86644 3.915 5.86046C3.9195 5.85299 5.373 5.84851 7.3305 5.84851H10.7385L10.7475 5.89632ZM10.5945 8.46195C10.602 8.48437 10.566 8.66816 10.4925 8.96701C10.4085 9.30322 10.2945 9.64241 10.179 9.89345C10.122 10.022 9.9795 10.2999 9.945 10.3522C9.9285 10.3761 9.8805 10.4523 9.8385 10.5195C9.5685 10.9409 9.183 11.3249 8.7435 11.6089C8.583 11.7105 8.253 11.8838 8.1645 11.9107C8.1465 11.9152 8.127 11.9241 8.1195 11.9301C8.109 11.9391 7.9725 11.9899 7.8135 12.0467C7.521 12.1498 6.9645 12.2618 6.5175 12.3082C6.228 12.3366 6.1815 12.338 5.067 12.338H3.9135V10.4V8.46046L7.227 8.45448C9.0495 8.45149 10.551 8.44701 10.563 8.44402C10.5765 8.44253 10.59 8.45149 10.5945 8.46195Z" fill="#ffffff"></path>
                                                    </svg>
                                                    {{ number_format(($item->price / $item->quantity ), 2) }}
                                                </h5>

                                                <p class="text-gray-500 text-xs uppercase">
                                                    Qty: {{ $item->quantity }}
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--//normal order card-->

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl overflow-hidden">
                                    <div class="p-6 border-b border-[#282B34] flex justify-between items-center">
                                        <h4 class="text-white font-medium uppercase">Shipping Address</h4>
                                    </div>
                                    <div class="divide-y divide-[#282B34]">
                                        <div class="p-6 hover:border-white/10 transition-colors">
                                            @php
                                            $shippingAddress = json_decode($order->shipping_address);
                                            @endphp

                                            <h4 class="text-white font-medium mb-1">{{ $shippingAddress?->name }}</h4>
                                            <p class="text-gray-500 text-sm leading-relaxed">
                                                {{ $shippingAddress?->address }} <br>
                                                {{ $shippingAddress?->city }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl overflow-hidden">
                                    <div class="p-6 border-b border-[#282B34] flex justify-between items-center">
                                        <h4 class="text-white font-medium uppercase">Billing Address</h4>
                                    </div>
                                    <div class="divide-y divide-[#282B34]">
                                        <div class="p-6 hover:border-white/10 transition-colors">
                                            @php
                                            $billingAddress = json_decode($order->billing_address);
                                            @endphp
                                            <h4 class="text-white font-medium mb-1">{{ $billingAddress?->name }}</h4>
                                            <p class="text-gray-500 text-sm leading-relaxed">
                                                {{ $billingAddress?->address }} <br>
                                                {{ $billingAddress?->city }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="lg:col-span-1">
                            <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl p-8 sticky top-[180px]">
                                <h4 class="text-white font-medium mb-6 uppercase">Total Summary</h4>

                                <div class="space-y-4">
                                    <ul class="border-y-1 border-[#282B34] py-[20px] mt-[20px] border-b-0">
                                        <li class="py-[10px]">
                                            <div class="flex flex-row justify-between">
                                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Subtotal</span>
                                                <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[5px] text-[15px]">
                                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af"></path>
                                                    </svg>
                                                    {{ format_price($order->sub_total) }}
                                                </span>
                                            </div>
                                        </li>
                                        <li class="py-[10px]">
                                            <div class="flex flex-row justify-between">
                                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Discount</span>
                                                <span class="flex flex-row text-[#29A706] items-center justify-end text-right gap-[5px] text-[14px]">
                                                    - <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path class="fill-[#29A706]" d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af"></path>
                                                    </svg>
                                                    {{ format_price(( $order->sub_total+ $order->tax + $order->shipping_cost+ $order->warranty_amount) - $order->grand_total) }}
                                                </span>
                                            </div>
                                        </li>
                                        <li class="py-[10px]">
                                            <div class="flex flex-row justify-between">
                                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Estimated Tax</span>
                                                <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[5px] text-[15px]">
                                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af"></path>
                                                    </svg>
                                                    {{ format_price($order->tax) }}
                                                </span>
                                            </div>
                                        </li>
                                        <li class="py-[10px]">
                                            <div class="flex flex-row justify-between">
                                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Warranty (Premium Care+)</span>
                                                <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[5px] text-[15px]">
                                                    + <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af"></path>
                                                    </svg>
                                                    {{ format_price($order->warranty_amount) }}
                                                </span>
                                            </div>
                                        </li>
                                        <li class="py-[10px]">
                                            <div class="flex flex-row justify-between">
                                                <span class="text-[#99a1af] text-[15px] justify-start text-left">Shipping &amp; Handling</span>
                                                <span class="flex flex-row text-[#99a1af] items-center justify-end text-right gap-[5px] text-[15px]">
                                                    @if($order->shipping_cost == 0 )
                                                    <span class="text-black uppercase font-bold text-[10px] bg-[#29A706] px-2 py-1 rounded">Free</span>
                                                    @else
                                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="#99a1af"></path>
                                                    </svg>
                                                    {{ format_price($order->shipping_cost) }}
                                                    @endif
                                                </span>

                                            </div>
                                        </li>
                                        <li class="border-y-1 border-[#282B34] py-[20px] pb-[0px] mt-[20px] border-b-0">
                                            <div class="flex flex-row justify-between">
                                                <span class="text-white text-[18px] justify-start text-left uppercase font-bold">TOTAL</span>
                                                <span class="flex flex-row text-white items-center justify-end text-right gap-[5px] text-[18px] font-bold">
                                                    <svg width="18" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="white"></path>
                                                    </svg>
                                                     {{ format_price($order->grand_total) }}
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="pt-6 border-t border-[#282B34] flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-[14px] leading-[20px]">
                                        Transaction Verified<br>
                                        <span class="text-white font-medium">Encrypted Checkout</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
</section>
<!--//Order single-->


<!--return order modal-->
<div id="returnModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
    <div id="modalContent" class="bg-[#1C2228] border border-[#282B34] w-full max-w-3xl rounded-[20px] shadow-2xl overflow-hidden">

        <div class="p-6 border-b border-[#282B34] flex justify-between items-center bg-[#1C2228]">
            <h4 class="text-white font-medium uppercase">Return Items</h4>
            <button id="closeModalX" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/5 text-gray-400 transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="returnOrderForm">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2">
                @foreach($order->orderDetails->where('is_pc_builder', 0) as $detail)
                    @php
                        // Total quantity already returned for this order detail
                        $returnedQty = $detail->returns->sum('return_qty');
                        // Remaining quantity that can still be returned
                        $remainingQty = $detail->quantity - $returnedQty;
                        if($remainingQty <= 0) continue;
                    @endphp
                    @php
                        $image = asset('assets/img/placeholder.jpg');
                        if (!empty($detail->product_stock?->image)) {
                            $image = Storage::url($detail->product_stock->image);
                        } elseif (!empty($detail->product?->thumbnail_img)) {
                            $image = Storage::url($detail->product->thumbnail_img);
                        }
                    @endphp
                    <label class="group flex items-center gap-5 bg-[#0f161b] p-4 rounded-[15px] border border-white/5 cursor-pointer hover:border-[#2A7CFF]/30 transition-all">
                        <label class="relative flex items-center justify-center cursor-pointer">
                            <input type="checkbox" class="return-checkbox peer hidden" data-detail-id="{{ $detail->id }}" data-max-qty="{{ $detail->quantity }}">
                            <div class="w-6 h-6 border-2 border-gray-700 rounded-md peer-checked:bg-[#2A7CFF] peer-checked:border-[#2A7CFF] transition-all flex items-center justify-center">
                                <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none size-3.5 stroke-white opacity-0 transition-opacity">
                                    <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </label>

                        <!-- Product image & info -->
                        <div class="w-14 h-14 bg-[#1C2228] rounded-[5px] border border-white/5 p-2 flex-shrink-0">
                            <img src="{{ $image }}" class="w-full h-full object-cover" alt="{{ $detail->product->name ?? '' }}" title="{{ $detail->product->name ?? '' }}">
                        </div>
                        <div class="flex-grow w-full mr-[50px]">
                            <p class="text-white text-sm font-medium line-clamp-1">
                                 {{ $detail->product->name ?? '' }}
                                @if(!empty($detail->product_stock->stock_title))
                                    : {{ $detail->product_stock->stock_title }}
                                @endif
                                
                            </p>
                            <p class="text-gray-500 text-xs uppercase tracking-tighter mt-[5px]">QTY: <span class="item-total-qty">{{ $detail->quantity }}</span></p>
                        </div>

                        <!-- Quantity dropdown -->
                         
                        <div class="return-qty-container hidden min-w-[110px]">
                            @if($remainingQty > 0)
                                <select name="return_qty[{{ $detail->id }}]" 
                                        class="w-full bg-[#0B0F13] border border-gray-800 text-white text-[13px] font-medium pl-4 pr-10 py-3 rounded-xl cursor-pointer outline-none appearance-none hover:border-gray-600 focus:border-[#2A7CFF] focus:ring-1 focus:ring-[#2A7CFF]/30 transition-all shadow-inner">
                                        
                                    @for($i = 1; $i <= $remainingQty; $i++)
                                        <option value="{{ $i }}">Qty: {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            @endif
                        </div>
                    </label>
                @endforeach

                <div class="pt-4 return-reason-container">
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2 px-1">Reason for return</p>
                    <textarea rows="3" class="w-full bg-[#0f161b] border border-[#282B34] rounded-2xl p-4 text-white text-sm focus:border-[#2A7CFF] outline-none transition-all placeholder:text-gray-700 resize-none" placeholder="Write your reason here..."></textarea>
                </div>
            </div>
        

            <div class="p-6 bg-[#1C2228] flex gap-3">
                <button id="cancelModalBtn" class="flex w-full cursor-pointer items-center justify-center gap-3 px-6 py-4 rounded-xl border border-[#2E363E] text-gray-400 font-medium uppercase text-[13px] hover:bg-[#252C33] hover:text-white transition-all duration-300 group">
                    Cancel
                </button>
                <button class="w-full flex py-4 rounded-xl bg-[#2A7CFF] text-center justify-center text-white hover:bg-[#1d69e4] shadow-lg shadow-[#2A7CFF]/20 transition-all text-sm font-medium uppercase cursor-pointer" type="submit">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
<!--//return order modal-->

<!--return status modal-->
<!-- <div id="statusModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-md p-4" onclick="closeStatusModal()">
        <div id="statusContent" class="bg-[#1C2228] border border-[#282B34] w-full max-w-2xl rounded-[20px] shadow-2xl overflow-hidden transform transition-all" onclick="event.stopPropagation()">
            
            <div class="p-6 border-b border-[#282B34] flex justify-between items-center bg-[#1C2228]">
                <h4 class="text-white font-medium uppercase tracking-wider text-sm">Return Details</h4>
                <button onclick="closeStatusModal()" class="text-gray-500 hover:text-white transition-colors cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-6">
                
                <div class="flex items-center gap-6 group">
                    <div class="w-20 h-20 bg-[#0f161b] rounded-xl border border-white/5 flex-shrink-0 flex items-center justify-center p-2">
                        <img src="src/images/product-single-01.webp" class="w-full h-full object-cover rounded-lg" alt="Product">
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-white font-medium line-clamp-1 text-sm md:text-base">VENGEANCE a7500 Gaming PC...</h4>
                        <p class="text-gray-500 text-xs mt-1">AMD Ryzen 7 9800X3D • RTX 5080 • 32GB DDR5</p>
                        <p class="text-[#2A7CFF] text-[11px] mt-2 font-bold uppercase tracking-tight">March 04, 2026 • 15:56</p>
                    </div>
                    <div class="hidden md:flex flex-col text-right items-end">
                        <p class="text-gray-400 text-[10px] uppercase font-bold mb-2">Quantity</p>
                            <p class="text-white text-lg font-bold leading-none">01</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/5">
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-3 px-1">Reason for return</p>
                    <div class="w-full bg-[#0f161b] border border-[#282B34] rounded-2xl p-5 text-gray-300 text-sm leading-relaxed italic relative">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s...
                    </div>
                </div>
            </div>

            <div class="p-6 bg-[#171c21] border-t border-[#282B34]">
                <p class="text-center text-gray-400 text-[13px] italic">
                    Our team is currently reviewing your request. You will receive an email once approved.
                </p>
            </div>
        </div>
    </div> -->
<!--//return status modal-->


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const minInput = document.getElementById('range-min');
        const maxInput = document.getElementById('range-max');
        const minLabel = document.getElementById('min-price');
        const maxLabel = document.getElementById('max-price');
        const progress = document.getElementById('slider-progress');

        const priceGap = 500; // Minimum gap between handles


    });


    // 1. Define the function globally immediately
    window.toggleModal = function() {
        const overlay = document.getElementById('modal-overlay');
        const container = document.getElementById('modal-container');

        if (!overlay || !container) {
            console.error("Modal elements not found in DOM!");
            return;
        }

        const isHidden = overlay.classList.contains('hidden');

        if (isHidden) {
            console.log("Opening Modal...");
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Small delay to trigger Tailwind transitions
            setTimeout(() => {
                overlay.classList.add('opacity-100');
                container.classList.add('scale-100');
                container.classList.remove('scale-95');
            }, 10);
        } else {
            console.log("Closing Modal...");
            overlay.classList.remove('opacity-100');
            container.classList.add('scale-95');
            container.classList.remove('scale-100');
            setTimeout(() => {
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    };

    // 2. Attach the click listener safely
    document.addEventListener('click', function(e) {
        const overlay = document.getElementById('modal-overlay');
        const container = document.getElementById('modal-container');

        // Check if the user clicked EXACTLY the overlay (the dark part)
        // and NOT the container (the white/dark box)
        if (e.target === overlay && !container.contains(e.target)) {
            window.toggleModal();
        }
    });



    // --- SPECIFICATION MODAL LOGIC ---
    const sOverlay = document.getElementById('spec-modal-overlay');
    const sContainer = document.getElementById('spec-modal-container');

    function toggleSpecModal() {
        const isHidden = sOverlay.classList.contains('hidden');
        if (isHidden) {
            sOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                sOverlay.classList.add('opacity-100');
                sContainer.classList.add('scale-100');
                sContainer.classList.remove('scale-95');
            }, 10);
        } else {
            sOverlay.classList.remove('opacity-100');
            sContainer.classList.add('scale-95');
            sContainer.classList.remove('scale-100');
            setTimeout(() => {
                sOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    }

    // --- WARRANTY MODAL LOGIC ---
    const wOverlay = document.getElementById('warranty-modal-overlay');
    const wContainer = document.getElementById('warranty-modal-container');

    function toggleWarrantyModal() {
        const isHidden = wOverlay.classList.contains('hidden');
        if (isHidden) {
            wOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                wOverlay.classList.add('opacity-100');
                wContainer.classList.add('scale-100');
                wContainer.classList.remove('scale-95');
            }, 10);
        } else {
            wOverlay.classList.remove('opacity-100');
            wContainer.classList.add('scale-95');
            wContainer.classList.remove('scale-100');
            setTimeout(() => {
                wOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    }

    // --- OUTSIDE CLICK CLOSING ---
    window.addEventListener('click', (e) => {
        if (e.target === sOverlay) toggleSpecModal();
        if (e.target === wOverlay) toggleWarrantyModal();
    });


    window.selectWarranty = function(selectedElement) {
        // 1. Get all warranty cards
        const cards = document.querySelectorAll('.warranty-card');

        cards.forEach(card => {
            // 2. Reset Styles to "Unselected"
            card.classList.remove('border-2', 'border-[#2A7CFF]', 'bg-[#161B22]');
            card.classList.add('border', 'border-gray-800', 'bg-[#282B3450]');

            // 3. Hide Checkmark icons
            const icon = card.querySelector('.check-icon');
            if (icon) icon.classList.add('hidden');
        });

        // 4. Apply "Selected" Styles to the clicked element
        selectedElement.classList.add('border-2', 'border-[#2A7CFF]', 'bg-[#161B22]');
        selectedElement.classList.remove('border', 'border-gray-800', 'bg-[#282B3450]');

        // 5. Show the checkmark for the selected plan
        const activeIcon = selectedElement.querySelector('.check-icon');
        if (activeIcon) activeIcon.classList.remove('hidden');

        console.log("Selected Warranty Plan:", selectedElement.querySelector('span').innerText);
    };



    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('billing-toggle');
        const list = document.getElementById('address-list-container');
        const overlay = document.getElementById('addr-modal-overlay');
        const modal = document.getElementById('addr-modal-container');


        window.openModal = () => {
            overlay.classList.remove('hidden');
            // FIX: Lock the body to prevent double scrollbars
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                overlay.classList.add('opacity-100');
                modal.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        window.closeModal = () => {
            overlay.classList.remove('opacity-100');
            modal.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                overlay.classList.add('hidden');
                // FIX: Restore scroll when closed
                document.body.style.overflow = 'auto';
            }, 300);
        };

        document.addEventListener('click', (e) => {
            if (e.target.closest('#open-address-modal')) openModal();
            if (e.target.closest('#close-modal-x') || e.target.closest('#discard-btn') || e.target === overlay) closeModal();
        });
    });



    document.addEventListener('DOMContentLoaded', () => {
        const variantButtons = document.querySelectorAll('.variant-btn');

        variantButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                // Find all buttons in the same group (GPU or RAM)
                const group = btn.closest('.variant-group');
                const siblingButtons = group.querySelectorAll('.variant-btn');

                // Reset all buttons in this group
                siblingButtons.forEach(s => {
                    s.classList.remove('active', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'border-1');
                    s.classList.add('border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'border');
                    s.classList.remove('text-white', 'font-bold');
                });

                // Set active state for clicked button
                btn.classList.add('active', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'border-1');
                btn.classList.remove('border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'border');
                btn.classList.add('text-white', 'font-bold');

                // Trigger Price Update (SEO/Analytics Hook)
                updateProductPrice();
            });
        });

        function updateProductPrice() {
            console.log("Recalculating price based on selections...");
            // Here you would logic to change the 1299.00 text
        }
    });


    document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus(); // Move to next
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus(); // Move back on backspace
            }
        });
    });
</script>
<!--//script-->


<!--return order modal script-->
<script>
    // const modal = document.getElementById('returnModal');
    // const openBtn = document.getElementById('openReturnBtn');
    // const closeX = document.getElementById('closeModalX');
    // const cancelBtn = document.getElementById('cancelModalBtn');

    // // Smooth Open
    // if (openBtn) {
    //     openBtn.addEventListener('click', () => {
    //         modal.classList.add('active');
    //         document.body.style.overflow = 'hidden'; // Stop background scroll
    //     });
    // }

    // // Smooth Close
    // const closeModal = () => {
    //     modal.classList.remove('active');
    //     document.body.style.overflow = '';
    // };

    // closeX.onclick = closeModal;
    // cancelBtn.onclick = closeModal;

    // // Click outside to close (Smoothly)
    // modal.addEventListener('click', (e) => {
    //     if (e.target === modal) closeModal();
    // });

    // // Escape Key to close
    // window.addEventListener('keydown', (e) => {
    //     if (e.key === "Escape") closeModal();
    // });
</script>
<!--//return order modal script-->

<!--return modal status modal script-->
<script>
    /* Simple JS triggers */
    function openStatusModal() {
        document.getElementById('statusModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Escape Key Support
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeStatusModal();
    });
</script>
<!--//return modal status modal script-->


<script>
    document.addEventListener('click', function(e) {

        if (e.target.classList.contains('cancel-order-btn')) {

            let button = e.target;
            let orderId = button.getAttribute('data-id');

            // if (!confirm('Are you sure you want to cancel this order?')) {
            //     return;
            // }
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to cancel this order?',
                icon: 'warning',
                width: '320px', 
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
            fetch('/my-orders/' + orderId + '/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(res => {

                    if (res.status === 'success') {
                        toastr.success(res.message);

                        // Disable button
                        button.innerText = 'Cancelled';
                        button.disabled = true;
                        button.classList.remove('hover:bg-red-500');
                        button.classList.add('opacity-50', 'cursor-not-allowed');

                        // Update status text (if exists)
                        let statusText = document.querySelector('.order-status-text');
                        if (statusText) {
                            statusText.innerText = 'Cancelled';
                        }
                        location.reload(); // Refresh to update all details and hide return/cancel options

                    } else {
                        toastr.error(res.message);
                    }

                })
                .catch(() => {
                    toastr.error('Something went wrong!');
                });
            });
        }

    });


    /*document.addEventListener('click', function(e) {
        if (e.target.closest('.return-order-btn')) {

            const button = e.target.closest('.return-order-btn');
            const orderId = button.getAttribute('data-id');

            if (!confirm('Are you sure you want to return this order?')) {
                return;
            }

            fetch(`/my-orders/${orderId}/return`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {

                    if (res.status === 'success') {
                        toastr.success(res.message);

                        button.innerText = 'Returned';
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                        button.classList.remove('hover:bg-[#2A7CFF]');

                        const statusText = document.querySelector('.order-status-text');
                        if (statusText) statusText.innerText = 'Returned';

                    } else {
                        toastr.error(res.message);
                    }

                })
                .catch(() => toastr.error('Something went wrong!'));
        }
    });*/

    // document.addEventListener('click', function (e) {
    //     // Open return modal
    //     if (e.target.closest('#openReturnBtn')) {
    //         document.getElementById('returnOrderModal').classList.remove('hidden');
    //     }

    //     // Close modal
    //     if (e.target.closest('#closeReturnModal') || e.target.closest('#cancelReturn')) {
    //         document.getElementById('returnOrderModal').classList.add('hidden');
    //     }
    // });

//     document.addEventListener('DOMContentLoaded', () => {
//     const returnCheckboxes = document.querySelectorAll('.return-checkbox');
//     const returnOrderForm = document.getElementById('returnOrderForm');
//     const submitButton = returnOrderForm.querySelector('button[type="submit"]');

//     // Store remaining quantities (initialize from Blade)
//     const remainingQty = {};
//     returnCheckboxes.forEach(checkbox => {
//         remainingQty[checkbox.dataset.detailId] = parseInt(checkbox.dataset.maxQty);
//     });

    // function updateSubmitVisibility() {
    //     const hasRemaining = Object.values(remainingQty).some(qty => qty > 0);
    //     submitButton.disabled = !hasRemaining;
    //     submitButton.style.display = hasRemaining ? 'flex' : 'none';
    // }

//     returnCheckboxes.forEach(checkbox => {
//         const detailId = checkbox.dataset.detailId;
//         const qtyContainer = checkbox.closest('label').querySelector('.return-qty-container');
//         const select = qtyContainer.querySelector('select');

//         // Show/hide dropdown on check
//         checkbox.addEventListener('change', () => {
//             if (checkbox.checked) {
//                 qtyContainer.classList.remove('hidden');

//                 // Populate dropdown with remaining quantity
//                 select.innerHTML = '';
//                 const maxQty = remainingQty[detailId];
//                 for (let i = 1; i <= maxQty; i++) {
//                     const opt = document.createElement('option');
//                     opt.value = i;
//                     opt.text = `Qty: ${String(i).padStart(2,'0')}`;
//                     select.appendChild(opt);
//                 }
//             } else {
//                 qtyContainer.classList.add('hidden');
//             }
//         });

//         // Update remainingQty on form submit
//         returnOrderForm.addEventListener('submit', (e) => {
//             e.preventDefault();

//             if (!checkbox.checked) return;

//             const selectedQty = parseInt(select.value);
//             remainingQty[detailId] -= selectedQty;

//             // Reset checkbox & dropdown
//             checkbox.checked = false;
//             qtyContainer.classList.add('hidden');

//             // Update displayed total qty
//             const itemQtySpan = checkbox.closest('label').querySelector('.item-total-qty');
//             itemQtySpan.innerText = remainingQty[detailId];

//             updateSubmitVisibility();

//             // TODO: AJAX call to save return (optional)
//         });
//     });

//     updateSubmitVisibility();
// });


// Return Modal Script
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('returnModal');
    const openBtn = document.getElementById('openReturnBtn');
    const closeX = document.getElementById('closeModalX');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const returnOrderForm = document.getElementById('returnOrderForm');
    const submitBtn = returnOrderForm.querySelector('button[type="submit"]');

    // Remaining quantity tracker
    const remainingQty = {};
    document.querySelectorAll('.return-checkbox').forEach(cb => {
        remainingQty[cb.dataset.detailId] = parseInt(cb.dataset.maxQty);
    });

    // --- Modal Open / Close ---
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    const closeModal = () => {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    };
    closeX.onclick = closeModal;
    cancelBtn.onclick = closeModal;
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    window.addEventListener('keydown', e => { if (e.key === "Escape") closeModal(); });

    // --- Checkbox Logic ---
    document.querySelectorAll('.return-checkbox').forEach(cb => {
        const detailId = cb.dataset.detailId;
        const qtyContainer = cb.closest('label.group').querySelector('.return-qty-container');
        const select = qtyContainer.querySelector('select');

        // Initially disable
        select.disabled = true;


        // Show/hide dropdown on check
        cb.addEventListener('change', () => {
            if (cb.checked) {
                qtyContainer.classList.remove('hidden');
                select.disabled = false;   // enable only when checked
            } else {
                qtyContainer.classList.add('hidden');
                select.disabled = true;
            }
        });
    });

    // --- Update Submit Button Visibility ---
    function updateSubmitVisibility() {
        const hasRemaining = Object.values(remainingQty).some(qty => qty > 0);
        submitBtn.disabled = !hasRemaining;
        submitBtn.style.display = hasRemaining ? 'flex' : 'none';
    }
    updateSubmitVisibility();

    // --- Form Submission ---
    returnOrderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // Disable submit immediately to prevent double clicks
        submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch(`/my-orders/${formData.get('order_id')}/return`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success'){
                toastr.success(res.message);
                location.reload(); // Refresh to update all details and hide return/cancel options
                closeModal;

            } else {
                toastr.error(res.message);
            }
        })
        .catch(() => toastr.error('Something went wrong!'))
        .finally(() => {
            // Re-enable button only if needed
            submitBtn.disabled = false;
        });
    });
});

</script>

@endsection