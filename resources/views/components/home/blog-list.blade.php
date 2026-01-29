<div class="py-12 bg-white">
    <div class="container mx-auto px-6 lg:px-12">
        <!-- Section Title -->

        <h2 class="text-3xl md:text-4xl font-normal text-center">
            
            @php
                $text = $page->getTranslation('heading9',$lang)    ; // Get this from DB
                $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-primary font-semibold">$1</span>', $text);
            @endphp

            {!! $formattedText !!}
        </h2>

        <p class="text-gray-600 mt-2 text-center">
            {{ $page->getTranslation('content5',$lang) }}
        </p>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-10">
            @foreach($blogs as $blog)
                <div class="rounded-2xl overflow-hidden bg-white shadow-lg border border-gray-200 group">
                    <!-- Blog Image with Hover Zoom -->
                    <div class="overflow-hidden rounded-t-2xl">
                        <img src="{{ uploaded_asset(($blog->image)) }}"  alt="{{ $blog->name }}"  class="w-full h-64 object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('images/blogs/default-blog.jpg') }}';">
                    </div>

                    <!-- Blog Content -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $blog->name }}</h3>
                        <p class="text-gray-600 text-sm mt-2">{{ Str::limit(strip_tags($blog->description), 120, '...') }}</p>
                        
                        <!-- Read More Button -->
                        <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}" 
                           class="mt-4 inline-block px-5 py-2 border border-black text-black rounded-full text-sm transition-all hover:bg-black hover:text-white">
                            Read Blog →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Blogs Button -->
        <div class="mt-8 flex justify-center">
            <a href="{{ route('blog') }}" class="px-6 py-3 bg-black text-white rounded-full text-sm hover:bg-gray-800 transition-all">
                View All Blogs
            </a>
        </div>
    </div>
</div>
