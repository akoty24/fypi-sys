<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\BranchController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\CustomerPointController;
use App\Http\Controllers\User\InventoryItemController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\PurchaseOrderController;
use App\Http\Controllers\User\SupplierController;
use App\Http\Controllers\User\TableController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ZoneController;
use Illuminate\Support\Facades\Route;

//auth
Route::prefix('auth')->group(function () {
    //customer
    Route::prefix('customer')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('/login', [\App\Http\Controllers\Customer\AuthController::class, 'login']);
            Route::post('/register', [\App\Http\Controllers\Customer\AuthController::class, 'register']);
        });
        Route::post('/logout', [\App\Http\Controllers\Customer\AuthController::class, 'logout']);
    });

    //user
    Route::prefix('user')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/register', [AuthController::class, 'register']);
            //my profile
        });
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    //admin
    Route::prefix('admin')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
       //     Route::post('/register', [\App\Http\Controllers\Admin\AuthController::class, 'register']);
        });
        Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout']);
    });
});

///////////////////////////////////////
/////store
Route::prefix('store')->middleware(['auth'])->group(function () {
    //store
    Route::get('/', [\App\Http\Controllers\User\StoreController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\User\StoreController::class, 'store']);
    Route::put('/', [\App\Http\Controllers\User\StoreController::class, 'update']);
    Route::delete('/', [\App\Http\Controllers\User\StoreController::class, 'destroy']);

//user
Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']); ////////////////////....
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

//customer  ................error
Route::prefix('customer')->group(function () {

    Route::get('/', [\App\Http\Controllers\User\CustomerController::class, 'index']);//..
    Route::get('/{id}', [\App\Http\Controllers\User\CustomerController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\User\CustomerController::class, 'store']);//...
    Route::put('/{id}', [\App\Http\Controllers\User\CustomerController::class, 'update']);//..
    Route::delete('/{id}', [\App\Http\Controllers\User\CustomerController::class, 'destroy']);
});

//branch
Route::prefix('branch')->group(function () {
    Route::get('/', [BranchController::class, 'index']);
    Route::get('/{id}', [BranchController::class, 'show']);//////////////////هظهر اي فرع ولا تبع اليوزر اللي مسجل
    Route::post('/', [BranchController::class, 'store']);
    Route::put('/{id}', [BranchController::class, 'update']);
    Route::delete('/{id}', [BranchController::class, 'destroy']);
});

//zones
Route::prefix('zone')->group(function () {
    Route::get('/', [ZoneController::class, 'index']);
    Route::get('/{id}', [ZoneController::class, 'show']);
    Route::post('/', [ZoneController::class, 'store']);
    Route::put('/{id}', [ZoneController::class, 'update']);
    Route::delete('/{id}', [ZoneController::class, 'destroy']);
});
//table
Route::prefix('table')->group(function () {
    Route::get('zones/{zoneId}/tables', [TableController::class, 'index']);
    Route::get('zones/{zoneId}/tables/{tableId}', [TableController::class, 'show']);
    Route::post('/', [TableController::class, 'store']);
    Route::put('/{id}', [TableController::class, 'update']);
    Route::delete('zones/{zoneId}/tables/{tableId}', [TableController::class, 'destroy']);
});
////inventory
//Route::prefix('inventory')->group(function () {
//    Route::get('/', [InventoryController::class, 'index']);
//    Route::get('/{id}', [InventoryController::class, 'show']);
//    Route::get('showByBranchID/{id}', [InventoryController::class, 'showByBranchID']);
//    Route::post('/', [InventoryController::class, 'store']);
//    Route::put('/{id}', [InventoryController::class, 'update']);
//    Route::delete('/{id}', [InventoryController::class, 'destroy']);
//});

//inventory item
Route::prefix('inventory_item')->group(function () {

    Route::post('/subscribe_inventory', [InventoryItemController::class, 'subscribe_inventory']);
    Route::get('/', [InventoryItemController::class, 'index']); //show all inventory items in inventory
    Route::get('/{id}', [InventoryItemController::class, 'show']);//show one inventory items in inventory
    Route::get('showByInventoryID/{id}', [InventoryItemController::class, 'showByInventoryID']);
    Route::post('/', [InventoryItemController::class, 'store']);
    Route::put('/{id}', [InventoryItemController::class, 'update']);
    Route::delete('/{id}', [InventoryItemController::class, 'destroy']);
});
//category
Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::get('showByBranchID/{id}', [CategoryController::class, 'showByBranchID']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});
//product
Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('showByBranchID/{id}', [ProductController::class, 'showByBranchID']);
    Route::get('showByCategoryID/{id}', [ProductController::class, 'showByCategoryID']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

//customer point                              //....
Route::prefix('customer_point')->group(function () {
    Route::get('/', [CustomerPointController::class, 'index']);
    Route::get('/{id}', [CustomerPointController::class,'show']);
    Route::post('/', [CustomerPointController::class, 'store']);
    Route::put('/{id}', [CustomerPointController::class, 'update']);
    Route::delete('/{id}', [CustomerPointController::class, 'destroy']);
});
//supplier
Route::prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'index']);
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::post('/', [SupplierController::class, 'store']);
    Route::put('/{id}', [SupplierController::class, 'update']);
    Route::delete('/{id}', [SupplierController::class, 'destroy']);
});

//order
Route::prefix('order')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});
//purchase order
Route::prefix('purchase_order')->group(function () {
    Route::get('/', [PurchaseOrderController::class, 'index']);
    Route::get('/{id}', [PurchaseOrderController::class, 'show']);
    Route::get('showByBranchID/{id}', [PurchaseOrderController::class, 'showByBranchID']);
    Route::post('/', [PurchaseOrderController::class, 'store']);
    Route::put('/{id}', [PurchaseOrderController::class, 'update']);
    Route::delete('/{id}', [PurchaseOrderController::class, 'destroy']);
});
});

///////////////////////////////////
/// admin
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/{id}', [AdminController::class, 'show']);
    Route::post('/', [AdminController::class, 'store']);
    Route::put('/{id}', [AdminController::class, 'update']);
    Route::delete('/{id}', [AdminController::class, 'destroy']);

//customer
    Route::prefix('customer')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CustomerController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Admin\CustomerController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy']);
    });
//user
    Route::prefix('user')->group(function () {

        Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Admin\UserController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy']);
    });
//store
        Route::get('/', [\App\Http\Controllers\Admin\StoreController::class, 'index']);
        Route::get('show/{id}', [\App\Http\Controllers\Admin\StoreController::class, 'show']);
        Route::get('destroy/{id}', [\App\Http\Controllers\Admin\StoreController::class, 'destroy']);
});
