@extends('dashboard.layout')
@section('title') Categorias Evento @endsection
@section('menu') #categorias-evento-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/categorias-evento">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/categorias-evento/create">Adicionar</a></li>
@endsection
@section('content')
<a href="/admin/categorias-evento" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/categorias-evento" enctype="multipart/form-data">
            @csrf
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="form-group col-lg-3">
                    <img class="col mx-0 p-0" id="foto" src="{{ asset('img/img-fundo.png') }}">
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="foto_perfil">Foto</label>
                        <div class="custom-file">
                            <input type="file" accept=".svg" class="custom-file-input" id="fotoInput" name="foto" onchange="loadImg(event, 'foto', 'fotoNome')" required autofocus>
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
                        <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome..." value="{{ old('nome') }}" required>
                        @if ($errors->has('nome'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nome') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição*</label>
                        <input type="text" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" id="descricao" name="descricao" placeholder="Descreva a categoria..." value="{{ old('descricao') }}">
                        @if ($errors->has('descricao'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('descricao') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary shadow"><i class="fas fa-plus mr-2"></i>Adicionar</button>
        </form>
    </div>
</div>
@endsection

