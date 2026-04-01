<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use DB;
use Auth;

class WishlistController extends Controller
{

    public function index(Request $request)
    {
        $lang = getActiveLanguage();
        $user_id = auth('frontend')->id();

        $wishlist = collect();

        if ($user_id) {
            $wishlist = Wishlist::with('product','product_stock')
                                ->where('user_id', $user_id)
                                ->paginate(30);

            $wishlist->getCollection()->transform(function ($data) {
                if ($data->product && $data->product_stock) {
                    return [
                        'id' => $data->id,
                        'product_id' => $data->product_id,
                        'stock_id' => $data->product_stock_id,
                        'thumbnail_img' => ($data->product_stock?->image && $data->product_stock?->image != '0')
                            ? $data->product_stock->image
                            : $data->product?->thumbnail_img,
                        'offer_tag' => $data->product_stock?->offer_tag,
                        'name' => $data->product_stock?->stock_title ?? $data->product?->name,
                        'offer_price' => $data->product_stock?->offer_price,
                        'price' => $data->product_stock?->price,
                        'page' => 'wishlist',
                    ];
                }
                return null;
            })->filter(); // remove null values
        }

        return view('frontend.user.wishlist', compact('lang', 'wishlist'));
    }

    public function getWishlistCount($user)
    {
        return Wishlist::where([
            'user_id' => $user
        ])->count();
    }

    public function getCount(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'wishlist_count' => $this->getWishlistCount($request->user()->id),
        ], 200);
    }

    public function destroy($id)
    {
        try {
            Wishlist::destroy($id);
            return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your wishlist')], 200);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 200);
        }

    }


    public function delete(Request $request)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())
                                ->where('id', $request->id)
                                ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json(['success' => true, 'message' => trans('messages.wishlist_product_removed')]);
        }

        return response()->json(['success' => false, 'message' => trans('messages.item_not_found')], 404);
    }

    public function toggle(Request $request)
    {
        $userId = auth()->id();
        $productId = $request->product_id;
        $stockId = $request->stock_id;

        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('product_stock_id', $stockId)
            ->first();

        if ($exists) {
            $exists->delete();

            return response()->json([
                'status' => 'removed'
            ]);
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'product_stock_id' => $stockId
            ]);

            return response()->json([
                'status' => 'added'
            ]);
        }
    }

    public function check(Request $request)
    {
        $exists = isWishlisted($request->product_id, $request->stock_id);

        return response()->json([
            'status' => $exists
        ]);
    }

}
