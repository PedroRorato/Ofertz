@extends('dashboard.admin.layout')
@section('title') Franqueados @endsection
@section('menu') #franqueados-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/franqueados">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/franqueados/create">Adicionar</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#cpf').mask('000.000.000-00', {placeholder: "000.000.000-00"});
        $('#telefone').mask('(00)00000-0000', {placeholder: "(00)00000-0000"});
        //Select erro cadastro
        $("#cidade option[value={!! old('cidade') ? old('cidade') : '1' !!}]").attr('selected', 'selected');
    });
</script>
<a href="/admin/franqueados" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/franqueados" onsubmit="spinner()">
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
                <div class="form-group col-md-4">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }}" id="cpf" name="cpf" value="{{ old('cpf') }}" required>
                    @if ($errors->has('cpf'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('cpf') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control{{ $errors->has('telefone') ? ' is-invalid' : '' }}" id="telefone" name="telefone" value="{{ old('telefone') }}" required>
                    @if ($errors->has('telefone'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('telefone') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label for="cidade">Cidade</label>
                    <select class="custom-select{{ $errors->has('cidade') ? ' is-invalid' : '' }}" id="cidade" name="cidade" required>
                        @foreach($cidades as $cidade)
                            <option value="{{ $cidade->id }}">{{ $cidade->nome.'-'.$cidade->uf }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('cidade'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('cidade') }}</strong>
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
            <div class="dash-botoes">
                <button type="submit" class="btn btn-primary shadow"><i class="fas fa-plus mr-2"></i>Adicionar</button>
            </div>
            <div class="dash-spinner">
                <i class="fas fa-sync-alt fa-spin mr-2"></i>Aguarde...
            </div>
        </form>
    </div>
</div>
@endsection