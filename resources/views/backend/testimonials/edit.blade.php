@extends('backend.layouts.app', ['title' => 'Edit Testimonial'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 h6">Edit Testimonial</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('testimonials.update', $testimonial) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row">
                            <div class="col-md-8">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $testimonial->name) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Sub Title</label>
                                <input type="text" name="sub_title" class="form-control"
                                    value="{{ old('sub_title', $testimonial->sub_title) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="text"
                                        {{ old('type', $testimonial->type) == 'text' ? 'selected' : '' }}>
                                        Text
                                    </option>
                                    <option value="video"
                                        {{ old('type', $testimonial->type) == 'video' ? 'selected' : '' }}>
                                        Video
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $testimonial->sort_order) }}">
                            </div>

                            <div class="col-md-4">
                                <label>Status</label>
                                <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" value="1"
                                        {{ old('status', $testimonial->status) == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <!-- TEXT SECTION -->
                        <div class="form-group" id="text-group">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control"
                                rows="6">{{ old('comment', $testimonial->comment) }}</textarea>
                        </div>

                        <!-- VIDEO SECTION -->
                        <div id="video-group" style="display:none;">
                            <div class="form-group row">
                                <!-- Video Source -->
                                <div class="col-md-6">
                                    <label>Video Source</label>
                                    <select name="video_source" id="video_source" class="form-control">
                                        <option value="upload"
                                            {{ old('video_source', $testimonial->video_source) == 'upload' ? 'selected' : '' }}>
                                            Upload From Device
                                        </option>
                                        <option value="youtube"
                                            {{ old('video_source', $testimonial->video_source) == 'youtube' ? 'selected' : '' }}>
                                            YouTube
                                        </option>
                                    </select>
                                </div>

                                <!-- NORMAL UPLOAD -->
                                <div class="col-md-6" id="normal-video-group">
                                    <label>Upload Video</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="video_path" name="video_path" accept="video/*">
                                        <label class="custom-file-label" for="video_path" id="video_pathname">
                                            {{ $testimonial->video_path ? basename($testimonial->video_path) : 'Choose video file' }}
                                        </label>
                                    </div>
                                    @error('video_path')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror

                                    @if($testimonial->video_path)
                                    <small class="d-block mt-2">Current Video:
                                        <a href="{{ asset('storage/' . $testimonial->video_path) }}" target="_blank">View</a>
                                    </small>
                                    @endif
                                </div>
                            </div>
                            

                            <!-- YOUTUBE FIELDS -->
                            <div id="youtube-group" style="display:none;">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>YouTube Common Link</label>
                                        <input type="text" name="common_link" class="form-control"
                                            value="{{ old('common_link', $testimonial->common_link) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label>YouTube Embed Link</label>
                                        <input type="text" name="embed_link" class="form-control"
                                            value="{{ old('embed_link', $testimonial->embed_link) }}">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        <a href="{{ route('testimonials.index') }}" class="btn btn-secondary btn-sm">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {

    function toggleMainType() {
        let type = $('#type').val();
        if (type === 'text') {
            $('#text-group').show();
            $('#video-group').hide();
        } else {
            $('#text-group').hide();
            $('#video-group').show();
            toggleVideoSource();
        }
    }

    function toggleVideoSource() {
        let source = $('#video_source').val();
        if (source === 'upload') {
            $('#normal-video-group').show();
            $('#youtube-group').hide();
        } else {
            $('#normal-video-group').hide();
            $('#youtube-group').show();
        }
    }

    toggleMainType();
    toggleVideoSource();

    $('#type').on('change', toggleMainType);
    $('#video_source').on('change', toggleVideoSource);

    // Show selected video file name
    $('#video_path').on('change', function() {
        let fileName = this.files[0] ? this.files[0].name : 'Choose video file';
        $('#video_pathname').text(fileName);
    });
});
</script>
@endsection
