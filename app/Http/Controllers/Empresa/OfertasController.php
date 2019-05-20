<?php

namespace App\Http\Controllers\Empresa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Oferta;
use App\Produto;
use App\CategoriasProduto;
use App\Cidade;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use DateTime;

class OfertasController extends Controller
{

    public function __construct(){
        $this->middleware('auth:empresa');
    }

    public function index(Request $request){

        //Variaveis
        $busca = '';
        $categoria_id = '';
        $situacao = '';
        $sit = '>';
        //Verificar filtro
        if (request()->has('busca')) {
            if (request()->has('busca') && request('busca') != null) {
                $busca = request('busca');
            }
            if (request('situacao') == 'FINALIZADA') {
                $situacao = 'FINALIZADA';
                $sit = '<';
            }
            $categoria_id = request('categoria_id');
            $ofertas = DB::table('ofertas')
            ->select('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome AS enome')
            ->join('produtos', 'ofertas.produto_id', '=', 'produtos.id')
            ->join('produtos_categoria_produtos', 'produtos.id', '=', 'produtos_categoria_produtos.produto_id')
            ->join('categorias_produtos', 'produtos_categoria_produtos.categorias_produto_id', '=', 'categorias_produtos.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('ofertas.empresa_id', '=', Auth::user()->id)
            ->where('ofertas.status', '=', 'ATIVO')
            ->where('ofertas.validade', $sit, \DB::raw('NOW()'))
            ->where('categorias_produtos.id', 'LIKE', $categoria_id)
            ->where('produtos.nome', 'LIKE', '%'.$busca.'%')
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome');
            //Appends e Paginate
            $amount = $ofertas->get()->count();
            $ofertas = $ofertas->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount, 'busca' => $busca, 'situacao' => $situacao, 'categoria_id' => $categoria_id]
            );
        } else{
            $ofertas = DB::table('ofertas')
            ->select('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome AS enome')
            ->join('produtos', 'ofertas.produto_id', '=', 'produtos.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('ofertas.empresa_id', '=', Auth::user()->id)
            ->where('ofertas.status', '=', 'ATIVO')
            ->where('ofertas.validade', '>', \DB::raw('NOW()'))
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome');
            //Appends e Paginate
            $amount = $ofertas->get()->count();
            $ofertas = $ofertas->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount]
            );
        }
        //Lista de categorias
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.empresa.ofertas.index', compact('ofertas', 'amount', 'situacao', 'busca', 'categoria_id', 'categorias'));
    }

    public function choose(Request $request){

        //Variaveis
        $busca = '';
        $categoria_id = '';
        $cidade_id = '';
        //Verificar filtro
        if (request()->has('busca')) {
            if (request()->has('busca') && request('busca') != null) {
                $busca = request('busca');
            }
            $categoria_id = request('categoria_id');
            $cidade_id = request('cidade_id');
            $produtos = DB::table('produtos')
            ->select('produtos.id', 'produtos.nome', 'empresas.empresa AS enome', 'cidades.nome AS cnome', 'cidades.uf AS cuf')
            ->join('produtos_categoria_produtos', 'produtos.id', '=', 'produtos_categoria_produtos.produto_id')
            ->join('categorias_produtos', 'produtos_categoria_produtos.categorias_produto_id', '=', 'categorias_produtos.id')
            ->join('cidades', 'produtos.cidade_id', '=', 'cidades.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('categorias_produtos.id', 'LIKE', $categoria_id)
            ->where('produtos.status', '=', 'ATIVO')
            ->where('produtos.empresa_id', '=', Auth::user()->id)
            ->where('produtos.nome', 'LIKE', '%'.$busca.'%')
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.id', 'produtos.nome', 'empresas.empresa', 'cidades.nome', 'cidades.uf');
            //Appends e Paginate
            $amount = $produtos->get()->count();
            $produtos = $produtos->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount, 'busca' => $busca, 'categoria_id' => $categoria_id]
            );
        } else{
            $produtos = DB::table('produtos')
            ->select('produtos.id', 'produtos.nome', 'empresas.empresa AS enome', 'cidades.nome AS cnome', 'cidades.uf AS cuf')
            ->join('cidades', 'produtos.cidade_id', '=', 'cidades.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('produtos.status', '=', 'ATIVO')
            ->where('produtos.empresa_id', '=', Auth::user()->id)
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.id', 'produtos.nome', 'empresas.empresa', 'cidades.nome', 'cidades.uf');
            //Appends e Paginate
            $amount = $produtos->get()->count();
            $produtos = $produtos->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount]
            );
        }
        //Lista de categorias
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.empresa.ofertas.choose', compact('produtos', 'amount', 'busca', 'categoria_id', 'categorias'));
    }

    public function create($id){

        //Dados do Produto
        $produto = Produto::findOrFail($id);
        //Verifica status
        abort_if($produto->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($produto->empresa_id != Auth::user()->id, 403);
        //Return
        return view('dashboard.empresa.ofertas.create', compact('produto'));
    }

    public function store(Request $request){

        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Validação da data
        $data = $auxiliar->validaDataTempo(request('data'));
        if (!$data) {
            return redirect()->back()->withInput()->with('data', 'Só são permitidas datas futuras');
        }

        //Validação do preço
        $preco = str_replace(',', '.', str_replace('.', '', request('preco')));

        //Validation
        request()->validate([
            'preco' => ['required', 'string', 'min:2', 'max:100'],
            'produto_id' => ['required', 'integer'],
            'observacao' => ['string', 'max:255'],
        ]);

        //Dados do Produto
        $produto = Produto::findOrFail(request('produto_id'));
        //Verifica status
        abort_if($produto->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($produto->empresa_id != Auth::user()->id, 403);

        //Create
        $dados = Oferta::create([
            'preco' => $preco,
            'validade' => $data,
            'empresa_id' => Auth::user()->id,
            'produto_id' => request('produto_id'),
            'cidade_id' => Auth::user()->cidade_id,
            'observacao' => request('observacao'),
        ]);

        ////Return
        return redirect('/empresa/ofertas')->withMessage("Oferta criado com sucesso!");
    }

    public function show($id){

        $oferta = Oferta::findOrFail($id);
        //Verifica status
        abort_if($oferta->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($oferta->empresa_id != Auth::user()->id, 403);
        $produto = $oferta->produto;
        //Data Painel
        $date_now = new DateTime();
        $date_validade = new DateTime($oferta->validade);
        $editar = true;
        if($date_now > $date_validade){
            $editar = false;
        }
        //Tratar data
        $partesDT = explode(" ", $oferta->validade);
        $partesData = explode("-", $partesDT[0]);
        $tempo = $partesDT[1];
        $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        //Lista de categorias
        return view('dashboard.empresa.ofertas.show', compact('oferta', 'editar', 'data', 'tempo', 'produto', 'categorias'));
    }

    public function update(Request $request, $id){
        
        $oferta = Oferta::findOrFail($id);
        //Verifica status
        abort_if($oferta->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($oferta->empresa_id != Auth::user()->id, 403);
        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Validação da data
        $data = $auxiliar->validaDataTempo(request('data'));
        if (!$data) {
            return redirect()->back()->withInput()->with('data', 'Só são permitidas datas futuras');
        }

        //Validação do preço
        $preco = str_replace(',', '.', str_replace('.', '', request('preco')));

        //Validation
        request()->validate([
            'preco' => ['required', 'string', 'min:2', 'max:100'],
            'observacao' => ['string', 'max:255'],
        ]);

        //Update
        $oferta->preco = $preco;
        $oferta->validade = $data;
        $oferta->observacao = request('observacao');
        $oferta->save();

        //Redirect
        return redirect('/empresa/ofertas/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $oferta = Oferta::findOrFail($id);
        //Verifica status
        abort_if($oferta->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($oferta->empresa_id != Auth::user()->id, 403);
        
        //Update
        $oferta->status = "EXCLUIDO";
        $oferta->save();

        //Redirect
        return redirect('/empresa/ofertas')->withMessage("Oferta excluído com sucesso!");
        
    }
}
