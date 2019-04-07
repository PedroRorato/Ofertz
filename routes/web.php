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

Route::get('/', function () {
    return view('inicial');
});
Route::get('/teste', function () {
    return view('teste');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/redireciona', 'RedirecionaLogin@index')->name('redireciona');
