@extends('dashboard.layout')
@section('title') Administradores @endsection
@section('menu') #administradores-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/admins">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/admins/create">Adicionar</a></li>
@endsection
@section('content')
<a href="/admin/admins" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/admins">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome..." value="{{ old('nome') }}" required autofocus>
                    @if ($errors->has('nome'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('nome') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" class="form-control{{ $errors->has('sobrenome') ? ' is-invalid' : '' }}" id="sobrenome" name="sobrenome" placeholder="Digite o sobrenome..." value="{{ old('sobrenome') }}" required>
                    @if ($errors->has('sobrenome'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('sobrenome') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-12">
                    <label for="email">Email</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Digite o email..." value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Digite a senha..." required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="password-confirm">Confirmar senha</label>
                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Confirme a senha..." required>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary shadow"><i class="fas fa-plus mr-2"></i>Adicionar</button>
        </form>
    </div>
</div>
@endsection