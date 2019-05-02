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
/*PAINEIS*/
/*Admin*/
Route::prefix('/admin')->group(function(){
	/*Login*/
	Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
	/*Dashboard*/
	Route::get('/', 'AdminController@index')->name('admin.dashboard');
	Route::resource('/admins', 'Admin\AdminAdminsController');
	Route::resource('/categorias-evento', 'Admin\AdminCategoriasEventoController');
	Route::resource('/categorias-produto', 'Admin\AdminCategoriasProdutoController');
	Route::resource('/cidades', 'Admin\AdminCidadesController');
	Route::resource('/empresas', 'Admin\AdminEmpresasController');
	Route::resource('/eventos', 'Admin\AdminEventosController');
});
/*Franqueado*/
Route::prefix('/franqueado')->group(function(){
	/*Login
	Route::get('/login', 'Auth\FranqueadoLoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'Auth\FranqueadoLoginController@login')->name('admin.login.submit');
	/*Dashboard
	Route::get('/', 'FranqueadoController@index')->name('admin.dashboard');
	Route::resource('/admins', 'Admin\AdminAdminsController');
	Route::resource('/categorias-evento', 'Admin\AdminCategoriasEventoController');
	Route::resource('/categorias-produto', 'Admin\AdminCategoriasProdutoController');
	Route::resource('/cidades', 'Admin\AdminCidadesController');
	Route::resource('/empresas', 'Admin\AdminEmpresasController');
	*/
});
/*Empresa*/
Route::prefix('/empresa')->group(function(){
	/*Login
	Route::get('/login', 'Auth\FranqueadoLoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'Auth\FranqueadoLoginController@login')->name('admin.login.submit');
	/*Dashboard
	Route::get('/', 'FranqueadoController@index')->name('admin.dashboard');
	Route::resource('/admins', 'Admin\AdminAdminsController');
	Route::resource('/categorias-evento', 'Admin\AdminCategoriasEventoController');
	Route::resource('/categorias-produto', 'Admin\AdminCategoriasProdutoController');
	Route::resource('/cidades', 'Admin\AdminCidadesController');
	Route::resource('/empresas', 'Admin\AdminEmpresasController');
	*/
});
/**/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/empresa', 'EmpresaController@index');

