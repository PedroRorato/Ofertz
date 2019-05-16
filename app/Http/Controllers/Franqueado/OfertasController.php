<?php

namespace App\Http\Controllers\Franqueado;

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
        $this->middleware('auth:admin');
    }

    public function index(Request $request){

        //Variaveis
        $busca = '';
        $categoria_id = '';
        $cidade_id = '';
        $status = '';
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
            $status = request('status');
            $categoria_id = request('categoria_id');
            $cidade_id = request('cidade_id');
            $ofertas = DB::table('ofertas')
            ->select('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome AS enome', 'cidades.nome AS cnome', 'cidades.uf AS cuf')
            ->join('produtos', 'ofertas.produto_id', '=', 'produtos.id')
            ->join('produtos_categoria_produtos', 'produtos.id', '=', 'produtos_categoria_produtos.produto_id')
            ->join('categorias_produtos', 'produtos_categoria_produtos.categorias_produto_id', '=', 'categorias_produtos.id')
            ->join('cidades', 'produtos.cidade_id', '=', 'cidades.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('ofertas.validade', $sit, \DB::raw('NOW()'))
            ->where('ofertas.status', 'LIKE', $status)
            ->where('categorias_produtos.id', 'LIKE', $categoria_id)
            ->where('produtos.cidade_id', 'LIKE', $cidade_id)
            ->where('produtos.nome', 'LIKE', '%'.$busca.'%')
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome', 'cidades.nome', 'cidades.uf');
            //Appends e Paginate
            $amount = $ofertas->get()->count();
            $ofertas = $ofertas->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount, 'busca' => $busca, 'situacao' => $situacao, 'cidade_id' => $cidade_id, 'categoria_id' => $categoria_id]
            );
        } else{
            $ofertas = DB::table('ofertas')
            ->select('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome AS enome', 'cidades.nome AS cnome', 'cidades.uf AS cuf')
            ->join('produtos', 'ofertas.produto_id', '=', 'produtos.id')
            ->join('cidades', 'produtos.cidade_id', '=', 'cidades.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('ofertas.validade', '>', \DB::raw('NOW()'))
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.nome', 'ofertas.id', 'ofertas.status', 'ofertas.preco', 'ofertas.validade', 'empresas.nome', 'cidades.nome', 'cidades.uf');
            //Appends e Paginate
            $amount = $ofertas->get()->count();
            $ofertas = $ofertas->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount]
            );
        }
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.admin.ofertas.index', compact('ofertas', 'amount', 'situacao', 'status', 'busca', 'cidade_id', 'categoria_id', 'cidades', 'categorias'));
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
            ->select('produtos.id', 'produtos.nome', 'empresas.nome AS enome', 'cidades.nome AS cnome', 'cidades.uf AS cuf')
            ->join('produtos_categoria_produtos', 'produtos.id', '=', 'produtos_categoria_produtos.produto_id')
            ->join('categorias_produtos', 'produtos_categoria_produtos.categorias_produto_id', '=', 'categorias_produtos.id')
            ->join('cidades', 'produtos.cidade_id', '=', 'cidades.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('categorias_produtos.id', 'LIKE', $categoria_id)
            ->where('produtos.status', '=', 'ATIVO')
            ->where('produtos.empresa_id', '=', '0')
            ->where('produtos.cidade_id', 'LIKE', $cidade_id)
            ->where('produtos.nome', 'LIKE', '%'.$busca.'%')
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.id', 'produtos.nome', 'empresas.nome', 'cidades.nome', 'cidades.uf');
            //Appends e Paginate
            $amount = $produtos->get()->count();
            $produtos = $produtos->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount, 'busca' => $busca, 'cidade_id' => $cidade_id, 'categoria_id' => $categoria_id]
            );
        } else{
            $produtos = DB::table('produtos')
            ->select('produtos.id', 'produtos.nome', 'empresas.nome AS enome', 'cidades.nome AS cnome', 'cidades.uf AS cuf')
            ->join('cidades', 'produtos.cidade_id', '=', 'cidades.id')
            ->join('empresas', 'produtos.empresa_id', '=', 'empresas.id')
            ->where('produtos.status', '=', 'ATIVO')
            ->where('produtos.empresa_id', '=', '0')
            ->orderBy('produtos.nome', 'ASC')
            ->groupBy('produtos.id', 'produtos.nome', 'empresas.nome', 'cidades.nome', 'cidades.uf');
            //Appends e Paginate
            $amount = $produtos->get()->count();
            $produtos = $produtos->orderBy('nome', 'asc')->paginate(25)->appends(
                ['amount' => $amount]
            );
        }
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        $categorias = CategoriasProduto::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.admin.ofertas.choose', compact('produtos', 'amount', 'busca', 'cidade_id', 'categoria_id', 'cidades', 'categorias'));
    }

    public function create($id){

        //Dados do Produto
        $produto = Produto::findOrFail($id);
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.admin.ofertas.create', compact('cidades', 'produto'));
    }

    public function store(Request $request){

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
            'cidade' => ['required', 'integer'],
            'produto_id' => ['required', 'integer'],
            'observacao' => ['string', 'max:255'],
        ]);

        //Create
        $dados = Oferta::create([
            'preco' => $preco,
            'validade' => $data,
            'empresa_id' => '0',
            'produto_id' => request('produto_id'),
            'cidade_id' => request('cidade'),
            'observacao' => request('observacao'),
        ]);

        ////Return
        return redirect('/admin/ofertas')->withMessage("Oferta criado com sucesso!");
    }

    public function show($id){

        $oferta = Oferta::findOrFail($id);
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
        //Lista de cidades e categorias
        return view('dashboard.admin.ofertas.show', compact('oferta', 'cidades', 'editar', 'data', 'tempo', 'produto', 'categorias'));
    }

    public function update(Request $request, $id){
        
        $oferta = Oferta::findOrFail($id);

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
        $oferta->status = request('status');        
        $oferta->save();

        //Redirect
        return redirect('/admin/ofertas/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $oferta = Oferta::findOrFail($id);
        
        //Update
        $oferta->status = "EXCLUIDO";
        $oferta->save();

        //Redirect
        return redirect('/admin/ofertas')->withMessage("Oferta excluído com sucesso!");
        
    }
}
