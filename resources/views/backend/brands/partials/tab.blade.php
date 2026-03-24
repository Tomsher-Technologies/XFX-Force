<!-- Brand Tabs -->
<div data-repeater-item class="tab-item border p-3 mb-3 shadow">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="tab-heading mb-0"></h6>
        <button type="button" data-repeater-delete class="btn btn-soft-danger btn-icon btn-circle btn-sm remove-btn">
            <i class="las la-trash"></i>
        </button>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Name</label>
            <input type="text" name="tab_name" class="form-control form-control-sm">
        </div>
        <div class="col-md-6">
            <label>Title</label>
            <input type="text" name="tab_title" class="form-control form-control-sm">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Image</label>
            <input type="file" name="tab_image" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
            <label>Status</label>
            <select name="tab_status" class="form-control form-control-sm">
                <option value="">Select Status</option>
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Sort Order</label>
            <input type="number" name="tab_sort_order" class="form-control form-control-sm" value="{{ old('sort_order',0) }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <label>Description</label>
            <textarea name="tab_description" class="aiz-text-editor form-control form-control-sm" data-min-height="200"></textarea>
        </div>
    </div>
</div>

<!-- Brand Tabs ends -->