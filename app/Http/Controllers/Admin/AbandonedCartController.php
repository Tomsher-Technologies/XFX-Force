<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use DB;
use Illuminate\Http\Request;

class AbandonedCartController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->put('cart_last_url', url()->full());
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;

        $query = Cart::whereStatus(0);

        if ($start_date !== '' && $start_date !== null) {
            $calc_end_date = ($end_date !== '' && $end_date !== null) ? $end_date : $start_date;
            $query->whereBetween('created_at', [$start_date . ' 00:00:00', $calc_end_date . ' 23:59:59']);
        }
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        // Calculate metrics
        $metricsQuery = clone $query;
        $total_abandoned_carts = (clone $metricsQuery)->groupBy(DB::raw('COALESCE(user_id, temp_user_id)'))->get()->count();
        $total_lost_revenue = (clone $metricsQuery)->sum(DB::raw('price * quantity'));
        $registered_carts = (clone $metricsQuery)->whereNotNull('user_id')->groupBy('user_id')->get()->count();
        $guest_carts = (clone $metricsQuery)->whereNull('user_id')->groupBy('temp_user_id')->get()->count();

        // Paginate carts with grouped aggregates
        $carts = $query->select('id', 'user_id', 'temp_user_id', 'created_at', 
            DB::raw('SUM(quantity) as total_quantity'), 
            DB::raw('SUM(price * quantity) as total_price')
        )
        ->groupBy(DB::raw('COALESCE(user_id, temp_user_id)'))
        ->latest()
        ->with(['user'])
        ->paginate(15);

        return view('backend.reports.abandoned_cart', compact(
            'carts', 
            'total_abandoned_carts', 
            'total_lost_revenue', 
            'registered_carts', 
            'guest_carts',
            'start_date',
            'end_date',
            'user_id'
        ));
    }

    public function view(Cart $cart)
    {
        $query = Cart::whereStatus(0)->with(['product'])->latest();

        if ($cart->user_id) {
            $query->where('user_id', $cart->user_id);
            $query->with(['user']);
        } else {
            $query->where('temp_user_id', $cart->temp_user_id);
        }
        $carts = $query->get();

        $total_quantity =  $carts->sum('quantity');
        $total_price = 0;

        foreach ($carts as $cart) {
            $total_price += $cart->quantity * $cart->price;
        }

        return view('backend.reports.abandoned_cart_details', compact('carts', 'total_quantity', 'total_price'));
    }
}
