<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['web'])->group(function () {
    // Product routes
    Route::get('/product/show',[productController::class, 'show'])->name('api.product.show');
    Route::post('/product/create', [productController::class, 'create'])->name('api.product.create');
    Route::post('/product/delete/{productId}',[productController::class, 'delete'])->name('api.product.delete');
    Route::post('/product/edit/{productId}',[productController::class,'edit'])->name('api.product.edit');
    Route::post('/product/update/{productId}',[productController::class,'update'])->name('api.product.update');
    Route::post('/product/cart/{productId}',[productController::class,'addToCart'])->name('api.product.cart');
    Route::get('/product/cart/show',[productController::class,'showCartProduct'])->name('api.product.cart.show');
    Route::post('/product/cart/remove/{cartId}',[productController::class,'removeCartProduct'])->name('api.product.cart.remove');
    Route::post('/product/cart/operation/{productId}',[productController::class,'cartOperation'])->name('api.product.cart.operation');

    // Login route 
    Route::post('/login' , [AuthenticatedSessionController::class , 'store'])->name('api.login');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.logout');
});
?>