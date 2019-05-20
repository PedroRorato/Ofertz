<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminEmpresasController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $empresas = new Empresa;
        $queries = [];
        $columns = [
            'status', 'cidade_id',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $empresas = $empresas->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $empresas = $empresas->whereRaw(" (`nome` like ? or `sobrenome` like ? or `email` like ? or `empresa` like ? ) ",[request('busca')."%", "%".request('busca')."%", request('busca')."%", "%".request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        //Contagem
        $amount = $empresas->get()->count();
        $empresas = $empresas->with('cidade');
        $empresas = $empresas->orderBy('empresa', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );

        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();

        //Return
        return view('dashboard.admin.empresas.index', compact('empresas', 'amount', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.admin.empresas.create', compact('cidades'));
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
            'cidade' => ['integer', 'max:255'],
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
            'cidade_id' => request('cidade'),
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
        return redirect('/admin/empresas')->withMessage("Empresa criada com sucesso!");
    }


    public function show($id){
        $empresa = Empresa::findOrFail($id);
        //Tratar data
        $data = NULL;
        if (isset($empresa->nascimento)) {
            $partesData = explode("-", $empresa->nascimento);
            $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        }
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.admin.empresas.show', compact('empresa', 'cidades', 'data'));
    }


    public function update(Request $request, $id){

        $empresa = Empresa::findOrFail($id);
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

        //UPDATES
        if (request('aceitar')) {
            //Validation
            request()->validate([
                'aceitar' => ['boolean'],
            ]);

            $empresa->status = 'ATIVO';
            $empresa->save();
            //Redirect
            return redirect('/admin/empresas/')->withMessage("Empresa aceita com sucesso!");
            
        } elseif (request('ativo')) {
            //Validation
            request()->validate([
                'ativo' => ['boolean'],
            ]);

            $empresa->status = 'ATIVO';
            $empresa->save();
            //Redirect
            return redirect('/admin/empresas/')->withMessage("Empresa com status ATIVO!");
            
        } elseif (request('inativo')) {
            //Validation
            request()->validate([
                'inativo' => ['boolean'],
            ]);

            $empresa->status = 'INATIVO';
            $empresa->save();
            //Redirect
            return redirect('/admin/empresas/')->withMessage("Empresa com status INATIVO!");
            
        } elseif ($request->has('password')) {
           //Validation
            request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $empresa->password = Hash::make(request('password'));
            $empresa->save();

            //Redirect
            return redirect('/admin/empresas/'.$id)->withMessage("Senha alterada com sucesso!");
        } elseif($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'cidade' => ['integer', 'max:255'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255', 'unique:empresas,email,'.$id],
                'genero' => ['required', 'alpha', 'max:6'],
                'telefone' => ['required', 'string', 'size:14'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);
            
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/empresas/', 300, 300);

            //Update
            $empresa->foto = $filename;
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->cidade_id = request('cidade');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = $data;
            $empresa->telefone = request('telefone');
            $empresa->status = request('status');
            $empresa->save();

            //Redirect
            return redirect('/admin/empresas/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'cidade' => ['integer', 'max:255'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255', 'unique:empresas,email,'.$id],
                'genero' => ['required', 'alpha', 'max:6'],
                'telefone' => ['required', 'string', 'size:14'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //Update
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->cidade_id = request('cidade');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = $data;
            $empresa->telefone = request('telefone');
            $empresa->status = request('status');
            $empresa->save();

            //Redirect
            return redirect('/admin/empresas/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $empresa = Empresa::findOrFail($id);
        
        //Update
        $empresa->status = "EXCLUIDO";
        $empresa->save();

        //Redirect
        return redirect('/admin/empresas')->withMessage("Empresa excluída com sucesso!");
    }
}
