@if($pcBuilderItems->isNotEmpty())
<div class="flex flex-col w-full bg-black/30 backdrop-blur-[60px] p-[20px] xl:p-[30px] rounded-[20px]">
    <h2 class="text-center md:text-left text-white text-[20px] uppercase mb-[0px] pb-[20px] border-b-1 border-[#282B34]">Build your PC</h2>
    <div class="flex flex-col divide-y-1 divide-[#282B34] mb-[20px]">
        @foreach($pcBuilderItems as $builderId => $items)
            @foreach($items as $item)
            <div class="cart-box">
                <div class="product-cart-item flex flex-col md:col-col md:grid md:grid-cols-12 gap-[20px] xl:gap-[50px] w-full bg-black/30 backdrop-blur-[60px] p-[20px] md:p-[30px] rounded-[20px]">
                    <div class="col-span-7 flex flex-col md:flex-row gap-[20px] md:gap-[30px]">
                        <div class="product-img h-[150px] md:h-auto w-full md:w-[200px] relative z-[1] bg-[#1E2225] rounded-[10px] overflow-hidden items-center justify-center flex">
                            @php
                                $image = asset('assets/img/placeholder.jpg');

                                if (!empty($item->product_stock?->image)) {
                                    $image = Storage::url($item->product_stock->image);
                                } elseif (!empty($item->product?->thumbnail_img)) {
                                    $image = Storage::url($item->product->thumbnail_img);
                                }
                            @endphp
                            <img src="{{ $image }}" class="absolute object-fit object-center w-auto md:w-full h-[150px] md:h-auto" alt="Upcoming Product 1" title="Upcoming Product 1">
                        </div>
                        <div class="flex flex-col gap-[10px]">
                            <h4 class="text-white text-[13px] leading-[20px] font-medium line-clamp-2 md:line-clamp-1 xl:line-clamp-2 text-center md:text-left">{{ $item->product_stock->stock_title ?? $item->product->name ?? '' }}</h4>
                            <!-- <a onclick="toggleSpecModal()" class="text-[#2A7CFF] text-[14px] cursor-pointer text-center md:text-left">Specifications</a>
                            <a onclick="toggleWarrantyModal()" class="text-center md:text-left w-full py-[5px] text-[12px] text-white flex flex-row items-center justify-center md:justify-start gap-[10px] leading-[0px] cursor-pointer"><i class="h-[20px] w-[20px] rounded-full block bg-[#262B35] flex flex-center items-center text-center justify-center text-[14px] tracking-[1px] cursor-pointer">+</i>Choose Your Warranty Plan</a> -->

                            @php
                                $productSpecifications = $item->product->specifications;
                            @endphp
                            @if($productSpecifications->isNotEmpty())
                                <a onclick="toggleSpecModal(this)" class="text-[#2A7CFF] text-[14px] cursor-pointer text-center md:text-left">Specifications</a>
                            @endif

                            <!-- Warranty popup link -->
                            @php
                                $productWarranties = $item->product->warranties;
                            @endphp
                            @if($productWarranties->isNotEmpty())
                                <a onclick="toggleWarrantyModal(this)" class="text-center xl:text-left w-full py-[5px] text-[12px] text-white flex flex-row items-center justify-center md:justify-start gap-[10px] leading-[0px] cursor-pointer"><i class="h-[20px] w-[20px] rounded-full block bg-[#262B35] flex flex-center items-center text-center justify-center text-[14px] tracking-[1px] cursor-pointer">+</i>Choose Your Warranty Plan</a>
                            @endif
                            
                        </div>
                    </div>
                    <div class="col-span-5">
                        <div class="flex flex-col md:flex-row gap-[15px] align-center items-center h-full">
                            <!--counter-->
                            <div class="product-item flex items-center gap-4 bg-[#0B0F13] border border-gray-800 rounded-xl p-1 shadow-inner w-full" data-cart-id="{{ $item->id }}" data-product-id="{{ $item->product_id }}" data-variant-id="{{ $item->product_stock_id }}">
                                
                                <button onclick="updateMultiQty(this, -1)" class="decrement-btn w-full h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all active:scale-90">
                                    <span class="icon-wrapper">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 minus-btn" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 12H4" /></svg>
                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 trash-btn" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </span>
                                </button>

                                <input type="number" value="{{ $item->quantity }}" readonly class="qty-input w-[50px] h-full text-center bg-[#282B34] text-white font-medium focus:outline-none text-[15px] p-[7px] rounded-lg">
                                <button onclick="updateMultiQty(this, 1)" class="w-full h-10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#2A7CFF] rounded-lg transition-all active:scale-90">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                            <!--//counter-->

                            <!--price-->
                                <span class="text-white items-center justify-center text-right gap-[10px] text-[15px] w-full item-wise-total">
                                    <div class="flex flex-row items-center  gap-[10px]">
                                        <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="white"></path>
                                        </svg>
                                        <span class="cart_offer_price">{{ format_price($item->offer_price * $item->quantity) }} </span> 
                                    </div>
                                    @if (filled($item->offer_tag))
                                    <div class="flex flex-row items-center gap-[10px] line-through text-[#898989]">
                                        <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.32445 0.0149494C1.33045 0.023919 1.36345 0.0642824 1.39495 0.103151C1.62444 0.37523 1.79693 0.817732 1.88993 1.37535C1.95143 1.74161 1.95443 1.85672 1.95443 3.25299V4.55359H1.32745C0.754472 4.55359 0.688474 4.5506 0.575979 4.52817C0.398985 4.4908 0.215992 4.39064 0.0929965 4.26207C-0.00449983 4.15892 -0.00149994 4.15294 0.00449983 4.46539C0.0119996 4.72401 0.0149994 4.75242 0.052498 4.89294C0.112496 5.11569 0.194993 5.28162 0.319488 5.42962C0.488982 5.63294 0.661475 5.74655 0.907466 5.82279C0.959964 5.83774 1.07096 5.84372 1.46395 5.84671L1.95443 5.85419V6.5015V7.1503L1.26295 7.14581L0.568479 7.14133L0.448483 7.09349C0.305989 7.03668 0.241491 6.99483 0.101996 6.87075L0 6.77955L0.00599978 7.06509C0.0134995 7.32969 0.0149994 7.35959 0.052498 7.49414C0.182993 7.96953 0.497981 8.30888 0.913466 8.4195C1.01696 8.44791 1.05746 8.4494 1.49094 8.45538L1.95443 8.46136V9.80083C1.95443 10.6096 1.94993 11.2061 1.94243 11.3077C1.93493 11.4004 1.91093 11.5738 1.88993 11.6949C1.79243 12.2525 1.61694 12.6726 1.36495 12.9447L1.31395 13H3.85036C5.3668 13 6.50076 12.994 6.66725 12.9865C6.95974 12.9716 7.61222 12.9073 7.75921 12.8759C7.80571 12.867 7.89271 12.8535 7.9497 12.8445C8.0712 12.8266 8.27219 12.7847 8.56168 12.7115C8.96966 12.6098 9.34165 12.4828 9.70614 12.3213C9.82013 12.2705 10.1471 12.1045 10.2341 12.0522C10.2806 12.0253 10.3361 11.9924 10.3571 11.9819C10.4156 11.9506 10.5131 11.8878 10.6556 11.7861C10.7261 11.7353 10.7966 11.6859 10.8116 11.6755C10.8746 11.6336 11.0921 11.4527 11.1911 11.3615C11.5676 11.0162 11.8826 10.632 12.127 10.2209C12.1615 10.1611 12.2065 10.0864 12.226 10.055C12.2755 9.97125 12.4795 9.55267 12.499 9.48988C12.508 9.46148 12.52 9.43158 12.526 9.4256C12.565 9.37477 12.79 8.66916 12.817 8.51518C12.826 8.46585 12.8305 8.45837 12.868 8.4509C12.892 8.44641 13.2415 8.44641 13.645 8.4494C14.452 8.45538 14.452 8.45538 14.6305 8.5376C14.731 8.58395 14.7609 8.60488 14.8719 8.70504C15.0174 8.8351 15.0039 8.85603 14.9949 8.53013C14.9889 8.33878 14.9814 8.22068 14.9679 8.17284C14.9169 7.98896 14.9049 7.95009 14.8599 7.85741C14.713 7.53749 14.467 7.30876 14.152 7.19963L14.029 7.15478L13.528 7.1488L13.0285 7.14133L13.0345 6.96642C13.0405 6.7362 13.0405 6.28024 13.033 6.04554L13.027 5.85718L13.696 5.85419C14.269 5.8512 14.3755 5.85419 14.4385 5.87063C14.6275 5.92295 14.7549 5.99471 14.9109 6.13673L14.9979 6.21746V5.99621C14.9979 5.7331 14.9844 5.61649 14.9304 5.44308C14.8239 5.09177 14.614 4.83015 14.314 4.6687C14.119 4.56405 14.107 4.56106 13.4365 4.55658C13.0435 4.55359 12.838 4.54761 12.8275 4.53864C12.8185 4.52967 12.811 4.51472 12.811 4.50276C12.811 4.4908 12.7885 4.39662 12.7585 4.29496C12.4075 3.05865 11.7521 2.07647 10.7936 1.34844C10.6631 1.24828 10.3436 1.03749 10.2146 0.965731C10.1651 0.937328 10.1111 0.907429 10.0976 0.898459C10.0346 0.864075 9.67314 0.687673 9.58314 0.650299C9.52914 0.62638 9.45865 0.596481 9.42715 0.584522C8.89767 0.355796 8.0097 0.139029 7.33173 0.0717571C7.22073 0.0612926 7.07374 0.0448482 7.00624 0.0388684C6.70025 0.00448482 6.27577 0 3.86536 0C1.82843 0 1.31695 0.00448482 1.32445 0.0149494ZM6.28477 0.662259C6.79175 0.692157 7.10373 0.731026 7.46822 0.819227C8.58118 1.08234 9.36415 1.63845 9.93263 2.56831C9.98513 2.65501 10.2071 3.10649 10.2401 3.19768C10.3976 3.62075 10.4741 3.8719 10.5416 4.20377C10.5581 4.2845 10.5806 4.39213 10.5911 4.44296C10.6016 4.4923 10.6061 4.53864 10.6016 4.54312C10.5941 4.5491 9.08816 4.55209 7.25223 4.5506L3.91485 4.54761L3.91035 2.62661C3.90885 1.57118 3.91035 0.693652 3.91485 0.677208L3.92085 0.648804H4.98731C5.57229 0.648804 6.15727 0.654784 6.28477 0.662259ZM10.7471 5.89903C10.7576 5.96332 10.7576 7.05462 10.7471 7.10844L10.7381 7.1488L7.32573 7.14581L3.91485 7.14133L3.91185 6.50747C3.90885 6.15915 3.91185 5.86914 3.91485 5.86316C3.91935 5.85568 5.3728 5.8512 7.33023 5.8512H10.7381L10.7471 5.89903ZM10.5941 8.46585C10.6016 8.48827 10.5656 8.67215 10.4921 8.97114C10.4081 9.3075 10.2941 9.64685 10.1786 9.898C10.1216 10.0266 9.97913 10.3046 9.94463 10.3569C9.92813 10.3809 9.88013 10.4571 9.83813 10.5244C9.56814 10.946 9.18266 11.3302 8.74317 11.6142C8.58268 11.7158 8.25269 11.8893 8.1642 11.9162C8.1462 11.9207 8.1267 11.9296 8.1192 11.9356C8.1087 11.9446 7.9722 11.9954 7.81321 12.0522C7.52072 12.1554 6.96424 12.2675 6.51726 12.3138C6.22777 12.3422 6.18127 12.3437 5.06681 12.3437H3.91335V10.4048V8.46435L7.22673 8.45837C9.04916 8.45538 10.5506 8.4509 10.5626 8.44791C10.5761 8.44641 10.5896 8.45538 10.5941 8.46585Z" fill="white"></path>
                                        </svg>
                                        <small class="cart_price"> {{ format_price($item->price * $item->quantity) }} </small>
                                    </div>
                                    @endif
                                </span>
                            <!--//price-->
                        </div>
                    </div>
                    @php
                        $productSpecifications = \App\Models\ProductSpecification::where(
                        'product_id',
                        $item->product->id
                        )->with(['specification','specificationItem'])
                        ->orderBy('sort_order')
                        ->get();

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
                        $productWarrantis = $item->product->warranties;
                    @endphp

                    @include('frontend.partials.warrantyandspecification',['specifications'=>$specifications, 'productWarrantis'=>$productWarrantis])
                </div>
            </div>
            @endforeach
    </div>
    <a href="#" onclick="resetAndRedirect({{ $builderId }})" class="flex w-full cursor-pointer items-center justify-center gap-3 px-6 py-4 rounded-xl border border-[#2E363E] text-gray-400 font-medium uppercase text-[13px] hover:bg-[#252C33] hover:text-white transition-all duration-300 group" fdprocessedid="x22w2b" >
        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.06225 2.46955C7.44287 2.55401 8.58718 2.86026 9.38256 3.64338C10.1665 4.41446 10.4778 5.51582 10.5671 5.90217C9.98386 6.54506 9.27096 7.29742 8.40698 8.16096C4.82477 11.7404 3.39283 12.6795 3.33374 12.7176C3.25164 12.7705 3.15773 12.8017 3.0603 12.8094L0.636471 12.9979C0.621313 12.9996 0.606133 12.9998 0.591549 12.9998C0.434944 12.9998 0.283705 12.9368 0.172604 12.825C0.0503889 12.7019 -0.0112895 12.5318 0.00170553 12.3582L0.190182 9.93732C0.197865 9.8386 0.230155 9.74226 0.283932 9.659C0.325253 9.5957 1.26836 8.16301 4.84546 4.58869C5.69033 3.7444 6.42826 3.04514 7.06225 2.46955ZM10.3953 0.00763642C10.5012 0.0244213 11.4443 0.191172 12.1277 0.864082C12.8078 1.55404 12.9752 2.49891 12.9919 2.60529C13.0144 2.74743 12.9856 2.89353 12.9099 3.01545C12.8842 3.05747 12.5011 3.66234 11.4851 4.85725C11.2593 4.23886 10.8658 3.44182 10.2078 2.79377C9.55179 2.1479 8.74277 1.76131 8.11694 1.53889C9.33083 0.505953 9.94742 0.117453 9.99194 0.0896677C10.1119 0.0147327 10.2552 -0.0155577 10.3953 0.00763642Z" fill="white"></path>
        </svg>
        <span>Edit your list</span>
    </a>
@endforeach
</div>
@endif