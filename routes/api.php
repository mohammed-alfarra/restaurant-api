<?php

use App\Http\Controllers\API\Dashboard\CategoryController;
use App\Http\Controllers\API\User\AuthController;
use Illuminate\Support\Facades\Route;

/*==========================================
=                user auth                 =
==========================================*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('me', [AuthController::class, 'me']);
/*=====  End of user auth          ======*/

/*==========================================
=                category                 =
==========================================*/
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
});
/*=====  End of category          ======*/
