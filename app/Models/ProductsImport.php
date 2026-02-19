<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductSeo;
use App\Models\ProductStock;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
            'New' => 0,
            'Refurbished' => 1,
            'Open Box' => 2,
        ];

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
            $parentSku = $row['parent_sku'] ?? null;

            // Skip empty SKU
            if (empty($sku)) {
                $this->errorsList[] = [
                    'row' => $index + 2,
                    'column' => 'SKU',
                    'errors' => ["SKU is missing in the File. <strong>SKU: {$sku}</strong>"],
                    'values' => $row
                ];
                return false;
            }

            // Duplicate in file
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

            // Database duplicate
            if (Product::where('sku', $sku)->exists() || ProductStock::where('sku', $sku)->exists()) {
                $this->errorsList[] = [
                    'row' => $index + 2,
                    'column' => 'SKU',
                    'errors' => ["SKU already exists in database: <strong>SKU: {$sku}</strong>"],
                    'values' => $row
                ];
                return false;
            }

            // Product Name missing 
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

        // Now process only $validRows
        $grouped = $validRows->groupBy(function ($row) {
            return !empty($row['parent_sku'])
                ? $row['parent_sku']
                : $row['sku'];
        });

        foreach ($grouped as $products) {
            // save product.
            $firstRow = $products->first();

            $product = null;
            if (!empty($firstRow['parent_sku'])) {
                $product = Product::where('sku', $firstRow['parent_sku'])->first();
            }

            if (!$product) {
                
                $product = Product::create([
                    'sku' => $firstRow['parent_sku'] ?: $firstRow['sku'],
                    'name' =>  trim($this->pickLatestValue($products, 'product_name') ?? ''),
                    'video_provider' => trim($this->pickLatestValue($products, 'video_provider') ?? ''),
                    'video_link' => trim($this->pickLatestValue($products, 'video_link') ?? ''),
                    'category_id' => Category::where('name', $this->pickLatestValue($products, 'category'))->value('id'),
                    'brand_id' => Brand::where('name', $this->pickLatestValue($products, 'brand'))->value('id'),
                    'condition' => $conditionMap[trim($this->pickLatestValue($products, 'condition'))] ?? 0,
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
                    'slug' => $this->productSlug(trim($this->pickLatestValue($products, 'product_name') ?? '')),
                    'description' => trim($this->pickLatestValue($products, 'description') ?? ''),
                    'updated_by' => Auth::user()->id,
                ]);
            }

            // save specification
            if (!empty($firstRow['specification'])) {
                $specs = explode(',', $firstRow['specification']);
                foreach ($specs as $spec) {
                    [$key, $value] = explode(':', $spec);
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'specification_id' => Specification::where('main_title', trim($key))->value('id'),
                        'specification_item_id' => SpecificationItem::where('title', trim($value))->value('id'),
                    ]);
                }
            }

            //save product seo
            $keywords = array();
            if (!empty($firstRow['meta_keywords'])) {
                $keywords = array_map('trim', explode(',', $firstRow['meta_keywords']));
            }

            ProductSeo::create([
                'product_id' => $product->id,
                'meta_title' => $firstRow['meta_title'] ?? $product->name,
                'meta_description' => $firstRow['meta_description'] ?? null,
                'meta_keywords' => !empty($keywords) ? implode(',', $keywords) : null,
                'og_title' => $firstRow['og_title'] ?? $product->name,
                'og_description' => $firstRow['og_description'] ?? null,
                'twitter_title' => $firstRow['twitter_title'] ?? $product->name,
                'twitter_description' => $firstRow['twitter_description'] ?? null,
            ]);

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
                $product->tabs()->create([
                    'heading' => $tab['heading'],
                    'content' => $tab['description'],
                ]);
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

            // Insert into database
            foreach ($warranties as $warranty) {
                $product->warranty()->create([
                    'title'       => $warranty['title'],
                    'price'       => $warranty['price'],
                    'months'      => $warranty['months'],
                    'description' => $warranty['description'],
                ]);
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
                // offer price and tag 
                $offertag = '';
                $productOrgPrice = $row['price'];
                $discountPrice = $productOrgPrice;
                $now = time();

                if (
                    $product->discount_start_date &&
                    $now >= (int) $product->discount_start_date &&
                    $now <= (int) $product->discount_end_date
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


                $stock = ProductStock::create([
                    'product_id' => $product->id,
                    'sku' => $row['sku'],
                    'qty' => $row['quantity'],
                    'stock_title' => $row['title'],
                    'model' => $row['model'],
                    'stock_description' => $row['stock_description'],
                    'status' => strtolower(trim($row['stock_status'] ?? '')) === 'inactive' ? 0 : 1,
                    'type' => $row['parent_sku'] ? 'variant' : 'single',
                    'image' => ImageHelper::downloadAndResizeImage('sub_product', $row['stock_image'], $product->sku, true),
                    'price' => $productOrgPrice,
                    'offer_price' => $discountPrice,
                    'offer_tag' => $offertag,
                ]);

                // Save attributes for this stock
                if (!empty($row['attribute'])) {
                    $attributes = explode(',', $row['attribute']);
                    foreach ($attributes as $attr) {
                        if (strpos($attr, ':') !== false) {
                            [$key, $value] = explode(':', $attr);
                            $attributeId = Attribute::where('name', trim($key))->value('id');
                            $attributeValueId = AttributeValue::where('value', trim($value))
                                ->where('attribute_id', $attributeId)
                                ->value('id');

                            if ($attributeId && $attributeValueId) {
                                ProductAttributes::create([
                                    'product_id' => $product->id,
                                    'product_varient_id' => $stock->id,
                                    'attribute_id' => $attributeId,
                                    'attribute_value_id' => $attributeValueId,
                                ]);
                            }
                        }
                    }
                }
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

    public function productSlug($name)
    {
        $slug = Str::slug($name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

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
