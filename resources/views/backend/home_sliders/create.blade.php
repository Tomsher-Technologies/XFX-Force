@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Create Slider</h5>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('home-slider.store') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-sm" placeholder="Name" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Slider Type --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Slider Type</label>
                            <div class="col-md-9">
                                <select name="slider_type" id="slider_type" onchange="toggleSliderType()"
                                    class="form-control form-control-sm aiz-selectpicker" required>
                                    <option value="image" {{ old('slider_type', 'image') == 'image' ? 'selected' : '' }}>
                                        Image
                                    </option>
                                    <option value="video" {{ old('slider_type') == 'video' ? 'selected' : '' }}>
                                        Video
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- IMAGE FIELDS --}}
                        <div id="imageFields">

                            {{-- Banner --}}
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Banner</label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">Browse</div>
                                        </div>
                                        <div class="form-control form-control-sm file-amount">Choose File</div>
                                        <input type="hidden" name="banner" value="{{ old('banner') }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                    @error('banner')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Mobile Banner --}}
                            <div class="form-group row d-none">
                                <label class="col-md-3 col-form-label">Mobile Banner</label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">Browse</div>
                                        </div>
                                        <div class="form-control form-control-sm file-amount">Choose File</div>
                                        <input type="hidden" name="mobile_banner" value="{{ old('mobile_banner') }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                    @error('mobile_banner')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        {{-- VIDEO FIELDS --}}
                        <div id="videoFields" class="d-none">

                            {{-- Website Video --}}
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Video (Website)</label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="video">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">Browse</div>
                                        </div>
                                        <div class="form-control form-control-sm file-amount">Choose File</div>
                                        <input type="hidden" name="video" value="{{ old('video') }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                    @error('video')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Mobile Video --}}
                            <div class="form-group row d-none">
                                <label class="col-md-3 col-form-label">Video (Mobile)</label>
                                <div class="col-md-9">
                                    <div class="input-group" data-toggle="aizuploader" data-type="video">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary">Browse</div>
                                        </div>
                                        <div class="form-control form-control-sm file-amount">Choose File</div>
                                        <input type="hidden" name="mobile_video" value="{{ old('mobile_video') }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                    @error('mobile_video')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title</label>
                            <div class="col-md-9">
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control form-control-sm" placeholder="Title" >
                                @error('title')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row d-none">
                            <label class="col-md-3 col-form-label">Subtitle</label>
                            <div class="col-md-9">
                                <input type="text" name="sub_title" value="{{ old('sub_title') }}"
                                    class="form-control form-control-sm" placeholder="Subtitle" >
                                @error('sub_title')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Button Text (COMMON) --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Button Text</label>
                            <div class="col-md-9">
                                <input type="text" name="btn_text" value="{{ old('btn_text') }}"
                                    placeholder="e.g. Shop Now" class="form-control form-control-sm">
                            </div>
                        </div>

                        {{-- Link Type --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Link Type</label>
                            <div class="col-md-9">
                                <select name="link_type" id="link_type" onchange="banner_form()"
                                    class="form-control form-control-sm aiz-selectpicker" required>
                                    <option value="external">External</option>
                                    <option value="product">Product</option>
                                    <option value="category">Category</option>
                                    <option value="brand">Brand</option>
                                </select>
                            </div>
                        </div>

                        <div id="banner_form"></div>

                        {{-- Sort Order --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Sort Order</label>
                            <div class="col-md-9">
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                    class="form-control form-control-sm" >
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9">
                                <select name="status" class="form-control form-control-sm aiz-selectpicker" required>
                                    <option value="1">Enabled</option>
                                    <option value="0">Disabled</option>
                                </select>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group text-right mb-0">
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            <a href="{{ route('home-slider.index') }}" class="btn btn-cancel btn-sm">
                                {{ trans('messages.cancel') }}
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            toggleSliderType();
            banner_form();
        });

        function toggleSliderType() {
            let type = $('#slider_type').val();

            if (type === 'video') {
                $('#imageFields').addClass('d-none');
                $('#videoFields').removeClass('d-none');
            } else {
                $('#videoFields').addClass('d-none');
                $('#imageFields').removeClass('d-none');
            }
        }

        function banner_form() {
            let link_type = $('#link_type').val();
            $.post('{{ route('banners.get_form') }}', {
                _token: '{{ csrf_token() }}',
                link_type: link_type
            }, function(data) {
                $('#banner_form').html(data);
            });
        }
    </script>
@endsection
