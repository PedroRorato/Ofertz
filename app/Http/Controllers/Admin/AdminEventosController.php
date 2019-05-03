<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Evento;
use App\Cidade;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

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
        $amount = $eventos->get()->count();
        $eventos = $eventos->with('cidade', 'empresa');
        $eventos = $eventos->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        
        return view('dashboard.admin.eventos.index', compact('eventos', 'amount', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.admin.eventos.create', compact('cidades'));
    }


    public function store(Request $request){

        //Validação da data
        $partes = explode("/", request('data'));
        $data = $partes[2] . '-' . $partes[1] . '-' . $partes[0] . 'T' . request('time');
        $date = strtotime($data);
        if (time() > $date) {
            return redirect()->back()->withInput()->with('data', 'Só são permitidas datas futuras');
        }

        //Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'cidade' => ['required', 'integer'],
            'descricao' => ['string', 'max:255'],
        ]);


        //S3
        $s3 = new AuxiliarController;
        $filename = $s3->s3($request->file('foto'), 'ofertz/eventos/');


        //Create
        Evento::create([
            'foto' => $filename,
            'nome' => request('nome'),
            'validade' => $data,
            'empresa_id' => '0',
            'cidade_id' => request('cidade'),
            'descricao' => request('descricao'),
        ]);

        ////Return
        return redirect('/admin/eventos')->withMessage("Evento criado com sucesso!");
    }


    public function show($id){
        $evento = Evento::findOrFail($id);
        //Tratar data
        $partesDT = explode(" ", $evento->validade);
        $partesData = explode("-", $partesDT[0]);
        $tempo = $partesDT[1];
        $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        return view('dashboard.admin.eventos.show', compact('evento', 'cidades', 'data', 'tempo'));
    }


    public function update(Request $request, $id){
        
        $evento = Evento::findOrFail($id);

        //Tratamento da data
        $partes = explode("/", request('data'));
        $data = $partes[2] . '-' . $partes[1] . '-' . $partes[0] . 'T' . request('time');
        $date = strtotime($data);

        //Validation
        request()->validate([
            'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'cidade' => ['required', 'integer'],
            'descricao' => ['string', 'max:255'],
            'status' => ['required', 'alpha', 'min:3', 'max:20'],
        ]);

        if($request->hasFile('foto')) {
            
            //S3
            $s3 = new AuxiliarController;
            $filename = $s3->s3($request->file('foto'), 'ofertz/eventos/');

            //Update
            $evento->foto = $filename;
            $evento->nome = request('nome');
            $evento->validade = $data;
            $evento->cidade_id = request('cidade');
            $evento->descricao = request('descricao');
            $evento->status = request('status');
            $evento->save();

            //Redirect
            return redirect('/admin/eventos/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{

            //Update
            $evento->nome = request('nome');
            $evento->validade = $data;
            $evento->cidade_id = request('cidade');
            $evento->descricao = request('descricao');
            $evento->status = request('status');
            $evento->save();

            //Redirect
            return redirect('/admin/eventos/'.$id)->withMessage("Edição realizada com sucesso!");
        }
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
