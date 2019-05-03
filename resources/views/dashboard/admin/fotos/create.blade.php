@extends('dashboard.layout')
@section('title') Fotos @endsection
@section('menu') #fotos-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/fotos">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/fotos/create">Adicionar</a></li>
@endsection
@section('content')
<a href="/admin/fotos" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/fotos" enctype="multipart/form-data">
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
                        <label for="foto_perfil">Foto</label>
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
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome da evento..." value="{{ old('nome') }}" required>
                @if ($errors->has('nome'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('nome') }}</strong>
                    </span>
                @endif
            </div>
            <hr>
            <button type="submit" class="btn btn-primary shadow"><i class="fas fa-plus mr-2"></i>Adicionar</button>
        </form>
    </div>
</div>
@endsection

