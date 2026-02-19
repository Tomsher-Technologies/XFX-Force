@extends('backend.layouts.app')

@section('content')
<style>
    .remove-attachment {
        display: none;
    }
</style>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ trans('messages.brand').' '.trans('messages.information') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Brand information -->
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">{{trans('messages.name')}}<span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{trans('messages.name')}}" id="name" name="name" class="form-control form-control-sm" onchange="title_update(this)" value="{{ old('name') }}">
                            @error('name')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">{{ trans('messages.slug') }}<span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{ trans('messages.slug') }}" id="slug" name="slug" class="form-control form-control-sm" value="{{ old('slug') }}">
                            @error('slug')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label">Log</label>
                            <input type="file" name="logo" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">{{ trans('messages.active_status') }}</label>
                            <select class="select2 form-control form-control-sm" name="is_active">
                                <option {{ old('status') == 1 ? 'selected' : '' }} value="1">{{ trans('messages.yes') }}
                                </option>
                                <option {{ old('status') == 0 ? 'selected' : '' }} value="0">{{ trans('messages.no') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Brand Information ends -->

                    <!-- Brand Sections -->
                    <h6 class="mb-0">{{ trans('messages.brand') . ' ' . trans('messages.sections') }}</h6>
                    <hr>
                    <div class="repeater" id="brand-sections-repeater">
                        <div data-repeater-list="sections">
                            @include('backend.brands.partials.section')
                        </div>
                        <div class="text-right">
                            <button type="button" data-repeater-create class="btn btn-primary btn-xs my-2">Add More Section</button>
                        </div>
                    </div>
                    <!-- Brand section ends -->
                    <!-- Brand Tabs -->
                    <h6 class="mb-0 mt-4">{{ trans('messages.brand') . ' ' . trans('messages.tabs') }}</h6>
                    <hr>
                    <div class="repeater" id="brand-tabs-repeater">
                        <div data-repeater-list="tabs">
                            @include('backend.brands.partials.tab')
                        </div>
                        <div class="text-right">
                            <button type="button" data-repeater-create class="btn btn-primary btn-xs my-2">Add More Tab</button>
                        </div>
                    </div>
                    <!-- Brand Tabs ends -->
                    <!-- SEO section starts -->
                    <h5 class="mb-0 h6">{{trans('messages.seo_section')}}</h5>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.meta_title')}}</label>
                            <input type="text" class="form-control form-control-sm" name="meta_title"
                                placeholder="{{trans('messages.meta_title')}}" value="{{ old('meta_title') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.meta_keywords')}}</label>
                            <input type="text" class="form-control form-control-sm" name="meta_keywords" placeholder="{{trans('messages.meta_keywords')}}" value="{{ old('meta_keywords') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label" for="name">{{trans('messages.meta_description')}}</label>
                            <textarea name="meta_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.og_title')}}</label>
                            <input type="text" class="form-control form-control-sm" name="og_title" placeholder="{{trans('messages.og_title')}}" value="{{ old('og_title') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.twitter_title')}}</label>
                            <input type="text" class="form-control form-control-sm" name="twitter_title" placeholder="{{trans('messages.twitter_title')}}" value="{{ old('twitter_title') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.og_description')}}</label>
                            <textarea name="og_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ old('og_description') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label" for="name">{{trans('messages.twitter_description')}}</label>
                            <textarea name="twitter_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200">{{ old('twitter_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">{{ trans('messages.Save') }}</button>
                        <a href="{{ route('brands.index') }}" class="btn btn-cancel btn-sm">{{trans('messages.cancel')}}</a>
                    </div>
                </form>
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

                    if (index === 0) {
                        $(this).find('[data-repeater-delete]').hide();
                    } else {
                        $(this).find('[data-repeater-delete]').show();
                    }
                });
            }

            /*  SECTIONS REPEATER */

            var maxSections = 3;
            $('#brand-sections-repeater [data-repeater-create]').on('click', function(e) {
                var itemCount = $('#brand-sections-repeater')
                    .find('[data-repeater-item]').length;
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
                    
                    // Clear inputs in the new section
                    $(this).find('.note-editor').remove();
                    $(this).find('textarea').val('');
                    AIZ.plugins.textEditor();

                    updateNumbers('#brand-sections-repeater', '.section-heading');
                },
                hide: function(deleteElement) {
                    if (confirm("Are you sure you want to remove this section?")) {
                        $(this).slideUp(deleteElement);
                        updateNumbers('#brand-sections-repeater', '.section-heading');
                    }
                }
            });

            updateNumbers('#brand-sections-repeater', '.section-heading');


            /* TABS REPEATER */

            var maxTabs = 5;

            $('#brand-tabs-repeater [data-repeater-create]').on('click', function(e) {
                var itemCount = $('#brand-tabs-repeater')
                    .find('[data-repeater-item]').length;

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

                    // Clear inputs in the new section
                    $(this).find('.note-editor').remove();
                    $(this).find('textarea').val('');
                    AIZ.plugins.textEditor();
                    
                    updateNumbers('#brand-tabs-repeater', '.tab-heading');
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                    updateNumbers('#brand-tabs-repeater', '.tab-heading');
                }
            });

            updateNumbers('#brand-tabs-repeater', '.tab-heading');

        });
    </script>
    @endsection