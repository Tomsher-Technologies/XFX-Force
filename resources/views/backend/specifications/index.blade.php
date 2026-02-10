@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h5 class="h5">All Specifications</h5>
        </div>
        <div class="col text-right">
            <button id="toggleForm" class="btn btn-primary btn-sm">
                Add Specification
            </button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- CREATE FORM (HIDDEN INITIALLY) --}}
<div id="createForm" style="display:none;" class="mb-2">
    @include('backend.specifications.create')
</div>

<div class="card">
    <div class="card-header">
        <div class="w-100">
            @include('backend.specifications.filter')
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered aiz-table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Title</th>
                    <th>Display Title</th>
                    <th class="text-center">Status</th>
                    <th width="10%" class="text-center">{{trans('messages.options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($specifications as $key => $row)
                <tr>
                    <td class="text-center">{{ $key + 1 + ($specifications->currentPage() - 1) * $specifications->perPage() }}</td>
                    <td>{{ $row->main_title }}</td>
                    <td>{{ $row->display_title }}</td>
                    <td class="text-center">
                        @if($row->status)
                        <span class="badge badge-success px-4 py-2">Active</span>
                        @else
                        <span class="badge badge-danger px-4 py-2">Inactive</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2 text-center">
                        <a href="{{ route('specifications.show', [$row->id,'page' => request()->get('page')]) }}"
                            class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                            <i class="las la-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('specifications.update',$row->id) }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="main_title" value="{{ $row->main_title }}">
                            <input type="hidden" name="display_title" value="{{ $row->display_title }}">
                            <input type="hidden" name="status" value="{{ $row->status }}">
                            <a href="{{ route('specifications.edit', [$row->id,'page' => request()->get('page')]) }}"
                                class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                <i class="las la-edit"></i>
                            </a>
                        </form>
                        <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('specifications.delete',$row->id)}}" title="Delete">
                            <i class="las la-trash"></i>
                        </a>
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

@section('modal')
    @include('modals.delete_modal')
@endsection