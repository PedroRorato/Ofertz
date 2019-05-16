@extends('dashboard.admin.layout')
@section('title') Cidades @endsection
@section('menu') #cidades-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/cidades">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/cidades/{{ $cidade->id }}">Painel da Categoria</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        $("#uf").children('[value="{!! $cidade->uf !!}"]').attr('selected', true);
    });
</script>
<script type="text/javascript">
    $(window).on('load', function() {
        $("#status").children('[value="{{ $cidade->status }}"]').attr('selected', true);
        @if ($errors->has('password'))
            $('#modalSenha').modal('show');
        @endif
    });
</script>
<a href="/admin/cidades" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/cidades/{{ $cidade->id }}" enctype="multipart/form-data" onsubmit="progressBar()">
            @csrf
            @method('PATCH')
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="col-lg-8 pb-4">
                    <div class="card foto-container1 {{ $errors->has('foto_desktop') ? 'border-danger text-danger' : '' }}">
                        <img id="result" class="img-fluid foto-cidade mx-auto" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $cidade->foto_desktop }}">
                        <div class="card-footer text-center">
                            Escolher foto Desktop
                        </div>
                        <input type="file" accept=".jpg, .jpeg, .png" class="f-input-cidade" id="fotoInput2" name="foto_desktop" onchange="loadImg(event, 'result', 'fotoNome2')">
                    </div>
                    @if ($errors->has('foto_desktop'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('foto_desktop') }}</strong>
                        </span>
                    @else
                        <small class="form-text text-muted pt-1">Largura mínima: 1500px | Altura mínima: 1000px</small>
                    @endif
                    
                </div>
                <div class="col-lg-4 pb-4">
                   <div class="card  foto-container2 {{ $errors->has('foto_mobile') ? 'border-danger text-danger' : '' }}">
                        <img id="result2" class="img-fluid foto-cidade mx-auto" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $cidade->foto_mobile }}">
                        <div class="card-footer text-center">
                            Escolher foto Mobile
                        </div>
                        <input type="file" accept=".jpg, .jpeg, .png" class="f-input-cidade" id="fotoInput2" name="foto_mobile" onchange="loadImg(event, 'result2', 'fotoNome2')">
                    </div>
                    @if ($errors->has('foto_mobile'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('foto_mobile') }}</strong>
                        </span>
                    @else
                        <small class="form-text text-muted pt-1">Largura mínima: 500px | Altura mínima: 800px</small>
                    @endif
                </div>
            </div>
        <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="nome">Cidade</label>
                        <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome..." value="{{ $cidade->nome }}" required>
                        @if ($errors->has('nome'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nome') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="uf">Estado</label>
                        <select class="custom-select" id="uf" name="uf">
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                        @if ($errors->has('uf'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('uf') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="descricao">Descrição*</label>
                        <input type="text" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" id="descricao" name="descricao" placeholder="Descreva a categoria..." value="{{ $cidade->descricao }}">
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
            <div class="dash-botoes">
                <button type="submit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-save mr-2"></i>Salvar</button>
                @if($cidade->status != 'EXCLUIDO')
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
    </div>
</div>

<!-- Modal DELETE -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Cidade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/cidades/{{ $cidade->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir a Cidade?</h5>
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