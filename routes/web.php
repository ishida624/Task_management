<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    // dd('hello');
    return 'hello';
});
Route::get('/update/{id}', function ($id) {
    return view('update', array('id' => $id));
});


// Route::get('/', function () {
//     return 'Hello World';
// });
// Route::get('/', function () {
//     return view('about');
// });

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
