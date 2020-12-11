<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminCheck;
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

// Home (blog)
Route::get('/',[HomeController::class,'index']);
Route::get('/getPosts',[HomeController::class,'getPosts']);
Route::get('/getCategories',[HomeController::class,'getCategories']);
Route::post('/post/{slug}',[HomeController::class,'postSingle']);
Route::get('/categories/{categoryName}/{id}',[HomeController::class,'categoryIndex']);
Route::get('/tags/{tagName}/{id}',[HomeController::class,'tagIndex']);
Route::get('/users/{userName}/{id}',[HomeController::class,'userIndex']);
Route::get('/searchStr',[HomeController::class,'search']);

// Auth
Route::post('login', [AuthController::class,'login']);
Route::post('auth',[AuthController::class,'auth']);
Route::post('logout', [AuthController::class,'logout']);

// Admin Panel
Route::prefix('/app')->middleware([AdminCheck::class])->group(function(){
    // Admin main page/'
    Route::get('/', [AuthController::class,'index']);
    // Users
    Route::get('get_users', [UserController::class,'index']);
    Route::post('create_user', [UserController::class,'store']);
    Route::post('update_user', [UserController::class,'update']);
    Route::post('delete_user', [UserController::class,'destroy']);
    // Roles
    Route::get('get_roles', [RoleController::class,'index']);
    Route::post('create_role', [RoleController::class,'store']);
    Route::post('update_role', [RoleController::class,'update']);
    Route::post('delete_role', [RoleController::class,'destroy']);
    Route::post('assign_roles', [RoleController::class,'assign']);
    // Tags
    Route::get('get_tags', [TagController::class,'index']);
    Route::post('create_tag', [TagController::class,'store']);
    Route::post('update_tag', [TagController::class,'update']);
    Route::post('delete_tag', [TagController::class,'destroy']);
    // Categories
    Route::get('get_categories', [CategoryController::class,'index']);
    Route::post('create_category', [CategoryController::class,'store']);
    Route::post('update_category', [CategoryController::class,'update']);
    Route::post('delete_category', [CategoryController::class,'destroy']);
    // Posts
    Route::get('get_posts', [PostController::class,'index']);
    Route::post('create_post', [PostController::class,'store']);
    Route::get('show_post/{id}', [PostController::class,'show']);
    Route::post('update_post/{id}', [PostController::class,'update']);
    Route::post('delete_post', [PostController::class,'destroy']);
    // Images
    Route::post('upload', [Controller::class,'upload']);
    Route::post('delete_image', [Controller::class,'deleteImage']);
    Route::post('update_avatar', [UserController::class,'updateAvatar']);
});
// To upload imgs from Editor.js image add this route name in middleware/VerifyCsrfToken 
// Name of the path must match the name of the route /app/createpost
Route::post('/app/createpost', [PostController::class,'uploadEditorImage']);
Route::post('/app/editpost/{slug}', [PostController::class,'uploadEditorImage']);


Route::any('/app/{slug}', [HomeController::class,'index']);
Route::any('/{slug}', [HomeController::class,'index'])->where('slug', '.*');
