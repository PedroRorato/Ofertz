@extends('dashboard.layout')
@section('title') Empresas @endsection
@section('menu') #empresas-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/empresas">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/empresas/{{ $empresa->id }}">Painel da Empresa</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#cnpj').mask('00.000.000/0000-00', {placeholder: "00.000.000/0001-00"});
        $('#nascimento').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
        $('#telefone').mask('(00)00000-0000', {placeholder: "(00)00000-0000"});
        //Select erro cadastro
        $("#cidade").children('[value="{{ $empresa->cidade_id }}"]').attr('selected', true);
        $("#genero").children('[value="{{ $empresa->genero }}"]').attr('selected', true);
        $("#status").children('[value="{{ $empresa->status }}"]').attr('selected', true);

        @if ($errors->has('password'))
            $('#modalSenha').modal('show');
        @endif
    });
</script>
<a href="/admin/empresas" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="/admin/empresas/{{ $empresa->id }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <h4 class="">Dados da Empresa</h4>
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <img class="col mx-0 p-0 foto-dash" id="foto2" src="https://s3.us-east-1.amazonaws.com/bergard-teste/{{ $empresa->foto }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="foto_perfil">Logo da Empresa</label>
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
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <input type="text" class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}" id="empresa" name="empresa" placeholder="Digite o nome da empresa..." value="{{ $empresa->empresa }}" required>
                        @if ($errors->has('empresa'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('empresa') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="cnpj">CNPJ*</label>
                        <input type="text" class="form-control{{ $errors->has('cnpj') ? ' is-invalid' : '' }}" id="cnpj" name="cnpj" placeholder="Digite o nome da empresa..." value="{{ $empresa->cnpj }}">
                        @if ($errors->has('cnpj'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('cnpj') }}</strong>
                            </span>
                        @endif
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
                        <textarea class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" id="exampleFormControlTextarea1" rows="3" id="descricao" name="descricao" placeholder="Descreva a empresa...">{{ $empresa->descricao }}</textarea>
                        @if ($errors->has('descricao'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('descricao') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <h4 class="">Dados do Empresário</h4>
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome do empresário..." value="{{ $empresa->nome }}" required>
                        @if ($errors->has('nome'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nome') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" class="form-control{{ $errors->has('sobrenome') ? ' is-invalid' : '' }}" id="sobrenome" name="sobrenome" placeholder="Digite o sobrenome do empresário..." value="{{ $empresa->sobrenome }}" required>
                        @if ($errors->has('sobrenome'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('sobrenome') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Digite o email..." value="{{ $empresa->email }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="genero">Gênero</label>
                        <select class="custom-select{{ $errors->has('genero') ? ' is-invalid' : '' }}" id="genero" name="genero" required>
                            <option value="female">Feminino</option>
                            <option value="male">Masculino</option>
                            <option value="other">Outro</option>
                        </select>
                        @if ($errors->has('genero'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('genero') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento*</label>
                        <input type="text" class="form-control{{ $errors->has('nascimento') ? ' is-invalid' : '' }}" id="nascimento" name="nascimento" value="{{ $empresa->nascimento }}">
                        @if ($errors->has('nascimento'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nascimento') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="telefone">Celular</label>
                        <input type="text" class="form-control{{ $errors->has('telefone') ? ' is-invalid' : '' }}" id="telefone" name="telefone" value="{{ $empresa->telefone }}" required>
                        @if ($errors->has('telefone'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('telefone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="custom-select" id="status" name="status">
                            <option value="ATIVO">ATIVO</option>
                            <option value="EXCLUIDO">EXCLUIDO</option>
                            <option value="INATIVO">INATIVO</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary shadow mr-3 mt-3 mt-sm-0"><i class="fas fa-save mr-2"></i>Salvar</button>
            <button type="button" class="btn btn-warning shadow mr-3 mt-3 mt-sm-0" data-toggle="modal" data-target="#modalSenha">
                <i class="fas fa-key mr-2"></i>Alterar senha
            </button>
            @if($empresa->status != 'EXCLUIDO')
            <button type="button" class="btn btn-danger shadow mt-3 mt-sm-0" data-toggle="modal" data-target="#modalDelete">
                <i class="fas fa-trash-alt mr-2"></i>Excluir
            </button>
            @endif
        </form>
    </div>
</div>


<!-- Modal SENHA -->
<div class="modal fade" id="modalSenha" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/empresas/{{ $empresa->id }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Digite a senha..." required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Confirmar senha</label>
                        <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Confirme a senha..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-warning shadow"><i class="fas fa-key mr-2"></i>Alterar senha</button>
                </div>
            </form>
        </div>
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
            <form method="POST" action="/admin/empresas/{{ $empresa->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir a Empresa?</h5>
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