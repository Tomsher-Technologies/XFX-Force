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
        $products = Product::orderBy('created_at', 'asc');
        $category = ($request->has('category')) ? $request->category : '';

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
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

        $products = $products->paginate(10);
        $type = 'All';

        return view('backend.products.index', compact('category', 'products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
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

        //save product specification
        $specIds = $request->input('specification_id', []);
        $itemIds = $request->input('specification_item_id', []);
        $sortOrders = $request->input('specification_sort_order', []);

        foreach ($specIds as $i => $specId) {
            $itemId = $itemIds[$i] ?? null;
            $sortOrder  = $sortOrders[$i] ?? 0;

            if (!$specId || !$itemId) {
                continue;
            }

            $specModel = new ProductSpecification();
            $specModel->product_id            = $product->id;
            $specModel->specification_id      = $specId;
            $specModel->specification_item_id = $itemId;
            $specModel->sort_order            = $sortOrder;
            $specModel->save();
        }

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
            if ($request->hasFile('image')) {
                $product_stock->image = ImageHelper::downloadAndResizeImage('sub_product', $request->file('image'), $product->sku, true);
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

                if (isset($variantData['image']) && $request->hasFile("variants.$index.image")) {
                    $stock->image = ImageHelper::downloadAndResizeImage(
                        'sub_product', 
                        $request->file("variants.$index.image"), 
                        $product->sku, 
                        true
                    );
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

        $tags = array();
        if (isset($request->tags[0]) && $request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
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

        //save product specification
        $specIds = $request->input('specification_id', []);
        $itemIds = $request->input('specification_item_id', []);
        $sortOrders = $request->input('specification_sort_order', []);

        foreach ($specIds as $i => $specId) {
            $productSpecificationId = $request->input('product_spec_id', [])[$i] ?? null;
            $itemId = $itemIds[$i] ?? null;
            $sortOrder  = $sortOrders[$i] ?? 0;

            if (!$specId || !$itemId) {
                continue;
            }

            $specModel = ProductSpecification::findOrNew($productSpecificationId);
            $specModel->product_id            = $product->id;
            $specModel->specification_id      = $specId;
            $specModel->specification_item_id = $itemId;
            $specModel->sort_order            = $sortOrder;
            $specModel->save();
        }

        //save product seo
        $seo                        = ProductSeo::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);
        $seo->meta_title            = $request->meta_title ?? $product->name;
        $seo->meta_description      = $request->meta_description;
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

        //save product tabs
        if ($request->has('tabs')) {
            ProductTabs::where('lang', $request->lang)->where('product_id', $product->id)->delete();
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

        // saving warranty
        if ($request->has('extended_warranty')) {
            ProductWarranty::where('product_id', $product->id)->delete();
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

            if ($request->hasFile('image')) {
                $product_stock->image = ImageHelper::downloadAndResizeImage('sub_product', $request->file('image'), $product->sku, true);
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
                
                if (isset($variantData['image']) && $request->hasFile("variants.$index.image")) {
                    $stock->image = ImageHelper::downloadAndResizeImage(
                        'sub_product', 
                        $request->file("variants.$index.image"), 
                        $product->sku, 
                        true
                    );
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
}
