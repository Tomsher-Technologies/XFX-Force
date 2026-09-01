<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Homepage --}}
    <url>
        <loc>https://www.pcgarage.me/</loc>
    </url>

    {{-- Products Listing --}}
    <url>
        <loc>https://www.pcgarage.me/products</loc>
    </url>

    {{-- Brands Listing --}}
    <url>
        <loc>https://www.pcgarage.me/brands</loc>
    </url>

    {{-- Product Pages --}}
    @foreach ($products as $product)
        @foreach ($product->stocks as $stock)
            <url>
                <loc>{{ route('product.details', [
                    'slug' => $product->slug,
                    'sku' => $stock->sku
                ]) }}</loc>

                @if ($product->updated_at)
                    <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
                @endif
            </url>
        @endforeach
    @endforeach

</urlset>
