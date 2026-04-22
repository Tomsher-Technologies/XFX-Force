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

                <div class="product-image w-[100px] sm:w-[180px] md:w-full md:col-span-1 shrink-0 overflow-hidden relative bg-white">
                    <img src="{{ get_product_image($product->thumbnail_img,'300') }}" alt="{{ $product->name }}" title="{{ $product->name }}"
                        class="absolute left-0 top-0 h-full w-full object-contain object-center">

                    @include('frontend.partials.wishlist-icon', [
                    'product' => $product->id,
                    'stock' => $firstStock ? $firstStock->id : null,
                    'page' => 'product-list' ?? null,
                    ])
                </div>

                <div class="flex flex-col flex-1 md:contents">

                    <div class="md:col-span-2 p-4 md:p-[30px]">
                        <h4 class="text-white text-[15px] sm:text-[18px] md:text-[20px] mb-1 md:mb-[10px] leading-tight font-medium line-clamp-2">
                            {{ $product->name }}
                        </h4>

                        <a href="#" class="flex items-center gap-[8px] mb-3 md:mb-[20px]">
                            <div class="flex items-center gap-[2px]">
                                @for ($i = 0; $i < 4; $i++)
                                    <svg class="w-3 h-3 md:w-4 md:h-4 text-[#FFB800] fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                @endfor

                                <div class="relative">
                                    <svg class="w-3 h-3 md:w-4 md:h-4 text-gray-600 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <div class="absolute top-0 left-0 overflow-hidden w-[50%]">
                                        <svg class="w-3 h-3 md:w-4 md:h-4 text-[#FFB800] fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    </div>
                                </div>
                            </div>
                            <span class="text-white text-[12px] md:text-[14px] font-medium">4.5</span>
                            <span class="text-[#898989] text-[11px] md:text-[13px] font-medium">(120 reviews)</span>
                        </a>

                        @php
                        $productSpecifications = \App\Models\ProductSpecification::where('product_stock_id', $firstStock->id)
                        ->with(['specification', 'specificationItem'])
                        ->orderBy('sort_order')
                        ->get();
                        @endphp

                        @if (!empty($productSpecifications))
                        <div class="specifications hidden sm:block">
                            <ul>
                                @foreach ($productSpecifications as $specification)
                                @php
                                $itemClass = "";
                                if($loop->index >= 2) $itemClass .= " md:flex hidden"; 
                                if($loop->index >= 3) $itemClass = " hidden"; 
                                @endphp

                                <li class="flex flex-row py-2 md:py-[15px] border-b border-[#282B34] justify-between items-center {{ $itemClass }}">
                                    <div class="title flex flex-row gap-2 md:gap-[20px] items-center">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path class="icon" d="M12.4936 7.50636C12.5936 8.10091 12.7273 9.06545 12.7273 10C12.7273 10.9345 12.5945 11.8991 12.4936 12.4936C11.8991 12.5936 10.9345 12.7273 10 12.7273C9.06545 12.7273 8.10091 12.5945 7.50636 12.4936C7.40636 11.8991 7.27273 10.9345 7.27273 10C7.27273 9.06545 7.40545 8.10091 7.50636 7.50636C8.10091 7.40636 9.06545 7.27273 10 7.27273C10.9345 7.27273 11.8991 7.40545 12.4936 7.50636ZM20 11.8182C20 12.3209 19.5936 12.7273 19.0909 12.7273H18.04C17.9764 13.3845 17.8991 14.0027 17.82 14.5455H18.6364C19.1391 14.5455 19.5455 14.9518 19.5455 15.4545C19.5455 15.9573 19.1391 16.3636 18.6364 16.3636H17.5109C17.4682 16.5809 17.4391 16.7145 17.4336 16.74C17.3582 17.0873 17.0873 17.3582 16.74 17.4336C16.7145 17.4391 16.5809 17.4682 16.3636 17.5109V18.6364C16.3636 19.1391 15.9573 19.5455 15.4545 19.5455C14.9518 19.5455 14.5455 19.1391 14.5455 18.6364V17.82C14.0027 17.8991 13.3845 17.9764 12.7273 18.04V19.0909C12.7273 19.5936 12.3209 20 11.8182 20C11.3155 20 10.9091 19.5936 10.9091 19.0909V18.1627C10.6073 18.1736 10.3036 18.1818 10 18.1818C9.69636 18.1818 9.39273 18.1736 9.09091 18.1627V19.0909C9.09091 19.5936 8.68455 20 8.18182 20C7.67909 20 7.27273 19.5936 7.27273 19.0909V18.04C6.61545 17.9764 5.99727 17.8991 5.45455 17.82V18.6364C5.45455 19.1391 5.04818 19.5455 4.54545 19.5455C4.04273 19.5455 3.63636 19.1391 3.63636 18.6364V17.5109C3.41909 17.4682 3.28545 17.4391 3.26 17.4336C2.91273 17.3582 2.64182 17.0873 2.56636 16.74C2.56091 16.7145 2.53182 16.5809 2.48909 16.3636H1.36364C0.860909 16.3636 0.454545 15.9573 0.454545 15.4545C0.454545 14.9518 0.860909 14.5455 1.36364 14.5455H2.18C2.10091 14.0027 2.02364 13.3845 1.96 12.7273H0.909091C0.406364 12.7273 0 12.3209 0 11.8182C0 11.3155 0.406364 10.9091 0.909091 10.9091H1.83727C1.82636 10.6073 1.81818 10.3036 1.81818 10C1.81818 9.69636 1.82636 9.39273 1.83727 9.09091H0.909091C0.406364 9.09091 0 8.68455 0 8.18182C0 7.67909 0.406364 7.27273 0.909091 7.27273H1.96C2.02364 6.61545 2.10091 5.99727 2.18 5.45455H1.36364C0.860909 5.45455 0.454545 5.04818 0.454545 4.54545C0.454545 4.04273 0.860909 3.63636 1.36364 3.63636H2.48909C2.53182 3.41909 2.56091 3.28545 2.56636 3.26C2.64182 2.91273 2.91273 2.64182 3.26 2.56636C3.28545 2.56091 3.41909 2.53182 3.63636 2.48909V1.36364C3.63636 0.860909 4.04273 0.454545 4.54545 0.454545C5.04818 0.454545 5.45455 0.860909 5.45455 1.36364V2.18C5.99727 2.10091 6.61545 2.02364 7.27273 1.96V0.909091C7.27273 0.406364 7.67909 0 8.18182 0C8.68455 0 9.09091 0.406364 9.09091 0.909091V1.83727C9.39273 1.82636 9.69636 1.81818 10 1.81818C10.3036 1.81818 10.6073 1.82636 10.9091 1.83727V0.909091C10.9091 0.406364 11.3155 0 11.8182 0C12.3209 0 12.7273 0.406364 12.7273 0.909091V1.96C13.3845 2.02364 14.0027 2.10091 14.5455 2.18V1.36364C14.5455 0.860909 14.9518 0.454545 15.4545 0.454545C15.9573 0.454545 16.3636 0.860909 16.3636 1.36364V2.48909C16.5809 2.53182 16.7145 2.56091 16.74 2.56636C17.0873 2.64182 17.3582 2.91273 17.4336 3.26C17.4391 3.28545 17.4682 3.41909 17.5109 3.63636H18.6364C19.1391 3.63636 19.5455 4.04273 19.5455 4.54545C19.5455 5.04818 19.1391 5.45455 18.6364 5.45455H17.82C17.8991 5.99727 17.9764 6.61545 18.04 7.27273H19.0909C19.5936 7.27273 20 7.67909 20 8.18182C20 8.68455 19.5936 9.09091 19.0909 9.09091H18.1627C18.1736 9.39273 18.1818 9.69636 18.1818 10C18.1818 10.3036 18.1736 10.6073 18.1627 10.9091H19.0909C19.5936 10.9091 20 11.3155 20 11.8182ZM14.5455 10C14.5455 8.30546 14.1764 6.60455 14.16 6.53273C14.0836 6.18636 13.8136 5.91636 13.4673 5.84C13.3955 5.82364 11.6945 5.45455 10 5.45455C8.30546 5.45455 6.60455 5.82364 6.53273 5.84C6.18636 5.91636 5.91636 6.18636 5.84 6.53273C5.82364 6.60455 5.45455 8.30546 5.45455 10C5.45455 11.6945 5.82364 13.3955 5.84 13.4673C5.91636 13.8136 6.18636 14.0836 6.53273 14.16C6.60455 14.1764 8.30546 14.5455 10 14.5455C11.6945 14.5455 13.3955 14.1764 13.4673 14.16C13.8136 14.0836 14.0836 13.8136 14.16 13.4673C14.1764 13.3955 14.5455 11.6945 14.5455 10Z" fill="#9F9FA9"></path>
                                        </svg>
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