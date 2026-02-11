@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="mb-0 h6">Edit Product</h5>
</div>
<div class="">
    <form class="form form-horizontal mar-top" action="{{ route('products.update', $product->id) }}" method="POST"
        enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-12">
                <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <!-- new ui tabs start -->
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Product Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">Product Images</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="discount-tab" data-bs-toggle="tab" data-bs-target="#discount" type="button" role="tab">Product Discounts</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Product Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Product Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tabs-tab" data-bs-toggle="tab" data-bs-target="#tabs" type="button" role="tab">Product Tabs</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video" type="button" role="tab">Product Video</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">SEO Section</button>
                    </li>
                </ul>
                <!-- new ui tab end -->
                <!-- new ui tab content start-->
                <div class="tab-content mt-3" id="productTabContent">
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.information') }}</h5>
                            </div>
                            <div class="card-body p-0">
                                {{-- <ul class="nav nav-tabs nav-fill border-light">
                                        @foreach (\App\Models\Language::all() as $key => $language)
                                            <li class="nav-item">
                                                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                                    href="{{ route('products.edit', ['id' => $product->id, 'lang' => $language->code]) }}">
                                <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                    height="11" class="mr-1">
                                <span>{{ $language->name }}</span>
                                </a>
                                </li>
                                @endforeach
                                </ul> --}}
                                <div class=" p-4">
                                    <div class="form-group row ">
                                        <div class="col-lg-6">
                                            <label class="col-from-label">{{ trans('messages.product').' '.trans('messages.name') }} <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="name" placeholder="{{ trans('messages.product').' '.trans('messages.name') }}"
                                                value="{{ $product->getTranslation('name',$lang) }}" onkeyup="title_update(this)" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">{{ trans('messages.slug') }}<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="{{ trans('messages.slug') }}" id="slug" name="slug" required
                                                class="form-control" value="{{ $product->slug }}">
                                            @error('slug')
                                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row" id="category">
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.category') }}<span class="text-danger">*</span></label>
                                            <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                                data-selected="{{ $product->category_id }}" data-live-search="true" required>
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}
                                                </option>
                                                @foreach ($category->childrenCategories as $childCategory)
                                                @include('backend.categories.child_category', [
                                                'child_category' => $childCategory,
                                                ])
                                                @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3"  id="brand">
                                            <label class="col-from-label">{{ trans('messages.brand') }}</label>
                                            @php
                                            $brands = \App\Models\Brand::where('is_active',1)->orderBy('name','asc')->get();
                                            @endphp
                                            <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                                data-live-search="true">
                                                <option value="">{{ trans('messages.select').' '.trans('messages.brand') }}</option>
                                                @foreach ($brands as $brand)
                                                <option @if ($product->brand_id == $brand->id) selected @endif value="{{ $brand->id }}">{{ $brand->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">
                                                {{ trans('messages.estimated_delivery_days') }}
                                            </label>
                                            <input type="text"
                                                class="form-control"
                                                name="estimated_delivery_days"
                                                value="{{ $product->estimated_delivery_days }}"
                                                autocomplete="off"
                                                placeholder="{{ trans('messages.estimated_delivery_days') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-form-label">
                                                {{ trans('messages.condition') }} <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control aiz-selectpicker"
                                                name="condition"
                                                id="condition"
                                                data-live-search="true">

                                                <option value="">Select Condition</option>

                                                <option value="0" {{ $product->condition == 0 ? 'selected' : '' }}>
                                                    New
                                                </option>

                                                <option value="1" {{ $product->condition == 1 ? 'selected' : '' }}>
                                                    Refurbished
                                                </option>

                                                <option value="2" {{ $product->condition == 2 ? 'selected' : '' }}>
                                                    Open Box
                                                </option>

                                            </select>
                                        </div>
                                    </div>

                                    <!-- specification block start -->
                                    <hr>
                                    <div class="shadow p-2 bg-light">
                                        <div class="specification_block">
                                            {{-- existing rows --}}
                                            @foreach($productSpecifications as $ps)
                                                <div class="form-group row">
                                                    <input type="hidden" name="product_spec_id[]" value="{{ $ps->id }}">
                                                    <div class="col-md-6">
                                                        <label class="col-from-label">{{ trans('messages.specification') }}</label>
                                                        <select class="form-control aiz-selectpicker"
                                                                name="specification_id[]"
                                                                data-live-search="true">
                                                            <option value="">
                                                                {{ trans('messages.select').' '.trans('messages.specification') }}
                                                            </option>
                                                            @foreach ($specifications as $specification)
                                                                <option value="{{ $specification->id }}"
                                                                    {{ $specification->id == $ps->specification_id ? 'selected' : '' }}>
                                                                    {{ $specification->main_title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @php
                                                    $renderItemTree = function ($items, $parentId = null, $level = 0, $selectedId = null) use (&$renderItemTree) {
                                                        foreach ($items->where('parent_id', $parentId) as $item) {
                                                            $prefix = str_repeat('&nbsp;-', $level);
                                                            $selected = $item->id == $selectedId ? 'selected' : '';
                                                            echo '<option value="'.$item->id.'" '.$selected.'>'.$prefix.$item->title.'</option>';

                                                            // recursive call
                                                            $renderItemTree($items, $item->id, $level + 1, $selectedId);
                                                        }
                                                    };
                                                    @endphp

                                                    <div class="col-md-4">
                                                        <label class="col-from-label">{{ trans('messages.items') }}</label>
                                                        <select class="form-control aiz-selectpicker"
                                                                name="specification_item_id[]"
                                                                data-live-search="true">
                                                            <option value="">
                                                                {{ trans('messages.select').' '.trans('messages.items') }}
                                                            </option>

                                                            @php
                                                            $renderItemTree(
                                                                $specificationItems->where('main_specification_id', $ps->specification_id),
                                                                null,
                                                                0,
                                                                $ps->specification_item_id
                                                            );
                                                            @endphp
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="d-block">&nbsp;</label>
                                                        <button type="button" class="remove-spec border-0 bg-transparent">
                                                            <i class="las la-trash text-danger"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach

                                            {{-- one empty row for "Add More" --}}
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label class="col-from-label">{{ trans('messages.specification') }}</label>
                                                    <select class="form-control aiz-selectpicker"
                                                            name="specification_id[]"
                                                            data-live-search="true">
                                                        <option value="">
                                                            {{ trans('messages.select').' '.trans('messages.specification') }}
                                                        </option>
                                                        @foreach ($specifications as $specification)
                                                            <option value="{{ $specification->id }}">
                                                                {{ $specification->main_title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="col-from-label">{{ trans('messages.items') }}</label>
                                                    <select class="form-control aiz-selectpicker"
                                                            name="specification_item_id[]"
                                                            data-live-search="true">
                                                        <option value="">
                                                            {{ trans('messages.select').' '.trans('messages.items') }}
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="d-block">&nbsp;</label>
                                                    <button type="button" class="remove-spec border-0 bg-transparent">
                                                        <i class="las la-trash text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-10">
                                                <div class="text-right">
                                                    <button type="button"
                                                            class="btn btn-success btn-xs add-more-specification mb-1">
                                                        Add More
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- specification block ends -->
                                        
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.tags') }}</label>
                                            <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                                placeholder="{{ trans('messages.type_hit_enter_add_tag') }}" value="{{ $product->tags }}">
                                            <small class="text-muted">{{ trans('messages.tag_details') }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.return_refund').' '.trans('messages.status') }}</label>
                                            <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                                <input type="hidden" name="return_refund" value="0">
                                                <input type="checkbox" name="return_refund" value="1" @if(old('return_refund', $product->return_refund ?? 0)) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.product_length') }}</label>
                                            <input type="text" class="form-control" name="product_length" value="{{ $product->product_length }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.product_width') }}</label>
                                            <input type="text" class="form-control" name="product_width" value="{{ $product->product_width }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.product_height') }}</label>
                                            <input type="text" class="form-control" name="product_height" value="{{ $product->product_height }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.product_weight') }}</label>
                                            <input type="text" class="form-control" name="product_weight" value="{{ $product->product_weight }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="images" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.images') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.gallery_images') }}<small>({{ trans('messages.1000*1000') }})</small></label>
                                    <div class="col-md-8">
                                        <input type="file" name="gallery_images[]" multiple class="form-control" accept="image/*">
                                        @if ($product->photos)
                                        <div class="file-preview box sm">
                                            @php
                                            $photos = explode(',', $product->photos);
                                            @endphp
                                            @foreach ($photos as $photo)
                                            <div
                                                class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                <div
                                                    class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                    <img src="{{ Storage::url($photo) }}" class="img-fit">
                                                </div>
                                                <div class="remove">
                                                    <button class="btn btn-link remove-galley"
                                                        data-url="{{ $photo }}" type="button">
                                                        <i class="la la-close"></i></button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.thumbnail_image') }}
                                        <small>({{ trans('messages.1000*1000') }})</small></label>
                                    <div class="col-md-8">
                                        <input type="file" name="thumbnail_image" class="form-control" accept="image/*">

                                        @if ($product->thumbnail_img)
                                        <div class="file-preview box sm">
                                            <div
                                                class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                <div
                                                    class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                    <img src="{{ Storage::url($product->thumbnail_img) }}"
                                                        class="img-fit">
                                                </div>
                                                <div class="remove">
                                                    <button class="btn btn-link remove-thumbnail" type="button">
                                                        <i class="la la-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
                                <div class="col-lg-8">
                                    <div id="thumbnail_img">
                                        @if ($product->thumbnail_img != null)
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="img-upload-preview">
                                                <img loading="lazy" src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" class="img-responsive">
                                                <input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
                                                <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="discount" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.discounts') }}</h5>
                            </div>
                            <div class="card-body">

                                @php
                                $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                                $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                                @endphp

                                <div class="form-group row">
                                    <label class="col-sm-3 col-from-label" for="start_date">{{ trans('messages.discount').' '.trans('messages.date').' '.trans('messages.range') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control aiz-date-range"
                                            @if ($product->discount_start_date && $product->discount_end_date) value="{{ $start_date . ' to ' . $end_date }}" @endif
                                        name="date_range" placeholder="{{ trans('messages.select').' '.trans('messages.date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.discount') }}</label>
                                    <div class="col-lg-6">
                                        <input type="number" lang="en" min="0" step="0.01"
                                            placeholder="{{ trans('messages.discount') }}" name="discount" class="form-control"
                                            value="{{ $product->discount }}">
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control aiz-selectpicker" name="discount_type">
                                            <option value="amount" <?php if ($product->discount_type == 'amount') {
                                                                        echo 'selected';
                                                                    } ?>>{{ trans('messages.flat') }}</option>
                                            <option value="percent" <?php if ($product->discount_type == 'percent') {
                                                                        echo 'selected';
                                                                    } ?>>{{ trans('messages.percent') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="details" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.details') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="col-from-label">
                                            {{ trans('messages.product_type') }}
                                        </label>
                                        <select class="form-control aiz-selectpicker"
                                            name="product_type"
                                            id="product_type" @if(isset($product) && $product->product_type == 1) disabled @endif>

                                            <option value="0" {{ $product->product_type == 0 ? 'selected' : '' }}>
                                                Single
                                            </option>

                                            <option value="1" {{ $product->product_type == 1 ? 'selected' : '' }}>
                                                Variant
                                            </option>

                                        </select>
                                        <input type="hidden" name="product_type" value="{{ $product->product_type }}">
                                    </div>
                                    <div class="col-md-6 variant-selector"  style="display:none">
                                        @php
                                        $attributes = \App\Models\Attribute::with('values')->where('is_active',1)->get();
                                        $selectedAttributes = $product->productAttributes
                                        ->pluck('attribute_id')
                                        ->unique()
                                        ->toArray();
                                        @endphp
                                        <label class="col-from-label">Select Variants</label>
                                        <select class="form-control aiz-selectpicker"
                                            id="attribute_selector"
                                            name="attributes[]"
                                            multiple
                                            data-live-search="true">

                                            @foreach($attributes as $attribute)
                                            <option value="{{ $attribute->id }}"
                                                {{ in_array($attribute->id,$selectedAttributes) ? 'selected' : '' }}>
                                                {{ $attribute->name }}
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <!-- Single type product block starts -->
                                <div id="single-fields" class="shadow p-3" @if(isset($product) && $product->product_type == 1) style="display:none;" @endif>
                                    <div class="d-flex justify-content-between">    
                                        <strong>Single Product</strong>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" name="status" value="1" {{ $product->stocks[0]->status == 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.sku') }} </label>
                                            <input type="text" placeholder="{{ trans('messages.sku') }}" name="sku" class="form-control" value="{{ $product->stocks[0]->sku }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.quantity') }} <span class="text-danger">*</span></label>
                                            <input type="number" lang="en" min="0" value="{{ $product->stocks[0]->qty }}" name="current_stock" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.price') }} <span class="text-danger">*</span></label>
                                            <input type="number" lang="en" min="0" value="{{ $product->stocks[0]->price }}" step="0.01" placeholder="{{ trans('messages.price') }}" name="price" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.vat') }} (%) </label>
                                            <input type="number" lang="en" min="0" value="{{ $product->stocks[0]->vat }}" step="0.01" placeholder="VAT" name="vat" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Variant Image</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">

                                            @if(isset($product->stocks[0]->image) && $product->stocks[0]->image)
                                            <div class="mt-2">
                                                <img src="{{ Storage::url($product->stocks[0]->image) }}" class="img-fit size-50px" alt="Variant Image">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 col-from-label">{{trans('messages.description') }}</label>
                                        <div class="col-md-12">
                                            <textarea class="aiz-text-editor" data-min-height="300" name="stock_description">
                                                {{ $product->stocks[0]->stock_description }}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single type product block ends -->
                                <!-- Variant type product block starts -->
                                <div id="variants-container" @if(isset($product) && $product->product_type == 0) style="display:none;" @endif>
                                    @foreach($product->stocks->where('type', 'variant')->values() as $index => $stock)
                                    <div class="card mb-3 variant-box shadow" data-index="{{ $index }}">
                                        <div class="card-header">
                                            <strong>Product Variant {{ $index+1 }}</strong>
                                            <input type="hidden" name="variants[{{ $index }}][stock_id]" value="{{ $stock->id }}">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="hidden" name="variants[{{ $index }}][status]" value="0">
                                                <input type="checkbox" name="variants[{{ $index }}][status]" value="1" {{ $stock->status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <div class="card-body">
                                            {{-- attribute labels --}}
                                            <div class="variant-attributes">
                                                <div class="form-group row">
                                                @foreach($stock->attributes->sortBy('attribute_id') as $attrRow)
                                                    <div class="col-md-3 mb-1">
                                                        <label>
                                                            {{ $attrRow->attribute->name }}
                                                        </label>
                                                        <select
                                                            name="variants[{{ $loop->parent->index }}][attributes][{{ $attrRow->attribute_id }}]"
                                                            class="form-control">

                                                            <option value="">Select {{ $attrRow->attribute->name }}</option>

                                                            @foreach($attrRow->attribute->values as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ $val->id == $attrRow->attribute_value_id ? 'selected' : '' }}>
                                                                {{ $val->value }}
                                                            </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label class="col-from-label">{{ trans('messages.sku') }} </label>
                                                    <input type="text" placeholder="{{ trans('messages.sku') }}" name="variants[{{ $index }}][sku]" class="form-control" value="{{ $stock->sku }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-from-label">{{ trans('messages.quantity') }} <span class="text-danger">*</span></label>
                                                    <input type="number" lang="en" min="0" step="0.01" placeholder="{{ trans('messages.quantity') }}" name="variants[{{ $index }}][current_stock]" class="form-control" required
                                                        value="{{ $stock->qty ?? 0 }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label class="col-from-label">{{ trans('messages.price') }} <span class="text-danger">*</span></label>
                                                    <input type="number" lang="en" min="0" value="{{ $stock->price ?? 0 }}" step="0.01" placeholder="{{ trans('messages.price') }}" name="variants[{{ $index }}][price]" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-from-label">{{ trans('messages.vat') }} (%)</label>
                                                    <input type="number" lang="en" min="0" step="0.01" placeholder="VAT" name="variants[{{ $index }}][vat]" class="form-control" value="{{ $stock->vat }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label>Variant Image</label>
                                                    <input type="file" name="variants[{{ $index }}][image]" class="form-control" accept="image/*">
                                                    @if(isset($stock->image) && $stock->image)
                                                    <div class="mt-2">
                                                        <img src="{{ Storage::url($stock->image) }}" class="img-fit size-50px" alt="Variant Image">
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-12 col-from-label">{{trans('messages.description') }}</label>
                                                <div class="col-md-12">
                                                    <textarea class="aiz-text-editor" data-min-height="300" name="variants[{{ $index }}][stock_description]">{{ $stock->stock_description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-right mb-3">
                                    <button type="button"
                                        id="add-variant"
                                        class="btn btn-sm btn-primary" @if(isset($product) && $product->product_type == 0) style="display:none;" @endif>
                                        Add Variant
                                    </button>
                                </div>
                                <!-- Variant type product block ends -->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="description" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.description') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{trans('messages.description') }}</label>
                                    <div class="col-md-8">
                                        <textarea class="aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]' data-min-height="300" name="description">{{ $product->getTranslation('description',$lang) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs" role="tabpanel">
                        <div class="card repeater">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.tabs') }}</h5>
                            </div>
                            <div class="card-body">
                                <div data-repeater-list="tabs">
                                    <div data-repeater-item>
                                        <input type="hidden" name="tab_id">
                                        <div class="form-group row">
                                            <label class="col-md-12 col-from-label">{{ trans('messages.heading') }}</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="tab_heading">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-12 col-from-label">{{ trans('messages.description') }}</label>
                                            <div class="col-md-12">
                                                <textarea class="text-area" name="tab_description"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">

                                            <div class="col-md-12 text-right">
                                                <input data-repeater-delete type="button" class="btn btn-danger action-btn"
                                                    value="{{ trans('messages.delete') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input data-repeater-create type="button" class="btn btn-success action-btn "
                                    value="{{ trans('messages.add') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="video" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.video') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.video').' '.trans('messages.provider') }}</label>
                                    <div class="col-lg-8">
                                        <select class="form-control aiz-selectpicker" name="video_provider"
                                            id="video_provider">
                                            <option value="youtube" <?php if ($product->video_provider == 'youtube') {
                                                                        echo 'selected';
                                                                    } ?>>{{ trans('messages.youtube') }}</option>

                                            <option value="vimeo" <?php if ($product->video_provider == 'vimeo') {
                                                                        echo 'selected';
                                                                    } ?>>{{ trans('messages.vimeo') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.video').' '.trans('messages.link') }}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="video_link"
                                            value="{{ $product->video_link }}" placeholder="{{ trans('messages.video').' '.trans('messages.link') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ trans('messages.seo_section') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.meta_title') }}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control"
                                            value="{{ $product->getSeoTranslation('meta_title',$lang) }}" name="meta_title"
                                            placeholder="Meta Title">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.meta_description') }}</label>
                                    <div class="col-lg-8">
                                        <textarea name="meta_description" rows="8" class="form-control">{{ $product->getSeoTranslation('meta_description',$lang) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ trans('messages.meta_keywords') }}</label>
                                    <div class="col-md-8">
                                        {{-- data-max-tags="1" --}}
                                        <input type="text" class="form-control aiz-tag-input" name="meta_keywords[]"
                                            placeholder="Type and hit enter to add a keyword"
                                            value="{{ $product->getSeoTranslation('meta_keywords',$lang) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.og_title') }}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="og_title" placeholder="{{ trans('messages.og_title') }}"
                                            value="{{ $product->getSeoTranslation('og_title',$lang) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.og_description') }}</label>
                                    <div class="col-lg-8">
                                        <textarea name="og_description" rows="8" class="form-control">{{ $product->getSeoTranslation('og_description',$lang)  }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_title') }}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="twitter_title"
                                            placeholder="{{ trans('messages.twitter_title') }}"
                                            value="{{ $product->getSeoTranslation('twitter_title',$lang) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_description') }}</label>
                                    <div class="col-lg-8">
                                        <textarea name="twitter_description" rows="8" class="form-control">{{ $product->getSeoTranslation('twitter_description',$lang) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- new ui tab content end -->
            </div>
            <div class="col-12">
                <div class="mb-3 text-right">
                    <button type="submit" name="button" class="btn btn-info">{{ trans('messages.update').' '.trans('messages.product') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('styles')
<style>

</style>
@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
    integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('.remove-thumbnail').on('click', function() {
        thumbnail = $(this)
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('products.delete_thumbnail') }}",
            data: {
                id: '{{ $product->id }}'
            },
            success: function(data) {
                $(thumbnail).closest('.file-preview-item').remove();
            }
        });
    });

    $('.remove-galley').on('click', function() {
        thumbnail = $(this)
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('products.delete_gallery') }}",
            data: {
                url: $(thumbnail).data('url'),
                id: '{{ $product->id }}'
            },
            success: function(data) {
                $(thumbnail).closest('.file-preview-item').remove();
            }
        });
    });
</script>

@php
$tabs = [];
foreach ($product->tabs as $key => $tab) {
$tabs[$key]['tab_id'] = $tab->id;
$tabs[$key]['tab_heading'] = $tab->heading;
$tabs[$key]['tab_description'] = $tab->content;
}
@endphp

<script>
    let buttons = [
        ["font", ["bold", "underline", "italic", "clear"]],
        ["para", ["ul", "ol", "paragraph"]],
        ["style", ["style"]],
        ["color", ["color"]],
        // ["table", ["table"]],
        ['mytable', ['addTable']],
        ["insert", ["link", "picture", "video"]],
        ["view", ["fullscreen", "codeview", "undo", "redo"]],

    ];
    $('.description-text-area').summernote({
        toolbar: buttons,
        height: 200,

        callbacks: {
            onImageUpload: function(data) {
                data.pop();
            },
            onPaste: function(e) {
                if (format) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window
                        .clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        }
    });

    var repeater = $('.repeater').repeater({
        initEmpty: true,
        show: function() {
            note = $(this).find('.text-area').summernote({
                toolbar: buttons,
                height: 200,
                buttons: {
                    addTable: function(context) {
                        var ui = $.summernote.ui;
                        // create button
                        var button = ui.button({
                            contents: '<i class="note-icon-table"/>',
                            tooltip: 'Insert 2x2 Table',
                            click: function() {
                                var tableHtml = '<table class="table table-bordered">';
                                for (var i = 0; i < 2; i++) {
                                    tableHtml += '<tr>';
                                    for (var j = 0; j < 2; j++) {
                                        tableHtml += '<td><br></td>';
                                    }
                                    tableHtml += '</tr>';
                                }
                                tableHtml += '</table>';
                                context.invoke('editor.insertNode', $(tableHtml)[0]);
                            }
                        });
                        return button.render();
                    }
                },
                callbacks: {
                    onImageUpload: function(data) {
                        data.pop();
                    },
                    onPaste: function(e) {
                        if (format) {
                            var bufferText = ((e.originalEvent || e).clipboardData || window
                                .clipboardData).getData('Text');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    }
                }
            });

            var nativeHtmlBuilderFunc = note.summernote('module', 'videoDialog').createVideoNode;

            note.summernote('module', 'videoDialog').createVideoNode = function(url) {
                var wrap = $('<div class="embed-responsive embed-responsive-16by9"></div>');
                var html = nativeHtmlBuilderFunc(url);
                html = $(html).addClass('embed-responsive-item');
                return wrap.append(html)[0];
            };

            $(this).slideDown();

        },
        hide: function(deleteElement) {
            if (confirm('Are you sure you want to delete this element?')) {
                $(this).slideUp(deleteElement);
            }
        },
    });

    repeater.setList({!!json_encode($tabs) !!});

    AIZ.plugins.tagify();

    $(document).ready(function() {
        $('.remove-files').on('click', function() {
            $(this).parents(".col-md-4").remove();
        });
    });

    $(function () {
        if ($("#product_type").val() == 1) {
            $("#single-fields").hide();
            $("#variant-fields").show();
            $(".variant-selector").show();
            AIZ.plugins.textEditor();
        } else {
            $("#single-fields").show();
            $("#variant-fields").hide();
            $(".variant-selector").hide();
            AIZ.plugins.textEditor();
        }
    });

    // Pass php data attributes to JS.
    var attributes = @json($attributes);

    // product type selection changes.
    $("#product_type").on("change", function () {
        if ($(this).val() == 1) { // Single → Variant
            $("#single-fields").hide();
            $("#variant-fields").show();
            $(".variant-selector").show();
            $("#add-variant").show()
            $("#variants-container").html("").show(); 
            AIZ.plugins.textEditor();
            addVariantBox();
            AIZ.plugins.textEditor();
        }
    });

    // attribute select change
    $("#attribute_selector").on("change", function () {
        renderAttributes();
    });

    // add variant
    $("#add-variant").on("click", function () {
        addVariantBox();
    });

    // add variant box
    function addVariantBox() {
        let index = $(".variant-box").length;
        let html = `
            <div class="card mb-3 variant-box shadow" data-index="${index}">
                <div class="card-header">
                    <h6>Product Variant ${index + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-variant">X</button>
                </div>
                <div class="card-body">
                    <div class="variant-attributes mb-3"><div class="form-group row"></div></div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>SKU</label>
                            <input type="text" name="variants[${index}][sku]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Quantity</label>
                            <input type="number" name="variants[${index}][current_stock]" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Price</label>
                            <input type="number" name="variants[${index}][price]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>VAT</label>
                            <input type="number" name="variants[${index}][vat]" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Variant Image</label>
                            <input type="file" name="variants[${index}][image]" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 col-from-label">{{trans('messages.description') }}</label>
                        <div class="col-md-12">
                            <textarea class="aiz-text-editor" data-min-height="300" name="variants[${index}][stock_description]"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $("#variants-container").append(html);
        AIZ.plugins.textEditor();
        renderAttributesForBox(index);
    }

    // render attributes
    function renderAttributes() {
        let selectedAttrs = $("#attribute_selector").val() || [];

        $(".variant-box").each(function () {
            let box = $(this);
            let index = box.data("index");
            let container = box.find(".variant-attributes .form-group");

            // store existing selected values
            let existingValues = {};
            container.find("select").each(function () {
                let name = $(this).attr("name");
                existingValues[name] = $(this).val();
            });

            container.html("");

            selectedAttrs.forEach(function (attrId) {
                let attr = attributes.find(a => a.id == attrId);
                if (!attr) return;

                let selectName = `variants[${index}][attributes][${attr.id}]`;

                let options = `<option value="">Select Value</option>`;
                attr.values.forEach(function (val) {
                    let selected =
                        existingValues[selectName] == val.id ? "selected" : "";
                    options += `<option value="${val.id}" ${selected}>${val.value}</option>`;
                });

                container.append(`
                    <div class="col-md-3 mb-1">
                        <label>${attr.name}</label>
                        <select class="form-control" name="${selectName}">
                            ${options}
                        </select>
                    </div>
                `);
            }); 
        });
    }

    function renderAttributesForBox(index){
        let selectedAttrs = $("#attribute_selector").val();
        if (!selectedAttrs || selectedAttrs.length === 0) return;
        let box = $('.variant-box[data-index="'+index+'"]');
        let container = box.find(".variant-attributes  .form-group");
        container.html("");
        selectedAttrs.forEach(function(attrId){
            let attr = attributes.find(a => a.id == attrId);
            if(!attr) return;
            let options = `<option value="">Select Value</option>`;
            attr.values.forEach(function(val){
                options += `<option value="${val.id}">${val.value}</option>`;
            });
            container.append(`
                <div class="col-md-3 mb-1">
                    <label>${attr.name}</label>
                    <select class="form-control"
                        name="variants[${index}][attributes][${attr.id}]">
                        ${options}
                    </select>
                </div>
            `);
        });
    }

    $('#product_type').on('change', function () {
        $('#product_type_hidden').val(this.value);
    }); 

    // specification
    $(document).on('change', "select[name='specification_id[]']", function () {
        var specId = $(this).val();
        var $block = $(this).closest('.form-group');   // current block
        var $itemSelect = $block.find("select[name='specification_item_id[]']");

        if (specId) {
            $.ajax({
                url: "{{ route('specifications.items') }}",
                type: 'GET',
                data: { specification_id: specId },
                success: function (data) {

                    $itemSelect.empty();
                    $itemSelect.append(
                        "<option value=''>{{ trans('messages.select') }} {{ trans('messages.items') }}</option>"
                    );

                    data.forEach(function (item) {
                        $itemSelect.append(
                            '<option value="' + item.id + '">' + item.title + '</option>'
                        );

                        if (item.sub_items && item.sub_items.length) {
                            addSubItems(item.sub_items, $itemSelect, 1);
                        }
                    });

                    $itemSelect.selectpicker('refresh');
                }
            });
        } else {
            $itemSelect
                .empty()
                .append("<option value=''>{{ trans('messages.select') }} {{ trans('messages.items') }}</option>")
                .selectpicker('refresh');
        }
    });

    function addSubItems(items, $select, level) {
        items.forEach(function(subItem) {
            var prefix = '&nbsp;-'.repeat(level) + ' ';
            $select.append('<option value="'+subItem.id+'">'+prefix+subItem.title+'</option>');

            if(subItem.sub_items && subItem.sub_items.length) {
                addSubItems(subItem.sub_items, $select, level + 1);
            }
        });
    }

    $(document).on("click", ".add-more-specification", function () {
        var $block = $(this)
            .parents(".row")
            .siblings(".specification_block")
            .children()
            .first();

        var $clone = $block.clone();

        // remove bootstrap-select wrappers from the clone
        $clone.find('.bootstrap-select').each(function () {
            var $wrapper = $(this);
            var $select  = $wrapper.find('select.aiz-selectpicker');

            // move select out and remove wrapper
            $wrapper.replaceWith($select);
        });

        // reset values in the clone
        $clone.find("select[name='specification_id[]']").val('');
        $clone.find("select[name='specification_item_id[]']").empty()
            .append("<option value=''>{{ trans('messages.select') }} {{ trans('messages.items') }}</option>");

        // append clone
        $(".specification_block").append($clone);

        // initialize selectpicker only in the new clone
        $clone.find('.aiz-selectpicker').selectpicker('render');
    });


</script>
@endsection
