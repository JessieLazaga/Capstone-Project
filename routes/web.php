<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserRegController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\RolesController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'prevent-back-history'],function(){

Route::redirect('/', destination:'login');
Route::middleware(['auth', 'role:Manufacturer'])->group(function(){
    Route::get('/manufacturer/dashboard', [ManufacturerController::class, 'index'])->name('manufacturer.dashboard');
    Route::post('/manufacturer/deliver', [ManufacturerController::class, 'delivered'])->name('procure.deliver');
});
Route::middleware(['auth', 'can:manage products','can:view products'])->group(function () {
    Route::get('/products/delete/{id}', [ProductController::class, 'DeleteProduct']);
    Route::post('/products/chart/{id}', [ProductController::class, 'chart']);   
    Route::post('/chart/history', [ProductController::class, 'click'])->name('chartDate');
    Route::get('/chart/clicked', [ProductController::class, 'clack'])->name('Date');
});
Route::middleware(['auth', 'can:manage users'])->group(function () {
    Route::get('/registration', [UserController::class, 'create'])->name('regist');
    Route::get('/registration/manufacturer', [UserController::class, 'createMnf'])->name('registMnf');
    Route::post('/registration/manufacturer/submit', [UserController::class, 'storeMnf']);
    Route::post('/registration/submit', [UserController::class, 'store']);
    Route::post('/roles/checked', [RolesController::class, 'check'])->name('checkBox');
    Route::post('/roles/unchecked', [RolesController::class, 'uncheck'])->name('uncheckBox');
    Route::post('/assign/checked', [RolesController::class, 'checkUser'])->name('checkBox');
    Route::post('/assign/unchecked', [RolesController::class, 'uncheckUser'])->name('uncheckBox');
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::get('/roles/assign', [RolesController::class, 'indexUser'])->name('user.index');
    Route::get('/roles/register', [RolesController::class, 'register'])->name('roles.register');
    Route::post('/roles/register/submit', [RolesController::class, 'store'])->name('roles.store');
});
Route::get('/user/verify/{token}', [UserController::class, 'verifyUser'])->name('user.verify');
Route::middleware(['auth', 'can:view transactions'])->group(function () {
    Route::get('/orders/download', [OrderController::class, 'displayReport'])->name('orders.download');
    Route::get('/view/{id}', [OrderController::class, 'view']);
});
Route::middleware(['auth', 'can:manage stocks'])->group(function () {
    Route::get('/stocks', [ProductController::class, 'showStocks'])->name('stocks');
    Route::post('/check-batch-number', [ProductController::class, 'checkBatchNumber']);
    Route::get('/batch', [ProductController::class, 'batchIndex'])->name('batch.index');
    Route::post('/confirm-delivery', [ProductController::class, 'confirmDelivery']);
    Route::get('/products/order/{id}', [ProductController::class, 'orderPanel'])->name('orderpanel');
    Route::post('/products/order/place', [ProductController::class, 'placeOrder'])->name('orderPlace');
    Route::get('/procurement/index', [ProductController::class, 'procureIndex'])->name('procurement.index');
    Route::get('/procurement/submit/{id}', [ProductController::class, 'receivedOrder'])->name('procurement.submit');
    Route::post('/procure/submit', [ProductController::class, 'submitOrder'])->name('procure.submit');
    Route::get('/expiry', [ProductController::class, 'expiry'])->name('expiry');
    Route::get('/set-batch/{id}', [ProductController::class, 'setBatch'])->name('products.setBatch');
    Route::post('/update-batch', [ProductController::class, 'updateBatch'])->name('products.updateBatch');
    Route::get('/batch/delete/{id}', [ProductController::class, 'deleteBatch'])->name('pullout');
});
Route::middleware(['auth', 'can:use POS'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);
    Route::post('/cart/void', [CartController::class, 'voidOrder']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/web/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/logout', [ProfileController::class, 'Logout'])->name('user.logout');
    Route::get('/user/password', [ProfileController::class, 'changePass'])->name('change.password');
    Route::post('/password/update', [ProfileController::class, 'updatePass'])->name('password.update');
    Route::get('/user/profile', [ProfileController::class, 'userProfile'])->name('user.profile');
    Route::post('/profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::resource('/logs', LogsController::class);
    Route::resource('/orders', OrderController::class);
    Route::resource('products', ProductController::class);
   //Sessions 
    Route::get('/sessions', [SessionsController::class, 'index'])->name('sessions.index');
});

});
    //Route::get('/email/verify', function () {
    //return view('auth.verify-email');
    //})->middleware('auth')->name('verification.notice');
    //Route::middleware('admin:admin')->group(function () {
    //Route::get('/admin/login', [AdminController::class, 'loginForm']);
    //Route::post('/admin/login', [AdminController::class, 'store'])->name('admin.login');
    //});

    //Route::get('/qr/generate/{id}', [ProductController::class, 'generateQr'])->name('generateqr')->middleware('auth:admin');
    //Route::get('/qr', [ProductController::class, 'showQr'])->name('showqr');
    //Route::get('/accounts', [AccountsController::class, 'RegView'])->name('account');
    //Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //Route::get('/admin/logout', [AdminController::class, 'Logout'])->name('admin.logout');
    //Route::get('/admin/password', [AdminProfileController::class, 'changePass'])->name('adminchange.password');
    //Route::post('/admin/password/update', [AdminProfileController::class, 'updatePass'])->name('adminpassword.update');
    //Route::get('/admin/profile', [AdminProfileController::class, 'userProfile'])->name('admin.profile');
    //Route::post('/admin/profile/update', [AdminProfileController::class, 'profileUpdate'])->name('adminprofile.update');





