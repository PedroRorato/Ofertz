@extends('dashboard.layout')
@section('title') Eventos @endsection
@section('menu') #eventos-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/eventos">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/eventos/create">Adicionar</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#data').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
        //Select erro cadastro
        $("#cidade option[value={!! old('cidade') ? old('cidade') : '1' !!}]").attr('selected', 'selected');
    });
</script>
<a href="/admin/eventos" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/eventos" enctype="multipart/form-data" onsubmit="progressBar()">
            @csrf
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <img class="col mx-0 p-0 foto-dash" id="foto2" src="{{ asset('img/img-fundo.png') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="foto_perfil">Banner do Evento</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpg, .jpeg, .png" class="custom-file-input{{ $errors->has('foto') ? ' is-invalid' : '' }}" id="fotoInput" name="foto" onchange="loadImg(event, 'foto2', 'fotoNome2')" required>
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
                        <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome da evento..." value="{{ old('nome') }}" required>
                        @if ($errors->has('nome'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nome') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="data">Data</label>
                        <input type="text" class="form-control{{ Session::has('data') ? ' is-invalid' : '' }}" id="data" name="data" value="{{ old('data') }}" required>
                        @if (Session::has('data'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ Session::get('data') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="time">Horário</label>
                        <input type="time" class="form-control{{ $errors->has('time') ? ' is-invalid' : '' }}" id="time" name="time" placeholder="Digite o nome da empresa..." value="{{ old('time') }}" required>
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
                        <textarea class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" id="exampleFormControlTextarea1" rows="3" id="descricao" name="descricao" placeholder="Descreva o evento...">{{ old('descricao') }}</textarea>
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
                                <input type="checkbox" class="custom-control-input {{ $errors->has('categorias') ? 'is-invalid' : '' }}" id="check{{ $categoria->id }}" name="categorias[]" value="{{ $categoria->id }}" {{ (is_array(old('categorias')) and in_array($categoria->id, old('categorias'))) ? ' checked' : '' }}> 
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
            </div>
            <hr>
            <div class="dash-botoes">
                <button type="submit" class="btn btn-primary shadow"><i class="fas fa-plus mr-2"></i>Adicionar</button>
            </div>
            <div class="dash-spinner">
                <div class="progress">
                    <div id="progresso" class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: 0%" ></div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

