<?php

namespace App\Http\Controllers\Franqueado;

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

class ProdutosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:franqueado');
    }


    public function index(Request $request){

        //Filters
        $produtos = new Produto;
        $queries = [];
        //Cidade Específica
        $produtos = $produtos->where('cidade_id', '=', Auth::user()->cidade_id)->where('status', '!=', 'EXCLUIDO');
        //Queries
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
        
        return view('dashboard.franqueado.produtos.index', compact('produtos', 'amount', 'queries'));
    }


    public function create(){
        //Lista de cidades
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.franqueado.produtos.create', compact('categorias'));
    }


    public function store(Request $request){

        //Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'descricao' => ['string', 'max:255'],
            'categorias' => ['required'],
        ]);

        //cropS3
        $auxiliar = new AuxiliarController;
        $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/produtos/', 300, 300);

        //Create
        $dados = Produto::create([
            'foto' => $filename,
            'nome' => request('nome'),
            'empresa_id' => '0',
            'cidade_id' => Auth::user()->cidade_id,
            'descricao' => request('descricao'),
        ]);

        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $dados->categorias()->attach($categoria);
        }

        ////Return
        return redirect('/franqueado/produtos')->withMessage("Produto criado com sucesso!");
    }


    public function show($id){
        $produto = Produto::findOrFail($id);
        //Verifica status
        abort_if($produto->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($produto->cidade_id != Auth::user()->cidade_id, 403);
        //Categorias à qual pertence
        $pertences = $produto->categorias;
        $array_pertence[] = 0;
        foreach ($pertences as $pertence) {
            $array_pertence[] = $pertence->id;
        }
        $pertences = $array_pertence;
        //Lista de categorias
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.franqueado.produtos.show', compact('produto', 'pertences', 'categorias'));
    }


    public function update(Request $request, $id){
        
        $produto = Produto::findOrFail($id);
        //Verifica status
        abort_if($produto->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($produto->cidade_id != Auth::user()->cidade_id, 403);
        //Validation
        request()->validate([
            'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'descricao' => ['string', 'max:255'],
            'categorias' => ['required'],
        ]);

        if($request->hasFile('foto')) {
            
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/produtos/', 300, 300);

            //Update
            $produto->foto = $filename;
            $produto->nome = request('nome');
            $produto->descricao = request('descricao');
            $produto->save();

        }else{

            //Update
            $produto->nome = request('nome');
            $produto->descricao = request('descricao');
            $produto->save();

        }

        //Remover Categorias
        $produto->categorias()->detach();
        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $produto->categorias()->attach($categoria);
        }

        //Redirect
        return redirect('/franqueado/produtos/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $produto = Produto::findOrFail($id);
        //Verifica status
        abort_if($produto->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($produto->cidade_id != Auth::user()->cidade_id, 403);
        //Update
        $produto->status = "EXCLUIDO";
        $produto->save();

        //Redirect
        return redirect('/franqueado/produtos')->withMessage("Produto excluído com sucesso!");
        
    }
}
