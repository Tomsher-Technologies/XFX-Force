<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        /*
         * Published products with available stock and SKU.
         */
        $products = Product::select([
                'id',
                'slug',
                'updated_at',
            ])
            ->where('published', 1)
            ->whereNotNull('slug')
            ->whereHas('stocks', function ($query) {
                $query->where('qty', '>', 0)
                    ->whereNotNull('sku');
            })
            ->with(['stocks' => function ($query) {
                $query->select([
                    'id',
                    'product_id',
                    'sku',
                    'qty',
                ])
                ->where('qty', '>', 0)
                ->whereNotNull('sku');
            }])
            ->get();

        /*
         * Active categories.
         *
         * Category slug is stored in category_translations table.
         */
        $categories = Category::select([
                'id',
                'updated_at',
            ])
            ->where('is_active', 1)
            ->with(['category_translations' => function ($query) {
                $query->select([
                    'id',
                    'category_id',
                    'slug',
                ]);
            }])
            ->get();

        /*
         * Active brands.
         *
         * Brand slug is stored directly in brands table.
         */
        $brands = Brand::select([
                'id',
                'slug',
                'updated_at',
            ])
            ->where('is_active', 1)
            ->whereNotNull('slug')
            ->get();

        return response()
            ->view('frontend.sitemap', compact(
                'products',
                'categories',
                'brands'
            ))
            ->header('Content-Type', 'application/xml');
    }
}
