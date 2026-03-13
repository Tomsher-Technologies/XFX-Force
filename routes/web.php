<?php

use App\Http\Controllers\Admin\InvoiceController;

use App\Http\Controllers\Frontend\BuildPcController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ForgotPasswordController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProfileController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [FrontendController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/product/{id}/{stockId?}', [ProductController::class, 'productDetails'])->name('product.details');
    Route::get('/products', [ProductController::class, 'index'])->name('products');

    Route::get('/get-variants-by-value', [ProductController::class, 'getVariantsByValue']);
    Route::get('/getVarientDetails', [ProductController::class, 'getVarientDetails']);
    Route::get('/getVariantState', [ProductController::class, 'getVariantState']);

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/addProductToCart', [CartController::class, 'addProductToCart']);
    Route::get('/removeCartItem/{id}', [CartController::class, 'removeCartItem']);
    Route::get('/getCartSummary', [CartController::class, 'getCartSummary']);
    Route::get('/updateProductWarranty', [CartController::class, 'updateProductWarranty']);

    Route::get('/shop/category/{categoryId}', [ProductController::class, 'shopByCategory'])->name('shop.category');

    Route::get('/buildyourpc', [BuildPcController::class,'index'])->name('buildyourpc');
    Route::get('/buildyourpc/products/{category_id}', [BuildPcController::class, 'getProductsByCategory']);
    Route::get('/buildyourpc/products/details/{stockId}', [BuildPcController::class, 'getProductDetails']);
    Route::get('/buildyourpc/savePcBuilder', [BuildPcController::class, 'savePcBuilder']);
    Route::get('/buildyourpc/getBuildData', [BuildPcController::class, 'getBuildData']);
    Route::get('/buildyourpc/place-order', [BuildPcController::class, 'placePcBuilderOrder'])->name('pcbuilder.place.order');
    Route::post('/pc-builder/reset', [BuildPcController::class, 'resetConfiguration'])->name('pc.builder.reset');
});












