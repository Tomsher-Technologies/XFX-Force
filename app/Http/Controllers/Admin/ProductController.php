<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductSeo;
use App\Models\ProductSpecification;
use App\Models\ProductStock;
use App\Models\ProductTabs;
use App\Models\ProductWarranty;
use App\Models\Specification;
use App\Models\SpecificationItem;
use Artisan;
use Auth;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Http\Request;
use Storage;
use Str;

class ProductController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_products',  ['only' => ['all_products','destroy']]);
        $this->middleware('permission:view_product',  ['only' => ['all_products']]);
        $this->middleware('permission:add_product',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_product',  ['only' => ['admin_product_edit','update']]);
    }

    /**
     * Function to list all products.
     * 
     * @param Request $request
     */
    public function all_products(Request $request)
    {
        $request->session()->put('last_url', url()->full());
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::orderBy('id', 'desc');
        $category = ($request->has('category')) ? $request->category : '';
        $brand_id = ($request->has('brand')) ? $request->brand : '';

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            if ($col_name == 'status') {
                $products = $products->where('published', $query);
            } else {
                $products = $products->orderBy($col_name, $query);
            }

            $sort_type = $request->type;
        }

        if ($request->has('brand') && $request->brand !== '0' && $request->brand !== '') {
            $products = $products->where('brand_id', $request->brand);
        }
        if ($request->has('category') && $request->category !== '0') {
            $childIds = [];
            $categoryfilter = $request->category;
            $childIds[] = array($request->category);

            if ($categoryfilter != '') {
                $childIds[] = getChildCategoryIds($categoryfilter);
            }

            if (!empty($childIds)) {
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }

            $products = $products->whereHas('category', function ($q) use ($childIds) {
                $q->whereIn('id', $childIds);
            });
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products->where(function($q) use ($sort_search) {
                        $q->where('name', 'like', '%' . $sort_search . '%')
                        ->orWhere('sku', 'like', '%' . $sort_search . '%')
                        ->orWhere('slug', 'like', '%' . $sort_search . '%')
                        ->orWhere('description', 'like', '%' . $sort_search . '%')
                        ->orWhere('tags', 'like', '%' . $sort_search . '%')
                        ->orWhereHas('stocks', function ($q) use ($sort_search) {
                            $q->where('sku', 'like', '%' . $sort_search . '%')
                            ->orWhere('stock_title', 'like', '%' . $sort_search . '%')
                            ->orWhere('model', 'like', '%' . $sort_search . '%')
                            ->orWhere('stock_description', 'like', '%' . $sort_search . '%');
                        });
                    });
        }

        $products = $products->paginate(10);
        $type = 'All';
        $brands = \App\Models\Brand::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('backend.products.index', compact('category', 'brands', 'brand_id', 'products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    /**
     * Function to load the create form. 
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.products.create', compact('categories'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('is_active', 1)->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    public function get_attribute_values(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('is_active', 1)->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslatedName(env('DEFAULT_LANGUAGE', 'en')) . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        // save product
        $skuMain = $request->input('sku') ?? generateUniqueSKU();
        $product = new Product;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->user_id = Auth::user()->id;
        $product->sku = cleanSKU($skuMain);
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->unit_price = $request->has('price') ? $request->price : 0;

        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }

        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;
        $product->slug = $slug;

        $product->condition = $request->condition;
        $product->estimated_delivery_days = $request->estimated_delivery_days;
        $product->product_type = $request->product_type;
        $product->product_length = $request->product_length;
        $product->product_width = $request->product_width;
        $product->product_height = $request->product_height;
        $product->product_weight = $request->product_weight;
        $product->description = $request->description;
        $product->return_refund = $request->input('return_refund');

        $tags = array();
        if (isset($request->tags[0]) && $request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->tags = implode(',', $tags);
        $product->save();
        
        // save product image.
        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }

            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = ImageHelper::downloadAndResizeImage('main_product', $file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::exists($product->thumbnail_img)) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                    }
                    Storage::delete($product->thumbnail_img);
                }
            }
            $gallery =ImageHelper::downloadAndResizeImage('main_product', $request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }

        $product->save();

        // SEO
        $seo = ProductSeo::firstOrNew(['lang' => env('DEFAULT_LANGUAGE', 'en'), 'product_id' => $product->id]);
        $seo->meta_title        = $request->meta_title ?? $product->name;
        $seo->meta_description  = $request->meta_description;
        $keywords = array();
        if (isset($request->meta_keywords[0]) && $request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }
        $seo->meta_keywords         = implode(',', $keywords);
        $seo->og_title              = $request->og_title ?? $product->name;
        $seo->og_description        = $request->og_description;
        $seo->twitter_title         = $request->twitter_title ?? $product->name;
        $seo->twitter_description   = $request->twitter_description;
        $seo->save();

        // saving tabs
        if ($request->has('tabs')) {
            foreach ($request->tabs as $tab) {
                if ($tab['tab_heading'] != '' && $tab['tab_description'] != '') {
                    $p_tab = $product->tabs()->create([
                        'lang'    => env('DEFAULT_LANGUAGE', 'en'),
                        'heading' => $tab['tab_heading'],
                        'content' => $tab['tab_description'],
                    ]);
                }
            }
        }

        // saving warranty
        if ($request->has('extended_warranty')) {
            foreach ($request->extended_warranty as $warranty) {
                if (!empty($warranty['warranty_title']) && !empty($warranty['warranty_months'])) {
                    $product->warranties()->create([
                        'title' => $warranty['warranty_title'],
                        'price' => $warranty['warranty_price'] ?? 0,
                        'months' => $warranty['warranty_months'],
                        'description' => $warranty['warranty_description'] ?? null,
                    ]);
                }
            }
        }

        // saving single type product
        if ($request->product_type == 0) {
            //save stock
            $product_stock = new ProductStock();
            $product_stock->product_id = $product->id;
            $product_stock->type = 'single';
            $product_stock->sku = cleanSKU($request->sku ?? generateUniqueSKU());
            $product_stock->qty = $request->current_stock;
            $product_stock->status = $request->status ?? 1;
            $product_stock->stock_description = $request->stock_description;
            $product_stock->model = $request->model;
            $product_stock->stock_title = $request->stock_title;
            $product_stock->image = '';
            
            $stockgallery = [];
            if ($request->hasfile('variant_images')) {
                if ($product_stock->image == null) {
                    $count = 1;
                    $old_stock_gallery = [];
                } else {
                    $old_stock_gallery = explode(',', $product_stock->image);
                    $count = count($old_stock_gallery) + 1;
                }

                foreach ($request->file('variant_images') as $key => $file) {
                    $stockgallery[] = ImageHelper::downloadAndResizeImage('sub_product', $file, $product_stock->sku, false, $count + $key);
                }
                $product_stock->image = implode(',', array_merge($old_stock_gallery, $stockgallery));
            }

            $offertag = '';
            $productOrgPrice = $request->price;
            $discountPrice = $productOrgPrice;

            if (
                $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {

                if ($product->discount_type == 'percent') {
                    $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                    $offertag = $product->discount . '% OFF';
                }

                if ($product->discount_type == 'amount') {
                    $discountPrice = $productOrgPrice - $product->discount;
                    $offertag = 'AED ' . $product->discount . ' OFF';
                }
            }

            $product_stock->price = $productOrgPrice;
            $product_stock->offer_price = $discountPrice;
            $product_stock->offer_tag = $offertag;
            $product_stock->save();

            //  Saving specification for single product.
            if ($request->has('variant.0.specifications')) {
                $specifications = $request->variant[0]['specifications'] ?? [];
                $items = $request->variant[0]['specification_items'] ?? [];
                $sortOrders = $request->variant[0]['sort_orders'] ?? [];
                foreach ($specifications as $key => $specId) {
                    if (!$specId) continue;
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'product_stock_id' => $product_stock->id,
                        'specification_id' => $specId,
                        'specification_item_id' => $items[$key] ?? null,
                        'sort_order' => $sortOrders[$key] ?? 0
                    ]);
                }
            }

            // Save the product SKU as the stock SKU
            $product->sku = cleanSKU($request->sku ?? generateUniqueSKU());
            $product->save();
        }

        // saving variant type product
        if ($request->product_type == 1 && $request->has('variants')) {
            foreach ($request->variants as $index=>$variantData) {
                // saving stock
                $stock = new ProductStock();
                $stock->product_id = $product->id;
                $stock->type = 'variant';
                $stock->sku = cleanSKU($variantData['sku'] ?? generateUniqueSKU());
                $stock->qty = $variantData['current_stock'];
                $stock->status = $variantData['status'] ?? 1;
                $stock->stock_description = $variantData['stock_description'] ?? '';
                $stock->model = $variantData['model'] ?? '';
                $stock->stock_title = $variantData['stock_title'] ?? '';


                if (isset($variantData['variant_images']) && $request->hasFile("variants.$index.variant_images")) {

                    $stock_images_gallery = [];

                    $files = $request->file("variants.$index.variant_images");

                    if (is_array($files)) {

                        foreach ($files as $fileIndex => $file) {

                            if (!$file || !$file->isValid()) continue;

                            // 🔥 STRONG UNIQUE COUNT (prevents collisions)
                            $uniqueCount = $index . '_' . $fileIndex . '_' . uniqid();

                            $savedPath = ImageHelper::downloadAndResizeImage(
                                'sub_product',
                                $file,
                                $stock->sku . '_' . $uniqueCount,
                                false,
                                $fileIndex + 1
                            );

                            $stock_images_gallery[] = $savedPath;
                        }
                    }

                    // 🔥 HARD CLEAN (remove duplicates safely)
                    $stock_images_gallery = array_values(array_unique(array_filter($stock_images_gallery)));

                    $stock->image = !empty($stock_images_gallery)
                        ? implode(',', $stock_images_gallery)
                        : null;
                }

                $offertag = '';
                $productOrgPrice = $variantData['price'];
                $discountPrice = $productOrgPrice;

                if (
                    $product->discount_start_date &&
                    strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                    strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
                ) {

                    if ($product->discount_type == 'percent') {
                        $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                        $offertag = $product->discount . '% OFF';
                    }

                    if ($product->discount_type == 'amount') {
                        $discountPrice = $productOrgPrice - $product->discount;
                        $offertag = 'AED ' . $product->discount . ' OFF';
                    }
                }

                $stock->price = $productOrgPrice;
                $stock->offer_price = $discountPrice;
                $stock->offer_tag = $offertag;
                
                $stock->save();

                // Save Specifications for this Variant
                if (!empty($variantData['specifications'])) {

                    foreach ($variantData['specifications'] as $key => $specId) {

                        if (!$specId) continue;

                        ProductSpecification::create([
                            'product_id' => $product->id,
                            'product_stock_id' => $stock->id,
                            'specification_id' => $specId,
                            'specification_item_id' => $variantData['specification_items'][$key] ?? null,
                            'sort_order' => $variantData['sort_orders'][$key] ?? 0
                        ]);
                    }
                }
                
                // Set product SKU as the first variant stock SKU
                if ($index === 0) {
                    $product->sku = cleanSKU($variantData['sku'] ?? generateUniqueSKU());
                    $product->save();
                }

                // Save attributes for this stock
                if (isset($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attributeId => $valueId) {
                        ProductAttributes::create([
                            'product_id' => $product->id,
                            'product_varient_id' => $stock->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId
                        ]);
                    }
                }
            }
        }

        flash(trans('messages.product') . ' ' . trans('messages.created_msg'))->success();
        return redirect()->route('products.all');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit(Request $request, $id)
    {
        $lang = $request->lang;

        $product = Product::with([
            'tabs' => function ($query) use ($lang) {
                $query->where('lang', $lang);
            },
            'seo',
            'stocks.attributes.attribute',
            'stocks.attributes.value'
        ])->findOrFail($id);

        $productSpecifications = ProductSpecification::where('product_id', $product->id)
        ->get();
        

        $specifications = Specification::where('status',1)
            ->orderBy('display_title','asc')
            ->get();
        $specificationItems = SpecificationItem::where('status',1 )
            ->orderBy('title','asc')
            ->get();

        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.products.edit', compact('product', 'categories', 'tags', 'lang', 'productSpecifications', 'specifications', 'specificationItems'));
    }

    /**
     * Function to update the product.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->category_id       = $request->category_id;
        $product->brand_id          = $request->brand_id;
        $product->user_id           = Auth::user()->id;
        $product->video_provider    = $request->video_provider;
        $product->video_link        = $request->video_link;
        $product->discount          = $request->discount;
        $product->discount_type     = $request->discount_type;
        $product->unit_price        = $request->has('price') ? $request->price : 0;

        // $tags = array();
        // if (isset($request->tags[0]) && $request->tags[0] != null) {
        //     foreach (json_decode($request->tags[0]) as $key => $tag) {
        //         array_push($tags, $tag->value);
        //     }
        // }

        $tags = [];

        if ($request->filled('tags') && isset($request->tags[0])) {

            $decodedTags = json_decode($request->tags[0]);

            if (is_array($decodedTags) || is_object($decodedTags)) {

                foreach ($decodedTags as $tag) {
                    if (isset($tag->value)) {
                        $tags[] = $tag->value;
                    }
                }
            }
        }

        $product->tags          = implode(',', $tags);
        $product->video_provider            = $request->video_provider;
        $product->video_link                = $request->video_link;
        $product->discount                  = $request->discount;
        $product->discount_type             = $request->discount_type;

        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date   = strtotime($date_var[0]);
            $product->discount_end_date     = strtotime($date_var[1]);
        }

        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count    = Product::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $id)->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;
        $product->slug = $slug;

        $product->product_type = $request->product_type;
        $product->condition = $request->condition;
        $product->estimated_delivery_days = $request->estimated_delivery_days;
        $product->product_length = $request->product_length;
        $product->product_width = $request->product_width;
        $product->product_height = $request->product_height;
        $product->product_weight = $request->product_weight;
        $product->description = $request->description;
        $product->return_refund = $request->input('return_refund');

        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }

            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = ImageHelper::downloadAndResizeImage('main_product', $file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::disk('public')->exists(str_replace('storage/', '', $product->thumbnail_img))) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::disk('public')->exists(str_replace('storage/', '', $path))) {
                            Storage::disk('public')->delete(str_replace('storage/', '', $path));
                        }
                    }
                    Storage::disk('public')->delete(str_replace('storage/', '', $product->thumbnail_img));
                }
            }
            $gallery = ImageHelper::downloadAndResizeImage('main_product', $request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }
        $product->save();

        //save product seo
        $seo                        = ProductSeo::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);
        $seo->meta_title            = $request->meta_title ?? $product->name;
        $seo->meta_description      = $request->meta_description;
        // $keywords = array();
        // if (isset($request->meta_keywords[0]) && $request->meta_keywords[0] != null) {
        //     foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
        //         array_push($keywords, $keyword->value);
        //     }
        // }

        $keywords = [];

        $metaInput = $request->meta_keywords[0] ?? null;

        $decodedKeywords = json_decode($metaInput);

        if (!empty($decodedKeywords) && is_iterable($decodedKeywords)) {
            foreach ($decodedKeywords as $keyword) {
                if (isset($keyword->value)) {
                    $keywords[] = $keyword->value;
                }
            }
        }

        $seo->meta_keywords         = implode(',', $keywords);
        $seo->og_title              = $request->og_title ?? $product->name;
        $seo->og_description        = $request->og_description;
        $seo->twitter_title         = $request->twitter_title ?? $product->name;
        $seo->twitter_description   = $request->twitter_description;
        $seo->save();

        ProductTabs::where('product_id', $product->id)->delete();
        //save product tabs
        if ($request->has('tabs')) {
            foreach ($request->tabs as $tab) {
                if ($tab['tab_heading'] != '' && $tab['tab_description'] != '') {
                    $p_tab = $product->tabs()->create([
                        'lang'    => $request->lang,
                        'heading' => $tab['tab_heading'],
                        'content' => $tab['tab_description'],
                    ]);
                }
            }
        }

        ProductWarranty::where('product_id', $product->id)->delete();
        // saving warranty
        if ($request->has('extended_warranty')) {
            
            foreach ($request->extended_warranty as $warranty) {
                if (!empty($warranty['warranty_title']) && !empty($warranty['warranty_months'])) {
                    $product->warranties()->create([
                        'title'       => $warranty['warranty_title'],
                        'price'       => $warranty['warranty_price'] ?? 0,
                        'months'      => $warranty['warranty_months'],
                        'description' => $warranty['warranty_description'] ?? null,
                    ]);
                }
            }
        }


        //save single type product
        if ($request->product_type == 0) {

            // If switching to single, remove all variant stocks.
            ProductStock::where('product_id', $product->id)
                ->where('type', 'variant')
                ->delete();
            // Remove variant attributes.
            $variantStockIds = ProductStock::where('product_id', $product->id)
                ->pluck('id');
            ProductAttributes::whereIn('product_varient_id', $variantStockIds)->delete();
            /** */

            $product_stock = ProductStock::where('product_id', $product->id)->first();
            $product_stock->product_id = $product->id;
            $product_stock->type = 'single';
            $product_stock->sku = cleanSKU($request->sku ?? generateUniqueSKU());
            $product_stock->qty = $request->current_stock;
            $product_stock->status = $request->status ?? 1;
            $product_stock->stock_description = $request->stock_description;
            $product_stock->model = $request->model;
            $product_stock->stock_title = $request->stock_title; 

            // if ($request->hasFile('image')) {
            //     $product_stock->image = ImageHelper::downloadAndResizeImage('sub_product', $request->file('image'), $product->sku, true);
            // }

            $stock_gallery = [];
            if ($request->hasfile('variant_images')) {
                if ($product_stock->image == null) {
                    $count = 1;
                    $old_stock_gallery = [];
                } else {
                    $old_stock_gallery = explode(',', $product_stock->image);
                    $count = count($old_stock_gallery) + 1;
                }

                foreach ($request->file('variant_images') as $key => $file) {
                    $stock_gallery[] = ImageHelper::downloadAndResizeImage('sub_product', $file, $product_stock->sku, false, $count + $key);
                }

                $product_stock->image = implode(',', array_merge($old_stock_gallery, $stock_gallery));
            }
            
            $offertag = '';
            $productOrgPrice = $request->price;
            $discountPrice = $productOrgPrice;

            if (
                $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {

                if ($product->discount_type == 'percent') {
                    $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                    $offertag = $product->discount . '% OFF';
                }

                if ($product->discount_type == 'amount') {
                    $discountPrice = $productOrgPrice - $product->discount;
                    $offertag = 'AED ' . $product->discount . ' OFF';
                }
            }

            $product_stock->price = $productOrgPrice;
            $product_stock->offer_price = $discountPrice;
            $product_stock->offer_tag = $offertag;
            $product_stock->save();

            //  Saving specification for single product.
            ProductSpecification::where('product_stock_id', $product_stock->id)->delete();
            if ($request->has('variant.0.specifications')) {
                $specifications = $request->variant[0]['specifications'] ?? [];
                $items = $request->variant[0]['specification_items'] ?? [];
                $sortOrders = $request->variant[0]['sort_orders'] ?? [];
                $specModelIds = $request->variant[0]['product_spec_id'] ?? [];
                foreach ($specifications as $key => $specId) {
                    
                    if (!$specId) continue;
                    $productSpecificationId = $specModelIds[$key] ?? null;

                    $specModel = ProductSpecification::findOrNew($productSpecificationId);
                    
                    $specModel->product_id = $product->id;
                    $specModel->product_stock_id = $product_stock->id;
                    $specModel->specification_id = $specId;
                    $specModel->specification_item_id = $items[$key] ?? null;
                    $specModel->sort_order = $sortOrders[$key] ?? 0;
                    $specModel->save();
                }
            }

            // Save the product SKU as the stock SKU
            $product->sku = $product_stock->sku;
            $product->save();
        }

        // save variant type product
        if ($request->product_type == 1 && $request->has('variants')) {
            // If switching to variant, remove single stock
            ProductStock::where('product_id', $product->id)
            ->where('type', 'single')
            ->delete();
            /** */

            foreach ($request->variants as $index=>$variantData) {
                // Create product stock
                $stock = !empty($variantData['stock_id'])
                        ? ProductStock::find($variantData['stock_id'])
                        : new ProductStock();

                $stock->product_id = $product->id;
                $stock->type = 'variant';
                $stock->sku = cleanSKU($variantData['sku'] ?? generateUniqueSKU());
                $stock->qty = $variantData['current_stock'];
                $stock->status = $variantData['status'] ?? 1;
                $stock->stock_description = $variantData['stock_description'] ?? '';
                $stock->model = $variantData['model'] ?? '';
                $stock->stock_title = $variantData['stock_title'] ?? '';
                
                if ($request->hasFile("variants.$index.variant_images")) {

                    // Clean existing images safely
                    $old_stock_gallery = array_values(array_filter(
                        $stock->image ? explode(',', $stock->image) : []
                    ));

                    $new_stock_gallery = [];

                    $files = $request->file("variants.$index.variant_images");

                    if (is_array($files)) {
                        foreach ($files as $fileIndex => $file) {

                            if (!$file || !$file->isValid()) continue;

                            $uniqueKey = $stock->sku . '_' . $index . '_' . $fileIndex . '_' . uniqid();

                            $new_stock_gallery[] = ImageHelper::downloadAndResizeImage(
                                'sub_product',
                                $file,
                                $uniqueKey,
                                false,
                                $fileIndex + 1
                            );
                        }
                    }

                    // Merge + HARD CLEAN
                    $merged = array_values(array_unique(array_filter(
                        array_merge($old_stock_gallery, $new_stock_gallery)
                    )));

                    $stock->image = !empty($merged) ? implode(',', $merged) : null;
                }

                $offertag = '';
                $productOrgPrice = $variantData['price'];
                $discountPrice = $productOrgPrice;

                if (
                    $product->discount_start_date &&
                    strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                    strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
                ) {

                    if ($product->discount_type == 'percent') {
                        $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                        $offertag = $product->discount . '% OFF';
                    }

                    if ($product->discount_type == 'amount') {
                        $discountPrice = $productOrgPrice - $product->discount;
                        $offertag = 'AED ' . $product->discount . ' OFF';
                    }
                }

                $stock->price = $productOrgPrice;
                $stock->offer_price = $discountPrice;
                $stock->offer_tag = $offertag;
                
                $stock->save();

                
                // Save Specifications for this Variant
                ProductSpecification::where('product_stock_id', $stock->id)->delete();
                if (!empty($variantData['specifications'])) {
                    // Get the corresponding arrays for this variant
                    $specIds       = $variantData['specifications'];
                    $itemIds       = $variantData['specification_items'] ?? [];
                    $sortOrders    = $variantData['sort_orders'] ?? [];
                    $specModelIds  = $variantData['product_spec_id'] ?? [];

                    foreach ($specIds as $i => $specId) {
                        if (!$specId) continue;

                        $productSpecificationId = $specModelIds[$i] ?? null;

                        $specModel = ProductSpecification::findOrNew($productSpecificationId);
                        $specModel->product_id = $product->id;
                        $specModel->product_stock_id = $stock->id;
                        $specModel->specification_id = $specId;
                        $specModel->specification_item_id = $itemIds[$i] ?? null;
                        $specModel->sort_order = $sortOrders[$i] ?? 0;
                        $specModel->save();
                    }
                }


                // Set product SKU as the first variant stock SKU
                if ($index === 0) {
                    $product->sku = $stock->sku;
                    $product->save();
                }

                ProductAttributes::where('product_varient_id', $stock->id)->delete();

                // Save attributes for this stock
                if (isset($variantData['attributes'])) {
                    foreach ($variantData['attributes'] as $attributeId => $valueId) {
                        ProductAttributes::updateOrCreate(
                            [
                                'product_varient_id' => $stock->id,
                                'attribute_id'       => $attributeId
                            ],
                            [
                                'product_id'         => $product->id,
                                'attribute_value_id' => $valueId
                            ]
                        );
                    }
                }
            }
        }

        flash(trans('messages.product') . ' ' . trans('messages.updated_msg'))->success();
        return redirect()->route('products.all');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }


    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    /**
     * Function to update the published status of product.
     *
     * @param Request $request
     * @return int
     */
    public function updatePublished(Request $request): int
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $seller = $product->user->seller;
            if ($seller->invalid_at != null && $seller->invalid_at != '0000-00-00' && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0) {
                return 0;
            }
        }

        $product->stocks()->update([
            'status' => $product->published ? 1 : 0
        ]);

        $product->save();
        return 1;
    }

    public function delete_thumbnail(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

        $fil_url = str_replace('/storage/', '', $product->thumbnail_img);
        $fil_url = $path = Storage::disk('public')->path($fil_url);

        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                unlink($path);
            }

            unlink($fil_url);
            $product->thumbnail_img = null;
            $product->save();
            return 1;
        }
    }

    public function delete_gallery(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        $fil_url = str_replace('/storage/', '', $request->url);
        $fil_url = $path = Storage::disk('public')->path($fil_url);
        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                unlink($path);
            }

            unlink($fil_url);

            $thumbnail_img = explode(',', $product->photos);
            $thumbnail_img =  array_diff($thumbnail_img, [$request->url]);
            if ($thumbnail_img) {
                $product->photos = implode(',', $thumbnail_img);
            } else {
                $product->photos = null;
            }

            $product->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function delete_stock_gallery(Request $request)
    {
        $stock = ProductStock::where('id', $request->id)->first();
        
        $fil_url = str_replace('/storage/', '', $request->url);
        $fil_url = $path = Storage::disk('public')->path($fil_url);

        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                unlink($path);
            }

            unlink($fil_url);

            $stock_images = explode(',', $stock->image);
            $stock_images =  array_diff($stock_images, [$request->url]);
            if ($stock_images) {
                $stock->image = implode(',', $stock_images);
            } else {
                $stock->image = null;
            }

            $stock->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function checkSku(Request $request)
    {
        $sku = $request->sku;

        $query = ProductStock::where('sku', $sku);

        // ignore current variant
        if ($request->stock_id) {
            $query->where('id', '!=', $request->stock_id);
        }

        $exists = $query->exists();
        
        return response()->json([
            'exists' => $exists
        ]);
    }
}
