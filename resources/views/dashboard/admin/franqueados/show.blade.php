@extends('dashboard.layout')
@section('title') Franqueados @endsection
@section('menu') #franqueados-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/franqueados">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/franqueados/{{ $franqueado->id }}">Painel do Administrador</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#cpf').mask('000.000.000-00', {placeholder: "000.000.000-00"});
        $('#telefone').mask('(00)00000-0000', {placeholder: "(00)00000-0000"});
        //Select erro cadastro
       $("#cidade").children('[value="{{ $franqueado->cidade_id }}"]').attr('selected', true);
        $("#status").children('[value="{{ $franqueado->status }}"]').attr('selected', true);
        @if ($errors->has('password'))
            $('#modalSenha').modal('show');
         @endif
    });
</script>
<a href="/admin/franqueados" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/franqueados/{{ $franqueado->id }}" onsubmit="spinner()">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome..." value="{{ $franqueado->nome }}" required autofocus>
                    @if ($errors->has('nome'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('nome') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" class="form-control{{ $errors->has('sobrenome') ? ' is-invalid' : '' }}" id="sobrenome" name="sobrenome" placeholder="Digite o sobrenome..." value="{{ $franqueado->sobrenome }}" required>
                    @if ($errors->has('sobrenome'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('sobrenome') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-12">
                    <label for="email">Email</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Digite o email..." value="{{ $franqueado->email }}" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }}" id="cpf" name="cpf" value="{{ $franqueado->cpf }}" required>
                    @if ($errors->has('cpf'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('cpf') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control{{ $errors->has('telefone') ? ' is-invalid' : '' }}" id="telefone" name="telefone" value="{{ $franqueado->telefone }}" required>
                    @if ($errors->has('telefone'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('telefone') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
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
                    <label for="status">Status</label>
                    <select class="custom-select" id="status" name="status">
                        <option value="ATIVO">ATIVO</option>
                        <option value="EXCLUIDO">EXCLUIDO</option>
                    </select>
                    @if ($errors->has('status'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <hr>
            <div class="dash-botoes">
                <button type="submit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-save mr-2"></i>Salvar</button>
                <button type="button" class="btn btn-warning shadow mr-3 mt-3 mt-sm-0" data-toggle="modal" data-target="#modalSenha">
                    <i class="fas fa-key mr-2"></i>Alterar senha
                </button>
                @if($franqueado->status != 'EXCLUIDO')
                <button type="button" class="btn btn-danger shadow mt-3 mt-sm-0" data-toggle="modal" data-target="#modalDelete">
                    <i class="fas fa-trash-alt mr-2"></i>Excluir
                </button>
                @endif
            </div>
            <div class="dash-spinner">
                <i class="fas fa-sync-alt fa-spin mr-2"></i>Aguarde...
            </div>
        </form>
    </div>
</div>

<!-- Modal SENHA -->
<div class="modal fade" id="modalSenha" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/franqueados/{{ $franqueado->id }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Digite a senha..." required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Confirmar senha</label>
                        <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Confirme a senha..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-warning shadow"><i class="fas fa-key mr-2"></i>Alterar senha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal DELETE -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Franqueado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/franqueados/{{ $franqueado->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir o Franqueado?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger shadow"><i class="fas fa-trash-alt mr-2"></i>Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection