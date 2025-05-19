<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// guest

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.store');
Route::patch('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
Route::patch('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
Route::delete('/cart/clear', [CartController::class, 'clear_cart'])->name('cart.empty');












// User
Route::middleware(['auth'])->group(function () {

    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});



//Admin
Route::middleware(['auth', AuthAdmin::class])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // brand route
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brands/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brands/{id}', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brands/{id}/delete', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');

    // categories route with resources
    Route::resource('/admin/categories', CategoryController::class)->names('admin.categories');

    // products route with resources
    Route::resource('/admin/products', ProductController::class)->names('admin.products');
});
