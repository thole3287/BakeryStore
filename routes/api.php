<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DescriptionOfAboutController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FAQsController;
use App\Http\Controllers\ForteController;
use App\Http\Controllers\HirringAboutController;
use App\Http\Controllers\NoNastiesController;
use App\Http\Controllers\OurKitchenController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\PositionAboutController;
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


//FAQs
Route::get('faq', [FAQsController::class, 'index']);
Route::get('faq/{id}', [FAQsController::class, 'show']);//detail
// Route::post('faq', [FAQsController::class, 'store']);
// Route::delete('faq/{id}', [FAQsController::class, 'destroy']);
// Route::put('faq/{id}', [FAQsController::class, 'update']);

Route::group(['middleware' => 'jwtManager'], function () {
    Route::post('faq', [FAQsController::class, 'store']);
    Route::delete('faq/{id}', [FAQsController::class, 'destroy']);
    Route::put('faq/{id}', [FAQsController::class, 'update']);
});

//no nasties
Route::get('get-three-nasties', [NoNastiesController::class, 'index3InFor']);
Route::get('nasties', [NoNastiesController::class, 'index']);
Route::get('nasties/{id}', [NoNastiesController::class, 'show']);//detail
// Route::post('nasties', [NoNastiesController::class, 'store']);
// Route::delete('nasties/{id}', [NoNastiesController::class, 'destroy']);
// Route::put('nasties/{id}', [NoNastiesController::class, 'update']);

Route::group(['middleware' => 'jwtManager'], function () {
    Route::post('nasties', [NoNastiesController::class, 'store']);
    Route::delete('nasties/{id}', [NoNastiesController::class, 'destroy']);
    Route::put('nasties/{id}', [NoNastiesController::class, 'update']);
});

//branch
Route::get('branch', [BranchController::class, 'index']);
Route::get('branch/{id}', [BranchController::class, 'show']);//detail
// Route::post('branch', [BranchController::class, 'store']);
// Route::delete('branch/{id}', [BranchController::class, 'destroy']);
// Route::put('branch/{id}', [BranchController::class, 'update']);

Route::group(['middleware' => 'jwtManager'], function () {
    Route::post('branch', [BranchController::class, 'store']);
    Route::delete('branch/{id}', [BranchController::class, 'destroy']);
    Route::put('branch/{id}', [BranchController::class, 'update']);
});

// Route::middleware('auth:api')->group(function () {
//     Route::post('/cancel-order-item', [CartController::class, 'cancelOrderItem'])->middleware('api_except_csrf');
// });

// Route::delete('order-items/{id}', [CartController::class, 'cancelOrderItem']);


//products
Route::get('products', [ProductsController::class, 'Index']);
Route::get('products/{id}', [ProductsController::class, 'show']); //product-detail
// Route::post('products', [ProductsController::class, 'store']);
// Route::put('products/{id}', [ProductsController::class, 'update']);
// Route::delete('products/{id}', [ProductsController::class, 'destroy']);

Route::group(['middleware' => 'jwtManager'], function () {
    Route::post('products', [ProductsController::class, 'store']);
    Route::put('products/{id}', [ProductsController::class, 'update']);
    Route::delete('products/{id}', [ProductsController::class, 'destroy']);
});

//type product
// Route::get("products-type", [ProductsTypeController::class, 'index']);
// Route::get("products-type/{type_id}",[ProductsTypeController::class, "show"]); //product type -detail
// Route::post("products-type", [ProductsTypeController::class, 'store']);
// Route::put('products-type/{id}', [ProductsTypeController::class, 'update']);
// Route::delete('products-type/{id}', [ProductsTypeController::class, 'destroy']);

// Route::group(['middleware' => 'jwtStaff'], function () {
//     Route::get("products-type", [ProductsTypeController::class, 'index']);
//     Route::get("products-type/{type_id}",[ProductsTypeController::class, "show"]); //product type -detail
// });
Route::get("products-type", [ProductsTypeController::class, 'index']);
Route::get("products-type/{id}",[ProductsTypeController::class, "show"]); //product type -de
Route::get("get-all-products-in-category/{id}",[ProductsTypeController::class, "getAllProductInCategory"]); //product type -de
Route::group(['middleware' => 'jwtManager'], function () {
    // Route::get("products-type", [ProductsTypeController::class, 'index']);
    // Route::get("products-type/{type_id}",[ProductsTypeController::class, "show"]); //product type -detail
    Route::post("products-type", [ProductsTypeController::class, 'store']);
    Route::put('products-type/{id}', [ProductsTypeController::class, 'update']);
    Route::delete('products-type/{id}', [ProductsTypeController::class, 'destroy']);
});

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
// // Route::post('slide', [SlideController::class, 'store']);
// Route::put('slide/{id}',[SlideController::class, 'update']);
// Route::delete('slide/{id}', [SlideController::class, 'destroy']);

Route::group(['middleware' => 'jwtManager'], function () {
    // Route::get('slide', [SlideController::class, 'index']);
    // Route::get('slide/{id}', [SlideController::class, 'show']); //slide detail
    Route::post('slide', [SlideController::class, 'store']);
    Route::put('slide/{id}',[SlideController::class, 'update']);
    Route::delete('slide/{id}', [SlideController::class, 'destroy']);
});

