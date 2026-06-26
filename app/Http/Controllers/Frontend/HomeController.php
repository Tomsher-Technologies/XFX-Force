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
use App\Models\Contacts;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use App\Mail\ContactEnquiry;
use Mail;

class HomeController extends Controller
{
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

    public function index()
    {
        $page = Page::where('slug', 'home')->first();
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

        $this->loadSEO($seoContents);

        // Fetch the sliders data
        $slider_ids = $page_content['home_slider'] ?? [];
        $sliders = collect();
        if (!empty($slider_ids)) {
            $sliders = HomeSlider::with(['mainImage', 'mobileImage', 'mainVideo', 'mobileVideo'])
                ->whereIn('id', $slider_ids)
                ->where('status', 1)
                ->orderByRaw("FIELD(id," . implode(',', $slider_ids) . ")")
                ->get();
        }

        // Fetch the banners data
        $banner_ids = $page_content['home_banners'] ?? [];
        $banners = collect();
        if (!empty($banner_ids)) {
            $banners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $banner_ids)
                ->where('status', 1)
                ->orderByRaw("FIELD(id," . implode(',', $banner_ids) . ")")
                ->get();
        }

        // Fetch the cate
        $categories = collect();
        if (!empty($page_content['categories'])) {
            $categories = Category::with('iconImage')
                ->whereIn('id', $page_content['categories'])
                ->where('is_active', 1)
                ->get();
        } else {
            $categories = Category::with('iconImage')
                ->where('is_active', 1)
                ->where('parent_id', 0)
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
        $upcomingNewProducts = Product::whereIn('id', $page_content['upcoming_new_products'] ?? [])
        ->where('published', 1)
        ->get();
        $upcomingPopularProducts = Product::whereIn('id', $page_content['upcoming_popular_products'] ?? [])
        ->where('published', 1)
        ->get();

        // --- Middle Banners ---
        $middleBannerIds = $page_content['middle_banners'] ?? [];
        $middleBanners = collect();
        if (!empty($middleBannerIds)) {
            $middleBanners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $middleBannerIds)
                ->where('status', 1)
                ->orderByRaw("FIELD(id," . implode(',', $middleBannerIds) . ")")
                ->get();
        }

        // Middle featured product details
        $middleNewProducts = Product::whereIn('id', $page_content['middle_featured_new_arrivals'] ?? [])
        ->where('published', 1)
        ->get();
        $middlePopularProducts = Product::whereIn('id', $page_content['middle_featured_popular_products'] ?? [])
        ->where('published', 1)
        ->get();

        //Middle full banner
        $middleFullBannerIds = $page_content['middle_full_banner'] ?? [];
        $middleFullBanners = collect();
        if (!empty($middleFullBannerIds)) {
            $middleFullBanners = Banner::with(['mainImage', 'mobileImage'])
                ->whereIn('id', $middleFullBannerIds)
                ->where('status', 1)
                ->orderByRaw("FIELD(id," . implode(',', $middleFullBannerIds) . ")")
                ->get();
        }

        // best deals product details
        $bestDealsProducts = Product::whereIn('id', $page_content['best_deals_products'] ?? [])
        ->where('published', 1)
        ->get();

        // product gallery images
        $popularGalleryProducts = Product::whereIn('id', $page_content['product_gallery_products'] ?? [])
        ->with('stocks')
        ->where('published', 1)
        ->get();
        
        // Graphic card product details
        $graphicCardProducts = Product::whereIn('id', $page_content['graphic_cards_products'] ?? [])
        ->where('published', 1)
        ->get();

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

    public function about()
    {
        $page = Page::where('type', 'about_us')->first();

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

        $this->loadSEO($seoContents);

        return view('frontend.about', compact('page', 'page_content'));
    }
    
    public function terms()
    {
        $page = Page::where('type', 'terms')->first();
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
        
        $this->loadSEO($seoContents);
        return view('frontend.terms', compact('page','page_content'));
    }

    public function privacy()
    {
        $page = Page::where('type', 'privacy_policy')->first();
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
        
        $this->loadSEO($seoContents);
        return view('frontend.privacy', compact('page','page_content'));
    }

    public function returnPolicy()
    {
        $page = Page::where('type', 'return_policy')->first();
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
        
        $this->loadSEO($seoContents);
        return view('frontend.return_policy', compact('page','page_content'));
    }

    public function cookiePolicy()
    {
        $page = Page::where('type', 'cookie_policy')->first();
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
        
        $this->loadSEO($seoContents);
        return view('frontend.policy', compact('page','page_content'));
    }

    public function shippingPolicy()
    {
        $page = Page::where('type', 'shipping_policy')->first();
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
        
        $this->loadSEO($seoContents);
        return view('frontend.policy', compact('page','page_content'));
    }

    public function warrantyPolicy()
    {
        $page = Page::where('type', 'warranty_policy')->first();
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
        
        $this->loadSEO($seoContents);
        return view('frontend.policy', compact('page','page_content'));
    }

    public function contact()
    {
        $page = Page::where('type', 'contact_us')->first();
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
       
        $this->loadSEO($seoContents);
        return view('frontend.contact', compact('page', 'page_content'));
    }

    public function submitContactForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9\-\+\s\(\)]{10,15}$/',
            'subject' => 'required|string|min:5|max:255',
            'message' => 'required|string|min:10',
            'g-recaptcha-response' => 'required'
        ],[
            'g-recaptcha-response.required' => 'Please verify you are not a robot.'
            ]);
        
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);
    
        $result = $response->json();
    
        if (!($result['success'] ?? false)) {
            return back()->withErrors(['g-recaptcha-response' => 'Captcha validation failed.'])->withInput();
        }

        $con                = new Contacts;
        $con->name          = $request->name ?? '';
        $con->email         = $request->email ?? '';
        $con->phone         = $request->phone ?? '';
        $con->subject       = $request->subject ?? '';
        $con->message       = $request->message ?? '';
        $con->save();
        
        // Send an email (optional)
        Mail::to(env('MAIL_ADMIN'))->queue(new ContactEnquiry($con));

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
