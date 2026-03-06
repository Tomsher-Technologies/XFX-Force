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
        $cartItems = Cart::with(['product', 'product_stock', 'product.warranties'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get()
            ->map(function ($item) {
                $item->subtotal = $item->quantity * $item->price;
                $item->offerSum = $item->quantity * $item->offer_price;
                return $item;
            });

        $subtotal  = $cartItems->sum('subtotal');
        $offerSum  = $cartItems->sum('offerSum');
        $discountSum = $subtotal - $offerSum;
        $cartCount = $cartItems->sum('quantity');
        $shipping = 0;
        $tax = 0;
        $warrantySum = $cartItems->sum(function ($cart) {
            return optional($cart->product->warranties->first())->price ?? 0;
        });
        $total = $offerSum + $tax + $shipping + $warrantySum;

        return view('frontend.cart', compact('cartItems', 'subtotal', 'discountSum','cartCount','tax', 'shipping', 'total', 'warrantySum'))
            ->with('total', $total);
    }

    public function addProductToCart(Request $request)
    {
        $userId = Auth::id();
        $variantId = $request->variantId;
        $productId = $request->productId;
        $requestedQty = $request->quantity ?? 1;
        $mode = $request->mode ?? 'set';

        $stock = ProductStock::findOrFail($variantId);

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_stock_id', $variantId)
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
                'availableQty' => $stock->qty
            ]);
        }

        // Stock validation
        if ($newQty > $stock->qty) {
            return response()->json([
                'success' => false,
                'message' => "Only {$stock->qty} item(s) available.",
                'availableQty' => $stock->qty - $currentCartQty
            ]);
        }

        if ($cartItem) {

            $cartItem->quantity = $newQty;
            $cartItem->save();

        } else {

            Cart::create([
                'user_id' => $userId,
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
            'availableQty' => $stock->qty - $newQty
        ]);
    }

    /*public function addToCart(Request $request)
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
    }*/

    public function getCartDetails()
    {
        $lang = getActiveLanguage();
        $response = $this->index();
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
        $user = getUser();

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

    /**
     * Get cart summary details on page load or update or delete.
     *
     * @return array
     */
    public function getCartSummary()
    {
        $cartItems = Cart::with(['product', 'product_stock', 'product.warranties'])
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->get()
                ->map(function ($item) {
                    $item->subtotal = $item->quantity * $item->price;
                    $item->offer_sum = $item->quantity * $item->offer_price;
                    return $item;
                });
        $subTotal     = $cartItems->sum('subtotal');
        $offerSum     = $cartItems->sum('offer_sum');
        $discountSum  = $subTotal - $offerSum;
        $shipping     = 0;
        $tax          = 0;
        $cartCount    = $cartItems->sum('quantity');

        $defaultWarrantySum = $cartItems->sum(function ($cart) {
            return optional($cart->product->warranties->first())->price ?? 0;
        });

        $total        = $offerSum + $tax + $shipping + $defaultWarrantySum;

        

        return [
            'status' => true,
            'sub_total'    => $subTotal,
            'discount_sum' => $discountSum,
            'shipping'     => $shipping,
            'tax'   => $tax,
            'cart_count'   => $cartCount,
            'total'        => $total,
        ];
    }
}
