<form method="GET" action="{{ route('attributes.index') }}">
    <div class="row">
        <div class="col-md-4">
            <input type="text"
                name="search"
                class="form-control form-control-sm"
                placeholder="Search name..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-4">
            <select name="is_active" class="form-control form-control-sm" ab="{{ request('is_active')}}">
                <option value="">All Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('attributes.index') }}" class="btn btn-cancel btn-sm">Reset</a>
        </div>
    </div>
</form>