@extends('dashboard.layout')
@section('title') Fotos @endsection
@section('menu') #fotos-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/fotos">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/fotos/create">Adicionar</a></li>
@endsection
@section('content')
<a href="/admin/fotos" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<form method="POST" action="/admin/fotos" enctype="multipart/form-data" onsubmit="progressBar()">
<div class="card shadow">
    <div class="card-body">

        <form method="POST" action="/admin/fotos" enctype="multipart/form-data" onsubmit="progressBar()">
            @csrf
            <small class="form-text text-muted">*Campos não obrigatórios</small>
            <br/>
            <input type="hidden" id="points" name="points">
            <input type="hidden" id="zoom" name="zoom">
            <div class="row">
                <div class="col-md-6">
                    <div class="card foto-container {{ $errors->has('foto') ? 'border-danger text-danger' : '' }}" data-toggle="modal" data-target="#editorImagem">
                        <img id="result" class="foto-dash" src="{{ asset('img/img-fundo.png') }}">
                        <div class="card-footer text-center">
                            Escolher foto
                        </div>
                    </div>
                    @if ($errors->has('foto'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('foto') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12">
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
        
    </div>
</div>

<!-- Modal data-dismiss="modal" -->
<div class="modal fade" id="editorImagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Editor de imagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0">
                <div id="inputEditor" class="custom-file">
                    <input type="file" accept=".jpg, .jpeg, .png" class="custom-file-input{{ $errors->has('foto') ? ' is-invalid' : '' }}" id="fotoInput" name="foto">
                    <label class="custom-file-label" id="fotoNome2" for="validatedCustomFile">Buscar imagem...(jpeg, jpg, png)</label>
                </div>
                <button id="resetEditor" type="button" class="btn btn-secondary btn-block reset" style="display: none">Trocar imagem</button>
                <div class="editor-container pt-1">
                    <p id="aviso" class="form-text text-muted text-center pb-2 mb-0 d-none" data-toggle="tooltip" data-placement="bottom" title="Utilize o mouse ou os dedos para dar zoom e reposicionar a imagem">
                        <i class="fas fa-info-circle mr-2" onclick="limpar()"></i>Aproxime ou arraste para reposicionar
                    </p>
                    <div id="ttt">
                        <img id="image">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button id="salvar" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-save mr-2"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>
</form>
<script>

function readURL(input) {
    console.log('foi1');
    if (input.files && input.files[0]) { 
        console.log('foi2');       
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#image').attr('src', e.target.result);
          var resize = new Croppie($('#image')[0], {
            viewport: { width: 280, height: 280 },
            boundary: { width: 280, height: 280 },
            showZoomer: false,
            enableOrientation: true
          });
          $('#inputEditor').css('display', 'none');
          $('#resetEditor').css('display', 'block');
          $('#aviso').removeClass('d-none');
          $('#salvar').on('click', function() {
            try {
                console.log(resize.get().points);
                console.log(resize.get().zoom);
                $("#points").val(resize.get().points);
                $("#zoom").val(resize.get().zoom);
                resize.result('base64').then(function(dataImg) {
                    var data = [{ image: dataImg }, { name: 'myimgage.jpg' }];
                    $('#result').attr('src', dataImg);
                });
            }
            catch(err) {
                console.log('err.message resize');
            }
            
          });
          $('.reset').on('click', function() {
            try {
                $("#fotoInput").val('');
                resize.destroy();
                $('#result').attr('src', '{!! asset("img/img-fundo.png") !!}');
                $('#image').attr('src', '');
                $('#aviso').addClass('d-none');
                $('#resetEditor').css('display', 'none');
                $('#inputEditor').css('display', 'block');
            }
            catch(err) {
                console.log('err.message djhfdjkhgdfhkb');
            }
            
          })
        }
        reader.readAsDataURL(input.files[0]);
    }
} 
$("#fotoInput").change(function() {
    readURL(this);
});



</script>

@endsection

