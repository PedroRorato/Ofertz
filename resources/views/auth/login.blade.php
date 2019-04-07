@extends('layouts.app')
@section('title') Login @endsection
@section('button') #nav-login @endsection
@section('content')
<div class="container">
    <div class="row ">
        <form class="py-5 col-lg-6 col-md-8 mx-auto" method="POST" action="{{ route('login') }}">
            <h2 class="text-center pb-2">Login</h2>
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
            <button type="submit" class="btn btn-danger btn-block">Login</button>
            <a href="/register" type="submit" class="btn btn-dark btn-block">Cadastrar</a>
            <hr class="mx-5">
            <a href="" type="submit" class="btn btn-facebook btn-block"><i class="fab fa-facebook fa-lg mr-2"></i>Login com Facebook</a>
        </form> 
    </div>
</div>
@endsection