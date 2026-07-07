@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="h3">Abandoned Cart Report</h1>
    </div>

    <!-- Metrics Row -->
    <div class="row gutters-10 mb-4">
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3 mb-lg-0">
            <div class="card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center">
                    <div class="size-50px rounded-circle d-flex align-items-center justify-content-center mr-3 bg-soft-primary text-primary">
                        <i class="las la-shopping-cart la-2x"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold fs-11">Total Carts</span>
                        <span class="h5 mb-0 fw-700">{{ $total_abandoned_carts }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3 mb-lg-0">
            <div class="card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center">
                    <div class="size-50px rounded-circle d-flex align-items-center justify-content-center mr-3 bg-soft-danger text-danger">
                        <i class="las la-money-bill-wave la-2x"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold fs-11">Potential Lost Revenue</span>
                        <span class="h5 mb-0 fw-700 text-danger">{{ single_price($total_lost_revenue) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3 mb-lg-0">
            <div class="card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center">
                    <div class="size-50px rounded-circle d-flex align-items-center justify-content-center mr-3 bg-soft-success text-success">
                        <i class="las la-user-check la-2x"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold fs-11">Registered Users</span>
                        <span class="h5 mb-0 fw-700">{{ $registered_carts }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3 mb-lg-0">
            <div class="card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center">
                    <div class="size-50px rounded-circle d-flex align-items-center justify-content-center mr-3 bg-soft-warning text-warning">
                        <i class="las la-user-tag la-2x"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase font-weight-bold fs-11">Guest Clients</span>
                        <span class="h5 mb-0 fw-700">{{ $guest_carts }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filters -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('abandoned-cart.index') }}" method="GET">
                <div class="row align-items-end gutters-10">
                    <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                        <label class="font-weight-bold  small uppercase">Start Date</label>
                        <input type="date" class="form-control form-control-sm" name="start_date" value="{{ $start_date }}">
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                        <label class="font-weight-bold  small uppercase">End Date</label>
                        <input type="date" class="form-control form-control-sm" name="end_date" value="{{ $end_date }}">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <button class="btn btn-warning btn-sm px-4" type="submit">Filter</button>
                        <a href="{{ route('abandoned-cart.index') }}" class="btn btn-light btn-sm px-4 border">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data List -->
    <div class="card shadow-sm border-0">
        <div class="card-header border-bottom-0 bg-transparent py-3">
            <h2 class="h6 mb-0 font-weight-bold">Abandoned Sessions</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th width="5%" class="text-center">#</th>
                            <th width="35%">User / Contact</th>
                            <th class="text-center" width="15%">Cart Type</th>
                            <th class="text-center" width="15%">Items Count</th>
                            <th class="text-center" width="15%">Potential Value</th>
                            <th width="15%">Last Active Date</th>
                            <th class="text-center" width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $key => $cart)
                            @php
                                $isRegistered = !empty($cart->user);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="align-middle">
                                    @if ($isRegistered)
                                        <div class="font-weight-bold text-dark">{{ $cart->user->name }}</div>
                                        <small class=" d-block">{{ $cart->user->email }} @if($cart->user->phone) | {{ $cart->user->phone }} @endif</small>
                                    @else
                                        <div class=" font-italic font-weight-bold">Guest Client</div>
                                        <small class=" d-block">Temp ID: {{ $cart->temp_user_id }}</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if ($isRegistered)
                                        <span class="badge badge-inline badge-soft-primary px-3 py-1">Registered</span>
                                    @else
                                        <span class="badge badge-inline badge-soft-secondary px-3 py-1">Guest</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle font-weight-bold text-dark">
                                    {{ $cart->total_quantity }}
                                </td>
                                <td class="text-center align-middle text-danger font-weight-bold">
                                    {{ env('DEFAULT_CURRENCY', 'AED')  }} {{ single_price($cart->total_price) }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ $cart->created_at->format('d-m-Y h:i:s A') }}
                                </td>
                                <td class="text-center align-middle">
                                    <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                        href="{{ route('abandoned-cart.view', $cart->id) }}"
                                        title="View items detail">
                                        <i class="las la-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="aiz-pagination mt-4">
                {{ $carts->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
