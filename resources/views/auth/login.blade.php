@extends('layouts.app')
@section('title') Login @endsection
@section('button') #nav-login @endsection
@section('content')
<div class="container">
    <br/>
    <h2 class="text-center pt-5">Qual espaço você deseja acessar?</h2>
    <div class="row justify-content-center">
        <div class="py-5 col-lg-6 col-md-8 col-12 text-center">
            <a href="/usuario/login" class="btn btn-outline-danger btn-lg btn-block mb-4"><i class="fas fa-lg fa-user d-inline mr-2"></i>Usuário</a>
            <a href="/empresa/login" class="btn btn-outline-danger btn-lg btn-block mb-4"><i class="fas fa-lg fa-store d-inline mr-2"></i>Empresa</a>
            <a href="/franqueado/login" class="btn btn-outline-danger btn-lg btn-block mb-4"><i class="fas fa-lg fa-handshake d-inline mr-2"></i>Franqueado</a>
        </div>  
    </div>
</div>
@endsection