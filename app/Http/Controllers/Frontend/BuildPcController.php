<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\PcBuilderCategorySetting;
use App\Models\PcBuilderSetup;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class BuildPcController extends Controller
{
    public function index()
    {
        // Left box categories
        $builderCategories = PcBuilderCategorySetting::with('category')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        // Load first category products by default
        $firstCategory = $builderCategories->first();

        $products = [];

        if ($firstCategory) {
            $products = Product::where('category_id', $firstCategory->category_id)
                ->with('stocks')
                ->where('published', 1)
                ->get();
        }

        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $builder = PcBuilderSetup::where('user_id', $user_id)
            ->where('is_ordered', false)
            ->first();
        $buildData = $builder ? $builder->build_data : [];

        $reviewProducts = [];

        if ($buildData) {
            $reviewProducts = $this->getReviewProducts($buildData);
        }

        $brands = Brand::where('is_active', 1)->get();

        return view('frontend.buildyourpc', compact(
            'builderCategories',
            'products',
            'firstCategory',
            'builder',
            'buildData',
            'reviewProducts',
            'brands'
        ));
    }

    // AJAX to get products by category
    public function getProductsByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $brandId = $request->brand_id;

        $products = Product::where('category_id', $categoryId)
        ->where('published', 1)
        ->when(!empty($brandId) && $brandId != 0, function ($query) use ($brandId) {
            $query->whereNotNull('brand_id') // skip null brands
                  ->where('brand_id', $brandId);
        })
        ->when($request->search, function ($query) use ($request) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        })
        // Join stocks only for sorting
        ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')
        ->select('products.*') // Only select product columns
        ->when($request->sort, function ($query) use ($request) {
            if($request->sort == 'price_low_high'){
                $query->orderBy('product_stocks.offer_price','asc');
            }
            elseif($request->sort == 'price_high_low'){
                $query->orderBy('product_stocks.offer_price','desc');
            }
        })
        ->distinct() // REMOVE duplicates caused by join
        ->with('stocks') // load stocks for display
        ->get();

        // If no products found, return a message
        if($products->isEmpty()){
            return response()->json([
                'html' => '<div class="text-center text-gray-400 py-10">No Products Found</div>'
            ]);
        }

        // Return rendered HTML for middle section
        $html = view('frontend.partials.pc-builder-products-list', compact('products'))->render();

        return response()->json(['html' => $html]);
    }

    public function getProductDetails(Request $request)
    {
        $stockId = $request->stockId;

        $stock = ProductStock::with('product')->find($stockId);
        if (!$stock) return response()->json(['error' => 'Product not found'], 404);

        // Return rendered HTML for middle section
        $html = view('frontend.partials.pc-builder-products-single-details', compact('stock'))->render();

        return response()->json(['html' => $html]);
    }


    public function savePcBuilder(Request $request)
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        if ($request->builder_id) {
            $builder = PcBuilderSetup::find($request->builder_id);
        } else {
            $builder = PcBuilderSetup::firstOrCreate(
                ['user_id' => $user_id],
                ['build_data' => []]
            );
        }

        $data = $builder->build_data ?? [];

        $categoryId = $request->categoryId;

        // Create category key if not exists
        if (!isset($data[$categoryId])) {
            $data[$categoryId] = [];
        }

        $found = false;

        foreach ($data[$categoryId] as $key => $item) {
            if ($item['product_id'] == $request->productId && $item['variant_id'] == $request->variantId) {
                $found = true;

                // remove and reindex array when quantity = 0.
                if ($request->qty == 0) {
                    unset($data[$categoryId][$key]);
                    $data[$categoryId] = array_values($data[$categoryId]);

                    // If no products left in this category, remove the category entirely
                    if (empty($data[$categoryId])) {
                        unset($data[$categoryId]);
                    }
                } else {
                    $data[$categoryId][$key]['quantity'] = $request->qty;
                }

                break;
            }
        }

        if (!$found) {
            $data[$categoryId][] = [
                'product_id' => $request->productId,
                'variant_id' => $request->variantId,
                'quantity' => $request->qty
            ];
        }


        $builder->build_data = $data;
        $builder->save();

        return response()->json([
            'status' => true,
            'builder_id' => $builder->id,
            'build_data' => $builder->build_data
        ]);
    }

    private function getReviewProducts($buildData)
    {
        $reviewProducts = [];

        $totalPrice = 0;
        $totalQty = 0;
        $totalDiscount = 0;
        $totalTax = 0;
        $totalWithTax = 0;

        $defaultVat = get_setting('default_vat') ?? 0;

        foreach ($buildData as $categoryId => $products) {
            $category = Category::find($categoryId);

            foreach ($products as $item) {
                $product = Product::find($item['product_id']);
                $variant = ProductStock::find($item['variant_id']);
                $quantity = $item['quantity'] ?? 1;

                if ($product && $variant) {
                    $itemTotal = $variant->price * $quantity;
                    $itemOfferTotal = $variant->offer_price * $quantity;
                    $discountSum = $itemTotal - $itemOfferTotal;

                    // Tax calculation
                    $itemTax = ($itemOfferTotal * $defaultVat) / 100;

                    // Total including tax
                    $itemTotalWithTax = $itemOfferTotal + $itemTax;

                    $reviewProducts[] = [
                        'category_id' => $categoryId,
                        'category_name' => $category->name ?? '',
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'image' => $variant->image ?: $product->thumbnail_img ?: '',
                        'price' => $variant->offer_price ?? 0,
                        'quantity' => $quantity,
                        'variant_id' => $variant->id,
                        'variant_name' => $variant->stock_title ?? '',
                        'offer_price' => $variant->offer_price ?? 0,
                        'total_price' => $itemTotal,
                        'total_offer_price' => $itemOfferTotal,
                        'discount_sum' => $discountSum,
                        'item_tax' => $itemTax,
                        'item_total_with_tax' => $itemTotalWithTax,
                    ];

                    // Aggregate totals
                    $totalPrice += $itemTotal;
                    $totalDiscount += $discountSum;
                    $totalQty += $quantity;
                    $totalTax += $itemTax;
                    $totalWithTax += $itemTotalWithTax;
                }
            }
        }

        return [
            'products' => $reviewProducts,
            'total_price' => $totalPrice,
            'total_qty' => $totalQty,
            'total_discount' => $totalDiscount,
            'total_tax' => $totalTax,
            'total_with_tax' => $totalWithTax,
        ];
    }

    // Function to get the latest build data.
    public function getBuildData()
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $builder = PcBuilderSetup::where('user_id', $user_id)->first();
        $buildData = $builder ? $builder->build_data : [];

        $reviewData = [
            'products' => [],
            'total_price' => 0,
            'total_qty' => 0,
            'total_discount' => 0,
            'total_tax' => 0,
            'total_with_tax' => 0,
        ];

        
        if ($buildData) {
            $reviewData = $this->getReviewProducts($buildData);
        }

        $html = view(
            'frontend.partials.pc-builder-products-review',
            ['reviewProducts' => $reviewData['products']]
        )->render();

        return response()->json([
            'html' => $html,
            'total_price' => $reviewData['total_price'],
            'total_qty' => $reviewData['total_qty'],
            'total_discount' => $reviewData['total_discount'],
            'total_tax' => $reviewData['total_tax'],
            'total_with_tax' => $reviewData['total_with_tax'],
        ]);
    }

    public function placePcBuilderOrder(Request $request)
    {
        $userId = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';

        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }


        $builderId = $request->builder_id;
        $builder = PcBuilderSetup::find($builderId);

        if (!$builder) {
            return response()->json([
                'status' => false,
                'message' => 'Builder not found'
            ]);
        }

        $buildData = $builder->build_data;
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        // Remove old builder cart items
        Cart::where('pc_builder_id', $builderId)
            ->where(function($query) use ($guestToken, $user_id) {
                    if($user_id) {
                        // Logged-in user
                        $query->where('user_id', $user_id);
                    } else {
                        // Guest user
                        $query->where('temp_user_id', $guestToken);
                    }
                })
            ->delete();

        foreach ($buildData as $categoryId => $products) {

            foreach ($products as $item) {

                $variant = ProductStock::find($item['variant_id']);

                if (!$variant) continue;

                Cart::create([
                    'user_id' => $userId,
                    'temp_user_id' => $userId ? null : $guestToken,
                    'product_id' => $item['product_id'],
                    'product_stock_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $variant->price ?? 0,
                    'offer_price' => $variant->offer_price ?? 0,
                    'offer_tag' => $variant->offer_tag ?? null,
                    'shipping_cost' => 0,
                    'status' => 'pending',
                    'pc_builder_id' => $builderId,
                    'is_pc_builder' => 1                
                ]);
            }
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Products added to cart'
        ]);
    }

    public function resetConfiguration(Request $request)
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }
        $builderId = $request->builder_id;

        Cart::where('pc_builder_id', $builderId)
            ->where(function($query) use ($guestToken, $user_id) {
                if($user_id) {
                    // Logged-in user
                    $query->where('user_id', $user_id);
                } else {
                    // Guest user
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->delete();

        $builder = PcBuilderSetup::where('id', $builderId)
            ->where(function($query) use ($guestToken, $user_id) {
                if($user_id) {
                    // Logged-in user
                    $query->where('user_id', $user_id);
                } else {
                    // Guest user
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->first();

        if (!$builder) {
            return response()->json([
                'status' => false,
                'message' => 'Configuration not found'
            ]);
        }

        
        return response()->json([
            'status' => true,
            'message' => 'Configuration reset successfully'
        ]);
    }
}
