@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Edit {{ $page->slug }} Page Information</h1>
            </div>
        </div>
    </div>
    @php
        $settings = $page ? json_decode($page->data, true) : [];
    @endphp
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
            
                <!-- Featured Custom Pre - Build Gaming PCs (Middle featured Products)-->
                <div class="card">
                    <div class="card-header">
                        <input type="hidden" name="type" value="{{ $page->type }}">
                        <h6 class="mb-0">Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Categories Title</label>
                                <input type="text" name="title" value="{{ $settings['title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Categories</label>
                                <select name="categories[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Categories" data-selected="{{ json_encode($settings['categories'] ?? []) }}">
                                    @foreach ($categories as $key => $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Products Title</label>
                                <input type="text" name="listing_title" value="{{ $settings['listing_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Ad Banner</label>
                                <select name="banners[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Banners" data-selected="{{ json_encode($settings['banners'] ?? []) }}" data-max-options="3">
                                    @foreach ($banners as $key => $banner)
                                    <option value="{{ $banner->id }}">{{ $banner->name }}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>

                    </div>
                </div>
            
            
                @include('backend.inc.page_seo', ['settings' => $settings])


                <div class="text-right mb-2">
                    <button type="submit" class="btn btn-info btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            var lang = '{{ $lang }}';
        
            if(lang == 'ae'){
                setEditorDirection(true);
            }else{
                setEditorDirection(false);
            }
            function setEditorDirection(isRtl) {
                const editor = $('.aiz-text-editor').next('.note-editor').find('.note-editable');
                editor.attr('dir', isRtl ? 'rtl' : 'ltr'); // Set direction
                editor.css('text-align', isRtl ? 'right' : 'left');
            }
        });
    </script>

@endsection
