@extends('dashboard.admin.layout')
@section('title') Administradores @endsection
@section('menu') #administradores-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/admins">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/admins/{{ $admin->id }}">Painel do Administrador</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        $("#status").children('[value="{{ $admin->status }}"]').attr('selected', true);
@if ($errors->has('password'))
    $('#modalSenha').modal('show');
 @endif
    });
</script>
<a href="/admin/admins" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/admins/{{ $admin->id }}" onsubmit="spinner()">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome..." value="{{ $admin->nome }}" required autofocus>
                    @if ($errors->has('nome'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('nome') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" class="form-control{{ $errors->has('sobrenome') ? ' is-invalid' : '' }}" id="sobrenome" name="sobrenome" placeholder="Digite o sobrenome..." value="{{ $admin->sobrenome }}" required>
                    @if ($errors->has('sobrenome'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('sobrenome') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Digite o email..." value="{{ $admin->email }}" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status</label>
                    <select class="custom-select" id="status" name="status">
                        <option value="ATIVO">ATIVO</option>
                        <option value="EXCLUIDO">EXCLUIDO</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="dash-botoes">
                <button type="submit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-save mr-2"></i>Salvar</button>
                <button type="button" class="btn btn-warning shadow mr-3 mt-3 mt-sm-0" data-toggle="modal" data-target="#modalSenha">
                    <i class="fas fa-key mr-2"></i>Alterar senha
                </button>
                @if($admin->status != 'EXCLUIDO')
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
            <form method="POST" action="/admin/admins/{{ $admin->id }}">
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
                <h5 class="modal-title">Excluir Administrador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/admins/{{ $admin->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir o Administrador?</h5>
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