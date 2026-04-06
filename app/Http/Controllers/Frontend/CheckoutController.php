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

class CheckoutController
{
    public function index()
    {
        $cartController = new CartController();
        $cartData = $cartController->getCartSummary();

        $addresses = [];
        $user = Auth::user();

        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        if($user_id){
            $addresses = Address::where('user_id', auth('frontend')->user()->id)->orderBy('id','desc')->get();
        }

        $userId = $user_id ? $user_id : null;
        $guestToken = request()->cookie('guest_token');

        if (!$guestToken && !$userId) {
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60 * 24 * 14); // 14 days
        }


        $cartItems = Cart::with(['product', 'product_stock'])
            ->when($userId, function($query) use ($userId) {
                $query->where('user_id', $userId);
            }, function($query) use ($guestToken) {
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
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:100',
            'billing_email' => 'required|email|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_country' => 'required|string|max:100',
            'billing_phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
            'billing_address' => 'required|string',
        ], [
            'name.regex' => 'Only alphabets and spaces are allowed in the name field.',
            'phone.regex' => 'Please enter a valid phone number (numbers only, 7-15 digits).'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors(),
                'redirect' => ''
            ],200);
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

        if ($billing_shipping_same != 'on') {

            if ($request->selected_addr) {

                $selectedAddress = Address::find($request->selected_addr);
                
                $shipping_address = [
                    'name'    => $selectedAddress->name,
                    'email'   => $request->billing_email,
                    'address' => $selectedAddress->address,
                    'city'    => $selectedAddress->city,
                    'state'   => $selectedAddress->state_name,
                    'country' => $selectedAddress->country_name,
                    'phone'   => $selectedAddress->phone,
                ];

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
                    'password' => bcrypt(\Illuminate\Support\Str::random(12)),
                    'user_type' => 'customer'
                ]);

                $user_id = $user->id;
            }
        }

        /* ---------------- Convert Guest Cart ---------------- */

        Cart::where('temp_user_id', $temp_user_id)
            ->update([
                'user_id' => $user_id,
                'temp_user_id' => null
            ]);

        /* ---------------- Get Cart ---------------- */
        $carts = Cart::where(function ($query) use ($user_id, $temp_user_id) {
                $query->where('user_id', $user_id)
                    ->orWhere('temp_user_id', $temp_user_id);
            })
            ->orderBy('id')
            ->get();

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
            'payment_type' => $request->payment_method ?? '',
            'payment_status' => 'un_paid',
            'grand_total' => 0,
            'tax' => 0,
            'warranty_amount' => 0,
            'sub_total' => 0,
            'offer_discount' => 0,
            'coupon_discount' => 0,
            'code' => date('Ymd-His') . rand(10, 99),
            'date' => strtotime('now'),
            'delivery_viewed' => 0
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
                'og_price' => $data->price,
                'tax' => $data->tax,
                'shipping_cost' => ($request->fulfillment_method == 'pickup') ? 0 : $data->shipping_cost, // Set shipping cost to 0 if pickup is selected
                'offer_price' => $data->offer_price,
                'price' => $data->offer_price * $data->quantity,
                'quantity' => $data->quantity,
                'pc_builder_id' => $data->pc_builder_id,
                'is_pc_builder' =>  $data->is_pc_builder,
            ];

            $productQuantities[$data->product_id] = $data->quantity;

            Product::where('id', $data->product_id)
                ->increment('num_of_sale', $data->quantity);
        }

        OrderDetail::insert($orderItems);
        $cartSummary = app(CartController::class)->getCartSummary();

        // $grand_total = ($sub_total + $cartSummary['tax'] + $cartSummary['shipping']) - ($discount + $total_coupon_discount);
        $shipping = ($request->fulfillment_method == 'pickup') ? 0 : $cartSummary['shipping'];
        $grand_total = ($sub_total + $cartSummary['tax'] + $shipping + $cartSummary['warranty_sum']) - ($discount + $total_coupon_discount);

        $order->update([
            'grand_total' => $grand_total,
            'sub_total' => $sub_total,
            'offer_discount' => $discount,
            'tax' => $cartSummary['tax'],
            'warranty_amount' => $cartSummary['warranty_sum'],
            'shipping_cost' => $shipping,
            'shipping_type' =>  ($request->fulfillment_method == 'pickup') 
                    ? 'pickup' 
                    : (($total_shipping == 0) ? 'free_shipping' : 'flat_rate'),
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

        reduceProductQuantity($productQuantities);

        /* ---------------- Update PC Builder ---------------- */

        $pcBuilderIds = $carts->where('is_pc_builder', 1)
                            ->pluck('pc_builder_id')
                            ->filter()
                            ->unique();

        if($pcBuilderIds->count()) {

            PcBuilderSetup::whereIn('id', $pcBuilderIds)
                ->update([
                    'is_ordered' => 1
                ]);
        }

        /* ---------------- Clear Cart ---------------- */

        Cart::where('user_id', $user_id)->delete();

        NotificationUtility::sendOrderPlacedNotification($order);

        User::where('user_type', 'admin')->get()
            ->each(fn($admin) => $admin->notify(new NewOrderNotification($order)));

        return response()->json([
            'status' => true,
            'errors' => '',
            'redirect' => route('order.success', $order->id)
        ]);
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
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'return_reason' => 'required|string|max:255',
            'return_qty' => 'required|array',
            'return_qty.*' => 'integer|min:1',
        ]);

        // Get the order
        $order = Order::findOrFail($request->order_id);
        if($order && $order->delivery_status == "delivered"){

            if ($order->return_request == 0) {
                $reason = $request->return_reason;
                $order->return_request = 1;
                $order->return_request_date = now();
                $order->return_reason = $reason;
                $order->save();
            }

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
                <p>Team ".env('APP_NAME')."</p>
            ";

            Mail::to(env('MAIL_ADMIN'))->queue(new EmailManager($array));
        }

        return response()->json(['success' => true, 'message' => 'Return request submitted successfully.']);
    }

}
