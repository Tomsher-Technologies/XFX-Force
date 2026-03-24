<section aria-labelledby="products-heading" class="pb-24 pt-6" id="product-list-wrapper">

    <!-- Product grid -->
    <div x-show="activeTab === 'gridview'" x-transition class="tab-panel">
        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-3">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($products as $product)
                        @php
                            // Get the first stock for this product
                            $firstStock = $product->stocks->first();
                        @endphp
                        <div>
                            <div class="product-card w-full relative border-hidden rounded-[20px] overflow-hidden bg-[#1E2225] flex flex-col items-start justify-start transition-all duration-600">
                                
                                @include('frontend.partials.wishlist-icon', ['product' => $product->id, 'stock' => $firstStock->id])
                                
                                <a href="{{ route('product.details', $product->id) }}" class="product-img h-[230px] w-full relative z-[1] bg-[#0B0F13] bg-gradient-to-t from-[#0B0F13] to-[#1E2225]">
                                    <img src="{{ Storage::url($product->thumbnail_img) }}"
                                        class="absolute object-cover object-center w-full h-full"
                                        alt="Upcoming Product 1" title="Upcoming Product 1">
                                    @if (!empty($firstStock->offer_tag))
                                        <badge
                                            class="absolute top-[20px] left-[20px] bg-[#077F09] text-white text-[12px] font-medium px-[15px] py-[5px] rounded-full capitalize">
                                            {{ $firstStock->offer_tag }}</badge>
                                    @endif
                                </a>
                                <div class="product-content p-[20px] flex flex-col gap-[20px] z-[1]">
                                    <h4 class="text-white text-[18px] leading-[25px] font-medium line-clamp-2">
                                        {{ $product->name }}</h4>
                                    <h5
                                        class="price flex flex-row text-[#2A7CFF] text-[18px] m-[0] font-bold align-center items-center gap-[10px]">
                                        <img src="{{ asset('assets/images/aed.svg') }}" class="w-[15px] h-[15px]"
                                            alt="AED"
                                            title="Symbol of AED">{{ $firstStock->offer_price ?? $firstStock->price }}
                                        @if (!empty($firstStock->offer_tag))
                                            <span
                                                class="text-[#898989] font-medium line-through">{{ $firstStock->price }}</span>
                                        @endif
                                    </h5>
                                    <a href="{{ route('product.details', $product->id) }}"
                                        class="w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 text-white">Buy
                                        now</a>
                                </div>
                               
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--// Product grid -->

    <!--product list-->
    <div x-show="activeTab === 'listview'" x-transition class="tab-panel">
        <div class="flex flex-col gap-[15px]">
            @foreach ($products as $product)
                @php
                    // Get the first stock for this product
                    $firstStock = $product->stocks->first();
                @endphp
                <div class="product-card-list grid grid-cols-4 overflow-hidden bg-black/30 backdrop-blur-[60px] rounded-[20px]"
                    onclick="window.location= '{{ route('product.details', $product['id']) }}'"
                    style="cursor: pointer;">
                    <div class="product-image col-span-1">
                        <img src="{{ Storage::url($product->thumbnail_img) }}" alt="" title=""
                            class="h-full w-full object-cover object-center">
                    </div>
                    <div class="col-span-2 p-[30px]">
                        <h4 class="text-white text-[20px] mb-[20px] leading-[30px] font-medium line-clamp-2">
                            {{ $product->name }}</h4>
                        @php
                            $productSpecifications = \App\Models\ProductSpecification::where('product_id', $product->id)
                                ->with(['specification', 'specificationItem'])
                                ->get();
                        @endphp
                        @if (!empty($productSpecifications))
                            <div class="specifications">
                                <ul>
                                    @foreach ($productSpecifications as $specification)
                                        <li class="flex flex-row py-[15px] border-b-1 border-[#282B34] justify-between">
                                            <div class="title flex flex-row gap-[20px] w-full">
                                                <img src="{{ asset('assets/images/processor-icon.svg') }}"
                                                    alt="" title="" class="w-[20px] h-[20px]">
                                                <h5 class="text-[15px] text-[#636671] uppercase">
                                                    {{ $specification->specification->main_title }}</h5>
                                            </div>
                                            <div class="value w-full">
                                                <h6 class="text-[15px] text-[#C4C4C4] text-left">
                                                    @if ($specification->specificationItem)
                                                        {{ $specification->specificationItem->title }}
                                                    @endif
                                                </h6>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-span-1 p-[30px] flex flex-col justify-between">
                        <div class="price w-full flex flex-col items-end">
                            <h5
                                class="price flex flex-row text-[#2A7CFF] text-right text-[30px] m-[0] font-bold align-center items-center gap-[10px]">
                                <img src="{{ asset('assets/images/aed.svg') }}" class="w-[25px] h-[25px]"
                                    alt="AED"
                                    title="Symbol of AED">{{ $firstStock->offer_price ?? $firstStock->price }}
                            </h5>
                            @if (!empty($firstStock->offer_tag))
                                <span
                                    class="text-[#898989] font-medium line-through text-[20px]">{{ $firstStock->price }}</span>
                            @endif
                        </div>
                        <div class="button-group flex flex-col gap-[15px]">
                            <a href="#"
                                class="w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 text-white hover:bg-white hover:text-black">view
                                details</a>
                            <button
                                class="flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-600 text-white hover:bg-[#2A7CFF] hover:text-white cursor-pointer"><img
                                    src="{{ asset('assets/images/cart.svg') }}" alt="" title=""
                                    class="mr-[15px]">Buy now</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!--//product list-->

</section>