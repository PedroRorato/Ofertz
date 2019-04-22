@extends('dashboard.admin.layout')
@section('title') Administradores @endsection
@section('collapse') #collapseAdministradores @endsection
@section('menuG') #administradores-menu @endsection
@section('menuP') #administradores-adicionar @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/admins">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/admins/{{ $admin->id }}">Painel do Administrador</a></li>
@endsection
@section('content')
@if ($errors->has('password'))
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#modalSenha').modal('show');
        });
    </script>
 @endif
<a href="/admin/admins" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        
        
        <div class="row">
            <div class="form-group col-md-6">
                <label>Nome</label>
                <h4>{{ $admin->name }}</h4>
            </div>
            <div class="form-group col-md-6">
                <label>Sobrenome</label>
                <h4>{{ $admin->surname }}</h4>
            </div>
            <div class="form-group col-md-6">
                <label>Email</label>
                <h4>{{ $admin->email }}</h4>
            </div>
            <div class="form-group col-md-6">
                <label>Status</label>
                <h4>{{ $admin->status }}</h4>
            </div>
        </div>
        <hr>
        <a href="/admin/admins/{{ $admin->id }}/edit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-edit mr-2"></i>Editar</a>
        <button type="button" class="btn btn-warning shadow mr-3 mt-3 mt-sm-0" data-toggle="modal" data-target="#modalSenha">
            <i class="fas fa-key mr-2"></i>Alterar senha
        </button>
        @if($admin->status != 'EXCLUIDO')
        <button type="submit" class="btn btn-danger shadow mt-3 mt-sm-0" data-toggle="modal" data-target="#modalDelete">
            <i class="fas fa-trash-alt mr-2"></i>Excluir
        </button>
        @endif
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