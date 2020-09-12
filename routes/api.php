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

/*
    +-----------+-------------------------+------------------+-----------------------++++----------------------------+
    | Method    | URI                     | Name             | Action                                                |
    +-----------+-------------------------+------------------+-------------------------------------------------------+
    | GET|HEAD  | /                       |                  | Closure                                               |
    | GET|HEAD  | projects                | projects.index   | App\Http\Controllers\Api\v1\ProjectController@index   |
    | POST      | projects                | projects.store   | App\Http\Controllers\Api\v1\ProjectController@store   |
    | GET|HEAD  | projects/create         | projects.create  | App\Http\Controllers\Api\v1\ProjectController@create  |
    | GET|HEAD  | projects/{article}      | projects.show    | App\Http\Controllers\Api\v1\ProjectController@show    |
    | PUT|PATCH | projects/{article}      | projects.update  | App\Http\Controllers\Api\v1\ProjectController@update  |
    | DELETE    | projects/{article}      | projects.destroy | App\Http\Controllers\Api\v1\ProjectController@destroy |
    | GET|HEAD  | projects/{article}/edit | projects.edit    | App\Http\Controllers\Api\v1\ProjectController@edit    |
    +-----------+-------------------------+------------------+-------------------------------------------------------+
*/

// Создать пользователя
Route::post('signup', 'Api\v1\UserController@signUp')->name('users.signup');
// Получить новый токен
Route::post('signin', 'Api\v1\UserController@signIn')->name('users.signin');

Route::middleware('auth:api')->group(function () {
    //Сменить токен
    Route::get('signout', 'Api\v1\UserController@signOut')->name('users.signout');
    // Информация о пользователе
    Route::get('/info', 'Api\v1\UserController@infoUser')->name('users.info');

    Route::resource('users', 'Api\v1\UserController');
    Route::resource('projects', 'Api\v1\ProjectController');
    Route::resource('tasks', 'Api\v1\TaskController');

    Route::delete('projects/{id}/kill', 'Api\v1\ProjectController@kill');

    // Elasticsearch
    Route::prefix('/search')->group(function () {
        // Поиск по ключевому слову ?ser=word...
        Route::get('/', 'Api\v1\SearchController@find')->name('search');
        // Добавление документов в Elastic
        Route::post('/', 'Api\v1\SearchController@createIndex');
        // Удаление документов из Elastic
        Route::delete('/', 'Api\v1\SearchController@deleteIndex');
    });
});
