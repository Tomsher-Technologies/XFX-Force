<!--spec modal-->

@php
$productSpecifications = \App\Models\ProductSpecification::where(
'product_id',
$item->product->id
)->with(['specification','specificationItem'])->get();

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
<div class="spec-modal-overlay fixed inset-0 z-[9999] hidden overflow-y-auto bg-black/80 backdrop-blur-md transition-all duration-300 opacity-0 px-4 py-10">
    <div class="spec-modal-container relative mx-auto bg-[#0B0F13] border border-gray-800 w-full max-w-4xl rounded-2xl shadow-2xl transform scale-100 transition-all duration-300 mt-[100px]">
        <div class="flex justify-end p-4">
            <button onclick="closeSpecModal(this.closest('.spec-modal-overlay'))"
                class="text-gray-500 hover:text-white text-xl p-2 cursor-pointer">✕</button>
        </div>
        <div class="px-8 pb-10 text-gray-300">
            <h2
                class="text-[18px] md:text-[20px] uppercase font-bold text-white pb-[20px] border-b-2 border-[#2A7CFF]">
                Specifications</h2>

            <ul class="flex flex-col gap-[5px] mt-[30px]">
                @if($specifications->isNotEmpty())
                @foreach ($specifications as $specification)
                <li
                    class="bg-[#282B3450] flex flex-row px-[15px] rounded-[5px] py-[15px] justify-between gap-[15px] md:gap-[0px]">
                    <div class="title flex flex-row gap-[20px] w-full">
                        <img src="src/images/processor-icon.svg" alt="" title="" class="w-[20px] h-[20px]">
                        <h5 class="text-[14px] md:text-[15px] text-[#636671] uppercase">{{ $specification['title'] }}</h5>
                    </div>
                    <div class="value w-full">
                        <h6 class="text-[14px] md:text-[15px] text-[#C4C4C4] text-left">{{ $specification['value'] }}</h6>
                    </div>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
<!--//spec modal-->

<!--warranty modal-->
@php
    $productWarrantis = $item->product->warranties;
@endphp
<div class="warranty-modal-overlay fixed inset-0 z-[9999] hidden overflow-y-auto bg-black/90 backdrop-blur-sm transition-all duration-300 opacity-0 px-4 py-10">
    <div class="warranty-modal-container relative mx-auto bg-[#0B0F13] border border-blue-900/30 w-full max-w-4xl rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 mt-[100px]">
        <div class="flex justify-end p-4">
            <button onclick="closeWarrantyModal(this.closest('.warranty-modal-overlay'))" class="text-gray-500 hover:text-white text-xl p-2 cursor-pointer">✕</button>
        </div>
        <div class="px-8 pb-10 text-gray-300">
            <h2 class="text-[18px] md:text-[20px] uppercase font-bold text-white pb-[20px] border-b-2 border-blue-500">Warranty Options</h2>
            <div class="mt-[30px] space-y-4">
                <div class="mt-[30px] space-y-4">
                    <div class="grid grid-cols-1 gap-4">

                        <div class="space-y-4">
                            @if($productWarrantis->isNotEmpty())
                            @foreach ($productWarrantis as $warranty)
                            
                            <label onclick="selectWarranty(this)" class="warranty-card relative flex items-center justify-between p-5 rounded-xl cursor-pointer transition-all duration-200 {{ $item->warranty_id == $warranty->id 
                                    ? 'border-2 border-[#2A7CFF] bg-[#161B22]' 
                                    : 'border border-gray-800 bg-[#282B3450]' 
                                }} cursor-pointer transition-all duration-200" data-cartid="{{$item->id}}" data-warrantyid="{{$warranty->id}}">
                                <div class="flex flex-col gap-1">
                                    <span class="text-white font-bold text-[16px]">{{$warranty->title}}</span>
                                    <p class="text-[13px] text-[#636671]">{{$warranty->description}}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-white font-bold text-[18px]">{{$warranty->price > 0 ? format_price($warranty->price): 'FREE' }}</span>
                                    <div class="check-icon text-[#2A7CFF] hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--//warranty modal-->