@extends('backend.layouts.app', ['title' => 'Create Testimonial'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 h6">Create Testimonial</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-8">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}">
                                @error('name')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Sub Title</label>
                                <input type="text" name="sub_title" class="form-control form-control-sm" value="{{ old('sub_title') }}">
                                @error('sub_title')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Type</label>
                                <select name="type" id="type" class="form-control form-control-sm">
                                    <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                                @error('type')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control form-control-sm"
                                    value="{{ old('sort_order') }}">
                                @error('sort_order')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="col-from-label">{{ trans('messages.status') }}</label>
                                <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" value="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            @error('status')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Text content -->
                        <div class="form-group" id="text-group">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control form-control-sm" rows="6">{{ old('comment') }}</textarea>
                            @error('comment')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Video section -->
                        <div id="video-section" style="display:none;">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>Video Source</label>
                                    <select name="video_source" id="video_source" class="form-control form-control-sm">
                                        <option value="">Select Source</option>
                                        <option value="upload" {{ old('video_source') == 'upload' ? 'selected' : '' }}>Upload From Device</option>
                                        <option value="youtube" {{ old('video_source') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                    </select>
                                    @error('video_source')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Normal Upload -->
                                <div class="col-md-6" id="normal-video-group" style="display:none;">
                                    <label>Upload Video</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="video_path" name="video_path" accept="video/*">
                                        <label class="custom-file-label" for="video_path" id="video_pathname">Choose video file</label>
                                    </div>
                                    @error('video_path')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- YouTube Fields -->
                            <div id="youtube-group" style="display:none;">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>YouTube Video Link</label>
                                        <input type="text" name="youtube_link" class="form-control form-control-sm" value="{{ old('youtube_link') }}">
                                        @error('youtube_link')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label>YouTube Embed Link</label>
                                        <input type="text" name="youtube_embed" class="form-control form-control-sm" value="{{ old('youtube_embed') }}">
                                        @error('youtube_embed')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>



                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
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
    $(function() {

        function toggleType() {
            let type = $('#type').val();

            if (type === 'text') {
                $('#text-group').show();
                $('#video-section').hide();
            } else {
                $('#text-group').hide();
                $('#video-section').show();
            }
        }

        function toggleVideoSource() {
            let source = $('#video_source').val();

            if (source === 'upload') {
                $('#normal-video-group').show();
                $('#youtube-group').hide();
            } else if (source === 'youtube') {
                $('#normal-video-group').hide();
                $('#youtube-group').show();
            } else {
                $('#normal-video-group').hide();
                $('#youtube-group').hide();
            }
        }

        toggleType();
        toggleVideoSource();

        $('#type').on('change', toggleType);
        $('#video_source').on('change', toggleVideoSource);

        $('#video_path').on('change', function() {
            let fileName = this.files[0] ? this.files[0].name : 'Choose video file';
            $('#video_pathname').text(fileName);
        });

    });
</script>

@endsection