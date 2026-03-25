
<div>
    <div class="product-card w-full relative border-hidden rounded-[20px] overflow-hidden bg-[#1E2225] flex flex-col items-start justify-start transition-all duration-600">
        
        @php
            // Get product object
            $product = \App\Models\Product::with('stocks')->find($prodData['product_id']);

            // Get the stock by the given stock_id
            $stock = $product
                ? $product->stocks->where('id', $prodData['stock_id'])->first()
                : null;
        @endphp

        @include('frontend.partials.wishlist-icon', [
            'product' => $prodData['product_id'],
            'stock' => $prodData['stock_id'],
            'page' => $prodData['page'] ?? null,
        ])

        <a href="{{ $stock ? route('product.details', ['slug' => $product->slug, 'sku' => $stock->sku]) : '#' }}"
            class="product-img h-[230px] w-full relative z-[1] bg-[#0B0F13] bg-gradient-to-t from-[#0B0F13] to-[#1E2225]">
            <img src="{{ get_product_image($prodData['thumbnail_img'],'300') }}"
                class="absolute object-cover object-center w-full h-full" alt="Upcoming Product 1"
                title="Upcoming Product 1">
            @if (filled($prodData['offer_tag']))
                <badge
                    class="absolute top-[20px] left-[20px] bg-[#077F09] text-white text-[12px] font-medium px-[15px] py-[5px] rounded-full capitalize">
                    {{ $prodData['offer_tag'] }}</badge>
            @endif
        </a>
        <div class="product-content p-[20px] flex flex-col gap-[20px] z-[1]">
            <h4 class="text-white text-[18px] leading-[25px] font-medium line-clamp-2">
                {{ $prodData['name'] ?? '' }}</h4>
            <h5 class="price flex flex-row text-[#2A7CFF] text-[18px] m-[0] font-bold align-center items-center gap-[5px]">
                {{-- <img src="{{ asset('assets/images/aed.svg') }}" class="w-[15px] h-[15px]" alt="AED"
                    title="Symbol of AED"> --}}


                <svg width="15px" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 13"><path d="M1.3 0v.1q.5.4.6 1.3v3.2H.7L0 4.2v.7l.3.5.6.4H2v1.3H.4l-.3-.2-.1-.1v.7q.3.7 1 1h1v1.3l-.1 1.9q-.2.8-.5 1.2H8a7 7 0 0 0 2.2-.9h.2l.3-.2.1-.1a5 5 0 0 0 2-3.2h1.8l.3.2q.1.3.1-.2v-.3l-.1-.3q-.3-.6-.7-.7H13V5.8h1.4q.3 0 .5.2l.1.1v-.8q-.3-.5-.7-.7l-.9-.1h-.6v-.3A6 6 0 0 0 10.2 1l-.1-.1-.7-.3q-1-.4-2-.5H1.2m5 .7 1.2.1a4 4 0 0 1 2.7 2.4l.3 1v.2l.1.1H3.9v-4h2.4m4.4 5.2V7H4V6h6.8m-.1 2.6-.1.5-.7 1.5a4 4 0 0 1-2 1.5l-1.3.3H4v-4h6.7" fill="#2a7cff"/></svg>
                
                <span style="margin-top:1px; ">{{ $prodData['offer_price'] ?? $prodData['price'] }}</span>
                @if ($prodData['offer_price'] != $prodData['price'])
                    <span class="text-[#898989] font-medium line-through" style="margin-top:1px; ">{{ $prodData['price'] }}</span>
                @endif
            </h5>
            <a href="{{ $stock ? route('product.details', ['slug' => $product->slug, 'sku' => $stock->sku]) : '#' }}"
                class="w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 text-white">Add to cart</a>
        </div>

    </div>
</div>
