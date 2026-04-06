<div class="specification-block row mb-2">
    <div class="col-md-4">
        <label>Specification</label>
        @php
        $specifications = \App\Models\Specification::where('status',1)->orderBy('display_title','asc')->get();
        @endphp
        <select class="form-control form-control-sm aiz-selectpicker specification-select" name="{{ $namePrefix }}[specifications][]" data-live-search="true">
            <option value="">{{ trans('messages.select').' '.trans('messages.specification') }}</option>
            @foreach($specifications as $spec)
            <option value="{{ $spec->id }}">{{ $spec->main_title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label>Items</label>
        <select class="form-control form-control-sm aiz-selectpicker specification-item" name="{{ $namePrefix }}[specification_items][]" data-live-search="true">
            <option value="">{{ trans('messages.select').' '.trans('messages.items') }}</option>
        </select>
    </div>
    <div class="col-md-2">
        <label>Sort Order</label>
        <input type="number" class="form-control form-control-sm" name="{{ $namePrefix }}[sort_orders][]" value="0">
    </div>
    <div class="col-md-2">
        <label class="d-block">&nbsp;</label>
        <button type="button" class="remove-spec border-0 bg-transparent">
            <i class="las la-trash text-danger"></i>
        </button>
    </div>
</div>
<div class="text-right mt-2">
    <button type="button" class="btn btn-success btn-xs add-spec" data-name-prefix="{{ $namePrefix }}">
        Add More
    </button>
</div>