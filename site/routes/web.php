<?php

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

use Illuminate\Support\Facades\Auth;

Route::get('/', "PostController@index")->name("home");

// Authentication
Route::get('/register', "Auth\RegisterController@showRegistrationForm")->name("register");
Route::post('/register', "Auth\RegisterController@register");

Route::get('/login', "Auth\LoginController@showLoginForm")->name("login");
Route::post('/login', "Auth\LoginController@login");

Route::get('/logout', function () {
    Auth::logout();
    return redirect("/");
})->name("logout");
Route::get('/dashboard', "UserController@dashboard")->name("dashboard")->middleware("auth");

// Post
Route::resource('/posts', "PostController")->except(["index", "show"])->middleware("auth");
Route::resource('/posts', "PostController")->only(["index", "show"]);

// Tag
Route::resource('/tags', "TagController")->only(["index", "show"]);
