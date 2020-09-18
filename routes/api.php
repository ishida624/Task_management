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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/todolist', 'PostController@index');
// Route::middleware('LogsInfo')->apiResource('task', 'api\PostController');
Route::middleware('tokenAuth')->apiResource('card', 'api\CardController');
Route::middleware('tokenAuth')->apiResource('task', 'api\TaskController');
// Route::middleware('tokenAuth')->apiResource('user', 'api\UserController');
Route::middleware('tokenAuth')->get('user/', 'api\UserController@index');
Route::middleware('tokenAuth')->get('user/{id}', 'api\UserController@show');
Route::middleware('tokenAuth')->put('user/', 'api\UserController@update_user_data');
Route::middleware('tokenAuth')->post('user/image', 'api\UserController@upload');
Route::middleware('tokenAuth')->delete('user/image', 'api\UserController@deleteImage');
Route::post('/userToken', 'api\GetToken@login');
Route::post('/register', 'api\GetToken@register');
// Route::middleware('LogsInfo', 'tokenAuth')->post('/task/upload/{id}', 'api\TaskController@upload');
