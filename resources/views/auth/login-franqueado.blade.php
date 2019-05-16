@extends('layouts.app')
@section('title') Login Franqueado @endsection
@section('button') #nav-login @endsection
@section('content')
<div class="container">
    <div class="row ">
        <form class="py-5 col-lg-6 col-md-8 mx-auto" method="POST" action="/franqueado/login">
            <h2 class="text-center pb-2">Login Franqueado</h2>
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" aria-describedby="emailHelp" name="email" value="{{ old('email') }}" placeholder="Digite seu email..." required autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Digite sua senha..." required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <a href=""><p class="form-text text-muted">Esqueceu a senha?</p></a>
            </div>
            @if(Session::has('message'))
            <div class="alert alert-danger text-center" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="close" onclick="hiddeAlert()">
                        <span>&times;</span>
                    </button>
            </div>
            @endif
            <button type="submit" class="btn btn-danger shadow btn-block">Login</button>
        </form> 
    </div>
</div>
@endsection