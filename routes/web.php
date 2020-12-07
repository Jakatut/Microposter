<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserAccess;

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
	Route::get('/', [HomeController::class, 'index'])->name('index');
	Route::get('/home', [HomeController::class, 'index'])->name('home');

	Route::get('/editProfile', [ProfileController::class, 'editProfile'])->name('editProfile');
	Route::post('/updateProfile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
	Route::get('/profile/{id?}', [ProfileController::class, 'profileById'])->name('profile');

	Route::post('/{id}/toggleFollow', [ProfileController::class, 'toggleFollow'])->name('toggleFollow');
	Route::get('/profile/{id}/isFollowing', [ProfileController::class, 'isFollowing'])->name('isFollowing');
	Route::get('/following/{id?}', [FollowerController::class, 'following'])->name('following');
	Route::get('/followers/{id?}', [FollowerController::class, 'followers'])->name('followers');

	Route::get('/users/{id?}', [UserController::class, 'index'])->name('users');


	// Route::get('/logout', [LoginController::class, 'doLogout']);
	Route::get('/posts', [PostController::class, 'index'])->name('posts');
	//these route are protected by 'UserAccess' middleware (makes sure that routes aren't accessed by non-original users)
	Route::middleware([UserAccess::class])->group(function(){
 
    Route::get('/posts/{postId}', [PostController::class, 'viewPost'])->name('posts.viewPost');
    Route::post('/posts/{postId}/delete', [PostController::class, 'deletePost'])->name('posts.deletePost');
 
 });
	
	Route::get('/newPost', [PostController::class, 'newPost'])->name('newPost');
	Route::post('/createNewPost', [PostController::class, 'createNewPost'])->name('createNewPost');
	Route::post('/posts/{postId}', [PostController::class, 'editPost'])->name('posts.editPost');
	
});





Route::get('login/{provider}', [SocialController::class, 'redirect']);
Route::get('auth/{provider}/callback',[SocialController::class, 'Callback'])->where('provider', '.*');