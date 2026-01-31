@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Specifications</h5>
        <button id="toggleForm" class="btn btn-primary btn-sm">
            Add Specification
        </button>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- CREATE FORM (HIDDEN INITIALLY) --}}
        <div id="createForm" style="display:none;" class="border p-3 mb-2">
            @include('backend.specifications.create')
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Display Title</th>
                    <th>Status</th>
                    <th width="10%">{{trans('messages.options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($specifications as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->main_title }}</td>
                    <td>{{ $row->display_title }}</td>
                    <td>
                        @if($row->status)
                        <span class="badge badge-success px-4 py-2">Active</span>
                        @else
                        <span class="badge badge-danger px-4 py-2">Inactive</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('specifications.viewSpecificationDetails', $row->id) }}"
                            class="btn btn-soft-primary btn-icon btn-circle">
                            <i class="las la-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('specifications.update',$row->id) }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="main_title" value="{{ $row->main_title }}">
                            <input type="hidden" name="display_title" value="{{ $row->display_title }}">
                            <input type="hidden" name="status" value="{{ $row->status }}">
                            <a href="{{ route('specifications.edit',$row->id) }}"
                                class="btn btn-soft-primary btn-icon btn-circle">
                                <i class="las la-edit"></i>
                            </a>
                        </form>
                        <form action="{{ route('specifications.destroy',$row->id) }}"
                            method="POST"
                            style="display:inline">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-soft-danger btn-icon btn-circle"
                                onclick="return confirm('Delete?')">
                                <i class="las la-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $specifications->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    $(function() {
        let visible = false;
        $("#toggleForm").on("click", function() {
            if (!visible) {
                $("#createForm").stop(true, true).slideDown(300);
                visible = true;
            } else {
                $("#createForm").stop(true, true).slideUp(300);
                visible = false;
            }
        });
    });
</script>
@endsection