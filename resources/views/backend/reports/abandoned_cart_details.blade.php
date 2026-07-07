@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Abandoned Cart Details</h1>
            </div>
            <div class="col text-right">
                <a class="btn btn-sm btn-primary" href="{{ Session::has('cart_last_url') ? Session::get('cart_last_url') : route('abandoned-cart.index') }}">
                    <i class="las la-angle-left"></i> Go Back
                </a>
            </div>
        </div>
    </div>

    <!-- Client Info Panel -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h2 class="h6 mb-0 font-weight-bold">Client & Session Information</h2>
        </div>
        <div class="card-body">
            @php
                $firstCart = $carts->first();
                $isReg = $firstCart && !empty($firstCart->user);
            @endphp
            @if ($firstCart)
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="pb-2 pl-0 font-weight-bold text-bold uppercase" width="35%">Name:</td>
                                <td class="pb-2">{{ $isReg ? $firstCart->user->name : 'Guest Client' }}</td>
                            </tr>
                            @if ($isReg)
                                <tr>
                                    <td class="pb-2 pl-0 font-weight-bold text-bold uppercase">Email:</td>
                                    <td class="pb-2"><a href="mailto:{{ $firstCart->user->email }}">{{ $firstCart->user->email }}</a></td>
                                </tr>
                                <tr>
                                    <td class="pb-2 pl-0 font-weight-bold text-bold uppercase">Phone:</td>
                                    <td class="pb-2">{{ $firstCart->user->phone ?? 'N/A' }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="pb-2 pl-0 font-weight-bold text-bold uppercase">Email:</td>
                                    <td class="pb-2">N/A</td>
                                </tr>
                                <tr>
                                    <td class="pb-2 pl-0 font-weight-bold text-bold uppercase">Phone:</td>
                                    <td class="pb-2">N/A</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6 border-md-left">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="pb-2 pl-0 pl-md-3 font-weight-bold text-bold uppercase" width="40%">Account Status:</td>
                                <td class="pb-2">
                                    @if ($isReg)
                                        <span class="badge badge-inline badge-soft-primary px-3 py-1">Registered User</span>
                                    @else
                                        <span class="badge badge-inline badge-soft-secondary px-3 py-1">Guest Client</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="pb-2 pl-0 pl-md-3 font-weight-bold text-bold uppercase">Total Items:</td>
                                <td class="pb-2 font-weight-bold">{{ $total_quantity }}</td>
                            </tr>
                            <tr>
                                <td class="pb-2 pl-0 pl-md-3 font-weight-bold text-bold uppercase">Potential Value:</td>
                                <td class="pb-2 font-weight-bold text-danger">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($total_price) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom-0 py-3">
            <h2 class="h6 mb-0 font-weight-bold">Cart Items</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th width="5%" class="text-center">#</th>
                            <th width="10%">Photo</th>
                            <th width="45%">Product Details</th>
                            <th class="text-center" width="15%">Date Added</th>
                            <th class="text-center" width="5%">Qty</th>
                            <th class="text-right" width="10%">Unit Price</th>
                            <th class="text-right" width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $key => $cart)
                            @php
                                $hasProduct = !empty($cart->product);
                            @endphp
                            <tr>
                                <td class="text-center align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">
                                    @if ($hasProduct)
                                        <img height="50" class="img-fit border rounded" src="{{ asset($cart->product->thumbnail_img) }}" alt="Product Image">
                                    @else
                                        <div class="bg-light border rounded text-center d-flex align-items-center justify-content-center" style="height: 50px; width: 50px;">
                                            <i class="las la-image  la-lg"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if ($hasProduct)
                                        <div class="font-weight-bold text-dark">{{ $cart->product->name }}</div>
                                        @if ($cart->variation)
                                            <small class=" d-block mt-1">Variant: {{ $cart->variation }}</small>
                                        @endif
                                    @else
                                        <div class=" font-italic font-weight-bold">Product Deleted</div>
                                    @endif
                                </td>
                                <td class="text-center align-middle ">
                                    {{ $cart->created_at->format('d-m-Y h:i:s A') }}
                                </td>
                                <td class="text-center align-middle font-weight-bold text-dark">
                                    {{ $cart->quantity }}
                                </td>
                                <td class="text-right align-middle font-weight-bold">
                                    {{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($cart->price) }}
                                </td>
                                <td class="text-right align-middle text-danger font-weight-bold">
                                    {{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($cart->price * $cart->quantity) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-light">
                            <td colspan="4"></td>
                            <td class="text-center font-weight-bold text-dark"><strong>{{ $total_quantity }}</strong></td>
                            <td class="text-right font-weight-bold text-dark"><strong>Grand Total:</strong></td>
                            <td class="text-right font-weight-bold text-danger"><strong>{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($total_price) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
