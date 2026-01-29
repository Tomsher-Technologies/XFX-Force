<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Models\PageTranslations;
use App\Models\PageSeos;
use App\Models\Brand;
use App\Models\HomeSlider;
use App\Models\Service;
use App\Models\BusinessSetting;
use App\Models\Subscriber;
use App\Models\Contacts;
use App\Models\Testimonials;
use App\Models\FaqCategory;
use App\Models\Blog;
use App\Mail\ContactEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\WebHomeProductsCollection;
use Illuminate\Support\Facades\Http;
use Storage;
use Validator;
use Mail;
use DB;
use Hash;
use Cache;

class FrontendController extends Controller
{
    public function loadSchemaSEO($model)
    {
        SEOTools::setTitle($model['title']);
        SEOTools::setDescription($model['meta_description']);
    
        SEOTools::opengraph()->setTitle($model['og_title']);
        SEOTools::opengraph()->setDescription($model['og_description']);
        SEOTools::opengraph()->setUrl(url()->full());
        SEOTools::opengraph()->addImage($model['og_image'] ?? asset('assets/img/logo.png'));
    
        SEOTools::twitter()->setTitle($model['twitter_title']);
        SEOTools::twitter()->setDescription($model['twitter_description']);
        SEOTools::twitter()->setSite('@homeiq');
    
        // Replace default WebPage schema
        $jsonLd = SEOTools::jsonLd();
        
        if ($model['type'] === 'product') {
            $jsonLd->setType('Product');
            $jsonLd->setTitle($model['meta_title']);
            $jsonLd->setDescription($model['meta_description']);
            $jsonLd->addImage($model['og_image'] ?? asset('assets/img/logo.png'));
        
            // Optional: Add product offers schema
            if (!empty($model['price'])) {
                $jsonLd->addValue('offers', [
                    '@type' => 'Offer',
                    'price' => $model['price'],
                    'priceCurrency' => $model['currency'] ?? 'AED',
                    'availability' => 'http://schema.org/InStock',
                    'url' => url()->full(),
                ]);
            }
        }else{
            $jsonLd->setType('WebPage');
            $jsonLd->setTitle($model['meta_title']);
            $jsonLd->setDescription($model['meta_description']);
            $jsonLd->addImage(asset('assets/img/favicon.ico'));
        }
        
    }
        
    public function loadSEO($model)
    {
        SEOTools::setTitle($model['title']);
        OpenGraph::setTitle($model['title']);
        TwitterCard::setTitle($model['title']);

        SEOMeta::setTitle($model['meta_title'] ?? $model['title']);
        SEOMeta::setDescription($model['meta_description']);
        SEOMeta::addKeyword($model['keywords']);

        OpenGraph::setTitle($model['og_title']);
        OpenGraph::setDescription($model['og_description']);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addProperty('locale', 'en_US');
        OpenGraph::addProperty('type', $model['og_type'] ?? 'website');
        OpenGraph::addImage($model['og_image'] ?? URL::to(asset('assets/img/logo.png')));
        
        JsonLd::setTitle($model['meta_title']);
        JsonLd::setDescription($model['meta_description']);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model['twitter_title']);
        TwitterCard::setSite("@homeiq");
        TwitterCard::setDescription($model['twitter_description']);

        SEOTools::jsonLd()->addImage(URL::to(asset('assets/img/favicon.ico')));
    }

    public function loadDynamicSEO($model)
    {
        SEOTools::setTitle($model->title);
        OpenGraph::setTitle($model->title);
        TwitterCard::setTitle($model->title);

        SEOMeta::setTitle($model->seo_title ?? $model->title);
        SEOMeta::setDescription($model->seo_description);
        SEOMeta::addKeyword($model->keywords);

        OpenGraph::setTitle($model->og_title);
        OpenGraph::setDescription($model->og_description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addProperty('locale', 'en_US');
           
        JsonLd::setTitle($model->seo_title);
        JsonLd::setDescription($model->seo_description);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model->twitter_title);
        TwitterCard::setSite("@homeiq");
        TwitterCard::setDescription($model->twitter_description);

        SEOTools::jsonLd()->addImage(URL::to(asset('assets/img/favicon.ico')));
    }

    public function home()
    {
        
    }

}
