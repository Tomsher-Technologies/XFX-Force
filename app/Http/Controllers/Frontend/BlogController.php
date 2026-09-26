<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\URL;

class BlogController extends Controller
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

        OpenGraph::addImage(
            uploaded_asset(get_setting('default_seo_og_image'))
            ?? URL::to(asset('assets/img/logo.png'))
        );

        JsonLd::setTitle($model['title']);
        JsonLd::setDescription($model['meta_description']);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model['twitter_title']);
        TwitterCard::setSite('@pcgarage');
        TwitterCard::setDescription($model['twitter_description']);

        SEOTools::jsonLd()->addImage(
            URL::to(asset('assets/img/favicon.ico'))
        );
    }

    /**
     * Blog listing.
     */
    public function blogs()
    {
        $page = Page::where('type', 'blog_listing')->first();

        $page_content = $page
            ? json_decode($page->data, true)
            : [];

        $seoContents = [
            'title' => $page_content['meta_title'] ?? 'Blogs',
            'meta_description' => $page_content['meta_description'] ?? '',
            'keywords' => $page_content['keywords'] ?? '',
            'og_title' => $page_content['og_title'] ?? 'Blogs',
            'og_description' => $page_content['og_description'] ?? '',
            'twitter_title' => $page_content['twitter_title'] ?? 'Blogs',
            'twitter_description' => $page_content['twitter_description'] ?? '',
        ];

        $this->loadSEO($seoContents);

        $blogs = Blog::where('status', 1)
            ->latest()
            ->get();

        return view('frontend.blogs', compact(
            'page',
            'page_content',
            'blogs'
        ));
    }

    /**
     * Blog details.
     */
    public function blogDetails($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $seoContents = [
            'title' => $blog->meta_title ?? $blog->title ?? 'Blog',
            'meta_description' => $blog->meta_description ?? '',
            'keywords' => $blog->keywords ?? '',
            'og_title' => $blog->og_title ?? $blog->title ?? 'Blog',
            'og_description' => $blog->og_description ?? '',
            'twitter_title' => $blog->twitter_title ?? $blog->title ?? 'Blog',
            'twitter_description' => $blog->twitter_description ?? '',
        ];

        $this->loadSEO($seoContents);

        return view('frontend.blog-details', compact('blog'));
    }
}
