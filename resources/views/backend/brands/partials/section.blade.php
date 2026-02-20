<!-- Brand Sections -->
<div data-repeater-item class="section-item border p-3 mb-3 shadow">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="section-heading mb-0"></h6>
        <button type="button" data-repeater-delete class="btn btn-soft-danger btn-icon btn-circle btn-sm remove-btn">
            <i class="las la-trash"></i>
        </button>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Title</label>
            <input type="text" name="section_title" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
            <label>Image</label>
            <input type="file" name="section_image" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
            <label>Status</label>
            <select name="section_status" class="form-control form-control-sm">
                <option value="">Select Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <label>Description</label>
            <textarea name="section_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200"></textarea>
        </div>
    </div>
</div>
<!-- Brand section ends -->