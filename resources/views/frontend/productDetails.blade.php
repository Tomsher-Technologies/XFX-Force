@extends('frontend.layouts.app')

@section('title', 'Shop - Single')
@section('content')

<!--breadcrumb-->
<section class="px-[16px] md:px-[140px] pt-[80px] md:pt-[150px] pb-[0px] relative">
    <nav class="flex text-gray-400 py-[15px] md:py-[30px] border-t-1 border-[#ffffff30]" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap">
            <li class="inline-flex items-center">
                <a href="index.html" class="inline-flex items-center text-sm font-medium hover:text-[#3E81FF] transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Home
                </a>
            </li>

            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="shop.html" class="ml-1 text-sm font-medium hover:text-[#3E81FF] md:ml-2 transition-colors">Shop</a>
                </div>
            </li>

            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="shop-category.html" class="ml-1 text-sm font-medium hover:text-[#3E81FF] md:ml-2 transition-colors">Category Name</a>
                </div>
            </li>

            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-white md:ml-2">{{$product->name}}</span>
                </div>
            </li>
        </ol>
    </nav>
</section>
<!--//breadcrumb-->

<!--product single intro-->
<section class="bg-[#0F161B] px-[16px] md:px-[140px] py-[50px] md:pt-[0px] md:mb-[100px]">
    <div class="flex flex-col md:grid md:grid-cols-11 gap-[30px] md:gap-[100px]">
        <div class="col-span-5 overflow-hidden h-[50vh] md:h-[70vh] w-full">
            <div class="swiper singleprdswiper relative overflow-hidden h-full w-full">
                <div class="swiper-wrapper">
                    @php $hasVariantImage = false; @endphp
                    @foreach($product->stocks as $stock)
                    @if($stock->image)
                    @php $hasVariantImage = true; @endphp
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        <a href="{{ Storage::url($stock->image) }}" class="glightbox">
                            <img src="{{ Storage::url($stock->image) }}" alt="" title="" class="w-full h-full object-cover object-center">
                        </a>
                    </div>
                    @endif
                    @endforeach

                    @if(!$hasVariantImage && $product->thumbnail_img)
                    <div class="swiper-slide" data-swiper-autoplay="8000">
                        <a href="{{ Storage::url($product->thumbnail_img) }}" class="glightbox">
                            <img src="{{ Storage::url($product->thumbnail_img) }}" alt="" title="" class="w-full h-full object-cover object-center">
                        </a>
                    </div>
                    @endif

                </div>
                <div class="swiper-button-next !absolute right-[0%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
                <div class="swiper-button-prev !absolute left-[0%] !flex !items-center !justify-center !w-[50px] !h-[50px] !z-10 !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
            </div>
        </div>
        <div class="product-info flex flex-col gap-[20px] md:gap-[50px] md:col-span-6 w-full">
            @php
            $productSpecifications = \App\Models\ProductSpecification::where(
                'product_id',
                $product->id
            )->with(['specification','specificationItem'])->get();

            $specValues = $productSpecifications
                ->map(function ($ps) {
                    if ($ps->specification && $ps->specificationItem) {
                        return $ps->specification->main_title . ' ' . $ps->specificationItem->title;
                    }
                })
                ->filter()
                ->implode(', ');
            @endphp

            <h1 class="text-white text-[20px] md:text-[30px] leading-[30px] md:leading-[45px] text-center md:text-left tracking-[0px]">{{ $product->name }} @if($specValues)
                : {{ $specValues }}
                @endif</h1>
            <div class="text-[15px] md:text-[18px] text-justify [text-align-last:center] md:[text-align-last:left] md:text-left text-[#ffffff50] leading-[30px] md:leading-[35px]">{{ $product->description }}</div>
            <div class="flex flex-col md:flex-row gap-[30px] border-y-1 border-[#ffffff30] py-[30px] w-full justify-between flex-end items-center md:items-end">
                <div>
                    @php
                    // Get the first stock for this product
                        $firstStock = $product->stocks->first();
                    @endphp
                    <label class="text-[15px] text-white mb-[15px] block text-center md:text-left">Price</label>
                    <div class="price w-full flex flex-row items-end gap-[15px]">
                        <h5 class="price flex flex-row text-[#2A7CFF] text-left text-[25px] m-[0] font-bold align-center items-center gap-[10px] leading-[35px]">
                            <img src="{{ asset('assets/images/aed.svg') }}" class="w-[22px] h-[22px]" alt="AED" title="Symbol of AED">{{ number_format($firstStock->offer_price, 2) }}
                        </h5>
                        <span class="text-[#898989] font-medium line-through text-[20px]">{{ $firstStock->price }} </span>
                    </div>
                </div>
                <div class="button-group flex flex-col md:grid grid-cols-2 gap-[15px] h-fit w-full md:w-fit">
                    <button class="w-full flex flex-row justify-center align-center items-center text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] bg-[#2A7CFF] border border-[#282B34] transition-all duration-600 text-white hover:bg-[#2A7CFF] hover:text-white cursor-pointer"><img src="{{ asset('assets/images/cart.svg') }}" alt="" title="" class="mr-[15px]">Add to cart</button>
                    <a href="#" class="w-full text-center bg-white md:bg-transparent text-black md:text-white uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 hover:bg-white hover:text-black">Add to Wishlist</a>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between">
                <a href="#" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b-1 md:border-hidden border-[#ffffff30]">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/make-your-order.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Make your Order</h4>
                        <span class="text-[15px] text-[#ffffff50] underline decoration-wavy underline-offset-8">Ways to receive</span>
                    </div>
                </a>
                <a href="#" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b-1 md:border-hidden border-[#ffffff30]">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/configurator.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Configurator</h4>
                        <span class="text-[15px] text-[#ffffff50]">Create your dream PC</span>
                    </div>
                </a>
                <a href="#" class="flex flex-row gap-[15px] items-center py-[20px] md:py-[0px] border-b-1 md:border-hidden border-[#ffffff30]">
                    <div class="h-[50px] w-[50px] rounded-full border border-[#ffffff30] p-[15px]"><img src="{{ asset('assets/images/need-help.svg')}}" alt="" title=""></div>
                    <div class="flex flex-col">
                        <h4 class="text-white text-[18px] mb-[0px]">Need help?</h4>
                        <span class="text-[15px] text-[#ffffff50]">Ask for advice</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<!--//product single intro-->

<!--specification-->
<div class="bg-[#0F161B] text-gray-300"  x-data="{ activeTab: 'overview' }" >

    <nav class="sticky top-[79px] md:top-[148px] z-50 w-full border-b border-gray-800 bg-[#0F151D] backdrop-blur-md">
        <div class="max-w-6xl mx-auto flex overflow-x-auto no-scrollbar whitespace-nowrap px-4 justify-start md:justify-center">
            <a href="javascript:void(0)" @click="activeTab='overview'" :class="activeTab === 'overview' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Overview</a>
            <a href="javascript:void(0)" @click="activeTab='specs'" :class="activeTab === 'specs' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Specifications</a>
            <a href="javascript:void(0)" @click="activeTab='equipment'" :class="activeTab === 'equipment' ? 'active': ''" class="cursor-pointer spy-link px-[30px] py-[20px] uppercase text-[13px] tracking-[1px] font-medium border-b-2 border-transparent transition-all hover:text-white">Equipments</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-10">

        <section x-show="activeTab === 'overview'" x-transition class="tab-panel" id="overview" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px] md:py-[100px]">
            <h2 class="text-[18px] md:text-[20px] text-left uppercase font-bold text-white pb-[20px] border-b-2 border-[#2A7CFF]">Overview</h2>
            <div class="mt-[30px]">
                <div class="text-[15px] md:text-[18px] text-justify leading-[30px] text-[#ffffff50]">{{ $product->description }}</div>
            </div>
        </section>

        <section x-show="activeTab === 'specs'" x-transition class="tab-panel" id="specs" class="content-section scroll-mt-[130px] md:scroll-mt-[200px] py-[50px]">
            <h2 class="text-[18px] md:text-[20px] text-left uppercase font-bold text-white pb-[20px] border-b-2 border-[#2A7CFF]">Specifications</h2>
            <div class="mt-[30px]">
                <div class="specifications">
                    @php
                        $specifications = $productSpecifications
                            ->map(function ($ps) {
                                if ($ps->specification && $ps->specificationItem) {
                                    return [
                                        'title' => $ps->specification->main_title,
                                        'value' => $ps->specificationItem->title,
                                    ];
                                }
                            })
                            ->filter()
                            ->values();
                    @endphp
                    <ul class="flex flex-col gap-[5px]">
                        @foreach ($specifications as $specification)
                            <li class="bg-[#282B3450] flex flex-row px-[15px] rounded-[5px] py-[15px] justify-between gap-[15px] md:gap-[0px]">
                                <div class="title flex flex-row gap-[20px] w-full">
                                    <img src="{{ asset('assets/images/processor-icon.svg') }}" alt="" title="" class="w-[20px] h-[20px]">
                                    <h5 class="text-[14px] md:text-[15px] text-[#636671] uppercase">{{ $specification['title'] }}</h5>
                                </div>
                                <div class="value w-full">
                                    <h6 class="text-[14px] md:text-[15px] text-[#C4C4C4] text-left">{{ $specification['value'] }}</h6>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    </main>
</div>
<!--//specification-->

<!--related products-->
<section class="bg-[#0F161B] px-[16px] md:px-[140px] py-[50px] md:py-[100px] relative">
    <div class="section-title mb-[30px] md:mb-[50px] relative flex flex-col md:flex-row items-center md:items-end justify-between">
        <h3 class="w-full text-[30px] md:text-[50px] text-white capitalize font-bold text-center uppercase text-center md:text-left leading-[40px] md:leading-[50px] m-[0] mb-[30px] md:mb-[0px]">Graphic Cards</h3>
    </div>
    <div class="relative group">
        <div class="swiper productswiper relative overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach($graphicCardProducts as $product)
                @php
                    // Get the first stock for this product
                    $firstStock = $product->stocks->first();
                @endphp
                <div class="swiper-slide" data-swiper-autoplay="8000">
                    <a href="#"
                        class="product-card relative border-hidden rounded-[20px] overflow-hidden bg-[#1E2225] flex flex-col items-start justify-start transition-all duration-600">
                        <div class="product-img h-[230px] w-full relative z-[1] bg-[#0B0F13] bg-gradient-to-t from-[#0B0F13] to-[#1E2225]">
                            <img src="{{ Storage::url($product->thumbnail_img) }}" class="absolute object-cover object-center w-full h-full"
                                alt="Upcoming Product 1" title="Upcoming Product 1">
                            <badge
                                class="absolute top-[20px] left-[20px] bg-[#2A7CFF] text-white text-[12px] font-medium px-[15px] py-[5px] rounded-full capitalize">
                                new arrival</badge>
                        </div>
                        <div class="product-content p-[20px] flex flex-col gap-[20px] z-[1]">
                            <h4 class="text-white text-[18px] leading-[25px] font-medium line-clamp-2">{{ $product->name }}</h4>
                            <h5
                                class="price flex flex-row text-[#2A7CFF] text-[18px] m-[0] font-bold align-center items-center gap-[10px]">
                                <img src="{{ asset('assets/images/aed.svg') }}" class="w-[15px] h-[15px]" alt="AED"
                                    title="Symbol of AED">{{ $firstStock->offer_price ?? $firstStock->price }} 
                                @if($firstStock->offer_price) <span
                                    class="text-[#898989] font-medium line-through">{{ $firstStock->price }}</span>
                                @endif
                            </h5>
                            <button
                                class="w-full text-center text-black uppercase text-[14px] font-medium px-[30px] py-[15px] rounded-[15px] border border-[#282B34] transition-all duration-600 text-white">Buy
                                now</button>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-pagination !relative flex flex-start mt-[50px] hidden md:block"></div>
        <div class="controls relative md:!absolute right-[0px] m-auto mt-[30px] md:mt-[0px] md:top-[-80px] flex items-center gap-[20px] justify-center md:justify-end">
            <div class="swiper-button-prev !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
            <div class="swiper-button-next !relative !flex !items-center !justify-center !w-[50px] !h-[50px] !cursor-pointer !rounded-full !bg-white/10 !backdrop-blur-[100px] !bg-center !bg-no-repeat !bg-[length:15%] !transition-all !duration-300 !hover:bg-white/20 !mt-[0px]"></div>
        </div>
    </div>
</section>
<!--//related products-->
@endsection

<script>
    // $(function() {
    //     $('.view-btn').on('click', function() {
    //         // 1. Add active class and background to the one you clicked
    //         $(this).addClass('active bg-[#282B34]');

    //         // 2. Remove those same classes from all other buttons in the same parent
    //         $(this).siblings().removeClass('active bg-[#282B34]');
    //     });
    // });
    /*document.addEventListener('DOMContentLoaded', () => {
        const minInput = document.getElementById('range-min');
        const maxInput = document.getElementById('range-max');
        const minLabel = document.getElementById('min-price');
        const maxLabel = document.getElementById('max-price');
        const progress = document.getElementById('slider-progress');

        const priceGap = 500; // Minimum gap between handles

        function updateSlider() {
            let minVal = parseInt(minInput.value);
            let maxVal = parseInt(maxInput.value);

            // Logic to prevent handles from crossing
            if (maxVal - minVal < priceGap) {
                if (this.id === 'range-min') {
                    minInput.value = maxVal - priceGap;
                } else {
                    maxInput.value = minVal + priceGap;
                }
            } else {
                minLabel.textContent = minInput.value;
                maxLabel.textContent = maxInput.value;

                // Calculate percentage for the blue progress bar
                const minPercent = (minInput.value / minInput.max) * 100;
                const maxPercent = 100 - (maxInput.value / maxInput.max) * 100;

                progress.style.left = minPercent + "%";
                progress.style.right = maxPercent + "%";
            }
        }

        [minInput, maxInput].forEach(input => {
            input.addEventListener('input', updateSlider);
        });

        // Run once on load to set initial state
        updateSlider();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('brand-search');
        const brandItems = document.querySelectorAll('.brand-item');

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();

            brandItems.forEach(item => {
                const brandName = item.getAttribute('data-name').toLowerCase();

                // Check if search query matches the brand name
                if (brandName.includes(query)) {
                    item.style.display = 'flex'; // Show match
                    item.classList.add('animate-fade-in'); // Optional: add a quick fade effect
                } else {
                    item.style.display = 'none'; // Hide non-match
                }
            });
        });

        // Handle selection (Active State)
        brandItems.forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('border-[#3E81FF]');
                this.classList.toggle('bg-[#282B34]');
                const img = this.querySelector('img');
                img.classList.toggle('grayscale-0');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('category-search');
        const brandItems = document.querySelectorAll('.category-item');

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();

            brandItems.forEach(item => {
                const brandName = item.getAttribute('data-name').toLowerCase();

                // Check if search query matches the brand name
                if (brandName.includes(query)) {
                    item.style.display = 'flex'; // Show match
                    item.classList.add('animate-fade-in'); // Optional: add a quick fade effect
                } else {
                    item.style.display = 'none'; // Hide non-match
                }
            });
        });
    });
    
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('.content-section');
        const navLinks = document.querySelectorAll('.spy-link');

        const observerOptions = {
            root: null,
            // -96px accounts for the 4rem/64px nav height + some breathing room
            rootMargin: '-96px 0px -70% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // We only care about sections entering the "active zone"
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');

                    // Remove active class from all links
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                    });

                    // Add active class to the specific link
                    const activeLink = document.querySelector(`.spy-link[href="#${id}"]`);
                    if (activeLink) {
                        activeLink.classList.add('active');
                    }
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));
    });*/
</script>