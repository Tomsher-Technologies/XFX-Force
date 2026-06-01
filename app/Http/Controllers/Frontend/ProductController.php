<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Resources\WebHomeProductsCollection;
use App\Models\Brand;
use App\Models\Banner;
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

    public function loadSEO($model)
    {
        SEOTools::setTitle($model['title']);
        OpenGraph::setTitle($model['title']);
        TwitterCard::setTitle($model['title']);

        SEOMeta::setTitle($model['title']);
        SEOMeta::setDescription($model['meta_description']);
        SEOMeta::addKeyword($model['keywords']);

        OpenGraph::setTitle($model['og_title']);
        OpenGraph::setDescription($model['og_description']);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addProperty('locale', 'en_US');
        OpenGraph::addProperty('type', $model['og_type'] ?? 'website');
        OpenGraph::addImage(uploaded_asset(get_setting('default_seo_og_image')) ?? URL::to(asset('assets/img/logo.png')));

        JsonLd::setTitle($model['title']);
        JsonLd::setDescription($model['meta_description']);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model['twitter_title']);
        TwitterCard::setSite('@pcgarage');
        TwitterCard::setDescription($model['twitter_description']);

        SEOTools::jsonLd()->addImage(URL::to(asset('assets/img/favicon.ico')));
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
            if($request->filled('condition')) {
                $product_query->where('condition', $request->condition);
            }
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

            $product_query->whereIn('category_id', $category_ids);
        }
        $product_query->where('slug', '!=', $product_slug)->latest();

        $products = $product_query->skip($offset)->take($limit)->get();

        return $products;
    }

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
        ])->where('slug', $slug)->where('published', 1)->firstOrFail();

        // Related products (same category, excluding this product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('published', 1)
            ->get();

        // Determine selected stock by SKU or default first stock
        $selectedStock = $sku 
            ? $product->stocks->where('sku', $sku)->first()
            : $product->stocks->first();

        // Get cart quantity
        // $cartQty = 0;
        // $cartId = null;
        // if ($selectedStock) {
        //     $cartQuery = Cart::where('product_id', $product->id)
        //         ->where('product_stock_id', $selectedStock->id)
        //         ->where('status', 'pending')
        //         ->where(function($query) use ($guestToken, $user_id) {
        //             if ($user_id) {
        //                 // Logged-in user
        //                 $query->where('user_id', $user_id);
        //             } else {
        //                 // Guest user
        //                 $query->where('temp_user_id', $guestToken);
        //             }
        //         });
        //     $cartQty = $cartQuery->value('quantity') ?? 0;
        //     $cartId = $cartQuery->value('id') ?? null;
        // }

        $cartQty = 0;
        $cartId = null;
        $totalReservedQty = 0;

        if ($selectedStock) {

            // Current user cart quantity (normal cart only)
            $cartQuery = Cart::where('product_id', $product->id)
                ->where('product_stock_id', $selectedStock->id)
                ->where('status', 'pending')
                ->where('is_pc_builder', 0)
                ->where(function($query) use ($guestToken, $user_id) {
                    if ($user_id) {
                        $query->where('user_id', $user_id);
                    } else {
                        $query->where('temp_user_id', $guestToken);
                    }
                });

            $cartQty = $cartQuery->sum('quantity');

            $cartId = $cartQuery->value('id');
            

            // TOTAL RESERVED QTY
            // includes normal cart + pc builder
            $totalReservedQty = Cart::where('product_stock_id', $selectedStock->id)
                ->where('status', 'pending')
                ->where(function($query) use ($guestToken, $user_id) {
                    if ($user_id) {
                        $query->where('user_id', $user_id);
                    } else {
                        $query->where('temp_user_id', $guestToken);
                    }
                })->sum('quantity');
        }

        $stockId = $selectedStock ? $selectedStock->id : null;

        $remainingQty = 0;

        if ($selectedStock) {
            $remainingQty = max(
                $selectedStock->qty - $totalReservedQty,
                0
            );
        }

        // New changes 

        // Step 1 — Get the selected variant  from SKU
        $selectedVariant = DB::select("
            SELECT 
                p.product_varient_id,
                p.attribute_value_id,
                ps.sku
            FROM product_attributes p
            LEFT JOIN product_stocks ps 
                ON ps.id = p.product_varient_id
            WHERE ps.sku = ?
            ORDER BY p.attribute_id
        ", [$sku]);

        
        // Get first attribute value of the SKU
        $firstAttribute = DB::selectOne("
            SELECT 
                p.product_varient_id,
                p.attribute_value_id
            FROM product_attributes p
            LEFT JOIN product_stocks ps 
                ON ps.id = p.product_varient_id
            WHERE ps.sku = ?
            ORDER BY p.attribute_id
            LIMIT 1
        ", [$sku]);



        $variantsById = [];
        $selectedLevelValues = [];

        if ($firstAttribute) {
            
            $valueId = $firstAttribute->attribute_value_id;

            // Step 2 — Get all variants having this first-level value
            $variants = DB::select("
                SELECT 
                    p.product_varient_id,
                    ps.sku,
                    p.attribute_id,
                    a.name,
                    p.attribute_value_id,
                    av.value
                FROM product_attributes p
                LEFT JOIN attributes a 
                    ON p.attribute_id = a.id
                LEFT JOIN attribute_values av 
                    ON p.attribute_value_id = av.id
                LEFT JOIN product_stocks ps 
                    ON ps.id = p.product_varient_id
                WHERE p.product_varient_id IN (
                    SELECT product_varient_id 
                    FROM product_attributes 
                    WHERE attribute_value_id = ?
                )
                ORDER BY p.product_varient_id, p.attribute_id
            ", [$valueId]);

            // Transform variants into mapping: variant_id => [attribute_id => value_id]
            
            foreach ($variants as $v) {
                $variantsById[$v->product_varient_id][$v->attribute_id] = $v->attribute_value_id;
            }

            // Get selected variant attribute values by SKU
            $selectedVariant = DB::select("
                SELECT 
                    p.product_varient_id,
                    p.attribute_value_id,
                    p.attribute_id,
                    ps.sku
                FROM product_attributes p
                LEFT JOIN product_stocks ps 
                    ON ps.id = p.product_varient_id
                WHERE ps.sku = ?
                ORDER BY p.attribute_id
            ", [$sku]);

            $selectedLevelValues = [];
            foreach ($selectedVariant as $v) {
                $selectedLevelValues[$v->attribute_id] = $v->attribute_value_id;
            }
        }

        $seo = $product->seo->first();
        $seoContents = [
            'title' => $seo->meta_title ?? '',
            'meta_description' => $seo->meta_description ?? '',
            'keywords' => $seo->meta_keywords ?? '',
            'og_title' => $seo->og_title ?? '',
            'og_description' => $seo->og_description ?? '',
            'twitter_title' => $seo->twitter_title ?? '',
            'twitter_description' => $seo->twitter_description ?? '',
        ];

        $this->loadSEO($seoContents);


        return view('frontend.productDetails', compact(
            'product', 
            'relatedProducts', 
            'selectedStock',
            'cartQty',
            'stockId',
            'variantsById',
            'selectedLevelValues',
            'selectedVariant',
            'cartId',
            'remainingQty'
        ));
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $view = $request->get('view', 'gridview');

        $products = Product::select('products.*')
            ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')
            ->where('published', 1);

        // Filters
        if ($request->filled('categories')) {
            $products->whereIn('products.category_id', $request->categories);
        }

        if($request->filled('condition')) {
            $products->where('products.condition', $request->condition);
        }

        if ($request->filled('brands')) {
            $products->whereIn('products.brand_id', $request->brands);
        }

        if ($request->filled('min_price')) {
            $products->where('product_stocks.offer_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $products->where('product_stocks.offer_price', '<=', $request->max_price);
        }

        // Global Search
        if ($request->filled('search')) {
            $search = $request->search;

            $products->where(function ($query) use ($search) {
                $query->where('products.name', 'LIKE', "%{$search}%")
                    ->orWhere('products.slug', 'LIKE', "%{$search}%")
                    ->orWhere('products.tags', 'LIKE', "%{$search}%")
                    ->orWhereHas('stocks', function ($q) use ($search) {
                        $q->where('stock_title', 'LIKE', "%{$search}%");
                    });
            });
        }


        // Sorting
        switch ($sort) {
            case 'oldest':
                $products->orderBy('products.created_at', 'asc');
                break;
            case 'price_low_high':
                $products->orderBy('product_stocks.offer_price', 'asc');
                break;
            case 'price_high_low':
                $products->orderBy('product_stocks.offer_price', 'desc');
                break;
            default:
                $products->orderBy('products.created_at', 'desc');
                break;
        }

        // $products = $products->with('stocks')->distinct()->paginate(12);
        $products = $products
            ->groupBy('products.id')
            ->with('stocks')
            ->paginate(12);
        $categories = Category::withCount('products')->where('is_active', 1)->orderBy('name', 'asc')->get();

        // Sort children alphabetically
        $categories->each(function ($category) {
            if ($category->childs->count()) {
                $category->childs = $category->childs->sortBy(function($child) {
                    return $child->category_translations->first()?->name ?? $child->name;
                })->values();
            }
        });
        $groupedCategories = $categories->groupBy('parent_id');

        $brands = Brand::withCount('products')->where('is_active', 1)->orderBy('name', 'asc')->get();


        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.partials.product-list', compact('products', 'view'))->render(),
                'hasMore' => $products->hasMorePages()
            ]);
        }
        


        $page = Page::where('type', 'product_listing')->first();
        
        $page_content = $page ? json_decode($page->data, true) : [];

        $seoContents = [
            'title' => $page_content['meta_title'] ?? '',
            'meta_description' => $page_content['meta_description'] ?? '',
            'keywords' => $page_content['keywords'] ?? '',
            'og_title' => $page_content['og_title'] ?? '',
            'og_description' => $page_content['og_description'] ?? '',
            'twitter_title' => $page_content['twitter_title'] ?? '',
            'twitter_description' => $page_content['twitter_description'] ?? '',
        ];

        $categorySlider = [];

        if (!empty($page_content['categories'])) {
            $categorySlider = Category::with('iconImage')
                ->whereIn('id', $page_content['categories'])
                ->where('is_active', 1)
                ->get();
        }

        $banner_ids = $page_content['banners'] ?? [];
        $banners = collect();
        if (!empty($banner_ids)) {
            $banners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $banner_ids)
                ->where('status', 1)
                ->orderByRaw("FIELD(id," . implode(',', $banner_ids) . ")")
                ->get();
        }

        $this->loadSEO($seoContents);
        return view('frontend.products', compact('products', 'banners', 'categorySlider','page_content', 'categories', 'brands', 'sort', 'view', 'groupedCategories'));
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

    public function getCategoryAndChildrenIds($categoryId)
    {
        $ids = [$categoryId];

        $children = Category::where('parent_id', $categoryId)->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getCategoryAndChildrenIds($childId));
        }

        return $ids;
    }

    public function shopByCategory(Request $request, $slug)
    {
        $sort = $request->get('sort', 'newest');
        $view = $request->get('view', 'gridview');

        // Get category using slug
        $category = Category::whereHas('category_translations', function ($q) use ($slug) {
        $q->where('slug', $slug);
        })
        ->with([
            'category_translations',
            'childs.category_translations', // eager load child categories with translations
        ])
        ->where('is_active', 1)
        ->first();

        if (!$category) {
            abort(404);
        }

        // Sort children alphabetically by translation name
        if ($category->childs->count()) {
            $category->childs = $category->childs->sortBy(function ($child) {
                return $child->category_translations->first()?->name ?? $child->name;
            })->values();
        }

        $categoryIds = $this->getCategoryAndChildrenIds($category->id);

        $products = Product::select('products.*')
            ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')
            ->where('published', 1)
            ->whereIn('products.category_id', $categoryIds);

        // Filters
        if ($request->filled('categories')) {
            $products->whereIn('products.category_id', $request->categories);
        }

        if ($request->filled('brands')) {
            $products->whereIn('products.brand_id', $request->brands);
        }

        if ($request->filled('min_price')) {
            $products->where('product_stocks.offer_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $products->where('product_stocks.offer_price', '<=', $request->max_price);
        }

        // Sorting
        switch ($sort) {
            case 'oldest':
                $products->orderBy('products.created_at', 'asc');
                break;

            case 'price_low_high':
                $products->orderBy('product_stocks.offer_price', 'asc');
                break;

            case 'price_high_low':
                $products->orderBy('product_stocks.offer_price', 'desc');
                break;

            default:
                $products->orderBy('products.created_at', 'desc');
                break;
        }

        // $products = $products->with('stocks')->distinct()->paginate(12);
        $products = $products
            ->groupBy('products.id')
            ->with('stocks')
            ->paginate(12);

        $brands = Brand::withCount('products')->whereIn('id', $products->pluck('brand_id')->filter()->unique())->orderBy('name', 'asc')->get();

        $categories = Category::withCount('products')->orderBy('name', 'asc')->get();

        // Sort children alphabetically
        $categories->each(function ($category) {
            if ($category->childs->count()) {
                $category->childs = $category->childs->sortBy(function($child) {
                    return $child->category_translations->first()?->name ?? $child->name;
                })->values();
            }
        });
        $groupedCategories = $categories->groupBy('parent_id');


        $productCount = $products->count();

        // If AJAX request, return only product list partial
        if ($request->ajax()) {
            return view('frontend.partials.product-list', compact('products', 'view'))->render();
        }

        // Load seo 
        $seoContents = [
            'title' => $category->category_translations[0]['meta_title'] ?? '',
            'meta_description' => $category->category_translations[0]['meta_description'] ?? '',
            'keywords' => $category->category_translations[0]['meta_keyword'] ?? '',
            'og_title' => $category->category_translations[0]['og_title'] ?? '',
            'og_description' => $category->category_translations[0]['og_description'] ?? '',
            'twitter_title' => $category->category_translations[0]['twitter_title'] ?? '',
            'twitter_description' => $category->category_translations[0]['twitter_description'] ?? '',
        ];

        $this->loadSEO($seoContents);

        // Ad slider
        $page = Page::where('type', 'product_listing')->first();
        $page_content = $page ? json_decode($page->data, true) : [];

        $banner_ids = $page_content['banners'] ?? [];
        $banners = collect();
        if (!empty($banner_ids)) {
            $banners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $banner_ids)
                ->where('status', 1)
                ->orderByRaw("FIELD(id," . implode(',', $banner_ids) . ")")
                ->get();
        }

        return view('frontend.shop-by-category', compact(
            'products',
            'categories',
            'brands',
            'sort',
            'view',
            'categoryIds',
            'category',
            'productCount',
            'banners'
        ));
    }

    public function shopByBrand(Request $request, $slug)
    {
        $sort = $request->get('sort', 'newest');
        $view = $request->get('view', 'gridview');

        // Get brand using slug directly from brand table
        $brand = Brand::where('slug', $slug)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->first();

        if (!$brand) {
            abort(404);
        }

        // Get all products of this brand
        $productsQuery = Product::select('products.*')
            ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')
            ->where('published', 1)
            ->where('products.brand_id', $brand->id);

        // Filters
        if ($request->filled('categories')) {
            $productsQuery->whereIn('products.category_id', $request->categories);
        }

        if ($request->filled('brands')) {
            // Only allow the current brand
            $productsQuery->whereIn('products.brand_id', [$brand->id]);
        }

        if ($request->filled('min_price')) {
            $productsQuery->where('product_stocks.offer_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $productsQuery->where('product_stocks.offer_price', '<=', $request->max_price);
        }

        // Sorting
        switch ($sort) {
            case 'oldest':
                $productsQuery->orderBy('products.created_at', 'asc');
                break;

            case 'price_low_high':
                $productsQuery->orderBy('product_stocks.offer_price', 'asc');
                break;

            case 'price_high_low':
                $productsQuery->orderBy('product_stocks.offer_price', 'desc');
                break;

            default:
                $productsQuery->orderBy('products.created_at', 'desc');
                break;
        }

        // $products = $productsQuery->with('stocks')->distinct()->paginate(12);
        $products = $productsQuery
            ->groupBy('products.id')
            ->with('stocks')
            ->paginate(12);

        // Product count
        $productCount = $products->count();

        // Fetch categories for filters: only categories that have products of this brand
        $categoryIds = $products->pluck('category_id')->unique()->toArray();

        // Get parent categories also
        $allCategoryIds = $categoryIds;

        $parentIds = Category::whereIn('id', $categoryIds)
            ->pluck('parent_id')
            ->filter()
            ->toArray();

        $allCategoryIds = array_unique(array_merge($allCategoryIds, $parentIds));

        $categories = Category::whereIn('id', $allCategoryIds)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        
        // Sort children alphabetically
        $categories->each(function ($category) {
            if ($category->childs->count()) {
                $category->childs = $category->childs->sortBy(function($child) {
                    return $child->category_translations->first()?->name ?? $child->name;
                })->values();
            }
        });

        // Grouped categories by parent_id for tree structure
        $groupedCategories = $categories->groupBy('parent_id');

        // For brand filter: only the current brand
        $brands = collect([$brand]);

        // AJAX request: return partial
        if ($request->ajax()) {
            return view('frontend.partials.product-list', compact('products', 'view'))->render();
        }

        // Load seo 
        $seoContents = [
            'title' => $brand->brand_translations[0]['meta_title'] ?? '',
            'meta_description' => $brand->brand_translations[0]['meta_description'] ?? '',
            'keywords' => $brand->brand_translations[0]['meta_keywords'] ?? '',
            'og_title' => $brand->brand_translations[0]['og_title'] ?? '',
            'og_description' => $brand->brand_translations[0]['og_description'] ?? '',
            'twitter_title' => $brand->brand_translations[0]['twitter_title'] ?? '',
            'twitter_description' => $brand->brand_translations[0]['twitter_description'] ?? '',
        ];

        $this->loadSEO($seoContents);

        // Ad slider
        $page = Page::where('type', 'product_listing')->first();
        $page_content = $page ? json_decode($page->data, true) : [];

        $banner_ids = $page_content['banners'] ?? [];
        $banners = collect();
        if (!empty($banner_ids)) {
            $banners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $banner_ids)
                ->where('status', 1)
                ->orderByRaw("FIELD(id," . implode(',', $banner_ids) . ")")
                ->get();
        }

        return view('frontend.shop-by-brand', compact(
            'products',
            'categories',
            'groupedCategories',
            'brands',
            'sort',
            'view',
            'brand',
            'productCount',
            'banners'
        ));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('query', '');

        if (!$query) return response()->json([]);

        $products = Product::where('published', 1)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('tags', 'like', "%{$query}%"); // search inside comma-separated tags
            })
            ->limit(10)
            ->get(['id', 'name', 'slug']); // include id if needed

        $results = $products->map(function($p){
            return [
                'name' => $p->name,
                'url' => route('product.details', ['slug' => $p->slug, 'sku' => $p->stocks->first()->sku ?? ''])
            ];
        });

        return response()->json($results);
    }
}
