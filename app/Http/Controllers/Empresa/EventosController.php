<?php

namespace App\Http\Controllers\Empresa;

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

class EventosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:empresa');
    }


    public function index(Request $request){

        //Filters
        $eventos = new Evento;
        $queries = [];
        //Cidade Específica
        $eventos = $eventos->where('empresa_id', '=', Auth::user()->id)->where('status', '!=', 'EXCLUIDO');
        //Situacao
        $situacao = '';
        $sit = '>';
        //Filtros
        if (request('situacao') == 'FINALIZADA') {
            $situacao = 'FINALIZADA';
            $sit = '<';
        }
        if (request()->has('busca') && request('busca') != null) {
            $eventos = $eventos->whereRaw(" (`nome` like ? ) ", "%".request('busca')."%");
            $queries['busca'] = request('busca');
        }
        //Contagem
        $eventos = $eventos->where('validade', $sit, \DB::raw('NOW()'));
        $amount = $eventos->get()->count();
        $eventos = $eventos->with('empresa');
        $eventos = $eventos->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        
        return view('dashboard.empresa.eventos.index', compact('eventos', 'amount', 'situacao', 'queries'));
    }


    public function create(){
        //Lista de categorias
        $categorias = CategoriasEvento::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.empresa.eventos.create', compact('categorias'));
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
            'empresa_id' => Auth::user()->id,
            'cidade_id' => Auth::user()->cidade_id,
            'descricao' => request('descricao'),
        ]);

        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $dados->categorias()->attach($categoria);
        }

        ////Return
        return redirect('/empresa/eventos')->withMessage("Evento criado com sucesso!");
    }


    public function show($id){
        $evento = Evento::findOrFail($id);
        //Verifica status
        abort_if($evento->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($evento->empresa_id != Auth::user()->id, 403);
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
        //Lista de categorias
        $categorias = CategoriasEvento::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.empresa.eventos.show', compact('evento', 'editar', 'data', 'tempo', 'pertences', 'categorias'));
    }


    public function update(Request $request, $id){
        
        $evento = Evento::findOrFail($id);
        //Verifica status
        abort_if($evento->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($evento->empresa_id != Auth::user()->id, 403);
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
            'descricao' => ['string', 'max:255'],
            'categorias' => ['required'],
        ]);

        if($request->hasFile('foto')) {
            
            //cropS3
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/eventos/', 300, 300);

            //Update
            $evento->foto = $filename;
            $evento->nome = request('nome');
            $evento->validade = $data;
            $evento->descricao = request('descricao');
            $evento->save();

        }else{

            //Update
            $evento->nome = request('nome');
            $evento->validade = $data;
            $evento->descricao = request('descricao');
            $evento->save();

        }

        //Remover Categorias
        $evento->categorias()->detach();
        //Adicionar Categorias
        foreach(request('categorias') as $categoria) {
            $evento->categorias()->attach($categoria);
        }

        //Redirect
        return redirect('/empresa/eventos/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $evento = Evento::findOrFail($id);
        //Verifica status
        abort_if($evento->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($evento->empresa_id != Auth::user()->id, 403);
        
        //Update
        $evento->status = "EXCLUIDO";
        $evento->save();

        //Redirect
        return redirect('/empresa/eventos')->withMessage("Evento excluído com sucesso!");
        
    }
}
