<section aria-labelledby="products-heading" class="pb-24 pt-6" id="product-list-wrapper">

    <!-- Product grid -->
    <div x-show="activeTab === 'gridview'" x-transition class="tab-panel">
        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-3">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($products as $product)
                        @php
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
                        
                        @include('frontend.partials.product_card', ['prodData' => $prodData])
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
                    onclick="window.location= '{{ $firstStock ? route('product.details', ['slug' => $product->slug, 'sku' => $firstStock->sku]) : '#' }}'"
                    style="cursor: pointer;">
                    <div class="product-image col-span-1">
                        <img src="{{ get_product_image($product->thumbnail_img,'300') }}" alt="{{ $product->name }}" title="{{ $product->name }}"
                            class="h-full w-full object-cover object-center">
                    </div>
                    <div class="col-span-2 p-[30px]">
                        <h4 class="text-white text-[20px] mb-[20px] leading-[30px] font-medium line-clamp-2">
                            {{ $product->name }}</h4>
                        @php
                            $productSpecifications = \App\Models\ProductSpecification::where('product_id', $product->id)
                                ->with(['specification', 'specificationItem'])
                                ->orderBy('sort_order')
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
                            @if (filled($firstStock->offer_tag))
                                <span
                                    class="text-[#898989] font-medium line-through text-[20px]">{{ $firstStock->price }}</span>
                            @endif
                        </div>
                        <div class="button-group flex flex-col gap-[15px]">
                            <a href="#"
                                class="w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 text-white hover:bg-white hover:text-black">view
                                details</a>
                            <!-- <button
                                class="flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-600 text-white hover:bg-[#2A7CFF] hover:text-white cursor-pointer"><img
                                    src="{{ asset('assets/images/cart.svg') }}" alt="" title=""
                                    class="mr-[15px]">View Details</button> -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!--//product list-->

</section>