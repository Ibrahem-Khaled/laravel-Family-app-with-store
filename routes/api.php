<?php

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

// Route to get all categories
Route::get('/categories', [HomeController::class, 'getCategories']);

// Route to get a specific category by ID
Route::get('/categories/{id}', [HomeController::class, 'getCategory']);

// Route to get random contents
Route::get('/contents/random', [HomeController::class, 'getRandomContents']);

// Route to get a specific content by ID
Route::get('/contents/{id}', [HomeController::class, 'getContent']);

// Route to get a sub-category by ID
Route::get('/sub-categories/{id}', [HomeController::class, 'getSubCategory']);

// Route to get all audios
Route::get('/audios/${?id}', [HomeController::class, 'getAudios']);
Route::get('/getSubCategories/audio', [HomeController::class, 'getSubCategoriesAudios']);