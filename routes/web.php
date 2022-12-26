<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UsersController;

use App\Rules\MatchOldPassword;
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

Auth::routes(['register'=>false]);
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/income',[OrderController::class, 'incomeChart'])->name('product.order.income');

// Login routes
Route::get('user/login', [FrontendController::class, 'login'])->name('login.form');
Route::post('user/login', [FrontendController::class, 'loginSubmit'])->name('login.submit');


// Register routes
Route::get('user/register', [FrontendController::class, 'register'])->name('register.form');
Route::post('user/register', [FrontendController::class, 'registerSubmit'])->name('register.submit');


// Logout routes
Route::get('user/logout', [FrontendController::class, 'logout'])->name('user.logout');


// Frontend Routes
Route::get('/home', [FrontendController::class, 'index']);
Route::get('/product-grids', [FrontendController::class, 'productGrids'])->name('product-grids');
Route::get('/product-lists', [FrontendController::class, 'productLists'])->name('product-lists');
Route::match(['get','post'],'/filter',[FrontendController::class, 'productFilter'])->name('shop.filter');
Route::get('product-detail/{id}', [FrontendController::class, 'productDetail'])->name('product-detail');
Route::post('/product/search', [FrontendController::class, 'productSearch'])->name('product.search');
Route::get('/product-cat/{id}',[FrontendController::class, 'productCat'])->name('product-cat');
Route::match(['get','post'],'/filter',[FrontendController::class, 'productFilter'])->name('shop.filter');

// Cart routes
Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('user');
Route::post('/add-to-cart', [CartController::class, 'singleAddToCart'])->name('single-add-to-cart')->middleware('user');
Route::get('cart-delete/{id}', [CartController::class, 'cartDelete'])->name('cart-delete');
Route::post('cart-update', [CartController::class, 'cartUpdate'])->name('cart.update');
Route::get('/cart',function(){
    return view('frontend.pages.cart');
})->name('cart');


// Checkout routes
Route::post('cart/order', [OrderController::class, 'store'])->name('cart.order');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('user');


// Backend Routes
Route::group(['prefix'=>'/admin','middleware'=>['auth','admin']],function(){
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    // Product routes
    Route::resource('/product', ProductController::class);
      // user route
      Route::resource('users', UsersController::class);

    // Category routes
    Route::resource('/category', CategoryController::class);

    // Product routes
    Route::resource('/product', ProductController::class);
    
    // Order routes
    Route::resource('/order', OrderController::class);
    
    // Shipping routes
    Route::resource('/shipping', ShippingController::class);

    // Password Change
    Route::get('change-password', [AdminController::class, 'changePassword'])->name('change.password.form');
    Route::post('change-password', [AdminController::class, 'changPasswordStore'])->name('change.password');
}); 

// User Routes
Route::group(['prefix'=>'/user','middleware'=>['user']],function(){
    Route::get('/', [HomeController::class, 'index'])->name('user');

    // Profile routes
    Route::get('/profile', [HomeController::class, 'profile'])->name('user-profile');
    Route::post('/profile/{id}', [HomeController::class, 'profileUpdate'])->name('user-profile-update');
    
    //  Order routes
    Route::get('/order', [HomeController::class, 'orderIndex'])->name('user.order.index');
    Route::get('/order/show/{id}', [HomeController::class, 'orderShow'])->name('user.order.show');
    Route::delete('/order/delete/{id}', [HomeController::class, 'userOrderDelete'])->name('user.order.delete');
    
    // Password Change routes
    Route::get('change-password', [HomeController::class, 'changePassword'])->name('user.change.password.form');
    Route::post('change-password', [HomeController::class, 'changPasswordStore'])->name('change.password');
});
// ordeer
Route::get('/admin/order', [OrderController::class,'index'])->name('Order');
Route::get('/admin/order/show/{id}', [OrderController::class,'show'])->name('admin.show');
Route::get('/admin/order/edit/{id}', [OrderController::class,'edit'])->name('admin.edit');
Route::patch('/admin/order/update/{id}', [OrderController::class,'update'])->name('admin.update');
Route::delete('/admin/order/destroy/{id}', [OrderController::class,'destroy'])->name('admin.destroy');

//  // Profile 
   
    Route::get('/profile',[HomeController::class,'profile'] )->name('user-profile');

    //Update-profile
    Route::post('/profile/{id}',[HomeController::class,'profileUpdate'])->name('user-profile-update');
     
    
    
    // Password Change
     Route::get('change-password', [HomeController::class,'changePassword'])->name('user.change.password.form');
     Route::post('change-password', [HomeController::class, 'changPasswordStore'])->name('change.password');