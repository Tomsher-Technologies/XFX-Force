<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['product', 'product_stock'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get()
            ->map(function ($item) {
                $item->subtotal = $item->qty * $item->price;
                return $item;
            });

        $subtotal  = $cartItems->sum('subtotal');
        $cartCount = $cartItems->sum('qty');

        return view('frontend.cart', compact('cartItems', 'subtotal', 'cartCount'))
            ->with('total', $subtotal);
    }

    public function addProductToCart(Request $request){
        $userId = Auth::id();
        $variantId = $request->has('variantId') ? $request->variantId : '';
        $productId = $request->has('productId') ? $request->productId : '';
        $requestedQty = $request->quantity ?? 1;

        // Get product stock
        $stock = ProductStock::findOrFail($variantId);

        // Calculate already added quantity in cart (pending)
        $cartQty = Cart::where('user_id', $userId)
            ->where('product_stock_id', $variantId)
            ->where('status', 'pending')
            ->sum('quantity');

        $availableQty = $stock->qty - $cartQty;

        if ($availableQty <= 0) {
            return response()->json([
                'success' => false,
                'message' => trans('messages.product_outofstock_msg').'!',
                'availableQty' => $availableQty - $requestedQty,
            ]);
        }

        // Limit requested quantity to available
        if ($requestedQty > $availableQty) {
            return response()->json([
                'success' => false,
                'message' => "Only {$availableQty} item(s) available for this variant.",
                'availableQty' => $availableQty - $requestedQty,
            ]);
        }

        // Check if cart already has this product variant for this user
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_stock_id', $variantId)
            ->where('status', 'pending')
            ->first();

        if ($cartItem) {
            // Merge quantities
            $cartItem->quantity += $requestedQty;
            $cartItem->save();
        } else {
            // Create new cart entry
            Cart::create([
                'user_id'    => $userId,
                'product_id'  => $productId,
                'product_stock_id'=> $variantId,
                'quantity'    => $requestedQty,
                'price'       => $stock->price ?? 0,
                'offer_price' => $stock->offer_price ?? 0,
                'offer_tag'   => $stock->offer_tag ?? NULL,
                // 'tax'         => $tax,
                'shipping_cost' => 0,
                'status'     => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('messages.product_add_cart_success'),
            'availableQty' => $availableQty - $requestedQty,
        ]);
    }

    public function addToCart(Request $request)
    {
        $product_slug   = $request->has('product_slug') ? $request->product_slug : '';
        $sku            = $request->has('sku') ? $request->sku : '';
        $quantity       = $request->has('quantity') ? $request->quantity : 0;

        $userId = Auth::id();
      
        $guestToken = $request->cookie('guest_token') ?? uniqid('guest_', true);

        if (auth()->user()) {
            $users_id_type = 'user_id';
            $user_id = auth()->user()->id;
            if ($guestToken) {
                Cart::where('temp_user_id', $guestToken)
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );
            }
        }else{
            $users_id_type = 'temp_user_id';
        }

        $variantProduct = ProductStock::leftJoin('products as p','p.id','=','product_stocks.product_id')
                                    ->where('p.sku', $sku)
                                    ->where('p.slug', $product_slug)
                                    ->select('product_stocks.*')->first() ?? [];

        if(!empty($variantProduct)){
            $product_id         = $variantProduct['product_id'] ?? null;
            $product_stock_id   = $variantProduct['id'] ?? null;
          
            $current_Stock      = $variantProduct['qty'] ?? 0;
            
            $carts = Cart::where([
                $users_id_type =>  ($users_id_type == 'user_id') ? $userId  : $guestToken,
                'product_id' => $product_id,
                'product_stock_id' => $product_stock_id
            ])->first();

            // Calculate the total quantity to check against stock
            $totalQuantityInCart = $quantity;

            $priceData = getProductPrice($variantProduct);
            $tax = 0;
            if ($carts) {

                $totalQuantityInCart += $carts->quantity;

                if ($current_Stock < $totalQuantityInCart) {
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.product_outofstock_msg').'!',
                        'cart_count' => $this->cartCount()
                    ], 200);
                }
    
                if($variantProduct->product->vat != 0){
                    $new_quantity = $carts->quantity + $quantity;
                    $tax = (($carts->offer_price * $new_quantity)/100) * $variantProduct->product->vat;
                }
                $carts->quantity        += $quantity;
                $carts->tax             = $tax;
                $carts->price           = $priceData['original_price'] ?? 0;
                $carts->offer_price     = $priceData['discounted_price'] ?? 0;
                $carts->offer_tag       = $priceData['offer_tag'] ?? NULL;
                $carts->save();
            }else {

                if ($current_Stock < $quantity) {
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.product_outofstock_msg').'!',
                        'cart_count' => $this->cartCount()
                    ], 200);
                }
               
                if($variantProduct->product->vat != 0){
                    $tax = (($priceData['discounted_price'] * ($quantity ?? 1))/100) * $variantProduct->product->vat;
                }
                $data[$users_id_type]           = ($users_id_type == 'user_id') ? $userId  : $guestToken;
                $data['product_id']             = $product_id;
                $data['product_stock_id']       = $product_stock_id;
                $data['quantity']               = $quantity;
                $data['price']                  = $priceData['original_price'] ?? 0;
                $data['offer_price']            = $priceData['discounted_price'] ?? 0;
                $data['offer_tag']              = $priceData['offer_tag'] ?? NULL;
                $data['tax']                    = $tax;
                $data['shipping_cost']          = 0;

                Cart::create($data);
            }

            return response()->json([
                'status' => true,
                'message' => trans('messages.product_add_cart_success'),
                'cart_count' =>  $this->cartCount()
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.product_add_cart_failed'),
                'cart_count' => $this->cartCount()
            ], 200); 
        }
    }

    public function getCartDetails()
    {
        $lang = getActiveLanguage();
        $response = $this->index();
        // echo '<pre>';
        // print_r($response);
        // die;
        return view('pages.cart',compact('response','lang'));
    }


    public function getCount(Request $request)
    {
        return response()->json([
            'success' => true,
            'cart_count' => $this->cartCount(),
        ]);
    }

    public function cartCount()
    {
        $user = getUser();

        return Cart::where([
            $user['users_id_type'] => $user['users_id']
        ])->count();
    }

    public function removeCartItem($id)
    {
        $cart_id = $id;
        $user = getUser();

        if ($cart_id != '' && $user['users_id'] != '') {
            Cart::where([
                $user['users_id_type'] => $user['users_id']
            ])->where('id', $cart_id)->delete();

            $updatedCart = Cart::where($user['users_id_type'], $user['users_id'])->get(); // Example for authenticated user

            // Return the updated cart summary
            $summary = $this->getCartSummary($updatedCart);

            return response()->json([
                'status' => true,
                'message' => trans('messages.cart_item_removed_success'),
                'updatedCartSummary' => $summary
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => trans('messages.cart_item_not_found'),
            ], 200);
        }
    }

    private function getCartSummary($cartItems)
    {
        $subTotal = $cartItems->sum('price');
        $discount = 0; // Add logic for discount
        $shipping = 0; // Add logic for shipping
        $vatAmount = $subTotal * 0.05; // Example VAT
        $total = $subTotal - $discount + $shipping + $vatAmount;

        return [
            'sub_total' => $subTotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'vat_amount' => $vatAmount,
            'total' => $total
        ];
    }


    public function changeQuantity(Request $request)
    {
        $cart_id    = $request->cart_id ?? '';
        $quantity   = $request->quantity ?? '';
        $action     = $request->action ?? '';
        $user       = getUser();

        if($cart_id != '' && $quantity != '' && $action != '' && $user['users_id'] != ''){
            $cart = Cart::where([
                $user['users_id_type'] => $user['users_id']
            ])->with([
                'product',
                'product_stock',
            ])->findOrFail($request->cart_id);
    
            $max_qty = $cart->product_stock->qty;

            if ($action == 'plus') {           // Increase quantity of a product in the cart.
                if ( $quantity <= $max_qty) {
                    $cart->quantity = $quantity;   // Update quantity of a product in the cart.
                    $cart->save();
                    return response()->json([
                        'status'    => true,
                        'message'   => "Cart updated",
                    ], 200);
                }else{
                    return response()->json([
                        'status'    => false,
                        'message'   => "Maximum quantity reached",
                    ], 200);
                }
            }elseif($action == 'minus'){   // Decrease quantity of a product in the cart. If it reaches zero then delete that row from the table.
                if($quantity < 1){
                    Cart::where('id',$cart->id)->delete();
                }else{
                    $cart->quantity = $quantity;        // Update quantity of a product in the cart.
                    $cart->save();
                }
                return response()->json([
                    'status'    => true,
                    'message'   => "Cart updated",
                ], 200);
            }else{
                return response()->json([
                    'status'    => false,
                    'message'   => "Undefined action value",
                ], 200);
            }
        } else {
            return response()->json([
                'status'    => false,
                'message'   => "Missing data"
            ], 200);
        }
    }

    
}
