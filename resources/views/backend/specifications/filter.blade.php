<form method="GET" action="{{ route('specifications.index') }}">
    <div class="row">
        <div class="col-md-4">
            <input type="text"
                name="search"
                class="form-control form-control-sm"
                placeholder="Search title..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-4">
            <select name="status" class="form-control form-control-sm">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('specifications.index') }}" class="btn btn-cancel btn-sm">Reset</a>
        </div>
    </div>
</form>