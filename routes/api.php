<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Регистрация нового пользователя
Route::post('signup', 'Api\v1\UserController@signUp')->name('api.users.signup');;
Route::post('signin', 'Api\v1\UserController@signIn')->name('api.users.signin');;

Route::get('signin', 'Api\v1\UserController@noAuth')->name('api.users.signin.noauth');;

Route::middleware('auth:api')->group(function(){

    Route::get('signout', 'Api\v1\UserController@signOut')->name('api.users.signout');;

    /**
     * Информация о всех пользователях, их проектах и задачах с пагинацией
     */
    Route::prefix('/users')->group(function () {
        // Список пользователей
        Route::get('/', 'Api\v1\UserController@index')->name('api.users.read');

    });

    Route::prefix('/users/{id}')->group(function () {
        // Информация по пользователю
        Route::get('/', 'Api\v1\UserController@show')->name('api.users.read.id');

    });


});
