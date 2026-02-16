@extends('backend.layouts.app', ['title' => 'Edit Testimonial'])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
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
                                <input type="text" name="name" class="form-control" value="{{ old('name', $testimonial->name) }}" required>
                                @error('name')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Sub Title</label>
                                <input type="text" name="sub_title" class="form-control" value="{{ old('sub_title', $testimonial->sub_title) }}">
                                @error('sub_title')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="text" {{ old('type', $testimonial->type) == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="video" {{ old('type', $testimonial->type) == 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                                @error('type')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $testimonial->sort_order) }}">
                                    @error('sort_order')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="col-from-label">Status</label>
                                <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" value="1" {{ old('status', $testimonial->status) == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                @error('status')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Text content -->
                        <div class="form-group" id="text-group">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control" rows="6">{{ old('comment', $testimonial->comment) }}</textarea>
                            @error('comment')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Video upload -->
                        <div class="form-group" id="video-group" style="display:none;">
                            <label>Upload Video</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="video_file" name="video_file" accept="video/*">
                                <label class="custom-file-label" for="video_file" id="video_filename">
                                    {{ $testimonial->video_path ? basename($testimonial->video_path) : 'Choose video file' }}
                                </label>
                            </div>
                            @error('video_file')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror

                            @if($testimonial->video_path)
                            <small class="d-block mt-2">Current Video:
                                <a href="{{ asset('storage/' . $testimonial->video_path) }}" target="_blank">View</a>
                            </small>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">Cancel</a>
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
        function toggleFields() {
            let type = $('#type').val();
            if (type === 'text') {
                $('#text-group').show();
                $('#video-group').hide();
            } else {
                $('#text-group').hide();
                $('#video-group').show();
            }
        }

        // Initial toggle
        toggleFields();

        // On type change
        $('#type').on('change', toggleFields);

        // Show selected video file name
        $('#video_file').on('change', function() {
            let fileName = this.files[0] ? this.files[0].name : 'Choose video file';
            $('#video_filename').text(fileName);
        });
    });
</script>
@endsection