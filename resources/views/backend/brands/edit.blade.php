@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">
                    {{ trans('messages.edit').' '.trans('messages.brand') }}
                </h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Basic Information -->
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $brand->name) }}">
                        </div>
                        <div class="col-md-6">
                            <label>Slug *</label>
                            <input type="text" name="slug" class="form-control form-control-sm" value="{{ old('slug', $brand->slug) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control form-control-sm">
                            @if(isset($brand->logo) && $brand->logo)
                            <div class="mt-2">
                                <img src="{{ Storage::url($brand->logo) }}" class="img-fit size-50px" alt="Brand Logo">
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">{{ trans('messages.active_status') }}</label>
                            <select name="is_active" class="form-control form-control-sm">
                                <option value="">Select Status</option>
                                <option value="1" {{ $brand->is_active == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $brand->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <!-- Brand Sections -->
                    <h6 class="mb-0">{{ trans('messages.brand') . ' ' . trans('messages.sections') }}</h6>
                    <hr>
                    <div class="repeater" id="brand-sections-repeater">
                        <div data-repeater-list="sections">
                            @forelse($brand->sections as $section)
                            <div data-repeater-item class="section-item border p-3 mb-3 shadow">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="section-heading mb-0"></h6>
                                    <button type="button"
                                        data-repeater-delete
                                        class="btn btn-soft-danger btn-icon btn-circle btn-sm remove-btn">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="id" value="{{ $section->id }}">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Title</label>
                                        <input type="text" name="section_title" class="form-control form-control-sm" value="{{ $section->title }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Image</label>
                                        <input type="file" name="section_image" class="form-control form-control-sm">
                                        @if(isset($section->image) && $section->image)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($section->image) }}" class="img-fit size-50px" alt="Variant Image">
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status</label>
                                        <select name="section_status" class="form-control form-control-sm">
                                            <option value="1" {{ $section->status == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ $section->status == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="section_description"
                                        class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ $section->description }}</textarea>
                                </div>
                            </div>

                            @empty
                            @include('backend.brands.partials.section')
                            @endforelse
                        </div>

                        <div class="text-right">
                            <button type="button" data-repeater-create class="btn btn-primary btn-xs my-2">
                                Add More Section
                            </button>
                        </div>
                    </div>

                    <!-- Brand Tabs -->
                    <h6 class="mb-0 mt-4">{{ trans('messages.brand') . ' ' . trans('messages.tabs') }}</h6>
                    <hr>
                    <div class="repeater" id="brand-tabs-repeater">
                        <div data-repeater-list="tabs">
                            @forelse($brand->tabs as $tab)
                            <div data-repeater-item class="border p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="tab-heading mb-0"></h6>
                                    <button type="button" data-repeater-delete class="btn btn-soft-danger btn-icon btn-circle btn-sm remove-btn">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="id" value="{{ $tab->id }}">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        <input type="text" name="tab_name" class="form-control form-control-sm" value="{{ $tab->name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Title</label>
                                        <input type="text" name="tab_title" class="form-control form-control-sm" value="{{ $tab->title }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Image</label>
                                        <input type="file" name="tab_image" class="form-control form-control-sm">
                                        @if(isset($tab->image) && $tab->image)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($tab->image) }}" class="img-fit size-50px" alt="Tab Image">
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status</label>
                                        <select name="tab_status" class="form-control form-control-sm">
                                            <option value="1" {{ $tab->status == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ $tab->status == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Sort Order</label>
                                        <input type="number" name="tab_sort_order" class="form-control form-control-sm" value="{{ $tab->sort_order }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="tab_description"
                                        class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ $tab->description }}</textarea>
                                </div>
                            </div>
                            @empty
                            @include('backend.brands.partials.tab')
                            @endforelse
                        </div>
                        <div class="text-right">
                            <button type="button" data-repeater-create class="btn btn-primary btn-xs my-2">Add More Tab</button>
                        </div>
                    </div>

                    <!-- Brand related products dropdown -->
                    <h6 class="mb-0 mt-4">Related Products</h6>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-12">
                            @php
                            $selectedProducts = old('products', json_decode($brand->products ?? '[]', true));
                            @endphp
                            <label>Products</label>
                            <select class="form-control form-control-sm aiz-selectpicker"
                                id="product_selector"
                                name="products[]"
                                multiple
                                data-live-search="true">

                                @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ in_array($product->id,$selectedProducts) ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <!-- Brand related products dropdown -->

                    <!-- SEO section starts -->
                    <h5 class="mb-0 h6">{{trans('messages.seo_section')}}</h5>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.meta_title')}}</label>
                            <input type="text" class="form-control form-control-sm" name="meta_title"
                                placeholder="{{trans('messages.meta_title')}}" value="{{ old('meta_title', $brand->getTranslation('meta_title', $lang)) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.meta_keywords')}}</label>
                            <input type="text" class="form-control form-control-sm" name="meta_keywords" placeholder="{{trans('messages.meta_keywords')}}" value="{{ old('meta_keywords', $brand->getTranslation('meta_keywords', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="name">{{trans('messages.meta_description')}}</label>
                            <textarea name="meta_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ old('meta_description', $brand->getTranslation('meta_description', $lang)) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.og_title')}}</label>
                            <input type="text" class="form-control form-control-sm" name="og_title" placeholder="{{trans('messages.og_title')}}" value="{{ old('og_title', $brand->getTranslation('og_title', $lang)) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.twitter_title')}}</label>
                            <input type="text" class="form-control form-control-sm" name="twitter_title" placeholder="{{trans('messages.twitter_title')}}" value="{{ old('twitter_title', $brand->getTranslation('twitter_title', $lang)) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.og_description')}}</label>
                            <textarea name="og_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ old('og_description', $brand->getTranslation('og_description', $lang)) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.twitter_description')}}</label>
                            <textarea name="twitter_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ old('twitter_description', $brand->getTranslation('twitter_description', $lang)) }}</textarea>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
    integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function title_update(e) {
        title = e.value;
        title = title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')
        $('#slug').val(title)
    }

    $(function() {
        function updateNumbers(repeaterSelector, headingClass) {
            $(repeaterSelector + ' [data-repeater-item]').each(function(index) {
                var label = (headingClass === '.section-heading') ? 'Section ' : 'Tab ';
                $(this).find(headingClass).text(label + (index + 1));
            });
        }

        /* SECTIONS REPEATER */
        var maxSections = 3;
        $('#brand-sections-repeater [data-repeater-create]').on('click', function(e) {
            var itemCount = $('#brand-sections-repeater').find('[data-repeater-item]').length;
            if (itemCount >= maxSections) {
                e.preventDefault();
                e.stopImmediatePropagation();
                AIZ.plugins.notify('warning', 'Maximum ' + maxSections + ' sections allowed.');
                return false;
            }
        });

        $('#brand-sections-repeater').repeater({
            show: function() {
                $(this).slideDown();
                updateNumbers('#brand-sections-repeater', '.section-heading');
            },
            hide: function(deleteElement) {
                $(this).slideUp(function() {
                    deleteElement();
                    updateNumbers('#brand-sections-repeater', '.section-heading');
                });
            }
        });

        updateNumbers('#brand-sections-repeater', '.section-heading');


        /* TABS REPEATER */
        var maxTabs = 5;
        $('#brand-tabs-repeater [data-repeater-create]').on('click', function(e) {
            var itemCount = $('#brand-tabs-repeater').find('[data-repeater-item]').length;
            if (itemCount >= maxTabs) {
                e.preventDefault();
                e.stopImmediatePropagation();
                AIZ.plugins.notify('warning', 'Maximum ' + maxTabs + ' tabs allowed.');
                return false;
            }
        });

        $('#brand-tabs-repeater').repeater({
            show: function() {
                $(this).slideDown();
                updateNumbers('#brand-tabs-repeater', '.tab-heading');
            },
            hide: function(deleteElement) {
                $(this).slideUp(function() {
                    deleteElement();
                    updateNumbers('#brand-tabs-repeater', '.tab-heading');
                });
            }
        });

        updateNumbers('#brand-tabs-repeater', '.tab-heading');

    });
</script>
@endsection