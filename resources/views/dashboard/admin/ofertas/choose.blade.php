@extends('dashboard.admin.layout')
@section('title') Ofertas @endsection
@section('menu') #ofertas-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/ofertas">Listagem</a></li>
<li class="breadcrumb-item"><a href="/admin/ofertas/choose">Escolher Produto</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        $("#cidade_id").children('[value="{{ isset($cidade_id) ? $cidade_id : '' }}"]').attr('selected', true);
    });
</script>
<a href="/admin/ofertas" class="btn btn-secondary shadow mb-3"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
<div class="card shadow">
    <div class="card-body">
        <h4><i class="fas fa-filter mr-2"></i>Filtros</h4>        
        <form method="GET" action="/admin/ofertas/choose">
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="busca">Digite o nome do produto</label>
                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Buscar..." value="{{ isset($busca) ? $busca : '' }}">
                </div>
                <div class="form-group col-lg-3">
                    <label for="categoria_id">Categoria</label>
                    <select class="custom-select" id="categoria_id" name="categoria_id" required>
                        <option value="%">TODOS</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-3">
                    <label for="cidade_id">Cidade</label>
                    <select class="custom-select" id="cidade_id" name="cidade_id" required>
                        <option value="%">TODOS</option>
                        @foreach($cidades as $cidade)
                            <option value="{{ $cidade->id }}">{{ $cidade->nome.'-'.$cidade->uf }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary shadow mr-3"><i class="fas fa-filter mr-2"></i>Filtrar</button>
            <a href="/admin/ofertas/choose" class="btn btn-secondary shadow mr-3"><i class="fas fa-sync-alt mr-2"></i>Limpar filtros</a>
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
                      <th scope="col">Produto</th>
                      <th scope="col">Empresa</th>
                      <th scope="col">Cidade</th>
                      <th scope="col" class="table-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produtos as $produto)
                        <tr>
                            <td>{{ $produto->nome }}</td>
                            <td>{{ $produto->enome }}</td>
                            <td>{{ $produto->cnome.'-'.$produto->cuf }}</td>
                            <td>
                                <a href="/admin/ofertas/produto/{{ $produto->id }}/create" class="btn btn-primary shadow" data-toggle="tooltip" title="Criar nova oferta">
                                    <i class="fas fa-tag mr-2"></i>Criar oferta
                                </a>
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
        {{ $produtos->links() }}
        <!-- END PAGINATION -->
    </div>
</div>
@endsection
