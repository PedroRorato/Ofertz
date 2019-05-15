@extends('dashboard.layout')
@section('title') Fotos @endsection
@section('menu') #fotos-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/fotos">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/fotos/{{ $foto->id }}">Painel da Empresa</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $("#status").children('[value="{{ $foto->status }}"]').attr('selected', true);
    });
</script>
<a href="/admin/fotos" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<form method="POST" action="/admin/fotos/{{ $foto->id }}" enctype="multipart/form-data" onsubmit="progressBar()">
<div class="card shadow">
    <div class="card-body">
        
        @csrf
        @method('PATCH')
        <small class="form-text text-muted">*Campos não obrigatórios</small>
        <br/>
        

        <input type="hidden" id="points" name="points">
        <div class="row">
            <div class="col-md-6">
                <div class="card foto-container {{ $errors->has('foto') ? 'border-danger text-danger' : '' }}" data-toggle="modal" data-target="#editorImagem">
                    <img id="result" class="foto-dash" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $foto->url }}">
                    <div class="card-footer text-center">
                        Escolher foto
                    </div>
                </div>
                @if ($errors->has('foto'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('foto') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <br>
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome da evento..." value="{{ $foto->nome }}" required>
            @if ($errors->has('nome'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('nome') }}</strong>
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
        <hr>
        <div class="dash-botoes">
            <button type="submit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-save mr-2"></i>Salvar</button>
            @if($foto->status != 'EXCLUIDO')
            <button type="button" class="btn btn-danger shadow mt-3 mt-sm-0" data-toggle="modal" data-target="#modalDelete">
                <i class="fas fa-trash-alt mr-2"></i>Excluir
            </button>
            @endif
        </div>
        <div class="dash-spinner">
            <div class="progress">
                <div id="progresso" class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: 0%" ></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Editor -->
<div class="modal fade" id="editorImagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Escolher foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0">
                <div id="inputEditor" class="custom-file">
                    <input type="file" accept=".jpg, .jpeg, .png" class="custom-file-input" id="fotoInput" name="foto">
                    <label class="custom-file-label" for="fotoInput">Buscar imagem...(jpeg, jpg, png)</label>
                </div>
                <div class="dash-spinner">
                    <h4 class="text-center mb-0"><i class="fas fa-sync-alt fa-spin mr-2"></i>Aguarde...</h4>
                </div>
                <button id="resetEditor" type="button" class="btn btn-secondary btn-block reset">Trocar imagem</button>
                <div class="editor-container pt-1">
                    <p id="aviso" class="form-text text-muted text-center pb-2 mb-0 d-none">Arraste para reposicionar</p>
                    <div id="tamanho"></div>
                    <div id="ttt">
                        <img id="image">
                    </div>
                    <div class="row zoomRow">
                        <div class="col">
                            <i class="pl-1 fas fa-search-minus zoomOut zoom"></i>
                        </div>
                        <div class="col text-right">
                            <i class="pr-2 fas fa-search-plus zoomIn zoom"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button id="cortar" type="button" class="btn btn-primary" disabled><i class="fas fa-crop-alt mr-2"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>
</form>

<!-- Modal DELETE -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/fotos/{{ $foto->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir a Foto?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger shadow"><i class="fas fa-trash-alt mr-2"></i>Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script -->
<script type="text/javascript">
    //
    $("#fotoInput").change(function() {
        if (this.files[0].size < '10000000') {
            $('#inputEditor').hide();
            $( ".dash-spinner" ).show();
            readURL(this, 'square');
            $( ".zoomRow" ).fadeTo(0, 1);
        } else{
            $("#fotoInput").val('');
            $('#tamanho').html('<div class="alert alert-danger mb-0 mt-2 text-center" role="alert">Tamanho máximo 10MB!<button type="button" class="close" onclick="hiddeAlert()"><span>&times;</span></button></h4></div>');
        }
    });
    //
    $('.reset').on('click', function() {
        $('#result').attr('src', "{{ asset('img/img-fundo.png') }}");
    });
</script>
@endsection