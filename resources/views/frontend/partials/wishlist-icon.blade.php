@php
    $isWishlisted = auth('frontend')->check() 
        ? isWishlisted($product, $stock)
        : false;
@endphp

<button data-product-id="{{ $product }}" data-page="{{ $page }}" data-stock-id="{{ $stock }}" class="wishlist-toggle absolute top-[10px] right-[10px] z-[10] w-[35px] h-[35px] md:w-[40px] md:h-[40px] bg-black/20 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center {{ $isWishlisted ? 'text-red-500' : 'text-white bg-black/20' }} transition-all duration-300 hover:bg-transparent hover:text-red-500 cursor-pointer group/heart">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-active/heart:scale-125" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"  viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
</button>