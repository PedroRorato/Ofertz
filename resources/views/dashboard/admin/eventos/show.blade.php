@extends('dashboard.admin.layout')
@section('title') Eventos @endsection
@section('menu') #eventos-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/eventos">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/eventos/{{ $evento->id }}">Painel do Evento</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#data').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
        //Select erro cadastro
        $("#cidade").children('[value="{{ $evento->cidade_id }}"]').attr('selected', true);
        $("#status").children('[value="{{ $evento->status }}"]').attr('selected', true);
    });
</script>
<a href="/admin/eventos" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
@if ($editar)
<form method="POST" action="/admin/eventos/{{ $evento->id }}" enctype="multipart/form-data"  onsubmit="progressBar()">
<div class="card shadow">
    <div class="card-body">
        @csrf
        @method('PATCH')
        <small class="form-text text-muted">*Campos não obrigatórios</small>
        <br/>
        <input type="hidden" id="points" name="points">
        <div class="row">
            <div class="col">
                <div class="card foto-container {{ ($errors->has('foto') || $errors->has('points')) ? 'border-danger text-danger' : '' }}" data-toggle="modal" data-target="#editorImagem">
                    <img id="result" class="img-fluid" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $evento->foto }}">
                    <div class="card-footer text-center">
                        Escolher foto
                    </div>
                </div>
                @if ($errors->has('foto'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('foto') }}</strong>
                    </span>
                @elseif ($errors->has('points'))
                    <span class="invalid-feedback" role="alert">
                        <strong>Salve a imagem antes de concluir o formulário.</strong>
                    </span>
                @endif
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome da evento..." value="{{ $evento->nome }}" required>
                    @if ($errors->has('nome'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('nome') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="data">Data</label>
                    <input type="text" class="form-control{{ Session::has('data') ? ' is-invalid' : '' }}" id="data" name="data" value="{{ $data }}" required>
                    @if (Session::has('data'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ Session::get('data') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="time">Horário</label>
                    <input type="time" class="form-control{{ $errors->has('time') ? ' is-invalid' : '' }}" id="time" name="time" value="{{ $tempo }}" required>
                    <small class="form-text text-muted">hh:mm AM/PM</small>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
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
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="descricao">Descrição*</label>
                    <textarea class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" id="exampleFormControlTextarea1" rows="3" id="descricao" name="descricao" placeholder="Descreva o evento...">{{ $evento->descricao }}</textarea>
                    @if ($errors->has('descricao'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('descricao') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-12">
                <h5 class="py-2">Categorias</h5>
                <div class="form-group">
                    @foreach($categorias as $categoria)
                        <div class="custom-control custom-checkbox custom-control-inline pb-3 mr-4">
                            <input type="checkbox" class="custom-control-input {{ $errors->has('categorias') ? 'is-invalid' : '' }}" id="check{{ $categoria->id }}" name="categorias[]" value="{{ $categoria->id }}" {{ array_search($categoria->id, $pertences) ? ' checked' : '' }}> 
                            <label class="custom-control-label" for="check{{ $categoria->id }}">{{ $categoria->nome }}</label>
                        </div>
                    @endforeach
                    @if ($errors->has('categorias'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('categorias') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-12">
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
            @if($evento->status != 'EXCLUIDO')
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
<!-- Modal Foto -->
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
<!-- Script -->
<script type="text/javascript">
    //
    $("#fotoInput").change(function() {
        if (this.files[0].size < '10000000') {
            $('#inputEditor').hide();
            $( ".dash-spinner" ).show();
            readURL(this, 'square', 270, 270);
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
@else
<div class="card shadow">
    <div class="card-body">
        <small class="form-text text-muted">*Campos não obrigatórios</small>
        <br/>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group foto-container">
                    <img class="col mx-0 p-0 img-fluid rounded" id="foto2" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $evento->foto }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Nome</label>
                    <h5>{{ $evento->nome }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Data</label>
                    <h5 id="data">{{ $data }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Horário</label>
                    <h5 id="time">{{ $tempo }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Cidade</label>
                    <h5 id="time">{{ $evento->cidade->nome.'-'.$evento->cidade->uf }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Status</label>
                    <h5 id="time">{{ $evento->status }}</h5>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="descricao">Descrição*</label>
                    <h5>{{ !empty($evento->descricao) ? $evento->descricao : 'Não há descrição' }}</h5>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <h5>Categorias</h5>
                    <h4 class="">
                    @foreach($evento->categorias as $categoria)
                        <span class="badge badge-secondary mr-2 mb-2">{{ $categoria->nome }}</span>
                    @endforeach
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal DELETE -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/eventos/{{ $evento->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir o Evento?</h5>
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