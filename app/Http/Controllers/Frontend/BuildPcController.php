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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BuildPcController extends Controller
{
    public function index()
    {
        // Left box categories
        $builderCategories = PcBuilderCategorySetting::with('category')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        // First category
        $firstCategory = $builderCategories->first();

        // Default empty paginator
        $stocks = collect();

        if ($firstCategory) {

            // Get category + child categories
            $categoryIds = Category::where('id', $firstCategory->category_id)
                ->orWhere('parent_id', $firstCategory->category_id)
                ->pluck('id')
                ->toArray();

            // FIXED QUERY (no join, uses whereHas)
            $stocks = ProductStock::with(['product.brand', 'product.reviews'])
                ->whereHas('product', function ($query) use ($categoryIds) {
                    $query->where('published', 1)
                        ->whereIn('category_id', $categoryIds);
                })
                ->latest()
                ->paginate(30);
        }

        // User / Guest handling
        $user_id = auth('frontend')->check() ? auth('frontend')->user()->id : null;
        $guestToken = request()->cookie('guest_token');

        $builder = PcBuilderSetup::where('is_ordered', false)
            ->where(function ($query) use ($user_id, $guestToken) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                } else {
                    $query->where('temp_user_id', $guestToken);
                }
            })
            ->first();

        $buildData = $builder ? $builder->build_data : [];

        $reviewProducts = [];

        if ($buildData) {
            $reviewProducts = $this->getReviewProducts($buildData);
        }

        $brands = Brand::where('is_active', 1)->get();

        return view('frontend.buildyourpc', compact(
            'builderCategories',
            'stocks',
            'firstCategory',
            'builder',
            'buildData',
            'reviewProducts',
            'brands',
        ));
    }

    // AJAX to get products by category
    public function getProductsByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $brandId    = $request->brand_id;
        $modelName  = $request->model;
        $search     = $request->search;
        $sort       = $request->sort;

        $stocks = ProductStock::with(['product.brand', 'product.reviews'])
            ->whereHas('product', function ($query) {
                $query->where('published', 1);
            })
            ->select('product_stocks.*');

        if ($categoryId) {
            $categoryIds = Category::where('id', $categoryId)
                ->orWhere('parent_id', $categoryId)
                ->pluck('id')
                ->toArray();

            $stocks->whereHas('product', function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            });
        }
        if ($brandId && $brandId != 0) {
            $stocks->whereHas('product', function ($q) use ($brandId) {
                $q->where('brand_id', $brandId);
            });
        }

        // model filter
        if ($modelName && $modelName != 'All') {
            $stocks->where('product_stocks.model', $modelName);
        }


        if ($search) {
            $stocks->where(function ($query) use ($search) {
                $query->where('product_stocks.stock_title', 'LIKE', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // sorting
        if ($sort == 'price_low_high') {
            $stocks->orderBy('product_stocks.offer_price', 'asc');
        } elseif ($sort == 'price_high_low') {
            $stocks->orderBy('product_stocks.offer_price', 'desc');
        } else {
            $stocks->latest(); // default
        }

        // PAGINATION
        $stocks = $stocks->paginate(30);

        $html = view('frontend.partials.pc-builder-products-list', [
            'stocks' => $stocks
        ])->render();

        return response()->json([
            'html' => $html,
            'next_page' => $stocks->hasMorePages() ? $stocks->currentPage() + 1 : null,
        ]);
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
        $user_id = auth('frontend')->check() ? auth('frontend')->user()->id : null;
        $guestToken = request()->cookie('guest_token');

        if (!$guestToken) {
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60 * 24 * 14); // 14 days
        }

        if ($request->builder_id) {
            $builder = PcBuilderSetup::find($request->builder_id);
        } else {

            if ($user_id) {
                $builder = PcBuilderSetup::firstOrCreate(
                    ['user_id' => $user_id],
                    [
                        'temp_user_id' => null,
                        'build_data' => []
                    ]
                );
            } else {
                $builder = PcBuilderSetup::firstOrCreate(
                    ['temp_user_id' => $guestToken],
                    [
                        'user_id' => null,
                        'build_data' => []
                    ]
                );
            }
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

        if (!$found && $request->qty > 0) {
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
                        'price' => $variant->price ?? 0,
                        'quantity' => $quantity,
                        'variant_id' => $variant->id,
                        'variant_name' => $variant->stock_title ?? '',
                        'offer_price' => $variant->offer_price ?? 0,
                        'offer_tag' => $variant->offer_tag ?? '',
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
        $user_id = auth('frontend')->check() ? auth('frontend')->user()->id : null;
        $guestToken = request()->cookie('guest_token');
        
        $builder = PcBuilderSetup::when($user_id, function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        }, function ($query) use ($guestToken) {
            $query->where('temp_user_id', $guestToken);
        })
        ->first();
        
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
        DB::beginTransaction();

        try {

            $userId = auth('frontend')->user()->id ?? null;
            $guestToken = request()->cookie('guest_token');

            if (!$guestToken) {
                $guestToken = uniqid('guest_', true);
                cookie()->queue('guest_token', $guestToken, 60 * 24 * 14);
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

            Cart::where('pc_builder_id', $builderId)
                ->where(function ($query) use ($guestToken, $userId) {
                    $userId
                        ? $query->where('user_id', $userId)
                        : $query->where('temp_user_id', $guestToken);
                })
                ->delete();

            foreach ($buildData as $products) {

                foreach ($products as $item) {

                    $variant = ProductStock::find($item['variant_id']);
                    if (!$variant) continue;

                    $cartQty = Cart::where('product_stock_id', $item['variant_id'])
                        ->where('status', 'pending')
                        ->where(function ($query) use ($guestToken, $userId) {
                            $userId
                                ? $query->where('user_id', $userId)
                                : $query->where('temp_user_id', $guestToken);
                        })
                        ->sum('quantity');

                    $availableQty = $variant->qty - $cartQty;

                    if ($item['quantity'] > $availableQty) {

                        DB::rollBack();

                        return response()->json([
                            'status' => false,
                            'message' => "Stock changed for " . ($variant->stock_title ?: $variant->product->name) .
                                        ". Only {$availableQty} item available."
                        ]);
                    }

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

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Products added to cart'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function resetConfiguration(Request $request)
    {
        $resetType = $request->input('reset_type', 'full'); // default full
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');
        $builderId = $request->builder_id;

        if ($resetType === 'full') {
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
        }

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

        // Reset configuration
         if ($resetType === 'full') {
            $builder->build_data = [];
            $builder->save();
        }

        
        return response()->json([
            'status' => true,
            'message' => 'Configuration reset successfully'
        ]);
    }

    public function getModels(Request $request)
    {
        $brandId = $request->brand_id;
        $categoryId = $request->category_id;

        $query = DB::table('product_stocks as ps')
            ->select('ps.model')
            ->distinct()
            ->leftJoin('products as p', 'p.id', '=', 'ps.product_id');

        if ($brandId && $brandId != 0) {
            $query->where('p.brand_id', $brandId);
        }

        if ($categoryId) {
            $query->where('p.category_id', $categoryId);
        }

        $query->whereNotNull('ps.model')
          ->where('ps.model', '!=', '');

        $models = $query->pluck('model');

        return response()->json($models);
    }

    public function deletePcBuilder(Request $request)
    {
         $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');
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

        // $builder->delete();

        return response()->json([
            'status' => true,
            'message' => 'Configuration deleted successfully',
            'redirect_url' => route('cart') // or your pc builder route
        ]);
    }
}
