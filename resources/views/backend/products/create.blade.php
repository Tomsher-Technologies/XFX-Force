@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ trans('messages.add').' '.trans('messages.new').' '.trans('messages.product') }}</h5>
    </div>
    <div class="">
        <form class="form form-horizontal mar-top" id="addNewProduct" action="{{ route('products.store') }}" method="POST"
            enctype="multipart/form-data" novalidate>
            <div class="row gutters-5">
                <div class="col-lg-12">
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
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.product').' '.trans('messages.name') }} <span
                                                class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" placeholder="{{ trans('messages.product').' '.trans('messages.name') }}"
                                            onkeyup="title_update(this)" required>
                                            <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label">{{ trans('messages.slug') }}<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="{{ trans('messages.slug') }}" id="slug" name="slug" required
                                                class="form-control">
                                            <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3" id="category">
                                            <label class="col-from-label">{{ trans('messages.category') }} <span class="text-danger">*</span></label>
                                            <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                                data-live-search="true" required>
                                                <option value="">{{ trans('messages.select').' '.trans('messages.category') }}</option>
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
                                            <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                        </div>
                                        <div class="col-md-3" id="brand">
                                            <label class="col-from-label">{{ trans('messages.brand') }}</label>
                                            @php   
                                                $brands = \App\Models\Brand::where('is_active',1)->orderBy('name','asc')->get();
                                            @endphp
                                            <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                                data-live-search="true">
                                                <option value="">{{ trans('messages.select').' '.trans('messages.brand') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}
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
                                                placeholder="{{ trans('messages.estimated_delivery_days') }}"
                                                autocomplete="off">
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label class="col-form-label">{{ trans('messages.condition') }}</label>
                                            <select class="form-control aiz-selectpicker" name="condition" id="condition"
                                                data-live-search="true">
                                                <option value="">Select Condition</option>
                                                <option value="0">New</option>
                                                <option value="1">Refurbished</option>
                                                <option value="2">Open Box</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- specification block starts -->
                                    <hr>
                                    <div class="shadow p-2 bg-light">
                                        <div class="specification_block">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label class="col-from-label">{{ trans('messages.specification') }}</label>
                                                    @php
                                                    $specifications = \App\Models\Specification::where('status',1)->orderBy('display_title','asc')->get();
                                                    @endphp
                                                    <select class="form-control aiz-selectpicker" name="specification_id[]" data-live-search="true">
                                                        <option value="">{{ trans('messages.select').' '.trans('messages.specification') }}</option>
                                                        @foreach ($specifications as $specification)
                                                        <option value="{{ $specification->id }}">{{ $specification->main_title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="col-from-label">{{ trans('messages.items') }}</label>
                                                    <select class="form-control aiz-selectpicker" name="specification_item_id[]" data-live-search="true">
                                                        <option value="">{{ trans('messages.select').' '.trans('messages.items') }}</option>
                                                        <!-- Options will be populated via AJAX -->
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
                                                    <button type="button" class="btn btn-success btn-xs add-more-specification mb-1">
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
                                                placeholder="{{ trans('messages.type_hit_enter_add_tag') }}">
                                            <small class="text-muted">{{ trans('messages.tag_details') }}</small>
                                        </div>
                                        <div class="col-md-4">
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
                                            <input type="text" class="form-control" name="product_length">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.product_width') }}</label>
                                            <input type="text" class="form-control" name="product_width">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.product_height') }}</label>
                                            <input type="text" class="form-control" name="product_height">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{ trans('messages.product_weight') }}</label>
                                            <input type="text" class="form-control" name="product_weight">
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
                                        <div class="col-md-6">
                                            <label class="col-form-label" for="signinSrEmail">{{ trans('messages.gallery_images') }}<small>({{ trans('messages.1000*1000') }})</small><span
                                                class="text-danger">*</span></label>
                                            <input type="file" name="gallery_images[]" multiple class="form-control"
                                                accept="image/*" required>
                                                <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label" for="signinSrEmail">{{ trans('messages.thumbnail_image') }}
                                            <small>({{ trans('messages.1000*1000') }})</small><span
                                                class="text-danger">*</span></label>
                                            <input type="file" name="thumbnail_image" class="form-control" accept="image/*" required>
                                            <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="discount" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.discounts') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="date_range">{{ trans('messages.discount').' '.trans('messages.date').' '.trans('messages.range') }}  </label>
                                            <input type="text" class="form-control aiz-date-range" id="date_range"
                                                name="date_range" placeholder="{{ trans('messages.select').' '.trans('messages.date') }}" data-time-picker="true"
                                                data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-from-label">{{ trans('messages.discount') }}</label>
                                            <input type="number" lang="en" min="0" value="0" step="0.01"
                                                placeholder="{{ trans('messages.discount') }}" name="discount" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-from-label">&nbsp;</label>
                                            <select class="form-control aiz-selectpicker" name="discount_type">
                                                <option value="amount">{{ trans('messages.flat') }}</option>
                                                <option value="percent">{{ trans('messages.percent') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="details" role="tabpanel">               
                            <div class="card product-repeater">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.details') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.product_type') }} </label>
                                            <select class="form-control aiz-selectpicker" name="product_type" id="product_type"
                                                data-live-search="true">
                                                <option value="0" selected>Single</option>
                                                <option value="1">Variant</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 variant-selector"  style="display:none">
                                            <label class="col-from-label">
                                                Select Variants
                                            </label>
                                            @php   
                                            $attributes = \App\Models\Attribute::with('values')
                                                ->where('is_active',1)
                                                ->orderBy('name','asc')
                                                ->get();
                                            @endphp
                                            <select class="form-control aiz-selectpicker"
                                                id="attribute_selector"
                                                multiple
                                                data-live-search="true">

                                                @foreach($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}">
                                                        {{ $attribute->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Single type product block starts -->
                                    <div id="single-fields" class="shadow p-3">
                                        <strong>Single Product</strong><hr>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label class="col-from-label">{{ trans('messages.sku') }} </label>
                                                <input type="text" placeholder="{{ trans('messages.sku') }}" name="sku" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-from-label">{{ trans('messages.quantity') }} <span  class="text-danger">*</span></label>
                                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ trans('messages.quantity') }}" name="current_stock" class="form-control" required>
                                                <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label class="col-from-label">{{ trans('messages.price') }} <span class="text-danger">*</span></label>
                                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ trans('messages.price') }}" name="price" class="form-control" required>
                                                <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-from-label">{{ trans('messages.vat') }} (%) </label>
                                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="VAT" name="vat" class="form-control"  required>
                                                <span class="error text-danger" style="display:none;">{{ trans('messages.required') }}</span>
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label>Variant Image</label>
                                                <input type="file" name="image" class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-12 col-from-label">{{trans('messages.description') }}</label>
                                            <div class="col-md-12">
                                                <textarea class="aiz-text-editor" data-min-height="300" name="stock_description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Single type product block ends -->
                                    
                                    <!-- Variant type product block starts -->
                                    <div id="variant-fields" style="display:none">
                                        <div id="variants-container"></div>
                                        <div class="text-right mb-3">
                                            <button type="button"
                                                id="add-variant"
                                                class="btn btn-sm btn-primary mb-2">
                                                Add Variant
                                            </button>
                                        </div>
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
                                        <label class="col-md-12 col-from-label">{{trans('messages.description') }}</label>
                                        <div class="col-md-12">
                                            <textarea class="aiz-text-editor" data-min-height="300" name="description"></textarea>
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
                                                    <input data-repeater-delete type="button" class="btn btn-danger action-btn" value="{{ trans('messages.delete') }}" />
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <input data-repeater-create type="button" class="btn btn-success action-btn"
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
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.video').' '.trans('messages.provider') }}</label>
                                            <select class="form-control aiz-selectpicker" name="video_provider"
                                                id="video_provider">
                                                <option value="youtube">{{ trans('messages.youtube') }}</option>
                                                <option value="vimeo">{{ trans('messages.vimeo') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-from-label">{{ trans('messages.video').' '.trans('messages.link') }}</label>
                                            <input type="text" class="form-control" name="video_link"
                                                placeholder="{{ trans('messages.video').' '.trans('messages.link') }}">
                                            <small class="text-muted">
                                                {{-- Use proper link without extra parameter. Don't use short share link/embeded iframe code. --}}
                                                </small>
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
                                            <input type="text" class="form-control" name="meta_title"
                                                placeholder="{{ trans('messages.meta_title') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-from-label">{{ trans('messages.meta_description') }}</label>
                                        <div class="col-lg-8">
                                            <textarea name="meta_description" rows="8" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ trans('messages.meta_keywords') }}</label>
                                        <div class="col-md-8">
                                            {{-- data-max-tags="1" --}}
                                            <input type="text" class="form-control aiz-tag-input" name="meta_keywords[]"
                                                placeholder="{{ trans('messages.type_hit_enter_add_keyword') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-from-label">{{ trans('messages.og_title') }}</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="og_title" placeholder="{{ trans('messages.og_title') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-from-label">{{ trans('messages.og_description') }}</label>
                                        <div class="col-lg-8">
                                            <textarea name="og_description" rows="8" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_title') }}</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="twitter_title"
                                                placeholder="{{ trans('messages.twitter_title') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_description') }}</label>
                                        <div class="col-lg-8">
                                            <textarea name="twitter_description" rows="8" class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- new ui tab content end -->
                </div>
                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group mr-2" role="group" aria-label="First group">
                            <button type="submit" name="button" value="draft" class="btn btn-cancel action-btn">{{ trans('messages.save_draft') }}</button>
                        </div>
                       
                        <div class="btn-group" role="group" aria-label="Second group">
                            <button type="submit" name="button" value="publish"
                                class="btn btn-success action-btn">{{ trans('messages.save_publish') }}</button>
                        </div>
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
      
        let buttons = [
                    ["font", ["bold", "underline", "italic", "clear"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["style", ["style"]],
                    ["color", ["color"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "undo", "redo"]],
                ];
        $('.description-text-area').summernote({
            toolbar: buttons,
            height: 300,
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

        $('.repeater').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();

                note = $(this).find('.text-area').summernote({
                    toolbar: buttons,
                    height: 300,
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

            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
        });

    </script>

    <script type="text/javascript">
        $('form').bind('submit', function(e) {
            if ($(".action-btn").attr('attempted') == 'true') {
                e.preventDefault();
            } else {
                $(".action-btn").attr("attempted", 'true');
            }
        });

    
let attributes = @json($attributes->values());

// product type switch
$("#product_type").on("change", function () {
    if ($(this).val() == 1) {     // Variant
        $("#single-fields").hide();
        $("#variant-fields").show();
        $(".variant-selector").show();
        if ($(".variant-box").length === 0) {
            addVariantBox();
        }
        AIZ.plugins.textEditor();

    } else { // Single
        $("#single-fields").show();
        $("#variant-fields").hide();
        $(".variant-selector").hide();
        $("#variants-container").html("");
        AIZ.plugins.textEditor();
    }
});

// attribute select change
$("#attribute_selector").on("change", function () {
    renderAttributes();
});

// create variant box
function addVariantBox() {
    let index = $(".variant-box").length;
    let html = `
        <div class="variant-box shadow p-3 mb-3" data-index="${index}">
            <div class="d-flex justify-content-between">
                <h6>Product Variant ${index + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-variant">X</button>
            </div>
            <hr>

            <div class="variant-attributes mb-3 form-group row"></div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>SKU</label>
                    <input type="text" name="variants[${index}][sku]" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Quantity</label>
                    <input type="number" name="variants[${index}][current_stock]" class="form-control" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>Price</label>
                    <input type="number" name="variants[${index}][price]" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>VAT</label>
                    <input type="number" name="variants[${index}][vat]" class="form-control" required>
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
    `;

    $("#variants-container").append(html);
    AIZ.plugins.textEditor();

    renderAttributesForBox(index);
}

// render attributes
function renderAttributes() {
    let selectedAttrs = $("#attribute_selector").val();
    $(".variant-box").each(function () {
        let box = $(this);
        let index = box.data("index");
        let container = box.find(".variant-attributes");
        container.html("");
        if (!selectedAttrs || selectedAttrs.length === 0) return;
        selectedAttrs.forEach(function (attrId) {
            let attr = attributes.find(a => a.id == attrId);
            if (!attr) return;
            let options = `<option value="">Select Value</option>`;
            attr.values.forEach(function (val) {
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
    });
}

function renderAttributesForBox(index){
    let selectedAttrs = $("#attribute_selector").val();
    if (!selectedAttrs || selectedAttrs.length === 0) return;
    let box = $('.variant-box[data-index="'+index+'"]');
    let container = box.find(".variant-attributes");
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

$(function() {
    $('#addNewProduct').find(".error.text-danger").hide();
    function blinkTab(tabButton) {
        let blinkCount = 0;
        const maxBlinks = 4;
        const interval = setInterval(() => {
            tabButton.classList.toggle('text-danger');
            blinkCount++;
            if (blinkCount >= maxBlinks * 2) {
                clearInterval(interval);
                tabButton.classList.remove('text-danger');
            }
        }, 300);
    }

    function validateForm() {
        let firstInvalidTab = null;
        let currentTabId = $('.tab-pane.active').attr('id');

        $('#addNewProduct').find('[required]').each(function() {
            const $field = $(this);
            let isEmpty = false;

            if ($field.is('input[type="file"]')) {
                isEmpty = !$field[0].files.length;
            } else if ($field.is('select')) {
                isEmpty = !$field.val() || $field.val() === "";
            } else {
                isEmpty = !$field.val().trim();
            }

            if (isEmpty) {
                const tabPane = $field.closest('.tab-pane');
                if (!tabPane.length) return;
                const tabId = tabPane.attr('id');
                const tabButton = $(`#productTab button[data-bs-target="#${tabId}"]`)[0];
                $field.addClass('border-danger');
                $field.parents(".form-group").find(".error.text-danger").show();
                if (!firstInvalidTab) firstInvalidTab = tabId;
                if(tabButton) blinkTab(tabButton);
            } else {
                $field.removeClass('border-danger');
                $field.parents(".form-group").find(".error.text-danger").hide();
            }
        });
        return firstInvalidTab;
    }

    $('#addNewProduct').on('submit', function(e) {
        e.preventDefault();
        let firstInvalidTab = validateForm();
        let currentTabId = $('.tab-pane.active').attr('id');
        if (firstInvalidTab) {
            if(firstInvalidTab !== currentTabId) {
                const tabButton = $(`#productTab button[data-bs-target="#${firstInvalidTab}"]`);
                tabButton.tab('show');
            }
            return false;
        }
        this.submit();
    });
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
