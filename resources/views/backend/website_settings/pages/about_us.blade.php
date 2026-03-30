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
                                <label class="col-form-label">Image</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount form-control-sm">Choose File</div>
                                    <input value="{{ old('image1', $settings['image1'] ?? '') }}" type="hidden" name="image1" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Description</label>
                                <textarea name="description" class="form-control form-control-sm" rows="5">{{ $settings['description'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <h6 class="mb-1"><u>Middle Section</u> </h6>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Main Heading</label>
                                <input type="text" name="mid_title" value="{{ $settings['mid_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Sub Heading</label>
                                <input type="text" name="mid_sub_title" value="{{ $settings['mid_sub_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Image</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount form-control-sm">Choose File</div>
                                    <input value="{{ old('image2', $settings['image2'] ?? '') }}" type="hidden" name="image2" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Description</label>
                                <textarea name="mid_description" class="form-control" rows="5">{{ $settings['mid_description'] ?? '' }}</textarea>
                            </div>
                        </div>


                        <h6 class="mb-1"><u>Last Section</u> </h6>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Heading</label>
                                <input type="text" name="last_title" value="{{ $settings['last_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Description</label>
                                <textarea name="last_description" class="form-control" rows="5">{{ $settings['last_description'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Image</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount form-control-sm">Choose File</div>
                                    <input value="{{ old('image3', $settings['image3'] ?? '') }}" type="hidden" name="image3" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="mb-1"><u>Footer Section</u> </h6>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Section 1 Heading</label>
                                <input type="text" name="section1_title" value="{{ $settings['section1_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Section 1 Description</label>
                                <textarea name="section1_description" class="form-control" rows="3">{{ $settings['section1_description'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Section 2 Heading</label>
                                <input type="text" name="section2_title" value="{{ $settings['section2_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Section 2 Description</label>
                                <textarea name="section2_description" class="form-control" rows="3">{{ $settings['section2_description'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Section 3 Heading</label>
                                <input type="text" name="section3_title" value="{{ $settings['section3_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Section 3 Description</label>
                                <textarea name="section3_description" class="form-control" rows="3">{{ $settings['section3_description'] ?? '' }}</textarea>
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
