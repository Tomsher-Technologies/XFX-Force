@extends('frontend.layouts.app')

@section('title', 'Address')

@section('content')

    <section
        class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[100px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
        <div class="text-white">
            <div
                class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t border-[#252b31] pt-0 xl:pt-[80px]">

                @include('frontend.layouts.sidebar')

                <main class="flex-grow xl:pb-0">
                    <div>
                        <div
                            class="flex flex-col md:flex-row justify-between items-end mb-6 pb-[30px] gap-4 border-b border-[#252B31] text-center md:text-left">
                            <div class="w-full">
                                <h2 class="text-[20px] font-medium mb-1 text-white uppercase">Addresses</h2>
                                <p class="text-gray-500">Manage your shipping locations.</p>
                            </div>
                            <div class="w-full flex justify-center md:justify-end">
                                <button onclick="toggleAddressModal('.address-modal', true)"
                                    class="w-fit flex items-center justify-center gap-2 bg-[#2A7CFF] hover:bg-[#1a66e5] text-white px-6 py-3 rounded-xl font-medium text-[13px] uppercase cursor-pointer transition-all active:scale-95 shadow-[0_0_20px_rgba(42,124,255,0.3)]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add New Address
                                </button>
                            </div>
                        </div>
                       
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[30px]">

                            @foreach ($addresses as $address)
                                <div class="bg-[#1C2228] border border-[#282B34] rounded-2xl overflow-hidden group hover:border-[#2A7CFF]/30 transition-all duration-300" id="address_div_{{ $address->id }}">
                                    <div class="p-6 flex flex-col gap-4">
                                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 w-full">
                                        
                                            <div class="flex items-center gap-3">
                                                <h4 class="text-white font-medium text-lg">{{ ucfirst($address->type) ?? $address->type }}</h4>

                                                @if ($address->set_default == 1)
                                                    <div class="flex w-fit items-center px-3 py-[5px] h-fit bg-blue-500/10 border border-blue-500/20 rounded-full">
                                                        <span class="text-blue-500 text-[11px] font-semibold uppercase leading-none">Default</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Right side: Edit + Delete buttons -->
                                            <div class="flex flex-row gap-2 md:gap-3 mt-2 md:mt-0">
                                                <button onclick="editAddress({{ $address->id }})" class="flex items-center gap-2 bg-[#252C33] hover:bg-[#2A7CFF] text-white text-[13px] uppercase font-medium px-4 py-2 rounded-xl transition-all">
                                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M7.06225 2.46955C7.44287 2.55401 8.58718 2.86026 9.38256 3.64338C10.1665 4.41446 10.4778 5.51582 10.5671 5.90217C9.98386 6.54506 9.27096 7.29742 8.40698 8.16096C4.82477 11.7404 3.39283 12.6795 3.33374 12.7176C3.25164 12.7705 3.15773 12.8017 3.0603 12.8094L0.636471 12.9979C0.621313 12.9996 0.606133 12.9998 0.591549 12.9998C0.434944 12.9998 0.283705 12.9368 0.172604 12.825C0.0503889 12.7019 -0.0112895 12.5318 0.00170553 12.3582L0.190182 9.93732C0.197865 9.8386 0.230155 9.74226 0.283932 9.659C0.325253 9.5957 1.26836 8.16301 4.84546 4.58869C5.69033 3.7444 6.42826 3.04514 7.06225 2.46955ZM10.3953 0.00763642C10.5012 0.0244213 11.4443 0.191172 12.1277 0.864082C12.8078 1.55404 12.9752 2.49891 12.9919 2.60529C13.0144 2.74743 12.9856 2.89353 12.9099 3.01545C12.8842 3.05747 12.5011 3.66234 11.4851 4.85725C11.2593 4.23886 10.8658 3.44182 10.2078 2.79377C9.55179 2.1479 8.74277 1.76131 8.11694 1.53889C9.33083 0.505953 9.94742 0.117453 9.99194 0.0896677C10.1119 0.0147327 10.2552 -0.0155577 10.3953 0.00763642Z"
                                                            fill="white"></path>
                                                    </svg>
                                                    Edit
                                                </button>

                                                <button onclick="deleteAddress({{ $address->id }})" class="flex items-center gap-2 bg-red-500/5 border border-red-500/20 text-red-500 px-4 py-2 rounded-xl hover:bg-red-500 hover:text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Bottom row: Full address info -->
                                        <div class=" text-gray-500 text-sm">
                                            {{ $address->name }}<br>
                                            {!! nl2br($address->address) !!}<br>
                                            {{ $address->city }}, {{ $address->state_name }},<br> 
                                            {{ $address->country_name }} {{ $address->postal_code ? '- '. $address->postal_code : '' }}
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </main>

                <!--address modal-->
                <div onclick="toggleAddressModal('.address-modal', false)"
                    class="address-modal fixed inset-0 z-[9999] hidden opacity-0 bg-black/80 backdrop-blur-md flex justify-center items-start p-4 overflow-y-auto transition-all duration-300 ease-in-out">
                    <div onclick="event.stopPropagation()" id="addr-modal-container"
                        class="bg-[#0B0F13] border border-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl relative mt-4 mb-4 md:mt-10 md:mb-10 transform scale-95 opacity-0 transition-all duration-300 ease-out">

                        <button onclick="toggleAddressModal('.address-modal', false)" type="button"
                            class="absolute top-4 right-4 text-gray-500 hover:text-white z-50 p-2 cursor-pointer transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 pointer-events-none" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <form id="addressForm" action="{{ route('save-address') }}" method="POST">
                            @csrf

                            <input type="hidden" name="address_id" value="0">

                            <div class="p-6 md:p-8">

                                <h4 class="text-xl font-medium text-white mb-6 uppercase tracking-wider">
                                    Add New Address
                                </h4>

                                <div class="relative w-full h-[220px] bg-[#161B22] rounded-xl mb-6 border border-gray-800 overflow-hidden">

                                    <div id="map" class="w-full h-full"></div>

                                    <button type="button" onclick="getCurrentLocation()" class="absolute cursor-pointer bottom-3 right-3 bg-[#2A7CFF] p-3 rounded-full shadow-lg hover:bg-blue-600 transition-all active:scale-95 z-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>

                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    {{-- Full Name --}}
                                    <div>
                                        <input type="text" name="name" id="name" placeholder="Full Name *"
                                            value=""
                                            class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                                        <p class="text-red-400 text-xs mt-1 error-name"></p>
                                    </div>

                                    {{-- Phone --}}
                                    <div>
                                        <input type="text" name="phone" id="phone"
                                            placeholder="Phone Number *"
                                            value=""
                                            class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                                        <p class="text-red-400 text-xs mt-1 error-phone"></p>
                                    </div>

                                    {{-- Address --}}
                                    <div class="md:col-span-2">
                                        <input type="text" name="address" id="address"
                                            placeholder="Address (Building, Street, Area etc.) *"
                                            value=""
                                            class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                                        <p class="text-red-400 text-xs mt-1 error-address"></p>
                                    </div>

                                    {{-- City --}}
                                    <div>
                                        <input type="text" name="city" id="city" placeholder="City *"
                                            value=""
                                            class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">

                                        <p class="text-red-400 text-xs mt-1 error-city"></p>
                                    </div>

                                    {{-- Emirate / State --}}
                                    <div class="relative w-full">

                                        <input type="text"  name="state" id="state"  placeholder="Emirate / State *"
                                            value=""
                                            class="bg-[#161B22] border border-gray-900/50 p-4 rounded-xl text-gray-400 w-full">
                                        <p class="text-red-400 text-xs mt-1 error-state"></p>
                                    </div>

                                    {{-- ZIP --}}
                                    <div>
                                        <input type="text" name="zipcode" id="zipcode" placeholder="ZIP Code"
                                            value=""
                                            class="bg-[#161B22] border border-gray-800 p-4 rounded-xl text-white w-full outline-none focus:border-[#2A7CFF]">
                                    </div>

                                    {{-- Country --}}
                                    <div>
                                        <input type="text" name="country" id="country" placeholder="Country *"
                                            value=""
                                            class="bg-[#161B22] border border-gray-900/50 p-4 rounded-xl text-gray-400 w-full">
                                        <p class="text-red-400 text-xs mt-1 error-country"></p>
                                    </div>

                                </div>

                                {{-- Address Type --}}
                                <div class="mt-6 flex gap-4">

                                    <label class="flex items-center gap-2 text-gray-300">
                                        <input type="radio" name="address_type" value="home" checked>
                                        Home
                                    </label>

                                    <label class="flex items-center gap-2 text-gray-300">
                                        <input type="radio" name="address_type" value="work">
                                        Work
                                    </label>

                                    <label class="flex items-center gap-2 text-gray-300">
                                        <input type="radio" name="address_type" value="other">
                                        Other
                                    </label>

                                </div>

                                {{-- Default Address --}}
                                <div class="mt-6 p-4 bg-[#161B22] border border-gray-800 rounded-xl flex items-center justify-between">

                                    <div class="flex flex-col">
                                        <span class="text-white text-sm font-medium">Set as Default Address</span>
                                        <span class="text-gray-500 text-xs">Automatically select this address for bookings and orders.</span>
                                    </div>

                                    <label class="relative inline-flex items-center cursor-pointer">

                                        <input type="checkbox" name="default" value="1" class="sr-only peer">

                                        <div class="w-11 h-6 bg-gray-800 rounded-full peer-checked:bg-[#2A7CFF] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full">
                                        </div>

                                    </label>

                                </div>

                                {{-- Buttons --}}
                                <div class="flex flex-col md:flex-row gap-3 mt-8">

                                    <button type="submit"
                                        class="flex-1 bg-[#2A7CFF] text-white font-medium uppercase py-4 rounded-xl text-[14px] hover:bg-[#1447e6]">
                                        Save Address
                                    </button>

                                    <button onclick="toggleAddressModal('.address-modal', false)" type="button"
                                    class="flex-1 bg-transparent border border-gray-800 text-gray-500 font-medium py-4 rounded-xl uppercase text-[14px] cursor-pointer hover:bg-gray-800 hover:text-white transition-all">Discard</button>

                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!--//address modal-->
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap&loading=async" async defer></script>
    <script>
        let map;
        let marker;

        function initMap() {
            let defaultLocation = { lat: 25.2048, lng: 55.2708 }; // Dubai
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: defaultLocation,
            });

            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true
            });

            updateLatLng(defaultLocation);

            // click map
            map.addListener("click", function(event){
                marker.setPosition(event.latLng);
                updateLatLng(event.latLng);
            });

            // drag marker
            marker.addListener("dragend", function(event){
                updateLatLng(event.latLng);
            });
        }

        function updateLatLng(location){
            let lat = typeof location.lat === "function" ? location.lat() : location.lat;
            let lng = typeof location.lng === "function" ? location.lng() : location.lng;

            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            console.log("Latitude:", lat);
            console.log("Longitude:", lng);
        }

        function getCurrentLocation(){
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(function(position){
                    let pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                    marker.setPosition(pos);
                    document.getElementById("latitude").value = pos.lat;
                    document.getElementById("longitude").value = pos.lng;
                });
            }
        }

        function toggleAddressModal(selector, isOpen) {
            const modal = document.querySelector(selector);
            const container = modal.querySelector('#addr-modal-container');

            if (isOpen) {
                // 1. Show the display first
                modal.classList.remove('hidden');

                // 2. Trigger animations in the next frame
                requestAnimationFrame(() => {
                    modal.classList.add('opacity-100');
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                });

                document.body.style.overflow = 'hidden'; // Prevent background scroll
                
            } else {
                // 1. Reverse animations
                modal.classList.remove('opacity-100');
                container.classList.remove('scale-100', 'opacity-100');
                container.classList.add('scale-95', 'opacity-0');

                // 2. Wait for transition to finish before hiding
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = ''; // Restore scroll
                }, 300); // Must match your duration-300
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            $.ajaxSetup({
                headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#addressForm").submit(function(e){
                e.preventDefault();
                $(".text-red-400").html("");
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    processData:false,
                    contentType:false,
                    success:function(response){
                        if(response.status === 'error'){                      
                            $.each(response.errors,function(key,value){
                                $(".error-"+key).text(value[0]);
                            });
                        }else{
                            alert("Address saved successfully");
                            location.reload();
                        }
                    },
                    error:function(xhr){
                        if(xhr.responseJSON && xhr.responseJSON.errors){
                            let errors = xhr.responseJSON.errors;
                            $.each(errors,function(key,value){
                                $(".error-"+key).text(value[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
