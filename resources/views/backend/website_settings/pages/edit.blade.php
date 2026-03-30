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
                            <label class="col-form-label">Title</label>
                            <input type="text" name="title" value="{{ $settings['title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Description</label>
                            <textarea name="description" class="aiz-text-editor form-control form-control-sm" data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" >{{ $settings['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        
          
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Seo Details</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Title</label>
                            <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Description</label>
                            <textarea name="meta_description" class="form-control" rows="5">{{ $settings['meta_description'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Keywords</label>
                            <input type="text" name="keywords" value="{{ $settings['keywords'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">OG Title</label>
                            <input type="text" name="og_title" value="{{ $settings['og_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">OG Description</label>
                            <textarea name="og_description" class="form-control" rows="5">{{ $settings['og_description'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Twitter Title</label>
                            <input type="text" name="twitter_title" value="{{ $settings['twitter_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-form-label">Twitter Description</label>
                            <textarea name="twitter_description" class="form-control" rows="5">{{ $settings['twitter_description'] ?? '' }}</textarea>
                        </div>
                    </div>

                </div>
            </div>


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
