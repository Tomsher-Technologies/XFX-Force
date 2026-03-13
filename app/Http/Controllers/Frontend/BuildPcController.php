<?php

namespace App\Http\Controllers\Frontend;

use App\Models\PcBuilderCategorySetting;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\PcBuilderSetup;
use App\Models\ProductStock;

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
                ->get();
        }

        $builder = PcBuilderSetup::where('user_id', auth()->id())->first();
        $buildData = $builder ? $builder->build_data : [];

        $reviewProducts = [];

        if ($buildData) {
            $reviewProducts = $this->getReviewProducts($buildData);
        }

        return view('frontend.buildyourpc', compact(
            'builderCategories',
            'products',
            'firstCategory',
            'builder',
            'buildData',
            'reviewProducts'
        ));
    }

    // AJAX to get products by category
    public function getProductsByCategory(Request $request)
    {

        $categoryId = $request->category_id;

        $products = Product::where('category_id', $categoryId)->with('stocks')->get();

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
        if ($request->builder_id) {
            $builder = PcBuilderSetup::find($request->builder_id);
        } else {
            $builder = PcBuilderSetup::firstOrCreate(
                ['user_id' => auth()->id()],
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
        $builder = PcBuilderSetup::where('user_id', auth()->id())->first();
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
        $builderId = $request->builder_id;

        $builder = PcBuilderSetup::find($builderId);

        if (!$builder) {
            return response()->json([
                'status' => false,
                'message' => 'Builder not found'
            ]);
        }

        $buildData = $builder->build_data;

        // Remove old builder cart items
        Cart::where('user_id', auth()->id())
            ->where('pc_builder_id', $builderId)
            ->delete();

        foreach ($buildData as $categoryId => $products) {

            foreach ($products as $item) {

                $variant = ProductStock::find($item['variant_id']);

                if (!$variant) continue;

                Cart::create([
                    'user_id' => auth()->id(),
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
        $builderId = $request->builder_id;

        Cart::where('user_id', auth()->id())
            ->where('pc_builder_id', $builderId)
            ->delete();

        $builder = PcBuilderSetup::where('id', $builderId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$builder) {
            return response()->json([
                'status' => false,
                'message' => 'Configuration not found'
            ]);
        }

        $builder->delete();

        
        return response()->json([
            'status' => true,
            'message' => 'Configuration reset successfully'
        ]);
    }
}
