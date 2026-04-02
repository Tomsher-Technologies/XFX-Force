<section aria-labelledby="products-heading" class="pb-0 pt-[15px] xl:pt-6" id="product-list-wrapper">

    <!-- Product grid -->
    <div x-show="activeTab === 'gridview'" x-transition class="tab-panel">
        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-3">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-[5px] md:gap-[15px]">
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
            $firstStock = $product->stocks->first();
            @endphp

            <div class="product-card-list product-item flex flex-row md:grid md:grid-cols-4 overflow-hidden bg-black/30 backdrop-blur-[60px] rounded-[10px] transition-all hover:bg-black/40"
                onclick="window.location= '{{ $firstStock ? route('product.details', ['slug' => $product->slug, 'sku' => $firstStock->sku]) : '#' }}'"
                style="cursor: pointer;">

                <div class="product-image w-[100px] sm:w-[180px] md:w-full md:col-span-1 shrink-0 overflow-hidden relative">
                    <img src="{{ get_product_image($product->thumbnail_img,'300') }}" alt="{{ $product->name }}" title="{{ $product->name }}"
                        class="absolute left-0 top-0 h-full w-full object-cover object-center">

                    @include('frontend.partials.wishlist-icon', [
                    'product' => $product->id,
                    'stock' => $firstStock->id,
                    'page' => 'product-list' ?? null,
                    ])
                </div>

                <div class="flex flex-col flex-1 md:contents">

                    <div class="md:col-span-2 p-4 md:p-[30px]">
                        <h4 class="text-white text-[15px] sm:text-[18px] md:text-[20px] mb-1 md:mb-[20px] leading-tight font-medium line-clamp-2">
                            {{ $product->name }}
                        </h4>

                        @php
                        $productSpecifications = \App\Models\ProductSpecification::where('product_id', $product->id)
                        ->with(['specification', 'specificationItem'])
                        ->orderBy('sort_order')
                        ->get();
                        @endphp

                        @if (!empty($productSpecifications))
                        <div class="specifications hidden sm:block">
                            <ul>
                                @foreach ($productSpecifications as $specification)
                                @php
                                // $loop->index starts at 0.
                                // Hidden if index >= 2 on tablets (sm),
                                // and index >= 3 on desktops (md).
                                $itemClass = "";
                                if($loop->index >= 2) $itemClass .= " md:flex hidden"; // Hide 3rd+ on Tablet
                                if($loop->index >= 3) $itemClass = " hidden"; // Hide 4th+ everywhere
                                @endphp

                                <li class="flex flex-row py-2 md:py-[15px] border-b border-[#282B34] justify-between items-center {{ $itemClass }}">
                                    <div class="title flex flex-row gap-2 md:gap-[20px] items-center">
                                        <img src="{{ asset('assets/images/processor-icon.svg') }}" class="w-3 h-3 md:w-[20px] md:h-[20px]">
                                        <h5 class="text-[11px] md:text-[14px] text-[#636671] uppercase">
                                            {{ $specification->specification->main_title }}
                                        </h5>
                                    </div>
                                    <div class="value">
                                        <h6 class="text-[11px] md:text-[14px] text-[#C4C4C4] text-right">
                                            {{ $specification->specificationItem->title ?? '' }}
                                        </h6>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div class="md:col-span-1 p-4 md:pt-[30px] md:px-[30px] flex flex-col justify-between items-start md:items-end">
                        <div class="price flex flex-row md:flex-col items-center md:items-end gap-3 w-full justify-between md:justify-start">
                            <h5 class="price flex flex-row text-[#2A7CFF] text-[18px] md:text-[28px] m-0 font-bold items-center gap-1">
                                <img src="{{ asset('assets/images/aed.svg') }}" class="w-4 h-4 md:w-[22px] md:h-[22px]" alt="AED">
                                {{ $firstStock->offer_price ?? $firstStock->price }}
                            </h5>
                            @if (filled($firstStock->offer_tag))
                            <span class="text-[#898989] font-medium line-through text-[14px] md:text-[18px]">
                                {{ $firstStock->price }}
                            </span>
                            @endif
                        </div>

                        <div class="button-group mt-3 md:mt-[15px] w-full hidden md:block">
                            <a href="#" class="w-full inline-block text-center text-white uppercase text-[12px] font-medium px-[20px] py-[12px] rounded-[12px] border border-[#282B34] transition-all hover:bg-white hover:text-black">
                                view details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
    <!--//product list-->

</section>