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
}


// $validatedData = $request->validate([
//             'billing_name' => 'required|string|max:255',
//             'billing_address' => 'required|string|max:255',
//             'billing_city' => 'required|string|max:255',
//             'billing_state' => 'required|string|max:255',
//             'billing_country' => 'required|string|max:255',
//             'billing_zipcode' => 'nullable|string',
//             'billing_phone' => 'required|string|min:10',  // Add phone validation
//             'billing_email' => 'required|email|max:255', // Add email validation
//             'shipping_name' => 'nullable|string|max:255',
//             'shipping_address' => 'nullable|string|max:255',
//             'shipping_city' => 'nullable|string|max:255',
//             'shipping_zipcode' => 'nullable|string',
//             'shipping_phone' => 'nullable|string|min:10', 
//             'shipping_state' => 'nullable|string|max:255',
//             'shipping_country' => 'nullable|string|max:255', 
//         ],[
//             'billing_name.required' => 'This field is required.',
//             'billing_address.required' => 'This field is required.',
//             'billing_city.required' => 'This field is required.',
//             'billing_state.required' => 'This field is required.',
//             'billing_country.required' => 'This field is required.',
//             'billing_zipcode.required' => 'This field is required.',
//             'billing_phone.required' => 'This field is required.',
//             'billing_phone.min' => 'The phone number must be at least 10 digits.',
//             'billing_email.required' => 'This field is required.',
//             'billing_email.email' => 'The email address must be a valid email.',
//             'billing_email.max' => 'The email address must not exceed 255 characters.',
//             'shipping_phone.min' => 'The phone number must be at least 10 digits.',
//         ]);