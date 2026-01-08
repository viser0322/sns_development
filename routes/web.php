<?php

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

// Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::redirect('/', '/home', 301);
Route::get('/home', 'App\Http\Controllers\HomeController@index');
Route::post('/home', 'App\Http\Controllers\HomeController@post');

Route::get('/notice', 'App\Http\Controllers\HomeController@notice');

Route::post('/reaction/regist', 'App\Http\Controllers\ReactionController@regist');
Route::post('/reaction/get', 'App\Http\Controllers\ReactionController@get');
Route::post('/reaction/count', 'App\Http\Controllers\ReactionController@count');

Route::get('/post/{id}', 'App\Http\Controllers\PostController@index');
Route::delete('/post/{id}/delete', 'App\Http\Controllers\PostController@destroy');
Route::post('/post/get/{id}', 'App\Http\Controllers\PostController@getDisplay');

Route::get('/login', 'App\Http\Controllers\Auth\LoginController@index')->name('login');
Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('/login/google', 'App\Http\Controllers\Auth\LoginController@redirectToGoogle');
Route::get('/login/google/callback', 'App\Http\Controllers\Auth\LoginController@handleGoogleCallback');

Route::get('/profile/{id}', 'App\Http\Controllers\UserController@show');

Route::get('/regist', 'App\Http\Controllers\CreateUserController@create');
Route::post('/regist', 'App\Http\Controllers\CreateUserController@store');

Route::get('/edit/{id}', 'App\Http\Controllers\UserController@edit');
Route::post('/edit/{id}', 'App\Http\Controllers\UserController@update');

