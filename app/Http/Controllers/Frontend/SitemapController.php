<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
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

        return response()
            ->view('frontend.sitemap', compact('products'))
            ->header('Content-Type', 'application/xml');
    }
}
