<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ForgotPasswordController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Frontend\HomeController;

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

Route::get('/', [FrontendController::class, 'home'])->name('home');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/product/{id}', [ProductController::class, 'productDetails'])->name('product.details');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/get-variants-by-value', [ProductController::class, 'getVariantsByValue']);
    Route::get('/getVarientDetails', [ProductController::class, 'getVarientDetails']);
    Route::get('/getVariantState', [ProductController::class, 'getVariantState']);
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/addProductToCart', [CartController::class, 'addProductToCart']);
    Route::get('/removeCartItem/{id}', [CartController::class, 'removeCartItem']);
    Route::get('/getCartSummary', [CartController::class, 'getCartSummary']);
    
});












