<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Produto;
use App\CategoriasProduto;
use App\Cidade;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminProdutosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $produtos = new Produto;
        $queries = [];
        $columns = [
            'status', 'cidade_id',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $produtos = $produtos->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $produtos = $produtos->whereRaw(" (`nome` like ? ) ", "%".request('busca')."%");
            $queries['busca'] = request('busca');
        }
        //Contagem
        $amount = $produtos->get()->count();
        $produtos = $produtos->with('cidade', 'empresa');
        $produtos = $produtos->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        
        return view('dashboard.admin.produtos.index', compact('produtos', 'amount', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.admin.produtos.create', compact('cidades', 'categorias'));
    }


    public function store(Request $request){

        //Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'cidade' => ['required', 'integer'],
            'descricao' => ['string', 'max:255'],
            'categorias' => ['required'],
        ]);

        //S3
        $s3 = new AuxiliarController;
        $filename = $s3->s3($request->file('foto'), 'ofertz/produtos/');

        //Create
        $dados = Produto::create([
            'foto' => $filename,
            'nome' => request('nome'),
            'empresa_id' => '0',
            'cidade_id' => request('cidade'),
            'descricao' => request('descricao'),
        ]);

        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $dados->categorias()->attach($categoria);
        }

        ////Return
        return redirect('/admin/produtos')->withMessage("Produto criado com sucesso!");
    }


    public function show($id){
        $produto = Produto::findOrFail($id);
        //Categorias à qual pertence
        $pertences = $produto->categorias;
        $array_pertence[] = 0;
        foreach ($pertences as $pertence) {
            $array_pertence[] = $pertence->id;
        }
        $pertences = $array_pertence;
        //Lista de cidades e categorias
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.admin.produtos.show', compact('produto', 'cidades', 'pertences', 'categorias'));
    }


    public function update(Request $request, $id){
        
        $produto = Produto::findOrFail($id);

        //Validation
        request()->validate([
            'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'cidade' => ['required', 'integer'],
            'descricao' => ['string', 'max:255'],
            'status' => ['required', 'alpha', 'min:3', 'max:20'],
            'categorias' => ['required'],
        ]);

        if($request->hasFile('foto')) {
            
            //S3
            $s3 = new AuxiliarController;
            $filename = $s3->s3($request->file('foto'), 'ofertz/produtos/');

            //Update
            $produto->foto = $filename;
            $produto->nome = request('nome');
            $produto->cidade_id = request('cidade');
            $produto->descricao = request('descricao');
            $produto->status = request('status');
            $produto->save();

        }else{

            //Update
            $produto->nome = request('nome');
            $produto->cidade_id = request('cidade');
            $produto->descricao = request('descricao');
            $produto->status = request('status');
            $produto->save();

        }

        //Remover Categorias
        $produto->categorias()->detach();
        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $produto->categorias()->attach($categoria);
        }

        //Redirect
        return redirect('/admin/produtos/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $produto = Produto::findOrFail($id);
        
        //Update
        $produto->status = "EXCLUIDO";
        $produto->save();

        //Redirect
        return redirect('/admin/produtos')->withMessage("Produto excluído com sucesso!");
        
    }
}
