<?php

namespace App\Http\Controllers\Franqueado;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class EmpresasController extends Controller
{

    public function __construct(){
        $this->middleware('auth:franqueado');
    }

    public function index(Request $request){

        //Filters
        $empresas = new Empresa;
        $queries = [];
        //Cidade Específica
        $empresas = $empresas->where('cidade_id', '=', Auth::user()->cidade_id)->where('status', '!=', 'EXCLUIDO');
        //Requests
        if (request()->has('status')) {
            $empresas = $empresas->where('status', 'LIKE', request('status'));
            $queries['status'] = request('status');
        }
        if (request()->has('busca') && request('busca') != null) {
            $empresas = $empresas->whereRaw(" (`nome` like ? or `sobrenome` like ? or `email` like ? or `empresa` like ? ) ",[request('busca')."%", "%".request('busca')."%", request('busca')."%", "%".request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        //Contagem
        $amount = $empresas->get()->count();
        $empresas = $empresas->orderBy('empresa', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );

        //Return
        return view('dashboard.franqueado.empresas.index', compact('empresas', 'amount', 'queries'));
    }

    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.franqueado.empresas.create', compact('cidades'));
    }

    public function store(Request $request){

        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Valida data
        $data = NULL;
        if (request('nascimento')) {
            $data = $auxiliar->validaNascimento(request('nascimento'));
            if (!$data) {
                return redirect()->back()->withInput()->with('data', 'Só são permitidas datas passadas');
            }
        }

        //Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'empresa' => ['required', 'string', 'min:2', 'max:100'],
            'cnpj' => ['string', 'size:18'],
            'descricao' => ['string', 'max:255'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'min:3', 'max:255', 'unique:empresas'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'genero' => ['required', 'alpha', 'max:6'],
            'telefone' => ['required', 'string', 'size:14'],
        ]);

        //cropS3
        $auxiliar = new AuxiliarController;
        $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/empresas/', 300, 300);

        //Create
        Empresa::create([
            'foto' => $filename,
            'empresa' => request('empresa'),
            'cnpj' => request('cnpj'),
            'cidade_id' => Auth::user()->cidade_id,
            'descricao' => request('descricao'),
            'nome' => request('nome'),
            'sobrenome' => request('sobrenome'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'genero' => request('genero'),
            'telefone' => request('telefone'),
            'status' => 'ATIVO',
            'nascimento' => $data,
        ]);

        //Return
        return redirect('/franqueado/empresas')->withMessage("Empresa criada com sucesso!");
    }

    public function show($id){
        $empresa = Empresa::findOrFail($id);
        //Verifica status
        abort_if($empresa->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($empresa->cidade_id != Auth::user()->cidade_id, 403);
        //Tratar data
        $data = NULL;
        if (isset($empresa->nascimento)) {
            $partesData = explode("-", $empresa->nascimento);
            $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        }
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.franqueado.empresas.show', compact('empresa', 'cidades', 'data'));
    }

    public function update(Request $request, $id){

        $empresa = Empresa::findOrFail($id);
        //Verifica status
        abort_if($empresa->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($empresa->cidade_id != Auth::user()->cidade_id, 403);
        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Valida data
        $data = NULL;
        if (request('nascimento')) {
            $data = $auxiliar->validaNascimento(request('nascimento'));
            if (!$data) {
                return redirect()->back()->withInput()->with('data', 'Só são permitidas datas passadas');
            }
        }

        if (request('aceitar')) {
            //Validation
            request()->validate([
                'aceitar' => ['boolean'],
            ]);

            $empresa->status = 'ATIVO';
            $empresa->save();
            //Redirect
            return redirect('/franqueado/empresas/')->withMessage("Empresa aceita com sucesso!");
            
        } elseif (request('ativo')) {
            //Validation
            request()->validate([
                'ativo' => ['boolean'],
            ]);

            $empresa->status = 'ATIVO';
            $empresa->save();
            //Redirect
            return redirect('/franqueado/empresas/')->withMessage("Empresa com status ATIVO!");
            
        } elseif (request('inativo')) {
            //Validation
            request()->validate([
                'inativo' => ['boolean'],
            ]);

            $empresa->status = 'INATIVO';
            $empresa->save();
            //Redirect
            return redirect('/franqueado/empresas/')->withMessage("Empresa com status INATIVO!");
            
        } elseif ($request->has('password')) {
           //Validation
            request()->validate([
                'password' => ['string', 'min:5', 'confirmed'],
            ]);

            //Update
            $empresa->password = Hash::make(request('password'));
            $empresa->save();

            //Redirect
            return redirect('/franqueado/empresas/'.$id)->withMessage("Senha alterada com sucesso!");
        } elseif($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
                'telefone' => ['required', 'string', 'size:14'],
            ]);
            
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/empresas/', 300, 300);

            //Update
            $empresa->foto = $filename;
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = $data;
            $empresa->telefone = request('telefone');
            $empresa->save();

            //Redirect
            return redirect('/franqueado/empresas/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
                'telefone' => ['required', 'string', 'size:14'],
            ]);

            //Update
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = $data;
            $empresa->telefone = request('telefone');
            $empresa->save();

            //Redirect
            return redirect('/franqueado/empresas/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }

    public function destroy($id){

        $empresa = Empresa::findOrFail($id);
        //Verifica status
        abort_if($empresa->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($empresa->cidade_id != Auth::user()->cidade_id, 403);
        
        //Update
        $empresa->status = "EXCLUIDO";
        $empresa->save();

        //Redirect
        return redirect('/franqueado/empresas')->withMessage("Empresa excluída com sucesso!");
    }
}
