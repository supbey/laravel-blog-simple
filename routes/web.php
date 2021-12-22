<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;


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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/', function () {
    return redirect('/blog');
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog.home');
Route::get('/blog/{slug}', [BlogController::class, 'showPost'])->name('blog.detail');



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// 后台路由
Route::get('/admin', function () {
    return redirect('/admin/post');
});
Route::middleware('auth')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::resource('admin/post', 'PostController', ['except' => 'show']);   // Route::resource('admin/post', 'PostController');
    Route::resource('admin/tag', 'TagController', ['except' => 'show']);    // Route::resource('admin/tag', 'TagController');
    Route::get('admin/upload', 'UploadController@index');
});


Route::namespace('App\Http\Controllers\Auth')->group(function() {
    // 登录退出
    //Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
    //Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');
    //Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout')->name('logout');    
});


Route::namespace('App\Http\Controllers\Admin')->group(function() {
    // 在这一行下面
    Route::get('admin/upload', 'UploadController@index');

    // 添加如下路由
    Route::post('admin/upload/file', 'UploadController@uploadFile');
    Route::delete('admin/upload/file', 'UploadController@deleteFile');
    Route::post('admin/upload/folder', 'UploadController@createFolder');
    Route::delete('admin/upload/folder', 'UploadController@deleteFolder');
});