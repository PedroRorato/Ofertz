@extends('dashboard.admin.layout')
@section('title') Ofertas @endsection
@section('menu') #ofertas-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/ofertas">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/ofertas/{{ $oferta->id }}">Painel da Oferta</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#data').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
        $('#preco').mask('#.##0,00', {reverse: true, placeholder: "0,00"});
        //Select erro cadastro
        $("#status").children('[value="{{ $oferta->status }}"]').attr('selected', true);
    });
</script>
<a href="/admin/ofertas" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
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
        @if ($editar)
        <form method="POST" action="/admin/ofertas/{{ $oferta->id }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="produto_id" value="{{ $produto->id }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="preco">Preço</label>
                        <input type="text" class="form-control{{ $errors->has('preco') ? ' is-invalid' : '' }}" id="preco" name="preco" value="{{ $oferta->preco }}" required>
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
                        <input type="text" class="form-control{{ Session::has('data') ? ' is-invalid' : '' }}" id="data" name="data" value="{{ $data }}" required>
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
                        <input type="time" class="form-control{{ $errors->has('time') ? ' is-invalid' : '' }}" id="time" name="time" placeholder="Digite o nome da empresa..." value="{{ $tempo }}" required>
                        <small class="form-text text-muted">hh:mm AM/PM</small>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="observacao">Observação*</label>
                        <textarea class="form-control{{ $errors->has('observacao') ? ' is-invalid' : '' }}" id="exampleFormControlTextarea1" rows="3" id="observacao" name="observacao" placeholder="Observação...">{{ $oferta->observacao }}</textarea>
                        @if ($errors->has('observacao'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('observacao') }}</strong>
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
                @if($oferta->status != 'EXCLUIDO')
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

        </form>
        @else
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="preco">Preço</label>
                    <h5 id="preco">{{ $oferta->preco }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="data">Validade: Data</label>
                    <h5 id="data">{{ $data }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="time">Validade: Horário</label>
                    <h5 id="time">{{ $tempo }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="status">Status</label>
                    <h5>{{ $oferta->status }}</h5>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="observacao">Observação</label>
                    <h5>{{ !empty($oferta->observacao) ? $oferta->observacao : 'Não há observação' }}</h5>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>


<!-- Modal DELETE -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/ofertas/{{ $oferta->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir o Empresa?</h5>
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