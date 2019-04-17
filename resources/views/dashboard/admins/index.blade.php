@extends('dashboard.admins.layout')
@section('title') Layout @endsection
@section('button') #nav-login @endsection
@section('content')
<button type="button" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Adicionar Usuário</button>
<hr>
<h4><i class="fas fa-filter mr-2"></i>Filtros</h4>
<div class="form-group">
    <label for="exampleFormControlInput1">Email, nome ou sobrenome</label>
    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Digite o email, nome ou sobrenome">
</div>
<div class="row">
    <div class="form-group col-3">
        <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
            <option selected>--Cidades--</option>
            <option value="1">Alegrete-RS</option>
            <option value="1">Erechim-RS</option>
            <option value="1">Santa Maria-RS</option>
            <option value="2">Uruguaiana-RS</option>
        </select>
    </div>
    <div class="form-group col-3">
        <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
            <option selected>--Status--</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
        </select>
    </div>
    <div class="form-group col-3">
        <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
            <option selected>--Gêneros--</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
        </select>
    </div>
    <div class="form-group col-3">
        <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
            <option selected>--Faixa Etária--</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
        </select>
    </div>
</div>
<button type="button" class="btn btn-primary mr-3"><i class="fas fa-filter mr-2"></i>Filtrar</button>
<button type="button" class="btn btn-success"><i class="fas fa-file-excel mr-2"></i>Download</button>
<hr>
<p class="form-text text-muted">Exibindo 1-50 de 2638 resultados.</p>
<div class="table-responsive" style="border: 1px solid lightgrey">
    <table class="table " >
        <thead>
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Email</th>
              <th scope="col">Cidade</th>
              <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Mark Otto</td>
                <td>markotto@gmail.com</td>
                <td>Santa Maria-RS</td>
                <td>
                    <button type="button" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Painel</button>
                </td>
            </tr>
            <tr>
                <td>Larry Bird</td>
                <td>larrybird@outlook.com</td>
                <td>Porto Alegre-RS</td>
                <td>
                    <button type="button" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Painel</button>
                </td>
            </tr>
            <tr class="table-secondary">
                <td>Jacob Thornton</td>
                <td>jacobthornton@hotmail.com</td>
                <td>Bagé-RS</td>
                <td>
                    <button type="button" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Painel</button>
                </td>
            </tr>
            <tr>
                <td>Larry Bird</td>
                <td>larrybird@outlook.com</td>
                <td>Porto Alegre-RS</td>
                <td>
                    <button type="button" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Painel</button>
                </td>
            </tr>
            <tr>
                <td>Mark Otto</td>
                <td>markotto@gmail.com</td>
                <td>Santa Maria-RS</td>
                <td>
                    <button type="button" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Painel</button>
                </td>
            </tr>
            <tr>
                <td>Larry Bird</td>
                <td>larrybird@outlook.com</td>
                <td>Porto Alegre-RS</td>
                <td>
                    <button type="button" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Painel</button>
                </td>
            </tr>
            <tr class="table-danger">
                <td>Jacob Thornton</td>
                <td>jacobthornton@hotmail.com</td>
                <td>Bagé-RS</td>
                <td>
                    <button type="button" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Painel</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<br/>
<nav>
	<ul class="pagination">
	    <li class="page-item">
	      <a class="page-link" href="#" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>
	    <li class="page-item"><a class="page-link" href="#">1</a></li>
	    <li class="page-item"><a class="page-link" href="#">2</a></li>
	    <li class="page-item"><a class="page-link" href="#">3</a></li>
	    <li class="page-item">
	      <a class="page-link" href="#" aria-label="Next">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>
	</ul>
</nav>
@endsection