<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\ProductWarranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Auth;

class CartController extends Controller
{
    public function index()
    {
        Cart::updateCartPricesWithLatestPrices();
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }

        $couponDiscount = 0;

        $cartItems = Cart::with(['product', 'product_stock', 'product.warranties'])
            ->where(function($query) use ($guestToken, $user_id) {
                if($user_id) {
                    // Logged-in user
                    $query->where('user_id', $user_id);
                } else {
                    // Guest user
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->where('status', 'pending')
            ->get()
            ->map(function ($item) {
                $item->subtotal = $item->quantity * $item->price;
                $item->offerSum = $item->quantity * $item->offer_price;
                return $item;
            });

        $couponCode = Cart::where('status', 'pending')
            ->where(function($query) use ($guestToken, $user_id) {
                if($user_id) {
                    // Logged-in user
                    $query->where('user_id', $user_id);
                } else {
                    // Guest user
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->whereNotNull('coupon_code')
            ->value('coupon_code'); 

        

        // direct cart items
        $directCartItems = $cartItems->where('is_pc_builder', 0);

        // PC Builder cart items
        $pcBuilderItems = $cartItems->where('is_pc_builder', 1)
        ->groupBy('pc_builder_id');

        $subtotal  = $cartItems->sum('subtotal');
        $offerSum  = $cartItems->sum('offerSum');
        $discountSum = $subtotal - $offerSum;
        $cartCount = $cartItems->count();
        $warrantySum  = $cartItems->sum('warranty_price');

        // coupon discount
        $totalBeforeCouponDicount = $offerSum + $warrantySum;

        if ($couponCode) {
            $couponDiscount = $this->getCouponDiscount($couponCode, $totalBeforeCouponDicount);
        }

        // Tax calculation
        $defaultVat = get_setting('default_vat') ?? 0;
        $totalBeforeTax = $offerSum - $couponDiscount + $warrantySum;
        $tax = ($totalBeforeTax * $defaultVat) / 100;

        // Shipping price calculation
        $totalBeforeShippingPriceApplied = $totalBeforeTax + $tax;
        $shipping = ($totalBeforeShippingPriceApplied > 0) ? (get_setting('default_shipping_amount') ?? 0) : 0;
        $freeShippingMinAmount = get_setting('free_shipping_min_amount') ?? 0;
        if ($totalBeforeShippingPriceApplied >= $freeShippingMinAmount) {
            $shipping = 0;
        }

        // Total
        $total = $offerSum + $tax + $shipping + $warrantySum - $couponDiscount;

        return view('frontend.cart', compact('cartItems', 'subtotal', 'discountSum', 'cartCount', 'tax', 'shipping', 'total', 'warrantySum', 'pcBuilderItems', 'directCartItems', 'couponCode' , 'couponDiscount'))
            ->with('total', $total);
    }

    public function addProductToCart(Request $request)
    {
        $userId = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';

        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }

        $variantId = $request->variantId;
        $productId = $request->productId;
        $requestedQty = $request->quantity ?? 1;
        $mode = $request->mode ?? 'set';

        $stock = ProductStock::findOrFail($variantId);

        $cartItem = Cart::where('product_stock_id', $variantId)
            ->where(function($query) use ($guestToken, $userId) {
                if($userId) {
                    // Logged-in user
                    $query->where('user_id', $userId);
                } else {
                    // Guest user
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->where('status', 'pending')
            ->first();

        $currentCartQty = $cartItem ? $cartItem->quantity : 0;

        if ($mode === 'increment') {
            $newQty = $currentCartQty + $requestedQty;
        } else {
            $newQty = $requestedQty;
        }

        // Remove item if qty becomes 0
        if ($newQty <= 0) {
            if ($cartItem) {
                $cartItem->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cartQty' => 0,
                'availableQty' => $stock->qty
            ]);
        }

        // Stock validation
        if ($newQty > $stock->qty) {
            return response()->json([
                'success' => false,
                'message' => "Only {$stock->qty} item(s) available.",
                'cartQty' => $currentCartQty,
                'availableQty' => $stock->qty - $currentCartQty
            ]);
        }

        if ($cartItem) {

            $cartItem->quantity = $newQty;
            $cartItem->save();
        } else {

            Cart::create([
                'user_id' => $userId,
                'temp_user_id' => $userId ? null : $guestToken,
                'product_id' => $productId,
                'product_stock_id' => $variantId,
                'quantity' => $newQty,
                'price' => $stock->price ?? 0,
                'offer_price' => $stock->offer_price ?? 0,
                'offer_tag' => $stock->offer_tag ?? null,
                'shipping_cost' => 0,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('messages.product_add_cart_success'),
            'cartQty' => $newQty,
            'availableQty' => $stock->qty - $newQty,
            'totalCartItemsCount' => $this->getCount(),
            'price' => format_price($stock->price * $newQty),
            'offerPrice' => format_price($stock->offer_price * $newQty),
        ]);
    }

    public function getCartDetails()
    {
        $lang = getActiveLanguage();
        $response = $this->index();
        return view('pages.cart', compact('response', 'lang'));
    }


    public function getCount()
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }
        $cartItems = Cart::with(['product', 'product_stock', 'product.warranties'])
            ->where(function($query) use ($guestToken, $user_id) {
                if($user_id) {
                    // Logged-in user
                    $query->where('user_id', $user_id);
                } else {
                    // Guest user
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->where('status', 'pending')
            ->get();

           return $cartItems->sum('quantity');
    }

    public function removeCartItem($id)
    {
        $user = getFrontEndUser();

        if ($id != '' && $user['users_id'] != '') {

            // Delete the cart item
            Cart::where([$user['users_id_type'] => $user['users_id']])
                ->where('id', $id)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => trans('messages.cart_item_removed_success'),
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => trans('messages.cart_item_not_found'),
        ], 200);
    }

    public function updateProductWarranty(Request $request)
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }
        $cartId = $request->cartId;
        $warrantyId = $request->warrantyId;


        $cart = Cart::where('id', $cartId)
            ->where(function($query) use ($guestToken, $user_id) {
                if($user_id) {
                    // Logged-in user
                    $query->where('user_id', $user_id);
                } else {
                    // Guest user
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->firstOrFail();

        // If warranty removed
        if(empty($warrantyId)) {
            $cart->warranty_id = null;
            $cart->warranty_price = 0;
        } else {
            $warranty = ProductWarranty::find($warrantyId);

            $cart->warranty_id = $warranty->id;
            $cart->warranty_price = $warranty->price ?? 0;
        }
        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Warranty updated',
        ]);
    }

    /**
     * Get cart summary details on page load or update or delete.
     *
     * @return array
     */
    public function getCartSummary()
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }

        $cartItems = Cart::with(['product', 'product_stock', 'product.warranties'])
            ->where('status', 'pending')
            ->where(function($query) use ($guestToken, $user_id) {
                if($user_id) {
                    $query->where('user_id', $user_id);
                } else {
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->get()
            ->map(function ($item) {
                // Use latest prices from the product stock
                $currentPrice = $item->product_stock->price ?? 0;
                $currentOffer = $item->product_stock->offer_price ?? $currentPrice;

                $item->subtotal = $item->quantity * $currentPrice;
                $item->offer_sum = $item->quantity * $currentOffer;
                return $item;
            });

        $couponCode = $cartItems->first()->coupon_code ?? null;

        $subTotal     = $cartItems->sum('subtotal');
        $offerSum     = $cartItems->sum('offer_sum');
        $discountSum  = $subTotal - $offerSum;

        $cartCount    = $cartItems->sum('quantity');
        $warrantySum  = $cartItems->sum('warranty_price');

        // coupon discount
        $totalBeforeCouponDicount        = $offerSum + $warrantySum;
        $couponDiscount = 0;
        if ($couponCode) {
            $couponDiscount = $this->getCouponDiscount($couponCode, $totalBeforeCouponDicount);
        }

        // Tax calculation
        $defaultVat = get_setting('default_vat') ?? 0;
        $totalBeforeTax = $offerSum - $couponDiscount + $warrantySum;
        $tax = ($totalBeforeTax * $defaultVat) / 100;

        // Shipping price calculation
        $totalBeforeShippingPriceApplied = $totalBeforeTax + $tax;
        
        $shipping = ($totalBeforeShippingPriceApplied > 0) ? (get_setting('default_shipping_amount') ?? 0) : 0;
        $freeShippingMinAmount = get_setting('free_shipping_min_amount') ?? 0;

        if ($totalBeforeShippingPriceApplied >= $freeShippingMinAmount) {
            $shipping = 0;
        }

        // Total calculation
        $total = $offerSum + $tax + $shipping + $warrantySum - $couponDiscount;

        return [
            'status' => true,
            'sub_total'    => $subTotal,
            'discount_sum' => $discountSum,
            'shipping'     => $shipping,
            'tax'   => $tax,
            'cart_count'   => $cartCount,
            'warranty_sum' => $warrantySum,
            'couponDiscount' => $couponDiscount,
            'total'        => $total,
        ];
    }


    public function apply_coupon_code(Request $request)
    {
        $user = getFrontEndUser();
        
        if($user['users_id'] != ''){
            $cart_items = Cart::where([$user['users_id_type'] => $user['users_id']])->get();
            $cartCount = count($cart_items);
            $coupon = Coupon::where('code', $request->coupon)->first();

            

            if ($cart_items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.cart_empty'),
                    'coupon_discount' => ''
                ], 200);
            }

            if ($coupon == null) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.invalid_coupon'),
                    'coupon_discount' => ''
                ], 200);
            }

            $in_range = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;

            
            if (!$in_range) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.coupon_expired'),
                    'coupon_discount' => ''
                ], 200);
            }

            
            if($coupon->one_time_use == 1){
                if($user['users_id_type'] == 'temp_user_id'){
                    $is_used = CouponUsage::where('guest_token', $user['users_id'])->where('coupon_id', $coupon->id)->first() != null;
                }else{
                    $is_used = CouponUsage::where('user_id', $user['users_id'])->where('coupon_id', $coupon->id)->first() != null;
                }
                
                

                if ($is_used) {
                    return response()->json([
                        'success' => false,
                        'message' => trans('messages.already_used_coupon'),
                        'coupon_discount' => ''
                    ], 200);
                }
            }

            


            $coupon_details = json_decode($coupon->details);

            if ($coupon->type == 'cart_base') {
                $subtotal = 0;
                $tax = 0;
                $shipping = 0;
                foreach ($cart_items as $key => $cartItem) {
                    $subtotal += $cartItem['offer_price'] * $cartItem['quantity'];
                    $tax += $cartItem['tax'];
                    $shipping += $cartItem['shipping_cost'];
                }
                $sum = $subtotal + $tax ;
                
                if ($sum >= $coupon_details->min_buy) {
                    if ($coupon->discount_type == 'percent') {
                        $coupon_discount = ($sum * $coupon->discount) / 100;
                        if ($coupon_discount > $coupon_details->max_discount) {
                            $coupon_discount = $coupon_details->max_discount;
                        }
                    } elseif ($coupon->discount_type == 'amount') {
                        $coupon_discount = $coupon->discount;
                    }

                    Cart::where($user['users_id_type'], $user['users_id'])->update([
                        'discount' => $coupon_discount / $cartCount,
                        'coupon_code' => $request->coupon,
                        'coupon_applied' => 1
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => trans('messages.coupon_applied'),
                        'coupon_discount' => format_price($coupon_discount),
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => trans('messages.coupon_cannot_applied'),
                        'coupon_discount' => ''
                    ], 200);
                }
            } elseif ($coupon->type == 'product_base') {
                $coupon_discount = 0;

                foreach ($cart_items as $key => $cartItem) {
                    foreach ($coupon_details as $key => $coupon_detail) {
                        if ($coupon_detail->product_id == $cartItem['product_id']) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount += ($cartItem['offer_price'] * $coupon->discount / 100) * $cartItem['quantity'];
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount += $coupon->discount * $cartItem['quantity'];
                            }
                        }
                    }
                }

