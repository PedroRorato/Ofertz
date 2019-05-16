<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Evento;
use App\CategoriasEvento;
use App\Cidade;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;
use DateTime;

class AdminEventosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $eventos = new Evento;
        $queries = [];
        $columns = [
            'status', 'cidade_id',
        ];
        //Situacao
        $situacao = '';
        $sit = '>';
        //Filtros
        if (request('situacao') == 'FINALIZADA') {
            $situacao = 'FINALIZADA';
            $sit = '<';
        }
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $eventos = $eventos->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $eventos = $eventos->whereRaw(" (`nome` like ? ) ", "%".request('busca')."%");
            $queries['busca'] = request('busca');
        }
        //Contagem
        $eventos = $eventos->where('validade', $sit, \DB::raw('NOW()'));
        $amount = $eventos->get()->count();
        $eventos = $eventos->with('cidade', 'empresa');
        $eventos = $eventos->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        
        return view('dashboard.admin.eventos.index', compact('eventos', 'amount', 'situacao', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        $categorias = CategoriasEvento::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.admin.eventos.create', compact('cidades', 'categorias'));
    }


    public function store(Request $request){

        $auxiliar = new AuxiliarController;
        //Validação da data
        $data = $auxiliar->validaDataTempo(request('data'));
        if (!$data) {
            return redirect()->back()->withInput()->with('data', 'Só são permitidas datas futuras');
        }

        //Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'cidade' => ['required', 'integer'],
            'descricao' => ['string', 'max:255'],
            'categorias' => ['required'],
            'points' => ['required', 'string'],
        ]);

        //Crop S3
        $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/eventos/', 300, 300);

        //Create
        $dados = Evento::create([
            'foto' => $filename,
            'nome' => request('nome'),
            'validade' => $data,
            'empresa_id' => '0',
            'cidade_id' => request('cidade'),
            'descricao' => request('descricao'),
        ]);

        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $dados->categorias()->attach($categoria);
        }

        ////Return
        return redirect('/admin/eventos')->withMessage("Evento criado com sucesso!");
    }


    public function show($id){
        $evento = Evento::findOrFail($id);
        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Data Painel
        $editar = $auxiliar->verificaData($evento->validade);
        //Validação da data
        //Tratar data
        $partesDT = explode(" ", $evento->validade);
        $partesData = explode("-", $partesDT[0]);
        $tempo = $partesDT[1];
        $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        //Categorias à qual pertence
        $pertences = $auxiliar->categoriasArray($evento->categorias);
        //Lista de cidades e categorias
        $categorias = CategoriasEvento::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.admin.eventos.show', compact('evento', 'cidades', 'editar', 'data', 'tempo', 'pertences', 'categorias'));
    }


    public function update(Request $request, $id){
        
        $evento = Evento::findOrFail($id);
        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Validação da data
        $data = $auxiliar->validaDataTempo(request('data'));
        if (!$data) {
            return redirect()->back()->withInput()->with('data', 'Só são permitidas datas futuras');
        }

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
            
            //cropS3
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/eventos/', 300, 300);

            //Update
            $evento->foto = $filename;
            $evento->nome = request('nome');
            $evento->validade = $data;
            $evento->cidade_id = request('cidade');
            $evento->descricao = request('descricao');
            $evento->status = request('status');
            $evento->save();

        }else{

            //Update
            $evento->nome = request('nome');
            $evento->validade = $data;
            $evento->cidade_id = request('cidade');
            $evento->descricao = request('descricao');
            $evento->status = request('status');
            $evento->save();

        }

        //Remover Categorias
        $evento->categorias()->detach();
        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $evento->categorias()->attach($categoria);
        }

        //Redirect
        return redirect('/admin/eventos/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $evento = Evento::findOrFail($id);
        
        //Update
        $evento->status = "EXCLUIDO";
        $evento->save();

        //Redirect
        return redirect('/admin/eventos')->withMessage("Evento excluído com sucesso!");
        
    }
}
