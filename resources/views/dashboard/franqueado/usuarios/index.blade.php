@extends('dashboard.franqueado.layout')
@section('title') Usuarios @endsection
@section('menu') #usuarios-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/franqueado/usuarios">Listagem</a></li>
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
<a href="/franqueado/usuarios/create" class="btn btn-primary shadow mb-3"><i class="fas fa-plus mr-2"></i>Adicionar</a>
<div class="card shadow">
    <div class="card-body">
        <h4><i class="fas fa-filter mr-2"></i>Filtros</h4>        
        <form method="GET" action="/franqueado/usuarios">
            <div class="form-group">
                <label for="busca">Digite a usuario, email, nome ou sobrenome</label>
                <input type="text" class="form-control" id="busca" name="busca" placeholder="Buscar..." value="{{ isset($queries['busca']) ? $queries['busca'] : '' }}">
            </div>
            <button type="submit" class="btn btn-primary shadow mr-3"><i class="fas fa-filter mr-2"></i>Filtrar</button>
            <a href="/franqueado/usuarios" class="btn btn-secondary shadow"><i class="fas fa-sync-alt mr-2"></i>Limpar filtros</a>
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
                      <th scope="col">Nome</th>
                      <th scope="col">Email</th>
                      <th scope="col" class="table-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nome.' '.$usuario->sobrenome }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <a href="/franqueado/usuarios/{{ $usuario->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger shadow" data-toggle="modal" title="Excluir" data-target="#modalDelete{{ $usuario->id }}"> 
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
        {{ $usuarios->links() }}
        <!-- END PAGINATION -->
    </div>
</div>
@foreach($usuarios as $usuario)
    @if($usuario->status != 'EXCLUIDO')
        <!-- Modal DELETE -->
        <div class="modal fade" id="modalDelete{{$usuario->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/franqueado/usuarios/{{ $usuario->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <h5>Tem certeza que deseja excluir a usuario?</h5>
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
