<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TemImageController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\ShopController;
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
Route::get('/',[FrontController::class,'index'])->name('front.index');
Route::get('/shop/{categorySlug?}/{subcategorySlug?}', [ShopController::class, 'index'])->name('shop.index');




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
        

        // Get Sub Category data
        Route::post('/product/sub-category',[ProductSubCategoryController::class, 'getSubCategory'])->name('product.SubCategory');
        //Product Image Update
        Route::post('product-image/update', [ProductImageController::class, 'update'])->name('product.image.update');
        // Delete Image
        Route::delete('/product-image/delete',[ProductImageController::class, 'destroy'])->name('product.image.delete');
    });
    

});