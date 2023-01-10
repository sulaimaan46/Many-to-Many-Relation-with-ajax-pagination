<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


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

Route::resource('users', UserController::class);

Route::get('post_users/view', [UserController::class,'view'])->name('post_users.view');

// Route::resource('comments', CommentController::class);
Route::get('/comments', [CommentController::class,'index'])->name('comments');
Route::get('/comments/show', [CommentController::class,'show'])->name('comments.show');
Route::get('/comments/edit/', [CommentController::class,'view'])->name('comments.edit');
Route::get('/comments/destroy', [CommentController::class,'destroy'])->name('comments.destroy');
Route::post('/comments/saveComments', [CommentController::class,'store'])->name('comments.saveComments');
Route::get('/comments/userslist', [CommentController::class,'userslist'])->name('comments.userslist');


Route::get('post_comments/view', [CommentController::class,'createView'])->name('post_comments.view');

Route::post('/comments', [CommentController::class,'getComments'])->name('comments.dataTable');

Route::resource('posts', PostController::class);

Route::get('/', function () {
    return view('welcome');
});
