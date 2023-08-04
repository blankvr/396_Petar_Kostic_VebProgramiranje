<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;


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
//login-register routes
Route::get('/', [UserController::class, "showCorrectHomepage"]);
Route::get('/login', [PageController::class, "login"]);
Route::get('/home', [PageController::class, "home"]);
Route::post('/login', [UserController::class, "login"]);
Route::post('/register', [UserController::class, "registration"]);
Route::get('/logout', [UserController::class, "logout"]);
Route::get('/my-feed/{user:username}', [UserController::class, "showMyFeed"]);



//post related routes
Route::get('/create-post', [PostController::class, 'showCreateForm']);
Route::post('/create-post', [PostController::class, 'storeNewPost']);
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::post('/post/{post}', [PostController::class, 'delete']);

Route::post('/edit-post/{post}', [PostController::class, 'showEditForm']);
Route::post('/edit-post/{post}/edit', [PostController::class, 'edit']);


//users following each other
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow']);
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow']);

//user profile related routes
Route::get('/profile/{user:username}', [UserController::class, 'userProfile']);
Route::get('/edit-profile/{user}', [UserController::class, 'showEditProfileForm']);
Route::post('/edit-profile/{user}/edit', [UserController::class, 'editProfile']);
Route::get('/user_followers/{user:username}', [UserController::class, 'showFollowers']);
Route::get('/user_following/{user:username}', [UserController::class, 'showFollowing']);
Route::get('/delete-profile/{user}', [UserController::class, 'deleteProfile']);




//admin-moderator pages
Route::get('/admin-page', [PageController::class, "adminPage"])->middleware('can:isAdmin');
Route::get('/manage-users', [PageController::class, "manageUsers"])->middleware('can:isAdmin');
Route::get('/manage-posts', [PageController::class, "managePosts"])->middleware('can:isMod-Admin');
Route::post('/delete-post/{post}', [PostController::class, 'adminDelete']);
Route::post('/delete-user/{user}', [UserController::class, 'deleteUser']);
Route::post('/edit-user-role/{user}', [PageController::class, 'editUserRole']);
Route::post('/edit-user-role/{user}/edit', [UserController::class, 'editRole']);












