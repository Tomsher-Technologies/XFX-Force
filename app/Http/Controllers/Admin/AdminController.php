<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Search;
use Artisan;
use Cache;

class AdminController extends Controller
{
    public function admin_dashboard(Request $request)
    {
        // CoreComponentRepository::initializeCache();
        $root_categories = Category::where('parent_id', 0)->get();
        
        // E-commerce KPI metrics
        $total_customers = \App\Models\User::where('user_type', 'customer')->count();
        $today_customers = \App\Models\User::where('user_type', 'customer')->where('created_at', '>=', \Carbon\Carbon::today())->count();
        $this_month_customers = \App\Models\User::where('user_type', 'customer')->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->count();
        $total_orders = \App\Models\Order::where('order_success', 1)->count();
        $total_products = \App\Models\Product::count();
        $total_brands = \App\Models\Brand::count();
        
        $total_revenue = \App\Models\Order::where('order_success', 1)->where('delivery_status', '!=', 'cancelled')->sum('grand_total');
        $total_revenue_all = $total_revenue;
        $pending_orders = \App\Models\Order::where('order_success', 1)->where('delivery_status', 'pending')->count();
        
        $today_revenue = \App\Models\Order::where('order_success', 1)->where('delivery_status', '!=', 'cancelled')->where('date', '>=', strtotime('today'))->sum('grand_total');
        $today_orders = \App\Models\Order::where('order_success', 1)->where('date', '>=', strtotime('today'))->count();
        $this_month_revenue = \App\Models\Order::where('order_success', 1)->where('delivery_status', '!=', 'cancelled')->where('date', '>=', strtotime('first day of this month 00:00:00'))->sum('grand_total');

        // Order Status Counts for distribution doughnut chart
        $statuses = ['pending', 'confirmed', 'picked_up', 'on_the_way', 'delivered', 'cancelled'];
        $order_status_counts = [];
        foreach ($statuses as $status) {
            $order_status_counts[$status] = \App\Models\Order::where('order_success', 1)->where('delivery_status', $status)->count();
        }

        // Monthly Sales Trend for past 12 months
        $sales_trend_months = [];
        $sales_trend_data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month_start = strtotime("first day of -$i month 00:00:00");
            $month_end = strtotime("last day of -$i month 23:59:59");
            $sales_trend_months[] = date('M Y', $month_start);
            $sales_trend_data[] = \App\Models\Order::where('order_success', 1)->where('delivery_status', '!=', 'cancelled')->whereBetween('date', [$month_start, $month_end])->sum('grand_total');
        }

        // Recent Orders
        $recent_orders = \App\Models\Order::where('order_success', 1)->latest('id')->limit(8)->with(['user', 'orderDetails'])->get();

        // Top Selling Products
        $top_products = \App\Models\Product::orderBy('num_of_sale', 'desc')->limit(5)->get();

       

        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function () use ($root_categories) {
            $num_of_sale_data = null;
            $qty_data = null;
            foreach ($root_categories as $key => $category) {
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = 0;
                $sale = 0;
                foreach ($products as $key => $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty . ',';
                $num_of_sale_data .= $sale . ',';
            }
            $item['num_of_sale_data'] = $num_of_sale_data;
            $item['qty_data'] = $qty_data;

            return $item;
        });

        return view('backend.dashboard', compact(
            'root_categories', 
            'cached_graph_data', 
            'total_customers',
            'today_customers',
            'this_month_customers',
            'total_orders',
            'total_products',
            'total_brands',
            'total_revenue',
            'total_revenue_all',
            'pending_orders',
            'today_revenue',
            'today_orders',
            'this_month_revenue',
            'order_status_counts',
            'sales_trend_months',
            'sales_trend_data',
            'recent_orders',
            'top_products'
        ));
    }

    function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        $keys = [
            'footer_categories',
            'footer_services',
            'business_settings',
            'categoriesTree',
            'header_categories',
            'header_brands',
            'cached_graph_data',
            'homeSlider',
            'home_categories',
            'home_products',
            'home_products',
            'home_services',
            'home_testimonials',
            'home_blogs',
            'brandsWithCount'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
        
        Cache::flush();

        flash(trans('messages.cache_cleared_successfully'))->success();
        return back();
    }
}
