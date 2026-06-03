<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CartController;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\OrderTracking;
use App\Models\Address;
use App\Models\CombinedOrder;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use App\Models\OrderReturn;
use App\Models\OrderPayments;
use Illuminate\Http\Request;
use App\Utility\NotificationUtility;
use App\Utility\SendSMSUtility;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewOrderNotification;
use App\Models\Cart;
use App\Mail\EmailManager;
use App\Models\PcBuilderSetup;
use Illuminate\Support\Facades\Validator;
use Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Services\TabbyService;
use App\Models\OrderPayment;

class CheckoutController
{
    public function index()
    {
        Cart::updateCartPricesWithLatestPrices();
        $cartController = new CartController();
        $cartData = $cartController->getCartSummary();

        $user = auth('frontend')->user();
        $auth_user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $userId = $auth_user_id ? $auth_user_id : null;
        $guestToken = request()->cookie('guest_token');

        // Merge guest cart into user cart if logged in
        if ($userId && $guestToken) {
            $guestCartItems = Cart::where('temp_user_id', $guestToken)
                ->where('status', 'pending')
                ->get();

            foreach ($guestCartItems as $item) {
                $existingItem = Cart::where('user_id', $userId)
                    ->where('product_stock_id', $item->product_stock_id)
                    ->where('status', 'pending')
                    ->first();

                if ($existingItem) {
                    $existingItem->quantity += $item->quantity;
                    $existingItem->save();
                    $item->delete();
                } else {
                    $item->user_id = $userId;
                    $item->temp_user_id = null;
                    $item->save();
                }
            }

            // Optional: delete guest token cookie after merge
            cookie()->queue(cookie()->forget('guest_token'));
        }

        // Fetch addresses if logged in
        $addresses = [];
        if ($userId) {
            $addresses = Address::where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->get();
        }

        // Fetch cart items
        $cartItems = Cart::with(['product', 'product_stock'])
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }, function ($query) use ($guestToken) {
                $query->where('temp_user_id', $guestToken);
            })
            ->where('status', 'pending')
            ->get();

        return view('frontend.checkout', array_merge($cartData, [
            'addresses' => $addresses,
            'user' => $user,
            'cartItems' => $cartItems,
        ]));
    }

    /**
     * Function to place order
     * 
     * @param Request $request
     */
    public function placeOrder(Request $request)
    {
        $cartController = new CartController();
        $cartSummary = $cartController->getCartSummary();
        $paymentType = $request->pay ?? 'cod'; // DEFAULT COD

        $isGuest = !auth('frontend')->check();
        $billing_shipping_same = $request->same_as_billing ?? null;

        $rules = [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:100',
            'billing_email' => 'required|email|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_country' => 'required|string|max:100',
            'billing_phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
            'billing_address' => 'required|string',
            'same_as_billing' => 'nullable',
        ];

        if ($isGuest && !$billing_shipping_same) {
            $rules = array_merge($rules, [
                'shipping_first_name'    => 'required|string|max:100',
                'shipping_phone'   => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
                'shipping_address' => 'required|string',
                'shipping_city'    => 'required|string|max:100',
                'shipping_state'   => 'required|string|max:100',
            ]);
        }

        $validator = Validator::make($request->all(), $rules, [
            'first_name.regex' => 'Only alphabets and spaces are allowed in the name field.',
            'billing_phone.regex' => 'Please enter a valid phone number (numbers only, 7-15 digits).',
            'billing_state.required' => 'Please select an emirate.',
            'shipping_phone.regex' => 'Please enter a valid phone number (numbers only, 7-15 digits).',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'redirect' => ''
            ], 200);
        }


        $name = $request->first_name . ' ' . $request->last_name;
        $billing_shipping_same = $request->same_as_billing ?? null;
        $shipping_address = [];
        $billing_address = [];

        $auth_user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $user_id = $auth_user_id ? $auth_user_id : null;

        /* ---------------- Guest Token ---------------- */
        $guest_token = request()->cookie('guest_token');

        if (!$guest_token) {
            $guest_token = uniqid('guest_', true);
            cookie()->queue('guest_token', $guest_token, 60 * 24 * 30);
        }

        $temp_user_id = $guest_token;

        /* ---------------- Address ---------------- */

        $billing_address = [
            'name'    => $name,
            'email'   => $request->billing_email,
            'address' => $request->billing_address,
            'city'    => $request->billing_city,
            'state'   => $request->billing_state,
            'country' => $request->billing_country,
            'phone'   => $request->billing_phone,
        ];

        $isGuest = !$user_id;

        /* ---------------- SHIPPING ---------------- */

        if ($billing_shipping_same != 'on') {

            // ---------------- LOGGED IN USER ----------------
            if (!$isGuest) {

                if ($request->selected_addr) {

                    $selectedAddress = Address::find($request->selected_addr);

                    if ($selectedAddress) {
                        $shipping_address = [
                            'name'    => $selectedAddress->name,
                            'email'   => $request->billing_email,
                            'address' => $selectedAddress->address,
                            'city'    => $selectedAddress->city,
                            'state'   => $selectedAddress->state_name,
                            'country' => $selectedAddress->country_name,
                            'phone'   => $selectedAddress->phone,
                        ];
                    }
                } else {

                    $shipping_address = [
                        'name'    => $request->shipping_name ?? $name,
                        'email'   => $request->billing_email,
                        'address' => $request->shipping_address ?? $request->billing_address,
                        'city'    => $request->shipping_city ?? $request->billing_city,
                        'state'   => $request->shipping_state ?? $request->billing_state,
                        'country' => $request->shipping_country ?? $request->billing_country,
                        'phone'   => $request->shipping_phone ?? $request->billing_phone,
                    ];
                }
            }

            // ---------------- GUEST USER ----------------
            else {
                $shippingFirstName = trim($request->shipping_first_name ?? '');
                $shippingLastName  = trim($request->shipping_last_name ?? '');

                $shippingName = trim($shippingFirstName . ' ' . $shippingLastName);

                $shipping_address = [
                    'name'    => $shippingName ?? $name,
                    'address' => $request->shipping_address,
                    'city'    => $request->shipping_city,
                    'state'   => $request->shipping_state,
                    'country' => $request->shipping_country,
                    'phone'   => $request->shipping_phone,
                ];
            }
        } else {
            $shipping_address = $billing_address;
        }

        $billing_address_json  = json_encode($billing_address);
        $shipping_address_json = json_encode($shipping_address);

        /* ---------------- User Handling ---------------- */

        $authUser = auth('frontend')->user();

        if ($authUser) {
            // Logged in user always use auth user id
            $user_id = $authUser->id;
        } else {
            // Guest user
            $existingUser = User::where('email', $request->billing_email)->first();

            if ($existingUser) {
                $user_id = $existingUser->id;
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => $request->billing_email,
                    'password' => null,
                    'user_type' => 'guest',
                ]);

                $user_id = $user->id;
            }
        }



        /* ---------------- Get Cart ---------------- */
        $carts = Cart::where(function ($query) use ($user_id, $temp_user_id) {
            $query->where('user_id', $user_id)
                ->orWhere('temp_user_id', $temp_user_id);
        })
            ->orderBy('id')
            ->get();

        /* ---------------- Stock Validation ---------------- */
        $stockErrors = [];
        $priceChanged = false;

        foreach ($carts as $cartItem) {

            $stock = ProductStock::find($cartItem->product_stock_id);

            if (!$stock) {
                $stockErrors[] = "Product stock not found for item ID {$cartItem->product_stock_id}";
                continue;
            }

            if ($stock->qty < $cartItem->quantity) {
                $stockErrors[] = $stock->product->name . " is out of stock.";
            }

            if ((float) $cartItem->offer_price != (float) $stock->offer_price) {
                $priceChanged = true;
            }
        }

        if (!empty($stockErrors) || $priceChanged) {
            $message = $priceChanged
                ? 'The item prices have changed. Please review your cart.'
                : 'Some items are out of stock';

            return response()->json([
                'status' => false,
                'message' => $message,
                'errors' => $stockErrors,
                'redirect' => route('cart')
            ]);
        }

        /* ---------------- Convert Guest Cart ---------------- */
        Cart::where('temp_user_id', $temp_user_id)
            ->update([
                'user_id' => $user_id,
                'temp_user_id' => null
            ]);

        /*re-fetch cart after conversion */
        $carts = Cart::where(function ($query) use ($user_id, $temp_user_id) {
            $query->where('user_id', $user_id)
                ->orWhere('temp_user_id', $temp_user_id);
        })
            ->orderBy('id')
            ->get();

        // check cart empty
        if ($carts->isEmpty()) {
            return response()->json([
                'status' => false,
                'errors' => '',
                'redirect' => route('order.fail')
            ]);
        }

        $carts->load(['product', 'product_stock']);

        /* ---------------- Estimated Delivery ---------------- */
        $maxDeliveryDays = get_setting('default_delivery_days') ?? 0;
        $estimated_delivery = now()->addDays($maxDeliveryDays);

        /* ---------------- Create Order ---------------- */
        $order = Order::create([
            'user_id' => $user_id,
            'guest_token' => $temp_user_id,
            'shipping_address' => $shipping_address_json,
            'billing_address' => $billing_address_json,
            'estimated_delivery' => $estimated_delivery,
            'shipping_type' => 'free_shipping',
            'shipping_cost' => 0,
            'delivery_status' => 'pending',
            'payment_type' => $paymentType,
            'payment_status' => 'unpaid',
            'grand_total' => 0,
            'tax' => 0,
            'warranty_amount' => 0,
            'sub_total' => 0,
            'offer_discount' => 0,
            'coupon_discount' => 0,
            'code' => date('Ymd-His') . rand(10, 99),
            'date' => strtotime('now'),
            'delivery_viewed' => 0,
            'order_success' => ($paymentType === 'cod') ? 1 : 0,
        ]);

        /* ---------------- Tracking ---------------- */
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'description' => 'The order has been placed successfully',
            'status_date' => now()
        ]);

        /* ---------------- Calculations ---------------- */
        $sub_total = $total_tax = $total_shipping = $discount = $total_coupon_discount = 0;
        $coupon_code = null;

        $orderItems = [];
        $productQuantities = [];

        foreach ($carts as $data) {

            $sub_total += $data->price * $data->quantity;
            $total_tax += $data->tax;
            $total_shipping += $data->shipping_cost;

            $discount += (($data->price * $data->quantity) - ($data->offer_price * $data->quantity)) + $data->offer_discount;

            if ($data->coupon_applied == 1) {
                $total_coupon_discount += $data->discount;

                if (!$coupon_code) {
                    $coupon_code = $data->coupon_code;
                }
            }

            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $data->product_id,
                'product_stock_id' => $data->product_stock->id,
                'warranty_id' => $data->warranty_id,
                'og_price' => $data->price,
                'tax' => $data->tax,
                'shipping_cost' => ($request->fulfillment_method == 'pickup') ? 0 : $data->shipping_cost, // Set shipping cost to 0 if pickup is selected
                'offer_price' => $data->offer_price,
                'price' => $data->offer_price * $data->quantity,
                'quantity' => $data->quantity,
                'pc_builder_id' => $data->pc_builder_id,
                'is_pc_builder' =>  $data->is_pc_builder,
                'is_returnable' => $data->product->return_refund == 1 ? 1 : 0,
            ];

            $productQuantities[$data->product_id] = $data->quantity;

            Product::where('id', $data->product_id)
                ->increment('num_of_sale', $data->quantity);
        }

        OrderDetail::insert($orderItems);

        $shipping = ($request->fulfillment_method == 'pickup') ? 0 : $cartSummary['shipping'];
        $grand_total = ($sub_total + $cartSummary['tax'] + $shipping + $cartSummary['warranty_sum']) - ($discount + $total_coupon_discount);

        $order->update([
            'grand_total' => $grand_total,
            'sub_total' => $sub_total,
            'offer_discount' => $discount,
            'tax' => $cartSummary['tax'],
            'warranty_amount' => $cartSummary['warranty_sum'],
            'has_warranty' => $cartSummary['has_warranty'],
            'shipping_cost' => $shipping,
            'shipping_type' => ($request->fulfillment_method == 'pickup')
                ? 'pickup'
                : (($total_shipping == 0) ? 'free_shipping' : 'flat_rate'),
            'pickup_location' => ($request->fulfillment_method == 'pickup') ? get_setting('pickup_address') : null,
            'coupon_discount' => round($total_coupon_discount),
            'coupon_code' => $coupon_code
        ]);

        /* ---------------- Coupon Usage ---------------- */

        if ($coupon_code) {
            CouponUsage::create([
                'user_id' => $user_id,
                'coupon_id' => Coupon::where('code', $coupon_code)->value('id')
            ]);
        }

        if ($paymentType === 'cod') { // CASE 1: COD 

            reduceProductQuantity($productQuantities);

            // Send mail to customer and admin when order placed.
            NotificationUtility::sendOrderPlacedNotification($order);

            // Notify admin
            User::where('user_type', 'admin')->get()
                ->each(fn($admin) => $admin->notify(new NewOrderNotification($order)));

            // Notify Customer
            $message = "Your order #{$order->code} has been placed successfully";
            sendNotification(
                $order->user,
                $message,
                $order,
                'order_placed'
            );

            /* ---------------- Clear Cart ---------------- */
            Cart::where('user_id', $user_id)->delete();

            /* ---------------- Delete PC Builder ---------------- */
            $pcBuilderIds = $carts->where('is_pc_builder', 1)
                ->pluck('pc_builder_id')
                ->filter()
                ->unique();

            if ($pcBuilderIds->count()) {
                PcBuilderSetup::whereIn('id', $pcBuilderIds)
                    ->delete();
            }

            return response()->json([
                'status' => true,
                'errors' => '',
                'redirect' => route('order.success', base64_encode($order->id)),
            ]);
        } elseif ($paymentType === 'card') { //CASE 2: STRIPE (NO STOCK / CART / NOTIFICATION YET)

            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'aed',
                        'product_data' => [
                            'name' => 'Order #' . $order->code,
                        ],
                        'unit_amount' => intval($grand_total * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?order_id=' . $order->id,
                'cancel_url' => route('stripe.cancel') . '?order_id=' . $order->id,
            ]);

            // Order payment
            OrderPayment::create([
                'order_id' => $order->id,
                'payment_status' => 'initiated',
                'payment_details' => json_encode([
                    'gateway' => 'stripe',
                    'session_id' => $session->id,
                    'amount' => $grand_total,
                    'currency' => 'AED',
                    'raw' => $session,
                ]),
            ]);

            $order->update([
                'transaction_id' => $session->id,
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'status' => true,
                'redirect' => $session->url
            ]);
        } elseif ($paymentType === 'tabby') {
            $items = [];

            foreach ($carts as $cartItem) {
                $items[] = [
                    'title' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => number_format($cartItem->offer_price, 2, '.', ''),
                    'category' => optional($cartItem->product->category)->name ?? 'General',
                    'reference_id' => (string) $cartItem->product_id,
                ];
            }

            $payload = [
                'payment' => [
                    'amount' => number_format($grand_total, 2, '.', ''),
                    'currency' => 'AED',
                    'buyer' => [
                        'name'  => $name,
                        'email' => $request->billing_email,
                        'phone' => $request->billing_phone,
                    ],

                    'shipping_address' => [
                        'city'    => $request->billing_city,
                        'address' => $request->billing_address,
                        'zip'     => '00000',
                    ],

                    'order' => [
                        'reference_id' => $order->code,
                        'items' => $items,
                    ],

                    'description' => 'Order #' . $order->code,
                    'meta' => [
                        'order_id' => $order->id,
                    ],
                ],

                'lang' => 'en',
                'merchant_code' => config('services.tabby.merchant_code'),
                'merchant_urls' => [
                    'success' => route('tabby.success', [
                        'order_id' => $order->id
                    ]),
                    'cancel' => route('tabby.cancel', [
                        'order_id' => $order->id
                    ]),
                    'failure' => route('tabby.cancel', [
                        'order_id' => $order->id
                    ]),
                ],
            ];

            $tabby = new \App\Services\TabbyService();
            $response = $tabby->createCheckout($payload);

            OrderPayment::create([
                'order_id' => $order->id,
                'payment_status' => 'initiated',
                'payment_details' => json_encode([
                    'gateway' => 'tabby',
                    'request' => $payload,
                    'response' => $response,
                ]),
            ]);
        
            if (!$response['success']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unable to create Tabby session',
                ]);
            }

            $data = $response['data'];

            $checkoutUrl =
                $data['configuration']['available_products']['installments'][0]['web_url']
                ?? null;

            if (!$checkoutUrl) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tabby checkout URL not received',
                ]);
            }

            $order->update([
                'payment_type' => 'tabby',
                'payment_status' => 'pending',
                'transaction_id' => $data['payment']['id'] ?? $data['id'],
            ]);

            return response()->json([
                'status' => true,
                'redirect' => $checkoutUrl,
            ]);
        }
    }

    public function stripeSuccess(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) return redirect()->route('order.fail');

        /* STOCK REDUCTION (ONLY AFTER PAYMENT) */
        $items = OrderDetail::where('order_id', $order->id)->get();

        $productQuantities = [];

        foreach ($items as $item) {
            $productQuantities[$item->product_id] = $item->quantity;
        }

        reduceProductQuantity($productQuantities);

        /* CART CLEAR */
        Cart::where('user_id', $order->user_id)->delete();

        /* PC BUILDER DELETE */
        $carts = Cart::where('user_id', $order->user_id)->get();

        $pcBuilderIds = $carts->where('is_pc_builder', 1)
            ->pluck('pc_builder_id')
            ->filter()
            ->unique();

        if ($pcBuilderIds->count()) {
            PcBuilderSetup::whereIn('id', $pcBuilderIds)->delete();
        }

        OrderPayment::where('order_id', $order->id)
            ->update([
                'payment_status' => 'paid',
                'payment_details' => json_encode([
                    'status' => 'paid_via_stripe_success',
                    'updated_at' => now()
                ]),
            ]);

        $order->update([
            'payment_status' => 'paid',
            'order_success' => 1
        ]);

        NotificationUtility::sendOrderPlacedNotification($order);

        return redirect()->route('order.success', base64_encode($order->id));
    }

    public function stripeCancel(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order) {
            OrderPayment::where('order_id', $order->id)
                ->update([
                    'payment_status' => 'failed',
                ]);

            $order->delete();
        }

        return redirect()->route('order.fail');
    }

    /**
     * Function to send order cancel request
     * 
     * @param Request $request
     * @param int $order_id
     */
    public function cancelOrderRequest(Request $request, $order_id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:255'
        ]);

        $user_id = auth('frontend')->id();

        $order = Order::where('id', $order_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.not_found')
            ], 200);
        }

        if ($order->cancel_request == 0 && $order->delivery_status == "pending") {

            $reason = $request->cancel_reason;
            $order->cancel_request = 1;
            $order->cancel_request_date = now();
            $order->cancel_reason = $reason;
            $order->save();

            /* ---------- Admin Mail ---------- */

            $array['view'] = 'emails.commonmail';
            $array['subject'] = "New Order Cancel Request - " . $order->code;
            $array['from'] = env('MAIL_FROM_ADDRESS');

            $array['content'] = "
                <p>Hi,</p>
                <p>We have received a new order cancel request.</p>
                <p><b>Order Code:</b> {$order->code}</p>
                <p><b>Customer Name:</b> {$order->user->name}</p>
                <p><b>Reason:</b> {$reason}</p>
                <p><b>Date:</b> " . now()->format('d M Y h:i A') . "</p>
            ";

            Mail::to(env('MAIL_ADMIN'))->queue(new EmailManager($array));

            // Notify admin
            $admins = User::where('user_type', 'admin')->get();
            $message = "Customer {$order->user->name} has requested to cancel Order #{$order->code}. Please review the request.";
            sendNotification($admins, $message, $order, 'cancel_request');

            return response()->json([
                'status' => true,
                'message' => trans('messages.request_success')
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => trans('messages.request_already_send')
        ], 200);
    }

    public function returnOrderRequest(Request $request, $id)
    {

        // Validate the input
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'return_reason' => 'required|string|max:255',
            'return_qty' => 'required|array',
            'return_qty.*' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the order
        $order = Order::findOrFail($request->order_id);
        $reason = $request->return_reason;
        if ($order && $order->delivery_status == "delivered") {


            // Loop through selected products and save return details
            foreach ($request->return_qty as $orderDetailId => $qty) {
                $orderDetail = OrderDetail::find($orderDetailId);

                $alreadyReturnedQty = $orderDetail->returns->sum('return_qty');
                $remainingQty = $orderDetail->quantity - $alreadyReturnedQty;

                if ($orderDetail && $qty <= $remainingQty) {
                    OrderReturn::create([
                        'order_id' => $order->id,
                        'order_detail_id' => $orderDetail->id,
                        'product_id' => $orderDetail->product_id,
                        'return_qty' => $qty,
                        'return_reason' => $request->return_reason,
                        'status' => 'Pending', // Default status
                    ]);
                }
            }

            /* ---------- Admin Mail ---------- */

            $array['view'] = 'emails.commonmail';
            $array['subject'] = "New Order Return Request - " . $order->code;
            $array['from'] = env('MAIL_FROM_ADDRESS');

            $array['content'] = "
                <p>Hi,</p>
                <p>A new order return request has been received.</p>
                <p><b>Order Code:</b> {$order->code}</p>
                <p><b>Customer Name:</b> {$order->user->name}</p>
                <p><b>Return Reason:</b> {$reason}</p>
                <p><b>Date:</b> " . now()->format('d M Y h:i A') . "</p>
                <p>Please review and take the necessary action.</p>
                
                <p>Best regards,</p>
                <p>Team " . env('APP_NAME') . "</p>
            ";

            Mail::to(env('MAIL_ADMIN'))->queue(new EmailManager($array));

            // Notify admin
            $admins = User::where('user_type', 'admin')->get();
            $message = "Customer {$order->user->name} has requested a return for Order #{$order->code}. Please review the request.";
            sendNotification($admins, $message, $order, 'return_request');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Return request submitted successfully.'
        ]);
    }

    public function tabbySuccess(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        // Prevent duplicate processing
        if ($order->payment_status === 'paid') {
            return redirect()->route(
                'order.success',
                base64_encode($order->id)
            );
        }

        /* STOCK REDUCTION (ONLY AFTER PAYMENT) */
        $items = OrderDetail::where('order_id', $order->id)->get();

        $productQuantities = [];

        foreach ($items as $item) {
            $productQuantities[$item->product_id] =
                ($productQuantities[$item->product_id] ?? 0)
                + $item->quantity;
        }

        reduceProductQuantity($productQuantities);

        /* PC BUILDER DELETE BEFORE CART CLEAR */
        $carts = Cart::where('user_id', $order->user_id)->get();

        $pcBuilderIds = $carts->where('is_pc_builder', 1)
            ->pluck('pc_builder_id')
            ->filter()
            ->unique();

        if ($pcBuilderIds->count()) {
            PcBuilderSetup::whereIn('id', $pcBuilderIds)->delete();
        }

        /* CLEAR CART */
        Cart::where('user_id', $order->user_id)->delete();

        OrderPayment::where('order_id', $order->id)
            ->update([
                'payment_status' => 'paid',
                'payment_details' => json_encode([
                    'status' => 'paid_via_tabby',
                    'updated_at' => now()
                ]),
            ]);
        $order->update([
            'payment_status' => 'paid',
            'order_success'  => 1,
        ]);

        // Send order emails/notifications
        NotificationUtility::sendOrderPlacedNotification($order);

        // Notify admin
        User::where('user_type', 'admin')->get()
            ->each(fn($admin) => $admin->notify(new NewOrderNotification($order)));

        // Notify customer
        $message = "Your order #{$order->code} has been placed successfully";

        sendNotification(
            $order->user,
            $message,
            $order,
            'order_placed'
        );

        return redirect()->route(
            'order.success',
            base64_encode($order->id)
        );
    }

    public function tabbyCancel(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order) {
            OrderPayment::where('order_id', $order->id)
                ->update([
                    'payment_status' => 'failed',
                ]);

            $order->update([
                'payment_status' => 'failed'
            ]);
        }

        return redirect()
            ->route('checkout')
            ->with('error', 'Payment was cancelled.');
    }
}
