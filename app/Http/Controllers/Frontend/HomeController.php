<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\HomeSlider;
use App\Models\Page;
use App\Models\Product;
use App\Models\Testimonials;
use App\Models\Upload;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->first();
        $page_content = $page ? json_decode($page->data, true) : [];

        // Fetch the sliders data
        $slider_ids = $page_content['home_slider'] ?? [];
        $sliders = collect();
        if (!empty($slider_ids)) {
            $sliders = HomeSlider::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $slider_ids)
                ->orderByRaw("FIELD(id," . implode(',', $slider_ids) . ")")
                ->get();
        }

        // Fetch the banners data
        $banner_ids = $page_content['home_banners'] ?? [];
        $banners = collect();
        if (!empty($banner_ids)) {
            $banners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $banner_ids)
                ->orderByRaw("FIELD(id," . implode(',', $banner_ids) . ")")
                ->get();
        }

        // Fetch the cate
        $categories = collect();
        if (!empty($page_content['categories'])) {
            $categories = Category::with('iconImage')
                ->whereIn('id', $page_content['categories'])
                ->get();
        }

        // Fetch featured products
        // NEW ARRIVALS
        $newArrivals = collect($page_content['new_arrivals'] ?? []);
        $newImageIds = $newArrivals->pluck('featured_new_image')->filter()->toArray();
        $newUploads = Upload::whereIn('id', $newImageIds)->get()->keyBy('id');

        // POPULAR ITEMS
        $popularItems = collect($page_content['popular_products'] ?? []);
        $popularImageIds = $popularItems->pluck('featured_popular_image')->filter()->toArray();
        $popularUploads = Upload::whereIn('id', $popularImageIds)->get()->keyBy('id');

        // Upcoming product details
        $upcomingNewProducts = Product::whereIn('id', $page_content['upcoming_new_products'] ?? [])->get();
        $upcomingPopularProducts = Product::whereIn('id', $page_content['upcoming_popular_products'] ?? [])->get();

        // --- Middle Banners ---
        $middleBannerIds = $page_content['middle_banners'] ?? [];
        $middleBanners = collect();
        if (!empty($middleBannerIds)) {
            $middleBanners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $middleBannerIds)
                ->orderByRaw("FIELD(id," . implode(',', $middleBannerIds) . ")")
                ->get();
        }

        // Middle featured product details
        $middleNewProducts = Product::whereIn('id', $page_content['middle_featured_new_arrivals'] ?? [])->get();
        $middlePopularProducts = Product::whereIn('id', $page_content['middle_featured_popular_products'] ?? [])->get();

        //Middle full banner
        $middleFullBannerIds = $page_content['middle_full_banner'] ?? [];
        $middleFullBanners = collect();
        if (!empty($middleFullBannerIds)) {
            $middleFullBanners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $middleFullBannerIds)
                ->orderByRaw("FIELD(id," . implode(',', $middleFullBannerIds) . ")")
                ->get();
        }

        // best deals product details
        $bestDealsProducts = Product::whereIn('id', $page_content['best_deals_products'] ?? [])->get();

        // product gallery images
        $popularGalleryProducts = Product::whereIn('id', $page_content['product_gallery_products'] ?? [])->get();
        
        // Graphic card product details
        $graphicCardProducts = Product::whereIn('id', $page_content['graphic_cards_products'] ?? [])->get();

        //Testimoanials details
        $testimonialsText = Testimonials::where('type', 'text')
            ->where('status', 1)
            ->latest()
            ->get();

        $testimonialsVideo = Testimonials::where('type', 'video')
            ->where('status', 1)
            ->latest()
            ->get();

        // Footer content
        $homePageFooters = $page_content['footers'] ?? [];
        $footerImageIds = collect($homePageFooters)->pluck('footer_image')->filter()->toArray();
        $footerUploads = Upload::whereIn('id', $footerImageIds)->get()->keyBy('id');
        

        return view('frontend.home', compact('page_content', 'sliders', 'banners', 'categories', 'newArrivals', 'popularItems', 'newUploads', 'popularUploads', 'upcomingNewProducts', 'upcomingPopularProducts', 'middleBanners', 'middleNewProducts', 'middlePopularProducts', 'middleFullBanners', 'bestDealsProducts', 'popularGalleryProducts', 'graphicCardProducts', 'testimonialsText', 'testimonialsVideo','homePageFooters', 'footerUploads'));
    }
}
