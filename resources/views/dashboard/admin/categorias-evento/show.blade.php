@extends('dashboard.admin.layout')
@section('title') Categorias Evento @endsection
@section('menu') #categorias-evento-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/categorias-evento">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/categorias-evento/{{ $categoria->id }}">Painel da Categoria</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        $("#status").children('[value="{{ $categoria->status }}"]').attr('selected', true);
        @if ($errors->has('password'))
            $('#modalSenha').modal('show');
        @endif
    });
</script>
<a href="/admin/categorias-evento" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/categorias-evento/{{ $categoria->id }}" enctype="multipart/form-data" onsubmit="spinner()">
            @csrf
            @method('PATCH')
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="form-group col-lg-3">
                    <img class="col mx-0 p-0" id="foto" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $categoria->foto }}" alt="your image">
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="foto_perfil">Foto</label>
                        <div class="custom-file">
                            <input type="file" accept=".svg" class="custom-file-input" id="fotoInput" name="foto" onchange="loadImg(event, 'foto', 'fotoNome')">
                            <label class="custom-file-label" id="fotoNome" for="validatedCustomFile">Buscar imagem...(svg)</label>
                            <div id="alert_perfil"></div>
                        </div>
                        @if ($errors->has('foto'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('foto') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome..." value="{{ $categoria->nome }}" required>
                        @if ($errors->has('nome'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nome') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição*</label>
                        <input type="text" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" id="descricao" name="descricao" placeholder="Descreva a categoria..." value="{{ $categoria->descricao }}">
                        @if ($errors->has('descricao'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('descricao') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="custom-select" id="status" name="status">
                            <option value="ATIVO">ATIVO</option>
                            <option value="EXCLUIDO">EXCLUIDO</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="dash-botoes">
                <button type="submit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-save mr-2"></i>Salvar</button>
                @if($categoria->status != 'EXCLUIDO')
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

<!-- Modal DELETE -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/categorias-evento/{{ $categoria->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir a Categoria?</h5>
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