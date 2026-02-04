@extends('backend.layouts.app')

@section('content')

<div class="col-lg-10 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ trans('messages.update_role_info')}}</h5>
        </div>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 col-from-label" for="name">{{ trans('messages.Role_Name')}}</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="{{ trans('messages.Role_Name')}}" value="{{ old('name', $role->name) }}" id="name" name="name" class="form-control">
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
               
                <div class="form-group row">
                    <label  class="col-md-3 col-from-label">Permissions</label>
                    <div class="col-md-9">
                        @foreach ($permission as $parent)
                            @php
                                $selected = '';
                                if (in_array($parent->id, old('permission', $rolePermissions))) {
                                    $selected = 'checked';
                                }
                            @endphp
                            <div class="col-sm-12 d-flex mt-2">
                                <div class="permission-group">

                                    <label class="parent-label custom-checkbox-label">
                                        <input type="checkbox" name="permissions[]"
                                            value="{{ $parent->name }}" {{ $selected }}
                                            class="parent-checkbox demo-sw mr-2"
                                            data-parent="{{ $parent->name }}">
                                        <span class="custom-checkmark"></span>
                                        {{ $parent->title }}
                                    </label>

                                    <div class="child-container mt-3" style="margin-left: 20px;">
                                        @foreach ($parent->children as $child)
                                            @php
                                                $selectedChild = '';
                                                if (
                                                    in_array(
                                                        $child->id,
                                                        old('permission', $rolePermissions),
                                                    )
                                                ) {
                                                    $selectedChild = 'checked';
                                                }
                                            @endphp

                                            <label class="custom-checkbox-label">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $child->name }}" {{ $selectedChild }}
                                                    class="child-checkbox"
                                                    data-parent="{{ $parent->name }}">
                                                <span class="custom-checkmark"></span>
                                                {{ $child->title }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @error('permissions')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">{{ trans('messages.Save')}}</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-cancel btn-sm">Cancel</a>
                </div>
            </div>
        </from>
    </div>
</div>

@endsection


@section('style')
    <style>
        .custom-checkbox-label {
            position: relative;
            padding-left: 28px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            user-select: none;
        }

        .custom-checkbox-label input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .custom-checkmark {
            position: absolute;
            left: 0;
            top: 2px;
            height: 15px;
            width: 15px;
            background-color: #ccc;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .custom-checkbox-label input:checked~.custom-checkmark {
            background-color: #08834a;
        }

        .custom-checkmark::after {
            content: "";
            position: absolute;
            display: none;
        }

        .custom-checkbox-label input:checked~.custom-checkmark::after {
            display: block;
            left: 6px;
            top: 2px;
            width: 4px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Style the checkbox container */
        .permission-group {
            /* margin-bottom: 15px; */
        }

        /* Style for parent checkboxes */
        .parent-label {
            font-weight: bold;
            /* font-size: 13px; */
            display: flex;
            align-items: center;
            /* margin-bottom: 5px; */
        }

        .child-label {
            display: flex;
            align-items: center;
        }

        /* Child checkboxes section */
        .child-container {
            margin-left: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Label hover effect */
        .checkbox-label:hover {
            color: #0958a3;
            cursor: pointer;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // When a child is checked/unchecked, update parent
            $('.child-checkbox').on('change', function() {
                let parentCheckbox = $('input[value="' + $(this).data('parent') + '"]');
                let allChildren = $('.child-checkbox[data-parent="' + $(this).data('parent') + '"]');
                let anyChecked = allChildren.is(':checked');

                parentCheckbox.prop('checked', anyChecked); // ✅ Check parent if any child is checked
            });

            // When a parent is checked/unchecked, update all children
            $('.parent-checkbox').on('change', function() {
                let allChildren = $('.child-checkbox[data-parent="' + $(this).data('parent') + '"]');
                allChildren.prop('checked', $(this).is(':checked')); // ✅ Check/uncheck all children
            });
        });
    </script>
@endsection