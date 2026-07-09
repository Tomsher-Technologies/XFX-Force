<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333333;
            margin: 0;
            padding: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        td, th {
            padding: 6px;
            vertical-align: top;
        }
        /* Top Header */
        .company-details {
            line-height: 1.5;
            color: #555555;
        }
        .company-details strong {
            color: #111111;
            font-size: 16px;
        }
        .invoice-title {
            text-align: right;
            line-height: 1.5;
        }
        .invoice-title h1 {
            margin: 0 0 8px 0;
            font-size: 24px;
            color: #111111;
            text-transform: uppercase;
            font-weight: bold;
        }
        .invoice-title p {
            margin: 2px 0;
            color: #555555;
        }
        /* Dividers */
        .divider {
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }
        /* Address Section */
        .address-title {
            font-weight: bold;
            color: #111111;
            text-transform: uppercase;
            font-size: 11px;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
        }
        /* Items Table */
        .items-table {
            margin-top: 15px;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
        }
        .items-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px;
        }
        .items-table tr.item-row:nth-child(even) td {
            background-color: #f9fafb;
        }
        .items-table tr.category-row td {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #111111;
            font-size: 11px;
            padding: 6px 10px;
            border-left: 3px solid #111111;
        }
        .variation-list {
            margin: 3px 0 0 0;
            padding-left: 12px;
            font-size: 11px;
            color: #6b7280;
        }
        .warranty-label {
            font-size: 11px;
            color: #6b7280;
            margin-top: 3px;
        }
        .badge-free {
            background-color: #10b981;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            padding: 1px 3px;
            text-transform: uppercase;
        }
        /* Totals */
        .totals-table td {
            padding: 4px 6px;
            color: #4b5563;
        }
        .totals-table tr.grand-total td {
            border-top: 1px solid #111111;
            border-bottom: 3px double #111111;
            font-weight: bold;
            font-size: 15px;
            color: #111111;
            padding-top: 6px;
        }
        /* Footer */
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div style="padding: 5px;">
        <!-- Top header -->
        <table>
            <tr>
                <td style="width: 50%; padding: 0;">
                    <div class="company-details">
                        @if($imagePath && file_exists($imagePath))
                        <img width="200" src="{{ $imagePath }}" alt="{{ env('APP_NAME') }}" title="{{ env('APP_NAME') }}" class="invoice-logo">
                        @else
                            {{-- {{ env('APP_NAME') }} --}}
                        @endif
                        <br>
                        {!! nl2br(get_setting('footer_address')) !!}<br>
                        Phone: {{ get_setting('footer_phone') }}<br>
                        Email: {{ get_setting('footer_email') }}
                    </div>
                </td>
                <td style="width: 50%; padding: 0;">
                    <div class="invoice-title">
                        <h1>Order Confirmation</h1>
                        <p><strong>Order Number:</strong> {{ $order->code }}</p>
                        <p><strong>Date:</strong> {{ date('d M Y, h:i A', $order->date) }}</p>
                        <p><strong>Payment Method:</strong> 
                            @php
                                $paymentLabels = [
                                    'cod' => 'Cash on Delivery',
                                    'card' => 'Debit / Credit Card',
                                    'tabby' => 'Tabby',
                                ];
                            @endphp
                            {{ $paymentLabels[$order->payment_type] ?? ucfirst($order->payment_type) }}
                        </p>
                        <p><strong>Shipping Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->shipping_type)) }}</p>
                    </div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Addresses side by side -->
        <table style="margin-bottom: 15px;">
            <tr>
                <td style="width: 50%; padding-left: 0; padding-right: 15px;">
                    <div class="address-title">Bill To</div>
                    @php 
                        $billingAddress = json_decode($order->billing_address);
                    @endphp
                    <p style="margin: 0; font-weight: bold; color: #111111;">{{ $billingAddress?->name }}</p>
                    <p style="margin: 3px 0 0 0; color: #4b5563;">{{ $billingAddress?->address }}</p>
                    <p style="margin: 2px 0 0 0; color: #4b5563;">{{ $billingAddress?->city }}</p>
                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #6b7280;">Phone: {{ $billingAddress?->phone }}</p>
                    <p style="margin: 2px 0 0 0; font-size: 12px; color: #6b7280;">Email: {{ $billingAddress?->email }}</p>
                </td>
                <td style="width: 50%; padding-right: 0; padding-left: 15px;">
                    @if($order->shipping_type == 'pickup')
                        <div class="address-title">Pickup Location</div>
                        <p style="margin: 3px 0 0 0; color: #4b5563;">{{ $order->pickup_location }}</p>
                    @else
                        <div class="address-title">Ship To</div>
                        @php 
                            $shippingAddress = json_decode($order->shipping_address);
                        @endphp
                        <p style="margin: 0; font-weight: bold; color: #111111;">{{ $shippingAddress?->name }}</p>
                        <p style="margin: 3px 0 0 0; color: #4b5563;">{{ $shippingAddress?->address }}</p>
                        <p style="margin: 2px 0 0 0; color: #4b5563;">{{ $shippingAddress?->city }}</p>
                        <p style="margin: 6px 0 0 0; font-size: 12px; color: #6b7280;">Phone: {{ $shippingAddress?->phone }}</p>
                        <p style="margin: 2px 0 0 0; font-size: 12px; color: #6b7280;">Email: {{ $shippingAddress?->email }}</p>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Ordered Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Product Description</th>
                    <th style="width: 15%; text-align: right;">Unit Price</th>
                    <th style="width: 15%; text-align: center;">Qty</th>
                    <th style="width: 20%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                $pcBuilderItems = $order->orderDetails->where('is_pc_builder', 1);
                $normalItems = $order->orderDetails->where('is_pc_builder', 0);
                @endphp

                @if($pcBuilderItems->count() > 0)
                    <tr class="category-row">
                        <td colspan="4">PC Builder Items</td>
                    </tr>
                    @foreach ($pcBuilderItems as $key => $orderDetail)
                        <tr class="item-row">
                            <td>
                                <div style="font-weight: bold; color: #111111;">{{ $orderDetail->product->name }}</div>
                                @if ($orderDetail->variation != null)
                                    @php
                                        $variations = json_decode($orderDetail->variation);
                                    @endphp
                                    <ul class="variation-list">
                                        @foreach($variations as $var)
                                            <li>{{ $var->name ?? '' }}: {{ $var->value ?? '' }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                @if ($orderDetail->og_price != $orderDetail->offer_price)
                                    <del style="color: #999999;"> {{ env('DEFAULT_CURRENCY', 'AED') }}{{ single_price($orderDetail->og_price) }}</del><br>
                                @endif
                                {{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                            </td>
                            <td style="text-align: center;">{{ $orderDetail->quantity }}</td>
                            <td style="text-align: right; font-weight: bold; white-space: nowrap; color: #111111;">{{ single_price($orderDetail->price) }}</td>
                        </tr>
                    @endforeach

                    @if($normalItems->count() > 0)
                        <tr class="category-row">
                            <td colspan="4">Other Products</td>
                        </tr>
                    @endif
                @endif

                @foreach ($normalItems as $key => $orderDetail)
                    <tr class="item-row">
                        <td>
                            <div style="font-weight: bold; color: #111111;">{{ $orderDetail->product->name }}</div>
                            @if($orderDetail->warranty)
                                <div class="warranty-label">
                                    Warranty: {{ $orderDetail->warranty->name }}
                                    @if($orderDetail->warranty->price > 0)
                                        (+ {{ format_price($orderDetail->warranty->price) }})
                                    @else
                                        <span class="badge-free">FREE</span>
                                    @endif
                                </div>
                            @endif
                            @if ($orderDetail->variation != null)
                                @php
                                    $variations = json_decode($orderDetail->variation);
                                @endphp
                                <ul class="variation-list">
                                    @foreach($variations as $var)
                                        <li>{{ $var->name ?? '' }}: {{ $var->value ?? '' }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            @if ($orderDetail->og_price != $orderDetail->offer_price)
                                <del style="color: #999999;">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($orderDetail->og_price) }}</del><br>
                            @endif
                            {{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                        </td>
                        <td style="text-align: center;">{{ $orderDetail->quantity }}</td>
                        <td style="text-align: right; font-weight: bold; white-space: nowrap; color: #111111;">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($orderDetail->price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Summary Table -->
        <table style="width: 100%; border: none; margin-top: 10px;">
            <tr>
                <td style="width: 45%; border: none;"></td>
                <td style="width: 55%; border: none; padding: 0;">
                    <table class="totals-table" style="width: 100%; margin-bottom: 0;">
                        <tr>
                            <td style="text-align: left;">Subtotal:</td>
                            <td style="text-align: right; font-weight: bold; width: 50%;">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->sub_total) }}</td>
                        </tr>
                        @if ($order->offer_discount != 0)
                            <tr>
                                <td style="text-align: left;">Discount:</td>
                                <td style="text-align: right; color: #b91c1c;">- {{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->offer_discount) }}</td>
                            </tr>
                        @endif
                        @if ($order->coupon_discount != 0)
                            <tr>
                                <td style="text-align: left;">Coupon Discount:</td>
                                <td style="text-align: right; color: #b91c1c;">- {{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->coupon_discount) }}</td>
                            </tr>
                        @endif
                        @if($order->has_warranty)
                            <tr>
                                <td style="text-align: left;">Warranty (Premium Care+):</td>
                                <td style="text-align: right; font-weight: bold;">
                                    @if($order->warranty_amount > 0)
                                        + {{ env('DEFAULT_CURRENCY', 'AED') }} {{ format_price($order->warranty_amount) }}
                                    @else
                                        <span class="badge-free">FREE</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td style="text-align: left;">Shipping Charge:</td>
                            <td style="text-align: right; font-weight: bold;">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->shipping_cost) }}</td>
                        </tr>
                        <tr class="grand-total">
                            <td style="text-align: left; font-size: 14px; color: #111111;">Grand Total<span style="font-size: 10px; color: #6b7280;"> (Including Tax):</span></td>
                            <td style="text-align: right; font-size: 16px; color: #111111;">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->grand_total) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0; font-size: 12px; font-weight: bold; color: #111111;">Thank you for shopping with PC Garage!</p>
            <p style="margin: 4px 0 0 0;">If you have any questions about this order confirmation, please contact our support team.</p>
            {{-- <p style="margin: 4px 0 0 0;"><a href="{{ env('APP_URL') }}" style="color: #2A7CFF; text-decoration: none; font-weight: bold;">{{ str_replace(['http://', 'https://'], '', env('APP_URL')) }}</a></p> --}}
        </div>
    </div>
</body>
</html>
