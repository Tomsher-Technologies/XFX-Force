<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        Schema::defaultStringLength(191); 

        Blade::component('components.home.blog-list', 'blogList');
        Blade::component('components.home.testimonials', 'testimonials');
        Blade::component('components.footer', 'footer');

        // ✅ Use Bootstrap Pagination
        Paginator::useBootstrap();

        // Header Categories
        View::composer(['frontend.layouts.header','frontend.layouts.footer'], function ($view) {

            $headerCategories = Category::where('is_active', 1)
                ->orderBy('name')
                ->get();

            $headerBrands = Brand::where('is_active', 1)
                ->orderBy('name')
                ->get();

            $guestToken = request()->cookie('guest_token');

            if(!$guestToken){
                $guestToken = uniqid('guest_', true);
                cookie()->queue('guest_token', $guestToken, 60*24*14); // 14 days
            }

            $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
            $totalCartItemsCount = Cart::where(function($query) use ($guestToken, $user_id) {
                    if($user_id) {
                        // Logged-in user
                        $query->where('user_id', $user_id);
                    } else {
                        // Guest user
                        $query->where('temp_user_id', $guestToken);
                    }
                })
                ->sum('quantity');

            $view->with([
                'headerCategories' => $headerCategories,
                'headerBrands' => $headerBrands,
                'totalCartItemsCount' => $totalCartItemsCount
            ]);

            
        });
    }
}
