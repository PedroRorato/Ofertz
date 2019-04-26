@extends('dashboard.layout')
@section('title') Categorias Produto @endsection
@section('menu') #categorias-produto-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/categorias-produto">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/categorias-produto/create">Adicionar</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).on('change', '#fotoInput', function () {
         if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#foto').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        }
        document.getElementById('fotoNome').innerHTML = event.target.files[0].name;
    });
</script>
<a href="/admin/categorias-produto" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/categorias-produto" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-6 col-lg-3">
                    <img class="col mx-0 p-0" id="foto" src="{{ asset('img/img-fundo.png') }}">
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="foto_perfil">Foto</label>
                        <div class="custom-file">
                            <input type="file" accept=".svg" class="custom-file-input" id="fotoInput" name="foto" required autofocus>
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
                        <label for="descricao">Descrição</label>
                        <input type="text" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" id="descricao" name="descricao" placeholder="Descreva a categoria..." value="{{ old('descricao') }}" required>
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

