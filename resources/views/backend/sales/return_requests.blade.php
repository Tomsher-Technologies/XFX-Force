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
    
    /* Interactive circular soft buttons */
    .btn-circle-soft {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        transition: all 0.2s ease-in-out;
    }
    .btn-circle-soft:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Badges with proper vertical alignment for icons */
    .badge-inline i {
        vertical-align: middle;
        position: relative;
        top: -1px;
    }
</style>
@endsection

@section('content')

<div class="card">
    
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">All Return Requests</h5>
            </div>
        </div>

        <div class="card-body">
            
                <form class="" action="" id="sort_orders" method="GET">
                    <div class="row">
                        
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Order Code</label>
                                <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($search) value="{{ $search }}" @endisset placeholder="Type Order code & hit Enter">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Request Approval Status</label>
                                <select id="ca_search" name="ca_search" class="form-control form-control-sm" >
                                    <option {{ ($ca_search == '') ? 'selected' : '' }} value="">Select status</option>
                                    <option {{ ($ca_search == 'pending') ? 'selected' : '' }} value="pending">Pending</option>
                                    <option {{ ($ca_search == 'approved') ? 'selected' : '' }} value="approved">Approved</option>
                                    <option {{ ($ca_search == 'received') ? 'selected' : '' }} value="received">Received</option>
                                    <option {{ ($ca_search == 'refunded') ? 'selected' : '' }} value="refunded">Refunded</option>
                                    <option {{ ($ca_search == 'rejected') ? 'selected' : '' }} value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Request Date</label>
                                <input type="text" class="aiz-date-range form-control form-control-sm" value="{{ $date }}" name="date" placeholder="Filter by request date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                            </div>
                        </div>

                     

                        <div class="col-auto m-auto">
                            <div class="form-group" style="margin: inherit;">
                                <button type="submit" class="btn btn-sm btn-info">Filter</button>
                                <a href="{{ route('return_requests.index') }}" class="btn btn-sm btn-cancel">{{trans('messages.reset')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
          
            <table class="table table-bordered aiz-table mb-2">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Order Code</th>
                        <th class="w-25">Customer</th>
                        <th class="text-center">Return Qty</th>
                        <th class="w-25">Reason</th>
                        <th class="text-center">Approval</th>
                        <th class="text-center">Fulfillment & Refund</th>
                        <th class="text-center">View Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr class="">
                            <td class="text-center">{{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}</td>
                            <td class=" text-center">{{ $order->order->code }}</td>
                            <td>
                                <div>
                                    <span class="d-block text-dark font-weight-medium">
                                        {{ $order->order->user->name ?? 'N/A' }}
                                    </span>
                                    <small class="text-gray">
                                        {{ $order->order->user->email ?? '' }}
                                    </small>
                                </div>
                            </td>
                            <td class=" text-center">{{ $order->return_qty }}</td>
                            <td class="">{{ $order->return_reason }}</td>
                            <!-- Approval Column -->
                            <td class="text-center">
                                @if($order->status == 'pending')
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <button onclick="updateReturnStatus({{ $order->id }}, 'approved')" 
                                                class="btn-circle-soft btn-soft-success" 
                                                title="Approve Request">
                                            <i class="las la-check fs-12"></i>
                                        </button>
                                        <button onclick="updateReturnStatus({{ $order->id }}, 'rejected')" 
                                                class="btn-circle-soft btn-soft-danger" 
                                                title="Reject Request">
                                            <i class="las la-times fs-12"></i>
                                        </button>
                                    </div>
                                @elseif(in_array($order->status, ['approved', 'received', 'refunded']))
                                    <span class="badge badge-md badge-inline badge-soft-success px-3 py-1.5 rounded-pill">
                                        <i class="las la-check-circle mr-1"></i>
                                        Approved
                                    </span>
                                @else
                                    <span class="badge badge-md badge-inline badge-soft-danger px-3 py-1.5 rounded-pill">
                                        <i class="las la-times-circle mr-1"></i>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <!-- Fulfillment & Refund Column -->
                            <td class="text-center">
                                @if($order->status == 'pending')
                                    <span class="text-muted fs-11">Awaiting Approval</span>
                                @elseif($order->status == 'rejected')
                                    <span class="text-muted fs-11">-</span>
                                @elseif($order->status == 'approved')
                                    <div class="d-flex flex-column align-items-center justify-content-center gap-1">
                                        <span class="badge badge-md badge-inline badge-soft-primary px-3 py-1.5 rounded-pill mb-1">
                                            <i class="las la-clock mr-1"></i>
                                            Awaiting Item
                                        </span>
                                        <button onclick="updateReturnStatus({{ $order->id }}, 'received')" 
                                                class="btn btn-xs btn-soft-info px-2.5 py-1.5 rounded font-weight-medium" 
                                                title="Mark Items Received">
                                            <i class="las la-truck-loading mr-1"></i> Mark Received
                                        </button>
                                    </div>
                                @elseif($order->status == 'received')
                                    <div class="d-flex flex-column align-items-center justify-content-center gap-1">
                                        <span class="badge badge-md badge-inline badge-soft-info px-3 py-1.5 rounded-pill mb-1">
                                            <i class="las la-truck mr-1"></i>
                                            Received
                                        </span>
                                        <button onclick="updateReturnStatus({{ $order->id }}, 'refunded')" 
                                                class="btn btn-xs btn-soft-success px-2.5 py-1.5 rounded font-weight-medium" 
                                                title="Mark Refunded">
                                            <i class="las la-money-bill-wave mr-1"></i> Mark Refunded
                                        </button>
                                    </div>
                                @elseif($order->status == 'refunded')
                                    <span class="badge badge-md badge-inline badge-soft-success px-3 py-1.5 rounded-pill">
                                        <i class="las la-check-circle mr-1"></i>
                                        Refunded
                                    </span>
                                @endif
                            </td>
                            <td class=" text-center">
                                <a class="btn btn-sm btn-soft-primary btn-icon btn-circle" href="{{route('return_orders.show', encrypt($order->order->id))}}" title="View">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>

        </div>
    
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function updateReturnStatus(returnId, status) {
        swal({
                title: "Are you sure?",
                text: `Are you sure you want to mark this return as ${status}?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "{{ route('return-request-status') }}",
                    type: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: JSON.stringify({ return_id: returnId, status: status }),

                    success: function(response) {
                        swal("Successfully updated!", {
                            icon: "success",
                        });
                        setTimeout(function () { 
                            location.reload(true); 
                        }, 3000); 
                    }
                });
            }else{
                $(this).val('');
            }
        });
    }
</script>
@endsection
