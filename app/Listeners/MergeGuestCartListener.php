<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\PcBuilderSetup;
use Illuminate\Auth\Events\Login;

class MergeGuestCartListener
{
    public function handle(Login $event)
    {
        // $user_id = auth('frontend')->check() ? auth('frontend')->user()->id : null;
        $user = $event->user;
        $user_id = $user->id;
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


        // =========================
        // PC BUILDER SYNC LOGIC
        // =========================

        $guestBuilder = PcBuilderSetup::where('temp_user_id', $guestToken)->first();

        if ($guestBuilder) {

            // 1. Remove old user builder cart items
            $oldBuilders = PcBuilderSetup::where('user_id', $user_id)->get();

            foreach ($oldBuilders as $oldBuilder) {
                Cart::where('user_id', $user_id)
                    ->where('is_pc_builder', 1)
                    ->where('pc_builder_id', $oldBuilder->id)
                    ->delete();
            }

            // 2. Delete old builder
            PcBuilderSetup::where('user_id', $user_id)->delete();

            // 3. Transfer guest builder to user
            $guestBuilder->update([
                'user_id' => $user_id,
                'temp_user_id' => null
            ]);
        }
        cookie()->queue(cookie()->forget('guest_token'));
    }
}
