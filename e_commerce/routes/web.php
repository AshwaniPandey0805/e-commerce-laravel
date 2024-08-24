<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TemImageController;
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

Route::get('/', function () {
    return view('welcome');
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
        Route::get('/brand/list',[BrandController::class, 'index'])->name('brand.index');
        Route::get('/brand/create',[BrandController::class, 'create'])->name('brand.create');
        Route::post('/brand/store',[BrandController::class, 'store' ])->name('brand.store');
        Route::any('/brand/{id}/edit',[BrandController::class, 'edit' ])->name('brand.edit');
        Route::put('/brand/{id}/update',[BrandController::class, 'update' ])->name('brand.update');
        Route::any('/brand/{id}/delete',[BrandController::class, 'destory' ])->name('brand.delete');
        
    });
    

});