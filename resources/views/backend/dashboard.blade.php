@extends('backend.layouts.app')

@section('style')
<style>
    body {
        background-color: #f8fafc;
        color: #1e293b;
    }
    .widget-card {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .widget-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
    }
    .card-border-success { border-left: 4px solid #10b981; }
    .card-border-primary { border-left: 4px solid #6366f1; }
    .card-border-warning { border-left: 4px solid #f59e0b; }
    .card-border-info { border-left: 4px solid #06b6d4; }

    .widget-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: transform 0.3s ease;
    }
    .widget-card:hover .widget-icon-box {
        transform: scale(1.1);
    }
    
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }
    .bg-soft-primary { background-color: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important; }
    .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }
    .bg-soft-info { background-color: rgba(6, 182, 212, 0.1) !important; color: #06b6d4 !important; }
    .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; }
    
    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }
    
    .table-hover-custom tbody tr:hover {
        background-color: #f8fafc;
    }
    .table-thead-light th {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 12px 16px;
    }
    .table-hover-custom td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    
    .product-list-item {
        display: flex;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .product-list-item:last-child {
        border-bottom: none;
    }
    .product-img {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 12px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    
    .badge-modern {
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 30px;
        display: inline-block;
    }
    .badge-modern-success { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }
    .badge-modern-danger { background-color: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; }
    .badge-modern-warning { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }
    .badge-modern-info { background-color: rgba(6, 182, 212, 0.1) !important; color: #06b6d4 !important; }
</style>
@endsection

@section('content')
    @if (Auth::user()->user_type == 'admin' || (Auth::user()->user_type == 'staff' && in_array('1', json_decode(Auth::user()->staff->role->permissions))))
        <!-- E-commerce KPI Cards Row -->
        <div class="row gutters-15">
            <!-- Total Revenue -->
            <div class="col-xl-3 col-md-6 col-sm-12">
                <a href="{{ route('all_orders.index') }}" class="text-reset d-block" style="text-decoration: none;">
                    <div class="card widget-card card-border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted d-block mb-1 text-uppercase fs-10 fw-600" style="letter-spacing: 0.5px;">Total Earnings</span>
                                    <h3 class="fw-700 mb-0 text-success">{{ single_price($total_revenue) }}</h3>
                                </div>
                                <div class="widget-icon-box bg-soft-success">
                                    <i class="las la-wallet"></i>
                                </div>
                            </div>
                            <div class="pt-2 border-top border-slate-100 fs-11 d-flex justify-content-between text-muted">
                                <span>Today: <b>{{ single_price($today_revenue) }}</b></span>
                                <span>Month: <b>{{ single_price($this_month_revenue) }}</b></span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Orders -->
            <div class="col-xl-3 col-md-6 col-sm-12">
                <a href="{{ route('all_orders.index') }}" class="text-reset d-block" style="text-decoration: none;">
                    <div class="card widget-card card-border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted d-block mb-1 text-uppercase fs-10 fw-600" style="letter-spacing: 0.5px;">Total Orders</span>
                                    <h3 class="fw-700 mb-0 text-primary">{{ $total_orders }}</h3>
                                </div>
                                <div class="widget-icon-box bg-soft-primary">
                                    <i class="las la-shopping-bag"></i>
                                </div>
                            </div>
                            <div class="pt-2 border-top border-slate-100 fs-11 d-flex justify-content-between text-muted">
                                <span>Today: <b>{{ $today_orders }}</b></span>
                                <span>Pending: <b class="text-warning">{{ $pending_orders }}</b></span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Products -->
            <div class="col-xl-3 col-md-6 col-sm-12">
                <a href="{{ route('products.all') }}" class="text-reset d-block" style="text-decoration: none;">
                    <div class="card widget-card card-border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted d-block mb-1 text-uppercase fs-10 fw-600" style="letter-spacing: 0.5px;">Total Products</span>
                                    <h3 class="fw-700 mb-0 text-warning">{{ $total_products }}</h3>
                                </div>
                                <div class="widget-icon-box bg-soft-warning">
                                    <i class="las la-box"></i>
                                </div>
                            </div>
                            <div class="pt-2 border-top border-slate-100 fs-11 d-flex justify-content-between text-muted">
                                <span>Brands: <b>{{ $total_brands }}</b></span>
                                <span>Categories: <b>{{ count($root_categories) }}</b></span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Customers -->
            <div class="col-xl-3 col-md-6 col-sm-12">
                <a href="{{ route('customers.index') }}" class="text-reset d-block" style="text-decoration: none;">
                    <div class="card widget-card card-border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted d-block mb-1 text-uppercase fs-10 fw-600" style="letter-spacing: 0.5px;">Total Customers</span>
                                    <h3 class="fw-700 mb-0 text-info">{{ $total_customers }}</h3>
                                </div>
                                <div class="widget-icon-box bg-soft-info">
                                    <i class="las la-users"></i>
                                </div>
                            </div>
                            <div class="pt-2 border-top border-slate-100 fs-11 d-flex justify-content-between text-muted">
                                <span>Today: <b>{{ $today_customers }}</b></span>
                                <span>Month: <b>{{ $this_month_customers }}</b></span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row gutters-15">
            <!-- Sales Trend Line Chart -->
            <div class="col-lg-8 mb-4">
                <div class="card widget-card h-100 mb-0">
                    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center bg-white">
                        <h6 class="mb-0 fs-14 fw-600 text-dark">Sales Performance</h6>
                        <span class="badge badge-inline bg-soft-primary">Last 12 Months</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="sales-trend-chart" class="w-100" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status Doughnut Chart -->
            <div class="col-lg-4 mb-4">
                <div class="card widget-card h-100 mb-0">
                    <div class="card-header border-bottom-0 bg-white">
                        <h6 class="mb-0 fs-14 fw-600 text-dark">Order Statuses</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="order-status-chart" class="w-100" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Top Selling Products Row -->
        <div class="row gutters-15">
            <!-- Recent Orders Table -->
            <div class="col-lg-8 mb-4">
                <div class="card widget-card h-100 mb-0">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white">
                        <h6 class="mb-0 fs-14 fw-600 text-dark">Recent Orders</h6>
                        <a href="{{ route('all_orders.index') }}" class="btn btn-sm btn-soft-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover-custom mb-0 fs-12">
                                <thead class="table-thead-light">
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Customer</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Delivery Status</th>
                                        <th class="text-center">Payment Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_orders as $order)
                                        <tr>
                                            <td class="align-middle fw-600">{{ $order->code }}</td>
                                            <td class="align-middle">
                                                @if ($order->user != null)
                                                    {{ $order->user->name }}
                                                @else
                                                    @php
                                                        $shipping = json_decode($order->shipping_address);
                                                    @endphp
                                                    Guest ({{ $shipping->name ?? 'Guest' }})
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">{{ single_price($order->grand_total) }}</td>
                                            <td class="align-middle text-center">
                                                @php
                                                    $status = ucfirst(str_replace('_', ' ', $order->delivery_status));
                                                @endphp
                                                <span class="badge-modern @if($order->delivery_status == 'delivered') badge-modern-success @elseif($order->delivery_status == 'cancelled') badge-modern-danger @else badge-modern-warning @endif">
                                                    {!! $status !!}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge-modern @if($order->payment_status == 'paid') badge-modern-success @else badge-modern-danger @endif">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a class="btn btn-sm btn-soft-primary btn-icon btn-circle" href="{{ route('all_orders.show', encrypt($order->id)) }}" title="View">
                                                    <i class="las la-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No recent orders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="col-lg-4 mb-4">
                <div class="card widget-card h-100 mb-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fs-14 fw-600 text-dark">Top Selling Products</h6>
                    </div>
                    <div class="card-body">
                        @forelse($top_products as $product)
                            <div class="product-list-item">
                                @if ($product->thumbnail_img)
                                    <img src="{{ product_image_url($product->thumbnail_img, 300) }}" alt="Image" class="product-img">
                                @else
                                    <div class="product-img d-flex align-items-center justify-content-center bg-light text-muted">
                                        <i class="las la-image fs-20"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1 min-w-0">
                                    <span class="d-block text-truncate fw-600 text-dark mb-1 fs-12" title="{{ $product->name }}">{{ $product->name }}</span>
                                    <span class="text-muted fs-11">{{ $product->category ? $product->category->getTranslation('name') : 'Category' }}</span>
                                </div>
                                <div class="text-right ml-2 pl-2 border-left border-slate-100">
                                    <span class="d-block fw-700 text-success fs-12">{{ single_price($product->unit_price) }}</span>
                                    <span class="text-muted fs-10">{{ $product->num_of_sale }} Sales</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">No sales data yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>



        <!-- Existing Category-wise Charts -->
        <div class="row gutters-15">
            <!-- Category Product Sale -->
            <div class="col-lg-12 mb-4">
                <div class="card widget-card mb-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fs-14 fw-600 text-dark">Category-wise Product Sale</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="graph-1" class="w-100" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Product Stock -->
            <div class="col-lg-12 mb-4">
                <div class="card widget-card mb-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fs-14 fw-600 text-dark">Category-wise Product Stock</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="graph-2" class="w-100" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        // Sales Trend Line Chart
        AIZ.plugins.chart('#sales-trend-chart', {
            type: 'line',
            data: {
                labels: @json($sales_trend_months),
                datasets: [{
                    label: 'Revenue',
                    data: @json($sales_trend_data),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.05)',
                    borderWidth: 2,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#6366f1',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f1f5f9',
                            zeroLineColor: '#f1f5f9'
                        },
                        ticks: {
                            fontColor: "#64748b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: 'transparent'
                        },
                        ticks: {
                            fontColor: "#64748b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    titleFontFamily: 'Poppins',
                    bodyFontFamily: 'Poppins',
                    cornerRadius: 6
                }
            }
        });

        // Order Status Doughnut Chart
        AIZ.plugins.chart('#order-status-chart', {
            type: 'doughnut',
            data: {
                labels: [
                    'Pending',
                    'Confirmed',
                    'Picked Up',
                    'On The Way',
                    'Delivered',
                    'Cancelled'
                ],
                datasets: [{
                    data: [
                        {{ $order_status_counts['pending'] ?? 0 }},
                        {{ $order_status_counts['confirmed'] ?? 0 }},
                        {{ $order_status_counts['picked_up'] ?? 0 }},
                        {{ $order_status_counts['on_the_way'] ?? 0 }},
                        {{ $order_status_counts['delivered'] ?? 0 }},
                        {{ $order_status_counts['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: [
                        "#f59e0b", // pending - yellow
                        "#6366f1", // confirmed - blue
                        "#a29bfe", // picked_up - purple
                        "#06b6d4", // on_the_way - teal
                        "#10b981", // delivered - green
                        "#ef4444"  // cancelled - red
                    ]
                }]
            },
            options: {
                cutoutPercentage: 70,
                legend: {
                    position: 'bottom',
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 8,
                        usePointStyle: true,
                        padding: 12,
                        fontSize: 10,
                        fontColor: "#64748b"
                    }
                },
                tooltips: {
                    titleFontFamily: 'Poppins',
                    bodyFontFamily: 'Poppins',
                    cornerRadius: 6
                }
            }
        });

        // Graph 1: Category Wise Product Sale
        AIZ.plugins.chart('#graph-1', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($root_categories as $key => $category)
                        '{{ $category->getTranslation('name') }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Sales Volume',
                    data: [
                        {{ $cached_graph_data['num_of_sale_data'] }}
                    ],
                    backgroundColor: [
                        @foreach ($root_categories as $key => $category)
                            'rgba(99, 102, 241, 0.4)',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach ($root_categories as $key => $category)
                            'rgba(99, 102, 241, 1)',
                        @endforeach
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f1f5f9',
                            zeroLineColor: '#f1f5f9'
                        },
                        ticks: {
                            fontColor: "#64748b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            fontColor: "#64748b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true,
                        fontColor: "#64748b"
                    },
                    onClick: function() {
                        return '';
                    },
                }
            }
        });

        // Graph 2: Category Wise Product Stock
        AIZ.plugins.chart('#graph-2', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($root_categories as $key => $category)
                        '{{ $category->getTranslation('name') }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Stock Quantity',
                    data: [
                        {{ $cached_graph_data['qty_data'] }}
                    ],
                    backgroundColor: [
                        @foreach ($root_categories as $key => $category)
                            'rgba(245, 158, 11, 0.4)',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach ($root_categories as $key => $category)
                            'rgba(245, 158, 11, 1)',
                        @endforeach
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f1f5f9',
                            zeroLineColor: '#f1f5f9'
                        },
                        ticks: {
                            fontColor: "#64748b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            fontColor: "#64748b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true,
                        fontColor: "#64748b"
                    },
                    onClick: function() {
                        return '';
                    },
                }
            }
        });
    </script>
@endsection
