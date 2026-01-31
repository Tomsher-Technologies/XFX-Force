@extends('backend.layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ $specification->display_title }}</h5>
        <a href="{{ route('specifications.index', ['page' => request('page')]) }}"
            class="btn btn-secondary btn-sm" title="Back to list">
            <i class="las la-arrow-left"></i>
        </a>
    </div>
    <div class="card-body">
        @if($items->isNotEmpty())
        <ul class="list-group">
            @foreach($items as $level1)
            <li class="list-group-item">
                <strong>{{ $level1->title }}</strong>
                @if($level1->subItems->isNotEmpty())
                <ul class="mt-2">
                    @foreach($level1->subItems as $level2)
                    <li>{{ $level2->title }}</li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
        @else
        <p>No specification items found.</p>
        @endif
    </div>
</div>
@endsection