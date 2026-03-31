<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="card">
            <div class="card-header">

                <h6 class="mb-0">Details</h6>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Title</label>
                        <input type="text" name="details[title]" value="{{ $details['title'] ?? '' }}" class="form-control form-control-sm">
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
                            <input value="{{ old('image1', $details['image1'] ?? '') }}" type="hidden" name="details[image1]" class="selected-files"
                                required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Description</label>
                        <textarea name="details[description]" class="form-control" rows="5">{{ $details['description'] ?? '' }}</textarea>
                    </div>
                </div>

                <h6 class="mb-1"><u>Middle Section</u> </h6>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Main Heading</label>
                        <input type="text" name="details[mid_title]" value="{{ $details['mid_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Sub Heading</label>
                        <input type="text" name="details[mid_sub_title]" value="{{ $details['mid_sub_title'] ?? '' }}" class="form-control form-control-sm">
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
                            <input value="{{ old('image2', $details['image2'] ?? '') }}" type="hidden" name="details[image2]" class="selected-files"
                                required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Description</label>
                        <textarea name="details[mid_description]" class="form-control" rows="5">{{ $details['mid_description'] ?? '' }}</textarea>
                    </div>
                </div>


                <h6 class="mb-1"><u>Last Section</u> </h6>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Heading</label>
                        <input type="text" name="details[last_title]" value="{{ $details['last_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Description</label>
                        <textarea name="details[last_description]" class="form-control" rows="5">{{ $details['last_description'] ?? '' }}</textarea>
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
                            <input value="{{ old('image3', $details['image3'] ?? '') }}" type="hidden" name="details[image3]" class="selected-files"
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
                        <input type="text" name="details[section1_title]" value="{{ $details['section1_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Section 1 Description</label>
                        <textarea name="details[section1_description]" class="form-control" rows="3">{{ $details['section1_description'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Section 2 Heading</label>
                        <input type="text" name="details[section2_title]" value="{{ $details['section2_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Section 2 Description</label>
                        <textarea name="details[section2_description]" class="form-control" rows="3">{{ $details['section2_description'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Section 3 Heading</label>
                        <input type="text" name="details[section3_title]" value="{{ $details['section3_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Section 3 Description</label>
                        <textarea name="details[section3_description]" class="form-control" rows="3">{{ $details['section3_description'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>