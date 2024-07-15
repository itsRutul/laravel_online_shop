<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\DiscountCouponController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\OrderController;

/* Route::get('/test', function(){
     invoiceEmail(15);

}); */

//front routes
Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('products/{id}', [FrontController::class, 'show'])->name('front.show');
Route::get('/subcategories',[FrontController::class, 'getsubcategories'])->name('subcategories');
Route::get('/shop', [ShopController::class, 'index'])->name('front.shop');

//cart routes
Route::get('/cart', [CartController::class, 'index'])->name('front.cart');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('front.cart.add');
Route::patch('/cart/update', [CartController::class, 'updateCart'])->name('front.cart.update');
Route::delete('/cart/remove', [CartController::class, 'removeFromCart'])->name('front.cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('front.cart.applyCoupon');

// routes/web.php
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('account/register', [AuthController::class, 'handleRegister'])->name('register.handle');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('account/login', [AuthController::class, 'handleLogin'])->name('login.handle');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('front.checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('front.checkout.process');
Route::post('/checkout/payment', [CheckoutController::class, 'payment'])->name('front.checkout.payment');
Route::get('/thankyou', [CheckoutController::class, 'thankyou'])->name('front.thankyou');

//account/profile routes (protected by middleware)
Route::group(['middleware' => 'auth'],function(){
    Route::get('account/profile', [AuthController::class, 'profile'])->name('account.profile');
    Route::post('account/update', [AuthController::class, 'updateprofile'])->name('account.profile.update');
    Route::get('wishlist', [AccountController::class, 'wishlist'])->name('account.wishlist');
    Route::post('wishlist/add', [AccountController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove', [AccountController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('/change-password', [AccountController::class, 'showChangePasswordForm'])->name('account.change-password');
    Route::post('/', [AccountController::class, 'changePassword'])->name('account.change-password.submit');
    Route::get('account/my-orders', [AccountController::class, 'myorders'])->name('account.my-orders');
    Route::get('/order-details/{id}', [AccountController::class, 'orderdetails'])->name('order.details');

});


// Admin Login Route
Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

// Admin Routes (Protected by middleware)
Route::group(['middleware' => 'admin.auth', 'prefix' => 'admin'], function () {

    // Dashboard and logout routes
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

    // Category routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('categories/{id}/deleteImage', [CategoryController::class, 'deleteImage'])->name('categories.deleteImage');


    // SubCategory routes
    Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
    Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
    Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
    Route::get('/sub-categories/{id}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
    Route::put('/sub-categories/{id}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
    Route::delete('/sub-categories/{id}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');
    Route::get('subcategories/{categoryId}', [SubCategoryController::class, 'subcategoriesByCategory'])->name('subcategories.by.category');


    // brands routes
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');


        //product routes

    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::delete('/products/{id}/delete-image', [ProductController::class, 'deleteImage'])->name('products.deleteImage');



    //country routes
    Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
    Route::get('/countries/create', [CountryController::class, 'create'])->name('countries.create');
    Route::post('/countries', [CountryController::class, 'store'])->name('countries.store');
    Route::get('countries/{id}/edit', [CountryController::class, 'edit'])->name('countries.edit');
    Route::put('/countries/{id}', [CountryController::class, 'update'])->name('countries.update');
    Route::delete('/countries/{id}', [CountryController::class, 'destroy'])->name('countries.destroy');

    //shipping routes
    Route::get('/shippings', [ShippingController::class, 'index'])->name('shippings.index');
    Route::get('/shippings/create', [ShippingController::class, 'create'])->name('shippings.create');
    Route::post('/shippings', [ShippingController::class, 'store'])->name('shippings.store');
    Route::get('/shippings/{id}/edit', [ShippingController::class, 'edit'])->name('shippings.edit');
    Route::put('/shippings/{id}', [ShippingController::class, 'update'])->name('shippings.update');
    Route::delete('/shippings/{id}', [ShippingController::class, 'destroy'])->name('shippings.destroy');

    //discount routes
    Route::get('/discount-coupons', [DiscountCouponController::class, 'index'])->name('discount-coupons.index');
    Route::get('/discount-coupons/create', [DiscountCouponController::class, 'create'])->name('discount-coupons.create');
    Route::post('/discount-coupons', [DiscountCouponController::class, 'store'])->name('discount-coupons.store');
    Route::get('/discount-coupons/{id}/edit', [DiscountCouponController::class, 'edit'])->name('discount-coupons.edit');
    Route::put('/discount-coupons/{id}', [DiscountCouponController::class, 'update'])->name('discount-coupons.update');
    Route::delete('/discount-coupons/{id}', [DiscountCouponController::class, 'destroy'])->name('discount-coupons.destroy');

    //order routes

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'details'])->name('orders.details');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');





});




