@extends('backend.layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        <h5>{{ $attribute->name }}
            <span class="status-dot rounded-circle wh-12 d-inline-block
                {{ $attribute->is_active ? 'bg-success' : 'bg-danger' }}" title="{{ $attribute->is_active ? 'Active' : 'Inactive' }}">
            </span>
        </h5>
        <a href="{{ route('attributes.index', ['page' => request('page')]) }}"
            class="btn btn-secondary btn-sm" title="Back to list">
            <i class="las la-arrow-left"></i>
        </a>
    </div>
    <div class="card-body">
        <h6>Attribute Values</h6>
        <ul>
            @foreach($attribute->values as $value)
            <li>
                {{ $value->value }} -
                {{ $value->is_active ? 'Active' : 'Inactive' }}
            </li>
            @endforeach
        </ul>
    </div>
</div>

@endsection