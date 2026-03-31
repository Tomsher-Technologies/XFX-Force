<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('is_active', 1)
                        ->orderBy('name', 'asc')
                        ->get();

        return view('frontend.brands-list', compact('brands'));
    }

    public function show($slug)
    {
        // Fetch the brand by slug
        $brand = Brand::where('slug', $slug)->firstOrFail();

        // Get details JSON as array
        $details = $brand->details ?? [];

        // Pass to Blade
        return view('frontend.brands.show', compact('brand', 'details'));
    }
}