//kitchen
Route::get('kitchen', [OurKitchenController::class, 'index']);
Route::get('kitchen/{id}', [OurKitchenController::class, 'show']); //kitchen detail
Route::group(['middleware' => 'jwtManager'], function () {
    // Route::get('kitchen', [OurKitchenController::class, 'index']);
    // Route::get('kitchen/{id}', [OurKitchenController::class, 'show']); //kitchen detail
    Route::post('kitchen', [OurKitchenController::class, 'store']);
    Route::put('kitchen/{id}',[OurKitchenController::class, 'update']);
    Route::delete('kitchen/{id}', [OurKitchenController::class, 'destroy']);
});



//avaible position
Route::get('position', [PositionAboutController::class, 'index']);
Route::get('position/{id}', [PositionAboutController::class, 'show']); //position detail
Route::group(['middleware' => 'jwtManager'], function () {
    // Route::get('position', [PositionAboutController::class, 'index']);
    // Route::get('position/{id}', [PositionAboutController::class, 'show']); //position detail
    Route::post('position', [PositionAboutController::class, 'store']);
    Route::put('position/{id}',[PositionAboutController::class, 'update']);
    Route::delete('position/{id}', [PositionAboutController::class, 'destroy']);
});

/// we are hirring
Route::get('hirring', [HirringAboutController::class, 'index']);
Route::get('hirring/{id}', [HirringAboutController::class, 'show']); //hirring detail
Route::group(['middleware' => 'jwtManager'], function () {
    // Route::get('hirring', [HirringAboutController::class, 'index']);
    // Route::get('hirring/{id}', [HirringAboutController::class, 'show']); //hirring detail
    Route::post('hirring', [HirringAboutController::class, 'store']);
    Route::put('hirring/{id}',[HirringAboutController::class, 'update']);
    Route::delete('hirring/{id}', [HirringAboutController::class, 'destroy']);
});

/// we are forte
Route::get('forte', [ForteController::class, 'index']);
Route::get('forte/{id}', [ForteController::class, 'show']); //forte detail
Route::get('get-three-forte', [ForteController::class, 'get3InFor']);
Route::group(['middleware' => 'jwtManager'], function () {
    // Route::get('forte', [ForteController::class, 'index']);
    // Route::get('forte/{id}', [ForteController::class, 'show']); //forte detail
    Route::post('forte', [ForteController::class, 'store']);
    Route::put('forte/{id}',[ForteController::class, 'update']);
    Route::delete('forte/{id}', [ForteController::class, 'destroy']);
});

/// we are about-baker
Route::get('about-baker', [DescriptionOfAboutController::class, 'index']);
Route::get('about-baker/{id}', [DescriptionOfAboutController::class, 'show']); //about-baker detail
Route::group(['middleware' => 'jwtManager'], function () {
    // Route::get('about-baker', [DescriptionOfAboutController::class, 'index']);
    // Route::get('about-baker/{id}', [DescriptionOfAboutController::class, 'show']); //about-baker detail
    Route::post('about-baker', [DescriptionOfAboutController::class, 'store']);
    Route::put('about-baker/{id}',[DescriptionOfAboutController::class, 'update']);
    Route::delete('about-baker/{id}', [DescriptionOfAboutController::class, 'destroy']);
});



// Route::middleware('auth:api')->group(function () {
//     Route::post('orders/{orderId}/items/{itemId}/cancel', [CartController::class, 'cancelOrderItem']);
// });

Route::get('order-update/{id}', [CartController::class, 'show']);
Route::delete('/orders/{id}/items/{itemId}', [CartController::class, 'cancelOrderItem']);

// Route::put('cancel-order/{id}', [CartController::class, 'cancelOrder']);

// Route::put('/bills/{id}/cancel', [CartController::class, 'cancelOrder']);


// Route::delete('/orders/{id}/items/{itemId}', 'OrderController@cancelOrderItem');
Route::post('/login-adminid', [AuthController::class, 'loginAdmin']);

Route::group(['middleware' => 'jwtManager'], function () {
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    // Route::post('/employees/calculate-working-time', [EmployeeController::class, 'calculateWorkingTime']);
    Route::post('/employees/working-time', [EmployeeController::class, 'addWorkingTime']);
});


Route::get('about-discription', [DescriptionOfAboutController::class, 'index']);
Route::get('about-discription/{id}', [DescriptionOfAboutController::class, 'show']);
// Route::post('about-discription', [DescriptionOfAboutController::class, 'store']);
// Route::put('about-discription/{id}', [DescriptionOfAboutController::class, 'update']);


Route::get('customer', [CustomerController::class, 'index']);
Route::get('customer/{id}', [CustomerController::class, 'show']);
Route::put('customer/{id}', [CustomerController::class, 'update']);
Route::delete('customer/{id}', [CustomerController::class, 'destroy']);



// Route::get('/employees', [EmployeeController::class, 'index']);
// Route::post('/employees', [EmployeeController::class, 'store']);
// Route::get('/employees/{id}', [EmployeeController::class, 'show']);
// Route::put('/employees/{id}', [EmployeeController::class, 'update']);
// Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
// // Route::post('/employees/calculate-working-time', [EmployeeController::class, 'calculateWorkingTime']);
// Route::post('/employees/working-time', [EmployeeController::class, 'addWorkingTime']);




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