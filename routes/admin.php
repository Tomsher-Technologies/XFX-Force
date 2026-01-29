<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AizUploadController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductBulkUploadController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\Bannercontroller;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\HomeSliderController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AbandonedCartController;
use App\Http\Controllers\Admin\NotificationController;


Route::get('/admin/notifications', function () {
    $admin = auth()->user();
    return $admin->unreadNotifications;
})->middleware('auth');

Route::post('/admin/notifications/mark-as-read', function () {
    $admin = auth()->user();
    $admin->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
})->middleware('auth');

Route::group(['middleware' => ['guest']], function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login']);
    
});

Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');
    Route::get('/cache-cache', [AdminController::class, 'clearCache'])->name('cache.clear');

    Route::resource('roles', RoleController::class);
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');

    Route::post('/banners/get_form', [Bannercontroller::class, 'get_form'])->name('banners.get_form');
    Route::get('/banners/destroy/{banner}', [Bannercontroller::class, 'destroy'])->name('banners.destroy');
    Route::resource('banners', Bannercontroller::class)->except(['show', 'destroy']);
    Route::get('/banners/edit/{id}', [Bannercontroller::class, 'edit'])->name('banners.edit');

    Route::get('/enquiries-contact', [PageController::class, 'enquiries'])->name('enquiries.contact');
    // website setting
    Route::group(['prefix' => 'website'], function () {
        Route::get('/footer', [WebsiteController::class, 'footer'])->name('website.footer');

        Route::get('/menu', [WebsiteController::class, 'menu'])->name('website.menu');
        Route::post('/menu', [WebsiteController::class, 'menuUpdate']);

        Route::get('/header', [WebsiteController::class, 'header'])->name('website.header');
        Route::get('/appearance', [WebsiteController::class, 'appearance'])->name('website.appearance');
        
        Route::post('/home-slider/update-status', [HomeSliderController::class, 'updateStatus'])->name('home-slider.update-status');
        Route::get('/home-slider/delete/{id}', [HomeSliderController::class, 'destroy'])->name('home-slider.delete');
        Route::resource('home-slider', HomeSliderController::class);

        Route::resource('custom-pages', PageController::class);
        Route::get('/pages', [PageController::class, 'index'])->name('website.pages');
        Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');
        Route::get('/custom-pages/destroy/{id}', [PageController::class, 'destroy'])->name('custom-pages.destroy');
        Route::post('/page/delete_image', [PageController::class, 'delete_image'])->name('page.delete_image');

        Route::get('/subscribers', [PageController::class, 'subscribers'])->name('subscribers.index');
        Route::get('/subscribers/destroy/{id}', [PageController::class, 'subscribersDestroy'])->name('subscriber.destroy');
    
        // Partners
        Route::resource('partners', PartnersController::class)->except('show');

        // Manage testimonials
        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::get('/testimonials/delete/{id}', [TestimonialController::class, 'destroy'])->name('testimonials.delete');
        Route::post('/testimonials/update-status', [TestimonialController::class, 'updateStatus'])->name('testimonials.update-status');
        
    });

    // Brands
    Route::resource('brands', BrandController::class);
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('/brands/status', [BrandController::class, 'updateStatus'])->name('brands.status');
    
    //Coupons
    Route::resource('coupon', CouponController::class);
    Route::get('/coupon/destroy/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy');
    Route::post('/coupon/get_form', [CouponController::class, 'get_coupon_form'])->name('coupon.get_coupon_form');
    Route::post('/coupon/get_form_edit', [CouponController::class, 'get_coupon_form_edit'])->name('coupon.get_coupon_form_edit');

    // uploaded files
    Route::any('/uploaded-files/file-info', [AizUploadController::class, 'file_info'])->name('uploaded-files.info');
    Route::resource('/uploaded-files', AizUploadController::class);
    Route::get('/uploaded-files/destroy/{id}', [AizUploadController::class, 'destroy'])->name('uploaded-files.destroy');
    Route::post('/aiz-uploader', [AizUploadController::class, 'show_uploader']);
    Route::post('/aiz-uploader/upload', [AizUploadController::class, 'upload']);
    Route::get('/aiz-uploader/get_uploaded_files', [AizUploadController::class, 'get_uploaded_files']);
    Route::post('/aiz-uploader/get_file_by_ids', [AizUploadController::class, 'get_preview_files']);
    Route::get('/aiz-uploader/download/{id}', [AizUploadController::class, 'attachment_download'])->name('download_attachment');

    //Notifications
    Route::get('/all-notification', [NotificationController::class, 'index'])->name('admin.all-notification');

    // Categories
    Route::get('/generate-slug', [CategoryController::class, 'generateSlug'])->name('generate-slug');
    Route::resource('categories', CategoryController::class)->except(['destroy']);
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/categories/status', [CategoryController::class, 'updateStatus'])->name('categories.status');

    Route::get('/products/all', [ProductController::class, 'all_products'])->name('products.all');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store/', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/products/admin/{id}/edit', [ProductController::class, 'admin_product_edit'])->name('products.edit');
    Route::post('/products/todays_deal', [ProductController::class, 'updateTodaysDeal'])->name('products.todays_deal');
    Route::post('/products/published', [ProductController::class, 'updatePublished'])->name('products.published');
    Route::post('/products/approved', [ProductController::class, 'updateProductApproval'])->name('products.approved');
    Route::post('/products/featured', [ProductController::class, 'updateFeatured'])->name('products.featured');
    Route::post('/bulk-product-delete', [ProductController::class, 'bulk_product_delete'])->name('bulk-product-delete');
    Route::post('/products/delete-thumbnail', [ProductController::class, 'delete_thumbnail'])->name('products.delete_thumbnail');
    Route::post('/products/delete_gallery', [ProductController::class, 'delete_gallery'])->name('products.delete_gallery');

    Route::get('/product-bulk-upload/index', [ProductBulkUploadController::class, 'index'])->name('product_bulk_upload.index');
    Route::post('/bulk-product-upload', [ProductBulkUploadController::class, 'bulk_upload'])->name('bulk_product_upload');

    //Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/published', [ReviewController::class, 'updatePublished'])->name('reviews.published');

    Route::get('/all_orders', [OrderController::class, 'all_orders'])->name('all_orders.index');
    Route::get('/all_orders/{id}/show', [OrderController::class, 'all_orders_show'])->name('all_orders.show');
    Route::get('/cancel_requests', [OrderController::class, 'allCancelRequests'])->name('cancel_requests.index');
    Route::post('/cancel-request-status', [OrderController::class, 'cancelRequestStatus'])->name('cancel-request-status');
    Route::get('/cancel_orders/{id}/show', [OrderController::class, 'cancel_orders_show'])->name('cancel_orders.show');
    Route::post('/orders/update_delivery_status', [OrderController::class, 'update_delivery_status'])->name('orders.update_delivery_status');
    Route::post('/orders/update_payment_status', [OrderController::class, 'update_payment_status'])->name('orders.update_payment_status');
    Route::post('/orders/update_tracking_code', [OrderController::class, 'update_tracking_code'])->name('orders.update_tracking_code');

    Route::get('/return_requests', [OrderController::class, 'allReturnRequests'])->name('return_requests.index');
    Route::post('/return-request-status', [OrderController::class, 'updateReturnStatus'])->name('return-request-status');
    Route::get('/return_orders/{id}/show', [OrderController::class, 'return_orders_show'])->name('return_orders.show');

    Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');

    Route::resource('customers', CustomerController::class);
    Route::get('customers_ban/{customer}', [CustomerController::class, 'ban'])->name('customers.ban');
    Route::get('/customers/login/{id}', [CustomerController::class, 'login'])->name('customers.login');
    Route::get('/customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/bulk-customer-delete', [CustomerController::class, 'bulk_customer_delete'])->name('bulk-customer-delete');

    // Route::resource('custom-pages', PageController::class);
    // Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');

    // Route::get('/pages', [WebsiteController::class, 'index'])->name('website.pages');

    Route::post('/business-settings/update', [BusinessSettingsController::class, 'update'])->name('business_settings.update');

    // Manage services

    Route::get('/service/all', [ServiceController::class, 'index'])->name('service.index');
    Route::get('/service/create', [ServiceController::class, 'create'])->name('service.create');
    Route::post('/service/store/', [ServiceController::class, 'store'])->name('service.store');
    Route::get('/service/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
    Route::post('/service/update/{id}', [ServiceController::class, 'update'])->name('service.update');
    Route::post('/service/status', [ServiceController::class, 'updateStatus'])->name('service.status');
    Route::get('/service/delete/{id}', [ServiceController::class, 'destroy'])->name('service.delete');


    //Manage faq categories
    Route::get('/faq_categories/all', [FaqCategoryController::class, 'index'])->name('faq_categories.index');
    Route::get('/faq_categories/create', [FaqCategoryController::class, 'create'])->name('faq_categories.create');
    Route::post('/faq_categories/store/', [FaqCategoryController::class, 'store'])->name('faq_categories.store');
    Route::get('/faq_categories/edit/{id}', [FaqCategoryController::class, 'edit'])->name('faq_categories.edit');
    Route::post('/faq_categories/update/{id}', [FaqCategoryController::class, 'update'])->name('faq_categories.update');
    Route::post('/faq_categories/status', [FaqCategoryController::class, 'updateStatus'])->name('faq_categories.status');
    Route::get('/faq_categories/delete/{id}', [FaqCategoryController::class, 'destroy'])->name('faq_categories.delete');

    Route::get('/faqs/edit/{id}', [FaqCategoryController::class, 'getAllCategoryFaqs'])->name('faqs.edit');
    Route::post('/faqs/update', [FaqCategoryController::class, 'updateFaq'])->name('faqs.update');

    // Manage Blogs
    
    Route::get('/blogs/all', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog/store/', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('/blog/update/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::post('/blog/status', [BlogController::class, 'updateStatus'])->name('blog.status');
    Route::get('/blog/delete/{id}', [BlogController::class, 'destroy'])->name('blog.delete');

    Route::get('/configuration', [BusinessSettingsController::class, 'shipping_configuration'])->name('shipping_configuration.index');
    Route::post('/shipping_configuration/free_shipping', [BusinessSettingsController::class, 'freeshipping_settings'])->name('shipping_configuration.free_shipping');
    Route::post('/configuration/return', [BusinessSettingsController::class, 'return_settings'])->name('configuration.return_settings');

    Route::post('/configuration/service', [BusinessSettingsController::class, 'service_settings'])->name('configuration.service_settings');

     //Reports
     Route::get('/stock_report', [ReportController::class, 'stock_report'])->name('stock_report.index');
     Route::get('/export-stock-report', [ReportController::class, 'exportStockReport'])->name('export.stock_report');
     
     Route::get('/in_house_sa le_report', [ReportController::class, 'in_house_sale_report'])->name('in_house_sale_report.index');
     
     Route::get('/wish_report', [ReportController::class, 'wish_report'])->name('wish_report.index');
     Route::get('/export-wish-report', [ReportController::class, 'exportWishReport'])->name('export.wish_report');
 
     Route::get('/user_search_report', [ReportController::class, 'user_search_report'])->name('user_search_report.index');
     Route::get('/export-search-report', [ReportController::class, 'exportSearchReport'])->name('export.search_report');
     
     Route::get('/abandoned-cart', [AbandonedCartController::class, 'index'])->name('abandoned-cart.index');
     Route::get('/{cart}/abandoned-cart', [AbandonedCartController::class, 'view'])->name('abandoned-cart.view');
     Route::get('/sales_report', [ReportController::class, 'sales_report'])->name('sales_report.index');
     Route::get('/sales_orders/{id}/show', [ReportController::class, 'sales_orders_show'])->name('sales_orders.show');
     Route::get('/export-sales-report', [ReportController::class, 'exportSalesReport'])->name('export.sales_report');
});