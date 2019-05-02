@extends('dashboard.layout')
@section('title') Eventos @endsection
@section('menu') #eventos-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/admin/eventos">Listagem</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        @if (!empty($queries))
            @foreach($columns as $column)
                $("#{{ $column }}").children('[value="{{ $queries[$column] }}"]').attr('selected', true);
            @endforeach
        @endif
    });
</script>
<a href="/admin/eventos/create" class="btn btn-primary shadow mb-3"><i class="fas fa-plus mr-2"></i>Adicionar</a>
<div class="card shadow">
    <div class="card-body">
        <h4><i class="fas fa-filter mr-2"></i>Filtros</h4>        
        <form method="GET" action="/admin/eventos">
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="busca">Digite a empresa, email, nome ou sobrenome</label>
                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Buscar..." value="{{ isset($queries['busca']) ? $queries['busca'] : '' }}">
                </div>
                <div class="form-group col-lg-3">
                    <label for="status">Status</label>
                    <select class="custom-select" id="status" name="status">
                        <option value="%">TODOS</option>
                        <option value="ATIVO">ATIVO</option>
                        <option value="EXCLUIDO">EXCLUIDO</option>
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
            <a href="/admin/eventos" class="btn btn-secondary shadow mr-3"><i class="fas fa-sync-alt mr-2"></i>Limpar filtros</a>
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
                      <th scope="col">Evento</th>
                      <th scope="col">Empresa</th>
                      <th scope="col">Cidade</th>
                      <th scope="col" class="table-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventos as $evento)
                        @if($evento->status == 'EXCLUIDO')
                        <tr class="table-danger">
                            <td>{{ $evento->nome }}</td>
                            <td>{{ $evento->empresa->empresa }}</td>
                            <td>{{ $evento->cidade->nome.'-'.$evento->cidade->uf }}</td>
                            <td>
                                <a href="/admin/eventos/{{ $evento->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @elseif($evento->status == 'PENDENTE')
                        <tr class="table-danger">
                            <td>{{ $evento->nome }}</td>
                            <td>{{ $evento->empresa->empresa }}</td>
                            <td>{{ $evento->cidade->nomee.'-'.$evento->cidade->uf }}</td>
                            <td>
                                <a href="/admin/eventos/{{ $evento->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td>{{ $evento->nome }}</td>
                            <td>{{ $evento->empresa->empresa }}</td>
                            <td>{{ $evento->cidade->nome.'-'.$evento->cidade->uf }}</td>
                            <td>
                                <a href="/admin/eventos/{{ $evento->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger shadow" data-toggle="modal" title="Excluir" data-target="#modalDelete{{ $evento->id }}"> 
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
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
        {{ $eventos->links() }}
        <!-- END PAGINATION -->
    </div>
</div>
@foreach($eventos as $evento)
    @if($evento->status != 'EXCLUIDO')
        <!-- Modal DELETE -->
        <div class="modal fade" id="modalDelete{{$evento->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir Categoria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/admin/eventos/{{ $evento->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <h5>Tem certeza que deseja excluir a Categoria?</h5>
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
