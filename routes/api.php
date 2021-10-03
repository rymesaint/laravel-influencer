<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\{
    DashboardController,
    ImageController,
    OrderController,
    PermissionController,
    ProductController as ProductAdmin,
    RoleController,
    UserController
};

use App\Http\Controllers\Influencer\{
    LinkController,
    ProductController as ProductInfluencer,
    StatsController,
};

use App\Http\Controllers\Checkout\{
    LinkController as LinkCheckout,
    OrderController as CheckoutOrderController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('admin')->group(function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::middleware(['auth:api', 'scope:admin'])->group(function() {
        Route::get('user', [AuthController::class, 'user']);
        Route::put('users/info', [AuthController::class, 'updateInfo']);
        Route::put('users/password', [AuthController::class, 'updatePassword']);
        
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('upload', [ImageController::class, 'upload']);
        Route::get('export', [OrderController::class, 'export']);
        Route::get('chart', [DashboardController::class, 'chart']);
        
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('products', ProductAdmin::class);
        Route::apiResource('orders', OrderController::class)->only('index', 'show');
        Route::apiResource('permissions', PermissionController::class)->only('index');
    });
});

Route::group([
    'prefix' => 'influencer',
], function() {
    Route::get('products', [ProductInfluencer::class, 'index']);

    Route::group([
        'middleware' => ['auth:api', 'scope:influencer'],
    ], function() {
        Route::get('user', [AuthController::class, 'user']);
        Route::put('users/info', [AuthController::class, 'updateInfo']);
        Route::put('users/password', [AuthController::class, 'updatePassword']);
        Route::post('links', [LinkController::class, 'store']);
        Route::get('stats', [StatsController::class, 'index']);
        Route::get('rankings', [StatsController::class, 'ranking']);
    });
});

Route::group([
    'prefix' => 'checkout'
], function() {
    Route::get('links/{code}', [LinkCheckout::class, 'show']);
    Route::post('orders', [CheckoutOrderController::class, 'store']);
    Route::post('orders/confirm', [CheckoutOrderController::class, 'confirm']);
});
