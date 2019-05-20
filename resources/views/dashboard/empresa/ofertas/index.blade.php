@extends('dashboard.empresa.layout')
@section('title') Ofertas @endsection
@section('menu') #ofertas-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/empresa/ofertas">Listagem</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        $('.preco').mask('#.##0,00', {reverse: true, placeholder: "0,00"});
        //Selects
        $("#situacao").children('[value="{{ $situacao }}"]').attr('selected', true);
    });
</script>
<a href="/empresa/ofertas/choose" class="btn btn-primary shadow mb-3"><i class="fas fa-plus mr-2"></i>Adicionar</a>
<div class="card shadow">
    <div class="card-body">
        <h4><i class="fas fa-filter mr-2"></i>Filtros</h4>        
        <form method="GET" action="/empresa/ofertas">
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="busca">Digite o nome do produto</label>
                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Buscar..." value="{{ isset($queries['busca']) ? $queries['busca'] : '' }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="situacao">Situação</label>
                    <select class="custom-select" id="situacao" name="situacao">
                        <option value="ANDAMENTO">EM ANDAMENTO</option>
                        <option value="FINALIZADA">FINALIZADA</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary shadow mr-3"><i class="fas fa-filter mr-2"></i>Filtrar</button>
            <a href="/empresa/ofertas" class="btn btn-secondary shadow mr-3"><i class="fas fa-sync-alt mr-2"></i>Limpar filtros</a>
        </form>
        <hr>
        @if($amount != 0)
        <p class="form-text text-muted">
            Exibindo
            @if(isset($_GET['page']))
                {{ (($_GET['page'] - 1) * 25) +1 }}-{{ ($_GET['page'] > $amount) ? $_GET['page']*25 : $amount}}
            @else
                1-{{ ('25' < $amount) ? '25' : $amount}}
            @endif
            de {{ $amount }} resultados.
        </p>
        <!-- TABLE -->
        <div class="table-responsive table-hover">
            <table class="table mb-0" >
                <thead>
                    <tr>
                        <th scope="col">Oferta</th>
                        <th scope="col">Empresa</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Validade</th>
                        <th scope="col" class="table-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ofertas as $oferta)
                    <tr>
                        <td>{{ $oferta->nome }}</td>
                        <td>{{ $oferta->enome }}</td>
                        <td class="preco">{{ $oferta->preco }}</td>
                        <td>{{ date("d/m/Y", strtotime($oferta->validade)) }}</td>
                        <td>
                            <a href="/empresa/ofertas/{{ $oferta->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger shadow" data-toggle="modal" title="Excluir" data-target="#modalDelete{{ $oferta->id }}"> 
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <br/>
        <div class="alert alert-danger text-center mb-0" role="alert">
            <h5 class="mb-0">Não foram encontrados resultados!</h5>
        </div>
        @endif
        <!-- END TABLE -->
        <br/>
        <!-- PAGINATION -->
        {{ $ofertas->links() }}
        <!-- END PAGINATION -->
    </div>
</div>
@foreach($ofertas as $oferta)
   @if($oferta->status != 'EXCLUIDO')
        <!-- Modal DELETE -->
        <div class="modal fade" id="modalDelete{{$oferta->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir OFERTA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/empresa/ofertas/{{ $oferta->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <h5>Tem certeza que deseja excluir a OFERTA?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-danger shadow"><i class="fas fa-trash-alt mr-2"></i>Excluir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
