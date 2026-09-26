<section class="px-[16px] md:px-[30px] lg:px-[50px] xl:px-[140px] py-[50px] xl:py-[80px]">
    @if ($blogs->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-[20px] md:gap-[50px]">
            @foreach ($blogs as $blog)
                <a href="{{ route('blogs.details', ['slug' => $blog->slug]) }}" class="blog-card border-b border-white/20 hover:border-white/50 w-full relative overflow-hidden flex flex-col items-start justify-start transition-all duration-[600ms]">
                    {{-- Blog Image --}}
                    <div class="blog-img h-[220px] md:h-[240px] aspect-auto w-full relative z-[1] bg-[#ffffff] block">
                        <img src="{{ uploaded_asset($blog->image ?? '') }}" class="absolute object-cover object-center w-full h-full" alt="{{ $blog->title ?? '' }}" title="{{ $blog->title ?? '' }}">
                    </div>

                    {{-- Blog Content --}}
                    <div class="blog-content w-full py-[20px] flex flex-col gap-[12px]">
                        {{-- Date --}}
                        @if ($blog->created_at)
                            <span class="text-white/50 text-[12px] md:text-[13px] font-normal uppercase">
                                {{ $blog->created_at->format('d M Y') }}
                            </span>
                        @endif

                        {{-- Title --}}
                        <h4 class="text-white text-[18px] md:text-[21px] leading-[25px] md:leading-[28px] font-medium line-clamp-2">
                            {{ $blog->name ?? '' }}
                        </h4>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="w-full text-center py-[60px]">
            <p class="text-gray-400 text-[16px]">
                No blogs available.
            </p>
        </div>
    @endif
</section>
