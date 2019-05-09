@extends('dashboard.layout')
@section('title') Ofertas @endsection
@section('menu') #ofertas-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/eventos">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/ofertas/choose">Escolher Produto</a></li>
<li class="breadcrumb-item"><a href="/admin/ofertas/produto/{{ $produto->id }}/create">Adicionar Oferta</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#data').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
        $('#preco').mask('#.##0,00', {reverse: true, placeholder: "0,00"});
    });
</script>
<a href="/admin/ofertas/choose" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/ofertas">
            @csrf
            <input type="hidden" name="produto_id" value="{{ $produto->id }}">
            <input type="hidden" name="cidade" value="{{ $produto->cidade->id }}">
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <img class="col mx-0 p-0 foto-dash" id="foto2" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $produto->foto }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group ">
                        <label for="nome" class="mb-1">Nome</label>
                        <h5>{{ $produto->nome }}</h5>
                    </div>
                    <div class="form-group">
                        <label for="data" class="mb-1">Cidade</label>
                        <h5>{{ $produto->cidade->nome . '-' . $produto->cidade->uf }}</h5>
                    </div>
                    <div class="form-group">
                        <label for="descricao" class="mb-1">Descrição</label>
                        <h5>{{ !empty($produto->descricao) ? $produto->descricao : 'Não há descrição' }}</h5>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Categorias</label>
                        <h4 class="">
                        @foreach($produto->categorias as $categoria)
                            <span class="badge badge-secondary mr-2 mb-2">{{ $categoria->nome }}</span>
                        @endforeach
                        </h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="preco">Preço</label>
                        <input type="text" class="form-control{{ $errors->has('preco') ? ' is-invalid' : '' }}" id="preco" name="preco" value="{{ old('data') }}" required>
                        @if ($errors->has('preco'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('preco') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="data">Validade: Data</label>
                        <input type="text" class="form-control{{ Session::has('data') ? ' is-invalid' : '' }}" id="data" name="data" value="{{ old('data') }}" required>
                        @if (Session::has('data'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ Session::get('data') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="time">Validade: Horário</label>
                        <input type="time" class="form-control{{ $errors->has('time') ? ' is-invalid' : '' }}" id="time" name="time" placeholder="Digite o nome da empresa..." value="{{ old('time') }}" required>
                        <small class="form-text text-muted">hh:mm AM/PM</small>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="observacao">Observação*</label>
                        <textarea class="form-control{{ $errors->has('observacao') ? ' is-invalid' : '' }}" id="exampleFormControlTextarea1" rows="3" id="observacao" name="observacao" placeholder="Observação...">{{ old('observacao') }}</textarea>
                        @if ($errors->has('observacao'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('observacao') }}</strong>
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

