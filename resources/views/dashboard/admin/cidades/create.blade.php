@extends('dashboard.layout')
@section('title') Cidades @endsection
@section('menu') #cidades-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/cidades">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/cidades/create">Adicionar</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        $("#uf").children('[value="{!! old('uf') !!}"]').attr('selected', true);
    });
</script>
<a href="/admin/cidades" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/cidades" enctype="multipart/form-data" onsubmit="progressBar()">
            @csrf
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group foto-mobile-dash">
                        <img class="col mx-0 p-0 foto-dash" id="foto2" src="{{ asset('img/img-fundo.png') }}">
                    </div>
                    <div class="form-group">
                        <label for="foto_perfil">Foto Desktop</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpg, .jpeg, .png" class="custom-file-input{{ $errors->has('foto_desktop') ? ' is-invalid' : '' }}" id="fotoInput" name="foto_desktop" onchange="loadImg(event, 'foto2', 'fotoNome2')" required>
                            <label class="custom-file-label" id="fotoNome2" for="validatedCustomFile">Buscar...(jpeg, jpg, png)</label>
                            <div id="alert_perfil"></div>
                        </div>
                        @if ($errors->has('foto_desktop'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('foto_desktop') }}</strong>
                            </span>
                        @else
                            <small class="form-text text-muted">Largura mínima: 1500px | Altura mínima: 1000px</small>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group foto-mobile-dash">
                        <img class="col mx-0 p-0 foto-dash" id="foto1" src="{{ asset('img/img-fundo.png') }}">
                    </div>
                    <div class="form-group">
                        <label for="foto_perfil">Foto Mobile</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpg, .jpeg, .png" class="custom-file-input{{ $errors->has('foto_mobile') ? ' is-invalid' : '' }}" id="fotoInput1" name="foto_mobile" onchange="loadImg(event, 'foto1', 'fotoNome1')" required>
                            <label class="custom-file-label" id="fotoNome1" for="validatedCustomFile">Buscar...(jpeg, jpg, png)</label>
                            <div id="alert_perfil"></div>
                        </div>
                        @if ($errors->has('foto_mobile'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('foto_mobile') }}</strong>
                            </span>
                        @else
                            <small class="form-text text-muted">Largura mínima: 500px | Altura mínima: 800px</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="nome">Cidade</label>
                        <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome..." value="{{ old('nome') }}" required>
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

