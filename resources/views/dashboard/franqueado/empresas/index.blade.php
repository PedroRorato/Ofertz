@extends('dashboard.franqueado.layout')
@section('title') Empresas @endsection
@section('menu') #empresas-menu @endsection
@section('breadcrumbs') 
<li class="breadcrumb-item"><a href="/franqueado/empresas">Listagem</a></li>
@endsection
@section('content')
<script type="text/javascript">
    $(window).on('load', function() {
        @if (!empty($queries))
            $("#status").children('[value="{!! $queries['status'] !!}"]').attr('selected', true);
        @endif
    });
</script>
<a href="/franqueado/empresas/create" class="btn btn-primary shadow mb-3"><i class="fas fa-plus mr-2"></i>Adicionar</a>
<div class="card shadow">
    <div class="card-body">
        <h4><i class="fas fa-filter mr-2"></i>Filtros</h4>        
        <form method="GET" action="/franqueado/empresas">
            <div class="row">
                <div class="form-group col-lg-9">
                    <label for="busca">Digite a empresa, email, nome ou sobrenome</label>
                    <input type="text" class="form-control" id="busca" name="busca" placeholder="Buscar..." value="{{ isset($queries['busca']) ? $queries['busca'] : '' }}">
                </div>
                <div class="form-group col-lg-3">
                    <label for="status">Status</label>
                    <select class="custom-select" id="status" name="status" autofocus>
                        <option value="%">TODOS</option>
                        <option value="ATIVO">ATIVO</option>
                        <option value="INATIVO">INATIVO</option>
                        <option value="PENDENTE" >PENDENTE</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary shadow mr-3"><i class="fas fa-filter mr-2"></i>Filtrar</button>
            <a href="/franqueado/empresas" class="btn btn-secondary shadow"><i class="fas fa-sync-alt mr-2"></i>Limpar filtros</a>
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
                      <th scope="col">Empresa</th>
                      <th scope="col">Responsável</th>
                      <th scope="col">Email</th>
                      <th scope="col">Telefone</th>
                      <th scope="col">Status</th>
                      <th scope="col" class="table-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
                        @if($empresa->status == 'INATIVO')
                        <tr class="table-secondary">
                            <td>{{ $empresa->empresa }}</td>
                            <td>{{ $empresa->nome.' '.$empresa->sobrenome }}</td>
                            <td>{{ $empresa->email }}</td>
                            <td>{{ $empresa->telefone }}</td>
                            <td>{{ $empresa->status }}</td>
                            <td>
                                <a href="/franqueado/empresas/{{ $empresa->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-success shadow" data-toggle="modal" title="Tornar ATIVO" data-target="#modalAtivar{{ $empresa->id }}"> 
                                    <i class="fas fa-thumbs-up"></i>
                                </button>
                                <button type="button" class="btn btn-danger shadow" data-toggle="modal" title="Excluir" data-target="#modalDelete{{ $empresa->id }}"> 
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @elseif($empresa->status == 'PENDENTE')
                        <tr class="bg-warning">
                            <td>{{ $empresa->empresa }}</td>
                            <td>{{ $empresa->nome.' '.$empresa->sobrenome }}</td>
                            <td>{{ $empresa->email }}</td>
                            <td>{{ $empresa->telefone }}</td>
                             <td>{{ $empresa->status }}</td>
                            <td>
                                <a href="/franqueado/empresas/{{ $empresa->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-success shadow" data-toggle="modal" title="Aceitar" data-target="#modalAceitar{{ $empresa->id }}"> 
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-danger shadow" data-toggle="modal" title="Excluir" data-target="#modalDelete{{ $empresa->id }}"> 
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td>{{ $empresa->empresa }}</td>
                            <td>{{ $empresa->nome.' '.$empresa->sobrenome }}</td>
                            <td>{{ $empresa->email }}</td>
                            <td>{{ $empresa->telefone }}</td>
                            <td>{{ $empresa->status }}</td>
                            <td>
                                <a href="/franqueado/empresas/{{ $empresa->id }}" class="btn btn-primary shadow" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-dark shadow" data-toggle="modal" title="Tornar INATIVO" data-target="#modalInativar{{ $empresa->id }}"> 
                                    <i class="fas fa-thumbs-down"></i>
                                </button>
                                <button type="button" class="btn btn-danger shadow" data-toggle="modal" title="Excluir" data-target="#modalDelete{{ $empresa->id }}"> 
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
        {{ $empresas->links() }}
        <!-- END PAGINATION -->
    </div>
</div>
@foreach($empresas as $empresa)
    @if($empresa->status != 'EXCLUIDO')
        <div class="modal fade" id="modalDelete{{$empresa->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir Empresa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/franqueado/empresas/{{ $empresa->id }}">
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
    @endif
    @if($empresa->status == 'INATIVO')
        <div class="modal fade" id="modalAtivar{{$empresa->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tornar status ATIVO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/franqueado/empresas/{{ $empresa->id }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="ativo" value="1">
                        <div class="modal-body">
                            <h5>Ao tornar o status ATIVO, a empresa poderá voltar a postar EVENTOS e OFERTAS. Tem certeza que deseja tornar o status da Empresa ATIVO?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success shadow"><i class="fas fa-thumbs-up mr-2"></i>Tornar status ATIVO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @if($empresa->status == 'ATIVO')
        <div class="modal fade" id="modalInativar{{$empresa->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tornar status INATIVO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/franqueado/empresas/{{ $empresa->id }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="inativo" value="1">
                        <div class="modal-body">
                            <h5>Ao tornar o status INATIVO, a empresa NÃO poderá postar EVENTOS e OFERTAS. Tem certeza que deseja tornar o status da Empresa INATIVO?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-dark shadow"><i class="fas fa-thumbs-down mr-2"></i>Tornar status INATIVO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @if($empresa->status == 'PENDENTE')
        <div class="modal fade" id="modalAceitar{{$empresa->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Aceitar Empresa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/franqueado/empresas/{{ $empresa->id }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="aceitar" value="1">
                        <div class="modal-body">
                            <h5>Tem certeza que deseja aceitar a Empresa?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success shadow"><i class="fas fa-check mr-2"></i>Aceitar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
