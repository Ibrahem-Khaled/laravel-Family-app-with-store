<?php

use App\Http\Controllers\api\authController;
use App\Http\Controllers\api\familyController;
use App\Http\Controllers\api\homeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// auth routes
Route::post('register', [authController::class, 'register']);
Route::post('login', [authController::class, 'loginByEmailOrPhone']);
Route::post('logout', [authController::class, 'logout']);
Route::get('me', [authController::class, 'me']);
Route::post('deleteAccount', [authController::class, 'deleteAccount']);


Route::get('/categories/{type?}/only', [HomeController::class, 'getCategoriesContentOnly']);
Route::get('/contents', [HomeController::class, 'getContents']);

Route::get('/products', [HomeController::class, 'getProducts']);

Route::get('/categories/{id}', [HomeController::class, 'getCategory']);
Route::get('/contents/{id}', [HomeController::class, 'getContent']);
Route::get('/sub-categories/{id}', [HomeController::class, 'getSubCategory']);

// Route to get all audios
Route::get('/audios/{id?}', [HomeController::class, 'getAudios']);
Route::get('/getSubCategories/audio', [HomeController::class, 'getSubCategoriesAudios']);

// Route to get all subscriptions
Route::get('/subscriptions/{token?}', [HomeController::class, 'getSubscriptions']);

// Route to get all Family and Family History
Route::get('/family', [familyController::class, 'index']);
Route::get('/family/{userId}', [familyController::class, 'getFamily']);
Route::post('/storePostInUserFamily', [familyController::class, 'storePostInUserFamily']);