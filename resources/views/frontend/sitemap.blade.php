<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @php
        $baseUrl = rtrim(config('app.url'), '/');
    @endphp

    {{-- Homepage --}}
    <url>
        <loc>{{ $baseUrl }}/</loc>
    </url>

    {{-- About --}}
    <url>
        <loc>{{ $baseUrl }}/about</loc>
    </url>

    {{-- Products Listing --}}
    <url>
        <loc>{{ $baseUrl }}/products</loc>
    </url>

    {{-- Brands Listing --}}
    <url>
        <loc>{{ $baseUrl }}/brands</loc>
    </url>

    {{-- Build Your PC --}}
    <url>
        <loc>{{ $baseUrl }}/buildyourpc</loc>
    </url>

    {{-- Contact --}}
    <url>
        <loc>{{ $baseUrl }}/contact</loc>
    </url>

    {{-- Terms --}}
    <url>
        <loc>{{ $baseUrl }}/terms</loc>
    </url>

    {{-- Privacy Policy --}}
    <url>
        <loc>{{ $baseUrl }}/privacy-policy</loc>
    </url>

    {{-- Return Policy --}}
    <url>
        <loc>{{ $baseUrl }}/return-policy</loc>
    </url>

    {{-- Cookie Policy --}}
    <url>
        <loc>{{ $baseUrl }}/cookie-policy</loc>
    </url>

    {{-- Shipping Policy --}}
    <url>
        <loc>{{ $baseUrl }}/shipping-policy</loc>
    </url>

    {{-- Warranty Policy --}}
    <url>
        <loc>{{ $baseUrl }}/warranty-policy</loc>
    </url>


    {{-- Category Pages --}}
    @foreach ($categories as $category)
        @foreach ($category->category_translations as $translation)
            @if ($translation->slug)
                <url>
                    <loc>{{ $baseUrl }}/shop/category/{{ $translation->slug }}</loc>

                    @if ($category->updated_at)
                        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
                    @endif
                </url>
            @endif
        @endforeach
    @endforeach

    {{-- Brand Pages --}}
    @foreach ($brands as $brand)
        @if ($brand->slug)
            {{-- Brand Shop Page --}}
            <url>
                <loc>{{ $baseUrl }}/shop/brand/{{ $brand->slug }}</loc>

                @if ($brand->updated_at)
                    <lastmod>{{ $brand->updated_at->toAtomString() }}</lastmod>
                @endif
            </url>

            {{-- Brand Detail Page --}}
            <url>
                <loc>{{ $baseUrl }}/brands/{{ $brand->slug }}</loc>

                @if ($brand->updated_at)
                    <lastmod>{{ $brand->updated_at->toAtomString() }}</lastmod>
                @endif
            </url>
        @endif
    @endforeach


    {{-- Product Pages --}}
    @foreach ($products as $product)
        @foreach ($product->stocks as $stock)
            <url>
                <loc>{{ route('product.details', [
                    'slug' => $product->slug,
                    'sku' => $stock->sku,
                ]) }}</loc>

                @if ($product->updated_at)
                    <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
                @endif
            </url>
        @endforeach
    @endforeach

</urlset>
