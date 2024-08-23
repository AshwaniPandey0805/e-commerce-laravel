<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
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
        Route::post('/category/store', [CategoryController::class, 'store'])->name('categories.store');
        
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

        
    });
    

});