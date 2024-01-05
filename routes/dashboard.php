<?php

use App\Http\Controllers\API\Dashboard\AuthController;
use App\Http\Controllers\API\Dashboard\CategoryController;
use App\Http\Controllers\API\Dashboard\CouponController;
use App\Http\Controllers\API\Dashboard\DiscountController;
use App\Http\Controllers\API\Dashboard\ItemController;
use Illuminate\Support\Facades\Route;

/*===================================
=            admin login           =
===================================*/
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout']);
Route::post('me', [AuthController::class, 'me']);
/*=====  End of admin login  ======*/

/*==========================================
=                category                 =
==========================================*/
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::post('subcategory', [CategoryController::class, 'storeSubcategory']);
});
/*=====  End of category          ======*/

/*==========================================
=                item                 =
==========================================*/
Route::group(['prefix' => 'items'], function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::post('/', [ItemController::class, 'store']);
});
/*=====  End of item          ======*/

/*==========================================
=                item                 =
==========================================*/
Route::group(['prefix' => 'coupons'], function () {
    Route::post('/', [CouponController::class, 'store']);
});
/*=====  End of item          ======*/

/*==========================================
=                discount                 =
==========================================*/
Route::group(['prefix' => 'discounts'], function () {
    Route::post('apply-category/{category}', [DiscountController::class, 'applyCouponToCategory']);
});
/*=====  End of discount          ======*/
