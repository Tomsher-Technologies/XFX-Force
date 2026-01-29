<section id="services-section" class="pt-10 pb-10 bg-gray-50">
    <div class="container-lg mx-auto px-6 lg:px-12">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl font-normal text-center relative z-10">
            @php
                $text = $page->getTranslation('heading3',$lang)    ; // Get this from DB
                $formattedText = preg_replace('/\[(.*?)\]/', '<span class="text-primary font-semibold">$1</span>', $text);
            @endphp

            {!! $formattedText !!}
        </h2>
        <p class="text-gray-600 mt-4 text-center relative z-10">
            {{ $page->getTranslation('heading4',$lang) }}
        </p>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
            @forelse($services as $service)
                <!-- Service Card -->
                <div class="group p-6 bg-white rounded-2xl shadow-md transition duration-300 border border-gray-200 hover:shadow-lg hover:border-primary relative overflow-hidden">
                    <a href="{{ route('services.show', ['slug' => $service->slug]) }}">
                        <!-- Service Image -->
                        <div class="relative w-full h-48">
                            <img src="{{ uploaded_asset($service['image']) }}" 
                                alt="{{ $service->getTranslation('name', $lang) }}" 
                                class="w-full h-full object-cover rounded-lg transition duration-300 group-hover:scale-105">
                        </div>
                        
                        <!-- Service Info -->
                        <div class="mt-4 relative z-10">
                            <h3 class="text-lg font-semibold text-black group-hover:text-primary transition duration-300">
                                {{ $service->getTranslation('name', $lang) }}
                            </h3>
                        </div>
                        
                        <p class="mt-3 text-sm text-gray-700 relative z-10">
                            {{ Str::limit($service->getTranslation('short_description', $lang), 200, '...') }}
                        </p>
                    </a>
                    
                    <!-- Service Price & Buttons -->
                    <div class="flex justify-between items-center mt-5 relative z-10">
                       
                        @if ($service->price != 0 && $service->price != NULL)
                            <span class="text-[#41B6E8] py-2 px-4 rounded-full font-semibold text-sm shadow-sm bg-gray-100">
                                {{ env('DEFAULT_CURRENCY') }} {{ number_format($service->price, 2) }}
                            </span>
                        @endif

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('services.show', ['slug' => $service->slug]) }}"
                               class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-600 rounded-full transition duration-300 hover:bg-gray-800 hover:text-white">
                                Learn More
                            </a>
                        </div>
                    </div>
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#E0F7FA] opacity-0 group-hover:opacity-70 transition duration-300 z-0 rounded-xl"></div>
                </div>
            @empty
                <!-- No Services Available Message -->
                <div class="col-span-full text-center text-gray-600 text-lg mt-6">
                    🚨 No services available at the moment.
                </div>
            @endforelse
        </div>

        <!-- View All Services Button -->
        <div class="mt-12 text-center">
            <a href="{{ route('services.index') }}" 
               class="px-6 py-3 bg-[#41B6E8] text-white font-semibold rounded-full hover:bg-[#3086B1] transition duration-300">
                View All Services
            </a>
        </div>

    </div>
</section>

