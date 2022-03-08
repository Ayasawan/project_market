<?php
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Route as RouteAlias;
 use App\Http\Controllers\CategoryController;
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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//RouteAlias::resource('products',ProductController::class);
//RouteAlias::resource('categories',CategoryController::class);
Route::post('register', [\App\Http\Controllers\PassportAuthController::class, 'register']);
Route::post('Login', [\App\Http\Controllers\PassportAuthController::class, 'Login']);

Route::middleware(['auth:api']) ->group(function (){
    Route::get('logout', [\App\Http\Controllers\PassportAuthController::class, 'logout']);
//route categories
    Route::post('categories/',[\App\Http\Controllers\CategoryController::class,'store']);
    Route::get('categories/{id}',[\App\Http\Controllers\CategoryController::class,'show']);
    Route::put('categories/{id}',[\App\Http\Controllers\CategoryController::class,'update']);
    Route::delete('categories/{id}',[\App\Http\Controllers\CategoryController::class,'destroy']);
    Route::get("categories/search/{name}",[\App\Http\Controllers\CategoryController::class,'search']);
    Route::get('categories/',[\App\Http\Controllers\CategoryController::class,'index']);

//route  products
    Route::get("products/search/{name}",[\App\Http\Controllers\ProductController::class,'search']);
    //تصاعدي
    Route::get('products/',[\App\Http\Controllers\ProductController::class,'index']);

    Route::get('products/x',[\App\Http\Controllers\ProductController::class,'indexx']);

    Route::post('products/',[\App\Http\Controllers\ProductController::class,'store']);
    Route::get('products/{id}',[\App\Http\Controllers\ProductController::class,'show']);
    Route::put('products/{id}',[\App\Http\Controllers\ProductController::class,'update']);
    Route::delete('products/{id}',[\App\Http\Controllers\ProductController::class,'destroy']);
     // route comment
    Route::prefix("products/{product}/comments")->group(function (){
        Route::get('/',[\App\Http\Controllers\CommentController::class,'index']);
        Route::post('/',[\App\Http\Controllers\CommentController::class,'store']);
//      Route::get('/{comment}',[\App\Http\Controllers\ProductController::class,'show']);
        Route::put('/{comments}',[\App\Http\Controllers\CommentController::class,'update']);
       Route::delete('/{comments}',[\App\Http\Controllers\CommentController::class,'destroy']);
    });
    Route::post('/{product}/like',[\App\Http\Controllers\LikeController::class,'store']);
});







