@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h4">{{ trans('messages.banner').' '.trans('messages.information') }}</h5>
    </div>
    {{-- <div class="row"> --}}
        <div class="col-lg-8 mx-auto">
            <div class="card">
                
                <div class="card-body p-0">
                    
                    <form  class="p-4" method="POST" action="{{ route('banners.update', $banner) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="lang" value="{{ $lang }}">
                       
                        <div class="form-group row @if ($lang != 'en') d-none @endif">
                            <label class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Name"
                                    value="{{ old('name', $banner->name) }}" id="name" name="name"
                                    class="form-control form-control-sm" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">
                                Banner
                                {{-- <small>(1300x650)</small> --}}
                            </label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control form-control-sm file-amount">Choose File</div>
                                    <input value="{{ old('banner', $banner->image) }}" type="hidden" name="banner"
                                        class="selected-files" required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('banner')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row d-none">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">
                                Mobile Banner
                                {{-- <small>(1300x650)</small> --}}
                            </label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control form-control-sm file-amount">Choose File</div>
                                    <input value="{{ old('mobile_banner', $banner->mobile_image) }}" type="hidden"
                                        name="mobile_banner" class="selected-files" required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('mobile_banner')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Title" value="{{ old('title', $banner->title) }}" id="title"
                                    name="title" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Sub Title</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Sub Title" value="{{ old('sub_title', $banner->sub_title) }}" id="sub_title"
                                    name="sub_title" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Button Text</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Button Text" value="{{ old('btn_text', $banner->btn_text) }}" id="btn_text"
                                    name="btn_text" class="form-control form-control-sm">
                            </div>
                        </div>

                        
                            <div class="form-group row @if ($lang != 'en') d-none @endif">
                                <label class="col-md-3 col-form-label">Link Type</label>
                                <div class="col-md-9">
                                    <select onchange="banner_form()" class="form-control form-control-sm aiz-selectpicker" name="link_type"
                                        id="link_type" data-live-search="true" required>
                                        <option {{ old('link_type', $banner->link_type) == 'external' ? 'selected' : '' }}
                                            value="external">
                                            External
                                        </option>
                                        <option {{ old('link_type', $banner->link_type) == 'product' ? 'selected' : '' }}
                                            value="product">Product
                                        </option>
                                        <option {{ old('link_type', $banner->link_type) == 'category' ? 'selected' : '' }}
                                            value="category">
                                            Category</option>
                                        <option {{ old('link_type', $banner->link_type) == 'brand' ? 'selected' : '' }}
                                            value="brand">Brand</option>
                                    </select>
                                    @error('link_type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="banner_form" class="@if ($lang != 'en') d-none @endif">
                            </div>
                            @error('link')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @error('link_ref_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="form-group row @if ($lang != 'en') d-none @endif">
                                <label class="col-md-3 col-form-label">Status</label>
                                <div class="col-md-9">
                                    <select class="form-control form-control-sm aiz-selectpicker" name="status" id="status" required>
                                        <option {{ old('status', $banner->status) == '1' ? 'selected' : '' }} value="1">
                                            Enabled</option>
                                        <option {{ old('status', $banner->status) == '0' ? 'selected' : '' }} value="0">
                                            Disabled</option>
                                    </select>
                                    @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                       

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Save
                            </button>
                            <a href="{{ route('banners.index') }}" class="btn btn-cancel btn-sm">{{trans('messages.cancel')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- </div> --}}
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            banner_form();
        });

        function banner_form() {
            var link_type = $('#link_type').val();
            var link_error = "{{ $errors->getBag('default')->first('link') }}"
            var link_id_error = "{{ $errors->getBag('default')->first('link_ref_id') }}"
            var old_data = "{{ $banner->link ?? $banner->link_ref_id }}"
            $.post('{{ route('banners.get_form') }}', {
                _token: '{{ csrf_token() }}',
                link_type: link_type,
                old_data: old_data,
            }, function(data) {
                $('#banner_form').html(data);
            });
        }
    </script>
@endsection
