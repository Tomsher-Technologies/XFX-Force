@extends('frontend.layouts.app')

@section('title', $blog->title)

@section('content')

    <!-- Blog Details Header -->
    <section class="px-[16px] md:px-[30px] lg:px-[50px] xl:px-[140px] pt-[80px] xl:pt-[150px] pb-[30px]">

        <div class="section-title border-t border-[#ffffff30] pt-[30px] xl:pt-[50px]">

            <!-- Breadcrumb -->
            <nav class="flex text-gray-400 pb-[15px] md:pb-[30px]" aria-label="Breadcrumb">

                <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap">

                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center text-sm font-medium hover:text-[#3E81FF] transition-colors">

                            <svg class="w-4 h-4 mr-2"
                                 fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>

                            Home
                        </a>
                    </li>

                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600"
                                 fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>

                            <a href="{{ route('blogs') }}"
                               class="ml-1 text-sm font-medium hover:text-[#3E81FF] md:ml-2 transition-colors">
                                Blogs
                            </a>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600"
                                 fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707 10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0 1 1 0 0 1 0 1.414z"
                                      clip-rule="evenodd">
                                </path>
                            </svg>

                            <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2 line-clamp-1">
                                {{ $blog->name }}
                            </span>
                        </div>
                    </li>

                </ol>

            </nav>

        </div>

    </section>


    <!-- Blog Content -->

<article class="px-[16px] md:px-[30px] lg:px-[50px] xl:px-[140px] pb-[100px]">

    <div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-[40px] xl:gap-[150px]">

            <!-- Blog Content -->
            <div class="lg:col-span-8">




                <!-- Blog Image -->
                @if(!empty($blog->image))

                    <div class="w-full aspect-[16/8] rounded-[12px] overflow-hidden bg-[#0f161b] mb-[40px]">

                        <img
                            src="{{ uploaded_asset($blog->image) }}"
                            alt="{{ $blog->title }}"
                            title="{{ $blog->title }}"
                            class="w-full h-full object-cover object-center"
                        >

                    </div>

                @endif

                <!-- Date -->
                @if($blog->created_at)

                    <div class="mb-[12px]">

                        <span class="text-[#2A7CFF] text-[12px] md:text-[14px] font-medium uppercase tracking-[1px]">
                            {{ $blog->created_at->format('d M Y') }}
                        </span>

                    </div>

                @endif


                <!-- Title -->
                <h1 class="about-title text-[28px] md:text-[36px] xl:text-[48px] leading-[38px] md:leading-[48px] xl:leading-[60px] text-left text-[#ffffff80] mb-[30px]">
                    {{ $blog->name }}
                </h1>

                <!-- Blog Description -->
                @if(!empty($blog->description))

                    <div class="blog-content">
                        {!! $blog->description !!}
                    </div>

                @endif

            </div>


            <!-- Recent Blogs Sidebar -->
            <aside class="lg:col-span-4">

                <div class="lg:sticky lg:top-[120px]">

                    <!-- Sidebar Title -->
                    <div class="border-b border-[#ffffff30] pb-[20px] mb-[25px]">

                        <h2 class="text-[20px] uppercase leading-[35px] font-semibold text-[#ffffff]">
                            Recent Blogs
                        </h2>

                    </div>


                    <!-- Recent Blog List -->
                    <div class="space-y-[25px]">

                        @forelse($recentBlogs as $recentBlog)

                            <a
                                href="{{ route('blogs.details', ['slug' => $recentBlog->slug]) }}"
                                class="group flex gap-[15px] pb-[25px] border-b border-[#ffffff20]"
                            >

                                <!-- Thumbnail -->
                                <div class="w-[110px] h-[80px] flex-shrink-0 rounded-[8px] overflow-hidden bg-[#0f161b]">

                                    <img
                                        src="{{ uploaded_asset($recentBlog->image) }}"
                                        alt="{{ $recentBlog->name }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('images/blogs/default-blog.jpg') }}';"
                                    >

                                </div>


                                <!-- Blog Info -->
                                <div class="min-w-0">

                                    @if($recentBlog->created_at)

                                        <span class="block text-white/50 text-[11px] uppercase tracking-[0.5px] mb-[5px]">
                                            {{ $recentBlog->created_at->format('d M Y') }}
                                        </span>

                                    @endif

                                    <h3 class="text-[15px] xl:text-[17px] leading-[23px] text-[#ffffffcc] font-medium line-clamp-2 transition-colors group-hover:text-[#3E81FF]">
                                        {{ $recentBlog->name }}
                                    </h3>

                                </div>

                            </a>

                        @empty

                            <p class="text-[#ffffff80] text-[14px]">
                                No recent blogs found.
                            </p>

                        @endforelse

                    </div>

                </div>

            </aside>

        </div>

    </div>

</article>



@endsection


@section('style')

<style>

    /*-- Blog Content --*/

    .blog-content {
        width: 100%;
        word-break: break-word;
        color: #ffffff80;
        background-color: #0f161b;
        font-family: "Mona-Sans", sans-serif;
        font-size: 20px;
        line-height: 40px;
        text-align: left;
    }


    /*-- Imported Zoho / Writer Content --*/

    .blog-content .zw-paragraph,
    .blog-content .zw-paragraph *,
    .blog-content .zw-line-div,
    .blog-content .zw-line-content,
    .blog-content .zw-text-portion,
    .blog-content .EOP-readonly,
    .blog-content .EOP {
        color: #ffffff80 !important;
        font-family: "Mona-Sans", sans-serif !important;
        font-size: 20px !important;
        line-height: 40px !important;
        text-align: left !important;
        background-color: transparent !important;
        white-space: normal !important;
        height: auto !important;
    }


    /*-- Paragraphs --*/

    .blog-content p {
        margin: 0 0 24px;
    }


    /*-- Headings --*/

    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4,
    .blog-content h5,
    .blog-content h6 {
        color: #fff;
        font-family: "Mona-Sans", sans-serif;
        line-height: 1.3;
        margin-top: 35px;
        margin-bottom: 20px;
    }

    .blog-content h1 {
        font-size: 36px;
    }

    .blog-content h2 {
        font-size: 32px;
    }

    .blog-content h3 {
        font-size: 28px;
    }

    .blog-content h4 {
        font-size: 24px;
    }

    .blog-content h5 {
        font-size: 21px;
    }

    .blog-content h6 {
        font-size: 18px;
    }


    /*-- Lists --*/

    .blog-content ul,
    .blog-content ol {
        margin: 0 0 24px;
        padding-left: 30px;
    }

    .blog-content ul {
        list-style-type: disc;
    }

    .blog-content ol {
        list-style-type: decimal;
    }

    .blog-content li {
        margin-bottom: 8px;
    }


    /*-- Links --*/

    .blog-content a {
        color: #3E81FF;
        text-decoration: underline;
        text-underline-offset: 3px;
    }


    /*-- Blockquote --*/

    .blog-content blockquote {
        margin: 30px 0;
        padding: 20px 25px;
        border-left: 4px solid #3E81FF;
        background: rgba(255, 255, 255, 0.05);
        font-style: italic;
    }


    /*-- Images --*/

    .blog-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 30px auto;
        border-radius: 8px;
    }


    /*-- Tables --*/

    .blog-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 30px 0;
    }

    .blog-content table th,
    .blog-content table td {
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        padding: 10px 12px !important;
        vertical-align: top;
        color: #ffffff80;
    }

    .blog-content table th {
        color: #fff;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.05);
    }


    /*-- Horizontal Rule --*/

    .blog-content hr {
        border: 0;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        margin: 40px 0;
    }


    /*-- Code --*/

    .blog-content pre {
        padding: 20px;
        overflow-x: auto;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        margin: 30px 0;
    }


    /*-- Mobile --*/

    @media (max-width: 767px) {
        .blog-content {
            font-size: 16px;
            line-height: 30px;
        }

        .blog-content .zw-paragraph,
        .blog-content .zw-paragraph *,
        .blog-content .zw-line-div,
        .blog-content .zw-line-content,
        .blog-content .zw-text-portion,
        .blog-content .EOP-readonly,
        .blog-content .EOP {
            font-size: 16px !important;
            line-height: 30px !important;
        }

        .blog-content h1 {
            font-size: 28px;
        }

        .blog-content h2 {
            font-size: 25px;
        }

        .blog-content h3 {
            font-size: 22px;
        }

        .blog-content h4 {
            font-size: 20px;
        }

        .blog-content h5 {
            font-size: 18px;
        }

        .blog-content h6 {
            font-size: 16px;
        }
    }

</style>

@endsection

