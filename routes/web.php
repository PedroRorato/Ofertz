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
/*Cadastro*/
Route::get('/cadastro', function () {
    return view('auth/cadastro');
});
Route::get('/cadastro-empresa', function () {
    return view('auth/cadastro-empresa');
});
Route::get('/cadastro-usuario', function () {
    return view('auth/cadastro-usuario');
});
/**/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/redireciona', 'RedirecionaLogin@index')->name('redireciona');
