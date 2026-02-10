@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h5 class="h5">{{trans('messages.all_roles')}}</h5>
		</div>
		<div class="col-md-6 text-md-right">
            @can('add_role')
                <a href="{{ route('roles.create') }}" class="btn btn-circle btn-info btn-sm">
                    <span>{{trans('messages.add_new_role')}}</span>
                </a>
            @endcan
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{trans('messages.roles')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th>{{trans('messages.Role_Name')}}</th>
                    <th class="text-center" width="">{{trans('messages.options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $key => $role)
                    <tr>
                        <td>{{ ($key+1) + ($roles->currentPage() - 1)*$roles->perPage() }}</td>
                        <td>{{ $role->name}}</td>
                        <td class="text-center">
                            @can('edit_role')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('roles.edit', ['id'=>$role->id] )}}" title="{{ trans('messages.edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endcan

                            @can('delete_role')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle confirm-delete  btn-sm" data-href="{{route('roles.destroy', $role->id)}}" title="{{ trans('messages.delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $roles->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
