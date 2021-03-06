@extends('layouts.app')
@section('title') Cadastrar Usuário @endsection
@section('button') #nav-login @endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
    	$('#nascimento').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
    	//Select erro cadastro
        $("#cidade option[value={!! old('cidade') ? old('cidade') : '1' !!}]").attr('selected', 'selected');
        $("#genero option[value={!! old('genero') ? old('genero') : '1' !!}]").attr('selected', 'selected');
    });
</script>
<div class="container">
	<div class="row ">
		<form class="py-5 col-lg-6 col-md-8 mx-auto" method="POST" action="/usuario/cadastro">
			<h2 class="text-center pb-2">Cadastro de Usuário</h2>
			<p class="text-center mb-2">Crie sua conta rápido e fácil com o Facebook</p>
			<a href="" class="btn btn-facebook btn-block"><i class="fab fa-facebook fa-lg mr-2"></i>Cadastrar com Facebook</a>
			<hr class="mx-5">
			@csrf
			<p class="text-center mb-2">Ou preencha o formulário abaixo</p>
			<div class="form-group">
			    <label for="cidade">Cidade</label>
			    <select class="custom-select{{ $errors->has('cidade') ? ' is-invalid' : '' }}" id="cidade" name="cidade" required autofocus>
				@foreach($cidades as $cidade)
                    <option value="{{ $cidade->id }}">{{ $cidade->nome.'-'.$cidade->uf }}</option>
                @endforeach
				</select>
				<small class="form-text text-muted">Cidade que você deseja receber ofertas. Pode ser alterada a qualquer momento!</small>
			    @if ($errors->has('cidade'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('cidade') }}</strong>
                    </span>
                @endif
		  	</div>
		  	<div class="form-group">
			    <label for="name">Nome</label>
			    <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Digite seu nome..." required>
			    @if ($errors->has('nome'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('nome') }}</strong>
                    </span>
                @endif
		  	</div>
		  	<div class="form-group">
			    <label for="sobrenome">Sobrenome</label>
			    <input type="text" class="form-control{{ $errors->has('sobrenome') ? ' is-invalid' : '' }}" id="sobrenome" name="sobrenome" value="{{ old('sobrenome') }}" placeholder="Digite seu sobrenome..." required>
			    @if ($errors->has('sobrenome'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('sobrenome') }}</strong>
                    </span>
                @endif
		  	</div>
		  	<div class="form-group">
			    <label for="email">Email</label>
			    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" placeholder="Digite seu email..." required>
			    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
		  	</div>
		  	<div class="form-group">
		    	<label for="password">Senha</label>
		    	<input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Digite sua senha..." required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @else
                	<small class="form-text text-muted">Deve conter pelo menos 5 caracteres!</small>
                @endif
		  	</div>
		  	<div class="form-group">
		    	<label for="password-confirm">Confirmar senha</label>
		    	<input type="password" class="form-control" id="password-confirm" class="form-control" name="password_confirmation" placeholder="Confirme sua senha..." required>
		  	</div>
		  	<div class="form-group">
			    <label for="genero">Gênero</label>
			    <select class="custom-select{{ $errors->has('genero') ? ' is-invalid' : '' }}" id="genero" name="genero" required autofocus>
					<option value="female">Feminino</option>
					<option value="male">Masculino</option>
					<option value="other">Outro</option>
				</select>

			    @if ($errors->has('genero'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('genero') }}</strong>
                    </span>
                @endif
		  	</div>
		  	<div class="form-group">
			    <label for="nascimento">Data de Nascimento</label>
			    <input type="text" class="form-control{{ $errors->has('nascimento') ? ' is-invalid' : '' }}" id="nascimento" name="nascimento" value="{{ old('nascimento') }}" placeholder="" required>
			    @if ($errors->has('nascimento'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('nascimento') }}</strong>
                    </span>
                @endif
		  	</div>
		  	<button type="submit" class="btn btn-danger btn-block">Cadastrar</button>
		  	<a href="/usuario/login"><p class="form-text text-muted">Já tem cadastro? Logar</p></a>
		</form>	
	</div>
</div>
@endsection