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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::get('/todolist', 'PostController@index');
// Route::middleware('LogInfo')->apiResource('task', 'api\PostController');
Route::middleware('LogsInfo', 'tokenAuth')->apiResource('task', 'api\PostController');
Route::middleware('LogsInfo')->post('/userToken', 'api\GetToken@login');
Route::middleware('LogsInfo')->post('/register', 'api\GetToken@register');
// Route::get('/LoginPage', function () {
//     return view('LoginPage');
// });
// Route::get('/RegisterPage', function () {
//     return view('register');
// });
