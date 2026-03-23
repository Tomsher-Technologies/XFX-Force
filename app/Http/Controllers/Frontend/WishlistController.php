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
        $products = [];
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        if($user_id != ''){
            $wishlist = Wishlist::with('product','product_stock')->where('user_id', $user_id)->get();
           
            if($wishlist){
                foreach($wishlist as $data){
                    if($data->product && $data->product_stock){
                        $priceData = getProductPrice($data->product_stock);

                        $products[] = [
                            'id' => (int) $data->id,
                            'product' => [
                                'variant_id' => $data->product_stock->id ?? '',
                                'product_id' => $data->product_id ?? '',
                                'name' => $data->product->getTranslation('name', $lang) ?? '',
                                'sku' => $data->product_stock->sku ?? '',
                                'slug' => $data->product->slug ?? '',
                                'thumbnail_image' => ($data->product_stock->image != NULL && $data->product_stock->image != '0') ? get_product_image($data->product_stock->image,'300') : get_product_image($data->product->thumbnail_img,'300'),
                                'stroked_price' => $priceData['discounted_price'] ?? 0,
                                'main_price' => $priceData['original_price'] ?? 0,
                                'min_qty' => $data->product->min_qty ?? 0,
                                'quantity' => $data->product_stock->qty ?? 0,
                                'offer_tag' => $priceData['offer_tag'] ?? '',
                                'attributes' => getProductAttributes($data->product_stock->attributes)
                            ]
                        ];
                    }
                }    
            }
        }
        // echo '<pre>';
        // print_r($result);
        // die;

        return view('frontend.user.wishlist',compact('lang','products','wishlist'));
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

}
