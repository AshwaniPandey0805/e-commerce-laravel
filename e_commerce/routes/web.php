<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DiscountCouponController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TemImageController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\wishListController;
use App\Models\TempImage;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test-mail', function(){
//     sendOrderEmail(17);
// });

Route::get('/',[FrontController::class,'index'])->name('front.index');
Route::get('/shop/{categorySlug?}/{subcategorySlug?}', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('shop.product');
Route::get('/cart',[CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/update-delete',[CartController::class, 'deleteCart'])->name('front.deleteCart');
Route::get('/checkout',[CartController::class, 'checkout'])->name('front.checkout');
Route::post('/checkout-process',[CartController::class, 'checkoutProcess'])->name('front.checkoutProcess');
Route::get('/shipping-charge/calculate', [CartController::class, 'calculateShippingCharge'])->name('front.calculateShippingCharge');
Route::post('/apply-coupon', [CartController::class, 'applyDiscountCoupon'])->name('front.applyDiscountCoupun');
Route::get('/remove-coupon', [CartController::class, 'removeDiscountCoupon'])->name('front.removeDiscountCoupun');
Route::post('/wish-list/store', [wishListController::class, 'store'])->name('account.wishlist.store');



Route::get('/thank/{id}',[CartController::class, 'thankYou'])->name('front.thankYou');





Route::group(['prefix' => 'account'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('/login', [AuthController::class, 'login'])->name('front.login');
        Route::get('/register', [AuthController::class, 'register'])->name('front.register');
        Route::post('/register/user', [AuthController::class, 'registerProcess'])->name('front.registerProcess');
        Route::post('/login', [AuthController::class, 'authentication'])->name('front.authentication');
    });
    Route::group(['middleware' => 'auth'], function(){
            Route::get('/profile',[AuthController::class, 'profile'])->name('account.profile');
            Route::post('/profile/{id}/update',[AuthController::class, 'updateUserProfile'])->name('account.profile.update');
            Route::post('/address/{id}/update',[AuthController::class, 'updateUserAddress'])->name('account.address.update');
            Route::get('/order',[AuthController::class, 'order'])->name('account.order');
            Route::get('/order-detail/{id}',[AuthController::class, 'orderDetail'])->name('account.orderDetail');
            
            Route::get('/wish-list/list', [wishListController::class, 'index'])->name('account.wishlist.index');
            Route::post('/wish-list/delete', [wishListController::class, 'delete'])->name('account.wishlist.delete');
            Route::get('/logout',[AuthController::class, 'logout'])->name('account.logout');
    });
});

Route::group(['prefix' => 'admin'], function(){

    Route::group(['middleware' => 'admin.guest'], function(){
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function(){
        Route::get('/category/list', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Create Category
        Route::get('/category/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::any('/category/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.delete');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::any('/category/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('categories.update');
        
        // uploade Temp image
        Route::post('/category/image-upload', [TemImageController::class, 'create'])->name('categories.image.upload');

        Route::get('/getSlug', function(Request $request) {
            $slug = '';
            if (!empty($request->query('title'))) {
                $slug = Str::slug($request->query('title'));
            }

            return response()->json([
                'slug' => $slug
            ]);
        })->name('get.slug');

        // Create Sub-Category Routes
        Route::get('/sub-category/list',        [SubCategoryController::class, 'index'  ])->name('sub-category.index');
        Route::get('/sub-category/create',      [SubCategoryController::class, 'create' ])->name('sub-category.create');
        Route::post('/sub-category/create',     [SubCategoryController::class, 'store'  ])->name('sub-category.store');
        Route::any('/sub-category/{id}/edit',   [SubCategoryController::class, 'edit'   ])->name('sub-category.edit');
        Route::put('/sub-category/{id}/update', [SubCategoryController::class, 'update' ])->name('sub-category.update');
        Route::any('/sub-category/{id}/delete', [SubCategoryController::class, 'destroy'])->name('sub-category.delete');


        // Create Band Route
        Route::get('/brand/list',        [BrandController::class, 'index'   ])->name('brand.index');
        Route::get('/brand/create',      [BrandController::class, 'create'  ])->name('brand.create');
        Route::post('/brand/store',      [BrandController::class, 'store'   ])->name('brand.store');
        Route::any('/brand/{id}/edit',   [BrandController::class, 'edit'    ])->name('brand.edit');
        Route::put('/brand/{id}/update', [BrandController::class, 'update'  ])->name('brand.update');
        Route::any('/brand/{id}/delete', [BrandController::class, 'destory' ])->name('brand.delete');


        //Create Product Routes
        Route::get('/product/list', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/create',[ProductController::class,'create'])->name('product.create');
        Route::post('/product/store',[ProductController::class,'store'])->name('product.store');
        Route::any('/product/{id}/edit',[ProductController::class,'edit'])->name('product.edit');
        Route::put('/product/{id}/update',[ProductController::class,'update'])->name('product.update');
        Route::any('/product/{id}/delete',[ProductController::class,'delete'])->name('product.delete');
        Route::get('/product/related-product', [ProductController::class, 'getProducts'])->name('product.getProducts');

        //Shipping Routes
        Route::get('/shipping/create', [ShippingController::class,'create'])->name('shipping.create');
        Route::post('/shipping/store', [ShippingController::class,'store'])->name('shipping.store');
        Route::get('/shipping/{id}/edit', [ShippingController::class,'edit'])->name('shipping.edit');
        Route::put('/shipping/{id}/update', [ShippingController::class,'update'])->name('shipping.update');
        Route::any('/shipping/{id}/delete', [ShippingController::class,'delete'])->name('shipping.delete');
        
        // Discount Coupon Routes
        Route::get('/coupon/list', [DiscountCouponController::class, 'index'])->name('coupon.index');
        Route::get('/coupon/create', [DiscountCouponController::class, 'create'])->name('coupon.create');
        Route::post('/coupon/store', [DiscountCouponController::class, 'store'])->name('coupon.store');
        Route::get('/coupon/{id}/edit', [DiscountCouponController::class, 'edit'])->name('coupon.edit');
        Route::post('/coupon/{id}/update', [DiscountCouponController::class, 'update'])->name('coupon.update');
        Route::any('/coupon/{id}/delete', [DiscountCouponController::class, 'delete'])->name('coupon.delete');

        // Order Routes
        Route::get('/order/list',[OrderController::class, 'index'])->name('order.index');
        Route::get('/order/{id}/detail',[OrderController::class, 'detail'])->name('order.detail');
        Route::post('/order/{id}/update',[OrderController::class, 'update'])->name('order.update');
        Route::post('/order/{id}/send-invoice-mail',[OrderController::class, 'sendInvoiceMail'])->name('order.sendInvoiceMail');

        // Users Routes
        Route::get('/user/list',[UserController::class, 'index'])->name('user.index');
        Route::get('/user/create',[UserController::class, 'create'])->name('user.create');
        Route::post('/user/store',[UserController::class, 'store'])->name('user.store');
        Route::get('/user/{id}/edit',[UserController::class, 'edit'])->name('user.edit');
        Route::post('/user/{id}/update-detail',[UserController::class, 'userDetailUpdate'])->name('user.update');
        Route::post('/user/{id}/update-address',[UserController::class, 'userAddressDetailUpdate'])->name('user.address.update');
        Route::any('/user/{id}/delete',[UserController::class, 'delete'])->name('user.delete');


        // Page Routes
        Route::get('/page/list', [PageController::class, 'index'])->name('page.index');
        Route::get('/page/create', [PageController::class, 'create'])->name('page.create');
        Route::post('/page/store', [PageController::class, 'store'])->name('page.store');
        Route::get('/page/{id}/edit', [PageController::class, 'edit'])->name('page.edit');
        Route::post('/page/{id}/update', [PageController::class, 'update'])->name('page.update');
        Route::any('/page/{id}/delete', [PageController::class, 'delete'])->name('page.delete');

        // Get Sub Category data
        Route::post('/product/sub-category',[ProductSubCategoryController::class, 'getSubCategory'])->name('product.SubCategory');
        //Product Image Update
        Route::post('product-image/update', [ProductImageController::class, 'update'])->name('product.image.update');
        // Delete Image
        Route::delete('/product-image/delete',[ProductImageController::class, 'destroy'])->name('product.image.delete');
    });
    

});