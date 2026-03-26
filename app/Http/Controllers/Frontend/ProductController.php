<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Resources\WebHomeProductsCollection;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\Category;
use App\Models\HomeSlider;
use App\Models\Page;
use App\Models\PageSeos;
use App\Models\PageTranslations;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductStock;
use App\Models\Review;
use App\Utility\SearchUtility;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Cache;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Mail;
use Storage;
use Validator;

class ProductController extends Controller
{
    private $frontController;

    function __construct(FrontendController $frontController)
    {
        $this->frontController = $frontController;
    }

    public function searchSuggestions(Request $request)
    {
        $sort_search = $request->get('search');
        $products = Product::where(function ($query) use ($sort_search) {
            $query->orWhereHas('stocks', function ($q) use ($sort_search) {
                $q->where('sku', 'like', '%' . $sort_search . '%');
            })->orWhereHas('product_translations', function ($q) use ($sort_search) {
                $q->where('tags', 'like', '%' . $sort_search . '%')->orWhere('name', 'like', '%' . $sort_search . '%');
            });
        })->where('published', 1)->limit(5)
            ->get();

        return response()->json($products);
    }

    public function loadMoreProducts(Request $request)
    {
        if ($request->ajax()) {
            $lang = getActiveLanguage();

            $price = $request->price_range;
            $min_price = $max_price = 0;
            if ($price != null) {
                $range = explode('-', $price);
                $min_price = $range[0];
                $max_price = $range[1];
            }

            $limit = $request->has('limit') ? $request->limit : 10;
            $offset = $request->has('offset') ? $request->offset : 0;
            $category = $request->has('category') ? $request->category  : false;
            $brand = $request->has('brand') ? $request->brand  : false;
            $sort_by = $request->has('sort_by') ? $request->sort_by : null;

            $product_query  = Product::wherePublished(1);
            $categoryData = null;
            if ($category) {
                $categoryData = Category::whereHas('category_translations', function ($query) use ($category) {
                    $query->where('slug', $category);
                })->where('is_active', 1)->first();

                $childIds = [];
                $category_ids = Category::whereHas('category_translations', function ($query) use ($category) {
                    $query->where('slug', $category);
                })->where('is_active', 1)->pluck('id')->toArray();

                $childIds[] = $category_ids;
                if (!empty($category_ids)) {
                    foreach ($category_ids as $cId) {
                        $childIds[] = getChildCategoryIds($cId);
                    }
                }

                if (!empty($childIds)) {
                    $childIds = array_merge(...$childIds);
                    $childIds = array_unique($childIds);
                }
                // print_r($childIds);
                // die;
                $product_query->whereIn('category_id', $childIds);
            }

            if ($brand) {
                $brand_ids = Brand::whereHas('brand_translations', function ($query) use ($brand) {
                    $query->where('slug', $brand);
                })->where('is_active', 1)->pluck('id')->toArray();

                $product_query->whereIn('brand_id', $brand_ids);
            }

            if ($sort_by) {
                switch ($sort_by) {
                    case 'latest':
                        $product_query->latest();
                        break;
                    case 'oldest':
                        $product_query->oldest();
                        break;
                    case 'name_asc':
                        $product_query->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $product_query->orderBy('name', 'desc');
                        break;
                    case 'price_high':
                        $product_query->select('*', DB::raw("
                                            (CASE 
                                                WHEN discount > 0 
                                                    AND (discount_start_date IS NULL OR discount_start_date <= NOW()) 
                                                    AND (discount_end_date IS NULL OR discount_end_date >= NOW()) 
                                                THEN 
                                                    CASE 
                                                        WHEN discount_type = 'percentage' 
                                                            THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - ((SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) * discount / 100)
                                                        WHEN discount_type = 'amount' 
                                                            THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - discount
                                                        ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                    END
                                                ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                            END) as sort_price
                                        "));
                        $product_query->orderBy('sort_price', 'desc');
                        break;
                    case 'price_low':
                        $product_query->select('*', DB::raw("
                                                (CASE 
                                                    WHEN discount > 0 
                                                        AND (discount_start_date IS NULL OR discount_start_date <= NOW()) 
                                                        AND (discount_end_date IS NULL OR discount_end_date >= NOW()) 
                                                    THEN 
                                                        CASE 
                                                            WHEN discount_type = 'percentage' 
                                                                THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - ((SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) * discount / 100)
                                                            WHEN discount_type = 'amount' 
                                                                THEN (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id) - discount
                                                            ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                        END
                                                    ELSE (SELECT MAX(price) FROM product_stocks WHERE product_id = products.id)
                                                END) as sort_price
                                            "));
                        $product_query->orderBy('sort_price', 'asc');
                        break;
                    default:
                        # code...
                        break;
                }
            }

            if ($request->search) {
                $sort_search = $request->search;
                $products = $product_query->where(function ($query) use ($sort_search) {
                    $query->orWhereHas('stocks', function ($q) use ($sort_search) {
                        $q->where('sku', 'like', '%' . $sort_search . '%');
                    })->orWhereHas('product_translations', function ($q) use ($sort_search) {
                        $q->where('tags', 'like', '%' . $sort_search . '%')->orWhere('name', 'like', '%' . $sort_search . '%');
                    });
                });
                // SearchUtility::store($sort_search, $request);
            }

            if ($max_price != 0 && $min_price != 0) {
                $product_query->whereHas('stocks', function ($query) use ($min_price, $max_price) {
                    $query->whereBetween('price', [$min_price, $max_price]);
                });
            }

            $products = $product_query->paginate(10)->appends($request->query());

            // Check if services exist and render the partial view
            if ($products->isEmpty()) {
                return response()->json(['html' => '', 'hasMore' => false]);
            }

            // Render the partial view and return it with a flag indicating if more pages are available
            $html = view('pages.product_card', ['products' => $products, 'lang' => $lang])->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $products->hasMorePages(),
            ]);
        }

        // Return a fallback if the request is not via AJAX
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function relatedProducts($limit, $offset, $product_slug, $category_slug)
    {

        $product_query = Product::with(['stocks'])->where('published', 1); // Prevent duplication

        if ($category_slug) {
            $category_ids = Category::whereHas('category_translations', function ($query) use ($category_slug) {
                $query->where('slug', $category_slug);
            })->pluck('id')->toArray();

            $childIds[] = $category_ids;
            if (!empty($category_ids)) {
                foreach ($category_ids as $cId) {
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if (!empty($childIds)) {
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }

            $product_query->whereIn('category_id', $category_ids);
        }
        $product_query->where('slug', '!=', $product_slug)->latest();

        $products = $product_query->skip($offset)->take($limit)->get();

        return $products;
    }

    // public function productDetails($id, $stockId = null)
    // {
    //     $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
    //     $guestToken = request()->cookie('guest_token');

    //     if(!$guestToken){
    //         $guestToken = uniqid('guest_', true);
    //         cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
    //     }

    //     $product = Product::with([
    //         'stocks',
    //         'stocks.attributes.attribute',
    //         'stocks.attributes.value'
    //     ])->findOrFail($id);

    //     $relatedProducts = Product::where('category_id', $product->category_id)
    //         ->where('id', '!=', $product->id)
    //         ->get();

    //     // determine selected stock
    //     $selectedStock = $stockId 
    //         ? $product->stocks->where('id', $stockId)->first()
    //         : $product->stocks->first();

    //     // get cart quantity
    //     $cartQty = 0;

    //     if ($selectedStock) {
    //         $cartQuery = Cart::where('product_id', $product->id)
    //             ->where('product_stock_id', $selectedStock->id)
    //             ->where('status', 'pending')
    //             ->where(function($query) use ($guestToken, $user_id) {
    //                 if($user_id) {
    //                     // Logged-in user
    //                     $query->where('user_id', $user_id);
    //                 } else {
    //                     // Guest user
    //                     $query->where('temp_user_id', $guestToken);
    //                 }
    //             });
    //         $cartQty = $cartQuery->value('quantity') ?? 0;
    //     }
        
    //     return view('frontend.productDetails', compact('product', 'relatedProducts','stockId', 'cartQty'));
    // }

    public function productDetails($slug, $sku)
    {
        // Get logged-in user or guest token
        $user_id = auth('frontend')->check() ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');

        if (!$guestToken) {
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }

        // Fetch product by slug
        $product = Product::with([
            'stocks',
            'stocks.attributes.attribute',
            'stocks.attributes.value'
        ])->where('slug', $slug)->firstOrFail();

        // Related products (same category, excluding this product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->get();

        // Determine selected stock by SKU or default first stock
        $selectedStock = $sku 
            ? $product->stocks->where('sku', $sku)->first()
            : $product->stocks->first();

        // Get cart quantity
        $cartQty = 0;
        if ($selectedStock) {
            $cartQuery = Cart::where('product_id', $product->id)
                ->where('product_stock_id', $selectedStock->id)
                ->where('status', 'pending')
                ->where(function($query) use ($guestToken, $user_id) {
                    if ($user_id) {
                        // Logged-in user
                        $query->where('user_id', $user_id);
                    } else {
                        // Guest user
                        $query->where('temp_user_id', $guestToken);
                    }
                });
            $cartQty = $cartQuery->value('quantity') ?? 0;
        }

       $stockId = $selectedStock ? $selectedStock->id : null;

        return view('frontend.productDetails', compact(
            'product', 
            'relatedProducts', 
            'selectedStock',
            'cartQty',
            'stockId'
        ));
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $view = $request->get('view', 'gridview');

        $products = Product::select('products.*')
            ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id');

        // Filters
        if ($request->filled('categories')) {
            $products->whereIn('products.category_id', $request->categories);
        }

        if ($request->filled('brands')) {
            $products->whereIn('products.brand_id', $request->brands);
        }

        if ($request->filled('min_price')) {
            $products->where('product_stocks.price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $products->where('product_stocks.price', '<=', $request->max_price);
        }

        // Sorting
        switch ($sort) {
            case 'oldest':
                $products->orderBy('products.created_at', 'asc');
                break;
            case 'price_low_high':
                $products->orderBy('product_stocks.price', 'asc');
                break;
            case 'price_high_low':
                $products->orderBy('product_stocks.price', 'desc');
                break;
            default:
                $products->orderBy('products.created_at', 'desc');
                break;
        }

        $products = $products->with('stocks')->distinct()->get();
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        if ($products->isEmpty()) {
            if ($request->ajax()) {
                return response()->json([
                    'No Products Found!',
                ]);
            }
        }
        // If AJAX request, return only product list partial
        if ($request->ajax()) {
            return view('frontend.partials.product-list', compact('products', 'view'))->render();
        }

        return view('frontend.products', compact('products', 'categories', 'brands', 'sort', 'view'));
    }



    public function getVariantsByValue(Request $request)
    {
        $valueId     = $request->value_id;
        $productId   = $request->product_id;
        $attributeId = $request->attribute_id;
        $selectedAttributes = json_decode($request->selectedAttributes, true) ?? [];


        // Step 1: Find matching variant IDs
        $matchingStockIds = ProductAttributes::where('product_id', $productId)
            ->whereIn('attribute_id', array_keys($selectedAttributes))
            ->whereIn('attribute_value_id', array_values($selectedAttributes))
            ->select('product_varient_id', 'attribute_id')
            ->groupBy('product_varient_id')
            ->havingRaw('COUNT(*) = ?', [count($selectedAttributes)])
            ->pluck('product_varient_id');
        // ->pluck('product_varient_id');

        // Step 3: Get only attributes AFTER clicked row
        $relatedAttributes = ProductAttributes::where('product_id', $productId)
            ->whereIn('product_varient_id', $matchingStockIds)
            ->whereNotIn('attribute_id', array_keys($selectedAttributes))
            ->orderBy('id')
            ->get();

        // Step 3: Format return array properly
        $formatted = $relatedAttributes->map(function ($attr) {
            return [
                'variant_id'   => $attr->product_varient_id,
                'attribute_id' => $attr->attribute_id,
                'value_id'     => $attr->attribute_value_id,
            ];
        })->values();




        return response()->json([
            'success' => true,
            'data' => $formatted
        ]);
    }

    public function getVarientDetails(Request $request)
    {
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $guestToken = request()->cookie('guest_token');

        if(!$guestToken){
            $guestToken = uniqid('guest_', true);
            cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
        }
        
        
        $productId   = $request->productId;
        $product = Product::where('id', $productId)->first();
        $selectedAttributes = json_decode($request->selectedAttributes, true) ?? [];
        

        // For single product
        if (empty($selectedAttributes)) {

            $variant = ProductStock::where('product_id', $productId)->first();

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'No variant found'
                ]);
            }

            $cartQty = Cart::where('product_stock_id', $variant->id)
                ->where('status', 'pending')
                ->where(function($query) use ($guestToken, $user_id) {
                    if($user_id) {
                        // Logged-in user
                        $query->where('user_id', $user_id);
                    } else {
                        // Guest user
                        $query->where('temp_user_id', $guestToken);
                    }
                })
                ->sum('quantity');

            $availableQty = $variant->qty - $cartQty;

            return response()->json([
                'success' => true,
                'data' => [
                    'variant_id' => $variant->id,
                    'variant_sku' => $variant->sku ?? '',
                    'slug' => $product->slug ?? '',
                    'title' => $variant->stock_title ?? $variant->product->name,
                    'price' => $variant->price,
                    'offer_price' => $variant->offer_price,
                    'image' => $variant->image ?? '',
                    'cartQty' => $cartQty,
                    'availableQty' => $availableQty,
                ]
            ]);
        }
        
        // For product with variants
        $variantIds = ProductAttributes::where('product_id', $productId)
            ->whereIn('attribute_id', array_keys($selectedAttributes))
            ->whereIn('attribute_value_id', array_values($selectedAttributes))
            ->pluck('product_varient_id');


        // 2️⃣ Group by variant_id and count matching attributes
        $matchingVariant = ProductAttributes::whereIn('product_varient_id', $variantIds)
            ->where('product_id', $productId)
            ->whereIn('attribute_id', array_keys($selectedAttributes))
            ->whereIn('attribute_value_id', array_values($selectedAttributes))
            ->select('product_varient_id')
            ->groupBy('product_varient_id')
            ->havingRaw('COUNT(*) = ?', [count($selectedAttributes)]) // must match all selected attributes
            ->first();

        // 3️⃣ Get the variant details
        if ($matchingVariant) {
            $variant = ProductStock::find($matchingVariant->product_varient_id);
            // calculate stock
            $cartQty = Cart::where('product_stock_id', $variant->id)
                ->where('status', 'pending')
                ->where(function($query) use ($guestToken, $user_id) {
                    if($user_id) {
                        // Logged-in user
                        $query->where('user_id', $user_id);
                    } else {
                        // Guest user
                        $query->where('temp_user_id', $guestToken);
                    }
                })
                ->sum('quantity');

            $availableQty = $variant->qty - $cartQty;
            return response()->json([
                'success' => true,
                'data' => [
                    'variant_id' => $variant->id,
                    'variant_sku' => $variant->sku ?? '',
                    'slug' => $product->slug ?? '',
                    'title' => $variant->stock_title,
                    'price' => $variant->price,
                    'offer_price' => $variant->offer_price,
                    'image' => $variant->image ?? '',
                    'cartQty' => $cartQty,
                    'availableQty' => $availableQty,
                ]
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'No matching variant']);
        }
    }

    public function shopByCategory(Request $request, $categoryId)
    {
        $sort = $request->get('sort', 'newest');
        $view = $request->get('view', 'gridview');

        // Get current category
        $category = Category::withCount('products')->findOrFail($categoryId);


        $products = Product::select('products.*')
            ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id');

        // Category from URL (default filter)
        $products->where('products.category_id', $categoryId);

        // Filters
        if ($request->filled('categories')) {
            $products->whereIn('products.category_id', $request->categories);
        }

        if ($request->filled('brands')) {
            $products->whereIn('products.brand_id', $request->brands);
        }

        if ($request->filled('min_price')) {
            $products->where('product_stocks.price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $products->where('product_stocks.price', '<=', $request->max_price);
        }

        // Sorting
        switch ($sort) {
            case 'oldest':
                $products->orderBy('products.created_at', 'asc');
                break;

            case 'price_low_high':
                $products->orderBy('product_stocks.price', 'asc');
                break;

            case 'price_high_low':
                $products->orderBy('product_stocks.price', 'desc');
                break;

            default:
                $products->orderBy('products.created_at', 'desc');
                break;
        }

        $products = $products->with('stocks')->distinct()->get();

        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        if ($products->isEmpty()) {
            if ($request->ajax()) {
                return response()->json([
                    'No Products Found!',
                ]);
            }
        }

        // Total products for this category after filters
        $productCount = $products->count();
        // If AJAX request, return only product list partial
        if ($request->ajax()) {
            return view('frontend.partials.product-list', compact('products', 'view'))->render();
        }

        return view('frontend.shop-by-category', compact(
            'products',
            'categories',
            'brands',
            'sort',
            'view',
            'categoryId',
            'category',
            'productCount'
        ));
    }
}
