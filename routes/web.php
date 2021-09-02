<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainPageController;

use App\Http\Controllers\PostController;

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

Route::get('/', [MainPageController::class,'index'])->name('welcome');
Route::get('/post/{id}', [MainPageController::class,'show'])->name('welcome.show');

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/loadAvatar', [DashboardController::class, 'loadAvatar'])->name('loadAvatar');

    //Posts
    Route::group(['prefix' => 'post'], function() {
        Route::get('create', [PostController::class, 'create'])->name('post.create');
        Route::post('/', [PostController::class, 'store'])->name('post.store');
        Route::get('edit/{id}', [PostController::class, 'edit'])->name('post.edit');
        Route::patch('update/{id}', [PostController::class, 'update'])->name('post.update');
        Route::delete('destroy/{id}', [PostController::class, 'destroy'])->name('post.destroy');
        Route::get('/toggleVisible/{id}', [PostController::class, 'toggleVisible'])->name('post.toggleVisible');
        Route::get('{id}', [PostController::class, 'show'])->name('post.show');
    });
});
