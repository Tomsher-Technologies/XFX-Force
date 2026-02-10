<form method="POST"
    action="{{ isset($attribute) 
            ? route('attributes.update',$attribute->id)
            : route('attributes.store') }}">

    @csrf
    @if(isset($attribute))
    @method('PUT')
    @endif
    <input type="hidden" name="page" value="{{ request('page') }}">
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text"
                name="name"
                class="form-control form-control-sm"
                value="{{ $attribute->name ?? '' }}"
                placeholder="Attribute Name"
                required>
        </div>

        <div class="col-md-2">
            <select name="is_active" class="form-control form-control-sm">
                <option value="">Select Status</option>
                <option value="1" {{ isset($attribute) && $attribute->is_active ? 'selected':'' }}>Active</option>
                <option value="0" {{ isset($attribute) && !$attribute->is_active ? 'selected':'' }}>Inactive</option>
            </select>
        </div>
    </div>

    {{-- VALUES --}}
    <div class="row">
        <div class="col-md-4">
            <h6>Attribute Values</h6>
        </div>
        <div class="col-md-2 text-right add-value-top d-none">
            <button type="button" id="add-value" class="btn btn-success btn-xs mb-2">
                Add Value
            </button>
        </div>
    </div>

    <div id="values-wrapper">

        @if(isset($attribute))
        @foreach($attribute->values as $val)
        <div class="row value-row mb-1">
            <div class="col-md-4">
                <input type="text"
                    name="values[]"
                    value="{{ $val->value }}"
                    class="form-control form-control-sm" required>
            </div>

            <div class="col-md-2">
                <select name="value_status[]" class="form-control form-control-sm" required>
                    <option value="1" {{ $val->is_active ? 'selected':'' }}>Active</option>
                    <option value="0" {{ !$val->is_active ? 'selected':'' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="button" class="remove-value border-0 bg-transparent mt-2">
                    <i class="las la-trash text-danger fs-20"></i>
                </button>
            </div>
        </div>
        @endforeach
        @endif

    </div>
    <div class="row add-value-bottom d-none">
        <div class="col-md-6 text-right">
            <button type="button" id="add-value" class="btn btn-success btn-xs mb-2">
                Add Value
            </button>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-md-12  text-right">
            <button type="submit" class="btn btn-primary btn-sm">
                {{ isset($attribute) ? 'Update' : 'Save' }}
            </button>
            <button type="button" id="cancelForm" class="btn btn-cancel btn-sm">
                Cancel
            </button>
        </div>
    </div>
</form>