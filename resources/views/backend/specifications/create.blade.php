<form method="POST" action="{{ route('specifications.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="main_title" class="form-control form-control-sm" placeholder="Title" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="display_title" class="form-control form-control-sm" placeholder="Display Title" required>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-control form-control-sm" required>
                <option value="">Select Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
            <button type="reset" class="btn btn-cancel btn-sm">Cancel</button>
        </div>
    </div>
</form>