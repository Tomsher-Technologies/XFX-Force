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
        $cartItems = Cart::with(['product', 'product_stock', 'product.warranties'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get()
            ->map(function ($item) {
                $item->subtotal = $item->quantity * $item->price;
                $item->offerSum = $item->quantity * $item->offer_price;
                return $item;
            });

        // direct cart items
        $directCartItems = $cartItems->where('is_pc_builder', 0);

        // PC Builder cart items
        $pcBuilderItems = $cartItems->where('is_pc_builder', 1)
        ->groupBy('pc_builder_id');

        $subtotal  = $cartItems->sum('subtotal');
        $offerSum  = $cartItems->sum('offerSum');
        $discountSum = $subtotal - $offerSum;
        $cartCount = $cartItems->count();
        
        $defaultVat = get_setting('default_vat') ?? 0;
        $tax = ($offerSum * $defaultVat) / 100;
        $warrantySum  = $cartItems->sum('warranty_price');

        $shipping = get_setting('default_shipping_amount') ?? 0;
        $freeShippingMinAmount = get_setting('free_shipping_min_amount') ?? 0;

        if ($offerSum >= $freeShippingMinAmount) {
            $shipping = 0;
        }
        
        $total = $offerSum + $tax + $shipping + $warrantySum;

        return view('frontend.cart', compact('cartItems', 'subtotal', 'discountSum','cartCount','tax', 'shipping', 'total', 'warrantySum', 'pcBuilderItems','directCartItems'))
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

    public function updateProductWarranty(Request $request)
    {
        $cartId = $request->cartId;
        $warrantyId = $request->warrantyId;

        $cart = Cart::where('id', $cartId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $warranty = ProductWarranty::find($warrantyId);

        $cart->warranty_id = $warranty->id;
        $cart->warranty_price = $warranty->price ?? 0;
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

        $shipping = get_setting('default_shipping_amount') ?? 0;
        $freeShippingMinAmount = get_setting('free_shipping_min_amount') ?? 0;

        if ($offerSum >= $freeShippingMinAmount) {
            $shipping = 0;
        }

        $defaultVat = get_setting('default_vat') ?? 0;
        $tax = ($offerSum * $defaultVat) / 100;
        
        $cartCount    = $cartItems->sum('quantity');
        $warrantySum  = $cartItems->sum('warranty_price');
        $total        = $offerSum + $tax + $shipping + $warrantySum;

        

        return [
            'status' => true,
            'sub_total'    => $subTotal,
            'discount_sum' => $discountSum,
            'shipping'     => $shipping,
            'tax'   => $tax,
            'cart_count'   => $cartCount,
            'warranty_sum' => $warrantySum,
            'total'        => $total,
        ];
    }
}
