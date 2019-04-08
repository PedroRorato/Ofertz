@extends('layouts.app')
@section('title') Cadastrar @endsection
@section('button') #nav-login @endsection
@section('content')
<div class="container">
	<br/>
	<h2 class="text-center pt-5">Como você deseja se cadastrar?</h2>
	<div class="row justify-content-center">
		<div class="py-5 col-lg-6 col-md-8 col-12 text-center">
			<a href="/cadastro-usuario" class="btn btn-outline-danger btn-lg mr-sm-5 mx-5 ml-sm-0 mb-4 mb-sm-0"><i class="fas fa-lg fa-user d-inline mr-2"></i>Usuário</a>
            <a href="/cadastro-empresa" class="btn btn-outline-danger btn-lg mx-5 mx-sm-0"><i class="fas fa-lg fa-store d-inline mr-2"></i>Empresa</a>
		</div>	
	</div>
	<a href="/login"><p class="text-center text-muted">Já tem cadastro? Logar</p></a>
</div>
@endsection