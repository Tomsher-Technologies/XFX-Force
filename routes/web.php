<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\BrandController;
use App\Http\Controllers\Frontend\BuildPcController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ForgotPasswordController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->middleware('nocache')->name('home');

Route::get('register/', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::get('terms', [HomeController::class, 'terms'])->name('terms');
Route::get('privacy-policy', [HomeController::class, 'privacy'])->name('privacy-policy');
Route::get('return-policy', [HomeController::class, 'returnPolicy'])->name('return-policy');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact-submit', [HomeController::class, 'submitContactForm'])->name('contact.submit');
Route::get('about', [HomeController::class, 'about'])->name('about');

Route::get('forgot-password/', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.sendResetLink');
Route::get('/password/reset/{email}/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}/{sku?}', [ProductController::class, 'productDetails'])
    ->middleware('nocache')
    ->name('product.details');
Route::get('/products', [ProductController::class, 'index'])->middleware('nocache')->name('products');
Route::get('/get-variants-by-value', [ProductController::class, 'getVariantsByValue']);
Route::get('/getVarientDetails', [ProductController::class, 'getVarientDetails']);
Route::get('/getVariantState', [ProductController::class, 'getVariantState']);

Route::middleware('nocache')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/buildyourpc', [BuildPcController::class, 'index'])->name('buildyourpc');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
});


Route::get('/addProductToCart', [CartController::class, 'addProductToCart']);
Route::get('/removeCartItem/{id}', [CartController::class, 'removeCartItem']);
Route::get('/getCartSummary', [CartController::class, 'getCartSummary'])->middleware('nocache');
Route::get('/updateProductWarranty', [CartController::class, 'updateProductWarranty']);
Route::post('/apply_coupon_code', [CartController::class, 'apply_coupon_code']);
Route::post('/remove_coupon_code', [CartController::class, 'remove_coupon_code']);

Route::get('/shop/category/{slug}', [ProductController::class, 'shopByCategory'])->middleware('nocache')->name('shop.category');
Route::get('/shop/brand/{slug}', [ProductController::class, 'shopByBrand'])->middleware('nocache')->name('shop.brand');


Route::get('/buildyourpc/products/details/{stockId}', [BuildPcController::class, 'getProductDetails']);
Route::get('/buildyourpc/products/{category_id}/{brand_id?}/{model?}', [BuildPcController::class, 'getProductsByCategory']);

Route::get('/buildyourpc/savePcBuilder', [BuildPcController::class, 'savePcBuilder']);
Route::get('/buildyourpc/getBuildData', [BuildPcController::class, 'getBuildData']);
Route::get('/buildyourpc/place-order', [BuildPcController::class, 'placePcBuilderOrder'])->name('pcbuilder.place.order');
Route::post('/pc-builder/reset', [BuildPcController::class, 'resetConfiguration'])->name('pc.builder.reset');
Route::post('/pc-builder/delete', [BuildPcController::class, 'deletePcBuilder'])->name('pc.builder.delete');


Route::post('/checkout/placeOrder', [CheckoutController::class, 'placeOrder']);

Route::get('/order-success/{id}', [OrderController::class, 'success'])->middleware('nocache')->name('order.success');
Route::get('/order-fail', [OrderController::class, 'fail'])->name('order.fail');

Route::get('/search-products', [ProductController::class, 'searchProducts']);
Route::get('/brands', [BrandController::class, 'index'])->name('brands.list');
Route::get('/brands/{slug}', [BrandController::class, 'show'])->name('brands.show');
Route::get('/builder/models', [BuildPcController::class, 'getModels']);


Route::group(['middleware' => ['auth:frontend','nocache']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('account', [ProfileController::class, 'getUserAccountInfo'])->name('account');
    Route::post('/account/update', [ProfileController::class, 'update'])->name('account.update'); 

    Route::get('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('account.changePassword');

    Route::get('my-address', [ProfileController::class, 'getUserAddressInfo'])->name('my-address');
    Route::post('save-address', [ProfileController::class, 'saveAddress'])->name('save-address');
    Route::post('/address/delete', [ProfileController::class, 'deleteAddress'])->name('delete-address');
    Route::get('edit-address/{id}', [ProfileController::class, 'editAddress'])->name('edit-address');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/check', [WishlistController::class, 'check'])->name('wishlist.check');

    Route::get('/notifications', [NotificationController::class, 'customerNotifications'])
        ->name('customer.notifications');
    
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'myOrderSingle'])->name('orders.show');
    Route::post('/my-orders/{order_id}/cancel', [CheckoutController::class, 'cancelOrderRequest'])
        ->name('orders.cancel');
    Route::get('invoice/{order_id}', [OrderController::class, 'invoice_download'])->name('order-invoice.download');

    Route::post('/my-orders/{id}/return', [CheckoutController::class, 'returnOrderRequest'])
        ->name('orders.return');

    Route::post('/notification/read/{id}', [NotificationController::class, 'markRead'])
    ->name('notification.read');

    Route::post('/review/store', [ReviewController::class, 'store'])->name('reviews.save');
});

Route::fallback(function () {
    return response()->view('frontend.errors.404', [], 404);
});
