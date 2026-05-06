@extends('frontend.layouts.app')

@section('title', 'Notifications')

@section('content')

<section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="text-white">
        <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-0 xl:border-t border-[#252b31] pt-0 xl:pt-[80px]">
            
            @include('frontend.layouts.sidebar')

            <main class="flex-grow xl:pb-0">
                    <div>
                        <div class="flex flex-row justify-between items-center mb-10 pb-[30px] gap-4 border-b border-[#252B31]">
                            <div class="w-full">
                                <h2 class="text-[18px] md:text-[20px] font-medium mb-1 md:mb-2 text-white uppercase text-center xl:text-left">Notifications</h2>
                                <p class="text-gray-500 text-[12px] md:text-base text-center xl:text-left">Stay updated with your order status and exclusive tech deals.</p>
                            </div>
                            <div class="flex-shrink-0">
                                <!-- <button class="text-[10px] md:text-[12px] text-gray-400 hover:text-white transition-colors uppercase tracking-wider cursor-pointer font-bold">Mark all as read</button> -->
                            </div>
                        </div>
                        
                        <div class="space-y-3 md:space-y-4">
                            @if($notifications->isNotEmpty())
                                @foreach($notifications as $notification)
                                    @php
                                        $isUnread = is_null($notification->read_at);
                                    @endphp
                                    @php
                                        $type = $notification->data['type'] ?? 'default';

                                        $config = [
                                            'order_placed' => [
                                                'heading' => 'Order Placed',
                                                'color' => 'blue',
                                                'icon' => 'check'
                                            ],
                                            'pending' => [
                                                'heading' => 'Order Pending Confirmation',
                                                'color' => 'yellow',
                                                'icon' => 'clock'
                                            ],
                                            'confirmed' => [
                                                'heading' => 'Order Confirmed',
                                                'color' => 'green',
                                                'icon' => 'check'
                                            ],
                                            'picked_up' => [
                                                'heading' => 'Order Picked Up',
                                                'color' => 'indigo',
                                                'icon' => 'truck'
                                            ],
                                            'on_the_way' => [
                                                'heading' => 'Out for Delivery',
                                                'color' => 'purple',
                                                'icon' => 'check'
                                            ],
                                            'delivered' => [
                                                'heading' => 'Order Delivered',
                                                'color' => 'green',
                                                'icon' => 'check'
                                            ],
                                            'cancelled' => [
                                                'heading' => 'Order Cancelled',
                                                'color' => 'red',
                                                'icon' => 'cancel'
                                            ],
                                            'cancel_approved' => [
                                                'heading' => 'Cancellation Approved',
                                                'color' => 'green',
                                                'icon' => 'check'
                                            ],
                                            'cancel_rejected' => [
                                                'heading' => 'Cancellation Rejected',
                                                'color' => 'red',
                                                'icon' => 'cancel'
                                            ],
                                            'return_approved' => [
                                                'heading' => 'Return Request Approved',
                                                'color' => 'green',
                                                'icon' => 'check'
                                            ],
                                            'return_rejected' => [
                                                'heading' => 'Return Request Rejected',
                                                'color' => 'red',
                                                'icon' => 'cancel'
                                            ],
                                            
                                        ];

                                        $current = $config[$type] ?? [
                                            'heading' => 'New Notification',
                                            'color' => 'blue',
                                            'icon' => 'bell'
                                        ];
                                    @endphp

                                    <div class="bg-[#1C2228] border border-[#282B34] border-l-4 {{ $isUnread ? 'border-l-[#2A7CFF]' : '' }} rounded-r-xl md:rounded-r-2xl overflow-hidden group hover:bg-[#252C33] transition-all duration-300">
                                        <div class="p-3 md:p-6 gap-3 md:gap-6">
                                            <div class="gap-3 md:gap-6">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-0.5 md:mb-1">
                                                        @if($isUnread)
                                                        <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-[linear-gradient(52deg,_#0844ff_11.5%,_#64b8fb_129.52%)] rounded-full"></span>
                                                        @endif
                                                        <h4 class="text-white font-medium text-[14px] md:text-lg leading-tight">
                                                            @php
                                                                $heading = 'New Notification';

                                                                if($notification->data['type'] == 'order_placed'){
                                                                    $heading = 'Order Placed';
                                                                } elseif($notification->data['type'] == 'cancel_approved'){
                                                                    $heading = 'Cancellation Approved';
                                                                } elseif($notification->data['type'] == 'cancel_rejected'){
                                                                    $heading = 'Cancellation Rejected';
                                                                } elseif($notification->data['type'] == 'confirmed'){
                                                                    $heading = 'Order Confirmed';
                                                                } elseif($notification->data['type'] == 'picked_up'){
                                                                    $heading = 'Order Picked Up';
                                                                } elseif($notification->data['type'] == 'on_the_way'){
                                                                    $heading = 'Out for Delivery';
                                                                } elseif($notification->data['type'] == 'delivered'){
                                                                    $heading = 'Order Delivered';
                                                                } elseif($notification->data['type'] == 'cancelled'){
                                                                    $heading = 'Order Cancelled';
                                                                } elseif($notification->data['type'] == 'pending'){
                                                                    $heading = 'Order Pending Confirmation';
                                                                } elseif($notification->data['type'] == 'return_approved'){
                                                                    $heading = 'Return Request Approved';
                                                                } elseif($notification->data['type'] == 'return_rejected'){
                                                                    $heading = 'Return Request Rejected';
                                                                }
                                                                
                                                            @endphp
                                                            {{ $heading }}
                                                        </h4>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <p class="text-gray-400 text-[11px] md:text-sm line-clamp-1">{{ $notification->data['message'] ?? '' }}</p>
                                                        <p class="text-gray-500 text-[9px] md:text-[11px] mt-1 uppercase tracking-tight font-bold" title="{{ $notification->created_at->format('F d, Y h:i A') }}">
                                                            {{ $notification->created_at->isToday() 
                                                                ? $notification->created_at->diffForHumans() 
                                                                : $notification->created_at->format('M d, Y h:i A') 
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-4 text-center fs-16 text-white">
                                    You're all caught up
                                    No notifications yet. Updates about your orders, returns, and offers will appear here.
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($notifications->hasPages())
                        <div class="mt-8 md:mt-10 w-full flex justify-center text-center">
                            {{ $notifications->links('vendor.pagination.frontend-dark') }}
                        </div>
                    @endif
                </main>


        </div>
    </div>
</section>
@endsection
