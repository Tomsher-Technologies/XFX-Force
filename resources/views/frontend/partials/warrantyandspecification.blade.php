<!--spec modal-->

@php
$productSpecifications = \App\Models\ProductSpecification::where(
'product_stock_id',
$item->product_stock->id
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
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="icon" d="M12.4936 7.50636C12.5936 8.10091 12.7273 9.06545 12.7273 10C12.7273 10.9345 12.5945 11.8991 12.4936 12.4936C11.8991 12.5936 10.9345 12.7273 10 12.7273C9.06545 12.7273 8.10091 12.5945 7.50636 12.4936C7.40636 11.8991 7.27273 10.9345 7.27273 10C7.27273 9.06545 7.40545 8.10091 7.50636 7.50636C8.10091 7.40636 9.06545 7.27273 10 7.27273C10.9345 7.27273 11.8991 7.40545 12.4936 7.50636ZM20 11.8182C20 12.3209 19.5936 12.7273 19.0909 12.7273H18.04C17.9764 13.3845 17.8991 14.0027 17.82 14.5455H18.6364C19.1391 14.5455 19.5455 14.9518 19.5455 15.4545C19.5455 15.9573 19.1391 16.3636 18.6364 16.3636H17.5109C17.4682 16.5809 17.4391 16.7145 17.4336 16.74C17.3582 17.0873 17.0873 17.3582 16.74 17.4336C16.7145 17.4391 16.5809 17.4682 16.3636 17.5109V18.6364C16.3636 19.1391 15.9573 19.5455 15.4545 19.5455C14.9518 19.5455 14.5455 19.1391 14.5455 18.6364V17.82C14.0027 17.8991 13.3845 17.9764 12.7273 18.04V19.0909C12.7273 19.5936 12.3209 20 11.8182 20C11.3155 20 10.9091 19.5936 10.9091 19.0909V18.1627C10.6073 18.1736 10.3036 18.1818 10 18.1818C9.69636 18.1818 9.39273 18.1736 9.09091 18.1627V19.0909C9.09091 19.5936 8.68455 20 8.18182 20C7.67909 20 7.27273 19.5936 7.27273 19.0909V18.04C6.61545 17.9764 5.99727 17.8991 5.45455 17.82V18.6364C5.45455 19.1391 5.04818 19.5455 4.54545 19.5455C4.04273 19.5455 3.63636 19.1391 3.63636 18.6364V17.5109C3.41909 17.4682 3.28545 17.4391 3.26 17.4336C2.91273 17.3582 2.64182 17.0873 2.56636 16.74C2.56091 16.7145 2.53182 16.5809 2.48909 16.3636H1.36364C0.860909 16.3636 0.454545 15.9573 0.454545 15.4545C0.454545 14.9518 0.860909 14.5455 1.36364 14.5455H2.18C2.10091 14.0027 2.02364 13.3845 1.96 12.7273H0.909091C0.406364 12.7273 0 12.3209 0 11.8182C0 11.3155 0.406364 10.9091 0.909091 10.9091H1.83727C1.82636 10.6073 1.81818 10.3036 1.81818 10C1.81818 9.69636 1.82636 9.39273 1.83727 9.09091H0.909091C0.406364 9.09091 0 8.68455 0 8.18182C0 7.67909 0.406364 7.27273 0.909091 7.27273H1.96C2.02364 6.61545 2.10091 5.99727 2.18 5.45455H1.36364C0.860909 5.45455 0.454545 5.04818 0.454545 4.54545C0.454545 4.04273 0.860909 3.63636 1.36364 3.63636H2.48909C2.53182 3.41909 2.56091 3.28545 2.56636 3.26C2.64182 2.91273 2.91273 2.64182 3.26 2.56636C3.28545 2.56091 3.41909 2.53182 3.63636 2.48909V1.36364C3.63636 0.860909 4.04273 0.454545 4.54545 0.454545C5.04818 0.454545 5.45455 0.860909 5.45455 1.36364V2.18C5.99727 2.10091 6.61545 2.02364 7.27273 1.96V0.909091C7.27273 0.406364 7.67909 0 8.18182 0C8.68455 0 9.09091 0.406364 9.09091 0.909091V1.83727C9.39273 1.82636 9.69636 1.81818 10 1.81818C10.3036 1.81818 10.6073 1.82636 10.9091 1.83727V0.909091C10.9091 0.406364 11.3155 0 11.8182 0C12.3209 0 12.7273 0.406364 12.7273 0.909091V1.96C13.3845 2.02364 14.0027 2.10091 14.5455 2.18V1.36364C14.5455 0.860909 14.9518 0.454545 15.4545 0.454545C15.9573 0.454545 16.3636 0.860909 16.3636 1.36364V2.48909C16.5809 2.53182 16.7145 2.56091 16.74 2.56636C17.0873 2.64182 17.3582 2.91273 17.4336 3.26C17.4391 3.28545 17.4682 3.41909 17.5109 3.63636H18.6364C19.1391 3.63636 19.5455 4.04273 19.5455 4.54545C19.5455 5.04818 19.1391 5.45455 18.6364 5.45455H17.82C17.8991 5.99727 17.9764 6.61545 18.04 7.27273H19.0909C19.5936 7.27273 20 7.67909 20 8.18182C20 8.68455 19.5936 9.09091 19.0909 9.09091H18.1627C18.1736 9.39273 18.1818 9.69636 18.1818 10C18.1818 10.3036 18.1736 10.6073 18.1627 10.9091H19.0909C19.5936 10.9091 20 11.3155 20 11.8182ZM14.5455 10C14.5455 8.30546 14.1764 6.60455 14.16 6.53273C14.0836 6.18636 13.8136 5.91636 13.4673 5.84C13.3955 5.82364 11.6945 5.45455 10 5.45455C8.30546 5.45455 6.60455 5.82364 6.53273 5.84C6.18636 5.91636 5.91636 6.18636 5.84 6.53273C5.82364 6.60455 5.45455 8.30546 5.45455 10C5.45455 11.6945 5.82364 13.3955 5.84 13.4673C5.91636 13.8136 6.18636 14.0836 6.53273 14.16C6.60455 14.1764 8.30546 14.5455 10 14.5455C11.6945 14.5455 13.3955 14.1764 13.4673 14.16C13.8136 14.0836 14.0836 13.8136 14.16 13.4673C14.1764 13.3955 14.5455 11.6945 14.5455 10Z" fill="#9F9FA9"></path>
                        </svg>
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
                                }} cursor-pointer transition-all duration-200" data-cartid="{{$item->id}}" data-warrantyid="{{$warranty->id}}" data-warrantyname="{{$warranty->title}}">
                                <div class="flex flex-col gap-1">
                                    <span class="text-white font-bold text-[16px]">{{$warranty->title}}</span>
                                    <div class="text-[13px] text-[#636671]">{!! $warranty->description !!}</div>
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