                if($coupon_discount != 0){
                    Cart::where($user['users_id_type'], $user['users_id'])->update([
                        'discount' => $coupon_discount / $cartCount,
                        'coupon_code' => $request->coupon,
                        'coupon_applied' => 1
                    ]);
    
                    return response()->json([
                        'success' => true,
                        'message' => trans('messages.coupon_applied'),
                        'coupon_discount' => format_price($coupon_discount),
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry, this coupon cannot be applied to this order',
                        'coupon_discount' => ''
                    ], 200);
                }
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => trans('messages.user_not_found'),
                'coupon_discount' => ''
            ], 200);
        }
    }

    public function remove_coupon_code(Request $request)
    {
        $user = getFrontEndUser();
        Cart::where([$user['users_id_type'] => $user['users_id']])->update([
            'discount' => 0.00,
            'coupon_code' => "",
            'coupon_applied' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('messages.coupon_removed')
        ], 200);
    }

    public function getCouponDiscount($couponCode, $total)
    {
        $user = getFrontEndUser();

        if (!$user['users_id']) {
            return 0;
        }

        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return 0;
        }

        $couponDetails = json_decode($coupon->details);

        $couponDiscount = 0;

        if ($total >= $couponDetails->min_buy) {

            if ($coupon->discount_type == 'percent') {

                $couponDiscount = ($total * $coupon->discount) / 100;

                if (isset($couponDetails->max_discount) && $couponDiscount > $couponDetails->max_discount) {
                    $couponDiscount = $couponDetails->max_discount;
                }

            } elseif ($coupon->discount_type == 'amount') {

                $couponDiscount = $coupon->discount;

            }
        }

        return $couponDiscount;
    }

}
