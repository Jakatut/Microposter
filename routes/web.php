<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Auth::routes();

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
Route::group(['middleware' => 'auth'], function(){
	Route::get('/', [HomeController::class, 'index']);
	Route::get('/home', [HomeController::class, 'index']);
	Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
	Route::get('/profile/{name}', [ProfileController::class, 'profileById'])->name('profileById');
	// Route::get('/logout', [LoginController::class, 'doLogout']);
});



Route::get('login/{provider}', [SocialController::class, 'redirect']);
Route::get('auth/{provider}/callback',[SocialController::class, 'Callback'])->where('provider', '.*');