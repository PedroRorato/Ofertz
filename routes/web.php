<?php

//Auth
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('inicial');
});
/*Guest*/

/*Login Geral*/
Route::get('/login', function () {
    return view('auth/login');
})->name('login');
/*USUARIO*/

Route::prefix('/usuario')->group(function(){
	/*Login*/
	Route::get('/login', 'Auth\UsuarioLoginController@showLoginForm');
	Route::post('/login', 'Auth\UsuarioLoginController@login');
	/*Cadastro*/
	Route::get('/cadastro', 'Auth\UsuarioRegisterController@showRegisterForm');
	Route::post('/cadastro', 'Auth\UsuarioRegisterController@register');
});

/*PAINEIS*/
/*Admin*/
Route::prefix('/admin')->group(function(){
	/*Login*/
	Route::get('/login', 'Auth\AdminLoginController@showLoginForm');
	Route::post('/login', 'Auth\AdminLoginController@login');
	/*Dashboard*/
	Route::get('/', 'Admin\AdminInicialController@index');
	Route::resource('/admins', 'Admin\AdminAdminsController');
	Route::resource('/categorias-evento', 'Admin\AdminCategoriasEventoController');
	Route::resource('/categorias-produto', 'Admin\AdminCategoriasProdutoController');
	Route::resource('/cidades', 'Admin\AdminCidadesController');
	Route::get('/conta', 'Admin\AdminContaController@show');
	Route::patch('/conta', 'Admin\AdminContaController@update');
	Route::delete('/conta', 'Admin\AdminContaController@destroy');
	Route::resource('/empresas', 'Admin\AdminEmpresasController');
	Route::resource('/eventos', 'Admin\AdminEventosController');
	Route::resource('/fotos', 'Admin\AdminFotosController');
	Route::resource('/franqueados', 'Admin\AdminFranqueadosController');
	Route::get('/ofertas', 'Admin\AdminOfertasController@index');
	Route::post('/ofertas', 'Admin\AdminOfertasController@store');
	Route::get('/ofertas/choose', 'Admin\AdminOfertasController@choose');
	Route::get('/ofertas/{oferta}', 'Admin\AdminOfertasController@show');
	Route::patch('/ofertas/{oferta}', 'Admin\AdminOfertasController@update');
	Route::delete('/ofertas/{oferta}', 'Admin\AdminOfertasController@destroy');
	Route::get('/ofertas/produto/{produto}/create', 'Admin\AdminOfertasController@create');
	Route::resource('/produtos', 'Admin\AdminProdutosController');
	Route::resource('/usuarios', 'Admin\AdminUsuariosController');
});
/*Franqueado*/
Route::prefix('/franqueado')->group(function(){
	/*Login*/
	Route::get('/login', 'Auth\FranqueadoLoginController@showLoginForm');
	Route::post('/login', 'Auth\FranqueadoLoginController@login');
	/*Dashboard*/
	Route::get('/', 'Franqueado\InicialController@index');
	Route::get('/conta', 'Franqueado\ContaController@show');
	Route::patch('/conta', 'Franqueado\ContaController@update');
	Route::delete('/conta', 'Franqueado\ContaController@destroy');
	Route::resource('/empresas', 'Franqueado\EmpresasController');
	Route::resource('/eventos', 'Franqueado\EventosController');
	Route::get('/ofertas', 'Franqueado\OfertasController@index');
	Route::post('/ofertas', 'Franqueado\OfertasController@store');
	Route::get('/ofertas/choose', 'Franqueado\OfertasController@choose');
	Route::get('/ofertas/{oferta}', 'Franqueado\OfertasController@show');
	Route::patch('/ofertas/{oferta}', 'Franqueado\OfertasController@update');
	Route::delete('/ofertas/{oferta}', 'Franqueado\OfertasController@destroy');
	Route::get('/ofertas/produto/{produto}/create', 'Franqueado\OfertasController@create');
	Route::resource('/produtos', 'Franqueado\ProdutosController');
	Route::resource('/usuarios', 'Franqueado\UsuariosController');
});
/*Empresa*/
Route::prefix('/empresa')->group(function(){
	/*Login*/
	Route::get('/login', 'Auth\EmpresaLoginController@showLoginForm');
	Route::post('/login', 'Auth\EmpresaLoginController@login');
	/*Cadastro*/
	Route::get('/cadastro', 'Auth\EmpresaRegisterController@showRegisterForm');
	Route::post('/cadastro', 'Auth\EmpresaRegisterController@register');
	/*Dashboard*/
	Route::get('/', 'Empresa\InicialController@index');
	Route::get('/conta', 'Empresa\ContaController@show');
	Route::patch('/conta', 'Empresa\ContaController@update');
	Route::delete('/conta', 'Empresa\ContaController@destroy');
	Route::resource('/empresas', 'Empresa\EmpresasController');
	Route::resource('/eventos', 'Empresa\EventosController');
	Route::get('/ofertas', 'Empresa\OfertasController@index');
	Route::post('/ofertas', 'Empresa\OfertasController@store');
	Route::get('/ofertas/choose', 'Empresa\OfertasController@choose');
	Route::get('/ofertas/{oferta}', 'Empresa\OfertasController@show');
	Route::patch('/ofertas/{oferta}', 'Empresa\OfertasController@update');
	Route::delete('/ofertas/{oferta}', 'Empresa\OfertasController@destroy');
	Route::get('/ofertas/produto/{produto}/create', 'Empresa\OfertasController@create');
	Route::resource('/produtos', 'Empresa\ProdutosController');
	Route::resource('/usuarios', 'Empresa\UsuariosController');
});