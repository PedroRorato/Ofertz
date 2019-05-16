@extends('dashboard.admin.layout')
@section('title') Usuarios @endsection
@section('menu') #usuarios-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/usuarios">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/usuarios/{{ $usuario->id }}">Painel da Usuario</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $('#cnpj').mask('00.000.000/0000-00', {placeholder: "00.000.000/0001-00"});
        $('#nascimento').mask('00/00/0000', {placeholder: "dd/mm/aaaa"});
        $('#telefone').mask('(00)00000-0000', {placeholder: "(00)00000-0000"});
        //Select erro cadastro
        $("#cidade").children('[value="{{ $usuario->cidade_id }}"]').attr('selected', true);
        $("#genero").children('[value="{{ $usuario->genero }}"]').attr('selected', true);
        $("#status").children('[value="{{ $usuario->status }}"]').attr('selected', true);

        @if ($errors->has('password'))
            $('#modalSenha').modal('show');
        @endif
    });
</script>
<a href="/admin/usuarios" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<form method="POST" action="/admin/usuarios/{{ $usuario->id }}" enctype="multipart/form-data" onsubmit="progressBar()">
<div class="card shadow">
    <div class="card-body">
        @csrf
        @method('PATCH')
        <small class="form-text text-muted">*Campos não obrigatórios</small>
        <br/>
        <input type="hidden" id="points" name="points">
        <div class="row">
            <div class="col">
                <div class="card foto-container {{ ($errors->has('foto') || $errors->has('points')) ? 'border-danger text-danger' : '' }}" data-toggle="modal" data-target="#editorImagem">
                    <img id="result" class="img-fluid" src="{{ isset($usuario->foto) ? 'https://s3.us-east-1.amazonaws.com/bergard-teste/'.$usuario->foto : asset('img/user.png') }}">
                    <div class="card-footer text-center">
                        Escolher foto
                    </div>
                </div>
                @if ($errors->has('foto'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('foto') }}</strong>
                    </span>
                @elseif ($errors->has('points'))
                    <span class="invalid-feedback" role="alert">
                        <strong>Salve a imagem antes de concluir o formulário.</strong>
                    </span>
                @endif
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" id="nome" name="nome" placeholder="Digite o nome do empresário..." value="{{ $usuario->nome }}" required>
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
                    <input type="text" class="form-control{{ $errors->has('sobrenome') ? ' is-invalid' : '' }}" id="sobrenome" name="sobrenome" placeholder="Digite o sobrenome do empresário..." value="{{ $usuario->sobrenome }}" required>
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
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Digite o email..." value="{{ $usuario->email }}" required>
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
                    <input type="text" class="form-control{{ $errors->has('data') ? ' is-invalid' : '' }}" id="nascimento" name="nascimento" value="{{ $data }}">
                    @if (Session::get('data'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ Session::get('data') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
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
            <div class="col-lg-6">
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
            <button type="button" class="btn btn-warning shadow mr-3 mt-3 mt-sm-0" data-toggle="modal" data-target="#modalSenha">
            <i class="fas fa-key mr-2"></i>Alterar senha
        </button>
            @if($usuario->status != 'EXCLUIDO')
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
    </div>
</div>
<!-- Modal Foto -->
<div class="modal fade" id="editorImagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Escolher foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0">
                <div id="inputEditor" class="custom-file">
                    <input type="file" accept=".jpg, .jpeg, .png" class="custom-file-input" id="fotoInput" name="foto">
                    <label class="custom-file-label" for="fotoInput">Buscar imagem...(jpeg, jpg, png)</label>
                </div>
                <div class="dash-spinner">
                    <h4 class="text-center mb-0"><i class="fas fa-sync-alt fa-spin mr-2"></i>Aguarde...</h4>
                </div>
                <button id="resetEditor" type="button" class="btn btn-secondary btn-block reset">Trocar imagem</button>
                <div class="editor-container pt-1">
                    <p id="aviso" class="form-text text-muted text-center pb-2 mb-0 d-none">Arraste para reposicionar</p>
                    <div id="tamanho"></div>
                    <div id="ttt">
                        <img id="image">
                    </div>
                    <div class="row zoomRow">
                        <div class="col">
                            <i class="pl-1 fas fa-search-minus zoomOut zoom"></i>
                        </div>
                        <div class="col text-right">
                            <i class="pr-2 fas fa-search-plus zoomIn zoom"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button id="cortar" type="button" class="btn btn-primary" disabled><i class="fas fa-crop-alt mr-2"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>
</form>
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
            <form method="POST" action="/admin/usuarios/{{ $usuario->id }}">
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
                <h5 class="modal-title">Excluir USUARIO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="/admin/usuarios/{{ $usuario->id }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>Tem certeza que deseja excluir o USUARIO?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger shadow"><i class="fas fa-trash-alt mr-2"></i>Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Script -->
<script type="text/javascript">
    //
    $("#fotoInput").change(function() {
        if (this.files[0].size < '10000000') {
            $('#inputEditor').hide();
            $( ".dash-spinner" ).show();
            readURL(this, 'square', 270, 270);
            $( ".zoomRow" ).fadeTo(0, 1);
        } else{
            $("#fotoInput").val('');
            $('#tamanho').html('<div class="alert alert-danger mb-0 mt-2 text-center" role="alert">Tamanho máximo 10MB!<button type="button" class="close" onclick="hiddeAlert()"><span>&times;</span></button></h4></div>');
        }
    });
    //
    $('.reset').on('click', function() {
        $('#result').attr('src', "{{ asset('img/img-fundo.png') }}");
    });
</script>
@endsection