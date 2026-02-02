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
        <ul>
            @foreach($items as $item)
            <li>
                {{ $item->title }}

                @if($item->subItems->count())
                <ul>
                    @foreach($item->subItems as $sub)
                    <li>{{ $sub->title }}</li>
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