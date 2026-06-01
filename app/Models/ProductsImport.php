<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductSeo;
use App\Models\ProductSpecification;
use App\Models\ProductStock;
use App\Models\Specification;
use App\Models\SpecificationItem;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToCollection, WithHeadingRow
{
    private $rows = 0;
    public $errorsList = [];

    private $year = 0;
    private $month = 0;

    public function __construct()
    {
        $this->year = Carbon::now()->year;
        $this->month =  Carbon::now()->format('m');
    }

    /**
     * Process the collection of rows from the Excel import.
     *
     * @param Collection $rows
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function collection(Collection $rows)
    {
        $conditionMap = [
            'new' => 0,
            'refurbished' => 1,
            'open box' => 2,
        ];

        // Clean SKUs
        $rows = $rows->map(function ($row) {
            $row['sku'] = $this->cleanSKU($row['sku'] ?? '');
            $row['parent_sku'] = !empty($row['parent_sku'])
                ? $this->cleanSKU($row['parent_sku'])
                : null;
            return $row;
        });

        // Validation
        $seenSkus = [];
        $validRows = $rows->filter(function ($row, $index) use (&$seenSkus) {
            $sku = $row['sku'] ?? null; 
            $productName = $row['product_name'] ?? null;
            $category = $row['category'] ?? null;

            if (empty($sku)) {
                $this->errorsList[] = [
                    'row' => $index + 2,
                    'column' => 'SKU',
                    'errors' => ["SKU is missing in the File. <strong>SKU: {$sku}</strong>"],
                    'values' => $row
                ];
                return false;
            }

            if (in_array($sku, $seenSkus)) {
                $this->errorsList[] = [
                    'row' => $index + 2,
                    'column' => 'SKU',
                    'errors' => ["Duplicate SKU in file: <strong>SKU: {$sku}</strong>"],
                    'values' => $row
                ];
                return false;
            }
            $seenSkus[] = $sku;

            if (empty($productName)) {
                $this->errorsList[] = [
                    'row' => $index + 2,
                    'column' => 'Product Name',
                    'errors' => ["Product Name is required. <strong>SKU: {$sku}</strong>"],
                    'values' => $row
                ];
                return false;
            }

            return true;
        })->values();

        $grouped = $validRows->groupBy(function ($row) {
            return !empty($row['parent_sku'])
                ? $row['parent_sku']
                : $row['sku'];
        });
        $generatedSlugs = [];

        foreach ($grouped as $products) {

            DB::beginTransaction();
            try {
                $firstRow = $products->first();
                $skuToUse = $firstRow['parent_sku'] ?: $firstRow['sku'];

                $categoryName = trim($this->pickLatestValue($products, 'category'));
                $brandName = trim($this->pickLatestValue($products, 'brand'));

                
                // Get or Create Category
                $category = Category::whereHas('category_translations', function ($q) use ($categoryName) {
                    $q->whereRaw('LOWER(name) = ?', [strtolower($categoryName)]);
                })->first();

                if (!$category) {

                    $category = Category::create([
                        'name' => $categoryName,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    CategoryTranslation::create([
                        'category_id' => $category->id,
                        'name' => $categoryName,
                        'slug' => Str::slug($categoryName),
                        'lang' => 'en'
                    ]);
                }

                $categoryId = $category->id;


                // Brand (case-insensitive)
                $brand = Brand::firstOrCreate(
                    ['name' => $brandName],
                    [
                        'slug' => Str::slug($brandName),
                        'status' => 1
                    ]
                );

                $brandId = $brand->id;

                // Product save or update
                $product = Product::where('sku', $skuToUse)->first();

                $productData = [
                    'name' =>  trim($this->pickLatestValue($products, 'product_name') ?? ''),
                    'video_provider' => trim($this->pickLatestValue($products, 'video_provider') ?? ''),
                    'video_link' => trim($this->pickLatestValue($products, 'video_link') ?? ''),
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'condition' => $conditionMap[trim(strtolower($this->pickLatestValue($products, 'condition')))] ?? 0,
                    'product_length' => $this->pickLatestValue($products, 'length'),
                    'product_width' => $this->pickLatestValue($products, 'width'),
                    'product_height' => $this->pickLatestValue($products, 'height'),
                    'product_weight' => $this->pickLatestValue($products, 'weight'),
                    'estimated_delivery_days' => $this->pickLatestValue($products, 'est_delivery_days'),
                    'tags' => trim($this->pickLatestValue($products, 'tags') ?? ''),
                    'product_type' => $this->pickLatestValue($products, 'parent_sku') ? 1 : 0,
                    'return_refund' => strtolower(trim($this->pickLatestValue($products, 'return_refund') ?? '')) === 'yes' ? 1 : 0,
                    'published' => strtolower(trim($this->pickLatestValue($products, 'published') ?? '')) === 'no' ? 0 : 1,
                    'discount' => $this->pickLatestValue($products, 'discount_price'),
                    'discount_type' => $this->pickLatestValue($products, 'discount_type'),
                    'discount_start_date' => !empty($this->pickLatestValue($products, 'discount_start_date'))
                        ? Date::excelToDateTimeObject($this->pickLatestValue($products, 'discount_start_date'))
                        ->setTime(0, 0, 0)
                        ->getTimestamp()
                        : null,
                    'discount_end_date' => !empty($this->pickLatestValue($products, 'discount_end_date'))
                        ? Date::excelToDateTimeObject($this->pickLatestValue($products, 'discount_end_date'))
                        ->setTime(23, 59, 59)
                        ->getTimestamp()
                        : null,
                    'description' => trim($this->pickLatestValue($products, 'description') ?? ''),
                    'updated_by' => Auth::user()->id,
                ];

                // Generate slug only for new products
                if (!$product) {
                    $productData['slug'] = $this->productSlug(
                        trim($this->pickLatestValue($products, 'product_name') ?? ''),
                        $generatedSlugs
                    );
                }

                $product = Product::updateOrCreate(
                    ['sku' => $skuToUse],
                    $productData
                );

                // Save or update product SEO
                $keywords = [];
                if (!empty($firstRow['meta_keywords'])) {
                    $keywords = array_map('trim', explode(',', $firstRow['meta_keywords']));
                }

                ProductSeo::updateOrCreate(
                    ['product_id' => $product->id], // Match by product
                    [
                        'meta_title' => $firstRow['meta_title'] ?? $product->name,
                        'meta_description' => $firstRow['meta_description'] ?? null,
                        'meta_keywords' => !empty($keywords) ? implode(',', $keywords) : null,
                        'og_title' => $firstRow['og_title'] ?? $product->name,
                        'og_description' => $firstRow['og_description'] ?? null,
                        'twitter_title' => $firstRow['twitter_title'] ?? $product->name,
                        'twitter_description' => $firstRow['twitter_description'] ?? null,
                    ]
                );

                //save product tabs
                $tabs = [];
                foreach ($firstRow as $key => $value) {
                    if (preg_match('/tab_(\d+)_heading/', $key, $matches)) {
                        $index = $matches[1];
                        $heading = $value;
                        $description = $firstRow['tab_' . $index . '_description'] ?? null;
                        if (!empty($heading) && !empty($description)) {
                            $tabs[] = [
                                'heading' => $heading,
                                'description' => $description,
                            ];
                        }
                    }
                }
                foreach ($tabs as $tab) {
                    $product->tabs()->updateOrCreate(
                        ['product_id' => $product->id], // Match by product
                        [
                            'heading' => $tab['heading'],
                            'content' => $tab['description']
                        ]
                    );
                }

                // Save Product Warranties from Excel
                $warranties = [];

                foreach ($firstRow as $key => $value) {
                    if (preg_match('/warranty_(\d+)_title/', $key, $matches)) {
                        $index = $matches[1];
                        $title = $value;
                        $months = $firstRow['warranty_' . $index . '_months'] ?? null;

                        // Only save if title and months are provided
                        if (!empty($title) && !empty($months)) {
                            $warranties[] = [
                                'title'       => $title,
                                'price'       => $firstRow['warranty_' . $index . '_price'] ?? 0,
                                'months'      => $months,
                                'description' => $firstRow['warranty_' . $index . '_description'] ?? null,
                            ];
                        }
                    }
                }

                foreach ($warranties as $warranty) {
                    $product->warranties()->updateOrCreate(
                        ['product_id' => $product->id], // match by product
                        [
                            'title' => $warranty['title'], 
                            'months' => $warranty['months'],
                            'price' => $warranty['price'],
                            'description' => $warranty['description'],
                        ]
                    );
                }

                // save images (thumbnail and gallery)
                if (isset($firstRow['url_1'])) {
                    $product->thumbnail_img = ImageHelper::downloadAndResizeImage(
                        'main_product',
                        $firstRow['url_1'],
                        $product->sku,
                        true
                    );
                }

                $gallery = [];
                if (isset($firstRow['url_2'])) {
                    if (!empty($firstRow['url_2'])) {
                        $urls = explode(',', $firstRow['url_2']);
                        foreach ($urls as $i => $url) {
                            $url = trim($url);
                            if (filter_var($url, FILTER_VALIDATE_URL)) {
                                $gallery[] = ImageHelper::downloadAndResizeImage(
                                    'main_product',
                                    $url,
                                    $product->sku,
                                    false,
                                    $i + 1
                                );
                            }
                        }
                    }
                }
                $product->photos = implode(',', $gallery);
                $product->save();

                // save stock
                foreach ($products as $row) {
                    
                    $offertag = '';
                    $productOrgPrice = $row['price'];
                    $discountPrice = $productOrgPrice;
                    $now = time();

                    if ($product->discount_start_date && $now >= (int) $product->discount_start_date && $now <= (int) $product->discount_end_date) {
                        if ($product->discount_type == 'percent') {
                            $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                            $offertag = $product->discount . '% OFF';
                        }
                        if ($product->discount_type == 'amount') {
                            $discountPrice = $productOrgPrice - $product->discount;
                            $offertag = 'AED ' . $product->discount . ' OFF';
                        }
                    }

                     // Collect all images for this stock
                    $stockImages = [];

                    // Stock image first
                    if (!empty($row['stock_image'])) {
                        $stockImages[] = ImageHelper::downloadAndResizeImage(
                            'sub_product',
                            $row['stock_image'],
                            $product->sku,
                            true
                        );
                    }

                    // Include product thumbnail
                    if (!empty($product->thumbnail_img)) {
                        $stockImages[] = $product->thumbnail_img;
                    }

                    // Include product gallery photos
                    if (!empty($product->photos)) {
                        $productGallery = explode(',', $product->photos);
                        $stockImages = array_merge($stockImages, $productGallery);
                    }

                    $stock = ProductStock::updateOrCreate(
                        ['product_id' => $product->id, 'sku' => $row['sku']], // Match by product + SKU
                        [
                            'qty' => $row['quantity'] ?? 0,
                            'stock_title' => $row['title'],
                            'model' => $row['model'],
                            'stock_description' => $row['stock_description'],
                            'status' => strtolower(trim($row['stock_status'] ?? '')) === 'inactive' ? 0 : 1,
                            'type' => $row['parent_sku'] ? 'variant' : 'single',
                            'image' =>  implode(',', $stockImages),
                            'price' => $productOrgPrice ?? 0,
                            'offer_price' => $discountPrice ?? 0,
                            'offer_tag' => $offertag ?? '',
                        ]
                    );

                    // save specification for the stock
                    if (!empty($row['specification'])) {
                        $specs = explode(',', $row['specification']);
                        foreach ($specs as $index => $spec) {
                            if (strpos($spec, ':') === false) {
                                continue;
                            }

                            [$key, $value] = array_map('trim', explode(':', $spec, 2));

                            // skip if either key or value is empty
                            if (empty($key) || empty($value)) {
                                continue;
                            }

                            $specification = Specification::firstOrCreate([
                                'main_title' => $key
                            ]);

                            $specificationItem = SpecificationItem::firstOrCreate([
                                'title' => $value,
                                'main_specification_id' => $specification->id
                            ]);

                            ProductSpecification::updateOrCreate(
                                [
                                    'product_stock_id' => $stock->id,
                                    'specification_id' => $specification->id,
                                ],
                                [
                                    'product_id' => $product->id,
                                    'specification_item_id' => $specificationItem->id,
                                    'sort_order' => $index + 1,
                                ]
                            );
                        }
                    }

                    // Save attributes for this stock
                    if (!empty($row['attribute'])) {
                        $attributes = explode(',', $row['attribute']);
                        foreach ($attributes as $attr) {
                            if (strpos($attr, ':') !== false) {
                                [$key, $value] = explode(':', $attr);
                                // Create / Get Attribute
                                $attribute = Attribute::firstOrCreate([
                                    'name' => trim($key)
                                ]);

                                // Create / Get Attribute Value
                                $attributeValue = AttributeValue::firstOrCreate([
                                    'value' => trim($value),
                                    'attribute_id' => $attribute->id
                                ]);

                                // Save Product Attribute
                                ProductAttributes::updateOrCreate(
                                    [
                                        'product_id' => $product->id,
                                        'product_varient_id' => $stock->id,
                                        'attribute_id' => $attribute->id,
                                    ],
                                    [
                                        'attribute_value_id' => $attributeValue->id,
                                        'updated_at' => now()
                                    ]
                                );
                            }
                        }
                    }
                }

            DB::commit();

            } catch (\Exception $e) {
                Log::error('Product Import Error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                DB::rollBack();
                flash('Products imported Failed.')->error();
                continue;
            }
        }

        flash('Products imported successfully.')->success();
    }

    public function model(array $row)
    {
        $this->rows++;
    }

    public function getRowCount()
    {
        return $this->rows;
    }

    // public function productSlug($name)
    // {
    //     $slug = Str::slug($name, '-');
    //     $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
    //     $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
    //     $slug .= $slug_suffix;

    //     return $slug;
    // }

    public function productSlug($name, &$generatedSlugs = [])
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::where('slug', $slug)->exists() ||
            in_array($slug, $generatedSlugs)
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $generatedSlugs[] = $slug;

        return $slug;
    }
    public function categorySlug($name)
    {
        $slug = Str::slug($name, '-');
        $same_slug_count = CategoryTranslation::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        return $slug;
    }

    /**
     * validation rules
     * 
     * @return array
     */
    // public function rules(): array
    // {
    //     return [
    //         '*.sku' => 'required|distinct',
    //         '*.product_name' => 'required'
    //     ];
    // }

    /**
     * Custom validation message.
     */
    public function customValidationMessages()
    {
        return [
            '*.sku.required' => 'SKU is required.',
            '*.sku.distinct' => 'Duplicate SKU found in the file.',
            '*.product_name.required' => 'Product Name is required.',
        ];
    }

    public function cleanSKU($sku)
    {
        $sku = trim($sku);
        $sku = preg_replace('/[^a-zA-Z0-9\-\_]/i', '', $sku);
        return $sku;
    }

    /**
     * Pick the latest non-empty value for a specific field from a collection of rows.
     *
     * @param mixed $rows
     * @param string $field
     *
     * @return mixed|null
     */
    function pickLatestValue($rows, $field)
    {
        foreach ($rows->reverse() as $row) {
            if (!empty($row[$field])) {
                return $row[$field];
            }
        }
        return null;
    }
}
