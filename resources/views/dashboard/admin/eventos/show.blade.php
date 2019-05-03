@extends('dashboard.layout')
@section('title') Eventos @endsection
@section('menu') #eventos-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/eventos">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/eventos/{{ $evento->id }}">Painel da Empresa</a></li>
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
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/eventos/{{ $evento->id }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <img class="col mx-0 p-0 foto-dash" id="foto2" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $evento->foto }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="foto_perfil">Banner do Evento</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpg, .jpeg, .png" class="custom-file-input{{ $errors->has('foto') ? ' is-invalid' : '' }}" id="fotoInput" name="foto" onchange="loadImg(event, 'foto2', 'fotoNome2')">
                            <label class="custom-file-label" id="fotoNome2" for="validatedCustomFile">Buscar...(jpeg, jpg, png)</label>
                            <div id="alert_perfil"></div>
                        </div>
                        @if ($errors->has('foto'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('foto') }}</strong>
                            </span>
                        @else
                            <small class="form-text text-muted">Formato quadrado</small>
                        @endif
                    </div>
                </div>
            </div>
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
            <button type="submit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-save mr-2"></i>Salvar</button>
            @if($evento->status != 'EXCLUIDO')
            <button type="button" class="btn btn-danger shadow mt-3 mt-sm-0" data-toggle="modal" data-target="#modalDelete">
                <i class="fas fa-trash-alt mr-2"></i>Excluir
            </button>
            @endif
        </form>
    </div>
</div>


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