@extends('backend.layouts.app')

@section('style')
<style>
    /* Pulsing status dot styling */
    .pulse-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        animation: status-pulse 1.5s infinite;
    }
    @keyframes status-pulse {
        0% { opacity: 0.4; transform: scale(0.9); }
        50% { opacity: 1; transform: scale(1.1); }
        100% { opacity: 0.4; transform: scale(0.9); }
    }
    .badge-inline i {
        vertical-align: middle;
        position: relative;
        top: -1px;
    }
    
    /* Timeline style */
    .timeline {
        position: relative;
        padding-left: 25px;
        margin: 0;
        list-style: none;
    }
    .timeline::before {
        content: "";
        position: absolute;
        left: 5px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: #ebedf2;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-badge {
        position: absolute;
        left: -25px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #3b82f6;
        z-index: 1;
    }
    .timeline-badge.badge-success { border-color: #2ec4b6; }
    .timeline-badge.badge-danger { border-color: #ef4444; }
    .timeline-badge.badge-warning { border-color: #f59e0b; }
    .timeline-badge.badge-info { border-color: #3b82f6; }
    
    .timeline-card {
        padding: 16px 20px;
        border-radius: 8px;
        background: #fdfdfd;
        border: 1px solid #eef0f5;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
    }
    .timeline-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: #e2e8f0;
    }
</style>
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-info" href="{{ Session::has('last_url') ? Session::get('last_url') : route('all_orders.index') }}" >Go Back</a>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                    <h6 class="mb-1 fw-600">Billing Address</h6>
                    @php
                        $billing_address = json_decode($order->billing_address);
                    @endphp
                    @if ($billing_address)
                        <address>
                            <span>{{ $billing_address->name }}</span><br>
                            {{ $billing_address->email }}<br>
                            {{ $billing_address->phone }}<br>
                            {{ $billing_address->address }},
                            {{ $billing_address->city }}, {{ $billing_address->state }}, {{ $billing_address->country }},
                            
                        </address>
                    @endif
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                @endphp

               

                <div class="col-md-3 ml-auto">
                    <label for="update_payment_status">Payment Status</label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                        id="update_payment_status">
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>Unpaid
                        </option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>Paid
                        </option>
                    </select>
                </div>
                <div class="col-md-3 ml-auto">
                    <label for="update_delivery_status">Delivery Status</label>
                    {{-- @if ($delivery_status != 'delivered' && $delivery_status != 'cancelled') --}}
                        <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                            id="update_delivery_status">
                            <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                Pending</option>
                            <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                Confirmed</option>
                            <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                Picked Up</option>
                            <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                On The Way</option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                Delivered</option>
                            <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                Cancel</option>
                        </select>
                    {{-- @else
                        <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                    @endif --}}
                </div>
                <div class="col-md-3 ml-auto d-none">
                    <label for="update_tracking_code">Tracking Code (optional)</label>
                    <input type="text" class="form-control" id="update_tracking_code"
                        value="{{ $order->tracking_code }}">
                </div>
            </div>
            <div class="mb-3">
                
            </div>
            <div class="row gutters-5">
                <div class="col-sm-12 col-md-6 text-md-left">

                    @if($order->shipping_type == "pickup")
                        <h6 class="mb-1 fw-600">Pickup Location</h6>
                        <address>
                            {{ $order->pickup_location }}
                        </address>
                    @else
                        @php
                            $shipping_address = json_decode($order->shipping_address);
                        @endphp
                        <h6 class="mb-1 fw-600">Shipping Address</h6>
                        @if ($shipping_address)
                            <address>
                                <span>{{ $shipping_address->name }}</span><br>
                                {{ $shipping_address->email }}<br>
                                {{ $shipping_address->phone }}<br>
                                {{ $shipping_address->address }},
                                {{ $shipping_address->city }}, {{ $shipping_address->state }}, {{ $shipping_address->country }},
                                
                            </address>
                        @endif
                    @endif
                    
                    
                    
                    
                </div>
                <div class="col-sm-12 col-md-6 float-right">
                    <table class="float-right">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">Order #</td>
                                <td class="text-right text-info text-bold"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Status</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Date </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    Total amount
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Payment method</td>
                                <td class="text-right">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th class="min-col">#</th>
                                <th width="10%">Photo</th>
                                <th class="text-uppercase">Description</th>
                                <th class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                                <th class="min-col text-center text-uppercase">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pcBuilderItems = $order->orderDetails->where('is_pc_builder', 1);
                                $normalItems = $order->orderDetails->where('is_pc_builder', 0);
                            @endphp 
                            @if($pcBuilderItems->count() > 0)
                            <tr class="bg-light">
                                <td class="fw-bold text-dark">
                                    PC Builder Items
                                </td>
                            </tr>
                            @foreach ($pcBuilderItems as $key => $orderDetail)
                                @php
                                    $returnRequest = $orderDetail->returns()->latest()->first(); // Get the latest return request for the product
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <img height="50" src="{{ get_product_image($orderDetail->product->thumbnail_img, '300') }}">
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <strong class="text-muted fs-13">{{ $orderDetail->product->name }}</strong>
                                            {{-- <small> --}}
                                                @if ($orderDetail->variation != null)
                                                    @php
                                                        $variations = json_decode($orderDetail->variation);
                                                    
                                                    @endphp
                                                    <ul>
                                                        @foreach($variations as $var)
                                                        <li> {{ $var->name ?? '' }} : <b>{{ $var->value ?? '' }}</b></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            {{-- </small> --}}
                                        @else
                                            <strong>Product Unavailable</strong>
                                        @endif
                                        @if ($order->delivery_status == 'delivered')
                                            {{-- @if ($returnRequest)
                                                <p><br><b>Return Status</b>: 
                                                    <span class="badge badge-lg badge-inline 
                                                        @if($returnRequest->status == 'pending') bg-warning
                                                        @elseif($returnRequest->status == 'approved') bg-success
                                                        @else bg-danger @endif">
                                                        {{ ucfirst($returnRequest->status) }}
                                                    </span>
                                                </p>
                                            @else
                                                <br><p>No return request for this product.</p>
                                            @endif --}}
                                        @endif
                                    </td>
                                   
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        @if ($orderDetail->og_price != $orderDetail->offer_price)
                                            <del>{{ single_price($orderDetail->og_price) }}</del> <br>
                                        @endif
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td class="fw-bold text-dark">Other Products</td>
                            </tr>
                            @endif
                            @foreach ($normalItems as $key => $orderDetail)
                                @php
                                    $returnRequest = $orderDetail->returns()->latest()->first(); // Get the latest return request for the product
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <img height="50" src="{{ get_product_image($orderDetail->product->thumbnail_img, '300') }}">
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <strong class="text-muted fs-13">{{ $orderDetail->product->name }}</strong>
                                            {{-- <small> --}}
                                                @if ($orderDetail->variation != null)
                                                    @php
                                                        $variations = json_decode($orderDetail->variation);
                                                    
                                                    @endphp
                                                    <ul>
                                                        @foreach($variations as $var)
                                                        <li> {{ $var->name ?? '' }} : <b>{{ $var->value ?? '' }}</b></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            {{-- </small> --}}
                                        @else
                                            <strong>Product Unavailable</strong>
                                        @endif
                                        @if ($order->delivery_status == 'delivered')
                                            {{-- @if ($returnRequest)
                                                <p><br><b>Return Status</b>: 
                                                    <span class="badge badge-lg badge-inline 
                                                        @if($returnRequest->status == 'pending') bg-warning
                                                        @elseif($returnRequest->status == 'approved') bg-success
                                                        @else bg-danger @endif">
                                                        {{ ucfirst($returnRequest->status) }}
                                                    </span>
                                                </p>
                                            @else
                                                <br><p>No return request for this product.</p>
                                            @endif --}}
                                        @endif
                                    </td>
                                   
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        @if ($orderDetail->og_price != $orderDetail->offer_price)
                                            <del>{{ single_price($orderDetail->og_price) }}</del> <br>
                                        @endif
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">Sub Total :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        
                        @if($order->tax > 0)
                            <tr>
                                <td>
                                    <strong class="text-muted">Tax :</strong>
                                </td>
                                <td>
                                    {{ single_price($order->tax) }}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td>
                                <strong class="text-muted">Shipping :</strong>
                            </td>
                            <td>
                                @if($order->shipping_cost > 0)
                                    {{ single_price($order->shipping_cost) }}
                                @else 
                                    <span class="badge badge-inline badge-success">Free</span>
                                @endif
                                
                            </td>
                        </tr>
                        @if($order->has_warranty)
                            <tr>
                                <td>
                                    <strong class="text-muted">Warranty (Premium Care+):</strong>
                                </td>
                                <td>
                                    @if($order->warranty_amount > 0)
                                        {{ format_price($order->warranty_amount) }}
                                    @else
                                        <span class="text-black uppercase font-bold text-[10px] bg-[#29A706] px-2 py-1 rounded">
                                            FREE
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <strong class="text-muted">Coupon :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">TOTAL :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>

    @if ($order->order_trackings->count() > 0)
        <div class="card shadow-sm border-light">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0 h6 text-dark fw-600">
                    <i class="las la-history text-primary mr-2" style="font-size: 1.25rem;"></i>
                    Order Status History & Activity Log
                </h5>
            </div>
            <div class="card-body">
                <ul class="timeline">
                    @foreach ($order->order_trackings as $tracking)
                        @php
                            $badgeClass = 'badge-info';
                            $textBadgeBg = 'bg-info';
                            if ($tracking->status == 'delivered') {
                                $badgeClass = 'badge-success';
                                $textBadgeBg = 'bg-success';
                            } elseif ($tracking->status == 'cancelled') {
                                $badgeClass = 'badge-danger';
                                $textBadgeBg = 'bg-danger';
                            } elseif ($tracking->status == 'pending') {
                                $badgeClass = 'badge-warning';
                                $textBadgeBg = 'bg-warning';
                            }
                            
                            // Extract who changed it and the action/details
                            $changed_by = 'System / Admin';
                            $details = $tracking->description;
                            
                            if ($tracking->status == 'pending' && (!$details || str_contains($details, 'placed successfully'))) {
                                $changed_by = '';
                                $details = 'Order placed successfully.';
                            } elseif ($details && str_contains($details, 'by ')) {
                                $parts = explode('by ', $details);
                                $changed_by = end($parts);
                                $details = str_replace(' by ' . $changed_by, '', $details);
                            } elseif (!$details) {
                                $details = 'Status updated to ' . ucfirst(str_replace('_', ' ', $tracking->status));
                            }
                        @endphp
                        <li class="timeline-item">
                            <div class="timeline-badge {{ $badgeClass }}"></div>
                            <div class="timeline-card">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
                                    <div class="d-flex align-items-center flex-wrap">
                                        <span class="badge badge-inline {{ $textBadgeBg }} text-uppercase fs-10 fw-600 px-2.5 py-1 text-white mr-3">
                                            {{ ucfirst(str_replace('_', ' ', $tracking->status)) }}
                                        </span>
                                        @if($changed_by != '')
                                            <span class="text-dark fs-13">
                                                Changed by: <strong class="text-primary fw-600">{{ $changed_by }}</strong>
                                            </span>
                                        @endif
                                        
                                    </div>
                                    <span class="text-muted fs-11 mt-1 mt-md-0">
                                        <i class="las la-calendar mr-1"></i>
                                        {{ date('d-m-Y', strtotime($tracking->status_date)) }}
                                        <span class="mx-1">•</span>
                                        <i class="las la-clock mr-1"></i>
                                        {{ date('h:i A', strtotime($tracking->status_date)) }}
                                    </span>
                                </div>
                                <p class="mb-0 text-secondary fs-12 font-italic">
                                    {{ $details }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($order->orderReturns->count() > 0)
        <div class="card">
            <div class="card-header">
                <h1 class="h2 fs-16 mb-0">Order Return Requests Details</h1>
            </div>
            <div class="card-body">
                <div class="mt-8">
                    <table class=" table aiz-table ">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 border text-left">#</th>
                                <th class="p-2 border text-left">Product</th>
                                <th class="p-2 border text-center">Return Qty</th>
                                <th class="p-2 border text-left">Reason</th>
                                <th class="p-2 border text-left">Date</th>
                                <th class="p-2 border text-center">Status</th>
                                <th class="p-2 border text-center">Balance Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderReturns as $key => $return)
                                @php
                                    $orderDetail = $return->orderDetail;
                                    $orderedQty = $orderDetail->quantity ?? 0;

                                    // Calculate total returned up to this return request (including current one)
                                    $totalReturnedUpToNow = $orderDetail->returns()
                                        ->where('id', '<=', $return->id) // Assumes auto-increment ID is sequential
                                        ->sum('return_qty');

                                    $balanceAtThisReturn = $orderedQty - $totalReturnedUpToNow;
                                @endphp
                                <tr class="border">
                                    <td class="p-2 border">
                                        {{ $key+1 }}
                                    </td>
                                    <td class="p-2 border">
                                        {{ $return->product->name ?? 'Product not found' }}
                                    </td>
                                    <td class="p-2 border text-center">
                                        {{ $return->return_qty }}
                                    </td>
                                    <td class="p-2 border">
                                        {{ $return->return_reason }}
                                    </td>
                                    <td class="p-2 border">
                                        {{ date('d M, Y H:i A', strtotime($return->created_at)) }}
                                    </td>
                                    <td class="p-2 border text-center">
                                        @if($return->status === 'pending')
                                            <span class="badge badge-sm badge-inline badge-soft-warning px-2.5 py-1.5 rounded-pill">
                                                <span class="pulse-dot bg-warning"></span>
                                                Pending
                                            </span>
                                        @elseif($return->status === 'approved')
                                            <span class="badge badge-sm badge-inline badge-soft-primary px-2.5 py-1.5 rounded-pill">
                                                <i class="las la-check-circle mr-1"></i>
                                                Approved
                                            </span>
                                        @elseif($return->status === 'received')
                                            <span class="badge badge-sm badge-inline badge-soft-info px-2.5 py-1.5 rounded-pill">
                                                <i class="las la-truck mr-1"></i>
                                                Received
                                            </span>
                                        @elseif($return->status === 'refunded')
                                            <span class="badge badge-sm badge-inline badge-soft-success px-2.5 py-1.5 rounded-pill">
                                                <i class="las la-check-circle mr-1"></i>
                                                Refunded
                                            </span>
                                        @else
                                            <span class="badge badge-sm badge-inline badge-soft-danger px-2.5 py-1.5 rounded-pill">
                                                <i class="las la-times-circle mr-1"></i>
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-2 border text-center text-blue-600 font-semibold">
                                        {{ $balanceAtThisReturn }} left
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        

        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                if (status === 'delivered' && {{ $order->payment_type == 'cod' ? 'true' : 'false' }}) {
                    $('#update_payment_status').val('paid').selectpicker('refresh');
                }
                AIZ.plugins.notify('success', 'Delivery status has been updated');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', 'Payment status has been updated');
            });
        });

        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', 'Order tracking code has been updated');
            });
        });
    </script>
@endsection
