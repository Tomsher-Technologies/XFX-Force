<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailManager;
use App\Models\Address;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderReturn;
use App\Models\OrderTracking;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Mail;
use Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            //->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct();

        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = \App\Models\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('frontend.user.seller.orders', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    public function success($id)
    {
        $order = Order::with('orderDetails.product')->findOrFail($id);
        return view('frontend.order.success', compact('order'));
    }

    public function fail()
    {
        return view('frontend.order.fail');
    }

    // All Orders
    public function all_orders(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();
        $request->session()->put('last_url', url()->full());

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;

        $orders = Order::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.all_orders.index', compact('orders', 'sort_search', 'delivery_status', 'date'));
    }

    public function all_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);

        return view('backend.sales.all_orders.show', compact('order'));
    }

    public function allCancelRequests(Request $request)
    {
        $request->session()->put('last_url', url()->full());
        $search         = ($request->has('search')) ? $request->search : '';
        $ca_search      = ($request->has('ca_search')) ? $request->ca_search : '';
        $date           = ($request->has('date')) ? $request->date : ''; //
        $refund_search  = ($request->has('refund_search')) ? $request->refund_search : '';

        $orders = Order::where('cancel_request', 1)->orderBy('cancel_request_date', 'DESC');
        if ($search) {
            $orders = $orders->where('code', 'like', '%' . $search . '%');
        }
        if ($ca_search) {
            $ca_search = ($ca_search == 10) ? 0 : $ca_search;
            $orders = $orders->where('cancel_approval', $ca_search);
        }

        if ($date != null) {
            $orders = $orders->whereDate('cancel_request_date', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('cancel_request_date', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        if ($refund_search) {
            $orders = $orders->where('cancel_refund_type', $refund_search);
        }

        $orders = $orders->paginate(15);
        return view("backend.sales.cancel_requests", compact('orders', 'search', 'ca_search', 'date', 'refund_search'));
    }

    public function allReturnRequests(Request $request)
    {
        $request->session()->put('return_last_url', url()->full());
        $search         = ($request->has('search')) ? $request->search : '';
        $ca_search      = ($request->has('ca_search')) ? $request->ca_search : '';
        $date           = ($request->has('date')) ? $request->date : ''; //
        $refund_search  = ($request->has('refund_search')) ? $request->refund_search : '';


        $orders = OrderReturn::with(['order', 'product'])->orderBy('created_at', 'DESC');
        if ($search) {
            $orders = $orders->whereHas('order', function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%'); // Adjust field name if necessary
            });
        }
        if ($ca_search) {
            $orders = $orders->where('status', $ca_search);
        }

        if ($date != null) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }

        $orders = $orders->paginate(15);
        return view("backend.sales.return_requests", compact('orders', 'search', 'ca_search', 'date', 'refund_search'));
    }


    public function myOrders()
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $orders = Order::where('user_id', $user_id)->latest()->get();
        return view('frontend.order.my-orders', compact('orders'));
    }

    
    /**
     * Function to approve / reject return order request
     * 
     * @param Request $request
     */
    public function updateReturnStatus(Request $request)
    {
        $request->validate([
            'return_id' => 'required|exists:order_returns,id',
            'status' => 'required|in:approved,rejected',
        ]);

        $return = OrderReturn::findOrFail($request->return_id);
        $return->status = $request->status;
        $return->save();

        $order = Order::findOrFail($return->order_id);
        if($order){
            if ($order->return_request == 1) {
                $order->return_approval = ($request->status == 'approved') ? 1 : 2;
                $order->return_approval_date = date('Y-m-d H:i:s');
                $order->save();
            }

            $customer = $order->user;
            $customerEmail = $customer->email;
            $customerName  = $customer->name;
            $orderCode     = $order->code;

            if ($request->status == "approved") { // Approved
        
                /* ---------- Customer Notification ---------- */
                $message = "Your return request for Order #{$orderCode} has been approved";
                sendNotification(
                    $customer,
                    $message,
                    $order,
                    'return_approved'
                );

                /* -------- Customer Email -------- */
                $array['view'] = 'emails.commonmail';
                $array['subject'] = "Your Order Return Request Approved - {$orderCode}";
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['content'] = "
                    <p>Hi {$customerName},</p>
                    <p>Your order return request has been <b>approved</b>.</p>
                    <p><b>Order Code:</b> {$orderCode}</p>
                    <p>Please follow the return instructions provided or wait for our team to contact you regarding the return process.</p>
                    <p>Once we receive and verify the returned item, the refund will be processed accordingly.</p>
                    <p>Thank you for shopping with us.</p>
                    <p>Best regards,</p>
                    <p>Team ".env('APP_NAME')."</p>
                ";
                Mail::to($customerEmail)->queue(new EmailManager($array));

            } else { // Rejected

                /* ---------- Customer Notification ---------- */
                $message = "Your return request for Order #{$orderCode} has been rejected";
                sendNotification(
                    $customer,
                    $message,
                    $order,
                    'return_rejected'
                );

                /* -------- Customer Email -------- */
                $array['view'] = 'emails.commonmail';
                $array['subject'] = "Your Order Return Request Rejected - {$orderCode}";
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['content'] = "
                    <p>Hi {$customerName},</p>
                    <p>Your order return request has been <b>rejected</b>.</p>
                    <p><b>Order Code:</b> {$orderCode}</p>
                    <p>After reviewing your request, we are unable to proceed with the return at this time.</p>
                    <p>If you need further clarification, please contact our support team.</p>
                    <p>Thank you for shopping with us.</p>
                    <p>Best regards,</p>
                    <p>Team ".env('APP_NAME')."</p>
                ";
                Mail::to($customerEmail)->queue(new EmailManager($array));
            }
        }

        return response()->json(['success' => true, 'message' => 'Return status updated successfully.']);
    }

    public function return_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));

        return view('backend.sales.return_orders_show', compact('order'));
    }

    /**
     * Function to approve/reject cancel request.
     * 
     * @param Request $request
     */
    public function cancelRequestStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $cancel_request = Order::findOrFail($id);
        if ($cancel_request->cancel_request == 1) {
            $cancel_request->cancel_approval = $status;
            
            $customer      = $cancel_request->user;
            $customerEmail = $customer->email;
            $customerName  = $customer->name;
            $orderCode     = $cancel_request->code;
            
            if ($status == 1) { //approved
                $cancel_request->delivery_status = 'cancelled';

                foreach ($cancel_request->orderDetails as $key => $orderDetail) {
                    $orderDetail->delivery_status = 'cancelled';
                    $orderDetail->save();

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }

                $track              = new OrderTracking;
                $track->order_id    = $cancel_request->id;
                $track->status      = 'cancelled';
                $track->description = 'Your order has been successfully canceled.';
                $track->status_date = date('Y-m-d H:i:s');
                $track->save();

                /* ---------- Customer Notification ---------- */
                $message = "Your cancel request for Order #{$orderCode} has been approved";
                sendNotification(
                    $customer,
                    $message,
                    $cancel_request,
                    'cancel_approved'
                );

                /* -------- Customer Email -------- */
                $array['view'] = 'emails.commonmail';
                $array['subject'] = "Your Order Cancel Request Approved - {$orderCode}";
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['content'] = "
                    <p>Hi {$customerName},</p>
                    <p>Your order cancel request has been <b>approved</b>.</p>
                    <p><b>Order Code:</b> {$orderCode}</p>
                    <p>We have successfully canceled your order.</p>
                    <p>Thank you for shopping with us.</p>
                    <p>Best regards,</p>
                    <p>Team ".env('APP_NAME')."</p>";
                Mail::to($customerEmail)->queue(new EmailManager($array));
            } else { // Rejected

                /* ---------- Customer Notification ---------- */
                $message = "Your cancel request for Order #{$orderCode} has been rejected";
                sendNotification(
                    $customer,
                    $message,
                    $cancel_request,
                    'cancel_rejected'
                );

                /* -------- Customer Email -------- */
                $array['view'] = 'emails.commonmail';
                $array['subject'] = "Your Order Cancel Request Rejected - {$orderCode}";
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['content'] = "
                    <p>Hi {$customerName},</p>
                    <p>Your order cancel request has been <b>rejected</b>.</p>
                    <p><b>Order Code:</b> {$orderCode}</p>
                    <p>Your order will be processed and delivered as scheduled.</p>
                    <p>Thank you for shopping with us.</p>
                    <p>Best regards,</p>
                    <p>Team ".env('APP_NAME')."</p>
                ";
                Mail::to($customerEmail)->queue(new EmailManager($array));
            }

            $cancel_request->cancel_approval_date = date('Y-m-d H:i:s');
            $cancel_request->save();

            echo 1;
        } else {
            echo 0;
        }
    }

    public function cancel_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('backend.sales.cancel_orders_show', compact('order'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                } catch (\Exception $e) {
                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }


    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('frontend.user.seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        // $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        $track              = new OrderTracking;
        $track->order_id    = $order->id;
        $track->status      = $request->status;
        $track->description = null;
        $track->status_date = date('Y-m-d H:i:s');
        $track->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }
        if ($request->status == 'delivered') {
            $order->delivery_completed_date = date('Y-m-d H:i:s');
            $order->save();
        }

        foreach ($order->orderDetails as $key => $orderDetail) {

            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();

            $product_stock = ProductStock::where('id', $orderDetail->product_stock_id)
                ->first();

            if ($request->status == 'cancelled') {
                if ($product_stock != null) {
                    $product_stock->qty += $orderDetail->quantity;
                    $product_stock->save();
                }
            }
        }

        $customer = $order->user;
        if ($customer) {
            // Notify customer when order delivery status changed
            $statusMessages = [
                'pending'     => "Your order #{$order->code} has been received and is now pending confirmation.",
                'confirmed'   => "Your order #{$order->code} has been confirmed and is being processed.",
                'picked_up'   => "Your order #{$order->code} has been picked up from our warehouse and is on its way.",
                'on_the_way'  => "Your order #{$order->code} is out for delivery and will reach you soon.",
                'delivered'   => "Your order #{$order->code} has been delivered. We hope you enjoy your purchase!",
                'cancelled'   => "Your order #{$order->code} has been cancelled.",
            ];

            $message = $statusMessages[$request->status] ?? "Order #{$order->code} status updated to {$request->status}";
            sendNotification($customer, $message, $order, $request->status);

            // Send mail to customer when order delivered or cancelled
            if (in_array($request->status, ['delivered', 'cancelled'])) {
                $customerName  = $order->user->name;
                $customerEmail = $order->user->email;
                $orderCode     = $order->code ?? $order->id; // adjust if you have a code field

                if ($request->status == 'delivered') {
                    $statusText = 'delivered';
                    $messageContent = "
                        <p>Hi {$customerName},</p>
                        <p>Your order has been <b>delivered</b>.</p>
                        <p><b>Order Code:</b> {$orderCode}</p>
                        <p>We hope you enjoy your purchase.</p>
                        <p>Thank you for shopping with us.</p>
                        <p>Best regards,</p>
                        <p>Team ".env('APP_NAME')."</p>";
                    $subject = "Your Order #{$orderCode} is Delivered";
                } elseif ($request->status == 'cancelled') {
                    $statusText = 'cancelled';
                    $messageContent = "
                        <p>Hi {$customerName},</p>
                        <p>Your order has been <b>cancelled</b>.</p>
                        <p><b>Order Code:</b> {$orderCode}</p>
                        <p>Thank you for shopping with us. We hope to serve you again soon!</p>
                        <p>Best regards,</p>
                        <p>Team ".env('APP_NAME')."</p>";
                    $subject = "Your Order #{$orderCode} is Cancelled";
                }

                $array['view']    = 'emails.commonmail';
                $array['subject'] = $subject;
                $array['from']    = env('MAIL_FROM_ADDRESS');
                $array['content'] = $messageContent;

                Mail::to($customerEmail)->queue(new EmailManager($array));
            }
        }


        return 1;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);


        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }

        $order->payment_status = $request->status;
        $order->save();

        return 1;
    }

    public function myOrderSingle($id)
    {
        try {
            $orderId = decrypt($id);
        } catch (\Exception $e) {
            abort(404);
        }
        $user_id = auth('frontend')->user()->id ?? '';

        $order = Order::with([
                'orderDetails.product',
                'orderDetails.returns'
            ])
            ->where('id', $orderId)
            ->where('user_id', $user_id)
            ->firstOrFail();

        $trackingHistory = OrderTracking::where('order_id', $orderId)
            ->orderBy('created_at')
            ->get()
            ->keyBy('status');

        // Only non PC builder items
        $details = $order->orderDetails->where('is_pc_builder', 0);
        $hasReturnableItems = false;

        foreach ($details as $detail) {
            $approvedQty = $detail->returns->where('status', 'approved')->sum('return_qty');
            $pendingQty  = $detail->returns->where('status', 'pending')->sum('return_qty');
            $rejectedQty = $detail->returns->where('status', 'rejected')->sum('return_qty');
            $processedQty = $approvedQty + $pendingQty + $rejectedQty;

            // if something still not processed → returnable
            if ($processedQty < $detail->quantity) {
                $hasReturnableItems = true;
                break;
            }
        }

        return view(
            'frontend.order.my-order-single',
            compact('order', 'trackingHistory', 'hasReturnableItems')
        );
    }
}
