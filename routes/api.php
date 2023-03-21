<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductsTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Page Controller
// Route::get('products',[PageController::class,'getProductIndex']);
// Route::get("productType/{type_id}",[PageController::class, "getProductByType"]);
// Route::get("product-detail/{id}", [PageController::class, "getProductsDetail"]);

//products
Route::get('products', [ProductsController::class, 'Index']); 
Route::get('products/{id}', [ProductsController::class, 'show']); //product-detail
Route::post('products', [ProductsController::class, 'store']); 
Route::put('products/{id}', [ProductsController::class, 'update']);
Route::delete('products/{id}', [ProductsController::class, 'destroy']);

//type product
Route::get("products-type", [ProductsTypeController::class, 'index']);
Route::get("products-type/{type_id}",[ProductsTypeController::class, "show"]); //product type -detail
Route::post("products-type", [ProductsTypeController::class, 'store']);
Route::put('products-type/{id}', [ProductsTypeController::class, 'update']);
Route::delete('products-type/{id}', [ProductsTypeController::class, 'destroy']);




//search
Route::get("search",[PageController::class, "getSearch"]);

//identify
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});
