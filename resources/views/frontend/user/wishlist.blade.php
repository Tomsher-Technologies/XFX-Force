@extends('frontend.layouts.app')

@section('title', 'My Account')

@section('content')

<section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[100px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="text-white">
        <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px]">
            
            @include('frontend.layouts.sidebar')

            <main class="flex-grow xl:pb-0">
                <div>
                    <div class="flex flex-col md:flex-row justify-between items-end mb-10 pb-[30px] gap-4 border-b border-[#252B31] text-center md:text-left">
                        <div class="w-full">
                            <h2 class="text-[20px] font-medium mb-1 text-white uppercase">My Wishlist</h2>
                            <p class="text-gray-500">Manage your shipping and billing locations.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-[5px] md:gap-[15px]">

                        @foreach ($wishlist as $product)
                            @php
                                $prodData = $product;
                            @endphp
                            @include('frontend.partials.product_card', ['prodData' => $prodData])
                        @endforeach
                       
                        
                    </div>
                    
                </div>
                <div class="mt-10">
                    {{ $wishlist->links('pagination::tailwind') }}
                </div>
            </main>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script>
        
    </script>
@endsection