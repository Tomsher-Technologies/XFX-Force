@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">Edit Slider</h5>
            </div>

            <div class="card-body">
                <form class="form-horizontal" method="POST"
                    action="{{ route('home-slider.update', $homeSlider->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md-9">
                            <input type="text" name="name"
                                value="{{ old('name', $homeSlider->name) }}"
                                class="form-control form-control-sm" required>
                        </div>
                    </div>

                    {{-- Slider Type --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Slider Type</label>
                        <div class="col-md-9">
                            <select name="slider_type" id="slider_type"
                                onchange="toggleSliderType()"
                                class="form-control form-control-sm aiz-selectpicker" required>
                                <option value="image"
                                    {{ old('slider_type', $homeSlider->slider_type) == 'image' ? 'selected' : '' }}>
                                    Image
                                </option>
                                <option value="video"
                                    {{ old('slider_type', $homeSlider->slider_type) == 'video' ? 'selected' : '' }}>
                                    Video
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- IMAGE FIELDS --}}
                    <div id="imageFields">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Banner</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">Browse</div>
                                    </div>
                                    <div class="form-control form-control-sm file-amount">Choose File</div>
                                    <input type="hidden" name="banner"
                                        value="{{ old('banner', $homeSlider->image) }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Mobile Banner</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">Browse</div>
                                    </div>
                                    <div class="form-control form-control-sm file-amount">Choose File</div>
                                    <input type="hidden" name="mobile_banner"
                                        value="{{ old('mobile_banner', $homeSlider->mobile_image) }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                    </div>

                    {{-- VIDEO FIELDS --}}
                    <div id="videoFields" class="d-none">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video (Website)</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="video">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">Browse</div>
                                    </div>
                                    <div class="form-control form-control-sm file-amount">Choose File</div>
                                    <input type="hidden" name="video"
                                        value="{{ old('video', $homeSlider->video) }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video (Mobile)</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="video">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">Browse</div>
                                    </div>
                                    <div class="form-control form-control-sm file-amount">Choose File</div>
                                    <input type="hidden" name="mobile_video"
                                        value="{{ old('mobile_video', $homeSlider->mobile_video) }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Title</label>
                        <div class="col-md-9">
                            <input type="text" name="title"
                                value="{{ old('title', $homeSlider->title) }}"
                                class="form-control form-control-sm" required>
                        </div>
                    </div>

                    {{-- Subtitle --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Subtitle</label>
                        <div class="col-md-9">
                            <input type="text" name="sub_title"
                                value="{{ old('sub_title', $homeSlider->sub_title) }}"
                                class="form-control form-control-sm" required>
                        </div>
                    </div>

                    {{-- Button Text --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Button Text</label>
                        <div class="col-md-9">
                            <input type="text" name="btn_text"
                                value="{{ old('btn_text', $homeSlider->btn_text) }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>

                    {{-- Link Type --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Link Type</label>
                        <div class="col-md-9">
                            <select name="link_type" id="link_type"
                                onchange="banner_form()"
                                class="form-control form-control-sm aiz-selectpicker">
                                <option value="external" {{ $homeSlider->link_type == 'external' ? 'selected' : '' }}>External</option>
                                <option value="product" {{ $homeSlider->link_type == 'product' ? 'selected' : '' }}>Product</option>
                                <option value="category" {{ $homeSlider->link_type == 'category' ? 'selected' : '' }}>Category</option>
                                <option value="brand" {{ $homeSlider->link_type == 'brand' ? 'selected' : '' }}>Brand</option>
                            </select>
                        </div>
                    </div>

                    <div id="banner_form"></div>

                    {{-- Sort Order --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Sort Order</label>
                        <div class="col-md-9">
                            <input type="number" name="sort_order"
                                value="{{ old('sort_order', $homeSlider->sort_order) }}"
                                class="form-control form-control-sm" required>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Status</label>
                        <div class="col-md-9">
                            <select name="status" class="form-control form-control-sm aiz-selectpicker">
                                <option value="1" {{ $homeSlider->status == 1 ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ $homeSlider->status == 0 ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="form-group text-right mb-0">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        <a href="{{ route('home-slider.index') }}" class="btn btn-cancel btn-sm">
                            Cancel
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
    $(document).ready(function () {
        toggleSliderType();
        banner_form();
    });

    function toggleSliderType() {
        let type = $('#slider_type').val();
        $('#imageFields').toggleClass('d-none', type === 'video');
        $('#videoFields').toggleClass('d-none', type === 'image');
    }

    function banner_form() {
            var link_type = $('#link_type').val();
            var link_error = "{{ $errors->getBag('default')->first('link') }}"
            var link_id_error = "{{ $errors->getBag('default')->first('link_ref_id') }}"
            var old_data = "{{ $homeSlider->link ?? $homeSlider->link_ref_id }}"
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