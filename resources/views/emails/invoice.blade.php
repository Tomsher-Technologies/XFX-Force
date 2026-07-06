<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style media="all">
        @font-face {
            font-family: 'Roboto';
            src: url("{{ static_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            line-height: 1.4;
            font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
        }

        body {
            background-color: #f6f8fa;
            font-size: 13px;
            padding: 20px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 30px;
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
            font-size: 12px;
        }
        .company-details strong {
            color: #111111;
            font-size: 16px;
        }
        .invoice-title {
            text-align: right;
            line-height: 1.5;
            font-size: 12px;
        }
        .invoice-title h1 {
            margin: 0 0 8px 0;
            font-size: 20px;
            color: #2A7CFF;
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
        .variation-list {
            margin: 3px 0 0 0;
            padding-left: 12px;
            font-size: 11px;
            color: #6b7280;
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
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Top header -->
        @php
            $logo = get_setting('default_invoice_logo');
        @endphp
        <table>
            <tr>
                <td style="width: 50%; padding: 0;">
                    <div class="company-details">
                        <a href="{{ env('APP_URL') }}">
                            <img src="{{ uploaded_asset($logo) }}" height="60" alt="{{ env('APP_NAME') }}" style="display:inline-block; margin-bottom: 10px;">
                        </a><br>
                        {!! nl2br(get_setting('footer_address')) !!}<br>
                        Phone: {{ get_setting('footer_phone') ?? '+971 123456789' }}<br>
                        Email: {{ get_setting('footer_email') ?? 'info@pcgarage.com' }}
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
                    @php 
                        $shippingAddress = json_decode($order->shipping_address);
                    @endphp
                    @if($order->shipping_type == 'pickup')
                        <div class="address-title">Pickup Location</div>
                        <p style="margin: 3px 0 0 0; color: #4b5563;">{{ $order->pickup_location }}</p>
                    @else
                        <div class="address-title">Ship To</div>
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
                @foreach ($order->orderDetails as $key => $orderDetail)
                    @if ($orderDetail->product != null)
                        <tr class="item-row">
                            <td>
                                <div style="font-weight: bold; color: #111111;">{{ $orderDetail->product->name }}</div>
                                @if ($orderDetail->variation != null)
                                    <ul class="variation-list">
                                        <li>{{ $orderDetail->variation }}</li>
                                    </ul>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                @if ($orderDetail->og_price != $orderDetail->offer_price)
                                    <del style="color: #999999;">{{ single_price($orderDetail->og_price) }}</del><br>
                                @endif
                                {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                            </td>
                            <td style="text-align: center;">{{ $orderDetail->quantity }}</td>
                            <td style="text-align: right; font-weight: bold; white-space: nowrap; color: #111111;">{{ single_price($orderDetail->price) }}</td>
                        </tr>
                    @endif
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
                            <tr>
                                <td style="text-align: left; font-size: 11px; color: #888;">Coupon Code:</td>
                                <td style="text-align: right; font-size: 11px; color: #888;">{{ $order->coupon_code }}</td>
                            </tr>
                        @endif
                        @if ($order->warranty_amount != 0)
                            <tr>
                                <td style="text-align: left;">Warranty Amount:</td>
                                <td style="text-align: right; font-weight: bold;">+ {{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->warranty_amount) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="text-align: left;">Shipping:</td>
                            <td style="text-align: right; font-weight: bold;">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->shipping_cost) }}</td>
                        </tr>
                        <tr class="grand-total">
                            <td style="text-align: left; font-size: 14px; color: #111111;">Grand Total<span style="font-size: 10px; color: #6b7280;"> (Including Tax):</span>:</td>
                            <td style="text-align: right; font-size: 16px; color: #111111;">{{ env('DEFAULT_CURRENCY', 'AED') }} {{ single_price($order->grand_total) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0; font-size: 12px; font-weight: bold; color: #111111;">Thank you for shopping with {{ env('APP_NAME') }}!</p>
            <p style="margin: 4px 0 0 0;">If you have any questions about this order, please reply to this email or contact support.</p>
            {{-- <p style="margin: 4px 0 0 0;"><a href="{{ env('APP_URL') }}" style="color: #2A7CFF; text-decoration: none; font-weight: bold;">{{ str_replace(['http://', 'https://'], '', env('APP_URL')) }}</a></p> --}}
        </div>
    </div>
</body>

</html>
