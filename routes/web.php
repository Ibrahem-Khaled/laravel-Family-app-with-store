<?php

use App\Http\Controllers\dashboard\AudioController;
use App\Http\Controllers\dashboard\CategoryController;
use App\Http\Controllers\dashboard\ContentController;
use App\Http\Controllers\dashboard\HistoryFamilyController;
use App\Http\Controllers\dashboard\postController;
use App\Http\Controllers\dashboard\SubCategoryController;
use App\Http\Controllers\dashboard\SubscriptionController;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('home.dashboard');
})->name('home');

Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home.dashboard');


    //this dashboard route is protected by the auth middleware
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('sub-categories', SubCategoryController::class);
    Route::resource('contents', ContentController::class);
    Route::resource('audio', AudioController::class);
    Route::resource('history-families', HistoryFamilyController::class);
    Route::resource('posts', postController::class);
    
    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('subscriptions/{subscription}/add-user', [SubscriptionController::class, 'addUser'])->name('subscriptions.addUser');
    Route::post('subscriptions/{subscription}/remove-user', [SubscriptionController::class, 'removeUser'])->name('subscriptions.removeUser');
});


require __DIR__ . '/web/auth.php';