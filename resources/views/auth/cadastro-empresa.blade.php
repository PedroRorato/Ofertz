@extends('layouts.app')
@section('title') Cadastrar Empresa @endsection
@section('button') #nav-login @endsection
@section('content')
<head>
	<script src="{{ asset('js/jquery.mask.min.js') }}" defer></script>
</head>
<script type="text/javascript">
    $(document).ready(function () {
    	$('#cnpj').mask('00.000.000/0000-00', {placeholder: "00.000.000/0000-00"});
    	$('#nascimento').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
    	$('#telefone').mask('(00)00000-0000', {placeholder: "(00)00000-0000"});
    	//Select erro cadastro
        $("#cidade option[value={!! old('cidade') ? old('cidade') : '' !!}]").attr('selected', 'selected');
        $("#genero option[value={!! old('genero') ? old('genero') : '1' !!}]").attr('selected', 'selected');
    });
</script>
<div class="container">
	<div class="row ">
		<form class="py-5 col-lg-6 col-md-8 mx-auto" method="POST" action="/empresa/cadastro">
			<h2 class="text-center pb-2">Cadastro de Empresa</h2>
			@if(Session::has('message'))
				<div class="alert alert-success shadow text-center" role="alert">
                    <h4 class="mb-0">
                        {{ Session::get('message') }}
                    </h4>
                </div>
			@else
				@if($errors->any() || Session::has('data'))
	                <div class="alert alert-danger shadow text-center" role="alert">
	                    <h4 class="mb-0">
	                        Erro ao enviar formulário! Confira os dados!
	                        <button type="button" class="close" onclick="hiddeAlert()">
	                            <span>&times;</span>
	                        </button>
	                    </h4>
	                </div>
	                @endif
				@csrf
				<p class="text-center mb-2">Preencha os dados da empresa</p>
			  	<div class="form-group">
				    <label for="cidade">Cidade</label>
				    <select class="custom-select{{ $errors->has('cidade') ? ' is-invalid' : '' }}" id="cidade" name="cidade" required autofocus>
					@foreach($cidades as $cidade)
	                    <option value="{{ $cidade->id }}">{{ $cidade->nome.'-'.$cidade->uf }}</option>
	                @endforeach
					</select>
					<small class="form-text text-muted">Cidade que você deseja anunciar suas ofertas.</small>
				    @if ($errors->has('cidade'))
	                    <span class="invalid-feedback" role="alert">
	                        <strong>{{ $errors->first('cidade') }}</strong>
	                    </span>
	                @endif
			  	</div>
			  	<div class="form-group">
				    <label for="empresa">Empresa</label>
				    <input type="text" class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}" id="empresa" name="empresa" value="{{ old('empresa') }}" placeholder="Digite o nome da empresa..." required>
				    <small class="form-text text-muted">Nome da empresa!</small>
				    @if ($errors->has('name'))
	                    <span class="invalid-feedback" role="alert">
	                        <strong>{{ $errors->first('name') }}</strong>
	                    </span>
	                @endif
			  	</div>
			  	<div class="form-group">
				    <label for="cnpj">CNPJ</label>
				    <input type="text" class="form-control{{ $errors->has('cnpj') ? ' is-invalid' : '' }}" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" placeholder="Digite o CNPJ..." required>
				    @if ($errors->has('cnpj'))
	                    <span class="invalid-feedback" role="alert">
	                        <strong>{{ $errors->first('cnpj') }}</strong>
	                    </span>
	                @endif
			  	</div>
			  	<hr class="mx-5">
				<p class="text-center mb-2">Preencha seus dados pessoais</p>
				<input type="hidden" name="tipo" value="EMPRESA" required>
			  	<div class="form-group">
				    <label for="nome">Nome</label>
				    <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Digite seu nome..." required>
				    <small class="form-text text-muted">Nome do proprietário ou responsável pelo empresa!</small>
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
				    <label for="telefone">Telefone</label>
				    <input type="text" class="form-control{{ $errors->has('telefone') ? ' is-invalid' : '' }}" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="" required>
				    @if ($errors->has('nascimento'))
	                    <span class="invalid-feedback" role="alert">
	                        <strong>{{ $errors->first('nascimento') }}</strong>
	                    </span>
	                @endif
			  	</div>		 
			  	<div class="form-group">
				    <label for="genero">Gênero</label>
				    <select class="custom-select{{ $errors->has('genero') ? ' is-invalid' : '' }}" id="genero" name="genero" required>
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
				    <input type="text" class="form-control{{ Session::has('data') ? ' is-invalid' : '' }}" id="nascimento" name="nascimento" value="{{ old('nascimento') }}" placeholder="" required>
				    @if (Session::has('data'))
	                    <span class="invalid-feedback" role="alert">
	                        <strong>{{ Session::get('data') }}</strong>
	                    </span>
	                @endif
			  	</div>		  	
			  	<button type="submit" class="btn btn-danger btn-block">Cadastrar</button>
			  	<a href="/empresa/login"><p class="form-text text-muted">Já tem cadastro? Logar</p></a>
			@endif
		</form>	
	</div>
</div>
@endsection