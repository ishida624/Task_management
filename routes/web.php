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
    return view('welcome');
});
Route::get('/update/{id}', function ($id) {
    return view('update', array('id'=>$id));
});
// Route::get('/delete/{id}', function ($id) {
//     return view('delete', array('id'=>$id));
// });
// Route::get('/LoginPage', function () {
//     return view('LoginPage');
// });
// Route::get('/RegisterPage', function () {
//     return view('register');
// });
// Route::post('/loginController', 'Todocontroller@login');
// Route::post('/registeredController', 'Todocontroller@register');
// Route::get('/LoginPage', 'Todocontroller@login')->name('LoginPage');
//Route::get('/welcome/{id}', 'db_test@db_test');
Route::get('/todolist', 'Todocontroller@readTable');
Route::middleware('auth')->group(function () {
    Route::put("/update", 'Todocontroller@update');
    Route::post('/create', 'Todocontroller@create');
    Route::delete('/delete', 'Todocontroller@delete');
    Route::put('/complete', 'Todocontroller@complete');
});

// Route::get('/', function () {
//     return 'Hello World';
// });
// Route::get('/', function () {
//     return view('about');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
