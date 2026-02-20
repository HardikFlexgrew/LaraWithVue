<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Middleware\PermissionMiddleware;

Route::middleware(['web'])->group(function () {

    Route::post('/login' , [AuthenticatedSessionController::class , 'store'])->name('api.login');   
    Route::post('/register' , [RegisteredUserController::class , 'store'])->name('api.register');   
    Route::get('/register/get_country' , [RegisteredUserController::class , 'getCountry']);   
    Route::get('/product/show',[productController::class, 'show'])->name('api.product.show');
   
    Route::post('/product/delete/{productId}',[productController::class, 'delete'])->name('api.product.delete');
    Route::post('/product/edit/{productId}',[productController::class,'edit'])->name('api.product.edit');
    Route::post('/product/update/{productId}',[productController::class,'update'])->name('api.product.update');
    
    Route::post('/product/cart/{productId}',[productController::class,'addToCart'])->name('api.product.cart');
    Route::get('/product/cart/show',[productController::class,'showCartProduct'])->name('api.product.cart.show');
    Route::post('/product/cart/remove/{cartId}',[productController::class,'removeCartProduct'])->name('api.product.cart.remove');
    Route::post('/product/cart/operation/{productId}',[productController::class,'cartOperation'])->name('api.product.cart.operation');
    
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/user', [AuthenticatedSessionController::class, 'user'])->name('api.user');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.logout');
        Route::post('/create-checkout-session', [CheckoutController::class, 'createSession'])->name('api.checkout.create');
        Route::get('/set-status-cart-item/{sessionId}',[CheckoutController::class, 'setStatus'])->name('api.checkout.set-status');
    });

    Route::post('/product/create', [productController::class, 'create'])
        ->middleware('permission:add-product')
        ->name('api.product.create');

    // Create Stripe Checkout Session
});
?>