@extends('backend.layouts.app')

@section('content')

@php
$editAttribute = session('editAttribute');
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h5 class="h5">All Attributes</h5>
        </div>
        @can('add_attribute')
        <div class="col text-right">
            <button id="toggleForm" class="btn btn-primary btn-sm" data-url="{{ route('attributes.create') }}">
                Add Attribute
            </button>
        </div>
        @endcan
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- CREATE FORM (HIDDEN INITIALLY) --}}
<div id="createForm" style="display:none">
    @include('backend.attribute.create')
</div>

<div class="card">
    <div class="card-header">
        <div class="w-100">
            @include('backend.attribute.filter')
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered aiz-table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Name</th>
                    <th class="text-center">Status</th>
                    <th width="10%" class="text-center">{{trans('messages.options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attributes as $key => $row)
                <tr>
                    <td class="text-center">{{ $key + 1 + ($attributes->currentPage() - 1) * $attributes->perPage() }}</td>
                    <td>{{ $row->name }}</td>
                    <td class="text-center">
                        @if($row->is_active)
                        <span class="badge badge-success px-4 py-2">Active</span>
                        @else
                        <span class="badge badge-danger px-4 py-2">Inactive</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2 text-center">
                        <a href="{{ route('attributes.show', [$row->id,'page' => request()->get('page')]) }}"
                            class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                            <i class="las la-eye"></i>
                        </a>
                        <form method="POST" action=" style=" display:inline;">
                            @csrf
                            <input type="hidden" name="main_title" value="{{ $row->main_title }}">
                            <input type="hidden" name="display_title" value="{{ $row->display_title }}">
                            <input type="hidden" name="status" value="{{ $row->status }}">
                            @can('edit_attribute')
                            <a href="javascript:void(0)"
                                data-url="{{ route('attributes.edit', [
                                        'attribute' => $row->id,
                                        'page' => request('page')
                                ]) }}"
                                class="btn btn-soft-primary btn-sm btn-circle btn-icon edit-btn">
                                <i class="las la-edit"></i>
                            </a>
                            @endcan
                        </form>
                        <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('attributes.delete',$row->id)}}" title="Delete">
                            <i class="las la-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $attributes->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')
<script>
    $(function() {
        toggleAddValueButtons();

        $("#toggleForm").on("click", function() {
            let url = $(this).data('url');
            $("#createForm").load(url, function() {
                $("#createForm").slideDown();
                toggleAddValueButtons();
            });

        });

        $(document).on('click', '#add-value', function() {

            let row = `
                <div class="row value-row mb-1">
                    <div class="col-md-4">
                        <input type="text"
                            name="values[]"
                            class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-2">
                        <select name="value_status[]" class="form-control form-control-sm" required>
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="remove-value border-0 bg-transparent mt-2">
                            <i class="las la-trash text-danger fs-20"></i>
                        </button>
                    </div>
                </div>`;

            $("#values-wrapper").append(row);
            toggleAddValueButtons();
        });

        $(document).on('click', '.edit-btn', function() {

            let url = $(this).data('url');

            $("#createForm").load(url, function() {
                $("#createForm").slideDown();

                $('html, body').animate({
                    scrollTop: $("#createForm").offset().top - 80
                }, 500);
                toggleAddValueButtons();
            });

        });

        $(document).on("click", ".remove-value", function() {
            $(this).closest(".value-row").remove();
            toggleAddValueButtons();
        });

        function toggleAddValueButtons() {

            let count = $("#values-wrapper .value-row").length;

            if (count === 0) {
                $(".add-value-top").removeClass('d-none');
                $(".add-value-bottom").addClass('d-none');
            } else {
                $(".add-value-top").addClass('d-none');
                $(".add-value-bottom").removeClass('d-none');
            }
        }

        $(document).on("click", "#cancelForm", function() {
            $("#createForm").find("form")[0].reset();
            $("#createForm").slideUp();
        });
    });
</script>
@endsection