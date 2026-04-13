<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\PcBuilderSetup;
use Illuminate\Auth\Events\Login;

class MergeGuestCartListener
{
    public function handle(Login $event)
    {
        $user_id = auth('frontend')->check() ? auth('frontend')->user()->id : null;
        $guestToken = request()->cookie('guest_token');

        if (!$guestToken) {
            return;
        }

        // Cart management
        $guestCarts = Cart::where('temp_user_id', $guestToken)
            ->where('status', 'pending')
            ->get();

        foreach ($guestCarts as $cart) {

            $existing = Cart::where('user_id', $user_id)
                ->where('product_id', $cart->product_id)
                ->where('product_stock_id', $cart->product_stock_id)
                ->where('status', 'pending')
                ->first();

            if ($existing) {
                // merge quantity
                $existing->quantity += $cart->quantity;
                $existing->save();

                $cart->delete();
            } else {
                // move cart to user
                $cart->update([
                    'user_id' => $user_id,
                    'temp_user_id' => null
                ]);
            }
        }

        // Pc builder management 
        $builder = PcBuilderSetup::where('temp_user_id', $guestToken)
            ->where('is_ordered', false)
            ->first();

        if ($builder) {

            $existingBuilder = PcBuilderSetup::where('user_id', $user_id)
                ->where('is_ordered', false)
                ->first();

            if ($existingBuilder) {

                // merge build_data safely
                $existingBuilder->build_data = array_merge(
                    $existingBuilder->build_data ?? [],
                    $builder->build_data ?? []
                );

                $existingBuilder->save();

                $builder->delete();

            } else {

                $builder->update([
                    'user_id' => $user_id,
                    'temp_user_id' => null
                ]);
            }
        }

        // remove guest cookie after merge
        cookie()->queue(cookie()->forget('guest_token'));
    }
}