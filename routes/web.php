<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use Illuminate\Http\Request;

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

Route::get('/', function () {
    return view('welcome');
});




Route::group(['prefix'=>'admin'],function(){


    Route::group(['middleware'=>'admin.guest'],function(){

        Route::get('/login',[AdminController::class,'index'])->name('admin.login');
        Route::post('/store',[AdminController::class,'store'])->name('admin.store');

    });

    Route::group(['middleware'=>'admin.auth'],function(){

        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');

       ///////////////Category Routes///////////////////

        Route::get('/categories/index',[CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::POST('/categories/store',[CategoryController::class,'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}/update',[CategoryController::class,'update'])->name('categories.update');
        Route::get('/categories/{category}/delete',[CategoryController::class,'destroy'])->name('categories.delete');

        //////////// SubCategory Routes ////////////

        Route::get('/sub-categories/index',[SubCategoryController::class,'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::POST('/sub-categories/store',[SubCategoryController::class,'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}/update',[SubCategoryController::class,'update'])->name('sub-categories.update');
        Route::get('/sub-categories/{subCategory}/delete',[SubCategoryController::class,'destroy'])->name('sub-categories.delete');

        ////////////  Brands Routes /////////////

        Route::get('/brands/index',[BrandController::class,'index'])->name('brands.index');
        Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
        Route::POST('/brands/store',[BrandController::class,'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');
        Route::put('/brands/{brand}/update',[BrandController::class,'update'])->name('brands.update');
        Route::get('/brands/{brand}/delete',[BrandController::class,'destroy'])->name('brands.delete');

        //////////////  Products Routes  /////////////

        Route::get('/products/index',[ProductController::class,'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::POST('/products/store',[ProductController::class,'store'])->name('products.store');


        /////////////////  Products Sub-Categories Route   ////////////////

        Route::get('/product-subcategories',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');
 
        //////////////////////////////////////////////////////////////////////

       ///// temp-images.create /////
       Route::POST('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');


        Route::get('getSlug',function(Request $request){
                $slug ='';
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);

        })->name('getSlug');


    });


});