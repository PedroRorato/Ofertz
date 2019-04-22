@extends('dashboard.admin.layout')
@section('title') Administradores @endsection
@section('collapse') #collapseAdministradores @endsection
@section('menuG') #administradores-menu @endsection
@section('menuP') #administradores-adicionar @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/admins">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/admins/{{ $admin->id }}">Painel do Administrador</a></li>
<li class="breadcrumb-item"><a href="/admin/admins/{{ $admin->id }}/edit">Editar Administrador</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        $("#status").children('[value="{{ $admin->status }}"]').attr('selected', true);
    });
</script>
<a href="/admin/admins/{{ $admin->id }}" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/admins/{{ $admin->id }}">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Digite o nome..." value="{{ $admin->name }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="surname">Sobrenome</label>
                    <input type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" id="surname" name="surname" placeholder="Digite o sobrenome..." value="{{ $admin->surname }}" required>
                    @if ($errors->has('surname'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('surname') }}</strong>
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
                        <option value="INATIVO">INATIVO</option>
                        <option value="NATIVO">NATIVO</option>
                    </select>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary shadow mr-3"><i class="fas fa-save mr-2"></i>Salvar</button>
        </form>
    </div>
</div>
@endsection