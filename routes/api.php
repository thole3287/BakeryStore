<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductsTypeController;
use App\Http\Controllers\SlideController;
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
Route::get("related-products/{id}", [PageController::class, "relatedProducts"]);
Route::get("new-products", [PageController::class, "newProducts"]);
Route::get("selling-products", [PageController::class, "sellingProducts"]);


// Route::middleware('auth:api')->group(function () {
//     Route::post('/cancel-order-item', [CartController::class, 'cancelOrderItem'])->middleware('api_except_csrf');
// });

// Route::delete('order-items/{id}', [CartController::class, 'cancelOrderItem']);


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

//cart
// Route::middleware('cart')->group(function () {
//     Route::get("add-to-cart/{id}",[CartController::class, "addToCart"]);
// });
// Route::get('/add-to-cart/{id}', function () {
//     Route::get("add-to-cart/{id}",[CartController::class, "addToCart"]);
// })->middleware('cart');
Route::get("add-to-cart", [CartController::class, 'showCart']);
Route::get("add-to-cart/{id}",[CartController::class, "addToCart"])->middleware('web');
Route::get('detete-item-cart/{id}', [CartController::class, 'deleteItemCart']);
Route::get('detete-item-all-cart/{id}', [CartController::class, 'deleteItemAllCart']);
Route::get('save-item-list-cart/{id}', [CartController::class, 'saveListItemCart']);
// Route::get('clear-cart', [CartController::class, 'clearCart']);
Route::get('order-list', [CartController::class, 'orderList']);


//order item
Route::post('order-items', [CartController::class, 'orderItems']);

//search
Route::get("search",[PageController::class, "getSearch"]);

//slide
Route::get('slide', [SlideController::class, 'index']);
Route::get('slide/{id}', [SlideController::class, 'show']); //slide detail
Route::post('slide', [SlideController::class, 'store']);
Route::put('slide/{id}',[SlideController::class, 'update']);
Route::delete('slide/{id}', [SlideController::class, 'destroy']);

// Route::middleware('auth:api')->group(function () {
//     Route::post('orders/{orderId}/items/{itemId}/cancel', [CartController::class, 'cancelOrderItem']);
// });

Route::get('order-update/{id}', [CartController::class, 'show']);
Route::delete('/orders/{id}/items/{itemId}', [CartController::class, 'cancelOrderItem']);

// Route::put('cancel-order/{id}', [CartController::class, 'cancelOrder']);

// Route::put('/bills/{id}/cancel', [CartController::class, 'cancelOrder']);


// Route::delete('/orders/{id}/items/{itemId}', 'OrderController@cancelOrderItem');



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
    Route::post('/login-admin', [AdminController::class, 'identifyUser']);
    Route::get('/user-profile-admin', [AdminController::class, 'userProfile']);
    Route::post('send-password-reset-link', [PasswordResetRequestController::class, 'sendEmail']);
    // Route::post('reset-password', [ChangePasswordController::class, 'passwordResetProcess']);
    Route::post('/reset-password', [ChangePasswordController::class, 'passwordResetProcess'])->name('password.update');



    Route::put('/users/{id}', [AuthController::class, 'update']);
});
Route::post('cart/{id}', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'showCart']);


Route::delete('order-items/{id}', [CartController::class, 'cancelOrderItem']);
Route::delete('delete-bill/{id}', [CartController::class,'deleteBill']);//ok


Route::group([
    'middleware' => 'api',
    'prefix' => 'order'
], function ($router) {
    Route::get('/order-list',[CartController::class, 'orderList']);
    Route::delete('/remove-bill/{id}', [CartController::class, 'removeOrderList']);
    // Route::delete('order-items/{id}', [CartController::class, 'cancelOrderItem']);


});
// Route::get('/order-list',[CartController::class, 'orderList']);
// Route::group(['middleware' => 'JwtMiddleware'], function () {
//     Route::post('/login-admin', [AdminController::class, 'login']);
// });
// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'jwt'
// ], function ($router) {
//     Route::get('/user-profile', [AdminController::class, 'userProfile']);
//     Route::post('/login-admin', [AdminController::class, 'identifyUser']);
// });

// Route::get('/user-profile', [AdminController::class, 'userProfile']);
// Route::post('/login-admin', [AdminController::class, 'identifyUser']);

// Route::middleware(['jwt'])->group(function () {
//     // Route::post('/login-admin', [AdminController::class, 'login']);
//     Route::post('/login-admin', [AdminController::class, 'identifyUser']);

// });
// Route::middleware(['auth', 'jwt'])->group(function () {
//     Route::get('/jwt', function () {
//         return response()->json(['message' => 'Welcome, admin!']);
//     });
//     Route::post('/login-admin', [AdminController::class, 'login']);

    
//     // other admin-only routes here...
// });