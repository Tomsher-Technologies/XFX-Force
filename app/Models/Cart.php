<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];
    
    protected $fillable = ['user_id', 'temp_user_id', 'product_id', 'product_stock_id', 'variation', 'quantity', 'metal_price', 'stone_price', 'making_charge', 'price', 'offer_price', 'offer_id', 'offer_tag', 'tax', 'shipping_cost', 'shipping_type', 'discount', 'offer_discount', 'coupon_code', 'coupon_applied', 'status','updated_at', 'warranty_id', 'warranty_price','pc_builder_id','is_pc_builder'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_stock()
    {
        return $this->belongsTo(ProductStock::class);
    }

    public static function cartItemsCount($userId = null)
    {
        $userId = $userId ?? auth()->id();

        return self::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
    }

    public static function updateCartPricesWithLatestPrices()
    {
        $user = getFrontEndUser();

        $cartItems = Cart::where([
            $user['users_id_type'] => $user['users_id'],
            'status' => 'pending'
        ])->get();

        foreach ($cartItems as $cart) {

            $stock = ProductStock::where('id', $cart->product_stock_id)->first();

            if (!$stock) continue;

            // Update cart
            $cart->update([
                'price' => $stock->price,
                'offer_price' => $stock->offer_price,
                'offer_tag' => $stock->offer_tag,
            ]);
        }
    }
}


